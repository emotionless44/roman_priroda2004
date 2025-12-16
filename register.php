<?php
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = "Ошибка: пользователь с таким email уже существует.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <header>
        <h1>Трекер Привычек</h1>
    </header>
    <main class="centered">
        <form method="post">
            <h2>Регистрация</h2>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <label>Email:<input type="email" name="email" required></label>
            <label>Пароль:<input type="password" name="password" required></label>
            <button type="submit">Зарегистрироваться</button>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </form>
    </main>
    <footer>
        <p>&copy;Трекер Привычек</p>
    </footer>
</div>
</body>
</html>