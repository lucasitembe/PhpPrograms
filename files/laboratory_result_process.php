<?php
@session_start();
include("./includes/connection.php");
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if($_POST['Patient_Payment_Item_List_ID'] !=''){
    $Patient_Payment_Item_List_ID=$_POST['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}
if($_GET['Patient_Payment_Item_List_ID'] !=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}
if($_POST['Item_ID']!=''){
    $Item_ID=$_POST['Item_ID'];
}else{
    $Item_ID='';
}
if($_GET['Item_ID']!=''){
    $Item_ID=$_GET['Item_ID'];
}else{
    $Item_ID='';
}

//if the submited button is pressed,first will check if the results where inserted b4 if not result will be inserted
//var_dump(filter_input_array(INPUT_GET));exit;
if((isset($_GET['submited'])) && filter_input(INPUT_GET, 'Status_From') == 'payment'){
if ((filter_input(INPUT_POST, 'Submit') == 'Save Results') AND (filter_input(INPUT_GET, 'pagename') == 'results'))
{

$k =0;
foreach ($_POST['Laboratory_Parameter_ID'] as  $Laboratory_Parameter_ID)
{
//
$Laboratory_Results = $_POST['Laboratory_Result'];
$select_results= mysqli_query($conn,"SELECT Patient_Payment_Result_ID,Laboratory_Result FROM tbl_patient_payment_results where Laboratory_Parameter_ID='".$Laboratory_Parameter_ID."' and Patient_Payment_ID='".filter_input(INPUT_GET, 'paymet_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
if(mysqli_num_rows($select_results) == 0){


              //if there is the value in fild box
    $insert_results = '';
    $update_result = '';

                       if(strlen($Laboratory_Results[$k]) > 0){
                            $insert_results =mysqli_query($conn,"INSERT INTO tbl_patient_payment_results (Laboratory_Parameter_ID,Employee_ID,Laboratory_Result,Result_Datetime,Patient_Payment_ID,Item_ID,Patient_ID)
                                                      VALUE ('$Laboratory_Parameter_ID','$Employee_ID','$Laboratory_Results[$k]',(SELECT NOW()),'".filter_input(INPUT_GET, 'paymet_id')."','".filter_input(INPUT_GET, 'Item_ID')."','".filter_input(INPUT_GET, 'patient_id')."') ") or die(mysqli_error($conn));

                         }

}else{
  extract(mysqli_fetch_array($select_results));
       $select_validation_status =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_validation WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."'");
                      if(mysqli_num_rows($select_validation_status) == 0){
                           if($Laboratory_Result != $Laboratory_Results[$k]){

                                $update_result =mysqli_query($conn,"UPDATE tbl_patient_payment_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' ");


                           }
                          if($update_result){ ?>
                              <script>
                                  alert("Result successfully updated");
                              </script>
                          <?php }else{ ?>
                              <script>
                                  alert("Failed to update result");
                              </script>
                          <?php }
              }
}
$k++;
}
header("location:laboratory_general_template.php?Item_ID=".$Item_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&payment_id=".filter_input(INPUT_GET, 'paymet_id')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&Item_ID=".filter_input(INPUT_GET, 'Item_ID')." ");



}else  if ((filter_input(INPUT_POST, 'submit') == 'Update Results') AND (filter_input(INPUT_GET, 'pagename') == 'validation')) {

$k =0;
foreach ($_POST['Laboratory_Parameter_ID'] as  $Laboratory_Parameter_ID)
{
$Laboratory_Results = $_POST['Laboratory_Result'];
$select_results= mysqli_query($conn,"SELECT Patient_Payment_Result_ID,Laboratory_Result FROM tbl_patient_payment_results where Laboratory_Parameter_ID='".$Laboratory_Parameter_ID."' and Patient_Payment_ID='".filter_input(INPUT_GET, 'paymet_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
if(mysqli_num_rows($select_results) == 0){


              //if there is the value in fild box
                       if(strlen($Laboratory_Results[$k]) > 0){
                      $insert_results =mysqli_query($conn,"INSERT INTO tbl_patient_payment_results (Laboratory_Parameter_ID,Employee_ID,Laboratory_Result,Result_Datetime,Patient_Payment_ID,Item_ID,Patient_ID)
                                                      VALUE ('$Laboratory_Parameter_ID','$Employee_ID','$Laboratory_Results[$k]',(SELECT NOW()),'".filter_input(INPUT_GET, 'paymet_id')."','".filter_input(INPUT_GET, 'Item_ID')."','".filter_input(INPUT_GET, 'patient_id')."') ");

                         }
    if($insert_results){ ?>
        <script>
            alert("Result successfully updated.");
        </script>
    <?php }else{ ?>
        <script>
            alert("Failed to update result.");
        </script>
    <?php }


}else{
  extract(mysqli_fetch_array($select_results));
       $select_validation_status =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_validation WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."'");
                      if(mysqli_num_rows($select_validation_status) > 0){
                           if($Laboratory_Result != $Laboratory_Results[$k]){
                              $isert_modified = mysqli_query($conn,"INSERT INTO tbl_laboratory_results_modification (Patient_Payment_Result_ID,Laboratory_Parameter_ID,Employee_ID,Laboratory_Result,Result_Datetime,Patient_Payment_ID,Item_ID,Patient_ID)
                                                      (SELECT * FROM tbl_patient_payment_results WHERE Patient_Payment_Result_ID ='".$Patient_Payment_Result_ID."')");
                              if($isert_modified){
                                $update_result =mysqli_query($conn,"UPDATE tbl_patient_payment_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' ");
                              }

                           }
                          if($update_result){ ?>
                              <script>
                                  alert("Result updated.");
                              </script>
                          <?php }else{ ?>
                              <script>
                                  alert("Failed to update result.");
                              </script>
                          <?php }
              }else{

              //if there is the value in fild box
                       if(strlen($Laboratory_Results[$k]) > 0){
                             $update_result =mysqli_query($conn,"UPDATE tbl_patient_payment_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' ");

                         }
              }
}
$k++;
}

header("location:laboratory_results_validation.php?Item_ID=".$Item_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&payment_id=".filter_input(INPUT_GET, 'paymet_id')."&patient_id=".filter_input(INPUT_GET, 'patient_id')." ");
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




}else if((isset($_GET['submited'])) && filter_input(INPUT_GET, 'Status_From') == 'cache'){




if ((filter_input(INPUT_POST, 'Submit') == 'Save Results') AND (filter_input(INPUT_GET, 'pagename') == 'results'))
{

$k =0;
foreach ($_POST['Laboratory_Parameter_ID'] as  $Laboratory_Parameter_ID)
{
//
$Laboratory_Results = $_POST['Laboratory_Result'];
$select_results= mysqli_query($conn,"SELECT Patient_Cache_Results_ID,Laboratory_Result FROM tbl_patient_cache_results where Laboratory_Parameter_ID='".$Laboratory_Parameter_ID."' and Payment_Cache_ID='".filter_input(INPUT_GET, 'paymet_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
if(mysqli_num_rows($select_results) == 0){


              //if there is the value in fild box
                       if(strlen($Laboratory_Results[$k]) > 0){
                      $insert_results =mysqli_query($conn,"INSERT INTO tbl_patient_cache_results (Laboratory_Parameter_ID,Employee_ID,Laboratory_Result,Result_Datetime,Payment_Cache_ID,Item_ID,Patient_ID)
                                                      VALUE ('$Laboratory_Parameter_ID','$Employee_ID','$Laboratory_Results[$k]',(SELECT NOW()),'".filter_input(INPUT_GET, 'paymet_id')."','".filter_input(INPUT_GET, 'Item_ID')."','".filter_input(INPUT_GET, 'patient_id')."') ");

                         }
                        if($insert_results){ ?>
                            <script>
                                alert("Result successfully inserted.");
                            </script>
                        <?php }else{ ?>
                            <script>
                                alert("Failed to insert result.");
                            </script>
                        <?php }


}else{

  extract(mysqli_fetch_array($select_results));
       $select_validation_status =mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results WHERE Patient_Cache_Results_ID = '".$Patient_Cache_Results_ID."'");
                      if(mysqli_num_rows($select_validation_status) == 0){
                           if($Laboratory_Result != $Laboratory_Results[$k]){

                                $update_result =mysqli_query($conn,"UPDATE tbl_patient_cache_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Cache_Results_ID = '".$Patient_Cache_Results_ID."' ");


                           }
                          if($update_result){ ?>
                              <script>
                                  alert("Result updated.");
                              </script>
                          <?php }else{ ?>
                              <script>
                                  alert("Failed to update result.");
                              </script>
                          <?php }
              }
}
$k++;
}

header("location:laboratory_general_template.php?Item_ID=".$Item_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&payment_id=".filter_input(INPUT_GET, 'paymet_id')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&Item_ID=".filter_input(INPUT_GET, 'Item_ID')." ");



}else  if ((filter_input(INPUT_POST, 'submit') == 'Update Results') AND (filter_input(INPUT_GET, 'pagename') == 'validation')) {

$k =0;
foreach ($_POST['Laboratory_Parameter_ID'] as  $Laboratory_Parameter_ID)
{
$Laboratory_Results = $_POST['Laboratory_Result'];
$select_results= mysqli_query($conn,"SELECT Patient_Cache_Results_ID,Laboratory_Result FROM tbl_patient_cache_results where   Laboratory_Parameter_ID='".$Laboratory_Parameter_ID."' and Payment_Cache_ID='".filter_input(INPUT_GET, 'paymet_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
if(mysqli_num_rows($select_results) == 0){


              //if there is the value in fild box
                       if(strlen($Laboratory_Results[$k]) > 0){
                      $insert_results =mysqli_query($conn,"INSERT INTO tbl_patient_cache_results (Laboratory_Parameter_ID,Employee_ID,Laboratory_Result,Result_Datetime,Patient_Payment_ID,Item_ID,Patient_ID)
                                                      VALUE ('$Laboratory_Parameter_ID','$Employee_ID','$Laboratory_Results[$k]',(SELECT NOW()),'".filter_input(INPUT_GET, 'paymet_id')."','".filter_input(INPUT_GET, 'Item_ID')."','".filter_input(INPUT_GET, 'patient_id')."') ");

                         }
    if($insert_results){?>
        <script>
            alert("Result inserted");
        </script>
    <?php }else{ ?>
        <script>
            alert("Failed to insert result.");
        </script>
    <?php }


}else{
  extract(mysqli_fetch_array($select_results));
       $select_validation_status =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_validation_cache WHERE Patient_Cache_Results_ID = '".$Patient_Cache_Results_ID."'");
                      if(mysqli_num_rows($select_validation_status) > 0){
                           if($Laboratory_Result != $Laboratory_Results[$k]){
                            
                                $select_query=mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results WHERE Patient_Cache_Results_ID='".$Patient_Cache_Results_ID."' ");
                                $select_query_row=mysqli_fetch_array($select_query);
                                $Patient_Cache_Results_ID=$select_query_row['Patient_Cache_Results_ID'];
                                $Laboratory_Parameter_ID=$select_query_row['Laboratory_Parameter_ID'];
                                $Employee_ID=$select_query_row['Employee_ID'];
                                $Laboratory_Result=$select_query_row['Laboratory_Result'];
                                $Result_Datetime=$select_query_row['Result_Datetime'];
                                $Payment_Cache_ID=$select_query_row['Payment_Cache_ID'];
                                $Item_ID=$select_query_row['Item_ID'];
                                $Patient_ID=$select_query_row['Patient_ID'];
                                
                              $isert_modified = mysqli_query($conn,"INSERT INTO tbl_laboratory_results_modification SET
                                                            Patient_Payment_Result_ID='$Patient_Cache_Results_ID',
                                                            Laboratory_Parameter_ID='$Laboratory_Parameter_ID',
                                                            Employee_ID='$Employee_ID',
                                                            Laboratory_Result='$Laboratory_Result',
                                                            Result_Datetime='$Result_Datetime',
                                                            Patient_Payment_ID='$Payment_Cache_ID',
                                                            Item_ID='$Item_ID',
                                                            Patient_ID='$Patient_ID' ");
                              if($isert_modified){
                                $update_result =mysqli_query($conn,"UPDATE tbl_patient_cache_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Cache_Results_ID = '".$Patient_Cache_Results_ID."' ");
                              }
                               if($update_result){ ?>
                                   <script>
                                       alert("Result updated.");
                                   </script>
                               <?php }else{ ?>
                                   <script>
                                       alert("Failed to update result");
                                   </script>
                               <?php }

                           }
              }else{

              //if there is the value in fild box
                       if(strlen($Laboratory_Results[$k]) > 0){
                             $update_result =mysqli_query($conn,"UPDATE tbl_patient_cache_results
                                                                 SET Laboratory_Result ='$Laboratory_Results[$k]',Employee_ID ='$Employee_ID', Result_Datetime =(SELECT NOW())
                                                                  WHERE Patient_Cache_Results_ID = '".$Patient_Cache_Results_ID."' ");

                         }
                          if($update_result){ ?>
                              <script>
                                  alert("Result successfully updated.");
                              </script>
                          <?php }else{ ?>
                              <script>
                                  alert("Failed to update result.");
                              </script>
                          <?php }
              }
}
$k++;
}

header("location:laboratory_results_validation.php?Item_ID=".$Item_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&payment_id=".filter_input(INPUT_GET, 'paymet_id')."&patient_id=".filter_input(INPUT_GET, 'patient_id')." ");
}


}