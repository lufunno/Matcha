<?php

    include_once "config/config.php";

    if ($_SESSION['login'] != "" && $_SESSION['passwd'])
    {
        $username = $_SESSION['login'];

        $getLocationsQ = "SELECT `lat`, `lng`, `username` FROM `user` WHERE `username` = ?";
        $getLocationsR = $conn->prepare($getLocationsQ);
        $getLocationsR->execute([$username]);
        while ($loc = $getLocationsR->fetch())
        {
            echo $loc['username'].":".$loc['lat'].":".$loc['lng']."\n";
        }
    }
?>