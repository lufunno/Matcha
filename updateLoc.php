<?php

    include_once "config/config.php";

    if ($_SESSION['login'] != "" && $_SESSION['passwd'] && isset($_POST['lat']) && isset($_POST['lng']))
    {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $username = $_SESSION['login'];

        $updateLocQ = "UPDATE `user` SET `lat` = ?, `lng` = ? WHERE `username` = ? AND `gps` = ?";
        $updateLocR = $conn->prepare($updateLocQ);
        $updateLocR->execute([$lat, $lng, $username, '0']);
        $conn->query("COMMIT");

        echo "GPS location changed";
    }
?>