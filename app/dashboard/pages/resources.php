<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$fullname = $_SESSION["fullname"] ?? "User";
$email    = $_SESSION["email"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Resources · TutorLink</title>
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
            width: 320px;
            border-right: 1px solid #e4f0ea;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            background: #ffffff;
        }

        .list-header {
            padding: 1rem 1.25rem 0.85rem;
            border-bottom: 1px solid #e4f0ea;
        }

        .list-header h1 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a4b6e;
        }

        .list-header p {
            font-size: 0.78rem;
            color: #6b8b7e;
            margin-top: 0.25rem;
            line-height: 1.4;
        }

        .list-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 1.25rem 1.25rem;
        }

        .side-section {
            margin-bottom: 1.5rem;
        }

        .side-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8a9e94;
            margin-bottom: 0.6rem;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f7f4;
            font-size: 0.82rem;
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-row span:first-child {
            color: #6b8b7e;
        }

        .stat-row span:last-child {
            color: #0a4b6e;
            font-weight: 700;
        }

        .filter-list {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .filter-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 0.75rem;
            border-radius: 5px;
            border: 1px solid transparent;
            background: transparent;
            color: #0a2e3f;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
            transition: all 0.15s;
        }

        .filter-item:hover {
            background: #f6fbf8;
        }

        .filter-item.active {
            background: #f0f7f4;
            border-color: #cfe3da;
            color: #0b6e4f;
            font-weight: 700;
        }

        .filter-item-label {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .filter-item-label i {
            width: 16px;
            text-align: center;
            color: #0a4b6e;
        }

        .filter-item.active .filter-item-label i {
            color: #0b6e4f;
        }

        .filter-count {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 8px;
            background: #f0f7f4;
            color: #0b6e4f;
        }

        .filter-item.active .filter-count {
            background: #0b6e4f;
            color: #ffffff;
        }

        .detail-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            min-width: 0;
        }

        .detail-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e4f0ea;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            justify-content: space-between;
        }

        .detail-header-text {
            min-width: 0;
        }

        .detail-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0a4b6e;
            margin-bottom: 0.1rem;
        }

        .detail-sub {
            font-size: 0.72rem;
            color: #6b8b7e;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.95rem;
            border-radius: 5px;
            border: 1.5px solid #0b6e4f;
            background: #ffffff;
            color: #0b6e4f;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s;
        }

        .btn-create:hover {
            background: #f0f7f4;
        }

        .header-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            background: #ffffff;
            color: #0a4b6e;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            transition: all 0.15s;
        }

        .header-icon-btn:hover {
            background: #f0f7f4;
            border-color: #0b6e4f;
            color: #0b6e4f;
        }

        .res-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.5rem;
        }

        .res-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .res-card {
            border: 1px solid #e4f0ea;
            border-radius: 5px;
            padding: 1rem 1.15rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            transition: border-color 0.15s;
        }

        .res-card:hover {
            border-color: #cfe3da;
        }

        .res-icon {
            width: 46px;
            height: 46px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #ffffff;
            flex-shrink: 0;
        }

        .res-icon.pdf {
            background: linear-gradient(135deg, #c0392b, #e74c3c);
        }

        .res-icon.video {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
        }

        .res-icon.link {
            background: linear-gradient(135deg, #0a4b6e, #0b6e4f);
        }

        .res-icon.doc {
            background: linear-gradient(135deg, #2980b9, #3498db);
        }

        .res-icon.other {
            background: linear-gradient(135deg, #7f8c8d, #95a5a6);
        }

        .res-info {
            flex: 1;
            min-width: 200px;
        }

        .res-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0a4b6e;
            margin-bottom: 0.25rem;
        }

        .res-meta {
            font-size: 0.8rem;
            color: #6b8b7e;
            line-height: 1.5;
        }

        .res-meta .badge {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            border: 1px solid #cfe3da;
            background: #f6fbf8;
            color: #0b6e4f;
            margin-right: 0.35rem;
        }

        .res-meta a {
            color: #0b6e4f;
            font-weight: 600;
            text-decoration: none;
        }

        .res-meta a:hover {
            text-decoration: underline;
        }

        .res-actions {
            display: flex;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.8rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid #cfe3da;
            background: #ffffff;
            color: #0a4b6e;
            font-family: inherit;
            transition: all 0.15s;
        }

        .btn:hover {
            border-color: #0b6e4f;
            color: #0b6e4f;
        }

        .btn-open {
            border-color: #0b6e4f;
            background: #0b6e4f;
            color: #ffffff;
        }

        .btn-open:hover {
            background: #0a5a40;
            color: #ffffff;
        }

        .btn-delete {
            border-color: #c0392b;
            background: #ffffff;
            color: #c0392b;
        }

        .btn-delete:hover {
            background: #fdecea;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #8a9e94;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .empty i {
            display: block;
            font-size: 2.25rem;
            color: #cfe3da;
            margin-bottom: 0.75rem;
        }

        .empty strong {
            color: #0a4b6e;
            font-weight: 600;
            display: block;
            margin-bottom: 0.35rem;
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
            padding: 1rem;
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
            max-width: 460px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
            box-shadow: 0 25px 50px -20px rgba(11, 110, 79, 0.3);
            transform: translateY(-10px);
            transition: transform 0.2s ease;
        }

        .modal-overlay.show .modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .modal-icon {
            width: 42px;
            height: 42px;
            border-radius: 5px;
            border: 1px solid #cfe3da;
            color: #0b6e4f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .modal-header h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a4b6e;
        }

        .modal-header p {
            font-size: 0.78rem;
            color: #6b8b7e;
            margin-top: 0.15rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #0a4b6e;
            margin-bottom: 0.35rem;
        }

        .input-field,
        .select-field {
            width: 100%;
            padding: 0.7rem 0.85rem;
            font-size: 0.88rem;
            background: #ffffff;
            border: 1.5px solid #cfe3da;
            border-radius: 5px;
            outline: none;
            color: #0a2e3f;
            font-family: inherit;
        }

        .input-field:focus,
        .select-field:focus {
            border-color: #0b6e4f;
            box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
        }

        .input-field.error,
        .select-field.error {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.08);
        }

        .error-message {
            display: none;
            color: #c0392b;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.3rem;
        }

        .error-message.show {
            display: block;
        }

        .modal-actions {
            display: flex;
            gap: 0.6rem;
            justify-content: flex-end;
            margin-top: 1.25rem;
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
            .wrap {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
            }

            body {
                overflow: auto;
            }

            .list-pane {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e4f0ea;
            }

            .list-scroll {
                max-height: none;
            }

            .detail-pane {
                min-height: 0;
            }

            .res-scroll {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .detail-header {
                padding: 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .res-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .toast {
                top: auto;
                bottom: 1rem;
                left: 1rem;
                right: 1rem;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <aside class="list-pane">
            <div class="list-header">
                <h1>My resources</h1>
                <p>Upload and manage the materials you share with students.</p>
            </div>

            <div class="list-scroll">
                <div class="side-section">
                    <div class="side-title">Overview</div>
                    <div class="stat-row">
                        <span>Total resources</span>
                        <span id="statTotal">0</span>
                    </div>
                    <div class="stat-row">
                        <span>PDF</span>
                        <span id="statPdf">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Videos</span>
                        <span id="statVideo">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Links</span>
                        <span id="statLink">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Documents</span>
                        <span id="statDoc">0</span>
                    </div>
                </div>

                <div class="side-section">
                    <div class="side-title">Filter by type</div>
                    <div class="filter-list" id="filterList">
                        <button class="filter-item active" data-filter="all">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-list"></i>
                                All resources
                            </span>
                            <span class="filter-count" id="countAll">0</span>
                        </button>

                        <button class="filter-item" data-filter="pdf">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-file-pdf"></i>
                                PDF
                            </span>
                            <span class="filter-count" id="countPdf">0</span>
                        </button>

                        <button class="filter-item" data-filter="video">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-video"></i>
                                Video
                            </span>
                            <span class="filter-count" id="countVideo">0</span>
                        </button>

                        <button class="filter-item" data-filter="link">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-link"></i>
                                Link
                            </span>
                            <span class="filter-count" id="countLink">0</span>
                        </button>

                        <button class="filter-item" data-filter="doc">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-file-word"></i>
                                Document
                            </span>
                            <span class="filter-count" id="countDoc">0</span>
                        </button>

                        <button class="filter-item" data-filter="other">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-file"></i>
                                Other
                            </span>
                            <span class="filter-count" id="countOther">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <section class="detail-pane">
            <div class="detail-header">
                <div class="detail-header-text">
                    <div class="detail-title" id="viewTitle">All resources</div>
                    <div class="detail-sub" id="viewSub">Materials you've shared with students</div>
                </div>
                <div class="header-actions">
                    <button class="btn-create" id="addResourceBtn">
                        <i class="fa-solid fa-plus"></i>
                        Add resource
                    </button>
                    <button class="header-icon-btn" id="refreshBtn" title="Refresh">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>
            </div>

            <div class="res-scroll">
                <div class="res-list" id="resList"></div>
            </div>
        </section>
    </div>

    <div class="modal-overlay" id="resourceModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-icon" id="resourceModalIcon">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div>
                    <h3 id="resourceModalTitle">Add resource</h3>
                    <p id="resourceModalSub">Share a link to a PDF, video, document or webpage.</p>
                </div>
            </div>

            <div class="form-group">
                <label class="input-label" for="resName">Resource name</label>
                <input class="input-field" type="text" id="resName" placeholder="e.g. Calculus Chapter 3 notes">
                <div class="error-message" id="resNameError">Please enter a resource name</div>
            </div>

            <div class="form-group">
                <label class="input-label" for="resType">Type</label>
                <select class="select-field" id="resType">
                    <option value="">Select a type...</option>
                    <option value="pdf">PDF</option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                    <option value="doc">Document</option>
                    <option value="other">Other</option>
                </select>
                <div class="error-message" id="resTypeError">Please pick a type</div>
            </div>

            <div class="form-group">
                <label class="input-label" for="resUrl">Resource link</label>
                <input class="input-field" type="url" id="resUrl" placeholder="https://drive.google.com/...">
                <div class="error-message" id="resUrlError">Please enter a valid http(s) link</div>
            </div>

            <div class="form-group">
                <label class="input-label" for="resDesc">Description (optional)</label>
                <input class="input-field" type="text" id="resDesc" placeholder="Short description for students">
            </div>

            <div class="modal-actions">
                <button class="btn" id="cancelResource">Cancel</button>
                <button class="btn btn-open" id="confirmResource">
                    <i class="fa-solid fa-check"></i>
                    Save resource
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-icon" style="color:#c0392b;border-color:#f5c6c1;">
                    <i class="fa-solid fa-trash"></i>
                </div>
                <div>
                    <h3>Delete resource?</h3>
                    <p id="deleteMessage">This cannot be undone.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn" id="cancelDelete">Cancel</button>
                <button class="btn btn-delete" id="confirmDelete">
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
        const API = "insert.php";
        const RESOURCES_TABLE = "user_b6a3d990_rea_lecturer_resource";
        const CURRENT_USER = {
            name: <?php echo json_encode($fullname); ?>,
            email: <?php echo json_encode($email); ?>
        };

        const TYPE_META = {
            pdf: { icon: "fa-file-pdf", label: "PDF", color: "pdf" },
            video: { icon: "fa-video", label: "Video", color: "video" },
            link: { icon: "fa-link", label: "Link", color: "link" },
            doc: { icon: "fa-file-word", label: "Document", color: "doc" },
            other: { icon: "fa-file", label: "Other", color: "other" }
        };

        const resList = document.getElementById("resList");
        const filterButtons = document.querySelectorAll(".filter-item");

        const statTotal = document.getElementById("statTotal");
        const statPdf = document.getElementById("statPdf");
        const statVideo = document.getElementById("statVideo");
        const statLink = document.getElementById("statLink");
        const statDoc = document.getElementById("statDoc");

        const countAll = document.getElementById("countAll");
        const countPdf = document.getElementById("countPdf");
        const countVideo = document.getElementById("countVideo");
        const countLink = document.getElementById("countLink");
        const countDoc = document.getElementById("countDoc");
        const countOther = document.getElementById("countOther");

        const viewTitle = document.getElementById("viewTitle");
        const viewSub = document.getElementById("viewSub");
        const addResourceBtn = document.getElementById("addResourceBtn");
        const refreshBtn = document.getElementById("refreshBtn");

        const resourceModal = document.getElementById("resourceModal");
        const resourceModalTitle = document.getElementById("resourceModalTitle");
        const resourceModalSub = document.getElementById("resourceModalSub");
        const resourceModalIcon = document.getElementById("resourceModalIcon");
        const resName = document.getElementById("resName");
        const resType = document.getElementById("resType");
        const resUrl = document.getElementById("resUrl");
        const resDesc = document.getElementById("resDesc");
        const resNameError = document.getElementById("resNameError");
        const resTypeError = document.getElementById("resTypeError");
        const resUrlError = document.getElementById("resUrlError");
        const cancelResource = document.getElementById("cancelResource");
        const confirmResource = document.getElementById("confirmResource");

        const deleteModal = document.getElementById("deleteModal");
        const deleteMessage = document.getElementById("deleteMessage");
        const cancelDelete = document.getElementById("cancelDelete");
        const confirmDelete = document.getElementById("confirmDelete");

        const toast = document.getElementById("toast");
        const toastIcon = document.getElementById("toastIcon");
        const toastMessage = document.getElementById("toastMessage");

        let resources = [];
        let activeFilter = "all";
        let editingId = null;
        let deletingId = null;
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

        async function api(payload) {
            const body = new URLSearchParams();
            Object.entries(payload).forEach(([k, v]) => body.append(k, v ?? ""));
            const res = await fetch(API, { method: "POST", body });
            const text = await res.text();
            try { return JSON.parse(text); } catch (e) { return { raw: text }; }
        }

        async function apiFilter(table, where) {
            const hasWhere = where && Object.keys(where).length > 0;
            const data = await api(
                hasWhere
                    ? { type: "filter", table: table, where: JSON.stringify(where) }
                    : { type: "get", table: table }
            );
            return Array.isArray(data) ? data : [];
        }

        async function apiDelete(table, where) {
            return await api({
                type: "delete",
                table: table,
                where: JSON.stringify(where || {})
            });
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
        }

        function formatDate(iso) {
            if (!iso) return "";
            const d = new Date(iso.replace(" ", "T"));
            if (isNaN(d)) return iso;
            return d.toLocaleDateString(undefined, { month: "short", day: "numeric", year: "numeric" });
        }

        function updateStats() {
            const total = resources.length;
            const pdf = resources.filter(r => r.type === "pdf").length;
            const video = resources.filter(r => r.type === "video").length;
            const link = resources.filter(r => r.type === "link").length;
            const doc = resources.filter(r => r.type === "doc").length;
            const other = resources.filter(r => r.type === "other").length;

            statTotal.textContent = total;
            statPdf.textContent = pdf;
            statVideo.textContent = video;
            statLink.textContent = link;
            statDoc.textContent = doc;

            countAll.textContent = total;
            countPdf.textContent = pdf;
            countVideo.textContent = video;
            countLink.textContent = link;
            countDoc.textContent = doc;
            countOther.textContent = other;
        }

        function updateHeaderForFilter() {
            if (activeFilter === "all") {
                viewTitle.textContent = "All resources";
                viewSub.textContent = "Materials you've shared with students";
            } else {
                const meta = TYPE_META[activeFilter];
                viewTitle.textContent = meta.label + " resources";
                viewSub.textContent = "Filtered by " + meta.label.toLowerCase();
            }
        }

        function renderList() {
            const list = resources
                .filter(r => activeFilter === "all" || r.type === activeFilter)
                .sort((a, b) => new Date(b.created_at || b.sent) - new Date(a.created_at || a.sent));

            resList.innerHTML = "";

            if (list.length === 0) {
                const empty = document.createElement("div");
                empty.className = "empty";
                empty.innerHTML =
                    '<i class="fa-solid fa-file-circle-plus"></i>' +
                    '<strong>No resources yet</strong>' +
                    'Click "Add resource" to share your first material.';
                resList.appendChild(empty);
                return;
            }

            list.forEach(r => {
                const meta = TYPE_META[r.type] || TYPE_META.other;

                const card = document.createElement("div");
                card.className = "res-card";

                const icon = document.createElement("div");
                icon.className = "res-icon " + meta.color;
                icon.innerHTML = '<i class="fa-solid ' + meta.icon + '"></i>';

                const info = document.createElement("div");
                info.className = "res-info";

                const title = document.createElement("div");
                title.className = "res-title";
                title.textContent = r.name || "Untitled";

                const metaEl = document.createElement("div");
                metaEl.className = "res-meta";
                metaEl.innerHTML =
                    '<span class="badge">' + meta.label + '</span>' +
                    (r.description ? escapeHtml(r.description) + '<br>' : '') +
                    '<a href="' + escapeHtml(r.url) + '" target="_blank" rel="noopener noreferrer">' +
                    escapeHtml(r.url) +
                    '</a><br>' +
                    '<span style="font-size:0.72rem;color:#8a9e94;">Added ' + formatDate(r.created_at || r.sent) + '</span>';

                info.appendChild(title);
                info.appendChild(metaEl);

                const actions = document.createElement("div");
                actions.className = "res-actions";

                const openBtn = document.createElement("a");
                openBtn.className = "btn btn-open";
                openBtn.href = r.url;
                openBtn.target = "_blank";
                openBtn.rel = "noopener noreferrer";
                openBtn.innerHTML = '<i class="fa-solid fa-arrow-up-right-from-square"></i> Open';
                actions.appendChild(openBtn);

                const editBtn = document.createElement("button");
                editBtn.className = "btn";
                editBtn.innerHTML = '<i class="fa-solid fa-pen"></i>';
                editBtn.title = "Edit";
                editBtn.addEventListener("click", () => openEditModal(r));
                actions.appendChild(editBtn);

                const delBtn = document.createElement("button");
                delBtn.className = "btn btn-delete";
                delBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
                delBtn.title = "Delete";
                delBtn.addEventListener("click", () => openDeleteModal(r));
                actions.appendChild(delBtn);

                card.appendChild(icon);
                card.appendChild(info);
                card.appendChild(actions);
                resList.appendChild(card);
            });
        }

        function openCreateModal() {
            editingId = null;
            resourceModalTitle.textContent = "Add resource";
            resourceModalSub.textContent = "Share a link to a PDF, video, document or webpage.";
            resourceModalIcon.innerHTML = '<i class="fa-solid fa-file-circle-plus"></i>';

            resName.value = "";
            resType.value = "";
            resUrl.value = "";
            resDesc.value = "";

            [resName, resType, resUrl].forEach(i => i.classList.remove("error"));
            [resNameError, resTypeError, resUrlError].forEach(e => e.classList.remove("show"));

            resourceModal.classList.add("show");
            setTimeout(() => resName.focus(), 50);
        }

        function openEditModal(r) {
            editingId = r.id;
            resourceModalTitle.textContent = "Edit resource";
            resourceModalSub.textContent = "Update the details of this resource.";
            resourceModalIcon.innerHTML = '<i class="fa-solid fa-pen"></i>';

            resName.value = r.name || "";
            resType.value = r.type || "";
            resUrl.value = r.url || "";
            resDesc.value = r.description || "";

            [resName, resType, resUrl].forEach(i => i.classList.remove("error"));
            [resNameError, resTypeError, resUrlError].forEach(e => e.classList.remove("show"));

            resourceModal.classList.add("show");
            setTimeout(() => resName.focus(), 50);
        }

        function closeResourceModal() {
            resourceModal.classList.remove("show");
            editingId = null;
        }

        function validateResource() {
            let valid = true;
            [resName, resType, resUrl].forEach(i => i.classList.remove("error"));
            [resNameError, resTypeError, resUrlError].forEach(e => e.classList.remove("show"));

            if (!resName.value.trim()) {
                resName.classList.add("error");
                resNameError.classList.add("show");
                valid = false;
            }

            if (!resType.value) {
                resType.classList.add("error");
                resTypeError.classList.add("show");
                valid = false;
            }

            const urlValue = resUrl.value.trim();
            if (!urlValue || !/^https?:\/\/.+/i.test(urlValue)) {
                resUrl.classList.add("error");
                resUrlError.classList.add("show");
                valid = false;
            }

            return valid;
        }

        function openDeleteModal(r) {
            deletingId = r.id;
            deleteMessage.textContent = 'Delete "' + (r.name || "this resource") + '"? This cannot be undone.';
            deleteModal.classList.add("show");
        }

        function closeDeleteModal() {
            deleteModal.classList.remove("show");
            deletingId = null;
        }

        addResourceBtn.addEventListener("click", openCreateModal);
        cancelResource.addEventListener("click", closeResourceModal);

        resourceModal.addEventListener("click", (e) => {
            if (e.target === resourceModal) closeResourceModal();
        });

        cancelDelete.addEventListener("click", closeDeleteModal);

        deleteModal.addEventListener("click", (e) => {
            if (e.target === deleteModal) closeDeleteModal();
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                if (resourceModal.classList.contains("show")) closeResourceModal();
                if (deleteModal.classList.contains("show")) closeDeleteModal();
            }
        });

        confirmResource.addEventListener("click", async () => {
            if (!validateResource()) return;

            const payload = {
                email: CURRENT_USER.email,
                name: resName.value.trim(),
                type: resType.value,
                url: resUrl.value.trim(),
                description: resDesc.value.trim()
            };

            confirmResource.disabled = true;

            if (editingId) {
                await api({
                    type: "update_resource",
                    id: editingId,
                    name: payload.name,
                    resource_type: payload.type,
                    url: payload.url,
                    description: payload.description
                });
            } else {
                await api({
                    type: "insert_resource_lecturer",
                    email: payload.email,
                    name: payload.name,
                    resource_type: payload.type,
                    url: payload.url,
                    description: payload.description
                });
            }

            confirmResource.disabled = false;
            closeResourceModal();
            await loadResources();
            showToast(editingId ? "Resource updated." : "Resource added.", "success");
        });

        confirmDelete.addEventListener("click", async () => {
            if (!deletingId) return;

            confirmDelete.disabled = true;

            await apiDelete(RESOURCES_TABLE, { id: deletingId });

            resources = resources.filter(r => String(r.id) !== String(deletingId));

            confirmDelete.disabled = false;
            closeDeleteModal();
            updateStats();
            renderList();
            showToast("Resource deleted.", "success");
        });

        filterButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                activeFilter = btn.dataset.filter;
                filterButtons.forEach(b => b.classList.toggle("active", b.dataset.filter === activeFilter));
                updateHeaderForFilter();
                renderList();
            });
        });

        refreshBtn.addEventListener("click", async () => {
            await loadResources();
            showToast("Resources refreshed.", "success");
        });

        [resName, resType, resUrl].forEach((input, i) => {
            input.addEventListener("input", () => {
                const errs = [resNameError, resTypeError, resUrlError];
                if (input.classList.contains("error") && input.value.trim()) {
                    input.classList.remove("error");
                    errs[i].classList.remove("show");
                }
            });
        });

        async function loadResources() {
            const all = await apiFilter(RESOURCES_TABLE, {});
            resources = all
                .filter(r => String(r.email) === String(CURRENT_USER.email))
                .map(r => ({
                    id: r.id,
                    name: r.name,
                    type: r.type || "other",
                    url: r.url,
                    description: r.description || "",
                    created_at: r.created_at || r.sent
                }));

            updateStats();
            renderList();
        }

        updateHeaderForFilter();
        loadResources();
    </script>
</body>

</html>