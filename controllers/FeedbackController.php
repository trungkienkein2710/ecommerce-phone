<?php
require_once __DIR__ . '/../config/database.php';

class FeedbackController {

    public function add() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }

        global $conn;

        $user_id = $_SESSION['user']['user_id'];
        $product_id = $_POST['product_id'];
        $rating = $_POST['rating'];
        $comment = trim($_POST['comment']);

        $errors = [];

        // VALIDATE
        if ($rating < 1 || $rating > 5) {
            $errors[] = "Rating must be 1-5";
        }

        if ($comment == '') {
            $errors[] = "Comment required";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=product_detail&id=$product_id");
            exit();
        }

        // INSERT
        $stmt = $conn->prepare("
            INSERT INTO feedback (user_id, product_id, rating, comment)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
        $stmt->execute();

        $_SESSION['success'] = "⭐ Feedback added!";
        header("Location: index.php?action=product_detail&id=$product_id");
        exit();
    }
}