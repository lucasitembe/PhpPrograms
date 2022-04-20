<?php
include("./includes/connection.php");
$card_no = $_POST['card_no'];
$feedback=[];
//check if this card exist in this database
$sql_check_if_this_card_exist_result=mysqli_query($conn,"SELECT Registration_ID FROM tbl_member_afya_card WHERE card_no='$card_no'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_if_this_card_exist_result)>0){
    $feedback['status_control']=200;
    $Registration_ID=$feedback['Registration_ID']=mysqli_fetch_assoc($sql_check_if_this_card_exist_result)['Registration_ID'];
    //for nhif member no
    $Member_Number="";
    $sql_select_member_no_result=mysqli_query($conn,"SELECT Member_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_member_no_result)>0){
        $Member_Number=mysqli_fetch_assoc($sql_select_member_no_result)['Member_Number'];
    }
    $feedback['Member_Number']=$Member_Number;
}else{
    $feedback['status_control']=404;
}
echo json_encode($feedback);