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
  <title>Session Requests · TutorLink</title>
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

    .req-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
    }

    .req-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .req-card {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
      transition: border-color 0.15s;
    }

    .req-card:hover {
      border-color: #cfe3da;
    }

    .req-avatar {
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

    .req-info {
      flex: 1;
      min-width: 200px;
    }

    .req-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.25rem;
    }

    .req-meta {
      font-size: 0.8rem;
      color: #6b8b7e;
      line-height: 1.5;
    }

    .req-meta strong {
      color: #0a2e3f;
      font-weight: 600;
    }

    .req-meta .price {
      color: #0b6e4f;
      font-weight: 700;
    }

    .req-meta a {
      color: #0b6e4f;
      font-weight: 600;
      text-decoration: none;
    }

    .req-meta a:hover {
      text-decoration: underline;
    }

    .req-side {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 0.5rem;
      flex-shrink: 0;
    }

    .req-status {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 0.3rem 0.65rem;
      border-radius: 5px;
      border: 1px solid #e4f0ea;
    }

    .req-status.pending {
      color: #d39e00;
      border-color: #f0e0b8;
      background: #fef9ec;
    }

    .req-status.accepted {
      color: #0b6e4f;
      border-color: #cfe3da;
      background: #f6fbf8;
    }

    .req-status.rejected {
      color: #c0392b;
      border-color: #f5c6c1;
      background: #fdecea;
    }

    .req-actions {
      display: flex;
      gap: 0.4rem;
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

    .btn-accept {
      border-color: #0b6e4f;
      background: #0b6e4f;
      color: #ffffff;
    }

    .btn-accept:hover {
      background: #0a5a40;
      border-color: #0a5a40;
      color: #ffffff;
    }

    .btn-reject {
      border-color: #c0392b;
      background: #ffffff;
      color: #c0392b;
    }

    .btn-reject:hover {
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

    .input-field {
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

    .input-field:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .input-field.error {
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

      .req-scroll {
        padding: 1rem;
      }
    }

    @media (max-width: 480px) {
      .detail-header {
        padding: 1rem;
      }

      .req-side {
        align-items: flex-start;
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
      }

      .req-actions {
        width: 100%;
      }

      .req-actions .btn {
        flex: 1;
        justify-content: center;
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
        <h1>Session requests</h1>
        <p>Review and respond to student booking requests.</p>
      </div>

      <div class="list-scroll">
        <div class="side-section">
          <div class="side-title">Overview</div>
          <div class="stat-row">
            <span>Total requests</span>
            <span id="statTotal">0</span>
          </div>
          <div class="stat-row">
            <span>Pending</span>
            <span id="statPending">0</span>
          </div>
          <div class="stat-row">
            <span>Accepted</span>
            <span id="statAccepted">0</span>
          </div>
          <div class="stat-row">
            <span>Rejected</span>
            <span id="statRejected">0</span>
          </div>
        </div>

        <div class="side-section">
          <div class="side-title">Filter</div>
          <div class="filter-list" id="filterList">
            <button class="filter-item active" data-filter="pending">
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
                All requests
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
          <div class="detail-title" id="viewTitle">Pending requests</div>
          <div class="detail-sub" id="viewSub">Students waiting for your response</div>
        </div>
        <div class="header-actions">
          <button class="header-icon-btn" id="refreshBtn" title="Refresh">
            <i class="fa-solid fa-rotate"></i>
          </button>
        </div>
      </div>

      <div class="req-scroll">
        <div class="req-list" id="reqList"></div>
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

    const reqList = document.getElementById("reqList");
    const filterButtons = document.querySelectorAll(".filter-item");

    const statTotal = document.getElementById("statTotal");
    const statPending = document.getElementById("statPending");
    const statAccepted = document.getElementById("statAccepted");
    const statRejected = document.getElementById("statRejected");

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

    let requests = [];
    let activeFilter = "pending";
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

    function matchesFilter(r) {
      if (activeFilter === "all") return true;
      return (r.status || "pending") === activeFilter;
    }

    function updateStats() {
      const total = requests.length;
      const pending = requests.filter(r => (r.status || "pending") === "pending").length;
      const accepted = requests.filter(r => r.status === "accepted").length;
      const rejected = requests.filter(r => r.status === "rejected").length;

      statTotal.textContent = total;
      statPending.textContent = pending;
      statAccepted.textContent = accepted;
      statRejected.textContent = rejected;

      countPending.textContent = pending;
      countAccepted.textContent = accepted;
      countRejected.textContent = rejected;
      countAll.textContent = total;
    }

    function updateHeaderForFilter() {
      if (activeFilter === "pending") {
        viewTitle.textContent = "Pending requests";
        viewSub.textContent = "Students waiting for your response";
      } else if (activeFilter === "accepted") {
        viewTitle.textContent = "Accepted requests";
        viewSub.textContent = "Sessions you've approved";
      } else if (activeFilter === "rejected") {
        viewTitle.textContent = "Rejected requests";
        viewSub.textContent = "Sessions you've declined";
      } else {
        viewTitle.textContent = "All requests";
        viewSub.textContent = "Everything students have booked";
      }
    }

    async function acceptBooking(r, meetingLink) {
      await api({
        type: "accept_booking",
        id: r.id,
        meeting_link: meetingLink,
        student_email: r.student_email,
        tutor_name: r.tutor_name || CURRENT_USER.name,
        subject: r.subject,
        day_name: DAYS_NAMES[r.day] || r.day_name || "",
        time: r.time
      });
    }

    async function rejectBooking(r) {
      await api({
        type: "update_booking_status",
        id: r.id,
        status: "rejected"
      });
    }

    async function revertBooking(r) {
      await api({
        type: "update_booking_status",
        id: r.id,
        status: "pending"
      });
    }

    function openAcceptModal(r) {
      const overlay = document.createElement("div");
      overlay.className = "modal-overlay show";

      overlay.innerHTML =
        '<div class="modal">' +
          '<div class="modal-header">' +
            '<div class="modal-icon">' +
              '<i class="fa-solid fa-link"></i>' +
            '</div>' +
            '<div>' +
              '<h3>Accept session</h3>' +
              '<p>Provide a Teams meeting link for the student.</p>' +
            '</div>' +
          '</div>' +
          '<div class="form-group">' +
            '<label class="input-label" for="meetingLink">Teams meeting link</label>' +
            '<input class="input-field" type="url" id="meetingLink" placeholder="https://teams.microsoft.com/l/meetup-join/...">' +
            '<div class="error-message" id="meetingLinkError">Please enter a valid Teams link</div>' +
          '</div>' +
          '<div class="modal-actions">' +
            '<button class="btn" id="cancelAccept">Cancel</button>' +
            '<button class="btn btn-accept" id="confirmAccept">' +
              '<i class="fa-solid fa-check"></i>' +
              ' Accept & notify student' +
            '</button>' +
          '</div>' +
        '</div>';

      document.body.appendChild(overlay);

      const input = overlay.querySelector("#meetingLink");
      const errEl = overlay.querySelector("#meetingLinkError");
      const cancelBtn = overlay.querySelector("#cancelAccept");
      const confirmBtn = overlay.querySelector("#confirmAccept");

      function close() {
        overlay.remove();
      }

      cancelBtn.addEventListener("click", close);

      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) close();
      });

      function onEsc(ev) {
        if (ev.key === "Escape") {
          close();
          document.removeEventListener("keydown", onEsc);
        }
      }
      document.addEventListener("keydown", onEsc);

      setTimeout(() => input.focus(), 50);

      confirmBtn.addEventListener("click", async () => {
        const link = input.value.trim();

        if (!link || !/^https?:\/\/.+/i.test(link)) {
          input.classList.add("error");
          errEl.classList.add("show");
          return;
        }

        input.classList.remove("error");
        errEl.classList.remove("show");

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Accepting...';

        await acceptBooking(r, link);

        r.status = "accepted";
        r.meeting_link = link;

        close();
        updateStats();
        renderList();
        showToast("Session accepted and student notified.", "success");
      });

      input.addEventListener("input", () => {
        if (input.classList.contains("error") && /^https?:\/\/.+/i.test(input.value.trim())) {
          input.classList.remove("error");
          errEl.classList.remove("show");
        }
      });
    }

    function renderList() {
      const list = requests
        .filter(matchesFilter)
        .sort((a, b) => new Date(b.sent) - new Date(a.sent));

      reqList.innerHTML = "";

      if (list.length === 0) {
        const empty = document.createElement("div");
        empty.className = "empty";
        empty.innerHTML =
          '<i class="fa-solid fa-inbox"></i>' +
          '<strong>No ' + (activeFilter === "all" ? "" : activeFilter + " ") + 'requests</strong>' +
          'New booking requests will appear here.';
        reqList.appendChild(empty);
        return;
      }

      list.forEach(r => {
        const card = document.createElement("div");
        card.className = "req-card";

        const avatar = document.createElement("div");
        avatar.className = "req-avatar";
        avatar.style.background = colorFromString(r.student_name || r.student_email);
        avatar.textContent = initials(r.student_name || r.student_email);

        const info = document.createElement("div");
        info.className = "req-info";

        const title = document.createElement("div");
        title.className = "req-title";
        title.textContent = r.subject || "Session";

        const meta = document.createElement("div");
        meta.className = "req-meta";

        let metaHtml =
          'From <strong>' + escapeHtml(r.student_name || r.student_email) + '</strong><br>' +
          (DAYS_NAMES[r.day] || r.day_name || "") + ' · ' +
          r.time + " - " + addHour(r.time) + ' · ' +
          '<span class="price">' + formatPrice(r.price) + '</span><br>' +
          'Requested ' + formatSent(r.sent);

        if (r.meeting_link) {
          metaHtml += '<br><a href="' + escapeHtml(r.meeting_link) + '" target="_blank" rel="noopener noreferrer">Join Teams meeting</a>';
        }

        meta.innerHTML = metaHtml;

        info.appendChild(title);
        info.appendChild(meta);

        const side = document.createElement("div");
        side.className = "req-side";

        const status = document.createElement("div");
        status.className = "req-status " + (r.status || "pending");
        status.textContent = (r.status || "pending");
        side.appendChild(status);

        const actions = document.createElement("div");
        actions.className = "req-actions";

        if ((r.status || "pending") === "pending") {
          const acceptBtn = document.createElement("button");
          acceptBtn.className = "btn btn-accept";
          acceptBtn.innerHTML = '<i class="fa-solid fa-check"></i> Accept';
          acceptBtn.addEventListener("click", () => openAcceptModal(r));
          actions.appendChild(acceptBtn);

          const rejectBtn = document.createElement("button");
          rejectBtn.className = "btn btn-reject";
          rejectBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Reject';
          rejectBtn.addEventListener("click", async () => {
            rejectBtn.disabled = true;
            await rejectBooking(r);
            r.status = "rejected";
            updateStats();
            renderList();
            showToast("Session rejected.", "success");
          });
          actions.appendChild(rejectBtn);
        } else {
          const revertBtn = document.createElement("button");
          revertBtn.className = "btn";
          revertBtn.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Mark pending';
          revertBtn.addEventListener("click", async () => {
            revertBtn.disabled = true;
            await revertBooking(r);
            r.status = "pending";
            updateStats();
            renderList();
            showToast("Session reverted to pending.", "success");
          });
          actions.appendChild(revertBtn);
        }

        side.appendChild(actions);

        card.appendChild(avatar);
        card.appendChild(info);
        card.appendChild(side);
        reqList.appendChild(card);
      });
    }

    async function loadRequests() {
      const all = await apiFilter(BOOKINGS_TABLE, {});
      requests = all
        .filter(r => String(r.tutor_email) === String(CURRENT_USER.email))
        .map(r => ({ ...r, status: r.status || "pending" }));

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
      await loadRequests();
      showToast("Requests refreshed.", "success");
    });

    updateHeaderForFilter();
    setInterval(loadRequests, 5000);
    loadRequests();
  </script>
</body>

</html>