<?php

// this script will insert the user into the users table
// as well as create a stats table for the user

function register($firstname, $username, $password, $email) {

$db = mysqli_connect("localhost", "root", "supfoo2971", "users");
		
	if (!$db) {
		return 2;
	}
	$password = sha1($password);
    
    // check if username is already taken
    $taken_query = mysqli_query($db, "SELECT username FROM users WHERE username='".$username."';");
    $num_rows = mysqli_num_rows($taken_query);

    if ($num_rows == 0) {
        // then safe to insert, so insert into users
        $result = mysqli_query($db,"INSERT INTO users (username, password, email, type, firstname) VALUES ('".$username."', '".$password."', '".$email."', 'member', '".$firstname."');");
        
        if ($result == false) {
            echo "Insert failed: ".mysqli_error($db)."<br>";
            echo "Critical error occured, please e-mail baron@nashordb.net.";
            die();
        }
        
        // safe to insert
        // connect to stats table
        $db = mysqli_connect("localhost", "root", "supfoo2971", "stats");
        $stats_result = mysqli_query($db, "CREATE TABLE ".$username." (entry_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, division  varchar(255), lp bigint(20), gain varchar(8), champion varchar(20), position varchar(20), kda varchar(30), cs int(10), mistakes varchar(255), improve_by varchar(255));");
        if ($stats_result == false) {
            echo "Create table failed: ".mysqli_error($db)."<br>";
            echo "Error creating performance table, please e-mail baron@nashordb.net.";
            die();
        }
            
        // after we have created everything, mail email
        $from = "baron@nashordb.net";
        $subject = "Welcome to NashorDB!";
        $message = "Welcome, fellow Summoner, \n\n
                            I Hope you find everything here easy to use and look at. If you have any questions or problems, please e-mail me back at baron@nashordb.net. Wish you the best of luck in soloQ!
                                \n\n
                                Chris Luk \n
                                    - NashorDB Admin";
        mail($email,$subject,$message,"From: $from\n");
        return true;
        
	} else {
            // return to login with modal saying username already exists.
            return false; // there was already a username existed
	}
}


?>