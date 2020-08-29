<?php

    include_once "config/config.php";

    if (isset($_POST['username']) && isset($_POST['email']))
    {
        $liked = $_POST['username'];
        $liker = $_SESSION['login'];
        $email = $_POST['email'];
        $checkLikesQ = "SELECT `liker` FROM `user_like` WHERE `liker` = ? AND `liked` = ?";
        $likeUserQ = "INSERT INTO `user_like`(`liker`, `liked`) VALUES(?, ?)";
        $checkImagesQ = "SELECT * FROM `images` WHERE `username` = ?";
        $chackPPicQ = "SELECT `p_pic_path` FROM `user` WHERE `username` = ?";
        $changeRatingQ = "UPDATE `user` SET `rating` = `rating` + ? WHERE `username` = ?";
        $insertNofitQ = "INSERT INTO `notif`(`from`, `to`, `msg`) VALUES(?, ?, ?)";
        $prefR = $conn->query("SELECT `notif` FROM `user` WHERE `username` = '$liked'");

        $checkImagesR = $conn->prepare($checkImagesQ);
        $checkImagesR->execute([$liked]);
        
        $chackPPicR = $conn->prepare($chackPPicQ);
        $chackPPicR->execute([$liked]);
        if ($checkImagesR->rowCount() > 0 || ($chackPPicR->fetch())['p_pic_path'] != "")
        {
            $checkLikesR = $conn->prepare($checkLikesQ);
            $checkLikesR->execute([$liker, $liked]);

            $item = $prefR->fetch();
            $pref = $item['notif'];

            $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
            $checkBlockedR = $conn->prepare($checkBlockedQ);
            $checkBlockedR->execute([$liked, $liker]);
            
            if ($checkLikesR->rowCount() == 0 && $checkBlockedR->rowCount() == 0)
            {
                $likeUserR = $conn->prepare($likeUserQ);
                $likeUserR->execute([$liker, $liked]);
                $changeRatingR = $conn->prepare($changeRatingQ);
                $changeRatingR->execute(['1', $liked]);

                $insertNofitR = $conn->prepare($insertNofitQ);
                $insertNofitR->execute([$liker, $liked, "likes you"]);

                if ($pref == '1')
                {
                    $obj->sendEmail($email, "$liker likes you", "like");
                }
                echo "You liked😍 $liked";
            }
            else if ($checkBlockedR->rowCount() == 0)
            {
                $deleteLikeQ = "DELETE FROM `user_like` WHERE `liker` = ? AND `liked` = ?";
                $deleteLikeR = $conn->prepare($deleteLikeQ);
                $deleteLikeR->execute([$liker, $liked]);
                $changeRatingR = $conn->prepare($changeRatingQ);
                $changeRatingR->execute(['-1', $liked]);
                if($deleteLikeR)
                {
                    $insertNofitR = $conn->prepare($insertNofitQ);
                    $insertNofitR->execute([$liker, $liked, "unliked you"]);

                    if ($pref == '1')
                    {
                        $obj->sendEmail($email, "$liker unliked you", "unlike");
                    }
                    echo "You unliked😔 $liked";
                }
                else
                {
                    echo "Something went wrong";
                }
            }
            else
            {
                echo "Action couldn't be compeleted🤕";
            }
            $conn->query("COMMIT");
        }
        else
        {
            echo "User can not be liked";
        }
    }
    else
    {
        echo "Username wasn't provided";
    }

?>