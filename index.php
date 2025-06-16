<?php
// index.php

// Calculate status
$statusText = "OK";
$timestamp  = date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🤖 AI Operator Bridge</title>
  <style>
    body {
      background: #0d0d22;
      color: #f0f0f0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      padding: 60px;
    }
    .robot {
      font-size: 5em;
      animation: float 3s ease-in-out infinite;
    }
    h1 {
      font-size: 2.5em;
      margin: 10px 0;
      animation: glow 2s ease-in-out infinite alternate;
    }
    p {
      font-size: 1.2em;
      color: #c8c8c8;
    }
    @keyframes float {
      0%,100% { transform: translateY(0); }
      50%    { transform: translateY(-15px); }
    }
    @keyframes glow {
      from { text-shadow: 0 0 8px #483D8B; }
      to   { text-shadow: 0 0 16px #6A5ACD; }
    }
    .status {
      display: inline-block;
      margin-top: 30px;
      padding: 15px 30px;
      background: #1a1a2e;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.6);
      font-size: 1.1em;
      white-space: pre-wrap;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="robot">🤖</div>
  <h1>AI Operator Bridge</h1>
  <p>Your AI Agent Control Panel is standing by…</p>
  <div class="status">
    Status: <?php echo htmlspecialchars($statusText); ?><br>
    Timestamp: <?php echo htmlspecialchars($timestamp); ?>
  </div>
</body>
</html>
