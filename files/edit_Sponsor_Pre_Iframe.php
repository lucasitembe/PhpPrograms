<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];   
    }else{
        $Guarantor_Name = '';
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>SPONSOR NAME</b></td>
		<td><b>REGION</b></td>
		    <td><b>DISTRICT</b></td> 
                        <td><b>PHONE NUMBER</b></td>
			    <td><b>MEMBER NUMBER STATUS</b></td></tr>';
    
    
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_sponsor WHERE Guarantor_Name like '%$Guarantor_Name%' order by Guarantor_Name"); 
		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<a href='#' ><tr><td>".$temp."<td><a href='editsponsor.php?Sponsor_ID=".$row['Sponsor_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Guarantor_Name']."</td>";
        echo "<td><a href='editsponsor.php?Sponsor_ID=".$row['Sponsor_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Region']."</td>";
        echo "<td><a href='editsponsor.php?Sponsor_ID=".$row['Sponsor_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['District']."</td>";
        echo "<td><a href='editsponsor.php?Sponsor_ID=".$row['Sponsor_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Phone_Number']."</td>";
        echo "<td><a href='editsponsor.php?Sponsor_ID=".$row['Sponsor_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Membership_Id_Number_Status']."</td>";
	$temp++;
	echo "</tr></a>";
    }
?></table></center>

