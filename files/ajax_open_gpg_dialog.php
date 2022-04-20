<?php
include_once("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}else{
    $Registration_ID=""; 
}
if(isset($_POST['Payment_Cache_ID'])){
   $Payment_Cache_ID=$_POST['Payment_Cache_ID']; 
}else{
   $Payment_Cache_ID=""; 
}
if(isset($_POST['Sub_Department_ID'])){
   $Sub_Department_ID=$_POST['Sub_Department_ID']; 
}else{
   $Sub_Department_ID=""; 
}
if(isset($_POST['Transaction_Type'])){
   $Transaction_Type=$_POST['Transaction_Type']; 
}else{
   $Transaction_Type=""; 
}
if(isset($_POST['Patient_Name'])){
   $Patient_Name=$_POST['Patient_Name']; 
}else{
   $Patient_Name=""; 
}
if(isset($_POST['Check_In_Type'])){
   $Check_In_Type=$_POST['Check_In_Type']; 
}else{
   $Check_In_Type=""; 
}
$select_Transaction_Items_Active = mysqli_query($conn,"select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, 
                                                    ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                                    from tbl_item_list_cache ilc, tbl_items its
                                                    where ilc.item_id = its.item_id and
                                                    ilc.status = 'active' and
                                                    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                                    ilc.Transaction_Type = '$Transaction_Type' and
                                                    ilc.Check_In_Type = '$Check_In_Type'") or die(mysqli_error($conn));

    $no = mysqli_num_rows($select_Transaction_Items_Active);

    if($no > 0){
        while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
            if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity'];
            }else{
                $Quantity = $row['Edited_Quantity'];
            }
            if($Quantity < 1){ $Control = 'no'; }
            if($row['Price'] <= 0){ $Control = 'no'; }

            $status = $row['status'];
            if($no > 1){
        ?>
        <?php
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            $temp++;
            
        }
    }
?>


    <div style="height:150px">
        <table class="table" >
            <tr>
                <td width="40%"><b>CONTROL NUMBER</b></td>
                <td id="control_number"></td>
            </tr>
            <tr>
                <td><b>REQUEST STATUS</b></td>
                <td id="gepg_request_status"></td>
            </tr>
            <tr>
                <td><b>PAYMENT STATUS</b></td>
                <td id="gepg_payment_status">Not Completed</td>
            </tr>
            <tr>
                <td><b>AMOUNT</b></td>
                <td id="amount_to_be_paid"><?php echo number_format($total); ?></td>
            </tr>
        </table>
    </div>
   <center>
        <table>
            <tr id="gepg_progress_bar" style="display:none">
                <td colspan="3" id=""><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></td>
            </tr>
            <tr>
                <td><input type="button" value="REQUEST CONTROL NUMBER" onclick="request_control_number_from_gepg(<?= $Registration_ID ?>,<?= $total ?>,'<?= $Patient_Name ?>','<?= $Check_In_Type ?>','<?= $Payment_Cache_ID ?>')" class="art-button-green"/></td>
                <td><input type="button" value="CONFIRM PAYMENT" class="art-button-green" onclick="gepg_confirm_payment(<?= $Payment_Cache_ID ?>,'<?= $Check_In_Type ?>',<?= $Registration_ID ?>)"/></td>
                <td><input type="button" value="PRINT RECEIPT" class="art-button-green"/></td>
            </tr>
        </table>
    </center>