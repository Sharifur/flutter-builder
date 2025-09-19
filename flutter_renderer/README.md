# FlutterBuilder Renderer

A Flutter application that renders projects created with the FlutterBuilder platform.

## Features

- Load and render FlutterBuilder projects from JSON schema
- Support for multiple widget types:
  - Text with customizable styling
  - Buttons with actions and navigation
  - Images with error handling
  - Input fields
  - Containers with layout options
- Multi-page navigation
- Real-time API integration with Laravel backend

## Setup

1. Ensure you have Flutter installed on your system
2. Clone this repository
3. Update the API URL in `lib/config.dart` to point to your Laravel backend
4. Run `flutter pub get` to install dependencies
5. Run `flutter run` to start the app

## Configuration

Edit `lib/config.dart` to set your Laravel API URL:

```dart
class Config {
  static const String apiUrl = 'http://your-laravel-backend.com';
}
```

## Usage

1. Launch the app
2. Enter a project ID from FlutterBuilder
3. The app will fetch the project schema from the API
4. Navigate between pages using button actions
5. Interact with widgets as defined in the schema

## Supported Widget Types

### Text
- Properties: value, color, fontSize, fontWeight

### Button
- Properties: label, color, textColor, action
- Actions: goTo:pageId for navigation

### Image
- Properties: url, width, height, alt
- Includes error handling for failed image loads

### Input
- Properties: placeholder, label, required, type
- Currently supports text input

### Container
- Properties: direction, spacing, padding, backgroundColor, children
- Supports both row and column layouts
- Can contain nested widgets

## API Integration

The app fetches project schemas from the Laravel backend using these endpoints:

- `GET /api/projects/{id}/schema` - Fetch project schema
- Authentication via Sanctum tokens (when implemented)

## Building for Production

### Web
```bash
flutter build web
```

### Mobile
```bash
flutter build apk  # Android
flutter build ios  # iOS
```

## Development

The main components are:

- `main.dart` - App entry point and project selector
- `json_renderer.dart` - Core rendering engine
- `config.dart` - Configuration settings

To add new widget types:

1. Add the widget type to the switch statement in `_buildWidget()`
2. Create a new `_build{WidgetType}Widget()` method
3. Handle the widget's specific properties and styling

## Testing

To test with sample data without a backend:

1. Use the sample schema in `assets/sample_schema.json`
2. Modify the code to load from assets instead of API for testing

## Troubleshooting

### Common Issues

1. **API Connection Failed**: Check the API URL in config.dart
2. **CORS Issues**: Ensure your Laravel backend allows requests from the Flutter app domain
3. **Image Loading Failed**: Check image URLs and network connectivity

### Debug Mode

Run in debug mode to see detailed error messages:
```bash
flutter run --debug
```