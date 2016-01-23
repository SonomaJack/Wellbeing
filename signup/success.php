<?php

  
  session_start();
/*


$PassedLocation = explode('?', $_SERVER['REQUEST_URI']); 
  
  
switch (sizeof($PassedLocation)) {
	case 1:
		$_SESSION['URLLoc'] = ''; 
		$_SESSION['PhysicianPassedIn'] = 'Need to Update'; 
		$URLParms = "?".$_SESSION['URLLoc'];
		break;
	case 2:
		$_SESSION['URLLoc'] = $PassedLocation[1]; 
		$_SESSION['PhysicianPassedIn'] = 'Need to Update'; 
		$URLParms = "?".$_SESSION['URLLoc'];

		break;
	case 3:
		$_SESSION['URLLoc'] = $PassedLocation[1]; 
		$_SESSION['PhysicianPassedIn'] = $PassedLocation[2];
		$URLParms = "?".$_SESSION['URLLoc']."?".$_SESSION['PhysicianPassedIn'];

		break;
	case 4:
		$_SESSION['URLLoc'] = $PassedLocation[1]; 
		$_SESSION['PhysicianPassedIn'] = $PassedLocation[2];
		$_SESSION['Message'] = $PassedLocation[3];
		$URLParms = "?".$_SESSION['URLLoc']."?".$_SESSION['PhysicianPassedIn']."?".$_SESSION['Message'] ;
		break;
	default:
		$_SESSION['URLLoc'] = ''; 
		$_SESSION['PhysicianPassedIn'] = 'Need to Update'; 
		$URLParms = "";
		break;
	}	
*/
if (!empty($_POST['Submit']))
  {
 header("Location: signup.php".$URLParms);
 }
 $MemberName=$_SESSION['MemberName'];
 
 ?>
  
 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>	PartnerMD - Processed Membership</title>
</head> 
<body> 
 <span id="Generalstatus" STYLE="background-color: #ffffcc;color: red; font-size: 16pt"><?php echo $MemberName;?></span> <br>
  
<form name="Form1" method="post"   id="Form1">  

	<input name="Submit" type="submit" id="Submit" value="Return to Member Processing " /> 
</body>
</form>