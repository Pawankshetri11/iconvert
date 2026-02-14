<?php
// Simple PHP server for testing license server locally
// Run with: php server.php

$host = '127.0.0.1';
$port = 8001;

echo "License Server Test Server\n";
echo "=========================\n";
echo "Starting server on http://{$host}:{$port}\n";
echo "Press Ctrl+C to stop\n\n";

// Simple routing
$routes = [
    'GET /' => function() {
        $testKey = 'ROYAL-' . strtoupper(substr(md5('test'), 0, 16));
        return json_encode([
            'message' => 'License Server API',
            'status' => 'running',
            'test_license_key' => $testKey,
            'endpoints' => [
                'POST /api/license/generate' => 'Generate license key',
                'POST /api/license/activate' => 'Activate license',
                'POST /api/license/validate' => 'Validate license',
                'GET /install' => 'Web installer'
            ]
        ]);
    },
    'GET /install' => function() {
        return [
            'content' => '<!DOCTYPE html>
        <html>
        <head><title>License Server Install</title></head>
        <body>
        <h1>License Server Installation</h1>
        <form method="POST" action="/install">
        <label>DB Host: <input name="db_host" value="localhost"></label><br>
        <label>DB Name: <input name="db_database" value="license_test"></label><br>
        <label>DB User: <input name="db_username" value="root"></label><br>
        <label>DB Pass: <input name="db_password"></label><br>
        <label>App Key: <input name="app_key" value="c3VwZXJzZWNyZXRrZXl0aGF0aXMyY2hhcmFjdGVycw=="></label><br>
        <button type="submit">Install</button>
        </form>
        </body>
        </html>',
            'content_type' => 'text/html'
        ];
    },
    'POST /install' => function($post) {
        return json_encode([
            'success' => true,
            'message' => 'Installation simulated',
            'config' => $post
        ]);
    },
    'POST /api/license/generate' => function($post) {
        $key = 'ROYAL-' . strtoupper(substr(md5(uniqid()), 0, 16));
        return json_encode([
            'license_key' => $key,
            'type' => $post['type'] ?? 'extended',
            'message' => 'License key generated successfully'
        ]);
    },
    'POST /api/license/activate' => function($post) {
        $key = $post['license_key'] ?? '';
        $domain = $post['domain'] ?? '';

        if (empty($key) || empty($domain)) {
            return json_encode([
                'valid' => false,
                'message' => 'Missing license_key or domain'
            ]);
        }

        // Simulate domain locking
        static $usedKeys = [];
        if (isset($usedKeys[$key]) && $usedKeys[$key] !== $domain) {
            return json_encode([
                'valid' => false,
                'message' => 'License already activated on another domain'
            ]);
        }

        $usedKeys[$key] = $domain;

        return json_encode([
            'valid' => true,
            'type' => 'extended',
            'message' => 'License activated successfully for domain: ' . $domain
        ]);
    },
    'POST /api/license/validate' => function($post) {
        $key = $post['license_key'] ?? '';
        $domain = $post['domain'] ?? '';

        static $usedKeys = [];

        if (isset($usedKeys[$key]) && $usedKeys[$key] === $domain) {
            return json_encode([
                'valid' => true,
                'type' => 'extended',
                'message' => 'License is valid'
            ]);
        }

        return json_encode([
            'valid' => false,
            'message' => 'License not found or invalid'
        ]);
    }
];

// Start server
$socket = stream_socket_server("tcp://{$host}:{$port}", $errno, $errstr);
if (!$socket) {
    die("Could not create socket: {$errstr}\n");
}

echo "Server started. Visit: http://{$host}:{$port}\n";
echo "Test endpoints:\n";
echo "- http://{$host}:{$port}/\n";
echo "- http://{$host}:{$port}/install\n\n";

stream_set_blocking($socket, false);

while (true) {
    $client = @stream_socket_accept($socket, 1);
    if ($client === false) {
        usleep(100000); // Sleep for 0.1 seconds
        continue;
    }

    $request = fread($client, 8192);
    if (!$request) {
        fclose($client);
        continue;
    }

    // Parse request
    $lines = explode("\n", $request);
    $firstLine = $lines[0];
    list($method, $path, $version) = explode(' ', $firstLine);

    // Get POST data
    $postData = '';
    $contentLength = 0;
    foreach ($lines as $line) {
        if (stripos($line, 'Content-Length:') === 0) {
            $contentLength = (int) trim(substr($line, 15));
        }
    }

    if ($contentLength > 0 && $method === 'POST') {
        $postData = substr($request, -($contentLength));
        parse_str($postData, $post);
    }

    $routeKey = $method . ' ' . $path;
    $routeResponse = isset($routes[$routeKey]) ? $routes[$routeKey]($post ?? []) : json_encode(['error' => 'Route not found']);

    // Handle different response formats
    if (is_array($routeResponse)) {
        $response = $routeResponse['content'];
        $contentType = $routeResponse['content_type'] ?? 'application/json';
    } else {
        $response = $routeResponse;
        $contentType = 'application/json';
    }

    // Send response
    $headers = "HTTP/1.1 200 OK\r\n";
    $headers .= "Content-Type: {$contentType}\r\n";
    $headers .= "Access-Control-Allow-Origin: *\r\n";
    $headers .= "Access-Control-Allow-Methods: GET, POST\r\n";
    $headers .= "Access-Control-Allow-Headers: Content-Type\r\n";
    $headers .= "Content-Length: " . strlen($response) . "\r\n";
    $headers .= "\r\n";

    fwrite($client, $headers . $response);
    fclose($client);
}

fclose($socket);
?>