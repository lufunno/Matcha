<?php

    include_once "config/config.php";

    if (isset($_GET['newEmail']) && strlen($_GET['newEmail']) > 0)
    {
        $username = $_SESSION['login'];
        $newEmail = $_GET['newEmail'];

        $changeEmailQ = "UPDATE `user` SET `email` = ? WHERE `username` = ?";
        $changeEmailR = $conn->prepare($changeEmailQ);
        $changeEmailR->execute([$newEmail, $username]);
        $conn->exec("COMMIT");
        if ($changeEmailR->rowCount())
        {
            echo "Email changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No email was entered";
    }
?>