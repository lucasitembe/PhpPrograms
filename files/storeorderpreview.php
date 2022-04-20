<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='storesubmittedorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>VIEW / EDIT</a>";
    }
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='storesubmittedorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>BACK</a>";
    }
}

//get Purchase_Order_ID
if (isset($_SESSION['General_Order_ID'])) {
    $Store_Order_ID = $_SESSION['General_Order_ID'];
} else {
    $Store_Order_ID = 0;
}

//get all basic information
$select_info = mysqli_query($conn, "SELECT * FROM tbl_store_orders so, tbl_sub_department sd, tbl_employee emp where
                                so.Employee_ID = emp.Employee_ID and
                                sd.Sub_Department_ID = so.Sub_Department_ID and
                                so.Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));

$num = mysqli_num_rows($select_info);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_info)) {
        $Store_Order_ID = $row['Store_Order_ID'];
        $Store_Order_Date = $row['Created_Date_Time'];
        $Sub_Department_Name = $row['Sub_Department_Name'];
        $Prepared_By = ucwords(strtolower($row['Employee_Name']));
        $Description = $row['Order_Description'];
    }
} else {
    $Store_Order_ID = '';
    $Store_Order_Date = '';
    $Sub_Department_Name = '';
    $Prepared_By = '';
    $Description = '';
}
// here
include 'store/store.interface.php';
    $count = 1;
    $count_approvals = 1;
    $Interface = new StoreInterface();
    $Approval_Levels = $Interface->fetchApprovalLevels_("store_order");
?>
<br /><br />
<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: 1px solid #ddd !important;
    }

    tr:hover {
        /* background-color: #eeeeee; */
        cursor: pointer;
    }
</style>
<fieldset>
    <legend align="left"><b>STORE ORDER PREVIEW</b></legend>
    <table width="100%">
        <tr>
            <td width="12%">Order Number</td>
            <td width="15%"><input type="text" value="<?php echo $Store_Order_ID; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Order Date</td>
            <td width="15%"><input type="text" value="<?php echo $Store_Order_Date; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Prepared By</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Prepared_By)); ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td>Store Ordered</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Sub_Department_Name)); ?>" readonly="readonly"></td>
            <td style="text-align: right;">Order Description</td>
            <td colspan="3"><input type="text" value="<?php echo $Description; ?>" readonly="readonly"></td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <table width="100%">
        <tr>

            <td><input type="text" placeholder="Username" id="Username" style="text-align:center" class="form-control" /></td>
            <td><input type="password" placeholder="Password" id="Password" style="text-align:center" class="form-control" /></td>
            <td style="text-align: right;">
                <?php
                //display approval button if needed
                //if(strtolower($_SESSION['userinfo']['Approval_Orders']) == 'yes'){
                ?>
                <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE ORDER" onclick="Verify_Approval_Selected_Order(<?php echo $Store_Order_ID; ?>)">
                <?php
                // }
                ?>
                <input type="button" class="art-button-green" value="PREVIEW ORDER" onclick="Preview_Order_Report(<?php echo $Store_Order_ID; ?>);">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 305px; background-color: white;' id='Items_Fieldset'>
    <table width='100%' class='table'>
        <tr style="background-color: #eee;">
            <td width=3%><b>Sn</b></td>
            <td><b>ITEM NAME</b></td>
            <td width=8% style='text-align: center;'><b>UOM</b></td>
            <td width=8% style='text-align: center;'><b>UNIT</b></td>
            <td width=8% style='text-align: center;'><b>ITEM PER UNIT</b></td>
            <td width=10% style='text-align: center;'><b>QUANTITY</b></td>
            <td width=12% style="text-align: right;"><b>LAST BUYING PRICE</b>&nbsp;&nbsp;&nbsp;</td>
            <td width=15% style='text-align: left;'><b>&nbsp;&nbsp;&nbsp;ITEM REMARK</b></td>
        </tr>

        <?php
        //select data from tbl_purchase_order_items
        $temp = 1;
        $Amount = 0;
        $Grand_Total = 0;
        $select_data = mysqli_query($conn, "SELECT * FROM tbl_store_order_items rqi, tbl_items it WHERE
                                rqi.item_id = it.item_id and Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_data);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select_data)) {
        ?>
                <tr style="background-color: #fff;">
                    <td><?php echo $temp; ?></td>
                    <td><?php echo $row['Product_Name']?></td>
                    <td style='text-align: center;'><?php echo $row['Unit_Of_Measure']; ?></td>
                    <td style='text-align: center;'><?php echo $row['Container_Qty']; ?></td>
                    <td style='text-align: center;'><?php echo $row['Items_Qty']; ?></td>
                    <td style='text-align: center;'><?php echo $row['Quantity_Required']; ?>&nbsp;&nbsp;&nbsp;</td>
                    <td style='text-align: right;'><?php echo  number_format($row['Last_Buying_Price']); ?>&nbsp;&nbsp;&nbsp;</td>
                    <td style='text-align: left;'>&nbsp;&nbsp;&nbsp;<?php echo $row['Item_Remark']; ?></td>
            <?php
                $temp++;
            }
        }
        echo "</table>";
            ?>
    </table>
</fieldset>

<fieldset style="height: 180px;overflow-y:scroll">
    <table width='100%'>
        <tr style="background-color: #ddd;">
            <td style="padding: 6px;" width='5%'><center>S/N</center></td>
            <td style="padding: 6px;" width='23.75%'>EMPLOYEE NAME</td>
            <td style="padding: 6px;" width='23.75%'>APPROVAL TITLE</td>
            <td style="padding: 6px;" width='23.75%'>APPROVAL DATE</td>
            <td style="padding: 6px;" width='23.75%'>APPROVAL STATUS</td>
        </tr>
        <tbody id="Display_Approval_Employee_List">
            <?php foreach($Approval_Levels as $Level) : ?>
                <?php $Approval_ = ($Interface->getIfEmployeeApprovalForDocument_($Store_Order_ID,$Level['document_approval_level_title'],"store_order")); ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 6px;"><center><?=$count_approvals++?></center></td>
                    <td style="padding: 6px;"><?=(sizeof($Approval_) > 0) ? $Approval_[0]['Employee_Name'] : ""?></td>
                    <td style="padding: 6px;"><?=$Level['document_approval_level_title']?></td>
                    <td style="padding: 6px;"><?=(sizeof($Approval_) > 0) ? $Approval_[0]['date_time'] : ""?></td>
                    <td style="padding: 6px;"><?=(sizeof($Approval_) > 0) ? "<span style='color:#5439bf;font-weight:500'>APPROVED</span>" : "<span style='color:red;font-weight:500'>NOT APPROVED</span>"?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<div id="Editing_Mode" style="width:25%;">
    <center><b>It seems that this order is being modified. Please contact order creator for more information!</b></center>
</div>


<div id="Approval_Error" style="width:25%;">
    <center><b>Process Fail! Please try again!</b></center>
</div>

<script type="text/javascript">
    function Preview_Order_Report(Store_Order_ID) {
        window.open("previousstoreorderreport.php?Store_Order_ID=" + Store_Order_ID + "&PreviousStoreOrder=PreviousStoreOrderThisPage", "_blank");
    }
</script>
<script>
    function confirm_approval(Store_Order_ID) {
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
                check_if_valid_user_to_approve_this_document(Store_Order_ID);
            }
        }
    }

    function check_if_valid_user_to_approve_this_document(Store_Order_ID) {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var Store_Order_ID = '<?php echo $Store_Order_ID; ?>';
        $.ajax({
            type: 'GET',
            url: 'verify_approver_privileges_support.php',
            data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Store_Order_ID + "&document_type=store_order",
            cache: false,
            success: function(feedback) {
                if (feedback == 'all_approve_success') {
                    $("#remove_button_column").hide();
                    Approval_Selected_Order(Store_Order_ID);
                } else if (feedback == "invalid_privileges") {
                    alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                } else if (feedback == "fail_to_approve") {
                    alert("Fail to approve..please try again");
                } else {
                    alert(feedback);
                }
            }
        });
    }
</script>

<script type="text/javascript">
    function Verify_Approval_Selected_Order(Store_Order_ID) {
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function() {
            data2990 = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = data2990;
                if (feedback == 'yes') {
                    confirm_approval(Store_Order_ID);
                } else if (feedback == 'editing') {
                    $("#Editing_Mode").dialog("open");
                } else {
                    $("#Approval_Error").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........
        myObjectVerify.open('GET', 'Edit_Store_Ordering_Verify_Status.php', true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Approval_Selected_Order(Store_Order_ID) {
        var sms = confirm("Are you sure you want to approve this order?");
        if (sms == true) {
            window.open("Approve_Order.php?Store_Order_ID=" + Store_Order_ID, "_parent");
        }
    }
</script>



<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function() {
        $("#Editing_Mode").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#Approval_Error").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
    });
</script>
<?php
include("./includes/footer.php");
?>
