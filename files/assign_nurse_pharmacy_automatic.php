<?php
include("./includes/connection.php");
$COUNT_UPDETED=0;
//select all nurses
$sql_select_all_nurses_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_employee WHERE Employee_Type='Nurse' OR Employee_Type='Ass Nursing Officer' OR Employee_Job_Code='Nurse' OR Employee_Title='RN'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_nurses_result)>0){
    while($rows=mysqli_fetch_assoc($sql_select_all_nurses_result)){
       $Employee_ID=$rows['Employee_ID'];
       //loop for all department
       $sql_update_privileges_result=mysqli_query($conn,"UPDATE tbl_privileges SET Pharmacy='yes' WHERE Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
       if($sql_update_privileges_result){
         $COUNT_UPDETED++;  
       }
//       for($i=190;$i<=207;$i++){
//           //check if employee already assigned department
//           $sql_check_if_department_exist_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID' AND Sub_Department_ID='$i'") or die(mysqli_error($conn));
//           if(mysqli_num_rows($sql_check_if_department_exist_result)<=0){
//               $sql_assigned_sub_d_to_nurse_result=mysqli_query($conn,"INSERT INTO tbl_employee_sub_department(Employee_ID,Sub_Department_ID) VALUES('$Employee_ID','$i')") or die(mysqli_error($conn));
//               if($sql_assigned_sub_d_to_nurse_result){
//                   $COUNT_UPDETED++;
//               }
//           }
//       }
    }
}
echo $COUNT_UPDETED;