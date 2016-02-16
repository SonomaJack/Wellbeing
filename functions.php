<?php
/*
 *    Copyright (c) 2010 Zuora, Inc.
 *    
 *    Permission is hereby granted, free of charge, to any person obtaining a copy of 
 *    this software and associated documentation files (the "Software"), to use copy, 
 *    modify, merge, publish the Software and to distribute, and sublicense copies of 
 *    the Software, provided no fee is charged for the Software.  In addition the
 *    rights specified above are conditioned upon the following:
 *    
 *    The above copyright notice and this permission notice shall be included in all
 *    copies or substantial portions of the Software.
 *    
 *    Zuora, Inc. or any other trademarks of Zuora, Inc.  may not be used to endorse
 *    or promote products derived from this Software without specific prior written
 *    permission from Zuora, Inc.
 *    
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *    FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL
 *    ZUORA, INC. BE LIABLE FOR ANY DIRECT, INDIRECT OR CONSEQUENTIAL DAMAGES
 *    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 *    ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
# method to query all records of a type.
function queryAll($instance, $query){

    $moreCount = 0;
    $recordsArray = array();
    $totalStart = time();

    $start = time();
    $result = $instance->query($query);
    $end = time();
    $elapsed = $end - $start;

    $done = $result->result->done;
    $size = $result->result->size;
    $records = $result->result->records;

    if ($size == 0){
    } else if ($size == 1){
        array_push($recordsArray, $records);
    } else {

        $locator = $result->result->queryLocator;
        $newRecords = $result->result->records;
        $recordsArray = array_merge($recordsArray, $newRecords);

        while (!$done && $locator && $moreCount == 0){
        
            $start = time();
            $result = $instance->queryMore($locator);
            $end = time();
            $elapsed = $end - $start;
    
            $done = $result->result->done;
            $size = $result->result->size;
            $locator = $result->result->queryLocator;
            print "\nqueryMore";

            $newRecords = $result->result->records;
            $count = count($newRecords);
            if ($count == 1){
                array_push($recordsArray, $newRecords);
            } else {
                $recordsArray = array_merge($recordsArray, $newRecords);
            }
    
        }
    }

    $totalEnd = time();
    $totalElapsed = $totalEnd - $totalStart;

    return $recordsArray;

}

function getPostValue($name,$default=null){
if (empty($_POST))
	{
		return "";
	}
else
	{
		$v = $_POST[$name];	
		if(!isset($v)){	
			$v = $default;
		}
		return $v;
	}
}
function isEmpty($var){
	return !isset($var) or trim($var)=='';
}

function makeAccount($name,$currency,$status, $Location){
    $zAccount = new Zuora_Account();
    $zAccount->AllowInvoiceEdit = 1;
    $zAccount->AutoPay = 0;
    $zAccount->Batch = 'Batch1';
    $zAccount->BillCycleDay = 'Auto';
    $zAccount->Currency = $currency;
    $zAccount->Name = $name;    
    $zAccount->PaymentTerm = 'Due Upon Receipt';
    $zAccount->Status = $status;
    $zAccount->PMDLocation__c = $Location;
   // $zAccount->DefaultPaymentMethodId = $PaymentMethodID;
    
    //$Location;
    

		//$zAccount->CrmId = 'SFDC-1223471249003';
		//$zAccount->PurchaseOrderNumber = 'PO-1223471249003';
		return $zAccount;
}

function UpdateSubscription($id, $ActDate){
    $zSubscription = new Zuora_Subscription();
    $zSubscription->Id = $id;
    $zSubscription->ServiceActivationDate = $ActDate;
    
	return $zAccount;
}
function makeUpdateAccount($id, $status, $contactObj ){
    $zAccount = new Zuora_Account();
    $zAccount->Id = $id;
    $zAccount->Status = $status;
    $zAccount->SoldToId=$contactObj;
    $zAccount->BillToId=$contactObj;
	return $zAccount;
}

function makeAPayment($PaymentMethodID, $AccountId, $Amount, $AppliedCreditBalanceAmount, $AppliedInvoiceAmount, $CreatedDate, $EffectiveDate, $Type ){
    $zPayment = new Zuora_Payment();
    $zPayment->PaymentMethodId = $PaymentMethodID;
    $zPayment->AccountId = $AccountId;
    $zPayment->Amount = 1;// $Amount;
    $zPayment->AppliedInvoiceAmount= 0;  //$AppliedCreditBalanceAmount;
    $zPayment->AppliedCreditBalanceAmount = 1;// $AppliedInvoiceAmount;    
    $zPayment->CreatedDate=$CreatedDate;
    $zPayment->EffectiveDate=$CreatedDate;
    $zPayment->Type=$Type;
  //  $zPayment->Name='Deposit';
  //  $zPayment->Currency='USD';
    $zPayment->BillCycleDay='Auto';
    $zPayment->Status='Processed';
	return $zPayment;
}


function makeContact($firstName,$lastName,$address1,$address2,$city,$state,$country,$postalCode,$workMail,$workPhone,$accountId=null,$WorkEmail){
	
	$zBillToContact = new Zuora_Contact();
    
    $zBillToContact->FirstName = $firstName;
    $zBillToContact->LastName = $lastName;    
    $zBillToContact->Address1 = $address1;
    $zBillToContact->Address2 = $address2;
    $zBillToContact->City = $city;
    $zBillToContact->State = $state;
    $zBillToContact->Country = $country;    
    $zBillToContact->PostalCode = $postalCode;
   // $zBillToContact->WorkEmail = $workMail;
    $zBillToContact->WorkPhone = $workPhone;
 	$zBillToContact->AccountId = $accountId;
    $zBillToContact->WorkEmail = $WorkEmail;
 		
		return  $zBillToContact;
}
function makePaymentMethod($creditCardHolderName,  $creditCardType, $creditCardNumber, $creditCardExpirationMonth, $creditCardExpirationYear,$accountId=null){
	  $zPaymentMethod = new Zuora_PaymentMethod();
    $zPaymentMethod->AccountId = $accountId;
    
//    $zPaymentMethod->CreditCardAddress1 = $address1;
//    $zPaymentMethod->CreditCardAddress2 = $address2;
//    $zPaymentMethod->CreditCardCity = $city;
//    $zPaymentMethod->CreditCardCountry = $country;
    $zPaymentMethod->CreditCardExpirationMonth = $creditCardExpirationMonth;
    $zPaymentMethod->CreditCardExpirationYear = $creditCardExpirationYear;
    $zPaymentMethod->CreditCardHolderName = $creditCardHolderName;
    $zPaymentMethod->CreditCardNumber = $creditCardNumber;
 //   $zPaymentMethod->CreditCardPostalCode = $postalCode;
 //   $zPaymentMethod->CreditCardState = $state;
    $zPaymentMethod->CreditCardType = $creditCardType;
    
    $zPaymentMethod->Type = 'CreditCard';	
    return $zPaymentMethod;
}
function makeSubscription($subscriptionName, $subscriptionNotes,$FullName,$StartDate__c,$Physician__c,$Location__c, $SalesRep, $StartDate, $InitialTerm){
	  $date = date('Y-m-d\TH:i:s',time());
	  
	  $zSubscription = new Zuora_Subscription();

    $zSubscription->Name = $subscriptionName;
		$zSubscription->Notes = $subscriptionNotes;
		
    $zSubscription->ContractAcceptanceDate = $date;
    $zSubscription->ContractEffectiveDate = $date;
   
    $zSubscription->InitialTerm = $InitialTerm;
    $zSubscription->RenewalTerm = 0;//$InitialTerm;
    $zSubscription->ServiceActivationDate = $date;
    $zSubscription->quantity = 1;
    
   
    $zSubscription->TermStartDate=$StartDate;
		$zSubscription->Status = 'Active';
		$zSubscription->Currency = 'USD';
		$zSubscription->AutoRenew = 0;
		
		//Custom fields now
	 	$zSubscription->Member__c 	= $FullName;
	 	$zSubscription->StartDate__c = $StartDate__c;
	 	$zSubscription->Physician__c = $Physician__c;
		$zSubscription->Location__c	= $Location__c;
		$zSubscription->SalesRep__c	= $SalesRep;
		$zSubscription->ZStartDate__c = $StartDate;
		$zSubscription->ZStartDa22te__c = $StartDate;
		
						   	
   	//,, $,$,$
   	
		
		
		
		return 	$zSubscription;
}
function setRatePlanData($zSubscriptionData,$chargeIds,$rateplancharges,$productRatePlanId){
	  $zRatePlan = new Zuora_RatePlan();
    $zRatePlan->AmendmentType = 'NewProduct';

    $zRatePlan->ProductRatePlanId = $productRatePlanId;
    $zRatePlanData = new Zuora_RatePlanData($zRatePlan);
    
    foreach($chargeIds as $cid){
    	foreach($rateplancharges as $rc){


    		if($rc->Id == $cid){
		 			$rpc = new Zuora_RatePlanCharge();
			    $rpc->ProductRatePlanChargeId = $cid;
			    //Set Quantity to 1
			    $rpc->Quantity =  1;
			    if($rc->DefaultQuantity>0){			    
			    	$rpc->Quantity =  1;
			  	}
			    $rpc->TriggerEvent ="ServiceActivation";
			    
			    $zPlanChargeData = new Zuora_RatePlanChargeData($rpc);
			    
			    
			    $zRatePlanData->addRatePlanChargeData($zPlanChargeData);    			
    		}
    	}
    }
    
  $zSubscriptionData->addRatePlanData($zRatePlanData);
    
}

function makeSubscriptionData($subscription,$chargeIds,$rateplancharges,$rateplanId){
	 $zSubscriptionData = new Zuora_SubscriptionData($subscription);
   	setRatePlanData($zSubscriptionData,$chargeIds,$rateplancharges,$rateplanId);
  // 	$zSubscriptionData->Member__c = "1";//$Member__c;
  //	$zSubscriptionData->StartDate__c = "2";//$StartDate__c;
  // 	$zSubscriptionData->Physician__c ="3";// $Physician__c;
  // 	$zSubscriptionData->Location__c ="4";// $Location__c;
	 return $zSubscriptionData;
}	

# generate an invoice
function generateInvoice($instance,$accountId,$invoiceDate,$targetDate){
	$zInvoices = array();
	
	$invoice = new Zuora_Invoice();
	$invoice->AccountId = $accountId;
	$invoice->InvoiceDate = $invoiceDate;
	$invoice->TargetDate = $targetDate;

	$zInvoices[] = $invoice;
	
	$result = $instance->generate($zInvoices);
	return $result;
}

# post invoice
function postInvoice($instance,$invoiceId){
  $invoice = new Zuora_Invoice();
	$invoice->Id = $invoiceId;
	$invoice->Status = 'Posted';
	
	$result = $instance->update(array($invoice));
	return $result;
}

function createAndApplyPayment($instance,$accountId,&$CreateStatus){
 # QUERY PaymentMethod
 $query = "SELECT Id,Type FROM PaymentMethod WHERE AccountId = '".$accountId."'";
 $records = queryAll($instance, $query);
 $paymentMethodId=$records[0]->Id;
  
 # QUERY Invoice Balance
 $query = "select Id,Balance from Invoice where AccountId = '".$accountId."' and Balance>0";
 $records = queryAll($instance, $query);
 $amount = $records[0]->Balance;
 $invoiceId = $records[0]->Id;
 
 $payment = new Zuora_Payment();
 $payment->AccountId = $accountId;
 $payment->Amount = $amount;
 $payment->EffectiveDate = date('Y-m-d\TH:i:s');
 $payment->PaymentMethodId = $paymentMethodId;
 $payment->Type = 'Electronic';
 $payment->Status = 'Draft';
 
 $result = $instance->create(array($payment));
 $paymentId = $result->result->Id;
 
 $success1 = $result->result->Success;
 $msg = "Payment: ".($success1 ? "Success" : $result->result->errors->Code . " (" . $result->result->errors->Message.")");
 
 $invoicePayment = new Zuora_InvoicePayment();
 $invoicePayment->Amount = $amount;
 $invoicePayment->InvoiceId = $invoiceId;
 $invoicePayment->PaymentId = $paymentId;  
 $result = $instance->create(array($invoicePayment));
 
 $success2 = $result->result->Success;
 $msg .=" -> InvoicePayment: ". ($success2 ?  "Success" : $result->result->errors->Code . " (" . $result->result->errors->Message.")");

 $payment = new Zuora_Payment();
 $payment->Id = $paymentId;
 $payment->Status = 'Processed';
 $result=$instance->update(array($payment));	
 $success3 = $result->result->Success;	
 $msg .=" -> Payment Processed:". ($success3 ?  "Success" : $result->result->errors->Code . " (" . $result->result->errors->Message.")");
$CreateStatus = $CreateStatus . "Create and Apply Payment: " . $msg . "<br>";
if ($success3){}
return $success3 ;
// print "\nCreate and Apply Payment: " . $msg;
}



?>