<?php


	require_once '../lib/API.php';
	
	require_once '../config.php';
	require_once '../functions.php';
	session_cache_expire( 1 );
	// Inialize session
	session_start();
	
	// set timeout period in seconds
	$inactive = 600;
	
	if(isset($_SESSION['start']) ) {
		$session_life = time() - $_SESSION['start'];
		if($session_life > $inactive){
			session_destroy();
			header("Location: Login.php");
		}
		else
		{
			//print "active ". $session_life;
			$_SESSION['start'] = time();
		}
	}
	else
	{
	if(isset($_SESSION['enviroment']) ) 
		{
			header("Location: Login.php");
		}
	}
	
	//$_SESSION['start'] = time();
	 

	$uid='';
  	$pw='';
 	$statString='';
 	$status="";
 	$enviroment=$_SESSION['enviroment'];
	$endpoint =$_SESSION['endpoint'];
	$uid=$_SESSION['uid'] ;
	$pw=$_SESSION['pw'];
	$config = new stdClass();
	$config->wsdl = "../".$wsdl;
	$instance = Zuora_API::getInstance($config);
	$instance->setQueryOptions($query_batch_size);
	$instance->setLocation($endpoint);
	$instance->login($uid, $pw);
	$nowdate =date('Y-m-d\TH:i:s',time());
			
	$products = queryAll($instance,"select Id ,Name from Product where EffectiveEndDate > '".$nowdate ."' and EffectiveStartDate <'".$nowdate ."' and   name <>'Subscription Discount'  " );
		
		if (count($products)>0){
			$productId = $products[0]->Id;
			//$status= "Got Some";
		}
		else
		{
			 
		}


	 if (!empty($_POST['Submit']))
	  {	  
/*		switch ($_POST['enviroment']) {
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
		
*/		
		if($_POST['Submit'])
		{
			//$ProdArray=explode("^",$_POST['Products']);
			$_SESSION['URLLoc']=$_POST['Products'];//$ProdArray[0];
			$_SESSION['PhysicianPassedIn']='Open House';//$ProdArray[1];
			
			//	$_SESSION['enviroment']=$_POST['enviroment'];
			header("Location: signup.php".$URLParms);
			$status = "";
			 // $status= "Products: " . $_POST['Products'];
		  }
		  else 
		  {
			echo "Else";
		  }
	  }
	   
  
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
<title>	PartnerMD - Select Location: Wellbeing Payment Processing</title>
</head> 
<body> 
 <img src="../Images/logo.png">
<h2>Select Location - Wellbeing Payment Processing</h2>
 
<!--<form name="Form1" method="post"  action="<?php echo  "signup.php"; ?>" id="Form1">   -->
<form name="Form1" method="post"   id="Form1">  
<!-- <span id="Generalstatus" STYLE="background-color: #ffffff;color: red; font-size: 16pt">status: <?php echo $status;?></span> <br>
 -->

	<!--<span id="statString"><?php echo $statString;?></span> <br>-->
  	<table>
  	
	<tr><td>     
	Select a  Location:  
	</td>
	<td> 
	
	    <label for="Products">  
                         </label> 
                 
              
                <select name="Products" id="Products" maxlength="32" >
				 			<?php if($products==null){?>
									<option value=''>-- ERROR OCCUR --</option>
				 			<?php
				 			}else { 
				 				?>
				 				<option value=''>-- SELECT A PRODUCT --</option>
				 			<?php 
				 			  foreach($products as  $p){
				 			if ($p->Name!="Miscellaneous Fees"){
				 			?>
				 				<option value="<?php echo $p->Name;?>" <?php if ($productId==$p->Id){ ?> selected <?php }?>> <?php echo htmlentities($p->Name);?> </option>
				 			<?php
				 			}}}
				 			?>
				  
				</select> 
	
	<!--
	<select name="Products" id="Products" maxlength="32" >
		<option value='Bothell^Update'>Bothell</option>
		<option value='Richmond^Update'>Richmond</option>
		<option value='Midlothian^Update'>Midlothian</option>
		<option value='Greenville^Update'>Greenville</option>
		<option value='Lansdowne^Update'>Lansdowne</option>
		<option value='McLean^Update'>McLean</option>
		<option value='Charlotte^Update'>Charlotte</option>
		<option value='Owings Mill^Update'>Owings Mill</option>
		<option value='Hinsdale^Update'>Hinsdale</option>
		<option value='Sandy Springs^Update'>Sandy Springs</option>		
		</select> 
	
	 -->
	</td><td>    
	<input name="Submit" type="submit" id="Submit" value="Submit" /> 
   </td>
	</tr>
	
   </table>
   <br>
   <br><hr>
   <span id="Generalstatus" STYLE="background-color: #ffffff;color: red">Working in: <?php echo $enviroment;?></span> <br>
	
</body>
</form>