<?php

function add_entry($coin, $amount, $btcbefore, $buyprice, $pergain, $btcafter, $sellprice, $btcgain) {
die("buy price:".$coin);
	// Connecting to database
	$connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
    
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
    
    // calculate cum gain
    $btc_query = "SELECT `cum_btc` FROM ".$username." ORDER BY entry_id DESC limit 1";

    $btc_result = mysqli_query($connection, $lp_query);
    $row_cnt = $btc_result->num_rows;
    if ($row_cnt == 0) {
        $btc_old = 0;
    } else {
        $btc_row = mysqli_fetch_assoc($lp_result);
        $btc_old = $btc_row['btc_after'];
    }

    // calculate cum btc
    $cumbtc = $btcafter - $btc_old;
    
    // insert the new form inputs into the database
    
    
    
    $insert_query = "INSERT INTO ".$username." SET coin='".$coin."', amt=$amount, sat_buy=$buyprice, sat_sel='".$sellprice."', btc_before='".$btcbefore."', btc_after='".$btcafter."', per_gain='".$pergain."', btc_gain='".$btcgain."', cum_btc='".$cumbtc."';";
    
    $result = mysqli_query($connection, $insert_query);
    if ($result == false) {
        echo "Errormessage: ".$connection->error;
        die();
    }

$array = array($coin, $amount, $btcbefore, $buyprice, $pergain, $btcafter, $sellprice, $btcgain, $cumbtc);
return $array;
}
?>
