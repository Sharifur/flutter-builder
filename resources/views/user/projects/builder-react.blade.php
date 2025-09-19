<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Builder - {{ $project->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/builder-entry.jsx'])
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
        }

        #builder-app {
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>
<body>
    <div id="builder-app"></div>

    <script>
        // Pass Laravel data to React
        window.builderConfig = {
            projectId: {{ $project->id }},
            projectName: @json($project->name),
            selectedPageId: {{ $selectedPage ? $selectedPage->id : 'null' }},
            csrfToken: @json(csrf_token()),
            apiBaseUrl: '{{ url('/user') }}',
            currentUser: @json(auth()->user()->only(['id', 'name', 'email']))
        };
    </script>
</body>
</html>