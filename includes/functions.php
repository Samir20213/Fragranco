<?php

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

function redirect($page) {
    header("Location: " . $page);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

?>