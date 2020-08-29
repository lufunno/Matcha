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
		<title>Suggestions</title>
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
			<a href="index.php"><img class="web_icon" src="https://cdn.icon-icons.com/icons2/1472/PNG/512/3011610-arms-couple-hold-in-lesbian-love_101380.png"></a>
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
            <label>Sort by:<label>
            <select id="sort_by" onchange="sortIt()">
                <option id="sort_age">age</option>
                <option id="sort_localization">localization</option>
                <option id="sort_fame_rating">fame rating</option>
                <option id="sort_common_tags">common tags</option>
            </select>
            <label>Filter by:<label>
            <select id="filter_by" onchange="filterIt()">
                <option id="filter_age">age</option>
                <option id="filter_localization">localization</option>
                <option id="filter_fame_rating">fame rating</option>
                <option id="filter_common_tags">common tags</option>
            </select>
            <div id="ze_list">

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

            </div>
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

            function switchIt(value)
            {
                switch(value)
                {
                    case "age":
                        return ("dob");
                    case "localization":
                        return ("location");
                    case "fame rating":
                        return ("rating");
                    case "common tags":
                        return ("interests");
                }
                return ("dob");
            }

            function sortIt()
            {
                var sortElem = document.getElementById('sort_by');
                var filterElem = document.getElementById('filter_by');
                var list = document.getElementById('ze_list');
                var sort = sortElem.options[sortElem.selectedIndex].value;
                var filter = filterElem.options[filterElem.selectedIndex].value;
                sort = switchIt(sort);
                filter = switchIt(filter);

                $.ajax({url:"getSuggested.php", data: {"sort":sort, "filter":filter}, success: function(result)
				{
					list.innerHTML = result;
				}})
            }

            function filterIt()
            {
                var sortElem = document.getElementById('sort_by');
                var filterElem = document.getElementById('filter_by');
                var list = document.getElementById('ze_list');
                var sort = sortElem.options[sortElem.selectedIndex].value;
                var filter = filterElem.options[filterElem.selectedIndex].value;
                sort = switchIt(sort);
                filter = switchIt(filter);

                $.ajax({url:"getSuggested.php", data: {"sort":sort, "filter":filter}, success: function(result)
				{
					list.innerHTML = result;
				}})
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
