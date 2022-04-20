<?php
include_once("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
}else{
    $start_date=""; 
}
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date']; 
}else{
   $end_date=""; 
}
if(isset($_POST['option_ID'])){
    $option_ID=$_POST['option_ID']; 
 }else{
    $option_ID=""; 
 }
$count=1;
$Patient_Name = null;
$Registration_ID = null;
$sql=mysqli_query($conn,"SELECT SpReconcReqId,SpBillId,PayRefId,BillCtrNum,PaidAmt,CCy,CtrAccNum,UsdPayChnl,TrxDtTm FROM tbl_gepgSpReconcRespo WHERE TrxDtTm BETWEEN '$start_date' AND '$end_date'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql)>0){
    while($amount_rows=mysqli_fetch_assoc($sql)){
        $sql1 = mysqli_query($conn,"SELECT Patient_Name,Registration_ID FROM tbl_patient_registration WHERE Registration_ID=(SELECT Registration_ID from tbl_payment_cache WHERE Payment_Cache_ID =(SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE gepg_bill_id='".$amount_rows['SpBillId']."' LIMIT 1))") or die(mysqli_error($conn));
        while($name_rows=mysqli_fetch_assoc($sql1)){
            $Patient_Name = $name_rows['Patient_Name'];
            $Registration_ID = $name_rows['Registration_ID'];
        }
        echo "<tr>
                <td>$count.</td>
                <td>".$Patient_Name."</td>
                <td>".$Registration_ID."</td>
                <td>".$amount_rows['SpReconcReqId']."</td>
                <td>".$amount_rows['SpBillId']."</td>
                <td>".$amount_rows['PayRefId']."</td>
                <td>".$amount_rows['BillCtrNum']."</td>
                <td>".$amount_rows['PaidAmt']."</td>
                <td>".$amount_rows['CCy']."</td>
                <td>".$amount_rows['CtrAccNum']."</td>
                <td>".$amount_rows['UsdPayChnl']."</td>
                <td>".$amount_rows['TrxDtTm']."</td>
             </tr>";
       $count++;
    }
}
?>