<?php
@session_start();
include("./includes/header.php");
include("./includes/connection.php");
include("return_unit_of_measure.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy(); 
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }if ($_SESSION['userinfo']['can_edit'] != 'yes' && isset($_GET['src']) && $_GET['src'] = 'edit') {
            header("Location: ./previousgrnlist.php?PreviousGrn=PreviousGrnThisPage");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href="grnpurchaseorder.php" class="art-button-green">BACK</a>
<br/>
<br/>
<fieldset style="height:450px;overflow-y: scroll;">
    <legend align='center'><b>LIST OF APPROVED GRN AGAINST PURCHASE ORDER</b></legend>
    <table class="table" style="background: #FFFFFF">
        <tr>
            <td><b>S/No.</b></td>
            <td><b>GRN No.</b></td>
            <td><b>LPO No.</b></td>
            <td><b>Created Date</b></td>
            <td><b>Employee Creating</b></td>
            <td><b>Supplier</b></td>
            <td><b>Delivery Note No.</b></td>
            <td><b>Invoice No.</b></td>
            <td><b>Delivery Date</b></td>
            <td><b>Delivery Person</b></td>
            <td><b>RV Number</b></td>
            <td><b>Action</b></td>
        </tr>
        <?php
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
            $sql_select_grn_purchase_order_result=mysqli_query($conn,"SELECT gpo.Grn_Purchase_Order_ID,gpo.Created_Date_Time,emp.Employee_Name,sup.Supplier_Name,gpo.Purchase_Order_ID,gpo.Debit_Note_Number,gpo.Invoice_Number,gpo.Delivery_Date,gpo.Delivery_Person,gpo.RV_Number FROM 
                    
            tbl_grn_purchase_order gpo,tbl_employee emp,tbl_supplier sup WHERE 
            
            gpo.Employee_ID=emp.Employee_ID AND gpo.supplier_id=sup.Supplier_ID AND Sub_Department_ID='$Sub_Department_ID' ORDER BY gpo.Grn_Purchase_Order_ID DESC
            ") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_grn_purchase_order_result)>0){
                $count=1;
                while($grn_purchase_rows=mysqli_fetch_assoc($sql_select_grn_purchase_order_result)){
                    $Grn_Purchase_Order_ID=$grn_purchase_rows['Grn_Purchase_Order_ID'];
                    $Created_Date_Time=$grn_purchase_rows['Created_Date_Time'];
                    $Employee_Name=$grn_purchase_rows['Employee_Name'];
                    $Supplier_Name=$grn_purchase_rows['Supplier_Name'];
                    $Purchase_Order_ID=$grn_purchase_rows['Purchase_Order_ID'];
                    $Debit_Note_Number=$grn_purchase_rows['Debit_Note_Number'];
                    $Invoice_Number=$grn_purchase_rows['Invoice_Number'];
                    $Delivery_Date=$grn_purchase_rows['Delivery_Date'];
                    $Delivery_Person=$grn_purchase_rows['Delivery_Person'];
                    $RV_Number=$grn_purchase_rows['RV_Number'];
                    echo "
                            <tr>
                                <td>$count.</td>
                                <td>$Grn_Purchase_Order_ID</td>
                                <td>$Purchase_Order_ID</td>
                                <td>$Created_Date_Time</td>
                                <td>$Employee_Name</td>
                                <td>$Supplier_Name</td>
                                <td>$Debit_Note_Number</td>
                                <td>$Invoice_Number</td>
                                <td>$Delivery_Date</td>
                                <td>$Delivery_Person</td>
                                <td>$RV_Number</td>
                                <td>
                                    <a href='selected_approved_grn_report.php?Grn_Purchase_Order_ID=$Grn_Purchase_Order_ID' target='_blank' class='art-button-green'>PREVIEW</a>
                                </td>
                            </tr>
                         ";
                    $count++;
                }
            }
        ?>
    </table>
</fieldset>