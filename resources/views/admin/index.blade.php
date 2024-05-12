<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>React app</title>
    @viteReactRefresh
    @vite('resources/js/admin/main.jsx') 
    @vite('resources/css/app.css')
</head>
<body>
    <div id="root"></div>
</body>
</html>