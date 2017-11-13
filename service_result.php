<?php
session_start();
if (!isset($_SESSION['authenticated']) OR !$_SESSION['authenticated'] == 1) {
    header("Location: verify.php");
die();
}
?>
<!DOCTYPE html>
<html lang="en-us">
  <head>
	<meta charset="utf-8">
    <title>Submission Results</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
</head>
<body>
    <body class="">
	<header id="header">
	<?php include "menu.php"; ?>
	  <div class="pull-right">
		<div id="logout" class="btn-header transparent pull-right">
		</div>
	  </div>
	</header>
	<aside id="left-panel">
	  <div class="login-info">
	    <span>
		  <?php echo "Welcome, " . $_SESSION['fullname']?>
		</span>
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="search.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
			<a href="logout.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>
		  </li>
		</ul>
	  </nav>
	</aside>
	<div id="main" role="main">
	  <div id="ribbon">
		<ol class="breadcrumb">
		  <li>Home</li>
		  <li>Register</li>
		</ol>
	  </div>
	  <br><br>
  <?php
  $msg = "";
  if(!empty($_POST['service']) && !empty($_POST['shift'])){
	  $to = $email;
	  $subject = "Registration";
	  $body = $msg;
	  $notification = $_POST['sendemail'];
	  $timestamp = date("Y-m-d H:i:s");
	  $service = implode(",", $_POST['service']);
      $theshift="";
	  $shift = implode(",", $_POST['shift']);
	  $shiftarr = explode(",", $shift);
	  foreach ($shiftarr as $shiftt){
		if (strpos($shiftt, 'mon') !== false){
			$theshift .= "<p>Monday ";
		} elseif (strpos($shiftt, 'tues') !== false){
			$theshift .= "<p>Tuesday ";
		} elseif (strpos($shiftt, 'wed') !== false){
			$theshift .= "<p>Wednesday ";
		} elseif (strpos($shiftt, 'thurs') !== false){
			$theshift .= "<p>Thursday ";
		} elseif (strpos($shiftt, 'fri') !== false){
			$theshift .= "<p>Friday ";
		} elseif (strpos($shiftt, 'sat') !== false){
			$theshift .= "<p>Saturday ";
		} elseif (strpos($shiftt, 'sun') !== false){
			$theshift .= "<p>Sunday ";
		}
		if (strpos($shiftt, 'morn') !== false){
			$theshift .= "Morning</p>";
		} elseif (strpos($shiftt, 'aft') !== false){
			$theshift .= "Afternoon</p>";
		} elseif (strpos($shiftt, 'eve') !== false){
			$theshift .= "Evening</p>";
		}
	  }
	  $msg = $msg . "Your registration has been received.<br>";
	  $msg = $msg . "<b>Service:</b> " . $service . "<br>";
	  $msg = $msg . "<b>Shift:</b> " . $theshift . "<br>";
	  echo $msg;
	  $conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
	  if (mysqli_connect_errno($conn)){
		echo 'Cannot connect to database: ' . mysqli_connect_error();
	  }else{
		$query1 = mysqli_prepare($conn, "update profile set availability=? where username=?;");
		mysqli_stmt_bind_param ($query1, "ss", $availability, $_SESSION["username"]);
		mysqli_stmt_execute($query1);
		mysqli_stmt_store_result($query1);
		$query3 = mysqli_prepare($conn, "SELECT ksuid FROM profile where username=?");
		mysqli_stmt_bind_param ($query3, "s", $_SESSION["username"]);
		mysqli_stmt_execute($query3);
		mysqli_stmt_store_result($query3);
		mysqli_stmt_bind_result($query3, $ksuID);
		$servshort = explode(',', $service);
		foreach($servshort as $sid){
		  $query2 = mysqli_prepare($conn, "SELECT servID FROM services where shortdesc= ?") ;             
		  mysqli_stmt_bind_param ($query2, "s", $sid);
		  mysqli_stmt_execute($query2);
		  mysqli_stmt_store_result($query2);
		  mysqli_stmt_bind_result($query2, $servID);
		  while (mysqli_stmt_fetch($query2)) {
			$query3 = "insert into servicesProvided (userid, servID) values('$ksuid', '$servID');";
			$result = mysqli_query($conn,$query3);
		  }
		  
		}
	    mysqli_close($conn);
	  }
	} else {
	  if (empty($_POST["ksuid"])){
		$msg = $msg . "<b>KSU ID is required. Please go back and fill it in.<br>";
	  }
	  if(empty($_POST["fname"])){
		  $msg = $msg . "<b>First name is required. Please go back and fill it in.<br>";
	  }
	  if(empty($_POST["lname"])){
		  $msg = $msg . "<b>Last name is required. Please go back and fill it in.<br>";
	  }
	  if(empty($_POST["email"])){
		  $msg = $msg . "<b>Email address is required. Please go back and fill it in.<br>";
	  }
	  if(empty($_POST['service'])){
		  $msg = $msg . "<b>At least one service is required. Please go back and fill it in.<br>";
	  }
	  if(empty($_POST['shift'])){
		  $msg = $msg . "<b>At least one shift is required. Please go back and fill it in.<br>";
	  }
      echo $msg;
    }
  echo "<br><br><br><a href='register.php'>Back</a>";
  ?>
  </body>
</html>