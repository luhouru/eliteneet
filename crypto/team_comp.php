            <div class="row">
                <!-- /.col-lg-12 -->
                <div class="col-lg-12">
                    <!-- /.panel -->
                    <?php 
					   //echo gen_team_comp(0,0);
                       //technically we could add entries...but for this page we already know which coins we want to look at from what we're invested in.
                    
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

                    foreach($coins_result as $value) {
                      // 
                    }

                    
                    
                    ?>
                </div>
            </div>