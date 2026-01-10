<?php

$config = require __DIR__ . '/src/Config/restApi.php';

$swagger = [
    'openapi' => '3.0.0',
    'servers' => [
        [
            'url' => 'http://192.168.1.100/rest/api/'
        ],
    ],
    'components' => [
        'securitySchemes' => [
            'BearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
                'description' => 'JWT Authorization header using the Bearer scheme. Example: "Authorization
                Bearer {token}"',
            ],
        ],
    ],    
    'info' => [
        'title' => 'API Documentation TicoUrl',
        'version' => '1.0.0',
        'description' => 'API documentation for TicoUrl',
        'contact' => [
            'name' => 'TicoUrl Team',
            'email' => 'info@ticourl.com',
            'license' => [
                'name' => 'MIT',
                'url' => 'https://opensource.org/licenses/MIT',
            ],
        ],
    ],
    'paths' => [],
];

foreach ($config as $path => $details) {
    $method = strtolower($details['method']);
    $swagger['paths'][$path][$method] = [
        'summary' => $details['function'] ?? '',
        'operationId' => $details['action'] ?? '',
        'tags' => [explode('\\', $details['class'])[2] ?? 'Default'],
        'parameters' => $details['parameters'] ?? [],
        'responses' => $details['responses'] ?? [],
    ];

    // Add middleware or role information if available
    if (!empty($details['middleware'])) {
        $swagger['paths'][$path][$method]['description'] = 'Requires Authentication';
    }
    if (!empty($details['role'])) {
        $swagger['paths'][$path][$method]['description'] = 'Roles: ' . implode(', ', $details['role']);
    }
}
//header('Content-Type: application/json');
$data = json_encode($swagger, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/public/swagger.json', $data);
//file_put_contents(__DIR__ . '/front-end/ticoUrl/swagger.json', $data);