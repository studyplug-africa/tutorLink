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
  <title>Schedule · TutorLink</title>
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

    .subject-chip i {
      font-size: 0.68rem;
      color: #8a9e94;
      cursor: pointer;
    }

    .subject-chip i:hover {
      color: #c0392b;
    }

    .subject-add {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.35rem 0.65rem;
      border: 1.5px dashed #cfe3da;
      border-radius: 5px;
      font-size: 0.76rem;
      font-weight: 600;
      color: #6b8b7e;
      background: transparent;
      cursor: pointer;
      font-family: inherit;
    }

    .subject-add:hover {
      border-color: #0b6e4f;
      color: #0b6e4f;
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
    }

    .btn-create:hover {
      background: #f0f7f4;
    }

    .week-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
    }

    .week-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      align-content: flex-start;
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
      min-height: 200px;
    }

    .day-col.today {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 3px rgba(11, 110, 79, 0.08);
    }

    .day-header {
      padding: 0.7rem 0.6rem;
      background: #f6fbf8;
      border-bottom: 1px solid #e4f0ea;
      text-align: center;
    }

    .day-col.today .day-header {
      background: #f0f7f4;
    }

    .day-name {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0b6e4f;
    }

    .day-date {
      font-size: 0.68rem;
      color: #6b8b7e;
      margin-top: 0.15rem;
    }

    .day-slots {
      flex: 1;
      padding: 0.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    .slot {
      border: 1px solid #cfe3da;
      border-radius: 5px;
      padding: 0.5rem 2rem 0.5rem 0.6rem;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      gap: 0.15rem;
      position: relative;
      transition: all 0.15s;
    }

    .slot:hover {
      background: #f6fbf8;
      border-color: #0b6e4f;
    }

    .slot-time {
      font-weight: 700;
      color: #0b6e4f;
      font-size: 0.72rem;
    }

    .slot-subject {
      font-weight: 600;
      color: #0a4b6e;
      font-size: 0.78rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .slot-price {
      font-size: 0.7rem;
      font-weight: 700;
      color: #0b6e4f;
      margin-top: 0.1rem;
    }

    .slot-remove {
      position: absolute;
      top: 50%;
      right: 6px;
      transform: translateY(-50%);
      width: 24px;
      height: 24px;
      border-radius: 5px;
      border: 1px solid transparent;
      background: transparent;
      color: #8a9e94;
      cursor: pointer;
      font-size: 0.7rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s;
    }

    .slot-remove:hover {
      background: #fdecea;
      border-color: #f5c6c1;
      color: #c0392b;
    }

    .day-empty {
      font-size: 0.7rem;
      color: #a8bcb2;
      text-align: center;
      padding: 1rem 0.25rem;
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

    .modal-icon.danger {
      color: #c0392b;
      border-color: #f5c6c1;
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

    .btn-danger {
      border-color: #c0392b;
      background: #ffffff;
      color: #c0392b;
    }

    .btn-danger:hover {
      background: #fdecea;
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

      .week-scroll {
        padding: 1rem;
        overflow: visible;
      }

      .week-grid {
        display: flex;
        flex-wrap: wrap;
      }

      .day-col {
        flex: 1 1 calc(50% - 0.75rem);
        min-width: 150px;
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

      .day-col {
        flex: 1 1 100%;
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
        <h1>Your schedule</h1>
        <p>Manage your weekly availability and subjects.</p>
      </div>

      <div class="list-scroll">
        <div class="side-section">
          <div class="side-title">Overview</div>
          <div class="stat-row">
            <span>Slots this week</span>
            <span id="statSlots">0</span>
          </div>
          <div class="stat-row">
            <span>Hours available</span>
            <span id="statHours">0h</span>
          </div>
          <div class="stat-row">
            <span>Subjects offered</span>
            <span id="statSubjects">0</span>
          </div>
        </div>

        <div class="side-section">
          <div class="side-title">Subjects you teach</div>
          <div class="subject-chips" id="subjectList"></div>
        </div>
      </div>
    </aside>

    <section class="detail-pane">
      <div class="detail-header">
        <div class="detail-header-text">
          <div class="detail-title">This week</div>
          <div class="detail-sub">Each slot is 1 hour long</div>
        </div>
        <div class="header-actions">
          <button class="btn-create" id="addSlotBtn">
            <i class="fa-solid fa-plus"></i>
            Add availability
          </button>
        </div>
      </div>

      <div class="week-scroll">
        <div class="week-grid" id="weekGrid"></div>
      </div>
    </section>
  </div>

  <div class="modal-overlay" id="slotModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon">
          <i class="fa-solid fa-calendar-plus"></i>
        </div>
        <div>
          <h3>Add availability</h3>
          <p>Choose a day, a start time, the subject, and the price.</p>
        </div>
      </div>

      <div class="form-group">
        <label class="input-label" for="slotDay">Day</label>
        <select class="select-field" id="slotDay">
          <option value="">Select a day...</option>
        </select>
        <div class="error-message" id="slotDayError">Please pick a day</div>
      </div>

      <div class="form-group">
        <label class="input-label" for="slotTime">Start time (1-hour slot)</label>
        <select class="select-field" id="slotTime">
          <option value="">Select a time...</option>
        </select>
        <div class="error-message" id="slotTimeError">Please pick a time</div>
      </div>

      <div class="form-group">
        <label class="input-label" for="slotSubject">Subject</label>
        <select class="select-field" id="slotSubject">
          <option value="">Select a subject...</option>
        </select>
        <div class="error-message" id="slotSubjectError">Please pick a subject</div>
      </div>

      <div class="form-group">
        <label class="input-label" for="slotPrice">Price per hour (R)</label>
        <input class="input-field" type="number" id="slotPrice" min="0" step="0.01" placeholder="e.g. 150.00">
        <div class="error-message" id="slotPriceError">Please enter a valid price (0 or more)</div>
      </div>

      <div class="modal-actions">
        <button class="btn" id="cancelSlot">Cancel</button>
        <button class="btn btn-primary" id="confirmSlot">
          <i class="fa-solid fa-check"></i>
          Add slot
        </button>
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="subjectModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon">
          <i class="fa-solid fa-book"></i>
        </div>
        <div>
          <h3>Add a subject</h3>
          <p>What can you tutor?</p>
        </div>
      </div>

      <div class="form-group">
        <label class="input-label" for="newSubject">Subject name</label>
        <input class="input-field" type="text" id="newSubject" placeholder="e.g. Java, C#, Mathematics">
        <div class="error-message" id="newSubjectError">Please enter a subject</div>
      </div>

      <div class="modal-actions">
        <button class="btn" id="cancelSubject">Cancel</button>
        <button class="btn btn-primary" id="confirmSubject">
          <i class="fa-solid fa-plus"></i>
          Add subject
        </button>
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="confirmModal">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-icon danger">
          <i class="fa-solid fa-trash"></i>
        </div>
        <div>
          <h3 id="confirmTitle">Remove this item?</h3>
          <p id="confirmMessage">This action cannot be undone.</p>
        </div>
      </div>
      <div class="modal-actions">
        <button class="btn" id="cancelConfirm">Cancel</button>
        <button class="btn btn-danger" id="confirmDelete">
          <i class="fa-solid fa-trash"></i>
          Remove
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
      schedules: "user_b6a3d990_rea_lecturer_schedule",
      subjects:  "user_b6a3d990_rea_lecturer_subjects"
    };

    const CURRENT_USER = {
      name: <?php echo json_encode($fullname); ?>,
      email: <?php echo json_encode($email); ?>
    };

    const HOURS = [
      "08:00", "09:00", "10:00", "11:00", "12:00",
      "13:00", "14:00", "15:00", "16:00", "17:00",
      "18:00", "19:00", "20:00"
    ];

    const DAYS = [
      { key: 1, name: "Monday" },
      { key: 2, name: "Tuesday" },
      { key: 3, name: "Wednesday" },
      { key: 4, name: "Thursday" },
      { key: 5, name: "Friday" },
      { key: 6, name: "Saturday" },
      { key: 0, name: "Sunday" }
    ];

    const addSlotBtn = document.getElementById("addSlotBtn");
    const weekGrid = document.getElementById("weekGrid");
    const subjectList = document.getElementById("subjectList");
    const statSlots = document.getElementById("statSlots");
    const statHours = document.getElementById("statHours");
    const statSubjects = document.getElementById("statSubjects");

    const slotModal = document.getElementById("slotModal");
    const slotDay = document.getElementById("slotDay");
    const slotTime = document.getElementById("slotTime");
    const slotSubject = document.getElementById("slotSubject");
    const slotPrice = document.getElementById("slotPrice");
    const slotDayError = document.getElementById("slotDayError");
    const slotTimeError = document.getElementById("slotTimeError");
    const slotSubjectError = document.getElementById("slotSubjectError");
    const slotPriceError = document.getElementById("slotPriceError");
    const cancelSlot = document.getElementById("cancelSlot");
    const confirmSlot = document.getElementById("confirmSlot");

    const subjectModal = document.getElementById("subjectModal");
    const newSubject = document.getElementById("newSubject");
    const newSubjectError = document.getElementById("newSubjectError");
    const cancelSubject = document.getElementById("cancelSubject");
    const confirmSubject = document.getElementById("confirmSubject");

    const confirmModal = document.getElementById("confirmModal");
    const confirmTitle = document.getElementById("confirmTitle");
    const confirmMessage = document.getElementById("confirmMessage");
    const cancelConfirm = document.getElementById("cancelConfirm");
    const confirmDelete = document.getElementById("confirmDelete");

    const toast = document.getElementById("toast");
    const toastIcon = document.getElementById("toastIcon");
    const toastMessage = document.getElementById("toastMessage");

    let subjects = [];
    let slots = [];
    let toastTimer = null;
    let pendingDelete = null;

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
        map[d.key] = {
          name: d.name,
          date: date.toLocaleDateString(undefined, { month: "short", day: "numeric" }),
          iso: date.toISOString().slice(0, 10),
          isToday: date.toDateString() === today.toDateString()
        };
      });
      return map;
    }

    function openConfirm(title, message, onConfirm) {
      confirmTitle.textContent = title;
      confirmMessage.textContent = message;
      pendingDelete = onConfirm;
      confirmModal.classList.add("show");
    }

    function closeConfirm() {
      confirmModal.classList.remove("show");
      pendingDelete = null;
    }

    cancelConfirm.addEventListener("click", closeConfirm);

    confirmModal.addEventListener("click", (e) => {
      if (e.target === confirmModal) closeConfirm();
    });

    confirmDelete.addEventListener("click", async () => {
      if (!pendingDelete) {
        closeConfirm();
        return;
      }
      const fn = pendingDelete;
      closeConfirm();
      await fn();
    });

    function renderSubjectList() {
      subjectList.innerHTML = "";

      subjects.forEach(s => {
        const chip = document.createElement("span");
        chip.className = "subject-chip";
        chip.innerHTML =
          escapeHtml(s.name) +
          ' <i class="fa-solid fa-trash" title="Remove subject"></i>';

        chip.querySelector("i").addEventListener("click", () => {
          openConfirm(
            "Remove subject?",
            'Remove "' + s.name + '" from your subjects?',
            async () => {
              subjects = subjects.filter(x => x.id !== s.id);
              saveLocal();
              renderSubjectList();
              renderWeek();
              updateStats();

              await apiDelete(T.subjects, { id: s.id });
              showToast("Subject removed.", "success");
            }
          );
        });

        subjectList.appendChild(chip);
      });

      const addBtn = document.createElement("button");
      addBtn.className = "subject-add";
      addBtn.innerHTML = '<i class="fa-solid fa-plus"></i> Add';
      addBtn.addEventListener("click", openSubjectModal);
      subjectList.appendChild(addBtn);
    }

    function renderWeek() {
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

        const daySlots = slots
          .filter(s => Number(s.day) === d.key)
          .sort((a, b) => a.time.localeCompare(b.time));

        if (daySlots.length === 0) {
          const empty = document.createElement("div");
          empty.className = "day-empty";
          empty.textContent = "No slots";
          slotsEl.appendChild(empty);
        } else {
          daySlots.forEach(s => {
            const slot = document.createElement("div");
            slot.className = "slot";

            const time = document.createElement("div");
            time.className = "slot-time";
            time.textContent = s.time + " - " + addHour(s.time);

            const subj = document.createElement("div");
            subj.className = "slot-subject";
            subj.textContent = s.subject;

            const price = document.createElement("div");
            price.className = "slot-price";
            price.textContent = formatPrice(s.price);

            const rm = document.createElement("button");
            rm.className = "slot-remove";
            rm.title = "Remove slot";
            rm.innerHTML = '<i class="fa-solid fa-trash"></i>';
            rm.addEventListener("click", (e) => {
              e.stopPropagation();
              openConfirm(
                "Remove slot?",
                'Remove ' + s.time + " - " + addHour(s.time) + " (" + s.subject + ")?",
                async () => {
                  slots = slots.filter(x => x.id !== s.id);
                  saveLocal();
                  renderWeek();
                  updateStats();

                  await apiDelete(T.schedules, { id: s.id });
                  showToast("Slot removed.", "success");
                }
              );
            });

            slot.appendChild(rm);
            slot.appendChild(time);
            slot.appendChild(subj);
            slot.appendChild(price);
            slotsEl.appendChild(slot);
          });
        }

        col.appendChild(slotsEl);
        weekGrid.appendChild(col);
      });
    }

    function updateStats() {
      statSlots.textContent = slots.length;
      statHours.textContent = slots.length + "h";
      statSubjects.textContent = subjects.length;
    }

    function saveLocal() {
      try {
        localStorage.setItem("tutorlink_lecturer_schedule", JSON.stringify({ subjects, slots }));
      } catch (e) {}
    }

    async function loadData() {
      const [allSubjects, allSlots] = await Promise.all([
        apiFilter(T.subjects, {}),
        apiFilter(T.schedules, {})
      ]);

      subjects = allSubjects
        .filter(s => s.email === CURRENT_USER.email)
        .map(s => ({ id: s.id, name: s.name }));

      slots = allSlots
        .filter(s => s.email === CURRENT_USER.email)
        .map(s => ({
          id: s.id,
          day: Number(s.day),
          time: s.time,
          subject: s.subject,
          price: Number(s.price || 0)
        }));

      saveLocal();
      renderSubjectList();
      renderWeek();
      updateStats();
    }

    function openSlotModal() {
      if (subjects.length === 0) {
        showToast("Add a subject first.", "error");
        openSubjectModal();
        return;
      }

      slotDay.value = "";
      slotTime.value = "";
      slotSubject.value = "";
      slotPrice.value = "";

      [slotDay, slotTime, slotSubject, slotPrice].forEach(i => i.classList.remove("error"));
      [slotDayError, slotTimeError, slotSubjectError, slotPriceError].forEach(e => e.classList.remove("show"));

      const dates = getWeekDates();

      slotDay.innerHTML = '<option value="">Select a day...</option>';
      DAYS.forEach(d => {
        const opt = document.createElement("option");
        opt.value = d.key;
        opt.textContent = d.name + " (" + dates[d.key].date + ")";
        slotDay.appendChild(opt);
      });

      slotTime.innerHTML = '<option value="">Select a time...</option>';
      HOURS.forEach(h => {
        const opt = document.createElement("option");
        opt.value = h;
        opt.textContent = h + " - " + addHour(h);
        slotTime.appendChild(opt);
      });

      slotSubject.innerHTML = '<option value="">Select a subject...</option>';
      subjects.forEach(s => {
        const opt = document.createElement("option");
        opt.value = s.name;
        opt.textContent = s.name;
        slotSubject.appendChild(opt);
      });

      slotModal.classList.add("show");
    }

    function closeSlotModal() {
      slotModal.classList.remove("show");
    }

    function validateSlot() {
      let valid = true;
      [slotDay, slotTime, slotSubject, slotPrice].forEach(i => i.classList.remove("error"));
      [slotDayError, slotTimeError, slotSubjectError, slotPriceError].forEach(e => e.classList.remove("show"));

      if (!slotDay.value) {
        slotDay.classList.add("error");
        slotDayError.classList.add("show");
        valid = false;
      }
      if (!slotTime.value) {
        slotTime.classList.add("error");
        slotTimeError.classList.add("show");
        valid = false;
      }
      if (!slotSubject.value) {
        slotSubject.classList.add("error");
        slotSubjectError.classList.add("show");
        valid = false;
      }

      const priceValue = slotPrice.value.trim();
      if (priceValue === "" || isNaN(Number(priceValue)) || Number(priceValue) < 0) {
        slotPrice.classList.add("error");
        slotPriceError.classList.add("show");
        valid = false;
      }

      return valid;
    }

    function openSubjectModal() {
      newSubject.value = "";
      newSubject.classList.remove("error");
      newSubjectError.classList.remove("show");
      subjectModal.classList.add("show");
      setTimeout(() => newSubject.focus(), 50);
    }

    function closeSubjectModal() {
      subjectModal.classList.remove("show");
    }

    addSlotBtn.addEventListener("click", openSlotModal);
    cancelSlot.addEventListener("click", closeSlotModal);

    slotModal.addEventListener("click", (e) => {
      if (e.target === slotModal) closeSlotModal();
    });

    cancelSubject.addEventListener("click", closeSubjectModal);

    subjectModal.addEventListener("click", (e) => {
      if (e.target === subjectModal) closeSubjectModal();
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        if (slotModal.classList.contains("show")) closeSlotModal();
        if (subjectModal.classList.contains("show")) closeSubjectModal();
        if (confirmModal.classList.contains("show")) closeConfirm();
      }
    });

    confirmSlot.addEventListener("click", async () => {
      if (!validateSlot()) return;

      const day = Number(slotDay.value);
      const time = slotTime.value;
      const subject = slotSubject.value;
      const price = Number(slotPrice.value).toFixed(2);

      const exists = slots.some(s => Number(s.day) === day && s.time === time);
      if (exists) {
        showToast("You already have a slot at that time.", "error");
        return;
      }

      const optimistic = {
        id: "tmp_" + Date.now(),
        day: day,
        time: time,
        subject: subject,
        price: Number(price)
      };

      slots.push(optimistic);
      saveLocal();
      renderWeek();
      updateStats();
      closeSlotModal();

      await api({
        type: "insert_slot",
        email: CURRENT_USER.email,
        day: day,
        time: time,
        subject: subject,
        price: price
      });

      showToast("Slot added.", "success");
    });

    confirmSubject.addEventListener("click", async () => {
      const name = newSubject.value.trim();
      if (!name) {
        newSubject.classList.add("error");
        newSubjectError.classList.add("show");
        return;
      }

      const exists = subjects.some(s => s.name.toLowerCase() === name.toLowerCase());
      if (exists) {
        showToast("That subject is already in your list.", "error");
        return;
      }

      const optimistic = {
        id: "tmp_" + Date.now(),
        name: name
      };

      subjects.push(optimistic);
      saveLocal();
      renderSubjectList();
      updateStats();
      closeSubjectModal();

      await api({
        type: "insert_subject",
        email: CURRENT_USER.email,
        name: name
      });

      showToast("Subject added.", "success");
    });

    newSubject.addEventListener("input", () => {
      if (newSubject.classList.contains("error") && newSubject.value.trim()) {
        newSubject.classList.remove("error");
        newSubjectError.classList.remove("show");
      }
    });

    slotPrice.addEventListener("input", () => {
      if (slotPrice.classList.contains("error") && slotPrice.value.trim() !== "" && Number(slotPrice.value) >= 0) {
        slotPrice.classList.remove("error");
        slotPriceError.classList.remove("show");
      }
    });

    loadData();
  </script>
</body>

</html>