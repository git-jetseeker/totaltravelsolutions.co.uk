<!DOCTYPE html>
<?php
require_once(__DIR__ . "/includes/payzone_gateway.php");
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payzone Gateway - Payment</title>
  <meta name="description" content="Payment Gateway example integration">
  <meta name="author" content="Keith Rigby - Payzone">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!--Payzone CSS -->
  <link rel="stylesheet" href="assets/payzone_gateway.css?v=1.3">
  <!--[if lt IE 9]>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
<body onload="payzoneResultsOnload();" >
  <div id='pzg-wrap'></div>
  <?php
  $ThreeDSecure=false;
  $ThreeDSecureResponse=false;

  if (isset($_POST["CrossReference"]) && !isset($_POST["PaREQ"]) || isset($_GET["CrossReference"]) || $_POST["StatusCode"]==30){
    $validate = $PayzoneGateway->validateResponse($_GET, $_POST);
    if (isset($validate["Notification"])) {//Check if the order has been processed, success or fail a Notification array will be added from the validate function
      $showresults=true; // set show results to true, we can hide the results if we need to for 3d secure handling etc
      ##### DEVELOPER NOTICE #####
      /*
      This section allows you to add in additional functions for specific scenarios with a completed tranasction, this section is left blank apart from the 3D secure transacation handler
      */
      ############################
      switch ($validate["Notification"]["Type"]) { //
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::SUCCESS: // Payment successful
          $iframesrc='results';
          break;
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::DECLINED:
          $iframesrc='results';
          break;
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::THREED:
          $iframesrc='results-threed';
          //3D Secure Authentication required - don't display results yet but pass over to 3D secure handler
          $showresults=false;
          break;
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::DUPLICATE:
          $iframesrc='results';
          break;
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::ERROR:
          $iframesrc='results';
          break;
        case \Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::UNKNOWN:
          default:
          $iframesrc='results';
          # code...
          break;
      }
      require_once(__DIR__ . "/includes/templates/response.php");
    }
    else {
      ?>
      <h1>No variables passed...</h1>
      <?php
    }
  }
  if ($PayzoneGateway->getIntegrationType() == \Payzone\Constants\INTEGRATION_TYPE::TRANSPARENT ){
    if(isset($_POST["PaREQ"]) && isset($_POST["CrossReference"])){
      $validate3D = $PayzoneGateway->validateResponse3DTransparent($_POST);
      $showresults=true;
      $validate["Notification"]["Type"]=\Payzone\Constants\PAYZONE_RESPONSE_OUTCOMES::THREED;
      $validate["Notification"]["Title"]="3D Secure";
      $validate["Notification"]["Message"]="3D Secure Authentication Required";
      $validate["Response"]["Message"]="";
      require_once(__DIR__ . "/includes/templates/response.php");
      if ($validate3D){
        $iframesrc='results-process';
        $ThreeDSecure=true;
      }
      else {
        //Unable to validate the 3D secure response.
      }
    }
    else if (isset($_POST["PaRes"])){
      $iframesrc='results-process';
      $ThreeDSecureResponse = true;
      $validate3D = $PayzoneGateway->validateResponse3DTransparentResponse($_POST);
    }
  }
  ?>

  <!--Payzone Scripts -->
  <script>
    var iframepage='results-process';
    function payzoneResultsOnload(){

    }
  </script>

  <script>
    function createInput(name,value){
      input = document.createElement("input");
      input.setAttribute("name", name);
      input.setAttribute("type", "hidden");
      input.setAttribute("value", value);
      return input;
    }
  </script>


  <?php
  $page='results';
  require_once(__DIR__ . "/includes/helpers/payzone_scripts.php");
  ?>
</body>
</html>
