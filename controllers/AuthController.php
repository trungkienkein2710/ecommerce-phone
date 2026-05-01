<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {

 // ===== REGISTER =====
public function register() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];

        $errors = [];

        // VALIDATE
        if ($name == '') $errors[] = "Name is required";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email";
        }

        if (!preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors[] = "Phone must be 9-11 digits";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }

        // CHECK EMAIL EXISTS
        $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $errors[] = "Email already exists";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=register");
            exit();
        }

        // HASH PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // INSERT
        $conn->query("
            INSERT INTO users (name, email, password, phone)
            VALUES ('$name','$email','$hash','$phone')
        ");

        $_SESSION['success'] = "🎉 Register success! Please login";
        header("Location: index.php?action=login");
        exit();
    }

    include __DIR__ . '/../views/register.php';
}

public function login() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = [];

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // ===== VALIDATE =====
        if ($email == '') {
            $errors[] = "Email is required";
        }

        if ($password == '') {
            $errors[] = "Password is required";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=login");
            exit();
        }

        // ===== CHECK USER =====
        $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

        if ($result->num_rows == 0) {
            $errors[] = "Email not found";
        } else {
            $user = $result->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                $errors[] = "Wrong password";
            }
        }

        // ===== IF ERROR =====
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=login");
            exit();
        }

        // ===== SUCCESS =====
        $_SESSION['user'] = $user;

        header("Location: index.php");
        exit();
    }

    include __DIR__ . '/../views/login.php';
}

// ===== PROFILE PAGE =====
public function profile() {
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit();
    }

    include __DIR__ . '/../views/profile.php';
}

// ===== UPDATE PROFILE =====
public function updateProfile() {
    global $conn;

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        exit();
    }

    $id = $_SESSION['user']['user_id'];

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    $errors = [];

    // VALIDATE
    if ($name == '') $errors[] = "Name required";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    // check email trùng
    $check = $conn->query("
        SELECT * FROM users 
        WHERE email = '$email' AND user_id != $id
    ");
    if ($check->num_rows > 0) {
        $errors[] = "Email already exists";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?action=profile");
        exit();
    }

    // UPDATE BASIC INFO
    $conn->query("
        UPDATE users SET
        name='$name',
        email='$email',
        phone='$phone'
        WHERE user_id=$id
    ");

    // UPDATE PASSWORD 
    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $conn->query("
            UPDATE users SET password='$hash'
            WHERE user_id=$id
        ");
    }

    // UPDATE SESSION
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;

    $_SESSION['success'] = "✅ Profile updated!";
    header("Location: index.php?action=profile");
    exit();
}

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }
}
?>