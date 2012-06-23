<?php include 'header.php'; ?>
<?php 

// Connection function moved to footer.php

/************************************************************************* 
               control code for application 
*************************************************************************/ 

// adding log records from other pages
if (isset($_GET['taskid']))
{
	get_data_taskid();
}

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
               get the data for an individual record 
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
    $sql = "select * from maintenanceLog where id = $id"; 
    $result = conn($sql); 
    if (mysql_num_rows($result)==1){ 
      //call the form and pass it the handle to the resultset 
      show_form($result); 
    }else{ 
      $msg = "No data found for selected record"; 
      confirm($msg); 
      //call the list users function 
      list_users(); 
    }//end if 
}//end function 

function get_data_taskid()
{
	//validate the taskid has been passed at that it is a number 
    if ((empty($_GET['taskid']))||(is_nan($_GET['taskid']))) 
    { 
        //there was a problem so list the users again 
      list_users(); 
      //kill the script 
      die(); 
    }else{ 
      //all is ok and assign the data to a local variable 
      $id = $_GET['taskid']; 
    }//end if 
    $sql = "SELECT 
				equipment.EquipmentName, 
				equipment.id, 
				equipment.Hours, 
				tasksRecurring.id, 
				tasksRecurring.TaskName, 
				tasksRecurring.EquipmentID
			FROM tasksRecurring LEFT JOIN equipment
			ON tasksRecurring.EquipmentID = equipment.id
			WHERE tasksRecurring.id = $id"; 
    $result = conn($sql); 
    if (mysql_num_rows($result)==1){ 
      //call the form and pass it the handle to the resultset 
      show_form_taskid($result); 
    }else{ 
      $msg = "No data found for selected record"; 
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
  $TaskName 		= ''; 
  $EquipmentID  	= ''; 
  $Date 			= date("Y-m-d");
  $EquipmentHours 	= '';
  $Notes			= '';
  $id         		= ''; 
  $value      		= 'Add';  //submit button value 
  $action     		= 'add';  //default form action is to add a new kid to db 

  //set the action based on what the user wants to do 
  if ($handle) 
  { 
    //set form values for button and action 
    $action = "edit"; 
    $value  = "Update"; 
    
    //get the values from the db resultset 
    $row = mysql_fetch_array($handle); 
    $TaskName 		= $row['TaskName']; 
    $EquipmentID  	= $row['EquipmentID']; 
    $Date     		= $row['Date']; 
	$EquipmentHours = $row['EquipmentHours'];
	$Notes			= $row['Notes'];
    $id         	= $row['id']; 

  }//end if 

  //error handling from the processing form function 
  if($data != '') 
  { 
    $elements = explode("|",$data); 
        $TaskName     		= $elements[0]; 
        $EquipmentID      	= $elements[1]; 
        $Date			    = $elements[2]; 
        $id             	= $elements[3]; 
		$EquipmentHours 	= $elements[4];
		$Notes 				= $elements[5];
  } 
?> 
    <body> 
    <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php  echo $action?>"> 
    <table width="400" align="center" border="0" cellspacing="0" cellpadding="0"> 
      <tr> 
          <td colspan="2" align="center" style="font-size:18px; font-weight:bold;">Maintenance Log</td> 
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
        <td align="right">Date: </td> 
        <td>
        <input name="Date" type="date" value="<?php echo $Date?>"> (YYYY-MM-DD)</td>  
      </tr> 
      <tr> 
        <td align="right">Equipment Hours: </td> 
        <td><input name="EquipmentHours" type="text" value="<?php echo $EquipmentHours?>"> </td>  
      </tr> 
      <tr>
      	<td align="right">Notes:</td>
        <td><textarea name="Notes"><?php echo $Notes ?></textarea></td>
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

function show_form_taskid($handle='',$data='') 
{ 
  //$handle is the link to the resultset, the ='' means that the handle can be empty / null so if nothing is picked it won't blow up 

  //set default values 
  $TaskName 		= ''; 
  $EquipmentID  	= ''; 
  $Date 			= date("Y-m-d");
  $EquipmentHours 	= '';
  $Notes			= '';
  $id         		= ''; 
  $value      		= 'Add';  //submit button value 
  $action     		= 'addTask';  //default form action is to add a new kid to db 

  //set the action based on what the user wants to do 
  if ($handle) 
  { 
    //set form values for button and action 
    $action = "addTask"; 
    $value  = "Add"; 
    
    //get the values from the db resultset 
    $row = mysql_fetch_array($handle); 
    $TaskName 		= $row['TaskName']; 
    $EquipmentID  	= $row['EquipmentID'];
	$EquipmentName	= $row['EquipmentName'];  
	$EquipmentHours = $row['Hours'];
    $id         	= $row['id']; 

  }//end if 

  //error handling from the processing form function 
  if($data != '') 
  { 
    $elements = explode("|",$data); 
        $TaskName     		= $elements[0]; 
        $EquipmentID      	= $elements[1]; 
        $Date			    = $elements[2]; 
        $id             	= $elements[3]; 
		$EquipmentHours 	= $elements[4];
		$Notes 				= $elements[5];
  } 
?> 
    <body> 
    <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php  echo $action?>"> 
    <table width="400" align="center" border="0" cellspacing="0" cellpadding="0"> 
      <tr> 
          <td colspan="2" align="center" style="font-size:18px; font-weight:bold;">Add to Maintenance Log</td> 
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
        <td align="right">Date: </td> 
        <td>
        <input name="Date" type="date" value="<?php echo $Date?>"> (YYYY-MM-DD)</td>  
      </tr> 
      <tr> 
        <td align="right">Equipment Hours: </td> 
        <td><input name="EquipmentHours" type="text" value="<?php echo $EquipmentHours?>"> </td>  
      </tr> 
      <tr>
      	<td align="right">Notes:</td>
        <td><textarea name="Notes"><?php echo $Notes ?></textarea></td>
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
				maintenanceLog.id,
				maintenanceLog.TaskName,
				maintenanceLog.Date,
				maintenanceLog.EquipmentHours,
				maintenanceLog.Notes
			FROM maintenanceLog LEFT JOIN equipment
			ON maintenanceLog.EquipmentID = equipment.id
			GROUP BY maintenanceLog.id"; 
    $result = conn($sql); 

  echo "<table width='800' id='DataList' align='center'> 
        <tr style='AboveHeading'>
			<td colspan='5'>
				<h1>Maintenance Log</h1>
				<p><a href='".$_SERVER['PHP_SELF']."?action=add'>Add a new record</a></p>
			</td>
		</tr> 
       	<tr class='TableHeading'>
			<td>Task</td>
			<td>Equipment</td>
			<td>Date</td>
			<td>Equipment Hours</td>
			<td>Notes</td>
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
        $TaskName 			= $rows['TaskName'];
		$EquipmentName 		= $rows['EquipmentName'];
		$EquipmentHours 	= $rows['EquipmentHours'];
								if ($EquipmentHours == '0') {
									$EquipmentHours = '';
								}
		$Date				= $rows['Date'];
		$Notes				= $rows['Notes']; 
		$id					= $rows['id'];
        
		//echo out the row 
        echo "<tr>
				<td>
					<a href='".$_SERVER['PHP_SELF']."?id=$id'>$TaskName</a>
				</td>
				<td>$EquipmentName</td>
				<td>$Date</td>
				<td>$EquipmentHours</td>
				<td>$Notes</td>
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
  $TaskName  		= ''; 
  $EquipmentID  	= ''; 
  $Date  			= ''; 
  $EquipmentHours 	= '';
  $Notes 			= '';
  $id     			= ''; 
  $action 			= '';  

  $TaskName  		= @$_POST['TaskName']; 
  $EquipmentID  	= @$_POST['EquipmentID']; 
  $Date  			= @$_POST['Date'];      
  $EquipmentHours  	= @$_POST['EquipmentHours'];
  $Notes  			= @$_POST['Notes'];
  $id     			= @$_POST['id'];          
  $action 			= @$_GET['action']; 
    
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
    $sql = "insert into maintenanceLog (TaskName, EquipmentID, Date, EquipmentHours, Notes) values('$TaskName','$EquipmentID','$Date','$EquipmentHours','$Notes')"; 
    $msg = "Record successfully added"; 
  } elseif ($action == "addTask") 
  { 
    $sql = "insert into 
				maintenanceLog 
			(
				TaskName, 
				EquipmentID, 
				Date, 
				EquipmentHours, 
				Notes
			) values(
				'$TaskName',
				'$EquipmentID',
				'$Date',
				'$EquipmentHours',
				'$Notes'
			)"; 
    /*$sql = "update 
				tasksRecurring 
			set 
				LastDate = '$Date', 
				LastHours = '$EquipmentHours' 
			where 
				id = $id";
	
	if ($EquipmentHours == '0') {
		die();
	} else {
		// Should also check whether new equipment hours > current equipment hours
		$sql = "update equipment set Hours = 'EquipmentHours' where id = $EquipmentID";
	} */
	
    $msg = "Record successfully added"; 
  
  }elseif($action=="edit"){ 
    $sql = "update maintenanceLog set TaskName = '$TaskName', EquipmentID = '$EquipmentID', Date = '$Date', EquipmentHours = '$EquipmentHours', Notes = '$Notes' where id = $id"; 
    $msg = "Record successfully updated"; 
  } 
  $result = conn($sql); 
  if (mysql_errno()==0) 
  { 
    confirm($msg); 
    list_users(); 
  }else{ 
    $msg = "There was a problem adding the record to the database. Error is:".mysql_error(); 
    confirm($msg); 
  }//end if 
     
	 


  $result = conn($sql); 
  if (mysql_errno()==0) 
  { 
    confirm($msg); 
    list_users(); 
  }else{ 
    $msg = "There was a problem adding the record to the database. Error is:".mysql_error(); 
    confirm($msg); 
  }//end if 
} 

/************************************************************************* 
               delete a record
*************************************************************************/ 
function delete_form() 
{ 

  $action = @$_GET['action']; 
  $id     = @$_POST['id'];      

  if ($action == "delete")    
    
    $sql = "DELETE FROM maintenanceLog WHERE id = '$id' ";  
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