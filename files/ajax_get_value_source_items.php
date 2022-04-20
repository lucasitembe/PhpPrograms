<?php
include("./includes/connection.php");
if(isset($_POST['data_element_value_source'])){
    if(isset($_POST['search_value'])){
        $search_value=$_POST['search_value'];
    }else{
        $search_value="";
    }
    $data_element_value_source=$_POST['data_element_value_source'];
    if($data_element_value_source=="Diseases"){
       $sql_select_disease_result=mysqli_query($conn,"SELECT disease_ID,disease_name,disease_code FROM tbl_disease WHERE disease_version='icd_10' AND (disease_name LIKE '%$search_value%' OR disease_code LIKE '%$search_value%') ORDER BY disease_name,disease_code ASC LIMIT 50") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_disease_result)>0){
           $count=1;
           while($disease_rows=mysqli_fetch_assoc($sql_select_disease_result)){
              $disease_ID=$disease_rows['disease_ID'];
              $disease_name=$disease_rows['disease_name'];
              $disease_code=$disease_rows['disease_code'];
              echo "<tr>
                        <td>
                            <input type='button' value='<<' onclick='merge_value_source_to_data_element($disease_ID)' />
                        </td>
                        <td><b>$disease_code~~</b>$disease_name</td>
                        <td>$count.</td>
                   </tr>";
              $count++;
           }
       }       
    }
    else if($data_element_value_source=="Pharmaceutical"){
        $sql_select_pharmaceutical_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search_value%' AND (Consultation_Type='Pharmacy' or Item_Type='Pharmacy') AND Status='Available' ORDER BY Product_Name ASC LIMIT 50") or die(mysqli_error($conn));
    
         if(mysqli_num_rows($sql_select_pharmaceutical_items_result)>0){
           $count=1;
           while($items_rows=mysqli_fetch_assoc($sql_select_pharmaceutical_items_result)){
              $Item_ID=$items_rows['Item_ID'];
              $Product_Name=$items_rows['Product_Name'];
              echo "<tr>
                        <td>
                            <input type='button' value='<<' onclick='merge_value_source_to_data_element($Item_ID)'/>
                        </td>
                        <td>$Product_Name</td>
                        <td>$count.</td>
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
        $sql_select_pharmaceutical_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search_value%' AND Consultation_Type='$data_element_value_source' AND Status='Available' ORDER BY Product_Name ASC LIMIT 50") or die(mysqli_error($conn));
    
         if(mysqli_num_rows($sql_select_pharmaceutical_items_result)>0){
           $count=1;
           while($items_rows=mysqli_fetch_assoc($sql_select_pharmaceutical_items_result)){
              $Item_ID=$items_rows['Item_ID'];
              $Product_Name=$items_rows['Product_Name'];
              echo "<tr>
                        <td>
                            <input type='button' value='<<' onclick='merge_value_source_to_data_element($Item_ID)'/>
                        </td>
                        <td>$Product_Name</td>
                        <td>$count.</td>
                   </tr>";
              $count++;
           }
       }
    }
}

