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

    $status = $_GET['pr'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Procure = new ProcureInterface();
    $counter = 1;
    $button_words = ($_GET['pr'] == "cancelled") ? "RETURN PR" : "APPROVE PR";
    $Action_Column = (sizeof($Procure->checkIfEmployeeIsAssigned($_SESSION['userinfo']['Employee_ID'],"purchase_requisition")) > 0) ? "" : ";display:none";
    if ((isset($_GET['From']) == "Management_Works")) {
        $url = "document_approval.php";
    } else {
        $url = "purchaseorder.php";
    }
?>

<a href="<?=$url?>?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green">BACK</a>
<br/><br/>

<fieldset>
    <table width='100%'>
        <tr>
            <td style="width: 17%;"><input type="text" style="text-align: center;" name="start_date" id="start_date" placeholder="Start Date"/></td>
            <td style="width: 17%;"><input type="text" style="text-align: center;" name="end_date" id="end_date" placeholder="End Date"/></td>
            <td style="width: 17%;"><input type="text" style="text-align: center;" onkeyup="filterOrderNumber()" id="order_requisition" placeholder="Purchase Requisition No."/></td>
            <td style="width: 17%;">
                <input type="hidden" id="status" value="<?=$_GET['pr']?>">
                <select id="supplier" onchange="filterOrderNumber()" style="padding: 5px;width:100%">
                    <option value="all">SELECT SUPPLIER</option>
                    <?php foreach($Procure->getAllSuppliers() as $Supplier){ ?>
                        <option value="<?=$Supplier['Supplier_ID']?>"><?=ucwords($Supplier['Supplier_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="width: 17%;">
                <select  id="store" onchange="filterOrderNumber()" style="padding: 5px;width:100%">
                    <option value="all">SELECT STORE REQUESTING</option>
                    <?php foreach($Procure->getStoreByNature('Storage And Supply') as $Store){ ?>
                        <option value="<?=$Store['Sub_Department_ID']?>"><?=ucwords($Store['Sub_Department_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="width: 10%;">
                <a href="#" class="art-button-green" onclick="filterOrderNumber()">FILTER</a>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 550px;overflow-y: scroll;overflow-x: none">
    <legend>LIST OF PURCHASE REQUISITION TO BE APPROVED</legend>
    <table class="table" style="background: #FFFFFF">
        <tr style="background: #eee">
            <td style='width: 1%'><center><b>S/No.</b></center></td>
            <td width='11%'><center><b>ORDER REQUESTING N<u>o.</u></b></center></td>
            <td width='11%'><center><b>PURCHASE REQUESTING N<u>o.</u></b></center></td>
            <td width='12%'><b>CREATED DATE</b></td>
            <td width='14%'><b>SUPPLIER</b></td>
            <td width='13%'><b>STORE REQUESTING</b></td>
            <td width='13%'><b>CREATED BY</b></td>
            <td width='15%'><b>PURCHASE REQUESTING DESCRIPTION</b></td>
            <td width='15%'><b>REFERENCE DOCUMENT</b></td>
            <td width='10%' style='text-align:center<?=$Action_Column?>'><b>ACTION</b></td>
        </tr>
        <tbody id="display_details">
            <?php if(sizeof($Procure->getPurchaseOrderList($status,NULL,NULL,NULL,'all','all'))){  ?>
            <?php foreach($Procure->getPurchaseOrderList($status,NULL,NULL,NULL,'all','all') as $details){ ?>
                <tr>
                    <td style="text-align: center;"><?= $counter++ ?></td>
                    <td style="text-align: center;"><?= $details['Store_Order_ID']?></td>
                    <td style="text-align: center;"><?= $details['purchase_requisition_id']?></td>
                    <td ><?= $details['created_date_time']?></td>
                    <td ><?= ucwords($details['Supplier_Name'])?></td>
                    <td ><?= ucwords($details['Sub_Department_Name'])?></td>
                    <td ><?= ucwords($details['Employee_Name'])?></td>
                    <td ><?= $details['purchase_requisition_description']?></td>
                    <td ><?= ($details['reference_document'] == "" ? " <span style='color:green;font-weight:500'>No Reference Document</span> " : " <a href='attachment/{$details['reference_document']}' target='_blank' class='art-button-green'>PREVIEW</a> ")?></td>
                    <td style="text-align:center;<?=$Action_Column?>"><?= (sizeof($Procure->getDocumentIfApproved($Employee_ID,'purchase_requisition',$details['purchase_requisition_id'])) > 0 ) ? " <span>APPROVED</span> " : " <a href='approve_selected_purchase_requisition.php?purchase_requisition_id={$details['purchase_requisition_id']}&pr=".$status."' class='art-button-green'>{$button_words}</a> " ?></td>
                </tr>
            <?php } ?>
            <?php }else{ ?>
                <tr style="background-color: #fff;">
                    <td style="text-align: center;padding:8px" colspan="10">NO LPO FOUND</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</fieldset>

<script>
    function filterOrderNumber(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var order_requisition = $('#order_requisition').val();
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
                order_requisition:order_requisition,
                supplier:supplier,
                store:store,
                status:status,
                Employee_ID:Employee_ID,
                request:'filter_purchase_requisition'
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

<?= include('./includes/footer.php'); ?>

