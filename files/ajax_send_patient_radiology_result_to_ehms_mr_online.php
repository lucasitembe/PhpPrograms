<?php
/*******gkcchief 19.04.2017********/
//send result to online server
include("includes/connection.php");
include("./includes/constants.php");
if(isset($_POST['Payment_Item_Cache_List_ID_arry'])){
 $Payment_Item_Cache_List_ID_arry=$_POST['Payment_Item_Cache_List_ID_arry'];
 $Registration_ID=$_POST['Registration_ID'];
//result by short text

foreach($Payment_Item_Cache_List_ID_arry as $Payment_Item_Cache_List_ID){
//retrive patient description 
    $result_by_text_array=[];
$sql_retrive_patient_description_result=mysqli_query($conn,"SELECT Parameter_Name,comments FROM tbl_radiology_discription rd,tbl_radiology_parameter rp WHERE rd.Parameter_ID=rp.Parameter_ID AND rd.Patient_Payment_Item_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_retrive_patient_description_result)>0){
    while($patient_description_rows=mysqli_fetch_assoc($sql_retrive_patient_description_result)){
        $Parameter_Name=$patient_description_rows['Parameter_Name'];
        $comments=$patient_description_rows['comments'];
        array_push($result_by_text_array, ($Parameter_Name.":::::".$comments));
    }
} 
//fetch saved radilogy image
$radiology_image="";
$fetch_radiology_image_result=mysqli_query($conn,"SELECT Radiology_Image FROM tbl_radiology_image WHERE Patient_Payment_Item_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($fetch_radiology_image_result)>0){
    while($radiology_image_rows=mysqli_fetch_assoc($fetch_radiology_image_result)){
        $Radiology_Image=$radiology_image_rows['Radiology_Image'];
            if(!empty($Radiology_Image)){
                $Radiology_Image;
                  $path_to_attachment_result="RadiologyImage/$Radiology_Image";
                  $b64Doc = chunk_split(base64_encode(file_get_contents($path_to_attachment_result)));
                  $radiology_image .=$b64Doc."unganisharadiologyimage";
           }
    }
}

 $result_by_text= json_encode($result_by_text_array);
// print_r($result_by_machine_intergration);
 $result_by_machine_intergration_data= "";
 //then send data to online saver
$patient_result_toehms_mr_online_array=array(
    "username"=>"gpitg",
    "password"=>"gpitgmronline",
    "result_by_text"=>$result_by_text,
    "result_by_attachment"=>$b64Doc,
    "Validated"=>"",
    "ivestigation_type"=>"radiology",
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
} 
