<?php
    include './includes/connection.php';
    include './includes/header.php';

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Procurement_Works'])){
	    if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
    <a href="approve_grn_purchase_order.php?from_procurement=yes&from=purchaseOrder" style="font-family: Arial, Helvetica, sans-serif;" class='art-button-green'>APPROVE GRN AGAINST PURCHASE ORDER</a>
    <a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" style="font-family: Arial, Helvetica, sans-serif;" class='art-button-green'>BACK</a>
    <br><br>

    <fieldset style="height: 700px;overflow-y:scroll">
        <legend>LIST OF GRN AGAINST PURCHASE ORDERS</legend>

        <table class='table'>
            <thead>
                <tr style="background-color: #ccc;">
                    <td width='3%'><b>S/No.</b></td>
                    <td width='9%'><center><b>SOR N<u>o.</u></b></center></td>
                    <td width='9%'><center><b>PR N<u>o.</u></b></center></td>
                    <td width='9%'><center><b>LPO N<u>o.</u></b></center></td>
                    <td width='12%'><b>Created Date</b></td>
                    <td width='15%'><b>Supplier</b></td>
                    <td width='15%'><b>Purchase Requisition Description</b></td>
                    <td width='5%'><b>Action</b></td>
                </tr>
            </thead>
            <?php 
                $Sub_Department_ID=$_SESSION['Storage_Info']['Sub_Department_ID'];
                $sql_select_purchase_requisition_result=mysqli_query($conn,"SELECT lpo.local_purchase_order_id,lpo.submitted_for_grn_approval_status,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.submitted_for_grn_approval_status IN('not_submitted','outstanding','pending') GROUP BY purchase_requisition_id ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));


                $invioce = "";
                if(mysqli_num_rows($sql_select_purchase_requisition_result)>0){
                    $count=1;
                    while($lpo_rows=mysqli_fetch_assoc($sql_select_purchase_requisition_result)){
                        $Store_Order_ID=$lpo_rows['Store_Order_ID'];
                        $purchase_requisition_description=$lpo_rows['purchase_requisition_description'];
                        $created_date_time=$lpo_rows['created_date_time'];
                        $Employee_Name=$lpo_rows['Employee_Name'];
                        $Supplier_Name=$lpo_rows['Supplier_Name'];
                        $purchase_requisition_id=$lpo_rows['purchase_requisition_id'];
                        $local_purchase_order_id=$lpo_rows['local_purchase_order_id'];
                        $Sub_Department_Name=$lpo_rows['Sub_Department_Name'];
                        $Sub_Department_ID=$lpo_rows['Sub_Department_ID'];
                        $Purchase_Type = $lpo_rows['Purchase_Type'];

                        $submitted_for_grn_approval_status = $lpo_rows['submitted_for_grn_approval_status'];

                        if($submitted_for_grn_approval_status == "outstanding"){
                            $style = 'background-color:#7eb9dc;';
                        }else{
                            $style = 'background-color:#fff;';
                        }

                    echo "<tr style='$style;font-size:14px'>
                                <td><center>$count.</center></td>
                                <td><center>$Store_Order_ID</center></td>
                                <td><center>$purchase_requisition_id</center></td>
                                <td><center>$local_purchase_order_id</center></td>
                                <td>$created_date_time</td>
                                <td>$Supplier_Name</td>
                                <td>$purchase_requisition_description</td>
                                <td><a href='grnpurchaseorder.php?purchase_requisition_id=$purchase_requisition_id&local_purchase_order_id=$local_purchase_order_id&from=procurement' class='art-button-green'>Process</a></td>
                            </tr>";
                    $count++;
                    }
                }
            ?>
        </table>
    </fieldset>

<?php 
    include './includes/footer.php';
?>