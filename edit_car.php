<?php
session_start();
include 'db.php';

// Проверка дали потребителят е администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Достъп забранен. Не сте администратор.");
}

// Зареждане на данните за автомобила
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();

    if (!$car) {
        die("Автомобилът не е намерен.");
    }
} else {
    die("Не е посочен ID на автомобила.");
}

// Обновяване на данните за автомобила (чрез Ajax ще бъде изпратено)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];
    $image_url = $_POST['image_url'];

    $sql = "UPDATE cars SET make = ?, model = ?, year = ?, price_per_day = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssidsi", $make, $model, $year, $price_per_day, $image_url, $car_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Автомобилът е обновен успешно.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Грешка при обновяване на автомобила: ' . $conn->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактиране на автомобил</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Редактиране на автомобил</h1>
        <form id="editCarForm" method="POST" action="">
            <div class="mb-3">
                <label for="make" class="form-label">Марка</label>
                <input type="text" class="form-control" id="make" name="make" value="<?php echo htmlspecialchars($car['make']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Модел</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Година</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($car['year']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price_per_day" class="form-label">Цена на ден</label>
                <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($car['price_per_day']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">URL на изображение</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($car['image_url']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Запази промените</button>
            <a href="admin.php" class="btn btn-secondary">Отказ</a>
        </form>
        <div id="responseMessage" class="mt-3"></div>
    </div>

    <script>
        $(document).ready(function() {
            // Обработка на изпращането на формата чрез Ajax
            $('#editCarForm').on('submit', function(e) {
                e.preventDefault(); // Предотвратяване на нормалното изпращане на формата

                // Изключване на бутона за изпращане, докато се обработва заявката
                $('button[type="submit"]').prop('disabled', true);

                // Изпращане на данните от формата чрез Ajax
                $.ajax({
                    url: '', // текущата страница
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        // Показване на съобщение за успех или грешка в зависимост от отговора
                        if (response.status === 'success') {
                            $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(function() {
                                window.location.href = 'admin.php'; // Пренасочване след успех
                            }, 2000);
                        } else {
                            $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#responseMessage').html('<div class="alert alert-danger">Имаше грешка с заявката. Моля, опитайте отново по-късно.</div>');
                    },
                    complete: function() {
                        // Възстановяване на бутона за изпращане след като заявката е завършена
                        $('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html>
