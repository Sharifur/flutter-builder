class Config {
  // Change this to your Laravel API URL
  static const String apiUrl = 'http://localhost:8000';

  // API endpoints
  static String getSchemaUrl(String projectId) => '$apiUrl/api/projects/$projectId/schema';
  static String getPreviewUrl(String projectId) => '$apiUrl/api/projects/$projectId/preview';
}