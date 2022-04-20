<?php
include("./includes/header.php");
include("./includes/connection.php");

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
        echo "<a href='Control_Grn_Open_Balance_Sessions.php?New_Grn_Open_Balance=True&Status=new' class='art-button-green'>NEW OPEN BALANCE</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='penginggpnopenbalance.php?PendingGrnOpenBalance=PendingGrnOpenBalanceThisPage' class='art-button-green'>PENDING OPEN BALANCES</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='Control_Grn_Open_Balance_Sessions.php?Previous_Grn_Open_Balance=True&Status=Previous' class='art-button-green'>PREVIOUS OPEN BALANCES</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage' class='art-button-green'>BACK</a>";
    }
}
?>

<style>
    table,tr,td{
        //border-collapse:collapse !important;
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
                    <select  id='Employee_ID' style='text-align: center;width:25%;display:inline' onchange ="clearOption()">
                        <option value="">All Employee</option>
                        <?php
                        $qr = "SELECT * FROM tbl_employee ORDER BY Employee_Name ASC";
                        $supplier_results = mysqli_query($conn,$qr);
                        while ($supplier_rows = mysqli_fetch_assoc($supplier_results)) {
                            ?>
                            <option value='<?php echo $supplier_rows['Employee_ID']; ?>'><?php echo strtoupper($supplier_rows['Employee_Name']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~GRN NUMBER~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Filter_Grn()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>	
<fieldset style='overflow-y: scroll; height: 390px; background-color: white;' id='Grn_Fieldset_List'>
    <legend align=right><b>Pending Grn Open Balances prepared by : <?php echo ucwords(strtolower($Employee_Name)); ?></b></legend>
    <?php
    $temp = 0;
    echo '<center><table width = 100% border=0>';
    echo "<tr><td colspan='7'><hr></td></tr>";
    echo '<tr>
                    <td width=5% style="text-align: center;"><b>SN</b></td>
                    <td width=10% style="text-align: center;"><b>GRN NUMBER</b></td>
                    <td width=15%><b>PREPARED BY</b></td>
                    <td width=15%><b>LOCATION</b></td>
                    <td width=15%><b>CREATED DATE</b></td>
                    <td width=30%><b>GRN DESCRIPTION</b></td>
                    <td width=7%></td></tr>';
    echo "<tr><td colspan='7'><hr></td></tr>";

    //get top 50 grn open balances based on selected employee id

    $sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_Description, gob.Grn_Open_Balance_ID
                                    from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
                                    emp.Employee_ID = gob.Employee_ID and
                                    sd.Sub_Department_ID = gob.Sub_Department_ID and
                                    gob.Employee_ID = '$Employee_ID' and
                                    gob.Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            //check if this grn already processed
            $Grn_Open_Balance_ID=$row['Grn_Open_Balance_ID'];
            $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='grn_physical_counting_as_open_balance' AND document_number='$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_if_all_approve_result)>0){}else{
            
            
            echo '<tr><td style="text-align: center;">' . ++$temp . '</td>
                        <td style="text-align: center;">' . $row['Grn_Open_Balance_ID'] . '</td>
                        <td>' . ucwords(strtolower($row['Employee_Name'])) . '</td>
                        <td>' . ucwords(strtolower($row['Sub_Department_Name'])) . '</td>
                        <td>' . $row['Created_Date_Time'] . '</td>
                        <td>' . $row['Grn_Open_Balance_Description'] . '</td>
                        <td><a href="Control_Grn_Open_Balance_Sessions.php?Pending_Grn_Open_Balance=True&Grn_Open_Balance_ID=' . $row['Grn_Open_Balance_ID'] . '" class="art-button-green">Process</a></td></tr>';
                }
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
        if ($("#Employee_ID").val() != '') {
            $("#Employee_ID").val('').trigger('change');
        }

        document.getElementById("date_From").value = '';
        document.getElementById("date_To").value = '';
    }
</script>
<script type="text/javascript">
    function Filter_Grn() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Order_No = document.getElementById("Order_No").value;
        var Employee_ID = document.getElementById("Employee_ID").value;

        if (Order_No != '') {
            document.getElementById('Grn_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function () {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Grn_Fieldset_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'Filter_Grn_Open_Balance.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                document.getElementById('Grn_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

                if (window.XMLHttpRequest) {
                    myObjectFilterGrn = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectFilterGrn = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectFilterGrn.overrideMimeType('text/xml');
                }

                myObjectFilterGrn.onreadystatechange = function () {
                    data8099 = myObjectFilterGrn.responseText;
                    if (myObjectFilterGrn.readyState == 4 && myObjectFilterGrn.status == 200) {
                        document.getElementById('Grn_Fieldset_List').innerHTML = data8099;
                    }
                }; //specify name of function that will handle server response........

                myObjectFilterGrn.open('GET', 'Filter_Grn_Open_Balance.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date  + '&Employee_ID=' + Employee_ID, true);
                myObjectFilterGrn.send();
            } else {
                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
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
include('./includes/footer.php');
?>