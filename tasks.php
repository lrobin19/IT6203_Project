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
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  
  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker();
  });
  </script>
  
	<script type="text/javascript">
      //This function takes two parameters: input to validate and EM name to display error message
      function validate_number(input_name, error_div){
		var var_input = document.getElementById(input_name);
		var var_error_div = document.getElementById(error_div);
		if(var_input.value == null) {
          var_error_div.innerHTML="element does not exist";
		  return false;
        } else if(var_input.value =="") {
          var_error_div.innerHTML="Please enter your 9 digit KSU NetID";
		  return false;
        } else if(isNaN(var_input.value)) {
          var_error_div.innerHTML ="Please enter numbers only.";
		  return false;
        } else if(var_input.value.length < 9) {
          var_error_div.innerHTML ="Please enter 9 digits.";
		  return false;
        }else  {
          var_error_div.innerHTML ="";
		  return true;
		  }
      }
	  function validate_string(input_name, error_div){
        var var_input = document.getElementById(input_name);
        var var_error_div = document.getElementById(error_div);
        if(var_input.value == null) {
          var_error_div.innerHTML="element does not exist";
		  return false;
        } else if(var_input.value =="") {
          var_error_div.innerHTML="This field is required.";
		  return false;
        } else if(var_input.value.length < 3) {
          var_error_div.innerHTML ="Please enter at least 3 characters.";
		  return false;
        }else  {
          var_error_div.innerHTML ="";
		  return true;
		  }
      }
	  function validate_checkbox(groupname, error_div) { 
	    var var_error_div = document.getElementById(error_div);
	    var checkboxes = document.querySelectorAll("input[name='"+groupname+"']");
		var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
		if (checkedOne){
		  var_error_div.innerHTML ="";
		}else{
		  var_error_div.innerHTML ="Please select at least one.";
		}
		return checkedOne;
	  }
	  
	  function validate_form(){
	    var ksuid = validate_number('ksuid','netid_err');
	    var fname = validate_string('fname','fname_err');
	    var lname = validate_string('lname','lname_err');
	    var email = validate_string('email','email_err');
	    var service = validate_checkbox('service[]', 'serv_err');
	    var shift = validate_checkbox('shift[]', 'shift_err');
	    if (ksuid && fname && lname && email && service &&shift){
		  return true;
		}else{
		  return false;
	    }
	  } 

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
	  	  <?php
			echo "<span>Welcome, " . $_SESSION["fname"]."</span>";
			?>
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="search.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
			<a href="logout.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>'
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
	  <div class="center">
<form action="taskcreated.php" method="post">
	      
		  <h1><b>KSUID</b></h1>
		  <h3>Choose the KSUID of the requesting user</h3>
		  <select id="ksu" name="ksu"> 
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT ksuid FROM profile");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='". $row['ksuid'] ."'>" . $row['ksuid'] . "</option>";
			}
		?>
		  </select>
		  
		  <h1><b>Select Service</b></h1>
		  <h3>Choose the service required</h3>
		  <select id="service" name="service"> 
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT description FROM services");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='".$row['description']."'>" . $row['description'] . "</option>";
			}
		?>
		  </select>
		  
		  <br></br>
		  
		  <h1>Select User</h1>
		  <h3>Assign a user to a task</h3>
		  <select id="user" name="user">
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT first_name FROM profile where type='tutor'");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='".$row['first_name']."'>" . $row['first_name'] . "</option>";
			}
		?>
			</select>
			
			<br></br>
			
			<h1><b>Task Deadline</b> </h1>
			 <input id="datepicker" name="deadline"/>
		  
		  <h1><b>Task Description</b></h1>
		  <h3>Select a description that best fits task</h3>
		  <select id="description" name="description"> 
			<option value="php1">Light PHP Tutoring</option>
			<option value="php2">Heavy PHP Tutoring</option>
			<option value="sql1">Light SQL Tutoring</option>
			<option value="sql2">Heavy SQL Tutoring</option>
			<option value="cpp1">Light C++ Tutoring</option>
			<option value="cpp2">Heavy C++ Tutoring</option>
			<option value="java1">Light Java Tutoring</option>
			<option value="java2">Heavy Java Tutoring</option>
			<option value="repair1">Light Computer Repair</option>
			<option value="repair2">Heavy Computer Repair</option>
		  </select>
	  <br>
	  <input type="submit" value="Submit" onclick="taskcreated.php">
	  </div>
	</form>
	</div>
	</div>
</body>
</html>