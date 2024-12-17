<?php
$host = 'localhost';
$user = 'root';
$password = 'deskod123';
$database = 'car_rental';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
