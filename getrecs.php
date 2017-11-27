<?php
$conn = new mysqli("localhost", "proj_user", "my*password", "lrobinson");
 $desc = $_REQUEST['action'];
 
 $query2 = mysqli_prepare($conn, "SELECT a.first_name, a.last_name, a.availability, c.description from profile a join servicesprovided b on a.ksuid=b.userid join services c on b.servid=c.servid where c.shortdesc= ?;");
 mysqli_stmt_bind_param ($query2, "s", $desc);
		mysqli_stmt_execute($query2);
		mysqli_stmt_store_result($query2);
		if( mysqli_stmt_num_rows($query2) == 0){
             echo "<h2>No tutors available for this service.<h2>";
          }else{
            mysqli_stmt_bind_result($query2, $fname, $lname, $avail, $desc);
			$i=0;
			while (mysqli_stmt_fetch($query2)) {
			  $resarr[$i][0] = $fname . " " . $lname;
			  $resarr[$i][1] = $avail;
			  $resarr[$i][2] = $desc;
			  $i+=1;
			}
			mysqli_close($conn);
			if( mysqli_stmt_num_rows($query2) == 0){
			  echo "<h2>No tutors available for this service.<h2>";
			}else{
				echo "<h1> Service: " . $desc ."</h1><br>";
			}
			?>
 <div class="row">

		<table class="center">
	    <tr>
		  <th>Tutor</th>
		  <th>Day</th>
		  <th>Time</th>
		</tr>
		<?php
		  for($x = 0; $x < count($resarr); $x++) {  
		  $availarr = explode(",", $avail);
			for ($y = 0; $y < count($availarr); $y++){
			  $row = "<tr><td> ".$resarr[$x][0] . "</td><td> ";
			  if (strpos($availarr[$y], 'mon') !== false){
				$theshift = "Monday ";
			  } elseif (strpos($availarr[$y], 'tues') !== false){
				$theshift = "Tuesday ";
			  } elseif (strpos($availarr[$y], 'wed') !== false){
				$theshift = "Wednesday ";
			  } elseif (strpos($availarr[$y], 'thurs') !== false){
				$theshift = "Thursday ";
			  } elseif (strpos($availarr[$y], 'fri') !== false){
				$theshift = "Friday ";
			  } elseif (strpos($availarr[$y], 'sat') !== false){
				$theshift = "Saturday ";
			  } elseif (strpos($availarr[$y], 'sun') !== false){
				$theshift = "Sunday ";
			  }
			  $row .= $theshift . "</td><td>";
			  if (strpos($availarr[$y], 'morn') !== false){
				$theshift = "Morning";
			  } elseif (strpos($availarr[$y], 'aft') !== false){
				$theshift = "Afternoon";
			  } elseif (strpos($availarr[$y], 'eve') !== false){
				$theshift = "Evening";
			  }
			  $row .= $theshift . "</td></tr>";
			  echo $row;
			}
			
		  }
		echo "</table>";
		  }
		?>
 </div>