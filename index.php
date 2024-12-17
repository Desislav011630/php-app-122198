<?php
session_start(); 
include 'db.php';

// Създаване на SQL заявка с филтри
$where_clauses = []; // Масив за условия
$params = []; // Масив за параметри
$sql = "SELECT * FROM cars"; // Основна заявка


if (!empty($_GET['make'])) { 
    $where_clauses[] = "make = ?"; 
    $params[] = $_GET['make'];  
}
if (!empty($_GET['model'])) { // Проверка дали моделът е зададен
    $where_clauses[] = "model = ?"; // Добавяне на условие за модел
    $params[] = $_GET['model'];
}
if (!empty($_GET['year'])) { // Проверка дали годината е зададена
    $where_clauses[] = "year = ?";
    $params[] = $_GET['year'];
}
if (!empty($_GET['price_min']) && !empty($_GET['price_max'])) { // Проверка дали минималната и максималната цена са зададени
    $where_clauses[] = "price_per_day BETWEEN ? AND ?";
    $params[] = $_GET['price_min'];
    $params[] = $_GET['price_max'];
}

// Ако има условия, добавяме ги към основната заявка
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

// Подготовка на заявката
$stmt = $conn->prepare($sql);
if ($params) { // Ако има параметри, ги свързваме със заявката
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}
$stmt->execute(); // Изпълнение на заявката
$result = $stmt->get_result(); // Получаване на резултати
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наемане на коли</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* CSS за стил на страницата */
        body {
            background-color: #f8f9fa; 
        }
        .navbar {
            background-color: #343a40; 
        }
        .navbar a {
            color: white !important; /* Бял цвят на връзките */
        }
        .card {
            height: 100%; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
        .card-img-top {
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
        }
    </style>
</head>
<body>
    <!-- Навигационна лента -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Наемане на коли</a>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="my_reservations.php" class="btn btn-info me-2">Моите резервации</a>
                    <a href="logout.php" class="btn btn-danger">Изход</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="btn btn-dark ms-2">Админ панел</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary me-2">Вход</a>
                    <a href="register.php" class="btn btn-secondary">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Основно съдържание -->
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Налични коли</h1>
        <!-- Форма за филтриране -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="make" class="form-control" placeholder="Марка" value="<?php echo htmlspecialchars($_GET['make'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="model" class="form-control" placeholder="Модел" value="<?php echo htmlspecialchars($_GET['model'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="year" class="form-control" placeholder="Година" value="<?php echo htmlspecialchars($_GET['year'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="price_min" class="form-control" placeholder="Мин. цена" value="<?php echo htmlspecialchars($_GET['price_min'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="price_max" class="form-control" placeholder="Макс. цена" value="<?php echo htmlspecialchars($_GET['price_max'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Филтрирай</button>
                </div>
            </div>
        </form>

        <!-- Показване на автомобили -->
        <div class="row g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($car = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 d-flex align-items-stretch'>";
                    echo "<div class='card'>";
                    echo "<img src='" . htmlspecialchars($car['image_url']) . "' class='card-img-top' alt='Car Image'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($car['make']) . " " . htmlspecialchars($car['model']) . "</h5>";
                    echo "<p class='card-text'><strong>Година:</strong> " . htmlspecialchars($car['year']) . "</p>";
                    echo "<p class='card-text'><strong>Цена/ден:</strong> " . htmlspecialchars($car['price_per_day']) . " лв</p>";
                    echo "<a href='rent_car.php?id=" . $car['id'] . "' class='btn btn-success mt-auto'>Наеми</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>Няма налични коли според зададените критерии.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
