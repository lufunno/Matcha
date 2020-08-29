<?php

                    include_once "config/config.php";

                    if (isset($_GET['sort']) && isset($_GET['filter']))
                    {
                        $username = $_SESSION['login'];
                        $sort = $_GET['sort'];
                        $filter = $_GET['filter'];
                        $getUserInfoQ = "SELECT * FROM `user` WHERE `username` = ?";
                        $getSeggestionsQ = "SELECT `username` FROM `user` WHERE `location` REGEXP ? AND `username` != ? && `interests` REGEXP ? AND `sexual_pref` REGEXP ? AND `rating` >= ? AND `rating` <= ? ORDER BY `$sort`";

                        $getUserInfoR = $conn->prepare($getUserInfoQ);
                        $getUserInfoR->execute([$username]);
                        if ($getUserInfoR->rowCount() > 0)
                        {
                            $userItem = $getUserInfoR->fetch();
                            $getSeggestionsR = $conn->prepare($getSeggestionsQ);
                            $loc = ($userItem['location'] == "" || $filter != "location")? "[a-z]|^|[A-Z]" : $userItem['location'];
                            $interests = ($userItem['interests'] == "" || $filter != "interests")? "[a-z]|^|[A-Z]" : $userItem['interests'];
                            $pref = ($userItem['sexual_pref'] == "")? "[a-z]|^|[A-Z]" : $userItem['sexual_pref'];
                            $pref = ($pref == "girls")? "guys": $pref;
                            $rating = $userItem['rating'];
                            $v = ($filter != "rating")? 1000000 : 10;
                            $getSeggestionsR->execute([$loc, $username, $interests, $pref, $rating - $v, $rating + $v]);
                            while ($sugItem = $getSeggestionsR->fetch())
                            {
                                $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
                                $checkBlockedR = $conn->prepare($checkBlockedQ);
                                $checkBlockedR->execute([$username, $sugItem['username']]);
                                if ($checkBlockedR->rowCount() == 0)
                                {
                                    echo "<a href='userprofile.php?visited=".$sugItem['username']."'><p>".$sugItem['username']."</p></a>";
                                }
                            }
                        }
                    }
?>