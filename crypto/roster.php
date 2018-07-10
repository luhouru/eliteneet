            <div class="row">
                <div class="col-lg-6">
                    <?php echo gen_roster(0,0); ?>
                </div>
                <!-- /.col-lg-12 -->
                <div class="col-lg-6">
                    <!-- /.panel -->
                     <div class="panel panel-success">
                        <div class="panel-heading">
                            <i class="fa fa-edit fa-fw"></i> Add New Roster
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           <form action="index.php?page=roster&action=add_roster" method="POST" role="form">
								<div class="form-group">
                                    <label>Top:</label>
                                    <input class="form-control" name="top">
								</div>
                                <div class="form-group">
                                    <label>Mid:</label>
                                    <input class="form-control" name="mid">
								</div>
                                <div class="form-group">
                                    <label>Jungle:</label>
                                    <input class="form-control" name="jungle">
								</div>
                                <div class="form-group">
                                    <label>ADC:</label>
                                    <input class="form-control" name="adc">
								</div>
								<div class="form-group">
									<label>Support:</label>
                                    <input class="form-control" name="support">
								</div>
							<button type="submit" class="btn btn-success btn-lg btn-block">Add Roster</button>
							</form>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>