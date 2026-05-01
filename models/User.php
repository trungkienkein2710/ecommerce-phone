<?php
require_once __DIR__ . '/../config/database.php';

class User {

    public static function create($name, $email, $password) {
        global $conn;

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$hash', 'customer')";

        return $conn->query($sql);
    }

    public static function findByEmail($email) {
        global $conn;

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        return $result->fetch_assoc();
    }
}
?>