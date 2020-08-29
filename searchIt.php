<?php

    include_once "config/config.php";

    if (isset($_POST['username']) && isset($_POST['gap']) && isset($_POST['location']) && isset($_POST['interests']) && isset($_POST['rating']) && isset($_POST['sort']) && isset($_POST['filter']))
    {
        $user = $_SESSION['login'];
        $username = ($_POST['username'] == "")? "[a-z]|^|[A-Z]" : $_POST['username'];
        $gap = $_POST['gap'];
        $sort = $_POST['sort'];
        $filter = $_POST['filter'];
        $location = ($_POST['location'] == "" || $filter != "location")? "[a-z]|^|[A-Z]" : $_POST['location'];
        $interests = ($_POST['interests'] == "" || $filter != "interests")? "[a-z]|^|[A-Z]" : $_POST['interests'];
        $rating = ($_POST['rating'] == "" || $filter != "rating")? "0" : $_POST['rating'];
        $v = ($filter != "rating")? 1000000 : 10;

        $getDOBQ = "SELECT `dob` FROM `user` WHERE `username` = ?";

        if ($gap > 0)
        {
            $getSearchedQ = "SELECT * FROM `user` WHERE `username` REGEXP ? AND `location` REGEXP ? AND `interests` REGEXP ? AND `rating` >= ? AND `rating` <= ? AND `dob` <= DATE_ADD(?, INTERVAL ? YEAR) AND `dob` >= ? ORDER BY `$sort`";
        }
        else if ($gap < 0)
        {
            $getSearchedQ = "SELECT * FROM `user` WHERE `username` REGEXP ? AND `location` REGEXP ? AND `interests` REGEXP ? AND `rating` >= ? AND `rating` <= ? AND `dob` >= DATE_ADD(?, INTERVAL ? YEAR) AND `dob` <= ? ORDER BY `$sort`";
        }
        else
        {
            $getSearchedQ = "SELECT * FROM `user` WHERE `username` REGEXP ? AND `location` REGEXP ? AND `interests` REGEXP ? AND `rating` >= ? AND `rating` <= ? ORDER BY `$sort`";
        }
        $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";

        $getDOBR = $conn->prepare($getDOBQ);
        $getDOBR->execute([$user]);
        if ($dobItem = $getDOBR->fetch())
        {
            $dob = $dobItem['dob'];
            $getSearchedR = $conn->prepare($getSearchedQ);
            if ($gap > 0 || $gap < 0)
            {
                $getSearchedR->execute([$username, $location, $interests, $rating - $v, $rating + $v, $dob, $gap, $dob]);
            }
            else
            {
                $getSearchedR->execute([$username, $location, $interests, $rating - $v, $rating + $v]);
            }
            while($result = $getSearchedR->fetch())
            {
                $checkBlockedR = $conn->prepare($checkBlockedQ);
                $checkBlockedR->execute([$user, $result['username']]);
                if ($checkBlockedR->rowCount() == 0)
                {
                    echo "<a href='userprofile.php?visited=".$result['username']."'><p>".$result['username']."</p></a>";
                }
            }
        }
    }
    else
    {
        echo "Something went wrong";
    }
?>