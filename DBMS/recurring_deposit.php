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
error_reporting(0);
$amountErr=$transErr=$mobErr=$panErr=$emailErr=$monthsErr=$dayErr=$monthsErr="";
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
	if($_POST["months"]=="")
	{
		$monthsErr="month is required!!";
	}else $monthsErr="";
	if(empty($_POST["day"]))
	{
		$dayErr="day is required!!";
	}
	else if(!preg_match('/^[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]$/',$_POST["day"]))
		$dayErr="day is a number!";
	else
	{
		$dayErr="";
		$day=test($_POST["day"]);
	}
	
	if(empty($_POST["trans"]))
		$transErr="Password is required!";
	else
		$transErr="";
	if(empty($_POST["mobile"]))
		$mobErr="Mobile no is mandatory!";
	else if(!(preg_match('/^[0-9]{10}$/',$_POST["mobile"])))
	
		$mobErr="Mobile no should hav exactly 10 digits!";
	else 
	{
		$mobErr="";
		$mobile=test($_POST["mobile"]);
	}
	
	if(empty($_POST["email"]))
		$emailErr="Email id is required!";
	else if(filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)===false)
	{
		$emailErr="Invalid email address";
	}
	
	
	else 
	{
		$emailErr="";
		$email=test($_POST["email"]);
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
	<th>Tenure of deposit(months)</th>
	<td><select name="months">
	<option>Select</option>
	<option>6</option>

	<option>9</option>

	<option>12</option>

	<option>15</option>

	<option>24</option>

	<option>27</option>
<option>36</option>
<option>39</option>

<option>48</option>
	
<option>60</option>
	
<option>90</option>

<option>120</option>
	</select>
	<span class="error">*<?php echo $monthsErr;?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>PAN</th>
	<td><input type="text" name="pan" value="<?php echo (isset($_POST['pan']) && !empty($_POST['pan'])) ? $_POST['pan'] : "" ?>">
	
	</td>
	<br/>
</tr>
<tr>
	<th>Email</th>
	<td><input type="text" name="email" value="<?php echo $_SESSION['email'] ?>">
	<span class="error"><?php echo $emailErr?></span>
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


</table>
<input type="checkbox" name="checkbox" value="check"  />I accept the <a href="terms2.html" target="_blank">terms and conditions</a>
<br>
Total available amount<br/>
<script>
if(!this.form.checkbox.checked)
{
    alert('You must agree to the terms first.');
    return false;
}
</script>
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
<input type="submit" value="Submit" onclick="if(!this.form.checkbox.checked){alert('You must agree to the terms first.');return false}">

</form>
	
<?php

if($trans_flag==1)
	echo "<br>$transErr";

if($amountErr==""&&$transErr==""&&$monthsErr==""&&$_POST["amount"]!=""&&$_POST['months']!=""&&$_POST["trans"]!=""&&$trans_flag==0)
	{
		$flag=0;
$flag1=0;
$flag2=0;
		echo "bal:".$bal;
		if($_POST["amount"]>$bal){
		$flag1=1;
		}
		if($bal<1000){$flag2=1;}
		
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
			if($flag==0){
				echo "Invalid mobile number!";
			}
			else if($flag1==1){echo "<br>Insufficient funds!";}
			else if($flag2==1){echo "<br>mMnimum amount should be 1000 rs";}
		else
		{
			$day=date("Y/m/d");
			$query="select * from account";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Account_number']==$num)
				{
					$bal=$value['Balance'];
					echo "<br>".$bal;
					break;
				}
			}
		
			$flag=0;
			$days=(string)$_POST['day'];
			$years=$_POST['months'];
			$var='+'.$years.' months';
			$expiryd=date('d-m-Y', strtotime($var));
			$curr_date=date("d-m-Y");
			$expiryd2=date('Y-m-d', strtotime($expiryd));
			$curr_date2=date("Y-m-d",strtotime($curr_date));
			$expiryd=strtotime($expiryd);
			$curr_date=strtotime($curr_date);
			$date1 = new DateTime($curr_date2);
			$query="select * from deposit";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Account_number']==$_SESSION['acc_no']&&strcmp($value['Type'],'RD')==0)
			{
				$issue=$value['date_of_issue'];
				break;
				
			}}
			$date2 = new DateTime($issue);
			$diff = $date1->diff($date2);
			if ($diff->m==0)
			$diff2= $diff->y*12;
			else if ($diff->y==0) $diff2= $diff->m;
			else $diff2=0;
			
			if (strcmp($withdraw,"yes")==0&&$curr_date<$expiryd){
				$principal=$_POST['amount'];
			$n=$diff2;
			$rate=0.065;
			$interest=(($principal*($n+1)*$rate)/2400)+$_POST['amount'];
		
			$query="delete from deposit where Account_number=$_SESSION[acc_no] and id=$_POST[id]";
				$result=mysqli_query($con,$query);
			}
			else if (strcmp($withdraw,"yes")==0||$curr_date>=$expiryd){
			
			$principal=$_POST['amount'];
			$n=$_POST['months'];
			$rate=0.075;
			$interest=(($principal*($n+1)*$rate)/2400)+$_POST['amount'];
			$query="delete from deposit where Account_number=$_SESSION[acc_no] id=$_POST[id]";
				$result=mysqli_query($con,$query);
			
			
			}
			else{
				$date=date("Y-m-d");
				$query="INSERT INTO deposit (Account_number, Type, Amount, date_of_issue, date_of_expiry) VALUES ('$_SESSION[acc_no]','RD','$_POST[amount]','$curr_date2','$expiryd2');";
			$result=mysqli_query($con,$query);
			$query3="insert into transaction values('$tid','$_SESSION[cust_id]','$_POST[amount]','$date','recurring deposit of INR $_POST[amount]')";
			$result3=mysqli_query($con,$query3);
			$bal=$bal-$_POST['amount'];
			
			$query3="update account set Balance='$bal', Modified_date='$date' where Account_number=$_SESSION[acc_no]";
			$result3=mysqli_query($con,$query3);
				
		}
			
	}}
	
	$query="select sum(Amount) as Total_RD,min(Amount) as Minimum_RD,max(Amount) as Maximum_RD from deposit where Account_number=$_SESSION[acc_no] and Type='RD'";
		$result=mysqli_query($con,$query);
		while($value=mysqli_fetch_array($result))
			{
				echo "Total RD:$value[Total_RD],Minimum RD:$value[Minimum_RD],Maximum RD:$value[Maximum_RD]";
			}
?>

	
		

</body>

</html>