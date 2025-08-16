<?php
if (!isset($_SESSION['email'])) {
    header("Location: enter.php");
    exit();
}
?>
