<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/functions.php");
$temp = 1;

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

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_Name = '';
}

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //get number of pending request				
        // $select_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
        //                                         po.sub_department_id = sd.sub_department_id and
        //                                         po.supplier_id = sp.supplier_id and
        //                                         po.order_status = 'submitted' and
        //                                         sd.Sub_Department_ID = '$Sub_Department_ID' group by purchase_order_id") or die(mysqli_error($conn));
        // $number = mysqli_num_rows($select_Order_Number);
        echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage' class='art-button-green'>NEW GRN</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnpurchaseorder.php?GrnPurchaseOrder=GrnPurchaseOrderThisPage' class='art-button-green'>BACK</a>";
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
                    <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~GRN No~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Filter_Previous_Grn_List()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Grn_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align='right'><b><?php
            if (isset($_SESSION['Storage_Info'])) {
                echo $Sub_Department_Name;
            }
            ?>, Previous GRN Agains Purchase Order</b></legend>
    <table width=100% style="border-collapse:collapse !important; border:none !important;">
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width=4% style='text-align: center;'><b>SN</b></td>
            <td width=6% style='text-align:center;'><b>Order N<u>o</u></b></td>
            <td width=6% style='text-align:center;'><b>Grn N<u>o</u></b></td>
            <td width=15%><b>Order Date & Time</b></td>
            <td width=10%><b>Store Need</b></td>
            <td width=20%><b>Supplier Name</b></td>
            <td width=40%><b>Order Description</b></td>
        </tr> 
        <tr><td colspan="9"><hr></td></tr>
        <?php
       
        //select order data
        $select_Order_Details = mysqli_query($conn,"select 'actual' as source, po.Purchase_Order_ID,poi.Grn_Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from tbl_purchase_order po,tbl_purchase_order_items poi, tbl_sub_department sd, tbl_supplier sp where
                                            po.sub_department_id = sd.sub_department_id and
                                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                            po.Supplier_ID = sp.Supplier_ID and
                                            po.order_status = 'served' and
                                            sd.Sub_Department_ID = '$Sub_Department_ID'
                                            
                                         UNION
                                         
                                         select 'pending' as source,  po.Purchase_Order_ID,poi.Grn_Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from tbl_purchase_order po,tbl_pending_purchase_order_items poi, tbl_sub_department sd, tbl_supplier sp where
                                            po.sub_department_id = sd.sub_department_id and
                                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                            po.Supplier_ID = sp.Supplier_ID and
                                            po.order_status = 'served' and
                                            sd.Sub_Department_ID = '$Sub_Department_ID'
                                         
                                         
               
                 group by Grn_Purchase_Order_ID order by Grn_Purchase_Order_ID desc limit 100") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Order_Details);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_Order_Details)) {
                 $href = '';
       if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
           if($row['source']=='pending'){
                    $href = "<a href='grnpendingpurchaseorderpreview.php?src=edit&Purchase_Order_ID=" . $row['Purchase_Order_ID'] . "&Grn_Purchase_Order_ID=" . $row['Grn_Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Edit</a>";
        }else{
                    $href = "<a href='grnpurchaseorder.php?src=edit&Purchase_Order_ID=" . $row['Purchase_Order_ID'] . "&Grn_Purchase_Order_ID=" . $row['Grn_Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Edit</a>";
           }
        }                       
        
                echo "<tr><td style='text-align:center;'>" . $temp . "</td>";
                echo "<td style='text-align: center;'>" . $row['Purchase_Order_ID'] . "</td>";
                 echo "<td style='text-align: center;'>" . $row['Grn_Purchase_Order_ID'] . "</td>";
                echo "<td>" . $row['Created_Date'] . "</td>";
                echo "<td>" . $row['Sub_Department_Name'] . "</td>";
                echo "<td>" . $row['Supplier_Name'] . "</td>";
                echo "<td>" . $row['Order_Description'] . "</td>";
                echo "<td>"
                . "$href"
                . "</td>";
                
                echo "<td>"
                . "<a href='grnpurchaseorderreport.php?Grn_Purchase_Order_ID= ".$row['Grn_Purchase_Order_ID']."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Blank' class='art-button-green'>Preview GRN </a>
                       "
                . "</td>";
                
                   echo     "</tr>";
                $temp++;
            }
            echo "</tr>";
        }
        ?>
    </table>
    <!-- <iframe src='Previous_Grn_Purchase_Order_List_Iframe.php'  width=100% height=390px></iframe> -->
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
    function Filter_Previous_Grn_List() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Order_No = document.getElementById("Order_No").value;

        if (Order_No != '') {
            document.getElementById('Grn_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function () {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Grn_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'Filter_Previous_Grn_List.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Supplier_ID !='') {
                 document.getElementById("date_To").style = 'border: 3px; text-align: center;width:15%;display:inline';
                 document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%;display:inline';
                 document.getElementById('Grn_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

                if (window.XMLHttpRequest) {
                    myObjectFilterGrn = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectFilterGrn = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectFilterGrn.overrideMimeType('text/xml');
                }
                myObjectFilterGrn.onreadystatechange = function () {
                    data26 = myObjectFilterGrn.responseText;
                    if (myObjectFilterGrn.readyState == 4) {
                        document.getElementById('Grn_List').innerHTML = data26;
                    }
                }; //specify name of function that will handle server response........

                myObjectFilterGrn.open('GET', 'Filter_Previous_Grn_List.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID, true);
                myObjectFilterGrn.send();
            } else {
                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_From").focus();
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    // document.getElementById("date_To").focus();
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
