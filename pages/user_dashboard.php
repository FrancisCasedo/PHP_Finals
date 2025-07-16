<?php
require_once "../config.php";
if ( isset( $_SESSION['student_number'] ) ) {
    header("Location: ../login_page.php");
} else {
    session_start();
}
?>

