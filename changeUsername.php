<?php

    include_once "config/config.php";

    if (isset($_GET['newUsername']) && strlen($_GET['newUsername']) > 0)
    {
        $username = $_SESSION['login'];
        $newUsername = $_GET['newUsername'];

        $findUserQuery = "SELECT * FROM `user` WHERE `username` = ?";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$newUsername]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else
        {
            $changeUsernameQ = "UPDATE `user` SET `username` = ? WHERE `username` = ?";
            $changePosterQ = "UPDATE `images` SET `username` = ? WHERE `username` = ?";
            $changeLikedQ = "UPDATE `user_like` SET `liked` = ? WHERE `liked` = ?";
            $changeLikerQ = "UPDATE `user_like` SET `liker` = ? WHERE `liker` = ?";
            $changeVisiterQ = "UPDATE `visit_history` SET `username` = ? WHERE `username` = ?";
            $changeVisitedQ = "UPDATE `visit_history` SET `visited_user` = ? WHERE `visited_user` = ?";
            $changeFromQ = "UPDATE `chat` SET `from` = ? WHERE `from` = ?";
            $changeToQ = "UPDATE `chat` SET `to` = ? WHERE `to` = ?";
            $changeNotifFromQ = "UPDATE `notif` SET `from` = ? WHERE `from` = ?";
            $changeNotifToQ = "UPDATE `notif` SET `to` = ? WHERE `to` = ?";
            $changeBlockerQ = "UPDATE `blocked` SET `username` = ? WHERE `username` = ?";
            $changeBlockedQ = "UPDATE `blocked` SET `blocked_user` = ? WHERE `blocked_user` = ?";
            $changeReporterQ = "UPDATE `report` SET `username` = ? WHERE `username` = ?";
            $changeReportedQ = "UPDATE `report` SET `reported_user` = ? WHERE `reported_user` = ?";

            $changeUsernameR = $conn->prepare($changeUsernameQ);
            $changeUsernameR->execute([$newUsername, $username]);
            
            $conn->exec("COMMIT");
            if ($changeUsernameR->rowCount())
            {
                $changePosterR = $conn->prepare($changePosterQ);
                $changeLikerR = $conn->prepare($changeLikerQ);
                $changeLikedR = $conn->prepare($changeLikedQ);
                $changeVisiterR = $conn->prepare($changeVisiterQ);
                $changeVisitedR = $conn->prepare($changeVisitedQ);
                $changeFromR = $conn->prepare($changeFromQ);
                $changeToR = $conn->prepare($changeToQ);
                $changeNotifFromR = $conn->prepare($changeNotifFromQ);
                $changeNotifToR = $conn->prepare($changeNotifToQ);
                $changeBlockerR = $conn->prepare($changeBlockerQ);
                $changeBlockedR = $conn->prepare($changeBlockedQ);
                $changeReporterR = $conn->prepare($changeReporterQ);
                $changeReportedR = $conn->prepare($changeReportedQ);

                $changePosterR->execute([$newUsername, $username]);
                $changeLikerR->execute([$newUsername, $username]);
                $changeLikedR->execute([$newUsername, $username]);
                $changeVisiterR->execute([$newUsername, $username]);
                $changeVisitedR->execute([$newUsername, $username]);
                $changeFromR->execute([$newUsername, $username]);
                $changeToR->execute([$newUsername, $username]);
                $changeNotifFromR->execute([$newUsername, $username]);
                $changeNotifToR->execute([$newUsername, $username]);
                $changeBlockerR->execute([$newUsername, $username]);
                $changeBlockedR->execute([$newUsername, $username]);
                $changeReporterR->execute([$newUsername, $username]);
                $changeReportedR->execute([$newUsername, $username]);
                $conn->exec("COMMIT");

                $_SESSION['login'] = $newUsername;
                echo "Username changed";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    else
    {
        echo "No username was entered";
    }
?>