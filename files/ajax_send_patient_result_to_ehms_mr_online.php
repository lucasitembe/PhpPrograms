<?php
/*******gkcchief 19.04.2019********/
//send result to online server
include("includes/connection.php");
include("./includes/constants.php");
if(isset($_POST['Payment_Item_Cache_List_ID'])){
$Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
$Registration_ID=$_POST['Registration_ID'];
//result by short text
 $testrs = mysqli_query($conn,"SELECT result,Validated FROM tbl_test_results ttr,tbl_tests_parameters_results ttpr WHERE ttr.test_result_ID=ttpr.ref_test_result_ID AND payment_item_ID='" . $Payment_Item_Cache_List_ID . "' ") or die(mysqli_error($conn));
 $result_rows = mysqli_fetch_assoc($testrs);
 $result_by_text = $result_rows['result'];
 $Validated = $result_rows['Validated'];
//get the attachmnet data and encode into base 64
 //get the name of attachment file
 $Attachment_Url="";
 $sql_select_name_of_attached_file_result=mysqli_query($conn,"SELECT Attachment_Url FROM tbl_attachment WHERE Registration_ID='$Registration_ID' AND item_list_cache_id='$Payment_Item_Cache_List_ID' ORDER BY Attachment_ID DESC LIMIT 1") or die(mysqli_error($conn));
 if(mysqli_num_rows($sql_select_name_of_attached_file_result)>0){
     $Attachment_Url=mysqli_fetch_assoc($sql_select_name_of_attached_file_result)['Attachment_Url'];
 }
 if(!empty($Attachment_Url)){
      $Attachment_Url;
        $path_to_attachment_result="patient_attachments/$Attachment_Url";
        $b64Doc = chunk_split(base64_encode(file_get_contents($path_to_attachment_result)));
 }
 //check for result from intergration
 $result_by_machine_intergration=[];
 $sql_select_result_from_intergrated_machine_result=mysqli_query($conn,"SELECT parameters,results,reference_range_normal_values,units,validated,result_date,status_h_or_l,modified,sent_to_doctor FROM tbl_intergrated_lab_results WHERE `sample_test_id`='$Payment_Item_Cache_List_ID' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
 if(mysqli_num_rows($sql_select_result_from_intergrated_machine_result)>0){
   while($result_by_machine_intergration_rows=mysqli_fetch_array($sql_select_result_from_intergrated_machine_result)){
//       $result_by_machine_intergration.=$result_by_machine_intergration_rows;
       array_push($result_by_machine_intergration, $result_by_machine_intergration_rows);
   }; 
 }
// print_r($result_by_machine_intergration);
 $result_by_machine_intergration_data= json_encode($result_by_machine_intergration);
 //then send data to online saver
$patient_result_toehms_mr_online_array=array(
    "username"=>"gpitg",
    "password"=>"gpitgmronline",
    "result_by_text"=>$result_by_text,
    "result_by_attachment"=>$b64Doc,
    "Validated"=>$Validated,
    "ivestigation_type"=>"laboratory",
    "hospital_id"=>"1101",
    "local_system_ordered_item_id"=>"$Payment_Item_Cache_List_ID",
    "result_by_machine_intergration"=>"$result_by_machine_intergration_data",
);
$patient_result_toehms_mr_online= json_encode($patient_result_toehms_mr_online_array);
$header = [
    'Content-Type: application/json',
    'Accept: application/json'
];
$url = ehms_mr_local_url.'/ehms_mr_local/send_patient_result_to_ehms_mr_online_local.php';
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $patient_result_toehms_mr_online );
curl_setopt($ch, CURLOPT_POSTREDIR, 3);
echo $result = curl_exec($ch);
curl_close($ch);
}
    
