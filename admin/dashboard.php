<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// total orders
$total_orders_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$total_orders = mysqli_fetch_assoc($total_orders_query)['total'];

// pending orders
$pending_orders_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE order_status='Pending'");
$pending_orders = mysqli_fetch_assoc($pending_orders_query)['total'];

// delivered orders
$delivered_orders_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE order_status='Delivered'");
$delivered_orders = mysqli_fetch_assoc($delivered_orders_query)['total'];

// total users
$total_users_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($total_users_query)['total'];

// total products
$total_products_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_products = mysqli_fetch_assoc($total_products_query)['total'];

// low stock alerts
$low_stock_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM product_sizes_stock WHERE stock <= 5");
$low_stock = mysqli_fetch_assoc($low_stock_query)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | FRAGRANCO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-title {
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-6px);
        }

        .dashboard-card h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #111;
        }

        .dashboard-card p {
            font-size: 34px;
            font-weight: 800;
            color: #c99700;
            margin: 0;
        }

        .top-bar {
            background: #111;
            color: white;
            padding: 18px 0;
            margin-bottom: 40px;
        }

        .top-bar a {
            color: gold;
            text-decoration: none;
            font-weight: 700;
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">FRAGRANCO Admin Panel</h3>
        </div>
        <div>
            Welcome, <?php echo $_SESSION['admin_username']; ?>

            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="add_product.php" style="color:skyblue;">Add Product</a>
            <a href="orders.php" style="color:lightgreen;">Orders</a>
            <a href="messages.php">Messages</a>
            <a href="logout.php" style="color:red;">Logout</a>
        </div>
    </div>
</div>

<div class="container pb-5">
    <h1 class="dashboard-title text-center">Admin Dashboard</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Total Orders</h4>
                <p><?php echo $total_orders; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Pending Orders</h4>
                <p><?php echo $pending_orders; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Delivered Orders</h4>
                <p><?php echo $delivered_orders; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Total Users</h4>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Total Products</h4>
                <p><?php echo $total_products; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Low Stock Alerts</h4>
                <p><?php echo $low_stock; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>