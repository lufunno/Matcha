<?php

    include_once "config/config.php";

    if (isset($_GET['gender']) && strlen($_GET['gender']) > 0)
    {
        $username = $_SESSION['login'];
        $gender = $_GET['gender'];

        $changeGenderQ = "UPDATE `user` SET `gender` = ? WHERE `username` = ?";
        $changeGenderR = $conn->prepare($changeGenderQ);
        $changeGenderR->execute([$gender, $username]);
        $conn->exec("COMMIT");
        if ($changeGenderR->rowCount())
        {
            echo "Gender changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No gender was selected";
    }
?>