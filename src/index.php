<?php
  session_name("Forum-SID");
  session_set_cookie_params(31536000);

  if (isset($_COOKIE[session_name()])) {
    session_start();
    if (isset($_SESSION['user']) && isset($_GET['logout'])) {
      session_destroy();
      header("Location: .");
      exit();
    }
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
  } else {
    $user = null;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="/css/scrollbar.css">
</head>
<body>
    <div class="login">
      <a class="_flex_center" href="<?php echo $user ? '?logout' : './login.php' ?>">
        <img src="https://lucasguimaraes.pro/media/profile/0.png" alt="">
        <span><?php echo $user ? 'Logout' : 'Login' ?></span>
      </a>
    </div>
    <div class="chat" id="chat">
    </div>
    <div class="controls">
        <input id="message" type="text" placeholder="Type a message" />
        <button id="send">Send</button>
        <button id="refrest">Refrest</button>
    </div>
    <script src="./js/chat.js"></script>
</body>
</html>