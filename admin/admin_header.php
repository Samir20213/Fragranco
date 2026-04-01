<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | FRAGRANCO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<style>
    .admin-nav a {
        transition: color 0.2s ease, background-color 0.2s ease;
        padding: 6px 12px;
        border-radius: 6px;
    }

    .admin-nav a.text-white:hover {
        background-color: rgba(255,255,255,0.1);
        color: #ffd700 !important;
        text-decoration: none;
    }

    .admin-nav a.text-danger:hover {
        background-color: rgba(255,0,0,0.15);
        color: #ff8080 !important;
        text-decoration: none;
    }
</style>

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand fw-bold" href="dashboard.php">FRAGRANCO Admin</a>

        <div class="admin-nav">
            <a href="dashboard.php" class="text-white me-3">Dashboard</a>
            <a href="products.php" class="text-white me-3">Products</a>
            <a href="add_product.php" class="text-white me-3">Add Product</a>
            <a href="orders.php" class="text-white me-3">Orders</a>
            <a href="messages.php" class="text-white me-3">Messages</a>
            <a href="logout.php" class="text-danger">Logout</a>
        </div>
    </div>
</nav>

<!-- Back Button -->
<div class="container mt-3">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>