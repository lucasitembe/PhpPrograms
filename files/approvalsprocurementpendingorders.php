<script src='js/functions.js'></script>

<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");

include_once("./functions/department.php");
include_once("./functions/supplier.php");


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

if(!isset($_SESSION['Procurement_Supervisor'])){
    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
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

//get sub department id
if(isset($_SESSION['Procurement_ID'])){
    $Sub_Department_ID = $_SESSION['Procurement_ID'];
}else{
    $Sub_Department_ID = 0;
}

//get sub department name
$Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);

if(isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){
    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>NEW ORDER</a>";
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
        echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
    }
}

if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
        echo "<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>";
    }
}

//generate start date
$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
}

$Start_Month_Date = substr($original_Date, 0, 8).'01 00:00';
?>


<!-- Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script>
    $(document).ready(function(){
        addDatePicker($("#date"));
        addDatePicker($("#date2"));
    });
</script>
<!--end of datepicker script-->


<?php

if(isset($_POST['submit'])){
    $Date_From = $_POST['Date_From'];
    $Date_To = $_POST['Date_To'];
}else{
    $Date_From = '';
    $Date_To = '';
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

<br/><br/>
<center>
    <table width=60% style="background-color: white;">
        <tr>
            <td style='text-align: right;' width=7%><b>Supplier</b></td>
            <td width=30%>
                <select id="Supplier_ID" name="Supplier_ID" onchange="Filter_Purchase_Orders()">
                    <option value="all">All</option>
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
            <td style='text-align: right;' width=7%><b>Start Date</b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='Date_From' placeholder='Start Date' style='text-align: center;' readonly="readonly" value="<?php echo $Start_Month_Date; ?>">
            </td>
            <td style='text-align: right;' width=7%><b>End Date</b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='Date_To' placeholder='End Date' style='text-align: center;' readonly="readonly" value="<?php echo $original_Date; ?>">
            </td>
            <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Filter_Purchase_Orders()'></td>
        </tr>
    </table>
</center>
<br/>

<form>
    <input type="hidden" id="Current_Approved_PO_ID"/>
</form>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Previous_Fieldset_List'>

    <legend style='background-color:#006400;color:white;padding:5px;' align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo ucwords(strtolower($Sub_Department_Name)); }?>, pending orders</b></legend>

    <?php
    $temp = 1;

    echo '<tr><td colspan="9"><hr></td></tr>';
    echo '<center><table width = 100% border=0>';
    echo "<tr id='thead'>
                <td width=4% style='text-align: center;'><b>SN</b></td>
                <td width=12% style='text-align: center;'><b>ORDER NUMBER</b></td>
                <td width=13%><b>ORDER DATE & TIME</b></td>
                <td width=10%><b>STORE NEED</b></td>
                <td width=15%><b>SUPPLIER NAME</b></td>
                <td width=20%><b>ORDER DESCRIPTION</b></td>
                <td style='text-align: center;'><b>ACTION</b></td>
            </tr>";
    echo '<tr><td colspan="9"><hr></td></tr>';

    $sql_select = mysqli_query($conn,"SELECT
                                          po.Purchase_Order_ID, po.Sent_Date, po.Created_Date,
                                          sd.Sub_Department_Name, s.Supplier_Name, po.Order_Description
                                        FROM tbl_purchase_order po, tbl_sub_department sd, tbl_supplier s
                                        WHERE po.Sub_Department_ID = sd.Sub_Department_ID
                                        AND po.Supplier_ID = s.Supplier_ID
                                        AND po.Approval_Level = '$before_assigned_level'
                                        AND po.Sent_Date BETWEEN '$Start_Month_Date' AND '$original_Date'
                                        AND po.Order_Status = 'submitted'
                                        ORDER BY po.Purchase_Order_ID DESC
                                        LIMIT 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){
            echo '<tr><td style="text-align: center;">'.$temp.'</td>
            				<td style="text-align: center;">'.$row['Purchase_Order_ID'].'</td>
            				<td>'.$row['Sent_Date'].'</td>
            			    <td>'.$row['Sub_Department_Name'].'</td>
            				<td>'.$row['Supplier_Name'].'</td>
            			    <td>'.$row['Order_Description'].'</td>
            				<td style="text-align: center;" width="10%">
                                <input type="button" value="PROCESS ORDER" onclick="Display_Purchase_Details('.$row['Purchase_Order_ID'].')"
                                    class="art-button-green">&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>';
            $temp++;
        }
    }
    echo '</table>';
    ?>
</fieldset>

<div id="Procurement_Details" style="width:90%;" >
    <center id='Procurement_Area'>

    </center>
</div>



<div id="Approval_Success_Message" style="width:25%;">
    <center>
        <b>Purchase order approved successfully</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Approval_Success_Message_Close()">
    </center>
</div>


<div id="Approval_Alert_Message" style="width:25%;">
    <center>
        <b>Process fail! Please try again</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Approval_Alert_Message_Close()">
    </center>
</div>

<div id="Approved_Alert_Message" style="width:25%;">
    <center>
        <b>Process fail! Purchase order already approved</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Approved_Alert_Message_Close()">
    </center>
</div>

<div id="Approved_Invalid_Alert_Message" style="width:25%;">
    <center>
        <b>Invalid username or password. Enter correct username and password</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Approved_Invalid_Alert_Message_Close()">
    </center>
</div>

<script type="text/javascript">
    function Approval_Success_Message_Close(){
        $("#Approval_Success_Message").dialog("close");
        document.getElementById("Username").value = '';
        document.getElementById("Password").value = '';
        var Confirm_Message = confirm("Click OK to preview Purchase Order");
        if (Confirm_Message) {
            Purchase_Order_ID = document.getElementById("Current_Approved_PO_ID").value;
            OpenPopupCenter('previouspurchaseorderreport.php?Purchase_Order_ID='+Purchase_Order_ID+
                'PreviousOrderReport=PreviousOrderReportThisPage', 'Purchase Order', 1200, 800);
        }
        Filter_Purchase_Orders();
    }
</script>

<script type="text/javascript">
    function Approval_Alert_Message_Close(){
        $("#Approval_Alert_Message").dialog("close");
        document.getElementById("Username").focus();
        document.getElementById("Username").value = '';
        document.getElementById("Password").value = '';
    }
</script>

<script type="text/javascript">
    function Approved_Invalid_Alert_Message_Close(){
        $("#Approved_Invalid_Alert_Message").dialog("close");
        document.getElementById("Username").focus();
        document.getElementById("Username").value = '';
        document.getElementById("Password").value = '';
    }
</script>

<script type="text/javascript">
    function Approved_Alert_Message_Close(){
        $("#Approved_Alert_Message").dialog("close");
        document.getElementById("Username").focus();
        document.getElementById("Username").value = '';
        document.getElementById("Password").value = '';
    }
</script>

<script>
    function Get_Previous_Requisition() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
            if(window.XMLHttpRequest) {
                myObjectGetPrevious = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectGetPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPrevious.overrideMimeType('text/xml');
            }

            myObjectGetPrevious.onreadystatechange = function (){
                data80 = myObjectGetPrevious.responseText;
                if (myObjectGetPrevious.readyState == 4) {
                    document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPrevious.open('GET','Get_Previous_Requisition.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
            myObjectGetPrevious.send();
        }else{

            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("date").focus();
            }else{
                document.getElementById("date").style = 'border: 3px; text-align: center;';
            }

            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("date2").focus();
            }else{
                document.getElementById("date2").style = 'border: 3px; text-align: center;';
            }
        }
    }
</script>


<script type="text/javascript">
    function Preview_Requisition_Report(Requisition_ID){
        var winClose=popupwindow('previousrequisitionreport.php?Requisition_ID='+Requisition_ID, 'REQUISITION DETAILS', 1200, 500);
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

<script type="text/javascript">
    function Filter_Purchase_Orders(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        //document.getElementById("Date_From").style = 'border: 3px solid white; text-align: center;';
        //document.getElementById("Date_To").style = 'border: 3px solid white; text-align: center;';
        if(Date_From != null && Date_To != '' && Date_To != null && Date_To != ''){
            if(window.XMLHttpRequest) {
                myObjectFilterPurchase = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectFilterPurchase = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilterPurchase.overrideMimeType('text/xml');
            }

            myObjectFilterPurchase.onreadystatechange = function (){
                data8990 = myObjectFilterPurchase.responseText;
                if (myObjectFilterPurchase.readyState == 4) {
                    document.getElementById('Previous_Fieldset_List').innerHTML = data8990;
                }
            }; //specify name of function that will handle server response........

            myObjectFilterPurchase.open('GET','Filter_Purchase_Orders.php?Date_From='+Date_From+'&Date_To='+Date_To+
                '&Search_Value='+Search_Value+'&Supplier_ID='+Supplier_ID,true);
            myObjectFilterPurchase.send();
        }else{
            if(Date_From == null || Date_From == ''){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                //document.getElementById("Date_From").style = 'border: 3px solid white; text-align: center;';
            }

            if(Date_To == null || Date_To == ''){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                //document.getElementById("Date_To").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Display_Purchase_Details(Purchase_Order_ID){

        if(window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
            data2090 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById("Procurement_Area").innerHTML = data2090;
                $("#Procurement_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectGetDetails.open('GET','Display_Purchase_Details.php?Purchase_Order_ID='+Purchase_Order_ID,true);
        myObjectGetDetails.send();
    }
</script>

<script type="text/javascript">
    function Approve_Purchase_Order(Purchase_Order_ID){
        var Username = document.getElementById("Username").value;
        var pwd = document.getElementById("Password").value;
        if(Username != null && Username != '' && pwd != null && pwd != ''){
            if(window.XMLHttpRequest) {
                myObjectApproveOrder = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectApproveOrder = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectApproveOrder.overrideMimeType('text/xml');
            }

            myObjectApproveOrder.onreadystatechange = function (){
                data221 = myObjectApproveOrder.responseText;
                if (myObjectApproveOrder.readyState == 4) {
                    var feedback = data221;
                    if(feedback == 'yes'){
                        $("#Approval_Success_Message").dialog("open");
                        $("#Procurement_Details").dialog("close");
                        document.getElementById("Current_Approved_PO_ID").value = Purchase_Order_ID;
                    }else if(feedback == 'approved'){
                        $("#Approved_Alert_Message").dialog("open");
                        document.getElementById("Username").value = '';
                        document.getElementById("Password").value = '';
                        document.getElementById("Username").focus();
                    }else if(feedback == 'invalid'){
                        $("#Approved_Invalid_Alert_Message").dialog("open");
                        document.getElementById("Username").value = '';
                        document.getElementById("Password").value = '';
                        document.getElementById("Username").focus();
                    }else{
                        $("#Approval_Alert_Message").dialog("open");
                        document.getElementById("Username").value = '';
                        document.getElementById("Password").value = '';
                        document.getElementById("Username").focus();
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectApproveOrder.open('GET','Approve_Purchase_Order.php?Purchase_Order_ID='+Purchase_Order_ID+'&Username='+Username+'&Password='+pwd,true);
            myObjectApproveOrder.send();
        }else{
            if(Username == null || Username == ''){
                document.getElementById("Username").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("Username").focus();
            }

            if(pwd == null || pwd == ''){
                document.getElementById("Password").style = 'border: 3px solid red; text-align: center;';
                if(Username != null && Username != ''){
                    document.getElementById("Password").focus();
                }
            }

        }
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>


<script>
    $('#Date_From').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#Date_From').datetimepicker({value:'',step:30});
    $('#Date_To').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->


<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function(){
        $("#Procurement_Details").dialog({ autoOpen: false, width:'90%',height:500, title:'PROCUREMENT DETAILS',modal: true});
    });
</script>

<script>
    $(document).ready(function(){
        $("#Approval_Success_Message").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Information!',modal: true});
    });
</script>

<script>
    $(document).ready(function(){
        $("#Approval_Alert_Message").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Alert Message!',modal: true});
    });
</script>

<script>
    $(document).ready(function(){
        $("#Approved_Alert_Message").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Alert Message!',modal: true});
    });
</script>

<script>
    $(document).ready(function(){
        $("#Approved_Invalid_Alert_Message").dialog({ autoOpen: false, width:'50%',height:150, title:'eHMS 2.0 ~ Alert Message!',modal: true});
    });
</script>

<?php
include("./functions/scripts.php");
include('./includes/footer.php');
?>