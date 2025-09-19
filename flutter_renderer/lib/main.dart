import 'package:flutter/material.dart';
import 'config.dart';
import 'json_renderer.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'FlutterBuilder Renderer',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: ProjectSelector(),
    );
  }
}

class ProjectSelector extends StatefulWidget {
  @override
  _ProjectSelectorState createState() => _ProjectSelectorState();
}

class _ProjectSelectorState extends State<ProjectSelector> {
  final TextEditingController _projectIdController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('FlutterBuilder Renderer'),
      ),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              'Enter Project ID to Preview',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            SizedBox(height: 20),
            TextField(
              controller: _projectIdController,
              decoration: InputDecoration(
                labelText: 'Project ID',
                hintText: 'Enter the project ID from FlutterBuilder',
                border: OutlineInputBorder(),
              ),
              keyboardType: TextInputType.number,
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                final projectId = _projectIdController.text.trim();
                if (projectId.isNotEmpty) {
                  Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (context) => JsonRenderer(projectId: projectId),
                    ),
                  );
                }
              },
              child: Text('Load Project'),
            ),
            SizedBox(height: 40),
            Text(
              'API URL: ${Config.apiUrl}',
              style: Theme.of(context).textTheme.bodySmall,
            ),
          ],
        ),
      ),
    );
  }
}