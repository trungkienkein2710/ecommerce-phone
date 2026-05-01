<?php
require_once __DIR__ . '/../config/database.php';

class Product {

    public static function getAll() {
        global $conn;
        $sql = "SELECT * FROM products";
        return $conn->query($sql);
    }

}
?>