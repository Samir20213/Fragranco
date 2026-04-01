<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("auth.php");
include("../config/db.php");
include("../mail/mailer.php");
include("admin_header.php");

$message_status = "";

// reply logic
if (isset($_POST['send_reply'])) {
    $message_id = (int)($_POST['message_id'] ?? 0);
    $reply_message = trim($_POST['reply_message'] ?? '');

    if ($message_id <= 0 || empty($reply_message)) {
        $message_status = "Reply message cannot be empty ❌";
    } else {
        // get original message info
        $stmt = mysqli_prepare($conn, "SELECT * FROM contact_messages WHERE id=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $message_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $contact = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($contact) {
            // save reply in DB
            $stmt = mysqli_prepare($conn, "UPDATE contact_messages SET reply_message=?, replied_at=NOW() WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $reply_message, $message_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // send email
                $mailResult = sendContactReplyMail(
                    $contact['email'],
                    $contact['name'],
                    $contact['subject'],
                    $reply_message
                );

                if ($mailResult === true) {
                    $message_status = "Reply sent successfully ✅ Email delivered 📧";
                } else {
                    $message_status = "Reply saved, but email failed ❌ " . $mailResult;
                }
            } else {
                $message_status = "Reply save failed ❌";
                mysqli_stmt_close($stmt);
            }
        } else {
            $message_status = "Message not found ❌";
        }
    }
}

// fetch all messages
$query = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
?>

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
.reply-box {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.reply-history {
    background: #f8f9fa;
    border-left: 4px solid #111;
    padding: 12px;
    border-radius: 10px;
    margin-top: 12px;
}
.reply-btn {
    background: #111;
    color: #fff;
    border: none;
    border-radius: 25px;
    padding: 10px 18px;
    font-weight: 700;
}
.reply-btn:hover {
    background: gold;
    color: black;
}
</style>

<div class="container py-5">
    <h1 class="page-title text-center">Contact Messages</h1>

    <?php if (!empty($message_status)) { ?>
        <div class="alert alert-info text-center"><?php echo $message_status; ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($query) > 0) { ?>
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <div class="message-box">
                <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                <div class="meta">
                    Email: <?php echo htmlspecialchars($row['email']); ?> |
                    Date: <?php echo htmlspecialchars($row['created_at']); ?>
                </div>

                <p class="subject">Subject: <?php echo htmlspecialchars($row['subject']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                <?php if (!empty($row['reply_message'])) { ?>
                    <div class="reply-history">
                        <strong>Admin Reply:</strong><br>
                        <?php echo nl2br(htmlspecialchars($row['reply_message'])); ?><br><br>
                        <small>Replied at: <?php echo htmlspecialchars($row['replied_at']); ?></small>
                    </div>
                <?php } ?>

                <div class="reply-box">
                    <form method="POST">
                        <input type="hidden" name="message_id" value="<?php echo (int)$row['id']; ?>">

                        <label class="form-label"><strong>Write Reply</strong></label>
                        <textarea name="reply_message" class="form-control mb-3" rows="4" placeholder="Write your reply here..." required></textarea>

                        <button type="submit" name="send_reply" class="reply-btn">Send Reply</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No messages found.</div>
    <?php } ?>
</div>

<?php include("admin_footer.php"); ?>