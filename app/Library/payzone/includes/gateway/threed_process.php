<?php /**
* Payzone Payment Gateway
* ========================================
* Web:   http://payzone.co.uk
* Email:  online@payzone.com
* Authors: Payzone, Keith Rigby
*/

if (count(get_included_files()) ==1) {
    exit("Direct access not permitted.");
}

require_once (__DIR__."/../gateway/paymentsystem.php");

use \Payzone\Gateway\PaymentSystem as PaymentSystem;

$rgeplRequestGatewayEntryPointList = new  PaymentSystem\RequestGatewayEntryPointList();
// you need to put the correct gateway entry point urls in here
// contact support to get the correct urls

// The actual values to use for the entry points can be established in a number of ways
// 1) By periodically issuing a call to GetGatewayEntryPoints
// 2) By storing the values for the entry points returned with each transaction
// 3) Speculatively firing transactions at https://gw1.xxx followed by gw2, gw3, gw4....
// The lower the metric (2nd parameter) means that entry point will be attempted first,
// EXCEPT if it is -1 - in this case that entry point will be skipped
// NOTE: You do NOT have to add the entry points in any particular order - the list is sorted
// by metric value before the transaction sumbitting process begins
// The 3rd parameter is a retry attempt, so it is possible to try that entry point that number of times
// before failing over onto the next entry point in the list
$rgeplRequestGatewayEntryPointList->add("https://gw1.payzoneonlinepayments.com:4430/", 100, 1);
$rgeplRequestGatewayEntryPointList->add("https://gw2.payzoneonlinepayments.com:4430/", 200, 1);
$rgeplRequestGatewayEntryPointList->add("https://gw3.payzoneonlinepayments.com:4430/", 300, 1);

$tdsaThreeDSecureAuthentication = new  PaymentSystem\ThreeDSecureAuthentication($rgeplRequestGatewayEntryPointList);

$tdsaThreeDSecureAuthentication->getMerchantAuthentication()->setMerchantID($PayzoneGateway->getMerchantID());
$tdsaThreeDSecureAuthentication->getMerchantAuthentication()->setPassword($PayzoneGateway->getMerchantPassword());

$tdsaThreeDSecureAuthentication->getThreeDSecureInputData()->setCrossReference($_POST["MD"]);
$tdsaThreeDSecureAuthentication->getThreeDSecureInputData()->setPaRES($_POST["PaRes"]);

$boTransactionProcessed = $tdsaThreeDSecureAuthentication->processTransaction($tdsarThreeDSecureAuthenticationResult, $todTransactionOutputData);

if ($boTransactionProcessed == false)
{
	// could not communicate with the payment gateway
	$paymentResponse["Message"]='Unable to communicate with the payment gateway.';
	$paymentResponse["StatusCode"]='99';
}
else
{
	$paymentResponse["Message"]=$tdsarThreeDSecureAuthenticationResult->getMessage();
	$paymentResponse["StatusCode"]=$tdsarThreeDSecureAuthenticationResult->getStatusCode();

	switch ($tdsarThreeDSecureAuthenticationResult->getStatusCode())
	{
		case 0:
		// status code of 0 - means transaction successful
		$paymentResponse["CrossReference"]=$todTransactionOutputData->getCrossReference();
		break;
		case 5:
		// status code of 5 - means transaction declined
		$paymentResponse["CrossReference"]=$todTransactionOutputData->getCrossReference();
		break;
		case 20:
		// status code of 20 - means duplicate transaction
		$paymentResponse["CrossReference"]=$todTransactionOutputData->getCrossReference();
		$paymentResponse["PreviousTransactionMessage"]=$tdsarThreeDSecureAuthenticationResult->getPreviousTransactionResult()->getMessage();
		$paymentResponse["DuplicateTransaction"]= true;
		break;
		case 30:
		// status code of 30 - means an error occurred
		$paymentResponse["ErrorMessages"] = "";
		// status code of 30 - means an error occurred
		$eCount = $cdtrCardDetailsTransactionResult->getErrorMessages()->getCount();
		if ($eCount > 0) {
			for ($i = 0; $i < $eCount; $i++) {
				$paymentResponse["ErrorMessages"].=$cdtrCardDetailsTransactionResult->getErrorMessages()->getAt($i);
			}
		}
		break;
		default:
		// unhandled status code

		break;
	}
}
?>
