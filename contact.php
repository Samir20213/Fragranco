<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/db.php");
include("includes/header.php");

$message_status = "";

if (isset($_POST['send_message'])) {
    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $subject      = trim($_POST['subject']);
    $message_text = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message_status = "Please fill all fields ❌";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_status = "Invalid email format ❌";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message_text);

            if (mysqli_stmt_execute($stmt)) {
                $message_status = "Your message has been sent successfully ✅";
            } else {
                $message_status = "Message sending failed ❌";
            }

            mysqli_stmt_close($stmt);
        } else {
            $message_status = "Database error ❌";
        }
    }
}
?>

<style>
.contact-section {
    padding: 60px 0;
}

.contact-box {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 30px;
}

.contact-title {
    font-size: 38px;
    font-weight: 800;
    margin-bottom: 20px;
}

.contact-box label {
    font-weight: 600;
    margin-bottom: 6px;
}

.contact-box .form-control {
    border-radius: 12px;
    margin-bottom: 15px;
    padding: 12px 14px;
}

.send-btn {
    background: #111;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 12px 24px;
    font-weight: 700;
}

.send-btn:hover {
    background: gold;
    color: black;
}
</style>

<section class="contact-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="contact-box">
                    <h1 class="contact-title text-center">Contact Us</h1>

                    <?php if (!empty($message_status)) { ?>
                        <div class="alert alert-info text-center"><?php echo $message_status; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>

                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>

                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>

                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="6" required></textarea>

                        <button type="submit" name="send_message" class="send-btn mt-2">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>