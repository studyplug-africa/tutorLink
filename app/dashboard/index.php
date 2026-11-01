<?php
session_start();



if($_SESSION["role"] =="lecturer"){


include("lecturer.php");



}else{




















if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$fullname = $_SESSION["fullname"] ?? "User";
$email    = $_SESSION["email"] ?? "";
$role     = $_SESSION["role"] ?? "student";

$initial = strtoupper(substr($fullname, 0, 1));

$notifPayload = [
    "where" => [
        "email" => $email
    ]
];

$notifUrl = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/filter";

$notifResponse = @file_get_contents(
    $notifUrl,
    false,
    stream_context_create([
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json",
            "content" => json_encode($notifPayload),
            "timeout" => 15
        ]
    ])
);

$notifications = json_decode($notifResponse, true);
if (!is_array($notifications)) {
    $notifications = [];
}
$notifCount = count($notifications);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorLink</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        html,
        body {
            background: #ffffff;
            height: 100%;
        }

        body {
            color: #0a2e3f;
            overflow: hidden;
        }

        .topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e4f0ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-img {
            width: 38px;
            height: 38px;
            border-radius: 5px;
            object-fit: cover;
        }

        .brand-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0a4b6e;
            letter-spacing: -0.02em;
        }

        .brand-name span {
            color: #0b6e4f;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            background: #ffffff;
            color: #0a4b6e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .icon-btn:hover {
            background: #f0f7f4;
            border-color: #0b6e4f;
            color: #0b6e4f;
        }

        .notif-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 9px;
            background: #c0392b;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .user-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            line-height: 1.25;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0a2e3f;
        }

        .user-role {
            font-size: 0.72rem;
            color: #6b8b7e;
            text-transform: capitalize;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #ffffff;
            color: #0a4b6e;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .notif-panel {
            position: fixed;
            top: 76px;
            right: 1.5rem;
            width: 340px;
            max-height: 420px;
            overflow-y: auto;
            background: #ffffff;
            border: 1px solid #cfe3da;
            border-radius: 5px;
            box-shadow: 0 20px 45px -18px rgba(11, 110, 79, 0.25);
            z-index: 200;
            transform: translateY(-10px);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .notif-panel.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .notif-header {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #e4f0ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-header h3 {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0a4b6e;
        }

        .notif-header span {
            font-size: 0.72rem;
            color: #6b8b7e;
            font-weight: 500;
        }

        .notif-list {
            list-style: none;
        }

        .notif-item {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f0f7f4;
            transition: background 0.15s;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #f6fbf8;
        }

        .notif-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0a4b6e;
            margin-bottom: 0.2rem;
        }

        .notif-msg {
            font-size: 0.78rem;
            color: #4a6b5e;
            line-height: 1.4;
            margin-bottom: 0.3rem;
        }

        .notif-date {
            font-size: 0.68rem;
            color: #8a9e94;
            font-weight: 500;
        }

        .notif-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: #8a9e94;
            font-size: 0.85rem;
        }

        .notif-empty i {
            display: block;
            font-size: 1.5rem;
            color: #cfe3da;
            margin-bottom: 0.5rem;
        }

        .layout {
            display: flex;
            padding-top: 64px;
            height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #ffffff;
            border-right: 1px solid #e4f0ea;
            padding: 1.25rem 0.9rem;
            position: fixed;
            top: 64px;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 90;
        }

        .nav-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8a9e94;
            padding: 0.25rem 0.75rem 0.6rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 5px;
            color: #0a2e3f;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.15s, border-color 0.15s;
            text-decoration: none;
            border: 1px solid transparent;
            background: transparent;
            font-family: inherit;
            width: 100%;
            text-align: left;
            margin-bottom: 0.15rem;
            position: relative;
        }

        .nav-item:hover {
            color: #0b6e4f;
        }

        .nav-item.active {
            color: #0b6e4f;
            border-color: #cfe3da;
            font-weight: 600;
        }

        .nav-icon {
            width: 18px;
            font-size: 0.9rem;
            text-align: center;
            flex-shrink: 0;
            color: #0a4b6e;
        }

        .nav-item.active .nav-icon {
            color: #0b6e4f;
        }

        .nav-badge {
            margin-left: auto;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background: #c0392b;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .nav-badge.hidden {
            display: none;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e4f0ea;
        }

        .signout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 5px;
            color: #0a2e3f;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            font-family: inherit;
            width: 100%;
            text-align: left;
            transition: color 0.15s, border-color 0.15s;
            background: transparent;
            cursor: pointer;
        }

        .signout-btn:hover {
            color: #c0392b;
            border-color: #cfe3da;
        }

        .signout-btn:hover .nav-icon {
            color: #c0392b;
        }

        .content {
            flex: 1;
            margin-left: 230px;
            height: calc(100vh - 64px);
        }

        .content-frame {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: #ffffff;
            display: block;
        }

        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.4rem;
            color: #0a4b6e;
            font-size: 1.15rem;
        }

        .backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 46, 63, 0.35);
            z-index: 80;
        }

        .backdrop.show {
            display: block;
        }

        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #ffffff;
            border-top: 1px solid #e4f0ea;
            z-index: 100;
            padding: 0 0.25rem;
            justify-content: space-between;
            align-items: stretch;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.15rem;
            padding: 0.35rem 0.15rem;
            border: none;
            background: transparent;
            color: #6b8b7e;
            font-family: inherit;
            font-size: 0.62rem;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.15s;
            position: relative;
        }

        .bottom-nav-item i {
            font-size: 1.05rem;
        }

        .bottom-nav-item.active {
            color: #0b6e4f;
        }

        .bottom-nav-item:active {
            background: #f0f7f4;
        }

        .bottom-badge {
            position: absolute;
            top: 4px;
            right: calc(50% - 22px);
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            border-radius: 8px;
            background: #c0392b;
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border: 2px solid #ffffff;
        }

        .bottom-badge.hidden {
            display: none;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 46, 63, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .modal {
            background: #ffffff;
            border: 1px solid #cfe3da;
            border-radius: 5px;
            width: 100%;
            max-width: 380px;
            margin: 1rem;
            padding: 1.5rem;
            box-shadow: 0 25px 50px -20px rgba(11, 110, 79, 0.3);
            transform: translateY(-10px);
            transition: transform 0.2s ease;
        }

        .modal-overlay.show .modal {
            transform: translateY(0);
        }

        .modal-icon {
            width: 44px;
            height: 44px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            color: #c0392b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            margin-bottom: 0.9rem;
        }

        .modal h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a4b6e;
            margin-bottom: 0.4rem;
        }

        .modal p {
            font-size: 0.88rem;
            color: #4a6b5e;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }

        .modal-actions {
            display: flex;
            gap: 0.6rem;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.1rem;
            border-radius: 5px;
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid #0a4b6e;
            background: #ffffff;
            color: #0a4b6e;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn:hover {
            background: #f0f7f4;
            border-color: #0b6e4f;
            color: #0b6e4f;
        }

        .btn-danger {
            border-color: #c0392b;
            color: #c0392b;
        }

        .btn-danger:hover {
            background: #fdecea;
            border-color: #c0392b;
            color: #c0392b;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .toast {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            transform: translateX(-150%);
            background: #ffffff;
            padding: 1rem 1.4rem;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 15px 35px -12px rgba(10, 75, 110, 0.25);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            z-index: 1200;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 260px;
            max-width: 340px;
            border-left: 5px solid #0b6e4f;
        }

        .toast.success {
            border-left-color: #0b6e4f;
        }

        .toast.error {
            border-left-color: #c0392b;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            flex-shrink: 0;
        }

        .toast.success .toast-icon {
            background: #0b6e4f;
        }

        .toast.error .toast-icon {
            background: #c0392b;
        }

        .toast-message {
            color: #0a2e3f;
            line-height: 1.3;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                height: calc(100vh - 64px - 64px);
            }

            .menu-toggle {
                display: block;
            }

            .user-meta {
                display: none;
            }

            .bottom-nav {
                display: flex;
            }

            .toast {
                top: auto;
                bottom: 80px;
                left: 1rem;
                right: 1rem;
                min-width: auto;
                max-width: none;
            }

            .notif-panel {
                top: 72px;
                bottom: 80px;
                right: 1rem;
                left: 1rem;
                width: auto;
                max-height: none;
            }
        }

        @media (max-width: 480px) {
            .topbar {
                padding: 0 1rem;
            }

            .brand-name {
                font-size: 1rem;
            }

            .bottom-nav-item {
                font-size: 0.58rem;
            }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
            <img src="logo.png" alt="TutorLink logo" class="logo-img">
            <div class="brand-name">Tutor<span>Link</span></div>
        </div>
        <div class="topbar-right">
            <button class="icon-btn" id="notifToggle" title="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="notif-dot <?php echo $notifCount === 0 ? 'hidden' : ''; ?>" id="topbarNotifDot" style="<?php echo $notifCount === 0 ? 'display:none;' : ''; ?>">
                    <?php echo $notifCount > 99 ? "99+" : $notifCount; ?>
                </span>
            </button>

            <div class="user-info">
                <div class="user-meta">
                    <span class="user-name"><?php echo htmlspecialchars($fullname); ?></span>
                    <span class="user-role"><?php echo htmlspecialchars($role); ?></span>
                </div>
                <div class="avatar">
                    <img src="https://static.vecteezy.com/system/resources/previews/074/064/285/large_2x/professional-male-avatar-icon-representing-user-profile-or-business-vector.jpg" alt="Avatar" onerror="this.style.display='none'; this.parentNode.textContent='<?php echo htmlspecialchars($initial); ?>';">
                </div>
            </div>
        </div>
    </header>

    <div class="notif-panel" id="notifPanel">
        <div class="notif-header">
            <h3>Notifications</h3>
            <span id="notifTotalLabel"><?php echo $notifCount; ?> total</span>
        </div>
        <ul class="notif-list" id="notifList">
            <?php if ($notifCount === 0): ?>
                <li class="notif-empty" id="notifEmpty">
                    <i class="fa-regular fa-bell-slash"></i>
                    No notifications yet
                </li>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <li class="notif-item">
                        <div class="notif-title"><?php echo htmlspecialchars($n["title"] ?? "Notification"); ?></div>
                        <div class="notif-msg"><?php echo htmlspecialchars($n["message"] ?? ""); ?></div>
                        <div class="notif-date"><?php echo htmlspecialchars($n["sent"] ?? ""); ?></div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="backdrop" id="backdrop"></div>

    <div class="layout">
        <aside class="sidebar" id="sidebar">
            <div class="nav-label">Menu</div>

            <button class="nav-item active" data-page="dashboard">
                <span class="nav-icon"><i class="fa-solid fa-house"></i></span>
                Home
            </button>

            <button class="nav-item" data-page="find-tutor">
                <span class="nav-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                Find Tutor
            </button>

            <button class="nav-item" data-page="my-sessions">
                <span class="nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                My Sessions
            </button>

            <button class="nav-item" data-page="resources">
                <span class="nav-icon"><i class="fa-solid fa-book"></i></span>
                Resources
            </button>

            <button class="nav-item" data-page="study-groups">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                Study Groups
            </button>

            <button class="nav-item" data-page="notifications">
                <span class="nav-icon"><i class="fa-solid fa-bell"></i></span>
                Notifications
                <span class="nav-badge <?php echo $notifCount === 0 ? 'hidden' : ''; ?>" id="sidebarNotifBadge">
                    <?php echo $notifCount > 99 ? "99+" : $notifCount; ?>
                </span>
            </button>

            <div class="sidebar-footer">
                <button class="signout-btn" id="signoutBtn">
                    <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    Sign out
                </button>
            </div>
        </aside>

        <main class="content">
            <iframe
                id="contentFrame"
                class="content-frame"
                src="pages/home"
                title="Content"
                frameborder="0"></iframe>
        </main>
    </div>

    <nav class="bottom-nav" id="bottomNav">
        <button class="bottom-nav-item active" data-page="dashboard">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </button>
        <button class="bottom-nav-item" data-page="find-tutor">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Find Tutor</span>
        </button>
       
        <button class="bottom-nav-item" data-page="resources">
            <i class="fa-solid fa-book"></i>
            <span>Resources</span>
        </button>
        <button class="bottom-nav-item" data-page="study-groups">
            <i class="fa-solid fa-users"></i>
            <span>Groups</span>
        </button>


        <button class="bottom-nav-item" data-page="notifications">
            <i class="fa-solid fa-bell"></i>
            <span>Alerts</span>
            <span class="bottom-badge <?php echo $notifCount === 0 ? 'hidden' : ''; ?>" id="bottomNotifBadge">
                <?php echo $notifCount > 99 ? "99+" : $notifCount; ?>
            </span>
        </button>

 <button class="bottom-nav-item" onclick="signout()">
    <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    Sign out
        </button>
        
    </nav>

    <div class="modal-overlay" id="signoutModal">
        <div class="modal">
            <div class="modal-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3>Sign out?</h3>
            <p>Are you sure you want to sign out of TutorLink? You will need to log in again to continue.</p>
            <div class="modal-actions">
                <button class="btn" id="cancelSignout">Cancel</button>
                <button class="btn btn-danger" id="confirmSignout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Sign out
                </button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast">
        <div class="toast-icon" id="toastIcon">!</div>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <script>
        const USER_EMAIL = <?php echo json_encode($email); ?>;

        const navItems = document.querySelectorAll(".nav-item");
        const bottomNavItems = document.querySelectorAll(".bottom-nav-item");
        const contentFrame = document.getElementById("contentFrame");
        const sidebar = document.getElementById("sidebar");
        const backdrop = document.getElementById("backdrop");
        const menuToggle = document.getElementById("menuToggle");
        const notifToggle = document.getElementById("notifToggle");
        const notifPanel = document.getElementById("notifPanel");
        const notifList = document.getElementById("notifList");
        const notifTotalLabel = document.getElementById("notifTotalLabel");
        const topbarNotifDot = document.getElementById("topbarNotifDot");
        const sidebarNotifBadge = document.getElementById("sidebarNotifBadge");
        const bottomNotifBadge = document.getElementById("bottomNotifBadge");
        const signoutBtn = document.getElementById("signoutBtn");
        const signoutModal = document.getElementById("signoutModal");
        const cancelSignout = document.getElementById("cancelSignout");
        const confirmSignout = document.getElementById("confirmSignout");
        const toast = document.getElementById("toast");
        const toastIcon = document.getElementById("toastIcon");
        const toastMessage = document.getElementById("toastMessage");

        const routes = {
            "dashboard": "pages/home",
            "find-tutor": "pages/find_tutor",
            "my-sessions": "pages/my_sessions",
            "resources": "pages/tutor_resources",
            "study-groups": "pages/study_groups",
            "notifications": "pages/notifications"
        };

        let toastTimer = null;

        function showToast(message, type) {
            clearTimeout(toastTimer);
            toast.classList.remove("success", "error", "show");
            void toast.offsetWidth;
            toastMessage.textContent = message;
            if (type === "success") {
                toast.classList.add("success");
                toastIcon.textContent = "✓";
            } else {
                toast.classList.add("error");
                toastIcon.textContent = "!";
            }
            toast.classList.add("show");
            toastTimer = setTimeout(() => {
                toast.classList.remove("show");
            }, 3500);
        }

        function updateNotifUI(count, items) {
            if (count > 0) {
                topbarNotifDot.style.display = "flex";
                topbarNotifDot.textContent = count > 99 ? "99+" : count;
                sidebarNotifBadge.classList.remove("hidden");
                sidebarNotifBadge.textContent = count > 99 ? "99+" : count;
                bottomNotifBadge.classList.remove("hidden");
                bottomNotifBadge.textContent = count > 99 ? "99+" : count;
            } else {
                topbarNotifDot.style.display = "none";
                sidebarNotifBadge.classList.add("hidden");
                bottomNotifBadge.classList.add("hidden");
            }
            notifTotalLabel.textContent = count + " total";

            if (Array.isArray(items)) {
                notifList.innerHTML = "";
                if (items.length === 0) {
                    const li = document.createElement("li");
                    li.className = "notif-empty";
                    li.innerHTML = '<i class="fa-regular fa-bell-slash"></i>No notifications yet';
                    notifList.appendChild(li);
                } else {
                    items.forEach(n => {
                        const li = document.createElement("li");
                        li.className = "notif-item";

                        const title = document.createElement("div");
                        title.className = "notif-title";
                        title.textContent = n.title || "Notification";

                        const msg = document.createElement("div");
                        msg.className = "notif-msg";
                        msg.textContent = n.message || "";

                        const date = document.createElement("div");
                        date.className = "notif-date";
                        date.textContent = n.sent || "";

                        li.appendChild(title);
                        li.appendChild(msg);
                        li.appendChild(date);
                        notifList.appendChild(li);
                    });
                }
            }
        }

        async function refreshNotifications() {
            try {
                const res = await fetch("pages/notif_feed", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        where: {
                            email: USER_EMAIL
                        }
                    })
                });

                const data = await res.json();
                const items = Array.isArray(data) ? data : [];
                updateNotifUI(items.length, items);
            } catch (err) {
                console.warn("Notification refresh failed", err);
            }
        }

        setInterval(refreshNotifications, 5000);

        function setActivePage(page) {
            navItems.forEach(i => {
                i.classList.toggle("active", i.dataset.page === page);
            });
            bottomNavItems.forEach(i => {
                i.classList.toggle("active", i.dataset.page === page);
            });

            contentFrame.src = routes[page] || "dashboard_home";

            if (page === "notifications") {
                refreshNotifications();
            }

            if (window.innerWidth <= 900) {
                sidebar.classList.remove("open");
                backdrop.classList.remove("show");
            }
        }

        navItems.forEach(item => {
            item.addEventListener("click", () => {
                setActivePage(item.dataset.page);
            });
        });

        bottomNavItems.forEach(item => {
            item.addEventListener("click", () => {
                setActivePage(item.dataset.page);
            });
        });

        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            backdrop.classList.toggle("show");
        });

        backdrop.addEventListener("click", () => {
            sidebar.classList.remove("open");
            backdrop.classList.remove("show");
        });

        notifToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            notifPanel.classList.toggle("show");
            if (notifPanel.classList.contains("show")) {
                refreshNotifications();
            }
        });

        document.addEventListener("click", (e) => {
            if (!notifPanel.contains(e.target) && e.target !== notifToggle) {
                notifPanel.classList.remove("show");
            }
        });

function signout(){
    openSignoutModal();
}

        function openSignoutModal() {
            signoutModal.classList.add("show");
        }

        function closeSignoutModal() {
            signoutModal.classList.remove("show");
        }

        signoutBtn.addEventListener("click", openSignoutModal);
        cancelSignout.addEventListener("click", closeSignoutModal);

        signoutModal.addEventListener("click", (e) => {
            if (e.target === signoutModal) closeSignoutModal();
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && signoutModal.classList.contains("show")) {
                closeSignoutModal();
            }
        });

        confirmSignout.addEventListener("click", async () => {
            confirmSignout.disabled = true;
            confirmSignout.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Signing out...';

            try {
                const res = await fetch("pages/logout", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    }
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok && data.success !== false) {
                    closeSignoutModal();
                    showToast("Signed out successfully. Redirecting...", "success");
                    setTimeout(() => {
                        window.location.href = "../";
                    }, 1200);
                } else {
                    closeSignoutModal();
                    showToast(data.message || "Could not sign out. Please try again.", "error");
                    confirmSignout.disabled = false;
                    confirmSignout.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Sign out';
                }
            } catch (err) {
                closeSignoutModal();
                showToast("Network error. Please try again.", "error");
                confirmSignout.disabled = false;
                confirmSignout.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Sign out';
            }
        });
    </script>
</body>

</html>

<?php 

                }

                ?>