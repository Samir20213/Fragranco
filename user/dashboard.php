<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");

$user_id = $_SESSION['user_id'];
$message = "";

// user info
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// settings save logic
if (isset($_POST['save_settings'])) {
    $theme_color = mysqli_real_escape_string($conn, $_POST['theme_color']);
    $font_family = mysqli_real_escape_string($conn, $_POST['font_family']);
    $font_size   = mysqli_real_escape_string($conn, $_POST['font_size']);

    $check_pref = mysqli_query($conn, "SELECT * FROM user_preferences WHERE user_id='$user_id'");

    if (mysqli_num_rows($check_pref) > 0) {
        mysqli_query($conn, "UPDATE user_preferences 
            SET theme_color='$theme_color', font_family='$font_family', font_size='$font_size'
            WHERE user_id='$user_id'");
    } else {
        mysqli_query($conn, "INSERT INTO user_preferences (user_id, theme_color, font_family, font_size)
            VALUES ('$user_id', '$theme_color', '$font_family', '$font_size')");
    }

    $message = "Settings saved successfully ✅";
}

// load preferences
$pref_query = mysqli_query($conn, "SELECT * FROM user_preferences WHERE user_id='$user_id'");
if (mysqli_num_rows($pref_query) > 0) {
    $pref = mysqli_fetch_assoc($pref_query);
    $theme_color = $pref['theme_color'];
    $font_family = $pref['font_family'];
    $font_size   = $pref['font_size'];
} else {
    $theme_color = "black";
    $font_family = "Arial";
    $font_size   = "16px";
}

// theme background
$dashboard_bg = "#111";
$dashboard_text = "#fff";

if ($theme_color == "white") {
    $dashboard_bg = "#ffffff";
    $dashboard_text = "#111";
} elseif ($theme_color == "sea green") {
    $dashboard_bg = "seagreen";
    $dashboard_text = "#fff";
} elseif ($theme_color == "light ash") {
    $dashboard_bg = "#d3d3d3";
    $dashboard_text = "#111";
}

// user orders
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
?>

<style>
.dashboard-section {
    padding: 50px 0;
    font-family: <?php echo $font_family; ?>;
    font-size: <?php echo $font_size; ?>;
}

.dashboard-wrapper {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
}

.dashboard-sidebar {
    flex: 0 0 260px;
    background: <?php echo $dashboard_bg; ?>;
    color: <?php echo $dashboard_text; ?>;
    border-radius: 20px;
    padding: 25px 20px;
    min-height: 650px;
}

.dashboard-sidebar h3 {
    color: gold;
    margin-bottom: 25px;
    font-weight: 800;
}

.dashboard-sidebar a {
    display: block;
    color: <?php echo $dashboard_text; ?>;
    text-decoration: none;
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 10px;
    transition: 0.3s;
}

.dashboard-sidebar a:hover {
    background: gold;
    color: black;
}

.dashboard-content {
    flex: 1;
    min-width: 300px;
}

.dashboard-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 25px;
    margin-bottom: 25px;
}

.dashboard-card h4 {
    margin-bottom: 18px;
    font-weight: 800;
    color: #111;
}

.info-row {
    margin-bottom: 10px;
    color: #444;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    border-bottom: 1px solid #eee;
    padding: 12px 10px;
    text-align: left;
}

.order-table th {
    background: #f8f9fa;
}

.badge-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    background: #fff3cd;
    color: #856404;
    font-size: 13px;
    font-weight: 700;
}

.small-box-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}

.small-box {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}

.small-box h5 {
    font-weight: 800;
    margin-bottom: 8px;
}

.settings-form .form-control,
.settings-form .form-select {
    border-radius: 12px;
    margin-bottom: 15px;
}

.save-btn {
    background: #111;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 10px 22px;
    font-weight: 700;
}

.save-btn:hover {
    background: gold;
    color: black;
}

@media (max-width: 768px) {
    .dashboard-wrapper {
        flex-direction: column;
    }

    .dashboard-sidebar {
        min-height: auto;
    }
}
</style>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-wrapper">

            <!-- LEFT SIDEBAR -->
            <div class="dashboard-sidebar">
                <h3>User Panel</h3>

                <a href="#profile">👤 Profile</a>
                <a href="#orders">📦 Orders</a>
                <a href="#cart">🛒 Cart</a>
                <a href="#address">📍 Saved Address</a>
                <a href="#settings">⚙ Settings</a>
                <a href="../logout.php">🚪 Logout</a>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="dashboard-content">

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php } ?>

                <!-- PROFILE -->
                <div class="dashboard-card" id="profile">
                    <h4>Profile Information</h4>
                    <div class="info-row"><strong>Name:</strong> <?php echo $user['name']; ?></div>
                    <div class="info-row"><strong>Email:</strong> <?php echo $user['email']; ?></div>
                    <div class="info-row"><strong>Phone:</strong> <?php echo $user['phone']; ?></div>
                </div>

                <!-- QUICK BOXES -->
                <div class="small-box-grid">
                    <div class="small-box">
                        <h5>Orders</h5>
                        <p>
                            <?php
                            $count_orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id'");
                            $order_count_row = mysqli_fetch_assoc($count_orders);
                            echo $order_count_row['total'];
                            ?>
                        </p>
                    </div>

                    <div class="small-box">
                        <h5>Cart Items</h5>
                        <p>
                            <?php
                            if (isset($_SESSION['cart'])) {
                                echo count($_SESSION['cart']);
                            } else {
                                echo 0;
                            }
                            ?>
                        </p>
                    </div>

                    <div class="small-box">
                        <h5>Status</h5>
                        <p>Active User</p>
                    </div>
                </div>

                <!-- ORDERS -->
                <div class="dashboard-card" id="orders">
                    <h4>My Orders</h4>

                    <?php if (mysqli_num_rows($order_query) > 0) { ?>
                        <div class="table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = mysqli_fetch_assoc($order_query)) { ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td>৳<?php echo $order['order_total']; ?></td>
                                            <td><?php echo $order['payment_method']; ?></td>
                                            <td><span class="badge-status"><?php echo $order['order_status']; ?></span></td>
                                            <td><?php echo $order['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <p>No orders found.</p>
                    <?php } ?>
                </div>

                <!-- CART -->
                <div class="dashboard-card" id="cart">
                    <h4>Cart</h4>
                    <p>
                        You currently have 
                        <strong>
                            <?php
                            if (isset($_SESSION['cart'])) {
                                echo count($_SESSION['cart']);
                            } else {
                                echo 0;
                            }
                            ?>
                        </strong>
                        item(s) in your cart.
                    </p>
                    <a href="../cart.php" class="btn btn-dark">Go to Cart</a>
                </div>

                <!-- SAVED ADDRESS -->
                <div class="dashboard-card" id="address">
                    <h4>Saved Address</h4>
                    <p>Separate address system will be added later.</p>
                    <p>You can use checkout address for now.</p>
                </div>

                <!-- SETTINGS -->
                <div class="dashboard-card" id="settings">
                    <h4>Dashboard Settings</h4>

                    <form method="POST" class="settings-form">
                        <label>Theme Color</label>
                        <select name="theme_color" class="form-select" required>
                            <option value="black" <?php if($theme_color == "black") echo "selected"; ?>>Black</option>
                            <option value="white" <?php if($theme_color == "white") echo "selected"; ?>>White</option>
                            <option value="sea green" <?php if($theme_color == "sea green") echo "selected"; ?>>Sea Green</option>
                            <option value="light ash" <?php if($theme_color == "light ash") echo "selected"; ?>>Light Ash</option>
                        </select>

                        <label>Font Family</label>
                        <select name="font_family" class="form-select" required>
                            <option value="Arial" <?php if($font_family == "Arial") echo "selected"; ?>>Arial</option>
                            <option value="Verdana" <?php if($font_family == "Verdana") echo "selected"; ?>>Verdana</option>
                            <option value="Georgia" <?php if($font_family == "Georgia") echo "selected"; ?>>Georgia</option>
                            <option value="Tahoma" <?php if($font_family == "Tahoma") echo "selected"; ?>>Tahoma</option>
                        </select>

                        <label>Font Size</label>
                        <select name="font_size" class="form-select" required>
                            <option value="14px" <?php if($font_size == "14px") echo "selected"; ?>>14px</option>
                            <option value="16px" <?php if($font_size == "16px") echo "selected"; ?>>16px</option>
                            <option value="18px" <?php if($font_size == "18px") echo "selected"; ?>>18px</option>
                            <option value="20px" <?php if($font_size == "20px") echo "selected"; ?>>20px</option>
                        </select>

                        <button type="submit" name="save_settings" class="save-btn">Save Settings</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include("../includes/footer.php"); ?>