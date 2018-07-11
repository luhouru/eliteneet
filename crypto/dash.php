           
        <!--<div class="row">
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
        </div>-->
                    <?php 
                    
                    // this function gets the price in USD given coin name
                    function calcPrice($coin) {
                        $tick = file_get_contents("https://api.coinmarketcap.com/v1/ticker/".$coin);
                        $data = json_decode($tick, TRUE);
                        $usd = $data[0]["price_usd"];
                        return $usd;
                    }
                    
                    function calcBalance($coin, $coinname) {
                            $coinPrice = calcPrice($coinname); // this works
                            // connect to db
                            $connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
                            $username = $_COOKIE['username'];
                            // grab all of the coins in this table and put it into an array
                            $myCoins = "SELECT amt FROM ".$username." WHERE coin = '".$coin."';";
                            $amount = mysqli_query($connection, $myCoins);
                            $amount_row = $amount->num_rows;
                            if ($amount_row == 0) {
                                $amount = "zero.";
                            } else {
                                $amount_result = mysqli_fetch_assoc($amount);
                                $balanceTrue = $amount_result['amt'];
                            }
                            return $balanceTrue;
                    }
                    
                    function totalBalance($coins_result) { 
                        // initiate variable balance
                            $balance = 0;
                            foreach($coins_result as $value) {
                                    switch ($value) {
                                        // in each case, calculate USD, and put it in an array
                                        case "TRX":
                                            $trx_balance = calcBalance("TRX", "tronix");
                                            $totalUSD = $trx_balance * calcPrice("tronix");
                                            $balance = $balance + $totalUSD;
                                            break;
                                        case "BTC":
                                            $btc_balance = calcBalance("BTC", "bitcoin");
                                            $totalUSD = $btc_balance * calcPrice("bitcoin");
                                            $balance = $balance + $totalUSD;
                                            break;
                                        case "ICX":
                                            $icx_balance = calcBalance("ICX", "icon");
                                            $totalUSD = $icx_balance * calcPrice("icon");
                                            $balance = $balance + $totalUSD;
                                            break;
                                        case "NEO":
                                            $neo_balance = calcBalance("NEO", "neo");
                                            $totalUSD = $neo_balance * calcPrice("neo");
                                            $balance = $balance + $totalUSD;
                                            break;
                                        default:
                                    }
                            }
                            return $balance;
                    }
                            // connect to db
                            $connection = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
                            $username = $_COOKIE['username'];
                            // grab all of the coins in this table and put it into an array
                            $myCoins = "SELECT coin FROM ".$username.";";
                            $coins = mysqli_query($connection, $myCoins);
                            $coin_row = $coins->num_rows;
                            if ($coin_row == 0) {
                                $coins = "No coins.";
                            } else {
                                $coins_result = mysqli_fetch_assoc($coins);
                            }
                    ?>
                <div style="margin-top:-30px; clear: both" class="col-lg-12">
                    <div><h3 style="color:white;line-height:60px; float: left;">BTC Value:
                        <?php
                        $balance = totalBalance($coins_result);
                        $btc_price = calcPrice("bitcoin");
                        $btc_value = $balance / $btc_price;
                        echo $btc_value." BTC"; ?></h3></div>
                    <div><h3 style="color:white;line-height:60px; float: right;">$
                        <?php
                        echo $balance." USD"; ?></h3></div>
                </div>   

                <div class="col-lg-12">
                     <div class="progress progress-striped active">
                        <div class="progress-bar"
                             <?php echo "style='width: ".$btc."%'"; ?> >
                        </div>
                    </div>
                    <!-- /.panel -->
                    <?php 
					   echo tablegen(0,0); 
					?>
                    </div>

        















                <!--<div class="col-lg-12">
                     <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Announcements
                        </div>
                        <div class="panel-body">
                            <p>Mandate of Heaven will become inactive soon. At least we made Bronze!</p>
                        </div>
                    </div>
                </div>-->
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
                <!--<div class="col-lg-6">
                     <?php //echo gen_roster(0,0); ?>
                </div>
				<div class="col-lg-6">
                    <?php //echo gen_team_comp(0,0); ?>

                </div>-->
           