<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $car_id = intval($_GET['id']);

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
    die("Не е зададен ID на автомобил.");
}

// Извличане на информацията за потребителя от сесията
$user_name = $_SESSION['username'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наем на автомобил</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Наем на автомобил</h1>
        <h2><?php echo htmlspecialchars($car['make']) . " " . htmlspecialchars($car['model']); ?></h2>
        <p><strong>Година:</strong> <?php echo $car['year']; ?></p>
        <p><strong>Цена на ден:</strong> <?php echo htmlspecialchars($car['price_per_day']); ?> лв.</p>
        <p><strong>Снимка:</strong></p>
        <img src="<?php echo htmlspecialchars($car['image_url']); ?>" alt="Снимка на автомобила" style="width: 300px; height: auto;">
        
        <form id="rental-form" method="POST" action="process_rental.php">
            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
            <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($user_name); ?>">
            <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($user_email); ?>">

            <div class="mb-3">
                <label for="rent_date" class="form-label">Дата на наемане</label>
                <input type="date" class="form-control" id="rent_date" name="rent_date" required>
            </div>

            <div class="mb-3">
                <label for="return_date" class="form-label">Дата на връщане</label>
                <input type="date" class="form-control" id="return_date" name="return_date" required>
            </div>

            <button type="submit" class="btn btn-success mt-3">Потвърди наемането</button>
            <a href="index.php" class="btn btn-secondary mt-3">Отказ</a>
        </form>
    </div>

    <script>
        $('#rental-form').submit(function(e) {
            const rentDate = $('#rent_date').val();
            const returnDate = $('#return_date').val();

            if (new Date(rentDate) > new Date(returnDate)) {
                e.preventDefault();
                alert('Датата на връщане трябва да бъде след датата на наемане.');
            }
        });
    </script>
</body>
</html>
