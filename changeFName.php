<?php

    include_once "config/config.php";

    if (isset($_GET['f_name']) && strlen($_GET['f_name']) > 0)
    {
        $username = $_SESSION['login'];
        $f_name = $_GET['f_name'];

        $changeFNameQ = "UPDATE `user` SET `f_name` = ? WHERE `username` = ?";
        $changeFNameR = $conn->prepare($changeFNameQ);
        $changeFNameR->execute([$f_name, $username]);
        $conn->exec("COMMIT");
        if ($changeFNameR->rowCount())
        {
            echo "First name changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No first name was entered";
    }
?>