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
$amountErr=$transErr=$mobErr=$cardErr=$expiryErr=$cvvErr="";
if($_SERVER["REQUEST_METHOD"]=="POST")
{
	if(empty($_POST["amount"]))
	{
		$amountErr="Amount is required!!";
	}
	else if(!preg_match('/^[0-9]+$/',$_POST["amount"]))
		$amountErr="Amount is a number!";
	else
	{
		$amountErr="";
		$amount=test($_POST["amount"]);
	}
	
	if(empty($_POST["trans"]))
		$transErr="Password is required!";
	else
		$transErr="";
	if(empty($_POST["mobile"]))
		$mobErr="Mobile no is mandatory!";
	else if(!(preg_match('/^[0-9]{10}$/',$_POST["mobile"])))
	//else if($len2!=10)
		$mobErr="Mobile no should hav exactly 10 digits!";
	else 
	{
		$mobErr="";
		$mobile=test($_POST["mobile"]);
	}
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
	else 
	{
		$cvvErr="";
		$cvv=test($_POST["cvv"]);
	}
	
	
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
	<th>Transaction password</th>
	<td><input type="password" name="trans" value="<?php echo (isset($_POST['trans']) && !empty($_POST['trans'])) ? $_POST['trans'] : "" ?>">
	<span class="error">*<?php echo $transErr;?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Amount</th>
	<td><input type="text" name="amount" value="<?php echo (isset($_POST['amount']) && !empty($_POST['amount'])) ? $_POST['amount'] : "" ?>">
	<span class="error">*<?php echo $amountErr;?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>Registered Mobile number</th>
	<td><input type="text" name="mobile" value="<?php echo (isset($_POST['mobile']) && !empty($_POST['mobile']) &&$mobErr=="" ) ? $_POST['mobile'] : "" ?>">
	<span class="error">*<?php echo $mobErr?></span>
	</td>
	<br/>
</tr>
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

//Authenticate the transaction password against the database
$query="select Transaction_password from customer where Account_number=$_SESSION[acc_no]";
$result=mysqli_query($con,$query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($value = mysqli_fetch_assoc($result)) {
		$fetched_pwd=$value['Transaction_password'];
        
    }
	if($_POST["trans"]!=""&&strcmp($_POST["trans"],$fetched_pwd)!=0)
	{
		$transErr="Invalid transaction password!";
		$trans_flag=1;
	}
	
}

?>
<input type="submit" value="Submit">

</form>
<?php

if($trans_flag==1)
	echo "<br>$transErr";
$flag=1;
$query="select * from debit_card where Account_number=$_SESSION[acc_no]";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{echo "<br>$value[block]";
				if(strcmp($value['block'],"block")==0){
				
				$flag=0;
				break;
			}
		}
		if($flag==0){
			echo "sorry your card is blocked ";
		}
else if($amountErr==""&&$transErr==""&&$_POST["amount"]!=""&&$_POST["trans"]!=""&&$trans_flag==0&&$cardErr==""&&$cvvErr==""&&$_POST['cvv']&&$_POST['card'])
	{
		$flag2=0;
		echo "bal:".$bal;
		if($_POST["amount"]>$bal){
		$flag2=1;
		}$flag=0;
			$query="select * from customer";
			$result=mysqli_query($con,$query);
			while($value=mysqli_fetch_array($result))
			{
				if($value['Mobile number']==$_POST['mobile'])
				{
					$flag=1;
					break;
				}
			}
			$flag3=0;
			$query="select * from debit_card d join account a on a.Account_number=d.Account_number join customer c on c.Customer_id=$_SESSION[cust_id]";
			$result=mysqli_query($con,$query);
				while($value=mysqli_fetch_array($result))
						{
							$newDate = date("d-m-Y", strtotime($_POST['expiry']));
				if($value['card_no']==$_POST['card']&&$value['cvv']==$_POST['cvv']&&$value['expiry_date']==$newDate)
				{$flag3=1;
			break;}
				
			}

			
			if($flag==0){
				echo "Invalid mobile number!";
			}
			else if($flag2==1){echo "<br>Insufficient funds!";}
			else if($flag3==0){echo "<br> card details are wrong";}
		else
		{
			$day=date("Y/m/d");
			$updated_bal=$bal-$_POST["amount"];
			$query="update account set Balance='$updated_bal',Modified_date='$day'where Account_number=$_SESSION[acc_no]";
			$result=mysqli_query($con,$query);
			$tid=mysqli_insert_id($con);
			$query="insert into transaction values('$tid','$_SESSION[cust_id]','$_POST[amount]','$day','Mobile bill payment of INR $_POST[amount]')";
			$result=mysqli_query($con,$query);
			
		}
			
	}
?>

</body>

</html>