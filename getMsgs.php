<?php

                include_once "config/config.php";

                if (isset($_GET['username']))
                {
                    $user1 = $_GET['username'];
                    $user2 = $_SESSION['login'];
                    $getMsgsQ = "SELECT * FROM `chat` WHERE (`to` = ? AND `from` = ?) OR (`to` = ? AND `from` = ?)";
                    $updateViewStatusQ = "UPDATE `chat` SET `viewed` = ? WHERE `from` = ? AND `to` = ?";
                    $getUserDpQ = "SELECT `p_pic_path` FROM `user` WHERE `username` = ?";

                    $getMsgsR = $conn->prepare($getMsgsQ);
                    $getMsgsR->execute([$user1, $user2, $user2, $user1]);
                    while($msg = $getMsgsR->fetch())
                    {
                        $updateViewStatusR = $conn->prepare($updateViewStatusQ);
                        $updateViewStatusR->execute(['1', $user1, $user2]);
                        $conn->query("COMMIT");
                        if ($user2 == $msg['from'])
                        {
                            $getUserDpR = $conn->prepare($getUserDpQ);
                            $getUserDpR->execute([$user2]);
                            $dp = $getUserDpR->fetch();
                            echo    "<div class='container' style='width: 70%; float: left;'>";
                            if(file_exists($dp['p_pic_path']))
                            {
                                echo    "<img src='".$dp['p_pic_path']."' style='width:100%;'>";
                            }
                            else
                            {
                                echo    "<img src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Zoo_Wuppertal_Schwarzfusskatze.jpg/290px-Zoo_Wuppertal_Schwarzfusskatze.jpg' style='width:100%;'>";
                            }
                            echo        "<p>".$msg['msg']."</p>
                                        <span class='time-right'>".$msg['date_created']."</span>
                                    </div>";
                        }
                        else
                        {
                            $getUserDpR = $conn->prepare($getUserDpQ);
                            $getUserDpR->execute([$user1]);
                            $dp = $getUserDpR->fetch();
                            echo    "<div class='container darker' style='width: 70%; float: right;'>";
                            if(file_exists($dp['p_pic_path']))
                            {
                                echo    "<img class='right' src='".$dp['p_pic_path']."' style='width:100%;'>";
                            }
                            else
                            {
                                echo    "<img class='right' src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Zoo_Wuppertal_Schwarzfusskatze.jpg/290px-Zoo_Wuppertal_Schwarzfusskatze.jpg' style='width:100%;'>";
                            }
                            echo        "<p>".$msg['msg']."</p>
                                        <span class='time-left'>".$msg['date_created']."</span>
                                    </div>";
                        }
                    }
                }

            ?>