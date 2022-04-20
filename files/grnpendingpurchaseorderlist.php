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
        if(isset($_GET['from']) && $_GET['from'] == "purchaseorder") {
            echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage&from=purchaseorder' class='art-button-green'>BACK</a>";
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
            ?>, GRN Agains Pending Purchase Order</b></legend>
    <table width=100%>
        <tr><td colspan="7"><hr></td></tr>
        <tr id='thead'>
            <td width=4% style='text-align: center;'><b>SN</b></td>
            <td width=6% style='text-align:left;'><b>ORDER N<u>O</u></b></td>
            <td width=15%><b>DATE & TIME</b></td>
            <td width=10%><b>STORE NEED</b></td>
            <td width=20%><b>SUPPLIER NAME</b></td>
            <td width=40%><b>ORDER DESCRIPTION</b></td>
            <td style='text-align: center;'><b>ACTION</b></td>
        </tr> 
        <tr><td colspan="7"><hr></td></tr>
        <?php
        
        //select order data
        $select_Order_Details = mysqli_query($conn,"select po.Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from  tbl_purchase_order po,tbl_pending_purchase_order_items poi,tbl_sub_department sd, tbl_supplier sp  where
                                                po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                                 po.Supplier_ID = sp.Supplier_ID and
                                                po.Sub_Department_ID = sd.Sub_Department_ID and
                                                LOWER(poi.Grn_Status) = 'pending' and
                                                sd.sub_department_id = '$Sub_Department_ID' and poi.Grn_Purchase_Order_ID IS NULL group by Purchase_Order_ID order by Purchase_Order_ID desc limit 500") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Order_Details);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_Order_Details)) {
                echo "<tr><td style='text-align:center;'>" . ++$temp . "<b>.</b></td>";
                echo "<td>" . $row['Purchase_Order_ID'] . "</td>";
                echo "<td>" . $row['Created_Date'] . "</td>";
                echo "<td>" . $row['Sub_Department_Name'] . "</td>";
                echo "<td>" . $row['Supplier_Name'] . "</td>";
                echo "<td>" . $row['Order_Description'] . "</td>";
                echo "<td><a href='grnpendingpurchaseorder.php?Purchase_Order_ID=" . $row['Purchase_Order_ID'] ."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Process</a></td></tr>";
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

            myObjectGetPreviousNote.open('GET', 'Get_Pending_Purchase_Orders_List.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
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

                myObjectGetPreviousNote.open('GET', 'Get_Pending_Purchase_Orders_List.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID, true);
                myObjectGetPreviousNote.send();
            } else {

                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    document.getElementById("date_From").focus();
                } else {
                    document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%;display:inline';
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    document.getElementById("date_To").focus();
                } else {
                    document.getElementById("date_To").style = 'border: 3px;  text-align: center;width:15%;display:inline';
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
<?php
include("./includes/footer.php");
?>
