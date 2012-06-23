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
    $sql = "select * from equipment where id = $id"; 
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
  $EquipmentName 	= ''; 
  $Make  			= ''; 
  $Model      		= ''; 
  $Vendor     		= ''; 
  $Serial 			= '';
  $WarrantyExpiry 	= '';
  $Hours 			= '';
  $HoursDate 		= '';
  $id        		= ''; 
  $value      		= 'Add';  //submit button value 
  $action     		= 'add';  //default form action is to add a new record to db 

  //set the action based on what the user wants to do 
  if ($handle) 
  { 
    //set form values for button and action 
    $action = "edit"; 
    $value  = "Update"; 
    
    //get the values from the db resultset 
    $row = mysql_fetch_array($handle); 
    $EquipmentName = $row['EquipmentName']; 
    $Make  = $row['Make']; 
    $Model      = $row['Model']; 
	$Vendor = $row['Vendor'];
    $Serial     = $row['Serial'];
    $WarrantyExpiry     = $row['WarrantyExpiry'];
	$Hours     = $row['Hours']; 
	$HoursDate     = $row['HoursDate'];
    $id         = $row['id']; 

  }//end if 

  //error handling from the processing form function 
  if($data != '') 
  { 
    $elements = explode("|",$data); 
        $EquipmentName  = $elements[0]; 
        $Make      		= $elements[1]; 
        $Model          = $elements[2]; 
        $id             = $elements[3]; 
  } 
?> 
    <body> 
    <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php  echo $action?>"> 
    <table width="400" align="center" border="0" cellspacing="0" cellpadding="0"> 
      <tr> 
          <td colspan="2" align="center" style="font-size:18px; font-weight:bold;">Equipment</td> 
          <input type="hidden" value="<?php echo $id?>" name="id"> 
      </tr> 
      <tr> 
        <td> </td> 
        <td> </td> 
      </tr> 
      <tr> 
        <td align="right">EquipmentName: </td> 
        <td><input name="EquipmentName" type="text" value="<?php echo $EquipmentName?>"> </td> 
      </tr> 
      <tr> 
       <td align="right">Make: </td> 
       <td><input name="Make" type="text" value="<?php echo $Make?>"> </td> 
      </tr> 
      <tr> 
        <td align="right">Model: </td> 
        <td><input name="Model" type="text" value="<?php echo $Model?>"> </td> 
      </tr> 
      <tr> 
        <td align="right">Vendor: </td> 
        <td><input name="Vendor" type="text" value="<?php echo $Vendor?>"> </td>  
      </tr>
      <tr> 
        <td align="right">Serial: </td> 
        <td><input name="Serial" type="text" value="<?php echo $Serial?>"> </td>  
      </tr> 
      <tr> 
        <td align="right">Warranty Expiration Date: </td> 
        <td><input name="WarrantyExpiry" type="text" value="<?php echo $WarrantyExpiry?>"> </td>  
      </tr> 
      <tr> 
        <td align="right">Hours: </td> 
        <td><input name="Hours" type="text" value="<?php echo $Hours?>"> </td>  
      </tr> 
      <tr> 
        <td align="right">Hours as of: </td> 
        <td><input name="HoursDate" type="text" value="<?php echo $HoursDate?>"> </td>  
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
    
    <tr><td colspan="2">
        <a href ="<?php echo $_SERVER['PHP_SELF']; ?>">Back to all entries</a> 
        </td></tr>
        </table> 
    </body> 

<?php 
}//end function 


/************************************************************************* 
               list all the equipment in the db 
*************************************************************************/ 
function list_users() 
{ 
    $y = 0; //counter for background color 
    
    $sql = "select * from equipment ";
    $result = conn($sql); 

  echo "<table width='800' align='center' id='DataList'> 
        <tr style='AboveHeading'>
			<td colspan='10'>
				<h1>Equipment</h1>
				<p><a href='".$_SERVER['PHP_SELF']."?action=add'>Add new equipment</a></p>
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
        
// **************		
// ************** JILL - COME BACK HERE TO ADD MORE EQUIPMENT COLUMNS
// **************
		
		//echo out the row 
        echo "<tr>
				<td>
					<a href='".$_SERVER['PHP_SELF']."?id=$id'>$EquipmentName</a>
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
} 


/************************************************************************* 
               add / update the contact's data 
*************************************************************************/ 
function process_form() 
{ 
  $EquipmentName  = ''; 
  $Make  = ''; 
  $Model  = ''; 
  $Vendor = '';
  $Serial = '';
  $WarrantyExpiry = '';
  $Hours = '';
  $HoursDate = '';
  $id     = ''; 
  $action = ''; 
  $status = 0;    //default value 

  $EquipmentName  = @$_POST['EquipmentName']; 
  $Make  = @$_POST['Make']; 
  $Model  = @$_POST['Model'];      
  $Vendor  = @$_POST['Vendor'];
  $Serial  = @$_POST['Serial'];
  $WarrantyExpiry  = @$_POST['WarrantyExpiry'];
  $Hours  = @$_POST['Hours'];
  $HoursDate  = @$_POST['HoursDate'];
  $id     = @$_POST['id'];          
  $action = @$_GET['action']; 
  $status = @$_POST['status']; 

  //if no status is set, defaults to 0 (allow contact) 
  
  //if ($status == ''){$status = 0; } 
    
  if (($EquipmentName=='')) 
  { 
    $msg = "Some data from the form was forgotten. Please fill in the entire form."; 
    confirm($msg); 
    $data = "$EquipmentName|$Make|$Model|$Vendor|$Serial|$WarrantyExpiry|$Hours|$HoursDate|$id"; 
    show_form('',$data); 
    die(); 
  }//end if 
    
   if ($action == "add") 
  { 
    $sql = "insert into equipment (EquipmentName, Make, Model, Vendor, Serial, WarrantyExpiry, Hours, HoursDate) values('$EquipmentName','$Make','$Model','$Vendor','$Serial','$WarrantyExpiry','$Hours','$HoursDate')"; 
    $msg = "Record successfully added"; 
  }elseif($action=="edit"){ 
    $sql = "update equipment set EquipmentName = '$EquipmentName', Make = '$Make', Model = '$Model', Vendor = '$Vendor', Serial = '$Serial', WarrantyExpiry = '$WarrantyExpiry', Hours = '$Hours', HoursDate = '$HoursDate' where id = $id"; 
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
    
    $sql = "DELETE FROM equipment WHERE id = '$id' ";  
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

<?php include 'footer.php'; ?>