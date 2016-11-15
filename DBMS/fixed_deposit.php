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
$amountErr=$transErr=$mobErr=$emailErr=$monthsErr=$monthsErr="";
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
	<th>Tenure of deposit(years)</th>
	<td><select name="months">
	<option>Select</option>
	<option>6 months</option>
	<option >1</option>

	<option>2</option>

	<option>3</option>

	<option>4</option>

	<option>5</option>

	<option>6</option>
<option>7</option>
<option>8</option>

<option>9</option>
	
<option>10</option>
	</select>
	<span class="error">*<?php echo $monthsErr;?></span>
	</td>
	<br/>
</tr>


<tr>
	<th>PAN</th>
	<td><input type="text" name="pan" >
	
	</td>
	<br/>
</tr>
<tr>
	<th>Email</th>
	<td><input type="text" name="email" value="<?php echo $_SESSION['email']?>">
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
<input type="checkbox" name="checkbox" value="check"  />I accept the <a href="terms3.html" target="_blank">terms and conditions</a>
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
//Find the balance of current customer to see if amount transferred is within the balance or not

/*$query="select Balance from account where Account_number=$_SESSION[acc_no]";
$result=mysqli_query($con,$query);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($value = mysqli_fetch_assoc($result)) {
		$bal=$value['Balance'];
        echo "INR ".$bal;
    }
}
echo " as of ".date("d/m/Y h:i:sa")."<br>";*/

$query="DELIMITER $$
 
CREATE PROCEDURE GetCustomers()
BEGIN
 SELECT customerName, creditlimit
 FROM customers;
    END$$";
$query="Create  PROCEDURE Getbal
(

@num BIGINT,                       
@bal BIGINT  OUT        
)
AS
BEGIN
SELECT @bal= Balance FROM account WHERE Account_number=@num
END;";
$result=mysqli_query("CALL Getbal();");

$query="Declare @bal as bigint  
Execute Getbal $_SESSION[acc_no], @bal output
select @bal";
$result=mysqli_query($con,$query);
echo "<br>BAL:@bal";



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
<input type="submit" value="Submit" onclick="if(!this.form.checkbox.checked){alert('You must agree to the terms first.');return false}">

</form>
	
<?php

if($trans_flag==1)
	echo "<br>$transErr";
if($amountErr==""&&$transErr==""&&$monthsErr==""&&$_POST["amount"]!=""&&$_POST['months']!=""&&$_POST["trans"]!=""&&$trans_flag==0&&$mobErr==""&&$_POST['mobile'])
	{$flag=0;
$flag1=0;
		echo "bal:".$bal;
		if($_POST["amount"]>$bal){
		$flag1=1;
		}
		
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
			//echo "sssssssss",$years;
			if(strcmp($_POST['months'],"6 months")==0)
			{$var='+'.$_POST['months'];
		//echo "<br>ev ",$var,"sdvd ",$years;
		}
			else $var='+'.$years.' years';
			//echo "<br>ev ",$var,"sdvd ",$years;
			$expiryd=date('d-m-Y', strtotime($var));
			$curr_date=date("d-m-Y");
			//echo $_POST['day'],"qws",$_SESSION['acc_no'];
			echo "<br>",$_SESSION[acc_no],'FD',$_POST['amount'],$curr_date,$expiryd;
			$expiryd2=date('Y-m-d', strtotime($expiryd));
			$curr_date2=date("Y-m-d",strtotime($curr_date));
			$expiryd=strtotime($expiryd);
			$curr_date=strtotime($curr_date);
			//echo "<br>",$_SESSION[acc_no],'FD',$_POST['amount'],$curr_date2,$expiryd2;
			
			
			/*//echo "<br>",$n,"xdg",$interest,"bjk ",pow((1+($rate/$n)),($n*$t));
			$today_time = strtotime($curr_date);
			//echo "<br>",$today_time,"wed",$interest,"<br>";
			$expire_time = strtotime($_POST['day']);
			//echo "<br>",$expire_time,"wq<br>";
			*/
			//$withdraws=date('d-m-Y', strtotime($_POST['withdraw']));
			$date1 = new DateTime($curr_date2);
			//$query="select * from deposit";
			$query="select * from deposit where Type in (select Type from deposit where Account_number=$_SESSION[acc_no])";
			$result=mysqli_query($con,$query);
			while($value=mysqli_fetch_array($result))
			{ 
				echo "<br>acc_no:$value[Account_number]";//"id:$value[id]";
				if($value['id']==$_POST['id'])
				{
					$issue=$value['date_of_issue'];
					echo "<br> hellooooooo";
				break;
				}
			}
			/*while($value=mysqli_fetch_array($result))
			{echo "<br>",$diff2," svsd ",$am;
				
				if($value['Account_number']==$_SESSION['acc_no']&&strcmp($value['Type'],'FD')==0&&$value['id']==$_POST['id'])
			{
				$issue=$value['date_of_issue'];
				break;
				
			}}*/
			$date2 = new DateTime($issue);
			$diff = $date1->diff($date2);
			if ($diff->m==0)
			$diff2= $diff->y;
		else $diff2= $diff->m;
			echo "difference " . $diff->y . " years, " . $diff->m." months, ".$diff->d." days ";
			if (strcmp($withdraw,"yes")==0&&$curr_date<$expiryd){
				$principal=$_POST['amount'];
			if($diff->m==0)
				$t=$_POST['months'];
			else $t=$diff2/12;
			$rate=0.065;
			 //P x (1 + r/n)nt 
             //I = A - P  
			 $n=($t*12)/6;
			 echo "<br> Your amount is: ",$interest;
			$interest=($principal*pow((1+($rate/$n)),($n*$t)));
			echo "<br> Your amount is: ",$interest;
			$query="delete from deposit where Account_number=$_SESSION[acc_no] and id=$_POST[id]";
				$result=mysqli_query($con,$query);
			}
			else if (strcmp($withdraw,"yes")==0||$curr_date>=$expiryd){
			$principal=$_POST['amount'];
			if(strcmp($_POST['months'],"6 months")==0) $t=0.5;
			else $t=$_POST['months'];
			$rate=0.075;
			 //P x (1 + r/n)nt 
             //I = A - P  
			 $n=($t*12)/6;
			 
			$interest=($principal*pow((1+($rate/$n)),($n*$t)))-$_POST['amount'];
			echo "<br> Your amount is: ",$interest;
			$query="delete from deposit where Account_number=$_SESSION[acc_no] and id=$_POST[id]";
				$result=mysqli_query($con,$query);
			
			
			}else{$query="INSERT INTO deposit (Account_number, Type, Amount, date_of_issue, date_of_expiry) VALUES ('$_SESSION[acc_no]','FD','$_POST[amount]','$curr_date2','$expiryd2');";
			$result=mysqli_query($con,$query);
				
			}
			
			
		}
		
		
			
	}
	$query="select sum(Amount) as Total_FD,min(Amount) as Minimum_FD,max(Amount) as Maximum_FD from deposit where Account_number=$_SESSION[acc_no] and Type='FD'";
		$result=mysqli_query($con,$query);
		while($value=mysqli_fetch_array($result))
			{
				echo "Total FD:$value[Total_FD],Minimum FD:$value[Minimum_FD],Maximum FD:$value[Maximum_FD]";
			}
	/*$query="select * from deposit  where Account_number=$_SESSION[acc_no] group by Type";
		$result=mysqli_query($con,$query);
		while($value=mysqli_fetch_array($result))
			{
				echo "<br>Account_number:$value[Account_number],Type:$value[Type],Amount:$value[Amount],,Date:$value[date_of_issue]";
			}*/
			
		
?>


	
		

</body>

</html>