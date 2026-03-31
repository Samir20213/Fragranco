<?php
include("auth.php");
include("../config/db.php");

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    die("Invalid product ID");
}

$stmt1 = mysqli_prepare($conn, "DELETE FROM product_sizes_stock WHERE product_id=?");
mysqli_stmt_bind_param($stmt1, "i", $product_id);
mysqli_stmt_execute($stmt1);
mysqli_stmt_close($stmt1);

$stmt2 = mysqli_prepare($conn, "DELETE FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt2, "i", $product_id);
mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);

header("Location: products.php");
exit();
?>