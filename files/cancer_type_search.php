<?php
include("./includes/connection.php");

    if(isset($_POST['search_value'])){
    $search_value  = $_POST['search_value'];
    $Registration_ID  = $_POST['Registration_ID'];
  
         $disease_ID=0;
         $disease_name="";
         $num_count=0;
         $cancer_ID=0;
         $select_diagnosis=mysqli_query($conn,"SELECT * FROM tbl_cancer_type WHERE Cancer_Name LIKE '%$search_value%' LIMIT 10");
         if(mysqli_num_rows($select_diagnosis)){
           while($row=mysqli_fetch_assoc($select_diagnosis)){
                $cancer_ID=$row['cancer_type_id'];
                $disease_name=$row['Cancer_Name'];
                $num_count++;
                
                
                
                echo "<tr class='rows_list'>
                <td width='2%;'> $num_count</td>
                <td style='text-align:left;'> $disease_name</td>
                <td><span ><button class='btn btn-info btn-block '  type='button' onclick='cancer_type_details(\"$cancer_ID\",\"$disease_name\",\" $Registration_ID\")' >ASSIGN PROTOCAL<i id='attach_cat_icon' style='color:#328CAF' class='fa fa-send fa-2x'></i><span></button></td>
                 </tr>";
 
                      
           }
          }else{
            echo "<tr>
                        <td colspan='3' style='color:red; text-align:center;'>No any Protocal assigned  to this patient yet!! </td>
                    </tr>";
        }
    }

 if(isset($_POST['btn_update_status'])){
  session_start();
   $Protocal_status = mysqli_real_escape_string($conn, $_POST['Protocal_status']);
   $Remarks = mysqli_real_escape_string($conn, $_POST['Reason']);
   $Patient_protocal_details_ID = $_POST['Patient_protocal_details_ID'];
   $Employee_ID =$_POST['Employee_ID'];

   $update_status = mysqli_query($conn, "UPDATE tbl_cancer_patient_details SET Protocal_status='$Protocal_status', Remarks='$Remarks', Status_date=NOW(), Employee_changed_status='$Employee_ID' WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID'") or die(mysqli_error($conn));
   if(!$update_status){
     echo "Failed to update staus";
   }else{
     echo "Protocal status updated successful";
   }
 }

