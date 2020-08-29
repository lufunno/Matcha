<?php

    include_once "config/config.php";

    if (isset($_POST['username']) && isset($_POST['msg']))
    {
        $from = $_SESSION['login'];
        $to = $_POST['username'];
        $msg = $_POST['msg'];
        
        $getEmailQ = "SELECT `email` FROM `user` WHERE `username` = ?";
        $sendMsgQ = "INSERT INTO `chat`(`from`, `to`, `msg`) VALUES(?, ?, ?)";

        $prefR = $conn->query("SELECT `notif` FROM `user` WHERE `username` = '$to'");
        $getEmailR = $conn->prepare($getEmailQ);
        $getEmailR->execute([$to]);

        $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
        $checkBlockedR = $conn->prepare($checkBlockedQ);
        $checkBlockedR->execute([$to, $from]);

        if ($email = $getEmailR->fetch() && $checkBlockedR->rowCount() == 0)
        {
            $sendMsgR = $conn->prepare($sendMsgQ);
            $sendMsgR->execute([$from, $to, $msg]);
            $conn->exec("COMMIT");
            if ($sendMsgR)
            {
                $item = $prefR->fetch();
                $pref = $item['notif'];
                if ($pref == "1")
                {
                    $obj->sendEmail($email['email'], $from.": ".$msg, "New message");
                }
                echo "Sent🤪";
            }
            else
            {
                echo "Was unable to send😔";
            }
        }
    }
    else
    {
        echo "Something went wrong🤯";
    }
?>