#!/usr/bin/php
<?php 

require_once 'lib/API.php';
require_once 'config.php';
require_once 'functions.php';
require_once('../lib/nusoap.php');

$config = new stdClass();
$config->wsdl = $wsdl;


$productName = 'Lansdowne';// to keep things simple ,you'd better create a product with flat-fee of one-time charge for testing. and turn off the "Require Customer Acceptance of Orders?" ,"Require Service Activation of Orders?" in the Default Subscription Settings.

$instance = Zuora_API::getInstance($config);
$instance->setQueryOptions($query_batch_size);

# LOGIN
$instance->setLocation($endpoint);



$contact_client = new SoapClient('http://localhost/php-quickstart-master/zuora.17.0.wsdl', array('trace' => true));

$contact_client->login('jbretcher@partnermd.com', 'jIll198^');


$soap_request = $contact_client->__getLastRequest();
$soap_response = $contact_client->__getLastResponse();
echo "SOAP request:\n$soap_request\n";
echo "SOAP response:\n$soap_response\n";


?>