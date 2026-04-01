<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/db.php");
include("includes/header.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container py-5'><h2>Category not found</h2></div>";
    include("includes/footer.php");
    exit();
}

$category_id = (int) $_GET['id'];

// category info আনো
$stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $category_id);
mysqli_stmt_execute($stmt);
$category_result = mysqli_stmt_get_result($stmt);
$category = mysqli_fetch_assoc($category_result);
mysqli_stmt_close($stmt);

if (!$category) {
    echo "<div class='container py-5'><h2>Invalid category</h2></div>";
    include("includes/footer.php");
    exit();
}

// এই category-র products আনো
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE category_id=? AND status='active' ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "i", $category_id);
mysqli_stmt_execute($stmt);
$product_result = mysqli_stmt_get_result($stmt);
?>

<style>
.category-hero {
    background: #000;
    color: gold;
    text-align: center;
    padding: 60px 20px;
}

.category-hero h1 {
    font-size: 56px;
    font-weight: 800;
    margin-bottom: 10px;
}

.category-hero p {
    font-size: 18px;
    color: #f5d76e;
}

.product-section {
    padding: 50px 0;
    background: #f5f5f5;
}

.product-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-8px);
}

.product-card img {
    width: 100%;
    height: 280px;
    object-fit: cover;
}

.product-body {
    padding: 20px;
    text-align: center;
}

.product-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #111;
}

.product-price {
    color: #c99700;
    font-weight: 700;
    font-size: 22px;
    margin-bottom: 12px;
}

.old-price {
    text-decoration: line-through;
    color: #888;
    margin-right: 8px;
}

.product-btn {
    display: inline-block;
    padding: 10px 22px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: 0.3s;
}

.product-btn:hover {
    background: gold;
    color: #000;
}

.no-product {
    text-align: center;
    padding: 60px 20px;
    font-size: 22px;
    color: #555;
}
</style>

<!-- Hero -->
<section class="category-hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($category['name']); ?> Collection</h1>
        <p>Explore premium perfumes in this category</p>
    </div>
</section>

<!-- Products -->
<section class="product-section">
    <div class="container">
        <div class="row g-4">
            <?php if (mysqli_num_rows($product_result) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($product_result)) { ?>
                    <?php
                    $image_path = $row['main_image'];
                    if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                        $image_path = "assets/uploads/" . $image_path;
                    }
                    ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="product-body">
                                <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>

                                <p class="product-price">
                                    <?php if (!empty($row['discount_price']) && $row['discount_price'] > 0) { ?>
                                        <span class="old-price">৳<?php echo htmlspecialchars($row['price']); ?></span>
                                        ৳<?php echo htmlspecialchars($row['discount_price']); ?>
                                    <?php } else { ?>
                                        ৳<?php echo htmlspecialchars($row['price']); ?>
                                    <?php } ?>
                                </p>

                                <a href="product-details.php?id=<?php echo (int)$row['id']; ?>" class="product-btn">View</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="no-product">
                        No products found in this category.
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>