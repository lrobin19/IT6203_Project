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
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{  
 $("#serv").change(function()
 {    
  var id = $(this).find(":selected").val();
  var dataString = 'action='+ id;
  $.ajax
  ({
   url: 'getrecs.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#display").html(r);
   } 
  });
 })
 // code to get all records from table via select box
});
</script>
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
		  echo "Welcome," . $_SESSION["fname"];
			?>		  
		</span>
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
		  <li>Search</li>
		</ol>
	  </div>
	  <br><br>
	  <div class="center">
<td  id="main"><!-- main part begins -->

   <p>In this page, you can search for a particular service in order to receive the proper help that you desire. Our goal is to always provide the best opportunity to succeed in your educational studies through proper outside assistance. Our peer tutorials are vetted and assigned to place students in the best position to excel.</p> 
   
   <p>Additionally, as a registered user, you can <a href= "register.php?update=true">modify</a> your profile to reflect your personal changes.</p>
   
   <h1><b><u>Search for Services</u></b></h1>
		<form action="student.php" method="post">
		<select id="serv" name="serv"> 
		<option selected="true" disabled="disabled">Select One</option>
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "select shortdesc, description from services");
			while ($row = $sql->fetch_assoc()){
			  echo "<option value='". $row['shortdesc'] ."'>" . $row['description'] . "</option>";
			}
		    mysqli_close($conn);
   ?>
		  <br><br>
		  <input type="submit" value="Search">
		</form>
		<br><br><br>
    <div class="" id="display">
       <!-- Records will be displayed here -->
    </div>
	  </div>
	</div>
  </body>
</html>


