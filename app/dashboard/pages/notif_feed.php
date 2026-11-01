<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Content-Type: application/json");
    echo json_encode([]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$email = $input["where"]["email"] ?? $_SESSION["email"] ?? "";

$payload = [
    "where" => [
        "email" => $email
    ]
];

$url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/filter";

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

$data = json_decode($response, true);
if (!is_array($data)) {
    $data = [];
}

usort($data, function($a, $b) {
    return strtotime($b["sent"] ?? 0) - strtotime($a["sent"] ?? 0);
});

header("Content-Type: application/json");
echo json_encode($data);
exit;