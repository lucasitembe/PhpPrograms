<?php
/*written by gkcchief on 01.03.2019 */
include("./includes/connection.php");
include("./includes/constants.php");
include("medical_record_encrypt_decrypt.php");
// call encrypt method
//$data = $crypt->encrypt($accountNum); 
//////////////////////////////////////////////////////////////////
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
    $consultation_ID=$_POST['consultation_ID'];
    $card_no="";
    //select patient card information
    $sql_select_card_no_result=mysqli_query($conn,"SELECT card_no FROM tbl_member_afya_card WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_card_no_result)>0){
        $card_no=mysqli_fetch_assoc($sql_select_card_no_result)['card_no'];
    }
    //select consultation_record
    $consultation_record=[];
    $ordered_item_record=[];
    $sql_select_complain_and_consultation_record_result=mysqli_query($conn,"SELECT consultation_histry_ID,maincomplain,cons_hist_Date FROM tbl_consultation_history WHERE consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_complain_and_consultation_record_result)>0){
        while($complain_date_rows=mysqli_fetch_assoc($sql_select_complain_and_consultation_record_result)){
            $consultation_histry_ID=$complain_date_rows['consultation_histry_ID'];
            $maincomplain=$complain_date_rows['maincomplain'];
            $cons_hist_Date=$complain_date_rows['cons_hist_Date'];
            // select final diagnosis
            $final_diagnosis="";
            $sql_select_final_diagnosis_result=mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_ID IN (SELECT disease_ID FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND diagnosis_type='diagnosis')") or  die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_final_diagnosis_result)>0){
                while($final_diagnosis_rows=mysqli_fetch_assoc($sql_select_final_diagnosis_result)){
                    $disease_code=$final_diagnosis_rows['disease_code'];
                    $final_diagnosis .=$disease_code."<<,>>";
                }
            }
            // select provisional diagnosis
            $provisional_diagnosis="";
            $sql_select_provisional_diagnosis_result=mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_ID IN (SELECT disease_ID FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND diagnosis_type='provisional_diagnosis')") or  die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_provisional_diagnosis_result)>0){
                while($provisional_diagnosis_rows=mysqli_fetch_assoc($sql_select_provisional_diagnosis_result)){
                    $disease_code=$provisional_diagnosis_rows['disease_code'];
                    $provisional_diagnosis .=$disease_code."<<,>>";
                }
            }
            ////select ordered_item_record
            
            $sql_select_ordered_item_record_result=mysqli_query($conn,"SELECT Product_Code,Payment_Item_Cache_List_ID,Check_In_Type,Consultant,ilc.Status,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache ilc,tbl_items it WHERE ilc.Item_ID=it.Item_ID AND Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_id='$consultation_ID')") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_ordered_item_record_result)>0){
                while($ordered_item_record_rows=mysqli_fetch_assoc($sql_select_ordered_item_record_result)){
                    $Product_Code=$ordered_item_record_rows['Product_Code'];
                    $Payment_Item_Cache_List_ID=$ordered_item_record_rows['Payment_Item_Cache_List_ID'];
                    $Check_In_Type=$ordered_item_record_rows['Check_In_Type'];
                    $Consultant=$ordered_item_record_rows['Consultant'];
                    $Status=$ordered_item_record_rows['Status'];
                    $Doctor_Comment=$ordered_item_record_rows['Doctor_Comment'];
                    $Transaction_Date_And_Time=$ordered_item_record_rows['Transaction_Date_And_Time'];
                    $orderd_item_ary=[];
                    array_push($orderd_item_ary, $Product_Code);
                    array_push($orderd_item_ary, $Payment_Item_Cache_List_ID);
                    array_push($orderd_item_ary, $crypt->encrypt($Check_In_Type));
                    array_push($orderd_item_ary, $crypt->encrypt($Consultant));
                    array_push($orderd_item_ary, $crypt->encrypt($Doctor_Comment));
                    array_push($orderd_item_ary, $crypt->encrypt($Status));
                    array_push($orderd_item_ary, $Transaction_Date_And_Time);
                    array_push($ordered_item_record,$orderd_item_ary);
                }
            }
            $consultation_rec_array=[];
            array_push($consultation_rec_array, $card_no);
            array_push($consultation_rec_array, ehms_mr_online_hospital_id);
            array_push($consultation_rec_array, $crypt->encrypt($maincomplain));
            array_push($consultation_rec_array, $cons_hist_Date);
            array_push($consultation_rec_array, $crypt->encrypt($final_diagnosis));
            array_push($consultation_rec_array, $consultation_histry_ID);
            array_push($consultation_rec_array, $crypt->encrypt($provisional_diagnosis));
            array_push($consultation_rec_array, $ordered_item_record);
            array_push($consultation_record,$consultation_rec_array);
        }
    }
    $medical_record_data_array=array(
    "username"=>"gpitg",
    "password"=>"gpitgmronline",
    "consultation_record"=>$consultation_record,
);
$medical_record_data= json_encode($medical_record_data_array);
$header = [
    'Content-Type: application/json',
    'Accept: application/json'
];
$url = ehms_mr_local_url.'/ehms_mr_local';
//$url = ehms_mr_local_url.'/ehms_mr_online';
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $medical_record_data );
curl_setopt($ch, CURLOPT_POSTREDIR, 3);
echo $result = curl_exec($ch);
curl_close($ch);
//$json = json_decode($result, true);
//print_r($json);
}