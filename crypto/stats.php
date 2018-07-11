<div style="margin-top:-30px;" class="col-lg-12">
                    <div>
                    <h3 style="color:white;line-height:60px;">BTC Value:
                        <?php
                                $connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
                                /*$db_name = 'stats';
                                mysql_select_db($db_name, $connection);*/
                                $username = $_COOKIE['username'];
                                // find the last entries LP
                                $last_btc_value = "SELECT `btc_after` FROM ".$username." ORDER BY entry_id DESC limit 1";
                                $btc_result = mysqli_query($connection, $last_btc_value);
                                $row_cnt = $btc_result->num_rows;
                                if ($row_cnt == 0) {
                                    // change this placeholder hardcode
                                    $btc = 0;
                                } else {
                                    $btc_row = mysqli_fetch_assoc($btc_result);
                                    $btc = $btc_row['cum_btc'];
                                }
                                echo $btc;
                            ?></h3>
                    </div>
            <div class="progress progress-striped active">
                <div class="progress-bar"
                     <?php
                        echo "style='width: ".$btc."%'";
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
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-upload fa-fw"></i> New Trade
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="index.php?page=stats&action=add_entry" method="POST" role="form">
                            <div class="col-lg-4">
								<div class="form-group">
                                    <label>Coin:</label>
                                    <select class="form-control" name="coin" id="select" type="text">
                                        
                                        <option>BTC</option>
                                        <option>BCH</option>
                                        <option>ETH</option>
                                        <option>LTC</option>
                                        <option>TRX</option>
                                        <option>NEO</option>
                                        <option>XRP</option>
                                        <option>ICX</option>
                                        <option>IOTA</option>
                                        <option>ENG</option>
                                        <option>ETC</option>
                                        <option>VEN</option>
                                    </select>
								</div>
                                <div class="form-group">
                                    <label>Amount:</label>
                                    <input type="text" class="form-control" name="amount">
								</div>
                                <div class="form-group">
                                    <label>BTC Before:</label>
                                    <input type="text" class="form-control" name="btcbefore">
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
									<label>Buy At:</label>
                                    <input type="text" class="form-control" name="buyprice">
								</div>
								<div class="form-group">
									<label>% Gain:</label>
                                    <input type="text" class="form-control" name="pergain">
								</div>
                                <div class="form-group">
									<label>BTC After:</label>
                                    <input type="text" class="form-control" name="btcafter">
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
									<label>Sell At:</label>
                                    <input type="text" class="form-control" name="sellprice">
								</div>
								<div class="form-group">
									<label>BTC Gain:</label>
                                    <input type="text" class="form-control" name="btcgain">
                                </div>
                            <div class="form-group">
							<button type="submit" align="center" style="margin-top:35px;" class="btn btn-info btn-lg btn-block">Enter Trade</button></div>
                            </div>
							</form>

                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
            </div>
        </div>