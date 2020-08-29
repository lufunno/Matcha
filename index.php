<?php
	include_once "config/config.php";

	if (isset($_GET['login']) && isset($_GET['passwd']) && isset($_GET['location']) && isset($_GET['dob']) && isset($_GET['email']) && isset($_GET['f_name']) && isset($_GET['l_name']))
    {
        $login = $_GET['login'];
        $passwd = $_GET['passwd'];
        $email = $_GET['email'];
        $l_name = $_GET['l_name'];
        $f_name = $_GET['f_name'];
        $location = $_GET['location'];
        $dob = $_GET['dob'];
        $findUserQuery = "SELECT * FROM `user` WHERE `username` = ?";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$login]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else
        {
        	$addUserQuery = "INSERT INTO `user`(`username`, `password`, `email`, `l_name`, `f_name`, `location`, `dob`) VALUES(?, ?, ?, ?, ?, ?, ?)";
            $addUserResult = $conn->prepare($addUserQuery);
            $addUserResult->execute([$login, $passwd, $email, $l_name, $f_name, $location, $dob]);
            $conn->query("COMMIT");
            $_SESSION['login'] = $login;
            $_SESSION['passwd'] = $passwd;
        }
    }
    else if ($_GET['sp'] == "Sign up")
    {
        ob_start();
        header("Location: signUp.php");
        ob_end_flush();
        die();
    }
    
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
		<title>Matcha</title>
		<style>
			body
			{
				background-image: linear-gradient(red, orange);
				text-align: center;
				margin: 0;
			}
			#header
			{
				position: relative;
				top: 0px;
				left: 0px;
				background-image: url("https://d1l9zs272jkxri.cloudfront.net/blog/uploads/2017/01/16120008/best-time-to-buy-roses.jpg");
				width: 100%;
				height: 45%;
				padding: 10px;
				text-align: center;
				z-index: 1;
			}
			.web_icon
			{
				height: 70%;
			}
			.user_icon
			{
				width: 50px;
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
			#main
			{
				position: relative;
				text-align: center;
				margin-top: 50px;
				width: 100%;
				height: -webkit-fill-available;
			}
			.post_container
			{
				background-color: white;
				border-radius: 5px;
				border: 0.5px solid gray;
				padding: 2px;
				width: 600px;
				text-align: center;
				margin-top: 10px;
				display: inline-block;
				box-shadow: 0px 8px 16px 0px grey;
			}
			.post_header
			{
				background-color: #DCDCDC;
				margin: 5px;
				border-radius: 5px;
				display: grid;
  				grid-template-columns: 0px auto 25px;
			}
			.poster_dp
			{
				width: 30px;
				margin: 10px;
				display: inline;
			}
			.poster_name
			{
				display: inline;
				font-style: bold;
			}
			.image_container
			{
				background-color: #DCDCDC;
				border-radius: 5px;
				text-align: center;
				display: inline-block;
				padding: 0px;
				width: 100%;
			}
			.post_image
			{
				width: 100%;
			}
			.comment_box
			{
				width: 400px;
				height: 50px;
				border-radius: 5px;
				border: 2px solid #1E90FF;
				color: black;
			}
			.like_post
			{
				width: 50px;
			}
			.like_post:hover
			{
				cursor: pointer;
			}
			.comment_container
			{
				display: inline-grid;
				text-align: top;
  				grid-template-columns: auto auto auto;
			}
			.post_comment
			{
				width: 50px;
				display: inline;
				border-radius: 100%;
				border: 1px solid #00FA9A;
				margin-left: 10px;
			}
			.post_comment:hover
			{
				cursor: pointer;
			}
			#main_dropdown
			{
				position: fixed;
				bottom: 85px;
				right: 25px;
			}
			#post_pic
			{
				width: 40px;
				border-radius: 100%;
				background-color: white;
				padding: 10px;
				border: 2px solid #4169E1;
			}
			#main_dropdown:hover #main_dropdown_items
			{
				display: block;
			}
			#main_dropdown_items
			{
				display: none;
				position: absolute;
				background-color: #f9f9f9;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				padding: 12px 16px;
				z-index: 1;
				bottom: 40px;
				right: 40px;
				min-width: 160px;
			}
			#main_dropdown_items p
			{
				background-color: #00008B;
				border-radius: 5px;
				color: white;
				padding: 15px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			}
			#likes_dropdown
			{
				display: none;
				position: fixed;
				z-index: 1;
				padding-top: 100px;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				overflow: auto;
				background-color: rgb(0,0,0);
				background-color: rgba(0,0,0,0.4);
			}
			#likes_dropdown_items
			{
				background-color: #fefefe;
				margin: auto;
				padding: 20px;
				border: 1px solid #888;
				width: 80%;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			}
			#likes_dropdown_items p
			{
				margin: 5px;
			}
			#close
			{
				color: #aaaaaa;
				float: right;
				font-size: 28px;
				margin-right: 9.5%;
				font-weight: bold;
			}
			.delete
			{
				color: #DD0000;
				font-size: 28px;
				font-weight: bold;
			}
			.delete:hover,
			.delete:focus {
				color: red;
				text-decoration: none;
				cursor: pointer;
			}
			#close:hover,
			#close:focus {
				color: #000;
				text-decoration: none;
				cursor: pointer;
			}
			.likes
			{
				cursor: pointer;
				display: inline;
				margin-right: 10px;
				color: grey;
			}
			.comments
			{
				cursor: pointer;
				display: inline;
				color: grey;
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
			#pages
			{
				display: inline-block;
				margin-top: 10px;
			}
			#pages a
			{
				color: black;
				float: left;
				padding: 8px 16px;
				text-decoration: none;
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
				box-shadow: 2px 2px 10px 0px red;
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
			#about
			{
				color: white;
				font-family: monospace;
				text-align: center;
				font-size: 15px;
				margin-right: 5%;
				margin-left: 5%; 
			}
			.button_style
			{
				padding: 10px;
				border: 2px solid green;
				border-radius: 5px;
				font-style: bold;
				background-color: black;
				color: white;
				box-shadow: 0px 2px 10px 0px red;
				cursor: pointer;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<div class="header_item">
                <a href='activeChats.php'><img class='user_icon' id='chat' src='https://cdn.iconscout.com/icon/premium/png-256-thumb/love-chat-24-663677.png'></a>
                <a href='notifs.php'><img class='user_icon' id='alert' src='http://icons.iconarchive.com/icons/icons8/christmas-flat-color/512/jingle-bell-icon.png'></a>
				<a href="userProfile.php"><img class="user_icon" src="https://images.vexels.com/media/users/3/147102/isolated/preview/082213cb0f9eabb7e6715f59ef7d322a-instagram-profile-icon-by-vexels.png"></a>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
			<img class="web_icon" src="https://www.pngitem.com/pimgs/m/518-5183771_thumb-image-naruto-y-hinata-png-transparent-png.png">
			<div class="header_item">
                <?php
                    include_once "config/config.php";

                    $username = $_SESSION['login'];
                    if ($username != "")
                    {
                        echo "<p id='web_name'>"."Welcome ".$username."</p>";
                    }
                    else
                    {
                        echo "<p id='web_name'>Matcha</p>";
                    }
                ?>
			</div>
		</div>
		<div id="main">

			<p id="about">Welcome to to Matcha where love is in the air🤯, sooooooooo don't hold your breath😱 because without love in your life you might as well drop dead🤪
			 right now. Joining a datin website doesn't mean you are desperate, it just mean you want something real for a change and the ain't no othe site more realer🤙🏾 that
			  this one, and if you're just looking for something one the side💃🏽, we do have alot of things for you🤷🏽‍♂️. Get ready to find yourself your future something
			  (wife, f* buddy)</p>
		
			  <div id="sign_">
				<a href="login.php"><input class="button_style" id="login_b" value="Login" type="button"/></a>
				<a href="signup.php"><input class="button_style" id="signup_b" value="Sign up" type="button"/></a>
			</div>
		</div>

		<div id="snackbar"></div>

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
