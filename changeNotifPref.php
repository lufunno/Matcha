<?php
    include_once "config/config.php";

    if (isset($_GET['pref']))
    {
        $username = $_SESSION['login'];
        $pref = $_GET['pref'];

        if ($pref == on)
        {
            $notif = 1;
        }
        else
        {
            $notif = 0;
        }
        $changeNotifQ = "UPDATE `user` SET `notif` = ? WHERE `username` = ?";
        $changeNotifR = $conn->prepare($changeNotifQ);
        $changeNotifR->execute([$notif, $username]);
        $conn->exec("COMMIT");

        echo "Notification preference changed";
    }
    else
    {
        echo "Couldn't change notification preference";
    }
?>