<?php
header("Content-Type: application/json");

$host = "localhost"; #change as needed
$user = "root"; #change as needed
$password = ""; #change as needed
$database = "contact_manager"; #change as needed

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}
?>