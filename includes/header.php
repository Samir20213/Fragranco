<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRAGRANCO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/fragranco/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/fragranco/home.php">FRAGRANCO</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/fragranco/home.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fragranco/shop.php">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fragranco/about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fragranco/contact.php">Contact</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fragranco/cart.php">Cart</a>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/fragranco/user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/fragranco/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/fragranco/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/fragranco/register.php">Register</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>