<?php
session_start();

// Проверка дали потребителят е логнат
if (!isset($_SESSION['role'])) {
    die("Access denied. Please log in as an administrator.");
}

// Проверка дали потребителят е администратор
if ($_SESSION['role'] !== 'admin') {
    die("Access denied. You do not have administrator privileges.");
}

// Връзка с базата данни
include 'db.php';

// Проверка дали формата е изпратена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];
    $image_url = $_POST['image_url'];

    // Вмъкване в базата данни
    $sql = "INSERT INTO cars (make, model, year, price_per_day, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssids", $make, $model, $year, $price_per_day, $image_url);

    if ($stmt->execute()) {
        echo "Car added successfully!";
        header("Location: admin.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Car</h1>
        <form action="add_car.php" method="POST">
            <input type="text" name="make" placeholder="Make" required>
            <input type="text" name="model" placeholder="Model" required>
            <input type="number" name="year" placeholder="Year" required>
            <input type="number" name="price_per_day" placeholder="Price per day" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add Car</button>
        </form>
    </div>
</body>
</html>
