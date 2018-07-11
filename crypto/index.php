<?php

//set timezone for east coast
date_default_timezone_set('America/New_York');

//require supporting functions
require_once("tablegen.php");
require_once("mostrecent.php");
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
	$tcset = true;
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
	$rosterset = true;
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
$db = mysqli_connect("localhost", "luho", "jisoo", "cryptodb");
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
		$subtitle = "About CryptoDB";
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
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CryptoDB: Cryptocurrency Database Trade Tracker</title>

    <!-- Core CSS - Include with every page -->
    <link rel="icon" 
      type="image/png" 
      href="/img/favicon.png">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="css/plugins/timeline/timeline.css" rel="stylesheet">
	
    <!-- Page-Level Plugin CSS - Tables -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body style="zoom: 85%";background-size:100%;background-position:absolute;background-attachment:fixed;background-color:transparent; background="img/blue_Bg.jpg">

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a style="color: white" class="navbar-brand" href="index.php"><?php echo "<b>TRON-AI:</b> Welcome, ".$userdetails['firstname'].". The date is ".date('F jS, Y',time()) .". It is ".date('h:iA',time())."."; ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <li><form action="index.php?action=logout" method="POST" role="form"><button style="margin-top:12px;" class="btn btn-danger btn-md" type="submit">LOGOUT</button></form>
                    </li>
                <!-- /.dropdown -->
            </ul>
            
            <!-- /.navbar-top-links -->

            <div class="navbar-default navbar-static-side" role="navigation">
                <div style="padding-top: 6px" class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <!--<li class="sidebar-search">
							<div style="margin-top: -20px;margin-bottom:-8px;" class="panel-heading">
                            <center><small><b>Error Code Lookup</b></small></center>
							</div>
							<form action="index.php?page=<?php if (isset($_GET['page'])) { echo $_GET['page']; } else { echo "search"; } ?>&action=lookup" method="POST" role="form">
                            <div class="input-group custom-search-form">
                                <input type="text" name="codesearch" class="form-control" placeholder="Ex: 1_0_20, 0-0-12">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                             /input-group 
							</form>
                        </li>-->
                        <li>
                            <a style="color:#3a66da;" href="index.php?page=dash"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a style="color:#3a66da;" href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Statistics<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a style="color:#3a66da;" href="index.php?page=stats"><i class="fa fa-table fa-fw"></i> Trading Tracker</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a style="color:#3a66da;" href="#"><i class="fa fa-group fa-fw"></i> Visual Data<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
<!--                                <li>
                                    <a style="color:#41d785;" href="index.php?page=bulletin"><i class="fa fa-paperclip fa-fw"></i> Bulletin</a>
                                </li>-->
                                <li>
                                    <a style="color:#3a66da;" href="index.php?page=roster"><i class="fa fa-table fa-fw"></i> Graphs</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a style="color:#3a66da;" href="index.php?page=comp#"><i class="fa fa-file fa-fw"></i> Placeholder Tab</a>
                        </li>
                        <li>
                            <a style="color:#3a66da;" href="index.php?page=soundcloud"><i class="fa fa-music fa-fw"></i> SoundCloud</a>
                        </li>
    					<li>
                            <a style="color:#3a66da;" href="index.php?page=about"><i class="fa fa-user fa-fw"></i> About</a>
                        </li>
                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        
        
        <div id="page-wrapper">
            <div class="row">
               
                <div class="col-lg-12">
                    <h1 style="color:white;line-height:60px;" class="page-header"><b>CryptoDB</b> <small style="color:#a0bcff"><?php echo $subtitle; ?></small> <img style="vertical-align:middle;float:right;" height="60" src="img/baron_icon.png" /></h1>
                </div>
                    
            </div>

			<?php

                //alert for roster and teamcomp addition
				if ( (empty($roster[0])) || (empty($roster[1])) || (empty($roster[2])) || (empty($roster[3])) || (empty($roster[4])) ) {
                    $rostermessage = NULL;
                } else { $rostermessage = "not null"; }
                if ( (empty($tc[0])) || (empty($tc[1])) || (empty($tc[2])) || (empty($tc[3])) || (empty($tc[4])) ) {
                    $tcmessage = NULL;
                } else { $tcmessage = "not null"; }	
				if ($rostermessage != NULL) {
					echo "<div class=\"alert alert-success\"><b><center>New Roster Added!</b></center></div>";
				} else if (isset($rosterset)) {
					echo "<div class=\"alert alert-danger\"><b><center>No roster added. Please input a valid roster.</center></b></div>";
				}  
                if ($tcmessage != NULL) {
					echo "<div class=\"alert alert-success\"><b><center>New Team Comp Added!</b></center></div>";
				} else if (isset($tcset)) {
					echo "<div class=\"alert alert-danger\"><b><center>No comp added. Please input a valid team composition.</center></b></div>";
				}

				if (isset($_GET['page'])) {
				switch($_GET['page']) {

				case "stats":
				require_once("stats.php");
				break;
                    
                case "comp":
                require_once("team_comp.php");
                break;
                    
                case "roster":
				require_once("roster.php");
				break;

				case "about":
				require_once("about.php");
				break;

				case "soundcloud":
				require_once("soundcloud.php");
				break;
                    
                case "bulletin":
				require_once("bulletin.php");
				break;
     
                case "dash":
				require_once("dash.php");
				break;
                    
				default:
				require_once("dash.php");
				break;
				}
				} else {
				//default page
				require_once("dash.php");
				}

			?>
			
        </div>
        <!-- /#page-wrapper -->
        
    </div>
    <!-- /#wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

	<!-- Page-Level Plugin Scripts - Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
	
    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="js/plugins/morris/morris.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-example').dataTable();
    });
    </script>
	
	<script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 10,
			"order": [[ 0, "desc" ]]
		} );
        $('#dataTables-latest').dataTable();
    });
    </script>
	
	    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-errors').dataTable();
    });
    </script>
	
	    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-data').dataTable();
    });
    </script>
	
	    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-php').dataTable();
    });
    </script>
	
	    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-lost').dataTable();
    });
    </script>
	
		    <script>
    $(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, {
			"iDisplayLength": 12
		} );
        $('#dataTables-sync').dataTable();
    });
    </script>
	
    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="js/plugins/morris/morris.js"></script>
      
    <?php
	$db = mysqli_connect("localhost", "root", "supfoo2971", "stats");
    $result = mysqli_query($db, "SELECT champion, count(*) FROM chrisluk GROUP BY champion ORDER BY count(*) DESC;");
    if ($result == false) {
        echo "WHY YOU FAIL BETCH FFS";
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
        ymax: 15,
        allowDecimals: false,
		labels: ['Count']
	});
	</script>
    
</body>

</html>