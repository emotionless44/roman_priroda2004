<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['habit']) && isset($_POST['start_date'])) {
        $stmt = $pdo->prepare("INSERT INTO habits (user_id, name, start_date) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $_POST['habit'], $_POST['start_date']]);
    }

    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare("DELETE FROM habits WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['delete_id'], $user_id]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM habits WHERE user_id = ?");
$stmt->execute([$user_id]);
$habits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Трекер Привычек</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <header>
        <h1>Трекер Привычек</h1>
        <nav>
            <a href="logout.php">Выйти</a>
        </nav>
    </header>
    <main class="centered">
        <form method="post">
            <h2>Добавить Привычку</h2>
            <label>Название:<input type="text" name="habit" required></label>
            <label>Дата начала:<input type="date" name="start_date" required></label>
            <button type="submit">Добавить</button>
        </form>

        <section class="habit-list">
            <h2>Активные Привычки</h2>
            <ul>
                <?php foreach ($habits as $habit): ?>
                    <li>
                        <?= htmlspecialchars($habit['name']) ?><br>
                        Прогресс: <?= round((time() - strtotime($habit['start_date'])) / 86400) ?> дней<br>
                        Дата начала: <?= htmlspecialchars($habit['start_date']) ?>
                        <form method="post" class="inline-form">
                            <input type="hidden" name="delete_id" value="<?= $habit['id'] ?>">
                            <button type="submit">Удалить</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy;Трекер Привычек</p>
    </footer>
</div>
</body>
</html>