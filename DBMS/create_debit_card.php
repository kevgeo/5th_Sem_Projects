<!doctype html>
<html>
<head><title>Form</title></head>
<style>
th
{
	text-align:left;
}
.error {color: #FF0000;}
</style>


<body>
<script>


</script>

<?php


error_reporting(0);
session_start();
//MAKE PASSWORD TO CONTAIN ALPHANUMERICS
//INVALID ACCOUNT NUMBER MESSAGE ON TOP
//SUBMIT BUTTON IS ENABLED  ONLY AFTER CHECKING THE CHECK BOX


$name=$email=$pwd=$mobile="";
$nameErr=$emailErr=$pwdErr=$mobErr="";

//Set the errors for the fields

if($_SERVER["REQUEST_METHOD"]=="POST")
{
	$len=strlen($_POST["pwd"]);
	$len2=strlen($_POST["mobile"]);
	if(empty($_POST["name"]))
	{
		$nameErr="Name is required";
	}
	else if(!(preg_match('/^[a-zA-Z]+$/',$_POST["name"])))
		$nameErr="Name should contain only alphabets!";
	else
	{
		$nameErr="";
		$name=test($_POST["name"]);
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
	
	
	
	if(empty($_POST["pwd"]))
		$pwdErr="Password is required!";
	else if($len<3||$len>15)
		$pwdErr="Password should be minimum 8 and max 15 characters!";
	else 
	{
		$pwdErr="";
		
		$pwd=test($_POST["pwd"]);
		
	}
	
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
}
function test($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	return $data;
}

?>

<p><span class="error">* required field</span></p>
<form method="POST" action="<?php $_PHP_SELF ?>">  

<table>
<tr>
	<th>Name</th>
	<td><input type="text" name="name" value="<?php echo (isset($_POST['name']) && !empty($_POST['name']) &&$nameErr=="") ? $_POST['name'] : "" ?>">
	<span class="error">*<?php echo $nameErr;?></span>
	</td>
	<br/>
</tr>



<tr>
	<th>Enter Login Password</th>
	<td><input type="password" name="pwd" value="<?php echo (isset($_POST['pwd']) && !empty($_POST['pwd']) &&$pwdErr=="") ? $_POST['pwd'] : "" ?>">
	<span class="error">*<?php echo $pwdErr?></span>
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

<input type="checkbox" name="terms" id="terms" value="term n cond" id="terms"  onClick="check(this)">I accept the <a href="terms.html" target="_blank">terms and conditions</a>

<input type="submit" value="submit" id="submit" >
<input type="reset" value="reset">
<br/>

</form>

<?php
session_start();

$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
$query="select * from customer";
$result=mysqli_query($con,$query);
$flag=0;
$acc_flag=0;
$name_flag=0;
if($_POST["name"]!=""&&$_POST["mobile"]!=""&&$_POST["pwd"]!=""&&$_POST["email"]!=""&&$mobErr==""&&$nameErr==""&&$emailErr==""&&$pwdErr=="")
	{   //echo "ssssssssssssssssssssss";
		while($value=mysqli_fetch_array($result))
		{		//echo "<br>",$_POST["mobile"]," sh ",$value['Mobile number']	;	
			if($_SESSION["acc_no"]==$value['Account_number'])
		$acc_flag=1;}
	echo "flag=".$flag;
		if($acc_flag==0)
		echo "Invalid account number!";
	else{
		$query="select Account_number from customer inner join debit_card on customer.Account_number=debit_card.Account_number";
		$result=mysqli_query($con,$query);
		echo "<br>rows ",$result->num_rows;
		if($result->num_rows==0)
		{
			$query="select * from customer";
		$result=mysqli_query($con,$query);
			while($value=mysqli_fetch_array($result))
		{		//echo "<br>",$_POST["mobile"]," sh ",$value['Mobile number']	;	
			
			if($_SESSION["acc_no"]==$value['Account_number']&& $_POST["mobile"]==$value['Mobile number'])
			{
				$flag=1;
					function random16() {
						  $number = "";
						  for($i=0; $i<16; $i++) {
							$min = ($i == 0) ? 1:0;
							$number .= mt_rand($min,9);
						  }
						  return $number;
						}

						$num=random16();
								$cvv=mt_rand(100,999);
					$start = strtotime("10 September 2018");
					 
					//End point of our date range.
					$end = strtotime("22 July 2030");
					 
					//Custom range.
					$timestamp = mt_rand($start, $end);
					 
					//Print it out.
					$dates=date("d-m-Y", $timestamp);
							 //$dates=date('d-m-Y', $rand_epoch);
							 //echo "<br>",$num," srg ",$cvv," srg ",$dates;
		 $query="INSERT INTO debit_card ( cvv, expiry_date,Account_number) VALUES ($cvv,'$dates',$_SESSION[acc_no])";
				$result=mysqli_query($con,$query);
				break;
				
			}
		}

	}		
		else{
			echo "you already have a debit card";
			//header("Location:index.html");
		}
	}}
?>


</body>
</html>