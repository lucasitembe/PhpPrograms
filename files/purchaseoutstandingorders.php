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
if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}else{
    $Employee_ID = '';
}

//get employee name
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = '';
}

if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
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
if(isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){
    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo '<input type="button" name="Load_Store_Order" id="Load_Store_Order" class="art-button-green" onclick="Load_Store_Orders()" value="LOAD STORE ORDER">';
    }

    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo "<a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
    }
}else{
    if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100){
        //calculate pending orders based on assigned level
        $before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
        $p_orders = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po
            where po.Order_Status = 'submitted' and po.Approval_Level = '$before_assigned_level'
            AND (SELECT count(*) FROM tbl_purchase_order_items poi WHERE poi.Purchase_Order_ID = po.Purchase_Order_ID) > 0") or die(mysqli_error($conn));
        $p_num = mysqli_num_rows($p_orders);

        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo "<a href='approvalsprocurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>
                        PENDING ORDERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>".$p_num."</span>
                        </a>";
        }
    }
}

if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage&from=purchaseOrder' class='art-button-green'>PREVIOUS ORDERS</a>";
        
          $Select_Pending_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from  tbl_purchase_order po,tbl_purchase_order_items poi,tbl_sub_department sd, tbl_supplier sp where
                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                            po.Sub_Department_ID = sd.Sub_Department_ID and
                            LOWER(poi.Grn_Status) = 'outstanding' 
                            group by poi.Purchase_Order_ID") or die(mysqli_error($conn));
        $number = mysqli_num_rows($Select_Pending_Order_Number);
        echo "<a href='purchaseoutstandingorders.php' class='art-button-green'>OUTSTANDING PURCHASE ORDER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    
    }
}

if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>BACK</a>";
    }
}

If(isset($_GET['purchase']))
    switch($_GET['purchase']){
        case "new":
            $action="purchaseorder.php?fr=list";
            $whereV="Sent";
            break;
        case "list":
            if($_GET['lForm']=='sentData'){
                $action=''; $whereV="Sent";
            } else if($_GET['lForm']=='saveData'){
                $action="purchaseorder.php?page=requizition";
                $whereV="Saved";
            }
            break;
    }
?>

<?php
//get sub department name
if(isset($_SESSION['Procurement_ID'])){
    $Sub_Department_ID = $_SESSION['Procurement_ID'];
    $Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);
}else{
    $Sub_Department_Name = '';
}

?>

    <br/><br/>
    <center>
        <table width=70% style="background-color:white;">
            <tr>
                <td style='text-align: right;' width=7%><b>Supplier</b></td>
                <td width=30%>
                    <select id="Supplier_ID" name="Supplier_ID">
                        <option></option>
                        <?php
                        $Suppliers = Get_Supplier_All();
                        foreach($Suppliers as $Supplier) {
                            echo "<option value='{$Supplier['Supplier_ID']}'> {$Supplier['Supplier_Name']} </option>";
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right;' width=10%><b>Order Number</b></td>
                <td width=30%>
                    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='Filter_Purchase_Orders()'
                           placeholder='~~~ ~~~ Enter Order Number ~~~ ~~~' style="text-align: center;">
                </td>
                <td width=7%></td>
            </tr>
            <tr>
                <?php
                $Today_Date = Get_Today_Date();
                $This_Month_Start_Date = Get_This_Month_Start_Date();
                ?>
                <td style="text-align: right;"><b>Start Date<b></td>
                <td width=30%>
                    <input type='text' name='Start_Date' id='Start_Date' required='required' autocomplete='off'
                           style="text-align: center;" readonly="readonly" value="<?php echo $This_Month_Start_Date; ?>">
                </td>
                <td style="text-align: right;"><b>End Date<b></td>
                <td width=30%>
                    <input type='text' name='End_Date' id='End_Date' required='required' autocomplete='off'
                           style="text-align: center;" readonly="readonly" value="<?php echo $Today_Date; ?>">
                </td>
                <td style="text-align: center;">
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Filter_Purchase_Orders();">
                </td>
            </tr>
        </table>
    </center>
    <br/>
    <fieldset style='overflow-y: scroll; height: 400px; background-color:white;' id='Items_Fieldset'>

    </fieldset>

    <script type="text/javascript">
        function Filter_Purchase_Orders(){
            var Start_Date = document.getElementById("Start_Date").value;
            var End_Date = document.getElementById("End_Date").value;
            var Supplier_ID = document.getElementById("Supplier_ID").value;
            var Search_Value = document.getElementById("Search_Value").value;

            if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
                if(window.XMLHttpRequest) {
                    myObjectPurchase = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    myObjectPurchase = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectPurchase.overrideMimeType('text/xml');
                }
                myObjectPurchase.onreadystatechange = function (){
                    data51 = myObjectPurchase.responseText;
                    if (myObjectPurchase.readyState == 4) {
                        document.getElementById('Items_Fieldset').innerHTML = data51;
                    }
                }; //specify name of function that will handle server response........
                myObjectPurchase.open('GET','purchaseoutstandingorders_search.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Supplier_ID='+Supplier_ID+'&Search_Value='+Search_Value,true);
                myObjectPurchase.send();
            }else{
                if(Start_Date == null || Start_Date == ''){
                    document.getElementById("Start_Date").style = 'border: 3px solid red; text-align: center;';
                    document.getElementById("Start_Date").focus();
                }

                if(End_Date == null || End_Date == ''){
                    document.getElementById("End_Date").style = 'border: 3px solid red; text-align: center;';
                    document.getElementById("End_Date").focus();
                }
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            addDatePicker($("#Start_Date"));
            addDatePicker($("#End_Date"));
            $('#Supplier_ID').select2();

            Filter_Purchase_Orders();
        });
    </script>

    <script type="text/javascript">
        function Cancel_Purchase_Order(Purchase_Order_ID){
            Confirm = confirm("Are you sure you want to cancel this Purchase Order?");
            if (Confirm) {
                if(window.XMLHttpRequest) {
                    purchaseOrderCancel = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    purchaseOrderCancel = new ActiveXObject('Micrsoft.XMLHTTP');
                    purchaseOrderCancel.overrideMimeType('text/xml');
                }
                purchaseOrderCancel.onreadystatechange = function (){
                    purchaseOrderCancelData = purchaseOrderCancel.responseText;
                    if (purchaseOrderCancel.readyState == 4) {
                        if(purchaseOrderCancelData.trim() == "yes") {
                            window.location = "previousorders.php?PreviousOrder=PreviousOrderThisPage";
                        } else {
                            alert("Something went wrong");
                        }
                    }
                }; //specify name of function that will handle server response........
                purchaseOrderCancel.open('GET','purchaseorder_cancel.php?Purchase_Order_ID='+Purchase_Order_ID,true);
                purchaseOrderCancel.send();
            }
        }
    </script>


    <script type="text/javascript">
        function Load_Store_Orders(){
            document.location = 'control_purchase_order_sessions.php?New_Purchase_Order=True&NPO=True&PurchaseOrder=PurchaseOrderThisPage&from=purchaseOrder';
        }
    </script>

    <script type="text/javascript">
        function Edit_Purchase_Order(Purchase_Order_ID){
            window.location = 'purchaseorderedit.php?Purchase_Order_ID='+Purchase_Order_ID+'&PurchaseOrderEdit=PurchaseOrderEditThisPage';
        }
    </script>
    <script>
        function Confirm_Discard_Order(Purchase_Order_ID){
             var Confirm_Message = confirm("Are you sure you want to Discard This Order");

        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                My_Object_Remove_Item = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Remove_Item.overrideMimeType('text/xml');
            }

            My_Object_Remove_Item.onreadystatechange = function () {
                data6 = My_Object_Remove_Item.responseText;
                if (My_Object_Remove_Item.readyState == 4) {
                    document.location.reload();
                }
            }; //specify name of function that will handle server response........

            My_Object_Remove_Item.open('GET', 'discard_purchase_order.php?type=order&Purchase_Order_ID=' + Purchase_Order_ID, true);
            My_Object_Remove_Item.send();
        }
        }
    </script>

    <script type="text/javascript">
        function Preview_Purchase_Order_Report(Purchase_Order_ID){
            var winClose=popupwindow('previouspurchaseorderreport.php?Purchase_Order_ID='+Purchase_Order_ID+'PreviousOrderReport=PreviousOrderReportThisPage', 'PURCHASE ORDER DETAILS', 1200, 500);
        }
        function popupwindow(url, title, w, h) {
            var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
            var wTop = window.screenTop ? window.screenTop : window.screenY;

            var left = wLeft + (window.innerWidth / 2) - (w / 2);
            var top = wTop + (window.innerHeight / 2) - (h / 2);
            var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
            return mypopupWindow;
        }
    </script>
<?php include('./includes/footer.php'); ?>