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
		<title>History</title>
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
                margin-top: 10px;
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
                <a href='activeChats.php'><img class='user_icon' id='chat' src='https://cdn.iconscout.com/icon/premium/png-256-thumb/love-chat-24-663677.png'></a>
                <a href='notifs.php'><img class='user_icon' id='alert' src='http://icons.iconarchive.com/icons/icons8/christmas-flat-color/512/jingle-bell-icon.png'></a>
				<a href="userProfile.php"><img class="user_icon" id="profile" src="https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png"></a>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
		</div>
        <div id="main">
            <table id="ze_table">
                <tr>
                    <th>People who liked you</th>
                    <th>People who viewed your profile</th>
                    <th>People you liked</th>
                    <th>Profiles you viewed</th>
                </tr>
                
                    <?php

                        include_once "config/config.php";

                        $username = $_SESSION['login'];
                        $getLikerQ = "SELECT * FROM `user_like` WHERE `liked` = ?";
                        $getlikedQ = "SELECT * FROM `user_like` WHERE `liker` = ?";
                        $getViewerQ = "SELECT * FROM `visit_history` WHERE `visited_user` = ?";
                        $getViewedQ = "SELECT * FROM `visit_history` WHERE `username` = ?";
                        
                        $getLikerR = $conn->prepare($getLikerQ);
                        $getlikedR = $conn->prepare($getlikedQ);
                        $getViewerR = $conn->prepare($getViewerQ);
                        $getViewedR = $conn->prepare($getViewedQ);

                        $getLikerR->execute([$username]);
                        $getlikedR->execute([$username]);
                        $getViewerR->execute([$username]);
                        $getViewedR->execute([$username]);

                        $likerRow = $getLikerR->fetch();
                        $likedRow = $getlikedR->fetch();
                        $viewerRow = $getViewerR->fetch();
                        $viewedRow = $getViewedR->fetch();
                        while ($likerRow || $likedRow || $viewerRow || $viewedRow)
                        {
                            echo "<tr>";
                            
                            if ($likerRow)
                            {
                                echo "<td><a href='userprofile.php?visited=".$likerRow['liker']."'>".$likerRow['liker']."</a></td>";
                            }
                            else
                            {
                                echo "<td></td>";
                            }
                            if ($likedRow)
                            {
                                echo "<td><a href='userprofile.php?visited=".$likedRow['liked']."'>".$likedRow['liked']."</a></td>";
                            }
                            else
                            {
                                echo "<td></td>";
                            }
                            if ($viewerRow)
                            {
                                echo "<td><a href='userprofile.php?visited=".$viewedRow['username']."'>".$viewedRow['username']."</a></td>";
                            }
                            else
                            {
                                echo "<td></td>";
                            }
                            if ($viewedRow)
                            {
                                echo "<td><a href='userprofile.php?visited=".$viewedRow['visited_user']."'>".$viewedRow['visited_user']."</a></td>";
                            }
                            else
                            {
                                echo "<td></td>";
                            }
                            
                            echo "</tr>";
                            $likerRow = $getLikerR->fetch();
                            $likedRow = $getlikedR->fetch();
                            $viewerRow = $getViewerR->fetch();
                            $viewedRow = $getViewedR->fetch();
                        }
                    ?>
                
            </table>
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
                        if (result == "msg" || result == "msgnotif")
                        {
                            var chatElem = document.getElementById("chat");
                            chatElem.style.background = "orangered";
                        }
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