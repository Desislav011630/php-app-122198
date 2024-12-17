<?php 
session_start();
include 'db.php'; // Включваме връзката с базата данни

// Проверяваме дали потребителят е логнат
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Извличаме резервациите за текущия потребител
$sql = "
    SELECT r.car_id, c.make, c.model, c.year, r.rent_date, r.return_date 
    FROM reservations r 
    JOIN cars c ON r.car_id = c.id 
    WHERE r.customer_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Моите резервации</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Моите резервации</h1>
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php" class="btn btn-secondary me-2">Обратно към началото</a>
            <a href="logout.php" class="btn btn-danger">Изход</a>
        </div>

        <?php if ($reservations->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID на автомобил</th>
                        <th>Марка</th>
                        <th>Модел</th>
                        <th>Година</th>
                        <th>Дата на наемане</th>
                        <th>Дата на връщане</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['car_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['make']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['model']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['year']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['rent_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['return_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-warning">Нямате направени резервации.</p>
        <?php endif; ?>
    </div>
</body>
</html>
