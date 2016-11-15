<!doctype html>
<html>
<head><title>Car loan</title></head>
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

$city=$res=$dob=$car_model=$salary=$amt=$tenure="";
$cityErr=$resErr=$dobErr=$car_modelErr=$salaryErr=$amtErr=$tenureErr="";

//Set the errors for the fields

if($_SERVER["REQUEST_METHOD"]=="POST")
{
	
	if(empty($_POST["city"]))
	{
		$cityErr="City is required";
	}
	else if(!(preg_match('/^[a-zA-Z]+$/',$_POST["city"])))
		$cityErr="City should contain only alphabets!";
	else
	{
		$cityErr="";
		$city=test($_POST["city"]);
	}
	if(empty($_POST["res"]))
		$resErr="Residence type is required!";
	
	else 
	{
		$resErr="";
		$res=test($_POST["res"]);
	}
	if(empty($_POST["dob"]))
		$dobErr="DOB is required!";
	else 
	{
		$dobErr="";
		$dob=test($_POST["dob"]);
	}
	
	if(empty($_POST["car_model"]))
		$car_modelErr="Model is required!";
	else 
	{
		$car_modelErr="";
		$car_model=test($_POST["car_model"]);
	}
	
	if(empty($_POST["salary"]))
		$salaryErr="Salary is required!";
	
	else if(!preg_match('/^[0-9]+$/',$_POST["salary"]))
		$salaryErr="Salary is a number!";
	else
	{
		$salaryErr="";
		$salary=test($_POST["salary"]);
	}
	
	
	if(empty($_POST["amt"]))
	{
		$amtErr="Amount is required!!";
	}
	else if(!preg_match('/^[0-9]+$/',$_POST["amt"]))
		$amtErr="Amount is a number!";
	else if($_POST["amt"]<100000)
		$amtErr="Minimum loan amount is 1 lakh!";
	else if($_POST["amt"]>10000000)
		$amtErr="Minimum loan amount is 1 crore!!";
	else
	{
		$amtErr="";
		$amt=test($_POST["amt"]);
	}
	
	if(empty($_POST["tenure"]))
		$tenureErr="Tenure is required!";
	
	else 
	{
		$tenureErr="";
		$tenure=test($_POST["tenure"]);
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
	<th>Which city do you live in currently?</th>
	<td><input type="text" name="city" value="<?php echo (isset($_POST['city']) && !empty($_POST['city']) &&$cityErr=="") ? $_POST['city'] : "" ?>">
	<span class="error">*<?php echo $cityErr?></span>
	</td>
	
</tr>


<tr>
	<th>Residence type</th>
	<td><select name="res">
	<option value="" disable selected hidden>Select</option>
	<option value="Owned by parent/sibling">Owned by parent/sibling</option>
	<option value="Rented-with family">Rented-with family</option>
	<option value="Rented-with friends">Rented-with friends</option>
	<option value="Rented-staying alone">Rented-staying alone</option>
	<option value="Paying guest">Paying guest</option>
	<option value="Hostel">Hostel</option>
	<span class="error">*<?php echo $resErr;?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Date of birth</th>
	<td><input type="date" min="1980-01-01" max="2015-05-30" name="dob">
	<span class="error">*<?php echo $dobErr;?></span>
	</td>
	<br/>
</tr>


<tr>
	<th>Car model</th>
	<td><input type="text" name="car_model" value="<?php echo (isset($_POST['car_model']) && !empty($_POST['car_model']) &&$car_modelErr=="") ? $_POST['car_model'] : "" ?>">
	<span class="error">*<?php echo $car_modelErr?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Monthly salary</th>
	<td><input type="text" name="salary" value="<?php echo (isset($_POST['salary']) && !empty($_POST['salary']) &&$salaryErr=="") ? $_POST['salary'] : "" ?>">
	<span class="error">*<?php echo $salaryErr?></span>
	</td>
	<br/>
</tr>

<tr>
	<th>Amount</th>
	<td><input type="text" name="amt" value="<?php echo (isset($_POST['amt']) && !empty($_POST['amt']) &&$amtErr=="") ? $_POST['amt'] : "" ?>">
	<span class="error">*<?php echo $amtErr?></span>
	</td>
	<br/>
</tr>


<tr>
	<th>Tenure period(in years)</th>
	<td><select name="tenure">
	<option value="" disable selected hidden>Select</option>
	<option value="1">1</option>

	<option value="2">2</option>

	<option value="3">3</option>

	<option value="4">4</option>

	<option value="5">5</option>
	</select>
	<span class="error">*<?php echo $tenureErr;?></span>
	</td>
	<br/>
</tr>


</table>


<input type="submit" value="submit" id="submit" >
<input type="reset" value="reset">
<br/>


</form>
	
<?php

$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
if($cityErr==""&&$resErr==""&&$dobErr==""&&$salaryErr==""&&$amtErr==""&&$tenureErr==""&&$car_modelErr==""&&$_POST["city"]!=""&&$_POST["salary"]!=""&&$_POST["amt"]!=""&&$_POST["car_model"]!=""&&!empty($_POST["res"])&&!empty($_POST["dob"])&&!empty($_POST["tenure"]))
	{
			$day=date("Y/m/d");
			$interest=$_POST["amt"]*0.12*$_POST["tenure"];
			$tot=$interest+$_POST["amt"];
			$amt_per_month=$tot/($_POST["tenure"]*12);;
			echo "p.m".$amt_per_month;
			$emi=$amt_per_month;
			if($_POST["salary"]>=2*$amt_per_month)
			{
				$lid=mysqli_insert_id($con);
				echo "lid:$lid";
				$query="insert into loan values('$lid','$_SESSION[acc_no]','Car','$day','$day','$_POST[tenure]','$_POST[amt]',$emi)";
				$result=mysqli_query($con,$query);
				echo "res:".$result;
			}
			else
			{
				echo "<br>Sorry!You are not eligible for the loan!";
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
	}
?>
</body>
</html>