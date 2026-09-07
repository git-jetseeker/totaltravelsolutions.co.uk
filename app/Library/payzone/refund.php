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
  <link rel="stylesheet" href="assets/payzone_gateway.css?v=1.2">
  <!--[if lt IE 9]>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
<body onload="payzonePaymentPageLoad();">
  <form class='payzone-form' id='payzone-refund-form' name='payzone-refund-form' target="_self" method="POST" style='display:none' action="<?php echo $PayzoneGateway->getURL('form-action-payment'); ?>" >
    <?php
    if ($PayzoneGateway->getIntegrationType() == \Payzone\Constants\INTEGRATION_TYPE::DIRECT){ ?>
      <?php #Check if payzone images should be displayed
      if ($PayzoneGateway->getPayzoneImages()) {
        ?>
        <div class='payzone-form-section'>
          <a href='https://www.payzone.co.uk/' target="_blank">
            <img class='payzone-logo' src="<?php echo $PayzoneHelper->getSiteSecureURL('base'); ?>/assets/images/payzone_logo.png" />
          </a>
        </div>
        <?php
        }
        #Check if current integraiton method is Direct API
        if ($PayzoneGateway->getIntegrationType() == \Payzone\Constants\INTEGRATION_TYPE::DIRECT) {
          require_once(__DIR__ . "/includes/templates/refund-details.php");
        }
      ?>
      <span id='form_errors'></span>
      <div class='payzone-form-section'>
        <input id='payzone-direct' type="hidden" name="payzone-direct" value="submitted" />
        <input id='payzone-cart-submit' type="submit" name="Submit" value="Submit" />
      </div>
      <?php
      if ($PayzoneGateway->getPayzoneImages()) {?>
      <div class='payzone-form-section'>
        <a href="https://www.payzone.co.uk/" target="_blank">
          <img class='payzone-footer-image' src="<?php echo $PayzoneHelper->getSiteSecureURL('base'); ?>/assets/images/payzone_cards_accepted.png" />
        </a>
      </div>
      <?php
      }
    }
  echo $PayzoneGateway->buildRefundRequest(); ?>
  </form>
  <?php
   #include scripts for handling of JSON Data
  $page='refund';
  require_once(__DIR__ . "/includes/helpers/payzone_scripts.php");
  ?>
</body>
</html>
