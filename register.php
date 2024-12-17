<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка за празни полета
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die("Всички полета са задължителни!");
    }

    // Проверка дали паролите съвпадат
    if ($password !== $confirm_password) {
        die("Паролите не съвпадат!");
    }

    // Проверка за валиден имейл адрес
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Невалиден имейл адрес!");
    }

    // Хеширане на паролата
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Добавяне в базата данни
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Регистрацията е успешна!";
        header("Location: login.php");
        exit();
    } else {
        echo "Грешка: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Име" required>
            <input type="email" name="email" placeholder="Имейл адрес" required>
            <input type="password" name="password" placeholder="Парола" required>
            <input type="password" name="confirm_password" placeholder="Повторете паролата" required>
            <button type="submit">Регистрирай се</button>
        </form>
    </div>
</body>
</html>
