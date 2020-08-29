<?php

    include_once "config/config.php";

    if (isset($_GET['l_name']) && strlen($_GET['l_name']) > 0)
    {
        $username = $_SESSION['login'];
        $l_name = $_GET['l_name'];

        $changeLNameQ = "UPDATE `user` SET `l_name` = ? WHERE `username` = ?";
        $changeLNameR = $conn->prepare($changeLNameQ);
        $changeLNameR->execute([$l_name, $username]);
        $conn->exec("COMMIT");
        if ($changeLNameR->rowCount())
        {
            echo "Last name changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No last name was entered";
    }
?>