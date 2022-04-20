<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/functions.php");
include_once("./functions/department.php");
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

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get sub departments
$Search_Values = '';
$select = mysqli_query($conn,"select Sub_Department_ID from tbl_employee_sub_department where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
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
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //get number of pending request
        if (isset($_SESSION['Storage'])) {
            $Sub_Department_Name = $_SESSION['Storage'];
        } else {
            $Sub_Department_Name = '';
        }
        $Store_Issue=$_SESSION['Storage_Info']['Sub_Department_ID'];
        $select_Order_Number = mysqli_query($conn,"select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
                                                        tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
                                                        rq.store_issue = sd.sub_department_id and
                                                        emp.employee_id = rq.employee_id and
                                                        rq.requisition_status = 'served' and
                                                        isu.Requisition_ID = rq.Requisition_ID and
                                                        rq.Store_Need IN ($Search_Values) and Store_Issue='$Store_Issue' order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
        $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='grnissuenote.php?GrnIssueNoteList=GrnIssueNoteListThisPage&from=prevgrn' class='art-button-green'>NEW GRN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnpreviouslist.php?GrnPreviousList=GrnPreviousListThisPage' class='art-button-green'>PREVIOUS GRN</a>"; //against issue note
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnissuenotelist.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
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
                    <input type="button" value="Filter" class="art-button-green" onclick="Filter_Previous_Grn_List()">
                </td>
            </tr>
            <tr>
                <td style="text-align:center">    
                    <label style='text-align: center;width:15%;display:inline'>Store Need</label>
                    <select name="Store_Need" id="Store_Need" style='text-align: center;width:15%;display:inline'>
                        <option value="0">All</option>
                        <?php
                            $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                            foreach ($Sub_Department_List as $Sub_Department) {
                                if($Sub_Department_ID=="{$Sub_Department['Sub_Department_ID']}"){
                                    $selected="selected='selected'";
                                }else{
                                    $selected="";
                                    
                                }
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}' $selected>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                  
                    <label style='text-align: center;width:15%;display:inline'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Store Issue</label>
                    <select name="Store_Issue" id="Store_Issue" style='text-align: center;width:15%;display:inline'>
                        <option value="0">All</option>
                        <?php
                            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
                            $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                            foreach ($Sub_Department_List as $Sub_Department) {
                                
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}' >";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>  
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Grn_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align='right'><b>Previous grn against issue note</b></legend>
    <table width=100% style="border-collapse:collapse !important; border:none !important;">
        <tr>
            <td width=4% style='text-align: center;'><b>SN</b></td>
            <td width=6% style='text-align:center;'><b>GRN N<u>O</u></b></td>
            <td width=6% style='text-align:center;'><b>ISSUE N<u>O</u></b></td>
            <td width=6% style='text-align:center;'><b>REQUISITION N<u>O</u></b></td>
            <td width=15%><b>GRN DATE</b></td>
            <td width=10%><b>STORE NEED</b></td>
            <td width=20%><b>STORE ISSUE</b></td>
            <td width=15%><b>PREPARED BY</b></td>
            <td width=10%><b>GRN DESCRIPTION</b></td>
            <td width=10%><b>ACTION</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
        <?php
         $Store_Issue=$_SESSION['Storage_Info']['Sub_Department_ID'];
        //select order data
        $select_grn = mysqli_query($conn,"select req.Requisition_ID, gin.Issue_ID, gin.Grn_Issue_Note_ID, gin.Created_Date_Time, gin.Issue_Description, req.Store_Need, req.Store_Issue, sd.Sub_Department_Name, emp.Employee_Name
                                from tbl_grn_issue_note gin, tbl_issues i, tbl_requisition req, tbl_sub_department sd, tbl_employee emp where
                                gin.Issue_ID = i.Issue_ID and
                                i.Requisition_ID = req.Requisition_ID and
                                gin.Employee_ID = emp.Employee_ID and
                                req.Store_Need = sd.Sub_Department_ID and Store_Issue='$Store_Issue' order by Grn_Issue_Note_ID desc limit 200") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_grn);


        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_grn)) {
                //get store issue
                $Store_Issue = $row['Store_Issue'];
                $slck = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slck);
                if ($nm > 0) {
                    while ($dt = mysqli_fetch_array($slck)) {
                        $Store_Issue_Name = $dt['Sub_Department_Name'];
                    }
                } else {
                    $Store_Issue_Name = '';
                }
                $href='';
                //if(isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit']=='yes'){
                    $href="grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "";
                //}


                echo "<tr><td style='text-align:center;'><a href='".$href."' style='text-decoration: none;'>" . $temp . "</a></td>";
                echo "<td style='text-align: center;'><a href='".$href."' style='text-decoration: none;'>" . $row['Grn_Issue_Note_ID'] . "</a></td>";
                echo "<td style='text-align: center;'><a href='previousissuenotereport.php?Issue_ID=".$row['Issue_ID']."&PreviousIssueNote=PreviousIssueNoteThisPage' style='text-decoration: none;' target='_blank'>" . $row['Issue_ID'] . "</a></td>";
                echo "<td style='text-align: center;'><a href='requisition_preview.php?Requisition_ID=".$row['Requisition_ID']."&RequisitionPreview=RequisitionPreviewThisPage' style='text-decoration: none;' target='_blank'>" . $row['Requisition_ID'] . "</a></td>";
                echo "<td><a href='".$href."' style='text-decoration: none;'>" . $row['Created_Date_Time'] . "</a></td>";
                echo "<td><a href='".$href."' style='text-decoration: none;'>" . $row['Sub_Department_Name'] . "</a></td>";
                echo "<td><a href='".$href."' style='text-decoration: none;'>" . $Store_Issue_Name . "</a></td>";
                echo "<td><a href='".$href."' style='text-decoration: none;'>" . $row['Employee_Name'] . "</a></td>";
                echo "<td><a href='".$href."' style='text-decoration: none;'>" . $row['Issue_Description'] . "</a></td>";
                echo "<td><a href='grnissuenotereport.php?Issue_ID=" . $row['Issue_ID'] . "' name='Preview' id='Preview' value='Preview' class='art-button-green' target='_blank' style='text-decoration: none;'>PREVIEW</a></td></tr>";
                $temp++;
            }
            //echo "</tr>";
        }
        ?>
    </table>
</fieldset>
<fieldset>
    <center style="color: #037CB0;">To preview issue note click issue note number. To preview requisition click requisition number</center>
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
<script>
    function Filter_Previous_Grn_List() {
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Order_No = document.getElementById("Order_No").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Store_Need = document.getElementById("Store_Need").value;
        var Store_Issue = document.getElementById("Store_Issue").value;

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

            myObjectGetPreviousNote.open('GET', 'Grn_Filter_Previous_List.php?Order_No=' + Order_No, true);
            myObjectGetPreviousNote.send();

        } else {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
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

                myObjectFilterGrn.open('GET', 'Grn_Filter_Previous_List.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date  + '&Employee_ID=' + Employee_ID+'&Store_Need='+Store_Need+'&Store_Issue='+Store_Issue, true);
                myObjectFilterGrn.send();
            } else {
                if (Start_Date == null || Start_Date == '') {
                    document.getElementById("date_From").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_From").focus();
                }

                if (End_Date == null || End_Date == '') {
                    document.getElementById("date_To").style = 'border: 2px solid red; text-align: center;width:15%;display:inline';
                    //document.getElementById("date_To").focus();
                }
            }
        }
    }
</script>




<script type="text/javascript">
    function Preview_Grn_Issue_Note(Issue_ID) {
        var winClose = popupwindow('grnissuenotereport.php?Issue_ID=' + Issue_ID + '&GrnIssueNoteReport=GrnIssueNoteReportThisPage', 'GRN ISSUE NOTE DETAILS', 1200, 500);
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
