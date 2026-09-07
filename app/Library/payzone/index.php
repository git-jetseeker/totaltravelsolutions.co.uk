<!DOCTYPE html>
<?php
require_once(__DIR__ . "/includes/payzone_gateway.php");
/**
 * [DEMO_DATA Demo data class for randomising order ID / amount etc for testing, and pre-populating some data address etc]
 */
class DEMO_DATA
{
  ##### DEVELOPER NOTE #####
  #~~~~~~~~~~~~~~~~~~~~~~~~~~#
  # This DEMO_DATA class is used purely for demonstration purposes, in a production environment the information below would be input by the end user or provided from saved session / db etc
  # The constants referenced here are then passed over to the cart page via POST - this will be similar to the way that real data from the customer will be passed from forms to the real cart page
  # to replicate the real functionality.
  #~~~~~~~~~~~~~~~~~~~~~~~~~~#
  ############################
  const CUSTOMERNAME    = 'Geoff Wayne';
  const ADDRESS1        = '113 Glendower Road';
  const ADDRESS2        = '';
  const CITY            = 'Birmingham';
  const STATE           = 'West Midlands';
  const POSTCODE        = 'B42 1SX';
  const COUNTRY         = 'United Kingdom';
  /*const CUSTOMERNAME    = 'John Watson';
  const ADDRESS1        = '32 Edward Street';
  const ADDRESS2        = '';
  const CITY            = 'Camborne';
  const STATE           = 'Cornwall';
  const POSTCODE        = 'TR14 8PA';
  const COUNTRY         = 'United Kingdom';*/
  static function ORDERID()
  {
    return time();
  }
  static function AMOUNT()
  {
    return mt_rand(1*100,100*100)/100;
  }
  static function ORDERDESC()
  {
    $PayzoneGateway = new Payzone\PayzoneGateway;
    return 'Example order processing | '.  $PayzoneGateway->getIntegrationType2();
  }
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payzone Gateway - About</title>
  <meta name="description" content="Payment Gateway example integration">
  <meta name="author" content="Keith Rigby - Payzone">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!--Payzone CSS -->
  <link rel="stylesheet" href="assets/payzone_gateway.css?v=1.2">
  <!--[if lt IE 9]>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>
<script>
  function submitForms()
  {
    withDataForm.submit()
  }
</script>
</head>
<body onload="submitForms()">
  <?php
  if ((!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'] == 'on') && $PayzoneGateway->getIntegrationType() == Payzone\Constants\INTEGRATION_TYPE::DIRECT) {
    ?>
    <div id='payzone_warning'>HTTPS IS DISABLED - PLEASE ENABLE HTTPS/SSL TO USE THE PAYZONE GATEWAY SECURELY</div>
    <?php
  } ?>
  <div class='payzone-about'>
    <?php echo $PayzoneGateway->versionCheck(); ?>
    <h1>Payzone Payment Gateway</h1>

    <hr class='payzone-clear-float'>
    <h2>Getting Started</h2>
    <p>This PHP integration pack will allow you to implement the payzone payment gateway into your site, using the preferred method for integration.</p>
    <p></p>
    <ol>
      <li>Upload the files to the server via FTP</li>
      <li>In the assets/includes/payzone_gateway.php file, inside the __construct() function update the set statements with your merchant details</li>
      <ul>
        <li>Merchant ID - this can be found in the <a href="https://mms.payzoneonlinepayments.com/Login.aspx">MMS</a> under Account Admin -> Gateway Account Admin </li>
        <li>Merchant Password - this can be reset in the <a href="https://mms.payzoneonlinepayments.com/Login.aspx">MMS</a> under Account Admin -> Gateway Account Admin </li>
        <li>Pre Shared Key - this can be found in the <a href="https://mms.payzoneonlinepayments.com/Login.aspx">MMS</a> under Account Admin -> Account Settings </li>
        <li>Secret Key - This is a unique string for use by the site, you can create any random string </li>
        <li>Hash method - this can be found in the <a href="https://mms.payzoneonlinepayments.com/Login.aspx">MMS</a> under Account Admin -> Account Settings </li>
      </ul>
      <li>Click on one of the Submit options below, depending on whether you want to submit with dummy data, or manually enter test details</li>
      <li>Once you have successfully tested you can start integrating with your system, recording the transaction into your transaction log, and getting / setting the configuration values using your sites database</li>
    </ol>
    <hr class='payzone-clear-float'>

    <h2>Example payment flow</h2>
    <p>The example payment flows below will allow you to submit a transaction end to end using the current configuration, there are 2 options:
      <br><strong>'Without Data'</strong> - which will simulate a 'new' customer or a customer with no records stored
      <br><strong>'With Data'</strong> - which will simulate a 'returning' customer who already have some details stored on your server, the details used in this example are from the test cards pack.
    </p>
    <div class='payzone-about-section payzone-configuration'>
      <h3>Example with data</h3>
      <form  id='withDataForm' name='withDataForm' target="_self" action="<?php echo $PayzoneGateway->getURL('cart-page'); ?>" method="POST" class=''>
        <p>To view the sample payment flow, prepopulated with post data (from this form) click on the below button, this will simulate the infomration being populated from your customer records.</p>
        <div class='payzone-form-section collapsed'>
          <label for='FullAmount'>FullAmount</label>
          <input type="text" name="FullAmount" value="<?php echo(DEMO_DATA::AMOUNT()); ?>" />
          <label for='OrderId'>OrderID</label>
          <input type="text" name="OrderID" value="<?php echo (DEMO_DATA::ORDERID());?>" />
          <label for='TransactionDateTime'>TransactionDateTime</label>
          <input type="text" name="TransactionDateTime" value="<?php echo (date('Y-m-d H:i:s P'));?>" />
          <label for='OrderDescription'>OrderDescription</label>
          <input type="text" name="OrderDescription" value="<?php echo(DEMO_DATA::ORDERDESC()); ?>" />
          <label for='CustomerName'>CustomerName</label>
          <input type="text" name="CustomerName" value="<?php echo(DEMO_DATA::CUSTOMERNAME); ?>" />
          <label for='Address1'>Address1</label>
          <input type="text" name="Address1" value="<?php echo(DEMO_DATA::ADDRESS1); ?>" />
          <label for='Address1'>Address2</label>
          <input type="text" name="Address2" value="<?php echo(DEMO_DATA::ADDRESS2); ?>" />
          <label for='City'>City</label>
          <input type="text" name="City" value="<?php echo(DEMO_DATA::CITY); ?>" />
          <label for='State'>State</label>
          <input type="text" name="State" value="<?php echo(DEMO_DATA::STATE); ?>" />
          <label for='PostCode'>PostCode</label>
          <input type="text" name="PostCode" value="<?php echo(DEMO_DATA::POSTCODE); ?>" />
          <label for='Country'>Country</label>
          <select name="Country">
            <?php echo $PayzoneHelper->getCountryDropDownlist(DEMO_DATA::COUNTRY);?>
          </select>
        </div>
        <input type='submit'  target='_self' class='payzone-btn' value='Submit with data'/>
      </form>
    </div>
    <div class='payzone-about-section payzone-configuration'>
      <h3>Example without data</h3>
      <form target="_self" action="<?php echo $PayzoneGateway->getURL('cart-page'); ?>" method="POST" class=''>

        <p>To view the sample payment flow, with no with post data being sent across (i.e. blank payment / new customer) please click the below button</p>
        <input type='submit'  target='_self' class='payzone-btn' value='Submit without data'/>
      </form>
    </div>
    <hr class='payzone-clear-float'>
    <h2>Module Configuration</h2>
    <div class='payzone-about-section payzone-configuration'>
      <h3>Configuration</h3>
      <table>
        <tr><th>Merchant ID</th><td><?php echo $PayzoneGateway->getMerchantId(); ?></td></tr>
        <tr><th>Merchant Password</th><td><?php echo $PayzoneGateway->getMerchantPassword(); ?></td></tr>
        <tr><th>PreSharedKey</th><td><?php echo $PayzoneGateway->getPreSharedKey(); ?></td></tr>
        <tr><th>SecretKey</th><td><?php echo $PayzoneGateway->getSecretKey(); ?></td></tr>
      </table>
      <table>
        <tr><th>Debug Mode</th><td><?php echo $PayzoneHelper->boolToString($PayzoneGateway->getDebugMode()); ?></td></tr>
        <tr><th>Show payzone logo (Payment/Results)</th><td><?php echo $PayzoneHelper->boolToString($PayzoneGateway->getPayzoneImages()); ?></td></tr>
        <tr><th>Order Order Details (Results)</th><td><?php echo $PayzoneHelper->boolToString($PayzoneGateway->getOrderDetails()); ?></td></tr>
      </table>
    </div>
    <div class='payzone-about-section payzone-configuration'>
      <h3>Payment Settings</h3>
      <table>
        <tr><th>Transaction Type</th><td><?php echo $PayzoneGateway->getTransactionType(); ?></td></tr>
        <tr><th>Hash Method</th><td><?php echo $PayzoneGateway->getHashMethod(); ?></td></tr>
        <tr><th>Base currency</th><td><?php echo $PayzoneGateway->getCurrencyCode(); ?></td></tr>
        <tr><th>Currency Symbol</th><td><?php echo $PayzoneHelper->getCurrencySymbol($PayzoneGateway->getCurrencyCode()); ?></td></tr>
      </table>
    </div>
    <div class='payzone-about-section payzone-configuration payzone-fullwidth'>
      <h3>URLS</h3>
      <table>
        <tr><th>Homepage</th><td><?php echo $PayzoneGateway->getURL('home-page'); ?></td></tr>
        <tr><th>Cart Page</th><td><?php echo $PayzoneGateway->getURL('cart-page'); ?></td></tr>
        <tr><th>Payment Page</th><td><?php echo $PayzoneGateway->getURL('payment-page'); ?></td></tr>
        <tr><th>Process Payment</th><td><?php echo $PayzoneGateway->getURL('process-payment'); ?></td></tr>
        <tr><th>Results Page</th><td><?php echo $PayzoneGateway->getURL('result-page'); ?></td></tr>
        <tr><th>Payment form action</th><td><?php echo $PayzoneGateway->getURL('form-action-payment'); ?></td></tr>

      </table>
    </div>
    <hr class='payzone-clear-float'>
  </div>
</body>
</html>
