
<style>
    td span:hover{
       color:red;
    }
    
</style>
<?php
 include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])  && $_GET['Patient_Name'] !='Patient_Name'){
        $Patient_Name = $_GET['Patient_Name'];
    }else{
        $Patient_Name = '';
    } 
    $temp = 0;
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
    echo '<div style="overflow-y:scroll;overflow-x: hidden;height:320px;   ">'; 
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width="5%"><b>SN</b></td><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr ,tbl_sponsor s, tbl_admission ad where
		pr.registration_id = ad.registration_id and  Admission_Status = 'pending' and
                Discharge_Clearance_Status ='not cleared' and
		s.Sponsor_ID = pr.Sponsor_ID
		AND pr.Patient_Name LIKE '%$Patient_Name%'
		") or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Guarantor_Name=$row['Guarantor_Name'];
       $billtype='';
       if($Guarantor_Name=='CASH'){
           $billtype='Inpatient Cash';
       }else{
           $billtype='Inpatient Credit';
       }
         echo '<tr><td>'.++$temp.'</td><td style="text-align: left;"><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Patient_Name']."</span></td>";
        echo '<td><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Guarantor_Name'].'</span></td>';
        echo '<td><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Date_Of_Birth'].'</span></td>';
        echo '<td><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Gender'].'</span></td>';
        echo '<td><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Phone_Number'].'</span></td>';
        echo '<td><span onclick="setPatient('.$row['Registration_ID'].',\''.$row['Patient_Name'].'\',\''.$billtype.'\')" style="text-decoration: none;">'.$row['Member_Number'].'</span></td>';
    }   echo "</tr>";
?>
</table></center>
</div>