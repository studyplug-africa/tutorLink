<?php
session_start();

if( $_SESSION["role"]=="lecturer"){









if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$fullname = $_SESSION["fullname"] ?? "User";
$email    = $_SESSION["email"] ?? "";
$role     = $_SESSION["role"] ?? "student";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home · TutorLink</title>
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
      overflow-y: auto;
    }

    .wrap {
      width: 100%;
      padding: 2rem 1.5rem 3rem;
    }

    .head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .head h1 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #0a4b6e;
      letter-spacing: -0.02em;
      margin-bottom: 0.3rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .head h1 i {
      color: #0b6e4f;
      font-size: 1.2rem;
    }

    .head p {
      font-size: 0.9rem;
      color: #6b8b7e;
    }

    .head .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.4rem 0.85rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.78rem;
      font-weight: 600;
      color: #0b6e4f;
      text-transform: capitalize;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 0.85rem;
      margin-bottom: 2rem;
    }

    .stat {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
    }

    .stat-label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #6b8b7e;
      margin-bottom: 0.4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-label i {
      color: #0a4b6e;
      font-size: 0.78rem;
    }

    .stat-value {
      font-size: 1.6rem;
      font-weight: 700;
      color: #0a4b6e;
      letter-spacing: -0.02em;
    }

    .stat-foot {
      font-size: 0.72rem;
      color: #8a9e94;
      margin-top: 0.3rem;
    }

    .section {
      margin-bottom: 2rem;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.85rem;
    }

    .section-header h2 {
      font-size: 1rem;
      font-weight: 700;
      color: #0a4b6e;
    }

    .section-header button {
      font-size: 0.8rem;
      font-weight: 600;
      color: #0b6e4f;
      background: transparent;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      font-family: inherit;
    }

    .section-header button:hover {
      text-decoration: underline;
    }

    .panel {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
    }

    .empty {
      padding: 1.5rem 0.5rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.85rem;
      line-height: 1.6;
    }

    .empty i {
      display: block;
      font-size: 1.5rem;
      color: #cfe3da;
      margin-bottom: 0.5rem;
    }

    .empty strong {
      color: #0a4b6e;
      font-weight: 600;
      display: block;
      margin-bottom: 0.25rem;
    }

    .list-row {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f0f7f4;
    }

    .list-row:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .list-row:first-child {
      padding-top: 0;
    }

    .list-avatar {
      width: 38px;
      height: 38px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-weight: 700;
      font-size: 0.8rem;
      flex-shrink: 0;
    }

    .list-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 5px;
    }

    .list-body {
      flex: 1;
      min-width: 0;
    }

    .list-title {
      font-size: 0.88rem;
      font-weight: 600;
      color: #0a4b6e;
      margin-bottom: 0.1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .list-meta {
      font-size: 0.75rem;
      color: #6b8b7e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .list-badge {
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 0.2rem 0.5rem;
      border-radius: 5px;
      border: 1px solid #e4f0ea;
      flex-shrink: 0;
      background: #ffffff;
      cursor: pointer;
      font-family: inherit;
    }

    .list-badge.pending {
      color: #d39e00;
      border-color: #f0e0b8;
      background: #fef9ec;
    }

    .list-badge.accepted {
      color: #0b6e4f;
      border-color: #cfe3da;
      background: #f6fbf8;
    }

    .list-badge.rejected {
      color: #c0392b;
      border-color: #f5c6c1;
      background: #fdecea;
    }

    .chip-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
    }

    .chip {
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

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .profit-row {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 0;
      font-size: 0.85rem;
      border-bottom: 1px solid #f0f7f4;
    }

    .profit-row:last-child {
      border-bottom: none;
    }

    .profit-row span:first-child {
      color: #6b8b7e;
    }

    .profit-row span:last-child {
      font-weight: 700;
    }

    .profit-row.profit span:last-child {
      color: #0b6e4f;
    }

    .profit-row.loss span:last-child {
      color: #c0392b;
    }

    .profit-row.net span:last-child {
      color: #0a4b6e;
      font-size: 1rem;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .feature-card {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      overflow: hidden;
      cursor: pointer;
      transition: border-color 0.15s, transform 0.15s;
      background: #ffffff;
    }

    .feature-card:hover {
      border-color: #0b6e4f;
      transform: translateY(-1px);
    }

    .feature-img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      display: block;
    }

    .feature-icon-wrap {
      width: 100%;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: #ffffff;
    }

    .feature-icon-wrap.green {
      background: linear-gradient(135deg, #0b6e4f, #16a97a);
    }

    .feature-icon-wrap.blue {
      background: linear-gradient(135deg, #0a4b6e, #1668a8);
    }

    .feature-icon-wrap.purple {
      background: linear-gradient(135deg, #6a3fa0, #9b59b6);
    }

    .feature-icon-wrap.red {
      background: linear-gradient(135deg, #c0392b, #e74c3c);
    }

    .feature-body {
      padding: 0.9rem 1rem 1rem;
    }

    .feature-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.25rem;
    }

    .feature-desc {
      font-size: 0.8rem;
      color: #6b8b7e;
      line-height: 1.5;
    }

    @media (max-width: 700px) {
      .grid-2 {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .wrap {
        padding: 1.25rem 1rem 2rem;
      }

      .head h1 {
        font-size: 1.3rem;
      }

      .stat-value {
        font-size: 1.4rem;
      }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="head">
      <div>
        <h1 id="greeting">
          <i class="fa-solid fa-hand-sparkles"></i>
          Welcome back
        </h1>
        <p>Here's everything happening on your TutorLink account.</p>
      </div>
      <span class="role-badge">
        <i class="fa-solid fa-user"></i>
        <?php echo htmlspecialchars($role); ?>
      </span>
    </div>

    <div class="stats-grid">
      <div class="stat">
        <div class="stat-label">
          <span>Subjects</span>
          <i class="fa-solid fa-book"></i>
        </div>
        <div class="stat-value" id="statSubjects">0</div>
        <div class="stat-foot">subjects you teach</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Weekly slots</span>
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div class="stat-value" id="statSlots">0</div>
        <div class="stat-foot">hours available</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Resources</span>
          <i class="fa-solid fa-folder-open"></i>
        </div>
        <div class="stat-value" id="statResources">0</div>
        <div class="stat-foot">materials uploaded</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Pending</span>
          <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-value" id="statPending">0</div>
        <div class="stat-foot">requests to review</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Accepted</span>
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-value" id="statAccepted">0</div>
        <div class="stat-foot">sessions approved</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Net profit</span>
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="stat-value" id="statNet">R 0.00</div>
        <div class="stat-foot">earned minus lost</div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Quick access</h2>
      </div>
      <div class="feature-grid">
        <div class="feature-card" onclick="navigate('schedule')">
          <div class="feature-icon-wrap green">
            <i class="fa-solid fa-calendar-days"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Schedule</div>
            <div class="feature-desc">Set your weekly availability, subjects and prices.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('requests')">
          <div class="feature-icon-wrap blue">
            <i class="fa-solid fa-inbox"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Requests</div>
            <div class="feature-desc">Accept or reject student session requests.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('resources')">
          <div class="feature-icon-wrap purple">
            <i class="fa-solid fa-folder-open"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Resources</div>
            <div class="feature-desc">Upload PDFs, videos, links and documents.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('profit')">
          <div class="feature-icon-wrap red">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Profit</div>
            <div class="feature-desc">Track your earnings and lost opportunities.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid-2">
      <div class="section">
        <div class="section-header">
          <h2>Subjects you teach</h2>
          <button onclick="navigate('schedule')">
            Manage <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
        <div class="panel">
          <div class="chip-row" id="subjectChips"></div>
        </div>
      </div>

      <div class="section">
        <div class="section-header">
          <h2>Profit summary</h2>
          <button onclick="navigate('profit')">
            Details <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
        <div class="panel">
          <div class="profit-row profit">
            <span>Gross profit</span>
            <span id="sumGross">R 0.00</span>
          </div>
          <div class="profit-row loss">
            <span>Lost profit</span>
            <span id="sumLoss">-R 0.00</span>
          </div>
          <div class="profit-row net">
            <span>Net profit</span>
            <span id="sumNet">R 0.00</span>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Recent session requests</h2>
        <button onclick="navigate('requests')">
          View all <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
      <div class="panel">
        <div id="requestList"></div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Recent resources</h2>
        <button onclick="navigate('resources')">
          Manage <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
      <div class="panel">
        <div id="resourceList"></div>
      </div>
    </div>
  </div>

  <script>
    const API = "insert.php";

    const T = {
      users:     "user_b6a3d990_rea_users",
      subjects:  "user_b6a3d990_rea_lecturer_subjects",
      schedules: "user_b6a3d990_rea_lecturer_schedule",
      bookings:  "user_b6a3d990_rea_bookings",
      resources: "user_b6a3d990_rea_lecturer_resource"
    };

    const CURRENT_USER = {
      name: <?php echo json_encode($fullname); ?>,
      email: <?php echo json_encode($email); ?>
    };

    const DAYS_NAMES = {
      0: "Sunday", 1: "Monday", 2: "Tuesday",
      3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"
    };

    const greeting = document.getElementById("greeting");
    const statSubjects = document.getElementById("statSubjects");
    const statSlots = document.getElementById("statSlots");
    const statResources = document.getElementById("statResources");
    const statPending = document.getElementById("statPending");
    const statAccepted = document.getElementById("statAccepted");
    const statNet = document.getElementById("statNet");

    const subjectChips = document.getElementById("subjectChips");
    const sumGross = document.getElementById("sumGross");
    const sumLoss = document.getElementById("sumLoss");
    const sumNet = document.getElementById("sumNet");

    const requestList = document.getElementById("requestList");
    const resourceList = document.getElementById("resourceList");

    function navigate(url) {
   
        window.location.href = url;
      
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
      return d.toLocaleDateString(undefined, { month: "short", day: "numeric" });
    }

    function setGreeting() {
      const h = new Date().getHours();
      let text = "Welcome back";
      if (h < 12) text = "Good morning";
      else if (h < 18) text = "Good afternoon";
      else text = "Good evening";

      greeting.innerHTML =
        '<i class="fa-solid fa-hand-sparkles"></i>' +
        text + ", " + escapeHtml(CURRENT_USER.name.split(" ")[0]);
    }

    async function loadOverview() {
      const [subjects, schedules, bookings, resources] = await Promise.all([
        apiFilter(T.subjects, {}),
        apiFilter(T.schedules, {}),
        apiFilter(T.bookings, {}),
        apiFilter(T.resources, {})
      ]);

      const mySubjects = subjects.filter(s => String(s.email) === String(CURRENT_USER.email));
      const mySlots = schedules.filter(s => String(s.email) === String(CURRENT_USER.email));
      const myBookings = bookings
        .filter(b => String(b.tutor_email) === String(CURRENT_USER.email))
        .map(b => ({ ...b, status: b.status || "pending", price: Number(b.price || 0) }));
      const myResources = resources.filter(r => String(r.email) === String(CURRENT_USER.email));

      statSubjects.textContent = mySubjects.length;
      statSlots.textContent = mySlots.length;
      statResources.textContent = myResources.length;

      const pending = myBookings.filter(b => b.status === "pending").length;
      const accepted = myBookings.filter(b => b.status === "accepted").length;

      statPending.textContent = pending;
      statAccepted.textContent = accepted;

      let gross = 0;
      let lost = 0;
      myBookings.forEach(b => {
        if (b.status === "accepted") gross += b.price;
        else if (b.status === "rejected") lost += b.price;
      });
      const net = gross - lost;

      statNet.textContent = formatMoney(net);
      statNet.style.color = net < 0 ? "#c0392b" : "#0a4b6e";

      sumGross.textContent = formatMoney(gross);
      sumLoss.textContent = "-" + formatMoney(lost);
      sumNet.textContent = formatMoney(net);

      subjectChips.innerHTML = "";
      if (mySubjects.length === 0) {
        subjectChips.innerHTML = '<span style="font-size:0.82rem;color:#8a9e94;">No subjects yet — add them from your schedule.</span>';
      } else {
        mySubjects.slice(0, 8).forEach(s => {
          const chip = document.createElement("span");
          chip.className = "chip";
          chip.textContent = s.name;
          subjectChips.appendChild(chip);
        });
        if (mySubjects.length > 8) {
          const extra = document.createElement("span");
          extra.className = "chip";
          extra.textContent = "+" + (mySubjects.length - 8) + " more";
          subjectChips.appendChild(extra);
        }
      }

      renderRequests(myBookings);
      renderResources(myResources);
    }

    function renderRequests(bookings) {
      const recent = bookings
        .filter(b => b.status === "pending")
        .sort((a, b) => new Date(b.sent) - new Date(a.sent))
        .slice(0, 4);

      requestList.innerHTML = "";

      if (recent.length === 0) {
        requestList.innerHTML =
          '<div class="empty">' +
            '<i class="fa-solid fa-inbox"></i>' +
            '<strong>All caught up</strong>' +
            'No pending requests right now.' +
          '</div>';
        return;
      }

      recent.forEach(b => {
        const row = document.createElement("div");
        row.className = "list-row";

        const avatar = document.createElement("div");
        avatar.className = "list-avatar";
        avatar.style.background = colorFromString(b.student_name || b.student_email);
        avatar.textContent = initials(b.student_name || b.student_email);

        const body = document.createElement("div");
        body.className = "list-body";

        const title = document.createElement("div");
        title.className = "list-title";
        title.textContent = b.subject || "Session";

        const meta = document.createElement("div");
        meta.className = "list-meta";
        meta.textContent =
          (b.student_name || b.student_email) + " · " +
          (DAYS_NAMES[b.day] || b.day_name || "") + " · " +
          b.time + " - " + addHour(b.time) + " · " + formatMoney(b.price);

        body.appendChild(title);
        body.appendChild(meta);

        const badge = document.createElement("span");
        badge.className = "list-badge pending";
        badge.textContent = "pending";

        row.appendChild(avatar);
        row.appendChild(body);
        row.appendChild(badge);
        requestList.appendChild(row);
      });
    }

    function renderResources(resources) {
      const recent = resources
        .sort((a, b) => new Date(b.created_at || b.sent) - new Date(a.created_at || a.sent))
        .slice(0, 4);

      resourceList.innerHTML = "";

      if (recent.length === 0) {
        resourceList.innerHTML =
          '<div class="empty">' +
            '<i class="fa-solid fa-folder-open"></i>' +
            '<strong>No resources yet</strong>' +
            'Add materials from the Resources page.' +
          '</div>';
        return;
      }

      recent.forEach(r => {
        const row = document.createElement("div");
        row.className = "list-row";

        const avatar = document.createElement("div");
        avatar.className = "list-avatar";
        avatar.style.background = colorFromString(r.name || "Resource");
        avatar.innerHTML = '<i class="fa-solid fa-file"></i>';

        const body = document.createElement("div");
        body.className = "list-body";

        const title = document.createElement("div");
        title.className = "list-title";
        title.textContent = r.name || "Untitled";

        const meta = document.createElement("div");
        meta.className = "list-meta";
        meta.textContent = (r.type || "other").toUpperCase() + " · " + formatDate(r.created_at || r.sent);

        body.appendChild(title);
        body.appendChild(meta);

        const open = document.createElement("button");
        open.type = "button";
        open.className = "list-badge accepted";
        open.textContent = "open";
        open.addEventListener("click", () => window.open(r.url, "_blank"));

        row.appendChild(avatar);
        row.appendChild(body);
        row.appendChild(open);
        resourceList.appendChild(row);
      });
    }

    setGreeting();
    loadOverview();
  </script>
</body>

</html>
<?php 


}else{
    ?>

<?php

if (!isset($_SESSION["user_id"])) {
    header("Location: index");
    exit;
}

$fullname = $_SESSION["fullname"] ?? "User";
$email    = $_SESSION["email"] ?? "";
$role     = $_SESSION["role"] ?? "student";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
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
      overflow-y: auto;
    }

    .wrap {
      width: 100%;
      padding: 2rem 1.5rem 3rem;
    }

    .head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .head h1 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #0a4b6e;
      letter-spacing: -0.02em;
      margin-bottom: 0.3rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .head h1 i {
      color: #0b6e4f;
      font-size: 1.2rem;
    }

    .head p {
      font-size: 0.9rem;
      color: #6b8b7e;
    }

    .head .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.4rem 0.85rem;
      border: 1px solid #cfe3da;
      border-radius: 5px;
      font-size: 0.78rem;
      font-weight: 600;
      color: #0b6e4f;
      text-transform: capitalize;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 0.85rem;
      margin-bottom: 2rem;
    }

    .stat {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
    }

    .stat-label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #6b8b7e;
      margin-bottom: 0.4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-label i {
      color: #0a4b6e;
      font-size: 0.78rem;
    }

    .stat-value {
      font-size: 1.6rem;
      font-weight: 700;
      color: #0a4b6e;
      letter-spacing: -0.02em;
    }

    .stat-foot {
      font-size: 0.72rem;
      color: #8a9e94;
      margin-top: 0.3rem;
    }

    .section {
      margin-bottom: 2rem;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.85rem;
    }

    .section-header h2 {
      font-size: 1rem;
      font-weight: 700;
      color: #0a4b6e;
    }

    .section-header button {
      font-size: 0.8rem;
      font-weight: 600;
      color: #0b6e4f;
      background: transparent;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      font-family: inherit;
    }

    .section-header button:hover {
      text-decoration: underline;
    }

    .panel {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      padding: 1rem 1.15rem;
    }

    .empty {
      padding: 1.5rem 0.5rem;
      text-align: center;
      color: #8a9e94;
      font-size: 0.85rem;
      line-height: 1.6;
    }

    .empty i {
      display: block;
      font-size: 1.5rem;
      color: #cfe3da;
      margin-bottom: 0.5rem;
    }

    .empty strong {
      color: #0a4b6e;
      font-weight: 600;
      display: block;
      margin-bottom: 0.25rem;
    }

    .list-row {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f0f7f4;
    }

    .list-row:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .list-row:first-child {
      padding-top: 0;
    }

    .list-avatar {
      width: 38px;
      height: 38px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-weight: 700;
      font-size: 0.8rem;
      flex-shrink: 0;
    }

    .list-body {
      flex: 1;
      min-width: 0;
    }

    .list-title {
      font-size: 0.88rem;
      font-weight: 600;
      color: #0a4b6e;
      margin-bottom: 0.1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .list-meta {
      font-size: 0.75rem;
      color: #6b8b7e;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .list-badge {
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 0.2rem 0.5rem;
      border-radius: 5px;
      border: 1px solid #e4f0ea;
      flex-shrink: 0;
      background: #ffffff;
      cursor: pointer;
      font-family: inherit;
    }

    .list-badge.pending {
      color: #d39e00;
      border-color: #f0e0b8;
      background: #fef9ec;
    }

    .list-badge.accepted {
      color: #0b6e4f;
      border-color: #cfe3da;
      background: #f6fbf8;
    }

    .list-badge.rejected {
      color: #c0392b;
      border-color: #f5c6c1;
      background: #fdecea;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .feature-card {
      border: 1px solid #e4f0ea;
      border-radius: 5px;
      overflow: hidden;
      cursor: pointer;
      transition: border-color 0.15s, transform 0.15s;
      background: #ffffff;
    }

    .feature-card:hover {
      border-color: #0b6e4f;
      transform: translateY(-1px);
    }

    .feature-icon-wrap {
      width: 100%;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: #ffffff;
    }

    .feature-icon-wrap.green {
      background: linear-gradient(135deg, #0b6e4f, #16a97a);
    }

    .feature-icon-wrap.blue {
      background: linear-gradient(135deg, #0a4b6e, #1668a8);
    }

    .feature-icon-wrap.purple {
      background: linear-gradient(135deg, #6a3fa0, #9b59b6);
    }

    .feature-icon-wrap.red {
      background: linear-gradient(135deg, #c0392b, #e74c3c);
    }

    .feature-body {
      padding: 0.9rem 1rem 1rem;
    }

    .feature-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.25rem;
    }

    .feature-desc {
      font-size: 0.8rem;
      color: #6b8b7e;
      line-height: 1.5;
    }

    .upcoming-card {
      border: 1px solid #cfe3da;
      border-radius: 5px;
      padding: 1rem 1.15rem;
      background: #f6fbf8;
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .upcoming-avatar {
      width: 46px;
      height: 46px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-weight: 700;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .upcoming-info {
      flex: 1;
      min-width: 200px;
    }

    .upcoming-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.15rem;
    }

    .upcoming-meta {
      font-size: 0.8rem;
      color: #6b8b7e;
      line-height: 1.5;
    }

    .upcoming-meta strong {
      color: #0a2e3f;
    }

    .upcoming-actions {
      display: flex;
      gap: 0.5rem;
      flex-shrink: 0;
    }

    .btn-mini {
      padding: 0.5rem 0.9rem;
      border-radius: 5px;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: all 0.15s;
    }

    .btn-mini:hover {
      background: #0b6e4f;
      color: #ffffff;
    }

    .btn-mini.fill {
      background: #0b6e4f;
      color: #ffffff;
    }

    .btn-mini.fill:hover {
      background: #0a5a40;
    }

    @media (max-width: 480px) {
      .wrap {
        padding: 1.25rem 1rem 2rem;
      }

      .head h1 {
        font-size: 1.3rem;
      }

      .stat-value {
        font-size: 1.4rem;
      }

      .upcoming-actions {
        width: 100%;
      }

      .upcoming-actions .btn-mini {
        flex: 1;
        justify-content: center;
      }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="head">
      <div>
        <h1 id="greeting">
          <i class="fa-solid fa-hand-sparkles"></i>
          Welcome back
        </h1>
        <p>Here's what's happening with your learning today.</p>
      </div>
      <span class="role-badge">
        <i class="fa-solid fa-user"></i>
        <?php echo htmlspecialchars($role); ?>
      </span>
    </div>

    <div class="stats-grid">
      <div class="stat">
        <div class="stat-label">
          <span>Tutors</span>
          <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div class="stat-value" id="statTutors">0</div>
        <div class="stat-foot">available to book</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>My sessions</span>
          <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-value" id="statSessions">0</div>
        <div class="stat-foot">total booked</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Pending</span>
          <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-value" id="statPending">0</div>
        <div class="stat-foot">awaiting tutor approval</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Accepted</span>
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-value" id="statAccepted">0</div>
        <div class="stat-foot">approved by tutor</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Resources</span>
          <i class="fa-solid fa-folder-open"></i>
        </div>
        <div class="stat-value" id="statResources">0</div>
        <div class="stat-foot">from tutors</div>
      </div>

      <div class="stat">
        <div class="stat-label">
          <span>Study groups</span>
          <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-value" id="statGroups">0</div>
        <div class="stat-foot">groups available</div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Quick access</h2>
      </div>
      <div class="feature-grid">
        <div class="feature-card" onclick="navigate('find_tutor')">
          <div class="feature-icon-wrap green">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Find a tutor</div>
            <div class="feature-desc">Browse tutors, see their subjects and book a session.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('my_sessions')">
          <div class="feature-icon-wrap blue">
            <i class="fa-solid fa-calendar-days"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">My sessions</div>
            <div class="feature-desc">Track your bookings, see statuses and meeting links.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('tutor_resources')">
          <div class="feature-icon-wrap purple">
            <i class="fa-solid fa-folder-open"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Resources</div>
            <div class="feature-desc">Browse PDFs, videos, links and documents from your tutors.</div>
          </div>
        </div>

        <div class="feature-card" onclick="navigate('study_groups')">
          <div class="feature-icon-wrap red">
            <i class="fa-solid fa-users"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Study groups</div>
            <div class="feature-desc">Join a group, chat with other students, share resources.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Upcoming session</h2>
        <button onclick="navigate('my_sessions')">
          View all <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
      <div id="upcomingWrap">
        <div class="panel">
          <div class="empty">
            <i class="fa-solid fa-calendar-days"></i>
            <strong>No upcoming sessions</strong>
            Book a tutor from the Find Tutor page to get started.
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Recent resources from tutors</h2>
        <button onclick="navigate('tutor_resources')">
          Browse all <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
      <div class="panel">
        <div id="resourceList"></div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>Popular study groups</h2>
        <button onclick="navigate('study_groups')">
          Browse all <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
      <div class="panel">
        <div id="groupList"></div>
      </div>
    </div>
  </div>

  <script>
    const API = "insert.php";

    const T = {
      users:     "user_b6a3d990_rea_users",
      subjects:  "user_b6a3d990_rea_lecturer_subjects",
      schedules: "user_b6a3d990_rea_lecturer_schedule",
      bookings:  "user_b6a3d990_rea_bookings",
      resources: "user_b6a3d990_rea_lecturer_resource",
      groups:    "user_b6a3d990_rea_study_groups"
    };

    const CURRENT_USER = {
      name: <?php echo json_encode($fullname); ?>,
      email: <?php echo json_encode($email); ?>
    };

    const DAYS_NAMES = {
      0: "Sunday", 1: "Monday", 2: "Tuesday",
      3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"
    };

    const greeting = document.getElementById("greeting");
    const statTutors = document.getElementById("statTutors");
    const statSessions = document.getElementById("statSessions");
    const statPending = document.getElementById("statPending");
    const statAccepted = document.getElementById("statAccepted");
    const statResources = document.getElementById("statResources");
    const statGroups = document.getElementById("statGroups");

    const upcomingWrap = document.getElementById("upcomingWrap");
    const resourceList = document.getElementById("resourceList");
    const groupList = document.getElementById("groupList");

    function navigate(url) {
     
        window.location.href = url;
      
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

    function formatMoney(value) {
      const n = Number(value);
      if (isNaN(n) || n <= 0) return "Free";
      return "R " + n.toFixed(2);
    }

    function formatDate(iso) {
      if (!iso) return "";
      const d = new Date(iso.replace(" ", "T"));
      if (isNaN(d)) return iso;
      return d.toLocaleDateString(undefined, { month: "short", day: "numeric" });
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

    function setGreeting() {
      const h = new Date().getHours();
      let text = "Welcome back";
      if (h < 12) text = "Good morning";
      else if (h < 18) text = "Good afternoon";
      else text = "Good evening";

      greeting.innerHTML =
        '<i class="fa-solid fa-hand-sparkles"></i>' +
        text + ", " + escapeHtml(CURRENT_USER.name.split(" ")[0]);
    }

    async function loadOverview() {
      const [users, subjects, bookings, resources, groups] = await Promise.all([
        apiFilter(T.users, {}),
        apiFilter(T.subjects, {}),
        apiFilter(T.bookings, {}),
        apiFilter(T.resources, {}),
        apiFilter(T.groups, {})
      ]);

      const tutorEmails = new Set(subjects.map(s => String(s.email).trim().toLowerCase()));
      const tutorNames = new Map();
      users.forEach(u => {
        if (u.email) tutorNames.set(String(u.email).trim().toLowerCase(), u.fullname || u.email);
      });

      const myBookings = bookings
        .filter(b => String(b.student_email) === String(CURRENT_USER.email))
        .map(b => ({ ...b, status: b.status || "pending", price: Number(b.price || 0) }));

      const pending = myBookings.filter(b => b.status === "pending").length;
      const accepted = myBookings.filter(b => b.status === "accepted").length;

      statTutors.textContent = tutorEmails.size;
      statSessions.textContent = myBookings.length;
      statPending.textContent = pending;
      statAccepted.textContent = accepted;
      statResources.textContent = resources.length;
      statGroups.textContent = groups.length;

      renderUpcoming(myBookings, tutorNames);
      renderResources(resources, tutorNames);
      renderGroups(groups);
    }

    function renderUpcoming(bookings, tutorNames) {
      const upcoming = bookings
        .filter(b => (b.status === "accepted" || b.status === "pending") && isUpcoming(b))
        .sort((a, b) => new Date(a.sent) - new Date(b.sent))[0];

      if (!upcoming) {
        upcomingWrap.innerHTML =
          '<div class="panel">' +
            '<div class="empty">' +
              '<i class="fa-solid fa-calendar-days"></i>' +
              '<strong>No upcoming sessions</strong>' +
              'Book a tutor from the Find Tutor page to get started.' +
            '</div>' +
          '</div>';
        return;
      }

      const tutorName = tutorNames.get(String(upcoming.tutor_email).trim().toLowerCase()) || upcoming.tutor_name || upcoming.tutor_email;

      upcomingWrap.innerHTML = "";

      const card = document.createElement("div");
      card.className = "upcoming-card";

      const avatar = document.createElement("div");
      avatar.className = "upcoming-avatar";
      avatar.style.background = colorFromString(tutorName);
      avatar.textContent = initials(tutorName);

      const info = document.createElement("div");
      info.className = "upcoming-info";
      info.innerHTML =
        '<div class="upcoming-title">' + escapeHtml(upcoming.subject || "Session") + '</div>' +
        '<div class="upcoming-meta">' +
          'with <strong>' + escapeHtml(tutorName) + '</strong><br>' +
          (DAYS_NAMES[upcoming.day] || upcoming.day_name || "") + ' · ' +
          upcoming.time + " - " + addHour(upcoming.time) + ' · ' +
          formatMoney(upcoming.price) +
        '</div>';

      const actions = document.createElement("div");
      actions.className = "upcoming-actions";

      const viewBtn = document.createElement("button");
      viewBtn.className = "btn-mini";
      viewBtn.innerHTML = '<i class="fa-solid fa-eye"></i> View';
      viewBtn.addEventListener("click", () => navigate("my_sessions"));
      actions.appendChild(viewBtn);

      if (upcoming.status === "accepted" && upcoming.meeting_link) {
        const joinBtn = document.createElement("button");
        joinBtn.className = "btn-mini fill";
        joinBtn.innerHTML = '<i class="fa-solid fa-video"></i> Join';
        joinBtn.addEventListener("click", () => window.open(upcoming.meeting_link, "_blank"));
        actions.appendChild(joinBtn);
      }

      card.appendChild(avatar);
      card.appendChild(info);
      card.appendChild(actions);
      upcomingWrap.appendChild(card);
    }

    function renderResources(resources, tutorNames) {
      const recent = resources
        .sort((a, b) => new Date(b.created_at || b.sent) - new Date(a.created_at || a.sent))
        .slice(0, 4);

      resourceList.innerHTML = "";

      if (recent.length === 0) {
        resourceList.innerHTML =
          '<div class="empty">' +
            '<i class="fa-solid fa-folder-open"></i>' +
            '<strong>No resources yet</strong>' +
            'Tutors haven\'t uploaded any materials yet.' +
          '</div>';
        return;
      }

      recent.forEach(r => {
        const tutorName = tutorNames.get(String(r.email).trim().toLowerCase()) || r.email;

        const row = document.createElement("div");
        row.className = "list-row";

        const avatar = document.createElement("div");
        avatar.className = "list-avatar";
        avatar.style.background = colorFromString(r.name || "Resource");
        avatar.innerHTML = '<i class="fa-solid fa-file"></i>';

        const body = document.createElement("div");
        body.className = "list-body";

        const title = document.createElement("div");
        title.className = "list-title";
        title.textContent = r.name || "Untitled";

        const meta = document.createElement("div");
        meta.className = "list-meta";
        meta.textContent = (r.type || "other").toUpperCase() + " · " + tutorName + " · " + formatDate(r.created_at || r.sent);

        body.appendChild(title);
        body.appendChild(meta);

        const open = document.createElement("button");
        open.type = "button";
        open.className = "list-badge accepted";
        open.textContent = "open";
        open.addEventListener("click", () => window.open(r.url, "_blank"));

        row.appendChild(avatar);
        row.appendChild(body);
        row.appendChild(open);
        resourceList.appendChild(row);
      });
    }

    function renderGroups(groups) {
      const recent = groups
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 4);

      groupList.innerHTML = "";

      if (recent.length === 0) {
        groupList.innerHTML =
          '<div class="empty">' +
            '<i class="fa-solid fa-users"></i>' +
            '<strong>No study groups yet</strong>' +
            'Create one or join the first group.' +
          '</div>';
        return;
      }

      recent.forEach(g => {
        const row = document.createElement("div");
        row.className = "list-row";

        const avatar = document.createElement("div");
        avatar.className = "list-avatar";
        avatar.style.background = colorFromString(g.name || "Group");
        avatar.textContent = initials(g.name || "Group");

        const body = document.createElement("div");
        body.className = "list-body";

        const title = document.createElement("div");
        title.className = "list-title";
        title.textContent = g.name || "Untitled group";

        const meta = document.createElement("div");
        meta.className = "list-meta";
        meta.textContent = (g.topic || "General") + " · " + (g.owner_name || "Owner");

        body.appendChild(title);
        body.appendChild(meta);

        const open = document.createElement("button");
        open.type = "button";
        open.className = "list-badge";
        open.textContent = "open";
        open.addEventListener("click", () => navigate("study_groups"));

        row.appendChild(avatar);
        row.appendChild(body);
        row.appendChild(open);
        groupList.appendChild(row);
      });
    }

    setGreeting();
    loadOverview();
  </script>
</body>

</html>
<?php 
}
?>