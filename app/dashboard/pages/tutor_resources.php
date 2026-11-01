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
    <title>Resources · TutorLink</title>
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

        .res-meta .tutor-tag {
            display: inline-block;
            font-size: 0.72rem;
            color: #0a4b6e;
            font-weight: 600;
            margin-bottom: 0.15rem;
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
            text-decoration: none;
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
                <h1>Tutor resources</h1>
                <p>Browse materials shared by tutors.</p>
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
                    <div class="detail-title" id="viewTitle">All tutor resources</div>
                    <div class="detail-sub" id="viewSub">Materials shared by your tutors</div>
                </div>
                <div class="header-actions">
                    <input type="text" id="searchInput" class="input-search" placeholder="Search by title or tutor..." style="padding:0.5rem 0.8rem;border:1px solid #cfe3da;border-radius:5px;font-size:0.82rem;outline:none;font-family:inherit;color:#0a2e3f;width:220px;">
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

    <div class="toast" id="toast">
        <div class="toast-icon" id="toastIcon">!</div>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <script>
        const API = "insert.php";
        const RESOURCES_TABLE = "user_b6a3d990_rea_lecturer_resource";
        const USERS_TABLE = "user_b6a3d990_rea_users";

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
        const searchInput = document.getElementById("searchInput");
        const refreshBtn = document.getElementById("refreshBtn");

        const toast = document.getElementById("toast");
        const toastIcon = document.getElementById("toastIcon");
        const toastMessage = document.getElementById("toastMessage");

        let resources = [];
        let tutorNames = new Map();
        let activeFilter = "all";
        let searchTerm = "";
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

        function tutorNameFor(email) {
            if (!email) return "Tutor";
            return tutorNames.get(String(email).trim().toLowerCase()) || email;
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
                viewTitle.textContent = "All tutor resources";
                viewSub.textContent = "Materials shared by your tutors";
            } else {
                const meta = TYPE_META[activeFilter];
                viewTitle.textContent = meta.label + " resources";
                viewSub.textContent = "Filtered by " + meta.label.toLowerCase();
            }
        }

        function renderList() {
            const term = searchTerm.trim().toLowerCase();

            const list = resources
                .filter(r => activeFilter === "all" || r.type === activeFilter)
                .filter(r => {
                    if (!term) return true;
                    const tutor = tutorNameFor(r.email);
                    const hay = (r.name + " " + (r.description || "") + " " + tutor).toLowerCase();
                    return hay.includes(term);
                })
                .sort((a, b) => new Date(b.created_at || b.sent) - new Date(a.created_at || a.sent));

            resList.innerHTML = "";

            if (list.length === 0) {
                const empty = document.createElement("div");
                empty.className = "empty";
                empty.innerHTML =
                    '<i class="fa-solid fa-folder-open"></i>' +
                    '<strong>No resources found</strong>' +
                    (term ? 'Try a different search term.' : 'Tutors haven\'t uploaded anything yet.');
                resList.appendChild(empty);
                return;
            }

            list.forEach(r => {
                const meta = TYPE_META[r.type] || TYPE_META.other;
                const tutor = tutorNameFor(r.email);

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
                    '<span class="tutor-tag"><i class="fa-solid fa-user"></i> ' + escapeHtml(tutor) + '</span><br>' +
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

                card.appendChild(icon);
                card.appendChild(info);
                card.appendChild(actions);
                resList.appendChild(card);
            });
        }

        async function loadData() {
            const [allResources, users] = await Promise.all([
                apiFilter(RESOURCES_TABLE, {}),
                apiFilter(USERS_TABLE, {})
            ]);

            tutorNames = new Map();
            users.forEach(u => {
                if (u.email) {
                    tutorNames.set(String(u.email).trim().toLowerCase(), u.fullname || u.email);
                }
            });

            resources = allResources.map(r => ({
                id: r.id,
                email: r.email,
                name: r.name,
                type: r.type || "other",
                url: r.url,
                description: r.description || "",
                created_at: r.created_at || r.sent
            }));

            updateStats();
            renderList();
        }

        filterButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                activeFilter = btn.dataset.filter;
                filterButtons.forEach(b => b.classList.toggle("active", b.dataset.filter === activeFilter));
                updateHeaderForFilter();
                renderList();
            });
        });

        searchInput.addEventListener("input", () => {
            searchTerm = searchInput.value;
            renderList();
        });

        refreshBtn.addEventListener("click", async () => {
            await loadData();
            showToast("Resources refreshed.", "success");
        });

        updateHeaderForFilter();
        loadData();
    </script>
</body>

</html>