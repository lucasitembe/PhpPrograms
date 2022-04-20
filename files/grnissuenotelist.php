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


    //    if(isset($_SESSION['userinfo'])){
    //	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
    //		    echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE</a>";
    //	    }
    //    }

    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
            echo "<a href='grnpreviouslist.php?GrnPreviousList=GrnPreviousListThisPage&from=prevgrn' class='art-button-green'>PREVIOUS GRN</a>"; //against issue note
        }
    }

    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
            echo "<a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
        }
    }

    //get sub departments
    $Search_Values = '';
    $select = mysqli_query($conn, "select Sub_Department_ID from tbl_employee_sub_department where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if ($nm > 0) {
        while ($row = mysqli_fetch_array($select)) {
            if ($Search_Values == '') {
                $Search_Values .= $row['Sub_Department_ID'];
            } else {
                $Search_Values .= ',' . $row['Sub_Department_ID'];
            }
        }
    }
    ?>

<?php
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $select = mysqli_query($conn, "select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Sub_Department_Name = $row['Sub_Department_Name'];
    } else {
        $Sub_Department_Name = '';
    }
} else {
    $Sub_Department_ID = 0;
    $Sub_Department_Name = '';
}
?>

<!--    Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script>
    $(function() {
        $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            //buttonImageOnly: true, 
            //showOn: "both",
            dateFormat: "yy-mm-dd",
            //showAnim: "bounce"
        });

    });
</script>

<!--    end of datepicker script-->

<!--    Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script>
    $(function() {
        $("#date2").datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            //buttonImageOnly: true, 
            //showOn: "both",
            dateFormat: "yy-mm-dd",
            //showAnim: "bounce"
        });

    });
</script>
<!--    end of datepicker script-->


<?php
if (isset($_POST['submit'])) {
    $Date_From = $_POST['Date_From'];
    $Date_To = $_POST['Date_To'];
} else {
    $Date_From = '';
    $Date_To = '';
}
?>

<br /><br />

<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
    }

    tr:hover {
        background-color: #eeeeee;
        cursor: progress;
    }

    select {
        padding: 5px;
    }
</style>

<center>
    <fieldset>
        <table width='100%'>
            <tr>
                <td style="text-align:center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_From" placeholder="Start Date" />
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_To" placeholder="End Date" />&nbsp;
                    <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress="clearOrder()" id='Order_No' placeholder='~~~~~~~ISSUE NUMBER~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Get_Previous_Issue_Notes()">
                </td>
            </tr>
        </table>
    </fieldset>
</center>
<br />
<fieldset style='overflow-y: scroll; height: 500px; background-color:white;color:black' id='Previous_Fieldset_List'>
    <legend style='background-color:#006400;color:white;padding:5px;' align=right><b>&nbsp;&nbsp;GRN Against Issue Note, List Of Issues&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
    <center>
        <table width="100%">
            <tr id='thead' style="background-color: #eee;">
                <td width=4% style='text-align: center;padding:8px'><b>SN</b></td>
                <td width=7% style='text-align: left;padding:8px'><b>ISSUE N<u>O</u></b></td>
                <td width=10% style='text-align: left;padding:8px'><b>REQUISITION N<u>O</u></b></td>
                <td width=12% style='text-align: left;padding:8px'><b>REQUESTED DATE</b></td>
                <td width=16% style='text-align: left;padding:8px'><b>REQUISITION PREPARED BY</b></td>
                <td width=12% style="padding: 8px;"><b>ISSUE DATE & TIME</b></td>
                <td width=15% style="padding: 8px;"><b>STORE NEED</b></td>
                <td width=15% style="padding: 8px;"><b>STORE ISSUE</b></td>
                <td width=15% style="padding: 8px;"><b>DESCRIPTION</b></td>
                <td style='text-align: center;padding:8px' width=13%><b>ACTION</b></td>
            </tr>
            <?php
            $temp = 1;
            //get top 50 grn open balances based on selected employee id
            $Store_Need = $_SESSION['Storage_Info']['Sub_Department_ID'];
            $Sub_Department_Name = $_SESSION['Storage'];
            $sql_select = mysqli_query($conn, "select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
									tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
									rq.store_issue = sd.sub_department_id and
									emp.employee_id = rq.employee_id and
									rq.requisition_status = 'served' and
									isu.Requisition_ID = rq.Requisition_ID and Store_Need='$Store_Need' order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_select);


            if ($num > 0) {
                while ($row = mysqli_fetch_array($sql_select)) {
                    $Store_Need = $row['Store_Need'];

                    //get store need
                    $get_store = mysqli_query($conn, "select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
                    $get_store_num = mysqli_num_rows($get_store);
                    if ($get_store_num > 0) {
                        while ($dt = mysqli_fetch_array($get_store)) {
                            $S_Need = $dt['Sub_Department_Name'];
                        }
                    } else {
                        $S_Need = '';
                    }
                    $Requisition_ID = $row['Requisition_ID'];
                    echo '<tr style="background-color:#fff"><td style="text-align: center;">' . $temp . '</td>
                            <td style="padding:8px">' . $row['Issue_ID'] . '</td>
                            <td style="padding:8px">' . $row['Requisition_ID'] . '</td>
                            <td style="padding:8px">' . $row['Sent_Date_Time'] . '</td>
                            <td style="padding:8px">' . $row['Employee_Name'] . '</td>	
                            <td style="padding:8px">' . $row['Issue_Date_And_Time'] . '</td>	
                            <td style="padding:8px">' . $S_Need . '</td> 	
                            <td style="padding:8px">' . $row['Sub_Department_Name'] . '</td> 	
                            <td style="padding:8px">' . $row['Requisition_Description'] . '</td> 
                            <td style="text-align: center;padding:8px">
                            <a href="Control_Grn_Session.php?New_Grn=True&Issue_ID=' . $row['Issue_ID'] . '&Requisition_ID=' . $Requisition_ID . '" class="art-button-green">&nbsp;&nbsp;&nbsp;Process&nbsp;&nbsp;&nbsp;</a>
                            </td>
                            
                        </tr>';
                    $temp++;
                }
            }
            echo '</table>';
            ?>
</fieldset>

<script>
    function Get_Previous_Issue_Notes() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Order_No = document.getElementById("Order_No").value;

        if (Order_No != '') {
            document.getElementById('Previous_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            if (window.XMLHttpRequest) {
                myObjectGetPreviousNote = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPreviousNote.overrideMimeType('text/xml');
            }

            myObjectGetPreviousNote.onreadystatechange = function() {
                data80 = myObjectGetPreviousNote.responseText;
                if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                    document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPreviousNote.open('GET', 'Get_List_Of_Issue_Notes.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {

            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {

                document.getElementById('Previous_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

                if (window.XMLHttpRequest) {
                    myObjectGetPreviousNote = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGetPreviousNote = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetPreviousNote.overrideMimeType('text/xml');
                }

                myObjectGetPreviousNote.onreadystatechange = function() {
                    data80 = myObjectGetPreviousNote.responseText;
                    if (myObjectGetPreviousNote.readyState == 4 && myObjectGetPreviousNote.status == 200) {
                        document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetPreviousNote.open('GET', 'Get_List_Of_Issue_Notes.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date, true);
                myObjectGetPreviousNote.send();
            } else {

                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    document.getElementById("date").focus();
                } else {
                    document.getElementById("date").style = 'border: 3px; text-align: center;width:15%;display:inline';
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date2").style = 'border: 3px solid red; text-align: center;width:15%;display:inline';
                    document.getElementById("date2").focus();
                } else {
                    document.getElementById("date2").style = 'border: 3px; text-align: center;width:15%;display:inline';
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
    function clearOption(opt) {
        document.getElementById("Order_No").value = '';
    }

    function clearOrder() {
        document.getElementById("date_From").value = '';
        document.getElementById("date_To").value = '';
    }
</script>
<script>
    $(document).ready(function() {
        $('#date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_From').datetimepicker({
            value: '',
            step: 1
        });
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({
            value: '',
            step: 1
        });

        $("select").select2();
    });
</script>
<?php
include('./includes/footer.php');
?>