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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript">
	  $(document).ready(function(){
		$('#ifhide').fadeOut('slow');
		$('#tptype').change(function(){
			$('#ifhide').fadeIn('slow');
		});
				$('#sptype').change(function(){
			$('#ifhide').fadeOut('slow');
		});
	});
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
	  
	  function validate_radio(input_name, error_div) { 
		var radiov='';
	    var var_error_div = document.getElementById(error_div);
		var elements = document.getElementsByName("ptype");
		for (i = 0; i < elements.length; i++)
		  {
			if (elements[i].checked){
			  var radiov = elements[i].value;
			}
		  }
		if (radiov == ''){
		  var_error_div.innerHTML ="Please select your profile type.";
		  return false;
		}else{
		  var_error_div.innerHTML ="";
		  return radiov;
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
		var ptype = document.getElementById(ptype);
	    var ksuid = validate_number('ksuid','netid_err');
	    var fname = validate_string('fname','fname_err');
	    var lname = validate_string('lname','lname_err');
	    var email = validate_string('email','email_err');
		var pwd = validate_pw('pword1', 'pword2','pwd_err');
		var ptype = validate_radio('ptype', 'ptype_err');
		if (ptype == 'tutor'){
		  var service = validate_checkbox('service[]', 'serv_err');
		  var shift = validate_checkbox('shift[]', 'shift_err');
	      if (service &&shift && ksuid && fname && lname && email && pwd){
			return true;
		  }else{
			return false;
	      }
		}else{
		  if (ksuid && fname && lname && email && pwd){
			return true;
		  }else{
			return false;
	      }
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
			<?php
			$_SESSION['pmenu']='unauth';
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
		<input calss="text" type="text" id="fname" name="fname" maxlength='32'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){echo "value='".$_SESSION["fname"]."'";}?>/>
			<br><br>
	    <em id="fname_err"></em>
		<br>
		<div align='left'><label class="flabel">Last Name</label></div>
		<input type="text" id="lname" name="lname" maxlength='32' <?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["lname"]."'";}?>/>
			<br><br>
	    <em id="lname_err"></em>
		<br>
		<div align='left'><label class="flabel"> Email Address</label></div>
		<input type="email" id="email" name="email" maxlength='48'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["email"]."'";}?>/>
			<br><br>
	    <em id="email_err"></em>
		<br>
		<div align='left'><label class="flabel">Username</label></div>
		<input type="text" id="uname" name="uname" maxlength='16'<?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			echo "value='".$_SESSION["uname"]."'";}?>/>
			<br><br>
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
		<br><br>
		<div align='left'><label class="flabel">Add Additional Service?</label></div>
	  <input type="text" id="addserv" name="addserv" maxlength='16'/>
	  <br><br>
	  <div align='left'><label class="flabel">I am a:</label></div>
		<div align='left'><input type="radio" id="sptype" value="student" name="ptype" <?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"] && $_SESSION["ptype"] == "student"){echo "checked";}?>>Student</div>
		<div align='left'><input type="radio" id="tptype" value="tutor" name="ptype" <?php if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"] && $_SESSION["ptype"] == "tutor"){echo "checked";}?>>Tutor</div>
	    <em id="ptype_err"></em>
		<br><br>
		<div class='ifhide' id='ifhide'>
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
	  <br><br>
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
		</div>
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