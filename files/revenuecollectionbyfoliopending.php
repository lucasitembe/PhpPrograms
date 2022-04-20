<?php include("./includes/header.php"); 
echo "<link rel='stylesheet' href='fixHeader.css'>";

?>
<script>
    function Confirm_Approval_Trnsaction(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID, Sponsor_Name) {
        if (Control_Quantity == 'yes') {
            var r = confirm("Are you sure you want to approve this transaction?\n\nClick OK to proceed");
            if (r == true) {
                Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name);
            }
        } else {
            alert("Process Fail! Some transactions contain zero quantity");
        }
    }


    //Aprove patient

    function patient_approval() {
        var Registration_ID = document.getElementById('Registration_ID').value;
        var Folio_Number = document.getElementById('Folio_Number').value;
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        var Patient_Bill_ID = document.getElementById('Patient_Bill_ID').value;
        var Control_Quantity = document.getElementById('Control_Quantity').value;
        var Patient_Payment_ID = document.getElementById('Patient_Payment_ID').value;
        var Signature_value = document.getElementById('Signature_value').value;
        var n = Signature_value.length;
        if (n < 5) {
            alert('Fill patient signature first');
        } else {
            Confirm_Approval_Trnsaction_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID);
        }

    }


    function Confirm_Approval_Trnsaction_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID) {
        //if(Control_Quantity == 'yes'){
        var r = confirm("Are you sure you want to approve this transaction?\n\nClick OK to proceed");
        if (r == true) {
            Approval_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID);
        }
        //}else{
        //	alert("Process Fail! Some transactions contain zero quantity");
        //}
    }




</script>
<script type='text/javascript'>
    function Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name) {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data2121 = myObject.responseText;
            if (myObject.readyState == 4) {
                //Approval_ID.disabled = 'disabled';
                document.getElementById('Approval_Area').innerHTML = data2121;
                generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name);
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Patient_Payment_ID=' + Patient_Payment_ID, true);
        myObject.send();

        alertToastr('', 'Approving bill. please wait..... ', 'info', '', false);
        $('#progressStatus').show();
    }
    //Genarate bill
    function generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name) {
        $.ajax({
            type: "GET",
            url: "Generate_Bill.php",
            dataType: "json",
            data: {Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID},
            beforeSend: function (xhr) {
                alertToastr('', 'Generating bill. please wait..... ', 'info', '', false);
            },
            success: function (data) {
                if (data.status == '200') {
                    alertToastr('', 'Bill Generated successifully.', 'success', '', false);
                    if (Sponsor_Name === "NHIF") {
                        SendBill(data.data, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID);
                    }
                } else if (data.status == 300) {
                    alertToastr('', 'Failed to generate bill. ' + data, 'error', '', false);
                } else {
                    alertToastr('', 'An error has occured.please try again. ' + data, 'error', '', false);
                }
            }
        });
    }
    function SendBill(Bill_ID, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID) {
        $.ajax({
            type: "GET",
            url: "createXMLBill.php",
            data: {Bill_ID: Bill_ID, Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID},
            beforeSend: function (xhr) {
                $('#progressStatus').show();
                alertToastr('', 'Sending bill. please wait..... ', 'info', '', false);
            },
            dataType: "json",
            success: function (data) {
                if (data.StatusCode == '200') {
                    alertToastr('', data.Message, 'success', '', false);
                } else if (data.StatusCode == '0') {
                    alertToastr('', 'There is a problem with connectivity, please check your connection!', 'error', '', false);
                } else {
                    alertToastr('', data.Message, 'error', '', false);
                }

            }, complete: function (x, y) {
                $('#progressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#progressStatus').hide();
            }
        });
    }
    //Approval patient signature
    function Approval_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID) {
        var Signature_value = document.getElementById('Signature_value').value;
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data2121 = myObject.responseText;
            if (myObject.readyState == 4) {

                //alert(data2121);
                //Approval_ID.disabled = 'disabled';
                document.getElementById('Approval_Area').innerHTML = data2121;
                document.getElementById('Signature_value').value = '';
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill_Patient.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Signature_value=' + Signature_value, true);
        myObject.send();
    }
</script>

<script>
    $('#Signature_value').signature('draw', $('#signatureJSON').val());
    $('#Signature_value').signature({disabled: true});
</script>


<?php
/// include("./includes/header.php");
include("./includes/connection.php");
$temp = 1;
$total = 0;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

$Date_From = '';
$Date_To = '';
?>


<!--    Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>

<!--<script src="script.js"></script>-->

<!--[if IE]> 
<script type="text/javascript" src="js/excanvas.js"></script> 
<![endif]-->

<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="js/jquery.signature.js"></script>
<!--<script src="script.js"></script>-->
<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<!--<script src="script.responsive.js"></script>-->
<link type="text/css" href="css/jquery.signature.css" rel="stylesheet">

<script>
    $(function () {
        $("#date_From").datepicker({
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

    $(function () {
        $("#date_To").datepicker({
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
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='./qualityassuarancework.php?QualityAssuranceWork=QualityAssuranceWorkThiPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>

<br/><br/>
<center>
    <fieldset>
        <legend align='right'><b>REVENUE COLLECTION BY FOLIO NUMBER</b></legend>
        <!--<br/>-->
        <?php
        //get variable from the back button
        if (isset($_GET['Insurance'])) {
            $Insurance = $_GET['Insurance'];
        } else {
            $Insurance = 'All';
        }

        if (isset($_GET['Date_From'])) {
            $Date_From = $_GET['Date_From'];
        } else {
            $Date_From = $Today;
        }

        if (isset($_GET['Date_To'])) {
            $Date_To = $_GET['Date_To'];
        } else {
            $Date_To = $Today;
        }

        if (isset($_GET['Payment_Type'])) {
            $Payment_Type = $_GET['Payment_Type'];
        } else {
            $Payment_Type = '';
        }

        if (isset($_GET['Patient_Type'])) {
            $Patient_Type = $_GET['Patient_Type'];
        } else {
            $Patient_Type = 'all';
        }

        if (isset($_GET['Branch'])) {
            $Branch = $_GET['Branch'];
        } else {
            $Branch = 'All';
        }


        if (isset($_POST['Print_FILTER'])) {
            $Branch = $_POST['Branch'];
            $Date_From = $_POST['Date_From'];
            $Date_To = $_POST['Date_To'];
            $Insurance = $_POST['Insurance'];
            $Payment_Type = $_POST['Payment_Type'];
            $Patient_Type = $_POST['Patient_Type'];
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
        </style> 

        <!--<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <table width=100%>
            <tr>
                <td width="5%" style="text-align: right;"><b>Branch</b></td>
                <td style='text-align: left; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='Branch' id='Branch' required='required'>
                        <?php
                        $select_Branches = mysqli_query($conn,"select Branch_Name from tbl_branches");
                        while ($row = mysqli_fetch_array($select_Branches)) {
                            echo "<option>" . $row['Branch_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td width="7%" style="text-align: right;"><b>Start Date</b></td>
                <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='Date_From' id='date_From' required='required' style='text-align: center;' value="<?php echo $Today; ?>">
                </td> 
                <td width="7%" style="text-align: right;"><b>End Date</b></td>
                <td style='text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='Date_To' id='date_To' required='required' style='text-align: center;' value="<?php echo $Today; ?>">
                </td>
                <td width="5%" style="text-align: right;"><b>Sponsor</b></td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='Insurance' id='Insurance' required='required'>
                        <!-- <option selected='selected'></option> -->
                        <?php
                        $data = mysqli_query($conn,"select * from tbl_sponsor where guarantor_name <> 'cash' order by Guarantor_Name") or die(mysqli_error($conn));
                        while ($row = mysqli_fetch_array($data)) {
                            if (strtolower($row['Guarantor_Name']) == 'nhif') {
                                echo '<option selected="selected">' . $row['Guarantor_Name'] . '</option>';
                            } else {
                                echo '<option>' . $row['Guarantor_Name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='Payment_Type' id='Payment_Type' required='required'>
                        <option selected='selected'>Credit</option>
                    </select>
                </td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='Get_Transaction_List()'>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;">
                    <input type="text" id="Search_Value" name="Search_Value" style="text-align: center;" placeholder='~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~' autocomplete='off' oninput="Search_Patient_Filtered()" onkeyup="Search_Patient_Filtered()">
                </td>
                <td colspan="2" style="text-align: center;">
                    <input type="text" id="Patient_Number" name="Patient_Number" style="text-align: center;" placeholder='~~~~~~Enter Patient Number~~~~~~' autocomplete='off' oninput="Search_Patient_Filtered_Patient_Number()" onkeyup="Search_Patient_Filtered_Patient_Number()">
                </td>
                <td colspan="3" style="text-align: center;">
                    <input type="text" id="Patient_Phone_Number" name="Patient_Phone_Number" style="text-align: center;" placeholder='~~~~~~Enter Patient Phone number~~~~~~' autocomplete='off' oninput="Search_Patient_Filtered_Phone_Number()" onkeyup="Search_Patient_Filtered_Phone_Number()">
                </td>
            </tr>
        </table>
</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 420px; background: white;' id='Items_Fieldset_List'>
    <?php
    $select_Filtered_Patients = "
        select pr.Patient_Name, pr.Registration_ID, sp.Guarantor_Name, pp.Folio_Number, pp.Patient_Bill_ID, pp.Billing_Process_Status, pp.Billing_Process_Employee_ID 
        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
        where pp.patient_payment_id = ppl.patient_payment_id and
        pp.registration_id = pr.registration_id and
        pp.receipt_date between '$Date_From' and '$Date_To' and
        sp.sponsor_id = pp.sponsor_id and
        pp.Bill_ID IS NULL and
	pp.Transaction_status <> 'cancelled' and
        (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and              
        pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = 'NHIF') and
        pp.branch_id = '$Branch_ID'
        GROUP BY  pr.Registration_ID, pp.Folio_Number, pp.Patient_Bill_ID order by pp.Folio_Number desc limit 10";

    echo '<center><table width =100% border=0 class="fixTableHead"><thead>';
    echo '
            <tr style="background-color: #ccc;" id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>Patient Name</b></td>
                <td width="7%" style="text-align: right;"><b>Patient#</b></td>
                <td width="8%" style="text-align: right;"><b>Folio Number</b></td>
                <td width="9%" style="text-align: center;"><b>Sponsor</b></td>
                <td width="15%" style="text-align: center;"><b>First Served Date</b></td>
                <td width="15%" style="text-align: right;"><b>Amount</b></td>
			</tr>
        </thead>';
    $results = mysqli_query($conn,$select_Filtered_Patients);

    while ($row = mysqli_fetch_array($results)) {
        //get first served date
        $Folio_Number = $row['Folio_Number'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Bill_ID = $row['Patient_Bill_ID'];
        $Registration_ID = $row['Registration_ID'];
        $first_saved_date = mysqli_query($conn,"SELECT Payment_Date_And_Time from  tbl_patient_payments
					    where Folio_Number = '$Folio_Number' and
					    Patient_Bill_ID = '$Patient_Bill_ID' and
						Sponsor_ID = (select sponsor_id from tbl_sponsor where guarantor_name = '$Guarantor_Name' limit 1) and
						    registration_ID = '$Registration_ID'
                        AND receipt_date between '$Date_From' 
                        AND '$Date_To'
						order by patient_payment_id asc limit 1") or die(mysqli_error($conn));
        $num2 = mysqli_num_rows($first_saved_date);
        if ($num2 > 0) {
            while ($num_rows = mysqli_fetch_array($first_saved_date)) {
                $Payment_Date_And_Time = $num_rows['Payment_Date_And_Time'];
            }
        } else {
            $Payment_Date_And_Time = '';
        }

        echo '<tr><td>' . $temp . '</td>';
        echo "<td>" . ucfirst($row['Patient_Name']) . "</td>";
        echo "<td style='text-align: right;'>" . $row['Registration_ID'] . "</td>";
        echo "<td style='text-align: right;'><a href='foliosummaryreport.php?Folio_Number=" . $row['Folio_Number'] . "&Insurance=" . $row['Guarantor_Name'] . "&Registration_ID=" . $row['Registration_ID'] . "&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>" . $row['Folio_Number'] . "</a></td>";
        echo "<td style='text-align: center;'>" . $row['Guarantor_Name'] . "</td>";
        echo "<td style='text-align: center;'>" . $Payment_Date_And_Time . "</td>";
        ?>
        <!-- <td> -->
            <!-- <input type='button' name='View_Button' id='View_Button' value='view' onclick="openItemDialog(<?php echo $row['Folio_Number']; ?>, '<?php echo $row['Guarantor_Name']; ?>',<?php echo $row['Registration_ID']; ?>, '<?php echo $Payment_Type; ?>', '<?php echo $Patient_Bill_ID; ?>');" class='art-button-green'> -->

            <?php
            if ($row['Billing_Process_Status'] == 'Approved') {
                $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
                //get employee Name
                $select_employee = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Billing_Process_Employee_ID'") or die(mysqli_error($conn));
                while ($select_emp = mysqli_fetch_array($select_employee)) {
                    $Employee_Name = $select_emp['Employee_Name'];
                }

                ?>    
                                                                    <!--<input type="checkbox" disabled='disabled' checked='checked' >-->
                <!-- <span style='color: #037CB0;'><b style="color: #037CB0;">Approved By <?php echo $Employee_Name; ?></b></span></td> -->

            <?php
        } else {
            ?>

            <!-- <span style='color: green;'><b>Pending.....</b></span></td> -->
        <!--<span id='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' name='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' style='color: green;'><b>Pending.....</b></span></td>-->    

        <?php
    }


    //generate amount based on folio number
    $select_total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) as Amount from
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
					pp.patient_payment_id = ppl.patient_payment_id and
					pr.registration_id = pp.registration_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Bill_ID IS NULL and
					pp.Transaction_status <> 'cancelled' and
					pp.Registration_ID = '$Registration_ID' and
					(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and
                    pp.receipt_date between '$Date_From' AND '$Date_To'
					AND pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'nhif' limit 1)") or die(mysqli_error($conn));

    $num_rows = mysqli_num_rows($select_total);
    if ($num_rows > 0) {
        while ($dt = mysqli_fetch_array($select_total)) {
            $Amount = $dt['Amount'];
        }
    } else {
        $Amount = 0;
    }
    echo "<td style='text-align: right;'>" . number_format($Amount) . "</td>";
    echo "</tr>";
    $total = $total + $Amount;
    $temp++;
}echo "<tr><td colspan=8><hr></td></tr>";
echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : " . number_format($total) . "</td></tr>";
echo "<tr><td colspan=8 ><hr></td></tr>";
?></table></center>

</fieldset>
<fieldset>
    <input type="button" value="PREVIEW" class="art-button-green pull-right" onclick="preview_on_pdf()"/>
</fieldset>

<script>
    function preview_on_pdf(){
        var Branch = document.getElementById("Branch").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Insurance = document.getElementById("Insurance").value;
        var Payment_Type = document.getElementById("Payment_Type").value;
        window.open("revenue_collection_by_folio_pending_preview.php?Insurance="+Insurance+"&date_From="+date_From+"&date_To="+date_To+"&Payment_Type="+Payment_Type+"&Branch="+Branch,"_blank");
    }
    function Get_Transaction_List() {
        var Branch = document.getElementById("Branch").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Insurance = document.getElementById("Insurance").value;
        var Payment_Type = document.getElementById("Payment_Type").value;

        if (Branch != '' && Branch != null && date_From != '' && date_From != null && date_To != '' && date_To != null && Insurance != '' && Insurance != null && Payment_Type != '' && Payment_Type != null) {
            document.getElementById('Items_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObjectGetTransaction = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetTransaction = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetTransaction.overrideMimeType('text/xml');
            }
            myObjectGetTransaction.onreadystatechange = function () {
                myData = myObjectGetTransaction.responseText;
                if (myObjectGetTransaction.readyState == 4) {
                    //alert(myData);
                    document.getElementById('Items_Fieldset_List').innerHTML = myData;
                }
            }; //specify name of function that will handle server response........

            myObjectGetTransaction.open('GET', 'Time_Range_Summary_report_By_Folio_Iframe_pending.php?Branch=' + Branch + '&date_From=' + date_From + '&date_To=' + date_To + '&Insurance=' + Insurance + '&Payment_Type=' + Payment_Type, true);
            myObjectGetTransaction.send();
        } else {

            if (Branch == '' || Branch == null) {
                document.getElementById("Branch").style = 'border: 3px solid red';
            }

            if (date_From == '' || date_From == null) {
                document.getElementById("date_From").style = 'border: 3px solid red';
            }

            if (date_To == '' || date_To == null) {
                document.getElementById("date_To").style = 'border: 3px solid red';
            }

            if (Insurance == '' || Insurance == null) {
                document.getElementById("Insurance").style = 'border: 3px solid red';
            }

            if (Payment_Type == '' || Payment_Type == null) {
                document.getElementById("Payment_Type").style = 'border: 3px solid red';
            }
        }
    }

</script>

<div id="Display_Folio_Details" style="width:50%;" >
    <span id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

<!---Display patient signature---->
<div id="Display_Patient_Signature" style="width:50%;" >
    <span id='Signature_Area'>
        <table width=100% style='border-style: none;'>
            <input type='hidden' id='Registration_ID'>
            <input type='hidden' id='Folio_Number'>
            <input type='hidden' id='Sponsor_ID'>
            <input type='hidden' id='Patient_Bill_ID'>
            <input type='hidden' id='Control_Quantity'>
            <input type='hidden' id='Patient_Payment_ID'>
            <tr>
                <td>
                    <textarea id='Signature_value'>  </textarea>
                    <br /><br />
                </td>
            </tr>
            <tr>
                <td>
            <center><input type='button' value='Approve Transaction' onclick='patient_approval()' id='Patient_approve_btn' class='art-button-green'></center>
            </td>
            </tr>
        </table>
    </span>
</div>

<!-- NHIF VERIFICATION FUNCTION		-->
<script src="js/token.js"></script>

<script>
                function updateAuthorizationNo(Check_In_ID) {
                    var AuthorizationNo = $('#AuthorizationNo').val();
                    $.ajax({
                        type: "GET",
                        url: "updateAuthorizationNo.php",
                        data: {AuthorizationNo: AuthorizationNo, Check_In_ID: Check_In_ID},
                        success: function (data) {
                            alert(data);
                        },

                    });
                }
</script>
<script type="text/javascript">
    function Approval_Fail_Message() {
        alert("Patient bill must contain type of illness and consultant name");
    }
</script>

<script>

    function openItemDialog(Folio_Number, Insurance, Registration_ID, Payment_Type, Patient_Bill_ID) {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Folio_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'billingprocess.php?Folio_Number=' + Folio_Number + '&Insurance=' + Insurance + '&Registration_ID=' + Registration_ID + '&Payment_Type=' + Payment_Type + '&Patient_Bill_ID=' + Patient_Bill_ID, true);
        myObjectGetDetails.send();
    }


    //Patient signature
    function openSignatureDialog(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID) {
        $("#Display_Patient_Signature").dialog("open");
        document.getElementById('Registration_ID').value = Registration_ID;
        document.getElementById('Folio_Number').value = Folio_Number;
        document.getElementById('Sponsor_ID').value = Sponsor_ID;
        document.getElementById('Patient_Bill_ID').value = Patient_Bill_ID;
        document.getElementById('Control_Quantity').value = Control_Quantity;
        document.getElementById('Patient_Payment_ID').value = Patient_Payment_ID;
    }
</script>



<script>
    function Search_Patient_Filtered(){
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Branch = document.getElementById("Branch").value;
        var Insurance = document.getElementById("Insurance").value;
        var Patient_Name = document.getElementById("Search_Value").value;
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Patient_Phone_Number").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchByName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchByName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchByName.overrideMimeType('text/xml');
        }

        myObjectSearchByName.onreadystatechange = function () {
            data2900 = myObjectSearchByName.responseText;
            if (myObjectSearchByName.readyState == 4) {
                document.getElementById('Items_Fieldset_List').innerHTML = data2900;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered_pending.php?Patient_Name=' + Patient_Name + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Name', true);
        myObjectSearchByName.send();
    }
</script>


<script>
    function Search_Patient_Filtered_Patient_Number() {
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Branch = document.getElementById("Branch").value;
        var Insurance = document.getElementById("Insurance").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        document.getElementById("Search_Value").value = '';
        document.getElementById("Patient_Phone_Number").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchByName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchByName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchByName.overrideMimeType('text/xml');
        }

        myObjectSearchByName.onreadystatechange = function () {
            data2900 = myObjectSearchByName.responseText;
            if (myObjectSearchByName.readyState == 4) {
                document.getElementById('Items_Fieldset_List').innerHTML = data2900;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered_pending.php?Patient_Number=' + Patient_Number + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Number', true);
        myObjectSearchByName.send();
    }
</script>


<script>
    function Search_Patient_Filtered_Phone_Number() {
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Branch = document.getElementById("Branch").value;
        var Insurance = document.getElementById("Insurance").value;
        var Patient_Phone_Number = document.getElementById("Patient_Phone_Number").value;
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Value").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchByName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchByName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchByName.overrideMimeType('text/xml');
        }

        myObjectSearchByName.onreadystatechange = function () {
            data2900 = myObjectSearchByName.responseText;
            if (myObjectSearchByName.readyState == 4) {
                document.getElementById('Items_Fieldset_List').innerHTML = data2900;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered_pending.php?Patient_Phone_Number=' + Patient_Phone_Number + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Phone_Number', true);
        myObjectSearchByName.send();
    }
</script>




<script>
    $(document).ready(function () {
        $("#Display_Folio_Details").dialog({autoOpen: false, width: '95%', height: 700, title: 'BILLING PROCESS', modal: true});

        $("#Display_Patient_Signature").dialog({autoOpen: false, width: '60%', height: 200, title: 'PATIENT SIGNATURE', modal: true});

        $('.ui-dialog-titlebar-close').click(function () {
            Get_Transaction_List();
        });

    });
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    });
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>




<?php
include("./includes/footer.php");
?>