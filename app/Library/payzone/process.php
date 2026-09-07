<?php /**
* Payzone Payment Gateway
* ========================================
* Web:   http://payzone.co.uk
* Email:  online@payzone.com
* Authors: Payzone, Keith Rigby
*/
$SuppressDebug=true;
require_once(__DIR__ . "/includes/payzone_gateway.php");

if (isset($_GET["pzgact"])){
  $action=$_GET["pzgact"];
}else if (isset($_POST["PaRes"])) {
  $action='threedsecure';
}
$IntegrationType=$PayzoneGateway->getIntegrationType();
$SecretKey=$PayzoneGateway->getSecretKey();
$HashMethod=$PayzoneGateway->getHashMethod();
if ($IntegrationType==\Payzone\Constants\INTEGRATION_TYPE::DIRECT){
  switch ($action) {
    case 'threedsecure':
      require_once(__DIR__ . "/includes/gateway/threed_process.php"); ?>
      <script>
        window.parent.postMessage({'option':'iframesrc','value':'three-response'},"<?php echo $PayzoneHelper->getSiteSecureURL('root'); ?>");
        window.parent.postMessage({'option':'threedresponse','value':'<?php echo json_encode($paymentResponse); ?>'},"<?php echo $PayzoneHelper->getSiteSecureURL('root'); ?>");
      </script>
    <?php
      break;


    case 'process':
      $CurrencyCode = $PayzoneGateway->getCurrencyCode();
      $respobj=array();
      $queryObj=array();
      $queryObj["Amount"]=$_POST["Amount"];
      $queryObj["CurrencyCode"]=$CurrencyCode;
      $queryObj["OrderID"]=$_POST["OrderID"];
      $queryObj["OrderDescription"]=$_POST["OrderDescription"];
      $queryObj["HashMethod"]=$HashMethod;
      $StringToHash =  $PayzoneHelper->generateStringToHashDirect($_POST["Amount"],$CurrencyCode,$_POST["OrderID"],$_POST["OrderDescription"],$SecretKey);
      $ShoppingCartHashDigest = $PayzoneHelper->calculateHashDigest($StringToHash,$SecretKey,$HashMethod);
      $ShoppingCartValidation = $PayzoneHelper->checkIntegrityOfIncomingVariablesDirect("SHOPPING_CART_CHECKOUT",$queryObj,$ShoppingCartHashDigest,$SecretKey);
      $respobj["ShoppingCartHashDigest"]=$ShoppingCartHashDigest;
      if ($ShoppingCartValidation){
        $queryObj =  $PayzoneGateway->buildXHRequest();
        //Process the transaction
        require_once(__DIR__ . "/includes/gateway/direct_process.php");

        echo json_encode($paymentResponse);//pass the response object back to the JS handler to process
      }
      else {
        $paymentResponse["ErrorMessage"]='Hash mismatch validation failure';
        $paymentResponse["ErrorMessages"]=true;
        echo json_encode($paymentResponse);
      }
      break;
      case 'refund':
        $CurrencyCode = $PayzoneGateway->getCurrencyCode();
        $respobj=array();
        $queryObj=array();
        $queryObj["Amount"]=$_GET["pzgamt"];
        $queryObj["CurrencyCode"]=$CurrencyCode;
        $queryObj["OrderID"]=$_GET["pzgorderid"];
        $queryObj["CrossReference"]=$_GET["pzgcrossref"];
        $queryObj["OrderDescription"]="";
        $queryObj["HashMethod"]=$HashMethod;
        $StringToHash =  $PayzoneHelper->generateStringToHashDirect($queryObj["Amount"],$CurrencyCode,  $queryObj["OrderID"],$queryObj["OrderDescription"],$SecretKey);
        $ShoppingCartHashDigest = $PayzoneHelper->calculateHashDigest($StringToHash,$SecretKey,$HashMethod);
        $ShoppingCartValidation = $PayzoneHelper->checkIntegrityOfIncomingVariablesDirect("SHOPPING_CART_CHECKOUT",$queryObj,$ShoppingCartHashDigest,$SecretKey);
        $respobj["ShoppingCartHashDigest"]=$ShoppingCartHashDigest;
        if ($ShoppingCartValidation){
          $queryObj =  $PayzoneGateway->buildXHRefund();
          //Process the transaction

          require_once(__DIR__ . "/includes/gateway/refund_process.php");

          echo json_encode($paymentResponse);//pass the response object back to the JS handler to process
        }
        else {
          $paymentResponse["ErrorMessage"]='Hash mismatch validation failure';
          $paymentResponse["ErrorMessages"]=true;
          echo json_encode($paymentResponse);
        }
        break;

    default:
      # code...
      break;
  }
}
?>
