<div class="row">
                <!-- /.col-lg-12 -->
                <div class="col-lg-12">
                    <!-- /.panel -->
                    <?php 
					   echo gen_hodl(0,0);
                    ?>
                </div>
            </div>


            <!--FORM TO ADD HOLDING-->
            <div class="col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-upload fa-fw"></i> New Hold
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="index.php?page=stats&action=add_hodl" method="POST" role="form">
                            <div class="col-lg-6">
								<div class="form-group">
                                    <label>Coin:</label>
                                    <select class="form-control" id="select" name="coin">
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
                                    <input placeholder="3000" type="number" min="0" max="1000000" class="form-control" name="amount">
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