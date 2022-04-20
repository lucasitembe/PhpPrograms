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

<a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

<br><br>

<fieldset style="height: 600px;overflow-y:scroll">
    <legend>Purchase Requisition With No Supplier ~ (PR)</legend>

    <table>
        <thead>
            <tr style="background: #ccc">
                <td style='width: 50px;padding:8px'><b>S/No.</b></td>
                <td style="padding: 8px;" width='11%'><b>Store Order Requisition N<u>o.</u></b></td>
                <td style="padding: 8px;" width='10%'><b>Purchase Requisition N<u>o.</u></b></td>
                <td style="padding: 8px;" width='12%'><b>Created Date</b></td>
                <td style="padding: 8px;" width='14%'><b>Supplier</b></td>
                <td style="padding: 8px;" width='13%'><b>Store Requesting</b></td>
                <td style="padding: 8px;" width='13%'><b>Created By</b></td>
                <td style="padding: 8px;" width='15%'><b>Purchase Requisition Description</b></td>
                <td width='15%' style="text-align: center;padding:8px"><b>Reference Document</b></td>
                <td style='width: 100px;text-align:center;padding:8px'><b>Action</b></td>
            </tr>
        </thead>

        <tbody id='display-data'></tbody>
    </table>
</fieldset>


<script>
    $(document).ready(() => {
        onLoadRequistion();
    });

    function onLoadRequistion(){
        var status = 'loading-waiting-requistions';
        $.get('procument-core.php',{
            status:status
        },(data) => {
            $('#display-data').html(data);
        })
    }
</script>


<?php
    include("./includes/footer.php");
?>