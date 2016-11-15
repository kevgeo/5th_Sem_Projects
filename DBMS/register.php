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
if(!this.form.checkbox.checked)
{
    alert('You must agree to the terms and conditions first.');
    return false;
}
</script>

<?php


error_reporting(0);
session_start();
//MAKE PASSWORD TO CONTAIN ALPHANUMERICS
//INVALID ACCOUNT NUMBER MESSAGE ON TOP
//SUBMIT BUTTON IS ENABLED  ONLY AFTER CHECKING THE CHECK BOX


$name=$email=$acc_no=$pwd=$mobile=$conPwd=$cust_id=$trans_pwd=$con_transPwd="";
$nameErr=$emailErr=$acc_noErr=$pwdErr=$mobErr=$conPwdErr=$cust_idErr=$trans_pwdErr=$con_transPwdErr="";

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
	if(empty($_POST["acc_no"]))
		$acc_noErr="Account number is required!";
	else if(!(preg_match('/^[0-9]{12}$/',$_POST["acc_no"])))
		$acc_noErr="Account number should have 12 digits!";
	else 
	{
		$acc_noErr="";
		$acc_no=test($_POST["acc_no"]);
	}
	
	if(empty($_POST["cust_id"]))
		$cust_idErr="Customer id is required!";
	else if(!(preg_match('/^[0-9]{9}$/',$_POST["cust_id"])))
		$cust_idErr="Customer id should have 9 digits!";
	else 
	{
		$cust_idErr="";
		$cust_id=test($_POST["cust_id"]);
	}
	
	if(empty($_POST["pwd"]))
		$pwdErr="Password is required!";
	else if(!(preg_match('/[a-z]/',$_POST["pwd"]))||!(preg_match('/[A-Z]/',$_POST["pwd"]))||!(preg_match('/[0-9]/',$_POST["pwd"])))
		$pwdErr="Password should contain alphanumerics!";
	else if($len<8||$len>15)
		$pwdErr="Password should be minimum 8 and max 15 characters!";
	else 
	{
		$pwdErr="";
		
		$pwd=test($_POST["pwd"]);
		
	}
	
	if(empty($_POST["conPwd"]))
		$conPwdErr="Password is required!";
	else if(strcmp($_POST["pwd"],$_POST["conPwd"])!=0)
		$conPwdErr="Invalid match to password!";
	else 
	{
		$conPwdErr="";
		
		$conPwd=test($_POST["conPwd"]);
		
	}
	
	
	if(empty($_POST["trans_pwd"]))
		$trans_pwdErr="Transaction password is required!";
	else if($len<8||$len>15)
		$trans_pwdErr="Password should be minimum 8 and max 15 characters!";
	else if(!(preg_match('/[a-z]/',$_POST["trans_pwd"]))||!(preg_match('/[A-Z]/',$_POST["trans_pwd"]))||!(preg_match('/[0-9]/',$_POST["trans_pwd"])))
		$trans_pwdErr="Password should contain alphanumerics!";
	else 
	{
		$trans_pwdErr="";
		
		$trans_pwd=test($_POST["trans_pwd"]);
		
	}
	
	if(empty($_POST["con_transPwd"]))
		$con_transPwdErr="Confirm field is required!";
	else if(strcmp($_POST["trans_pwd"],$_POST["con_transPwd"])!=0)
		$con_transPwdErr="Invalid match to transaction password!";
	else 
	{
		$con_transPwdErr="";
		
		$con_transPwd=test($_POST["con_transPwd"]);
		
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
	<th>Account number</th>
	<td><input type="text" name="acc_no" value="<?php echo (isset($_POST['acc_no']) && !empty($_POST['acc_no']) &&$acc_noErr=="" ) ? $_POST['acc_no'] : "" ?>">
	<span class="error">*<?php echo $acc_noErr?></span>
	</td>
	<br/>
</tr>


<tr>
	<th>Customer id</th>
	<td><input type="text" name="cust_id" value="<?php echo (isset($_POST['cust_id']) && !empty($_POST['cust_id']) &&$cust_idErr=="") ? $_POST['cust_id'] : "" ?>">
	<span class="error">*<?php echo $cust_idErr?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Login Password</th>
	<td><input type="password" name="pwd" value="<?php echo (isset($_POST['pwd']) && !empty($_POST['pwd']) &&$pwdErr=="") ? $_POST['pwd'] : "" ?>">
	<span class="error">*<?php echo $pwdErr?></span>
	</td>
	
	<br/>
</tr>

<tr>
	<th>Confirm login password</th>
	<td><input type="password" name="conPwd" value="<?php echo (isset($_POST['conPwd']) && !empty($_POST['conPwd']) &&$conPwdErr=="" ) ? $_POST['conPwd'] : "" ?>">
	<span class="error">*<?php echo $conPwdErr?></span>
	</td>
	
	<br/>
</tr>

<tr>
	<th>Transaction Password</th>
	<td><input type="password" name="trans_pwd" value="<?php echo (isset($_POST['trans_pwd']) && !empty($_POST['trans_pwd']) &&$trans_pwdErr=="") ? $_POST['trans_pwd'] : "" ?>">
	<span class="error">*<?php echo $trans_pwdErr?></span>
	</td>
	
	<br/>
</tr>

<tr>
	<th>Confirm transaction password</th>
	<td><input type="password" name="con_transPwd" value="<?php echo (isset($_POST['con_transPwd']) && !empty($_POST['con_transPwd']) &&$conPwdErr=="" ) ? $_POST['con_transPwd'] : "" ?>">
	<span class="error">*<?php echo $con_transPwdErr?></span>
	</td>
	
	<br/>
</tr>

<tr>
	<th>Email</th>
	<td><input type="text" name="email" value="<?php echo (isset($_POST['email']) && !empty($_POST['email']) &&$emailErr=="" ) ? $_POST['email'] : "" ?>">
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




<input type="checkbox" name="checkbox" value="check"  />I accept the <a href="terms.html" target="_blank">terms and conditions</a>

<input type="submit" value="Submit" onclick="if(!this.form.checkbox.checked){alert('You must agree to the terms and conditions first.');return false}">

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
if($_POST["name"]!=""&&$_POST["mobile"]!=""&&$_POST["pwd"]!=""&&$_POST["email"]!=""&&$_POST["acc_no"]!=""&& $_POST["conPwd"]!=""&& $_POST["trans_pwd"]!=""&& $_POST["con_transPwd"]!=""&&$mobErr==""&&$nameErr==""&&$acc_noErr==""&&$emailErr==""&&$pwdErr==""&&$conPwdErr==""&&$trans_pwdErr==""&&$con_transPwdErr=="")
	{
		$_SESSION["name"]=$_POST["name"];
		$_SESSION["email"]=$_POST["email"];
		$_SESSION["acc_no"]=$_POST["acc_no"];
		$_SESSION["cust_id"]=$_POST["cust_id"];
		while($value=mysqli_fetch_array($result))
		{				
			if($_POST["acc_no"]==$value['Account_number'])
				$acc_flag=1;
			if($_POST["acc_no"]==$value['Account_number']&& $_POST["mobile"]==$value['Mobile number']&&$_POST["cust_id"]==$value['Customer_id'])
			{
				
				$flag=1;
				if($value['Name']!=NULL)
					$name_flag=1;
				else
				{
					$upd="update customer set Name='$_POST[name]',Login_password='$_POST[pwd]',Transaction_password='$_POST[trans_pwd]',Email_id='$_POST[email]' where Account_number=$_POST[acc_no]";  // and Login password=$_POST[pwd] where $_POST[acc_no]='$value[Account number] ,Login password='$_POST[pwd]' '";   //$value[Login password]
					mysqli_query($con,$upd);
				}
				break;
				
			}
		}
		echo "flag=".$flag;
		if($acc_flag==0)
		echo "Invalid account number!";
		
		else if($flag==0)
			echo "Invalid combination of customer id,account number and mobile number!";
		else if($name_flag==1)
			echo "You have already registered!";
		else header("Location:home.php");
	
	}
?>


</body>
</html>