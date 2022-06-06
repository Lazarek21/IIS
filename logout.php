<?php
    session_start();
    unset($_SESSION['login']);
    unset($_SESSION['type_id']);
    unset($_SESSION['user_id']);
    unset($_SESSION['carrier_id']);
    unset($_SESSION['links']);
    header("Location: index.php")
?>