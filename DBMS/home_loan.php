<!doctype html>
<html>
<head><title>Home loan</title></head>
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
$city=$prop_details=$dob=$builder=$salary=$amt=$tenure=$property="";
$cityErr=$prop_detailsErr=$dobErr=$builderErr=$salaryErr=$amtErr=$tenureErr=$propertyErr="";

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
	
	if(empty($_POST["property"]))
	{
		$propertyErr="City is required";
	}
	else if(!(preg_match('/^[a-zA-Z]+$/',$_POST["property"])))
		$propertyErr="City should contain only alphabets!";
	else
	{
		$propertyErr="";
		$property=test($_POST["property"]);
	}
	
	if(empty($_POST["prop_details"]))
		$prop_detailsErr="Property details is required!";
	
	else 
	{
		$prop_detailsErr="";
		$prop_details=test($_POST["prop_details"]);
	}
	if(empty($_POST["dob"]))
		$dobErr="DOB is required!";
	else 
	{
		$dobErr="";
		$dob=test($_POST["dob"]);
	}
	
		$builder=test($_POST["builder"]);
	
	
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
	
	else if($_POST["amt"]<500000)
		$amtErr="Minimum loan amount is 5 lakhs!";
	else if($_POST["amt"]>100000000)
		$amtErr="Minimum loan amount is 10 crore!!";
	
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
	<th>City in which property is based</th>
	<td><input type="text" name="property" value="<?php echo (isset($_POST['property']) && !empty($_POST['property']) &&$propertyErr=="") ? $_POST['property'] : "" ?>">
	<span class="error">*<?php echo $propertyErr?></span>
	</td>
	
</tr>




<tr>
	<th>Property details</th>
	<td><select name="prop_details">
	<option value="" disable selected hidden>Select</option>
	<option value="Buy already built home/flat">Buy already built home/flat/option>
	<option value="Buy home/flat built by builder">Buy home/flat built by builder</option>
	<span class="error">*<?php echo $prop_detailsErr;?></span>
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
	<th>Name of builder and project</th>
	<td><input type="text" name="builder" value="<?php echo (isset($_POST['builder']) && !empty($_POST['builder']) &&$builderErr=="") ? $_POST['builder'] : "" ?>">
	
	</td>
	<br/>
</tr>

<tr>
	<th>Which city do you live in currently?</th>
	<td><input type="text" name="city" value="<?php echo (isset($_POST['city']) && !empty($_POST['city']) &&$cityErr=="") ? $_POST['city'] : "" ?>">
	<span class="error">*<?php echo $cityErr?></span>
	</td>
	
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
	<option value="5">5</option>

	<option value="6">6</option>

	<option value="7">7</option>

	<option value="8">8</option>

	<option value="9">9</option>
	<option value="10">10</option>
	<option value="11">11</option>

	<option value="12">12</option>

	<option value="13">13</option>

	<option value="14">14</option>
	<option value="15">15</option>
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

</form>
	
<?php

$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
if($cityErr==""&&$prop_detailsErr==""&&$dobErr==""&&$salaryErr==""&&$amtErr==""&&$tenureErr==""&&$propertyErr==""&&$_POST["city"]!=""&&$_POST["salary"]!=""&&$_POST["amt"]!=""&&$propertyErr==""&&!empty($_POST["prop_details"])&&!empty($_POST["dob"])&&!empty($_POST["tenure"]))
	{
			$day=date("Y/m/d");
			$interest=$_POST["amt"]*0.0925*$_POST["tenure"];
			$tot=$interest+$_POST["amt"];
			$amt_per_month=$tot/($_POST["tenure"]*12);
			echo "p.m".$amt_per_month;
			$emi=$amt_per_month;
			if($_POST["salary"]>=2*$amt_per_month)
			{
				$lid=mysqli_insert_id($con);
				$query="insert into loan values('$lid','$_SESSION[acc_no]','Home','$day','$day','$_POST[tenure]','$_POST[amt]','$emi')";
				$result=mysqli_query($con,$query);
			}
			else
			{
				echo "<br>Sorry!You are not eligible for loan!";
			}
			$query="select * from account";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				
				if($value['Account_number']==$num)
				{
					$bal=$value['Balance'];
					//echo "<br>".$bal;
					break;
				}
			}	
	}
?>
</body>
</html>