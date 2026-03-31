<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/db.php");
include("includes/header.php");

$message = "";

if (isset($_POST['register'])) {
    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $phone            = trim($_POST['phone']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required ❌";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format ❌";
    } elseif ($password !== $confirm_password) {
        $message = "Password and Confirm Password do not match ❌";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $message = "This email is already registered ❌";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");

                if ($insert_stmt) {
                    mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $email, $phone, $hashed_password);

                    if (mysqli_stmt_execute($insert_stmt)) {
                        $message = "Registration successful ✅";
                    } else {
                        $message = "Registration failed ❌";
                    }

                    mysqli_stmt_close($insert_stmt);
                } else {
                    $message = "Database insert error ❌";
                }
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = "Database error ❌";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Create Your Account</h2>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" name="register" class="btn btn-dark w-100">Register</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>