<?php
    include "config/config.php";

	if ($_SESSION['login'] == "")
	{
		header('Location: login.php');
		die();
    }
    if (isset($_POST['url']) && isset($_POST['from']) && isset($_POST['post_pic']) && $_POST['url'] != "" && isset($_POST['chosen_frame']) && $_POST['chosen_frame'] != "")
    {
        if (!file_exists("images"))
        {
            mkdir("images");
        }
        if ($_POST['origin'] == "file")
        {
            $image = "images/".$_FILES['b_pic']['name'];
            $target = "images/".basename($_FILES['b_pic']['name']);
            move_uploaded_file($_FILES["b_pic"]["tmp_name"], $target);
        }
        else
        {
            $username = $SESSION['login'];
            $rawData = $_POST['url'];

            $filteredData = explode(',', $rawData);
            $unencoded = base64_decode($filteredData[1]);
            $randomName = rand(0, 99999);
            //Create the image 
            $fp = fopen("images/".$randomName.'.jpg', 'w');
            fwrite($fp, $unencoded);
            fclose($fp);
            $image = "images/".$randomName.".jpg";
        }
        $srcPath = $_POST['chosen_frame'];
        $from = $_POST['from'];
        if (substr($image, -3) == "jpg")
        {
            $dest = imagecreatefromjpeg($image);
        }
        else if (substr($image, -3) == "png")
        {
            $dest = imagecreatefrompng($image);
        }
        else if (substr($image, -3) == "gif")
        {
            $dest = imagecreatefromgif($image);
        }
        $src = imagecreatefrompng($srcPath);
        $srcXpos = 0;
        $srcYpos = 0;
        $srcXcrop = 0;
        $srcYcrop = 0;
        $username = $_SESSION['login'];
        $caption = $_POST['caption'];
        $time = time();
        if (substr($image, -3) == "gif")
        {
            $newImageName = "images/".$username."_".date("Y_m_d", $time)."_".$time.".gif";
        }
        else
        {
            $newImageName = "images/".$username."_".date("Y_m_d", $time)."_".$time.".jpg";
        }

        list($srcWidth, $srcHeight) = getimagesize($srcPath);
        imagecolortransparent($src, imagecolorat($src, 0, 0));

        imagecopymerge($dest, $src, $srcXpos, $srcYpos, $srcXcrop, $srcYcrop, $srcWidth, $srcHeight, 100);
        if (substr($image, -3) == "gif")
        {
            imagegif($dest, $newImageName, 100);
        }
        else
        {
            imagejpeg($dest, $newImageName, 100);
        }

        if (file_exists($image))
        {
            unlink($image);
        }
        imagedestroy($dest);
        imagedestroy($src);
        
        if ($from == "profile")
        {
            $postImageQuery = "UPDATE `user` SET `p_pic_path` = ? WHERE `username` = ?";
            $postImageResult = $conn->prepare($postImageQuery);
            $postImageResult->execute([$newImageName, $username]);
            header("Location: userProfile.php");
            die();
        }
        else
        {
            $getImagesCountQ = "SELECT COUNT(*) FROM `images` WHERE `username` = ?";
            $getImagesCountR = $conn->prepare($getImagesCountQ);
            $getImagesCountR->execute([$username]);
            $item = $getImagesCountR->fetch();
            $count = $item['COUNT(*)'];

            if ($count < 4)
            {
                $postImageQuery = "INSERT INTO `images`(`username`, `caption`, `image_path`) VALUES(?, ?, ?)";
                $postImageResult = $conn->prepare($postImageQuery);
                $postImageResult->execute([$username, $caption, $newImageName]);
                header("Location: userProfile.php");
                die();
            }
            else
            {
                echo "<script>alert('You have reached your limit(4), delete some pictures first');</script>";
            }
        }

    }
    else if (isset($_POST['url']) && isset($_POST['post_pic']))
    {
        echo "<script>alert('You forgot something like taking a picture or selecting a frame');</script>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        <title>Upload picture</title>
        <style>
            body
            {
                text-align: center;
            }
            #side
            {
                background-color: black;
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
			#search_icon
			{
				width: 30px;
				margin-top: 5px;
				margin-left: 5px;
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
            #screenshot
            {
                display: none;
                max-height: 100px;
            }
            #vid
            {
                width: 600px;
                display: block;
            }
            #captured_one
            {
                display: none;
                width: 600px;
            }
            #omunye
            {
                display: none;
                position: absolute;
                top: 120px;
            }
            #take_pic
            {
                position: relative;
                left: 265px;
                background-color: rgba(255,255,255,0.7);
                border-radius: 100%;
                padding: 10px;
                border: 5px solid RoyalBlue;
                height: 60px;
                width: 60px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            }
            #take_another_one
            {
                position: relative;
                top: 10px;
                left: 340px;
                background-color: rgba(255,255,255,0.7);
                border-radius: 100%;
                padding: 10px;
                border: 5px solid green;
                height: 25px;
                width: 25px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            }
            #post_pic
            {
                position: relative;
                height: 50px;
                background-color: rgba(0,0,0,0.7);
                color: white;
                padding: 10px;
                border: 3px solid white;
                border-radius: 10px;
                top: -20px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            }
            #img_caption
            {
                position: relative;
                width: 500px;
                height: 50px;
                margin-top: 10px;
				border-radius: 5px;
				border: 2px solid #1E90FF;
            }
            #main
            {
                background-color: #DCDCDC;
                padding: 10px;
                border-radius: 10px;
                top: 120px;
                left: 50px;
                text-align: left;
                display: grid;
  				grid-template-columns: auto auto;
                grid-gap: 20px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
                margin-bottom: 20px;
                min-width: 800px;
                min-height: -webkit-fill-available;
            }
            #side
            {
                top: 120px;
                right: 50px;
                border-radius: 10px;
                display: grid;
  				grid-template-columns: auto auto;
                padding: 10px;
                grid-gap: 10px;
				overflow: auto;
                border: 5px solid grey;
                min-width: 100px;
            }
            .grid_img
            {
                width: 100%;
            }
            #frames
            {
                display: grid;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                border-radius: 10px;
                height: 98%;
				overflow: auto;
  				grid-template-columns: auto auto;
                grid-gap: 5%;
                padding: 10px;
                min-width: 100px;
            }
            #lay
            {
                display: grid;
                grid-template-columns: auto auto;
                grid-gap: 10px;
                margin-top: 120px;
                min-width: 1000px;
                min-height: 1000px;
            }
            .frame
            {
                width: 100%;
                background-color: white;
                border-radius: 10px;
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
            #b_image
            {
                background-color: black;
                padding: 5px;
                color: white;
                border-radius: 3px;
            }
			#web_name
			{
				font-style: bold;
				color: white;
				font-family: monospace;
				font-size: 18px;
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
                    <a href="userProfile.php"><img class="user_icon" src="https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png"></a>
                    <div class="header_item" style="display: inline; width: 30px;">
                        <img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
                    </div>
                </div>
            </div>
        <div id="lay">
            <div id="main">
                <div id="cam">
                    <video id="vid" autoplay></video>
                    <canvas id="screenshot"></canvas>
                    <img id="captured_one" src="">
                    <img id="omunye" src="">
                    <button id="take_pic"></button>
                    <img src="https://cdn3.iconfinder.com/data/icons/faticons/32/refresh-01-512.png" id="take_another_one">
                    <form id="submit_form" method="POST" enctype="multipart/form-data">
                        <input id="b_image" type="file" value="browse" accept="image/*" name="b_pic"  onchange="loadFile(event)" style="display: none;">
                        <p><label for="b_image" style="cursor: pointer; background-color: 191970; padding: 10px; border-radius: 5px; color: white;">Click here to choose image</label></p>
                        <textarea id="img_caption" type="text" name="caption"></textarea>
                        <input id="post_pic" name="post_pic" type="submit" value="Upload">
                        <input id="url" name="url" type="text" style="display: none;">
                        <input id="chosen_frame" name="chosen_frame" type="text" style="display: none;">
                        <input id="origin" name="origin" type="text" style="display: none;">
                        <?php
                            if (isset($_GET['from']))
                            {
                                echo "<input style='display: none;' id='from' name='from' value=".$_GET['from'].">";
                            }
                        ?>
                    </form>
                </div>
                <div id="frames">
                    <div class="frame" id="none"></div>
                    <img class="frame" id="cat1" src="images/cat-png-gray-cat-png-image-347.png">
                    <img class="frame" id="dragon" src="images/dragon.png">
                    <img class="frame" id="cupid" src="images/cupid.png">
                </div>
            </div>
            <div id="side">
            <?php

                include_once "config/config.php";

                $username = $_SESSION['login'];
                $getPostQuery = "SELECT * FROM `images` WHERE `username`=? ORDER BY `id` DESC";

                $getPostResult = $conn->prepare($getPostQuery);
                $getPostResult->execute([$username]);
                if ($getPostResult->rowCount())
                {
                    while ($post = $getPostResult->fetch())
                    {
                        if (file_exists($post['image_path']))
						{
							$image_path = $post['image_path'];
						}
						else
						{
							$image_path = "images/image_not_found.jpg";
						}
                        $id = $post['id'];
                        $deleteId = "delete".$id;
                        $imageId = "image".$id;
                        if (file_exists($image_path))
                        {
                            echo    "<div style='align: center; background-color: grey; padding: 2px; border-radius: 2px;'>
                                        <img id=$imageId class='grid_img' src='".$image_path."'>
                                        <span class='delete' id='".$deleteId."' onclick=deletePost('$id') title='Delete post'>&times;</span>
                                    </div>";
                        }
                        else
                        {
                            echo    "<div style='align: center; background-color: grey; padding: 2px; border-radius: 2px;'>
                                        <img id=$imageId class='grid_img' src='images/image_not_found.jpg'>
                                        <span class='delete' id='".$deleteId."' onclick=deletePost('$id') title='Delete post'>&times</span>;
                                    </div>";
                        }
                    }
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

            function hasGetUserMedia()
            {
                return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
            }

                const constraints = {
                video: true
                };

                const video = document.getElementById('vid');
                const img = document.getElementById('captured_one');
                const canvas = document.getElementById('screenshot');
                const shoot = document.getElementById('take_pic');
                const re_shoot = document.getElementById('take_another_one');
                const post_it = document.getElementById('post_pic');
                const url = document.getElementById('url');
                const cat = document.getElementById('cat1');
                const dragon = document.getElementById('dragon');
                const cupid = document.getElementById('cupid');
                const none = document.getElementById('none');
                var context = canvas.getContext('2d');
                const frame = document.getElementById('omunye');
                const chosenFrame = document.getElementById('chosen_frame');
                const origin = document.getElementById('origin');
                const from = document.getElementById('from');

                frame.style.display = "none";
            if (hasGetUserMedia())
            {
                navigator.mediaDevices.getUserMedia(constraints).
                then((stream) => {video.srcObject = stream});

                navigator.mediaDevices.getUserMedia(constraints).
                then((stream) => {video.srcObject = stream});

                //When you take a picture
                shoot.onclick = video.onclick = function()
                {
                    if (frame.style.display != "none")
                    {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0);
                        img.src = canvas.toDataURL('image/jpeg');
                        url.value = canvas.toDataURL('image/jpeg');
                        img.style.display = "block";
                        video.style.display = "none";
                        origin.value = "cam";
                    }
                };
                re_shoot.onclick = function()
                {
                    img.src = "";
                    url.value = "";
                    img.style.display = "none";
                    video.style.display = "block";
                };
            }
            else
            {
                alert('getUserMedia() is not supported by your browser');
            }
                //frames start
                cat.addEventListener("click", function()
                {
                    if (video.style.display != "none" || origin.value == "file")
                    {
                        frame.src = cat.src;
                        chosenFrame.value = cat.src;
                        frame.style.display = "block";
                    }
                });
                dragon.addEventListener("click", function()
                {
                    if (video.style.display != "none" || origin.value == "file")
                    {
                        frame.src = dragon.src;
                        chosenFrame.value = dragon.src;
                        frame.style.display = "block";
                    }
                });
                cupid.addEventListener("click", function()
                {
                    if (video.style.display != "none" || origin.value == "file")
                    {
                        frame.src = cupid.src;
                        chosenFrame.value = cupid.src;
                        frame.style.display = "block";
                    }
                });
                none.addEventListener("click", function()
                {
                    frame.style.display = "none";
                    chosenFrame.value = "";
                });
                //frames end

                //When you select a picture
                var loadFile = function(event)
                {
                    if (event.target.files[0])
                    {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        img.src = URL.createObjectURL(event.target.files[0]);
                        canvas.innerHTML = "<img src='" + img.src + "'>";
                        //canvas.style.display = "block";
                        url.value = canvas.toDataURL('image/jpeg');
                        img.style.display = "block";
                        video.style.display = "none";
                        origin.value = "file";
                    }
                };

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
                
			function showSnackbar(message) {
				var snackbar = document.getElementById("snackbar");

				snackbar.innerHTML = message;
				snackbar.className = "show";
				setTimeout(function()
				{
					snackbar.className = "";
				}, 3000);
            }
                
			function logOut()
			{
				$.ajax({url:"logOut.php", success: function(result)
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
