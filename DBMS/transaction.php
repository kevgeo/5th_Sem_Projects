<!doctype html>
<html>
<head><title>Dashboard</title>
<body>
<table caption="List of transactions" border="1">
<tr>
	<th>Id</th>
	<th>Transaction date</th>
	<th>Amount</th>
	<th>Transaction remarks</th>
</tr>
<?php

session_start();
$db="net_banking";
$con=mysqli_connect("localhost","root","","net_banking") or die("Unable to connect to mysql");
mysqli_select_db($con,$db);
$query="select * from transaction inner join customer where customer.Customer_id=$_SESSION[cust_id] and customer.Customer_id=transaction.Customer_id ORDER BY Date asc";
$result=mysqli_query($con,$query);
$count=1;
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) 
	{
	   $date=$row['Date'];
	   $amt=$row['Amount'];
	   $remark=$row['Remark'];

	   echo 
		   '<tr>
			   <td>'.$count.'</td>
			   <td>'.$date.'</td>
			   <td>'.$amt.'</td>
			   <td>'.$remark.'</td>
		   </tr>';
		  $count++;
	}
	
}


?>

</table>
</body>
</html>