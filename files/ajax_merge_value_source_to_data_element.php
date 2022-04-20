<?php
include("./includes/connection.php");
if(isset($_POST['selected_dhis2_dataelement_id'])&&isset($_POST['Item_ID_disease_ID'])){
   $selected_dhis2_dataelement_id=$_POST['selected_dhis2_dataelement_id']; 
   $Item_ID_disease_ID=$_POST['Item_ID_disease_ID']; 
   $dataset_id=$_POST['dataset_id']; 
   $data_element_value_source=$_POST['data_element_value_source']; 
   
   $sql_select_data_element_id_result=mysqli_query($conn,"SELECT dhis2_dataelement_id,categoryOptionCombo FROM tbl_dhis2_dataelements WHERE dataset_id='$dataset_id' AND dhis2_dataelement_id='$selected_dhis2_dataelement_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_data_element_id_result)>0){
        $count=1;
        while($data_elemetn_id_rows=mysqli_fetch_assoc($sql_select_data_element_id_result)){
            $dhis2_dataelement_id=$data_elemetn_id_rows['dhis2_dataelement_id'];
            $categoryOptionCombo=$data_elemetn_id_rows['categoryOptionCombo'];
            //check if item arleady added to a given value source
            $sql_check_if_exist_result=mysqli_query($conn,"SELECT dhis2_data_value_merge_id FROM tbl_dhis2_data_value_merge WHERE dataset_id='$dataset_id' AND dhis2_dataelement_id='$dhis2_dataelement_id' AND categoryOptionCombo='$categoryOptionCombo' AND data_element_value_source_id='$Item_ID_disease_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_if_exist_result)<=0){
                //add data element to value source
                $sql_add_data_element_to_value_source_result=mysqli_query($conn,"INSERT INTO tbl_dhis2_data_value_merge(dataset_id,dhis2_dataelement_id,categoryOptionCombo,data_element_value_source_id,data_element_value_source) VALUES('$dataset_id','$dhis2_dataelement_id','$categoryOptionCombo','$Item_ID_disease_ID','$data_element_value_source')") or die(mysqli_error($conn));
            
                if($sql_add_data_element_to_value_source_result){
                   // echo "success";
                }else{
                    echo "fail";
                }
            }
        }
    }
   
}
?>
