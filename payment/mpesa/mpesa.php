<?php
// Initialize the variables
$consumer_key = '4iBZxGNcAAAG7wn0ARHk5fgb4v0n6LsU';
$consumer_secret = 'mAUZWkzqaMsxiz4f';
$Business_Code = '9592733';
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$Type_of_Transaction = 'CustomerPayBillOnline';
$Token_URL = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$phone_number = $_POST['phone_number'];
$OnlinePayment = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$total_amount = $_POST['amount'];
$CallBackURL = 'https://2f50f430.ngrok.io/callback.php?key=your password';
$Time_Stamp = date("Ymdhis");
$password = base64_encode($Business_Code . $Passkey . $Time_Stamp);
?>


    <!DOCTYPE html>





    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Form </title>
    </head>
    <style>
        body {
            background-color: skyblue;
        }
    </style>

    <body>
        <center>
            <form style="padding-top: 150px;" action="MpesaTest.php" method="POST">
                <fieldset style="width: 400px; height: 300px; background-color: white;">
                    <label style="padding-top: 200px;color:red;">Lipa Online</label>
                    <br>
                    <input class="input2" type="text" name="amount" placeholder="Enter Amount" autofocus style="margin-top: 60px;">
                    <br><br>
                    <input type="text" name="phone_number" placeholder="254" maxlength="14">
                    <br><br>
                    <button class="button" style="color: white; background-color: blue;">
               Make Payment Now
               </button>
                </fieldset>
            </form>
        </center>
        </div>
    </body>

    </html>