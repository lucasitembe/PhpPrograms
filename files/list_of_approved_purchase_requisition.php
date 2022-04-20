<?php
    include("./includes/header.php");
    include 'procurement/procure.interface.php';

    if(!isset($_SESSION['userinfo'])){ @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes"); }
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Procurement_Works'])){
            if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes"); }
            }else{ header("Location: ./index.php?InvalidPrivilege=yes"); }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Procure = new ProcureInterface();
    $count = 1;
?>
<a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green">BACK</a>
<br/>
<br/>

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
                        <option value="<?=$Supplier['Supplier_ID']?>"><?=ucwords($Supplier['Supplier_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td width='15.8%'>
                <select style="width: 100%;padding:5px;text-align:center" id="Requesting_Store_ID" onchange="filterApprovedPR()">
                    <option value="all">Requesting Store</option>
                    <?php foreach($Procure->getStoreByNature('Storage And Supply') as $Store){ ?>
                        <option value="<?=$Store['Sub_Department_ID']?>"><?=ucwords($Store['Sub_Department_Name'])?></option>
                    <?php } ?>
                </select>
            </td>
            <td width='8%'><center><a href="#" class="art-button-green" onclick="filterApprovedPR()">FILTER</a></center></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 550px;overflow-y: scroll;overflow-x: none">
    <legend align='left' style="font-weight: 500;">LIST OF APPROVED PURCHASE REQUISITION</legend>
    <table class="table" >
        <tr style="background: #ddd;font-weight:500">
            <td style="padding: 8px;" width='5%'><center>S/No.</center></td>
            <td style="padding: 8px;" width='11.875%'><center>STORE ORDER N<u>o.</u></center></td>
            <td style="padding: 8px;" width='11.875%'><center>PURCHASE REQUISITION N<u>o.</u></center></td>
            <td style="padding: 8px;" width='11.875%'>CREATED DATE</td>
            <td style="padding: 8px;" width='11.875%'>SUPPLIER</td>
            <td style="padding: 8px;" width='11.875%'>STORE REQUEST</td>
            <td style="padding: 8px;" width='11.875%'>CREATED BY</td>
            <td style="padding: 8px;" width='11.875%'>PURCHASE REQUISITION DESC</td>
            <td style="padding: 8px;" width='11.875%'>ACTION</td>
        </tr>
        <tbody id="Response_Display"></tbody>
    </table>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(() => { loadReport(); });

    function loadReport(){
        let Filter_Approved_LPO_Report = {
            Start_Date:null,
            End_Date:null,
            Requisition_No:"",
            Purchase_Requisition_No:"",
            Supplier_ID:"all",
            Requesting_Store_ID:"all",
        }

        $.get('procurement/procure.common.php',{Filter_Approved_LPO_Report:Filter_Approved_LPO_Report,Filter_Approved_LPO:"Filter_Approved_LPO"},(response) => {
            $('#Response_Display').html(response);
        })
    }

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

        let Filter_Approved_LPO_Report = {
            Start_Date:Start_Date,
            End_Date:End_Date,
            Requisition_No:Requisition_No,
            Purchase_Requisition_No:Purchase_Requisition_No,
            Supplier_ID:Supplier_ID,
            Requesting_Store_ID:Requesting_Store_ID,
        }

        $.get('procurement/procure.common.php',{Filter_Approved_LPO_Report:Filter_Approved_LPO_Report,Filter_Approved_LPO:"Filter_Approved_LPO"},(response) => {
            $('#Response_Display').html(response);
        })
    }
</script>

<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<?php include('./includes/footer.php'); ?>