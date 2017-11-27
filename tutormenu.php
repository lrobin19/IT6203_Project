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
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
  
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
			  $_SESSION['pmenu']='tutor';
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
		  <li>Tutor</li>
		</ol>
	  </div>
	  <br><br>
	  <div class="center">
	<form action="taskchange.php" method="post">
	      
		  <h1><b>Welcome!</b></h1>
		  <h3>Transfer Tasks</h3>
		  <select id="task" name="task"> 
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT taskid FROM task");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='". $row['taskid'] . "'>" . $row['taskid'] . "</option>";
			}
		?>
		  </select>
		  
		  <br></br>
		  
		  <h3>Select new user for task</h3>
		  <select id="username" name="username">
		<?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT username FROM profile where type='tutor'");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='". $row['username'] . "'>" . $row['username'] . "</option>";
			}
		?>
			</select>
			
			<br></br>
			<input type="submit" value="Submit">
			</form>
			
			<form action="taskcompleted.php" method="post">
			
			<h1><b>Completed Tasks</b></h1>
			<h3>Select task that has been completed.</h3>
			 <select id="task" name="task"/>
			 <?php 
			$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
			$sql = mysqli_query($conn, "SELECT taskid FROM task");
				while ($row = $sql->fetch_assoc()){
				echo "<option value='". $row['taskid'] . "'>" . $row['taskid'] . "</option>";
			}
		?>
		</select>
		  
		  <br><br>
		  <input type="submit" value="Submit">
		  </form>
		  
		  <h1><b>View Tasks</b></h1>
		  <?php
		   $conn = mysqli_connect("localhost", "proj_user", "my*password", "lrobinson")
        or die("Cannot connect to database:") .
           mysqli_connect_error($conn);
   
		$sql = "SELECT * FROM task";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
            echo "<div align='center'><table border='2' height='100'><tr><th>TaskID</th><th>KSUID</th><th>Service</th>
					<th>User</th><th>Description</th><th>Deadline</th></tr>";
 
            while($row = $result->fetch_assoc()) {
                
			echo "<tr><td>";
			echo $row['taskID'];
			echo "</td><td>";
			echo $row["ksuid"];
			echo "</td><td>"; 
			echo $row["service"];
			echo "</td><td>"; 
			echo $row["user"];
			echo "</td><td>";   
			echo $row["description"];
			echo "</td><td>"; 
			echo $row["deadline"];
			echo "</td></tr>";  
            }
            echo "</table></div>";
		}
	 ?>
	  </div>
	</div>
	</div>
</body>
</html>