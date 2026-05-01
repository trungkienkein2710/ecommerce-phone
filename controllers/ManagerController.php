<?php
require_once __DIR__ . '/../config/database.php';

class ManagerController {

    private function checkManager() {
        if (!isset($_SESSION['user']) || 
           !in_array($_SESSION['user']['role'], ['manager','admin'])) {
            die("No Access");
        }
    }

    // ===== DASHBOARD =====
    public function dashboard() {
    $this->checkManager();
    global $conn;

    $totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
    $totalCategories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];
    $totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];

    include __DIR__ . '/../views/manager/dashboard.php';
}

    // ===== PRODUCT LIST =====
    public function products() {
        $this->checkManager();
        global $conn;

        $products = $conn->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id
        ");

        if (!$products) die("SQL ERROR: " . $conn->error);

        include __DIR__ . '/../views/manager/products.php';
    }

    // ===== ADD PRODUCT =====
    public function addProduct() {
        $this->checkManager();
        global $conn;

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = trim($_POST['name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stock = $_POST['stock'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;

            // VALIDATE
            if ($name == '') $errors[] = "Product name is required";
            if (!is_numeric($price) || $price < 0) $errors[] = "Invalid price";
            if (!is_numeric($stock) || $stock < 0) $errors[] = "Invalid stock";

            // SPEC VALIDATE
            $ram = trim($_POST['ram'] ?? '');
            $storage = trim($_POST['storage'] ?? '');
            $battery = trim($_POST['battery'] ?? '');

            if ($ram && !preg_match('/^[0-9]+(GB|gb)$/', $ram)) {
                $errors[] = "RAM format: 8GB";
            }

            if ($storage && !preg_match('/^[0-9]+(GB|TB)$/', $storage)) {
                $errors[] = "Storage format: 128GB / 1TB";
            }

            if ($battery && !preg_match('/^[0-9]+mAh$/', $battery)) {
                $errors[] = "Battery format: 5000mAh";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: index.php?action=add_product");
                exit();
            }

            // IMAGE
            $image = $_FILES['image']['name'] ?? '';
            if ($image) {
                move_uploaded_file($_FILES['image']['tmp_name'], "public/assets/images/" . $image);
            }

            // INSERT PRODUCT
            $stmt = $conn->prepare("
                INSERT INTO products (name, description, price, stock, image, category_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("ssdisi",
                $name,
                $_POST['description'],
                $price,
                $stock,
                $image,
                $category_id
            );

            if (!$stmt->execute()) {
                die($stmt->error);
            }

            $product_id = $stmt->insert_id;

            // SPEC
            $display = $_POST['display'] ?? null;
            $chipset = $_POST['chipset'] ?? null;
            $cpu = $_POST['cpu'] ?? null;
            $os = $_POST['os'] ?? null;
            $nfc = isset($_POST['nfc']) ? 1 : 0;

            if ($display || $chipset || $cpu || $ram || $storage || $battery || $os || $nfc) {

                $stmt = $conn->prepare("
                    INSERT INTO product_specifications
                    (product_id, display, chipset, cpu, ram, storage, battery, os, nfc)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->bind_param("isssssssi",
                    $product_id, $display, $chipset, $cpu,
                    $ram, $storage, $battery, $os, $nfc
                );

                $stmt->execute();
            }

            $_SESSION['success'] = "✅ Product added!";
            header("Location: index.php?action=manager_products");
            exit();
        }

        $categories = $conn->query("SELECT * FROM categories");
        include __DIR__ . '/../views/manager/add_product.php';
    }

    // ===== UPDATE PRODUCT =====
    public function editProduct() {
    $this->checkManager();
    global $conn;

    $id = (int)($_GET['id'] ?? 0);

    $product = $conn->query("
        SELECT * FROM products WHERE product_id = $id
    ")->fetch_assoc();

    $spec = $conn->query("
        SELECT * FROM product_specifications WHERE product_id = $id
    ")->fetch_assoc();

    $categories = $conn->query("SELECT * FROM categories");

    include __DIR__ . '/../views/manager/edit_product.php';
}

   public function updateProduct() {
    $this->checkManager();
    global $conn;

    $id = $_POST['product_id'];
    $errors = [];

    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if ($name == '') $errors[] = "Name required";
    if (!is_numeric($price) || $price < 0) $errors[] = "Invalid price";
    if (!is_numeric($stock) || $stock < 0) $errors[] = "Invalid stock";

    // lấy ảnh cũ
    $old = $conn->query("SELECT image FROM products WHERE product_id=$id")->fetch_assoc();
    $image = $old['image'];

    // ===== IMAGE UPDATE =====
    if (!empty($_FILES['image']['name'])) {

        $fileName = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, WEBP allowed";
        } else {
            $image = time() . "_" . $fileName;
            move_uploaded_file($tmp, "public/assets/images/" . $image);
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?action=edit_product&id=$id");
        exit();
    }

    // ===== UPDATE PRODUCT =====
    $stmt = $conn->prepare("
        UPDATE products SET 
        name=?, description=?, price=?, stock=?, image=?, category_id=?
        WHERE product_id=?
    ");

    $stmt->bind_param("ssdissi",
        $name,
        $_POST['description'],
        $price,
        $stock,
        $image,
        $_POST['category_id'],
        $id
    );

    if (!$stmt->execute()) {
        die($stmt->error);
    }

    // ===== SPEC =====
    $display = $_POST['display'] ?? '';
    $chipset = $_POST['chipset'] ?? '';
    $cpu = $_POST['cpu'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $storage = $_POST['storage'] ?? '';
    $battery = $_POST['battery'] ?? '';
    $os = $_POST['os'] ?? '';
    $nfc = isset($_POST['nfc']) ? 1 : 0;

    $hasSpec = !empty(array_filter([
        $display, $chipset, $cpu, $ram, $storage, $battery, $os, $nfc
    ]));

    $check = $conn->query("SELECT * FROM product_specifications WHERE product_id=$id");

    if ($hasSpec) {

        if ($check->num_rows > 0) {
            $stmt = $conn->prepare("
                UPDATE product_specifications SET
                display=?, chipset=?, cpu=?, ram=?, storage=?, battery=?, os=?, nfc=?
                WHERE product_id=?
            ");

            $stmt->bind_param("sssssssii",
                $display, $chipset, $cpu,
                $ram, $storage, $battery,
                $os, $nfc, $id
            );

            $stmt->execute();

        } else {
            $stmt = $conn->prepare("
                INSERT INTO product_specifications
                (product_id, display, chipset, cpu, ram, storage, battery, os, nfc)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("isssssssi",
                $id, $display, $chipset, $cpu,
                $ram, $storage, $battery,
                $os, $nfc
            );

            $stmt->execute();
        }

    } else {
        $conn->query("DELETE FROM product_specifications WHERE product_id=$id");
    }

    $_SESSION['success'] = "✅ Product updated!";
    header("Location: index.php?action=manager_products");
    exit();
}

    // ===== DELETE PRODUCT =====
    public function deleteProduct() {
        $this->checkManager();
        global $conn;

        $id = $_GET['id'];

        $conn->query("DELETE FROM products WHERE product_id=$id");

        $_SESSION['success'] = "🗑️ Deleted!";
        header("Location: index.php?action=manager_products");
        exit();
    }

    // ===== CATEGORY LIST =====
    public function categories() {
        $this->checkManager();
        global $conn;

        $categories = $conn->query("
            SELECT c.*, COUNT(p.product_id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.category_id = p.category_id
            GROUP BY c.category_id
        ");

        include __DIR__ . '/../views/manager/categories.php';
    }
    public function addCategory() {
    $this->checkManager();
    global $conn;

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');

        if ($name == '') {
            $errors[] = "Category name is required";
        }

        if (strlen($name) < 3) {
            $errors[] = "Category must be at least 3 characters";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=add_category");
            exit();
        }

        $conn->query("
            INSERT INTO categories (name, description)
            VALUES ('$name','$desc')
        ");

        $_SESSION['success'] = "✅ Category added!";
        header("Location: index.php?action=manager_categories");
        exit();
    }

    include __DIR__ . '/../views/manager/add_category.php';
}

public function editCategory() {
    $this->checkManager();
    global $conn;

    $id = $_GET['id'] ?? 0;

    $result = $conn->query("SELECT * FROM categories WHERE category_id = $id");

    if (!$result || $result->num_rows == 0) {
        $_SESSION['error'] = "❌ Category not found";
        header("Location: index.php?action=manager_categories");
        exit();
    }

    $category = $result->fetch_assoc();

    include __DIR__ . '/../views/manager/edit_category.php';
}

public function updateCategory() {
    $this->checkManager();
    global $conn;

    $id = $_POST['category_id'];
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);

    $errors = [];

    if ($name == '') {
        $errors[] = "Category name is required";
    }

    if (strlen($name) < 3) {
        $errors[] = "Category must be at least 3 characters";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?action=edit_category&id=$id");
        exit();
    }

    $conn->query("
        UPDATE categories SET
        name = '$name',
        description = '$desc'
        WHERE category_id = $id
    ");

    $_SESSION['success'] = "✅ Category updated!";
    header("Location: index.php?action=manager_categories");
}

    // ===== DELETE CATEGORY =====
    public function deleteCategory() {
        $this->checkManager();
        global $conn;

        $id = $_GET['id'];

        $check = $conn->query("
            SELECT COUNT(*) as total FROM products WHERE category_id = $id
        ")->fetch_assoc();

        if ($check['total'] > 0) {
            $_SESSION['error'] = "❌ Category is in use!";
        } else {
            $conn->query("DELETE FROM categories WHERE category_id=$id");
            $_SESSION['success'] = "🗑️ Deleted!";
        }

        header("Location: index.php?action=manager_categories");
        exit();
    }

    // ===== ORDERS LIST =====
public function orders() {
    $this->checkManager();
    global $conn;

    $orders = $conn->query("
        SELECT o.*, u.name 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY o.created_at DESC
    ");

    if (!$orders) {
        die("SQL ERROR: " . $conn->error);
    }

    include __DIR__ . '/../views/manager/orders.php';
}

// ===== UPDATE ORDER STATUS =====
public function updateOrderStatus() {
    $this->checkManager();
    global $conn;

    $id = $_GET['id'] ?? null;
    $new_status = $_GET['status'] ?? null;

    $allowed = ['pending','shipping','completed','cancelled'];

    if (!$id || !in_array($new_status, $allowed)) {
        $_SESSION['error'] = "❌ Invalid request";
        header("Location: index.php?action=manager_orders");
        exit();
    }

    //GET STATUS 
    $result = $conn->query("
        SELECT status FROM orders WHERE order_id = $id
    ");

    if (!$result || $result->num_rows == 0) {
        $_SESSION['error'] = "❌ Order not found";
        header("Location: index.php?action=manager_orders");
        exit();
    }

    $order = $result->fetch_assoc();
    $current_status = $order['status'];

    // NO EXCHANGES allowed if already completed or cancelled.
    if (in_array($current_status, ['completed','cancelled'])) {
        $_SESSION['error'] = "❌ Cannot change status of completed/cancelled order";
        header("Location: index.php?action=manager_orders");
        exit();
    }

    // block flow error
    $validFlow = [
        'pending' => ['shipping','cancelled'],
        'shipping' => ['completed'],
    ];

    if (isset($validFlow[$current_status]) && 
        !in_array($new_status, $validFlow[$current_status])) {

        $_SESSION['error'] = "❌ Invalid status flow";
        header("Location: index.php?action=manager_orders");
        exit();
    }

    // UPDATE
    $stmt = $conn->prepare("
        UPDATE orders SET status = ?
        WHERE order_id = ?
    ");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();

    $_SESSION['success'] = "✅ Order updated!";
    header("Location: index.php?action=manager_orders");
    exit();
}


}
?>