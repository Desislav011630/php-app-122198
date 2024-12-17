<?php
session_start();
include 'db.php'; // Уверете се, че файлът db.php съществува и правилно дефинира $conn

// Проверка дали потребителят е администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Достъп забранен. Не сте администратор.");
}

// Обработка на заявката за изтриване
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $car_id = intval($_POST['id']);

    // Проверка дали ID е валидно
    if ($car_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Невалидно ID на автомобил.']);
        exit;
    }

    $sql = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Автомобилът е изтрит успешно.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Грешка при изтриване на автомобила: ' . $conn->error]);
    }

    $stmt->close();
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Няма подаден ID на автомобил.']);
}
?>
