<?php
session_start();
ob_start();
$Amount = $_GET['Amount'];
include("admin/inc/config.php");
include("admin/inc/functions.php");
///////
// Getting all language variables into array as global variable
$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);             
foreach ($result as $row) {
  define('LANG_VALUE_'.$i,$row['lang_value']);
  $i++;
}
$payment_date = date('Y-m-d H:i:s');
      $payment_id = time();
//////




      ///////////////////////////////////////////////
      $statement = $pdo->prepare("INSERT INTO tbl_payment (   
                              customer_id,
                              customer_name,
                              customer_email,
                              payment_date,
                              txnid, 
                              paid_amount,
                              card_number,
                              card_cvv,
                              card_month,
                              card_year,
                              bank_transaction_info,
                              payment_method,
                              payment_status,
                              shipping_status,
                              payment_id
                          ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $statement->execute(array(
                              $_SESSION['customer']['cust_id'],
                              $_SESSION['customer']['cust_name'],
                              $_SESSION['customer']['cust_email'],
                              $payment_date,
                              '',
                              $Amount,
                              '', 
                              '',
                              '', 
                              '',
                              '',
                              'M-Pesa',
                              'Pending',
                              'Pending',
                              $payment_id
                          ));

      $i=0;
      foreach($_SESSION['cart_p_id'] as $key => $value) 
      {
          $i++;
          $arr_cart_p_id[$i] = $value;
      }

      $i=0;
      foreach($_SESSION['cart_p_name'] as $key => $value) 
      {
          $i++;
          $arr_cart_p_name[$i] = $value;
      }

      $i=0;
      foreach($_SESSION['cart_size_name'] as $key => $value) 
      {
          $i++;
          $arr_cart_size_name[$i] = $value;
      }

      $i=0;
      foreach($_SESSION['cart_color_name'] as $key => $value) 
      {
          $i++;
          $arr_cart_color_name[$i] = $value;
      }

      $i=0;
      foreach($_SESSION['cart_p_qty'] as $key => $value) 
      {
          $i++;
          $arr_cart_p_qty[$i] = $value;
      }

      $i=0;
      foreach($_SESSION['cart_p_current_price'] as $key => $value) 
      {
          $i++;
          $arr_cart_p_current_price[$i] = $value;
      }

      $i=0;
      $statement = $pdo->prepare("SELECT * FROM tbl_product");
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);             
      foreach ($result as $row) {
        $i++;
        $arr_p_id[$i] = $row['p_id'];
        $arr_p_qty[$i] = $row['p_qty'];
      }

      for($i=1;$i<=count($arr_cart_p_name);$i++) {
          $statement = $pdo->prepare("INSERT INTO tbl_order (
                          product_id,
                          product_name,
                          size, 
                          color,
                          quantity, 
                          unit_price, 
                          payment_id
                          ) 
                          VALUES (?,?,?,?,?,?,?)");
          $sql = $statement->execute(array(
                          $arr_cart_p_id[$i],
                          $arr_cart_p_name[$i],
                          $arr_cart_size_name[$i],
                          $arr_cart_color_name[$i],
                          $arr_cart_p_qty[$i],
                          $arr_cart_p_current_price[$i],
                          $payment_id
                      ));

          // Update the stock
            for($j=1;$j<=count($arr_p_id);$j++)
            {
                if($arr_p_id[$j] == $arr_cart_p_id[$i]) 
                {
                    $current_qty = $arr_p_qty[$j];
                    break;
                }
            }
            $final_quantity = $current_qty - $arr_cart_p_qty[$i];
            $statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
            $statement->execute(array($final_quantity,$arr_cart_p_id[$i]));
            
      }
      //////////////////////////////////////////////


$customer = $_SESSION['customer'];
$date = date("Y-m-d");
$con = mysqli_connect("localhost","root","","ecommerceweb");

$id = $customer['cust_id'];
$name = $customer['cust_name'];
$email = $customer['cust_email'];


$t_id = time();
//if(isset($_POST['submit'])) {


 $PartyA = "254".$customer['cust_phone'];

$insert_sql = "INSERT INTO `tbl_payment`(`customer_id`, `customer_name`, `customer_email`, `payment_date`, `txnid`, `paid_amount`, `card_number`, `card_cvv`, `card_month`, `card_year`, `bank_transaction_info`, `payment_method`, `payment_status`, `shipping_status`, `payment_id`) 
VALUES ('$id','$name','$email','$date','','$Amount','','','','','','M-pesa','Pending','Pending','$t_id')";
  //$values = file_get_contents('php://input');
  //$arrays = json_decode($values,true);
  //$PartyA = $_POST['PhoneNumber'];
 
 //$result = mysqli_query($con,$insert_sql);
  
  $TransactionDesc = 'Product payment';



  date_default_timezone_set('Africa/Nairobi');

  # access token
  $consumerKey = 'nk16Y74eSbTaGQgc9WF8j6FigApqOMWr'; //Fill with your app Consumer Key
  $consumerSecret = '40fD1vRXCq90XFaU'; // Fill with your app Secret

  # define the variales
  # provide the following details, this part is found on your test credentials on the developer account
  $BusinessShortCode = '174379';
  $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  
  
  /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
  */
   
  $AccountReference = 'Till Number 9592733';
  $TransactionDesc = 'Test Payment';
 
  # Get the timestamp, format YYYYmmddhms -> 20181004151020
  $Timestamp = date('YmdHis');    
  
  # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint urls
  $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://9bd2-154-159-237-117.ngrok-free.app/callback.php';  

  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $result = json_decode($result);
  $access_token = $result->access_token;  
  curl_close($curl);

  # header for stk push
  $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  print_r($curl_response);

  unset($_SESSION['cart_p_id']);
      unset($_SESSION['cart_size_id']);
      unset($_SESSION['cart_size_name']);
      unset($_SESSION['cart_color_id']);
      unset($_SESSION['cart_color_name']);
      unset($_SESSION['cart_p_qty']);
      unset($_SESSION['cart_p_current_price']);
      unset($_SESSION['cart_p_name']);
      unset($_SESSION['cart_p_featured_photo']);

  header('location: payment_success.php');


//}

  ?>