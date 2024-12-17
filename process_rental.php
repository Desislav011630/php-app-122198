<?php
session_start();
include 'db.php'; // Уверете се, че файлът `db.php` правилно настройва $conn

// Проверка дали потребителят е влязъл в системата
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверка дали формата е изпратена с POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Вземане на данните от формата
    $car_id = intval($_POST['car_id']);
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $rent_date = $_POST['rent_date'];
    $return_date = $_POST['return_date'];

    // Проверка за празни полета
    if (empty($customer_name) || empty($customer_email) || empty($rent_date) || empty($return_date)) {
        die("All fields are required.");
    }

    // Проверка за валидност на датите
    if (strtotime($rent_date) > strtotime($return_date)) {
        die("Return date must be after rent date.");
    }

    // Проверка дали автомобилът съществува
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Car not found.");
    }

    // Вмъкване на резервацията в таблицата reservations
    $sql = "INSERT INTO reservations (car_id, customer_name, customer_email, rent_date, return_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $car_id, $customer_name, $customer_email, $rent_date, $return_date);

    if ($stmt->execute()) {
        // Успешно добавяне на резервацията
        header("Location: success.php?message=Reservation successful");
        exit();
    } else {
        // Грешка при добавяне
        echo "Error: " . $stmt->error;
    }
} else {
    // Ако страницата е достъпена без POST заявка
    header("Location: index.php");
    exit();
}
?>
