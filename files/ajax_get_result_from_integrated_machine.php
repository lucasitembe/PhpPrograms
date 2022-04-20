<?php
include("includes/connection.php");
session_start();
if (isset($_POST['Payment_Item_Cache_List_ID'])) {
   $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
   $Product_Name=$_POST['Product_Name'];
   $count_run=1;
   ?>
<div class="row"><div class="col-md-4"></div><div class="col-md-4"><input type="text" value="<?= $Payment_Item_Cache_List_ID ?>" id="new_specimen_id"></div><div class="col-md-4"><input type="button" value="UPDATE SAMPLE ID" onclick="change_speciment_id('<?= $Payment_Item_Cache_List_ID ?>')" class="art-button-green"/></div></div>  
  <?php
   $sql_select_machine_run_time_result=mysqli_query($conn,"SELECT result_date FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' GROUP BY result_date ORDER BY  intergrated_lab_results_id DESC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_machine_run_time_result)>0){
      while($run_time_rows=mysqli_fetch_assoc($sql_select_machine_run_time_result)){
         $result_date=$run_time_rows['result_date'];
   ?>
<fieldset>
    <div class="col-md-4"><h3><?= $count_run ?>. Machine Run</h3></div>
</fieldset>
<fieldset style='height:400px;overflow-y: scroll'>
    <table class="table table-bordered" style="background: #FFFFFF">
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
    $count_run++;
        $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' AND result_date='$result_date'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)>0){
            $count_sr=1;
           while($rows=mysqli_fetch_assoc($sql_select_patient_lab_intergrate_result)){
              $parameters= $rows['parameters'];
              $results= $rows['results'];
              $reference_range_normal_values= $rows['reference_range_normal_values'];
              if ($reference_range_normal_values === ''){
                $select_lab_ref = mysqli_query($conn,"SELECT * FROM tbl_parameters WHERE Parameter_Name='$parameters'");
                while($row = mysqli_fetch_array($select_lab_ref)){
                    $reference_range_normal_values = $row['lower_value']." - ".$row['higher_value'];
                }
              }
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
            echo "<tr><td colspan='10' style='color:red'><h3>NO RESULT</h3></td></tr>";
        }
        $checkIfAllowedValidate = "no";
        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $checkIfAllowedValidate = "yes";
        }
    ?>
    </tbody>
</table>
</fieldset>
<fieldset>
    <?php if($sent_to_doctor=='no') {?>
    <input type="button" value="SEND" onclick="send_patient_lab_result('<?= $Product_Name ?>',<?= $Payment_Item_Cache_List_ID ?>,'<?= $result_date ?>')" class="pull-right" />
    <?php } ?>
    <?php if($validated=='no'&&$checkIfAllowedValidate=="yes") {?>
    <input type="button" value="VALIDATE" onclick="validate_patient_lab_result('<?= $Product_Name ?>',<?= $Payment_Item_Cache_List_ID ?>,'<?= $result_date ?>')"class="pull-right"/>
    <?php } ?>
    <input type="button" value="PREVIEW" onclick="preview_intergrated_lab_result('<?= $Product_Name ?>',<?= $Payment_Item_Cache_List_ID ?>,'<?= $result_date ?>')" class="pull-right art-button" />
    
</fieldset>
       <?php 
      }
  }else{
            echo "<fieldset><h3 style='color:red'>NO RESULT</h3></fieldset>";
        }
}
?>
<script>
   function change_speciment_id(Payment_Item_Cache_List_ID){
       var new_specimen_id=$("#new_specimen_id").val();
       if(confirm("Are you sure you want to change specimen id from "+ Payment_Item_Cache_List_ID +" to "+ new_specimen_id)){
                $.ajax({
                type:'POST',
                url:'ajax_change_speciment_id.php',
                data:{Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,new_specimen_id:new_specimen_id},
                success:function(data){
                    if(data=="success"){
                       alert("Updated Successfully") 
                    }else{
                       alert("process fail...please try again."); 
                    }
                }
            });
         }
   }
</script>
