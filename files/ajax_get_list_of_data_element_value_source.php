<?php
include("./includes/connection.php");
if(isset($_POST['data_element_value_source'])){

    $data_element_value_source=$_POST['data_element_value_source'];
    $dhis2_dataelement_id=$_POST['dhis2_dataelement_id'];
    $dataset_id=$_POST['dataset_id'];
    if($data_element_value_source=="Diseases"){
       $sql_select_disease_result=mysqli_query($conn,"SELECT disease_ID,disease_name FROM tbl_disease WHERE disease_version='icd_10' AND disease_ID IN (SELECT data_element_value_source_id FROM tbl_dhis2_data_value_merge WHERE dhis2_dataelement_id='$dhis2_dataelement_id' AND dataset_id='$dataset_id') ORDER BY disease_name ASC LIMIT 50") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_disease_result)>0){
           $count=1;
           while($disease_rows=mysqli_fetch_assoc($sql_select_disease_result)){
              $disease_ID=$disease_rows['disease_ID'];
              $disease_name=$disease_rows['disease_name'];
              echo "<tr>
                        <td>$count.</td>
                        <td>$disease_name</td>
                        <td>
                            <input type='button' value='X' onclick='remove_all_items_merged_to_this_value_source($disease_ID,\"$dhis2_dataelement_id\",\"$dataset_id\")'/>
                        </td>
                   </tr>";
              $count++;
           }
       }else{
           //echo "imegoma disease";
       }       
    }
    else if($data_element_value_source=="Pharmaceutical"){
        $sql_select_pharmaceutical_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Item_ID IN (SELECT data_element_value_source_id FROM tbl_dhis2_data_value_merge WHERE dhis2_dataelement_id='$dhis2_dataelement_id' AND dataset_id='$dataset_id') AND (Consultation_Type='Pharmacy' or Item_Type='Pharmacy') AND Status='Available' ORDER BY Product_Name ASC LIMIT 50") or die(mysqli_error($conn));
    
         if(mysqli_num_rows($sql_select_pharmaceutical_items_result)>0){
           $count=1;
           while($items_rows=mysqli_fetch_assoc($sql_select_pharmaceutical_items_result)){
              $Item_ID=$items_rows['Item_ID'];
              $Product_Name=$items_rows['Product_Name'];
              echo "<tr>
                        <td>$count.</td>
                        <td>$Product_Name</td>
                        <td>
                            <input type='button' value='X' onclick='remove_all_items_merged_to_this_value_source($Item_ID,\"$dhis2_dataelement_id\",\"$dataset_id\")'/>
                        </td>
                   </tr>";
              $count++;
           }
       }
    }
    else if($data_element_value_source=="Date Range"){
        
    }
    else if($data_element_value_source=="Other Item"){
        
    }
    else{
        $sql_select_pharmaceutical_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Item_ID IN (SELECT data_element_value_source_id FROM tbl_dhis2_data_value_merge WHERE dhis2_dataelement_id='$dhis2_dataelement_id' AND dataset_id='$dataset_id') AND Consultation_Type='$data_element_value_source' AND Status='Available' ORDER BY Product_Name ASC LIMIT 50") or die(mysqli_error($conn));
    
         if(mysqli_num_rows($sql_select_pharmaceutical_items_result)>0){
           $count=1;
           while($items_rows=mysqli_fetch_assoc($sql_select_pharmaceutical_items_result)){
              $Item_ID=$items_rows['Item_ID'];
              $Product_Name=$items_rows['Product_Name'];
              echo "<tr>
                        <td>$count.</td>
                        <td>$Product_Name</td>
                        <td>
                            <input type='button' value='X' onclick='remove_all_items_merged_to_this_value_source($Item_ID,\"$dhis2_dataelement_id\",\"$dataset_id\")'/>
                        </td>
                   </tr>";
              $count++;
           }
       }
       
    }
}else{
    //echo "nje";
}

