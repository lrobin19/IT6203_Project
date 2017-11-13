<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en-us">
  <head>
	<meta charset="utf-8">
    <title>Submission Results</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
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
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<a href="index.html" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Home</span></a>
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
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
  $_SESSION["errmsg"] = "";
  $msg="";
  if(!empty($_POST["ksuid"]) && !empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["email"]) && !empty($_POST['uname']) && !empty($_POST['pword1'])){
	  $ksuid = $_POST["ksuid"];
	  $fname = $_POST["fname"];
	  $lname = $_POST["lname"];
	  $email = $_POST["email"];
	  $uname = $_POST["uname"];
	  $password = $_POST["pword1"];
	  $to = $email;
	  $subject = "Registration";
	  $notification = $_POST['sendemail'];
	  $timestamp = date("Y-m-d H:i:s");
	  if($_POST['sendemail'] == 'yes'){
		echo "<p>Email Confirmation: Yes </p>";
	    if (mail($to, $subject, $body)) {
		  echo("<p>Confirmation email message successfully sent!</p>");
	    } else {
		  echo("<p>Confirmation email message delivery failed...</p>");
		}
	    }else {
		  echo "<p>Email Confirmation: No </p>";
	  }
	  $msg = $msg . "Your registration has been received.<br>";
	  $msg = $msg . "<b>First name:</b> " . $fname . "<br>";
	  $msg = $msg . "<b>Last name:</b> " . $lname . "<br>";
	  $msg = $msg . "<b>Email:</b> " . $email . "<br>";
  	  $msg = $msg . "<b>Username:</b> " . $uname . "<br>";
	  echo $msg;
	  //Insert registration/profile information into database
	  //Connect to database
	  $conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
	  // connect to ldap server
	  $ldapconn = ldap_connect("localhost") or die("Could not connect to LDAP server.");
	  //Test bother database and ldap connections
	  if (! $ldapconn || mysqli_connect_errno($conn)){
		if (! $ldapconn){
		  $_SESSION["errmsg"]&="Could not connect to LDAP server.";
		  echo "Could not connect to LDAP server.";
		}
		if ( mysqli_connect_errno($conn)){
		  $_SESSION["errmsg"]&="Cannot connect to database: " . mysqli_connect_error();
		}
		//If both connections verified, insert information into database and ldap
	  }else{
		$query1 = mysqli_prepare($conn, "insert into profile (ksuid, first_name, last_name, email, post_date, notification, username) values(?,?,?,?,?,?,?);");
		mysqli_stmt_bind_param ($query1, "issssis", $ksuid, $fname,$lname,$email, $timestamp, $notification, $uname);
		mysqli_stmt_execute($query1);
		mysqli_stmt_store_result($query1);
	    mysqli_close($conn);
		// use OpenDJ version V3 protocol
		if (!ldap_set_option($ldapconn,LDAP_OPT_PROTOCOL_VERSION,3)){
		  $_SESSION["errmsg"] &="<p>Failed to set version to protocol 3</p>";
		  echo "<p>Failed to set version to protocol 3</p>";
		}
		// credentials to verify
		$ldaprdn = "cn=manager,dc=designstudio1,dc=com";
		$ldappass = "my*password"; // associated password
		// binding to ldap server
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
        // verify binding
		if ($ldapbind) {
		  $ldaprecord['givenName'] = $fname;
		  $ldaprecord['sn'] = $lname;
		  $ldaprecord['cn'] = $uname;
		  $ldaprecord['objectclass'][0] = "top";
		  $ldaprecord['objectclass'][1] = "person";
		  $ldaprecord['objectclass'][2] = "inetOrgPerson";
		  $ldaprecord['userPassword'] = $password;
		  $ldaprecord['mail'] = $email;
		  ldap_add($ldapconn, "cn=" . $uname . ",dc=designstudio1,dc=com", $ldaprecord);
		  //close ldap connection VERY IMPORTANT
		  ldap_close($ldapconn);
		} else {
			echo "LDAP bind unsuccessful.";
	  }
	  }
	} else {
	  if (empty($_POST["ksuid"])){
		$_SESSION["errmsg"] &= "<b>KSU ID is required<br>";
		echo "<b>KSU ID is required.<br>";
	  }
	  if(empty($_POST["fname"])){
		  $_SESSION["errmsg"] &= "<b>First name is required.<br>";
		  echo "<b>First name is required.<br>";
	  }
	  if(empty($_POST["lname"])){
		 $_SESSION["errmsg"] &=  "<b>Last name is required.<br>";
		 echo "<b>Last name is required.<br>";
	  }
	  if(empty($_POST["email"])){
		  $_SESSION["errmsg"] &= "<b>Email address is required.<br>";
		  echo "<b>Email is required.<br>";
	  }
	  if(empty($_POST["uname"])){
		  $_SESSION["errmsg"] &= "<b>Username is required.<br>";
		  echo "<b>Username is required.<br>";
	  }
	  if(empty($_POST["pword1"])){
		  $_SESSION["errmsg"] &= "<b>Password is required.<br>";
		  echo "<b>Passowrd is required.<br>";
	  }
	  //header("Location: register.php");
    }
  ?>
  </body>
</html>