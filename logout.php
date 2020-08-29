<?php
    include_once "config/config.php";
    
    $_SESSION['login'] = "";
    $_SESSION['passwd'] = "";
    ob_start();
    header("Location: login.php");
    ob_end_flush();
?>