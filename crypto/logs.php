            <div class="row">
                <div class="col-lg-12">
                    <!-- /.panel -->
                     <div class="panel panel-danger">
                        <div class="panel-heading">
                            <i class="fa fa-warning fa-fw"></i> Error Logs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#error-pills" data-toggle="tab">Errors</a>
                                </li>
                                <li><a href="#data-pills" data-toggle="tab">Data</a>
                                </li>
                                <li><a href="#php-pills" data-toggle="tab">PHP</a>
                                </li>
                                <li><a href="#lost-pills" data-toggle="tab">Lost</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" style="margin-top: 15px">
                                <div class="tab-pane fade in active" id="error-pills">
                                    <?php echo recentgen("errors"); ?>
                                </div>
                                <div class="tab-pane fade" id="data-pills">
									<?php echo recentgen("data");?>
                                </div>
                                <div class="tab-pane fade" id="php-pills">
            						<?php echo recentgen("php");?>
                                </div>
                                <div class="tab-pane fade" id="lost-pills">
          							<?php echo recentgen("lost");?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->