<?php include 'header.php'; ?>

<?php
 
// *** USEFUL DATES FOR THIS PAGE
 
	$todays_date = date("Y-m-d");
	
	//$today = strtotime($todays_date);
	$todayPlusThree = strtotime($todays_date . "+3 month");
	$todayPlusOne = strtotime($todays_date . "+1 month");
	$todayMinusOne = strtotime($todays_date . "-1 month");
	
	//making the dates human-readable...
	$todayPlusThreeReadable = date("Y-m-d", $todayPlusThree);
	$todayPlusOneReadable = date("Y-m-d", $todayPlusOne);
	$todayMinusOneReadable = date("Y-m-d", $todayMinusOne);
	
	// For SQL date comparison, need to remove the dashes
	$todayMinusOneSQL = date("Ymd", $todayMinusOne);
	$todayPlusOneSQL = date("Ymd", $todayPlusOne);
	$todayPlusThreeSQL = date("Ymd", $todayPlusThree);
	
// *** UPCOMING TASKS TABLE ***

$sql = "SELECT 
				equipment.EquipmentName, 
				equipment.id, 
				equipment.Hours, 
				tasksRecurring.id, 
				tasksRecurring.TaskName, 
				tasksRecurring.EquipmentID, 
				tasksRecurring.IntervalType, 
				tasksRecurring.IntervalHours, 
				tasksRecurring.IntervalDays, 
				tasksRecurring.LastDate, 
				tasksRecurring.LastHours
			FROM tasksRecurring LEFT JOIN equipment
			ON tasksRecurring.EquipmentID = equipment.id
			GROUP BY tasksRecurring.id"; 
    $result = conn($sql); 

  echo "<table width='800' id='DataList' align='center'> 
        <tr style='AboveHeading'>
			<td colspan='7'>
				<h1>Tasks Due within one month or 200 hours</h1>
				<p><a href='recurringTasks.php?action=add'>Add a new task</a></p>
			</td>
		</tr> 
       	<tr class='TableHeading'>
			<td>Task</td>
			<td>Equipment</td>
			<td>Interval</td>
			<td>Last Completed</td>
			<td>Next Due</td>
			<td>Current Hours</td>
			<td>&nbsp;</td>
		</tr> 
       	"; 
        
     if (mysql_num_rows($result)){ 
      //show a list of kids with name as a link to the prepopulated form with their data in it 
      while($rows = mysql_fetch_array($result)){ 
        
        //change row background color 
        //(($y % 2) == 0) ? $bgcolor = "#FFFFFF" : $bgcolor="#CCCCCC"; 
		
		// just set default bgcolor
		
		$bgcolor = "#FFFFFF";
		$showhiderow = "";
        
        //build strings to make life easier 
        $TaskName = $rows['TaskName'];
		$EquipmentName = $rows['EquipmentName'];
		$EquipmentHours = $rows['Hours'];
		if ($EquipmentHours == '0') {
			$EquipmentHours = '';
		}
		$IntervalType = $rows['IntervalType'];
		$IntervalHours = $rows['IntervalHours'];
		if ($IntervalHours == '0') {
			$IntervalHours = '';
		}
		$IntervalDays = $rows['IntervalDays'];
		if ($IntervalDays == '0') {
			$IntervalDays = '';
		}
		$LastDate = $rows['LastDate'];
		if ($LastDate == '0000-00-00') {
			$LastDate = '';
		}
		$LastHours = $rows['LastHours'];
		if ($LastHours == '0') {
			$LastHours = '';
		}
        $id     = $rows['id']; 
		
		if ($IntervalType == 'days') {
			
			$CurrentDate = date("Y-m-d"); // current date
			$CurrentDate = strtotime($CurrentDate);
			$DueDate = strtotime(date("Y-m-d", strtotime($LastDate)) . " +$IntervalDays day");
			
			if ($DueDate <= $CurrentDate) {
				$bgcolor="#F4D8D2";	
			} else if ($DueDate >= $todayPlusOne) {
				// hide the row if the task is due more than a month in the future
				$showhiderow="hide";
			}
				
			$DueDate = date("Y-m-d", $DueDate);
			$DueHours = '';
		}
		
		
		if ($IntervalType == 'hours') {
			
			$DueHours = ($LastHours + $IntervalHours);
			
			if ($DueHours <= $EquipmentHours) {
				$bgcolor="#F4D8D2";	
			} else if ($DueHours > ($EquipmentHours + 200)) {
				// hide  the row if the task is due more than 200 hours in the future
				$showhiderow="hide";
			}

			$DueDate = '';
		}
		
        
		//echo out the row 
        echo "<tr class='$showhiderow'>
				<td>
					<a href='recurringTasks.php?id=$id'>$TaskName</a>
				</td>
				<td>$EquipmentName</td>
				
				<td>";
				
				if ($IntervalType == 'days') {
					echo "$IntervalDays days";
				}
				
				if ($IntervalType == 'hours') {
				
					echo "$IntervalHours hours";
					
				}
				
		echo "</td><td>";
				
				if ($IntervalType == 'days') {
					echo "$LastDate";
				}
				
				if ($IntervalType == 'hours') {
				
					echo "$LastHours hours";
				}
		
		
		echo "</td><td style='background-color: $bgcolor'>";
				
				if ($IntervalType == 'days') {
					echo "$DueDate";
				}
				
				if ($IntervalType == 'hours') {
				
					echo "$DueHours hours";
					
				}
				
				
		echo	"</td>
				<td>$EquipmentHours</td>
				<td><a href='MaintenanceLog.php?taskid=$id'>Mark completed</a></td>
			<tr>"; 
       // $y++;  //increment the counter 
      }//end while 
      echo "</table>"; 
  }else{ 
    //handle no results 
    echo "<tr><td colspan='2' align='center'><b>No data found.</b></td></tr>"; 
  }//endif 	
	
	
// **** EQUIPMENT WARRANTY TABLE ***	
	
	
	$sql = "SELECT * FROM equipment WHERE WarrantyExpiry BETWEEN $todayMinusOneSQL AND $todayPlusThreeSQL";
    $result = conn($sql); 
	
	$y = 0; //counter for background color 

  echo "<table width='800' align='center' id='DataList'> 
        <tr style='AboveHeading'>
			<td colspan='10'>
				<h1>Warranties Expiring between $todayMinusOneReadable and $todayPlusThreeReadable</h1>
				<p><a href='equipment.php?action=add'>Add new equipment</a></p>
			</td>
		</tr>   
     	<tr class='TableHeading'>
			<td>Equipment Name</td>
			<td>Make</td>
			<td>Model</td>
			<td>Vendor</td>
			<td>Serial</td>
			<td colspan='3'>Hours</td>
			<td>Warranty Date</td>
		</tr>"; 
        
     if (mysql_num_rows($result)){ 
      //show a list of kids with name as a link to the prepopulated form with their data in it 
      while($rows = mysql_fetch_array($result)){ 
        
        //change row background color 
        //(($y % 2) == 0) ? $bgcolor = "#8FBC8F" : $bgcolor=" #9ACD32"; 
        
        //build strings to make life easier 
        $EquipmentName = $rows['EquipmentName'];
		$Make = $rows['Make'];
		$Model = $rows['Model'];
		$Vendor = $rows['Vendor'];
		$Serial = $rows['Serial'];
		$WarrantyExpiry = $rows['WarrantyExpiry'];
		$Hours = $rows['Hours'];
		
		if ($Hours =='0') {
			$Hours = '';	
		}
		
		$HoursDate = $rows['HoursDate'];
		
		if ($HoursDate == '0000-00-00') {
			$HoursDate = '';
		}
		
		//$HoursDate = date("m-d-Y");
        $id     = $rows['id']; 
        
        // detect whether the warranty has expired
		
		$todays_date = date("Y-m-d");
		$today = strtotime($todays_date);
		$expiration_date = strtotime($WarrantyExpiry);

		if ($expiration_date > $today) {
     		$expired = "no";
			} else {
     		$expired = "yes";
			}
			
		if ($expired == "yes") {
			$bgcolor="#F4D8D2";	
		} else {
			$bgcolor="#DDF4D2";
		}
		
		
		//convert status to readable string from 1 or 0 
        //($status == 0) ? $status = "Available to contact" : $status = "Do not contact at present."; 
		
		//echo out the row 
        echo "<tr>
				<td>
					<a href='equipment.php?id=$id'>$EquipmentName</a>
				</td>
				<td>$Make</td>
				<td>$Model</td>
				<td>$Vendor</td>
				<td>$Serial</td>
				<td>$Hours</td><td> as of </td><td>$HoursDate</td>
				<td style='background-color:$bgcolor;'>$WarrantyExpiry</td>
			<tr>"; 
        $y++;  //increment the counter 
      }//end while 
      echo "</table>"; 
  }else{ 
    //handle no results 
    echo "<tr><td colspan='3' align='center'><b>No data found.</b></td></tr>"; 
  }//endif 

/************************************************************************* 
               db connection function 
*************************************************************************/ 
function conn($sql) 
{    
/* 
  If you use include("/connect.php") delete everyting else within these function bracket 
  and change $result = conn($sql); to $result = mysql_query($sql) or die; 
*/ 

$host = "localhost";   // may need to change according your settings 
$user = "root";        // may need to change according your settings 
$pass = "";       // may need to change according your settings 
$db   = "floatyourboat"; 

    //echo "commnecing connection to local db<br>"; 
    
    if (!($conn=mysql_connect($host, $user, $pass)))  { 
        printf("error connecting to DB by user = $user and pwd=$pass"); 
        exit; 
    } 
    $db3=mysql_select_db($db,$conn) or die("Unable to connect to local database"); 

    $result = mysql_query($sql) or die ("Can't run query because ". mysql_error()); 
    
    return $result;
	
	
    
}//end function 


?>

<?php include 'footer.php'; ?>