<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config/db.php");

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Email and Password are required ❌";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format ❌";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=? LIMIT 1");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];

                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect_page = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header("Location: " . $redirect_page);
                        exit();
                    } else {
                        header("Location: user/dashboard.php");
                        exit();
                    }
                } else {
                    $message = "Incorrect password ❌";
                }
            } else {
                $message = "Email not found ❌";
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = "Database error ❌";
        }
    }
}

include("includes/header.php");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Login to Your Account</h2>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>