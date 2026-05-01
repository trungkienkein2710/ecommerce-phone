<?php
session_start();

require_once "controllers/ProductController.php";
require_once "controllers/AuthController.php";
require_once "controllers/AdminController.php";
require_once "controllers/ManagerController.php";
require_once "controllers/CartController.php";
require_once "controllers/OrderController.php";

$action = $_GET['action'] ?? 'home';

switch ($action) {

    // ===== AUTH =====
    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // ===== ADMIN =====
    case 'admin':
        (new AdminController())->dashboard();
        break;

    case 'change_role':
        (new AdminController())->changeRole();
        break;

    // ===== MANAGER =====
    case 'manager':
        (new ManagerController())->dashboard();
        break;

    // ===== PRODUCT MANAGEMENT =====
    case 'manager_products':
        (new ManagerController())->products();
        break;

    case 'add_product':
        (new ManagerController())->addProduct();
        break;

    case 'edit_product':
        (new ManagerController())->editProduct();
        break;

    case 'update_product':
        (new ManagerController())->updateProduct();
        break;

    case 'delete_product':
        (new ManagerController())->deleteProduct();
        break;

    // ===== CATEGORY MANAGEMENT =====
    case 'manager_categories':
        (new ManagerController())->categories();
        break;

    case 'add_category':
        (new ManagerController())->addCategory();
        break;

    case 'edit_category':
        (new ManagerController())->editCategory();
        break;

    case 'update_category':
        (new ManagerController())->updateCategory();
        break;

    case 'delete_category':
        (new ManagerController())->deleteCategory();
        break;

    // ===== PRODUCT DETAIL =====
    case 'product_detail':
        (new ProductController())->detail();
        break;

    // ===== CART =====
    case 'add_to_cart':
        (new CartController())->add();
        break;

    case 'cart':
        (new CartController())->view();
        break;

    case 'update_cart':
        (new CartController())->update();
        break;

    case 'remove_cart':
        (new CartController())->remove();
        break;
    
    case 'update_order_status':
        (new ManagerController())->updateOrderStatus();
        break;

    // ===== CHECKOUT (🔥 UPDATED) =====
    case 'checkout':
        (new CartController())->checkoutForm(); // 👉 form
        break;

    case 'place_order':
        (new CartController())->placeOrder(); // 👉 submit
        break;

    case 'my_orders':
        (new OrderController())->myOrders();
        break;    
     case 'manager_orders':
        (new ManagerController())->orders();
        break;

    case 'update_order_status':
        (new ManagerController())->updateOrderStatus();
        break;   
    
    case 'order_detail':
        (new OrderController())->detail();
        break;

    case 'cancel_order':
        (new OrderController())->cancel();
        break;

    case 'add_feedback':
        require_once "controllers/FeedbackController.php";
        (new FeedbackController())->add();
        break;

    case 'profile':
        (new AuthController())->profile();
        break;

    case 'update_profile':
        (new AuthController())->updateProfile();
        break;    

    case 'about':
        (new ProductController())->about();
        break;

    case 'privacy':
        (new ProductController())->privacy();
        break;
    // ===== DEFAULT =====
    default:
        (new ProductController())->index();
        break;
}