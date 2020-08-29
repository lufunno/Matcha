<?php

    include_once "config/config.php";

    if ($_SESSION['login'] != "" && $_SESSION['passwd'])
    {
        $username = $_SESSION['login'];

        $getLocationsQ = "SELECT `lat`, `lng`, `username` FROM `user`";
        $getLocationsR = $conn->prepare($getLocationsQ);
        $getLocationsR->execute();
        while ($loc = $getLocationsR->fetch())
        {
            echo $loc['username'].":".$loc['lat'].":".$loc['lng']."\n";
        }
    }
?>