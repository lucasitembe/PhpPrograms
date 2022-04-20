<?php
          require_once('./includes/connection.php');
           if(isset($_POST['search_value2'])){
           $search_value  = $_POST['search_value2'];
         $sql_select_brand_namel_items_result=mysqli_query($conn,"SELECT * FROM tbl_items WHERE  Consultation_Type='Pharmacy' AND Product_Name LIKE '%$search_value%' LIMIT 50") or die(mysqli_error($conn));

         if(mysqli_num_rows($sql_select_brand_namel_items_result)>0){
           $count=1;
           while($items_rows=mysqli_fetch_assoc($sql_select_brand_namel_items_result)){
              $Item_ID=$items_rows['Item_ID'];
              $Product_Name=$items_rows['Product_Name'];
              echo "<tr>
                    <td>$count.</td>
                    <td>$Product_Name</td>
                        <td>
                            <input type='button' style='color:blue;' value='>>' onclick='merge_value_source_to_data_element($Item_ID)'/>
                        </td>
                   </tr>";
              $count++;
           }
       }
           }
