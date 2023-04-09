<?php require_once('header.php');

$con = mysqli_connect("localhost","root","","ecommerceweb");
$i = 1;

$sql = "SELECT * FROM `responses`";
$result = mysqli_query($con,$sql);



if(isset($_POST['print'])){
    
    echo '<script type="text/javascript">
window.print();
</script>';

}



 ?>

<br><br>

<hr>

<div class="container">

	<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Customer phone</th>
      <th scope="col">Amount</th>
      <th scope="col">Status</th>
      <th scope="col">Status Description</th>
      <th scope="col">Receipt Number</th>
    </tr>
  </thead>
  <tbody>
  	<?php while ($transactions = mysqli_fetch_assoc($result)) {

  		$phone = substr($transactions['phonenumber'],3);
$sqm = "SELECT `cust_name` FROM `tbl_customer` WHERE `cust_phone`='$phone'";
$cust_data = mysqli_fetch_assoc(mysqli_query($con,$sqm));
  		
  	?>
    <tr>
      <th scope="row"><?php echo $i;?></th>
      <td><?php 
      if(is_null($cust_data)){
      	echo "Not Available";
      }else{
      echo $cust_data['cust_name'];}
  ?></td>
      <td><?php echo $transactions['phonenumber'];?></td>
      <td><?php echo $transactions['amount'];?></td>
      <td><?php 

      $code = $transactions['ResponseCode'];
      if($code == 1032){
      	echo "Cancelled";
      }else if($code == 1019){
      	echo "Transaction timed out";
      }else if($code == 1037){
      	echo "Invalid number";
      }else if($code == 0){
      	echo "Successfull";
      }

  ?></td>
  <td><?php echo $transactions['ResponseDescription'];?></td>
      <td><?php echo $transactions['receiptnumber'];?></td>
    </tr>
<?php
		$i ++;
		 }?>
  </tbody>
</table>
<form method="post"><Input type="submit" name="print" class="btn btn-success" value="Print"/></form>
</div>

<?php require_once('footer.php'); ?>