<!doctype html>
<html>
<head><title>dth payment</title></head>

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
$amountErr=$transErr=$dthErr=$subErr="";
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
	if(empty($_POST["dth"]))
		$dthErr="dth name is must!";
		else 
	{
		$dthErr="";
		$dth=test($_POST["dth"]);
	}
	if(empty($_POST["sub"]))
		$subErr="subscriber no is mandatory!";
		else 
	{
		$subErr="";
		$sub=test($_POST["sub"]);
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
$query="select * from payee";
$result=mysqli_query($con,$query);
$flag=0;
$list=array();
$i=0;
	while($value=mysqli_fetch_array($result))
		{				
			
			if($_SESSION["acc_no"]==$value['Account_number'])
			{
				array_push($list,$value['Name']);
			}
			$i=$i+1;
		}
		
?>



<form method="post" action="<?php $_PHP_SELF?>">

<table>
<tr>
	<th>Remarks</th>
	<td><input type="text" name="remarks" value="<?php echo (isset($_POST['remarks']) && !empty($_POST['remarks'])) ? $_POST['remarks'] : "" ?>">
	</td>
	<br/>
</tr>

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
	<th>enter the dth provider name</th>
	<td><input type="text" name="dth" value="<?php echo (isset($_POST['dth']) && !empty($_POST['dth']) &&$mobErr=="" ) ? $_POST['dth'] : "" ?>">
	<span class="error">*<?php echo $dthErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>enter subscriber id</th>
	<td><input type="text" name="sub" value="<?php echo (isset($_POST['sub']) && !empty($_POST['sub']) &&$mobErr=="" ) ? $_POST['sub'] : "" ?>">
	<span class="error">*<?php echo $subErr?></span>
	</td>
	<br/>
</tr>
</table>

Total available amount<br/>

<?php
date_default_timezone_set("Asia/Kolkata");
$trans_flag=0;
$query="select Balance from account where Account_number=$_SESSION[acc_no]";
$result=mysqli_query($con,$query);
if (mysqli_num_rows($result) > 0) {
    while($value = mysqli_fetch_assoc($result)) {
		$bal=$value['Balance'];
        echo "INR ".$bal;
    }
}
echo " as of ".date("d/m/Y h:i:sa")."<br>";

$query="select Transaction_password from customer where Account_number=$_SESSION[acc_no]";
$result=mysqli_query($con,$query);
if (mysqli_num_rows($result) > 0) {
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
	<br><br><a href="debit_pay.php">Else pay through debit card</a>
<?php

if($trans_flag==1)
	echo "<br>$transErr";

if($payeeErr==""&&$amountErr==""&&$transErr==""&&$_POST["amount"]!=""&&$_POST["trans"]!=""&&$trans_flag==0&&$subErr==""&&$_POST['sub'])
	{
		
		if($_POST["amount"]>$bal)
		echo "<br>Insufficient funds!";
	else if($bal<1000){echo "<br>Minimum amount should be 1000";}
		else
		{
			$day=date("Y/m/d");
			$updated_bal=$bal-$_POST["amount"];
			$query="update account set Balance='$updated_bal',Modified_date='$day'where Account_number=$_SESSION[acc_no]";
			$result=mysqli_query($con,$query);
			  $tid=mysqli_insert_id($con);
			$query="insert into transaction values('$tid','$_SESSION[cust_id]','$_POST[amount]','$day','dth bill payment of INR $_POST[amount]')";
			$result=mysqli_query($con,$query);
		}
			
	}
?>

	
		

</body>

</html>