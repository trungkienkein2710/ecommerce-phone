<?php
require_once __DIR__ . '/../config/database.php';

class OrderController {

    // ===== MY ORDERS =====
    public function myOrders() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            return;
        }

        global $conn;
        $user_id = (int)$_SESSION['user']['user_id'];

        $orders = $conn->query("
            SELECT * FROM orders 
            WHERE user_id = $user_id
            ORDER BY created_at DESC
        ");

        if (!$orders) {
            die("SQL ERROR: " . $conn->error);
        }

        include __DIR__ . '/../views/order/my_orders.php';
    }

    // ===== ORDER DETAIL =====
    public function detail() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            return;
        }

        global $conn;

        $order_id = (int)$_GET['id'];
        $user_id = (int)$_SESSION['user']['user_id'];

        // Only show users their own orders (except admin/manager).
        $role = $_SESSION['user']['role'];

        $where = ($role == 'admin' || $role == 'manager')
            ? "o.order_id = $order_id"
            : "o.order_id = $order_id AND o.user_id = $user_id";

        // get order
        $order = $conn->query("
            SELECT o.*, u.name 
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE $where
        ")->fetch_assoc();

        if (!$order) {
            die("❌ Order not found");
        }

        // get items
        $items = $conn->query("
            SELECT od.*, p.name, p.image
            FROM order_details od
            LEFT JOIN products p ON od.product_id = p.product_id
            WHERE od.order_id = $order_id
        ");

        if (!$items) {
            die("SQL ERROR: " . $conn->error);
        }

        include __DIR__ . '/../views/order/detail.php';
    }

    // ===== CANCEL ORDER + RESTORE STOCK =====
    public function cancel() {

    if (!isset($_SESSION['user'])) {
        header("Location: index.php?action=login");
        return;
    }

    global $conn;

    $order_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user']['user_id'];
    $role = $_SESSION['user']['role'];

    // 🔐 check role
    if ($role == 'admin' || $role == 'manager') {
        $order = $conn->query("
            SELECT * FROM orders 
            WHERE order_id = $order_id
        ")->fetch_assoc();
    } else {
        $order = $conn->query("
            SELECT * FROM orders 
            WHERE order_id = $order_id 
            AND user_id = $user_id
        ")->fetch_assoc();
    }

    if (!$order) {
        die("❌ Order not found");
    }

    if ($order['status'] != 'pending') {
        die("❌ Only pending orders can be cancelled");
    }

    // 🚀 TRANSACTION
    $conn->begin_transaction();

    try {

        // 🔥 LẤY ITEMS
        $items = $conn->query("
            SELECT product_id, quantity 
            FROM order_details
            WHERE order_id = $order_id
        ");

        if (!$items) {
            throw new Exception("Fetch items failed: " . $conn->error);
        }

        // ❗ DEBUG (nếu cần)
        // echo $items->num_rows;

        // 🔄 RESTORE STOCK
        while ($item = $items->fetch_assoc()) {

            $product_id = (int)$item['product_id'];
            $qty = (int)$item['quantity'];

            $ok = $conn->query("
                UPDATE products 
                SET stock = stock + $qty
                WHERE product_id = $product_id
            ");

            if (!$ok) {
                throw new Exception("Stock update failed: " . $conn->error);
            }
        }

        // ❌ CANCEL
        $ok2 = $conn->query("
            UPDATE orders 
            SET status = 'cancelled'
            WHERE order_id = $order_id
        ");

        if (!$ok2) {
            throw new Exception("Order update failed: " . $conn->error);
        }

        $conn->commit();

    } catch (Exception $e) {

        $conn->rollback();
        die("❌ Cancel failed: " . $e->getMessage());
    }

    header("Location: index.php?action=my_orders");
}
}