<?php 
include("./includes/connection.php");
if(isset($_POST['dhis2_dataelement_id'])&&isset($_POST['categoryOptionCombo'])&&isset($_POST['displayname'])){
    $dhis2_dataelement_id=$_POST['dhis2_dataelement_id'];
    $categoryOptionCombo=$_POST['categoryOptionCombo'];
    $displayname=$_POST['displayname'];
    $dataset_id=$_POST['dataset_id'];
    
    
     
    ?>
<table class="table">
    <caption style="font-size:20px"><b><?= $displayname ?></b></caption>
   
    <?php 
        $value_data_element="";
            $sql_select_merged_value_result=mysqli_query($conn,"SELECT data_element_value_source_id,data_element_value_source FROM tbl_dhis2_data_value_merge WHERE dhis2_dataelement_id='$dhis2_dataelement_id' AND categoryOptionCombo='$categoryOptionCombo' AND dataset_id='$dataset_id'") or die();
            if(mysqli_num_rows($sql_select_merged_value_result)>0){
                while($data_element_value_source_id_row=mysqli_fetch_assoc($sql_select_merged_value_result)){
                    $data_element_value_source_id=$data_element_value_source_id_row['data_element_value_source_id'];
                    $data_element_value_source=$data_element_value_source_id_row['data_element_value_source'];
                    if($data_element_value_source=="Diseases"){
                        echo " <tr>
                                    <td><b>S/No</b></td>
                                    <td><b>Patient Name</b></td>
                                    <td><b>Consultation Date</b></td>
                                    <td><b>Final Diagnosis</b></td>
                                </tr><tr><td colspan='4'><hr/></td></tr>";
                       $sql_select_value_result=mysqli_query($conn,"SELECT disease_ID,consultation_ID,Disease_Consultation_Date_And_Time FROM tbl_disease_consultation WHERE disease_ID='$data_element_value_source_id' AND diagnosis_type='diagnosis'") or die(mysqli_error($conn)); 
                       
                       $value_data_element=mysqli_num_rows($sql_select_value_result);
                       if($value_data_element>0){
                           $count_row=1;
                            while($patient_rows=mysqli_fetch_assoc($sql_select_value_result)){
                                $disease_ID=$patient_rows['disease_ID'];
                                $consultation_ID=$patient_rows['consultation_ID'];
                                $Disease_Consultation_Date_And_Time=$patient_rows['Disease_Consultation_Date_And_Time'];
                                
                                //get patient_detail
                                $sql_select_patient_name_result=mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID IN(SELECT Registration_ID FROM tbl_consultation WHERE consultation_ID='$consultation_ID')") or die(mysqli_error($conn));
                                $Patient_Name=mysqli_fetch_assoc($sql_select_patient_name_result)['Patient_Name'];
                                //get disease_detail
                                $sql_select_disease_name_result=mysqli_query($conn,"SELECT disease_name,disease_code FROM tbl_disease WHERE disease_ID='$disease_ID'") or die(mysqli_error($conn));
                                $disease_rows=mysqli_fetch_assoc($sql_select_disease_name_result);
                                $disease_name=$disease_rows['disease_name'];
                                $disease_code=$disease_rows['disease_code'];

                                echo "<tr>
                                            <td>$count_row.</td>
                                            <td>$Patient_Name</td>
                                            <td>$Disease_Consultation_Date_And_Time</td>
                                            <td><b>$disease_code~~</b>$disease_name</td>
                                     </tr>";
                                $count_row++;
                            }
                       }
                    }
                }
            }
    ?>
</table>
    <?php
}