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
    <title>Profit · TutorLink</title>
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

        .stat-row.total span:last-child {
            color: #0b6e4f;
            font-size: 1rem;
        }

        .stat-row.loss span:last-child {
            color: #c0392b;
        }

        .stat-row.gross span:last-child {
            color: #0a4b6e;
            font-size: 1rem;
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

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
            padding: 1rem 1.5rem 0;
        }

        .summary-card {
            border: 1px solid #e4f0ea;
            border-radius: 5px;
            padding: 0.9rem 1rem;
        }

        .summary-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b8b7e;
            margin-bottom: 0.35rem;
        }

        .summary-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0a4b6e;
        }

        .summary-value.profit {
            color: #0b6e4f;
        }

        .summary-value.loss {
            color: #c0392b;
        }

        .bk-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 1.5rem 1.25rem;
        }

        .bk-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .bk-card {
            border: 1px solid #e4f0ea;
            border-radius: 5px;
            padding: 0.9rem 1.05rem;
            display: flex;
            gap: 0.9rem;
            align-items: center;
            flex-wrap: wrap;
            transition: border-color 0.15s;
        }

        .bk-card:hover {
            border-color: #cfe3da;
        }

        .bk-avatar {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: #ffffff;
            flex-shrink: 0;
        }

        .bk-info {
            flex: 1;
            min-width: 180px;
        }

        .bk-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0a4b6e;
            margin-bottom: 0.15rem;
        }

        .bk-meta {
            font-size: 0.78rem;
            color: #6b8b7e;
            line-height: 1.5;
        }

        .bk-meta strong {
            color: #0a2e3f;
            font-weight: 600;
        }

        .bk-side {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.35rem;
            flex-shrink: 0;
        }

        .bk-amount {
            font-size: 1rem;
            font-weight: 700;
        }

        .bk-amount.profit {
            color: #0b6e4f;
        }

        .bk-amount.loss {
            color: #c0392b;
        }

        .bk-amount.pending {
            color: #d39e00;
        }

        .bk-status {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 0.25rem 0.55rem;
            border-radius: 5px;
            border: 1px solid #e4f0ea;
        }

        .bk-status.pending {
            color: #d39e00;
            border-color: #f0e0b8;
            background: #fef9ec;
        }

        .bk-status.accepted {
            color: #0b6e4f;
            border-color: #cfe3da;
            background: #f6fbf8;
        }

        .bk-status.rejected {
            color: #c0392b;
            border-color: #f5c6c1;
            background: #fdecea;
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

            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .bk-scroll {
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

            .summary-cards {
                grid-template-columns: 1fr;
                padding: 1rem 1rem 0;
            }

            .bk-side {
                align-items: flex-start;
                flex-direction: row;
                gap: 0.75rem;
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
                <h1>Profit</h1>
                <p>Track your earnings from approved sessions and losses from rejected ones.</p>
            </div>

            <div class="list-scroll">
                <div class="side-section">
                    <div class="side-title">Overview</div>
                    <div class="stat-row gross">
                        <span>Gross profit</span>
                        <span id="statGross">R 0.00</span>
                    </div>
                    <div class="stat-row loss">
                        <span>Lost profit</span>
                        <span id="statLost">-R 0.00</span>
                    </div>
                    <div class="stat-row total">
                        <span>Net profit</span>
                        <span id="statNet">R 0.00</span>
                    </div>
                </div>

                <div class="side-section">
                    <div class="side-title">Bookings</div>
                    <div class="stat-row">
                        <span>Total</span>
                        <span id="statTotal">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Accepted</span>
                        <span id="statAccepted">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Pending</span>
                        <span id="statPending">0</span>
                    </div>
                    <div class="stat-row">
                        <span>Rejected</span>
                        <span id="statRejected">0</span>
                    </div>
                </div>

                <div class="side-section">
                    <div class="side-title">Filter</div>
                    <div class="filter-list" id="filterList">
                        <button class="filter-item active" data-filter="all">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-list"></i>
                                All bookings
                            </span>
                            <span class="filter-count" id="countAll">0</span>
                        </button>

                        <button class="filter-item" data-filter="accepted">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-circle-check"></i>
                                Accepted
                            </span>
                            <span class="filter-count" id="countAccepted">0</span>
                        </button>

                        <button class="filter-item" data-filter="pending">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-hourglass-half"></i>
                                Pending
                            </span>
                            <span class="filter-count" id="countPending">0</span>
                        </button>

                        <button class="filter-item" data-filter="rejected">
                            <span class="filter-item-label">
                                <i class="fa-solid fa-circle-xmark"></i>
                                Rejected
                            </span>
                            <span class="filter-count" id="countRejected">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <section class="detail-pane">
            <div class="detail-header">
                <div class="detail-header-text">
                    <div class="detail-title" id="viewTitle">All bookings</div>
                    <div class="detail-sub" id="viewSub">Every session booked with you</div>
                </div>
                <div class="header-actions">
                    <button class="header-icon-btn" id="refreshBtn" title="Refresh">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>
            </div>

            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-label">Gross profit</div>
                    <div class="summary-value profit" id="sumProfit">R 0.00</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Lost profit</div>
                    <div class="summary-value loss" id="sumLoss">R 0.00</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Net profit</div>
                    <div class="summary-value" id="sumNet">R 0.00</div>
                </div>
            </div>

            <div class="bk-scroll">
                <div class="bk-list" id="bkList"></div>
            </div>
        </section>
    </div>

    <div class="toast" id="toast">
        <div class="toast-icon" id="toastIcon">!</div>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <script>
        const API = "insert.php";
        const BOOKINGS_TABLE = "user_b6a3d990_rea_bookings";

        const CURRENT_USER = {
            name: <?php echo json_encode($fullname); ?>,
            email: <?php echo json_encode($email); ?>
        };

        const DAYS_NAMES = {
            0: "Sunday",
            1: "Monday",
            2: "Tuesday",
            3: "Wednesday",
            4: "Thursday",
            5: "Friday",
            6: "Saturday"
        };

        const bkList = document.getElementById("bkList");
        const filterButtons = document.querySelectorAll(".filter-item");

        const statGross = document.getElementById("statGross");
        const statLost = document.getElementById("statLost");
        const statNet = document.getElementById("statNet");

        const statTotal = document.getElementById("statTotal");
        const statAccepted = document.getElementById("statAccepted");
        const statPending = document.getElementById("statPending");
        const statRejected = document.getElementById("statRejected");

        const countAll = document.getElementById("countAll");
        const countAccepted = document.getElementById("countAccepted");
        const countPending = document.getElementById("countPending");
        const countRejected = document.getElementById("countRejected");

        const viewTitle = document.getElementById("viewTitle");
        const viewSub = document.getElementById("viewSub");
        const refreshBtn = document.getElementById("refreshBtn");

        const sumProfit = document.getElementById("sumProfit");
        const sumLoss = document.getElementById("sumLoss");
        const sumNet = document.getElementById("sumNet");

        const toast = document.getElementById("toast");
        const toastIcon = document.getElementById("toastIcon");
        const toastMessage = document.getElementById("toastMessage");

        let bookings = [];
        let activeFilter = "all";
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

        function initials(text) {
            if (!text) return "S";
            const parts = text.trim().split(/\s+/);
            if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
            return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
        }

        function colorFromString(str) {
            const colors = [
                "linear-gradient(135deg, #0a4b6e, #0b6e4f)",
                "linear-gradient(135deg, #1668a8, #16a97a)",
                "linear-gradient(135deg, #0b6e4f, #1668a8)",
                "linear-gradient(135deg, #0a4b6e, #16a97a)"
            ];
            let hash = 0;
            for (let i = 0; i < str.length; i++) hash = str.charCodeAt(i) + ((hash << 5) - hash);
            return colors[Math.abs(hash) % colors.length];
        }

        function addHour(time) {
            if (!time) return "";
            const [h, m] = time.split(":").map(Number);
            const next = (h + 1) % 24;
            return String(next).padStart(2, "0") + ":" + String(m).padStart(2, "0");
        }

        function formatMoney(value) {
            const n = Number(value);
            if (isNaN(n)) return "R 0.00";
            return "R " + n.toFixed(2);
        }

        function formatDate(iso) {
            if (!iso) return "";
            const d = new Date(iso.replace(" ", "T"));
            if (isNaN(d)) return iso;
            return d.toLocaleDateString(undefined, { month: "short", day: "numeric", year: "numeric" });
        }

        function isProfitStatus(status) {
            return status === "accepted";
        }

        function isLossStatus(status) {
            return status === "rejected";
        }

        function priceOf(b) {
            const n = Number(b.price);
            return isNaN(n) || n < 0 ? 0 : n;
        }

        function updateTotals() {
            let gross = 0;
            let lost = 0;
            let accepted = 0;
            let pending = 0;
            let rejected = 0;

            bookings.forEach(b => {
                const status = b.status || "pending";
                if (status === "accepted") {
                    accepted++;
                    gross += priceOf(b);
                } else if (status === "rejected") {
                    rejected++;
                    lost += priceOf(b);
                } else {
                    pending++;
                }
            });

            const net = gross - lost;

            statGross.textContent = formatMoney(gross);
            statLost.textContent = "-" + formatMoney(lost);
            statNet.textContent = formatMoney(net);

            sumProfit.textContent = formatMoney(gross);
            sumLoss.textContent = formatMoney(lost);
            sumNet.textContent = formatMoney(net);
            sumNet.className = "summary-value " + (net < 0 ? "loss" : "profit");

            statTotal.textContent = bookings.length;
            statAccepted.textContent = accepted;
            statPending.textContent = pending;
            statRejected.textContent = rejected;

            countAll.textContent = bookings.length;
            countAccepted.textContent = accepted;
            countPending.textContent = pending;
            countRejected.textContent = rejected;
        }

        function updateHeaderForFilter() {
            if (activeFilter === "all") {
                viewTitle.textContent = "All bookings";
                viewSub.textContent = "Every session booked with you";
            } else if (activeFilter === "accepted") {
                viewTitle.textContent = "Accepted bookings";
                viewSub.textContent = "Counted as profit";
            } else if (activeFilter === "pending") {
                viewTitle.textContent = "Pending bookings";
                viewSub.textContent = "Awaiting your decision";
            } else {
                viewTitle.textContent = "Rejected bookings";
                viewSub.textContent = "Counted as loss";
            }
        }

        function renderList() {
            const list = bookings
                .filter(b => activeFilter === "all" || (b.status || "pending") === activeFilter)
                .sort((a, b) => new Date(b.sent) - new Date(a.sent));

            bkList.innerHTML = "";

            if (list.length === 0) {
                const empty = document.createElement("div");
                empty.className = "empty";
                empty.innerHTML =
                    '<i class="fa-solid fa-chart-line"></i>' +
                    '<strong>No bookings here</strong>' +
                    'Bookings will appear once students book sessions with you.';
                bkList.appendChild(empty);
                return;
            }

            list.forEach(b => {
                const status = b.status || "pending";
                const price = priceOf(b);

                const card = document.createElement("div");
                card.className = "bk-card";

                const avatar = document.createElement("div");
                avatar.className = "bk-avatar";
                avatar.style.background = colorFromString(b.student_name || b.student_email);
                avatar.textContent = initials(b.student_name || b.student_email);

                const info = document.createElement("div");
                info.className = "bk-info";

                const title = document.createElement("div");
                title.className = "bk-title";
                title.textContent = b.subject || "Session";

                const meta = document.createElement("div");
                meta.className = "bk-meta";
                meta.innerHTML =
                    'From <strong>' + escapeHtml(b.student_name || b.student_email) + '</strong><br>' +
                    (DAYS_NAMES[b.day] || b.day_name || "") + ' · ' +
                    b.time + " - " + addHour(b.time) + '<br>' +
                    '<span style="font-size:0.72rem;color:#8a9e94;">Booked ' + formatDate(b.sent) + '</span>';

                info.appendChild(title);
                info.appendChild(meta);

                const side = document.createElement("div");
                side.className = "bk-side";

                const amount = document.createElement("div");
                amount.className = "bk-amount " + (
                    status === "accepted" ? "profit" :
                    status === "rejected" ? "loss" : "pending"
                );
                amount.textContent = (status === "rejected" ? "-" : "") + formatMoney(price);
                side.appendChild(amount);

                const statusEl = document.createElement("div");
                statusEl.className = "bk-status " + status;
                statusEl.textContent = status;
                side.appendChild(statusEl);

                card.appendChild(avatar);
                card.appendChild(info);
                card.appendChild(side);
                bkList.appendChild(card);
            });
        }

        async function loadBookings() {
            const all = await apiFilter(BOOKINGS_TABLE, {});
            bookings = all
                .filter(b => String(b.tutor_email) === String(CURRENT_USER.email))
                .map(b => ({
                    ...b,
                    status: b.status || "pending",
                    price: Number(b.price || 0)
                }));

            updateTotals();
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

        refreshBtn.addEventListener("click", async () => {
            await loadBookings();
            showToast("Bookings refreshed.", "success");
        });

        updateHeaderForFilter();
        loadBookings();
    </script>
</body>

</html>