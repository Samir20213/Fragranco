<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("auth.php");
include("../config/db.php");

$message = "";

if (isset($_POST['add_product'])) {
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
    $image_url         = trim($_POST['image_url'] ?? '');

    $is_featured    = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;

    $size_6   = (int)($_POST['size_6'] ?? 0);
    $size_15  = (int)($_POST['size_15'] ?? 0);
    $size_30  = (int)($_POST['size_30'] ?? 0);
    $size_50  = (int)($_POST['size_50'] ?? 0);
    $size_100 = (int)($_POST['size_100'] ?? 0);

    $main_image = "";

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
    } elseif (!empty($image_url)) {
        if (filter_var($image_url, FILTER_VALIDATE_URL)) {
            $main_image = $image_url;
        } else {
            $message = "Invalid image URL ❌";
        }
    }

    if (empty($message)) {
        if (
            $category_id <= 0 ||
            empty($name) ||
            empty($brand_name) ||
            $price <= 0 ||
            empty($status) ||
            empty($main_image)
        ) {
            $message = "Please fill all required fields correctly ❌";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO products
            (category_id, name, brand_name, price, discount_price, main_image, short_description, full_description, top_note, middle_note, base_note, is_featured, is_new_arrival, is_best_seller, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "issddssssssiiis",
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
                    $status
                );

                if (mysqli_stmt_execute($stmt)) {
                    $product_id = mysqli_insert_id($conn);
                    mysqli_stmt_close($stmt);

                    $stock_stmt = mysqli_prepare($conn, "INSERT INTO product_sizes_stock (product_id, size, stock) VALUES (?, ?, ?)");

                    if ($stock_stmt) {
                        $sizes = [
                            ['6ml', $size_6],
                            ['15ml', $size_15],
                            ['30ml', $size_30],
                            ['50ml', $size_50],
                            ['100ml', $size_100]
                        ];

                        foreach ($sizes as $s) {
                            $size_name = $s[0];
                            $stock_val = $s[1];
                            mysqli_stmt_bind_param($stock_stmt, "isi", $product_id, $size_name, $stock_val);
                            mysqli_stmt_execute($stock_stmt);
                        }
                        mysqli_stmt_close($stock_stmt);
                    }

                    $message = "Product added successfully ✅";
                } else {
                    $message = "Insert failed ❌";
                    mysqli_stmt_close($stmt);
                }
            } else {
                $message = "Database prepare error ❌";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .page-title { font-size: 36px; font-weight: 800; margin-bottom: 25px; }
        .form-box { background: #fff; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); padding: 30px; }
        .form-box label { font-weight: 600; margin-bottom: 6px; }
        .form-box .form-control, .form-box .form-select { border-radius: 12px; margin-bottom: 15px; }
        .save-btn { background: #111; color: white; border: none; border-radius: 25px; padding: 12px 24px; font-weight: 700; }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="page-title text-center">Add New Product</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php } ?>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Brand Name</label>
                    <input type="text" name="brand_name" class="form-control" value="FRAGRANCO" required>
                </div>

                <div class="col-md-6">
                    <label>Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php
                        $cat_query = mysqli_query($conn, "SELECT * FROM categories");
                        while ($cat = mysqli_fetch_assoc($cat_query)) {
                            echo "<option value='" . (int)$cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Discount Price</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Image Upload</label>
                    <input type="file" name="main_image" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Image URL</label>
                    <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                </div>

                <div class="col-md-6">
                    <label>Short Description</label>
                    <textarea name="short_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                    <label>Full Description</label>
                    <textarea name="full_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-4">
                    <label>Top Note</label>
                    <input type="text" name="top_note" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Middle Note</label>
                    <input type="text" name="middle_note" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Base Note</label>
                    <input type="text" name="base_note" class="form-control">
                </div>
            </div>

            <hr class="my-4">

            <h4 class="mb-3">Stock by Size</h4>
            <div class="row">
                <div class="col-md-2">
                    <label>6ml</label>
                    <input type="number" name="size_6" class="form-control" value="0">
                </div>
                <div class="col-md-2">
                    <label>15ml</label>
                    <input type="number" name="size_15" class="form-control" value="0">
                </div>
                <div class="col-md-2">
                    <label>30ml</label>
                    <input type="number" name="size_30" class="form-control" value="0">
                </div>
                <div class="col-md-2">
                    <label>50ml</label>
                    <input type="number" name="size_50" class="form-control" value="0">
                </div>
                <div class="col-md-2">
                    <label>100ml</label>
                    <input type="number" name="size_100" class="form-control" value="0">
                </div>
            </div>

            <hr class="my-4">

            <h4 class="mb-3">Product Tags</h4>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured">
                <label class="form-check-label" for="featured">Featured Product</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="newarrival">
                <label class="form-check-label" for="newarrival">New Arrival</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="bestseller">
                <label class="form-check-label" for="bestseller">Best Seller</label>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="add_product" class="save-btn">Add Product</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>