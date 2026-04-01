<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/db.php");
include("includes/header.php");

if (!isset($_GET['id'])) {
    echo "<h2 class='text-center mt-5'>No product selected</h2>";
    include("includes/footer.php");
    exit();
}

$product_id = (int) $_GET['id'];

$product_query = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id' AND status='active'");

if (!$product_query) {
    die("SQL Error: " . mysqli_error($conn));
}

$product = mysqli_fetch_assoc($product_query);

if (!$product) {
    echo "<h2 class='text-center mt-5'>Product not found</h2>";
    include("includes/footer.php");
    exit();
}

$size_query = mysqli_query($conn, "SELECT * FROM product_sizes_stock WHERE product_id='$product_id'");

if (!$size_query) {
    die("Size Query Error: " . mysqli_error($conn));
}

$sizes = [];
while ($size_row = mysqli_fetch_assoc($size_query)) {
    $sizes[] = $size_row;
}
?>

<style>
.product-details-section {
    padding: 60px 0;
}

.product-image-box img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.product-info-box {
    padding: 10px 20px;
}

.product-name {
    font-size: 42px;
    font-weight: 800;
    color: #111;
    margin-bottom: 10px;
}

.product-brand {
    font-size: 18px;
    color: #777;
    margin-bottom: 15px;
}

.product-price {
    font-size: 30px;
    font-weight: 800;
    color: #c99700;
    margin-bottom: 15px;
}

.old-price {
    text-decoration: line-through;
    color: #888;
    font-size: 20px;
    margin-right: 10px;
}

.desc-box {
    margin: 20px 0;
    color: #444;
    line-height: 1.8;
}

.note-box {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
}

.size-box {
    margin: 20px 0;
}

.size-box label {
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.size-select,
.qty-input {
    width: 100%;
    max-width: 250px;
    padding: 12px 15px;
    border-radius: 12px;
    border: 1px solid #ddd;
    margin-bottom: 15px;
}

.stock-list {
    background: #fff8e5;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
}

.action-btn {
    display: inline-block;
    padding: 12px 28px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 700;
    margin-right: 10px;
    transition: 0.3s;
    border: none;
}

.cart-btn {
    background: #111;
    color: #fff;
}

.buy-btn {
    background: linear-gradient(135deg, #ffd700, #ffb300);
    color: #111;
}

.related-title {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 30px;
}

.related-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
    transition: 0.35s;
    height: 100%;
}

.related-card img {
    width: 100%;
    height: 240px;
    object-fit: cover;
}

.related-body {
    padding: 20px;
    text-align: center;
}

.related-name {
    font-size: 22px;
    font-weight: 700;
}

.related-price {
    color: #c99700;
    font-weight: 700;
    margin: 10px 0;
}

.related-btn {
    display: inline-block;
    padding: 10px 18px;
    background: #111;
    color: white;
    text-decoration: none;
    border-radius: 25px;
}
</style>

<section class="product-details-section">
    <div class="container">
        <div class="row align-items-start g-4">
            
            <div class="col-md-6">
                <div class="product-image-box">
                    <?php
                    $image_path = $product['main_image'];
                    if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                        $image_path = "assets/uploads/" . $image_path;
                    }
                    ?>
                    <img src="<?php echo $image_path; ?>" alt="<?php echo $product['name']; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="product-info-box">
                    <h1 class="product-name"><?php echo $product['name']; ?></h1>
                    <p class="product-brand">Brand: <?php echo $product['brand_name']; ?></p>

                    <div class="product-price">
                        <?php if (!empty($product['discount_price']) && $product['discount_price'] > 0) { ?>
                            <span class="old-price">৳<?php echo $product['price']; ?></span>
                            ৳<?php echo $product['discount_price']; ?>
                        <?php } else { ?>
                            ৳<?php echo $product['price']; ?>
                        <?php } ?>
                    </div>

                    <div class="desc-box">
                        <p><strong>Short Description:</strong> <?php echo $product['short_description']; ?></p>
                        <p><strong>Full Description:</strong> <?php echo $product['full_description']; ?></p>
                    </div>

                    <div class="note-box">
                        <p><strong>Top Note:</strong> <?php echo $product['top_note']; ?></p>
                        <p><strong>Middle Note:</strong> <?php echo $product['middle_note']; ?></p>
                        <p><strong>Base Note:</strong> <?php echo $product['base_note']; ?></p>
                    </div>

                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                        <div class="size-box">
                            <label for="size">Select Size</label>
                            <select name="size" id="size" class="size-select" required>
                                <?php
                                $available_found = false;
                                if (!empty($sizes)) {
                                    foreach ($sizes as $size) {
                                        if ((int)$size['stock'] > 0) {
                                            $available_found = true;
                        ?>
                                            <option value="<?php echo $size['size']; ?>">
                                                <?php echo $size['size']; ?> (Available)
                                            </option>
                        <?php
                                        }
                                    }
                                }

                                if (!$available_found) {
                                    echo '<option value="">Out of Stock</option>';
                                }
                                ?>
                            </select>

                            <label for="quantity">Quantity</label>
                            <input type="number" name="qty" id="quantity" class="qty-input" value="1" min="1" required>
                        </div>

                        <div class="stock-list">
                            <strong>Available Stock by Size:</strong><br>
                            <?php if (!empty($sizes)) { ?>
                                <?php foreach ($sizes as $size) { ?>
                                    <?php echo $size['size']; ?> = <?php echo $size['stock']; ?> pcs<br>
                                <?php } ?>
                            <?php } else { ?>
                                No stock data found
                            <?php } ?>
                        </div>

                        <div>
                            <?php if ($available_found) { ?>
                                <button type="submit" name="add_to_cart" class="action-btn cart-btn">Add to Cart</button>
                                <button type="submit" name="buy_now" class="action-btn buy-btn">Buy Now</button>
                            <?php } else { ?>
                                <button type="button" class="action-btn cart-btn" disabled style="opacity:0.6; cursor:not-allowed;">Out of Stock</button>
                            <?php } ?>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <h2 class="related-title text-center">Related Products</h2>

        <div class="row g-4">
            <?php
            $category_id = $product['category_id'];
            $related_query = mysqli_query($conn, "SELECT * FROM products WHERE category_id='$category_id' AND id != '$product_id' AND status='active' ORDER BY id DESC LIMIT 3");

            if ($related_query) {
                while ($related = mysqli_fetch_assoc($related_query)) {
            ?>
            <div class="col-md-4">
                <div class="related-card">
                    <?php
                    $image_path = $related['main_image'];
                    if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                        $image_path = "assets/uploads/" . $image_path;
                    }
                    ?>
                    <img src="<?php echo $image_path; ?>" alt="">
                    <div class="related-body">
                        <h3 class="related-name"><?php echo $related['name']; ?></h3>
                        <p class="related-price">
                            <?php if (!empty($related['discount_price']) && $related['discount_price'] > 0) { ?>
                                ৳<?php echo $related['discount_price']; ?>
                            <?php } else { ?>
                                ৳<?php echo $related['price']; ?>
                            <?php } ?>
                        </p>
                        <a href="product-details.php?id=<?php echo $related['id']; ?>" class="related-btn">View Details</a>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>