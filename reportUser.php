<?php

    include_once "config/config.php";

    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['msg']))
    {
        $reporter = $_SESSION['login'];
        $reported = $_POST['username'];
        $email = $_POST['email'];
        $msg = $_POST['msg'];

        $addToReportedQ = "INSERT INTO `report`(`username`, `reported_user`, `msg`) VALUES(?, ?, ?)";
        $addToReportedR = $conn->prepare($addToReportedQ);
        $addToReportedR->execute([$reporter, $reported, $msg]);
        $conn->exec("COMMIT");
        if ($addToReportedR)
        {
            echo "This person is not reported, Thanx😊";
        }
    }
    else
    {
        echo "Something is missing🤯";
    }
?>