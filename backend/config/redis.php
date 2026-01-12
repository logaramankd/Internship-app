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
}

$redis = new Redis();

$redisHost = getenv("REDIS_HOST");
$redisPort = getenv("REDIS_PORT");
$redisPass = getenv("REDIS_PASSWORD");
$redisTls  = getenv("REDIS_TLS");

if (!$redisHost || !$redisPort) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Redis environment variables not set",
        "debug" => [
            "REDIS_HOST" => $redisHost,
            "REDIS_PORT" => $redisPort,
            "REDIS_TLS"  => $redisTls
        ]
    ]);
    exit;
}

try {
    if ($redisTls === "true") {
        $redis->connect("tls://" . $redisHost, $redisPort, 5);
    } else {
        $redis->connect($redisHost, $redisPort, 5);
    }

    if ($redisPass) {
        $redis->auth($redisPass);
    }

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
