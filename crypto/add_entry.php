<?php

function add_entry($division, $lp, $champion, $position, $kda, $cs, $mistakes, $improvements) {

	// Connecting to database
	$connection = mysqli_connect("localhost", "root", "supfoo2971", "stats");
    /*$db_name = 'stats';
    mysql_select_db($db_name, $connection);*/
 	
	// INSERT INTO TABLE VALUES FROM FORM
	/*$division = $_POST['division'];
    $lp = $_POST['lp'];
    $gain = $_POST['gain'];
    $champion = $_POST['champion'];
    $position = $_POST['position'];
    $kda = $_POST['kda'];
    $cs = $_POST['cs'];
	$mistakes = $_POST['mistakes'];
	$improvements = $_POST['improvements'];*/
	
    // find the last entries LP
    $username = $_COOKIE['username'];
    $lp_query = "SELECT `lp` FROM ".$username." ORDER BY entry_id DESC limit 1";

    $lp_result = mysqli_query($connection, $lp_query);
    $row_cnt = $lp_result->num_rows;
    if ($row_cnt == 0) {
        $lp_old = 0;
    } else {
        $lp_row = mysqli_fetch_assoc($lp_result);
        $lp_old = $lp_row['lp'];
    }

    // calculate gain
    $gain = $lp - $lp_old;
    
    // insert the new form inputs into the database
    $insert_query = "INSERT INTO ".$username." SET division='".$division."', lp=$lp, gain='".$gain."', champion='".$champion."', position='".$position."', kda='".$kda."', cs=$cs, mistakes='".$mistakes."', improve_by='".$improvements."';";
    $result = mysqli_query($connection, $insert_query);
    if ($result == false) {
        echo "Errormessage: ".$connection->error;
        die();
    }

$array = array($division, $lp, $gain, $champion, $position, $kda, $cs, $mistakes, $improvements);
return $array;
}
?>
