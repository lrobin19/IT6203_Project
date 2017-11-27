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
			<?php
			if (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
			  $ptype = $_POST["ptype"];
			  if($ptype == 'tutor'){
				$_SESSION['pmenu']='tutor';
			  }else{
				$_SESSION['pmenu']='student';
			  }
			}
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
  <?php
  $_SESSION["errmsg"] = "";
  $msg="";
  $msg1="";
  if(!empty($_POST["ksuid"]) && !empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["email"]) && !empty($_POST['uname']) && !empty($_POST['pword1']) && !empty($_POST['ptype'])){
	$ksuid = $_POST["ksuid"];
	$_SESSION["ksuid"] = $ksuid;
	$fname = $_POST["fname"];
	$_SESSION["fname"] = $fname;
	$lname = $_POST["lname"];
	$_SESSION["lname"] = $lname;
	$email = $_POST["email"];
	$_SESSION["email"] = $email;
	$uname = $_POST["uname"];
	$_SESSION["uname"] = $uname;
	$password = $_POST["pword1"];
	$ptype = $_POST["ptype"];
	$_SESSION["ptype"] = $ptype;
	$to = $email;
	$subject = "Registration";
	$notification = $_POST['sendemail'];
	if ($notification=='no'){
	  $notification=0;
	}else{
	  $notification=1;
	}
	if ($ptype == 'tutor'){
	  $service = implode(",", $_POST['service']);
      $theshift="";
	  $shift = implode(",", $_POST['shift']);
	  $shiftarr = explode(",", $shift);
	  foreach ($shiftarr as $shiftt){
		if (strpos($shiftt, 'mon') !== false){
			$theshift .= "<p>Monday ";
		} elseif (strpos($shiftt, 'tues') !== false){
			$theshift .= "<p>Tuesday ";
		} elseif (strpos($shiftt, 'wed') !== false){
			$theshift .= "<p>Wednesday ";
		} elseif (strpos($shiftt, 'thurs') !== false){
			$theshift .= "<p>Thursday ";
		} elseif (strpos($shiftt, 'fri') !== false){
			$theshift .= "<p>Friday ";
		} elseif (strpos($shiftt, 'sat') !== false){
			$theshift .= "<p>Saturday ";
		} elseif (strpos($shiftt, 'sun') !== false){
			$theshift .= "<p>Sunday ";
		}
		if (strpos($shiftt, 'morn') !== false){
			$theshift .= "Morning</p>";
		} elseif (strpos($shiftt, 'aft') !== false){
			$theshift .= "Afternoon</p>";
		} elseif (strpos($shiftt, 'eve') !== false){
			$theshift .= "Evening</p>";
		}
	  }
	}
	$timestamp = date("Y-m-d H:i:s");
	$addserv = $_POST["addserv"];
	//Connect to database
	$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
	// connect to ldap server
	$ldapconn = ldap_connect("localhost") or die("Could not connect to LDAP server.");
	//Test both database and ldap connections
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
	  if (!isset($_SESSION["pupdate"])){
		$query1 = mysqli_prepare($conn, "insert into profile (ksuid, first_name, last_name, email, post_date, notification, username, type) values(?,?,?,?,?,?,?,?);");
		mysqli_stmt_bind_param ($query1, "issssiss", $ksuid, $fname,$lname,$email, $timestamp, $notification, $uname, $ptype);
		mysqli_stmt_execute($query1);
		mysqli_stmt_store_result($query1);
		if ($addserv != ''){
		  $shortdesc=substr($addserv, 0, 3);
		  $query2 = mysqli_prepare($conn, "insert into services (description, shortdesc) values(?,?);");
		  mysqli_stmt_bind_param ($query2, "ss", $addserv, $shortdesc);
		  mysqli_stmt_execute($query2);
		  mysqli_stmt_store_result($query2);
		}
		if ($ptype == 'tutor'){
		  $query3 = mysqli_prepare($conn, "update profile set availability=? where username=?;");
		  mysqli_stmt_bind_param ($query3, "ss", $shift, $_SESSION["username"]);
		  mysqli_stmt_execute($query3);
		  mysqli_stmt_store_result($query3);
		  $servshort = explode(',', $service);
		  foreach($servshort as $sid){
			$query5 = mysqli_prepare($conn, "SELECT servID FROM services where shortdesc= ?");
			mysqli_stmt_bind_param ($query5, "s", $sid);
			mysqli_stmt_execute($query5);
			mysqli_stmt_store_result($query5);
			mysqli_stmt_bind_result($query5, $servID);
			while (mysqli_stmt_fetch($query5)) {
			  $query6 = mysqli_prepare($conn, "insert into servicesProvided (userid, servID) values(?,?);");
			  mysqli_stmt_bind_param ($query6, "ss", $ksuid, $servID);
			  mysqli_stmt_execute($query6);
			  mysqli_stmt_store_result($query6);
		    }
		  }
		}
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
		if($_POST['sendemail'] == 'yes'){
		  echo "<p>Email Confirmation: Yes </p>";
		  if (mail($to, $subject, $msg)) {
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
	  }elseif (isset ($_SESSION["pupdate"]) && $_SESSION["pupdate"]){
		$query6 = mysqli_prepare($conn, "update profile set ksuid=?, first_name=?, last_name=?, email=?, post_date=?, notification=?, username=?, type=? where ksuid=?");
		if ( !$query6 ) {
		  die('mysqli error: '.mysqli_error($conn));
		}
		mysqli_stmt_bind_param ($query6, "issssissi", $ksuid, $fname,$lname,$email, $timestamp, $notification, $uname, $ptype, $_SESSION['ksuid']);
		mysqli_stmt_execute($query6);
		mysqli_stmt_store_result($query6);
		if ($addserv != ''){
		  $shortdesc=substr(addserv, 0, 3);
		  $query7 = mysqli_prepare($conn, "insert into services (description, shortdesc) values(?,?);");
		  mysqli_stmt_bind_param ($query7, "ss", $addserv, $shortdesc);
		  mysqli_stmt_execute($query7);
		  mysqli_stmt_store_result($query7);
		}
		if ($ptype == 'tutor'){
		  $query8 = mysqli_prepare($conn, "update profile set availability=? where username=?;");
		  mysqli_stmt_bind_param ($query8, "ss", $shift, $_SESSION["username"]);
		  mysqli_stmt_execute($query8);
		  mysqli_stmt_store_result($query8);
		  $servshort = explode(',', $service);
		  foreach($servshort as $sid){
			$query9 = mysqli_prepare($conn, "SELECT servID FROM services where shortdesc= ?");
			mysqli_stmt_bind_param ($query9, "s", $sid);
			mysqli_stmt_execute($query9);
			mysqli_stmt_store_result($query9);
			mysqli_stmt_bind_result($query9, $servID);
			while (mysqli_stmt_fetch($query9)) {
			  $query10 = mysqli_prepare($conn, "insert into servicesProvided (userid, servID) values(?,?);");
			  mysqli_stmt_bind_param ($query10, "ss", $ksuid, $servID);
			  mysqli_stmt_execute($query10);
			  mysqli_stmt_store_result($query10);
		    }
		  }
		}
	    mysqli_close($conn);
		// use OpenDJ version V3 protocol
		if (!ldap_set_option($ldapconn,LDAP_OPT_PROTOCOL_VERSION,3)){
		  $_SESSION["errmsg"] &="<p>Failed to set version to protocol 3</p>";
		  echo "<p>Failed to set version to protocol 3</p>";
		}
		// credentials to verify
		$ldaprdn = "cn=manager,dc=designstudio1,dc=com";
		$ldapupd = "cn=".$_SESSION["uname"].",dc=designstudio1,dc=com";
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
		  ldap_mod_replace($ldapconn, $ldapupd, $ldaprecord);
		  //close ldap connection VERY IMPORTANT
		  ldap_close($ldapconn);
		} else {
		  echo ldap_error($ldapconn);
		}
		if($_POST['sendemail'] == 'yes'){
		  echo "<p>Email Confirmation: Yes </p>";
		  if (mail($to, $subject, $msg)) {
		    echo("<p>Confirmation email message successfully sent!</p>");
		  } else {
		    echo("<p>Confirmation email message delivery failed...</p>");
	  	  }
	    }else {
		  echo "<p>Email Confirmation: No </p>";
		}
		$msg1 = $msg1 . "Your profile has been updated.<br>";
		$msg1 = $msg1 . "<b>First name:</b> " . $fname . "<br>";
		$msg1 = $msg1 . "<b>Last name:</b> " . $lname . "<br>";
		$msg1 = $msg1 . "<b>Email:</b> " . $email . "<br>";
		$msg1 = $msg1 . "<b>Username:</b> " . $uname . "<br>";
		echo $msg1;
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
	  if(empty($_POST["ptype"])){
		  $_SESSION["errmsg"] &= "<b>Profile type is required.<br>";
		  echo "<b>Profile type is required.<br>";
	  }
	}
  ?>
  </body>
</html>