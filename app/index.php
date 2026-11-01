<?php
session_start();

$success = false;
$errorMessage = "";
$loggedInUser = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif (empty($password)) {
        $errorMessage = "Please enter your password.";
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

        if ($checkResponse === false) {
            $errorMessage = "Unable to connect to the server. Please try again.";
        } else {
            $users = json_decode($checkResponse, true);

            if (!is_array($users) || count($users) === 0) {
                $errorMessage = "No account found with that email.";
            } else {
                $user = $users[0];
                $storedHash = $user["password"] ?? "";

                if (password_verify($password, $storedHash)) {
                    $success = true;
                    $loggedInUser = $user;

                    $_SESSION["user_id"]  = $user["id"] ?? null;
                    $_SESSION["fullname"] = $user["fullname"] ?? "";
                    $_SESSION["email"]    = $user["email"] ?? "";
                    $_SESSION["role"]     = $_POST["role"] ?? "student";
                } else {
                    $errorMessage = "Incorrect password. Please try again.";
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
  <title>TutorLink · Login</title>
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

    .forgot-row {
      display: flex;
      justify-content: flex-end;
      margin-top: -0.3rem;
      margin-bottom: 1.3rem;
    }

    .forgot-link {
      font-size: 0.83rem;
      color: #0a4b6e;
      text-decoration: none;
      font-weight: 500;
      border-bottom: 1px solid transparent;
      transition: border 0.15s, color 0.15s;
    }

    .forgot-link:hover {
      border-bottom-color: #0b6e4f;
      color: #0b6e4f;
    }

    .login-btn {
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

    .login-btn:hover {
      background: #f0f7f4;
      border-color: #0b6e4f;
      color: #0b6e4f;
    }

    .login-btn:active {
      transform: scale(0.99);
    }

    .separator {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      color: #6b8b7e;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 1.3rem;
    }

    .separator::before,
    .separator::after {
      content: "";
      flex: 1;
      height: 1.5px;
      background: linear-gradient(to right, transparent, #cfe3da, transparent);
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
        width: 38px;
        height: 38px;
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

      <h1 class="welcome-title" id="welcomeTitle">Welcome back</h1>
      <p class="welcome-sub" id="welcomeSub">Log in to manage your students</p>

      <form id="loginForm" method="POST" action="index" novalidate>
        <input type="hidden" name="role" id="roleField" value="student">

        <div class="form-group">
          <label class="input-label" for="email">Email address</label>
          <input class="input-field" type="email" id="email" name="email" autocomplete="email">
          <div class="error-message" id="emailError">Please enter a valid email address</div>
        </div>

        <div class="form-group">
          <label class="input-label" for="password">Password</label>
          <input class="input-field" type="password" id="password" name="password" autocomplete="current-password">
          <div class="error-message" id="passwordError">Please enter your password</div>
        </div>

        <div class="forgot-row">
          <a href="" class="forgot-link" id="forgotLink">Forgot password?</a>
        </div>

        <button class="login-btn" type="submit" id="loginBtn">Log in as Student</button>

        <div class="separator">or</div>

        <div class="signup-row">
          <span id="signupPrompt">Don't have an account?</span>
          <a href="register" class="signup-link" id="signupLink">Sign up as Student OR Lecturer</a>
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
    const welcomeTitle = document.getElementById('welcomeTitle');
    const welcomeSub = document.getElementById('welcomeSub');
    const loginBtn = document.getElementById('loginBtn');
    const signupPrompt = document.getElementById('signupPrompt');
    const signupLink = document.getElementById('signupLink');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    let currentRole = 'student';
    let toastTimer = null;

    function showToast(message, type) {
      clearTimeout(toastTimer);
      toast.classList.remove('success', 'error', 'show');
      void toast.offsetWidth;
      toastMessage.textContent = message;
      if (type === 'success') {
        toast.classList.add('success');
        toastIcon.textContent = '..';
      } else {
        toast.classList.add('error');
        toastIcon.textContent = '!';
      }
      toast.classList.add('show');
      toastTimer = setTimeout(() => {
        toast.classList.remove('show');
      }, 3200);
    }

    function setRole(role) {
      currentRole = role;
      roleField.value = role;
      roleToggle.textContent = role === 'student' ? 'Student' : 'Lecturer';

      if (role === 'student') {
        welcomeSub.textContent = 'Log in to manage your students';
        welcomeSub.classList.remove('lecturer-border');
        loginBtn.textContent = 'Log in as Student';
        signupPrompt.textContent = "Don't have an account?";
        signupLink.textContent = 'Sign up as Student';
      } else {
        welcomeSub.textContent = 'Log in to manage your courses';
        welcomeSub.classList.add('lecturer-border');
        loginBtn.textContent = 'Log in as Lecturer';
        signupPrompt.textContent = "Don't have an account?";
        signupLink.textContent = 'Sign up as Lecturer';
      }
      clearErrors();
    }

    function clearErrors() {
      emailInput.classList.remove('error');
      passwordInput.classList.remove('error');
      emailError.classList.remove('show');
      passwordError.classList.remove('show');
    }

    function validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validateForm() {
      let valid = true;
      clearErrors();

      const emailValue = emailInput.value.trim();
      const passwordValue = passwordInput.value;

      if (!emailValue || !validateEmail(emailValue)) {
        emailInput.classList.add('error');
        emailError.classList.add('show');
        valid = false;
      }

      if (!passwordValue) {
        passwordInput.classList.add('error');
        passwordError.classList.add('show');
        valid = false;
      }

      return valid;
    }

    roleToggle.addEventListener('click', () => {
      currentRole = currentRole === 'student' ? 'lecturer' : 'student';
      setRole(currentRole);
    });

    emailInput.addEventListener('input', () => {
      if (emailInput.classList.contains('error') && validateEmail(emailInput.value.trim())) {
        emailInput.classList.remove('error');
        emailError.classList.remove('show');
      }
    });

    passwordInput.addEventListener('input', () => {
      if (passwordInput.classList.contains('error') && passwordInput.value.length > 0) {
        passwordInput.classList.remove('error');
        passwordError.classList.remove('show');
      }
    });

    form.addEventListener('submit', (e) => {
      if (!validateForm()) {
        e.preventDefault();
        showToast('Please check your email and password', 'error');
      }
    });

    document.getElementById('forgotLink').addEventListener('click', (e) => {
      e.preventDefault();
      showToast('Password reset link sent to your email', 'success');
    });

    signupLink.addEventListener('click', (e) => {
      e.preventDefault();
      window.location.href = "register";
    });

    <?php if ($success): ?>
      showToast('Logged in successfully as ' + '<?php echo ucfirst($_SESSION["role"]); ?>', 'success');
      emailInput.value = '';
      passwordInput.value = '';
      clearErrors();
  window.location.href = "dashboard/";


    <?php elseif (!empty($errorMessage)): ?>
      showToast(<?php echo json_encode($errorMessage); ?>, 'error');
    <?php endif; ?>
  </script>
</body>
</html>