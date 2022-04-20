<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Customer_Name'])){
        $Customer_Name = $_GET['Customer_Name'];   
    }else{
        $Customer_Name = '';
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>CUSTOMER NAME</b></td>
		<td><b>REGION</b></td>
		    <td><b>DISTRICT</b></td> 
                        <td><b>PHONE NUMBER</b></td>
			    <td><b>EMAIL ADDRESS</b></td></tr>';
    
    
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_patient_registration WHERE Patient_Name like '%$Customer_Name%' AND registration_type='customer' order by Patient_Name"); 
		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<a href='#' ><tr><td>".$temp;
        echo "<td><a href='editcustomer.php?Registration_ID=".$row['Registration_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Patient_Name']."</td>";
        echo "<td><a href='editcustomer.php?Registration_ID=".$row['Registration_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Region']."</td>";
        echo "<td><a href='editcustomer.php?Registration_ID=".$row['Registration_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['District']."</td>";
        echo "<td><a href='editcustomer.php?Registration_ID=".$row['Registration_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Phone_Number']."</td>";
        echo "<td><a href='editcustomer.php?Registration_ID=".$row['Registration_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Email_Address']."</td>";
	$temp++;
	echo "</tr></a>";
    }
?></table></center>

