<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($conn, "SELECT products.*, categories.name AS category_name 
FROM products 
LEFT JOIN categories ON products.category_id = categories.id
ORDER BY products.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .page-title {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 25px;
        }
        .table-box {
            background: #fff;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
        .btn-sm-custom {
            padding: 6px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .edit-btn {
            background: #111;
            color: #fff;
        }
        .delete-btn {
            background: crimson;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="page-title text-center">Manage Products</h1>

    <div class="mb-3 text-end">
        <a href="add_product.php" class="btn btn-dark">+ Add New Product</a>
    </div>

    <div class="table-box">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)) { ?>
                        <?php
                        $image_path = $row['main_image'];
                        if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                            $image_path = "../assets/uploads/" . $image_path;
                        }
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="<?php echo $image_path; ?>" class="product-img" alt=""></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td>৳<?php echo $row['price']; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-sm-custom edit-btn">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn-sm-custom delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>