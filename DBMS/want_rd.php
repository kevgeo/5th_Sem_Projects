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
$transErr=$mobErr=$withdrawErr=$idErr="";
if($_SERVER["REQUEST_METHOD"]=="POST")
{
	
	if(empty($_POST["id"]))
		$idErr="id no is mandatory!";
	
	else 
	{
		$idErr="";
		$id=test($_POST["id"]);
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
	
	
	
	if(!preg_match('/^[a-zA-Z]*$/',$_POST["withdraw"]))
		$amountErr="withdarw has to be a character!";
	else
	{
		$withdrawErr="";
		$withdraw=test($_POST["withdraw"]);
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
	<th>Registered Mobile number</th>
	<td><input type="text" name="mobile" value="<?php echo (isset($_POST['mobile']) && !empty($_POST['mobile']) &&$mobErr=="" ) ? $_POST['mobile'] : "" ?>">
	<span class="error">*<?php echo $mobErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>Want to withdraw the money?</th>
	<td><input type="text" name="withdraw" value="<?php echo (isset($_POST['withdraw']) && !empty($_POST['withdraw']) &&$withdrawErr=="" ) ? $_POST['withdraw'] : "" ?>">
	<span class="error">*<?php echo $withdrawErr?></span>
	</td>
	<br/>
</tr>
<tr>
	<th>If yes enter the id</th>
	<td><input type="text" name="id" value="<?php echo (isset($_POST['id']) && !empty($_POST['id']) &&$idErr=="" ) ? $_POST['id'] : "" ?>">
	<span class="error">*<?php echo $idErr?></span>
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
if($transErr==""&&$mobErr==""&&$withdrawErr==""&&$idErr==""&&$_POST['id']!=""&&$_POST['withdraw']!=""&&$_POST["trans"]!=""&&$trans_flag==0)
	{
		$flag=0;
		$flag1=0;
		$flag2=0;
		
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
			$query="select * from deposit";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Account_number']==$_SESSION['acc_no'])
				{
					$bal=$value['Balance'];
					echo "<br>".$bal;
					$expiryd2=$value['date_of_expiry'];
					break;
				}
			}
		
			$flag=0;
			$curr_date2=date("Y-m-d",strtotime($curr_date));
			$expiryd=strtotime($expiryd2);
			$curr_date=strtotime($curr_date);
			$date1 = new DateTime($curr_date2);
			$query="select * from deposit where Type in (select Type from deposit where Account_number=$_SESSION[acc_no])";
			$result=mysqli_query($con,$query);
			while($value=mysqli_fetch_array($result))
			{ 
				
				if($value['id']==$_POST['id'])
				{
					$issue=$value['date_of_issue'];
				$principal=$value['Amount'];
				break;
				}
			}
			
			$date2 = new DateTime($issue);
			$diff = $date1->diff($date2);
			if ($diff->m==0)
			$diff2= $diff->y*12;
			else if ($diff->y==0) $diff2= $diff->m;
			else $diff2=0;
			if (strcmp($_POST['withdraw'],"yes")==0&&$curr_date<$expiryd){
			
			$n=$diff2;
			$rate=0.065;
			$interest=(($principal*($n+1)*$rate)/2400)+$principal;
		
			$query="delete from deposit where Account_number=$_SESSION[acc_no] and id=$_POST[id]";
				$result=mysqli_query($con,$query);
			}
			else if (strcmp($_POST['withdraw'],"yes")==0||$curr_date>=$expiryd){
			
			$n=$diff2;
			$rate=0.075;
			$interest=(($principal*($n+1)*$rate)/2400)+$principal;
			$query="delete from deposit where Account_number=$_SESSION[acc_no] id=$_POST[id]";
				$result=mysqli_query($con,$query);
			
			
			}
			
			
	}}
?>

	
		

</body>

</html>