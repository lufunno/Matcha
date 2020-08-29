<?php
	include_once 'Install.class.php';
	include_once "database.php";

	session_start();
	$obj = new Config();
	if (!($conn = $obj->connect()))
	{
		$createDbQuery = "CREATE DATABASE IF NOT EXISTS `$dbname`";
		
		try {
			$dbh = new PDO("mysql:host=$servername", $username, $password);
			$dbh->exec($createDbQuery) or die("something went wrong");

			$conn = $obj->connect();
			$conn->exec("CREATE TABLE IF NOT EXISTS `user`(`username` varchar(50) not null,`f_name` varchar(50) not null,`l_name` varchar(50) not null, `password` varchar(255) not null, `email` varchar(50), `notif` BOOLEAN NOT NULL DEFAULT TRUE, `gender` varchar(50), `sexual_pref` VARCHAR(50) DEFAULT '', `bio` varchar(500), `intersts` VARCHAR(500) NOT NULL DEFAULT '', `p_pic_path` varchar(200), `rating` DOUBLE DEFAULT 0, `lat` DOUBLE DEFAULT 0, `lng` DOUBLE DEFAULT 0, `last_seen` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `location` VARCHAR(100) NOT NULL DEFAULT '', `dob` DATE NOT NULL DEFAULT '1994-09-05', `gps` BOOLEAN NOT NULL DEFAULT FALSE)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `images`(`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `username` varchar(50) not null, `caption` varchar(500), `image_path` varchar(500) not null, `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `user_like`(`liker` VARCHAR(50) not null, `liked` VARCHAR(50) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `visit_history`(`username` VARCHAR(50) not null, `visited_user` VARCHAR(50) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `report`(`username` VARCHAR(50) not null, `reported_user` VARCHAR(50) not null, `msg` VARCHAR(500) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `blocked`(`username` VARCHAR(50) not null, `blocked_user` VARCHAR(50) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `chat`(`from` VARCHAR(50) not null, `to` VARCHAR(50) not null, `msg` VARCHAR(50) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `viewed` BOOLEAN NOT NULL DEFAULT FALSE)");
			$conn->exec("CREATE TABLE IF NOT EXISTS `notif`(`from` VARCHAR(50) not null, `to` VARCHAR(50) not null, `msg` VARCHAR(50) not null, `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `viewed` BOOLEAN NOT NULL DEFAULT FALSE)");

			$conn->exec("ALTER DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `user` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `images` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `user_like` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `visit_history` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `report` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `blocked` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `chat` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `notif` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("COMMIT");

		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}
	
	if(!$conn)
	{
		die("something went wrong".mysqli_connect_error());
	}
?>
