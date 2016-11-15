<!doctype html>
<html>
<head><title>Dashboard</title>

<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<div class="hor">
<ul class="nav nav-pills">
<li class="active"><a href="#">Home</a></li>
  <li><a href="fund.html" >Fund transfer</a></li>
  <li><a href="fixed_deposit.php" >Fixed deposit</a></li>
    <li><a href="recurring_deposit.php" >Recurring deposit</a></li>
	<li><a href="car_loan.php" >Car loan</a></li>
	<li><a href="home_loan.php" >home loan</a></li>
	<li><a href="transaction.php" >Transactions</a></li>\
	<li><a href="bill.html">Bill payments</a></li>
	<li><a href="create_debit_card.php">Create debit card</a></li>
  </ul>
<?php
error_reporting(0);
session_start();
$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
date_default_timezone_set("Asia/Kolkata");
$day=date("Y-m-d");
$list=array();


$query="select sum(Amount) as Total,Type  from deposit where Account_number=$_SESSION[acc_no] group by Type  having COUNT(Type)>0";
		$result=mysqli_query($con,$query);
		while($value=mysqli_fetch_array($result))
			{
				echo "<br>Total:$value[Total],Type:$value[Type]";
			}





//Find balance of current customer

$query="select * from loan where Account_number=$_SESSION[acc_no]";

$result=mysqli_query($con,$query);

$tot=0;
if (mysqli_num_rows($result) > 0) {
    while($value = mysqli_fetch_assoc($result)) 
	{
		//find if tenure period is completed or not
		//find no of years since start date
		$start_date = new DateTime("$value[Start_date]");
		$current_date = new DateTime("$day");
		$yr=$value['Tenure'];
		$date=date_create("$value[Start_date]");
		date_add($date,date_interval_create_from_date_string("$yr years"));
		//echo "<br>after $yr yrs:".date_format($date,"Y-m-d");
		$date_after_tenure_years=date_format($date,"Y-m-d");
		
		
		if(strtotime("$day")>strtotime("$date_after_tenure_years"))
		{
			//remove record...since he finished paying
			echo "<br>No need to pay!";
		}
		else 
		{
			//echo "<br>Lid:".$value['Lid'];
			$last_paid_date = new DateTime("$value[Payment_date]");
			$lid=$value['Lid'];
			$emi=$value['EMI'];
			$last=$value['Payment_date'];
			$diff = $last_paid_date->diff($current_date);
			if ($diff->m==0)
			$diff2= $diff->y*12;
			else if ($diff->y==0) $diff2= $diff->m;
			else $diff2=0;
		//	echo "<br>difference " . $diff->y . " years, " . $diff->m." months, ".$diff->d." days ";
			
			//Find balance of current customer
			$query="select Balance from account where Account_number=$_SESSION[acc_no]";
			$res=mysqli_query($con,$query);
			if (mysqli_num_rows($res) > 0) {
				// output data of each row
				while($value = mysqli_fetch_assoc($res)) 
					$bal=$value['Balance'];
			}
				$updated_bal=$bal-$emi;
				$month=0;
				if($diff->y>0)
					$month=($diff->y)*12;
				$month+=$diff->m;
				
					for($i=1;$i<=$month;$i++)
					{
						if($updated_bal<0)
						{
							echo "Insufficient funds!You cannot pay your loan!!";
							break;
						}
						else if($updated_bal<1000)
						{
							echo "Minimum balance should be Rs 1000!!";
							break;
						}
							
						else
						{
							$day = date('Y-m-d', strtotime('+1 month', strtotime($last)));
							$query="update account set Balance='$updated_bal',Modified_date='$day' where Account_number=$_SESSION[acc_no]";
							$res=mysqli_query($con,$query);
							echo "<br>upd bal:$updated_bal";
							$tid=mysqli_insert_id($con);
							$query="insert into transaction values('$tid','$_SESSION[cust_id]','$emi','$day','INR $emi debited from account for loan payment')";
							$res=mysqli_query($con,$query);
							$query="update loan set Payment_date='$day' where Lid='$lid' and Account_number=$_SESSION[acc_no]";
							$res=mysqli_query($con,$query);
							
						}
						$updated_bal-=$emi;
						$last=$day;
					}
					
				
			
			
		
	
    }
 }
		
		
}


$date=date('Y-m-d');
$dates2=date('Y-m-d');
//echo $date;
$query="select * from account";
$result=mysqli_query($con,$query);
while($value=mysqli_fetch_assoc($result)){
	if($value['Account_number']==$_SESSION['acc_no'])
	{
		$am=$value['Balance'];
		break;
	}
}
$flag=0;
$flag1=0;
 if($am<1000)
 {
	$flag1=1;
	echo "<br>Minimum balance should be Rs 1000";
}
else{$flag=0;
$query="select * from login_details";
$result=mysqli_query($con,$query);
while($value=mysqli_fetch_assoc($result)){
	if($value['Account_number']==$_SESSION['acc_no'])
	{
		$flag=1;
		break;
	}
}
if($flag==0){
	$query="INSERT INTO login_details(login_date,Account_number) VALUES ('$date',$_SESSION[acc_no]);";
$result=mysqli_query($con,$query);
$query="select * from deposit";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				if($value['Account_number']==$_SESSION['acc_no']&&strcmp($value['Type'],'RD')==0)
			{    
				$issue=$value['date_of_issue'];
				$expiryd=$value['date_of_expiry'];
				$curr_date=date("Y-m-d");
				$ams=$value['Amount'];
			$date1 = new DateTime($curr_date);
			$date2 = new DateTime($dates2);
			$expiryd=strtotime($expiryd);
			$curr_date=strtotime($curr_date);
			$issue=date('Y-m-d', strtotime('+1 months'));
			$issue=strtotime($issue);
			
			
			$diff = $date1->diff($date2);
			if ($diff->m==0)
			$diff2= $diff->y*12;
			else if ($diff->y==0) $diff2= $diff->m;
			else $diff2=0;
			$am2=$am;
			if($curr_date<$expiryd){
				
				while($diff2>0){
					
					$am2=$am-$ams;
					if($am2>$am){
						echo "insufficient amount";
					}else{
							$am=$am2;
					$query2="update account set Balance=$am where Account_number=$_SESSION[acc_no]";
			$result2=mysqli_query($con,$query2);
			$tid=mysqli_insert_id($con);
			$query3="insert into transaction values('$tid','$_SESSION[cust_id]','$value[Amount]','$date','recurring deposit of INR $value[Amount]')";
			$result3=mysqli_query($con,$query3);
			$diff2=$diff2-1;
		
				}}
			}
				
			}
			$query4="update login_details set login_date='$dates2'";
			$result4=mysqli_query($con,$query4);}

}
	else{
		$query="select * from login_details";
		$result=mysqli_query($con,$query);
		while($value=mysqli_fetch_assoc($result)){
		if($value['Account_number']==$_SESSION['acc_no'])
		{
			$d=$value['login_date'];
			break;
		}
	}
			$query="select * from deposit";
			$result=mysqli_query($con,$query);
			
			while($value=mysqli_fetch_array($result))
			{
				if($value['Account_number']==$_SESSION['acc_no']&&strcmp($value['Type'],'RD')==0)
			{    
				$issue=$value['date_of_issue'];
				$expiryd=$value['date_of_expiry'];
				$curr_date=date("Y-m-d");
				$ams=$value['Amount'];
			$date1 = new DateTime($curr_date);
			$date2 = new DateTime($d);
			$expiryd=strtotime($expiryd);
			$curr_date=strtotime($curr_date);
			$issue=date('Y-m-d', strtotime('+1 months'));
			$issue=strtotime($issue);
			
			
			$diff = $date1->diff($date2);
			if ($diff->m==0)
			$diff2= $diff->y*12;
			else if ($diff->y==0) $diff2= $diff->m;
			else $diff2=0;
			$am2=$am;
			//echo "difference " . $diff->y . " years, " . $diff->m." months, ".$diff->d." days ";
			if($curr_date<$expiryd){
				
				while($diff2>0){
					$am2=$am-$ams;
					if($am2>$am){
						echo "insufficient amount";
					}else{
							$am=$am2;
					$query2="update account set Balance=$am where Account_number=$_SESSION[acc_no]";
			$result2=mysqli_query($con,$query2);
			$tid=mysqli_insert_id($con);
			$query3="insert into transaction values('$tid','$_SESSION[cust_id]','$value[Amount]','$date','recurring deposit of INR $value[Amount]')";
			$result3=mysqli_query($con,$query3);
			$diff2=$diff2-1;
				}}
			}
				$query4="update login_details set login_date='$dates2'";
				$result4=mysqli_query($con,$query4);	
				}
			}
}
}
  ?>
  
</body>
</html>