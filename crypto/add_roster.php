<?php

function add_roster($top, $mid, $jungle, $adc, $support) {

	// Connecting to database
	$connection = mysqli_connect("localhost", "root", "supfoo2971", "roster");
    /*$db_name = 'roster';
    mysql_select_db($db_name, $connection);*/
    
    // insert the new form inputs into the database
    if ($_COOKIE['username'] == "bot") {
        die("Error: Access denied. Contact administrator.");
    } else if ( (empty($top)) || (empty($mid)) || (empty($jungle)) || (empty($adc)) || (empty($support)) ) {
        $insert_query = "SELECT * FROM lineup;";
    } else {
        $insert_query = "INSERT INTO lineup SET top='".$top."', mid='".$mid."', jungle='".$jungle."', adc='".$adc."', support='".$support."';";
    }
    
	if (!mysqli_query($connection, $insert_query)) {
        die("Error: " . mysqli_error());
    }

$array = array($top, $mid, $jungle, $adc, $support);
return $array;
}
?>