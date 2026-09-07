<!DOCTYPE html>
<?php
require_once(__DIR__ . "/includes/payzone_gateway.php");
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payzone Gateway - Cart</title>
  <meta name="description" content="Payment Gateway example integration">
  <meta name="author" content="Keith Rigby - Payzone">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!--Payzone CSS -->
  <link rel="stylesheet" href="assets/payzone_gateway.css?v=1.2">
  <!--[if lt IE 9]>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
<body>
  <form class='payzone-form' id='shopping-cart-form' name='shopping-cart-form' action='<?php echo $PayzoneGateway->getURL('payment-page'); ?>' method='POST'>
    <?php #Check if payzone images should be displayed
    if ($PayzoneGateway->getPayzoneImages()) { ?>
      <div class='payzone-form-section'>
        <a href='https://www.payzone.co.uk/' target="_blank">
          <img class='payzone-logo' src="<?php echo $PayzoneHelper->getSiteSecureURL('base'); ?>/assets/images/payzone_logo.png" />
        </a>
      </div>
    <?php
    }
    #Include standard cart details
    require_once(__DIR__ . "/includes/templates/cart-details.php");
    #Check if customer details should be displayed
    if ($PayzoneGateway->getIntegrationType()==Payzone\Constants\INTEGRATION_TYPE::DIRECT) {
      require_once(__DIR__ . "/includes/templates/customer-details.php");
    }?>
    <hr>
    <span id='form_errors'></span>
    <div class='payzone-form-section'>
      <input id='payzone-cart-submit' type="submit" name="Submit" value="Submit" />
    </div>

  </form>
  <?php #include scripts for handling of JSON Data
$page='cart';
require_once(__DIR__ . "/includes/helpers/payzone_scripts.php"); ?>
</body>
</html>
