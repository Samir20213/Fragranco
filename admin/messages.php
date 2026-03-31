<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .page-title {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .message-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 22px;
            margin-bottom: 20px;
        }

        .message-box h5 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .subject {
            font-weight: 700;
            color: #111;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="page-title text-center">Contact Messages</h1>

    <?php if (mysqli_num_rows($query) > 0) { ?>
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <div class="message-box">
                <h5><?php echo $row['name']; ?></h5>
                <div class="meta">
                    Email: <?php echo $row['email']; ?> |
                    Date: <?php echo $row['created_at']; ?>
                </div>
                <p class="subject">Subject: <?php echo $row['subject']; ?></p>
                <p><?php echo nl2br($row['message']); ?></p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No messages found.</div>
    <?php } ?>
</div>

</body>
</html>