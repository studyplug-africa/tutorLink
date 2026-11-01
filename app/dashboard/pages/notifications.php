<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$email = $_SESSION["email"] ?? "";

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

$notifications = json_decode($response, true);
if (!is_array($notifications)) {
    $notifications = [];
}

usort($notifications, function ($a, $b) {
    return strtotime($b["sent"] ?? 0) - strtotime($a["sent"] ?? 0);
});

function timeAgo($timestamp)
{
    if (!$timestamp) return "";
    $time = strtotime($timestamp);
    if (!$time) return $timestamp;
    $diff = time() - $time;
    if ($diff < 60) return "just now";
    if ($diff < 3600) return floor($diff / 60) . "m ago";
    if ($diff < 86400) return floor($diff / 3600) . "h ago";
    if ($diff < 604800) return floor($diff / 86400) . "d ago";
    return date("M j, Y", $time);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications · TutorLink</title>
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

        .wrap {
            display: flex;
            height: 100vh;
            background: #ffffff;
        }

        .list-pane {
            width: 360px;
            border-right: 1px solid #e4f0ea;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            background: #ffffff;
        }

        .list-header {
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid #e4f0ea;
        }

        .list-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .list-header h1 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a4b6e;
            letter-spacing: -0.01em;
        }

        .list-header span {
            font-size: 0.72rem;
            color: #6b8b7e;
            font-weight: 600;
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #8a9e94;
            font-size: 0.78rem;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 0.55rem 0.75rem 0.55rem 2rem;
            font-size: 0.83rem;
            border: 1px solid #cfe3da;
            border-radius: 5px;
            outline: none;
            color: #0a2e3f;
            background: #ffffff;
            font-family: inherit;
            transition: border 0.15s, box-shadow 0.15s;
        }

        .search-input::placeholder {
            color: #a8bcb2;
        }

        .search-input:focus {
            border-color: #0b6e4f;
            box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
        }

        .list-scroll {
            flex: 1;
            overflow-y: auto;
        }

        .notif-row {
            display: flex;
            gap: 0.75rem;
            padding: 0.95rem 1.25rem;
            border-bottom: 1px solid #f0f7f4;
            cursor: pointer;
            transition: background 0.15s;
            border-left: 3px solid transparent;
            position: relative;
        }

        .notif-row:hover {
            background: #f6fbf8;
        }

        .notif-row.active {
            background: #f0f7f4;
            border-left-color: #0b6e4f;
        }

        .notif-row.unread .notif-row-title {
            font-weight: 700;
        }

        .notif-row.unread .notif-row-preview {
            color: #0a2e3f;
            font-weight: 500;
        }

        .notif-row-icon {
            width: 38px;
            height: 38px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b6e4f;
            font-size: 0.85rem;
            flex-shrink: 0;
            background: #ffffff;
        }

        .notif-row-body {
            flex: 1;
            min-width: 0;
        }

        .notif-row-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.2rem;
        }

        .notif-row-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0a4b6e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-row-time {
            font-size: 0.68rem;
            color: #8a9e94;
            font-weight: 500;
            flex-shrink: 0;
        }

        .notif-row-preview {
            font-size: 0.78rem;
            color: #6b8b7e;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notif-delete {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 26px;
            height: 26px;
            border-radius: 5px;
            border: 1px solid transparent;
            background: transparent;
            color: #8a9e94;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.72rem;
            opacity: 0;
            transition: opacity 0.15s, color 0.15s, background 0.15s, border-color 0.15s;
        }

        .notif-row:hover .notif-delete {
            opacity: 1;
        }

        .notif-delete:hover {
            background: #fdecea;
            border-color: #f5c6c1;
            color: #c0392b;
        }

        .empty-list {
            padding: 3rem 1.25rem;
            text-align: center;
            color: #8a9e94;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .empty-list i {
            display: block;
            font-size: 2rem;
            color: #cfe3da;
            margin-bottom: 0.65rem;
        }

        .empty-list strong {
            color: #0a4b6e;
            font-weight: 600;
        }

        .detail-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            min-width: 0;
        }

        .detail-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #e4f0ea;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .detail-back {
            display: none;
            width: 34px;
            height: 34px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            background: #ffffff;
            color: #0a4b6e;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .detail-back:hover {
            background: #f0f7f4;
            border-color: #0b6e4f;
            color: #0b6e4f;
        }

        .detail-header-text {
            min-width: 0;
            flex: 1;
        }

        .detail-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a4b6e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.15rem;
        }

        .detail-sub {
            font-size: 0.75rem;
            color: #6b8b7e;
            font-weight: 500;
        }

        .detail-delete {
            width: 36px;
            height: 36px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            background: #ffffff;
            color: #8a9e94;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .detail-delete:hover {
            background: #fdecea;
            border-color: #f5c6c1;
            color: #c0392b;
        }

        .detail-body {
            flex: 1;
            overflow-y: auto;
            padding: 2rem 1.5rem;
        }

        .detail-body-inner {
            max-width: 720px;
            margin: 0;
        }

        .detail-meta {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #e4f0ea;
        }

        .detail-avatar {
            width: 42px;
            height: 42px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b6e4f;
            font-size: 1rem;
            flex-shrink: 0;
            background: #ffffff;
        }

        .detail-meta-text {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .detail-meta-from {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0a4b6e;
        }

        .detail-meta-date {
            font-size: 0.72rem;
            color: #8a9e94;
        }

        .detail-message {
            font-size: 0.95rem;
            color: #0a2e3f;
            line-height: 1.7;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .detail-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 3rem 1.5rem;
            text-align: center;
            color: #8a9e94;
            font-size: 0.9rem;
        }

        .detail-empty i {
            font-size: 2.5rem;
            color: #cfe3da;
            margin-bottom: 0.85rem;
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
            border: 1px solid #f5c6c1;
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

        @media (max-width: 800px) {
            .list-pane {
                width: 100%;
                border-right: none;
            }

            .detail-pane {
                position: fixed;
                inset: 0;
                z-index: 50;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }

            .wrap.mobile-view-detail .detail-pane {
                transform: translateX(0);
            }

            .wrap.mobile-view-detail .list-pane {
                display: none;
            }

            .detail-back {
                display: inline-flex;
            }

            .notif-delete {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="wrap" id="wrap">
        <aside class="list-pane">
            <div class="list-header">
                <div class="list-header-top">
                    <h1>Notifications</h1>
                    <span id="notifCountLabel"><?php echo count($notifications); ?> total</span>
                </div>
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        id="searchInput"
                        class="search-input"
                        placeholder="Search notifications..."
                        autocomplete="off">
                </div>
            </div>

            <div class="list-scroll" id="listScroll">
                <?php if (count($notifications) === 0): ?>
                    <div class="empty-list" id="emptyOriginal">
                        <i class="fa-regular fa-bell-slash"></i>
                        No notifications yet
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $i => $n): ?>
                        <div
                            class="notif-row <?php echo $i === 0 ? 'active' : ''; ?> <?php echo $i < 2 ? 'unread' : ''; ?>"
                            data-id="<?php echo htmlspecialchars($n["id"] ?? $i); ?>"
                            data-title="<?php echo htmlspecialchars($n["title"] ?? "Notification"); ?>"
                            data-message="<?php echo htmlspecialchars($n["message"] ?? ""); ?>"
                            data-sent="<?php echo htmlspecialchars($n["sent"] ?? ""); ?>"
                            data-search="<?php echo htmlspecialchars(strtolower(($n["title"] ?? "") . " " . ($n["message"] ?? ""))); ?>">
                            <div class="notif-row-icon">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div class="notif-row-body">
                                <div class="notif-row-top">
                                    <div class="notif-row-title"><?php echo htmlspecialchars($n["title"] ?? "Notification"); ?></div>
                                    <div class="notif-row-time"><?php echo htmlspecialchars(timeAgo($n["sent"] ?? "")); ?></div>
                                </div>
                                <div class="notif-row-preview"><?php echo htmlspecialchars($n["message"] ?? ""); ?></div>
                            </div>
                            <button class="notif-delete" type="button" title="Delete" data-delete-id="<?php echo htmlspecialchars($n["id"] ?? $i); ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="empty-list" id="emptySearch" style="display:none;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    No notification found for <strong id="searchTermLabel"></strong>
                </div>
            </div>
        </aside>

        <section class="detail-pane">
            <div class="detail-header">
                <button class="detail-back" id="detailBack">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="detail-header-text">
                    <div class="detail-title" id="detailTitle">Select a notification</div>
                    <div class="detail-sub" id="detailSub">Choose one from the list to read</div>
                </div>
                <button class="detail-delete" id="detailDelete" title="Delete this notification" style="display:none;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>

            <div class="detail-body" id="detailBody">
                <?php if (count($notifications) === 0): ?>
                    <div class="detail-empty">
                        <i class="fa-regular fa-bell-slash"></i>
                        No notifications to display
                    </div>
                <?php else: ?>
                    <div class="detail-body-inner" id="detailInner">
                        <div class="detail-meta">
                            <div class="detail-avatar"><i class="fa-solid fa-bell"></i></div>
                            <div class="detail-meta-text">
                                <div class="detail-meta-from">TutorLink</div>
                                <div class="detail-meta-date" id="detailDate"></div>
                            </div>
                        </div>
                        <div class="detail-message" id="detailMessage"></div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-icon">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3>Delete notification?</h3>
            <p>This notification will be permanently removed. You can't undo this action.</p>
            <div class="modal-actions">
                <button class="btn" id="cancelDelete">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast">
        <div class="toast-icon" id="toastIcon">!</div>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <script>
        const wrap = document.getElementById("wrap");
        const rows = document.querySelectorAll(".notif-row");
        const detailTitle = document.getElementById("detailTitle");
        const detailSub = document.getElementById("detailSub");
        const detailDate = document.getElementById("detailDate");
        const detailMessage = document.getElementById("detailMessage");
        const detailBack = document.getElementById("detailBack");
        const detailDelete = document.getElementById("detailDelete");
        const searchInput = document.getElementById("searchInput");
        const emptySearch = document.getElementById("emptySearch");
        const searchTermLabel = document.getElementById("searchTermLabel");
        const notifCountLabel = document.getElementById("notifCountLabel");

        const deleteModal = document.getElementById("deleteModal");
        const cancelDelete = document.getElementById("cancelDelete");
        const confirmDelete = document.getElementById("confirmDelete");

        const toast = document.getElementById("toast");
        const toastIcon = document.getElementById("toastIcon");
        const toastMessage = document.getElementById("toastMessage");

        let activeRow = null;
        let pendingDeleteId = null;
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
            toastTimer = setTimeout(() => toast.classList.remove("show"), 3500);
        }

        function formatDate(str) {
            if (!str) return "";
            const d = new Date(str.replace(" ", "T"));
            if (isNaN(d.getTime())) return str;
            return d.toLocaleString(undefined, {
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit"
            });
        }

        function updateCountLabel() {
            const visible = document.querySelectorAll(".notif-row").length;
            notifCountLabel.textContent = visible + " total";
        }

        function resetDetailEmpty() {
            detailTitle.textContent = "Select a notification";
            detailSub.textContent = "Choose one from the list to read";
            detailDate.textContent = "";
            detailMessage.textContent = "";
            detailDelete.style.display = "none";
        }

        function loadNotification(row) {
            if (!row) {
                resetDetailEmpty();
                activeRow = null;
                return;
            }

            activeRow = row;

            document.querySelectorAll(".notif-row").forEach(r => r.classList.remove("active"));
            row.classList.add("active");

            const title = row.dataset.title || "Notification";
            const message = row.dataset.message || "";
            const sent = row.dataset.sent || "";

            detailTitle.textContent = title;
            detailSub.textContent = formatDate(sent) || "TutorLink";
            detailDate.textContent = formatDate(sent) || "";
            detailMessage.textContent = message;
            detailDelete.style.display = "inline-flex";

            if (window.innerWidth <= 800) {
                wrap.classList.add("mobile-view-detail");
            }
        }

        rows.forEach(row => {
            row.addEventListener("click", (e) => {
                if (e.target.closest(".notif-delete")) return;
                loadNotification(row);
            });
        });

        if (rows.length > 0) {
            loadNotification(rows[0]);
        }

        detailBack.addEventListener("click", () => {
            wrap.classList.remove("mobile-view-detail");
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth > 800) {
                wrap.classList.remove("mobile-view-detail");
            }
        });

        searchInput.addEventListener("input", () => {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;
            let firstVisible = null;

            document.querySelectorAll(".notif-row").forEach(row => {
                const haystack = row.dataset.search || "";
                const match = term === "" || haystack.includes(term);
                row.style.display = match ? "" : "none";
                if (match) {
                    visible++;
                    if (!firstVisible) firstVisible = row;
                }
            });

            if (term !== "" && visible === 0) {
                emptySearch.style.display = "block";
                searchTermLabel.textContent = '"' + searchInput.value.trim() + '"';
                resetDetailEmpty();
            } else {
                emptySearch.style.display = "none";
            }

            if (firstVisible && (!activeRow || activeRow.style.display === "none")) {
                loadNotification(firstVisible);
            }

            updateCountLabel();
        });

        function openDeleteModal(id) {
            pendingDeleteId = id;
            deleteModal.classList.add("show");
        }

        function closeDeleteModal() {
            deleteModal.classList.remove("show");
            pendingDeleteId = null;
        }

        document.querySelectorAll(".notif-delete").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                const id = btn.dataset.deleteId;
                if (id) openDeleteModal(id);
            });
        });

        detailDelete.addEventListener("click", () => {
            if (!activeRow) return;
            const id = activeRow.dataset.id;
            if (id) openDeleteModal(id);
        });

        cancelDelete.addEventListener("click", closeDeleteModal);

        deleteModal.addEventListener("click", (e) => {
            if (e.target === deleteModal) closeDeleteModal();
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && deleteModal.classList.contains("show")) {
                closeDeleteModal();
            }
        });

        confirmDelete.addEventListener("click", async () => {
            if (!pendingDeleteId) return;

            confirmDelete.disabled = true;
            confirmDelete.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Deleting...';

            try {
                const res = await fetch("delete_notification", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: pendingDeleteId
                    })
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok && data.success !== false) {
                    const rowToRemove = document.querySelector(
                        '.notif-row[data-id="' + pendingDeleteId + '"]'
                    );
                    if (rowToRemove) {
                        const wasActive = rowToRemove.classList.contains("active");
                        rowToRemove.remove();

                        if (wasActive) {
                            const nextVisible = Array.from(document.querySelectorAll(".notif-row"))
                                .find(r => r.style.display !== "none");
                            if (nextVisible) {
                                loadNotification(nextVisible);
                            } else {
                                resetDetailEmpty();
                            }
                        }
                    }

                    closeDeleteModal();
                    showToast("Notification deleted.", "success");
                    updateCountLabel();

                    if (document.querySelectorAll(".notif-row").length === 0) {
                        if (termSafe()) {
                            emptySearch.style.display = "block";
                            searchTermLabel.textContent = '"' + searchInput.value.trim() + '"';
                        }
                    }
                } else {
                    closeDeleteModal();
                    showToast(data.message || "Could not delete. Try again.", "error");
                }
            } catch (err) {
                closeDeleteModal();
                showToast("Network error. Please try again.", "error");
            } finally {
                confirmDelete.disabled = false;
                confirmDelete.innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
            }
        });

        function termSafe() {
            return searchInput.value.trim() !== "";
        }
    </script>
</body>

</html>