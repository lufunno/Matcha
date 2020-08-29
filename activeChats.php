<?php
	include_once "config/config.php";

    if ($_SESSION['login'] == "")
	{
		header('Location: login.php');
		die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
		<title>Active chats</title>
		<style>
			body
			{
                width: 95%;
				text-align: center;
			}
            #ze_table
            {
                width: 70%;
                text-align: center;
            }
            #main
            {
                text-align: center;
                min-height: -webkit-fill-available;
                margin-top: 130px;
            }
            #header
			{
				position: fixed;
				top: 0px;
				left: 0px;
				background-image: url("https://d1l9zs272jkxri.cloudfront.net/blog/uploads/2017/01/16120008/best-time-to-buy-roses.jpg");
				width: 100%;
				padding: 10px;
				box-shadow: 0px 8px 16px 0px grey;
				display: inline-grid;
  				grid-template-columns: auto auto auto;
				text-align: center;
				z-index: 1;
			}
			.web_icon
			{
				width: 50px;
				display: inline;
			}
			.user_icon
			{
				width: 50px;
				display: inline;
			}
            #profile
            {
            }
			.header_item
			{
				text-align: center;
			}
			#web_name
			{
				font-style: bold;
				color: white;
				font-family: monospace;
				font-size: 18px;
			}
            #footer
            {
				position: relative;
				background-color: grey;
				display: inline-block;
				bottom: 0px;
				width: 100%;
				color: white;
				border-radius: 10px;
                margin-top: 1000px;
				box-shadow: 2px 2px 10px 0px grey;
            }
            #cr
            {
                display: inline;
                float: right;
                margin-right: 10px;
            }
            #f_msg
            {
                display: inline;
                float: left;
                margin-left: 10px;
            }
            .container
            {
                border: 2px solid #dedede;
                background-color: #f1f1f1;
                border-radius: 5px;
                padding: 10px;
                margin: 10px 0;
                box-shadow: 2px 2px 10px 0px grey;
            }
            .container::after
            {
                content: "";
                clear: both;
                display: table;
            }
            .container img
            {
                float: left;
                max-width: 60px;
                width: 100%;
                margin-right: 20px;
                border-radius: 10px;
            }
            .time-right
            {
                float: right;
                color: #aaa;
            }
		</style>
	</head>
	<body>
        <div id="header">
			<a href="index.php"><img class="web_icon" src="https://i.pinimg.com/originals/94/e4/9a/94e49a31c3f1f0295dccee9298f0e5f8.png"></a>
			<div class="header_item">
                <?php
                    include_once "config.php";

                    $username = $_SESSION['login'];
                    if ($username != "")
                    {
                        echo "<p id='web_name'>"."Welcome ".$username."</p>";
                    }
                    else
                    {
                        echo "<p id='web_name'>Camagru</p>";
                    }
                ?>
			</div>
			<div class="header_item">
                <a href='notifs.php'><img class='user_icon' id='alert' src='http://icons.iconarchive.com/icons/icons8/christmas-flat-color/512/jingle-bell-icon.png'></a>
				<a href="userProfile.php"><img class="user_icon" id="profile" src="https://images.vexels.com/media/users/3/147102/isolated/preview/082213cb0f9eabb7e6715f59ef7d322a-instagram-profile-icon-by-vexels.png"></a>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
		</div>
        <div id="main">

            <?php
                
                include_once "config/config.php";

                $username = $_SESSION['login'];

                $getLikedQ = "SELECT * FROM `user_like` WHERE `liker` = ?";
                $getMatchQ = "SELECT * FROM `user_like` WHERE `liker` = ? AND `liked` = ?";
                $getUserQ = "SELECT * FROM `user` WHERE `username` = ?";

                $getLikedR = $conn->prepare($getLikedQ);
                $getLikedR->execute([$username]);
                while($liked = $getLikedR->fetch())
                {
                    $getMatchR = $conn->prepare($getMatchQ);
                    $getMatchR->execute([$liked['liked'], $username]);
                    while ($match = $getMatchR->fetch())
                    {
                        $getUserR = $conn->prepare($getUserQ);
                        $getUserR->execute([$match['liker']]);

                        $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
                        $checkBlockedR = $conn->prepare($checkBlockedQ);

                        if ($user = $getUserR->fetch())
                        {
                            $checkBlockedR->execute([$username, $user['username']]);
                            if ($checkBlockedR->rowCount() == 0)
                            {
                                echo    "<a href='chat.php?username=".$user['username']."'><div class='container'>";
                                if (file_exists($user['p_pic_path']))
                                {
                                    echo    "<img src='".$user['p_pic_path']."' style='width:100%;'>";
                                }
                                else
                                {
                                    echo    "<img src='images/image_not_found.jpg' style='width:100%;'>";
                                }
                                echo        "<p>".$user['username']."</p>
                                            <span class='time-right'>".$match['date_created']."</span>
                                        </div></a>";
                            }
                        }
                    }
                }

            ?>
            
        </div>

		<script type="text/javascript">
           
           function updateLoc()
            {
                if (navigator.geolocation)
                {
                    navigator.geolocation.getCurrentPosition(function(position)
                    {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        $.ajax({url: "updateLoc.php", method: "POST", data: {"lat":lat, "lng":lng}, success: function(result)
                        {
                            //showSnackbar(result);
                        }})
                    });
                }
            }

           updateInfo();
            function updateInfo() {
                setTimeout(function()
                {
                    updateLoc();
                    $.ajax({url: "updateInfo.php", success: function(result)
                    {
                        if (result == "notif" || result == "msgnotif")
                        {
                            var notifElem = document.getElementById("alert");
                            notifElem.style.background = "green";
                        }
                        updateInfo();
                    }})
                }, 3000);
            }

			function logOut()
			{
				$.ajax({url:"logout.php", success: function(result)
				{
					location.reload();
				}})
            }

		</script>
    
            <div id="footer">
                <p id="f_msg">This website is proundly provided to you by Nedzingahe Kondelelani</p>
                <p id="cr">knedzing©2018</p>
            </div>

	</body>
</html>
