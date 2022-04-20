<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
include_once("./functions/grnwithoutpurchaseorder.php");
include_once("./functions/employee.php");
include_once("./functions/supplier.php");
include("./includes/functions.php");

include_once("./grnwithoutpurchaseorder_navigation.php");

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

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_ID = '';
    $Sub_Department_Name = '';
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
?>


<style>
    table,tr,td{ border-collapse:collapse !important; border:none !important; }
    tr:hover{ background-color:#eeeeee; cursor:pointer; }
</style> 

<br/><br/>
<center>
    <fieldset>  
        <table width='100%'> 
            <tr> 
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select  id='Supplier_ID' style='text-align: center;width:20%;display:inline' onchange ="clearOption()">
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
                    <select id="Employee_ID" name="Employee_ID" style='text-align: center;width:15%;display:inline'  onchange ="clearOption()">
                        <option value="">All Receiver</option>
                        <?php
                        $Employees = Get_Employee_All();
                        foreach ($Employees as $Employee) {
                            echo "<option value='{$Employee['Employee_ID']}'> {$Employee['Employee_Name']} </option>";
                        }
                        ?>
                    </select>
                    <input type='text' name='Order_No' style='text-align: center;width:20%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~Order No~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Get_Previous_Grn()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>

<script src='js/functions.js'></script>

<script>
                        addDatePicker($("#Date_From"));
                        addDatePicker($("#Date_To"));
</script>

<br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Previous_Fieldset_List'>
    <legend style='background-color:#006400;color:white;padding:5px;' align=right>
        <b><?php
            if (isset($_SESSION['Storage_Info'])) {
                echo $Sub_Department_Name;
            }
            ?>, Previous GRN Without Purchase Order</b>
    </legend>

    <?php
    $temp = 1;
    echo "<tr><td colspan=9><hr></td></tr>";
    echo "<center><table width = 100% border=0>";
    echo "<tr id='thead'>
                <td width=4% style='text-align: center;'><b>SN</b></td>
                <td width=10% style='text-align: center;'><b>GRN N<u>O</u></b></td>
                <td width=15%><b>Created Date & Time</b></td>
                <td width=15%><b>SUPERVISOR NAME</b></td>
                <td width=12%><b>RECEIVER NAME</b></td>
                <td width=15%><b>SUPPLIER NAME</b></td>
                <td width=15%><b>LOCATION</b></td>
                <td style='text-align: center;' width=10%><b>ACTION</b></td>
                </tr>";
    echo '<tr><td colspan="9"><hr></td></tr>';

    $GRN_Without_Purchase_Order_List = Get_GRN_Without_Purchase_Order_List($Sub_Department_ID, null, null, null, null,null, 200);
    foreach ($GRN_Without_Purchase_Order_List as $GRN_Without_Purchase_Order) {
        
         $href = '';
            if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                $href = "<a href='Edit_grnwithoutpurchaseorder.php?Grn_ID=" . $GRN_Without_Purchase_Order['Grn_ID'] . "' class='art-button-green'>Edit</a>";
            }
            $GRN_ID_EMPTY=$GRN_Without_Purchase_Order['Grn_ID'];

                $select_grn_ID = mysqli_query($conn,"SELECT Grn_ID FROM tbl_grn_without_purchase_order_items WHERE Grn_ID='$GRN_ID_EMPTY'");
                if(mysqli_num_rows($select_grn_ID) >0){
                    //get supervisor name
                    $Supervisor_ID = $GRN_Without_Purchase_Order['Supervisor_ID'];
                    $Supervisor = Get_Employee($Supervisor_ID);
                    $Supervisor_Name = $Supervisor['Employee_Name'];
                    echo "<tr>";
                    echo "<td style='text-align: center;'> {$temp} </td>";
                    echo "<td style='text-align: center;'> {$GRN_Without_Purchase_Order['Grn_ID']} </td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Grn_Date_And_Time'] . "</td>";
                    echo "<td>" . ucwords(strtolower($Supervisor_Name)) . "</td>";
                    echo "<td>" . ucwords(strtolower($GRN_Without_Purchase_Order['Employee_Name'])) . " </td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Supplier_Name'] . "</td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Sub_Department_Name'] . "</td>";
                    echo "<td style='text-align: center;'>";
                    echo "<a target='_blank' class='art-button-green'";
                    echo "  href='Session_Control_Grn_Without_Perchase_Order.php?Status=Previous&Grn_ID=";
                    echo $GRN_Without_Purchase_Order['Grn_ID'] . "'";
                    echo ">Preview</a>";
                    
                    echo "</td>
                            <td>$href</td>      
                            </tr>";
                    
                    $temp++;
                }
        
    }
    echo '</table>';
    ?>
</fieldset>
<script>
    function clearOption(opt) {
        document.getElementById("Order_No").value = '';
    }

    function clearOrder() {
        if ($("#Supplier_ID").val() != '') {
            $("#Supplier_ID").val('').trigger('change');
        }if ($("#Employee_ID").val() != '') {
            $("#Employee_ID").val('').trigger('change');
        }

        document.getElementById("Date_From").value = '';
        document.getElementById("Date_To").value = '';
    }
</script>
<script>
    function Get_Previous_Grn() {
        var Start_Date = document.getElementById("Date_From").value;
        var End_Date = document.getElementById("Date_To").value;
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Employee_ID = $("#Employee_ID").val();
        var Order_No = document.getElementById("Order_No").value;


        if (Order_No != '') {
            document.getElementById('Previous_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function () {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'Get_Previous_GRN_Without_Purchase_Order.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                showPleaseWaitDialog();

                if (window.XMLHttpRequest) {
                    myObjectGetPrevious = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetPrevious.overrideMimeType('text/xml');
                }

                myObjectGetPrevious.onreadystatechange = function () {
                    data80 = myObjectGetPrevious.responseText;
                    if (myObjectGetPrevious.readyState == 4) {
                        document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                    }
                    hidePleaseWaitDialog();
                }; //specify name of function that will handle server response........

                myObjectGetPrevious.open('GET', 'Get_Previous_GRN_Without_Purchase_Order.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Supplier_ID=' + Supplier_ID + '&Employee_ID=' + Employee_ID, true);
                myObjectGetPrevious.send();
            } else {

                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;width:15%;';
                    document.getElementById("Date_From").focus();
                } else {
                    document.getElementById("Date_From").style = 'border: 3px; text-align: center;width:15%;';
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;width:15%;';
                    document.getElementById("Date_To").focus();
                } else {
                    document.getElementById("Date_To").style = 'border: 3px; text-align: center;width:15%;';
                }
            }
        }
    }
</script>
<script>
    $(document).ready(function () {
        $("select").select2();
    });
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">


<?php include_once('./functions/scripts.php'); ?>
<script src="js/select2.min.js"></script>
<?php include_once('./includes/footer.php'); ?>