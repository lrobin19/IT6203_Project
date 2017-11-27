<?php
session_start();
$_SESSION["errmsg"]='';
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
	  <div class="center">
<body>
  <?php
  	$service = $_POST["service"];
    $user = $_POST["user"];
	$description = $_POST["description"];
	$deadline = $_POST["deadline"];
    $msg = ("Thank you. The selected user will complete the task by " . $deadline);
    $conn = mysqli_connect("localhost", "proj_user",
       "my*password", "lrobinson");
	   
	   echo $msg;

  $data = mysqli_prepare($conn,
    "INSERT INTO task(service, user, description, deadline) VALUES (?, ?, ?, ?);");
		
		mysqli_stmt_bind_param ($data, 'ssss', $service, $user, $description, $deadline);
		
		mysqli_stmt_execute($data)
       or die("Error. Couldn't insert into the table." 
				. mysqli_error($conn));
 
  ?>
</body>
</html>