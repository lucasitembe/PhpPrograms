<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['Registration_ID'])&&isset($_POST['Patient_Name'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  
  $filter="AND ck.Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
  if(!empty($Patient_Name)){
    $filter.="AND Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($Registration_ID)){
     $filter.="AND  pr.Registration_ID ='$Registration_ID'";
  }
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ck,tbl_employee ep WHERE pr.Registration_ID=ck.Registration_ID AND ck.Employee_ID=ep.Employee_ID AND ck.AuthorizationNo<>'' $filter GROUP BY ck.Check_In_Date_And_Time ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['Phone_Number'];
         $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
         $Gender=$patient_list_rows['Gender'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $Payment_Date_And_Time=$patient_list_rows['Check_In_Date_And_Time'];
         $bill_payment_code = $patient_list_rows['AuthorizationNo'];
         $membernumber = $patient_list_rows['Member_Number'];
         $employeename = $patient_list_rows['Employee_Name'];
        
        $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
        while($date = mysqli_fetch_array($sql_date_time)){
            $Today = $date['Date_Time'];
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";

        //sql select payment sponsor
        $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
        echo "
                <tr class='rows_list' $change_color_style >
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Phone_Number</td>
                        <td>$age</td>
                        <td>$Gender</td>
                        <td>$Guarantor_Name</td>
                        <td>$membernumber</td>
                        <td>$bill_payment_code</td>
                        <td>$employeename</td>
                        <td>$Payment_Date_And_Time</td>\
                    </a>
                </tr>
                ";
        $count_sn++;
      }
  }
}




