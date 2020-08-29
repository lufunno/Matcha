<?php

    include_once "config/config.php";

    if (isset($_GET['username']) && isset($_GET['newPassword']))
    {
        $username = $_GET['username'];
        $newPassword = $_GET['newPassword'];

            $changePassQ = "UPDATE `user` SET `password` = ? WHERE `username` = ?";
            $changePassR = $conn->prepare($changePassQ);
            $changePassR->execute([$newPassword, $username]);
            $conn->exec("COMMIT");
            if ($changePassR->rowCount())
            {
                $_SESSION['passwd'] = $theRealPass;
                echo "Password changed...";
                echo "<a href='login.php'>Click to Go to log in page</a>";
            }
            else
            {
                echo "Something went wrong";
            }
    }
    else
    {
        echo "You forgot to enter all the values";
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>...</title>
	</head>
	<body>
		
	</body>
</html>