<?php

include 'devcoder.php';

(new DotEnv($_SERVER['DOCUMENT_ROOT'] . '/.env'))->load();

$host = getenv('DB_HOST');
$user = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo "Database Connection Failed";
}
