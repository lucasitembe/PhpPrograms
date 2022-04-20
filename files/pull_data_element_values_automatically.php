<?php 
include("./includes/connection.php");
if(isset($_POST['period_year'])&&isset($_POST['period_type'])&&isset($_POST['complete_date'])&&isset($_POST['dataset_id'])&&isset($_POST['orgUnit'])){
  $period_year=$_POST['period_year'];
  $period_type=$_POST['period_type'];
  $complete_date=$_POST['complete_date'];
  $dataset_id=$_POST['dataset_id'];
  $orgUnit=$_POST['orgUnit'];
    $sql_select_data_elements_result=mysqli_query($conn,"SELECT dataelement_value,dhis2_dataelement_auto_id,dhis2_dataelement_id,categoryOptionCombo,displayname FROM tbl_dhis2_dataelements WHERE dataset_id='$dataset_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_data_elements_result)>0){
        $count++;
        while($data_element_rows=mysqli_fetch_assoc($sql_select_data_elements_result)){
            $dhis2_dataelement_id=$data_element_rows['dhis2_dataelement_id'];
            $categoryOptionCombo=$data_element_rows['categoryOptionCombo'];
            $displayname=$data_element_rows['displayname'];
            $dhis2_dataelement_auto_id=$data_element_rows['dhis2_dataelement_auto_id'];
            $dataelement_value=$data_element_rows['dataelement_value'];
            
            //get merged value
            $value_data_element="";
            $sql_select_merged_value_result=mysqli_query($conn,"SELECT data_element_value_source_id,data_element_value_source FROM tbl_dhis2_data_value_merge WHERE dhis2_dataelement_id='$dhis2_dataelement_id' AND categoryOptionCombo='$categoryOptionCombo' AND dataset_id='$dataset_id'") or die();
            if(mysqli_num_rows($sql_select_merged_value_result)>0){
                while($data_element_value_source_id_row=mysqli_fetch_assoc($sql_select_merged_value_result)){
                    $data_element_value_source_id=$data_element_value_source_id_row['data_element_value_source_id'];
                    $data_element_value_source=$data_element_value_source_id_row['data_element_value_source'];
                    if($data_element_value_source=="Diseases"){
                        
                       $sql_select_value_result=mysqli_query($conn,"SELECT disease_ID FROM tbl_disease_consultation WHERE disease_ID='$data_element_value_source_id' AND diagnosis_type='diagnosis'") or die(mysqli_error($conn)); 
                       
                       $value_data_element=mysqli_num_rows($sql_select_value_result);
                    }
                }
            }
            
            //save data element value
            $sql_update_data_element_value_result=mysqli_query($conn,"UPDATE tbl_dhis2_dataelements SET dataelement_value='$value_data_element' WHERE dhis2_dataelement_auto_id='$dhis2_dataelement_auto_id'") or die(mysqli_error($conn));

            echo "<tr>
                        <td>$count.</td>
                        <td>$dhis2_dataelement_id</td>
                        <td>$displayname</td>
                        <td><input type='text' class='form-control' value='$value_data_element' id='data_element_value$dhis2_dataelement_auto_id' onkeyup='save_data_element_value($dhis2_dataelement_auto_id)' placeholder='DateElement Value' value='$dataelement_value'/>
                            <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' id='ajax_loder$dhis2_dataelement_auto_id'width='20' height='20'>
                        </td>
                        <td style='text-align:right'>
                            <input type='button' class='art-button-green' onclick='open_confirm_dialog(\"$dhis2_dataelement_id\",\"$categoryOptionCombo\",\"$displayname\",\"$dataset_id\")' value='CONFIRM'>
                        </td>
                 </tr>";
            $count++;
        }
    }
}else{
    echo "imegoma";
}
?>