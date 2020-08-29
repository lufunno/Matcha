<?php

    include_once "config/config.php";

    if (isset($_GET['dob']) && strlen($_GET['dob']) > 0)
    {
        $username = $_SESSION['login'];
        $dob = $_GET['dob'];

        $changeDOBQ = "UPDATE `user` SET `dob` = ? WHERE `username` = ?";
        $changeDOBR = $conn->prepare($changeDOBQ);
        $changeDOBR->execute([$dob, $username]);
        $conn->exec("COMMIT");
        if ($changeDOBR->rowCount())
        {
            echo "Last date of birth changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No date of birth was entered";
    }
?>