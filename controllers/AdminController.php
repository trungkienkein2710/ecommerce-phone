<?php

require_once __DIR__ . '/../config/database.php';

class AdminController {

    // Check admin
    private function checkAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            die("❌ No access");
        }
    }

    // Dashboard
    public function dashboard() {
        $this->checkAdmin();

        global $conn;
        $users = $conn->query("SELECT * FROM users");
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // Change Role
    public function changeRole() {
        $this->checkAdmin();

        global $conn;

        $id = $_GET['id'];
        $role = $_GET['role'];

        $conn->query("UPDATE users SET role='$role' WHERE user_id=$id");

        header("Location: index.php?action=admin");
    }
}
?>