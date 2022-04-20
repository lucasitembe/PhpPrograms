

<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
	
	 if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }else{
        $Registration_ID = '';
    }

//table for technical instruction
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">	<td width = 5%><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td><b>PATIENT NO</b></td>
				<td><b>SPONSOR</b></td>
                 <td><b>GENDER</b></td>
                            </tr>';
    $select_Food = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender, Date_Of_Birth, pp.Patient_Payment_ID,
	Patient_Payment_Item_List_ID ,Patient_Direction
FROM tbl_Patient_Registration pr, tbl_sponsor sp, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
WHERE pr.Patient_Name like '%$Patient_Name%' AND
pr.Registration_ID = pp.Registration_ID
AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND  pr.sponsor_id = sp.sponsor_id
 and  ppl.Check_In_Type='Dialysis' AND Process_Status='saved'") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_Food)){
        echo "<tr><td>".$temp."</td><td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
		echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		
        echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        
        echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    $temp++;
    }   echo "</tr>";
?></table></center>