<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/department.php");
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

//get Storage name & ID
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
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
</style>
<br/><br/>
<center>
    <fieldset>  
        <table width='100%'> 
            <tr> 
                <td style="text-align:center">    
                    <input type="text" autocomplete="off" style='text-align: center;width:12%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:12%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <select  id='Employee_ID' style='text-align: center;width:18%;display:inline' onchange ="clearOption()">
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
                    <input type='text' name='Order_No' style='text-align: center;width:16%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~GRN NUMBER~~~~~~~'>
                    <label style='text-align: center; display:inline;'>&nbsp;&nbsp;Store Name</label>
                    <select name="Sub_Department_ID" id="Sub_Department_ID" style='text-align: center;width:15%;display:inline'>
                        <option value="0">All</option>
                        <?php
                            $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                            foreach ($Sub_Department_List as $Sub_Department) {
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="Filter_Previous_Grn_Open_Balances()">
                </td>
            </tr>
        </table>
    </fieldset>  
</center>

<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Grn_Fieldset_List'>
    <legend align=right><b>Previous Grn Open Balances</b></legend>
    <?php
    $temp = 0;
    echo '<center><table width = 100% border=0><tr><td colspan="8"><hr></td></tr>';
    echo '<tr><td width=5% style="text-align: center;"><b>SN</b></td>
				<td width=7%><b>GRN NUMBER</b></td>
				<td width=10%><b>LOCATION</b></td>
				<td width=10%><b>PREPARED BY</b></td>
				<td width=15%><b>CREATED DATE</b></td>
				<td width=20%><b>GRN DESCRIPTION</b></td>
				<td width=15%><b>SUPERVISOR NAME</b></td>
				<td width=25% colspan=2></td>
                           </tr>
                           <tr>
                             <td colspan="8"><hr></td>
                          </tr>';

    //get top 50 grn open balances based on selected employee id
    $sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_Description, gob.Employee_ID
									from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
									emp.Employee_ID = gob.Supervisor_ID and
									sd.Sub_Department_ID = gob.Sub_Department_ID and
									gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc limit 100") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
               $href = '';
            if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                $href = '<a href="editgrnopenbalance.php?Grn_Open_Balance_ID=' . $row['Grn_Open_Balance_ID'] . '" class="art-button-green" >Edit</a>';
            }
            //get employee prepared
            $Prep_Employee = $row['Employee_ID'];
            $sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
            $Pre_no = mysqli_num_rows($sel);
            if ($Pre_no > 0) {
                while ($dt = mysqli_fetch_array($sel)) {
                    $Created_By = $dt['Employee_Name'];
                }
            } else {
                $Created_By = '';
            }
            echo '<tr><td style="text-align: center;">' . ++$temp . '</td>
					<td>' . $row['Grn_Open_Balance_ID'] . '</td>
					<td>' . $row['Sub_Department_Name'] . '</td>
					<td>' . $Created_By . '</td>
					<td>' . $row['Created_Date_Time'] . '</td>
					<td>' . $row['Grn_Open_Balance_Description'] . '</td>
					<td>' . $row['Employee_Name'] . '</td>
					<td style="text-align: center;">
                                        <input type="button" name="Preview" id="Preview" class="art-button-green" value="Preview" onclick="Preview_Details(' . $row['Grn_Open_Balance_ID'] . ')">
                                        '.$href.'    
                              </td>
                         </tr>';
        }
    }
    echo '<tr><td colspan="8"><hr></td></tr></table>';
    ?>
</fieldset>

<div id="Preview_Details" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

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
    function Preview_Details(Grn_Open_Balance_ID) {
        if (window.XMLHttpRequest) {
            myObjectAddItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function () {
            data2922 = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data2922;
                $("#Preview_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectAddItem.open('GET', 'Preview_Grn_Details.php?Grn_Open_Balance_ID=' + Grn_Open_Balance_ID, true);
        myObjectAddItem.send();
    }
</script>
<script>
    function Filter_Previous_Grn_Open_Balances() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Order_No = document.getElementById("Order_No").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

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

            myObjectGetPreviousNote.open('GET', 'Filter_Previous_Grn_Open_Balance.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                document.getElementById('Grn_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                if (Start_Date > End_Date) {
                    alert("Start date should be before end date");
                } else {
                   
                    if (window.XMLHttpRequest) {
                        myObjectFilter = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectFilter.overrideMimeType('text/xml');
                    }

                    myObjectFilter.onreadystatechange = function () {
                        data20 = myObjectFilter.responseText;
                        if (myObjectFilter.readyState == 4) {
                            document.getElementById("Grn_Fieldset_List").innerHTML = data20;
                        }
                    }; //specify name of function that will handle server response........
                    myObjectFilter.open('GET', 'Filter_Previous_Grn_Open_Balance.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date  + '&Employee_ID=' + Employee_ID+'&Sub_Department_ID='+Sub_Department_ID, true);
                    myObjectFilter.send();
                }
            } else {
                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;width:12%;display:inline';
                    //document.getElementById("date_To").focus();
                } else {
                    document.getElementById("date_To").style = 'border: 3px; text-align: center;width:12%;display:inline';
                }

                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;width:12%;display:inline';
                    //document.getElementById("date_From").focus();
                } else {
                    document.getElementById("date_From").style = 'border: 3px; text-align: center;width:12%;display:inline';
                }
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $("#Preview_Details").dialog({autoOpen: false, width: '95%', height: 600, title: 'GRN OPEN BALANCE DETAILS', modal: true});
    });
</script>


<script type="text/javascript">
    function Preview_Report(Grn_Open_Balance_ID) {
        var winClose = popupwindow('Preview_Grn_Details_Report.php?Grn_Open_Balance_ID=' + Grn_Open_Balance_ID, 'GRN DETAILS', 1200, 500);
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

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
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
include('./includes/footer.php');
?>