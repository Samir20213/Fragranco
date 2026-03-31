<?php
session_start();
include("config/db.php");
include("includes/header.php");

// cart session create if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// add to cart logic
if (isset($_POST['add_to_cart']) || isset($_POST['buy_now'])) {
    $product_id = (int) $_POST['product_id'];
    $size = $_POST['size'];
    $qty = (int) $_POST['qty'];

    $found = false;

    // same product + same size already in cart?
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id && $item['size'] == $size) {
            $_SESSION['cart'][$key]['qty'] += $qty;
            $found = true;
            break;
        }
    }

    // if not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'size' => $size,
            'qty' => $qty
        ];
    }

    // if buy now clicked, later checkout এ যাবে
    if (isset($_POST['buy_now'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = "checkout.php";
            header("Location: login.php");
            exit();
        } else {
            header("Location: checkout.php");
            exit();
        }
    }
}

// remove item
if (isset($_GET['remove'])) {
    $remove_index = $_GET['remove'];
    unset($_SESSION['cart'][$remove_index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

$total = 0;
?>

<style>
.cart-title {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 30px;
}

.cart-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.cart-img {
    width: 100%;
    max-width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 12px;
}

.cart-name {
    font-size: 24px;
    font-weight: 700;
}

.cart-price {
    color: #c99700;
    font-weight: 700;
    font-size: 20px;
}

.remove-btn {
    background: crimson;
    color: white;
    padding: 8px 14px;
    border-radius: 20px;
    text-decoration: none;
}

.total-box {
    background: #111;
    color: white;
    padding: 25px;
    border-radius: 18px;
    text-align: right;
    font-size: 24px;
    font-weight: 700;
}

.checkout-btn {
    display: inline-block;
    margin-top: 15px;
    background: gold;
    color: black;
    padding: 10px 22px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
}
</style>

<div class="container py-5">
    <h1 class="cart-title">Your Cart</h1>

    <?php if (empty($_SESSION['cart'])) { ?>
        <div class="alert alert-warning">Your cart is empty.</div>
    <?php } else { ?>

        <?php foreach ($_SESSION['cart'] as $index => $item) { ?>
            <?php
            $product_id = $item['product_id'];
            $size = $item['size'];
            $qty = $item['qty'];

            $query = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");
            $product = mysqli_fetch_assoc($query);

            if (!$product) continue;

            $price = (!empty($product['discount_price']) && $product['discount_price'] > 0)
                ? $product['discount_price']
                : $product['price'];

            $subtotal = $price * $qty;
            $total += $subtotal;
            ?>

            <div class="cart-card">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <?php
                        $image_path = $product['main_image'];
                        if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
                            $image_path = "assets/uploads/" . $image_path;
                        }
                        ?>
                        <img src="<?php echo $image_path; ?>" class="cart-img" alt="">
                    </div>

                    <div class="col-md-4">
                        <div class="cart-name"><?php echo $product['name']; ?></div>
                        <div>Size: <?php echo $size; ?></div>
                        <div>Quantity: <?php echo $qty; ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="cart-price">৳<?php echo $price; ?></div>
                        <div>Subtotal: ৳<?php echo $subtotal; ?></div>
                    </div>

                    <div class="col-md-3 text-end">
                        <a href="cart.php?remove=<?php echo $index; ?>" class="remove-btn">Remove</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="total-box">
            Total: ৳<?php echo $total; ?><br>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>

    <?php } ?>
</div>

<?php include("includes/footer.php"); ?>