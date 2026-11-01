<?php
$type = $_POST["type"] ?? "";

$allowed = [
    "user_b6a3d990_rea_study_groups",
    "user_b6a3d990_rea_group_members",
    "user_b6a3d990_rea_group_messages",
    "user_b6a3d990_rea_group_resources",
    "user_b6a3d990_rea_lecturer_subjects",
    "user_b6a3d990_rea_bookings",
    "user_b6a3d990_rea_lecturer_resource",
    "user_b6a3d990_rea_lecturer_schedule",
];

$deletable = [
    "user_b6a3d990_rea_group_members",
    "user_b6a3d990_rea_group_messages",
    "user_b6a3d990_rea_group_resources",
    "user_b6a3d990_rea_lecturer_subjects",
    "user_b6a3d990_rea_lecturer_resource",
    "user_b6a3d990_rea_lecturer_schedule",
];

if ($type === "insert_group") {
    $payload = [
        "name"        => $_POST["name"] ?? null,
        "topic"       => $_POST["topic"] ?? null,
        "description" => $_POST["description"] ?? null,
        "owner_name"  => $_POST["owner_name"] ?? null,
        "owner_email" => $_POST["owner_email"] ?? null,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_study_groups/insert";
} elseif ($type === "update_group") {
    $payload = [
        "set" => [
            "name"        => $_POST["name"] ?? null,
            "topic"       => $_POST["topic"] ?? null,
            "description" => $_POST["description"] ?? null,
        ],
        "where" => ["id" => $_POST["id"] ?? null]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_study_groups/update";
} elseif ($type === "delete_group") {
    $payload = [
        "where" => ["id" => $_POST["id"] ?? null]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_study_groups/delete";
} elseif ($type === "insert_member") {
    $payload = [
        "group_id" => $_POST["group_id"] ?? null,
        "fullname" => $_POST["fullname"] ?? null,
        "email"    => $_POST["email"] ?? null,
        "role"     => $_POST["role"] ?? null,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_group_members/insert";
} elseif ($type === "update_member") {
    $payload = [
        "set" => ["role" => $_POST["role"] ?? null],
        "where" => [
            "group_id" => $_POST["group_id"] ?? null,
            "email"    => $_POST["email"] ?? null,
        ]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_group_members/update";
} elseif ($type === "insert_message") {
    $payload = [
        "group_id" => $_POST["group_id"] ?? null,
        "name"     => $_POST["name"] ?? null,
        "email"    => $_POST["email"] ?? null,
        "text"     => $_POST["text"] ?? null,
        "sent"     => $_POST["sent"] ?? null,
        "type"     => $_POST["msg_type"] ?? "message",
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_group_messages/insert";

    if (($_POST["msg_type"] ?? "") === "announcement") {
        $group_id   = $_POST["group_id"] ?? "";
        $group_name = $_POST["group_name"] ?? "Study Group";
        $sender     = $_POST["name"] ?? "Someone";
        $text       = $_POST["text"] ?? "";
        $sent       = $_POST["sent"] ?? date("Y-m-d H:i:s");
        $myEmail    = $_POST["email"] ?? "";

        $membersRes = @file_get_contents(
            "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_group_members/filter",
            false,
            stream_context_create([
                "http" => [
                    "method"  => "POST",
                    "header"  => "Content-Type: application/json",
                    "content" => json_encode(["where" => ["group_id" => $group_id]]),
                    "timeout" => 15
                ]
            ])
        );

        $members = json_decode($membersRes, true);
        if (is_array($members)) {
            foreach ($members as $m) {
                $to = $m["email"] ?? "";
                if (!$to || $to === $myEmail) continue;

                @file_get_contents(
                    "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/insert",
                    false,
                    stream_context_create([
                        "http" => [
                            "method"  => "POST",
                            "header"  => "Content-Type: application/json",
                            "content" => json_encode([
                                "title"   => "New announcement in " . $group_name,
                                "message" => $sender . ": " . $text,
                                "sent"    => $sent,
                                "email"   => $to,
                            ]),
                            "timeout" => 15
                        ]
                    ])
                );
            }
        }
    }
} elseif ($type === "insert_resource") {
    $payload = [
        "group_id" => $_POST["group_id"] ?? null,
        "url"      => $_POST["url"] ?? null,
        "added_by" => $_POST["added_by"] ?? null,
        "email"    => $_POST["email"] ?? null,
        "sent"     => $_POST["sent"] ?? null,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_group_resources/insert";
} elseif ($type === "insert_subject") {
    $payload = [
        "email" => $_POST["email"] ?? null,
        "name"  => $_POST["name"] ?? null,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_lecturer_subjects/insert";
} elseif ($type === "insert_slot") {
    $payload = [
        "email"   => $_POST["email"] ?? null,
        "day"     => $_POST["day"] ?? null,
        "time"    => $_POST["time"] ?? null,
        "subject" => $_POST["subject"] ?? null,
        "price"   => $_POST["price"] ?? 0,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_lecturer_schedule/insert";
} elseif ($type === "update_booking_status") {
    $payload = [
        "set" => [
            "status" => $_POST["status"] ?? null,
        ],
        "where" => [
            "id" => $_POST["id"] ?? null,
        ]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_bookings/update";
} elseif ($type === "accept_booking") {
    $bookingId = $_POST["id"] ?? null;
    $link      = $_POST["meeting_link"] ?? "";
    $student   = $_POST["student_email"] ?? "";
    $tutorName = $_POST["tutor_name"] ?? "Your tutor";
    $subject   = $_POST["subject"] ?? "session";
    $dayName   = $_POST["day_name"] ?? "";
    $time      = $_POST["time"] ?? "";

    $payload = [
        "set" => [
            "status"       => "accepted",
            "meeting_link" => $link,
        ],
        "where" => [
            "id" => $bookingId,
        ]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_bookings/update";

    $response = file_get_contents(
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

    if ($student) {
        $notif = [
            "title"   => "Session accepted · " . $subject,
            "message" => $tutorName . " accepted your session on " . $dayName . " at " . $time . ". Join link: " . $link,
            "sent"    => date("Y-m-d H:i:s"),
            "email"   => $student,
        ];

        @file_get_contents(
            "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/insert",
            false,
            stream_context_create([
                "http" => [
                    "method"  => "POST",
                    "header"  => "Content-Type: application/json",
                    "content" => json_encode($notif),
                    "timeout" => 15
                ]
            ])
        );
    }

    header("Content-Type: application/json");
    echo $response ?: json_encode(["success" => true]);
    exit;
} elseif ($type === "get") {
    $table = $_POST["table"] ?? "";
    if (!in_array($table, $allowed)) {
        header("Content-Type: application/json");
        echo json_encode([]);
        exit;
    }
    $url = "https://studyplugtools.cloud/you_connect.php/" . $table . "/get";
    $payload = [];
} elseif ($type === "filter") {
    $table = $_POST["table"] ?? "";
    $where = isset($_POST["where"]) ? json_decode($_POST["where"], true) : [];

    if (!in_array($table, $allowed)) {
        header("Content-Type: application/json");
        echo json_encode([]);
        exit;
    }

    $url = "https://studyplugtools.cloud/you_connect.php/" . $table . "/filter";
    $payload = ["where" => $where];
} elseif ($type === "insert_booking") {
    $payload = [
        "student_name"  => $_POST["student_name"] ?? null,
        "student_email" => $_POST["student_email"] ?? null,
        "tutor_name"    => $_POST["tutor_name"] ?? null,
        "tutor_email"   => $_POST["tutor_email"] ?? null,
        "subject"       => $_POST["subject"] ?? null,
        "day"           => $_POST["day"] ?? null,
        "day_name"      => $_POST["day_name"] ?? null,
        "time"          => $_POST["time"] ?? null,
        "price"         => $_POST["price"] ?? 0,
        "sent"          => $_POST["sent"] ?? null,
        "status"        => "pending",
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_bookings/insert";

    $response = file_get_contents(
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

    $tutor = $_POST["tutor_email"] ?? "";
    if ($tutor) {
        $notif = [
            "title"   => "New booking request · " . ($_POST["subject"] ?? "session"),
            "message" => $_POST["student_name"] . " wants to book " . ($_POST["subject"] ?? "") . " on " . ($_POST["day_name"] ?? "") . " at " . ($_POST["time"] ?? "") . ". Open your requests page to accept or reject.",
            "sent"    => date("Y-m-d H:i:s"),
            "email"   => $tutor,
        ];

        @file_get_contents(
            "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/insert",
            false,
            stream_context_create([
                "http" => [
                    "method"  => "POST",
                    "header"  => "Content-Type: application/json",
                    "content" => json_encode($notif),
                    "timeout" => 15
                ]
            ])
        );
    }

    header("Content-Type: application/json");
    echo $response ?: json_encode(["success" => true]);
    exit;
} elseif ($type === "delete") {
    $table = $_POST["table"] ?? "";
    $where = isset($_POST["where"]) ? json_decode($_POST["where"], true) : [];

    if (!in_array($table, $deletable)) {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "Not deletable"]);
        exit;
    }

    $url = "https://studyplugtools.cloud/you_connect.php/" . $table . "/delete";
    $payload = ["where" => $where];
}  elseif ($type === "insert_resource_lecturer") {
    $payload = [
        "email"       => $_POST["email"] ?? null,
        "name"        => $_POST["name"] ?? null,
        "type"        => $_POST["resource_type"] ?? "other",
        "url"         => $_POST["url"] ?? null,
        "description" => $_POST["description"] ?? null,
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_lecturer_resource/insert";
} elseif ($type === "update_resource") {
    $payload = [
        "set" => [
            "name"        => $_POST["name"] ?? null,
            "type"        => $_POST["resource_type"] ?? null,
            "url"         => $_POST["url"] ?? null,
            "description" => $_POST["description"] ?? null,
        ],
        "where" => [
            "id" => $_POST["id"] ?? null,
        ]
    ];
    $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_lecturer_resource/update";
} else {
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "message" => "Unknown type"]);
    exit;
}

$response = file_get_contents(
    $url,
    false,
    stream_context_create([
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json",
            "content" => json_encode($payload)
        ]
    ])
);

header("Content-Type: application/json");
echo $response;
