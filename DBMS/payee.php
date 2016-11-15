<!doctype html>
<html>
<head><title>Add payee</title></head>
<style>
th
{
	text-align:left;
}
</style>
<body>
<?php
error_reporting(0);
$acc_no=$payee_acc=$name=$nick="";
$nameErr=$acc_noErr=$payee_accErr=$nickErr="";
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST")
{
	
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
	
	
	if(empty($_POST["nick"]))
	{
		$nickErr="Nickname is required";
	}
	else if(!(preg_match('/^[a-zA-Z]+$/',$_POST["nick"])))
		$nickErr="Nickname should contain only alphabets!";
	else
	{
		$nickErr="";
		$nick=test($_POST["nick"]);
	}
	
	
	if(empty($_POST["payee_acc"]))
		$payee_accErr="Payee account number is required!";
	else if(!(preg_match('/^[0-9]{12}$/',$_POST["payee_acc"])))
		$payee_accErr="Payee account number should have 12 digits!";
	else 
	{
		$payee_accErr="";
		$payee_acc=test($_POST["payee_acc"]);
	}
	
	if(empty($_POST["acc_no"]))
		$acc_noErr="Field is required!";
	else if(!(preg_match('/^[0-9]{12}$/',$_POST["acc_no"])))
		$acc_noErr="Account number should have 12 digits!";
	else 
	{
		$acc_noErr="";
		$acc_no=test($_POST["acc_no"]);
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
	<th>Nickname</th>
	<td><input type="text" name="nick" value="<?php echo (isset($_POST['nick']) && !empty($_POST['nick']) &&$nickErr=="") ? $_POST['nick'] : "" ?>">
	<span class="error">*<?php echo $nickErr;?></span>
	</td>
	<br/>
</tr>


<tr>
	<th>Payee account number</th>
	<td><input type="text" name="payee_acc" value="<?php echo (isset($_POST['payee_acc']) && !empty($_POST['payee_acc']) &&$payee_accErr=="" ) ? $_POST['payee_acc'] : "" ?>">
	<span class="error">*<?php echo $payee_accErr?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Confirm account number</th>
	<td><input type="text" name="acc_no" value="<?php echo (isset($_POST['acc_no']) && !empty($_POST['acc_no']) &&$acc_noErr=="" ) ? $_POST['acc_no'] : "" ?>">
	<span class="error">*<?php echo $acc_noErr?></span>
	</td>
	<br/>
</tr>



</table>
<input type="submit" value="submit">
<input type="reset" value="reset">


</form>

<?php
//session_start();
//CHANGED CUSTOMER TABLE TO USER VIEW-&&$value['Name']!=NULL condn on line 140
$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
$query="select * from user";
$result=mysqli_query($con,$query);
$flag=0;
$acc_flag=0;
$payee_flag=0;
$ac=0;

if($_POST["name"]!=""&&$_POST["nick"]!=""&&$_POST["payee_acc"]!=""&&$_POST["acc_no"]!=""&&$nameErr==""&&$acc_noErr==""&&$payee_accErr==""&&$nickErr=="")
	{
		$name_flag=0;
		//Payee ac no and confirm field should match and should not be equal to your own acc no
		if($_POST["acc_no"]==$_POST["payee_acc"]&&$_POST["payee_acc"]!=$_SESSION["acc_no"])
		{
				while($value=mysqli_fetch_array($result))
				{	
					if($_POST["payee_acc"]==$value['Account_number'])
					{	
						$flag=1;
						$query="select Name from payee where Account_number=$_SESSION[acc_no]";
						$res=mysqli_query($con,$query);
						while($row=mysqli_fetch_array($res))
						{
							if(strcmp($row['Name'],$_POST["name"])==0)
							{
								echo "Name should be unique!";
								$name_flag=1;
								break;
							}
						}
						if($name_flag!=1)
						$query="insert into payee values('$_SESSION[acc_no]','$_POST[payee_acc]','$_POST[name]','$_POST[nick]')";
						if(!mysqli_query($con,$query))
							echo "Payee already exists!!";
						
						break;
						
					}
				}
				
				if($flag==0)
					{
						
						echo "<br>Invalid payee account number!";
					}
			
			
			
		}
		//Check if acc no and confirm field match
		else if($_POST["acc_no"]!=$_POST["payee_acc"])
		{	
			echo "<br>Payee account number and the confirm field should match!!";
		}
		else echo "<br>Payee account number should be different from your own!";
	
	}
?>




</body>
</html>