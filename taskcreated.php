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
			<a href="index.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Home</span></a>
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="tutormenu.php" title="tutor_menu"><i class="fa fa-lg fa-fw fa-home"></i><span class="menu-item-parent">Tutor Menu</span></a>
			<a href="tasks.php" title="create_task"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Create Task</span></a>
			<a href="search.php" title="search"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
			<a href="logout.php" title="logout"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>
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
		<h1>Confirmation</h1>
		<?php
		  $msg='';
		  $conn = mysqli_connect("localhost", "proj_user", "my*password", "lrobinson");
          mysqli_connect_error($conn);

		  $ksu = $_POST["ksu"];
		  $service = $_POST["service"];
		  $user = $_POST["user"];
		  $description = $_POST["description"];
		  $deadline = $_POST["deadline"];

		  $sql = mysqli_prepare ($conn, "Insert into task (ksuid, service, user, description, deadline)	Values (?,?,?,?,?)") or die("Error: ". mysqli_error($conn));
		  mysqli_stmt_bind_param ($sql, 'sssss', $ksu, $service, $user, $description, $deadline);
    	  mysqli_stmt_execute($sql) or die("Error. Couldn't insert into the table." . mysqli_error($conn));
		  $id = mysqli_insert_id($conn);
   		  if ($sql) {
			echo "Task Created<br><br>";
			echo ("This task is entry #") . $id;
		  } else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		  }
		  mysqli_stmt_close($sql);
		  $conn->close();
?>
		  
	</div>
	</div>
</body>
</html>