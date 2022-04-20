<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
//get employee id 
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_ID = '';
    $Sub_Department_Name = '';
}
?>

<style>
    table,tr,td{ border-collapse:collapse !important; border:none !important; }
    tr:hover{ background-color:#eeeeee; cursor:pointer; }
</style>
<a href="grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage" class="art-button-green">BACK</a>
<br/><br/>
<fieldset>
    <legend><?= $Sub_Department_Name ?><b>~~LIST OF GRN TO APPROVE</b></legend>
    <table class="table" style="background: #FFFFFF">
        <tr>
            <td style="width:50px">S/No.</td>
            <td>Supplier Name</td>
            <td>LPO</td>
            <td>Debit Note Number</td>
            <td>Invoice Number</td>
            <td>RV Number</td>
            <td>Delivery Date</td>
        </tr>
        <tr>
            <td colspan="7"><hr/></td>
        </tr>
            <?php 
                 $sql_select_grn_without_id_result=mysqli_query($conn,"SELECT  *FROM tbl_grn_without_purchase_order_approval_cache WHERE Sub_Department_ID='$Sub_Department_ID' GROUP BY grn_without_id ORDER BY grn_without_id DESC") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_grn_without_id_result)>0){
                $count=1;
                while($rows=mysqli_fetch_assoc($sql_select_grn_without_id_result)){
                 $Supplier_ID=$rows['Supplier_ID'];
                 $Sub_Department_ID = $rows['Sub_Department_ID'];
                 $Debit_Note_Number = $rows['Debit_Note_Number'];
                 $Invoice_Number = $rows['Invoice_Number'];   
                 $Delivery_Date = $rows['Delivery_Date'];
                 $RV_Number = $rows['RV_Number'];
                 $lpo = $rows['lpo'];
                 $grn_without_id = $rows['grn_without_id'];
                    
                    $sql_select_Supplier_Name_result=mysqli_query($conn,"SELECT Supplier_Name FROM tbl_supplier WHERE Supplier_ID='$Supplier_ID'") or die(mysqli_error($conn));
                    $Supplier_Name=mysqli_fetch_assoc($sql_select_Supplier_Name_result)['Supplier_Name'];
                    $link_head="<a href='Submit_Grn_Without_Purchase_Order_for_approval.php?Supplier_ID=$Supplier_ID&Sub_Department_ID=$Sub_Department_ID&Debit_Note_Number=$Debit_Note_Number&Invoice_Number=$Invoice_Number&Delivery_Date=$Delivery_Date&RV_Number=$RV_Number&lpo=$lpo&from_approval&grn_without_id=$grn_without_id'><b>";
                    $link_tail="</b></a>";
                 echo "<tr> 
                            <td>$link_head $count $link_tail</td>
                            <td>$link_head $Supplier_Name $link_tail</td>
                            <td>$link_head $lpo $link_tail</td>
                            <td>$link_head $Debit_Note_Number $link_tail</td>
                            <td>$link_head $Invoice_Number $link_tail</td>
                            <td>$link_head $RV_Number $link_tail</td>
                            <td>$link_head $Delivery_Date $link_tail</td>
                            </a>
                        </tr>";
                 $count++;
                }
            }
            ?>
        </tr>
    </table>
</fieldset>