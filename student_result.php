<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title>Student Page</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
		<style>
		table, th, td {
			border: 1px solid black;
		}
		</style>
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
	  	<?php
		  echo "<span>Welcome, " . $_SESSION["fname"]."</span>";
		?>
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<?php
			  $_SESSION['pmenu']='student';
			  include "aside.php"; 
			?>
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
	  <center>
	  <h2><p class="ex1">Search Results<br><br>
	  <div class="center">
<?php
$servername = "localhost";
$username = "proj_user";
$password = "my*password";
$dbname = "lrobinson";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT ksuid, first_name, last_name, email, availability FROM profile";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>KSU ID</th><th>Name</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ksuid"]. "</td><td>" . $row["first_name"]. " " . $row["last_name"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?> 

</body>
</html>