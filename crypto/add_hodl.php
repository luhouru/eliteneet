<?php

function add_teamcomp($coin, $amt) {

	// Connecting to database
	$connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
    
    // insert the new form inputs into the database
    if ($_COOKIE['username'] == "bot") {
        die("Error: Access denied. Contact administrator.");
    } else if ( (empty($coin)) || (empty($amt)) ) {
        $insert_query = "SELECT * FROM ".$username."_hodl;";
    } else {
        $insert_query = "INSERT INTO ".$username."_hodl SET coin='".$coin."', amt=$amt;";
    }
	if (!mysqli_query($connection, $insert_query)) {
        die("Error: " . mysqli_error());
    }

$array = array($coin, $amt);
return $array;
}
?>