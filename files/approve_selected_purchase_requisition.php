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
$amounts = explode(",", $result[0]['costs']);

$sql_select_purchase_requisition_result = mysqli_query($conn, "SELECT pr.Requisition_Type,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=sup.Supplier_ID AND pr.store_requesting=sd.Sub_Department_ID AND purchase_requisition_id='$purchase_requisition_id' AND pr.pr_status in ('active','cancelled') ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_select_purchase_requisition_result) > 0) {
    $count = 1;
    while ($pr_rows = mysqli_fetch_assoc($sql_select_purchase_requisition_result)) {
        $Store_Order_ID = $pr_rows['Store_Order_ID'];
        $purchase_requisition_description = $pr_rows['purchase_requisition_description'];
        $created_date_time = $pr_rows['created_date_time'];
        $Employee_Name = ucwords($pr_rows['Employee_Name']);
        $Supplier_Name = ucwords($pr_rows['Supplier_Name']);
        $purchase_requisition_id = $pr_rows['purchase_requisition_id'];
        $Sub_Department_Name = ucwords($pr_rows['Sub_Department_Name']);
        $Sub_Department_ID = $pr_rows['Sub_Department_ID'];
        $Requisition_Type = $pr_rows['Requisition_Type'];
    }
}

?>


<a href="approve_purchase_requisition.php?pr=<?=$_GET['pr']?>" class="art-button-green">BACK</a>
<input type="hidden" id="status" value="<?=$_GET['pr']?>">

<br />
<br />
<style>
    .table,
    tr,
    td {
        border: none !important;
    }

    .table {
        border: none !important;
    }

    .table,
    tr {
        border: 1px solid #ddd !important;
    }

    .aproval-table {
        border: '1px solid black !important';
        width: 100%;
    }

    .aproval-table tbody {
        background-color: #fff;
    }

    .aproval-table tr td {
        padding: 10px;
    }
</style>

<fieldset>
    <legend>APPROVE PURCHASE REQUISITION</legend>
    <table width='100%'>
        <tr>
            <td width='8%' style="padding: 6px;"><b>S/No.</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="1"></td>

            <td width='8%' style="padding: 6px;"><b>SOR N<u>o.</u></b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $Store_Order_ID ?>"></td>

            <td width='8%' style="padding: 6px;"><b>PR N<u>o.</u></b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $purchase_requisition_id ?>"></td>

            <td width='8%' style="padding: 6px;"><b>Created Date</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $created_date_time ?>"></td>
        </tr>

        <tr>
            <td width='8%' style="padding: 6px;"><b>Supplier</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $Supplier_Name ?>"></td>

            <td width='8%' style="padding: 6px;"><b>Store Requesting</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $Sub_Department_Name ?>"></td>

            <td width='8%' style="padding: 6px;"><b>Created By</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $Employee_Name ?>"></td>

            <td width='8%' style="padding: 6px;"><b>PR Description</b></td>
            <td><input readonly type="text" name="" width="100%" style="background-color: #fff;" value="<?= $purchase_requisition_description ?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 330px;overflow-y:scroll">
    <table width='100%'>
        <tr style="background-color: #eee;">
            <td width='8%' style="padding: 8px;">
                <center><b>S/N</b></center>
            </td>
            <td style="padding: 8px;"><b>ITEM NAME</b></td>
            <td width='8%' style="padding: 8px;text-align:center"><b>UOM</b></td>
            <td width='8%' style="padding: 8px;text-align:center"><b>UNIT</b></td>
            <td width='8%' style="padding: 8px;text-align:center"><b>ITEM PER UNIT</b></td>
            <td width='8%' style="padding: 8px;text-align:center"><b>QUANTITY</b></td>
            <td width='8%' style="padding: 8px;text-align:end"><b>PRICE</b></td>
            <td width='8%' style="padding: 8px;text-align:end"><b>AMOUNT</b></td>
        </tr>

        <tbody>
            <?php
            foreach ($Procure->fetchLocalPurchaseOrderItems($purchase_requisition_id) as $details) {
                $formatted_price = (int)$details['buying_price'];
                $sub_total = (int)$details['buying_price'] * (int)$details['item_per_container'];
                $item_sub_total += $sub_total;
            ?>
                <tr style="background-color: #fff;">
                    <td width='8%' style="padding: 8px;"><center><?= $purchase_row_counter++ ?></center></td>
                    <td style="padding: 8px;"><?= $details['Product_Name'] ?></td>
                    <td style="padding: 8px;" width='10%'><center><?= $details['Unit_Of_Measure'] ?></center></td>
                    <td style="padding: 8px;" width='10%'><center><?= $details['container_quantity'] ?></center></td>
                    <td style="padding: 8px;" width='10%'><center><?= $details['item_per_container'] ?></center></td>
                    <td style="padding: 8px;" width='10%'><center><?= $details['quantity_required'] ?></center></td>
                    <td style="padding: 8px;text-align:end" width='10%'><?= number_format($formatted_price, 2) ?></td>
                    <td style="padding: 8px;text-align:end" width='10%'><?= number_format($sub_total, 2) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td style="padding: 8px;"><b>SUB TOTAL</b></td>
                <td style="padding: 8px;text-align:end" colspan="6"><b><?= number_format($item_sub_total, 2) ?></b></td>
            </tr>
            <tr>
                <td style="padding: 8px;text-align:start"><b>OTHER CHARGES</b></td>
            </tr>
            <?php foreach ($Procure->fetchOtherChargesItems() as $details) {
                $other_cost_sub_total += $amounts[$other_charges_counter - 1];  ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 8px;text-align:center"><?= $other_charges_counter ?></td>
                    <td style="padding: 8px;" colspan="6"><?= $details['Product_Name'] ?></td>
                    <td style="padding: 8px;text-align:end"><?= number_format($amounts[$other_charges_counter - 1], 2) ?></td>
                </tr>
            <?php $other_charges_counter++; } ?>
            <tr>
                <td style="padding: 8px;"><b>SUB TOTAL</b></td>
                <td style="padding: 8px;text-align:end" colspan="7"><b><?= number_format($other_cost_sub_total, 2) ?></b></td>
            </tr>
            <tr>
                <td style="padding: 8px;"><b>DISCOUNT</b></td>
                <td style="padding: 8px;text-align:end" colspan="7"><b><?= $result[0]['discount'] ?>%</b></td>
            </tr>
            <tr>
                <td style="padding: 8px;"><b>GRAND TOTAL</b></td>
                <?php $calc = ((int)$result[0]['discount'] > 0) ? $item_sub_total - (($result[0]['discount'] / 100) * $item_sub_total) + $other_cost_sub_total : $item_sub_total + $other_cost_sub_total; ?>
                <td style="padding: 8px;text-align:end" colspan="7"><b><?= number_format($calc, 2) ?></b></td>
            </tr>
        </tbody>
    </table>
</fieldset>



<!-- Check if the document is cancelled status or not -->
<?php if ($_GET['pr'] == 'cancelled') { ?>
    <fieldset id='return'>
        <table width='100%'><tr><td><a href="#" class='art-button-green' onclick="returnDocument()" style="font-family: Arial, Helvetica, sans-serif;float:right">RETURN DOCUMENT</a></td></tr></table>
    </fieldset>
<?php } else { ?>

    <fieldset><b>SOR N<u>o.</u></b>~~>Store Order Requisition No. <b>PR N<u>o.</u></b>~~>Purchase Requisition Number</fieldset>
    <fieldset>
        <h5>Document Approval Summary ~ <b><?= $Requisition_Type ?> Requisition</b></h5>
        <br>
        <table border="1" class='aproval-table'>
            <thead>
                <tr>
                    <td style="text-align: center;">S/N</td>
                    <td>Employee Name</td>
                    <td>Approval Title</td>
                    <td>Approval Date</td>
                    <td>Approval Status</td>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 1;
                $check = "";

                $queryEmp = mysqli_query($conn, "SELECT * FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_type='purchase_requisition' GROUP BY eal.document_approval_level_id") or die(mysqli_error($conn));

                if (mysqli_num_rows($queryEmp) > 0) {
                    while ($name = mysqli_fetch_assoc($queryEmp)) {
                        $no = $name['assigned_approval_level_id'];
                        $nu = $name['document_approval_level_title_id'];
                        $id = $name['Employee_ID'];

                        $sup = mysqli_fetch_assoc(mysqli_query($conn,"SELECT document_approval_level_title FROM tbl_document_approval_level_title WHERE document_approval_level_title_id = '$nu'"))['document_approval_level_title'];
                        $sql_select_approver_result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT date_time,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$id' AND dac.document_number='$purchase_requisition_id' AND dac.document_type = 'purchase_requisition'"));
                        $check_status = (empty($sql_select_approver_result)) ? "<span style='background-color:red;color:white;padding:5px;font-wight:500'><b>Not Approved</b></span>" :"<span style='background-color:green;color:white;padding:5px'><b>Approved</b></span>";
                        

                        echo "<tr>
                                <td style='text-align: center;'>" . $count++ . "</td>
                                <td>".$sql_select_approver_result['Employee_Name']."</td>
                                <td>".$sup . "</td>
                                <td>".$sql_select_approver_result['date_time']."</td>
                                <td>".$check_status."</td>
                            </tr>";

                        $check_status = "";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center'><b>No Approval Found</b></td></tr>";
                }
                ?>

            </tbody>
        </table>

        <br>

        <table class="table">
            <tr>
                <td><input type="text" placeholder="Username" id="Username" value="" style="text-align:center" class="form-control" /></td>
                <td><input type="password" placeholder="Password" id="Password" value="" style="text-align:center" class="form-control" /></td>
                <td width="10%">
                    <input type="button" value="APPROVE PR" id="verifyTitles" class="art-button-green" onclick='confirm_approval()' />
                </td>
                <td width="10%">
                    <input type="button" id='cancel_pr' value="DISAPPROVE" onclick="cancel()" class="art-button-green" />
                </td>
            </tr>
        </table>
    </fieldset>
<?php } ?>

<div id="cancel_space"></div>

<script>
    $(document).ready(() => {
        var employeeId = <?= $_SESSION['userinfo']['Employee_ID'] ?>;
        var purchase_requisition_id = <?= $_GET['purchase_requisition_id'] ?>;

        check_document(employeeId, purchase_requisition_id);
        check_cancel_priviledge(employeeId);
    });

    function check_document(employeeId, purchase_requisition_id) {
        var purchase_requisition = 'purchase_requisition';
        var document_name = 'purchase_requisition';

        $.get('approval_document_check.php', {
            employeeId: employeeId,
            purchase_requisition_id: purchase_requisition_id,
            purchase_requisition: purchase_requisition,
            document_name: document_name
        }, (response) => {
            if (response == 2 || response == 3) {
                $('#verifyTitles').css('display', 'none');
            }
        });
    }

    function check_cancel_priviledge(employeeId) {
        var purchase_requisition = 'purchase_requisition';
        var request = "check_cancel_priviledge";
        var document_narration = '<?= $_GET['document'] ?>';
        var document_name = 'purchase_requisition';

        $.get('approval_document_check.php', {
            request: request,
            employeeId: employeeId,
            document_name: document_name
        }, (response) => {
            if (response == 0) {
                $('#cancel_pr').css('display', 'none');
            }

            if (document_narration == 'cancelled' && response == 0) {
                $('#return').css('display', 'none');
            }
        });
    }
</script>

<script>
    function cancel() {
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var request = 'cancel_purchase_requisition';

        $.post(
            'cancel_document.php', {
                request: request
            }, (data) => {
                $("#cancel_space").dialog({
                    title: "CANCEL DOCUMENT REQUISITION No: " + purchase_requisition_id,
                    width: "30%",
                    height: 250,
                    modal: true
                });
                $('#cancel_space').html(data);
                $('#cancel_space').dialog('open');
            }
        )
    }
</script>

<script>
    function cancelPr() { 
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        var Employee_ID = '<?= $_SESSION['userinfo']['Employee_ID']; ?>';
        var request = 'cancel_purchase_requisition';
        var reason_for_cancellation = $('#reason_for_cancellation').val();
        var status = $('#status').val();

        if(confirm('Are you sure you want cancel this Requisition ')){
            $.post('procurement/procure.common.php',{
                purchase_requisition_id:purchase_requisition_id,
                Employee_ID:Employee_ID,
                request:request,
                reason_for_cancellation:reason_for_cancellation
            },(response) => {
                if(response == 100){
                    document.location = "approve_purchase_requisition.php?pr="+status;
                }else{
                    alert('Something went wrong contact Administrator for support');
                }
            });
        }
    }
</script>

<!-- return cancelled document -->
<script>
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
</script>
<!-- return cancelled document -->

<!-- remove Items from the document -->
<script>
    function removeItems(items_ID) {
        var purchase_requisition_id = <?= $_GET['purchase_requisition_id'] ?>;
        var btn = "remove_item";
        var employee_ID = <?= $_SESSION['userinfo']['Employee_ID'] ?>;

        if (confirm('Are you sure you want to remove the items clicked ?')) {
            $.ajax({
                type: "POST",
                url: "requisition_query.php",
                data: {
                    btn: btn,
                    purchase_requisition_id: purchase_requisition_id,
                    items_ID: items_ID,
                    employee_ID: employee_ID
                },
                success: (response) => {
                    alert(response);
                    location.reload();
                }
            });
        }
    }
</script>
<!-- remove Items from the document -->

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

    function check_if_valid_user_to_approve_this_document() {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var purchase_requisition_id = '<?php echo $purchase_requisition_id; ?>';
        $.ajax({
            type: 'GET',
            url: 'verify_approver_privileges_support.php',
            data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + purchase_requisition_id + "&document_type=purchase_requisition",
            cache: false,
            success: function(feedback) {
                if (feedback == 'all_approve_success') {
                    alert("Approved Successfully");
                    $(".remove_btn").hide();
                    approve_purchase_requisition();
                } else if (feedback == "invalid_privileges") {
                    alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                } else if (feedback == "fail_to_approve") {
                    alert("Fail to approve..please try again");
                } else {
                    $(".remove_btn").hide();
                    alert(feedback);
                }
            }
        });
    }

    function approve_purchase_requisition() {
        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
        $.ajax({
            type: 'GET',
            url: 'ajax_approve_purchase_requisition.php',
            data: {
                purchase_requisition_id: purchase_requisition_id
            },
            cache: false,
            success: function(data) {
                if (data == "success") {
                    document.location = "list_of_approved_purchase_requisition.php";
                }
            }
        });
    }
</script>
<?php
include('./includes/footer.php');
?>