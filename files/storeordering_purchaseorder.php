<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
include_once("./storeordering_navigation.php");

include_once("./functions/supplier.php");
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
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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


    <script>
        $(function () {
            addDatePicker($("#date_From"));
            addDatePicker($("#date_To"));
            $('#Supplier_ID').select2();
        });
    </script>

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
        <legend align=right><b>PURCHASE ORDERS</b></legend>
        <table width=100% border=0>
            <tr><td colspan="8"><hr></td></tr>
            <tr id='thead'>
                <td width=4% style='text-align: center;'><b>Sn</b></td>
                <td width=12% style='text-align: center;'><b>Order Number</b></td>
                <td width=12% style='text-align: center;'><b>Store Order Number</b></td>
                <td width=13%><b>Order Date & Time</b></td>
                <td width=10%><b>Store Need</b></td>
                <td width=15%><b>Supplier Name</b></td>
                <td width=20%><b>Order Description</b></td>
                <td style='text-align: center;' width="10%"><b>Action</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
            <?php
            //get details
            $select_data = "SELECT po.Purchase_Order_ID, po.Store_Order_ID, po.Created_Date, sd.Sub_Department_Name,
                                   po.Order_Description, sp.Supplier_Name
                                FROM tbl_purchase_order po, tbl_sub_department sd, tbl_employee emp, tbl_supplier sp
                                WHERE po.sub_department_id = sd.sub_department_id AND
                                po.Supplier_ID = sp.Supplier_ID AND
                                emp.employee_id = po.employee_id AND
                                po.order_status IN ('submitted','Served')
                                ORDER BY po.Purchase_Order_ID DESC
                                limit 100";

            $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
            while($row = mysqli_fetch_array($result)){
                echo "<tr><td style='text-align: center;'>".$temp."</td>";
                echo "<td style='text-align: center;'>".$row['Purchase_Order_ID']."</td>";
                echo "<td style='text-align: center;'><a href='previousstoreorderreport.php?Store_Order_ID=";
                echo $row['Store_Order_ID']."&PreviousStoreOrder=PreviousStoreOrderThisPage' target='_blank'>";
                echo $row['Store_Order_ID'];
                echo "</a></td>";
                echo "<td>".$row['Created_Date']."</td>";
                echo "<td>".$row['Sub_Department_Name']."</td>";
                echo "<td>".$row['Supplier_Name']."</td>";
                echo "<td>".$row['Order_Description']."</td>";
                echo "<td width=4% style='text-align: center;'>
                        <input type='button' name='Display' id='Display' value='PREVIEW REPORT' class='art-button-green' onclick='Preview_Purchase_Order_Report(".$row['Purchase_Order_ID'].")'>
                        </td></tr>";
                $temp++;
            }
            ?>
            <!--<iframe src='Previous_Orders_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>' width=100% height=350px></iframe>-->
        </table>
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

//          if (Order_No == '') {
//              Order_No = '0'; 
//          }

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

            myObjectGetPreviousNote.open('GET', 'Preview_Previous_Order_Filter.php?Order_No=' + Order_No, true);
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

                myObjectGetPreviousNote.open('GET', 'Preview_Previous_Order_Filter.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID, true);
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
<?php
include('./includes/footer.php');
?>