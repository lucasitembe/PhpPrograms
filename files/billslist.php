<?php
include("./includes/connection.php");
include("./includes/header.php");

echo "<link rel='stylesheet' href='fixHeader.css'>";

$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$can_edit_claim_bill = $_SESSION['userinfo']['can_edit_claim_bill'];

?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
       <!--
        <a href='Eclaim_Billing_Session_Control.php?New_Bill=New_Bill&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            CREATE NEW BILL
        </a>
        -->
        <a href='monthly_bill_summery.php?Bill_Summery=Bill_Summery&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            BILLS SUMMERY
        </a>
        <a href='monthly_bill_summery_analysis.php?Bill_Summery=Bill_Summery&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            BILLS SUMMERY ANALYSIS
        </a>

        <a href='claim_reconciliation.php?NHIF_Received_Claims=NHIF_Received_Claims&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            NHIF RECEIVED CLAIMS
        </a>
        <a href='claim_printing_services.php?NHIF_Received_Claims=NHIF_Received_Claims&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            CLAIMS PRINTING SERVICES
        </a>
        <?php
    }
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <!--a href='Eclaim_Billing_Session_Control.php?Previous_Bills=True&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            APPROVED BILLS
        </a-->
        <?php
    }
}


?>

<?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
            ?>
            <a href='qualityassuarancework.php?QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
                BACK
            </a>
            <?php
        }
    }
    $today = date('Y-m-d');
    $sql_selectbll = mysqli_query($conn,"SELECT COUNT(bl.Bill_ID) AS TotalUnprocessed from tbl_bills bl, tbl_employee emp where     emp.Employee_ID = bl.Employee_ID AND Bill_Date >'2021-11-30' and date(bl.Bill_Date_And_Time) <> '$today' AND e_bill_delivery_status<>1   ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_selectbll)>0){
        while($rws = mysqli_fetch_assoc($sql_selectbll)){
            $TotalUnprocessed = $rws['TotalUnprocessed'];
        }
    }else{
        $TotalUnprocessed = 0;
    }

?>
<style>
    button{
        color:#FFFFFF!important;
        height:27px!important;
    }
</style>
<br/><br/>

<center>
    <center>
        <fieldset>
            <legend align="right" ><b><span class="badge " style="background-color: red;"><?= $TotalUnprocessed ?>  CLAIM NOT SENT </span></b><b>Billing Processing</b></legend>
            <!--<form action='#' method='post' name='myForm' id='myForm'>-->
            <table width=100%>
                <tr>
                    <td style='text-align: right; width: 5%'><b>Start Date</b></td>
                    <td width=7%>
                        <input style='text-align: center;' type='text' name='Start_Date' id='date' required='required' placeholder='Start Date' readonly='readonly'>
                    </td>
                    <td style='text-align: right; width: 5%'><b>End Date</b></td>
                    <td width=7%>
                        <input style='text-align: center;' type='text' name='End_Date' id='date2' required='required' placeholder='End Date' readonly='readonly'>
                    </td>
					<!--
                    <td style='text-align: right; width: 5%'><b>Attendance Month</b></td>-->
                    <td width=8%>
                        <select name='attendence_month' id='attendence_month' style="width: 100%;">
                            <option value='' selected="selected">Select Month</option>
                            <option value='January'>January</option>
                            <option value='February'>February</option>
                            <option value='March'>March</option>
                            <option value='April'>April</option>
                            <option value='May'>May</option>
                            <option value='June'>June</option>
                            <option value='July'>July</option>
                            <option value='August'>August</option>
                            <option value='September'>September</option>
                            <option value='October'>October</option>
                            <option value='November'>November</option>
                            <option value='December'>December</option>
                        </select>
                    </td>
                    <!--   <input type='text' name='attendence_month' id='attendence_month' required='required' placeholder='Attendance Month' readonly='readonly'>
                   </td> -->
                    <td style='text-align: right;' width=5%><b>Insurance</b></td>
                    <td width=10%>
                        <select name='Sponsor_ID' id='Sponsor_ID' required='required'>
                            <option selected='selected'></option>
                            <?php
                            $sql = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor
                                                                where Guarantor_Name <> 'CASH'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td width="8%">
                        <b>Failed and Sent Bill</b>
                    </td>
                    <td width="8%">
                        <select id="fail_n_sent_bill" style="width:100%">
                            <option value="all">All Bill</option>
                            <option value="1">Sent Bill</option>
                            <option value="0">Failed Bill</option>
                        </select>
                    </td>
                    <td style="text-align:right; width:6%;"><b>Patient Type:</b></td>
                    <td>
                        <!--input type="text" placeholder="Enter Patient number/Name "  id="patient_details" oninput="Search_Patient();"/-->
                        <select id="patient_status" name="patient_status" style="width:100%">
                          <option value="All">Select All</option>
                          <option value="Inpatient">Inpatient</option>
                          <option value="Outpatient">Outpatient</option>
                        </select>
                    </td>
                    <td><input type="text"  name="filter_patient" id="filter_patient" title="Number Recommended For Faster Searching" placeholder="Enter Patient Name/Number" oninput="Search_Patient();"></td>
                    <td style='width: 5%;'>
                        <input name='Submit' id='Submit' type='button' value='FILTER' class='art-button-green' onclick="Get_List_Of_Bill('filter')">
                    </td>
                </tr>
            </table>
            <!--</form>-->
            <div id='Bills_Fieldset_List'>
            <fieldset style='overflow-y: scroll; height: 320px;'>
                <?php
                $count=1;
                echo '<center><table width = 100% border=0 class="fixTableHead">';
                echo '
                    <thead>
                        <tr style="background-color: #ccc;">
                            <td width=3%><b>SN</b></td><td width=5% style="text-align: left;"><b>Bill No</b></td>
                            <td width=4%><b>Folio No.</b></td>
                            <td width=5%><b>Sponsor</b></td>
                            <td width=8% style="text-align: left;"><b>Patient Number </b></td>
                            <td width=15% style="text-align: left;"><b>Patient Name </b></td>
                            <td width=7% style="text-align: left;"><b>Phone Number </b></td>
                            <td width=6% style="text-align: left;"><b>Patient Type </b></td>
                            <td width=7% style="text-align: left;"><b>Attendence Date</b></td>
                            <td width=7% style="text-align: left;"><b>Created Date</b></td>
                            <td width=7% style="text-align: right;"><b>Total Amount</b></td>
                            <td width=9% style="text-align: center;"><b>Bill Status</b></td>
                            <td width=9% style="text-align: center;"><b>Form 2A&B</b></td>
                            <td width=6% style="text-align: center;"><b>Case Notes</b></td>
                        </tr>
                    </thead>';

                //select all previous bills
                $today = date('Y-m-d');
                $sql_select = mysqli_query($conn,"select bl.e_bill_delivery_status,bl.Bill_ID,bl.invoice_created, bl.Bill_Date_And_Time,bl.Sponsor_ID,sp.Guarantor_Name from tbl_bills bl, tbl_employee emp, tbl_sponsor sp where     emp.Employee_ID = bl.Employee_ID and     bl.Sponsor_ID = sp.Sponsor_ID and date(bl.Bill_Date_And_Time) = '$today'   order by bl.Bill_ID asc ") or die(mysqli_error($conn));

                $total_amount=0;
                $total_display=0;
                $num = mysqli_num_rows($sql_select);
                if ($num > 0) {
                    while ($row = mysqli_fetch_array($sql_select)) {
                        //get bill id to calculate grand total
                        $Bill_ID = $row['Bill_ID'];
                        $Sponsor_ID = $row['Sponsor_ID'];
                        $invoice_created = $row['invoice_created'];

            //find the attendence date

                        $patient_visit_date = mysqli_fetch_assoc(mysqli_query($conn,"SELECT visit_date FROM tbl_check_in WHERE Check_In_ID = (SELECT DISTINCT Check_In_ID FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID')"))['visit_date'];
						$FolioNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Folio_No FROM tbl_claim_folio WHERE Bill_ID = $Bill_ID"))['Folio_No'];
                        //calculate bill grand total
                        $get_Total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) as Bill_Amount,pp.Folio_Number,pp.Check_In_ID,pp.Patient_Bill_ID,pp.Registration_ID,pp.Billing_Type,pr.Patient_Name, pr.Phone_Number from    tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_patient_registration pr where    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Registration_ID=pr.Registration_ID and ppl.Status<>'removed' and     pp.Bill_ID = '$Bill_ID'") or die(mysqli_error($conn));
                        $num_total = mysqli_num_rows($get_Total);
                        if ($num_total > 0) {
                            while ($data = mysqli_fetch_array($get_Total)) {
                                $Bill_Amount = $data['Bill_Amount'];
                                $Folio_Number = $data['Folio_Number'];
                                $Patient_Bill_ID = $data['Patient_Bill_ID'];
                                $Registration_ID= $data['Registration_ID'];
                                $Patient_Name= $data['Patient_Name'];
                                $Phone_Number= $data['Phone_Number'];
                                $Billing_Type= $data['Billing_Type'];
                                $Check_In_ID= $data['Check_In_ID'];
                            }
                            $typecode = "";
							$Billing_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' AND Billing_Type ='Inpatient Credit' ORDER BY patient_payment_id DESC LIMIT 1"))['Billing_Type'];
                            $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
                            if(mysqli_num_rows($select111) > 0){
                                $Billing_Type ='Inpatient Credit';
                                $typecode ="IN";
                            }else{
                                $Billing_Type ='Outpatient Credit';
                                $typecode = "OUT";

                            }
                        } else {
                            $Bill_Amount = 0;
                            $Patient_Bill_ID = 0;
                            $Folio_Number = 0;
                            $Registration_ID= 0;
                        }
                        if ($row['e_bill_delivery_status'] == "1") {
                            echo "<tr>";
                            echo "<td>" . ($count++) . "</td>";
                            echo "<td>" . $Bill_ID . "</td>";
                            echo "<td>" . $FolioNo . "</td>";
                            echo "<td>" . $row['Guarantor_Name'] . "</td>";
                            echo "<td>" . $Registration_ID. "</td>";
                            echo "<td>" . $Patient_Name . "</td>";
                            echo "<td>" . $Phone_Number . "</td>";
                            echo "<td>" . explode(' ',$Billing_Type)[0] . "</td>";
                            echo "<td>" . $patient_visit_date . "</td>";
                            echo "<td>" . $row['Bill_Date_And_Time'] . "</td>";
                            echo "<td style='text-align: right;'>" . number_format($Bill_Amount) . "</td>";

                            //echo "<td style='text-align: center;'><a href='#' class='art-button-green'>Preview Bill</a></td>";
                            echo "<td style='text-align: center;background-color:white;color:green'><b>Sent</b></td>";
                            echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td>";

                            echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'></td>";
                            echo "</tr>";
                            if($invoice_created == 'no'){
                                $total_amount +=$Bill_Amount;
                            }
                                $total_display +=$Bill_Amount;
                        } else {
                            echo "<tr>";
                            echo "<td>" . ($count++) . "</td>";
                            if($can_edit_claim_bill=='yes'){
                                echo "<td><a target='_blank' href='Edit_Failed_Approved_BIll.php?Bill_ID=".$Bill_ID."&Folio_Number=".$Folio_Number."&Sponsor_ID=".$Sponsor_ID."&Patient_Bill_ID=".$Patient_Bill_ID."&Check_In_ID=".$Check_In_ID."&Registration_ID=".$Registration_ID."'>" . $Bill_ID . "</a></td>";
                            }else{
                                echo "<td>$Bill_ID</td>";
                            }
                            // echo "<td><a target='_blank'  href='Edit_Failed_Approved_BIll.php?Bill_ID=".$Bill_ID."&Folio_Number=".$Folio_Number."&Sponsor_ID=".$Sponsor_ID."&Patient_Bill_ID=".$Patient_Bill_ID."&Check_In_ID=".$Check_In_ID."&Registration_ID=".$Registration_ID."'>" . $Bill_ID . "</a></td>";
                            echo "<td>" . $FolioNo. "</td>";
                            echo "<td>" . $row['Guarantor_Name'] . "</td>";
                            echo "<td>" . $Registration_ID . "</td>";
                            echo "<td>" .  $Patient_Name . "</td>";
                            echo "<td>" .  $Phone_Number . "</td>";
                            echo "<td>" .  explode(' ',$Billing_Type)[0] . "</td>";
                            echo "<td>" . $patient_visit_date . "</td>";
                            echo "<td>" . $row['Bill_Date_And_Time'] . "</td>";
                            echo "<td style='text-align: right;'>" . number_format($Bill_Amount) . "</td>";
                            //echo "<td style='text-align: center;'><a href='#' class='art-button-green'>Preview Bill</a></td>";
                            echo "<td id='td_" . $Bill_ID . "'  style='text-align: center;'><button onclick='resendBill(" . $Bill_ID . "," . $Sponsor_ID . "," . $Folio_Number . "," . $Patient_Bill_ID . "," . $Registration_ID . ",this);' class='art-button-green'>Send claim</button></td>
                            ";
                            echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td>";
                            echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'></td>";
                            echo "</tr>";
                            if($invoice_created == 'no'){
                                $total_amount +=$Bill_Amount;
                            }
                                $total_display +=$Bill_Amount;
                        }
                        if($invoice_created == 'no'){
                            echo "<input type='hidden' name='bill_id' value='".$Bill_ID."'>";
                        }
                            echo "<input type='hidden' name='invoice_exist' value='".$invoice_created."'>";
                    }
                }
                echo '</table>';
                ?>
            </fieldset>
            <br>
            <center><label>Total Amount:<b id="display_amount"> <?=number_format($total_display)?></b></label></center>
            <input type='hidden' value='<?=$total_amount?>' id='total_amount'>
            <center>
              <input type="button" name="excel_peport" value="Excel Report" class="art-button-green" onclick="Download_Excel_Report();">
              <input type="button" name="preview_bill" value="Preview Bill" class="art-button-green" onclick="Preview_Bills_Report();">
            </center>
            <!--input type='submit' name='btn_create_invoice' id='create_invoice' onclick='Create_Invoice();' class='art-button-green' value='Create Invoice' style='float:right;margin-top:10px;border:2px solid green;'-->
            <!--input type='submit' name='btn_print_invoice' id='print_invoice' onclick='Print_Invoice();' class='art-button-green' value='Print Invoice' style='float:right;margin-top:10px;border:2px solid green;display:none;'-->
        </div>
    </center>
    <div id="create_bill_dialog" style="display:none;">
        <label>Narration</label>
        <textarea name='narration' style='min-height:150px;' id='narration'></textarea>
        <br>
        <input type='submit' name='btn_save_invoice' id='btn_save_invoice' class='art-button-green' value='Save' style='float:right;' onclick='Save_Invoice();'>
        <input type='submit' name='btn_cancel_invoice' id='btn_cancel_invoice' class='art-button-green' value='Cancel' style='float:right;' onclick='Cancel_Invoice();'>
        <br>
    </div>
    <table width=100%>
        <tr>
            <td style='text-align: right;'>
                <input type='button' value='' id='Preview_Previous_Approved_Transaction' name='Preview_Previous_Approved_Transaction' class='art-button-green' style='visibility: hidden;'>
                <input type='button' value='' id='Preview_Next_Approved_Transaction' name='Preview_Next_Approved_Transaction' class='art-button-green' style='visibility: hidden;'>
                <input type='button' value='GENERATE BILL' id='Generate_Bill_Button' name='Generate_Bill_Button' class='art-button-green' style='visibility: hidden;' onclick=(Generate_Bill());>
            </td>
        </tr>
    </table>
</center>


<script>
    function Get_List_Of_Bill(call_status) {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var attendence_month = document.getElementById("attendence_month").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var patient_status = document.getElementById("patient_status").value;
        var fail_n_sent_bill = document.getElementById("fail_n_sent_bill").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != '' && Sponsor_ID != null) {
            if (window.XMLHttpRequest) {
                myObjectGetSelectedBills = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetSelectedBills = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetSelectedBills.overrideMimeType('text/xml');
            }

        $("#Bills_Fieldset_List").html("<img src='images/ajax-loader_1.gif' alt='Loading....'>");

            myObjectGetSelectedBills.onreadystatechange = function () {
                data1 = myObjectGetSelectedBills.responseText;
                if (myObjectGetSelectedBills.readyState == 4) {
                    document.getElementById('Bills_Fieldset_List').innerHTML = data1;
                }
            }; //specify name of function that will handle server response........

            myObjectGetSelectedBills.open('GET', 'Get_List_Of_Billis.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID+'&fail_n_sent_bill='+fail_n_sent_bill+'&call_status='+call_status+'&patient_status='+patient_status+'&attendence_month='+attendence_month, true);
            myObjectGetSelectedBills.send();
        } else {
            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").focus();
                document.getElementById("Sponsor_ID").style = 'border-color: red';
                alert('Select Insurance');
                return false;
            }
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").focus();
                document.getElementById("date").style = 'border-color: red';
            }
            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").focus();
                document.getElementById("date2").style = 'border-color: red';
            }
            if(Start_Date == '' || End_Date == ''){
                alert('Enter Start and End Date');
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Details(Folio_Number,Sponsor_ID,Registration_ID,Patient_Bill_ID,Check_In_ID,Bill_ID){
       // var winClose=popupwindow('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, 'e-CLAIM DETAILS', 1200, 500);
		window.open('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, '_blank');
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }

    function Preview_Case_Notes(Registration_ID,Check_In_ID,Bill_ID,typecode){

        //var winClose=popupwindow('case_note_preview.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID+'&typecode='+typecode, 'e-CLAIM DETAILS', 1200, 500);
		window.open('case_note_preview.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID+'&typecode='+typecode,'_blank');
    }
</script>

<script>
    function Generate_Bill() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        var Confirm_Message = confirm("Are you sure you want to create this bill\nStart Date : " + Start_Date + "\nEnd Date : " + End_Date);
        if (Confirm_Message == true) {
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
                if (window.XMLHttpRequest) {
                    myObjectGenerateBill = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectGenerateBill = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGenerateBill.overrideMimeType('text/xml');
                }
                myObjectGenerateBill.onreadystatechange = function () {
                    data2 = myObjectGenerateBill.responseText;
                    if (myObjectGenerateBill.readyState == 4) {
                        //document.getElementById('Bills_Fieldset_List').innerHTML = data2;
                        //document.getElementById("Generate_Bill_Button").style.visibility = 'visible';
                        //document.getElementById("Preview_Previous_Approved_Transaction").style.visibility = 'visible';
                        //document.getElementById("Preview_Next_Approved_Transaction").style.visibility = 'visible';
                        //document.getElementById("Preview_Previous_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS BEFORE '+Start_Date;
                        //document.getElementById("Preview_Next_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS AFTER '+End_Date;
                        var Feedback = data2;
                        if (Feedback == 'Successfull') {
                            alert("Bill created successfully");
                            document.location = "./billslist.php?BillsList=BillsListThisPage";
                        } else {
                            alert("Creating bill process fail!! Please try again");
                        }
                    }
                }
            }
            ; //specify name of function that will handle server response........
            myObjectGenerateBill.open('GET', 'Generate_Bill.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID, true);
            myObjectGenerateBill.send();
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<script>
    function resendBill(BID,Sponsor_ID,Folio_Number,Patient_Bill_ID,Registration_ID,btn) {
        // $("#"+$(btn).attr('id')).hide();
        var Bill_ID = BID;
        var Sponsor_ID = Sponsor_ID;
        var Folio_Number = Folio_Number;
        var Patient_Bill_ID = Patient_Bill_ID;
        var Registration_ID = Registration_ID;

        $.ajax({
            type: "GET",
            url: "createXMLBill.php",
            dataType: "json",
            data: {Bill_ID,Sponsor_ID,Folio_Number,Patient_Bill_ID,Registration_ID},
            beforeSend: function (xhr) {
                alertToastr('', 'Sending bill. please wait..... ', 'info', '', false);
            },
            success: function (data) {
                console.log(data);
                if (data.StatusCode === 200) {
                    alertToastr('SUCCESS', 'Patient bill sent successifully.... ', 'success', '', false);
                    $(btn).hide();
                    $('#td_' + Bill_ID).html('<b>Sent</b>').css('background-color', 'white').css('color', 'green');
                    saveresponce(data.Message, Bill_ID);

                } else {
                    alertToastr('ERROR', data.Message, 'error', '', false);
                    if(data.StatusCode === 406 && data.Message.includes('already been submitted')){
                    $(btn).hide();
                            $('#td_' + Bill_ID).html('<b>Sent</b>').css('background-color', 'white').css('color', 'green');
            
                    }
                }
            }

            // $('#td_'+Bill_ID).html('<b>Sent</b>');
        });

    }
    function saveresponce(nhifresponce, Bill_ID){
        $.ajax({
            type: "POST",
            url: "CheckUnProcessedItem.php",
            data: {  Bill_ID: Bill_ID, nhifresponce:nhifresponce, savenhifresponce:''},           
            success: function (responce) {
                console.log(responce);
            }
        })
    }
    function Create_Invoice(){
        var narration    =  $('#narration').val();
        var Start_Date   =  $('#date').val();
        var End_Date     =  $('#date2').val();
        var Sponsor_ID   =  $('#Sponsor_ID').val();
        if(Start_Date =='' || End_Date ==''){
            alert("Enter Start Date and End Date");
            return false;
        }
        if(Sponsor_ID == ''){
            alert('Select Insurance');
            return false;
        }
            $("#create_bill_dialog").dialog('open');
    }
    function Save_Invoice(){
        var Sponsor_ID   =  $('#Sponsor_ID').val();
        var Amount       =  $('#total_amount').val();
        var narration    =  $('#narration').val();
        var Start_Date   =  $('#date').val();
        var End_Date     =  $('#date2').val();

        //var total_amount =  $('#total_amount').val();
        var bill_id      =  document.getElementsByName('bill_id');
        var bill_list    =  [];
            if(narration.trim()===''){
                alert('Write Narration');
            }else{
            if(Sponsor_ID ===''){
                alert('Select Insurance First');
                location.reload();
                //$('#narration').val('')
            }else{
                $.ajax({
                    url:'ajax_create_patient_list_invoice.php',
                    type:'post',
                    data:{Sponsor_ID:Sponsor_ID,Amount:Amount,bill_list:bill_list,narration:narration,Start_Date:Start_Date,End_Date:End_Date},
                    beforeSend: function (xhr) {
                        alertToastr('', 'Creating Invoice. please wait..... ', 'info', '', false);
                    },
                    dataType:'json',
                    success:function(xhr){
                        if(xhr.result != 'fail'){
                          //$('.print_invoice').show();
                            alert(xhr.result);
                            Get_List_Of_Bill('reload');
                        }else{
                            alert('Invoice Already Created or No Bill for this Insurance');
                        }
                        //location.reload();
                    }
                });
                //alert('saved sc');
                $("#create_bill_dialog").dialog('close');
                //location.reload();
                $('#narration').val('')
            }
        }

    }
    function Cancel_Invoice(){
        $("#create_bill_dialog").dialog('close');
        //location.reload();
        $('#narration').val('');
    }
    function Print_Invoice(){
        window.open('preview_invoice.php?Invoice_ID=from_bill_list');
    }

    function Search_Patient(){
      var Start_Date = document.getElementById("date").value;
      var End_Date = document.getElementById("date2").value;
      var attendence_month = document.getElementById("attendence_month").value;
      var Sponsor_ID = document.getElementById("Sponsor_ID").value;
      var fail_n_sent_bill = document.getElementById("fail_n_sent_bill").value;
      var patient_status = document.getElementById("patient_status").value;
      var patient_details = $("#filter_patient").val().trim();
      var search_value = '';
      if(Start_Date == '' || End_Date ==''){
        alert("YOU HAVE TO WRITE START AND END DATE!!");
        return false;
      }
      if(Sponsor_ID ==''){
        alert("YOU HAVE TO SELECT INSURANCE!!");
        return false;
      }
      //alert(isNaN(patient_details));
      if(patient_details ==''){
        return false;
      }
      if(isNaN(patient_details)){
        search_value = 'patient_name';
      }else{
        search_value = 'patient_number';
        //alert('namba')
      }
      $.ajax({
        url:'Get_List_Of_Billis.php',
        type:'get',
		beforeSend:function(){
        $("#Bills_Fieldset_List").html("<img src='images/ajax-loader_1.gif' lat='Loading....'>");
      	},
        data:{search_for:"single_patient",search_value:search_value,patient_details:patient_details,Start_Date:Start_Date,End_Date:End_Date,Sponsor_ID:Sponsor_ID,fail_n_sent_bill:fail_n_sent_bill,patient_status:patient_status,attendence_month:attendence_month},
        success:function(result){
          $("#Bills_Fieldset_List").html(result);
        }
      });
    }
    function Preview_Bills_Report(){
      var Start_Date = document.getElementById("date").value;
      var End_Date = document.getElementById("date2").value;
      var attendence_month = document.getElementById("attendence_month").value;
      var Sponsor_ID = document.getElementById("Sponsor_ID").value;
      var fail_n_sent_bill = document.getElementById("fail_n_sent_bill").value;
      var patient_status = document.getElementById("patient_status").value;
      var patient_details = $("#filter_patient").val().trim();
      var search_value = '';
      if(Start_Date == '' || End_Date ==''){
        alert("YOU HAVE TO WRITE START AND END DATE!!");
        return false;
      }
      if(Sponsor_ID ==''){
        alert("YOU HAVE TO SELECT INSURANCE!!");
        return false;
      }
      //alert(isNaN(patient_details));
      // if(patient_details ==''){
      //   return false;
      // }
      if(isNaN(patient_details)){
        search_value = 'patient_name';
      }else{
        search_value = 'patient_number';
        //alert('namba')
      }
      window.open("print_preview_bills_list.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID+"&fail_n_sent_bill="+fail_n_sent_bill+"&patient_status="+patient_status+"&attendence_month="+attendence_month);
    }

    function Download_Excel_Report(){
      var Start_Date = document.getElementById("date").value;
      var End_Date = document.getElementById("date2").value;
      var attendence_month = document.getElementById("attendence_month").value;
      var Sponsor_ID = document.getElementById("Sponsor_ID").value;
      var fail_n_sent_bill = document.getElementById("fail_n_sent_bill").value;
      var patient_status = document.getElementById("patient_status").value;
      var patient_details = $("#filter_patient").val().trim();
      var search_value = '';
      if(Start_Date == '' || End_Date ==''){
        alert("YOU HAVE TO WRITE START AND END DATE!!");
        return false;
      }
      if(Sponsor_ID ==''){
        alert("YOU HAVE TO SELECT INSURANCE!!");
        return false;
      }
      window.open("approved_bills_list_excel_report.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID+"&fail_n_sent_bill="+fail_n_sent_bill+"&patient_status="+patient_status+"&attendence_month="+attendence_month);
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
        $("#create_bill_dialog").dialog({autoOpen: false, width: '50%',height:'300', title: 'CREATE INVOICE', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
    });
</script>


<?php
include("./includes/footer.php");
?>
