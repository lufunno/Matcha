<?php

    include_once "config/config.php";

    if (isset($_GET['username']) && isset($_GET['password']) && isset($_GET['email']))
    {
        $username = $_GET['username'];
        $email = $_GET['email'];
        $newPassword = $_GET['password'];


        $getPassQ = "SELECT * FROM `user` WHERE `email` = '$email' AND `username` = '$username'";
        $getPassR = $conn->prepare($getPassQ);
        $getPassR->execute([$email, $username]);
        if ($getPassR->rowCount() > 0)
        {
            $theRealPass = hash('whirlpool', $newPassword);
            $theRealOldPass = ($getPassR->fetch())['password'];
            $reqUri = $_SERVER['REQUEST_URI'];
            $url = $_SERVER['HTTP_HOST'] . substr($reqUri, 0, strrpos($reqUri, "forgotPassword.php"));
            sendEmail($email,   "<html>
                                    <a href='http://".$url."changePass.php?newPassword=".$theRealPass."&username=".$username."'>
                                        <input type='submit' value='click to change password' style='color: #FFFFFF; padding: 10px; background-color: red;'/>
                                    </a>
                                </html>", "Password reset");
            die("Check your email");
        }
        else
        {
            echo "User not found in our database";
        }
    }
    else
    {
        echo "Something went wrong. NB: your password must be atleast 6 characters";
    }

    function sendEmail($to, $msg, $sbj)
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "www.kondie@live.com";
        $header = "From:" . $from;

        mail($to, $sbj, $msg, $header);
    }
?>