<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once __DIR__ . '/../config/mongo.php';
require_once __DIR__ . '/../config/redis.php';


$method = $_SERVER['REQUEST_METHOD'];
$sessionKey = $_REQUEST['session_key'] ?? '';

if (!$sessionKey) {
    echo json_encode(["status" => "error", "message" => "Session missing"]);
    exit;
}

$user_id = $redis->get("session:$sessionKey");

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "Invalid session"]);
    exit;
}

try {

    if ($method === 'GET') {

        $profile = $profileCollection->findOne([
            'user_id' => (int)$user_id
        ]);

        echo json_encode([
            "status" => "success",
            "data" => $profile
        ]);
        exit;
    }

    if ($method === 'POST') {

        $age = $_POST['age'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $designation = $_POST['designation'] ?? '';
        $company = $_POST['company'] ?? '';

        if (!$age || !$dob || !$contact || !$address) {
            echo json_encode([
                "status" => "error",
                "message" => "All fields are required"
            ]);
            exit;
        }

        $profileCollection->updateOne(
            ['user_id' => (int)$user_id],
            ['$set' => [
                'user_id' => (int)$user_id,
                'age' => $age,
                'dob' => $dob,
                'contact' => $contact,
                'address' => $address,
                'designation'=>$designation,
                'company'=>$company,
                'updated_at' => date("Y-m-d H:i:s")
            ]],
            ['upsert' => true]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Profile saved successfully"
        ]);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Server error",
        "debug" => $e->getMessage()
    ]);
    exit;
}
