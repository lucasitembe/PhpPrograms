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
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Pharmacy_Supervisor'])) {
                header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        //get number of pending request
        if (isset($_SESSION['Pharmacy_ID'])) {
            $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        } else {
            $Sub_Department_ID = '';
        }

        $select_Order_Number = mysqli_query($conn,"select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
		    tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
			rq.store_issue = sd.sub_department_id and
				emp.employee_id = rq.employee_id and
				    rq.requisition_status = 'served' and
					isu.Requisition_ID = rq.Requisition_ID and
					    rq.Store_Need = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='pharmacygrnissuenotelist.php?PharmacyGrnIssueNoteList=PharmacyGrnIssueNoteListThisPage' class='art-button-green'>NEW GRN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        echo "<a href='pharmacypreviousgrnlist.php?PreviousGrn=PreviousGrnThisPage' class='art-button-green'>PREVIOUS GRN</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Pharmacy'] == 'yes') {
        echo "<a href='pharmacygoodreceivingnote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
    }
}
?>

<?php
//get sub department name
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
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
    $(function () {
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
    $(function () {
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

<br/><br/>
<fieldset>  
    <table width='100%'> 
        <tr> 
            <td style="text-align:center">    
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                <label style='text-align: center;width:15%;display:inline'>Receiving From</label>&nbsp;
                <select name="Store_Issue" id="Store_Issue" style='text-align: center;width:15%;display:inline'>
                    <option value="0">All Stores</option>
                    <?php
                        $Sub_Department_List = Get_Storage_And_Pharmacy_Sub_Departments();
                        foreach ($Sub_Department_List as $Sub_Department) {
                            if($Sub_Department_ID != $Sub_Department['Sub_Department_ID']){
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                                echo "{$Sub_Department['Sub_Department_Name']}";
                                echo "</option>";
                            }
                        }
                    ?>
                </select>
                <input type='text' name='Order_No' style='text-align: center;width:21%;display:inline' onkeypress ="clearOrder()" id='Order_No' placeholder='~~~~~~~GRN No~~~~~~~'>
                <input type="button" value="Filter" class="art-button-green" onclick="Pharmacy_Get_List_Of_Issue_Notes()">
            </td>
        </tr>
    </table>
</fieldset>  
</center>
<br/>
<br/>
<fieldset style='overflow-y: scroll; height: 350px;' id='Previous_Fieldset_List'>
    <legend align=right><b>&nbsp;&nbsp;<?php
            if (isset($_SESSION['Pharmacy_ID'])) {
                echo $Sub_Department_Name;
            }
            ?>, GRN Against Issue Note, List Of Issues&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
    <center><table width = 100% border=0>
            <tr id='thead'>
                <td width=4% style='text-align: center;'><b>Sn</b></td>
                <td width=7% style='text-align: left;'><b>Grn N<u>o</u></b></td>
                <td width=7% style='text-align: left;'><b>Grn Date</u></b></td>
                <td width=7% style='text-align: left;'><b>Issue N<u>o</u></b></td>
                <td width=7% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
                <td width=13% style='text-align: left;'><b>Requested Date</b></td>
                <td width=17% style='text-align: left;'><b>Requisition Prepared By</b></td>
                <td width=13%><b>Issue Date & Time</b></td>
                <td width=15%><b>Received From</b></td>
                <td width=30%><b>Requisition Description</b></td>
                <td style='text-align: center;' width=10%><b>Action</b></td>
            </tr>

            <?php
            $temp = 1;
            //get top 50 grn open balances based on selected employee id
            //$Sub_Department_Name = $_SESSION['Storage'];
            $sql_select = mysqli_query($conn,"select grn.Grn_Issue_Note_ID,grn.Created_Date_Time,rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
					tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu,tbl_grn_issue_note grn where
					    rq.store_issue = sd.sub_department_id and
						    emp.employee_id = rq.employee_id and
                                                        rq.requisition_status = 'Received' and
                                                            isu.Requisition_ID = rq.Requisition_ID and
                                                             isu.Issue_ID = grn.Issue_ID  and
                                                                rq.Store_Need = '$Sub_Department_ID' order by grn.Grn_Issue_Note_ID desc limit 200") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_select);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($sql_select)) {
                    $Sub_Department_ID = $row['Store_Issue'];
                    //get Sub_department_need
                    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    if ($no > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            $Sub_Department_Name = $data['Sub_Department_Name'];
                        }
                    } else {
                        $Sub_Department_Name = '';
                    }

                    echo '<tr><td style="text-align: center;">' . $temp . '</td>
                    <td>' .  $row['Grn_Issue_Note_ID'] . '</td>
    			    <td>' . $row['Created_Date_Time'] . '</td>
                    <td><a href="previousissuenotereport.php?Issue_ID='.$row['Issue_ID'].'&PreviousIssueNote=PreviousIssueNoteThisPage" target="_blank" style="text-decoration: none;">'.$row['Issue_ID'].'</a></td>
    			    <td><a href="requisition_preview.php?Requisition_ID='.$row['Requisition_ID'].'&RequisitionPreview=RequisitionPreviewThisPage" target="_blank" style="text-decoration: none;">'.$row['Requisition_ID'].'</a></td>
    			    <td>' . $row['Sent_Date_Time'] . '</td>
    			    <td>' . $row['Employee_Name'] . '</td>	
    			    <td>' . $row['Issue_Date_And_Time'] . '</td>	
    			    <td>' . $Sub_Department_Name . '</td> 	
    			    <td>' . $row['Requisition_Description'] . '</td> 
    			    <td style="text-align: center;">
    			    <a href="Control_Pharmacy_Grn_Session.php?New_Grn=New&Issue_ID=' . $row['Issue_ID'] . '" class="art-button-green">&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a>
    			    </td>
    			</tr>';
                    $temp++;
                }
            }
            echo '</table>';
            ?>
            </fieldset>
<fieldset>
    <center style="color: #037CB0;">To preview issue note click issue note number. To preview requisition click requisition number</center>
</fieldset>
    <!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID;   ?>&Date_From=<?php //echo $Date_From;   ?>&Date_To=<?php //echo $Date_To;   ?>' width=100% height=380px></iframe>-->
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
                function Pharmacy_Get_List_Of_Issue_Notes() {
                    var Start_Date = document.getElementById("date_From").value;
                    var End_Date = document.getElementById("date_To").value;
                    var Order_No = document.getElementById("Order_No").value;
                    var Store_Issue = document.getElementById("Store_Issue").value;

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

                        myObjectGetPreviousNote.open('GET', 'Pharmacy_Get_List_Of_Issue_Notes.php?Order_No=' + Order_No, true);
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

                            myObjectGetPreviousNote.onreadystatechange = function () {
                                data80 = myObjectGetPreviousNote.responseText;
                                if (myObjectGetPreviousNote.readyState == 4) {
                                    document.getElementById('Previous_Fieldset_List').innerHTML = data80;
                                }
                            }; //specify name of function that will handle server response........

                            myObjectGetPreviousNote.open('GET', 'Pharmacy_Get_List_Of_Issue_Notes.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date+'&Store_Issue='+Store_Issue, true);
                            myObjectGetPreviousNote.send();
                        } else {

                            if (Start_Date == null || Start_Date == '') {
                                document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;width:15%';
                                document.getElementById("date_From").focus();
                            } else {
                                document.getElementById("date_From").style = 'border: 3px; text-align: center;width:15%';
                            }

                            if (End_Date == null || End_Date == '') {
                                document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;width:15%';
                                document.getElementById("date_To").focus();
                            } else {
                                document.getElementById("date_To").style = 'border: 3px; text-align: center;width:15%';
                            }
                        }
                    }
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