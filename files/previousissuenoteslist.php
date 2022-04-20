<script src='js/functions.js'></script>
<!--<script src="jquery.js"></script>-->

<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>

<?php
include("./includes/header.php");
include("./includes/connection.php");

include("./functions/issuenotes.php");
include("./functions/department.php");

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
        echo "<a href='listofissuenotes.php?NewIssueNote=NewIssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='previousissuenoteslist.php?PreviousIssueNoteListPage=True' class='art-button-green'>PREVIOUS ISSUES</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //	    echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>BACK</a>";
        echo "<a href='issuenotes.php?IssueNotes=IssueNoteThisPage' class='art-button-green'>BACK</a>";
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

<script>
    $(function() {
        addDatePicker($("#date"));
        addDatePicker($("#date2"));
    });
</script>

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
        border: none !important;
    }

    tr:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }
</style>

<center>
    <fieldset>
        <table width='100%'>
            <tr>
                <td style="text-align:center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' onfocus="clearOption(this.value)" id="date" placeholder="Start Date" />
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date2" placeholder="End Date" />&nbsp;

                    <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress="clearOrder()" id='Order_No' placeholder='~~~~~~~ISSUE NUMBER~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="Get_Previous_Issue_Notes()">
                </td>
            </tr>
        </table>
    </fieldset>
</center>
<br />

<style>
    .issue_notes #thead td, .issue_notes .tbody td{
        border: 1px solid #ccc !important;
    }
</style>

<fieldset style='overflow-y: scroll; height: 410px;' id='Previous_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align=right>
        <b>
        <?php if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
            echo $Sub_Department_Name;
        } ?> 
            ~ Previous Issue Notes (Electronic)
        </b>
    </legend>
    <center>
        <table style='margin-top:8px' width=100% class='issue_notes'>
            <tr id='thead'>
            
            <td width=4% style='text-align: center;'><b>Sn</b></td>
            <td width=5% style='text-align: left;'><b>Issue N<u>o</u></b></td>
            <td width=5% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
            <td width=10% style='text-align: left;'><b>Requested Date</b></td>
            <td width=10% style='text-align: left;'><b>Prepared By</b></td>
            <td width=14%><b>Issue Date & Time</b></td>
            <td width=13%><b>Store Need</b></td>
            <td width=15%><b>Requisition Description</b></td>
            <td style='text-align: center;' width=30%><b>Action</b></td>
            
            </tr>

            <?php
            $temp = 1;
            //get top 50 issue notes based on selected employee id
            $Sub_Department_Name = $_SESSION['Storage'];
            $sql_select = mysqli_query($conn, "SELECT * FROM tbl_issues iss, tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    sd.sub_department_id = rq.Store_Issue AND
                                    emp.Employee_ID = iss.Employee_ID AND
                                    rq.Store_Issue = '$Sub_Department_ID'
                                    ORDER BY iss.Issue_ID DESC
                                    LIMIT 200") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_select);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($sql_select)) {
                    //get store need
                    $issue = $row['Issue_ID'];
                    $Store_Need = $row['Store_Need'];
                    $Sub_Department_Name = Get_Sub_Department_Name($Store_Need);

                    echo '<tr class="tbody"><td style="text-align: center;">' . $temp . '</td>
			    <td>' . $issue_ID . '</td>
			    <td>' . $row['Requisition_ID'] . '</td>
			    <td>' . $row['Sent_Date_Time'] . '</td>
			    <td>' . $row['Employee_Name'] . '</td>	
			    <td>' . $row['Issue_Date_And_Time'] . '</td>	
			    <td>' . $Sub_Department_Name . '</td> 	
			    <td>' . $row['Requisition_Description'] . '</td> 
			    <td style="text-align: center;">';
                    if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                        //if (Can_Edit_Issue_Note($row['Issue_ID'])) {
                        echo '<a href="Control_Issue_Note_Session.php?Edit_Issue_Note=True&Issue_Note_ID=' . $row['Issue_ID'] .
                            '" class="art-button-green">&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;</a>';
                        // }
                    }

            ?>
                    <input type="button" name="Preview" id="Preview" value="PREVIEW ISSUE" class="art-button-green" onclick="Preview_Grn_Issue_Note(<?php echo $issue; ?>)">
            <?php
                    $temp++;
                }
            }
            echo '</table>';
            ?>
</fieldset>

<!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID; 
                                                                ?>&Date_From=<?php //echo $Date_From; 
                                                                                                        ?>&Date_To=<?php //echo $Date_To; 
                                                                                                                                            ?>' width=100% height=380px></iframe>-->

<script>
    function clearOption(opt) {
        document.getElementById("Order_No").value = '';
    }

    function clearOrder() {
        //        if ($("#Employee_ID").val() != '') {
        //            $("#Employee_ID").val('').trigger('change');
        //        }

        document.getElementById("date").value = '';
        document.getElementById("date2").value = '';
    }
</script>
<script>
    function Get_Previous_Issue_Notes() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
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

            myObjectGetPreviousNote.open('GET', 'Get_Previous_Issue_Notes.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                document.getElementById('Previous_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                if (window.XMLHttpRequest) {
                    myObjectFilterGrn = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectFilterGrn = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectFilterGrn.overrideMimeType('text/xml');
                }
                myObjectFilterGrn.onreadystatechange = function() {
                    data26 = myObjectFilterGrn.responseText;
                    if (myObjectFilterGrn.readyState == 4) {
                        document.getElementById('Previous_Fieldset_List').innerHTML = data26;
                    }
                }; //specify name of function that will handle server response........

                myObjectFilterGrn.open('GET', 'Get_Previous_Issue_Notes.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date, true);
                myObjectFilterGrn.send();
            } else {
                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_From").focus();
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date2").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_To").focus();
                }
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Grn_Issue_Note(Issue_ID) {
        window.open('previousissuenotereport.php?Issue_ID=' + Issue_ID + '&PreviousIssueNote=PreviousIssueNoteThisPage', '_blank');
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
<?php
include('./includes/footer.php');
?>