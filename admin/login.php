<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

$message = "";

if (isset($_POST['admin_login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Username and Password are required ❌";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM admins WHERE username=? LIMIT 1");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_username'] = $row['username'];

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $message = "Incorrect password ❌";
                }
            } else {
                $message = "Admin username not found ❌";
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = "Database error ❌";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | FRAGRANCO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa;">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Admin Login</h2>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" name="admin_login" class="btn btn-dark w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>