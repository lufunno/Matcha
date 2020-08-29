<?php

    include_once "config/config.php";

    if (isset($_GET['lat']) && isset($_GET['lng']))
    {
        $username = $_SESSION['login'];
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];

        $changeLocationQ = "UPDATE `user` SET `lat` = ?, `lng` = ?, `gps` = ? WHERE `username` = ?";
        $changeLocationR = $conn->prepare($changeLocationQ);
        $changeLocationR->execute([$lat, $lng, '1', $username]);
        $conn->exec("COMMIT");
        if ($changeLocationR->rowCount())
        {
            echo "Location changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No location was entered";
    }
?>