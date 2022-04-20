<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include 'procurement/procure.interface.php';

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Procurement_Works'])){
            if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{ header("Location: ./index.php?InvalidPrivilege=yes"); }
    }else{ @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes"); }

    $Procure = new ProcureInterface();$counter = 1;
    $button_words = ($_GET['lpo'] == "cancelled") ? "RETURN LPO" : "APPROVE LPO";
    $Action_Column = (sizeof($Procure->checkIfEmployeeIsAssigned($_SESSION['userinfo']['Employee_ID'],"purchase_order")) > 0) ? "" : ";display:none";
    if ((isset($_GET['From']) == "Management_Works")) {
        $url = "document_approval.php";
    } else {
        $url = "purchaseorder.php";
    }
?>
<a href="<?=$url?>?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage&lpo=<?=$_GET['lpo']?>" class="art-button-green">BACK</a>
<input type="hidden" id="status" value="<?=$_GET['lpo']?>">
<br/><br/>

<fieldset>
    <table width='100%'>
        <tr>
            <td style="width: 17%;"><input type="text" style="text-align: center;" name="start_date" id="start_date" placeholder="Start Date"/></td>
            <td style="width: 17%;"><input type="text" style="text-align: center;" name="end_date" id="end_date" placeholder="End Date"/></td>
            <td style="width: 17%;"><input type="text" style="text-align: center;" onkeyup="filterLpo()" id="lpo_number" placeholder="Requisition Number"/></td>
            <td style="width: 17%;">
                <select id="supplier" onchange="filterLpo()" style="padding: 5px;width:100%">
                    <option value="all">SELECT SUPPLIER</option>
                    <?php foreach($Procure->getAllSuppliers() as $Supplier){ ?>
                        <option value="<?=$Supplier['Supplier_ID']?>"><?=$Supplier['Supplier_Name']?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="width: 17%;">
                <select  id="store" onchange="filterLpo()" style="padding: 5px;width:100%">
                    <option value="all">SELECT STORE REQUESTING</option>
                    <?php foreach($Procure->getStoreByNature('Storage And Supply') as $Store){ ?>
                        <option value="<?=$Store['Sub_Department_ID']?>"><?=$Store['Sub_Department_Name']?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="width: 10%;">
                <a href="#" class="art-button-green" onclick="filterLpo()">FILTER</a>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 600px;overflow-x: none">
    <legend>APPROVE LOCAL PURCHASE ORDER (LPO)</legend>
    <table width='100%'>
        <tr style="background: #ddd">
            <td style='padding:8px;font-weight:500' width='5%'><center>S/No.</center></td>
            <td style='padding:8px;font-weight:500' width='11.875%'><center>STORE ORDER REQUESTING N<u>o.</u></center></td>
            <td style='padding:8px;font-weight:500' width='11.875%'><center>PURCHASE REQUESTING N<u>o.</u></center></td>
            <td style='padding:8px;font-weight:500' width='11.875%'>CREATED DATE</td>
            <td style='padding:8px;font-weight:500' width='11.875%'>SUPPLIER</td>
            <td style='padding:8px;font-weight:500' width='11.875%'>STORE REQUESTING</td>
            <td style='padding:8px;font-weight:500' width='11.875%'>CREATED BY</td>
            <td style='padding:8px;font-weight:500' width='11.875%'>PURCHASE REQUISITION DESC</td>
            <td style='padding:8px;font-weight:500<?=$Action_Column?>' width='11.875%'>ACTION</td>
        </tr>

        <tbody id='display_details'>
            <?php if(sizeof($Procure->fetchLocalPurchaseOrderList(NULL,NULL,NULL,"all","all",$_GET['lpo']))) { ?>
                <?php foreach($Procure->fetchLocalPurchaseOrderList(NULL,NULL,NULL,"all","all",$_GET['lpo']) as $details) : ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 8px;"><center><?=$counter++?></center></td>
                        <td style="padding: 8px;"><center><?=$details['Store_Order_ID']?></center></td>
                        <td style="padding: 8px;"><center><?=$details['purchase_requisition_id']?></center></td>
                        <td style="padding: 8px;"><?=$details['created_date_time']?></td>
                        <td style="padding: 8px;"><?=ucwords($details['Supplier_Name'])?></td>
                        <td style="padding: 8px;"><?=ucwords($details['Sub_Department_Name'])?></td>
                        <td style="padding: 8px;"><?=ucwords($details['Employee_Name'])?></td>
                        <td style="padding: 8px;"><?=$details['purchase_requisition_description']?></td>
                        <td style="padding: 4px;<?=(sizeof($Procure->checkIfApproved($_SESSION['userinfo']['Employee_ID'],$details['purchase_requisition_id']))> 0 || sizeof($Procure->checkIfEmployeeIsAssigned($_SESSION['userinfo']['Employee_ID'],"purchase_order")) < 1) ? "display:none" : ""?>"><a href='approve_selected_local_purchase_order.php?purchase_requisition_id=<?=$details['purchase_requisition_id']?>&lpo=<?=$_GET['lpo']?>' class='art-button-green'><?=$button_words?><?=$show?></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php } else { ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 8px;" colspan="9"><center>NO PURCHASE FOUND</center></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</fieldset>

<script>
    function filterLpo(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var lpo_number = $('#lpo_number').val();
        var supplier = $('#supplier').val();
        var store = $('#store').val();
        var status = $('#status').val();
        var Employee_ID = '<?=$_SESSION['userinfo']['Employee_ID']?>';


        if(start_date == "" || start_date == null){
            $('#start_date').css('border','1px solid red');
            exit();
        }
        $('#start_date').css('border','1px solid #ccc');

        if(end_date == "" || end_date == null){
            $('#end_date').css('border','1px solid red');
            exit();
        }
        $('#end_date').css('border','1px solid #ccc');

        $.ajax({
            type: "GET",
            url: "procurement/procure.common.php",
            data: {
                start_date:start_date,
                end_date:end_date,
                lpo_number:lpo_number,
                supplier:supplier,
                store:store,
                status:status,
                Employee_ID:Employee_ID,
                request:'filter_lpo'
            },
            success: (response) => {
                $('#display_details').html(response);
            }
        });
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>

<?php include('./includes/footer.php'); ?>
