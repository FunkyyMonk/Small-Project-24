<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit();
}
header("Content-Type: application/json");

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

// Check if required fields are present
if (!isset($data['name']) || !isset($data['email']) || !isset($data['phone'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit();
}

$name = $data['name'];
$email = $data['email'];
$phone = $data['phone'];

// Look up user_id using email
$checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkUser->bind_param("s", $email);
$checkUser->execute();
$checkUser->store_result();
$checkUser->bind_result($user_id);
$checkUser->fetch();

if ($checkUser->num_rows === 0) {
    http_response_code(400);
    echo json_encode(["error" => "User email not found"]);
    exit();
}

$checkUser->close();
//finds user id in database to link name/phone to email
$sql = $conn->prepare("INSERT INTO contacts (name, email, phone, user_id) VALUES (?, ?, ?, ?)");
$sql->bind_param("sssi", $name, $email, $phone, $user_id);

if ($sql->execute()) {
    echo json_encode(["message" => "Contact created successfully"]);
} else {
    echo json_encode(["error" => "MySQL Error: " . $conn->error]);
}

$sql->close();
$conn->close();
?>
