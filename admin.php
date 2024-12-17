<?php
session_start();
include 'db.php';

// Проверка дали потребителят е администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Достъп забранен. Не сте администратор.");
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ Панел</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Админ Панел</h1>
        
        <!-- Бутон за връщане към потребителския изглед -->
        <a href="index.php" class="btn btn-secondary mb-3">Обратно към потребителския изглед</a>
        
        <a href="add_car.php" class="btn btn-primary mb-3">Добави нов автомобил</a>
        <a href="logout.php" class="btn btn-secondary mb-3">Изход</a>
        <h2>Налични автомобили</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Марка</th>
                    <th>Модел</th>
                    <th>Година</th>
                    <th>Цена/ден</th>
                    <th>Снимка</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="car-list">
                <?php
                $sql = "SELECT * FROM cars";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($car = $result->fetch_assoc()) {
                        echo "<tr id='car-" . $car['id'] . "'>";
                        echo "<td>" . $car['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($car['make']) . "</td>";
                        echo "<td>" . htmlspecialchars($car['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($car['year']) . "</td>";
                        echo "<td>" . htmlspecialchars($car['price_per_day']) . " лв</td>";
                        echo "<td><img src='" . htmlspecialchars($car['image_url']) . "' alt='Снимка на автомобила' style='width: 100px; height: auto;'></td>";
                        echo "<td>
                                <a href='edit_car.php?id=" . $car['id'] . "' class='btn btn-warning btn-sm'>Редактиране</a>
                                <button class='btn btn-danger btn-sm delete-car' data-id='" . $car['id'] . "'>Изтриване</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Няма налични автомобили.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Изтриване на автомобил с Ajax
            $('.delete-car').click(function() {
                const carId = $(this).data('id');
                if (confirm('Сигурни ли сте, че искате да изтриете този автомобил?')) {
                    $.ajax({
                        url: 'delete_car.php', // Уверете се, че delete_car.php съществува
                        type: 'POST',
                        data: { id: carId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#car-' + carId).remove();
                                alert(response.message);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Настъпи грешка при изтриването на автомобила.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
