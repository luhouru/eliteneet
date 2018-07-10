<?php

//set timezone for east coast
date_default_timezone_set('America/New_York');

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
require_once("register.php");

//set login status and messages to default
$loggedin = FALSE;
$action = "home";
$warning = "redirect";
$alertset = FALSE;


//check to see if they're logging in
//note: load nothing until login is confirmed
if (isset($_GET['action'])) {
    switch($_GET['action']) {
	    case "login":
            if (checklogin($_POST['username'],$_POST['password'])) {
            $loggedin = TRUE;
            $warning = "goodlogin";
            if (isset($_POST['remember'])) {
            $plustime = time();
            } else {
            $plustime = 3600;
            }
            setcookie("loggedin", TRUE, time()+$plustime);
            setcookie("username", $_POST['username'], time()+$plustime);
            } else {
            $warning = "badlogin&username=".$_POST['username'];
            }
            break;
        case "register":
            $pass_or_fail = register($_POST['inputName'], $_POST['inputUsername'], $_POST['inputPassword'], $_POST['inputEmail']);
            if ($pass_or_fail == false) {
                // account could not be created because username already exists
                $warning = "failcreate";
                header('Location: login.php?warning='.$warning);
                die();
            } else {
                // then account was successfully created and we should post a banner.
                $warning = "successcreate";
                header('Location: login.php?warning='.$warning);
                die();
            }
            break;
        default:
            header('Location: login.php');
        break;
	}
}


//check to see if they remain logged in
if(isset($_COOKIE["loggedin"]) && $_COOKIE['loggedin'] == TRUE) {
	$loggedin = TRUE;
	$warning = "goodlogin";
}

//if they're not logged in, send them back to the login page
//sorry!
if (!$loggedin) {
header('Location: login.php?warning='.$warning);
die();
}


//check to see if there are any other actions they want
//if not, show the general dashboard
if (isset($_GET['action'])) {
	switch($_GET['action']) {
	case "login":
		if (checklogin($_POST['username'],$_POST['password'])) {
		$loggedin = TRUE;
		$warning = "goodlogin";
		setcookie("loggedin", TRUE, time()+3600);
		setcookie("username", $_POST['username'], time()+3600);
		$_COOKIE['username'] = $_POST['username'];
		} else {
		$warning = "badlogin&username=".$_POST['username'];
		}
	break;

	case "logout":
		unset($_COOKIE['loggedin']);
        unset($_COOKIE['username']);
        setcookie("loggedin", null, -1);
        setcookie("username", null, -1);
		header('Location: login.php?warning=loggedout');
		die();
	break;
    
	case "add_teamcomp":
	if (!isset($_POST['top'])) {
		$_POST['top'] = NULL;
	}
    if (!isset($_POST['mid'])) {
		$_POST['mid'] = NULL;
	}
    if (!isset($_POST['jungle'])) {
		$_POST['jungle'] = NULL;
	}
    if (!isset($_POST['adc'])) {
		$_POST['adc'] = NULL;
	}
    if (!isset($_POST['support'])) {
		$_POST['support'] = NULL;
	}
        
    $tc = add_teamcomp($_POST['top'],$_POST['mid'],$_POST['jungle'],$_POST['adc'],$_POST['support']);
	$tcset = TRUE;
	break;
        
    case "add_roster":
	if (!isset($_POST['top'])) {
		$_POST['top'] = NULL;
	}
    if (!isset($_POST['mid'])) {
		$_POST['mid'] = NULL;
	}
    if (!isset($_POST['jungle'])) {
		$_POST['jungle'] = NULL;
	}
    if (!isset($_POST['adc'])) {
		$_POST['adc'] = NULL;
	}
    if (!isset($_POST['support'])) {
		$_POST['support'] = NULL;
	}
    
    $roster = add_roster($_POST['top'],$_POST['mid'],$_POST['jungle'],$_POST['adc'],$_POST['support']);
	$rosterset = TRUE;
	break;

	case "add_entry":
	if (!isset($_POST['division'])) {
		$_POST['division'] = NULL;
	}
    if (!isset($_POST['lp'])) {
		$_POST['lp'] = NULL;
	}
    if (!isset($_POST['champion'])) {
		$_POST['champion'] = NULL;
	}
    if (!isset($_POST['position'])) {
		$_POST['position'] = NULL;
	}
    if (!isset($_POST['kda'])) {
		$_POST['kda'] = NULL;
	}
	if (!isset($_POST['cs'])) {
		$_POST['cs'] = NULL;
	}
	if (!isset($_POST['mistakes'])) {
		$_POST['mistakes'] = NULL;
	}
    if (!isset($_POST['improvements'])) {
		$_POST['improvements'] = NULL;
	}

	$alert = add_entry($_POST['division'],$_POST['lp'],$_POST['champion'],$_POST['position'],$_POST['kda'],$_POST['cs'],$_POST['mistakes'],$_POST['improvements']);
	$alertset = TRUE;
	break;

	default:
	$action = "home";
	break;
	}
} else {

}


//grab the user details
$db = mysqli_connect("localhost", "root", "supfoo2971", "users");
$query = "SELECT * FROM users where username='".$_COOKIE['username']."'";
$result = mysqli_query($db, $query);
$userdetails = mysqli_fetch_assoc($result);
mysqli_close($db);

$subtitle = "";
if (isset($_GET['page'])) {
	switch($_GET['page']) {
        
        case "roster":
		$subtitle = "Add New Roster";
		break;
        
        case "comp":
		$subtitle = "Team Comps";
		break;

		case "about":
		$subtitle = "About NashorDB";
		break;
        
        case "soundcloud":
		$subtitle = "Soundcloud";
		break;
        
        case "reddit":
		$subtitle = "Reddit";
		break;
        
        case "bulletin":
		$subtitle = "Mandate of Heaven Bulletin";
		break;
        
        case "stats":
		$subtitle = "Performance Chart";
		break;

		default:
		$subtitle = "Dashboard";
		break;
		}
} else {
	//default page
	$subtitle = "Dashboard";
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootswatch: Readable</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/bootswatch.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
      <script src="../bower_components/respond/dest/respond.min.js"></script>
    <![endif]-->
    <script>

     var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-23019901-1']);
      _gaq.push(['_setDomainName', "bootswatch.com"]);
        _gaq.push(['_setAllowLinker', true]);
      _gaq.push(['_trackPageview']);

     (function() {
       var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
       ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
     })();

    </script>
    <style>
        ::-webkit-datetime-edit-year-field:not([aria-valuenow]),
        ::-webkit-datetime-edit-month-field:not([aria-valuenow]),
        ::-webkit-datetime-edit-day-field:not([aria-valuenow]) {
            color: transparent;
        }
      </style>  
  </head>
  <body style="background-size:100%;background-position:absolute;background-attachment:fixed;" background="img/nashor_bg.png">
    <div class="navbar navbar-default navbar-relative-top" style="background-color:transparent;">
      <div class="container">
        <div class="navbar-header">
          <a href="../" class="navbar-brand">Bootswatch</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Themes <span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="../default/">Default</a></li>
                <li class="divider"></li>
                <li><a href="../cerulean/">Cerulean</a></li>
                <li><a href="../cosmo/">Cosmo</a></li>
                <li><a href="../cyborg/">Cyborg</a></li>
                <li><a href="../darkly/">Darkly</a></li>
                <li><a href="../flatly/">Flatly</a></li>
                <li><a href="../journal/">Journal</a></li>
                <li><a href="../lumen/">Lumen</a></li>
                <li><a href="../paper/">Paper</a></li>
                <li><a href="../readable/">Readable</a></li>
                <li><a href="../sandstone/">Sandstone</a></li>
                <li><a href="../simplex/">Simplex</a></li>
                <li><a href="../slate/">Slate</a></li>
                <li><a href="../spacelab/">Spacelab</a></li>
                <li><a href="../superhero/">Superhero</a></li>
                <li><a href="../united/">United</a></li>
                <li><a href="../yeti/">Yeti</a></li>
              </ul>
            </li>
            <li>
              <a href="../help/">Help</a>
            </li>
            <li>
              <a href="http://news.bootswatch.com">Blog</a>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Download <span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="download">
                <li><a href="css/bootstrap.min.css">bootstrap.min.css</a></li>
                <li><a href="css/bootstrap.css">bootstrap.css</a></li>
                <li class="divider"></li>
                <li><a href="./variables.less">variables.less</a></li>
                <li><a href="./bootswatch.less">bootswatch.less</a></li>
              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><form action="index.php?action=logout" method="POST" role="form"><button style="margin-top:12px;" class="btn btn-danger btn-md" type="submit">LOGOUT</button></form></li>
          </ul>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="page-header" id="banner">
        <div class="row">
          <div class="col-lg-8 col-md-7 col-sm-6">
            <h1 style="color:white;">MY DASHBOARD</h1>
            <p style="color:white;" class="lead">For all your League of Legends needs!</p>
          </div>
          <div class="col-lg-4 col-md-5 col-sm-6">
            <div class="sponsor">
            </div>
          </div>
        </div>
      </div>
        
        <!--MOST PLAYED CHAMPIONS MORRIS CHART-->
        <div class="row">
            <div class="col-lg-12">
                <!-- /.panel -->
                 <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Most Played Champions
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div style="height:325px;" id="morris-file-bar"></div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>
        
        <p style="color:white">
                    <?php
                    	$connection = mysqli_connect("localhost", "root", "supfoo2971", "stats");
                        // find the last entries LP
                        $username = $_COOKIE['username'];
                        $lp_query = "SELECT `lp` FROM ".$username." ORDER BY entry_id DESC limit 1";

                        $lp_result = mysqli_query($connection, $lp_query);

                        // fetch query results
                        $lp_row = mysqli_fetch_assoc($lp_result);
                        $lp_old = $lp_row['lp'];
                        $div_query = "SELECT `division` FROM `".$username."` ORDER BY entry_id DESC limit 1";

			// fetch division query results
			$div_result = mysqli_query($connection, $div_query);
			$div_row = mysqli_fetch_assoc($div_result);
            if($div_row == false) {
                die();
            }
			$current_div = $div_row['division'];
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
 	
                        // find the last entries LP
                        $lp_query = "SELECT `lp` FROM ".$username." ORDER BY entry_id DESC limit 1";
                        $lp_result = mysqli_query($connection, $lp_query);
                        if ($lp_result == false) {
                            die();
                        }
                        // fetch query results
	                $lp_row = mysqli_fetch_assoc($lp_result);
                        $lp_old = $lp_row['lp'];
                        echo "style='width: ".$lp_old."%'";
                    ?>>
                </div>
            </div>    
        
        <!--THIS IS WHERE THE PERFORMANCE TABLE WILL BE-->
        <div class="row">
                <div class="col-lg-12">
                    <!-- /.panel -->
                    <?php 
					   echo tablegen(0,0); 
					?>
                </div>
        </div>
        
        
            <div class="row">
                <div class="page-header">
                    <h1 id="tables" style="color:#c7e274;">Log Entry</h1>
                    <p style="color:white;margin-left:3em;">Use this form to log your post-game data.</p>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-info">
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
                                    <input placeholder="0" type="number" name="quantity" min="0" max="100" class="form-control" name="lp">
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
                                    <input placeholder="0" type="number" name="quantity" min="0" max="650" class="form-control" name="cs">
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
							<button type="submit" align="center" style="margin-top:35px;" class="btn btn-info btn-lg btn-block">Add Entry</button></div>
                            </div>
							</form>

                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
            </div>
        </div>
        
        
        <br> <br> <br> <br> <br> <br> <br>
      <!-- Navbar
      ================================================== -->
      <div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="navbar">Navbar</h1>
            </div>

            <div class="bs-component">
              <div class="navbar navbar-default">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">Brand</a>
                </div>
                <div class="navbar-collapse collapse navbar-responsive-collapse">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Active</a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Dropdown header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                      </ul>
                    </li>
                  </ul>
                  <form class="navbar-form navbar-left">
                    <input type="text" class="form-control col-lg-8" placeholder="Search">
                  </form>
                  <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="bs-component">
              <div class="navbar navbar-inverse">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">Brand</a>
                </div>
                <div class="navbar-collapse collapse navbar-inverse-collapse">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Active</a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Dropdown header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                      </ul>
                    </li>
                  </ul>
                  <form class="navbar-form navbar-left">
                    <input type="text" class="form-control col-lg-8" placeholder="Search">
                  </form>
                  <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div><!-- /example -->

          </div>
        </div>
      </div>


      <!-- Buttons
      ================================================== -->
      <div class="bs-docs-section">
        <div class="page-header">
          <div class="row">
            <div class="col-lg-12">
              <h1 id="buttons">Buttons</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">

            <p class="bs-component">
              <a href="#" class="btn btn-default">Default</a>
              <a href="#" class="btn btn-primary">Primary</a>
              <a href="#" class="btn btn-success">Success</a>
              <a href="#" class="btn btn-info">Info</a>
              <a href="#" class="btn btn-warning">Warning</a>
              <a href="#" class="btn btn-danger">Danger</a>
              <a href="#" class="btn btn-link">Link</a>
            </p>

            <p class="bs-component">
              <a href="#" class="btn btn-default disabled">Default</a>
              <a href="#" class="btn btn-primary disabled">Primary</a>
              <a href="#" class="btn btn-success disabled">Success</a>
              <a href="#" class="btn btn-info disabled">Info</a>
              <a href="#" class="btn btn-warning disabled">Warning</a>
              <a href="#" class="btn btn-danger disabled">Danger</a>
              <a href="#" class="btn btn-link disabled">Link</a>
            </p>


            <div style="margin-bottom: 15px;">
              <div class="btn-toolbar bs-component" style="margin: 0;">
                <div class="btn-group">
                  <a href="#" class="btn btn-default">Default</a>
                  <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-primary">Primary</a>
                  <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-success">Success</a>
                  <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-info">Info</a>
                  <a href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-warning">Warning</a>
                  <a href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <p class="bs-component">
              <a href="#" class="btn btn-primary btn-lg">Large button</a>
              <a href="#" class="btn btn-primary">Default button</a>
              <a href="#" class="btn btn-primary btn-sm">Small button</a>
              <a href="#" class="btn btn-primary btn-xs">Mini button</a>
            </p>

          </div>
          <div class="col-lg-6">

            <p class="bs-component">
              <a href="#" class="btn btn-default btn-lg btn-block">Block level button</a>
            </p>


            <div class="bs-component" style="margin-bottom: 15px;">
              <div class="btn-group btn-group-justified">
                <a href="#" class="btn btn-default">Left</a>
                <a href="#" class="btn btn-default">Middle</a>
                <a href="#" class="btn btn-default">Right</a>
              </div>
            </div>

            <div class="bs-component" style="margin-bottom: 15px;">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <a href="#" class="btn btn-default">1</a>
                  <a href="#" class="btn btn-default">2</a>
                  <a href="#" class="btn btn-default">3</a>
                  <a href="#" class="btn btn-default">4</a>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-default">5</a>
                  <a href="#" class="btn btn-default">6</a>
                  <a href="#" class="btn btn-default">7</a>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-default">8</a>
                  <div class="btn-group">
                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      Dropdown
                      <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="#">Dropdown link</a></li>
                      <li><a href="#">Dropdown link</a></li>
                      <li><a href="#">Dropdown link</a></li>
                     </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="bs-component">
              <div class="btn-group-vertical">
                  <a href="#" class="btn btn-default">Button</a>
                  <a href="#" class="btn btn-default">Button</a>
                  <a href="#" class="btn btn-default">Button</a>
                  <a href="#" class="btn btn-default">Button</a>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Typography
      ================================================== -->
      <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="type">Typography</h1>
            </div>
          </div>
        </div>

        <!-- Headings -->

        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <h1>Heading 1</h1>
              <h2>Heading 2</h2>
              <h3>Heading 3</h3>
              <h4>Heading 4</h4>
              <h5>Heading 5</h5>
              <h6>Heading 6</h6>
              <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <h2>Example body text</h2>
              <p>Nullam quis risus eget <a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
              <p><small>This line of text is meant to be treated as fine print.</small></p>
              <p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
              <p>The following snippet of text is <em>rendered as italicized text</em>.</p>
              <p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
            </div>

          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <h2>Emphasis classes</h2>
              <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
              <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
              <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
              <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
              <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
            </div>

          </div>
        </div>

        <!-- Blockquotes -->

        <div class="row">
          <div class="col-lg-12">
            <h2 id="type-blockquotes">Blockquotes</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
              </blockquote>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote class="pull-right">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
              </blockquote>
            </div>
          </div>
        </div>
      </div>

      <!-- Tables
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Tables</h1>
            </div>

            <div class="bs-component">
              <table class="table table-striped table-hover ">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Column heading</th>
                    <th>Column heading</th>
                    <th>Column heading</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="info">
                    <td>3</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="success">
                    <td>4</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="danger">
                    <td>5</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="warning">
                    <td>6</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="active">
                    <td>7</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                </tbody>
              </table> 
            </div><!-- /example -->
          </div>
        </div>
      </div>

      <!-- Forms
      ================================================== -->
      <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Forms</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="well bs-component">
              <form class="form-horizontal">
                <fieldset>
                  <legend>Legend</legend>
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> Checkbox
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Textarea</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="textArea"></textarea>
                      <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-2 control-label">Radios</label>
                    <div class="col-lg-10">
                      <div class="radio">
                        <label>
                          <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                          Option one is this
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                          Option two can be something else
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="select" class="col-lg-2 control-label">Selects</label>
                    <div class="col-lg-10">
                      <select class="form-control" id="select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                      <br>
                      <select multiple="" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                      <button class="btn btn-default">Cancel</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="col-lg-4 col-lg-offset-1">

              <form class="bs-component">
                <div class="form-group">
                  <label class="control-label" for="focusedInput">Focused input</label>
                  <input class="form-control" id="focusedInput" type="text" value="This is focused...">
                </div>

                <div class="form-group">
                  <label class="control-label" for="disabledInput">Disabled input</label>
                  <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
                </div>

                <div class="form-group has-warning">
                  <label class="control-label" for="inputWarning">Input warning</label>
                  <input type="text" class="form-control" id="inputWarning">
                </div>

                <div class="form-group has-error">
                  <label class="control-label" for="inputError">Input error</label>
                  <input type="text" class="form-control" id="inputError">
                </div>

                <div class="form-group has-success">
                  <label class="control-label" for="inputSuccess">Input success</label>
                  <input type="text" class="form-control" id="inputSuccess">
                </div>

                <div class="form-group">
                  <label class="control-label" for="inputLarge">Large input</label>
                  <input class="form-control input-lg" type="text" id="inputLarge">
                </div>

                <div class="form-group">
                  <label class="control-label" for="inputDefault">Default input</label>
                  <input type="text" class="form-control" id="inputDefault">
                </div>

                <div class="form-group">
                  <label class="control-label" for="inputSmall">Small input</label>
                  <input class="form-control input-sm" type="text" id="inputSmall">
                </div>

                <div class="form-group">
                  <label class="control-label">Input addons</label>
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Button</button>
                    </span>
                  </div>
                </div>
              </form>

          </div>
        </div>
      </div>

      <!-- Navs
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="nav">Navs</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <h2 id="nav-tabs">Tabs</h2>
            <div class="bs-component">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
                <li><a href="#profile" data-toggle="tab">Profile</a></li>
                <li class="disabled"><a>Disabled</a></li>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Dropdown <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a href="#dropdown1" data-toggle="tab">Action</a></li>
                    <li class="divider"></li>
                    <li><a href="#dropdown2" data-toggle="tab">Another action</a></li>
                  </ul>
                </li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="home">
                  <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                </div>
                <div class="tab-pane fade" id="profile">
                  <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                </div>
                <div class="tab-pane fade" id="dropdown1">
                  <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                </div>
                <div class="tab-pane fade" id="dropdown2">
                  <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <h2 id="nav-pills">Pills</h2>
            <div class="bs-component">
              <ul class="nav nav-pills">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Profile</a></li>
                <li class="disabled"><a href="#">Disabled</a></li>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Dropdown <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </li>
              </ul>
            </div>
            <br>
            <div class="bs-component">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Profile</a></li>
                <li class="disabled"><a href="#">Disabled</a></li>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Dropdown <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">
            <h2 id="nav-breadcrumbs">Breadcrumbs</h2>
            <div class="bs-component">
              <ul class="breadcrumb">
                <li class="active">Home</li>
              </ul>

              <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Library</li>
              </ul>

              <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Library</a></li>
                <li class="active">Data</li>
              </ul>
            </div>

          </div>
        </div>


        <div class="row">
          <div class="col-lg-4">
            <h2 id="pagination">Pagination</h2>
            <div class="bs-component">
              <ul class="pagination">
                <li class="disabled"><a href="#">&laquo;</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>

              <ul class="pagination pagination-lg">
                <li class="disabled"><a href="#">&laquo;</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>

              <ul class="pagination pagination-sm">
                <li class="disabled"><a href="#">&laquo;</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">
            <h2 id="pager">Pager</h2>
            <div class="bs-component">
              <ul class="pager">
                <li><a href="#">Previous</a></li>
                <li><a href="#">Next</a></li>
              </ul>

              <ul class="pager">
                <li class="previous disabled"><a href="#">&larr; Older</a></li>
                <li class="next"><a href="#">Newer &rarr;</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">

          </div>
        </div>
      </div>

      <!-- Indicators
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="indicators">Indicators</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <h2>Alerts</h2>
            <div class="bs-component">
              <div class="alert alert-dismissable alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Warning!</h4>
                <p>Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissable alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <h2>Labels</h2>
            <div class="bs-component" style="margin-bottom: 40px;">
              <span class="label label-default">Default</span>
              <span class="label label-primary">Primary</span>
              <span class="label label-success">Success</span>
              <span class="label label-warning">Warning</span>
              <span class="label label-danger">Danger</span>
              <span class="label label-info">Info</span>
            </div>
          </div>
          <div class="col-lg-4">
            <h2>Badges</h2>
            <div class="bs-component">
              <ul class="nav nav-pills">
                <li class="active"><a href="#">Home <span class="badge">42</span></a></li>
                <li><a href="#">Profile <span class="badge"></span></a></li>
                <li><a href="#">Messages <span class="badge">3</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Progress bars
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="progress">Progress bars</h1>
            </div>

            <h3 id="progress-basic">Basic</h3>
            <div class="bs-component">
              <div class="progress">
                <div class="progress-bar" style="width: 60%;"></div>
              </div>
            </div>

            <h3 id="progress-alternatives">Contextual alternatives</h3>
            <div class="bs-component">
              <div class="progress">
                <div class="progress-bar progress-bar-info" style="width: 20%"></div>
              </div>

              <div class="progress">
                <div class="progress-bar progress-bar-success" style="width: 40%"></div>
              </div>

              <div class="progress">
                <div class="progress-bar progress-bar-warning" style="width: 60%"></div>
              </div>

              <div class="progress">
                <div class="progress-bar progress-bar-danger" style="width: 80%"></div>
              </div>
            </div>

            <h3 id="progress-striped">Striped</h3>
            <div class="bs-component">
              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-info" style="width: 20%"></div>
              </div>

              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-success" style="width: 40%"></div>
              </div>

              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%"></div>
              </div>

              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-danger" style="width: 80%"></div>
              </div>
            </div>

            <h3 id="progress-animated">Animated</h3>
            <div class="bs-component">
              <div class="progress progress-striped active">
                <div class="progress-bar" style="width: 45%"></div>
              </div>
            </div>

            <h3 id="progress-stacked">Stacked</h3>
            <div class="bs-component">
              <div class="progress">
                <div class="progress-bar progress-bar-success" style="width: 35%"></div>
                <div class="progress-bar progress-bar-warning" style="width: 20%"></div>
                <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Containers
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="container">Containers</h1>
            </div>
            <div class="bs-component">
              <div class="jumbotron">
                <h1>Jumbotron</h1>
                <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                <p><a class="btn btn-primary btn-lg">Learn more</a></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>List groups</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <ul class="list-group">
                <li class="list-group-item">
                  <span class="badge">14</span>
                  Cras justo odio
                </li>
                <li class="list-group-item">
                  <span class="badge">2</span>
                  Dapibus ac facilisis in
                </li>
                <li class="list-group-item">
                  <span class="badge">1</span>
                  Morbi leo risus
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="list-group">
                <a href="#" class="list-group-item active">
                  Cras justo odio
                </a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in
                </a>
                <a href="#" class="list-group-item">Morbi leo risus
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="list-group">
                <a href="#" class="list-group-item">
                  <h4 class="list-group-item-heading">List group item heading</h4>
                  <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                </a>
                <a href="#" class="list-group-item">
                  <h4 class="list-group-item-heading">List group item heading</h4>
                  <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                </a>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Panels</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="panel panel-default">
                <div class="panel-body">
                  Basic panel
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading">Panel heading</div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-body">
                  Panel content
                </div>
                <div class="panel-footer">Panel footer</div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Panel primary</h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>

              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Panel success</h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>

              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title">Panel warning</h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title">Panel danger</h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>

              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Panel info</h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <h2>Wells</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="well">
                Look, I'm in a well!
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="well well-sm">
                Look, I'm in a small well!
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="well well-lg">
                Look, I'm in a large well!
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dialogs
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Dialogs</h1>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <h2>Modals</h2>
            <div class="bs-component">
              <div class="modal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                      <p>One fine body…</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <h2>Popovers</h2>
            <div class="bs-component">
              <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Left</button>

              <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Top</button>

              <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
              sagittis lacus vel augue laoreet rutrum faucibus.">Bottom</button>

              <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Right</button>
            </div>
            <h2>Tooltips</h2>
            <div class="bs-component">
              <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="" data-original-title="Tooltip on left">Left</button>

              <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">Top</button>

              <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Bottom</button>

              <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="" data-original-title="Tooltip on right">Right</button>
            </div>
          </div>
        </div>
      </div>

      <div id="source-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Source Code</h4>
            </div>
            <div class="modal-body">
              <pre></pre>
            </div>
          </div>
        </div>
      </div>

      <footer>
        <div class="row">
          <div class="col-lg-12">

            <ul class="list-unstyled">
              <li class="pull-right"><a href="#top">Back to top</a></li>
              <li><a href="http://news.bootswatch.com" onclick="pageTracker._link(this.href); return false;">Blog</a></li>
              <li><a href="http://feeds.feedburner.com/bootswatch">RSS</a></li>
              <li><a href="https://twitter.com/bootswatch">Twitter</a></li>
              <li><a href="https://github.com/thomaspark/bootswatch/">GitHub</a></li>
              <li><a href="../help/#api">API</a></li>
              <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=F22JEM3Q78JC2">Donate</a></li>
            </ul>
            <p>Made by <a href="http://thomaspark.me" rel="nofollow">Thomas Park</a>. Contact him at <a href="mailto:thomas@bootswatch.com">thomas@bootswatch.com</a>.</p>
            <p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/gh-pages/LICENSE">MIT License</a>.</p>
            <p>Based on <a href="http://getbootstrap.com" rel="nofollow">Bootstrap</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/" rel="nofollow">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts" rel="nofollow">Google</a>.</p>

          </div>
        </div>

      </footer>


    </div>


    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
    
    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="js/plugins/morris/morris.js"></script>
      
    <?php
	$db = mysqli_connect("localhost", "root", "supfoo2971", "stats");
    $result = mysqli_query($db, "SELECT champion, count(*) FROM chrisluk GROUP BY champion ORDER BY count(*) DESC;");
    if ($result == false) {
        die();
    }
        
	$errors = mysqli_fetch_all($result);
	$datas = "";
	foreach ($errors as &$val) {
		$val[0] = basename($val[0]);
		$datas .= "{ y: '$val[0]', a: $val[1]},";
	}
	?>
	
	<script>
	Morris.Bar({
		element: 'morris-file-bar',
		data: [
		<?php echo $datas; ?>
			],
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Count']
	});
	</script>  
    
  </body>
</html>

