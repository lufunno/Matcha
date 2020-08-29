<?php

    include_once "config/config.php";

    if (isset($_GET['sexual_pref']) && strlen($_GET['sexual_pref']) > 0)
    {
        $username = $_SESSION['login'];
        $sexual_pref = $_GET['sexual_pref'];

        $changeSexualPrefQ = "UPDATE `user` SET `sexual_pref` = ? WHERE `username` = ?";
        $changeSexualPrefR = $conn->prepare($changeSexualPrefQ);
        $changeSexualPrefR->execute([$sexual_pref, $username]);
        $conn->exec("COMMIT");
        if ($changeSexualPrefR->rowCount())
        {
            echo "Sexual prefefence changed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "No sexual preference was selected";
    }
?>