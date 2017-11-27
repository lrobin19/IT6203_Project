<!DOCTYPE html>
<html lang="en-us">
  <head>
	<meta charset="utf-8">
    <title>Logout</title>
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
			  $_SESSION['pmenu']='unauth';
			  include "aside.php"; 
			?>
		  </li>
		</ul>
	  </nav>
	</aside>
	  <br><br>
	  <?php
	  session_start();
      $_SESSION = array();
      session_destroy();
	  ?>
	  <center><h1>You have been logged out of Task List. Click <a href="index.php">here</a> to log back in</h1></center>
  </body>
</html>