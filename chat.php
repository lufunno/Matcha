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
		<title>Chat</title>
		<style>
			body
			{
                width: 99%;
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
                min-height: 90%;
                margin-top: 150px;
                margin-bottom: 10px;
            }
            #header
			{
				position: fixed;
				top: 0px;
				left: 0px;
				background-image: url("https://d1l9zs272jkxri.cloudfront.net/blog/uploads/2017/01/16120008/best-time-to-buy-roses.jpg");
				width: 100%;
				padding: 10px;
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
                visibility: hidden;
				border-radius: 10px;
                margin-top: 10px;
				box-shadow: 2px 2px 10px 0px grey;
                margin-bottom: 100px;
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
            }
            .darker
            {
                border-color: #ccc;
                background-color: #ddd;
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
            .container img.right
            {
                float: right;
                margin-left: 20px;
                margin-right:0;
            }
            .time-right
            {
                float: right;
                color: #aaa;
            }
            .time-left
            {
                float: left;
                color: #999;
            }
            #snackbar
			{
				visibility: hidden;
				min-width: 250px;
				margin-left: -125px;
				background-color: #333;
				color: #fff;
				text-align: center;
				border-radius: 5px;
				padding: 16px;
				position: fixed;
				z-index: 1;
				left: 50%;
				bottom: 30px;
			}
			#snackbar.show
			{
				visibility: visible;
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
        <?php

            if (isset($_GET['username']))
            {
                echo "<h1 id='user' style='top: 55px; position: fixed; width: 100%; background-color: darkslategrey; left: 0px; box-shadow: 0px 8px 16px 0px grey; color: white; font-family: fantasy; padding-top: 10px;'>".$_GET['username']."</h1>";
            }
        ?>
        <div id="main">
        
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

        </div>

            <div id="reply_box" style="z-index: 1; position: fixed; bottom: 0px; width: 100%; background-color: dodgerblue; padding: 10px; left: 0px; box-shadow: 2px 2px 10px 0px black;">
                <textarea name="user_msg" id="user_msg" style="width: 80%; height: 60px; border-radius: 7px;" placeholder="Enter a reply..."></textarea>
                <img src="https://cdn0.iconfinder.com/data/icons/line-action-bar/48/send-512.png" id="send_msg" onclick="sendMsg()" style="width: 40px; background-color: white; border-radius: 100%; padding: 10px; cursor: pointer; border: 2px solid orangered;">
            </div>
            
        <div id="snackbar"></div>

		<script type="text/javascript">

           getMsgs();
            function getMsgs() {
                setTimeout(function()
                {
                    var user = document.getElementById("user").innerHTML;
                    var main = document.getElementById("main");
                    $.ajax({url: "getMsgs.php", data: {"username":user}, success: function(result)
                    {
                        if (result != "" && main.innerHTML != result)
                        {
                            main.innerHTML = result;
                        }
                        getMsgs();
                    }})
                }, 3000);
            }

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

            function sendMsg()
            {
                var msg = document.getElementById("user_msg").value;
                var user = document.getElementById("user").innerHTML;

                if (msg != "")
                {
                    $.ajax({url: "sendMsg.php", data: {"msg":msg, "username":user}, method: "POST", success: function(result)
                    {
                        location.reload();
                    }})
                }
                else
                {
                    showSnackbar("You didn't enter any reply🤷🏽‍♂️... i wonder🧐");
                }
            }

		    function logOut()
			{
				$.ajax({url:"logout.php", success: function(result)
				{
                    showSnackbar(result);
					location.reload();
				}})
            }

			function showSnackbar(message)
            {
				var snackbar = document.getElementById("snackbar");
				snackbar.innerHTML = message;
				snackbar.className = "show";
				setTimeout(function()
				{
					snackbar.className = "";
				}, 3000);
			}

		</script>
 
            <div id="footer">
                <p id="f_msg">This website is proundly provided to you by Nedzingahe Kondelelani</p>
                <p id="cr">knedzing©2018</p>
            </div>

	</body>
</html>
