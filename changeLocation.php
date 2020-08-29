<?php

    include_once "config/config.php";

    if (isset($_GET['location']) && strlen($_GET['location']) > 0)
    {
        $username = $_SESSION['login'];
        $location = $_GET['location'];

        $changeLocationQ = "UPDATE `user` SET `location` = ? WHERE `username` = ?";
        $changeLocationR = $conn->prepare($changeLocationQ);
        $changeLocationR->execute([$location, $username]);
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