<?php
require_once('./includes/connection.php');
if (isset($_POST['consultation_ID'])) {
    $consultation_ID = $_POST['consultation_ID'];
    $Registration_ID = $_POST['Registration_ID'];
    //SELECT ALL PRESCRIBED MEDICINE UNDER THIS CONSULTATION
    $sql_select_all_consultation_medicine_result=mysqli_query($conn,"SELECT Product_Name,it.Item_ID,Doctor_Comment,Payment_Item_Cache_List_ID FROM tbl_items it,tbl_item_list_cache ilc WHERE it.Item_ID=ilc.Item_ID AND Check_In_Type='Pharmacy' AND Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID='$consultation_ID' AND ilc.status IN ('partial dispensed', 'dispensed'))") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_consultation_medicine_result)){
        $count_sn=1;
        echo "<table class='table' style='background:#FFFFFF'><tr><td><b>S/No.</b></td><td><b>MEDICATION NAME</b></td><td><b>CURRENT DOSAGE</b></td><td><b>UPDATE NEW DOSAGE</b></td><td><b>Discontinue Reason</b></td><td><b>ACTION</b></td><tr>";
        while($medication_rows=mysqli_fetch_assoc($sql_select_all_consultation_medicine_result)){
            $Item_ID=$medication_rows['Item_ID'];
            $Product_Name=$medication_rows['Product_Name'];
            $Doctor_Comment=$medication_rows['Doctor_Comment'];
            $Payment_Item_Cache_List_ID=$medication_rows['Payment_Item_Cache_List_ID'];
            
            //check if this medicine has been discontinue

            $sql_check_if_the_medicine_result=mysqli_query($conn,"SELECT Discontinue_Reason FROM tbl_inpatient_medicines_given WHERE Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID' AND Item_ID='$Item_ID' AND Discontinue_Status='yes'") or die(mysqli_error($conn));
            $background="";
            $hide_btn="";
            $Discontinue_Reason="";
            if(mysqli_num_rows($sql_check_if_the_medicine_result)>0){
               $background=" style='background:#f5190a; color:white; '";
               $hide_btn="hide";
               $readonly ='readonly';
               $Discontinue_Reason=mysqli_fetch_assoc($sql_check_if_the_medicine_result)['Discontinue_Reason'];
              $btnCancelDiscontinue="<input type='button' onclick='cancel_discontinuetion($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID )' class='btn btn-danger btn-sm' value='CANCEL'>";
            }else{
                $btnCancelDiscontinue='';
                $readonly='';
            }
            echo "<tr $background>
                    <td width='50px'>$count_sn.</td>
                    <td>$Product_Name</td>
                    <td>$Doctor_Comment</td>
                    <td width='30%'><input type='text' placeholder='New Dosage' style='text-align:center;width:100%' $readonly id='new_dosage$Payment_Item_Cache_List_ID' oninput='updateDoctorItemCOmment($Payment_Item_Cache_List_ID,this.value)' /></td>
                    <td><input type='text' placeholder='Enter Discontinue Reason' style='text-align:center' id='discontinue_reason$Item_ID' class='$hide_btn'>$Discontinue_Reason</td>
                    <td width='7%'><input type='button' value='Discontinue' onclick='discontinue_medication_for_this_patient($Registration_ID,$consultation_ID,$Item_ID, $Payment_Item_Cache_List_ID)' class='art-button $hide_btn'/>$btnCancelDiscontinue</td>
                 </tr>";
            $count_sn++;
        }
    }
}


// <input type='button' value='Update' class='btn btn-primary pull-right' onclick='update_new_dosage($Payment_Item_Cache_List_ID,\"$Doctor_Comment\")'/>