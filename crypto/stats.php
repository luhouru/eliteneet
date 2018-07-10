            <p style="color:black">
                    <?php
                    	$connection = mysqli_connect("localhost", "root", "supfoo2971", "stats");
                        /*$db_name = 'stats';
                        mysql_select_db($db_name, $connection);*/
                        $username = $_COOKIE['username'];
            // find the last entries LP
			$lp_query = "SELECT `lp` FROM ".$username." ORDER BY entry_id DESC limit 1";
			$lp_result = mysqli_query($connection, $lp_query);
            $row_cnt = $lp_result->num_rows;
            if ($row_cnt == 0) {
                $lp_old = 0;
            } else {
                $lp_row = mysqli_fetch_assoc($lp_result);
                $lp_old = $lp_row['lp'];
            }
            
            // fetch query results
            $div_query = "SELECT `division` FROM ".$username." ORDER BY entry_id DESC limit 1";
			// fetch division query results
			$div_result = mysqli_query($connection, $div_query);
            $row_cnt = $div_result->num_rows;
            if ($row_cnt == 0) {
                $current_div = "Unknown";
            } else {
                $div_row = mysqli_fetch_assoc($div_result);
			    $current_div = $div_row['division'];
            }
			$next_div = "";
			switch ($current_div) {
			    case "Bronze V":
			        $next_div = "Bronze IV";
			        break;
			    case "Bronze IV":
				$next_div = "Bronze III";
				break;
			    case "Bronze III":
				$next_div = "Bronze II";
				break;
			    case "Bronze II":
				$next_div = "Bronze I";
				break;
			    case "Bronze I":
				$next_div = "Silver V";
				break;
			    case "Silver V":
                                $next_div = "Silver IV";
                                break;
                            case "Silver IV":
                                $next_div = "Silver III";
                                break;
                            case "Silver III":
                                $next_div = "Silver II";
                                break;
                            case "Silver II":
                                $next_div = "Silver I";
                                break;
                            case "Silver I":
                                $next_div = "Gold V";
 			    case "Gold V":
                                $next_div = "Gold IV";
                                break;
                            case "Gold IV":
                                $next_div = "Gold III";
                                break;
                            case "Gold III":
                                $next_div = "Gold II";
                                break;
                            case "Gold II":
                                $next_div = "Gold I";
                                break;
                            case "Gold I":
                                $next_div = "Platinum V";
 			    case "Platinum V":
                                $next_div = "Platinum IV";
                                break;
                            case "Platinum IV":
                                $next_div = "Platinum III";
                                break;
                            case "Platinum III":
                                $next_div = "Platinum II";
                                break;
                            case "Platinum II":
                                $next_div = "Platinum I";
                                break;
                            case "Platinum I":
                                $next_div = "Diamond V";
	                        break;
                            case "Diamond V":
                                $next_div = "Diamond IV";
                                break;
 			                case "Diamond IV":
                                $next_div = "Diamond III";
                                break;
                            case "Diamond III":
                                $next_div = "Diamond II";
                                break;
                            case "Diamond II":
                                $next_div = "Diamond I";
                                break;
                            case "Diamond I":
                                $next_div = "Challenger";
                                break;
                            case "Challenger":
                                $next_div = "Master";
	                            break;
                            case "Master":
                                $next_div = "Unknown";
	                        break;
                            default:
                                $next_div = "Unknown";
                                break;
			}

                        if ($lp_old == 100) {
                            echo "In Series! Next Division: ".$next_div;
                        } else {
                            echo "Next Division: ".$next_div;
                        }
                    ?></p>
            <div class="progress progress-striped active">
                <div class="progress-bar"
                     <?php
                    	$connection = mysqli_connect("localhost", "root", "supfoo2971", "stats");
                        /*$db_name = 'stats';
                        mysql_select_db($db_name, $connection);*/
 	                    $username = $_COOKIE['username'];
                        // find the last entries LP
                        $lp_query = "SELECT `lp` FROM ".$username." ORDER BY entry_id DESC limit 1";
                        $lp_result = mysqli_query($connection, $lp_query);
                        $row_cnt = $lp_result->num_rows;
                        if ($row_cnt == 0) {
                            $lp_old = 0;
                        } else {
                            $lp_row = mysqli_fetch_assoc($lp_result);
                            $lp_old = $lp_row['lp'];
                        }
                        echo "style='width: ".$lp_old."%'";
                    ?>>
                </div>
            </div>    
            <div class="row">
                <div class="col-lg-12">
                    <!-- /.panel -->
                    <?php 
					   echo tablegen(0,0); 
					?>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <i class="fa fa-upload fa-fw"></i> Add Match Details
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="index.php?page=stats&action=add_entry" method="POST" role="form">
                            <div class="col-lg-4">
								<div class="form-group">
                                    <label>Division:</label>
                                    <select class="form-control" id="select" name="division">
                                        <option>Master</option>
                                        <option>Challenger</option>
                                        <option>Diamond I</option>
                                        <option>Diamond II</option>
                                        <option>Diamond III</option>
                                        <option>Diamond IV</option>
                                        <option>Diamond V</option>
                                        <option>Platinum I</option>
                                        <option>Platinum II</option>
                                        <option>Platinum III</option>
                                        <option>Platinum IV</option>
                                        <option>Platinum V</option>
                                        <option>Gold I</option>
                                        <option>Gold II</option>
                                        <option>Gold III</option>
                                        <option>Gold IV</option>
                                        <option>Gold V</option>
                                        <option>Silver I</option>
                                        <option>Silver II</option>
                                        <option>Silver III</option>
                                        <option>Silver IV</option>
                                        <option>Silver V</option>
                                        <option>Bronze I</option>
                                        <option>Bronze II</option>
                                        <option>Bronze III</option>
                                        <option>Bronze IV</option>
                                        <option>Bronze V</option>
                                    </select>
								</div>
                                <div class="form-group">
                                    <label>Current LP:</label>
                                    <input placeholder="0" type="number" min="0" max="100" class="form-control" name="lp">
								</div>
                                <div class="form-group">
                                    <label>Champion:</label>
                                    <input class="form-control" name="champion">
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Position:</label>
                                    <select class="form-control" id="select" name="position">
                                        <option>Top</option>
                                        <option>Jungle</option>
                                        <option>Mid</option>
                                        <option>Marksman</option>
                                        <option>Support</option>
                                    </select>
								</div>
                            
								<div class="form-group">
									<label>KDA:</label>
                                    <input placeholder="0/0/0" class="form-control" name="kda">
								</div>
                                <div class="form-group">
									<label>CS:</label>
                                    <input placeholder="0" type="number" min="0" max="650" class="form-control" name="cs">
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
									<label>Mistakes:</label>
                                    <input class="form-control" name="mistakes">
								</div>
								<div class="form-group">
									<label>Improve By:</label>
                                    <input class="form-control" name="improvements">
                                </div>
                            <div class="form-group">
							<button type="submit" align="center" style="margin-top:35px;" class="btn btn-success btn-lg btn-block">Add Entry</button></div>
                            </div>
							</form>

                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
            </div>
