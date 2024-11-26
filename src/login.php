<?php
    session_name("Forum-SID");
    session_set_cookie_params(31536000);

    include 'bd.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['login'])) {
            // Login
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                header('Location: chat.php');
            } else {
                $error = 'Usuário ou senha inválidos';
            }
            $stmt->close();
        } elseif (isset($_POST['register'])) {
            // Registro
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                $error = 'Nome de usuário já existe';
            } else {
                $stmt = $connect->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $success = 'Conta criada com sucesso! Você pode fazer login agora.';
            }
            $stmt->close();
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="./css/style-login.css">
</head>
<body class="_flex_center">
  <div class="login-form _flex_column_xCenter">
    <form class="_flex_column_xCenter _max_size" method="POST">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="_flex_center _max_size">
          <button type="submit" name="login">Login</button>
          <button type="submit" name="register">Register</button>
        </div>
        <div class="_flex_center _max_size">
          <a href="./">Anonymously</a>
        </div>
    </form>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p>$success</p>"; } ?>
  </div>
</body>
</html>