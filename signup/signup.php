<?php

/*
 * Copyright (c) 2010 Zuora, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to use copy,
 * modify, merge, publish the Software and to distribute, and sublicense copies of
 * the Software, provided no fee is charged for the Software. In addition the
 * rights specified above are conditioned upon the following:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * Zuora, Inc. or any other trademarks of Zuora, Inc. may not be used to endorse
 * or promote products derived from this Software without specific prior written
 * permission from Zuora, Inc.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL
 * ZUORA, INC. BE LIABLE FOR ANY DIRECT, INDIRECT OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

// Inialize session
session_start();

// set timeout period in seconds
$inactive = 600;

if (isset($_SESSION['start'])) {
    $session_life = time() - $_SESSION['start'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: Login.php");
    } else {
        // print "active ". $session_life;
        $_SESSION['start'] = time();
    }
} else {
    if (!isset($_SESSION['enviroment'])) {
        header("Location: Login.php");
    }
}

date_default_timezone_set('America/New_York');

require_once '../lib/API.php';
require_once '../config.php';
require_once '../functions.php';

$ValidationStatus          = "";
$CCInfo                    = "CCInfo";
$Name                      = "Name";
$FirstName                 = "FirstName";
$LastName                  = "LastName";
$WorkEmail                 = "WorkEmail";
$StartDate                 = "StartDate";
$WorkPhone                 = "WorkPhone";
$Address1                  = "Address1";
$Address2                  = "Address2";
$City                      = "City";
$State                     = "State";
$Country                   = "Country";
$PostalCode                = "PostalCode";
$CreditCardHolderName      = "CreditCardHolderName";
$CreditCardNumber          = "CreditCardNumber";
$CreditCardExpirationMonth = "CreditCardExpirationMonth";
$CreditCardExpirationYear  = "CreditCardExpirationYear";
$CreditCardType            = "CreditCardType";
$CreditCardPostalCode      = "CreditCardPostalCode";
$validated				   = 'validated';

$status        = '';
$Generalstatus = '';
$CreateStatus  = '';

$PassedLocation = explode('?', $_SERVER['REQUEST_URI']);

$username   = $_SESSION['uid'];
$password   = $_SESSION['pw'];
$enviroment = $_SESSION['enviroment'];

switch ($_SESSION['enviroment']) {
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

// print $username ."<br>";
// print $password."<br>";

/*
 * switch (sizeof($PassedLocation)) {
 * case 1:
 * $_SESSION['URLLoc'] = '';
 * $_SESSION['PhysicianPassedIn'] = 'Need to Update';
 * $URLParms = "?".$_SESSION['URLLoc'];
 * break;
 * case 2:
 * $_SESSION['URLLoc'] = $PassedLocation[1];
 * $_SESSION['PhysicianPassedIn'] = 'Need to Update';
 * $URLParms = "?".$_SESSION['URLLoc'];
 *
 * break;
 * case 3:
 * $_SESSION['URLLoc'] = $PassedLocation[1];
 * $_SESSION['PhysicianPassedIn'] = $PassedLocation[2];
 * $URLParms = "?".$_SESSION['URLLoc']."?".$_SESSION['PhysicianPassedIn'];
 *
 * break;
 * case 4:
 * $_SESSION['URLLoc'] = $PassedLocation[1];
 * $_SESSION['PhysicianPassedIn'] = $PassedLocation[2];
 * $_SESSION['Message'] = $PassedLocation[3];
 * $URLParms = "?".$_SESSION['URLLoc']."?".$_SESSION['PhysicianPassedIn'] ;
 * $Generalstatus=str_replace("%20"," ", $_SESSION['Message']);
 * break;
 * default:
 * $_SESSION['URLLoc'] = '';
 * $_SESSION['PhysicianPassedIn'] = 'Need to Update';
 * break;
 * }
 *
 * $URLParms = "?".$_SESSION['URLLoc']."?".$_SESSION['PhysicianPassedIn'];
 *
 */

$requireFieldsArray = array(
    $CCInfo,
    $Name,
    $FirstName,
    $LastName,
    $CreditCardHolderName,
    $CreditCardNumber,
    $CreditCardExpirationMonth,
    $CreditCardExpirationYear,
    $CreditCardType,
    $Address1,
    $City,
    $State,
    $PostalCode,
    $WorkEmail
);

$fieldsValue                             = array();
$fieldsValue[$CCInfo]                    = getPostValue($CCInfo, '4444111144441111');
$fieldsValue[$Name]                      = getPostValue($Name, ' ');
$fieldsValue[$FirstName]                 = getPostValue($FirstName, '');
$fieldsValue[$LastName]                  = getPostValue($LastName, '');
$fieldsValue[$WorkEmail]                 = getPostValue($WorkEmail, '');
$fieldsValue[$StartDate]                 = getPostValue($StartDate, '');
$fieldsValue[$validated]				 = getPostValue($validated,'NO');

 $fieldsValue[$WorkPhone] = getPostValue($WorkPhone,'');
 $fieldsValue[$Address1] = getPostValue($Address1,'');
 $fieldsValue[$Address2] = getPostValue($Address2,'');
 $fieldsValue[$City] = getPostValue($City,'');
 $fieldsValue[$State] = getPostValue($State,'');
 $fieldsValue[$Country] = getPostValue($Country,'USA');
 $fieldsValue[$PostalCode] = getPostValue($PostalCode,'');
 
$fieldsValue[$CreditCardHolderName]      = getPostValue($CreditCardHolderName, '');
$fieldsValue[$CreditCardNumber]          = getPostValue($CreditCardNumber, '41111111111111111');
$fieldsValue[$CreditCardExpirationMonth] = getPostValue($CreditCardExpirationMonth, '');
$fieldsValue[$CreditCardExpirationYear]  = getPostValue($CreditCardExpirationYear, '');
$fieldsValue[$CreditCardType]            = getPostValue($CreditCardType, 'Visa');
$fieldsValue[$CreditCardPostalCode]      = getPostValue($CreditCardPostalCode, '');

$config       = new stdClass();
$config->wsdl = "../" . $wsdl;

$instance = Zuora_API::getInstance($config);
$instance->setQueryOptions($query_batch_size);

// LOGIN
$instance->setLocation($endpoint);


if ($instance->login($username, $password)) {
    
    // Load the docs
    
    switch ($_SESSION['URLLoc']) {
        case 'Midlothian':
            $Docs = array(
                1 => 'Keller',
                'Cross',
                'Scharpf'
            );
            break;
        case 'Greenville':
            $Docs = array(
                1 => 'Durham',
                'Morse',
                'Burford'
            );
            break;
        case 'Bothell':
            $Docs = array(
                1 => 'Pepe',
                'Regal'
            );
            break;
        case 'Richmond':
            $Docs = array(
                1 => 'Mumper',
                'Ferguson',
                'Pong',
                'Kladder',
                'Burkwall',
                'Spiers',
                'Rowe',
                'Solan',
                'Wein',
                'Schwartz',
                'Bettinger'
            );
            break;
        case 'McLean':
            $Docs = array(
                1 => 'Kitay',
                'Alexander'
            );
            break;
        case 'Lansdowne':
            $Docs = array(
                1 => 'Ditaranto'
            );
            break;
        case 'Sandy Springs':
            $Docs = array(
                1 => 'Beaty',
                'Lemene',
                'Lassiter'
            );
            break;
        case 'Owings Mill':
            $Docs = array(
                1 => 'Sobel',
                'Goldbloom'
            );
            break;
        case 'Charlotte':
            $Docs = array(
                1 => 'Acampora'
            );
            break;
        case 'Hinsdale':
            $Docs = array(
                1 => 'Liu'
            );
            break;
        case 'St. Charles':
            $Docs = array(
                1 => 'Shah'
            );
            break;
    }
    
    // End Loading the Docs
    
    if (empty($_POST['Products'])) {
        $productId = "";
    } else {
        $productId = $_POST['Products'];
    }
    
    if (empty($_POST['RatePlans'])) {
        $rateplanId = "";
    } else {
        $rateplanId = $_POST['RatePlans'];
    }
    
    if (empty($_POST['Charges'])) {
        $chargeIds = "";
    } else {
        $chargeIds = $_POST['Charges'];
    }
    
    $nowdate = date('Y-m-d\TH:i:s', time());
    if ($_SESSION['URLLoc'] != "") {
        // print "select Id ,Name from Product where Name = '" .$_SESSION['URLLoc']. "' and EffectiveEndDate > '".$nowdate ."' and EffectiveStartDate <'".$nowdate ."'" ;
        $products = queryAll($instance, "select Id ,Name from Product where name = '" . $_SESSION['URLLoc'] . "'  and EffectiveEndDate > '" . $nowdate . "' and EffectiveStartDate <'" . $nowdate . "'");
    } else {
        $products = queryAll($instance, "select Id ,Name from Product where EffectiveEndDate > '" . $nowdate . "' and EffectiveStartDate <'" . $nowdate . "'");
    }
    
    if (count($products) == 1) {
        $productId = $products[0]->Id;
    }
    
    if (isset($productId) && strlen($productId) == 32) {
        $ValidRatePlans = "name like 'Well%'";
        $rateplans      = queryAll($instance, "select Id,Name from ProductRatePlan where " . $ValidRatePlans . " and ProductId='" . $productId . "'");
        
        // if(count($rateplans)==1){
        $rateplanId = $rateplans[0]->Id;
        // }
        
        if (isset($rateplanId) && strlen($rateplanId) == 32) {
            $rateplancharges = queryAll($instance, "select Id, Name, AccountingCode, DefaultQuantity, Type, Model, ProductRatePlanId from ProductRatePlanCharge where ProductRatePlanId ='" . $rateplanId . "' and Name like 'Wellbeing%'" );
            
            if ($chargeIds == null) {
                $chargeIds = array();
                foreach ($rateplancharges as $rc) {
                    $chargeIds[] = $rc->Id;
                }
            }
        }
    }
    
    if (!empty($_POST['Submit'])) {
        if ($_POST['Submit']) {
        	$gValidated=$_POST['validated'];
        	if ($gValidated != 'YES') {
        		//$status = $status . " <br>1 gValidated:" . $gValidated."-" ; 
        		validate();
        		//$status = $status . " <br>2 gValidated:" . $gValidated."-" ; 
        	}
        	else
        	{
	            if (validate()) {
	                
	                switch (substr($gCreditCardNumber, 0, 1)) {
	                    case 3:
	                        $creditCardType = "AmericanExpress";
	                        break;
	                    case 4:
	                        $creditCardType = "Visa";
	                        break;
	                    case 5:
	                        $creditCardType = "MasterCard";
	                        break;
	                    default:
	                        $creditCardType = "Visa";
	                        break;
	                }
	                // Build the Account object
	                
	                $RetAcct[0] = makeAccount($gFullName, "USD", "Draft", $_SESSION['URLLoc']);
	                // Create the Account in Zuora, it returns an object to indicate success
	                $result2    = $instance->create($RetAcct);
	                
	                // Check if successful
	                if (isset($result2)) { // Successful account create
	                    if ($result2->result->Success) {
	                        $CreateStatus     = "Account created for " . $gFullName . "<br>";
	                        // If we have the id to the account then build a paymentmethod object
	                        $PaymentMethod[0] = makePaymentMethod($gFullName, $creditCardType, $gCreditCardNumber, $gCreditCardExpirationMonth, $gCreditCardExpirationYear, $result2->result->Id);
	                        // $gCreditCardNumber
	                        
	                        // Now create the PaymentMethod in Zuora and attach it to the account
	                        // the call returns the id of the paymentMethod to be passed to the account create
	                        $result = $instance->create($PaymentMethod);
	                        if (isset($result)) { // Sccessful payment method create
	                            
	                            if ($result->result->Success) {
	                                $CreateStatus        = $CreateStatus . "Payment Method created for " . $gFullName . "<br>";
	                                // Now create a contact so we can make the account active
                                    //function makeContact($            firstName,$lastName,$address1,$address2,$city,$state,$country,$postalCode,$workMail,$workPhone,$accountId=null,$WorkEmail){
	                                $RetContact[0]       = makeContact($gFirstName, $gLastName, $fieldsValue['Address1'], '', $fieldsValue['City'], $fieldsValue['State'], 'USA', $fieldsValue['PostalCode'], '', '', $result2->result->Id, $fieldsValue[$WorkEmail]);
	                                $resultCreateContact = $instance->create($RetContact);
	                                
	                                if ($resultCreateContact->result->Success) // check the success of create contact
	                                    {
	                                    
	                                    // createMessage($resultCreateContact);
	                                    
	                                    // Now update the account to active
	                                    $RetAcct[0]   = makeUpdateAccount($result2->result->Id, 'Active', $resultCreateContact->result->Id);
	                                    $CreateStatus = $CreateStatus . "ID: " . $RetAcct[0]->Id . "<br>";
	                                    $resultUpdate = $instance->update($RetAcct);
	                                    
	                                    // Now create a Subscription
	                                    
	                                    $subscriptionName = "New Signup - From Wellbeing(" . date('m/d/Y h:i:s') . ")";
	                                    // print $_SESSION['PhysicianPassedIn'] . " - " . $_SESSION['URLLoc'];
	                                    $subscription     = makeSubscription($subscriptionName, null, $Name, date('m/d/Y'), $_POST['Docs'], $_SESSION['URLLoc'], $_POST['SalesRep'], $_POST['StartDate'], 3);
	                                    $gsubscriptionID  = $subscription->Id;
                                        $CreateStatus = $CreateStatus . "number of items in chargeIds: ".sizeof($chargeIds) . "<br>";  
                                        foreach ($chargeIds as $cid) 
                                        {   
                                        	  
                                        	$CreateStatus = $CreateStatus . "cid-".$cid . "-<br>";  
                                        }  

                                        $CreateStatus = $CreateStatus . "number of items in rateplancharges: ".sizeof($rateplancharges) . "<br>";  
                                        foreach ($rateplancharges as $rr) 
                                        {   
                                        	$CreateStatus = $CreateStatus . "rc->Id-".$rr->Id ."-<br>";  
                                        	 
                                        } 
	                            
	                                    $zSubscriptionData = makeSubscriptionData($subscription, $chargeIds, $rateplancharges, $rateplanId);
	                                    $zSubscribeOptions = new Zuora_SubscribeOptions(false, false);
	                                    // $result = $instance->PMD_CreateAcct($account, $zSubscriptionData);
	                                    // $max = sizeof($PaymentMethod);
	                                    // $CreateStatus = $CreateStatus ."Number of items selected:". $max ."<br>" ;
	                                    // for($i = 0; $i < $max;$i++)
	                                    // {
	                                    $resultSub = $instance->subscribe($RetAcct[0], $zSubscriptionData, $RetContact[0], $PaymentMethod[0], $zSubscribeOptions, $RetContact[0]);
	                                    // $resultSub = $instance->subscribe($RetAcct[0], $zSubscriptionData, $RetContact[0], $PaymentMethod[0], $zSubscribeOptions, $RetContact[0]);
	                                    if ($resultSub->result->Success) // check the success of subscribe
	                                        {
	                                        $CreateStatus = $CreateStatus . "Subscription(New Signup - From Open House(" . date('m/d/Y h:i:s') . ")) Created <br>";
	                                        
	                                        // Now create an invoice
	                                        
	                                        // GENERATE & QUERY & POST INVOICE
	                                        
	                                        $accountId = $result2->result->Id;
                                            $lStartDate = new DateTime($_POST['StartDate']);
                                            $formattedDate = date_format($lStartDate ,'Y-m-d\TH:i:s');
	                                        if ($accountId && date('Y-m-d\TH:i:s') >= $formattedDate) {
	                                            $invoiceDate  = date('Y-m-d\TH:i:s');
	                                            $targetDate   = date('Y-m-d\TH:i:s');
	                                            $result       = generateInvoice($instance, $accountId, $invoiceDate, $targetDate);
	                                            $success      = $result->result->Success;
	                                            $msg          = ($success ? $result->result->Id : $result->result->Errors->Code . " (" . $result->result->Errors->Message . ")");
	                                            $CreateStatus = $CreateStatus . "Invoice Created.<br>";
	                                            
	                                            if ($success) {
	                                                // QUERY Invoice
	                                                $query   = "SELECT Id, InvoiceNumber,Status FROM Invoice WHERE id = '" . $result->result->Id . "'";
	                                                $records = queryAll($instance, $query);
	                                                // $CreateStatus = $CreateStatus . "Invoice Queried ($query): " . $records[0]->InvoiceNumber ." ". $records[0]->Status . "<br>";
	                                                
	                                                // POST Invoice
	                                                $result       = postInvoice($instance, $result->result->Id);
	                                                $success      = $result->result->Success;
	                                                $CreateStatus = $CreateStatus . "Invoice Posted :" . ($result->result->Success ? "Success <br>" : $result->result->Errors->Code . " (" . $result->result->Errors->Message . ")<br>");
	                                                
	                                                if ($success) {
	                                                	$CreateStatus = $CreateStatus . "In next section";
	                                                    // DO PAYMENT$success3 ? "Success"
	                                                    $PaymentSuccess = createAndApplyPayment($instance, $accountId, $CreateStatus);
	                                                    if ($PaymentSuccess) {
	                                                        $Generalstatus          = "Created Account and processed payment for " . $gFullName;
	                                                        $_SESSION['MemberName'] = $Generalstatus;
	                                                        header("Location: success.php" . $URLParms); // ."?".$Generalstatus);
	                                                    } else {
	                                                        $Generalstatus = "Fail";
	                                                    }
	                                                }
	                                            }
	                                        }
	                                    } // check the success of subscribe
	                                    else // check the success of subscribe
	                                        {
	                                        createMessage($resultSub);
	                                    }
	                                } // check the success of create contact
	                                // }
	                                else // check the success of create contact
	                                    {
	                                    createMessage($resultCreateContact);
	                                }
	                                
	                                // Now create a payment
	                                // $Retpayment[0] = makeAPayment($result->result->Id, $RetAcct[0]->Id, 75, 75, 0, date('Y-m-d') ."T" .date('h:i:s'), date('Y-m-d') ."T" .date('h:i:s'), 'Electronic' );
	                                // $resultCreatePayment = $instance->create($Retpayment);
	                                // createMessage($resultCreatePayment);
	                                
	                                // createMessage($resultUpdate);
	                            } // iif($result->result->Success)
	                        } // if(isset($result))
	                        else {
	                            createMessage($result);
	                        } // else $result success
	                    } // if($result2->result->Success)
	                    else {
	                        createMessage($result2);
	                    } // else --> isset
	                } // if empty post
	                // createMessage($result);
	                
	                $status = $status . "Created Account for: " . $gFullName . " in Richmond<br> ";
	                // subscribedata($instance,$chargeIds,$rateplancharges,$rateplanId);
	            }
        	}
        }
        
        else {
            $status = "<b>Login Failed</b>";
        }
    }
}
function createMessage($subscriberesult)
{
    global $status;
    
    $status = "";
    if (isset($subscriberesult)) {
        if ($subscriberesult->result->Success) {
            $status = $status . "<b>Create Result: Success</b>";
            // $status = $status . "<br>&nbsp;&nbsp;Account Id: " . $subscriberesult->result->AccountId;
            // $status = $status . "<br>&nbsp;&nbsp;Account Number: " . $subscriberesult->result->AccountNumber;
            // $status = $status . "<br>&nbsp;&nbsp;Subscription Id: " .$subscriberesult->result->SubscriptionId;
            // $status = $status . "<br>&nbsp;&nbsp;Subscription Number: " .$subscriberesult->result->SubscriptionNumber;
            // $status = $status . "<br>&nbsp;&nbsp;Invoice Id: " .$subscriberesult->result->InvoiceId;
            // $status = $status . "<br>&nbsp;&nbsp;Invoice Number: " .$subscriberesult->result->InvoiceNumber;
        } else {
            $status = $status . "<b>Create Result: Failed</b>";
            if (is_array($subscriberesult->result->Errors)) {
                foreach ($subscriberesult->result->Errors as $err) {
                    $status = $status . "<br>&nbsp;&nbsp;Error Code: " . $err->Code;
                    $status = $status . "<br>&nbsp;&nbsp;Error Message: " . $err->Message;
                }
            } else {
                $status = $status . "<br>&nbsp;&nbsp;Error Code: " . $subscriberesult->result->Errors->Code;
                $status = $status . "<br>&nbsp;&nbsp;Error Message: " . $subscriberesult->result->Errors->Message;
            }
        }
    }
}
function subscribedata($instance, $chargeIds, $rateplancharges, $rateplanId)
{
    global $CCInfo;
    global $Name;
    global $FirstName;
    global $LastName;
    global $StartDate;
    global $WorkEmail;
    global $WorkPhone;
    global $Address1;
    global $Address2;
    global $City;
    global $State;
    global $Country;
    global $PostalCode;
    global $CreditCardHolderName;
    global $CreditCardNumber;
    global $CreditCardExpirationMonth;
    global $CreditCardExpirationYear;
    global $CreditCardType;
    global $CreditCardPostalCode;
    
    $subscriptionName = " New Signup - From Open House(" . date('m/d/Y h:i:s') . ")";
    $account          = makeAccount(getPostValue($Name), 'USD', 'Draft');
    // $contact = makeContact(getPostValue($FirstName),getPostValue($LastName),getPostValue($Address1),getPostValue($Address2),getPostValue($City),getPostValue($State),getPostValue($Country),getPostValue($PostalCode),getPostValue($WorkEmail),getPostValue($WorkPhone));
    // $paymentmethod = makePaymentMethod(getPostValue($CreditCardHolderName), getPostValue($Address1),getPostValue($Address2), getPostValue($City), getPostValue($State), getPostValue($Country), getPostValue($PostalCode), getPostValue($CreditCardType), getPostValue($CreditCardNumber), getPostValue($CreditCardExpirationMonth),getPostValue($CreditCardExpirationYear));
    
    $subscription = makeSubscription($subscriptionName, null);
    
    $zSubscriptionData = makeSubscriptionData($subscription, $chargeIds, $rateplancharges, $rateplanId);
    
    // $zSubscribeOptions = new Zuora_SubscribeOptions(false,false);
    $result = $instance->PMD_CreateAcct($account, $zSubscriptionData);
    // $result = $instance->subscribe($account, $zSubscriptionData, $contact, $paymentmethod, $zSubscribeOptions, $contact);
    
    createMessage($result);
}
function ShortCreateSubscription($pName, $instance, $chargeIds, $rateplancharges, $rateplanId, $account, $contact, $paymentMethod, $StartDate__c, $Physician__c, $Location__c)
{
    global $gsubscriptionID;
    $subscriptionName = " New Signup - From Open House(" . date('m/d/Y h:i:s') . ")";
    $subscription     = makeSubscription($subscriptionName, null);
    $gsubscriptionID  = $subscription->Id;
    $CreateStatus     = $CreateStatus . "$subscription->Id: " . $subscription->Id . "<br>";
    
    $Member__c = $pName;
    
    $zSubscriptionData = makeSubscriptionData($subscription, $chargeIds, $rateplancharges, $rateplanId, $StartDate__c, $Physician__c, $Location__c);
    $zSubscribeOptions = new Zuora_SubscribeOptions(false, false);
    // $result = $instance->PMD_CreateAcct($account, $zSubscriptionData);
    $resultSub         = $instance->subscribe($account, $zSubscriptionData, $contact, $paymentMethod, $zSubscribeOptions, $contact);
    createMessage($resultSub);
    return $resultSub;
}
function ClearData()
{
    /*
     * global $requireFieldsArray;
     * //$fieldsValue["CCInfo"] ="";
     * $_POST['CCInfo']="";
     * document.getElementById("CCInfo").value="";
     * foreach($requireFieldsArray as $ea){
     * $fieldsValue[$ea]="";
     * }
     */
}
function validate()
{
    global $productId;
    global $rateplanId;
    global $chargeIds;
    global $requireFieldsArray;
    global $ValidationStatus;
    global $gCreditCardNumber;
    
    global $gValidated;

   // $fieldsValue[$address1], '', $fieldsValue[$city], $fieldsValue[$state], 'USA', $fieldsValue[$postalCode]
 


    if (isEmpty($productId))
        return false;
    if (isEmpty($rateplanId))
        return false;
    if (isEmpty($_POST['Docs'])) {
        $ValidationStatus = "Please select a Doctor";
        return false;
    }
    
    if (isEmpty($_POST['CCInfo'])) {
        $ValidationStatus = "Please enter a cc number";
        return false;
    }
    // CreditCardNumber
    // if(!isset($chargeIds)) return false;
    
    if (isset($chargeIds)) {
        if (empty($_POST['Charges'])) {
            //if (sizeof ( $chargeIds ) < 1) {
            $ValidationStatus = "Please select a value in the charge list";
            
            return false;
        } else {
            //$ValidationStatus = "";
        }
    } else {
        $ValidationStatus = "Please select a value in the charge list";
        
        return false;
    }
    
    // if(sizeof($chargeIds)>1) return false;
    
    // Now update the cc info
    global $gAddress1 ;
    global $gCity ;
    global $gState  ;
    global $gPostalCode  ;
    global $gFirstName;
    global $gLastName;
    global $gFullName;
    global $gCreditCardExpirationMonth;
    global $gCreditCardExpirationYear;
    global $gCreditCardNumber;
    global $fieldsValue;
    global $Name;

       //Set the global address vars
    //$fieldsValue["Address1"]=$_POST["Address1"];
    //$gCity=$fieldsValue["City"];
    //$gState=$fieldsValue["State"];
    //$gPostalCode =$fieldsValue["PostalCode"];
    
    if (!isEmpty($fieldsValue["CCInfo"])) {
        $ParsedString               = explode("^", $fieldsValue["CCInfo"]);
        $ParsedName                 = explode("/", $ParsedString[1]);
        $gCreditCardNumber          = substr($ParsedString[0], 2, strlen($ParsedString[0]) - 2);
        $gFirstName                 = substr($ParsedString[1], stripos($ParsedString[1], "/") + 1, 99);
        $gLastName                  = $ParsedName[0];
        if ($gValidated != 'YES') {
        	$gFullName                  = trim($gLastName) . ", " . trim($gFirstName) . " - Wellbeing"  ;
        }
        else
        {
        	$gFullName = $_POST['Name'];
        }
        $Name                       = trim($gFirstName) . " " . trim($gLastName);  //$gFullName;
        $gCreditCardExpirationMonth = substr($ParsedString[2], 2, 2);
        $gCreditCardExpirationYear  = "20" . substr($ParsedString[2], 0, 2);


    }
    // end update cc info
     
    //Check required field
    if (!isEmpty($fieldsValue["CreditCardNumber"])) {
        $valid = true;
        foreach($requireFieldsArray as $ea){
            $valid = $valid && validateValue($ea);
        }  
     }
    $gValidated='YES';
    return $valid ; //true;
}
function validateValue($param)
{
    global $status;
    global $fieldsValue;
    
    $empty = isEmpty($fieldsValue[$param]);
    if ($empty) {
        $status = $status . $param . " is a required value.<br>";
        $ValidationStatus = $status;
    }
    
    return !$empty;
}



function StateDropdown($post=null, $type='abbrev') {
    $states = array(
        array('AK', 'Alaska'),
        array('AL', 'Alabama'),
        array('AR', 'Arkansas'),
        array('AZ', 'Arizona'),
        array('CA', 'California'),
        array('CO', 'Colorado'),
        array('CT', 'Connecticut'),
        array('DC', 'District of Columbia'),
        array('DE', 'Delaware'),
        array('FL', 'Florida'),
        array('GA', 'Georgia'),
        array('HI', 'Hawaii'),
        array('IA', 'Iowa'),
        array('ID', 'Idaho'),
        array('IL', 'Illinois'),
        array('IN', 'Indiana'),
        array('KS', 'Kansas'),
        array('KY', 'Kentucky'),
        array('LA', 'Louisiana'),
        array('MA', 'Massachusetts'),
        array('MD', 'Maryland'),
        array('ME', 'Maine'),
        array('MI', 'Michigan'),
        array('MN', 'Minnesota'),
        array('MO', 'Missouri'),
        array('MS', 'Mississippi'),
        array('MT', 'Montana'),
        array('NC', 'North Carolina'),
        array('ND', 'North Dakota'),
        array('NE', 'Nebraska'),
        array('NH', 'New Hampshire'),
        array('NJ', 'New Jersey'),
        array('NM', 'New Mexico'),
        array('NV', 'Nevada'),
        array('NY', 'New York'),
        array('OH', 'Ohio'),
        array('OK', 'Oklahoma'),
        array('OR', 'Oregon'),
        array('PA', 'Pennsylvania'),
        array('PR', 'Puerto Rico'),
        array('RI', 'Rhode Island'),
        array('SC', 'South Carolina'),
        array('SD', 'South Dakota'),
        array('TN', 'Tennessee'),
        array('TX', 'Texas'),
        array('UT', 'Utah'),
        array('VA', 'Virginia'),
        array('VT', 'Vermont'),
        array('WA', 'Washington'),
        array('WI', 'Wisconsin'),
        array('WV', 'West Virginia'),
        array('WY', 'Wyoming')
    );
    
    $options = '<option value=""></option>';
    
    foreach ($states as $state) {
        if ($type == 'abbrev') {
        $options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[0].'</option>'."\n";
    } elseif($type == 'name') {
        $options .= '<option value="'.$state[1].'" '. check_select($post, $state[1], false) .' >'.$state[1].'</option>'."\n";
    } elseif($type == 'mixed') {
        $options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[1].'</option>'."\n";
    }
    }
        
    echo $options;
}

/**
 * Check Select Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_select($i,$m,$e=true) {
    if ($i != null) { 
        if ( $i == $m ) { 
            $var = ' selected="selected" '; 
        } else {
            $var = '';
        }
    } else {
        $var = '';  
    }
    if(!$e) {
        return $var;
    } else {
        echo $var;
    }
}






?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
<title>PartnerMD - WellBeing Payment Processing</title>
</head>

<body>

	<form name="Form1" method="post"
		action="<?php
echo $_SERVER['REQUEST_URI'];
?>" id="Form1">
<?php
$fieldsValue[$StartDate] = date("m/d/Y");
?>
		<!-- <span id="Generalstatus" STYLE="background-color: #ffffcc;color: red; font-size: 16pt"><?php
echo $Generalstatus;
?></span> <br>-->

<table>
    <tr>
        <td width="50%">
            <img src="../Images/logo-sm.png">       
        </td>
        <td width="50%" align="right"> 
            <span id="sizeof($chargeIds)" STYLE="background-color: #ffffcc; color: red; font-size: 16pt">
                <?php 
                    if ($ValidationStatus != null){echo $ValidationStatus;  }
                    if ($ValidationStatus != null && $status!=null){?><br><?php }
                    if ($status!=null){echo $status;}
                ?>
            </span>
        </td>   
    </tr>
</table>


		
		<table border=1>
			<tr>
				<td>
					<table>
                        <tr><td colspan="3"><h3>Select a Product</h3></td></tr>
						<tr align="center">
							<td>Product *</td>
							<td>Rate Plan *</td>
                            
                            <td>Charges *</td>
						</tr>
						<tr valign="top">
							<td><label for="Products"> </label> <select name="Products"
								onchange="document.Form1.submit()" id="Products" maxlength="32">
				 			<?php if ($products == null) {?>
									<option value=''>-- ERROR OCCUR --</option>
				 			<?php } else {?>
				 				<option value=''>-- SELECT A PRODUCT --</option>
				 			<?php     foreach ($products as $p) {?>
				 				<option value="<?php echo $p->Id;?>"
							<?php if ($productId == $p->Id) {?> selected <?php }?>> <?php echo htmlentities($p->Name);?> 
                                </option>
				 			<?php     }}?>				  
				            </select>
                            </td>


							<td><label for="RatePlans"> </label> <select name="RatePlans" 
								onchange="document.Form1.submit()" id="RatePlans" maxlength="32"> 
									<?php if ($rateplans == null) {?>
									<option value=''>-- SELECT A PRODUCT ABOVE --</option>
                                    <?php } else {    
                                        foreach ($rateplans as $r) { $name = $r->Name . ($r->AccountingCode ? (" (" . $r->AccountingCode . ")") : "");?>
										<option value="<?php echo $r->Id;?>"
										<?php if ($rateplanId == $r->Id) {?> selected <?php }?>> 
                                        <?php echo htmlentities($name);?> 
                                        </option>
									<?php    }}?>
								</select></td>
								  
							<td valign="top"><label for="Charges"> </label>
                            <select size="4"
								name="Charges[]" multiple="multiple" id="Charges" maxlength="32"> 
                                <?php if ($rateplancharges == null) {?>
								<option value=''>-- SELECT A RATE PLAN ABOVE --</option>
							<?php } else {    foreach ($rateplancharges as $c) {?>
								<option value="<?php echo $c->Id;?>"
								 <?php	foreach($chargeIds as $cid) { 
									if(trim($c->Id)==trim($cid)) echo ' selected="selected"';  }   										 
                                    if ($c->Name == "Membership Activation Fee") {?> selected
										<?php  } ?>> 
                                        <?php echo htmlentities($c->Name); ?> </option>
							             <?php    }}?>
								</select></td>
						</tr>
						</td>
                    </table>
<table border="1">
<tr><td>
		<table cellspacing="5" cellpadding="5" valign="top" >
			<tr>
				<td>
					

					<table>



						<tr>
                            <td colspan="2">
                                <h2>Member Information</h2>
                            </td>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <h2>Billing Information</h2>
                            </td>
                        </tr>
                        <tr>
							<td><label for="SalesRep"> Sales Rep</label></td>
							<td> 
                                <select name="SalesRep" id="SalesRep" maxlength="32">
									<option value="Jaime Rolfe">Jaime Rolfe</option>
									<option value="Catlin Rankin">Catlin Rankin</option>
									<option value="Michael Church">Michael Church</option>
									<option value="Patty Keneagy">Patty Keneagy</option>
									<option value="Chase Chewning">Chase Chewning</option>
									<option value="Other">Other</option>
							     </select>
                            </td>
                            <td>&nbsp;</td>
                            <td><label for="CreditCardType"> Card Type</label></td>
                            <td><select name="CreditCardType" id="CreditCardType" maxlength="32">
                                <option <?php  if ($fieldsValue[$CreditCardType] == 'Visa') {?>
                                        selected <?php }?> value="Visa">Visa </option>
                                <option <?php if ($fieldsValue[$CreditCardType] == 'MasterCard') {?>
                                        selected <?php }?> value="MasterCard">MasterCard</option>
                                <option <?php if ($fieldsValue[$CreditCardType] == 'AmericanExpress') {?>
                                        selected <?php }?> value="AmericanExpress">AmericanExpress</option>
                                </select>
                            </td>
						</tr>

						<tr>
							<td><label for="Doc"> Physician</label></td>
							<td><label for="Products"> </label> 
                            <select name="Docs" id="Docs" maxlength="32">
				 			<?php if ($Docs == null) { ?>
									<option value=''>-- ERROR OCCUR --</option>
				 			<?php } else {?>
				 				
				 			<?php     foreach ($Docs as $p) {
                                if ($p->Name != "Miscellaneous Fees") {?>
				 				<option value="<?php  echo $p; ?>"> 
                                <?php echo htmlentities($p);?> </option>
				 			<?php         }    }}?>
				  
				            </select>
                            </td>
                            <td>&nbsp;</td>
                            <td><label for="CreditCardNumber"> Card Number</label></td>
                            <td><input name="CreditCardNumber" type="password"
                                id="CreditCardNumber" maxlength="40" size="20"
                                value="<?php if (isset($gCreditCardNumber)) { echo $gCreditCardNumber;}?>" />
                            </td>						
                        </tr>
						
						<tr>
							<td><label for="StartDate"> Start Date <snall>(m/d/yyyy) </snall> </label></td>
							<td><input name="StartDate" type="text" id="StartDate"
								maxlength="10" size="10"
								value="<?php echo htmlentities($fieldsValue[$StartDate]);?>" />


							</td>
                            <td>&nbsp;</td>
                            <td><label for="CreditCardHolderName"> Name on Card</label></td>
                            <td><input name="CreditCardHolderName" type="text"
                                id="CreditCardHolderName" maxlength="40" size="20"
                                value="<?php if (isset($Name)) {    echo $Name;}?>" />
                            </td>
						</tr>
						<tr>
							<td><label for="CCInfo"> CCInfo *</label></td>
							<td><input name="CCInfo" type="password" id="CCInfo"
								maxlength="100" size="20"
								value="<?php echo htmlentities($fieldsValue[$CCInfo]); ?>"
								autofocus />
								<input type="hidden" name="validated" value="<?php echo $gValidated;?>" />


							</td>
                             <td>&nbsp;</td>
                            <td><label for="CreditCardExpirationMonth"> Expiration <snall>(mm/yyyy) </snall></label>
                            </td>
                            <td><input name="CreditCardExpirationMonth" type="text"
                                id="CreditCardExpirationMonth" maxlength="2" size="2"
                                value="<?php if (isset($gCreditCardExpirationMonth)) {echo $gCreditCardExpirationMonth;}?>" />

                                <input name="CreditCardExpirationYear" type="text"
                                id="CreditCardExpirationYear" maxlength="4" size="4"
                                value="<?php if (isset($gCreditCardExpirationYear)) {echo $gCreditCardExpirationYear;}?>" />

                            </td>
						</tr>
						
                        <tr>
                            <td><label for="WorkEmail"> *Email </label></td>
                            <td><input name="WorkEmail" type="text" id="WorkEmail"
                                maxlength="80" size="20"
                                value="<?php echo htmlentities($fieldsValue[$WorkEmail]);?>" />
                            </td>
                           <td>&nbsp;</td>
                            <td><label for="Address1">* Address </label></td>
                            <td><input name="Address1" type="text"
                                id="Address1" maxlength="40" size="20"
                                value="<?php echo htmlentities($fieldsValue[$Address1]); ?>" />
                            </td>
                        </tr>
						<tr>
							<td><label for="Name"> Account Name </label></td>
							<td><input name="Name" type="text" id="Name" maxlength="40"
								size="20"
								value="<?php if (isset($gFullName)) {    echo $gFullName;}?>" />
							</td>
                           <td>&nbsp;</td>
                            <td><label for="City">* City </label></td>
                            <td><input name="City" type="text"
                                id="City" maxlength="40" size="20"
                                value="<?php echo htmlentities($fieldsValue[$City]);?>" />
                            </td> 
						</tr>
						<tr>
							<td><label for="FirstName"> First Name </label></td>
							<td><input name="FirstName" type="text" id="FirstName"
								maxlength="40" size="20"
								value="<?php if (isset($gFirstName)) {    echo $gFirstName;}?>" />
							</td>
                           <td>&nbsp;</td>
                            <td><label for="State">* State </label></td>
                            <td> <select name="State"><?php echo StateDropdown('VA', 'mixed'); ?></select>
                            </td> 
						</tr>
						<tr>
							<td><label for="LastName"> Last Name </label></td>
							<td><input name="LastName" type="text" id="LastName"
								maxlength="80" size="20"
								value="<?php if (isset($gLastName)) {    echo $gLastName;}?>" />
                            </td>
                           <td>&nbsp;</td>
                                <td><label for="PostalCode">* ZIP/Postal Code </label></td>
                                <td><input name="PostalCode" type="text"
                                    id="PostalCode" maxlength="40" size="20"
                                    value="<?php echo htmlentities($fieldsValue[$PostalCode]); ?>" />
                                </td> 
						 
					</table>
				</td>
			</tr>
            <tr>
                <td colspan=4 align=center>
                <table border="1"> <tr><td>
                <input name="Submit" type="submit" 
                    id="Submit" value="Submit" /> 
                </td></tr></table>
                    <input type="reset" id="blagh" style="display: none" />
                   <!-- <hr size=10 noshade> ** Select the Sales Rep and the Physician,
                    then enter an email if you have one. <br> Once that is entered
                    place the cursor on the CCInfo field and swipe the card. <br>
                <br>
                 <hr>Ignore the field below here, they get automatically populated
                    from the card swipe.
                    <hr>-->
                </td>
            </tr> 
		</table>
       </td></tr></table>
                        </table></td>
                        </tr>
		</table>

                    </td>
                    </tr>
        
        </table>

		<hr>
		<span id="Environment" STYLE="background-color: #ffffff; color: red">Working in: <?php
echo $enviroment;
?></span>
		<br>

	</form>
</body>
</html>
