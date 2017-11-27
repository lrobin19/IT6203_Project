<?php
session_start();
if (!isset($_SESSION["errmsg"])){
$_SESSION["errmsg"]="";
}
?>
<!DOCTYPE html>
<html lang="en-us">
  <head>
	<meta charset="utf-8">
    <title>Welcome</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
		<script type="text/javascript">
      //This function takes two parameters: input to validate and EM name to display error message
	  function validate_string(input_name, error_div){
        var var_input = document.getElementById(input_name);
        var var_error_div = document.getElementById(error_div);
        if(var_input.value == null) {
          var_error_div.innerHTML="Element does not exist";
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
	  function validate_form(){
	    var uname = validate_string('uname','uname_err');
	    var pword = validate_string('pword','pword_err');
	    if (uname && pword){
		  return true;
		}else{
		  return false;
	    }
	  } 
    </script>
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
	  <nav>
		<ul>
		  <li class="">
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="search.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Login</span></a>
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
	  <h2><p class="ex1">Welcome to the KSU Task List. On this site you can either register your services or search for someone who offers these services.<br><br>
	  <div class="center">
	    <form method="post" action="login_result.php">
		<div class="err">
		  <?php
			echo $_SESSION["errmsg"] ."<br>";
			?>
		</div>
		 <label for="username">Username
			<input type="text" id="uname" name="uname" maxlength='32'><br>
			<em id="uname_err"></em>
		  </label>
		  <br>
		  <label for="password">Password
			<input type="password" id="pword" name="pword" maxlength='32'><br>
			<em id="pword_err"></em>
		  </label>
		  <br>
		  <br>
		  <label class="input">
	      <button type="submit" value="Submit" onclick="return(validate_form()) "> Submit</button>OR <a href='register.php'>Register</a>
		  </label>
		</form>
	</div>
</p></h2>
</center>
<br><br>
<div class="footer">
  <p>Group 3: Leo Iacono, Lauren Robinson, Roy Sosby, Holbert White</p>
</div>
</body>
</html>