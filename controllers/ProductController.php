<?php
require_once __DIR__ . '/../config/database.php';

class ProductController {

    public function index() {

        global $conn;

        $keyword = $_GET['keyword'] ?? '';
        $price = $_GET['price'] ?? '';
        $category_id = $_GET['category_id'] ?? '';

        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE 1";

        if (!empty($keyword)) {
            $sql .= " AND p.name LIKE '%$keyword%'";
        }

        if ($price == 1) {
            $sql .= " AND p.price < 5000000";
        } elseif ($price == 2) {
            $sql .= " AND p.price BETWEEN 5000000 AND 10000000";
        } elseif ($price == 3) {
            $sql .= " AND p.price > 10000000";
        }

        if (!empty($category_id)) {
            $sql .= " AND p.category_id = $category_id";
        }

        $products = $conn->query($sql);


        $categoryList = [];
        $result = $conn->query("SELECT * FROM categories");
        while($row = $result->fetch_assoc()){
            $categoryList[] = $row;
        }

        include __DIR__ . '/../views/home.php';
    }

   public function detail() {

    global $conn;

    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo "❌ Invalid product ID";
        return;
    }

    // PRODUCT
    $product = $conn->query("
        SELECT p.*, c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.product_id = $id
    ");

    if (!$product || $product->num_rows == 0) {
        echo "❌ Product not found";
        return;
    }

    $product = $product->fetch_assoc();

    // FEEDBACK
    $feedbacks = $conn->query("
        SELECT f.*, u.name 
        FROM feedback f
        LEFT JOIN users u ON f.user_id = u.user_id
        WHERE f.product_id = $id
        ORDER BY f.created_at DESC
    ");

    if (!$feedbacks) {
        $feedbacks = null; 
    }

    // AVG
    $avg = $conn->query("
        SELECT AVG(rating) as avg_rating, COUNT(*) as total
        FROM feedback
        WHERE product_id = $id
    ")->fetch_assoc();

    // SPEC
    $specs = $conn->query("
        SELECT * FROM product_specifications
        WHERE product_id = $id
    ")->fetch_assoc();

    include __DIR__ . '/../views/product_detail.php';
}

// ===== ABOUT US =====
public function about() {
    include __DIR__ . '/../views/about.php';
}

// ===== PRIVACY POLICY =====
public function privacy() {
    include __DIR__ . '/../views/privacy.php';
}
}