<?php

    include_once "config/config.php";

    if (isset($_POST['username']) && isset($_POST['email']))
    {
        $reporter = $_SESSION['login'];
        $reported = $_POST['username'];
        $email = $_POST['email'];

        $addToBlockedQ = "INSERT INTO `blocked`(`username`, `blocked_user`) VALUES(?, ?)";
        $unblockUserQ = "DELETE FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
        $checkIfBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
        $insertNofitQ = "INSERT INTO `notif`(`from`, `to`, `msg`) VALUES(?, ?, ?)";

        $checkIfBlockedR = $conn->prepare($checkIfBlockedQ);
        $checkIfBlockedR->execute([$reporter, $reported]);

        $prefR = $conn->query("SELECT `notif` FROM `user` WHERE `username` = '$reported'");

        $item = $prefR->fetch();
        $pref = $item['notif'];
        if ($checkIfBlockedR->rowCount() == 0)
        {
            $addToBlockedR = $conn->prepare($addToBlockedQ);
            $addToBlockedR->execute([$reporter, $reported]);

            $insertNofitR = $conn->prepare($insertNofitQ);
            $insertNofitR->execute([$reporter, $reported, "blocked you"]);

            $conn->exec("COMMIT");
            if ($addToBlockedR)
            {
                if ($pref == "1" && $username != $visited)
                {
                    $obj->sendEmail($email, "$username blocked you", "Blocked");
                }
                echo "This person is now blocked🚫";
            }
        }
        else
        {
            $unblockUserR = $conn->prepare($unblockUserQ);
            $unblockUserR->execute([$reporter, $reported]);

            $insertNofitR = $conn->prepare($insertNofitQ);
            $insertNofitR->execute([$reporter, $reported, "unblocked you"]);

            $conn->exec("COMMIT");
            if ($unblockUserR)
            {
                if ($pref == "1" && $username != $visited)
                {
                    $obj->sendEmail($email, "$username unblocked you", "Unblocked");
                }
                echo "This person is now unblocked🤪";
            }
        }
    }
    else
    {
        echo "Something is missing🤯";
    }
?>