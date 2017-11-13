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
	<title> KSU Task List </title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
  </head>
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
		<?php
		  echo "Welcome," . $_SESSION["fullname"];
			?>		  
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
		  <li>Search</li>
		</ol>
	  </div>
	  <br><br>
	  <div class="center">
	    <h1><b><u>Search for Services</u></b></h1>
		<br>
		<form action="search.php" method="post">
	      <br>
	      <h2>Select a service</h2>
		  <select id="service" name="service"> 
			<option value="php">PHP Tutoring</option>
			<option value="sql">SQL Tutoring</option>
			<option value="cpp">C++ Tutoring</option>
			<option value="java">Java Tutoring</option>
			<option value="repair">Computer Repair</option>
		  </select>
		  <br><br>
		  <input type="submit" value="Submit">
		</form>
		<br><br><br>
		<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		  $serv = $_POST['service'];
		  $conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			if (mysqli_connect_errno($conn)){
			  echo 'Cannot connect to database: ' . mysqli_connect_error();
			}else{
			  $query2 = mysqli_prepare($conn, "SELECT a.first_name, a.last_name, a.availability, c.description from profile a join servicesprovided b on a.ksuid=b.userid join services c on b.servid=c.servid where c.shortdesc= ?") ;
			  mysqli_stmt_bind_param ($query2, "s", $serv);
			  mysqli_stmt_execute($query2);
			  mysqli_stmt_store_result($query2);
			  mysqli_stmt_bind_result($query2, $fname, $lname, $avail, $desc);
			}
			$i=0;
			while (mysqli_stmt_fetch($query2)) {
			  $resarr[$i][0] = $fname . " " . $lname;
			  $resarr[$i][1] = $avail;
			  $resarr[$i][2] = $serv;
			  $i+=1;
			}
			mysqli_close($conn);
			if( mysqli_stmt_num_rows($query2) == 0){
			  echo "<h2>No tutors available for this service.<h2>";
			}else{
				echo "<h1> Service: " . $desc ."</h1><br>";
			?>
		<table class="center">
	    <tr>
		  <th>Name</th>
		  <th>Day</th>
		  <th>Time</th>
		</tr>
		<?php
		  for($x = 0; $x < count($resarr); $x++) {  
		  $availarr = explode(",", $avail);
			for ($y = 0; $y < count($availarr); $y++){
			  $row = "<tr><td> ".$resarr[$x][0] . "</td><td> ";
			  if (strpos($availarr[$y], 'mon') !== false){
				$theshift = "Monday ";
			  } elseif (strpos($availarr[$y], 'tues') !== false){
				$theshift = "Tuesday ";
			  } elseif (strpos($availarr[$y], 'wed') !== false){
				$theshift = "Wednesday ";
			  } elseif (strpos($availarr[$y], 'thurs') !== false){
				$theshift = "Thursday ";
			  } elseif (strpos($availarr[$y], 'fri') !== false){
				$theshift = "Friday ";
			  } elseif (strpos($availarr[$y], 'sat') !== false){
				$theshift = "Saturday ";
			  } elseif (strpos($availarr[$y], 'sun') !== false){
				$theshift = "Sunday ";
			  }
			  $row .= $theshift . "</td><td>";
			  if (strpos($availarr[$y], 'morn') !== false){
				$theshift = "Morning";
			  } elseif (strpos($availarr[$y], 'aft') !== false){
				$theshift = "Afternoon";
			  } elseif (strpos($availarr[$y], 'eve') !== false){
				$theshift = "Evening";
			  }
			  $row .= $theshift . "</td></tr>";
			  echo $row;
			}
			
		  }
		echo "</table>";
		}
		}
		?>
	  </div>
	</div>
  </body>
</html>