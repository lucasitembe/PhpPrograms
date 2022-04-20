<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/supplier.php");
include_once("./functions/grnpurchasecache.php");
include("return_unit_of_measure.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


//get employee name
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}
//get sub department id
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
}
if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
} else {
    $Supplier_ID = '';
}
//echo $Supplier_ID;
if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = '';
}
if (isset($_GET['Debit_Note_Number'])) {
    $Debit_Note_Number = $_GET['Debit_Note_Number'];
} else {
    $Debit_Note_Number = '';
}
if (isset($_GET['Invoice_Number'])) {
    $Invoice_Number = $_GET['Invoice_Number'];
} else {
    $Invoice_Number = '';
}
if (isset($_GET['Delivery_Date'])) {
    $Delivery_Date = $_GET['Delivery_Date'];
} else {
    $Delivery_Date = '';
}
if (isset($_GET['RV_Number'])) {
    $RV_Number = $_GET['RV_Number'];
} else {
    $RV_Number = '';
}
if (isset($_GET['lpo'])) {
    $lpo = $_GET['lpo'];
} else {
    $lpo = '';
}


// check if data arleady added
// $sql_select_grn_without_id_result=mysqli_query($conn,"SELECT grn_without_id FROM tbl_grn_without_purchase_order_approval_cache WHERE Sub_Department_ID='$Sub_Department_ID' AND lpo='$lpo' AND Invoice_Number='$Invoice_Number' AND Supplier_ID='$Supplier_ID' ORDER BY grn_without_id DESC LIMIT 1") or die(mysqli_error($conn));

//    if(mysqli_num_rows($sql_select_grn_without_id_result)>0){
//        $grn_without_id=mysqli_fetch_assoc($sql_select_grn_without_id_result)['grn_without_id'];
//    }else{
    //save data for approval
    $sql_save_data_result=mysqli_query($conn,"INSERT INTO tbl_grn_without_purchase_order_approval_cache(Supplier_ID,Sub_Department_ID,lpo,Debit_Note_Number,Invoice_Number,RV_Number,Delivery_Date,Employee_ID) VALUES('$Supplier_ID','$Sub_Department_ID','$lpo','$Debit_Note_Number','$Invoice_Number','$RV_Number','$Delivery_Date','$Employee_ID')") or die(mysqli_error($conn));
    
if($sql_save_data_result){
   $sql_select_grn_without_id_result=mysqli_query($conn,"SELECT grn_without_id FROM tbl_grn_without_purchase_order_approval_cache WHERE Sub_Department_ID='$Sub_Department_ID' ORDER BY grn_without_id DESC LIMIT 1") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_select_grn_without_id_result)>0){
       $grn_without_id=mysqli_fetch_assoc($sql_select_grn_without_id_result)['grn_without_id'];
            
       $sql_select_items_result=mysqli_query($conn,"SELECT * FROM tbl_grn_purchase_cache WHERE Sub_Department_ID='$Sub_Department_ID' AND Supplier_ID = '$Supplier_ID' ") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_items_result)>0){
                while($item_rows=mysqli_fetch_assoc($sql_select_items_result)){
                    $Purchase_Cache_ID=$item_rows['Purchase_Cache_ID'];
                    $Quantity_Required=$item_rows['Quantity_Required'];
                    $Item_Remark=$item_rows['Item_Remark'];
                    $Item_ID=$item_rows['Item_ID'];
                    $Container_Qty=$item_rows['Container_Qty'];
                    $Items_Per_Container=$item_rows['Items_Per_Container'];
                    $Price=$item_rows['Price'];
                    $Employee_ID=$item_rows['Employee_ID'];
                    $Expire_Date=$item_rows['Expire_Date'];
                    $batch_no=$item_rows['batch_no'];
                    $rejected=$item_rows['rejected'];
                    $Sub_Department_ID=$item_rows['Sub_Department_ID'];
                    $Supplier_ID=$item_rows['Supplier_ID'];
                    
                    
                    $sql_save_grn_without_p_order_items_result=mysqli_query($conn,"INSERT INTO tbl_grn_without_purchase_order_approval_cache_items(Purchase_Cache_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Per_Container,Price,Employee_ID,Expire_Date,batch_no,rejected,Sub_Department_ID,grn_without_id,Supplier_ID) VALUES('$Purchase_Cache_ID','$Quantity_Required','$Item_Remark','$Item_ID','$Container_Qty','$Items_Per_Container','$Price','$Employee_ID','$Expire_Date','$batch_no','$rejected','$Sub_Department_ID','$grn_without_id','$Supplier_ID')") or die(mysqli_error($conn));

                    if($sql_save_grn_without_p_order_items_result){
                        $sql_delete_inserted_item=mysqli_query($conn,"DELETE FROM tbl_grn_purchase_cache WHERE Item_ID='$Item_ID' AND Sub_Department_ID='$Sub_Department_ID' AND Supplier_ID = '$Supplier_ID' AND Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    }
                }
            }
        }
   }
   
//    }
   
   $sql_select_grn_without_id_result=mysqli_query($conn,"SELECT * FROM tbl_grn_without_purchase_order_approval_cache WHERE Sub_Department_ID='$Sub_Department_ID' AND lpo='$lpo' AND Supplier_ID='$Supplier_ID' AND Employee_ID = '$Employee_ID' ORDER BY grn_without_id DESC LIMIT 1") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_select_grn_without_id_result)>0){
       
   while($rows=mysqli_fetch_assoc($sql_select_grn_without_id_result)){
    $Supplier_ID=$rows['Supplier_ID'];
    $Sub_Department_ID = $rows['Sub_Department_ID'];
    $Debit_Note_Number = $rows['Debit_Note_Number'];
    $Invoice_Number = $rows['Invoice_Number'];   
    $Delivery_Date = $rows['Delivery_Date'];
    $RV_Number = $rows['RV_Number'];
    $lpo = $rows['lpo'];
    $grn_without_id = $rows['grn_without_id'];

  
        }
    }
    if(isset($_GET['from_approval'])){
       ?>
<a href="approve_grn_without_purchases_order.php" class="art-button-green">BACK</a>
           <?php 
    }else{
?>
<a href="grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage" class="art-button-green">BACK</a>
    <?php  } ?>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
<br/>
<br/>
<fieldset>
    <legend align="left"><b><?php echo ucwords(strtolower($Sub_Department_Name)); ?>~~APPROVE GRN WITHOUT PURCHASES ORDER</b></legend>
    <table width=100%>
                <tr>
                    <td width='10%' style='text-align: right;' id="Supplier_Name_label">Supplier Name</td>
                    <td width='25%'>
                        <select name="Supplier_ID" id="Supplier_ID">
                            
                            <?php
                            $Supplier_List = Get_Supplier_All();
                            foreach ($Supplier_List as $Supplier) {
                                if($Supplier_ID!="{$Supplier['Supplier_ID']}")continue;
                                echo "<option value='{$Supplier['Supplier_ID']}'> {$Supplier['Supplier_Name']} </option>";
                                
                            }
                            ?>
                        </select>
                    </td>
                    <td width='10%' style='text-align: right;'>Delivery Note Number</td>
                    <td width='25%'>
                        <input type='text' name='Debit_Note_Number'  id='Debit_Note_Number'
                               value='<?php if ($Debit_Note_Number != '') {
                                echo $Debit_Note_Number;
                            } ?>'  />
                    </td>
                    <td width='10%' style='text-align: right;'>Invoice Number</td>
                    <td width='25%'>
                        <input type='text' name='Invoice_Number'  id='Invoice_Number'
                               value='<?php if ($Invoice_Number != '') {
                                echo $Invoice_Number;
                            } ?>'  />
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Delivery Date</td>
                    <td >
                        <input type='text' name='Delivery_Date'  id='Delivery_Date' readonly='readonly'
                               value='<?php if ($Delivery_Date != '') {
                                echo $Delivery_Date;
                            } ?>'  />
                    </td>
                    <td style='text-align: right;'>Receiver Name</td>
                    <td >
                        <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly'
                               value='<?php if ($Employee_ID != 0 && $Employee_Name != '') {
                                echo $Employee_Name;
                            } ?>' />
                    </td>
                    <td width='10%' style='text-align: right;'>RV Number one</td>
                    <td width='25%'>
                        <input type='text' name='RV_Number'  id='RV_Number'
                               value='<?php if ($RV_Number != '') {
                                echo $RV_Number;
                            } ?>'  />
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right">LPO</td>
                    <td><input type='text' name='lpo_number'  id='lpo'
                           value='<?php if ($lpo != '') {
                            echo $lpo;
                        } ?>'  />
                      </td>
                    <td></td>
                    <td></td>
                    
                </tr>
            </table>
</fieldset>
<fieldset>
    <table class="table">
        <tr>
            <td><input type="text" placeholder="Username" id="Username" style="text-align:center" class="form-control"></td>
            <td><input type="password" placeholder="password" id="Password" style="text-align:center" class="form-control"></td>
            <td><input type="button" value="APPROVE GRN" onclick="confirm_approval()" class="art-button-green pull-right"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <?php 
        
if(isset($_GET['grn_without_id'])){
  $grn_without_id=$_GET['grn_without_id']; 
}

    echo '<center><table width = 100% border=0>';
    echo '<tr>
				<td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>

        <td>Unit Of Measure</td>
				<td ' . $display . '  style="text-align: center;">Containers</td>
				<td ' . $display . ' style="text-align: right;">Items per Container</td>
				<td width=10% style="text-align: right;">Quantity</td>
        <td width=10% style="text-align: right;">Rejected</td>
				<td width=7% style="text-align: right;">Buying Price</td>
				<td width=7% style="text-align: right;">Sub Total</td>
				<td width=7% style="text-align: right;">Batch No</td>
				<td width=10% style="text-align: right;">Expire Date</td>
				
			</tr>';
    echo "<tr><td colspan='12'><hr></td></tr>";

    $Temp = 1;
    $total = 0;
   
     $sql_select_items_result=mysqli_query($conn,"SELECT * FROM tbl_grn_without_purchase_order_approval_cache_items WHERE grn_without_id='$grn_without_id' AND Supplier_ID = $Supplier_ID AND Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_items_result)>0){
                while($item_rows=mysqli_fetch_assoc($sql_select_items_result)){
                    $Purchase_Cache_ID=$item_rows['Purchase_Cache_ID'];
                    $Quantity_Required=$item_rows['Quantity_Required'];
                    $Item_Remark=$item_rows['Item_Remark'];
                    $Item_ID=$item_rows['Item_ID'];
                    $Container_Qty=$item_rows['Container_Qty'];
                    $Items_Per_Container=$item_rows['Items_Per_Container'];
                    $Price=$item_rows['Price'];
                    $Employee_ID=$item_rows['Employee_ID'];
                    $Expire_Date=$item_rows['Expire_Date'];
                    $batch_no=$item_rows['batch_no'];
                    $rejected=$item_rows['rejected'];
                    $Sub_Department_ID=$item_rows['Sub_Department_ID'];
                    $grn_without_id=$item_rows['grn_without_id'];
                    
                    $sql_select_product_name_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                    $Product_Name=mysqli_fetch_assoc($sql_select_product_name_result)['Product_Name'];
    
        echo "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
        echo "<td><input type='text' readonly='readonly' value='" . $Product_Name . "' title='" . $Product_Name . "'></td>";
        echo "<td><input type='text' name='quantity_rejected[]' value='".unitOfMeasure($Item_ID)."'/></td>";
        ?>
        <td <?php echo $display ?> >
            <input type='text' id='Container_<?php echo $Purchase_Cache_ID; ?>' value='<?php echo $Container_Qty; ?>' style='text-align: right;width:100%' oninput="Update_Quantity('<?php echo $Purchase_Cache_ID; ?>')">
        </td>
        <td <?php echo $display ?> >
            <input type='text' id='Items_<?php echo $Purchase_Cache_ID; ?>' value='<?php echo $Items_Per_Container; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $Purchase_Cache_ID; ?>')">
        </td>
        <td>
            <input type='text' id='QR<?php echo $Purchase_Cache_ID; ?>' value='<?php echo $Quantity_Required; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $Purchase_Cache_ID; ?>)">
        </td>
        <td>
            <input type='text' id='re<?php echo $Purchase_Cache_ID; ?>' value='<?=$rejected; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $Purchase_Cache_ID; ?>)" placeholder="rejected"/>
        </td>
        <td>
            <input type='text' id='<?php echo $Purchase_Cache_ID; ?>' name='<?php echo $Purchase_Cache_ID; ?>' value='<?php echo $Price; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $Purchase_Cache_ID; ?>)">
        </td>
        <?php
        echo "<td><input type='text' name='Sub_Total" . $Purchase_Cache_ID . "' id='Sub_Total" . $Purchase_Cache_ID . "' readonly='readonly' value='" . number_format($Quantity_Required * $Price,2) . "' style='text-align: right;'></td>";
        echo "<td><input type='text' value='" . $batch_no . "'></td>";
        echo "<td><input type='text' value='" . $Expire_Date . "'></td>";
        
        ?>
       
        <?php
        echo "</tr>";
        $Temp++;
        $Grand_Total += ($row['Quantity_Required'] * $row['Price']);
    }
}
    echo "<tr><td colspan=12><hr></td></tr>";
    echo "<tr>";
    echo "<td colspan=6 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    echo "<td colspan=3 style='text-align: right;'><b>" . number_format($Grand_Total,2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=12><hr></td></tr>";
    echo '</table>';

    ?>
</fieldset>
<script>
    function confirm_approval(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        if(Supervisor_Username==""){
            $("#Username").css("border","2px solid red");
        }else if(Supervisor_Password==""){
            $("#Username").css("border","");
            $("#Password").css("border","2px solid red");
        }else{
            $("#Username").css("border","");
            $("#Password").css("border","");
           if(confirm("Are you sure you want to approve this grn?")){
            check_if_valid_user_to_approve_this_document();
        } 
      }   
    }
    function check_if_valid_user_to_approve_this_document(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var grn_without_id = '<?php echo $grn_without_id; ?>';
         $.ajax({
                        type: 'GET',
                        url: 'verify_approver_privileges_support.php',
                        data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + grn_without_id+"&document_type=grn_without_purchases_order",
                        cache: false,
                        success: function (feedback) {
                            if (feedback == 'all_approve_success') {
                                $("#remove_button_column").hide();
                                Submit_Grn();
                            }else if(feedback=="invalid_privileges"){
                                alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                            }else if(feedback=="fail_to_approve"){
                                alert("Fail to approve..please try again");  
                            }else{
                                alert(feedback);
                            }
                        }
                    });
    }
    function Submit_Grn() {
            var Supplier_ID = '<?php echo $Supplier_ID; ?>';
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var lpo  =         '<?php echo $lpo; ?>';
            var Debit_Note_Number = '<?php echo $Debit_Note_Number; ?>';
            var Invoice_Number ='<?php echo $Invoice_Number; ?>';
            var RV_Number = '<?php echo $RV_Number; ?>';
            var Delivery_Date = '<?php echo $Delivery_Date; ?>';
            var grn_without_id = '<?php echo $grn_without_id; ?>';

            var r = confirm("Are you sure you want to submit this grn?\n\nClick ok to proceed or cancel to terminate process");
            if (r == true) { 
                document.location = 'Submit_Grn_Without_Purchase_Order.php?Supplier_ID=' + Supplier_ID +
                        '&Sub_Department_ID=' + Sub_Department_ID  +
                        '&Debit_Note_Number=' + Debit_Note_Number + '&Invoice_Number=' + Invoice_Number +
                        '&Delivery_Date=' + Delivery_Date+'&RV_Number=' + RV_Number + "&lpo="+lpo+'&grn_without_id='+grn_without_id;
            }
        }
</script>
<?php
include("./includes/footer.php");
?>  