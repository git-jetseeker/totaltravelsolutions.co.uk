<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Under Maintenance</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{
      font-family:'Inter',sans-serif;
      background:linear-gradient(135deg,#0f172a,#1e293b);
      color:#e2e8f0;
      display:flex;flex-direction:column;align-items:center;justify-content:center;
      text-align:center;
      padding:20px;
    }

    .container{
      max-width:640px;
      padding:40px 28px;
      background:rgba(255,255,255,0.03);
      border-radius:18px;
      box-shadow:0 8px 32px rgba(0,0,0,0.3);
      backdrop-filter:blur(12px);
    }

    h1{
      font-size:40px;
      margin-bottom:12px;
      font-weight:800;
      background:linear-gradient(90deg,#06b6d4,#7c3aed);
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }

    p{
      font-size:16px;
      color:#cbd5e1;
      margin-bottom:24px;
    }

    .icon{
      font-size:72px;
      line-height:1;
      margin-bottom:16px;
      color:#06b6d4;
    }

    .progress{
      width:100%;height:6px;border-radius:3px;overflow:hidden;background:rgba(255,255,255,0.1);
      position:relative;margin-bottom:16px;
    }
    .bar{
      width:0;height:100%;background:linear-gradient(90deg,#06b6d4,#7c3aed);
      animation:load 4s ease-in-out infinite;
    }

    @keyframes load{
      0%{width:0}
      50%{width:100%}
      100%{width:0}
    }

    footer{
      margin-top:28px;
      font-size:13px;
      color:#64748b;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="icon">🛠️</div>
    <h1>Under Maintenance</h1>
    <p>We're currently performing some scheduled maintenance. We’ll be back online shortly. Thanks for your patience!</p>
    <div class="progress"><div class="bar"></div></div>
    <footer>© 2025 JET SEEKER. All rights reserved.</footer>
  </div>
</body>
</html>
