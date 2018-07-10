<?php

function tablegen($type, $pagenum) {
// Connecting to database

//styling stuff
$and = "&type=".$type;
$prev = $pagenum - 1;
if ($prev < 0) {
$prev = 0;
}
$next = $pagenum + 1;

if ($pagenum == 0) {
$backstyle = "btn-default";
} else {
$backstyle = "btn-primary";
}

$tbody = '';
$connection = mysqli_connect("localhost", "root", "supfoo2971", "stats");
/*$db_name = 'stats';
mysql_select_db($db_name, $connection);*/
$query = "";
$username = $_COOKIE['username'];
$query = "SELECT * FROM ".$username." ORDER BY entry_id desc;";
$result = mysqli_query($connection, $query);
    
$count = 0;
$startpt = $pagenum * 15;
$endpt = $startpt + 14;

if ($result === false) {
	return (false);
}

// Display table
while ($table = mysqli_fetch_row($result)) {
	mysqli_data_seek($result, 0);
	if (mysqli_num_rows($result)) {
		$inc = 0;
		mysqli_data_seek($result, 0);
		while($row = mysqli_fetch_assoc($result)) {
			//if ($inc >= $startpt && $inc <= $endpt) {
                $sign = $row['gain'];
	            if ($sign < 0) {
                    $tbody .= "<tr class='danger'>\n";
                } elseif ($sign == 0) {
                    $tbody .= "<tr class='info'>\n";
                } else {
                    $tbody .= "<tr class='success'>\n";
                }
				foreach(array_slice($row,1) as $key=>$value) {    
                    $tbody .= '<td>'.htmlentities(trim(preg_replace("/\s+/", " ", $value)))."</td>\n";
				}
            	$tbody .= "</tr>\n";
			//}
			$inc += 1;
			$count++;
		}
	}
}

$totalpages = ceil($count/15)-1;

if ($pagenum == $totalpages) {
$forstyle = "btn-default";
$next--;
} else {
$forstyle = "btn-primary";
}

$result = '<div style="padding-bottom:3px;" class="panel panel-danger">
                        <div class="panel-heading">
                            <i class="fa fa-table fa-fw"></i> Performance Chart
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table data-pagination="true" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 60px;">Division</th>
                                                    <th style="width: 25px;">LP</th>
                                                    <th style="width: 25px;">Gain</th>
                                                    <th style="width: 60px;">Champion</th>
                                                    <th style="width: 60px;">Position</th>
                                                    <th style="width: 40px;">KDA</th>
                                                    <th style="width: 20px;">CS</th>
                                                    <th style="width: 75px;">Mistakes</th>
                                                    <th style="width: 75px;">Improvements</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            '.$tbody.'
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.col-lg-12 (nested) -->
                                <div class="col-lg-12">
                                    <div id="morris-bar-chart"></div>
                                </div>
                                <!-- /.col-lg-12 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
					';

return $result;
}

?>
