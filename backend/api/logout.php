<?php
header("Access-Control-Allow-Origin: https://profile-managements.netlify.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/redis.php';

$sessionKey = $_POST['session_key'] ?? '';

if ($sessionKey) {
    $redis->del("session:$sessionKey");
}

echo json_encode(["status" => "success"]);
