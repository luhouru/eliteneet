           
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">
            <div class="well well-sm">
             <strong>DASHBOARD: </strong>Here you'll find all your important data.
            </div>
                    </div>
            <div class="col-lg-6">
            <div class="well well-sm">
             <strong>STATISTICS &rarr; PERFORMANCE CHART: </strong>Begin logging your data.
            </div>
                </div>
             </div>
        </div>
                <div class="col-lg-12">
                    <div>
                    <h3>LP:
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
                                echo $lp_old;
                            ?></h3>
                    </div>
                     <div class="progress progress-striped active">
                        <div class="progress-bar"
                             <?php echo "style='width: ".$lp_old."%'"; ?> >
                        </div>
                    </div>
                    <!-- /.panel -->
                    <?php 
					   echo mostrecent(0,0);
					?>
                    </div>
                <div class="col-lg-12">
                    <!-- /.panel -->
                     <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Announcements
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Mandate of Heaven will become inactive soon. At least we made Bronze!</p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!--<div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Your Most Played Champions
                        </div>
                        <div class="panel-body">
                            <div style="height:350px;" id="morris-file-bar">
				            </div>
                        </div>
                    </div>
                </div>-->
                <!-- /.col-lg-12 -->
                <div class="col-lg-6">
                    <!-- /.panel -->
                     <?php echo gen_roster(0,0); ?>
                </div>
                <!-- /.col-lg-6 -->
				<div class="col-lg-6">
                    <?php echo gen_team_comp(0,0); ?>

                </div>
            </div>
            <!-- /.row -->