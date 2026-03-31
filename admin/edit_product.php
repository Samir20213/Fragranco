<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("auth.php");
include("../config/db.php");

if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$product_id = (int)$_GET['id'];
$message = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$product) {
    die("Product not found");
}

$stocks = [];
$stock_stmt = mysqli_prepare($conn, "SELECT * FROM product_sizes_stock WHERE product_id=?");
mysqli_stmt_bind_param($stock_stmt, "i", $product_id);
mysqli_stmt_execute($stock_stmt);
$stock_result = mysqli_stmt_get_result($stock_stmt);
while ($stock = mysqli_fetch_assoc($stock_result)) {
    $stocks[$stock['size']] = $stock['stock'];
}
mysqli_stmt_close($stock_stmt);

if (isset($_POST['update_product'])) {
    $category_id       = (int)($_POST['category_id'] ?? 0);
    $name              = trim($_POST['name'] ?? '');
    $brand_name        = trim($_POST['brand_name'] ?? '');
    $price             = (float)($_POST['price'] ?? 0);
    $discount_price    = ($_POST['discount_price'] !== '') ? (float)$_POST['discount_price'] : 0;
    $short_description = trim($_POST['short_description'] ?? '');
    $full_description  = trim($_POST['full_description'] ?? '');
    $top_note          = trim($_POST['top_note'] ?? '');
    $middle_note       = trim($_POST['middle_note'] ?? '');
    $base_note         = trim($_POST['base_note'] ?? '');
    $status            = trim($_POST['status'] ?? '');

    $is_featured    = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;

    $size_6   = (int)($_POST['size_6'] ?? 0);
    $size_15  = (int)($_POST['size_15'] ?? 0);
    $size_30  = (int)($_POST['size_30'] ?? 0);
    $size_50  = (int)($_POST['size_50'] ?? 0);
    $size_100 = (int)($_POST['size_100'] ?? 0);

    $main_image = $product['main_image'];

    if (!empty($_FILES['main_image']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_name = $_FILES['main_image']['name'];
        $file_tmp  = $_FILES['main_image']['tmp_name'];
        $file_size = $_FILES['main_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_extensions)) {
            $message = "Only jpg, jpeg, png, webp files are allowed ❌";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $message = "Image size must be under 2MB ❌";
        } else {
            $check = getimagesize($file_tmp);
            if ($check === false) {
                $message = "Uploaded file is not a valid image ❌";
            } else {
                $safe_name = time() . "_" . preg_replace("/[^A-Za-z0-9.\-_]/", "_", $file_name);
                if (move_uploaded_file($file_tmp, "../assets/uploads/" . $safe_name)) {
                    $main_image = $safe_name;
                } else {
                    $message = "Image upload failed ❌";
                }
            }
        }
    }

    if (empty($message)) {
        $update_stmt = mysqli_prepare($conn, "UPDATE products SET
            category_id=?,
            name=?,
            brand_name=?,
            price=?,
            discount_price=?,
            main_image=?,
            short_description=?,
            full_description=?,
            top_note=?,
            middle_note=?,
            base_note=?,
            is_featured=?,
            is_new_arrival=?,
            is_best_seller=?,
            status=?
            WHERE id=?");

        if ($update_stmt) {
            mysqli_stmt_bind_param(
                $update_stmt,
                "issddssssssiiisi",
                $category_id,
                $name,
                $brand_name,
                $price,
                $discount_price,
                $main_image,
                $short_description,
                $full_description,
                $top_note,
                $middle_note,
                $base_note,
                $is_featured,
                $is_new_arrival,
                $is_best_seller,
                $status,
                $product_id
            );

            if (mysqli_stmt_execute($update_stmt)) {
                mysqli_stmt_close($update_stmt);

                $upd = mysqli_prepare($conn, "UPDATE product_sizes_stock SET stock=? WHERE product_id=? AND size=?");
                $size_map = [
                    ['6ml', $size_6],
                    ['15ml', $size_15],
                    ['30ml', $size_30],
                    ['50ml', $size_50],
                    ['100ml', $size_100]
                ];

                foreach ($size_map as $s) {
                    $size_name = $s[0];
                    $stock_val = $s[1];
                    mysqli_stmt_bind_param($upd, "iis", $stock_val, $product_id, $size_name);
                    mysqli_stmt_execute($upd);
                }
                mysqli_stmt_close($upd);

                $message = "Product updated successfully ✅";

                $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
                mysqli_stmt_bind_param($stmt, "i", $product_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $product = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);

                $stocks = [];
                $stock_stmt = mysqli_prepare($conn, "SELECT * FROM product_sizes_stock WHERE product_id=?");
                mysqli_stmt_bind_param($stock_stmt, "i", $product_id);
                mysqli_stmt_execute($stock_stmt);
                $stock_result = mysqli_stmt_get_result($stock_stmt);
                while ($stock = mysqli_fetch_assoc($stock_result)) {
                    $stocks[$stock['size']] = $stock['stock'];
                }
                mysqli_stmt_close($stock_stmt);
            } else {
                $message = "Update failed ❌";
                mysqli_stmt_close($update_stmt);
            }
        } else {
            $message = "Database prepare error ❌";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; }
        .form-box { background:#fff; padding:30px; border-radius:20px; box-shadow:0 8px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">Edit Product</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php } ?>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Brand Name</label>
                    <input type="text" name="brand_name" class="form-control" value="<?php echo htmlspecialchars($product['brand_name']); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php
                        $cat_query = mysqli_query($conn, "SELECT * FROM categories");
                        while ($cat = mysqli_fetch_assoc($cat_query)) {
                            $selected = ($cat['id'] == $product['category_id']) ? "selected" : "";
                            echo "<option value='".(int)$cat['id']."' $selected>".htmlspecialchars($cat['name'])."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Discount Price</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control" value="<?php echo htmlspecialchars($product['discount_price']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Change Image</label>
                    <input type="file" name="main_image" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Current Image</label><br>
                    <?php
                    $image_path = $product['main_image'];
                    if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                        $image_path = "../assets/uploads/" . $image_path;
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" width="120" style="border-radius:10px;">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Short Description</label>
                    <textarea name="short_description" class="form-control" rows="3"><?php echo htmlspecialchars($product['short_description']); ?></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Full Description</label>
                    <textarea name="full_description" class="form-control" rows="3"><?php echo htmlspecialchars($product['full_description']); ?></textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Top Note</label>
                    <input type="text" name="top_note" class="form-control" value="<?php echo htmlspecialchars($product['top_note']); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Middle Note</label>
                    <input type="text" name="middle_note" class="form-control" value="<?php echo htmlspecialchars($product['middle_note']); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Base Note</label>
                    <input type="text" name="base_note" class="form-control" value="<?php echo htmlspecialchars($product['base_note']); ?>">
                </div>
            </div>

            <hr>

            <h4 class="mb-3">Stock by Size</h4>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label>6ml</label>
                    <input type="number" name="size_6" class="form-control" value="<?php echo (int)($stocks['6ml'] ?? 0); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label>15ml</label>
                    <input type="number" name="size_15" class="form-control" value="<?php echo (int)($stocks['15ml'] ?? 0); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label>30ml</label>
                    <input type="number" name="size_30" class="form-control" value="<?php echo (int)($stocks['30ml'] ?? 0); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label>50ml</label>
                    <input type="number" name="size_50" class="form-control" value="<?php echo (int)($stocks['50ml'] ?? 0); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label>100ml</label>
                    <input type="number" name="size_100" class="form-control" value="<?php echo (int)($stocks['100ml'] ?? 0); ?>">
                </div>
            </div>

            <hr>

            <h4 class="mb-3">Tags</h4>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" <?php if($product['is_featured'] == 1) echo "checked"; ?>>
                <label class="form-check-label" for="featured">Featured Product</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="newarrival" <?php if($product['is_new_arrival'] == 1) echo "checked"; ?>>
                <label class="form-check-label" for="newarrival">New Arrival</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="bestseller" <?php if($product['is_best_seller'] == 1) echo "checked"; ?>>
                <label class="form-check-label" for="bestseller">Best Seller</label>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?php if($product['status'] == 'active') echo "selected"; ?>>Active</option>
                    <option value="inactive" <?php if($product['status'] == 'inactive') echo "selected"; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" name="update_product" class="btn btn-dark">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>