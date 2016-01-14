<?php
        //Connect to DB
	$servername = "localhost";
	$username = "starfish_fbapps";
	$password = "RpWBFCoJAFmNqkLk";
	$db = "starfish_fbapps_db";
        
	// Create connection
	$link = mysqli_connect($servername,$username,$password,$db) or die("Error connection".mysqli_error($link));

 ?>