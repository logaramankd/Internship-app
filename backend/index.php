<?php
header("Content-Type: application/json");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo json_encode(["status" => "ok", "message" => "Backend is running"]);
        break;

    case '/health':
        echo json_encode(["status" => "ok", "service" => "internship-app"]);
        break;

    case '/api/test':
        require __DIR__ . '/api/test.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
}
