<?php

    include_once "config/config.php";

    if ($_SESSION['login'] != "" && $_SESSION['passwd'])
    {
        $username = $_SESSION['login'];
        $updateLastSeenQ = "UPDATE `user` SET `last_seen` = CURRENT_TIMESTAMP WHERE `username` = ?";
        $getMsgViewQ = "SELECT `viewed` FROM `chat` WHERE `to` = ? AND `viewed` = ?";
        $getNotifViewQ = "SELECT `viewed` FROM `notif` WHERE `to` = ? AND `viewed` = ?";

        $updateLastSeenR = $conn->prepare($updateLastSeenQ);
        $updateLastSeenR->execute([$username]);

        $getMsgViewR = $conn->prepare($getMsgViewQ);
        $getMsgViewR->execute([$username, '0']);
        if ($getMsgViewR->rowCount() > 0)
        {
            echo "msg";
        }
        $getNotifViewR = $conn->prepare($getNotifViewQ);
        $getNotifViewR->execute([$username, '0']);
        if ($getNotifViewR->rowCount() > 0)
        {
            echo "notif";
        }
    }
?>