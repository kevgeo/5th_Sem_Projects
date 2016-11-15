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

<?php
error_reporting(0);
session_start();
//MAKE PASSWORD TO CONTAIN ALPHANUMERICS
//INVALID ACCOUNT NUMBER MESSAGE ON TOP


$name=$email=$acc_no=$pwd=$mobile=$conPwd=$cust_id="";
$nameErr=$emailErr=$acc_noErr=$pwdErr=$mobErr=$conPwdErr=$cust_idErr="";


if($_SERVER["REQUEST_METHOD"]=="POST")
{
	if(empty($_POST["cust_id"]))
		$cust_idErr="Customer id is required!";
	else 
	{
		$cust_idErr="";
		$cust_id=test($_POST["cust_id"]);
	}
	
	if(empty($_POST["pwd"]))
		$pwdErr="Password is required!";
	else 
	{
		$pwdErr="";
		
		$pwd=test($_POST["pwd"]);
		
	}
	
}
//The trim() function removes whitespace and other predefined characters from both sides of a string
//The stripslashes() function removes backslashes added by the addslashes() function
function test($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	return $data;
}

?>


<form method="post" action="<?php $_PHP_SELF ?>">  

<table>

<tr>
	<th>User id</th>
	<td><input type="text" name="cust_id" value="<?php echo (isset($_POST['cust_id']) && !empty($_POST['cust_id']) &&$cust_idErr=="") ? $_POST['cust_id'] : "" ?>">
	<span class="error">*<?php echo $cust_idErr?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Password</th>
	<td><input type="password" name="pwd" value="<?php echo (isset($_POST['pwd']) && !empty($_POST['pwd']) &&$pwdErr=="") ? $_POST['pwd'] : "" ?>">
	<span class="error">*<?php echo $pwdErr?></span>
	</td>
	
	<br/>
</tr>


</table>
<input type="submit" value="submit">
<input type="reset" value="reset">
</form>


<?php

session_start();
error_reporting(0);
$username='root';
$password='';
$hostname='localhost';
$db1='net_banking';
$db=mysqli_connect("$hostname","$username","$password","$db1") or die("Unable to connect to mysql");
mysqli_select_db($db,$db1);
$que="SELECT * FROM customer";

$result=mysqli_query($db,$que);
//Validate customer id and password against the database
if($_POST["cust_id"]!="" && $_POST["pwd"]!="" &&$cust_idErr==""&&$pwdErr=="")
{
	$flag=0;
	while($value=mysqli_fetch_array($result))
	{				
		 if($_POST["cust_id"]==$value['Customer_id']&& $_POST["pwd"]==$value['Login_password']&&$value['Name']!=NULL)
		{
			$flag=1;
			$_SESSION["name"]=$value['Name'];
			$_SESSION["email"]=$value['Email_id'];
			$_SESSION["acc_no"]=$value['Account_number'];
			$_SESSION["cust_id"]=$value['Customer_id'];
			 header("Location:home.php");
			break;
			
		}
	}
	if($flag==0)
		echo "Invalid combination of Customer id and password!<br>";
}
	


?>



</body>
</html>