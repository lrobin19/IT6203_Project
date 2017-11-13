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
<?php
  $msg = "";
  if(!empty($_POST["uname"]) && !empty($_POST["pword"])){
	$username = $_POST["uname"];
	$password = $_POST["pword"];
	
	// connect to ldap server
	$ldapconn = ldap_connect("localhost") or die("Could not connect to LDAP server.");

	// use OpenDJ version V3 protocol
	if (!ldap_set_option($ldapconn,LDAP_OPT_PROTOCOL_VERSION,3)){
	  echo "<p>Failed to set version to protocol 3</p>";
	}

	// credentials to verify
	$ldaprdn = "cn=".$username.",dc=designstudio1,dc=com";
	$ldappass = $password; // associated password
	if ($ldapconn) {
      // binding to ldap server
      $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
      // verify binding
	  if ($ldapbind) {
		$filter="(cn=$username)";
		$search_result=ldap_search($ldapconn,"dc=designstudio1,dc=com", $filter);
		$entry = ldap_first_entry($ldapconn, $search_result);
		$attrs = ldap_get_attributes($ldapconn, $entry);
		echo "<br>Attrs " . var_dump($attrs);
		$_SESSION["fullname"]=$attrs["sn"][0];
		//$_SESSION["email"]=$attrs["mail"][0];
		$_SESSION["authenticated"] = 1;
		$_SESSION["username"] = $username;
		//echo $_SESSION["fullname"].','. $_SESSION["email"] .','. $_SESSION["authenticated"] = 1 .','. $_SESSION["username"];
		header("Location: search.php");
		ldap_close($ldapconn);
		$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
		if (mysqli_connect_errno($conn)){
		  echo 'Cannot connect to database: ' . mysqli_connect_error();
		}else{
		  $query1 = mysqli_prepare($conn, "Select ksuid, email from profile where username=?;");
		  mysqli_stmt_bind_param ($query1, "s", $_SESSION["username"]);
		  mysqli_stmt_execute($query1);
		  mysqli_stmt_store_result($query1);
		}
		mysqli_close($conn);
      } else {
		ldap_close($ldapconn);
        $_SESSION["errmsg"]="Authentication failed. Please check your username/password and try again.";
		header("Location: index.php");
		die();
      } // end else
	  //close ldap connection VERY IMPORTANT
	} else {
	if (empty($_POST["uname"])){
	  $msg = $msg . "<b>Username is required. Please go back and fill it in.<br>";
	}
	if(empty($_POST["pword"])){
	  $msg = $msg . "<b>Password is required. Please go back and fill it in.<br>";
	}
      echo $msg;
    }
  }
    echo "<br><br><br><a href='index.php'>Back</a>";
  ?>
  </body>
</html>