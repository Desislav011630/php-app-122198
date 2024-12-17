<?php
include 'db.php';
$car_id = $_GET['id'];
$result = $conn->query("SELECT * FROM cars WHERE id = $car_id");
$car = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "{$car['make']} {$car['model']}"; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4"><?php echo "{$car['make']} {$car['model']}"; ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $car['image_url']; ?>" alt="<?php echo $car['make']; ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <p><strong>Year:</strong> <?php echo $car['year']; ?></p>
                <p><strong>Price per day:</strong> $<?php echo $car['price_per_day']; ?></p>
                <form action="rent_car.php" method="POST" class="mt-3">
                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                    <div class="mb-3">
                        <input type="text" name="customer_name" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="customer_email" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="rent_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="return_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Rent Car</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
