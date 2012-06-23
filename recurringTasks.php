<?php include 'header.php'; ?>
<?php 

// Connection function moved to footer.php

/************************************************************************* 
               control code for application 
*************************************************************************/ 

//submit button was pressed so call the process form function 
if (isset($_POST['submit'])) 
{ 
  process_form(); 
  die(); 
}//end if 

if (isset($_POST['submit_delete'])) 
{ 
  delete_form(); 
  die(); 
}//end if 


//call the get_data function 
if (isset($_GET['id'])) 
{ 
  get_data(); 
}//endif 


//nothing chosen so list the kids 
if ((empty($_POST))&&(empty($_GET))) 
{ 
  list_users(); 
  die(); 
}//end if 


//request to add a new contact so call the show_form function 
if ((isset($_GET['action']))&&($_GET['action']=='add')) 
{ 
  show_form(); 
}//endif 



/************************************************************************* 
               get the data for an individual contact 
*************************************************************************/ 

function get_data() 
{ 
    //validate the id has been passed at that it is a number 
    if ((empty($_GET['id']))||(is_nan($_GET['id']))) 
    { 
        //there was a problem so list the users again 
      list_users(); 
      //kill the script 
      die(); 
    }else{ 
      //all is ok and assign the data to a local variable 
      $id = $_GET['id']; 
    }//end if 
    $sql = "select * from tasksRecurring where id = $id"; 
    $result = conn($sql); 
    if (mysql_num_rows($result)==1){ 
      //call the form and pass it the handle to the resultset 
      show_form($result); 
    }else{ 
      $msg = "No data found for selected equipment"; 
      confirm($msg); 
      //call the list users function 
      list_users(); 
    }//end if 
}//end function 


/************************************************************************* 
               show the input / edit form 
*************************************************************************/ 
function show_form($handle='',$data='') 
{ 
  //$handle is the link to the resultset, the ='' means that the handle can be empty / null so if nothing is picked it won't blow up 

  //set default values 
  $TaskName = ''; 
  $EquipmentID  = ''; 
  $IntervalType      = ''; 
  $IntervalHours     = ''; 
  $IntervalDays = '';
  $LastDate = '';
  $LastHours = '';
  $id         = ''; 
  $value      = 'Add';  //submit button value 
  $action     = 'add';  //default form action is to add a new kid to db 

  //set the action based on what the user wants to do 
  if ($handle) 
  { 
    //set form values for button and action 
    $action = "edit"; 
    $value  = "Update"; 
    
    //get the values from the db resultset 
    $row = mysql_fetch_array($handle); 
    $TaskName = $row['TaskName']; 
    $EquipmentID  = $row['EquipmentID']; 
    $IntervalType      = $row['IntervalType']; 
    $IntervalHours     = $row['IntervalHours'];
    $IntervalDays     = $row['IntervalDays'];
	$LastDate     = $row['LastDate']; 
	$LastHours     = $row['LastHours'];
    $id         = $row['id']; 

  }//end if 

  //error handling from the processing form function 
  if($data != '') 
  { 
    $elements = explode("|",$data); 
        $TaskName     	= $elements[0]; 
        $EquipmentID      		= $elements[1]; 
        $IntervalType          = $elements[2]; 
        $id             = $elements[3]; 
		$IntervalHours = $elements[4];
		$IntervalDays = $elements[5];
		$LastDate = $elements[6];
		$LastHours = $elements[7];
  } 
?> 
    <body> 
    <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php  echo $action?>"> 
    <table width="400" align="center" border="0" cellspacing="0" cellpadding="0"> 
      <tr> 
          <td colspan="2" align="center" style="font-size:18px; font-weight:bold;">Recurring Tasks</td> 
          <input type="hidden" value="<?php echo $id?>" name="id"> 
      </tr> 
      <tr> 
        <td> </td> 
        <td> </td> 
      </tr> 
      <tr> 
        <td align="right">Task Name: </td> 
        <td><input name="TaskName" type="text" value="<?php echo $TaskName?>"> </td> 
      </tr> 
      <tr> 
       <td align="right">Equipment ID: </td> 
       <td><input name="EquipmentID" type="text" value="<?php echo $EquipmentID?>"> </td> 
      </tr> 
      <tr> 
        <td align="right">Interval Type: </td> 
        <td>
        <select name="IntervalType">
        	<option name="days" value="days" 
				<?php if ($IntervalType == 'days') {
					echo "selected";
					}  
				?>
             >Days</option>
            <option name="hours" value="hours"
            	<?php if ($IntervalType == 'hours') {
					echo "selected";
					}  
				?>
            
            >Hours</option>
        </select>    
        </td> 
      </tr> 
      <tr> 
        <td align="right">Interval Hours: </td> 
        <td><input name="IntervalHours" type="text" value="<?php echo $IntervalHours?>"> </td>  
      </tr>
      <tr> 
        <td align="right">Interval Days: </td> 
        <td><input name="IntervalDays" type="text" value="<?php echo $IntervalDays?>"> </td>  
      </tr> 
      <tr> 
        <td align="right">Last Date: </td> 
        <td>
        <input name="LastDate" type="date" value="<?php echo $LastDate?>"> (YYYY-MM-DD)</td>  
      </tr> 
      <tr> 
        <td align="right">Last Hours: </td> 
        <td><input name="LastHours" type="text" value="<?php echo $LastHours?>"> </td>  
      </tr> 
      <tr> 
        <td> </td> 
        <td> </td> 
      </tr> 
      <tr> 
        <td colspan="2" align="center"><input name="submit" type="submit" value="<?php echo $value?>"> 
        <input name="reset" type="reset" value="Clear Form"></form> 
        
        <form name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete"> 
        <input type="hidden" value="<?php echo $id?>" name="id"> 
        <input name="submit_delete" type="submit" value="Delete"> 
        </form> 
        
        </td> 
      </tr> 
      <tr><td><a href ="<?php echo $_SERVER['PHP_SELF']; ?>">Back to all entries</a></td></tr>
    </table> 
    
         
    </body> 

<?php 
}//end function 


/************************************************************************* 
               list all the tasks in the db 
*************************************************************************/ 
function list_users() 
{ 
    $y = 0; //counter for background color 
    
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
				<h1>Recurring Tasks</h1>
				<p><a href='".$_SERVER['PHP_SELF']."?action=add'>Add a new task</a></p>
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
        
        // detect whether the warranty has expired
		
		//$todays_date = date("Y-m-d");
		//$today = strtotime($todays_date);
		//$expiration_date = strtotime($WarrantyExpiry);

		//if ($expiration_date > $today) {
     	//	$expired = "no";
		//	} else {
     	//	$expired = "yes";
		//	}
			
		//if ($expired == "yes") {
		//	$bgcolor="#FF0000";	
		//} else {
		//	$bgcolor="#00FF00";
		//}
		
		if ($IntervalType == 'days') {
			
			$CurrentDate = date("Y-m-d"); // current date
			$CurrentDate = strtotime($CurrentDate);
			$DueDate = strtotime(date("Y-m-d", strtotime($LastDate)) . " +$IntervalDays day");
			
			if ($DueDate <= $CurrentDate) {
				$bgcolor="#F4D8D2";	
			}
				
			$DueDate = date("Y-m-d", $DueDate);
			$DueHours = '';
		}
		
		
		if ($IntervalType == 'hours') {
			
			$DueHours = ($LastHours + $IntervalHours);
			
			if ($DueHours <= $EquipmentHours) {
				$bgcolor="#F4D8D2";	
			}

			$DueDate = '';
		}
		
        
		//echo out the row 
        echo "<tr>
				<td>
					<a href='".$_SERVER['PHP_SELF']."?id=$id'>$TaskName</a>
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
        $y++;  //increment the counter 
      }//end while 
      echo "</table>"; 
  }else{ 
    //handle no results 
    echo "<tr><td colspan='2' align='center'><b>No data found.</b></td></tr>"; 
  }//endif 
} 


/************************************************************************* 
               add / update the contact's data 
*************************************************************************/ 
function process_form() 
{ 
  $TaskName  = ''; 
  $EquipmentID  = ''; 
  $IntervalType  = ''; 
  $IntervalHours = '';
  $IntervalDays = '';
  $LastDate = '';
  $LastHours = '';
  $id     = ''; 
  $action = ''; 
  //$status = 0;    //default value 

  $TaskName  = @$_POST['TaskName']; 
  $EquipmentID  = @$_POST['EquipmentID']; 
  $IntervalType  = @$_POST['IntervalType'];      
  $IntervalHours  = @$_POST['IntervalHours'];
  $IntervalDays  = @$_POST['IntervalDays'];
  $LastDate  = @$_POST['LastDate'];
  $LastHours  = @$_POST['LastHours'];
  $id     = @$_POST['id'];          
  $action = @$_GET['action']; 
  //$status = @$_POST['status']; 
    
  if (($TaskName=='')) 
  { 
    $msg = "Some data from the form was forgotten. Please fill in the entire form."; 
    confirm($msg); 
    $data = "$TaskName|$EquipmentID|$IntervalType|$IntervalHours|$IntervalDays|$LastDate|$LastHours|$id"; 
    show_form('',$data); 
    die(); 
  }//end if 



    
   if ($action == "add") 
  { 
    $sql = "insert into tasksRecurring (TaskName, EquipmentID, IntervalType, IntervalHours, IntervalDays, LastDate, LastHours) values('$TaskName','$EquipmentID','$IntervalType','$IntervalHours','$IntervalDays','$LastDate','$LastHours')"; 
    $msg = "Record successfully added"; 
  }elseif($action=="edit"){ 
    $sql = "update tasksRecurring set TaskName = '$TaskName', EquipmentID = '$EquipmentID', IntervalType = '$IntervalType', IntervalHours = '$IntervalHours', IntervalDays = '$IntervalDays', LastDate = '$LastDate', LastHours = '$LastHours' where id = $id"; 
    $msg = "Record successfully updated"; 
  } 
  $result = conn($sql); 
  if (mysql_errno()==0) 
  { 
    confirm($msg); 
    list_users(); 
  }else{ 
    $msg = "There was a problem adding the user to the database. Error is:".mysql_error(); 
    confirm($msg); 
  }//end if 
     
} 

/************************************************************************* 
               delete a contact 
*************************************************************************/ 
function delete_form() 
{ 

  $action = @$_GET['action']; 
  $id     = @$_POST['id'];      

  if ($action == "delete")    
    
    $sql = "DELETE FROM tasksRecurring WHERE id = '$id' ";  
    $msg = "Record successfully deleted";  

    $result = conn($sql); 
  
  if (mysql_errno()==0) 
  { 
    confirm($msg); 
    list_users(); 
  }else{ 
    $msg = "There was a problem deleting the user to the database. Error is:".mysql_error(); 
    confirm($msg); 
  }//end if 
    } 

  

/************************************************************************* 
               alert box popup confimation message function 
*************************************************************************/ 
function confirm($msg) 
{ 
  echo "<script langauge=\"javascript\">alert(\"".$msg."\");</script>"; 
}//end function 

?>

<?php
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