<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./functions/department.php");
include("./functions/supplier.php");
?>
<script src='js/functions.js'></script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
$temp = 1;

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

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
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<?php
if (isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1) {
    if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
        echo '<input type="button" name="Load_Store_Order" id="Load_Store_Order" class="art-button-green" onclick="Load_Store_Orders()" value="LOAD STORE ORDER">';
    }

    if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
        echo "<a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
    }
} else {
    if (isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100) {
        //calculate pending orders based on assigned level
        $before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
        $p_orders = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po
            where po.Order_Status = 'submitted' and po.Approval_Level = '$before_assigned_level'
            AND (SELECT count(*) FROM tbl_purchase_order_items poi WHERE poi.Purchase_Order_ID = po.Purchase_Order_ID) > 0") or die(mysqli_error($conn));
        $p_num = mysqli_num_rows($p_orders);

        if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
            echo "<a href='approvalsprocurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>
                        PENDING ORDERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $p_num . "</span>
                        </a>";
        }
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
        echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
        if(isset($_GET['from']) && $_GET['from'] == "purchaseOrder") {
            echo "<a href='purchaseoutstandingorders.php' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>BACK</a>";
        }
    }
}

If (isset($_GET['purchase']))
    switch ($_GET['purchase']) {
        case "new":
            $action = "purchaseorder.php?fr=list";
            $whereV = "Sent";
            break;
        case "list":
            if ($_GET['lForm'] == 'sentData') {
                $action = '';
                $whereV = "Sent";
            } else if ($_GET['lForm'] == 'saveData') {
                $action = "purchaseorder.php?page=requizition";
                $whereV = "Saved";
            }
            break;
    }
?>

<?php
//get sub department name
if (isset($_SESSION['Procurement_ID'])) {
    $Sub_Department_ID = $_SESSION['Procurement_ID'];
    $Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);
} else {
    $Sub_Department_Name = '';
}
?>

<br/><br/>
<center>
    <fieldset>  
        <table width='100%'> 
            <tr> 
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <select  id='Supplier_ID' style='text-align: center;width:25%;display:inline' onchange ="clearOption()">
                        <option value="">All Suppliers</option>
                        <?php
                        $qr = "SELECT * FROM tbl_supplier ORDER BY Supplier_Name ASC";
                        $supplier_results = mysqli_query($conn,$qr);
                        while ($supplier_rows = mysqli_fetch_assoc($supplier_results)) {
                            ?>
                            <option value='<?php echo $supplier_rows['Supplier_ID']; ?>'><?php echo strtoupper($supplier_rows['Supplier_Name']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~Order No~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Filter_Purchase_Orders()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white;' id='Items_Fieldset'>

</fieldset>
<script>
    function clearOption(opt) {
        document.getElementById("Order_No").value = '';
    }

    function clearOrder() {
        if ($("#Supplier_ID").val() != '') {
            $("#Supplier_ID").val('').trigger('change');
        }

        document.getElementById("date_From").value = '';
        document.getElementById("date_To").value = '';
    }
</script>
<script>
    function Filter_Purchase_Orders() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Order_No = document.getElementById("Order_No").value;

        if (Order_No != '') {
            document.getElementById('Items_Fieldset').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function () {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Items_Fieldset').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'previousorders_search.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {

            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                document.getElementById("date_To").style = 'border: 3px; text-align: center;width:15%;display:inline';
                document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%;display:inline';

                document.getElementById('Items_Fieldset').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

                if (window.XMLHttpRequest) {
                    myObjectGetPreviousNote = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetPreviousNote.overrideMimeType('text/xml');
                }

                myObjectGetPreviousNote.onreadystatechange = function () {
                    data80 = myObjectGetPreviousNote.responseText;
                    if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                        document.getElementById('Items_Fieldset').innerHTML = data80;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetPreviousNote.open('GET', 'previousorders_search.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID, true);
                myObjectGetPreviousNote.send();
            } else {

                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_From").focus();
                } else {
                    document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%;display:inline';
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_To").focus();
                } else {
                    document.getElementById("date_To").style = 'border: 3px; text-align: center;width:15%;display:inline';
                }
            }
        }
    }
</script>
<script type="text/javascript">
    function Filter_Purchase_OrdersOld() {
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Search_Value = document.getElementById("Search_Value").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
            if (window.XMLHttpRequest) {
                myObjectPurchase = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectPurchase = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPurchase.overrideMimeType('text/xml');
            }
            myObjectPurchase.onreadystatechange = function () {
                data51 = myObjectPurchase.responseText;
                if (myObjectPurchase.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = data51;
                }
            }; //specify name of function that will handle server response........
            myObjectPurchase.open('GET', 'previousorders_search.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID + '&Search_Value=' + Search_Value, true);
            myObjectPurchase.send();
        } else {
            if (Start_Date == null || Start_Date == '') {
                document.getElementById("Start_Date").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("Start_Date").focus();
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("End_Date").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("End_Date").focus();
            }
        }
    }
</script>

<script>
    $(function () {
        addDatePicker($("#date_From"));
        addDatePicker($("#date_To"));
        $('#Supplier_ID').select2();
    });
</script>

<script type="text/javascript">
    function Cancel_Purchase_Order(Purchase_Order_ID) {
        Confirm = confirm("Are you sure you want to cancel this Purchase Order?");
        if (Confirm) {
            if (window.XMLHttpRequest) {
                purchaseOrderCancel = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                purchaseOrderCancel = new ActiveXObject('Micrsoft.XMLHTTP');
                purchaseOrderCancel.overrideMimeType('text/xml');
            }
            purchaseOrderCancel.onreadystatechange = function () {
                purchaseOrderCancelData = purchaseOrderCancel.responseText;
                if (purchaseOrderCancel.readyState == 4) {
                    if (purchaseOrderCancelData.trim() == "yes") {
                        window.location = "previousorders.php?PreviousOrder=PreviousOrderThisPage";
                    } else {
                        alert("Something went wrong");
                    }
                }
            }; //specify name of function that will handle server response........
            purchaseOrderCancel.open('GET', 'purchaseorder_cancel.php?Purchase_Order_ID=' + Purchase_Order_ID, true);
            purchaseOrderCancel.send();
        }
    }
</script>


<script type="text/javascript">
    function Load_Store_Orders() {
        document.location = 'control_purchase_order_sessions.php?New_Purchase_Order=True&NPO=True&PurchaseOrder=PurchaseOrderThisPage';
    }
</script>

<script type="text/javascript">
    function Edit_Purchase_Order(Purchase_Order_ID) {
        window.location = 'purchaseorderedit.php?Purchase_Order_ID=' + Purchase_Order_ID + '&PurchaseOrderEdit=PurchaseOrderEditThisPage';
    }
</script>

<script type="text/javascript">
    function Preview_Purchase_Order_Report(Purchase_Order_ID) {
        //var winClose = popupwindow('previouspurchaseorderreport.php?Purchase_Order_ID=' + Purchase_Order_ID + 'PreviousOrderReport=PreviousOrderReportThisPage', 'PURCHASE ORDER DETAILS', 1200, 500);
        window.open('previouspurchaseorderreport.php?Purchase_Order_ID=' + Purchase_Order_ID + 'PreviousOrderReport=PreviousOrderReportThisPage');
    }
    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>
<?php include('./includes/footer.php'); ?>