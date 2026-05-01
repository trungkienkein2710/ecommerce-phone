<?php
$conn = new mysqli("localhost", "root", "123456", "ecommerce_phone_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>