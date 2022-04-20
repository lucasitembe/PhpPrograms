<?php
include("includes/connection.php");
if (isset($_POST['Registration_ID'])) {
   $Registration_ID=$_POST['Registration_ID'];
   //select patient tes
   $sql_select_patient_lab_test_result=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID,Item_ID,Transaction_Date_And_Time FROM tbl_item_list_cache WHERE Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID') AND Check_In_Type='Laboratory' AND Payment_Item_Cache_List_ID IN (SELECT sample_test_id FROM tbl_intergrated_lab_results) ORDER BY Transaction_Date_And_Time DESC") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_select_patient_lab_test_result)>0){
       $count_rest=1;
       while($result_rows=mysqli_fetch_assoc($sql_select_patient_lab_test_result)){
          $Payment_Item_Cache_List_ID=$result_rows['Payment_Item_Cache_List_ID'];
          $Item_ID=$result_rows['Item_ID'];
          $Transaction_Date_And_Time=$result_rows['Transaction_Date_And_Time'];
          
          //select test name
          $sql_select_test_name_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
          $Product_Name=mysqli_fetch_assoc($sql_select_test_name_result)['Product_Name'];
          ?>
            <table class="table table-bordered" style="background: #FFFFFF">
                <tr>
                <caption> 
                    <b><?= $count_rest.".".$Product_Name."   ".$Transaction_Date_And_Time ?></b>&nbsp;&nbsp;&nbsp;<input type="button" value="PREVIEW" onclick="preview_lab_result('<?= $Product_Name ?>',<?= $Payment_Item_Cache_List_ID ?>)" class="art-button-green"/>
                </caption>
                </tr>
                <tr>
                    <td><b>S/No.</b></td>
                    <td><b>PARAMETERS</b></td>
                    <td><b>RESULTS</b></td>
                    <td><b>REFERENCE RANGE/NORMAL VALUE</b></td>
                    <td><b>UNITS</b></td>
                    <td><b>STATUS</b></td>
                    <td><b>M</b></td>
                    <td><b>V</b></td>
                    <td><b>S</b></td>
                    <td><b>RESULT DATE</b></td>
                </tr>
                <tbody>
                <?php 
                    $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)>0){
                        $count_sr=1;
                       while($rows=mysqli_fetch_assoc($sql_select_patient_lab_intergrate_result)){
                          $parameters= $rows['parameters'];
                          $results= $rows['results'];
                          $reference_range_normal_values= $rows['reference_range_normal_values'];
                          $units= $rows['units'];
                          $status_h_or_l= $rows['status_h_or_l'];
                          $sample_test_id= $rows['sample_test_id'];
                          $machine_type= $rows['machine_type'];
                          $result_date= $rows['result_date'];
                          $modified= $rows['modified'];
                          $validated= $rows['validated'];
                          $sent_to_doctor= $rows['sent_to_doctor'];
                           echo "<tr>
                                    <td>$count_sr</td>
                                    <td>$parameters</td>
                                    <td>$results</td>
                                    <td>$reference_range_normal_values</td>
                                    <td>$units</td>
                                    <td>$status_h_or_l</td>
                                    <td>$modified</td>
                                    <td>$validated</td>
                                    <td>$sent_to_doctor</td>
                                    <td>$result_date</td>
                                </tr>";
                           $count_sr++;
                       } 
                    }else{
                        echo "<tr><td style='color:red' colspan='11'>Result not sent to doctor</td></tr>";
                    }
                ?>
                </tbody>
            </table>
          <?php
          $count_rest++;
       }
   }else{
           ?>
              <h1>NO RESULT FOUND</h1> 
               <?php
       }
}