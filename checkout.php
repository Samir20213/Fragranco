<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("includes/auth.php");
include("config/db.php");
include("includes/header.php");

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='container py-5'><div class='alert alert-warning'>Your cart is empty.</div></div>";
    include("includes/footer.php");
    exit();
}

$message = "";
$total = 0;

// total হিসাব
foreach ($_SESSION['cart'] as $item) {
    $product_id = (int)$item['product_id'];
    $qty = (int)$item['qty'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$product) {
        continue;
    }

    $price = (!empty($product['discount_price']) && $product['discount_price'] > 0)
        ? $product['discount_price']
        : $product['price'];

    $subtotal = $price * $qty;
    $total += $subtotal;
}

// order place logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name  = trim($_POST['customer_name']);
    $address        = trim($_POST['address']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $payment_method = trim($_POST['payment_method']);
    $user_id        = (int)$_SESSION['user_id'];

    if (empty($customer_name) || empty($address) || empty($phone) || empty($email) || empty($payment_method)) {
        $message = "Please fill all checkout fields ❌";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format ❌";
    } else {
        $order_status = "Pending";

        $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, customer_name, address, phone, email, payment_method, order_total, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isssssds", $user_id, $customer_name, $address, $phone, $email, $payment_method, $total, $order_status);

            if (mysqli_stmt_execute($stmt)) {
                $order_id = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);

                foreach ($_SESSION['cart'] as $item) {
                    $product_id = (int)$item['product_id'];
                    $size = $item['size'];
                    $qty = (int)$item['qty'];

                    $product_stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
                    mysqli_stmt_bind_param($product_stmt, "i", $product_id);
                    mysqli_stmt_execute($product_stmt);
                    $product_result = mysqli_stmt_get_result($product_stmt);
                    $product = mysqli_fetch_assoc($product_result);
                    mysqli_stmt_close($product_stmt);

                    if (!$product) {
                        continue;
                    }

                    $price = (!empty($product['discount_price']) && $product['discount_price'] > 0)
                        ? $product['discount_price']
                        : $product['price'];

                    $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");

                    if ($item_stmt) {
                        mysqli_stmt_bind_param($item_stmt, "iisid", $order_id, $product_id, $size, $qty, $price);
                        mysqli_stmt_execute($item_stmt);
                        mysqli_stmt_close($item_stmt);
                    }
                }

                unset($_SESSION['cart']);
                $message = "Order placed successfully ✅";
            } else {
                $message = "Order failed ❌";
                mysqli_stmt_close($stmt);
            }
        } else {
            $message = "Database error ❌";
        }
    }
}
?>

<style>
.checkout-title {
    font-size: 38px;
    font-weight: 800;
    margin-bottom: 30px;
}

.checkout-box,
.summary-box {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 25px;
}

.checkout-box label {
    font-weight: 600;
    margin-bottom: 6px;
}

.checkout-box .form-control,
.checkout-box .form-select {
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 15px;
}

.summary-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-name {
    font-size: 18px;
    font-weight: 700;
    color: #111;
}

.summary-meta {
    color: #666;
    font-size: 14px;
}

.summary-price {
    font-weight: 700;
    color: #c99700;
}

.total-box {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #ddd;
    font-size: 24px;
    font-weight: 800;
    color: #111;
}

.place-btn {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(135deg, #ffd700, #ffb300);
    color: #111;
    font-weight: 800;
    font-size: 16px;
    transition: 0.3s;
    margin-top: 20px;
}

.place-btn:hover {
    background: #111;
    color: #ffd700;
}
</style>

<div class="container py-5">
    <h1 class="checkout-title text-center">Checkout</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php } ?>

    <?php if (!empty($_SESSION['cart'])) { ?>
    <form method="POST" action="">
        <div class="row g-4">

            <div class="col-md-7">
                <div class="checkout-box">
                    <h3 class="mb-4">Billing Details</h3>

                    <label>Full Name</label>
                    <input type="text" name="customer_name" class="form-control" required>

                    <label>Shipping Address</label>
                    <textarea name="address" class="form-control" rows="4" required></textarea>

                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>

                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>

                    <label>Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">Select Payment Method</option>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="Manual Payment">Manual Payment</option>
                        <option value="Future Online Payment">Future Online Payment</option>
                    </select>

                    <button type="submit" class="place-btn">Place Order</button>
                </div>
            </div>

            <div class="col-md-5">
                <div class="summary-box">
                    <h3 class="mb-4">Order Summary</h3>

                    <?php foreach ($_SESSION['cart'] as $item) { ?>
                        <?php
                        $product_id = (int)$item['product_id'];
                        $size = $item['size'];
                        $qty = (int)$item['qty'];

                        $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
                        mysqli_stmt_bind_param($stmt, "i", $product_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $product = mysqli_fetch_assoc($result);
                        mysqli_stmt_close($stmt);

                        if (!$product) continue;

                        $price = (!empty($product['discount_price']) && $product['discount_price'] > 0)
                            ? $product['discount_price']
                            : $product['price'];

                        $subtotal = $price * $qty;
                        ?>

                        <div class="summary-item">
                            <div class="summary-name"><?php echo $product['name']; ?></div>
                            <div class="summary-meta">
                                Size: <?php echo $size; ?> <br>
                                Quantity: <?php echo $qty; ?>
                            </div>
                            <div class="summary-price">
                                ৳<?php echo $subtotal; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="total-box">
                        Total: ৳<?php echo $total; ?>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <?php } ?>
</div>

<?php include("includes/footer.php"); ?>