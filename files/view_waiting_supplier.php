<?php 
    include("./includes/header.php");
    include("./includes/connection.php");

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

<style>
    .custom-table td{
        border: none !important;
        border-bottom: 1px solid #ddd !important;
    }
</style>

<a href="requisition_without_supplier.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

<br><br>

<fieldset>
    <legend>Purchase Requisition ~ (PR) Waiting Supplier</legend>

    <table style="width: 100%;" class="custom-table">
        <tbody>
            <tr style="background-color: #ccc;">
                <td style="padding: 8px;" width='5%'><center>S/No</center></td>
                <td style="padding: 8px;" width='10%'><center>SOR No</center></td>
                <td style="padding: 8px;" width='15%'><center>PR No</center></td>
                <td style="padding: 8px;" width='15%'>Created Date</td>
                <td style="padding: 8px;" width='15%'>Store Requesting</td>
                <td style="padding: 8px;" width='15%'>Created By</td>
                <td style="padding: 8px;" width='20%'>PR Description</td>
            </tr>

            <?php 
                $purchase_requisition_id = $_GET['purchase_requisition_id'];
                $Query = "SELECT reference_document,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.purchase_requisition_id = '$purchase_requisition_id' AND pr.Supplier_ID=0 AND pr.store_requesting=sd.Sub_Department_ID AND pr.pr_status='waiting_supplier' ORDER BY purchase_requisition_id DESC";

                $sql_select_purchase_requisition_result=mysqli_query($conn,$Query) or die(mysqli_error($conn));
                $data = mysqli_fetch_assoc($sql_select_purchase_requisition_result);
                $Sub_Department_ID = $data['Sub_Department_ID'];

                echo "<tr style='background-color: #fff;'>
                    <td style='padding: 8px;' width='5%'><center>1</center></td>
                    <td style='padding: 8px;' width='10%'><center>".$data['Store_Order_ID']."</center></td>
                    <td style='padding: 8px;' width='15%'><center>".$purchase_requisition_id."</center></td>
                    <td style='padding: 8px;' width='15%'>".$data['created_date_time']."</td>
                    <td style='padding: 8px;' width='15%'>".$data['Sub_Department_Name']."</td>
                    <td style='padding: 8px;' width='15%'>".$data['Employee_Name']."</td>
                    <td style='padding: 8px;' width='20%'>".$data['purchase_requisition_description']."</td>
                </tr>";
            ?>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <table style="width: 100%;" class="custom-table">
        <tbody>
            <tr style="background-color: #ccc;">
                <td style="padding: 8px;" width='5%'><center>No</center></td>
                <td style="padding: 8px;" width='5%'><center>Folio Number</center></td>
                <td style="padding: 8px;" width='%'>Item Name</td>
                <td style="padding: 8px;text-align:center;" width='10%'>Package</td>
                <td style="padding: 8px;text-align:center" width='10%'>Items per Container</td>
                <td style="padding: 8px;text-align:center" width='10%'>Quantity</td>
                <td style="padding: 8px;text-align:center" width='10%'>Balance</td>
                <td style="padding: 8px;text-align:end" width='10%'>Buying Price</td>
                <td style="padding: 8px;text-align:end" width='10%'>Sub Total</td>
            </tr>

            <?php 
                $Temp=1; $total = 0;$grand_total=0;
                $sql_select_purchase_requisition_items=mysqli_query($conn,"SELECT Item_ID,container_quantity,item_per_container,quantity_required,buying_price FROM tbl_purchase_requisition_items WHERE purchase_requisition_id='$purchase_requisition_id' AND item_status='active'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_purchase_requisition_items)>0){
                    while($pr_items_rows=mysqli_fetch_assoc($sql_select_purchase_requisition_items)){
                        $Item_ID=$pr_items_rows['Item_ID'];
                        $container_quantity=$pr_items_rows['container_quantity'];
                        $item_per_container=$pr_items_rows['item_per_container'];
                        $quantity_required=$pr_items_rows['quantity_required'];
                        $buying_price=$pr_items_rows['buying_price'];

                        $sub_total=0;
                            $get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $n_get = mysqli_num_rows($get_balance);
                            if($n_get > 0){
                                while ($nget = mysqli_fetch_array($get_balance)) {
                                        $Item_Balance = $nget['Item_Balance'];
                                }
                            }else{
                                $Item_Balance = 0;
                            }
            
                            $sql_select_item_detail_result=mysqli_query($conn,"SELECT Product_Name,item_folio_number FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_item_detail_result)>0){
                                while($items_rows=mysqli_fetch_assoc($sql_select_item_detail_result)){
                                    $Product_Name=$items_rows['Product_Name'];
                                    $item_folio_number=$items_rows['item_folio_number'];
                                }
                            }
                            $sub_total=$buying_price*$quantity_required;
                            $folio = ($item_folio_number != "") ? $item_folio_number : "-";

                        echo '<tr style="background-color: #fff;">
                            <td style="padding: 8px;"><center>'.$Temp++.'</center></td>
                            <td style="padding: 8px;"><center>'.$folio.'</center></td>
                            <td style="padding: 8px;">'.$Product_Name.'</td>
                            <td style="padding: 8px;text-align:center">'.$container_quantity.'</td>
                            <td style="padding: 8px;text-align:center">'.$item_per_container.'</td>
                            <td style="padding: 8px;text-align:center">'.$quantity_required.'</td>
                            <td style="padding: 8px;text-align:center">'.$Item_Balance.'</td>
                            <td style="padding: 8px;text-align:end"><input style="text-align:end" id="item'.$Item_ID.'" onkeyup="update_price('.$Item_ID.')" value="'.$buying_price.'"/></td>
                            <td style="padding: 8px;text-align:end">'.$sub_total.'</td>
                        </tr>';
                        $grand_total+=$sub_total;
                    }
                    echo "
                        <tr style='background-color: #ccc;'>
                            <td colspan='8' style='text-align:end;padding:8px;font-weight:bold'>Grand Total : </td>
                            <td style='text-align:end;padding:8px;font-weight:bold'>".number_format($grand_total)."/=</td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <div class="sections" style="grid-template-columns: 1fr 1fr 1.5fr;gap:1em;display:grid">
        <div></div>
        <div></div>
        <div>
            <select id="Supplier_ID" style="width:83%;padding:4px">
                <option value='' selected='selected'>Select Supplier</option>";
                <?php $select_Supplier = mysqli_query($conn,"SELECT * FROM tbl_supplier") or die(mysqli_error($conn));
                        while($row = mysqli_fetch_array($select_Supplier)){
                        echo "<option value='".$row['Supplier_ID']."'>".ucfirst($row['Supplier_Name'])."</option>";
                    }
                ?>
            </select>
            <a href="#" onclick="submit_supplier()" class="art-button-green">SUBMIT</a>
        </div>
    </div>
</fieldset>

<script>
    function submit_supplier() {  
        var Supplier_ID = $('#Supplier_ID').val();
        var purchase_requisition_id = <?=$_GET['purchase_requisition_id']?>;
        var submit_supplier = 'submit_supplier';

        if(Supplier_ID == null || Supplier_ID == ""){
            alert('Please Select Supplier')
        }else{
            if(confirm('Are you sure you want update this document ?')){
                $.post('procument-core.php',{
                    submit_supplier:submit_supplier,
                    Supplier_ID:Supplier_ID,
                    purchase_requisition_id:purchase_requisition_id
                },(response) => {
                    if(response == 1){
                        window.location.replace('requisition_without_supplier.php');
                    }
                });
            }
        }
    }

    function update_price(Item_Id){
        var Items_Price = $('#item'+Item_Id).val();
        var purchase_requisition_id = '<?=$_GET['purchase_requisition_id']?>';

        $.post('procument-core.php',{
            Item_Id:Item_Id,
            Items_Price:Items_Price,
            purchase_requisition_id:purchase_requisition_id
        },(response) => {
            console.log(response);
        });
    }
</script>

<?php 
    include("./includes/connection.php");
?>