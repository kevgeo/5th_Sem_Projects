<!doctype html>
<html>
<head><title>debit_pay</title></head>

<style>
.error
{
	color:red;
}
th
{
	text-align:left;
}
</style>

<body>
<?php
error_reporting(0);
$blockErr=$cardErr=$expiryErr=$cvvErr="";
if($_SERVER["REQUEST_METHOD"]=="POST")
{
	
	if(empty($_POST["card"]))
		$cardErr="card no is mandatory!";
	else if(!(preg_match('/^[0-9]{16}$/',$_POST["card"])))
	//else if($len2!=10)
		$cardErr="card no should hav exactly 16 digits!";
	else 
	{
		$cardErr="";
		$card=test($_POST["card"]);
	}
	if(empty($_POST["cvv"]))
		$cvvErr="cvv no is mandatory!";
	else if(!(preg_match('/^[0-9]{3}$/',$_POST["cvv"])))
	//else if($len2!=10)
		$cvvErr="cvv no should hav exactly 3 digits!";
	if(empty($_POST["block"]))
		$blockErr="this field is mandatory";
	else if(!(preg_match('/^[a-zA-Z]*$/',$_POST["block"])))
	//else if($len2!=10)
		$blockErr="it should be only characters";
	else 
	{
		$cvvErr="";
		$cvv=test($_POST["cvv"]);
	}/*
	if(empty($_POST["expiry"]))
		$expiryErr="expiry date is mandatory!";
	else if(!(preg_match('/^[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]$/',$_POST["expiry"])))
	//else if($len2!=10)
		$expiryErr="expiry date should be of [d-m-Y] format";
	else 
	{
		$expiryErr="";
		$expiry=test($_POST["expiry"]);
	}
	*/
	}

function test($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	return $data;
}

session_start();
$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);

		
?>

<form method="post" action="<?php $_PHP_SELF?>">
<table>

<tr>
	<th>Enter card number</th>
	<td><input type="text" name="card" value="<?php echo (isset($_POST['card']) && !empty($_POST['card']) &&$cardErr=="" ) ? $_POST['card'] : "" ?>">
	<span class="error">*<?php echo $cardErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>Enter cvv</th>
	<td><input type="text" name="cvv" value="<?php echo (isset($_POST['cvv']) && !empty($_POST['cvv']) &&$cvvErr=="" ) ? $_POST['cvv'] : "" ?>">
	<span class="error">*<?php echo $cvvErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>Enter Expiry Date</th>
	<td><input type="date" data-date-format="DD MM YYYY" name="expiry" value="<?php echo (isset($_POST['expiry']) && !empty($_POST['expiry']) &&$expiryErr=="" ) ? $_POST['expiry'] : "" ?>">
	<span class="error">*<?php echo $expiryErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>Enter card to be blocked or unblocked</th>
	<td><input type="text" name="block" >
	<span class="error">*<?php echo $blockErr?></span>
	</td>
	<br/>
</tr>


</table>

Total available amount<br/>

<?php
date_default_timezone_set("Asia/Kolkata");
$trans_flag=0;
//Find the balance of current customer to see if amount transferred is within the balance or not
$query="select Balance from account where Account_number=$_SESSION[acc_no]";
$result=mysqli_query($con,$query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($value = mysqli_fetch_assoc($result)) {
		$bal=$value['Balance'];
        echo "INR ".$bal;
    }
}
echo " as of ".date("d/m/Y h:i:sa")."<br>";



?>
<input type="submit" value="Submit">

</form>
<?php


//echo "hhhhhhhhhhhhhh";
if($cardErr==""&&$cvvErr==""&&$expiryErr=="")
	{//echo "sfd";
$flag=1;
		$date=date('d-m-Y',strtotime($_POST['expiry']));
		$query="select * from debit_card";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{//echo "<br>",$diff2," svsd ",$am;
				//echo "<br>",$value['Account_number']," jhb ",$_SESSION['acc_no']," jhb ",$value['card_no']," jhb ",$_POST['card']," jhb ",$value['cvv']," jhb ",$_POST['cvv']," jhb ",$value['expiry_date']," jhb ",$date;
				if($value['Account_number']==$_SESSION['acc_no']&&$value['card_no']==$_POST['card']&&$value['cvv']==$_POST['cvv']&&$value['expiry_date']==$date)
			{
				//$value['block']=$_POST['block'];
				//echo "swwwwwwwww";
				$flag=0;
				
			break;
			}
			
	}
	if ($flag==0){
		//echo "<br>dssd ",$_POST['block'],$_SESSION[acc_no];
		$query="update debit_card set block='$_POST[block]' where Account_number=$_SESSION[acc_no]";
			$result=mysqli_query($con,$query);
	}
	}
?>

</body>

</html>