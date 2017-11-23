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
	<title> KSU Task List </title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
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
	  
	  	function validate_pw(pw1, pw2, error_div){
        var var_input1 = document.getElementById(pw1);
		var var_input2 = document.getElementById(pw2);
        var var_error_div = document.getElementById(error_div);
		var re=/^[\w!@#$%]+$/;
		var re_num = /[0-9]/;
		var re_llet = /[a-z]/;
		var re_ulet = /[A-Z]/;
		var re_spe = /[!@#$%_]/;
        if(var_input1.value == null || var_input1.value == null) {
          var_error_div.innerHTML="This field is required";
		  return false;
        } else if(var_input1.value =="" || var_input2.value =="") {
          var_error_div.innerHTML="Both password fields are required.";
		  return false;
		} else if(var_input1.value != var_input2.value) {
          var_error_div.innerHTML ="Passwords do not match. Please try again.";
		  return false;
		} else if(var_input1.value.length < 6) {
          var_error_div.innerHTML ="Your password must be at least 6 characters.";
		  return false;
		} else if(!re.test(var_input1.value)) {
          var_error_div.innerHTML ="Password can only contain numbers, letters and the following characters: !@#$%_. Please try again.";
		  return false;
		} else if(!re_num.test(var_input1.value)) {
          var_error_div.innerHTML="Password must contain at least one number (0-9).";
          return false;
		} else if(!re_llet.test(var_input1.value)) {
          var_error_div.innerHTML="Password must contain at least one lowercase letter (a-z).";
          return false;
		} else if(!re_ulet.test(var_input1.value)) {
          var_error_div.innerHTML="Password must contain at least one uppercase letter (A-Z).";
		  return false;
		} else if(!re_spe.test(var_input1.value)) {
          var_error_div.innerHTML="Password must contain at least one character in (!@#$%_).";
		  return false;
        }else  {
          var_error_div.innerHTML ="";
		  return true;
		  }
      }
	  
	  function validate_form(){
	    var ksuid = validate_number('ksuid','netid_err');
	    var fname = validate_string('fname','fname_err');
	    var lname = validate_string('lname','lname_err');
	    var email = validate_string('email','email_err');
		var pwd = validate_pw('pword1', 'pword2','pwd_err');
	    if (ksuid && fname && lname && email && pwd){
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
			if (isset($_GET["update"])){
				echo '<span>Welcome, ' . $_SESSION["fname"].'</span>';
			}
			?>		  
		</span>
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="search.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
			<?php
			if (isset($_GET["update"])){
				echo '<a href="logout.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>';
			}
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
	  <?php
	  if (isset($_GET["update"])){
		$_SESSION["pupdate"]='true';
	  }
	  ?>
	  <div class="center">
	  <h1><b><u>Register your Services</u></b></h1>
	  <br><br>
	  <form method="post" action="register_result.php">
	  		<div class="err">
		  <?php
			echo $_SESSION["errmsg"] ."<br>";
			?>
		</div>
	    <div align='left'><label class="flabel"> KSU NetID</label></div>
		<input type="text" id="ksuid" name="ksuid" <?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["ksuid"]."'";
			}?>/><br><br>
		<em id="netid_err"></em>
		<br>
		<div align='left'><label class="flabel">First Name</label></div>
		<input calss="text" type="text" id="fname" name="fname" maxlength='32'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["fname"]."'";
			}?>/><br><br>
	    <em id="fname_err"></em>
		<br>
		<div align='left'><label class="flabel">Last Name</label></div>
		<input type="text" id="lname" name="lname" maxlength='32' <?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["lname"]."'";
			}?>/><br><br>
	    <em id="lname_err"></em>
		<br>
		<div align='left'><label class="flabel"> Email Address</label></div>
		<input type="email" id="email" name="email" maxlength='48'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["email"]."'";
			}?>/><br><br>
	    <em id="email_err"></em>
		<br>
		<div align='left'><label class="flabel">Username</label></div>
		<input type="text" id="uname" name="uname" maxlength='16'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["uname"]."'";
			}?>/><br><br>
	    <em id="uname_err"></em>
		<br>
		<div align='left'><label class="flabel">Enter password</label></div>
		<input type="password" id="pword1"  name="pword1" maxlength='16' required><br>
	    <meter max="4" id="password-strength-meter"></meter>
		<p id="password-strength-text"></p>
		<br>
		<div align='left'><label class="flabel">Re-enter Password</label></div>
		<input type="password" id="pword2" name="pword2" maxlength='16' required><br><br>
	    <em id="pwd_err"></em>
		<br>
		<div align='left'><label class="flabel">Send email confirmation</label></div>
		<input type='hidden' value='no' name='sendemail'>
		<div align='left'><input type="checkbox" id="sendemail" value="yes" name="sendemail"></div>
		<br><br>
	  <input type="submit" value="Submit" onclick="return(validate_form()) ">
	  </div>
	</form>
	</div>
	</div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
  	<script type="text/javascript">
  	  var strength = {
  0: "Worst",
  1: "Bad",
  2: "Weak",
  3: "Good",
  4: "Strong"
}
var password = document.getElementById('pword1');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function() {
  var val = password.value;
  var result = zxcvbn(val);

  // Update the password strength meter
  meter.value = result.score;

  // Update the text indicator
  if (val !== "") {
    text.innerHTML = "Strength: " + strength[result.score]; 
  } else {
    text.innerHTML = "";
  }
});
</script>
</body>
</html>