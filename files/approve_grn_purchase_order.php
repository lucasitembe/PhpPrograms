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
<?php 
    if($_GET['from_procurement'] == "yes"){
        echo "<a href='procurement-recieving.php' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";
    }
?>
<br/><br/>
<fieldset style="height: 450px;overflow-y: auto">
    <legend align="center">
        <b>APPROVE GRN AGAINST PURCHASE ORDER</b>
    </legend>
    <table class="table table-bordered" style="background: #FFFFFF">
        <tr style="background-color: #ddd;">
            <td style="font-weight: bold;" width="5%"><center>S/No.</center></td>
            <td style="font-weight: bold;" width="6%"><center>LPO No.</center></td>
            <td style="font-weight: bold;" width="15%">Employee Receiving</td>
            <td style="font-weight: bold;" width="14%">Store</td>  
            <td style="font-weight: bold;" width="15%">Supplier</td>
            <td style="font-weight: bold;" width="10%"><center>Delivery Note No.</center></td>
            <td style="font-weight: bold;" width="10%"><center>Invoice No.</center></td>
            <td style="font-weight: bold;" width="10%">Delivery Date</td>
            <td style="font-weight: bold;" width="10%">Delivery Person</td>
            <td style="font-weight: bold;" width="10%">Action</td>
        </tr>
        <?php 
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
            $verify = (isset($_GET['from_procurement'])) ? "yes" : "no";

            if($verify == "yes"){
                $sql ="SELECT glpoc.local_purchase_order_id,glpoc.grn_local_purchase_order_cache_id,glpoc.Delivery_Note_Number,glpoc.Invoice_Number,glpoc.Delivery_Date,glpoc.Delivery_Person,glpoc.RV_Number,emp.Employee_Name,sub.Sub_Department_Name,sp.Supplier_Name FROM tbl_grn_local_purchase_order_cache glpoc,tbl_employee emp,tbl_sub_department sub,tbl_supplier sp WHERE glpoc.receiver_Employee_ID=emp.Employee_ID AND glpoc.Sub_Department_ID=sub.Sub_Department_ID AND glpoc.Supplier_ID=sp.Supplier_ID";
            }else{
                $sql ="SELECT glpoc.local_purchase_order_id,glpoc.grn_local_purchase_order_cache_id,glpoc.Delivery_Note_Number,glpoc.Invoice_Number,glpoc.Delivery_Date,glpoc.Delivery_Person,glpoc.RV_Number,emp.Employee_Name,sub.Sub_Department_Name,sp.Supplier_Name FROM tbl_grn_local_purchase_order_cache glpoc,tbl_employee emp,tbl_sub_department sub,tbl_supplier sp WHERE glpoc.receiver_Employee_ID=emp.Employee_ID AND glpoc.Sub_Department_ID=sub.Sub_Department_ID AND glpoc.Supplier_ID=sp.Supplier_ID AND glpoc.Sub_Department_ID='$Sub_Department_ID'";
            }

            $sql_select_grn_purchase_order_result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_grn_purchase_order_result)>0){
                $count=1;
                while($grn_purchase_order_rows=mysqli_fetch_assoc($sql_select_grn_purchase_order_result)){
                    $grn_local_purchase_order_cache_id=$grn_purchase_order_rows['grn_local_purchase_order_cache_id'];
                    $Delivery_Note_Number=$grn_purchase_order_rows['Delivery_Note_Number'];
                    $Invoice_Number=$grn_purchase_order_rows['Invoice_Number'];
                    $Delivery_Date=$grn_purchase_order_rows['Delivery_Date'];
                    $Delivery_Person=$grn_purchase_order_rows['Delivery_Person'];
                    $RV_Number=$grn_purchase_order_rows['RV_Number'];
                    $Employee_Name=$grn_purchase_order_rows['Employee_Name'];
                    $Sub_Department_Name=$grn_purchase_order_rows['Sub_Department_Name'];
                    $Supplier_Name=$grn_purchase_order_rows['Supplier_Name'];
                    $local_purchase_order_id=$grn_purchase_order_rows['local_purchase_order_id'];

                    echo "<tr>
                            <td style='text-align:center'>$count</td>
                            <td style='text-align:center'>$local_purchase_order_id</td>
                            <td style='text-align:left'>$Employee_Name</td>
                            <td style='text-align:left'>$Sub_Department_Name</td>
                            <td style='text-align:left'>$Supplier_Name</td>
                            <td style='text-align:center'>$Delivery_Note_Number</td>
                            <td style='text-align:center'>$Invoice_Number</td>
                            <td style='text-align:left'>$Delivery_Date</td>
                            <td style='text-align:left'>$Delivery_Person</td>
                            <td style='text-align:center'>
                                <a href='approve_grn_purchase_order_items.php?grn_local_purchase_order_cache_id=$grn_local_purchase_order_cache_id&from_procurement=$verify' class='art-button-green'>Approve</a>
                            </td>
                        </tr>";
                        $count++;
                }
            }
        ?>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>
