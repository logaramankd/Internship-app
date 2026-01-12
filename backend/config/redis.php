<?php

$redis = new Redis();

$redisHost = getenv("REDIS_HOST");
$redisPort = getenv("REDIS_PORT");
$redisPass = getenv("REDIS_PASSWORD");

try {

    if ($redisHost && $redisPort) {
        // Railway environment
        $redis->connect($redisHost, $redisPort, 5);

        if ($redisPass) {
            $redis->auth($redisPass);
        }

    } else {
        // Local environment
        $redis->connect("127.0.0.1", 6379, 5);
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
