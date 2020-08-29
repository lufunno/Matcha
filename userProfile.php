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
        <title>User profile</title>
        <style>
            body
            {
                text-align: center;
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
            #main
            {
                position: relative;
                width: 100%;
                display: grid;
                grid-template-columns: auto auto;
				min-height: -webkit-fill-available;
                grid-column-gap: 50px; 
            }
            #user_details
            {
                display: inline;
            }
            #user_pics
            {
                width: 100%;
                display: inline-grid;
  				grid-template-columns: auto auto;
                padding: 10px;
                grid-gap: 10px;
				overflow: auto;
                margin-top: 10px;
            }
            .grid_img
            {
                width: 100%;
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
  				grid-template-columns: auto auto auto auto;
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
            #edit_profile
            {
                margin-top: 120px;
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
            #user_ppic
            {
                width: 300px;
                margin: 10px;
                border-radius: 50px;
                border: 4px solid orangered;
                box-shadow: 2px 2px 15px 0px grey;
            }
            #change_ppic
            {
                width: 40px;
                box-shadow: 2px 2px 15px 0px grey;
                margin-bottom: 10px;
                border-radius: 40px;
            }
            #upload_pics
            {
                width: 100px;
                box-shadow: 2px 2px 15px 0px grey;
                margin-bottom: 10px;
                border-radius: 50px;
            }
            #user_ppic:hover
            {
                
            }
            .detail_item
            {
                padding: 10px;
                margin: 5px;
                border: 2px solid royalblue;
                border-radius: 5px;
            }
            .detail_item_v
            {
            }
            .detail_item_change
            {
            }
			.delete
			{
				color: #DD0000;
				font-size: 28px;
				font-weight: bold;
			}
			.delete:hover,
			.delete:focus
            {
				color: red;
				text-decoration: none;
				cursor: pointer;
			}
            #like_button
            {
                width: 100px;
                cursor: pointer;
                box-shadow: 2px 2px 15px 0px grey;
                margin-bottom: 10px;
                border-radius: 100px;
            }
            #view_history
            {
                width: 100px;
                cursor: pointer;
                box-shadow: 2px 2px 15px 0px grey;
                margin-bottom: 10px;
                border-radius: 100px;
            }
            #block_user
            {
                width: 100px;
                cursor: pointer;
                box-shadow: 2px 2px 15px 0px grey;
                margin-bottom: 10px;
                border-radius: 100px;
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
            <div class="header_item" style="width: 100%; margin-top: 10px;">
				<img class="user_icon" onclick="search()" style="display: inline; width: 30px; float: right; border-radius: 4px; margin-left: 10px; cursor: pointer;" src="https://3vs.co/wp-content/uploads/2017/01/search-icon-png-31.png">
				<input type="text" id="search_box" style="width: 250px; height: 30px; border-radius: 5px; border: 1px solid white; float: right;" placeholder="Search...">
			</div>
			<div class="header_item">
                <a href='activeChats.php'><img class='user_icon' id='chat' src='https://cdn.iconscout.com/icon/premium/png-256-thumb/love-chat-24-663677.png'></a>
                <a href='notifs.php'><img class='user_icon' id='alert' src='http://icons.iconarchive.com/icons/icons8/christmas-flat-color/512/jingle-bell-icon.png'></a>
                <?php
                    $username = $_SESSION['login'];
                    if ($_GET['visited'])
                    {
                        $visited = $_GET['visited'];
                    }
                    else
                    {
                        $visited = $username;
                    }
                    if ($visited == $username)
                    {
                        echo "<a href='userProfile.php'><img class='user_icon' id='profile' style='visibility: hidden;' src='https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png'></a>";
                    }
                    else
                    {
                        echo "<a href='userProfile.php'><img class='user_icon' id='profile' src='https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png'></a>";
                    }
                ?>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
		</div>

        <div id="edit_profile">
            
            <?php
                include_once "config/config.php";

                $username = $_SESSION['login'];
                if (isset($_GET['visited']))
                {
                    $visited = $_GET['visited'];
                }
                else
                {
                    $visited = $username;
                }

                $prefR = $conn->query("SELECT `notif`, `p_pic_path` FROM `user` WHERE `username` = '$visited'");

                $getEmailQ = "SELECT `email` FROM `user` WHERE `username` = ?";
                $getEmailR = $conn->prepare($getEmailQ);
                $getEmailR->execute([$visited]);
                $email = ($getEmailR->fetch())['email'];

                $sendVisitQ = "INSERT INTO `visit_history`(`username`, `visited_user`) VALUES(?, ?)";
                $insertNofitQ = "INSERT INTO `notif`(`from`, `to`, `msg`) VALUES(?, ?, ?)";

                if ($username != $visited)
                {
                    $checkBlockedQ = "SELECT * FROM `blocked` WHERE `username` = ? AND `blocked_user` = ?";
                    $checkBlockedR = $conn->prepare($checkBlockedQ);
                    $checkBlockedR->execute([$visited, $username]);
                    if ($checkBlockedR->rowCount() == 0)
                    {
                        $sendVisitR = $conn->prepare($sendVisitQ);
                        $sendVisitR->execute([$username, $visited]);

                        $insertNofitR = $conn->prepare($insertNofitQ);
                        $insertNofitR->execute([$username, $visited, "visited your profile"]);
                    }
                }

                $item = $prefR->fetch();
                $pref = $item['notif'];
                if ($visited == $username)
                {
                    if ($pref == '1')
                    {
                        echo "<label for='notif' style='margin-left: 5px;'>Notification</label><input id='notif' name='notif' type='checkbox' checked/>";
                    }
                    else
                    {
                        echo "<label for='notif' style='margin-left: 5px;'>Notification</label><input id='notif' name='notif' type='checkbox'/>";
                    }
                    echo "<br><a href='suggestedPeople.php?sort=dob&filter=dob'><input id='open_suggestions' type='button' value='Suggested people' style='cursor: pointer'></a><br>";
                    echo "<br><a href='Location.php'><input id='open_map' type='button' value='Open map' style='cursor: pointer'></a><br>";
                }
                else
                {
                    if ($pref == '1')
                    {
                        echo "<label for='notif' style='margin-left: 5px; display: none;'>Notification</label><input id='notif' name='notif' type='checkbox' style='display: none;' checked/>";
                        if ($username != $visited)
                        {
                            $obj->sendEmail($email, "$username visited your profile", "Knock knock, you have a visitor");
                        }
                    }
                    else
                    {
                        echo "<label for='notif' style='margin-left: 5px; display: none;'>Notification</label><input id='notif' name='notif' style='display: none;' type='checkbox'/>";
                    }
                    echo "<input id='report_user' type='button' value='Report as fake user' style='cursor: pointer' onclick=reportUser('$visited')><br>";

                    $getLastSeenQ = "SELECT `last_seen` FROM `user` WHERE `username` = ?";
                    $getLastSeenR = $conn->prepare($getLastSeenQ);
                    $getLastSeenR->execute([$visited]);
                    $lastSeen = ($getLastSeenR->fetch())['last_seen'];
                    $diffR = ($conn->query("SELECT TIMESTAMPDIFF(SECOND, '$lastSeen', TIMESTAMPADD(SECOND, 10, CURRENT_TIMESTAMP))"))->fetch();
                    $diff = $diffR["TIMESTAMPDIFF(SECOND, '$lastSeen', TIMESTAMPADD(SECOND, 10, CURRENT_TIMESTAMP))"];
                    if ($diff < 20)
                    {
                        echo "<p style='color: dodgerblue; font-family: fantasy;'>Online</p>";
                    }
                    else
                    {
                        echo "<p>Last seen: $lastSeen</p>";
                    }
                    $getMoreInfoQ = "SELECT * FROM `user_like` WHERE (`liker` = ? AND `liked` = ?) OR (`liker` = ? AND `liked` = ?)";
                    $getMoreInfoR = $conn->prepare($getMoreInfoQ);
                    $getMoreInfoR->execute([$visited, $username, $username, $visited]);
                    if ($getMoreInfoR->rowCount() > 0)
                    {
                        $status = "";
                        while ($info = $getMoreInfoR->fetch())
                        {
                            if ($info['liker'] == $username)
                            {
                                $status = ($status == "")? "liked" : "connected";
                            }
                            else if ($info['liker'] == $visited)
                            {
                                $status = ($status == "")? "like" : "connected";
                            }
                        }
                        if ($status == "connected")
                        {
                            echo "<p style='font-family: cursive; color: darkblue;'>Connected</p>";
                        }
                        else if ($status == "liked")
                        {
                            echo "<p style='font-family: cursive; color: darkblue;'>Liked</p>";
                        }
                        else if ($status == "like")
                        {
                            echo "<p style='font-family: cursive; color: darkblue;'>Likes you</p>";
                        }
                    }
                    else
                    {
                        echo "<p style='font-family: cursive; color: darkblue;'>No activity</p>";
                    }
                }
                
                echo "<p id='email' style='display: none'>$email<p></div>";
                if ($visited == $username)
                {
                    echo "<a href='history.php'><img id='view_history' title='Click to view your history' src='https://image.flaticon.com/icons/png/512/504/504084.png'></a>";
                }
                else
                {
                    echo "<img title='Click to like this person' id='like_button' onclick=likeUser('$visited') src='https://images.vexels.com/media/users/3/147090/isolated/preview/697b9004f271ad47335687eaa6af934c-bot-o-cora--o-instagram-by-vexels.png'>";
                }
                $path = $item['p_pic_path'];
                if (file_exists($path))
                {
                    echo    "<img id='user_ppic' src='$path'/>";
                }
                else
                {
                    echo    "<img id='user_ppic' src='http://www.best4geeks.com/wp-content/uploads/2018/05/face-hiding-profile-pic-for-girl.jpg'/>";
                }
                if ($visited == $username)
                {
                    echo "<a href='upload_pic.php?from=profile'><img id='change_ppic' src='https://lh3.googleusercontent.com/proxy/AsN7D2WHiUy_yV8HrKiVXCvUDWlTNf0fCSLagORKixjWTLpmrSm5E7EGJirpl4C1P9MQkx_o_bh5Ffrg_x9guK7BypwOOWzNuPYX1JuK8T-YI_Lu2xETGLL8mh-s7Jg0omqvfWtumsYNt0FpFvk_mmytrmCLlv6ldf9GGYL-BYUel6FPBjiIEb7X9JgFpiYm22gpPx54_auLqBOCMC18W6uUw_36-VKiiy-8awq9j64'/></a>";
                }
                else
                {
                    echo "<img id='block_user' onclick=blockUser('$visited') src='https://cdn3.iconfinder.com/data/icons/musthave/256/Cancel.png'/>";
                }
            ?>
        
        <div id="main">
            <div id="user_pics">

                <?php

                    include_once "config/config.php";

                    $username = $_SESSION['login'];
                    if (isset($_GET['visited']))
                    {
                        $visited = $_GET['visited'];
                    }
                    else
                    {
                        $visited = $username;
                    }
                    $getPostQuery = "SELECT * FROM `images` WHERE `username`=? ORDER BY `id` DESC";

                    $getPostResult = $conn->prepare($getPostQuery);
                    $getPostResult->execute([$visited]);
                    if ($getPostResult->rowCount())
                    {
                        while ($post = $getPostResult->fetch())
                        {
                            $id = $post['id'];
                            $deleteId = "delete".$id;
                            $imageId = "image".$id;
                            echo    "<div style='background-color: white; padding: 5px; color: black; border-radius: 5px; box-shadow: 2px 2px 7px 0px grey'>
                                        <img id=$imageId class='grid_img' src='".$post['image_path']."'>
                                        <p>".$post['caption']."</p>";
                            if ($visited == $username)
                            {
                                echo    "<span class='delete' id='".$deleteId."' onclick=deletePost('$id') title='Delete post'>&times;</span>";
                            }
                            echo    "</div>";
                        }
                    }
                    if ($visited == $username)
                    {
                        echo "<a href='upload_pic.php?from=".'upload'."'><img id='upload_pics' src='https://lh3.googleusercontent.com/proxy/AsN7D2WHiUy_yV8HrKiVXCvUDWlTNf0fCSLagORKixjWTLpmrSm5E7EGJirpl4C1P9MQkx_o_bh5Ffrg_x9guK7BypwOOWzNuPYX1JuK8T-YI_Lu2xETGLL8mh-s7Jg0omqvfWtumsYNt0FpFvk_mmytrmCLlv6ldf9GGYL-BYUel6FPBjiIEb7X9JgFpiYm22gpPx54_auLqBOCMC18W6uUw_36-VKiiy-8awq9j64'/></a>";
                    }

                ?>

            </div>
            <div id="user_details">
                <h1 style="font-family: cursive;">User details</h1>

                <?php

                    include_once "config/config.php";

                    if (isset($_GET['visited']))
                    {
                        $user = $_GET['visited'];
                    }
                    else
                    {
                        $user = $_SESSION['login'];
                    }
                    $getDetailsQ = "SELECT * FROM `user` WHERE `username` = ?";
                    $getDetailsR = $conn->prepare($getDetailsQ);
                    $getDetailsR->execute([$user]);
                    
                    if ($getDetailsR->rowCount() > 0)
                    {
                        $details = $getDetailsR->fetch();
                        echo    "<h3 style='color: cornflowerblue; font-family: fantasy;'>Rating: ".$details['rating']."</h3>";
                        if ($visited == $username)
                        {
                            echo "<input type='button' name='email' value='Change email' onclick='changeEmail()'/>
                                  <input type='button' name='password' value='Change password' onclick='changePassword()'/><br/>";
                        }
                        echo    "<div class='detail_item'><label class='detail_item_v'>Username: $user</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='login' value='Change username' onclick='changeUsername()'/>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>First name: ".$details['f_name']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='login' value='Change first name' onclick='changeFName()'/>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Location: ".$details['location']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='location' value='Change location' onclick='changeLocation()'/>
                                    <div id='map' style='width: 100%; height: 300px;'></div>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Date of birth: ".$details['dob']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='date' id='dob' name='dob' value='".$details['dob']."' onchange='changeDOB()'/>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Last name: ".$details['l_name']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='login' value='Change last name' onclick='changeLName()'/>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Gender: ".$details['gender']."</label>";
                        if ($visited == $username)
                        {
                            echo "<select class='detail_item_change' name='login' id='gender' onchange='changeGender()'>
                                    <option value='male'>Male</option>
                                    <option value='female'>Female</option>
                                </select>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Sexual preference: ".$details['sexual_pref']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<select class='detail_item_change' name='login' id='sexual_pref' onchange='changeSexualPref()'>
                                        <option value='both'>Both</option>
                                        <option value='girls'>Girls</option>
                                        <option value='guys'>Guys</option>
                                    </select>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Interests: ".$details['intersts']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='login' value='Edit interests' onclick='changeInterests()'/>";
                        }
                        echo    "</div><br/>
                                <div class='detail_item'><label class='detail_item_v'>Bio: ".$details['bio']."</label>";
                        if ($visited == $username)
                        {
                            echo    "<input class='detail_item_change' type='button' name='login' value='Edit Bio' onclick='changeBio()'/>";
                        }
                        echo    "</div><br/>";
                    }
                ?>

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
            function updateInfo()
            {
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

            function reportUser(username)
            {
                var msg = prompt("What made you think he/she is a fake user?", "");
                var email = document.getElementById('email').innerHTML;
                $.ajax({url: "reportUser.php", data: {"username":username, "email":email, "msg":msg}, method: "POST", success: function(result)
                {
                    showSnackbar(result);
                }})
            }

            function blockUser(username)
            {
                var email = document.getElementById('email').innerHTML;
                $.ajax({url: "blockUser.php", data: {"username":username, "email":email}, method: "POST", success: function(result)
                {
                    showSnackbar(result);
                }})
            }

            function search()
            {
                searchTxt = document.getElementById("search_box").value;

                if (searchTxt != "")
                {
                    $(location).attr('href', 'search.php?txt=' + searchTxt);
                }
                else
                {
                    showSnackbar("No text Entered");
                }
            }

            function likeUser(username)
            {
                var email = document.getElementById('email').innerHTML;
                $.ajax({url: "likeUser.php", data: {"username":username, "email":email}, method: "POST", success: function(result)
                {
                    showSnackbar(result);
                }})
            }

            function changeLocation()
            {
                var location = prompt("Please enter your new Location", "");
                if (location != "" && location != null)
                {
                    $.ajax({url:"changeLocation.php", data: {"location":location}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No location entered");
                }
            }

            function changeDOB()
            {
                var dob = document.getElementById('dob').value;
                if (dob != "" && dob != null)
                {
                    $.ajax({url:"changeDOB.php", data: {"dob":dob}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No date of birth entered");
                }
            }

            function changeUsername()
            {
                var newUsername = prompt("Please enter your new Username", "kondie_");
                if (newUsername != "" && newUsername != null)
                {
                    $.ajax({url:"changeUsername.php", data: {"newUsername":newUsername}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No username entered");
                }
            }

            function changeEmail()
            {
                var newEmail = prompt("Please enter your new Email", "knedzing@student.wethinkcode.co.za");
                if (newEmail != "" && newEmail != null)
                {
                    $.ajax({url:"changeEmail.php", data: {"newEmail":newEmail}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No email entered");
                }
            }

            function changeFName()
            {
                var f_name = prompt("Please enter your new first name", "");
                if (f_name != "" && f_name != null)
                {
                    $.ajax({url:"changeFName.php", data: {"f_name":f_name}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No first name entered");
                }
            }

            function changeLName()
            {
                var l_name = prompt("Please enter your new last name", "");
                if (l_name != "" && l_name != null)
                {
                    $.ajax({url:"changeLName.php", data: {"l_name":l_name}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No last name entered");
                }
            }

            function changeGender()
            {
                var genderElem = document.getElementById('gender');
                var gender = genderElem.options[genderElem.selectedIndex].value;
                if (gender != "" && gender != null)
                {
                    $.ajax({url:"changeGender.php", data: {"gender":gender}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No gender entered");
                }
            }

            function changeSexualPref()
            {
                var sexualElem = document.getElementById('sexual_pref');
                var sexualPref = sexualElem.options[sexualElem.selectedIndex].value;
                if (sexualPref != "" && sexualPref != null)
                {
                    $.ajax({url:"changeSexualPref.php", data: {"sexual_pref":sexualPref}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No sexual preference entered");
                }
            }

            function changeInterests()
            {
                var interests = prompt("Please enter your interests", "");
                if (interests != "" && interests != null)
                {
                    $.ajax({url:"changeInterests.php", data: {"interests":interests}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No interests entered");
                }
            }

            function changeBio()
            {
                var bio = prompt("Please enter your Bio", "");
                if (bio != "" && bio != null)
                {
                    $.ajax({url:"changeBio.php", data: {"bio":bio}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No bio entered");
                }
            }

            function changePassword()
            {
                var oldPassword = prompt("Please enter your OLD PASSWORD", "");
                var newPassword = prompt("Please enter your NEW PASSWORD", "");
                var confPassword = prompt("Please re-enter your NEW PASSWORD", "");
                if (oldPassword != "" && newPassword != "" && confPassword != "")
                {
                    $.ajax({url:"changePassword.php", data: {"oldPassword":oldPassword, "newPassword":newPassword, "confPassword":confPassword}, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("Enter all the values");
                }
            }

            (document.getElementById('notif')).onclick = function()
            {
                var pref = $('#notif:checked').val();

                $.ajax({url:"changeNotifPref.php?pref="+pref, success: function(result)
				{
					showSnackbar(result);
				}})
            }

			function deletePost(id)
			{
				var srcId = "image"+id;
				var path = document.getElementById(srcId).src;
				$.ajax({url: "deletePost.php?id=" + id + "&path=" + path, success: function(result)
				{
					if (result == "Deleted")
					{
						location.reload();
                        showSnackbar("Post deleted");
					}
					showSnackbar(result);
				}})
            }
                
			function logOut()
			{
				$.ajax({url:"logout.php", success: function(result)
				{
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

            function changePos(lat, lng)
            {
                $.ajax({url:"changePos.php", data: {"lat":lat, "lng": lng}, success: function(result)
                {
                    showSnackbar(result);
                }})
            }
            
            var map, infoWindow, marker = false;
            function initMap()
            {
                map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 6
                });
                infoWindow = new google.maps.InfoWindow;

                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                    };

                    infoWindow.setPosition(pos);
                    infoWindow.setContent('You are here');
                    infoWindow.open(map);
                    map.setCenter(pos);
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });

                $.ajax({url: "getMyLoc.php", success: function(result)
                {
                    var values = result.split(':');
                    var myLatLng = {lat: parseFloat(values[1]), lng: parseFloat(values[2])};
                    marker = new google.maps.Marker({position: myLatLng, map: map, draggable: true, title: values['0']});
                    google.maps.event.addListener(marker, 'dragend', function(event)
                    {
                        changePos(event.latLng.lat(), event.latLng.lng());
                    });
                }})

                google.maps.event.addListener(map, 'click', function(event)
                {
                    var clickedLoc = event.latLng;

                    if(marker === false)
                    {
                        marker = new google.maps.Marker({position: clickedLoc, map: map, draggable: true});
                        google.maps.event.addListener(marker, 'dragend', function(event)
                        {
                            changePos(event.latLng.lat(), event.latLng.lng());
                        });
                    }
                    else
                    {
                        marker.setPosition(clickedLoc);
                    }
                    changePos(clickedLoc.lat(), clickedLoc.lng());
                });
                }
                else
                {
                handleLocationError(false, infoWindow, map.getCenter());
                }
                
            }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlD9l56Dkx3J8KjDFjPfqpboXIoFfwsHY&callback=initMap">
        </script>
        <div id="footer">
            <p id="f_msg">This website is proundly provided to you by Nedzingahe Kondelelani</p>
            <p id="cr">knedzing©2018</p>
        </div>

    </body>
</html>
