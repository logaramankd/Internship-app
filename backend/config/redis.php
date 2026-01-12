<?php
// Load .env locally (same pattern as MySQL)
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            putenv($line);
        }
    }
}$redisUrl = getenv("REDIS_URL");

if (!$redisUrl) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "REDIS_URL not set in environment"
    ]);
    exit;
}

$redis = new Redis();

try {
    $parts = parse_url($redisUrl);

    $host = $parts['host'];
    $port = $parts['port'];
    $pass = $parts['pass'];

    // TLS connection (Upstash requires this)
    $redis->connect("tls://$host", $port, 5, null, 0, 0, [
        'auth' => $pass,
        'ssl' => ['verify_peer' => false]
    ]);

    $redis->ping();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Redis connection failed",
        "debug" => $e->getMessage()
    ]);
    exit;
}
