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
	<script type="text/javascript">
      //This function takes two parameters: input to validate and EM name to display error message
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
	    var service = validate_checkbox('service[]', 'serv_err');
	    var shift = validate_checkbox('shift[]', 'shift_err');
	    if (service &&shift){
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
	  <div class="center">
	  <h1><b><u>Register your Services</u></b></h1>
	  <br><br>
	  <form method="post" action="register_result.php">
		<div align="center">
		<h2>Services Offered</h2> 
	  <?php 
		$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
		if (mysqli_connect_errno($conn)){
          echo 'Cannot connect to database: ' . mysqli_connect_error();
		}else{
		  $query = mysqli_prepare($conn, "select * from services;");
		  mysqli_stmt_execute($query);
		  mysqli_stmt_store_result($query);
		  if( mysqli_stmt_num_rows($query) == 0){
             echo "No entries found";
          }else{
            mysqli_stmt_bind_result($query, $servID, $description, $shortdesc);
            while (mysqli_stmt_fetch($query)) {
			  ?>
			  <input type="checkbox" name="service[]" value="<?php echo $shortdesc;?>" id="<?php echo $shortdesc;?>" /><?php echo $description;?><br>
		      <?php
			}
		    mysqli_close($conn);
		  }
		}
   ?>
	  <br>
	  <em id="serv_err"></em><br>
	  <h1>Availability</h1>
	  <table class="center">
	    <tr>
		  <th>Shift</th>
		  <th>M</th>
		  <th>T</th>
		  <th>W</th>
		  <th>R</th>
		  <th>F</th>
		  <th>Sa</th>
		  <th>Su</th>
		</tr>
		<tr>
			<td>Morning (8am-12pm)</td>
			<td><input type="checkbox" name='shift[]' id="monmorn" value="monmorn"></td>
			<td><input type="checkbox" name='shift[]' id="tuesmorn" value="tuesmorn"></td>
			<td><input type="checkbox" name='shift[]' id="wedmorn" value="wedmorn"></td>
			<td><input type="checkbox" name='shift[]' id="thursmorn" value="thursmorn"></td>
			<td><input type="checkbox" name='shift[]' id="frimorn" value="frimorn"></td>
			<td><input type="checkbox" name='shift[]' id="satmorn" value="satmorn"></td>
			<td><input type="checkbox" name='shift[]' id="sunmorn" value="sunmorn"></td>
		  </tr>
		  <tr>
			<td> Afternoon (12pm-4pm)</td>
			<td><input type="checkbox" name='shift[]' id="monaft" value="monaft"></td>
			<td><input type="checkbox" name='shift[]' id="tuesaft" value="tuesaft"></td>
			<td><input type="checkbox" name='shift[]' id="wedaft" value="wedaft"></td>
			<td><input type="checkbox" name='shift[]' id="thursaft" value="thursaft"></td>
			<td><input type="checkbox" name='shift[]' id="friaft" value="friaft"></td>
			<td><input type="checkbox" name='shift[]' id="sataft" value="sataft"></td>
			<td><input type="checkbox" name='shift[]' id="sunaft" value="sunaft"></td>
		  </tr>
		  <tr>
			<td>Evening (4pm-8pm)</td>
			<td><input type="checkbox" name='shift[]' id="moneve" value="moneve"></td>
			<td><input type="checkbox" name='shift[]' id="tueseve" value="tueseve"></td>
			<td><input type="checkbox" name='shift[]' id="wedeve" value="wedeve"></td>
			<td><input type="checkbox" name='shift[]' id="thurseve" value="thurseve"></td>
			<td><input type="checkbox" name='shift[]' id="frieve" value="frieve"></td>
			<td><input type="checkbox" name='shift[]' id="sateve" value="sateve"></td>
			<td><input type="checkbox" name='shift[]' id="suneve" value="suneve"></td>
		  </tr>
		</table>
	  <br>
	  <em id="shift_err"></em>
	  <br>
	  <input type="submit" value="Submit" onclick="return(validate_form()) ">
	  </div>
	</form>
	</div>
	</div>
});
</script>
</body>
</html>