<?php
$success = false;
$errorMessage = "";
$apiResponse = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST["fullname"] ?? "";
    $email    = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirmPassword"] ?? "";
    $role     = $_POST["role"] ?? "student";

    $hasUpper   = preg_match('/[A-Z]/', $password);
    $hasLower   = preg_match('/[a-z]/', $password);
    $hasNumber  = preg_match('/[0-9]/', $password);
    $hasSpecial = preg_match('/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\\\/;\'`~]/', $password);
    $longEnough = strlen($password) >= 8;

    if (!in_array($role, ["student", "lecturer"])) {
        $role = "student";
    }

    if (!$longEnough || !$hasUpper || !$hasLower || !$hasNumber || !$hasSpecial) {
        $errorMessage = "Password must be 8+ characters with uppercase, lowercase, number & special character.";
    } elseif ($password !== $confirm) {
        $errorMessage = "Passwords do not match.";
    } elseif (empty($fullname) || strlen(trim($fullname)) < 2) {
        $errorMessage = "Please enter your full name.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } else {
        $checkPayload = [
            "where" => [
                "email" => $email
            ]
        ];

        $checkUrl = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_users/filter";

        $checkResponse = @file_get_contents(
            $checkUrl,
            false,
            stream_context_create([
                "http" => [
                    "method"  => "POST",
                    "header"  => "Content-Type: application/json",
                    "content" => json_encode($checkPayload),
                    "timeout" => 15
                ]
            ])
        );

        $existingUsers = json_decode($checkResponse, true);

        if (is_array($existingUsers) && count($existingUsers) > 0) {
            $errorMessage = "An account with this email already exists. Please log in instead.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $payload = [
                "fullname" => $fullname,
                "email"    => $email,
                "password" => $hashedPassword,
                "role"     => $role,
            ];

            $url = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_users/insert";

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

            if ($response === false) {
                $errorMessage = "Unable to connect to the server. Please try again.";
            } else {
                $decoded = json_decode($response, true);
                if (isset($decoded["error"]) || (isset($decoded["status"]) && $decoded["status"] === "error")) {
                    $errorMessage = $decoded["message"] ?? "Registration failed. Please try again.";
                } else {
                    $success = true;

                    $notifPayload = [
                        "title"   => "Welcome to TutorLink",
                        "message" => "Hi " . $fullname . ", your " . $role . " account has been created successfully. Start exploring TutorLink today!",
                        "sent"    => date("Y-m-d H:i:s"),
                        "email"   => $email,
                    ];

                    $notifUrl = "https://studyplugtools.cloud/you_connect.php/user_b6a3d990_rea_notifcation/insert";

                    @file_get_contents(
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
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TutorLink · Register</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    }

    body {
      background: #ffffff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    .card {
      background: #ffffff;
      max-width: 440px;
      width: 100%;
      border-radius: 5px;
      box-shadow: 0 20px 45px -18px rgba(11, 110, 79, 0.15), 0 8px 20px -10px rgba(10, 75, 110, 0.1);
      border: 1px solid #e4f0ea;
      position: relative;
    }

    .role-toggle {
      position: absolute;
      top: 1.25rem;
      right: 1.25rem;
      padding: 0.5rem 1rem;
      font-size: 0.78rem;
      font-weight: 600;
      border: 1.5px solid #0b6e4f;
      background: #ffffff;
      color: #0b6e4f;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.2s ease;
      font-family: inherit;
      letter-spacing: 0.3px;
      z-index: 10;
    }

    .role-toggle:hover {
      background: #0b6e4f;
      color: #ffffff;
    }

    .card-body {
      padding: 2rem 2rem 2.25rem;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      margin-bottom: 1.75rem;
    }

    .logo-img {
      width: 84px;
      height: 78px;
      border-radius: 5px;
      object-fit: cover;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #0b6e4f, #0a4b6e);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.02em;
    }

    .welcome-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: #0a4b6e;
      margin-bottom: 0.3rem;
      letter-spacing: -0.02em;
    }

    .welcome-sub {
      font-size: 0.9rem;
      color: #4a6b5e;
      margin-bottom: 1.6rem;
      line-height: 1.4;
      border-left: 3px solid #0b6e4f;
      padding-left: 0.75rem;
      transition: border-color 0.2s;
    }

    .welcome-sub.lecturer-border {
      border-left-color: #0a4b6e;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    .input-label {
      display: block;
      font-size: 0.83rem;
      font-weight: 600;
      color: #0a4b6e;
      margin-bottom: 0.4rem;
      letter-spacing: 0.3px;
    }

    .input-field {
      width: 100%;
      padding: 0.85rem 1.1rem;
      font-size: 0.95rem;
      background: #ffffff;
      border: 1.5px solid #cfe3da;
      border-radius: 5px;
      outline: none;
      transition: border 0.15s, box-shadow 0.15s;
      color: #0a2e3f;
    }

    .input-field:focus {
      border-color: #0b6e4f;
      box-shadow: 0 0 0 4px rgba(11, 110, 79, 0.1);
    }

    .input-field.error {
      border-color: #c0392b;
      box-shadow: 0 0 0 4px rgba(192, 57, 43, 0.08);
    }

    .error-message {
      display: none;
      color: #c0392b;
      font-size: 0.78rem;
      font-weight: 500;
      margin-top: 0.35rem;
      padding-left: 0.2rem;
    }

    .error-message.show {
      display: block;
    }

    .password-strength {
      display: flex;
      gap: 0.35rem;
      margin-top: 0.55rem;
    }

    .strength-bar {
      flex: 1;
      height: 4px;
      background: #e4f0ea;
      border-radius: 2px;
      transition: background 0.25s;
    }

    .strength-bar.filled {
      background: #0b6e4f;
    }

    .strength-bar.filled.mid {
      background: #d39e00;
    }

    .strength-bar.filled.weak {
      background: #c0392b;
    }

    .strength-label {
      font-size: 0.72rem;
      font-weight: 500;
      color: #6b8b7e;
      margin-top: 0.3rem;
    }

    .password-rules {
      list-style: none;
      margin-top: 0.5rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.25rem 0.6rem;
    }

    .password-rules li {
      font-size: 0.72rem;
      color: #8a9e94;
      display: flex;
      align-items: center;
      gap: 0.3rem;
      transition: color 0.2s;
    }

    .password-rules li::before {
      content: "○";
      font-size: 0.7rem;
      color: #cfe3da;
    }

    .password-rules li.met {
      color: #0b6e4f;
    }

    .password-rules li.met::before {
      content: "●";
      color: #0b6e4f;
    }

    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 0.6rem;
      margin-bottom: 1.3rem;
      margin-top: 1rem;
    }

    .terms-row input[type="checkbox"] {
      width: 16px;
      height: 16px;
      margin-top: 0.15rem;
      accent-color: #0b6e4f;
      cursor: pointer;
      flex-shrink: 0;
    }

    .terms-label {
      font-size: 0.82rem;
      color: #4a6b5e;
      line-height: 1.4;
      cursor: pointer;
    }

    .terms-label a {
      color: #0b6e4f;
      font-weight: 600;
      text-decoration: none;
      border-bottom: 1px solid #cfe3da;
      transition: border 0.15s, color 0.15s;
    }

    .terms-label a:hover {
      border-bottom-color: #0a4b6e;
      color: #0a4b6e;
    }

    .terms-row.error .terms-label {
      color: #c0392b;
    }

    .create-btn {
      width: 100%;
      background: #ffffff;
      color: #0a4b6e;
      font-weight: 600;
      font-size: 0.98rem;
      padding: 0.95rem;
      border-radius: 5px;
      border: 1.5px solid #0a4b6e;
      cursor: pointer;
      transition: all 0.2s;
      letter-spacing: 0.2px;
      margin-bottom: 1.4rem;
      font-family: inherit;
    }

    .create-btn:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .create-btn:active {
      transform: scale(0.99);
    }

    .signup-row {
      text-align: center;
      font-size: 0.92rem;
      color: #2b5a4a;
    }

    .signup-link {
      color: #0b6e4f;
      font-weight: 600;
      text-decoration: none;
      border-bottom: 2px solid #cfe3da;
      padding-bottom: 1px;
      transition: border 0.15s, color 0.15s;
      cursor: pointer;
    }

    .signup-link:hover {
      border-bottom-color: #0a4b6e;
      color: #0a4b6e;
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
      z-index: 999;
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

    @media (max-width: 480px) {
      .card-body {
        padding: 1.5rem 1.25rem 1.75rem;
      }
      .welcome-title {
        font-size: 1.4rem;
      }
      .brand-name {
        font-size: 1.3rem;
      }
      .logo-img {
        width: 64px;
        height: 60px;
      }
      .role-toggle {
        top: 1rem;
        right: 1rem;
        padding: 0.45rem 0.85rem;
        font-size: 0.72rem;
      }
      .toast {
        left: 1rem;
        right: 1rem;
        min-width: auto;
        max-width: none;
      }
    }
  </style>
</head>
<body>
  <div class="card">
    <button type="button" class="role-toggle" id="roleToggle">Student</button>

    <div class="card-body">
      <div class="brand">
        <img src="logo.png" alt="TutorLink logo" class="logo-img">
        <div class="brand-name">TutorLink</div>
      </div>

      <h1 class="welcome-title" id="welcomeTitle">Create your account</h1>
      <p class="welcome-sub" id="welcomeSub">Join TutorLink and start learning</p>

      <form id="registerForm" method="POST" action="register" novalidate>
        <input type="hidden" name="role" id="roleField" value="student">

        <div class="form-group">
          <label class="input-label" for="fullname">Full name</label>
          <input class="input-field" type="text" id="fullname" name="fullname" autocomplete="name">
          <div class="error-message" id="fullnameError">Please enter your full name</div>
        </div>

        <div class="form-group">
          <label class="input-label" for="email">Email address</label>
          <input class="input-field" type="email" id="email" name="email" autocomplete="email">
          <div class="error-message" id="emailError">Please enter a valid email address</div>
        </div>

        <div class="form-group">
          <label class="input-label" for="password">Password</label>
          <input class="input-field" type="password" id="password" name="password" autocomplete="new-password">
          <div class="error-message" id="passwordError">Password does not meet requirements</div>

          <div class="password-strength">
            <div class="strength-bar" id="bar1"></div>
            <div class="strength-bar" id="bar2"></div>
            <div class="strength-bar" id="bar3"></div>
            <div class="strength-bar" id="bar4"></div>
          </div>
          <div class="strength-label" id="strengthLabel">Password strength</div>

          <ul class="password-rules" id="passwordRules">
            <li data-rule="length">8+ characters</li>
            <li data-rule="upper">Uppercase letter</li>
            <li data-rule="lower">Lowercase letter</li>
            <li data-rule="number">Number</li>
            <li data-rule="special">Special character</li>
          </ul>
        </div>

        <div class="form-group">
          <label class="input-label" for="confirmPassword">Confirm password</label>
          <input class="input-field" type="password" id="confirmPassword" name="confirmPassword" autocomplete="new-password">
          <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
        </div>

        <div class="terms-row" id="termsRow">
          <input type="checkbox" id="terms" name="terms">
          <label class="terms-label" for="terms">
            I agree to the <a href="#" id="termsLink">Terms of Service</a> and <a href="#" id="privacyLink">Privacy Policy</a>.
          </label>
        </div>

        <button class="create-btn" type="submit" id="createBtn">Create Student account</button>

        <div class="signup-row">
          Already have an account? <a href="#" class="signup-link" id="loginLink">Log in</a>
        </div>
      </form>
    </div>
  </div>

  <div class="toast" id="toast">
    <div class="toast-icon" id="toastIcon">!</div>
    <div class="toast-message" id="toastMessage"></div>
  </div>

  <script>
    const roleToggle = document.getElementById('roleToggle');
    const roleField = document.getElementById('roleField');
    const welcomeSub = document.getElementById('welcomeSub');
    const createBtn = document.getElementById('createBtn');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    const form = document.getElementById('registerForm');
    const fullnameInput = document.getElementById('fullname');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('terms');
    const termsRow = document.getElementById('termsRow');
    const fullnameError = document.getElementById('fullnameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const strengthBars = [
      document.getElementById('bar1'),
      document.getElementById('bar2'),
      document.getElementById('bar3'),
      document.getElementById('bar4')
    ];
    const strengthLabel = document.getElementById('strengthLabel');
    const ruleItems = document.querySelectorAll('#passwordRules li');

    let currentRole = 'student';
    let toastTimer = null;

    function showToast(message, type) {
      clearTimeout(toastTimer);
      toast.classList.remove('success', 'error', 'show');
      void toast.offsetWidth;
      toastMessage.textContent = message;
      if (type === 'success') {
        toast.classList.add('success');
        toastIcon.textContent = '✓';
      } else {
        toast.classList.add('error');
        toastIcon.textContent = '!';
      }
      toast.classList.add('show');
      toastTimer = setTimeout(() => {
        toast.classList.remove('show');
      }, 4000);
    }

    function setRole(role) {
      currentRole = role;
      roleField.value = role;
      roleToggle.textContent = role === 'student' ? 'Student' : 'Lecturer';

      if (role === 'student') {
        welcomeSub.textContent = 'Join TutorLink and start learning';
        welcomeSub.classList.remove('lecturer-border');
        createBtn.textContent = 'Create Student account';
      } else {
        welcomeSub.textContent = 'Join TutorLink and start tutoring';
        welcomeSub.classList.add('lecturer-border');
        createBtn.textContent = 'Create Lecturer account';
      }
      clearErrors();
    }

    function clearErrors() {
      fullnameInput.classList.remove('error');
      emailInput.classList.remove('error');
      passwordInput.classList.remove('error');
      confirmInput.classList.remove('error');
      termsRow.classList.remove('error');
      fullnameError.classList.remove('show');
      emailError.classList.remove('show');
      passwordError.classList.remove('show');
      confirmPasswordError.classList.remove('show');
    }

    function validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function checkPasswordRules(pw) {
      return {
        length: pw.length >= 8,
        upper: /[A-Z]/.test(pw),
        lower: /[a-z]/.test(pw),
        number: /[0-9]/.test(pw),
        special: /[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(pw)
      };
    }

    function isStrongPassword(pw) {
      const r = checkPasswordRules(pw);
      return r.length && r.upper && r.lower && r.number && r.special;
    }

    function updatePasswordUI() {
      const pw = passwordInput.value;
      const rules = checkPasswordRules(pw);
      let metCount = 0;

      ruleItems.forEach(item => {
        const rule = item.dataset.rule;
        if (rules[rule]) {
          item.classList.add('met');
          metCount++;
        } else {
          item.classList.remove('met');
        }
      });

      strengthBars.forEach((bar, i) => {
        bar.classList.remove('filled', 'mid', 'weak');
        if (i < metCount) {
          bar.classList.add('filled');
          if (metCount <= 2) bar.classList.add('weak');
          else if (metCount === 3 || metCount === 4) bar.classList.add('mid');
        }
      });

      if (!pw) {
        strengthLabel.textContent = 'Password strength';
        strengthLabel.style.color = '#6b8b7e';
      } else if (metCount <= 2) {
        strengthLabel.textContent = 'Weak password';
        strengthLabel.style.color = '#c0392b';
      } else if (metCount <= 4) {
        strengthLabel.textContent = 'Medium password';
        strengthLabel.style.color = '#d39e00';
      } else {
        strengthLabel.textContent = 'Strong password';
        strengthLabel.style.color = '#0b6e4f';
      }
    }

    function validateForm() {
      let valid = true;
      clearErrors();

      const fullnameValue = fullnameInput.value.trim();
      const emailValue = emailInput.value.trim();
      const passwordValue = passwordInput.value;
      const confirmValue = confirmInput.value;

      if (!fullnameValue || fullnameValue.length < 2) {
        fullnameInput.classList.add('error');
        fullnameError.classList.add('show');
        valid = false;
      }

      if (!emailValue || !validateEmail(emailValue)) {
        emailInput.classList.add('error');
        emailError.classList.add('show');
        valid = false;
      }

      if (!isStrongPassword(passwordValue)) {
        passwordInput.classList.add('error');
        passwordError.textContent = 'Password must be 8+ chars with uppercase, lowercase, number & special character';
        passwordError.classList.add('show');
        valid = false;
      }

      if (!confirmValue || confirmValue !== passwordValue) {
        confirmInput.classList.add('error');
        confirmPasswordError.classList.add('show');
        valid = false;
      }

      if (!termsCheckbox.checked) {
        termsRow.classList.add('error');
        valid = false;
      }

      return valid;
    }

    roleToggle.addEventListener('click', () => {
      currentRole = currentRole === 'student' ? 'lecturer' : 'student';
      setRole(currentRole);
    });

    fullnameInput.addEventListener('input', () => {
      if (fullnameInput.classList.contains('error') && fullnameInput.value.trim().length >= 2) {
        fullnameInput.classList.remove('error');
        fullnameError.classList.remove('show');
      }
    });

    emailInput.addEventListener('input', () => {
      if (emailInput.classList.contains('error') && validateEmail(emailInput.value.trim())) {
        emailInput.classList.remove('error');
        emailError.classList.remove('show');
      }
    });

    passwordInput.addEventListener('input', () => {
      updatePasswordUI();
      if (passwordInput.classList.contains('error') && isStrongPassword(passwordInput.value)) {
        passwordInput.classList.remove('error');
        passwordError.classList.remove('show');
      }
      if (confirmInput.classList.contains('error') && confirmInput.value === passwordInput.value) {
        confirmInput.classList.remove('error');
        confirmPasswordError.classList.remove('show');
      }
    });

    confirmInput.addEventListener('input', () => {
      if (confirmInput.classList.contains('error') && confirmInput.value === passwordInput.value) {
        confirmInput.classList.remove('error');
        confirmPasswordError.classList.remove('show');
      }
    });

    termsCheckbox.addEventListener('change', () => {
      if (termsCheckbox.checked) {
        termsRow.classList.remove('error');
      }
    });

    form.addEventListener('submit', (e) => {
      if (!validateForm()) {
        e.preventDefault();
        showToast('Please fix the errors in the form', 'error');
      }
    });

    document.getElementById('termsLink').addEventListener('click', (e) => {
      e.preventDefault();
    });

    document.getElementById('privacyLink').addEventListener('click', (e) => {
      e.preventDefault();
    });

    document.getElementById('loginLink').addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = "index";
    });

    <?php if ($success): ?>
      showToast('Account created! Check your notifications.', 'success');
      form.reset();
      updatePasswordUI();
      setRole('student');
    <?php elseif (!empty($errorMessage)): ?>
      showToast(<?php echo json_encode($errorMessage); ?>, 'error');
    <?php endif; ?>
  </script>
</body>
</html>