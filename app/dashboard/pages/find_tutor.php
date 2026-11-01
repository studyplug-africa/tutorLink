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
  <title>Find Tutor · TutorLink</title>
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
      width: 340px;
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
    }

    .list-header span {
      font-size: 0.72rem;
      color: #6b8b7e;
      font-weight: 600;
    }

    .search-wrap {
      position: relative;
      margin-bottom: 0.65rem;
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
    }

    .search-input::placeholder {
      color: #a8bcb2;
    }

    .search-input:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .filter-row {
      display: flex;
      gap: 0.4rem;
    }

    .filter-select {
      flex: 1;
      padding: 0.5rem 0.7rem;
      font-size: 0.78rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      outline: none;
      color: #0a2e3f;
      background: #ffffff;
      font-family: inherit;
      cursor: pointer;
    }

    .filter-select:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.1);
    }

    .list-scroll {
      flex: 1;
      overflow-y: auto;
    }

    .tutor-row {
      display: flex;
      gap: 0.75rem;
      padding: 0.95rem 1.25rem;
      border-bottom: 1px solid #f0f7f4;
      cursor: pointer;
      border-left: 3px solid transparent;
    }

    .tutor-row:hover {
      background: #f6fbf8;
    }

    .tutor-row.active {
      background: #f0f7f4;
      border-left-color: #0b6e4f;
    }

    .tutor-avatar {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .tutor-body {
      flex: 1;
      min-width: 0;
    }

    .tutor-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      margin-bottom: 0.2rem;
    }

    .tutor-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: #0a4b6e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .tutor-count {
      font-size: 0.68rem;
      color: #6b8b7e;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    .tutor-subjects {
      font-size: 0.78rem;
      color: #6b8b7e;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
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
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e4f0ea;
      display: flex;
      align-items: center;
      gap: 0.85rem;
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
      flex-shrink: 0;
    }

    .detail-back:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .detail-avatar {
      width: 42px;
      height: 42px;
      border-radius: 5px;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 700;
      color: #ffffff;
      flex-shrink: 0;
    }

    .detail-header-text {
      min-width: 0;
      flex: 1;
    }

    .detail-title {
      font-size: 1rem;
      font-weight: 700;
      color: #0a4b6e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 0.1rem;
    }

    .detail-sub {
      font-size: 0.72rem;
      color: #6b8b7e;
      font-weight: 500;
    }

    .detail-empty {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .detail-empty i {
      font-size: 2.5rem;
      color: #cfe3da;
      margin-bottom: 0.85rem;
    }

    .detail-empty strong {
      color: #0a4b6e;
      font-weight: 600;
    }

    .detail-body {
      flex: 1;
      overflow-y: auto;
      padding: 1.5rem;
    }

    .section-title {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #8a9e94;
      margin-bottom: 0.6rem;
    }

    .section {
      margin-bottom: 1.75rem;
    }

    .subject-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
    }

    .subject-chip {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.35rem 0.65rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.76rem;
      font-weight: 600;
      color: #0b6e4f;
      background: #f6fbf8;
    }

    .week-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 0.65rem;
    }

    .day-col {
      flex: 1 1 180px;
      min-width: 180px;
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      overflow: hidden;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      min-height: 140px;
    }

    .day-col.today {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.08);
    }

    .day-header {
      padding: 0.55rem 0.5rem;
      background: #f6fbf8;
      border-bottom: 1px solid #e4f0ea;
      text-align: center;
    }

    .day-col.today .day-header {
      background: #f0f7f4;
    }

    .day-name {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0b6e4f;
    }

    .day-date {
      font-size: 0.65rem;
      color: #6b8b7e;
      margin-top: 0.1rem;
    }

    .day-slots {
      flex: 1;
      padding: 0.45rem;
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
    }

    .slot {
      border: 1px solid #cfe3da;
      border-radius: 5px;
      padding: 0.55rem 0.65rem;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
      cursor: pointer;
      transition: all 0.15s;
      text-align: left;
      font-family: inherit;
      color: inherit;
      width: 100%;
      position: relative;
    }

    .slot:hover:not(.booked):not(.past) {
      background: #f0f7f4;
      border-color: #0b6e4f;
      transform: translateY(-1px);
    }

    .slot.booked {
      background: #f6fbf8;
      border-color: #e4f0ea;
      cursor: not-allowed;
      opacity: 0.6;
    }

    .slot.past {
      background: #fafbfc;
      border-color: #e4f0ea;
      cursor: not-allowed;
      opacity: 0.5;
    }

    .slot-time {
      font-weight: 700;
      color: #0b6e4f;
      font-size: 0.72rem;
    }

    .slot.booked .slot-time {
      color: #8a9e94;
    }

    .slot.past .slot-time {
      color: #8a9e94;
    }

    .slot-subject {
      font-weight: 600;
      color: #0a4b6e;
      font-size: 0.76rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      padding-right: 3rem;
    }

    .slot-price {
      position: absolute;
      top: 0.55rem;
      right: 0.65rem;
      font-size: 0.72rem;
      font-weight: 700;
      color: #0b6e4f;
      background: #f0f7f4;
      border: 1px solid #cfe3da;
      padding: 0.1rem 0.4rem;
      border-radius: 4px;
    }

    .slot.booked .slot-price {
      color: #8a9e94;
      background: #f6fbf8;
      border-color: #e4f0ea;
    }

    .slot.past .slot-price {
      color: #8a9e94;
      background: #f6fbf8;
      border-color: #e4f0ea;
    }

    .slot-status {
      font-size: 0.62rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #8a9e94;
      margin-top: 0.15rem;
    }

    .slot.available .slot-status {
      color: #0b6e4f;
    }

    .slot.past .slot-status {
      color: #c0392b;
    }

    .day-empty {
      font-size: 0.68rem;
      color: #a8bcb2;
      text-align: center;
      padding: 0.85rem 0.25rem;
      font-style: italic;
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

    .confirm-details {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 0.85rem 1rem;
      margin-bottom: 1.25rem;
      background: #f6fbf8;
    }

    .confirm-row {
      display: flex;
      justify-content: space-between;
      padding: 0.35rem 0;
      font-size: 0.85rem;
    }

    .confirm-row span:first-child {
      color: #6b8b7e;
    }

    .confirm-row span:last-child {
      color: #0a4b6e;
      font-weight: 600;
    }

    .confirm-row.total {
      border-top: 1px solid #e4f0ea;
      margin-top: 0.35rem;
      padding-top: 0.6rem;
    }

    .confirm-row.total span:last-child {
      color: #0b6e4f;
      font-size: 1rem;
      font-weight: 700;
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
      border: 1.5px solid #cfe3da;
      background: #ffffff;
      color: #0a4b6e;
      font-family: inherit;
    }

    .btn:hover {
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .btn-primary {
      border-color: #0b6e4f;
      background: #0b6e4f;
      color: #ffffff;
    }

    .btn-primary:hover {
      background: #0a5a40;
      color: #ffffff;
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
  <div class="wrap" id="wrap">
    <aside class="list-pane">
      <div class="list-header">
        <div class="list-header-top">
          <h1>Find a tutor</h1>
          <span id="tutorCountLabel">0 tutors</span>
        </div>
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search by name or subject..." autocomplete="off">
        </div>
        <div class="filter-row">
          <select id="dayFilter" class="filter-select">
            <option value="">All days</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="0">Sunday</option>
          </select>
          <select id="subjectFilter" class="filter-select">
            <option value="">All subjects</option>
          </select>
        </div>
      </div>

      <div class="list-scroll" id="listScroll"></div>
    </aside>

    <section class="detail-pane">
      <div class="detail-header">
        <button class="detail-back" id="detailBack">
          <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="detail-avatar" id="detailAvatar">T</div>
        <div class="detail-header-text">
          <div class="detail-title" id="detailTitle">Select a tutor</div>
          <div class="detail-sub" id="detailSub">Pick a tutor from the list to see their availability</div>
        </div>
      </div>

      <div class="detail-empty" id="detailEmpty">
        <i class="fa-solid fa-user-graduate"></i>
        <strong>No tutor selected</strong>
        Pick a tutor on the left to view their subjects and weekly slots.
      </div>

      <div class="detail-body" id="detailBody" style="display:none;">
        <div class="section">
          <div class="section-title">Subjects</div>
          <div class="subject-chips" id="subjectChips"></div>
        </div>

        <div class="section">
          <div class="section-title">Available slots this week</div>
          <div class="week-grid" id="weekGrid"></div>
        </div>
      </div>
    </section>
  </div>

  <div class="modal-overlay" id="bookModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon">
          <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div>
          <h3>Book this slot?</h3>
          <p>Confirm your session booking.</p>
        </div>
      </div>

      <div class="confirm-details">
        <div class="confirm-row">
          <span>Tutor</span>
          <span id="bookTutor">—</span>
        </div>
        <div class="confirm-row">
          <span>Subject</span>
          <span id="bookSubject">—</span>
        </div>
        <div class="confirm-row">
          <span>Day</span>
          <span id="bookDay">—</span>
        </div>
        <div class="confirm-row">
          <span>Time</span>
          <span id="bookTime">—</span>
        </div>
        <div class="confirm-row total">
          <span>Price</span>
          <span id="bookPrice">—</span>
        </div>
      </div>

      <div class="modal-actions">
        <button class="btn" id="cancelBook">Cancel</button>
        <button class="btn btn-primary" id="confirmBook">
          <i class="fa-solid fa-check"></i>
          Book slot
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

    const T = {
      users:     "user_b6a3d990_rea_users",
      subjects:  "user_b6a3d990_rea_lecturer_subjects",
      schedules: "user_b6a3d990_rea_lecturer_schedule",
      bookings:  "user_b6a3d990_rea_bookings"
    };

    const CURRENT_USER = {
      name: <?php echo json_encode($fullname); ?>,
      email: <?php echo json_encode($email); ?>
    };

    const DAYS = [
      { key: 1, name: "Monday" },
      { key: 2, name: "Tuesday" },
      { key: 3, name: "Wednesday" },
      { key: 4, name: "Thursday" },
      { key: 5, name: "Friday" },
      { key: 6, name: "Saturday" },
      { key: 0, name: "Sunday" }
    ];

    const wrap = document.getElementById("wrap");
    const listScroll = document.getElementById("listScroll");
    const searchInput = document.getElementById("searchInput");
    const dayFilter = document.getElementById("dayFilter");
    const subjectFilter = document.getElementById("subjectFilter");
    const tutorCountLabel = document.getElementById("tutorCountLabel");

    const detailBack = document.getElementById("detailBack");
    const detailAvatar = document.getElementById("detailAvatar");
    const detailTitle = document.getElementById("detailTitle");
    const detailSub = document.getElementById("detailSub");
    const detailEmpty = document.getElementById("detailEmpty");
    const detailBody = document.getElementById("detailBody");
    const subjectChips = document.getElementById("subjectChips");
    const weekGrid = document.getElementById("weekGrid");

    const bookModal = document.getElementById("bookModal");
    const bookTutor = document.getElementById("bookTutor");
    const bookSubject = document.getElementById("bookSubject");
    const bookDay = document.getElementById("bookDay");
    const bookTime = document.getElementById("bookTime");
    const bookPrice = document.getElementById("bookPrice");
    const cancelBook = document.getElementById("cancelBook");
    const confirmBook = document.getElementById("confirmBook");

    const toast = document.getElementById("toast");
    const toastIcon = document.getElementById("toastIcon");
    const toastMessage = document.getElementById("toastMessage");

    let tutors = [];
    let allSubjects = [];
    let allSlots = [];
    let allBookings = [];
    let activeTutor = null;
    let pendingBooking = null;
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
      const [h, m] = time.split(":").map(Number);
      const next = (h + 1) % 24;
      return String(next).padStart(2, "0") + ":" + String(m).padStart(2, "0");
    }

    function formatPrice(value) {
      const n = Number(value);
      if (!value || isNaN(n) || n <= 0) return "Free";
      return "R " + n.toFixed(2);
    }

    function getWeekDates() {
      const today = new Date();
      const dow = today.getDay();
      const monday = new Date(today);
      const diff = (dow === 0 ? -6 : 1 - dow);
      monday.setDate(today.getDate() + diff);
      monday.setHours(0, 0, 0, 0);

      const map = {};
      DAYS.forEach(d => {
        const date = new Date(monday);
        const offset = d.key === 0 ? 6 : d.key - 1;
        date.setDate(monday.getDate() + offset);

        const iso = date.getFullYear() + "-" +
          String(date.getMonth() + 1).padStart(2, "0") + "-" +
          String(date.getDate()).padStart(2, "0");

        map[d.key] = {
          name: d.name,
          date: date.toLocaleDateString(undefined, { month: "short", day: "numeric" }),
          iso: iso,
          isToday: date.toDateString() === today.toDateString()
        };
      });
      return map;
    }

    function isSlotBooked(tutorEmail, day, time) {
      return allBookings.some(b =>
        String(b.tutor_email) === String(tutorEmail) &&
        Number(b.day) === Number(day) &&
        b.time === time
      );
    }

    function isPastSlot(dayKey, time) {
      const dates = getWeekDates();
      const info = dates[dayKey];
      if (!info) return false;

      const [h, m] = time.split(":").map(Number);
      const slotDate = new Date(
        info.iso + "T" +
        String(h).padStart(2, "0") + ":" +
        String(m).padStart(2, "0") + ":00"
      );

      return slotDate.getTime() < Date.now();
    }

    async function loadData() {
      const [users, subjects, slots, bookings] = await Promise.all([
        apiFilter(T.users, {}),
        apiFilter(T.subjects, {}),
        apiFilter(T.schedules, {}),
        apiFilter(T.bookings, {}).catch(() => [])
      ]);

      allSubjects = subjects;
      allSlots = slots;
      allBookings = bookings;

      const lecturerEmails = new Set(subjects.map(s => s.email));
      const lecturerNames = new Map();

      users.forEach(u => {
        if (lecturerEmails.has(u.email)) {
          lecturerNames.set(u.email, u.fullname || u.email);
        }
      });

      lecturerEmails.forEach(email => {
        if (!lecturerNames.has(email)) lecturerNames.set(email, email);
      });

      tutors = Array.from(lecturerEmails).map(email => ({
        email: email,
        name: lecturerNames.get(email) || email,
        subjects: subjects
          .filter(s => s.email === email)
          .map(s => s.name),
        slots: slots.filter(s => s.email === email)
      }));

      buildSubjectFilter();
      renderList();
    }

    function buildSubjectFilter() {
      const all = new Set();
      allSubjects.forEach(s => all.add(s.name));
      const sorted = Array.from(all).sort();

      const current = subjectFilter.value;
      subjectFilter.innerHTML = '<option value="">All subjects</option>';
      sorted.forEach(name => {
        const opt = document.createElement("option");
        opt.value = name;
        opt.textContent = name;
        subjectFilter.appendChild(opt);
      });
      subjectFilter.value = current;
    }

    function filteredTutors() {
      const term = searchInput.value.trim().toLowerCase();
      const day = dayFilter.value;
      const subject = subjectFilter.value;

      return tutors.filter(t => {
        if (subject && !t.subjects.includes(subject)) return false;
        if (day) {
          const hasSlot = t.slots.some(s => Number(s.day) === Number(day));
          if (!hasSlot) return false;
        }
        if (term) {
          const hay = (t.name + " " + t.subjects.join(" ")).toLowerCase();
          if (!hay.includes(term)) return false;
        }
        return true;
      });
    }

    function renderList() {
      const list = filteredTutors();
      listScroll.innerHTML = "";
      tutorCountLabel.textContent = list.length + (list.length === 1 ? " tutor" : " tutors");

      if (list.length === 0) {
        const empty = document.createElement("div");
        empty.className = "empty-list";
        empty.innerHTML = '<i class="fa-solid fa-user-graduate"></i>No tutors match your filters.<br>Try clearing the search or filters.';
        listScroll.appendChild(empty);
        return;
      }

      list.forEach(t => {
        const row = document.createElement("div");
        row.className = "tutor-row" + (activeTutor && activeTutor.email === t.email ? " active" : "");
        row.dataset.email = t.email;

        const avatar = document.createElement("div");
        avatar.className = "tutor-avatar";
        avatar.style.background = colorFromString(t.name);
        avatar.textContent = initials(t.name);

        const body = document.createElement("div");
        body.className = "tutor-body";

        const top = document.createElement("div");
        top.className = "tutor-top";

        const name = document.createElement("div");
        name.className = "tutor-name";
        name.textContent = t.name;

        const count = document.createElement("div");
        count.className = "tutor-count";
        count.innerHTML = '<i class="fa-solid fa-calendar-days"></i>' + t.slots.length;

        top.appendChild(name);
        top.appendChild(count);

        const subj = document.createElement("div");
        subj.className = "tutor-subjects";
        subj.textContent = t.subjects.length > 0 ? t.subjects.join(" · ") : "No subjects listed";

        body.appendChild(top);
        body.appendChild(subj);

        row.appendChild(avatar);
        row.appendChild(body);

        row.addEventListener("click", () => openTutor(t.email));
        listScroll.appendChild(row);
      });
    }

    function openTutor(email) {
      const t = tutors.find(x => x.email === email);
      if (!t) return;

      activeTutor = t;

      document.querySelectorAll(".tutor-row").forEach(r => {
        r.classList.toggle("active", r.dataset.email === email);
      });

      detailEmpty.style.display = "none";
      detailBody.style.display = "block";

      detailAvatar.style.display = "flex";
      detailAvatar.textContent = initials(t.name);
      detailAvatar.style.background = colorFromString(t.name);

      detailTitle.textContent = t.name;
      detailSub.textContent = t.subjects.length + " subject" + (t.subjects.length === 1 ? "" : "s") +
        " · " + t.slots.length + " slot" + (t.slots.length === 1 ? "" : "s") + " this week";

      subjectChips.innerHTML = "";
      if (t.subjects.length === 0) {
        subjectChips.innerHTML = '<span style="font-size:0.8rem;color:#8a9e94;">No subjects listed</span>';
      } else {
        t.subjects.forEach(name => {
          const chip = document.createElement("span");
          chip.className = "subject-chip";
          chip.textContent = name;
          subjectChips.appendChild(chip);
        });
      }

      renderWeek(t);

      if (window.innerWidth <= 800) {
        wrap.classList.add("mobile-view-detail");
      }
    }

    function renderWeek(t) {
      const dates = getWeekDates();
      weekGrid.innerHTML = "";

      DAYS.forEach(d => {
        const col = document.createElement("div");
        col.className = "day-col" + (dates[d.key].isToday ? " today" : "");

        const header = document.createElement("div");
        header.className = "day-header";
        header.innerHTML =
          '<div class="day-name">' + d.name + '</div>' +
          '<div class="day-date">' + dates[d.key].date + '</div>';
        col.appendChild(header);

        const slotsEl = document.createElement("div");
        slotsEl.className = "day-slots";

        const daySlots = t.slots
          .filter(s => Number(s.day) === d.key)
          .sort((a, b) => a.time.localeCompare(b.time));

        if (daySlots.length === 0) {
          const empty = document.createElement("div");
          empty.className = "day-empty";
          empty.textContent = "No slots";
          slotsEl.appendChild(empty);
        } else {
          daySlots.forEach(s => {
            const booked = isSlotBooked(t.email, s.day, s.time);
            const past = isPastSlot(s.day, s.time);
            const blocked = booked || past;

            const slot = document.createElement("button");
            slot.type = "button";
            slot.className = "slot " + (past ? "past" : booked ? "booked" : "available");

            const time = document.createElement("div");
            time.className = "slot-time";
            time.textContent = s.time + " - " + addHour(s.time);

            const subj = document.createElement("div");
            subj.className = "slot-subject";
            subj.textContent = s.subject;

            const price = document.createElement("div");
            price.className = "slot-price";
            price.textContent = formatPrice(s.price);

            const status = document.createElement("div");
            status.className = "slot-status";
            if (past) status.textContent = "Past";
            else if (booked) status.textContent = "Booked";
            else status.textContent = "Available";

            slot.appendChild(time);
            slot.appendChild(subj);
            slot.appendChild(price);
            slot.appendChild(status);

            if (!blocked) {
              slot.addEventListener("click", () => openBookModal(t, s, d.name));
            } else {
              slot.disabled = true;
            }

            slotsEl.appendChild(slot);
          });
        }

        col.appendChild(slotsEl);
        weekGrid.appendChild(col);
      });
    }

    function openBookModal(t, s, dayName) {
      pendingBooking = { tutor: t, slot: s, dayName: dayName };

      bookTutor.textContent = t.name;
      bookSubject.textContent = s.subject;
      bookDay.textContent = dayName;
      bookTime.textContent = s.time + " - " + addHour(s.time);
      bookPrice.textContent = formatPrice(s.price);

      bookModal.classList.add("show");
    }

    function closeBookModal() {
      bookModal.classList.remove("show");
      pendingBooking = null;
    }

    cancelBook.addEventListener("click", closeBookModal);

    bookModal.addEventListener("click", (e) => {
      if (e.target === bookModal) closeBookModal();
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && bookModal.classList.contains("show")) {
        closeBookModal();
      }
    });

    confirmBook.addEventListener("click", async () => {
      if (!pendingBooking) return;

      const { tutor: t, slot: s, dayName } = pendingBooking;

      if (isSlotBooked(t.email, s.day, s.time)) {
        closeBookModal();
        showToast("That slot was just booked. Pick another.", "error");
        await loadData();
        if (activeTutor) openTutor(activeTutor.email);
        return;
      }

      closeBookModal();

      await api({
        type: "insert_booking",
        student_name: CURRENT_USER.name,
        student_email: CURRENT_USER.email,
        tutor_name: t.name,
        tutor_email: t.email,
        subject: s.subject,
        day: s.day,
        day_name: dayName,
        time: s.time,
        price: Number(s.price || 0).toFixed(2),
        sent: new Date().toISOString().slice(0, 19).replace("T", " ")
      });

      allBookings.push({
        tutor_email: t.email,
        student_email: CURRENT_USER.email,
        subject: s.subject,
        day: s.day,
        time: s.time
      });

      showToast("Slot booked! Check My Sessions.", "success");

      if (activeTutor) renderWeek(activeTutor);
    });

    searchInput.addEventListener("input", renderList);
    dayFilter.addEventListener("change", renderList);
    subjectFilter.addEventListener("change", renderList);

    detailBack.addEventListener("click", () => {
      wrap.classList.remove("mobile-view-detail");
    });

    loadData();
  </script>
</body>

</html>