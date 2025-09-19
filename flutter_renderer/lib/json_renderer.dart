import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'config.dart';

class JsonRenderer extends StatefulWidget {
  final String projectId;

  const JsonRenderer({Key? key, required this.projectId}) : super(key: key);

  @override
  _JsonRendererState createState() => _JsonRendererState();
}

class _JsonRendererState extends State<JsonRenderer> {
  Map<String, dynamic>? schema;
  bool isLoading = true;
  String? error;
  int currentPageIndex = 0;

  @override
  void initState() {
    super.initState();
    loadSchema();
  }

  Future<void> loadSchema() async {
    try {
      setState(() {
        isLoading = true;
        error = null;
      });

      final response = await http.get(
        Uri.parse(Config.getSchemaUrl(widget.projectId)),
        headers: {'Accept': 'application/json'},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true) {
          setState(() {
            schema = data['data'];
            isLoading = false;
          });
        } else {
          throw Exception(data['message'] ?? 'Failed to load project');
        }
      } else {
        throw Exception('HTTP ${response.statusCode}: ${response.reasonPhrase}');
      }
    } catch (e) {
      setState(() {
        error = e.toString();
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Scaffold(
        appBar: AppBar(title: Text('Loading Project...')),
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (error != null) {
      return Scaffold(
        appBar: AppBar(title: Text('Error')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error, size: 64, color: Colors.red),
              SizedBox(height: 16),
              Text('Failed to load project:', style: TextStyle(fontWeight: FontWeight.bold)),
              SizedBox(height: 8),
              Text(error!, textAlign: TextAlign.center),
              SizedBox(height: 16),
              ElevatedButton(
                onPressed: loadSchema,
                child: Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    if (schema == null || schema!['pages'] == null || schema!['pages'].isEmpty) {
      return Scaffold(
        appBar: AppBar(title: Text('No Content')),
        body: Center(child: Text('No pages found in this project')),
      );
    }

    final pages = schema!['pages'] as List;
    final currentPage = pages[currentPageIndex];

    return Scaffold(
      appBar: AppBar(
        title: Text(schema!['name'] ?? 'Project'),
        actions: [
          if (pages.length > 1)
            PopupMenuButton<int>(
              onSelected: (index) {
                setState(() {
                  currentPageIndex = index;
                });
              },
              itemBuilder: (context) => pages
                  .asMap()
                  .entries
                  .map((entry) => PopupMenuItem(
                        value: entry.key,
                        child: Text(entry.value['name'] ?? 'Page ${entry.key + 1}'),
                      ))
                  .toList(),
              child: Icon(Icons.pages),
            ),
        ],
      ),
      body: _buildPage(context, currentPage),
    );
  }

  Widget _buildPage(BuildContext context, Map<String, dynamic> page) {
    final widgets = page['widgets'] as List<dynamic>? ?? [];

    return ListView(
      padding: EdgeInsets.all(16),
      children: widgets.map<Widget>((w) => _buildWidget(context, w)).toList(),
    );
  }

  Widget _buildWidget(BuildContext context, Map<String, dynamic> widget) {
    final type = widget['type'] as String?;

    switch (type) {
      case 'Text':
        return _buildTextWidget(widget);
      case 'Button':
        return _buildButtonWidget(widget);
      case 'Image':
        return _buildImageWidget(widget);
      case 'Input':
        return _buildInputWidget(widget);
      case 'Container':
        return _buildContainerWidget(widget);
      default:
        return Container(
          margin: EdgeInsets.symmetric(vertical: 8),
          padding: EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.red[50],
            border: Border.all(color: Colors.red),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Text('Unknown widget type: $type'),
        );
    }
  }

  Widget _buildTextWidget(Map<String, dynamic> widget) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 4),
      child: Text(
        widget['value'] ?? 'Sample text',
        style: TextStyle(
          color: _parseColor(widget['color']),
          fontSize: (widget['fontSize'] ?? 16).toDouble(),
          fontWeight: widget['fontWeight'] == 'bold' ? FontWeight.bold : FontWeight.normal,
        ),
      ),
    );
  }

  Widget _buildButtonWidget(Map<String, dynamic> widget) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 8),
      child: ElevatedButton(
        style: ElevatedButton.styleFrom(
          backgroundColor: _parseColor(widget['color']),
          foregroundColor: _parseColor(widget['textColor']),
        ),
        onPressed: () {
          final action = widget['action'] as String?;
          if (action != null && action.startsWith('goTo:')) {
            final targetPageId = action.substring(5);
            final pages = schema!['pages'] as List;
            final targetPageIndex = pages.indexWhere((p) => p['id'] == targetPageId);

            if (targetPageIndex != -1) {
              setState(() {
                currentPageIndex = targetPageIndex;
              });
            } else {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text('Page not found: $targetPageId')),
              );
            }
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Button pressed: ${widget['label'] ?? 'Button'}')),
            );
          }
        },
        child: Text(widget['label'] ?? 'Button'),
      ),
    );
  }

  Widget _buildImageWidget(Map<String, dynamic> widget) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 8),
      child: Image.network(
        widget['url'] ?? 'https://via.placeholder.com/300x200',
        width: (widget['width'] ?? 300).toDouble(),
        height: (widget['height'] ?? 200).toDouble(),
        fit: BoxFit.cover,
        errorBuilder: (context, error, stackTrace) {
          return Container(
            width: (widget['width'] ?? 300).toDouble(),
            height: (widget['height'] ?? 200).toDouble(),
            decoration: BoxDecoration(
              color: Colors.grey[300],
              border: Border.all(color: Colors.grey),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.broken_image, size: 32, color: Colors.grey[600]),
                SizedBox(height: 8),
                Text(
                  'Failed to load image',
                  style: TextStyle(color: Colors.grey[600], fontSize: 12),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildInputWidget(Map<String, dynamic> widget) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 8),
      child: TextField(
        decoration: InputDecoration(
          hintText: widget['placeholder'] ?? 'Enter text',
          labelText: widget['label'] ?? '',
          border: OutlineInputBorder(),
        ),
        obscureText: widget['type'] == 'password',
      ),
    );
  }

  Widget _buildContainerWidget(Map<String, dynamic> widget) {
    final children = widget['children'] as List<dynamic>? ?? [];
    final direction = widget['direction'] as String? ?? 'column';
    final padding = (widget['padding'] ?? 16).toDouble();
    final spacing = (widget['spacing'] ?? 8).toDouble();

    return Container(
      margin: EdgeInsets.symmetric(vertical: 8),
      padding: EdgeInsets.all(padding),
      decoration: BoxDecoration(
        color: _parseColor(widget['backgroundColor']),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.grey[300]!),
      ),
      child: direction == 'row'
          ? Row(
              children: _buildChildrenWithSpacing(children, spacing, Axis.horizontal),
            )
          : Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: _buildChildrenWithSpacing(children, spacing, Axis.vertical),
            ),
    );
  }

  List<Widget> _buildChildrenWithSpacing(List<dynamic> children, double spacing, Axis axis) {
    final widgets = children.map((child) => _buildWidget(context, child)).toList();

    if (widgets.isEmpty) return [Text('Empty container', style: TextStyle(color: Colors.grey))];

    final result = <Widget>[];
    for (int i = 0; i < widgets.length; i++) {
      result.add(widgets[i]);
      if (i < widgets.length - 1) {
        result.add(axis == Axis.horizontal
            ? SizedBox(width: spacing)
            : SizedBox(height: spacing));
      }
    }
    return result;
  }

  Color _parseColor(dynamic color) {
    if (color == null) return Colors.black;
    if (color is String && color.startsWith('#')) {
      try {
        return Color(int.parse(color.substring(1), radix: 16) + 0xFF000000);
      } catch (e) {
        return Colors.black;
      }
    }
    return Colors.black;
  }
}