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
</body>
</html>