<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['Registration_ID'])&&isset($_POST['Patient_Name'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  
  $filter="AND ed.Edit_date BETWEEN '$start_date' AND '$end_date'";
  if(!empty($Patient_Name)){
    $filter.="AND pr.Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($Registration_ID)){
     $filter.="AND  ed.Registration_ID ='$Registration_ID'";
  }
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT ed.Registration_ID, pr.Patient_Name, emp.Employee_Name, ed.Edit_date, ed.Old_name, ed.Sponsor_ID, ed.Old_sponsor FROM tbl_patient_edit ed, tbl_patient_registration pr, tbl_employee emp WHERE emp.Employee_ID = ed.Employee_ID AND pr.Registration_ID = ed.Registration_ID $filter ORDER BY ed.Edit_date ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Edit_date=$patient_list_rows['Edit_date'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $old_Sponsor_ID=$patient_list_rows['Old_sponsor'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
         $Old_name = $patient_list_rows['Old_name'];

         $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
         $Previous_Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$old_Sponsor_ID'"))['Guarantor_Name'];
        
                
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Registration_ID</td>
                        <td>$Patient_Name</td>
                        <td>$Old_name</td>
                        <td>$Guarantor_Name</td>
                        <td>$Previous_Guarantor_Name</td>
                        <td>$Employee_Name</td>
                        <td>$Edit_date</td>
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
  }
}
?>