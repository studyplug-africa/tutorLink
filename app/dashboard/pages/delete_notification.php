<?php
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Not logged in."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Missing notification id."]);
    exit;
}

$payload = [
    "where" => [
        "id" => $id
    ]
];

$url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/delete";

$response = @file_get_contents(
    $url,
    false,
    stream_context_create([
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json",
            "content" => json_encode($payload),
            "timeout" => 15
        ]
    ])
);

if ($response === false) {
    echo json_encode(["success" => false, "message" => "Server error."]);
    exit;
}

echo json_encode(["success" => true, "message" => "Notification deleted."]);