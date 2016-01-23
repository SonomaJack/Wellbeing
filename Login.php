<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


	require_once '../lib/API.php';
	require_once '../config.php';
	require_once '../functions.php';

// Inialize session
	session_start();


	$uid='';
  	$pw='';
 	$statString='';
 	$status="";
	if (!empty($_POST['enviroment'])) {
		 if (!empty($_POST['Submit']))
		  {	  
			switch ($_POST['enviroment']) {
			case 'Production':
				$endpoint = 'https://www.zuora.com/apps/services/a/17.0';
				break;
			case 'Sandbox':
				$endpoint = 'https://apisandbox.zuora.com/apps/services/a/17.0';
				break;
			default:
				$endpoint = 'https://apisandbox.zuora.com/apps/services/a/17.0';
				break;
			}
		
		
			$config = new stdClass();
			$config->wsdl = "../".$wsdl;
			$instance = Zuora_API::getInstance($config);
			$instance->setLocation($endpoint);
		
			if($_POST['Submit'])
			{
				if($instance->login($_POST['uid'], $_POST['pw'])){
					$_SESSION['uid'] =htmlspecialchars($_POST['uid']);
					$_SESSION['pw'] =$_POST['pw'];
					$_SESSION['enviroment']=$_POST['enviroment'];
					$_SESSION['endpoint']=$endpoint;
					ob_flush();
					$_SESSION['start'] = time();
					header("Location: select_location.php");
					$status = "";
				}
				else
				{
					$status = "Login error - Check your userid and/or password";
				}
			  }
			  else 
			  {
			 
			  }
		  }
		}
  
  /*
  function Validate ($parm)
  {
	if (!empty($_POST[$parm]))
  	{
  		if($_POST[$parm])
  		{
   			$_SESSION[$parm] =htmlspecialchars($_POST[$parm]);
  		}
  	}
  }
  */
  function Login($instance)
  {
  # LOGIN 
	$instance->setLocation($_POST['enviroment']);

	if($instance->login($_POST['uid'], $_POST['pw'])){
		return true;
	}
	else
	{
	return false;
	}
	
	
  }
  
 ?>
 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>	PartnerMD - Logon Page: Wellbeing</title>
</head> 
<body>   
 <img src="Images1/logo.png">
<h2>Logon to Wellbeing Payment Processing... </h2>
 <form name="Form1" method="post"   id="Form1">
 <span id="Generalstatus" STYLE="background-color: #ffffff;color: red; font-size: 16pt"><?php echo $status;?></span> <br>

 

	<!--<span id="statString"><?php echo $statString;?></span> <br>-->
  	<table>
  	<tr><td>
	UserID:  
	</td>
	<td>
  	<input name="uid" type="text" id="uid" maxlength="40" size="40"  value="<?php echo htmlentities($uid);?>" /><br>
   	</td></tr>
   	<tr><td>     
	Password:  
	</td>
	<td>
	<input name="pw" type="password" id="pw" maxlength="40" size="40"  value="<?php echo $pw;?>" /> <br>         
	</td></tr>
	<tr><td>     
	
	<td>
		<tr><td>     
	Select the environment 
	</td>
	<td> 
	<select name="enviroment" id="enviroment" maxlength="32" >
		<option value='Production'>Production</option>
		<option value='Sandbox' selected>Sandbox</option>
		</select> 
	
	 
	</td>
	</tr>
	
   	<tr><td colspan=2 align="center" >   <br>  
	<input name="Submit" type="submit" id="Submit" value="Login" /> 
   </td></tr>
   </table>
</body>
</form>