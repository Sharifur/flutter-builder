<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZipArchive;

class SchemaController extends Controller
{
    use AuthorizesRequests;
    public function show(Project $project)
    {
        // For demo purposes, remove authorization check
        // In production, you might want to add authorization back
        // $this->authorize('view', $project);

        $schema = $project->toSchemaArray();

        return response()->json([
            'success' => true,
            'data' => $schema,
        ]);
    }

    public function preview(Project $project)
    {
        $this->authorize('view', $project);

        // For now, return the Flutter web URL (placeholder)
        // In production, this would build and serve the Flutter web app
        $previewUrl = config('app.url') . "/flutter-preview/{$project->id}";

        return response()->json([
            'success' => true,
            'data' => [
                'preview_url' => $previewUrl,
                'schema' => $project->toSchemaArray(),
            ],
        ]);
    }

    public function export(Project $project)
    {
        $this->authorize('view', $project);

        try {
            $zipPath = $this->generateFlutterProject($project);

            return response()->download($zipPath, "{$project->name}.zip")->deleteFileAfterSend();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Flutter project: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function generateFlutterProject(Project $project): string
    {
        $tempDir = storage_path('app/temp');
        $projectDir = $tempDir . '/' . uniqid('flutter_project_');
        $zipPath = $tempDir . '/' . uniqid('flutter_project_') . '.zip';

        // Create temp directory
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Create project directory structure
        mkdir($projectDir, 0755, true);
        mkdir($projectDir . '/lib', 0755, true);
        mkdir($projectDir . '/lib/pages', 0755, true);
        mkdir($projectDir . '/lib/widgets', 0755, true);

        // Generate pubspec.yaml
        $pubspecContent = $this->generatePubspecYaml($project);
        file_put_contents($projectDir . '/pubspec.yaml', $pubspecContent);

        // Generate main.dart
        $mainContent = $this->generateMainDart($project);
        file_put_contents($projectDir . '/lib/main.dart', $mainContent);

        // Generate JSON renderer
        $rendererContent = $this->generateJsonRenderer();
        file_put_contents($projectDir . '/lib/json_renderer.dart', $rendererContent);

        // Create assets directory
        mkdir($projectDir . '/assets', 0755, true);

        // Generate schema.json
        $schemaContent = json_encode($project->toSchemaArray(), JSON_PRETTY_PRINT);
        file_put_contents($projectDir . '/assets/schema.json', $schemaContent);

        // Create zip file
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }

        $this->addDirectoryToZip($zip, $projectDir, '');
        $zip->close();

        // Clean up project directory
        $this->deleteDirectory($projectDir);

        return $zipPath;
    }

    private function generatePubspecYaml(Project $project): string
    {
        $projectName = strtolower(str_replace([' ', '-'], '_', $project->name));

        return "name: {$projectName}
description: Generated Flutter app from FlutterBuilder

version: 1.0.0+1

environment:
  sdk: ^3.0.0

dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0

dev_dependencies:
  flutter_test:
    sdk: flutter

flutter:
  uses-material-design: true
  assets:
    - assets/
";
    }

    private function generateMainDart(Project $project): string
    {
        return "import 'package:flutter/material.dart';
import 'json_renderer.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: '{$project->name}',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: JsonRenderer(),
    );
  }
}
";
    }

    private function generateJsonRenderer(): string
    {
        return "import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:flutter/services.dart';

class JsonRenderer extends StatefulWidget {
  @override
  _JsonRendererState createState() => _JsonRendererState();
}

class _JsonRendererState extends State<JsonRenderer> {
  Map<String, dynamic>? schema;

  @override
  void initState() {
    super.initState();
    loadSchema();
  }

  Future<void> loadSchema() async {
    final String response = await rootBundle.loadString('assets/schema.json');
    final data = json.decode(response);
    setState(() {
      schema = data;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (schema == null) {
      return Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    return _buildPage(context, schema!['pages'][0]);
  }

  Widget _buildPage(BuildContext context, Map<String, dynamic> page) {
    return Scaffold(
      appBar: AppBar(title: Text(page['name'] ?? 'Untitled')),
      body: ListView(
        children: (page['widgets'] as List<dynamic>)
            .map<Widget>((w) => _buildWidget(context, w))
            .toList(),
      ),
    );
  }

  Widget _buildWidget(BuildContext context, Map<String, dynamic> widget) {
    switch (widget['type']) {
      case 'Text':
        return Padding(
          padding: EdgeInsets.all(8.0),
          child: Text(
            widget['value'] ?? '',
            style: TextStyle(
              color: _parseColor(widget['color']),
              fontSize: (widget['fontSize'] ?? 16).toDouble(),
              fontWeight: widget['fontWeight'] == 'bold'
                  ? FontWeight.bold
                  : FontWeight.normal,
            ),
          ),
        );
      case 'Button':
        return Padding(
          padding: EdgeInsets.all(8.0),
          child: ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: _parseColor(widget['color']),
              foregroundColor: _parseColor(widget['textColor']),
            ),
            onPressed: () {
              if (widget['action'] != null && widget['action'].startsWith('goTo:')) {
                String target = widget['action'].split(':')[1];
                var targetPage = schema!['pages'].firstWhere(
                  (p) => p['id'] == target,
                  orElse: () => null,
                );
                if (targetPage != null) {
                  Navigator.of(context).push(MaterialPageRoute(
                    builder: (_) => _buildPage(context, targetPage),
                  ));
                }
              }
            },
            child: Text(widget['label'] ?? 'Button'),
          ),
        );
      case 'Image':
        return Padding(
          padding: EdgeInsets.all(8.0),
          child: Image.network(
            widget['url'] ?? '',
            width: (widget['width'] ?? 300).toDouble(),
            height: (widget['height'] ?? 200).toDouble(),
            errorBuilder: (context, error, stackTrace) {
              return Container(
                width: (widget['width'] ?? 300).toDouble(),
                height: (widget['height'] ?? 200).toDouble(),
                color: Colors.grey[300],
                child: Icon(Icons.error),
              );
            },
          ),
        );
      case 'Input':
        return Padding(
          padding: EdgeInsets.all(8.0),
          child: TextField(
            decoration: InputDecoration(
              hintText: widget['placeholder'] ?? '',
              labelText: widget['label'] ?? '',
            ),
          ),
        );
      case 'Container':
        return Container(
          padding: EdgeInsets.all((widget['padding'] ?? 16).toDouble()),
          color: _parseColor(widget['backgroundColor']),
          child: widget['direction'] == 'row'
              ? Row(
                  children: (widget['children'] as List<dynamic>? ?? [])
                      .map((child) => _buildWidget(context, child))
                      .toList(),
                )
              : Column(
                  children: (widget['children'] as List<dynamic>? ?? [])
                      .map((child) => _buildWidget(context, child))
                      .toList(),
                ),
        );
      default:
        return SizedBox.shrink();
    }
  }

  Color _parseColor(dynamic color) {
    if (color == null) return Colors.black;
    if (color is String && color.startsWith('#')) {
      return Color(int.parse(color.substring(1), radix: 16) + 0xFF000000);
    }
    return Colors.black;
  }
}
";
    }

    private function addDirectoryToZip(ZipArchive $zip, string $source, string $prefix): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $file = $file->getRealPath();

            if (is_dir($file)) {
                $relativePath = $prefix . substr($file, strlen($source) + 1) . '/';
                $zip->addEmptyDir($relativePath);
            } elseif (is_file($file)) {
                $relativePath = $prefix . substr($file, strlen($source) + 1);
                $zip->addFile($file, $relativePath);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!file_exists($dir)) return;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            if ($fileinfo->isDir()) {
                rmdir($fileinfo->getRealPath());
            } else {
                unlink($fileinfo->getRealPath());
            }
        }

        rmdir($dir);
    }
}