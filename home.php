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
    <title>Home</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">

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
	  <div class="login-info">
	  </div>
	  <nav>
		<ul>
		  <li class="">
			<a href="index.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Home</span></a>
			<?php
			if (!isset ($_SESSION["uid"])){
				echo '<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>';
			}
				echo '<a href="logout.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>';
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
	  <center>
		<h2>
		  <nav>
			<a href="register.php"><div>Register</div></a><br>
			<a href="search.php"><div>Search</div></a><br>
			<a href="select_services"><div>Select Services</div></a><br>
			<a href="tasks.php"><div>Create A Task</div></a><br>
			<a href="register.php?update=true"><div>Update Profile</div>
		  </nav>  
		</h2>
		</center>
	</div>
<br><br>
</body>
</html>
