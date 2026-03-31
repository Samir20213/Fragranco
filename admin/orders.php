<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("auth.php");
include("../config/db.php");
include("../mail/mailer.php");

$message = "";

if (isset($_POST['update_status'])) {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['order_status'] ?? '');

    if ($order_id > 0 && !empty($new_status)) {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status=? WHERE id=?");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Order status updated successfully ✅";
            } else {
                $message = "Status update failed ❌";
            }

            mysqli_stmt_close($stmt);

            if ($new_status === "Accepted") {
                $info_stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id=? LIMIT 1");
                mysqli_stmt_bind_param($info_stmt, "i", $order_id);
                mysqli_stmt_execute($info_stmt);
                $info_result = mysqli_stmt_get_result($info_stmt);
                $order_info = mysqli_fetch_assoc($info_result);
                mysqli_stmt_close($info_stmt);

                if ($order_info) {
                    $mailResult = sendOrderAcceptedMail(
                        $order_info['email'],
                        $order_info['customer_name'],
                        $order_info['id'],
                        $new_status
                    );

                    if ($mailResult === true) {
                        $message .= " | Email sent successfully 📧";
                    } else {
                        $message .= " | Email failed ❌";
                    }
                }
            }
        } else {
            $message = "Database prepare error ❌";
        }
    }
}

$order_query = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .page-title { font-size: 34px; font-weight: 800; margin-bottom: 25px; }
        .order-box { background: #fff; border-radius: 18px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); padding: 25px; margin-bottom: 25px; }
        .section-label { font-weight: 700; color: #111; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; background: #fff3cd; color: #856404; font-size: 13px; font-weight: 700; }
        .product-item { background: #f8f9fa; border-radius: 12px; padding: 12px 15px; margin-bottom: 10px; }
        .update-btn { background: #111; color: white; border: none; border-radius: 25px; padding: 9px 18px; font-weight: 700; }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="page-title text-center">Order Management</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($order_query) > 0) { ?>
        <?php while($order = mysqli_fetch_assoc($order_query)) { ?>
            <div class="order-box">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h4 class="mb-3">Customer Info</h4>
                        <p><span class="section-label">Order ID:</span> #<?php echo (int)$order['id']; ?></p>
                        <p><span class="section-label">Name:</span> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><span class="section-label">Phone:</span> <?php echo htmlspecialchars($order['phone']); ?></p>
                        <p><span class="section-label">Email:</span> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><span class="section-label">Address:</span> <?php echo htmlspecialchars($order['address']); ?></p>
                    </div>

                    <div class="col-md-4">
                        <h4 class="mb-3">Ordered Products</h4>

                        <?php
                        $order_id = (int)$order['id'];
                        $items_stmt = mysqli_prepare($conn, "SELECT order_items.*, products.name AS product_name
                            FROM order_items
                            LEFT JOIN products ON order_items.product_id = products.id
                            WHERE order_items.order_id=?");
                        mysqli_stmt_bind_param($items_stmt, "i", $order_id);
                        mysqli_stmt_execute($items_stmt);
                        $items_query = mysqli_stmt_get_result($items_stmt);
                        ?>

                        <?php while($item = mysqli_fetch_assoc($items_query)) { ?>
                            <div class="product-item">
                                <strong><?php echo htmlspecialchars($item['product_name']); ?></strong><br>
                                Size: <?php echo htmlspecialchars($item['size']); ?><br>
                                Qty: <?php echo (int)$item['quantity']; ?><br>
                                Price: ৳<?php echo htmlspecialchars($item['price']); ?>
                            </div>
                        <?php } mysqli_stmt_close($items_stmt); ?>
                    </div>

                    <div class="col-md-4">
                        <h4 class="mb-3">Order Info</h4>
                        <p><span class="section-label">Total:</span> ৳<?php echo htmlspecialchars($order['order_total']); ?></p>
                        <p><span class="section-label">Payment:</span> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                        <p>
                            <span class="section-label">Current Status:</span>
                            <span class="status-badge"><?php echo htmlspecialchars($order['order_status']); ?></span>
                        </p>
                        <p><span class="section-label">Date:</span> <?php echo htmlspecialchars($order['created_at']); ?></p>

                        <form method="POST" class="mt-3">
                            <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">

                            <label class="form-label"><strong>Update Status</strong></label>
                            <select name="order_status" class="form-select mb-3" required>
                                <option value="Pending" <?php if($order['order_status'] == 'Pending') echo "selected"; ?>>Pending</option>
                                <option value="Accepted" <?php if($order['order_status'] == 'Accepted') echo "selected"; ?>>Accepted</option>
                                <option value="Processing" <?php if($order['order_status'] == 'Processing') echo "selected"; ?>>Processing</option>
                                <option value="Shipped" <?php if($order['order_status'] == 'Shipped') echo "selected"; ?>>Shipped</option>
                                <option value="Delivered" <?php if($order['order_status'] == 'Delivered') echo "selected"; ?>>Delivered</option>
                                <option value="Cancelled" <?php if($order['order_status'] == 'Cancelled') echo "selected"; ?>>Cancelled</option>
                            </select>

                            <button type="submit" name="update_status" class="update-btn">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No orders found.</div>
    <?php } ?>
</div>
</body>
</html>