<!doctype html>
<html>
<head><title>TRANSFER FUNDS</title></head>

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
//CHANGED CUSTOMER TABLE TO USER VIEW
error_reporting(0);
$amountErr=$payeeErr=$transErr="";
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
	if(empty($_POST["payees"]))
		$payeeErr="Select a payee!";
	else
		$payeeErr="";
	if(empty($_POST["trans"]))
		$transErr="Password is required!";
	else
		$transErr="";
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
To which payee account
<select name="payees">
<option value="" disable selected hidden>Select</option>
<?php
	for($i=0;$i<count($list);$i++)
	{
		echo "<option value=".$list[$i].">".$list[$i]."</option>";
	}
?>


</select>
<span class="error">*<?php echo $payeeErr;?></span>
<br/>
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
$query="select Transaction_password from user where Account_number=$_SESSION[acc_no]";
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

if($payeeErr==""&&$amountErr==""&&$transErr==""&&$_POST["amount"]!=""&&$_POST["trans"]!=""&&!empty($_POST["payees"])&&$trans_flag==0)
	{
		echo "bal:".$bal;
		if($_POST["amount"]>$bal)
			echo "<br>Insufficient funds!";
		else if($bal-$_POST["amount"]<1000)
			echo "<br>Minimum amount should be Rs 1000!!!";
		else
		{
			$day=date("Y/m/d");
			$updated_bal=$bal-$_POST["amount"];
			echo "<br>upd:$updated_bal";
			//Update balance of current customer
			$query="update account set Balance='$updated_bal',Modified_date='$day'where Account_number=$_SESSION[acc_no]";
			$result=mysqli_query($con,$query);
			
			
				
			$query="CREATE OR REPLACE TRIGGER display_balance_changes
			BEFORE UPDATE ON account
			FOR EACH ROW
			BEGIN
			   dbms_output.put_line('Old salary: ' || :OLD.Balance);
				dbms_output.put_line('New salary: ' || :NEW.salary);
			   dbms_output.put_line('Previous balance:'|| OLD.balance);
			   dbms_output.put_line('New balance:' ||NEW.balance);
			END;";
			$result=mysqli_query($con,$query);
			
			
			$query="select * from payee";
			
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Name']==$_POST[payees])
				{
					$num=$value['Payee_number'];
					
					break;
				}
			}
			$query="select * from account";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Account_number']==$num)
				{
					$bal=$value['Balance'];
					break;
				}
			}
			$updated_bal=$bal+$_POST['amount'];
			//Update balance of payee
			$query="update account set Balance='$updated_bal',Modified_date='$day' where Account_number=$num";
			$result=mysqli_query($con,$query);
		
			$flag=0;
			//Update the transaction made into transaction table
			$tid=mysqli_insert_id($con);
			$query="insert into transaction values('$tid','$_SESSION[cust_id]','$_POST[amount]','$day','INR $_POST[amount] transferred to $_POST[payees]')";
			$result=mysqli_query($con,$query);
		
			
		}
			
	}
?>
	
		

</body>

</html>