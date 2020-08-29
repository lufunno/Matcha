<?php

    include_once "config/config.php";

    if (isset($_GET['interests']) && strlen($_GET['interests']) > 0)
    {
        $username = $_SESSION['login'];
        $interests = $_GET['interests'];

        $changeInterestsQ = "UPDATE `user` SET `interests` = ? WHERE `username` = ?";
        $changeInterestsR = $conn->prepare($changeInterestsQ);
        $changeInterestsR->execute([$interests, $username]);
        $conn->exec("COMMIT");
        if ($changeInterestsR->rowCount())
        {
            echo "Interests changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No interests was entered";
    }
?>