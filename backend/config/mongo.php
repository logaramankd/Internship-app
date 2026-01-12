<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;

$mongoUri = "mongodb+srv://intern_user:kdram2004@profilemanagement.yuh76bg.mongodb.net/?appName=ProfileManagement";

$client = new Client($mongoUri);

$database = $client->internship_app;
$profileCollection = $database->profiles;
