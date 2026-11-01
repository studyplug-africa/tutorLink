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
  <title>My Sessions · TutorLink</title>
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

    .session-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
    }

    .session-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .session-card {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
      transition: border-color 0.15s;
    }

    .session-card:hover {
      border-color: #cfe3da;
    }

    .session-avatar {
      width: 46px;
      height: 46px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .session-info {
      flex: 1;
      min-width: 200px;
    }

    .session-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.25rem;
    }

    .session-meta {
      font-size: 0.8rem;
      color: #6b8b7e;
      line-height: 1.5;
    }

    .session-meta strong {
      color: #0a2e3f;
      font-weight: 600;
    }

    .session-meta .price {
      color: #0b6e4f;
      font-weight: 700;
    }

    .session-link {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      margin-top: 0.4rem;
      padding: 0.35rem 0.7rem;
      border-radius: 5px;
      border: 1.5px solid #0b6e4f;
      background: #f6fbf8;
      color: #0b6e4f;
      font-size: 0.76rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.15s;
    }

    .session-link:hover {
      background: #0b6e4f;
      color: #ffffff;
    }

    .session-link i {
      font-size: 0.7rem;
    }

    .session-side {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 0.4rem;
      flex-shrink: 0;
    }

    .session-status {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 0.3rem 0.65rem;
      border-radius: 5px;
      border: 1px solid #e4f0ea;
    }

    .session-status.pending {
      color: #d39e00;
      border-color: #f0e0b8;
      background: #fef9ec;
    }

    .session-status.accepted {
      color: #0b6e4f;
      border-color: #cfe3da;
      background: #f6fbf8;
    }

    .session-status.rejected {
      color: #c0392b;
      border-color: #f5c6c1;
      background: #fdecea;
    }

    .session-status.cancelled {
      color: #6b8b7e;
      border-color: #e4f0ea;
      background: #f6fbf8;
    }

    .session-date {
      font-size: 0.7rem;
      color: #8a9e94;
      font-weight: 500;
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

    .toast.success { border-left-color: #0b6e4f; }
    .toast.error { border-left-color: #c0392b; }
    .toast.show { transform: translateX(0); }

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

    .toast.success .toast-icon { background: #0b6e4f; }
    .toast.error .toast-icon { background: #c0392b; }

    .toast-message { color: #0a2e3f; line-height: 1.3; }

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

      .session-scroll {
        padding: 1rem;
      }
    }

    @media (max-width: 480px) {
      .detail-header {
        padding: 1rem;
      }

      .session-side {
        align-items: flex-start;
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
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
        <h1>My sessions</h1>
        <p>Track your booked sessions and their status.</p>
      </div>

      <div class="list-scroll">
        <div class="side-section">
          <div class="side-title">Overview</div>
          <div class="stat-row">
            <span>Total</span>
            <span id="statTotal">0</span>
          </div>
          <div class="stat-row">
            <span>Upcoming</span>
            <span id="statUpcoming">0</span>
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
            <button class="filter-item active" data-filter="upcoming">
              <span class="filter-item-label">
                <i class="fa-solid fa-calendar-check"></i>
                Upcoming
              </span>
              <span class="filter-count" id="countUpcoming">0</span>
            </button>

            <button class="filter-item" data-filter="pending">
              <span class="filter-item-label">
                <i class="fa-solid fa-hourglass-half"></i>
                Pending
              </span>
              <span class="filter-count" id="countPending">0</span>
            </button>

            <button class="filter-item" data-filter="accepted">
              <span class="filter-item-label">
                <i class="fa-solid fa-circle-check"></i>
                Accepted
              </span>
              <span class="filter-count" id="countAccepted">0</span>
            </button>

            <button class="filter-item" data-filter="rejected">
              <span class="filter-item-label">
                <i class="fa-solid fa-circle-xmark"></i>
                Rejected
              </span>
              <span class="filter-count" id="countRejected">0</span>
            </button>

            <button class="filter-item" data-filter="all">
              <span class="filter-item-label">
                <i class="fa-solid fa-list"></i>
                All sessions
              </span>
              <span class="filter-count" id="countAll">0</span>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <section class="detail-pane">
      <div class="detail-header">
        <div class="detail-header-text">
          <div class="detail-title" id="viewTitle">Upcoming sessions</div>
          <div class="detail-sub" id="viewSub">Your next scheduled learning blocks</div>
        </div>
        <div class="header-actions">
          <button class="header-icon-btn" id="refreshBtn" title="Refresh">
            <i class="fa-solid fa-rotate"></i>
          </button>
        </div>
      </div>

      <div class="session-scroll">
        <div class="session-list" id="sessionList"></div>
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

    const sessionList = document.getElementById("sessionList");
    const filterList = document.getElementById("filterList");
    const filterButtons = document.querySelectorAll(".filter-item");

    const statTotal = document.getElementById("statTotal");
    const statUpcoming = document.getElementById("statUpcoming");
    const statPending = document.getElementById("statPending");
    const statRejected = document.getElementById("statRejected");

    const countUpcoming = document.getElementById("countUpcoming");
    const countPending = document.getElementById("countPending");
    const countAccepted = document.getElementById("countAccepted");
    const countRejected = document.getElementById("countRejected");
    const countAll = document.getElementById("countAll");

    const viewTitle = document.getElementById("viewTitle");
    const viewSub = document.getElementById("viewSub");
    const refreshBtn = document.getElementById("refreshBtn");

    const toast = document.getElementById("toast");
    const toastIcon = document.getElementById("toastIcon");
    const toastMessage = document.getElementById("toastMessage");

    let bookings = [];
    let activeFilter = "upcoming";
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
          : { type: "get",    table: table }
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
      if (!text) return "T";
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

    function formatPrice(value) {
      const n = Number(value);
      if (!value || isNaN(n) || n <= 0) return "Free";
      return "R " + n.toFixed(2);
    }

    function formatSent(iso) {
      if (!iso) return "";
      const d = new Date(iso.replace(" ", "T"));
      if (isNaN(d)) return iso;
      return d.toLocaleDateString(undefined, { month: "short", day: "numeric", year: "numeric" });
    }

    function isUpcoming(b) {
      if (!b.day && b.day !== 0) return false;
      if (!b.time) return false;

      const today = new Date();
      const dow = today.getDay();
      const monday = new Date(today);
      const diff = (dow === 0 ? -6 : 1 - dow);
      monday.setDate(today.getDate() + diff);
      monday.setHours(0, 0, 0, 0);

      const dayKey = Number(b.day);
      const offset = dayKey === 0 ? 6 : dayKey - 1;
      const date = new Date(monday);
      date.setDate(monday.getDate() + offset);

      const [h, m] = b.time.split(":").map(Number);
      date.setHours(h, m, 0, 0);

      return date.getTime() > Date.now();
    }

    function matchesFilter(b) {
      if (activeFilter === "all") return true;
      if (activeFilter === "upcoming") {
        return (b.status === "accepted" || b.status === "pending" || !b.status) && isUpcoming(b);
      }
      if (activeFilter === "pending") return b.status === "pending" || !b.status;
      if (activeFilter === "accepted") return b.status === "accepted";
      if (activeFilter === "rejected") return b.status === "rejected";
      return true;
    }

    function updateStats() {
      const total = bookings.length;
      const pending = bookings.filter(b => b.status === "pending" || !b.status).length;
      const accepted = bookings.filter(b => b.status === "accepted").length;
      const rejected = bookings.filter(b => b.status === "rejected").length;
      const upcoming = bookings.filter(b => (b.status === "accepted" || b.status === "pending" || !b.status) && isUpcoming(b)).length;

      statTotal.textContent = total;
      statUpcoming.textContent = upcoming;
      statPending.textContent = pending;
      statRejected.textContent = rejected;

      countUpcoming.textContent = upcoming;
      countPending.textContent = pending;
      countAccepted.textContent = accepted;
      countRejected.textContent = rejected;
      countAll.textContent = total;
    }

    function renderList() {
      const list = bookings
        .filter(matchesFilter)
        .sort((a, b) => new Date(b.sent) - new Date(a.sent));

      sessionList.innerHTML = "";

      if (list.length === 0) {
        const empty = document.createElement("div");
        empty.className = "empty";
        empty.innerHTML =
          '<i class="fa-solid fa-calendar-days"></i>' +
          '<strong>No sessions here</strong>' +
          'Book a slot from Find Tutor to get started.';
        sessionList.appendChild(empty);
        return;
      }

      list.forEach(b => {
        const card = document.createElement("div");
        card.className = "session-card";

        const avatar = document.createElement("div");
        avatar.className = "session-avatar";
        avatar.style.background = colorFromString(b.tutor_name || b.tutor_email);
        avatar.textContent = initials(b.tutor_name || b.tutor_email);

        const info = document.createElement("div");
        info.className = "session-info";

        const title = document.createElement("div");
        title.className = "session-title";
        title.textContent = b.subject || "Session";

        const meta = document.createElement("div");
        meta.className = "session-meta";
        meta.innerHTML =
          'with <strong>' + escapeHtml(b.tutor_name || b.tutor_email) + '</strong><br>' +
          (DAYS_NAMES[b.day] || b.day_name || "") + ' · ' +
          b.time + " - " + addHour(b.time) + ' · ' +
          '<span class="price">' + formatPrice(b.price) + '</span><br>' +
          'Booked ' + formatSent(b.sent);

        info.appendChild(title);
        info.appendChild(meta);

        if (b.status === "accepted" && b.meeting_link) {
          const link = document.createElement("a");
          link.className = "session-link";
          link.href = b.meeting_link;
          link.target = "_blank";
          link.rel = "noopener noreferrer";
          link.innerHTML = '<i class="fa-solid fa-video"></i> Join Teams meeting';
          info.appendChild(link);
        }

        const side = document.createElement("div");
        side.className = "session-side";

        const status = document.createElement("div");
        status.className = "session-status " + (b.status || "pending");
        status.textContent = (b.status || "pending");
        side.appendChild(status);

        const date = document.createElement("div");
        date.className = "session-date";
        date.textContent = (DAYS_NAMES[b.day] || b.day_name || "");
        side.appendChild(date);

        card.appendChild(avatar);
        card.appendChild(info);
        card.appendChild(side);
        sessionList.appendChild(card);
      });
    }

    function updateHeaderForFilter() {
      if (activeFilter === "upcoming") {
        viewTitle.textContent = "Upcoming sessions";
        viewSub.textContent = "Your next scheduled learning blocks";
      } else if (activeFilter === "pending") {
        viewTitle.textContent = "Pending sessions";
        viewSub.textContent = "Waiting for tutor approval";
      } else if (activeFilter === "accepted") {
        viewTitle.textContent = "Accepted sessions";
        viewSub.textContent = "Approved by your tutor";
      } else if (activeFilter === "rejected") {
        viewTitle.textContent = "Rejected sessions";
        viewSub.textContent = "Sessions your tutor declined";
      } else {
        viewTitle.textContent = "All sessions";
        viewSub.textContent = "Everything you've booked";
      }
    }

    async function loadBookings() {
      const all = await apiFilter(BOOKINGS_TABLE, {});
      bookings = all
        .filter(b => String(b.student_email) === String(CURRENT_USER.email))
        .map(b => ({ ...b, status: b.status || "pending" }));

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

    refreshBtn.addEventListener("click", async () => {
      refreshBtn.classList.add("spinning");
      await loadBookings();
      showToast("Sessions refreshed.", "success");
    });

    updateHeaderForFilter();
    setInterval(loadBookings, 5000);
    loadBookings();
  </script>
</body>

</html>