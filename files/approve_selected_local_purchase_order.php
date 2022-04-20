<?php
include("./includes/header.php");
include("./includes/connection.php");
include 'procurement/procure.interface.php';
$Procure = new ProcureInterface();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procurement_Works'])) {
        if ($_SESSION['userinfo']['Procurement_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$purchase_requisition_id = (isset($_GET['purchase_requisition_id'])) ? $_GET['purchase_requisition_id'] : "0";
$purchase_row_counter = 1;
$other_charges_counter = 1;
$item_sub_total = 0;
$other_cost_sub_total = 0;
$result = $Procure->fetchProcurementOtherCharges($purchase_requisition_id);
$purchase_details = $Procure->fetchPurchaseLocalOrderDetails($purchase_requisition_id);
$amounts = explode(",",$result[0]['costs']);

?>
<a href="list_of_local_purchase_order_to_approve.php?lpo=<?=$_GET['lpo']?>" class="art-button-green">BACK</a>
<br />
<style>
    .table,tr,td{
        border:none!important;
    }

    .table,tr{
        border:1px solid #ccc!important;
    }
    .table{
        border:none!important;
    }

    .aproval-table{
       border:'1px solid black !important';
       width: 100%;
    }

    .aproval-table tbody{
        background-color: #fff;
    }

    .aproval-table tr td{
        padding: 10px;
    }
</style>

<fieldset>
    <input type="hidden" id="status" value="<?=$_GET['lpo']?>">
    <legend>APPROVE LOCAL PURCHASE ORDER (LPO)</legend>
    <table width='100%'>
        <tr>
            <td width='8%' style="padding: 6px;"><b>S/No.</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="1"></td>

            <td width='8%' style="padding: 6px;"><b>SOR No.</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=$purchase_details[0]['Store_Order_ID']?>"></td>

            <td width='8%' style="padding: 6px;"><b>PR N<u>o.</u></b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=$purchase_details[0]['purchase_requisition_id']?>"></td>

            <td width='8%' style="padding: 6px;"><b>Created Date</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=$purchase_details[0]['created_date_time']?>"></td>
        </tr>

        <tr>
            <td width='8%' style="padding: 6px;"><b>Supplier</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=ucwords($purchase_details[0]['Supplier_Name'])?>"></td>

            <td width='8%' style="padding: 6px;"><b>Store Requesting</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=ucwords($purchase_details[0]['Sub_Department_Name'])?>"></td>

            <td width='8%' style="padding: 6px;"><b>Created By</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=$purchase_details[0]['Employee_Name']?>"></td>

            <td width='8%' style="padding: 6px;"><b>PR Description</b></td>
            <td><input readonly type="text" width="100%" style="background-color: #fff;" value="<?=$purchase_details[0]['purchase_requisition_description']?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 385px;overflow-x:scroll">
    <table width='100%'>
        <tr style="background-color: #eee;">
            <td style="padding: 8px;" width='8%'><center><b>S/No.</b></center></td>
            <td style="padding: 8px;"><b>ITEM NAME</b></td>
            <td style="padding: 8px;" width='10%'><center><b>ITEM PER PACK</b></center></td>
            <td style="padding: 8px;" width='10%'><center><b>TOTAL PACK</b></center></td>
            <td style="padding: 8px;text-align:end" width='10%'><b>PRICE</b></td>
            <td style="padding: 8px;text-align:end" width='10%'><b>AMOUNT</b></td>
        </tr>

        <?php 
            foreach($Procure->fetchLocalPurchaseOrderItems($purchase_requisition_id) as $details) { 
                $formatted_price = (int)$details['buying_price'];
                $sub_total = (int)$details['buying_price'] * (int)$details['item_per_container'];
                $item_sub_total += $sub_total;
        ?>
            <tr style="background-color: #fff;">
                <td style="padding: 8px;" width='8%'><center><?=$purchase_row_counter++?></center></td>
                <td style="padding: 8px;"><?=$details['Product_Name']?></td>
                <td style="padding: 8px;" width='10%'><center><?=$details['container_quantity']?></center></td>
                <td style="padding: 8px;" width='10%'><center><?=$details['item_per_container']?></center></td>
                <td style="padding: 8px;text-align:end" width='10%'><?=number_format($formatted_price,2)?></td>
                <td style="padding: 8px;text-align:end" width='10%'><?=number_format($sub_total,2)?></td>
            </tr>
        <?php } ?>
        <tr>
            <td style="padding: 8px;"><b>SUB TOTAL</b></td>
            <td style="padding: 8px;text-align:end" colspan="5"><b><?=number_format($item_sub_total,2)?></b></td>
        </tr>
        <tr>
            <td style="padding: 8px;text-align:start"><b>OTHER CHARGES</b></td>
        </tr>
        <?php foreach($Procure->fetchOtherChargesItems() as $details) { $other_cost_sub_total += $amounts[$other_charges_counter-1];  ?>
            <tr style="background-color: #fff;">
                <td style="padding: 8px;text-align:center"><?=$other_charges_counter?></td>
                <td style="padding: 8px;" colspan="4"><?=$details['Product_Name']?></td>
                <td style="padding: 8px;text-align:end"><?=number_format($amounts[$other_charges_counter-1],2)?></td>
            </tr>
        <?php $other_charges_counter++; } ?>
        <tr>
            <td style="padding: 8px;"><b>SUB TOTAL</b></td>
            <td style="padding: 8px;text-align:end" colspan="5"><b><?=number_format($other_cost_sub_total,2)?></b></td>
        </tr>
        <tr>
            <td style="padding: 8px;"><b>DISCOUNT</b></td>
            <td style="padding: 8px;text-align:end" colspan="5"><b><?=$result[0]['discount']?>%</b></td>
        </tr>
        <tr>
            <td style="padding: 8px;"><b>GRAND TOTAL</b></td>
            <?php $calc = ((int)$result[0]['discount'] > 0) ? $item_sub_total - (($result[0]['discount'] / 100) * $item_sub_total) + $other_cost_sub_total : $item_sub_total + $other_cost_sub_total; ?>
            <td style="padding: 8px;text-align:end" colspan="5"><b><?=number_format($calc,2)?></b></td>
        </tr>
    </table>
</fieldset>

<?php if ($_GET['lpo'] == 'cancelled') { ?>
    <fieldset id='return'>
        <table width='100%'><tr><td><a href="#" class='art-button-green' onclick="returnDocument()" style="font-family: Arial, Helvetica, sans-serif;float:right">RETURN DOCUMENT</a></td></tr></table>
    </fieldset>
<?php } else { ?>

<fieldset><b>SOR N<u>o.</u></b>~~>Store Order Requisition No. <?=$purchase_details[0]['Store_Order_ID']?> ~ <b>PR N<u>o.</u></b>~~>Purchase Requisition No. <?=$purchase_requisition_id?></fieldset>
<fieldset>
    <h5 style="line-height: 2;">Document Approval Summary</h5>
    <table border="1" class='aproval-table'>
        <thead>
            <tr>
                <td style="text-align: center;"><b>S/N</b></td>
                <td><b>EMPLOYEE NAME</b></td>
                <td><b>APPROVAL TITLE</b></td>
                <td><b>APPROVAL DATE</b></td>
                <td><b>APPROVAL STATUS</b></td>
            </tr>
        </thead> 

        <tbody>
            <?php 
                $count = 1;
                $check = "";

                $queryEmp = mysqli_query($conn,"SELECT * FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_type='purchase_order' GROUP BY eal.document_approval_level_id") or die(mysqli_error($conn));

                if(mysqli_num_rows($queryEmp) > 0){
                    while($name = mysqli_fetch_assoc($queryEmp)){
                        $no = $name['assigned_approval_level_id'];
                        $nu = $name['document_approval_level_title_id'];
                        $id = $name['Employee_ID'];

                        $sup = mysqli_fetch_assoc(mysqli_query($conn,"SELECT document_approval_level_title FROM tbl_document_approval_level_title WHERE document_approval_level_title_id = '$nu'"))['document_approval_level_title'];
                        $sql_select_approver_result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT date_time,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$id' AND dac.document_number='$purchase_requisition_id' AND dac.document_type = 'purchase_order'"));

                        $check_status = (empty($sql_select_approver_result)) ? "<span style='background-color:red;color:white;padding:5px;font-wight:500'><b>Not Approved</b></span>" :"<span style='background-color:green;color:white;padding:5px'><b>Approved</b></span>";
                        
                        echo "<tr>
                                <td style='text-align: center;'>".$count++."</td>
                                <td>".$sql_select_approver_result['Employee_Name']."</td>
                                <td>".$sup."</td>
                                <td>".$sql_select_approver_result['date_time']."</td>
                                <td>".$check_status."</td>
                            </tr>";

                        $check_status = "";
                        
                    }
                }else{
                    echo "<tr><td colspan='5' style='text-align:center'><b>No Approval Found</b></td></tr>";
                }
            ?>
        </tbody>

    </table>

    <table class="table">
        <tr>
            <td><input type="text" placeholder="Username" id="Username" value="" style="text-align:center" class="form-control" /></td>
            <td><input type="password" placeholder="Password" id="Password" value="" style="text-align:center" class="form-control" /></td>
            <td width="10%">
                <input type="button" id='verifyTitles' value="APPROVE LPO" class="art-button-green" onclick='confirm_approval()' />
            </td>
            <td width="10%">
                <input  type="button" id='cancel_pr' value="DISAPPROVE LPO" onclick="cancelLpoDoc()" class="art-button-green"/>
            </td>
        </tr>
    </table>
</fieldset>
<?php } ?>

<div id="disapprove_lpo_space"></div>

<script>
    $(document).ready(() => {
        var employeeId = <?=$_SESSION['userinfo']['Employee_ID']?>;
        var purchase_requisition_id = <?=$_GET['purchase_requisition_id']?>;

        check_cancel_privilege(employeeId);
        check_document(employeeId,purchase_requisition_id);

    });

    function check_document(employeeId,purchase_requisition_id){
        var purchase_requisition = 'purchase_requisition';
        var document_name = 'purchase_order';

        $.get('approval_document_check.php',{
            employeeId:employeeId,
            purchase_requisition_id:purchase_requisition_id,
            purchase_requisition:purchase_requisition,
            document_name:document_name
        },(response) => {
            if(response == 2 || response == 3){
                $('#verifyTitles').css('display','none')
            }
        });
    }

    function check_cancel_privilege(employeeId){
        var purchase_requisition = 'purchase_requisition';
        var request = "check_cancel_priviledge";
        var document_narration = '<?=$_GET['document']?>';
        var document_name = 'purchase_order';

        $.get('approval_document_check.php',{
            request:request,
            employeeId:employeeId,
            document_name:document_name
        },(response) => {
            if(response == 0){
                $('#cancel_pr').css('display','none');
            }
        });
    }

    function cancelLpoDoc() { 
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var request = 'to_cancel_lpo';

        $.ajax({
            type: "GET",
            url: "cancel_document.php",
            data: {
                purchase_requisition_id:purchase_requisition_id,
                Employee_ID:Employee_ID,
                request:request
            },
            success: (response) => {
                $("#disapprove_lpo_space").dialog({
                    title: "CANCEL LOCAL PURCHASE ORDER No: " + purchase_requisition_id,
                    width: "30%",
                    height: 260,
                    modal: true
                });
                $('#disapprove_lpo_space').html(response);
                $('#disapprove_lpo_space').dialog('open');
            }
        });
    }
    
</script>

<!-- new implementation -->
<script>
    function cancelLpo() {  
        var reason_for_cancellation = $('#reason_for_cancellation').val();
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var status = $('#status').val();
        var request = 'lpo_cancellation';

        if(reason_for_cancellation == ""){
            $('#reason_for_cancellation').css('border','1px solid red');
            exit(1);
        }
        $('#reason_for_cancellation').css('border','1px solid #ccc');

        if(confirm('Are you sure, you want cancel this requisition ?')){
            $.post('procurement/procure.common.php',{
                request:request,
                Employee_ID:Employee_ID,
                purchase_requisition_id:purchase_requisition_id,
                reason_for_cancellation:reason_for_cancellation
            },(response) => {
                if(response == 100){
                    document.location = "list_of_local_purchase_order_to_approve.php?lpo="+status;
                }
            });
        }   
    }
</script>
<!-- new implementation -->

<script>
    function cancel() {
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var documentAction = 'toCancellDocument';

        if (confirm('Are you want to cancel the document')) {
            $.post(
                'cancel_doc.php', {
                    purchase_requisition_id: purchase_requisition_id,
                    Employee_ID: Employee_ID,
                    documentAction: documentAction,
                    from:'lpo'
                }, (data) => {
                    $("#cancel_space").dialog({
                        title: "CANCEL DOCUMENT REQUISITION No: " + purchase_requisition_id,
                        width: "30%",
                        height: 330,
                        modal: true
                    });
                    $('#cancel_space').html(data);
                    $('#cancel_space').dialog('open');
                }
            )
        }
    }
</script>

<script>
    function confirm_approval() {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        if (Supervisor_Username == "") {
            $("#Username").css("border", "2px solid red");
        } else if (Supervisor_Password == "") {
            $("#Username").css("border", "");
            $("#Password").css("border", "2px solid red");
        } else {
            $("#Username").css("border", "");
            $("#Password").css("border", "");
            if (confirm("Are you sure you want to approve this Order?")) {
                check_if_valid_user_to_approve_this_document();
            }
        }
    }

    function returnDocument() {
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var documentAction = 'toReturnDocument';

        if (confirm('Are you want to return the document')) {
            $.ajax({
                type: "POST",
                url: "cancel_doc.php",
                data: {
                    documentAction: documentAction,
                    Employee_ID: Employee_ID,
                    purchase_requisition_id: purchase_requisition_id
                },
                success: (data) => {
                    $("#cancel_space").dialog({
                        title: "RETURN DOCUMENT REQUISITION No: " + purchase_requisition_id,
                        width: "30%",
                        height: 330,
                        modal: true
                    });
                    $('#cancel_space').html(data);
                    $('#cancel_space').dialog('open');
                }
            });
        }
    }

    function check_if_valid_user_to_approve_this_document() {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var purchase_requisition_id = '<?php echo $purchase_requisition_id; ?>';
        $.ajax({
            type: 'GET',
            url: 'verify_approver_privileges_support.php',
            data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + purchase_requisition_id + "&document_type=purchase_order",
            cache: false,
            success: function(feedback) {
                if (feedback == 'all_approve_success') {
                    alert("Approved Successfully");
                    $(".remove_btn").hide();
                    create_local_purchase_order();
                } else if (feedback == "invalid_privileges") {
                    alert("Invalid Username or Password or you do not have enough privilege to approve this requisition");
                } else if (feedback == "fail_to_approve") {
                    alert("Fail to approve..please try again");
                } else {
                    $(".remove_btn").hide();
                    alert(feedback);
                }
            }
        });
    }

    function create_local_purchase_order() {
        var purchase_requisition_description = '<?=$purchase_details[0]['purchase_requisition_description'];?>';
        var Sub_Department_ID = '<?=$purchase_details[0]['Sub_Department_ID']?>';
        var Store_Order_ID = '<?=$purchase_details[0]['Store_Order_ID'] ?>';
        var Supplier_ID = '<?=$purchase_details[0]['Supplier_ID'] ?>';
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var employee_creating = '<?=$purchase_details[0]['employee_creating'] ?>';

        $.ajax({
            type: 'GET',
            url: 'ajax_create_lpo.php',
            data: {
                purchase_requisition_description: purchase_requisition_description,
                Sub_Department_ID: Sub_Department_ID,
                Store_Order_ID: Store_Order_ID,
                Supplier_ID: Supplier_ID,
                purchase_requisition_id: purchase_requisition_id,
                employee_creating: employee_creating
            },
            success: function(data) {
                if (data == "success") {
                    document.location = "list_of_local_purchase_order.php";
                }
            }
        });
    }
</script>
<?php include('./includes/footer.php'); ?>