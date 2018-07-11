<?php

// THIS IS THE REGISTER SCRIPT
// REGISTERS A USER AND THEIR TABLES
// copyright ~LUHO.

function register($firstname, $username, $password, $email) {

$db = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
		
    // DB CONNECTION FAIL = ABORT
    
	if (!$db) {
		return 2;
	}
    
    // ENCRYPT PASSWORD
    
	$password = sha1($password);
    
    // CHECK IF EXISTING USER EXISTS
    
    $taken_query = mysqli_query($db, "SELECT username FROM users WHERE username='".$username."';");
    $num_rows = mysqli_num_rows($taken_query);

    if ($num_rows == 0) {
        
        // IF SAFE, CREATE USER
        
        $result = mysqli_query($db,"INSERT INTO users (username, password, email, type, firstname) VALUES ('".$username."', '".$password."', '".$email."', 'member', '".$firstname."');");
        
        if ($result == false) {
            echo "Insert failed: ".mysqli_error($db)."<br>";
            echo "Critical error occured, please e-mail baron@nashordb.net.";
            die();
        }
        
        // WE CREATE THE TRACKER TABLE
        $stats_result = mysqli_query($db, "CREATE TABLE ".$username." (entry_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, coin varchar(255), amt bigint(20), sat_buy decimal(20,8), sat_sel decimal(20,8), btc_before decimal(20,3), btc_after decimal(20,3), per_gain decimal(20,2), btc_gain decimal(20,3), cum_btc decimal(20,3));");
        
        
        if ($stats_result == false) {
            echo "Create table failed: ".mysqli_error($db)."<br>";
            echo "Error creating trades table, please e-mail contact the administrator.";
            die();
        }
        

        // WE ALSO CREATE A HOLDINGS TABLE
        $hodl_result = mysqli_query($db, "CREATE TABLE ".$username."_hodl (entry_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, coin varchar(255), amt bigint(20));");
        
        if ($hodl_result == false) {
            echo "Create table failed: ".mysqli_error($db)."<br>";
            echo "Error creating hodl table, please e-mail contact the administrator.";
            die();
        }
            
        // after we have created everything, mail email
        
        // ANOTHER WIP FOR SENDING E-MAIL
        /*$from = "baron@nashordb.net";
        $subject = "Welcome to NashorDB!";
        $message = "Welcome, fellow Summoner, \n\n
                            I Hope you find everything here easy to use and look at. If you have any questions or problems, please e-mail me back at baron@nashordb.net. Wish you the best of luck in soloQ!
                                \n\n
                                Chris Luk \n
                                    - NashorDB Admin";
        mail($email,$subject,$message,"From: $from\n");
        return true;*/
        return true;
	} else {
            // return to login with modal saying username already exists.
            return false; // there was already a username existed
	}
}


?>