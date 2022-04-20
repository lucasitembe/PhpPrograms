<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/functions.php");
$temp = 0;
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_Name = 0;
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='previousgrnlist.php?PreviousGrnList=PreviousGrnListThisPage' class='art-button-green'>PREVIOUS GRN</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //  $Select_Pending_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from  tbl_purchase_order po,tbl_pending_purchase_order_items poi,tbl_sub_department sd where
        //                     po.Purchase_Order_ID = poi.Purchase_Order_ID and
        //                     po.Sub_Department_ID = sd.Sub_Department_ID and
        //                     LOWER(poi.Grn_Status) = 'pending' and
        //                     sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by poi.Purchase_Order_ID") or die(mysqli_error($conn));
        // $number = mysqli_num_rows($Select_Pending_Order_Number);
        echo "<a href='grnpendingpurchaseorderlist.php?PendingPurchaseOrderList=PendingPurchaseOrderListThisPage&from=purchaseorder' class='art-button-green'>PENDING PURCHASE ORDER</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        if(isset($_GET['from']) && $_GET['from'] == "purchaseorder") {
            echo "<a href='grnpurchaseorder.php?GrnPurchaseOrder=GrnPurchaseOrderThisPage&from=purchaseorder' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";
        }
    }
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
    select{
        padding:5px;
    }
</style>

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
                    <input type="button" value="Filter" class="art-button-green" onclick="Get_Purchase_Orders()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Purchase_Order_List'>
    <legend align='right'><b><?php
            if (isset($_SESSION['Storage_Info'])) {
                echo $Sub_Department_Name;
            }
            ?>, GRN Agains Purchase Order</b></legend>
<!--    <table width=100%>
        <tr><td colspan="7"><hr></td></tr>
        <tr id='thead'>
            <td width=4% style='text-align: center;'><b>SN</b></td>
            <td width=6% style='text-align:left;'><b>ORDER N<u>O</u></b></td>
            <td width=15%><b>ORDER DATE & TIME</b></td>
            <td width=10%><b>STORE NEED</b></td>
            <td width=20%><b>SUPPLIER NAME</b></td>
            <td width=40%><b>ORDER DESCRIPTION</b></td>
            <td style='text-align: center;'><b>ACTION</b></td>
        </tr> 
        <tr><td colspan="7"><hr></td></tr>
        <?php
        //select order data
//        $select_Order_Details = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
//                                            po.sub_department_id = sd.sub_department_id and
//                                            po.supplier_id = sp.supplier_id and
//                                            po.order_status = 'submitted' and
//                                            po.Approval_Level = po.Approvals and
//                                            sd.sub_department_id = '$Sub_Department_ID' group by Purchase_Order_ID order by Purchase_Order_ID desc limit 100") or die(mysqli_error($conn));
//
//        $no = mysqli_num_rows($select_Order_Details);
//        if ($no > 0) {
//            while ($row = mysqli_fetch_array($select_Order_Details)) {
//                echo "<tr><td style='text-align:center;'>" . ++$temp . "<b>.</b></td>";
//                echo "<td>" . $row['Purchase_Order_ID'] . "</td>";
//                echo "<td>" . $row['Created_Date'] . "</td>";
//                echo "<td>" . $row['Sub_Department_Name'] . "</td>";
//                echo "<td>" . $row['Supplier_Name'] . "</td>";
//                echo "<td>" . $row['Order_Description'] . "</td>";
//                echo "<td><a href='grnpurchaseorder.php?Purchase_Order_ID=" . $row['Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Process</a></td></tr>";
//            }
//        }
        ?>
    </table>-->
    <table class="table table-condensed" style="background: #FFFFFF">
        <tr>
            <td colspan="10"><hr/></td>
        </tr>
        <tr>
            <td width='3%'><b>S/No.</b></td>
            <td width='9%'><b>SOR N<u>o.</u></b></td>
            <td width='9%'><b>PR N<u>o.</u></b></td>
            <td width='9%'><b>LPO N<u>o.</u></b></td>
            <td width='12%'><b>Created Date</b></td>
            <td width='15%'><b>Supplier</b></td>
            <td width='15%'><b>Purchase Requisition Description</b></td>
            <td width='5%'><b>Action</b></td>
        </tr>
        <tr>
            <td colspan="10"><hr/></td>
        </tr>
        <?php 
            $Sub_Department_ID=$_SESSION['Storage_Info']['Sub_Department_ID'];
            $sql_select_purchase_requisition_result=mysqli_query($conn,"SELECT lpo.local_purchase_order_id,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.store_requesting=sd.Sub_Department_ID AND lpo.store_requesting='$Sub_Department_ID' AND lpo.submitted_for_grn_approval_status='not_submitted'   ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_purchase_requisition_result)>0){
                $count=1;
                while($lpo_rows=mysqli_fetch_assoc($sql_select_purchase_requisition_result)){
                   $Store_Order_ID=$lpo_rows['Store_Order_ID'];
                   $purchase_requisition_description=$lpo_rows['purchase_requisition_description'];
                   $created_date_time=$lpo_rows['created_date_time'];
                   $Employee_Name=$lpo_rows['Employee_Name'];
                   $Supplier_Name=$lpo_rows['Supplier_Name'];
                   $purchase_requisition_id=$lpo_rows['purchase_requisition_id'];
                   $local_purchase_order_id=$lpo_rows['local_purchase_order_id'];
                   $Sub_Department_Name=$lpo_rows['Sub_Department_Name'];
                   $Sub_Department_ID=$lpo_rows['Sub_Department_ID'];
                   echo "<tr>
                            <td>$count.</td>
                            <td>$Store_Order_ID</td>
                            <td>$purchase_requisition_id</td>
                            <td>$local_purchase_order_id</td>
                            <td>$created_date_time</td>
                            <td>$Supplier_Name</td>
                            <td>$purchase_requisition_description</td>
                            <td><a href='grnpurchaseorder.php?purchase_requisition_id=$purchase_requisition_id&local_purchase_order_id=$local_purchase_order_id' class='art-button-green'>Process</a></td>
                        </tr>";
                   $count++;
                }
            }
        ?>
    </table>
    <!-- <iframe src='Grn_Purchase_Order_List_Iframe.php' width=100% height=350px></iframe> -->
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
    function Get_Purchase_Orders() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Order_No = document.getElementById("Order_No").value;

//          if (Order_No == '') {
//              Order_No = '0'; 
//          }

        if (Order_No != '') {
            document.getElementById('Purchase_Order_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function () {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Purchase_Order_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'Get_Purchase_Orders_List.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {

            if (Supplier_ID !='') {
                 document.getElementById("date_To").style = 'border: 3px; text-align: center;width:15%;display:inline';
                 document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%;display:inline';

                document.getElementById('Purchase_Order_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

                if (window.XMLHttpRequest) {
                    myObjectGetPreviousNote = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetPreviousNote.overrideMimeType('text/xml');
                }

                myObjectGetPreviousNote.onreadystatechange = function () {
                    data80 = myObjectGetPreviousNote.responseText;
                    if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                        document.getElementById('Purchase_Order_List').innerHTML = data80;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetPreviousNote.open('GET', 'Get_Purchase_Orders_List.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID, true);
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


<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script src="js/jquery.js"></script>
<script src="js/select2.min.js"></script>
<script src="css/jquery.datetimepicker.js"></script>


<script>
    $(document).ready(function () {
        $('#date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_From').datetimepicker({value: '', step: 1});
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({value: '', step: 1});

        $("select").select2();
    });
</script>
<!--End datetimepicker-->


<?php
include("./includes/footer.php");
?>
