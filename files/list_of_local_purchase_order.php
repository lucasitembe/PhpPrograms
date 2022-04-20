<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include 'procurement/procure.interface.php';

    if(!isset($_SESSION['userinfo'])){ @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes"); }
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Procurement_Works'])){
            if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes"); }
            }else{ header("Location: ./index.php?InvalidPrivilege=yes"); }
    }else{
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Procure = new ProcureInterface();
    $count = 1;
?>
<a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green">BACK</a>
<br/><br/>

<fieldset style="background-color: #fff;">
    <table width='100%' >
        <tr>
            <td width='15.8%'><input type="text" id="Start_Date" placeholder="Start Date" style="text-align: center;"></td>
            <td width='15.8%'><input type="text" id="End_Date" placeholder="End Date" style="text-align: center;"></td>
            <td width='15.8%'><input type="text" id="Requisition_No" placeholder="Order No" style="text-align: center;" onkeyup="filterApprovedPR()"></td>
            <td width='15.8%'><input type="text" id="Purchase_Requisition_No" placeholder="Purchase Requisition No" onkeyup="filterApprovedPR()" style="text-align: center;"></td>
            <td width='15.8%'>
                <select style="width: 100%;padding:5px;text-align:center" id="Supplier_ID" onchange="filterApprovedPR()">
                    <option value="all">All Supplier</option>
                    <?php foreach($Procure->getAllSuppliers() as $Supplier){ ?>
                        <option value="<?=$Supplier['Supplier_ID']?>"><?=ucfirst($Supplier['Supplier_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td width='15.8%'>
                <select style="width: 100%;padding:5px;text-align:center" id="Requesting_Store_ID" onchange="filterApprovedPR()">
                    <option value="all">Requesting Store</option>
                    <?php foreach($Procure->getStoreByNature('Storage And Supply') as $Store){ ?>
                        <option value="<?=$Store['Sub_Department_ID']?>"><?=ucfirst($Store['Sub_Department_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td width='8%'><center><a href="#" class="art-button-green" onclick="filterApprovedPR()">FILTER</a></center></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 550px;overflow-y: scroll;overflow-x: none">
    <legend align='center' style="font-weight: 500;">LIST OF APPROVED LOCAL PURCHASE ORDER</legend>
    <table class="table" style="background: #FFFFFF">
        <tr style="background: #ddd">
            <td style='width: 5%;padding:5px;font-weight:500'><center>S/No.</center></td>
            <td style="padding: 8px;font-weight:500" width='10%'><center>STORE ORDER REQUISITION N<u>o.</u></center></td>
            <td style="padding: 8px;font-weight:500" width='9%'><center>PURCHASE REQUISITION N<u>o.</u></center></td>
            <td style="padding: 8px;font-weight:500" width='9%'><center>LOCAL PURCHASE ORDER N<u>o.</u></center></td>
            <td style="padding: 8px;font-weight:500" width='9%'>CREATED DATE</td>
            <td style="padding: 8px;font-weight:500" width='9%'>SUPPLIER</td>
            <td style="padding: 8px;font-weight:500" width='9%'>STORE REQUESTING</td>
            <td style="padding: 8px;font-weight:500" width='9%'>CREATED BY</td>
            <td style="padding: 8px;font-weight:500" width='9%'>PURCHASE REQUISITION DESC</td>
            <td style="padding: 8px;font-weight:500" width='9%'>ACTION</td>
        </tr>

        <tbody id="Response_Display">
            <?php foreach($Procure->getApprovedLpo(null,null,null,null,null,null) as $LPO) : ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 8px;"><center><?=$count++?></center></td>
                    <td style="padding: 8px;"><center><?=$LPO['Store_Order_ID']?></center></td>
                    <td style="padding: 8px;"><center><?=$LPO['purchase_requisition_id']?></center></td>
                    <td style="padding: 8px;"><center><?=$LPO['local_purchase_order_id']?></center></td>
                    <td style="padding: 8px;"><?=$LPO['created_date_time']?></td>
                    <td style="padding: 8px;"><?=ucfirst($LPO['Supplier_Name'])?></td>
                    <td style="padding: 8px;"><?=ucfirst($LPO['Sub_Department_Name'])?></td>
                    <td style="padding: 8px;"><?=ucfirst($LPO['Employee_Name'])?></td>
                    <td style="padding: 8px;"><?=ucfirst($LPO['purchase_requisition_description'])?></td>
                    <td style="padding: 5px;"><a  href='preview_selected_local_purchase_order.php?purchase_requisition_id=<?=$LPO['purchase_requisition_id']?>&local_purchase_order_id=<?=$LPO['local_purchase_order_id']?>' class='art-button-green' target='_blank'class="art-button-green">PREVIEW</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<script>
    function filterApprovedPR(){
        var Start_Date = $('#Start_Date').val();
        var End_Date = $('#End_Date').val();
        var Requisition_No = $('#Requisition_No').val();
        var Purchase_Requisition_No = $('#Purchase_Requisition_No').val();
        var Supplier_ID = $('#Supplier_ID').val();
        var Requesting_Store_ID = $('#Requesting_Store_ID').val();

        if(Start_Date == "" || Start_Date == null){
            $('#Start_Date').css('border','1px solid red');
            exit();
        }
        $('#Start_Date').css('border','1px solid #ccc');

        if(End_Date == "" || End_Date == null){
            $('#End_Date').css('border','1px solid red');
            exit();
        }
        $('#End_Date').css('border','1px solid #ccc');

        $.ajax({
            type: "GET",
            url: "procurement/procure.common.php",
            data: {
                Start_Date:Start_Date,
                End_Date:End_Date,
                Requisition_No:Requisition_No,
                Purchase_Requisition_No:Purchase_Requisition_No,
                Supplier_ID:Supplier_ID,
                Requesting_Store_ID:Requesting_Store_ID,
                filter_approve_lpo_report:'filter_approve_lpo_report'
            },
            success: (response) => {
                $('#Response_Display').html(response);
            }
        });
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<?php include('./includes/footer.php'); ?>

