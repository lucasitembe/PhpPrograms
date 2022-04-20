<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");

include("./storeordering_navigation.php");

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Officer';
}


//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}


//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}


//get requisition id
if (isset($_SESSION['General_Order_ID'])) {
    $Store_Order_ID = $_SESSION['General_Order_ID'];
} else {
    $Store_Order_ID = 0;
}

$sub_department = $_SESSION['Storage_Info']['Sub_Department_ID'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Storage_Supervisor'])) {
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
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
    table,
    tr,
    td {
        border-collapse: collapse !important;
        /* border: none !important; */

    }

    tr:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }
</style>
<br /><br />
<fieldset>
    <center>
        <table width="60%">
            <tr>
                <td style='text-align: right;' width=12%><b>Start Date</b></td>
                <td width=30%>
                    <input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;' style='text-align: center;'>
                </td>
                <td style='text-align: right;' width=12%><b>End Date</b></td>
                <td width=30%>
                    <input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;' style='text-align: center;'>
                </td>
                <td style='text-align: center;' width=7%>
                    <input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Previous_Orders()'>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br />
<fieldset style='overflow-y: scroll; height: 500px; background-color: white;' id='Orders_Area'>
    <?php
    if (strtolower($_SESSION['userinfo']['Approval_Orders']) == 'no') {
        echo "<legend align='right'><b>SUBMITTED / PENDING ORDERS</b></legend>";
    } else {
        echo "<legend align='right'><b>SUBMITTED ORDERS</b></legend>";
    }
    ?>

    <table width="100%">
        <tr style="background-color: #ddd;">
            <td style="padding: 8px;" width="5%"><b><center>SN</center></b></td>
            <td style="padding: 8px;" width="7%"><b><center>ORDER NO</center></b></td>
            <td style="padding: 8px;"><b>PREPARED BY</b></td>
            <td style="padding: 8px;"><b>SUB DEPARTMENT NAME</b></td>
            <td style="padding: 8px;"><b>CREATED DATE</b></td>
            <td style="padding: 8px;"><b>SUBMITTED DATE</b></td>
            <td  colspan="2" width="15%" style="text-align: center;padding:8px"><b>ACTIONS</b></td>
        </tr>
        <?php
        $temp = 0;
        if (strtolower($_SESSION['userinfo']['Approval_Orders']) == 'no') {
            $select = mysqli_query($conn, "SELECT Store_Order_ID, emp.Employee_Name, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time
                                   FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                   WHERE Order_Status in ('Submitted', 'pending', 'saved') AND
                                        emp.Employee_ID = so.Employee_ID AND
                                        so.Sub_Department_ID = sd.Sub_Department_ID AND
                                        so.Sub_Department_ID = $sub_department AND
                                        (select count(*) from tbl_store_order_items soi
                                        where soi.Store_Order_ID = so.Store_Order_ID) > 0
                                   ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
        } else {
            $select = mysqli_query($conn, "SELECT Store_Order_ID, emp.Employee_Name, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time
                                   FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                   WHERE Order_Status in ('Submitted', 'pending') AND
                                        emp.Employee_ID = so.Employee_ID AND
                                        so.Sub_Department_ID = sd.Sub_Department_ID AND
                                        so.Sub_Department_ID = $sub_department AND
                                        (select count(*) from tbl_store_order_items soi
                                        where soi.Store_Order_ID = so.Store_Order_ID) > 0
                                   ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
        }
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
        ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 8px;"><center><?=++$temp;?></center></td>
                    <td style="padding: 8px;"><center><?php echo $data['Store_Order_ID']; ?></center></td>
                    <td style="padding: 8px;"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
                    <td style="padding: 8px;"><?php echo $data['Sub_Department_Name']; ?></td>
                    <td style="padding: 8px;"><?php echo $data['Created_Date_Time']; ?></td>
                    <td style="padding: 8px;"><?php echo $data['Sent_Date_Time']; ?></td>
                    <td style="padding: 8px;">
                        <center>
                            <a href="Store_Order_Control_session.php?Section=PreviewSupervisor&Store_Order_ID=<?php echo $data['Store_Order_ID']; ?>" target="_parent" class="art-button-green">APPROVE</a>
                            <a href="storeordering.php?status=edit&Store_Order_ID=<?=$data['Store_Order_ID']?>&NPO=True&StoreOrder=StoreOrderThisPage" class='art-button-green'>EDIT</a>
                        </center>
                    </td>
                </tr>
        <?php
            }
        }

        ?>
    </table>
</fieldset>

<script type="text/javascript">
    function Preview_Order(Store_Order_ID) {
        window.open("previousstoreorderreport.php?Store_Order_ID=" + Store_Order_ID + "&PreviousStoreOrder=PreviousStoreOrderThisPage", "_blank");
    }

    function Cancel_Order(Store_Order_ID) {
        var check = confirm("Are sure you want to cancel this order?");
        if (check) {
            if (window.XMLHttpRequest) {
                myObjectClearStoreOrder = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectClearStoreOrder = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectClearStoreOrder.overrideMimeType('text/xml');
            }

            myObjectClearStoreOrder.onreadystatechange = function() {
                data200 = myObjectClearStoreOrder.responseText;
                if (myObjectClearStoreOrder.readyState == 4) {
                    var feedback = data200;
                    if (feedback == 'Yes') {
                        location.reload();
                    }
                }
            }

            myObjectClearStoreOrder.open('GET', 'Store_Order_Cancel.php?Store_Order_ID=' + Store_Order_ID, true);
            myObjectClearStoreOrder.send();
        }
    }
</script>

<script type="text/javascript">
    function Get_Previous_Orders() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
            document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';

            if (window.XMLHttpRequest) {
                myObjectOrders = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectOrders = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectOrders.overrideMimeType('text/xml');
            }

            myObjectOrders.onreadystatechange = function() {
                data = myObjectOrders.responseText;
                if (myObjectOrders.readyState == 4) {
                    document.getElementById('Orders_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

            myObjectOrders.open('GET', 'Get_Previous_Orders.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Approval_Orders=' + '<?php echo $_SESSION['userinfo']['Approval_Orders']; ?>', true);
            myObjectOrders.send();
        } else {
            if (Start_Date == null || Start_Date == '') {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }
            if (End_Date == null || End_Date == '') {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<?php
include("./includes/footer.php");
?>