<?php
$SuppressDebug = true;
require_once(__DIR__ . "/includes/payzone_gateway_new.php");
$action = "process";

$IntegrationType = $PayzoneGateway->getIntegrationType();
$SecretKey = $PayzoneGateway->getSecretKey();
$HashMethod = $PayzoneGateway->getHashMethod();


$CurrencyCode = $PayzoneGateway->getCurrencyCode();
$respobj = array();
$queryObj = array();
$queryObj["Amount"] = 150;
$queryObj["CurrencyCode"] = $CurrencyCode;
$queryObj["OrderID"] = 2001;
$queryObj["OrderDescription"] = "this is direct testing";
$queryObj["HashMethod"] = $HashMethod;
$queryObj["CrossReferenceTransaction"] = false;
$StringToHash = $PayzoneHelper->generateStringToHashDirect($queryObj["Amount"], $CurrencyCode, $queryObj["OrderID"], $queryObj["OrderDescription"], $SecretKey);
$ShoppingCartHashDigest = $PayzoneHelper->calculateHashDigest($StringToHash, $SecretKey, $HashMethod);
$ShoppingCartValidation = $PayzoneHelper->checkIntegrityOfIncomingVariablesDirect("SHOPPING_CART_CHECKOUT", $queryObj, $ShoppingCartHashDigest, $SecretKey);
$respobj["ShoppingCartHashDigest"] = $ShoppingCartHashDigest;
if ($ShoppingCartValidation) {

    $data = [];

    $data["Country"] = "UK";
    $data["FullAmount"] = "150";
    $data["OrderID"] = "2001";
    $data["TransactionDateTime"] = date("Y-m-d H:i:s");
    $data["OrderDescription"] = "this is new testing";
    $data["CardNumber"] = "4976000000003436";
    $data["CV2"] = "452";
    $data["IssueNumber"] = "";
    $data["ExpiryDateMonth"] = "12";
    $data["ExpiryDateYear"] = "20";
//    $data["StartDateMonth"] = "01";
//    $data["StartDateYear"] = "17";

//    32 Mulberry Street,
//Eastfort,
//Violetdell
//VL14 8PA

    $data["CustomerName"] = "John Watson";
    $data["Address1"] = "32 Mulberry Street, Eastfort, Violetdell";
    $data["Address2"] = "";
    $data["Address3"] = "";
    $data["Address4"] = "";
    $data["City"] = "Violetdell";
    $data["State"] = "Violetdell";
    $data["PostCode"] = "VL14 8PA";
    $data["EmailAddress"] = "parkingzone@gmail.com";

    $PayzoneGateway->setDebugMode(true);

    $queryObj = $PayzoneGateway->buildXHRequest($data);
    //Process the transaction
    require_once(__DIR__ . "/includes/gateway/direct_process.php");

    echo json_encode($paymentResponse);//pass the response object back to the JS handler to process
} else {
    $paymentResponse["ErrorMessage"] = 'Hash mismatch validation failure';
    $paymentResponse["ErrorMessages"] = true;
    echo json_encode($paymentResponse);
}

?>