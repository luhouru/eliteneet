<?php

function gen_hodl($type, $pagenum) {
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
$connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
$username = $_COOKIE['username'];
$query = "SELECT * FROM ".$username."_hodl;";
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
                $tbody .= "<tr>\n";
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

$result = '<div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-table fa-fw"></i> Team Comps
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 60px;">Coin</th>
                                                    <th style="width: 60px;">Holding</th>
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