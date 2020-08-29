<?php
    include_once "config/config.php";

    if (isset($_GET['id']) && isset($_GET['path']))
    {
        $id = $_GET['id'];
        $path = $_GET['path'];

        $deletePostQ = "DELETE from `images` WHERE `id` = ?";
        $deletePostR = $conn->prepare($deletePostQ);
        $deletePostR->execute([$id]);
        if ($deletePostR && substr($path, strrpos($path, "images/")) != "images/image_not_found.jpg" && file_exists("images/image_not_found.jpg"))
        {
            unlink(substr($path, strrpos($path, "images/")));
            echo "Deleted";
        }
    }
    else
    {
        echo "No value provided";
    }
?>