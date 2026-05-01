<?php
require_once __DIR__ . '/../config/database.php';

class CartController {

    // ===== ADD TO CART =====
    public function add() {

        global $conn;

        $id = (int)$_GET['id'];

        $result = $conn->query("SELECT * FROM products WHERE product_id = $id");
        if (!$result || $result->num_rows == 0) {
            die("❌ Product not found");
        }

        $product = $result->fetch_assoc();

        // create cart 
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $current_qty = $_SESSION['cart'][$id]['qty'] ?? 0;

        // ❗ check stock
        if ($current_qty + 1 > $product['stock']) {
            die("❌ Not enough stock");
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty' => 1
            ];
        }

        header("Location: index.php?action=cart");
    }

    // ===== VIEW CART =====
    public function view() {
        $cart = $_SESSION['cart'] ?? [];
        include __DIR__ . '/../views/cart/index.php';
    }

    // ===== REMOVE =====
    public function remove() {
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);
        header("Location: index.php?action=cart");
    }

    // ===== UPDATE QTY =====
    public function update() {

        global $conn;

        $id = (int)$_GET['id'];
        $type = $_GET['type'];

        if (!isset($_SESSION['cart'][$id])) {
            header("Location: index.php?action=cart");
            return;
        }

        // get stock in DB
        $product = $conn->query("SELECT stock FROM products WHERE product_id = $id")->fetch_assoc();

        if ($type == 'increase') {

            if ($_SESSION['cart'][$id]['qty'] + 1 > $product['stock']) {
                die("❌ Not enough stock");
            }

            $_SESSION['cart'][$id]['qty']++;
        }

        if ($type == 'decrease') {
            $_SESSION['cart'][$id]['qty']--;

            if ($_SESSION['cart'][$id]['qty'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }

        header("Location: index.php?action=cart");
    }

    // ===== CHECKOUT FORM =====
    public function checkoutForm() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            return;
        }

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            die("❌ Cart is empty");
        }

        include __DIR__ . '/../views/cart/checkout.php';
    }

    // ===== PLACE ORDER =====
    public function placeOrder() {

        global $conn;

        if (!isset($_SESSION['user'])) {
            die("❌ Please login");
        }

        $user_id = $_SESSION['user']['user_id'];
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            die("❌ Cart empty");
        }

        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $payment = $_POST['payment_method'];

        // ===== TOTAL =====
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // ===== TRANSACTION (PRO) =====
        $conn->begin_transaction();

        try {

            // INSERT ORDER
            $conn->query("
                INSERT INTO orders (user_id, total_price, address, phone, payment_method)
                VALUES ('$user_id','$total','$address','$phone','$payment')
            ");

            $order_id = $conn->insert_id;

            // ORDER DETAILS + UPDATE STOCK
            foreach ($cart as $id => $item) {

                // check stock again
                $p = $conn->query("SELECT stock FROM products WHERE product_id = $id")->fetch_assoc();

                if ($p['stock'] < $item['qty']) {
                    throw new Exception("Not enough stock for product ID $id");
                }

                // insert order detail
                $conn->query("
                    INSERT INTO order_details (order_id, product_id, quantity, price)
                    VALUES ('$order_id','$id','{$item['qty']}','{$item['price']}')
                ");

                // Minus STOCK
                $conn->query("
                    UPDATE products
                    SET stock = stock - {$item['qty']}
                    WHERE product_id = $id
                ");
            }

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            die("❌ Order failed: " . $e->getMessage());
        }

        // CLEAR CART
        unset($_SESSION['cart']);

        $_SESSION['success'] = "🎉 Order placed successfully!";
        header("Location: index.php?action=home");
        exit();
    }
}