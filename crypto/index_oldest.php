<?php
//set timezone for east coast
date_default_timezone_set('America/New_York');

//require supporting functions
require_once("/Library/WebServer/Documents/NashorDB/tablegen.php");
require_once("/Library/WebServer/Documents/NashorDB/gen_roster.php");
require_once("/Library/WebServer/Documents/NashorDB/checklogin.php");
require_once("/Library/WebServer/Documents/NashorDB/add_entry.php");
require_once("/Library/WebServer/Documents/NashorDB/add_roster.php");
require_once("/Library/WebServer/Documents/NashorDB/add_teamcomp.php");
require_once("/Library/WebServer/Documents/NashorDB/genchecklist.php");
require_once("/Library/WebServer/Documents/NashorDB/gen_team_comp.php");
require_once("/Library/WebServer/Documents/NashorDB/genlatestmsg.php");

//set login status and messages to default
$loggedin = FALSE;
$action = "home";
$warning = "redirect";
$alertset = FALSE;

//check to see if they're logging in
//note: load nothing until login is confirmed
if (isset($_GET['action'])) {
	if ($_GET['action'] == "login") {
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
header('Location: http://localhost/NashorDB/login.php?warning='.$warning);
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
		header('Location: http://localhost/NashorDB/login.php?warning=loggedout');
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
$db = mysqli_connect("localhost", "syno", "fiend", "users");
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
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NashorDB: A Database Management Dashboard</title>

    <!-- Core CSS - Include with every page -->
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

<body style="background-color:#FFFFFF">

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a style="color: black" class="navbar-brand" href="index.php"><?php echo "<b>Nashor:</b> Welcome, ".$userdetails['firstname'].". The date is ".date('F jS, Y',time()) .". It is ".date('h:iA',time())."."; ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!--<li><a href="index.php?page=admin"><i class="fa fa-gear fa-fw"></i> Admin Panel</a>
                        </li>-->
                        <li><a href="index.php?action=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
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
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Statistics<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="index.php?page=stats"><i class="fa fa-table fa-fw"></i> Performance Chart</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-group fa-fw"></i> Ranked 5's<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="index.php?page=bulletin"><i class="fa fa-paperclip fa-fw"></i> Bulletin</a>
                                </li>
                                <li>
                                    <a href="index.php?page=roster"><i class="fa fa-table fa-fw"></i> Roster</a>
                                </li>
                                <li>
                                    <a href="index.php?page=comp#"><i class="fa fa-file fa-fw"></i> Team Comps</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="index.php?page=soundcloud"><i class="fa fa-music fa-fw"></i> SoundCloud</a>
                        </li>
    					<li>
                            <a href="index.php?page=about"><i class="fa fa-user fa-fw"></i> About</a>
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
                    <h1 style="color:black;line-height:60px;" class="page-header"><b>NashorDB</b> <small style="color:#4582ec"><?php echo $subtitle; ?></small> <img style="vertical-align:middle;float:right;" height="60" src="http://oi59.tinypic.com/2lkcp6x.jpg" /></h1>
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
				} else if ($rosterset) {
					echo "<div class=\"alert alert-danger\"><b><center>No roster added. Please input a valid roster.</center></b></div>";
				}  
                if ($tcmessage != NULL) {
					echo "<div class=\"alert alert-success\"><b><center>New Team Comp Added!</b></center></div>";
				} else if ($tcset) {
					echo "<div class=\"alert alert-danger\"><b><center>No comp added. Please input a valid team composition.</center></b></div>";
				}

				if (isset($_GET['page'])) {
				switch($_GET['page']) {

				case "stats":
				require_once("/Library/WebServer/Documents/NashorDB/stats.php");
				break;
                    
                case "comp":
                require_once("/Library/WebServer/Documents/NashorDB/team_comp.php");
                break;
                    
                case "roster":
				require_once("/Library/WebServer/Documents/NashorDB/roster.php");
				break;

				case "about":
				require_once("/Library/WebServer/Documents/NashorDB/about.php");
				break;

				case "soundcloud":
				require_once("/Library/WebServer/Documents/NashorDB/soundcloud.php");
				break;
                    
                case "bulletin":
				require_once("/Library/WebServer/Documents/NashorDB/bulletin.php");
				break;

				default:
				require_once("/Library/WebServer/Documents/NashorDB/dash.php");
				break;
				}
				} else {
				//default page
				require_once("/Library/WebServer/Documents/NashorDB/dash.php");
				}

			?>
			
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
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
	
	<?php
	$db = mysqli_connect("localhost", "chrisluk", "continuum", "reporting");
	$result = mysqli_query($db,"SELECT file, COUNT(*) FROM (SELECT file FROM errors UNION ALL SELECT file FROM data UNION ALL SELECT file FROM php) s GROUP BY file ORDER BY COUNT(*) DESC;");
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
	
    <script>
    Morris.Donut({
        element: 'donut-most-played',
        resize: true,
        data: [
            {label: "Champion 1", value: 12},
            {label: "Champion 2", value: 30},
            {label: "Champion 3", value: 20}
        ]
    });
    </script>
    
</body>

</html>