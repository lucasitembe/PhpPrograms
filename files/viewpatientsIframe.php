<?php
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    echo '<center><table width =100% border=1>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration where
                First_Name like '%$Patient_Name%'
                    or Second_name like '%$Patient_Name%'
                        or Last_Name like '%$Patient_Name%'") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&VisitorDetails=VisitorDetailsThisForm' target='_parent' style='text-decoration: none;'>".$row['First_Name']." ".$row['Second_Name']." ".$row['Last_Name']."</a></td></tr>"; 
    }
?></table>

