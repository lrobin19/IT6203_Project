<?php
if (isset($_SESSION['pmenu'])){
  if($_SESSION['pmenu'] == 'tutor'){
			echo '<a href="tutormenu.php" title="tutor_menu"><i class="fa fa-lg fa-fw fa-home"></i><span class="menu-item-parent">Home</span></a>
			<a href="register.php?update=true" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Update Profile</span></a>
			<a href="logout.php" title="logout"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>';
  }else	if($_SESSION['pmenu'] == 'student'){
	  echo '<a href="search.php" title="search"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
		<a href="register.php?update=true" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Update Profile</span></a>
			<a href="logout.php" title="logout"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>';
  }else{
	  echo '<a href="index.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Login</span></a>
	  <a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>';
  }
}else{
  echo '<a href="index.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Login</span></a>
  <a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>';
}







/*
			<a href="index.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Home</span></a>
			<a href="register.php" title="register"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Register</span></a>
			<a href="tutormenu.php" title="tutor_menu"><i class="fa fa-lg fa-fw fa-home"></i><span class="menu-item-parent">Tutor Menu</span></a>
			<a href="tasks.php" title="create_task"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Create Task</span></a>
			<a href="search.php" title="search"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Search</span></a>
			<a href="logout.php" title="logout"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Logout</span></a>
			*/