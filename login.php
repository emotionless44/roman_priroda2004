<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <header>
        <h1>Трекер Привычек</h1>
    </header>
    <main class="centered">
        <form method="post">
            <h2>Вход</h2>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <label>Email:<input type="email" name="email" required></label>
            <label>Пароль:<input type="password" name="password" required></label>
            <button type="submit">Войти</button>
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </form>
    </main>
    <footer>
        <p>&copy;Трекер Привычек</p>
    </footer>
</div>
</body>
</html>