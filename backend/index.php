<?php
header("Content-Type: application/json");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {

    case '/':
        echo json_encode(["status" => "ok", "message" => "Backend is running"]);
        break;

    case '/api/test':
        echo json_encode(["message" => "API is working"]);
        break;

    case '/api/signup':
        require __DIR__ . '/api/register.php';
        break;

    case '/api/login':
        require __DIR__ . '/api/login.php';
        break;

    case '/api/profile':
        require __DIR__ . '/api/profile.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
}
