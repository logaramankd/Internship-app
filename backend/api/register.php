<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../backend/config/db.php';
require_once __DIR__ . '/../backend/config/redis.php';

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($name === "" || $email === "" || $password === "") {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, password)
         VALUES (:name, :email, :password)"
    );

    $stmt->execute([
        ":name" => $name,
        ":email" => $email,
        ":password" => $hashedPassword
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "User registered successfully"
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode([
            "status" => "error",
            "message" => "Email already exists"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Registration failed",
            "debug" => $e->getMessage()   // keep TEMPORARY for debugging
        ]);
    }
}
