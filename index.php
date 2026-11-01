<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TutorLink</title>
  <link rel="icon" href="logo.png">
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

    .loader-page {
      position: fixed;
      inset: 0;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 5000;
    }

    .loader-center {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
      flex: 1;
      justify-content: center;
      padding: 2rem;
    }

    .loader-logo {
      width: 130px;
      height: 130px;
      object-fit: contain;
      animation: logoPulse 2s ease-in-out infinite;
    }

    .loader-logo-fallback {
      width: 130px;
      height: 130px;
      border-radius: 22px;
      background: linear-gradient(135deg, #0b6e4f, #0a4b6e);
      color: #ffffff;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 2.8rem;
      font-weight: 800;
      letter-spacing: -1.5px;
      animation: logoPulse 2s ease-in-out infinite;
      box-shadow: 0 20px 45px -18px rgba(11, 110, 79, 0.5);
    }

    @keyframes logoPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.06); }
    }

    .loader-brand {
      font-size: 2.1rem;
      font-weight: 700;
      background: linear-gradient(135deg, #0b6e4f, #0a4b6e);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.03em;
      margin-top: 0.75rem;
    }

    .loader-tagline {
      font-size: 0.9rem;
      color: #6b8b7e;
      font-weight: 500;
      letter-spacing: 0.2px;
    }

    .loader-bottom {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.85rem;
      padding-bottom: 4rem;
    }

    .loader-label {
      font-size: 0.78rem;
      color: #8a9e94;
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    .loader-dots {
      display: flex;
      gap: 0.55rem;
    }

    .loader-dots span {
      width: 11px;
      height: 11px;
      border-radius: 50%;
      background: #0b6e4f;
      opacity: 0.35;
      animation: dotBounce 1.3s infinite ease-in-out;
    }

    .loader-dots span:nth-child(2) {
      animation-delay: 0.18s;
      background: #0a4b6e;
    }

    .loader-dots span:nth-child(3) {
      animation-delay: 0.36s;
      background: #16a97a;
    }

    @keyframes dotBounce {
      0%, 80%, 100% {
        transform: translateY(0);
        opacity: 0.35;
      }
      40% {
        transform: translateY(-11px);
        opacity: 1;
      }
    }

    @media (max-width: 480px) {
      .loader-logo,
      .loader-logo-fallback {
        width: 100px;
        height: 100px;
        font-size: 2.2rem;
      }

      .loader-brand {
        font-size: 1.7rem;
      }

      .loader-bottom {
        padding-bottom: 3rem;
      }
    }
  </style>
</head>

<body>
  <div class="loader-page">
    <div class="loader-center">
      <img
        src="logo.png"
        alt="TutorLink"
        class="loader-logo"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
      <div class="loader-logo-fallback">TL</div>
      <div class="loader-brand">TutorLink</div>
      <div class="loader-tagline">Connect · Learn · Grow</div>
    </div>

    <div class="loader-bottom">
      <div class="loader-label">Loading</div>
      <div class="loader-dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>

  <script>
    setTimeout(() => {
      window.location.href = "app/";
    }, 3500);
  </script>
</body>

</html>