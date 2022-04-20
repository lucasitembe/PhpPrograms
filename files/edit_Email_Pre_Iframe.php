<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Recepient_Name'])){
        $Recepient_Name = $_GET['Recepient_Name'];   
    }else{
        $Recepient_Name = '';
    }
	
	 $filter='';
	 
	 if($Recepient_Name != ''){
	     $filter=" WHERE Recepient_Name like '%$Recepient_Name%' ";
	 }
	
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>RECEPIENT NAME</b></td>
		<td><b>RECEPIENT EMAIL</b></td>
		    <td><b>DATE CREATED</b></td> 
                        <td><b>ADDED BY</b></td>';
			   
    
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_email_recepients  er JOIN tbl_employee e ON  er.Created_By=e.Employee_ID $filter order by Recepient_Name") or die(mysqli_error($conn)); 
		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<a href='#' ><tr><td>".$temp."<td><a href='editrecepient.php?Recepient_ID=".$row['Recepient_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['Recepient_Name']."</td>";
        echo "<td><a href='editrecepient.php?Recepient_ID=".$row['Recepient_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['Recepient_Email']."</td>";
        echo "<td><a href='editrecepient.php?Recepient_ID=".$row['Recepient_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['Date_Created']."</td>";
        echo "<td><a href='editrecepient.php?Recepient_ID=".$row['Recepient_ID']."&EditRecepient=EditRecepientThisForm' target='_parent' style='text-decoration: none;' title='Click This Recepient To Edit'>".$row['Employee_Name']."</td>";
        
	$temp++;
	echo "</tr></a>";
    }
?></table></center>

