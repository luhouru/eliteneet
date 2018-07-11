<?php



$warningtext = "";

if (isset($_GET['warning'])) {
	switch($_GET['warning']) {
		case "badlogin":
		$warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-danger\">Invalid credentials. Please try again!</div>";
		break;
        case "goodlogin":
		$warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-success\">You have successfully logged in.</div>";
		break;
		case "unknown":
		$warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-danger\">An error has occurred. You have been logged out.</div>";
		break;
		case "loggedout":
		$warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-info\">You are logged out, thanks!</div>";
		break;
        case "successcreate":
        $warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-success\">You have successfully created your account. Please login with your credentials.</div>";
        break;
        case "failcreate":
        $warningtext = "<div id=\"fade\" style=\"position:absolute;vertical-align: middle;text-align:center;margin-left:auto;margin-right:auto;width:500px;\" class=\"alert alert-danger\">The username you provided already exists!</div>";
        break;
		default:
		$warningtext = "";
		break;
	}
}


//require supporting functions
require_once("tablegen.php");
require_once("gen_roster.php");
require_once("checklogin.php");
require_once("add_entry.php");
require_once("add_roster.php");
require_once("add_teamcomp.php");
require_once("genchecklist.php");
require_once("gen_team_comp.php");
require_once("genlatestmsg.php");

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CryptoDB - Database Tracker for Personal Crypto Trades</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/bootswatch.min.css">
      <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
      <link rel="icon" 
      type="image/png" 
      href="/img/favicon.png">
    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
  
  </head>
    
    <body style="zoom: 90%;background-size:100%;background-position:absolute;background-attachment:fixed;" background="img/nashor_bg.png">
        
        
 <div class="container">
    <div class="outer">
        <div class="inner">       
        
    <div class="navbar navbar-default nav-fixed-top" style="margin-top:-30px;background-color: transparent">
      <div class="container">
        <div class="navbar-header" style="background:transparent; background-color:transparent;">
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li>
              <button style="background-color: #FFFFFF;border: 1px solid #bebebe;" class="btn btn-default" data-toggle="modal" href="#about-modal">About CryptoDB</button>
              <!-- Modal -->
            </li>
            <li class="dropdown">
              <button style="margin-left:25px;background-color: #FFFFFF;border: 1px solid #bebebe;" class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" id="download">Links <span class="caret"></span></button>
              <ul class="dropdown-menu" aria-labelledby="download">
                <li><a href="http://www.binance.com">Binance</a></li>
                <li><a href="http://www.coinbase.com">Coinbase</a></li>
                <li class="divider"></li>
                <li><a href="http://www.reddit.com/r/cryptocurrency">Reddit</a></li>
                <li><a href="http://www.tradingview.com/">TradingView</a></li>
              </ul>
            </li>
          </ul>
        <div align="right">
              <button class="btn btn-md btn-primary" style="width:100px;color: #FFFFFF; margin-right:5px;" data-toggle="modal" href="#login-modal">Login</button>
                <button class="btn btn-md btn-danger" style="width:100px;color: #FFFFFF" data-toggle="modal" href="#register-modal">Register</button>
                
        </div>

        </div>
      </div>
    </div>
        
    <?php echo $warningtext; ?>
    
      <!--<div style="margin-top:300px;margin-left:820px;position:absolute;overflow: hidden;" align="middle" class="col-lg-4">
            <div class="bs-component">
              <h2 style="color:white;">Post-Game Logging System</h2><br>
              <p style="margin-left:3em;color:white;" align="left">&#8594; Record your match details by filling out forms</p>
              <p style="margin-left:3em;color:white;" align="left">&#8594; Actively view your progress on a user-friendly table</p>
              <p style="margin-left:3em;color:white;" align="left">&#8594; List mistakes or things you could have done differently</p>
            </div>
        </div>  
        -->
        
        
    <div style="margin-top:85px;margin-left:auto;margin-right:auto;" class="modal fade" id="about-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button style="color:black;" type="button" class="close" data-dismiss="modal">×</button>
                      <h4 class="modal-title">About CryptoDB</h4>
                    </div>
                    <div class="modal-body">
                      CryptoDB is a database management tool that helps to aid those in hopes of trading like a pro. Instead of manually logging your data in a Google Docs spreadsheet, CryptoDB offers a user-friendly interface to record your trades, track your % gains, and add any additional comments that would help remind you to focus what's important, and make less mistakes as you make money. I hope you can find this useful, happy trading!<br>
                      
                    </div>
                    <div class="modal-footer">
                      <p align="left">&copy; LUHO &nbsp;&nbsp;</p>
                    </div>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->
        
        
        <!--START OF REGISTER MODAL-->
        <div style="margin-top:85px;" class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button style="color:black;" type="button" class="close" data-dismiss="modal">×</button>
                      <h4 class="modal-title">Register Form (beta)</h4>
                    </div>
                    <form class="form-horizontal" action="index.php?action=register" method="POST" role="form">
                    <div class="modal-body" style="transform:scale(0.9);">
                            <fieldset>
                        <div class="form-group">
                            <label for="inputName" class="col-lg-2 control-label">Name</label><br><br>
                            <div class="col-lg-12">
                                <input type="text" class="form-control" name="inputName" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputUsername" class="col-lg-2 control-label">Username</label><br><br>
                            <div class="col-lg-12">
                                <input type="text" class="form-control" name="inputUsername" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-lg-2 control-label">Password</label><br><br>
                            <div class="col-lg-12">
                                <input type="password" class="form-control" name="inputPassword" placeholder="Do not use a real password!!">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">Email</label><br><br>
                            <div class="col-lg-12">
                                <input type="email" class="form-control" name="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        </fieldset>

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-default btn-block">Register Now</button>
                    </div>
                    </form>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->    
        <!-- END OF REGISTER MODAL -->
        <!--START OF REGISTER MODAL-->
        <div style="margin-top:85px;" class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button style="color:black;" type="button" class="close" data-dismiss="modal">×</button>
                      <h4 class="modal-title">Login</h4>
                    </div>
                    <form class="form-horizontal" action="index.php?action=login" method="POST" role="form">
                    <div class="modal-body" style="transform:scale(0.9);">
                            <fieldset>
                        <div class="form-group">
                            <label for="username" class="col-lg-2 control-label">Username</label><br><br>
                            <div class="col-lg-12">
                                <input type="text" class="form-control" name="username" placeholder="Enter username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">Password</label><br><br>
                            <div class="col-lg-12">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                        </fieldset>

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    </form>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->    
        <!-- END OF LOGIN MODAL -->
        
    <div class="container" >
      <div class="page-header" id="banner">
        <div style="margin-top:-55px;" class="row">
          <div style="margin-top:100px;" class="col-lg-12">
              <div style="position: relative; left: 0; top: 0;">
                  <div style="margin-top:-80px;" align="center">
                      
            <hr style="border-color:#4cf3ad;background-color:#4cf3ad;color:#4cf3ad;">
                  <img style="margin-left:auto; margin-top:30px; height:130px; weight:130px;overflow: hidden;" src="img/ndb_logo.png"/><br><br>
                  </div>
                  <hr style="border-color:#4cf3ad;background-color:#4cf3ad;color:#4cf3ad;">
              </div>
       
                   
    <div style="margin-top:60px;" id="table" class="row">
            <h1 id="tables" style="color:#00FFBF;">RECORD MATCH DETAILS</h1>
         <div class="col-lg-12">
              <div class="col-lg-6">
                  <img style="height:80%;width:80%;margin-top:0px;margin-left: 25px;margin-right: auto;" src="img/mhl_data.png"/>
              </div>
              <div class="col-lg-6">
                  <img style="height:100%;width:100%;margin-top:-25px;margin-left: auto;margin-right: auto;" src="img/sample_table.png"/>
              </div>
         </div>
         
        <div class="col-lg-12">
        <!--FORM TO ADD ENTRY-->
            <div class="row">
                <div style="margin-top:75px;" class="page-header">
                    <br>
                    <h1 id="tables" style="color:#00FFBF;">USER-FRIENDLY FORMS</h1>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-upload fa-fw"></i> LOG NEW ENTRY
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
                                    <input placeholder="0" type="number" name="quantity" min="0" max="100" class="form-control" name="lp" disabled>
								</div>
                                <div class="form-group">
                                    <label>Champion:</label>
                                    <input class="form-control" name="champion" disabled>
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
                                    <input placeholder="0/0/0" class="form-control" name="kda" disabled>
								</div>
                                <div class="form-group">
									<label>CS:</label>
                                    <input placeholder="0" type="number" name="quantity" min="0" max="650" class="form-control" name="cs" disabled>
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
									<label>Mistakes:</label>
                                    <input class="form-control" name="mistakes" disabled>
								</div>
								<div class="form-group">
									<label>Improve By:</label>
                                    <input class="form-control" name="improvements" disabled>
                                </div>
                            <div class="form-group">
							<button type="submit" align="center" style="margin-top:35px;" class="disabled btn btn-danger btn-lg btn-block">Add Entry</button></div>
                            </div>
							</form>

                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
            </div>
        </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
              <br><br>
            <div class="page-header">
                    <h1 id="tables" style="color:#00FFBF;">MOST PLAYED CHART</h1>
            </div>
            <img align="middle" style="position: relative;height:100%;width:100%;" src="http://i.imgur.com/6dDBSOO.png"/>
          </div>
        </div>
        
        <!-------------- REVIEWS -------------->
        <div class="row">
          <div class="col-lg-12">
              <br><br>
            <div style="margin-top:125px;" class="page-header">
                    <h1 id="tables" style="color:#00FFBF;">REVIEWS</h1>
            </div>
          </div>
        </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote>
                <p style="color:white;">This is such an awesome way to track your progress in soloQ. I'm definitely going to keep using this and make my way to Gold!</p>
                <small style="color:#00FFBF;"><cite title="Source Title">Emperor Googz</cite></small>
              </blockquote>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote class="pull-right">
                <p style="color:white;">No more need for Google Docs. This dashboard is the best way to log your improvements in ranked. List your mistakes, LP gain, and KDA!</p>
                <small style="color:#00FFBF;"> <cite title="Source Title">Chombol</cite></small>
              </blockquote>
            </div>
          </div>

        
        
        
       

          
        <div class="row">
          <div align="center" class="col-lg-12">
              <br><br><br>
            <p style="color:white;">Email: <a style="color:rgb(226, 63, 63);" href="mailto:baron@nashordb.net">baron@nashordb.net</a><br><br><a style="color:#00FFBF;" href="http://chrisluk.im" rel="nofollow">&copy; CHRISLUK</a></p>
          </div>
        </div>



    </div>
    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"/>
    <script src="js/bootstrap.js"/>
    <!--<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>-->
    <script src="assets/js/bootswatch.js"></script>
    <script>
        setTimeout(function() {
            $('#fade').fadeOut('fast');
        }, 2500);    
    </script>
        <script>
        $("#showMe").on("click" ,function(){
            scrolled=scrolled-300;
            $(".cover").animate({
                scrollTop:  scrolled
            });
        });
        </script>
    <script>
	$(function() {
	  $('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {

	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	  });
	});
	</script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  </body>
</html>