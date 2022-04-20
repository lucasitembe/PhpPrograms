<?php include("./includes/header.php"); ?>
<script>
    function CheckUnProcessedItem(Registration_ID,Check_In_ID){
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        $.ajax({
            type: "POST",
            url: "CheckUnProcessedItem.php",
            data: { date_From:date_From,date_To:date_To,  Registration_ID: Registration_ID, Check_In_ID: Check_In_ID, Patient_Bill_ID: Patient_Bill_ID,UnProcessedItem:''},           
            success: function (responce) {
                if(responce==0){
                    alert("Hamna"+responce)
                } else{
                    alert("ipo"+responce)
                }
            }
        })
    }
    function Confirm_Approval_Trnsaction(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID, Sponsor_Name, Check_In_ID, AuthorizationNo,full_disease_data, notDone) {
        if(notDone=='yes'){
            var unprocesseditems = $("#unprocesseditems").val();
            if(unprocesseditems =='' ){
                alertToastr('', 'It seems there are unprocessd Service please check or Enter Reason For Approval bill with unprocessd Service', 'warning', '', false);
                if(unprocesseditems.length<10){
                    alertToastr('', 'Enter atleast more than 10 characheters', 'info', '', false);
                }
                $("#unprocesseditems").css("border", "3px solid red");
                exit;
            }
        }else{
            var unprocesseditems = 'No';
        }
        
      if(AuthorizationNo.trim() == ''){
        alert('Patient misses Authorization Number\n Please Authize first ');
        return false;
      }

	 if(AuthorizationNo.length < 12){
		alert("AUTHORIZATION NUMBER IS LESS THAN 12 DIGITS");
        return false;
      }

        if (Control_Quantity == 'yes') {
            // CheckUnProcessedItem(Registration_ID,Check_In_ID);
            
            var r = confirm("Are you sure you want to approve this transaction?\n\nClick OK to proceed");
            if (r == true) {
               
                Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name,Check_In_ID,full_disease_data);

                if(notDone =='yes'){
                    $.ajax({
                    type: "POST",
                    url: "CheckUnProcessedItem.php",
                    data: {  Registration_ID: Registration_ID, Check_In_ID: Check_In_ID,unprocesseditems:unprocesseditems, UnProcessedItem:''},           
                    success: function (responce) {
                        console.log(responce);
                    }
                    })
                }
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
        //  alert("Process Fail! Some transactions contain zero quantity");
        //}
    }

</script>
<script type='text/javascript'>
        function Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name,Check_In_ID,full_disease_data) {
        var Bill_ID = '';
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
                var Bill_ID = generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name,full_disease_data, Check_In_ID);
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Patient_Payment_ID=' + Patient_Payment_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, true);
        myObject.send();

        alertToastr('', 'Approving bill. please wait..... ', 'info', '', false);
        $('#progressStatus').show();
    }
    //Genarate bill
    function generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name,full_disease_data, Check_In_ID) {
        var Bill_ID = '';
        $.ajax({
            type: "GET",
            url: "Generate_Bill.php",
            'async':false,
            dataType: "json",
            data: {Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID,full_disease_data:full_disease_data, Check_In_ID:Check_In_ID},
            beforeSend: function (xhr) {
                alertToastr('', 'Generating bill. please wait..... ', 'info', '', false);
            },
            success: function (data) {
                if (data.status == '200') {
                    alertToastr('', 'Bill Generated successifully.', 'success', '', false);
                        Bill_ID = data.data;
                    //if (Sponsor_Name === "NHIF") {
                        SendBill(data.data, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID);
                    //}
                } else if (data.status == 300) {
                    alertToastr('', 'Failed to generate bill. ' + data, 'error', '', false);
                } else {
                    alertToastr('', 'An error has occured.please try again. ' + data, 'error', '', false);
                }
            }
        });
        return Bill_ID;
    }
    function SendBill(Bill_ID, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID) {
        $.ajax({
            type: "GET",
            url: "createXMLBill.php",
            data: {Bill_ID: Bill_ID, Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID,Location:"InitialStage"},
            beforeSend: function (xhr) {
                $('#progressStatus').show();
                alertToastr('', 'Sending bill. please wait..... ', 'info', '', false);
                console.log("before sending");
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                if (data.StatusCode == '200') {
                    alertToastr('', data.Message, 'success', '', false);
                } else if (data.StatusCode == '0') {
                    alertToastr('', 'There is a problem with connectivity, please check your internet connection!', 'error', '', false);
                } else {
                    alertToastr('', data.Message, 'error', '', false);
                }

            }, complete: function (x, y) {
                console.log(x+y);
                $('#progressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR+textStatus+errorThrown);
                $('#progressStatus').hide();
            }
        });
    }

    // function createClaim(){
    //   $.ajax({
    //     url:'create_claim.php',
    //     type:'post',
    //     data:{Registration_ID:Registration_ID,AuthorizationNo:AuthorizationNo,disease_code:disease_code,item_id:item_id,unit_price:unit_price,quantity:quantity,discount:discount},
    //     success:function(){
    //
    //     }
    //   });
    // }
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
echo "<link rel='stylesheet' href='fixHeader.css'>";
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
<script src="script.responsive.js"></script>-->
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
                <td width="5%" style="text-align: right;"><b>Start Date</b></td>
                <td style='width:10%; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='Date_From' id='date_From' required='required' style='text-align: center;' value="<?php echo $Today; ?>">
                </td>
                <td width="5%" style="text-align: right;"><b>End Date</b></td>
                <td style='text-align: center;width:10%; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='Date_To' id='date_To' required='required' style='text-align: center;' value="<?php echo $Today; ?>">
                </td>
                <td width="5%" style="text-align: right;"><b>Sponsor</b></td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='Insurance' id='Insurance' required='required' onchange="Get_Transaction_List()">
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
                <td width='7%'><b>Authorization No</b></td>
                <td style='text-align: center;width:7%; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='PatientAuthorizationNo' id='PatientAuthorizationNo' required='required'>
                        <option value="all">All</option>
                        <option value="with_authorization_no">With Authorization No</option>
                        <option value="without_authorization_no">Without Authorization No</option>
                    </select>
                </td>
                <td style="text-align:right;"><b>Patient Status</b></td>
                <td style='text-align: center;text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <!-- <select name='Payment_Type' id='Payment_Type' required='required' style="width:100%;">
                        <option selected='selected'>Credit</option>
                    </select> -->
                    <select id="current_patient_status" name="current_patient_status" onchange='Get_Transaction_List()'>
                      <option value="all">Select All</option>
                      <option value="Inpatient">Inpatient</option>
                      <option value="Outpatient">Outpatient</option>
                    </select>
                </td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='Get_Transaction_List()'>
                </td>
            </tr>
            <tr>
              <!-- functions are not working for now,  i add x-->
                <td colspan="5" style="text-align: center;">
                    <input type="text" id="Search_Value" name="Search_Value" style="text-align: center;" placeholder='~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~' autocomplete='off' oninput="Get_Transaction_List()" onkeyup="Get_Transaction_List()">
                </td>
                <td colspan="2" style="text-align: center;">
                    <input type="text" id="Patient_Number" name="Patient_Number" style="text-align: center;" placeholder='~~~~~~Enter Patient Number~~~~~~' autocomplete='off' oninput="Get_Transaction_List()" onkeyup="Get_Transaction_List()">
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
    $select_Filtered_Patients = "   SELECT pr.Patient_Name,pr.Phone_Number, pr.Registration_ID, sp.Guarantor_Name, pp.Folio_Number, pp.Patient_Bill_ID, pp.Billing_Process_Status,pp.Billing_Type, pp.Billing_Process_Employee_ID, ci.AuthorizationNo, ci.Check_In_ID,  ppl.Clinic_ID     from tbl_patient_payments pp, tbl_check_in ci, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp  where pp.patient_payment_id = ppl.patient_payment_id  and   ci.Check_In_ID = pp.Check_In_ID and  pp.registration_id = pr.registration_id and   pp.receipt_date between '$Date_From' and '$Date_To' and   sp.sponsor_id = pp.sponsor_id and   pp.Bill_ID IS NULL and    pp.Transaction_status <> 'cancelled' and Billing_Type IN ('Inpatient Credit', 'Outpatient Credit') and   pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = 'NHIF') and   pp.branch_id = '$Branch_ID'   GROUP BY ci.Check_In_ID  ORDER BY ci.Check_In_ID ASC ";

    echo '<center><table width =100% border=0 class="fixTableHead">
    <thead>';
    echo '<tr id="thead">
                <td width=5%><b>SN</b></td>
                <td width="15%"><b>Patient Name</b></td>
                <td width="10%"><b>Phone Number</b></td>
                <td width="7%" style="text-align: right;"><b>Patient#</b></td>
                <td width="7%" style="text-align: right;"><b>Authorization#</b></td>
                <td width="8%" style="text-align: right;"><b>Folio Number</b></td>
                <td width="7%" style="text-align: center;"><b>Sponsor</b></td>
                <td width="8%" style="text-align: center;"><b>Clinic Name</b></td>
                <td width="8%" style="text-align: center;"><b>Served Date</b></td>
                <td width="7%" style="text-align: right;"><b>Patient Status</b></td>
                <td width="18%" style="text-align: center;"><b>Approval Status</b></td>
                <td width="10%" style="text-align: right;"><b>Amount</b></td>
            </tr></thead>';
    $results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($results)) {
        //get first served date
        $Folio_Number = $row['Folio_Number'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Bill_ID = $row['Patient_Bill_ID'];
        $Registration_ID = $row['Registration_ID'];
        $Check_In_ID = $row['Check_In_ID'];
        $Billing_Type = $row['Billing_Type'];
	    $Clinic_ID = $row['Clinic_ID'];

		/*  check if other items have already billed 	*/
		$billed_items = mysqli_query($conn,"SELECT patient_payment_id FROM tbl_patient_payments WHERE Check_In_ID = '$Check_In_ID' AND Bill_ID IS NOT NULL");
		if(mysqli_num_rows($billed_items) >0){continue;}

        $style = '';
		$select111 = mysqli_query($conn,"SELECT cd.Admission_ID, Admission_Status, Discharge_Clearance_Status FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
        if(mysqli_num_rows($select111) > 0){
            while($rw = mysqli_fetch_assoc($select111)){
            $Billing_Type ='Inpatient';
            $Admission_Status = $rw['Admission_Status']; 
            $Discharge_Clearance_Status = $rw['Discharge_Clearance_Status']; 
            $Discharge_Reason_ID = $rw['Discharge_Reason_ID'];
            if($Discharge_Reason_ID >0 && $Discharge_Clearance_Status=='not cleared'){
                $style = "style='background-color: yellow; color:#fff;'";
            }
            }            
        }else{
            $Billing_Type ='Outpatient';

        }


        /*
            check if a patient has been consulted by the doctor
        */
        $ConsultationInfo = mysqli_query($conn,"SELECT c.consultation_ID, c.Clinic_ID, c.Process_Status FROM tbl_consultation c, tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND pp.Check_In_ID ='$Check_In_ID'");

            $hasServedConsultation = array();
        while ($rowConsult = mysqli_fetch_assoc($ConsultationInfo)) {
            
            $Consultation_ID = $rowConsult['consultation_ID'];
            $Process_Status = $rowConsult['Process_Status'];
            $Clinic_IDs = $rowConsult['Clinic_ID'];

            array_push($hasServedConsultation,$Process_Status);
        }

        if(!in_array('served',$hasServedConsultation)){
          continue;
        }

        if($Clinic_IDs > 0){
            $Clinic_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic_IDs'"))['Clinic_Name'];
          }
	    $signature = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'  AND signature IS NOT NULL"));
        
        if(!empty($signature)){
          $style = "style='background-color: green; color:#fff;'";
        }
        echo '<tr '.$style.'><td>' . $temp . '</td>';
        echo "<td>" . ucfirst($row['Patient_Name']) . "</td>";
        echo "<td>" . $row['Phone_Number'] . "</td>";
        echo "<td style='text-align: right;'>" . $row['Registration_ID'] . "</td>";
        echo "<td style='text-align: right;'>" . $row['AuthorizationNo'] . "</td>";
        echo "<td style='text-align: right;'><a href='foliosummaryreport.php?Folio_Number=" . $row['Folio_Number'] . "&Insurance=" . $row['Guarantor_Name'] . "&Registration_ID=" . $row['Registration_ID'] . "&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>" . $row['Folio_Number'] . "</a></td>";
        echo "<td style='text-align: center;'>" . $row['Guarantor_Name'] . "</td>";
        echo "<td style='text-align: center;'>" . $Clinic_Name . "</td>";

        echo "<td style='text-align: center;'>" . $Date_From . "</td>";
        echo "<td style='text-align: center;'>".$Billing_Type."</td>";
        ?>
        <td style="text-align:center;">
            <input type='button' name='View_Button' id='View_Button' value='view' onclick="openItemDialog(<?php echo $row['Folio_Number']; ?>,'<?php echo $row['Guarantor_Name']; ?>',<?php echo $row['Registration_ID']; ?>,'<?php echo $Payment_Type; ?>','<?php echo $Patient_Bill_ID; ?>',<?php echo $row['Check_In_ID']; ?>);" class='art-button-green'>

            <?php
            if ($row['Billing_Process_Status'] == 'Approved') {
                $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
                //get employee Name
                $select_employee = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Billing_Process_Employee_ID'") or die(mysqli_error($conn));
                while ($select_emp = mysqli_fetch_array($select_employee)) {
                    $Employee_Name = $select_emp['Employee_Name'];
                }
                ?>
                                                                    <!--<input type="checkbox" disabled='disabled' checked='checked' >-->
                <span style='color: #037CB0;'><b style="color: #037CB0;">Approved By <?php echo $Employee_Name; ?></b></span></td>

            <?php
        } else {
            ?>

            <span style='color: green;'><b>Pending.....</b></span></td>
        <!--<span id='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' name='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' style='color: green;'><b>Pending.....</b></span></td>-->

        <?php
    }


    //generate amount based on folio number
    $select_total = mysqli_query($conn,"select sum((price - discount)*quantity) as Amount from
                    tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_check_in ci where
            ci.Check_In_ID=pp.Check_In_ID and
            ci.Check_In_ID='".$row['Check_In_ID']."' and
                    pp.patient_payment_id = ppl.patient_payment_id and
                    pr.registration_id = pp.registration_id and  pp.Patient_Bill_ID = '$Patient_Bill_ID' and ppl.Status<>'removed' and 
                    pp.Bill_ID IS NULL and
                    pp.Transaction_status <> 'cancelled' and
                    pp.Registration_ID = '$Registration_ID' and Billing_Type IN ('Inpatient Credit', 'Outpatient Credit') and  pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'NHIF' limit 1)") or die(mysqli_error($conn));
// pp.Folio_Number = '$Folio_Number' and
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
}echo "<tr><td colspan=11><hr></td></tr>";
echo "<tr><td colspan=11 style='text-align: right;'><b> TOTAL : " . number_format($total) . "</td></tr>";
echo "<tr><td colspan=11 ><hr></td></tr>";
?></table></center>

</fieldset>
<center>
    <!-- <input type="button" name="preview_patient_bills" id="preview_patient_bills" onclick="Preview_Bills_Report();" value="Preview" class="art-button-green"> -->
</center>
<script>
    function Get_Transaction_List() {

        var Branch = document.getElementById("Branch").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Insurance = document.getElementById("Insurance").value;
        //var Payment_Type = document.getElementById("Payment_Type").value;
        var Payment_Type = 'Credit';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var AuthorizationNo = document.getElementById("PatientAuthorizationNo").value;
        var current_patient_status = document.getElementById("current_patient_status").value;

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

            myObjectGetTransaction.open('GET', 'Time_Range_Summary_report_By_Folio_Iframe.php?Branch=' + Branch + '&date_From=' + date_From + '&date_To=' + date_To + '&Insurance=' + Insurance + '&Payment_Type=' + Payment_Type+'&AuthorizationNo='+AuthorizationNo+'&Search_Value='+Search_Value+'&Patient_Number='+Patient_Number+'&current_patient_status='+current_patient_status, true);
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

<div id="Add_Disease_Dialog" >
  <?php
  $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
     if(mysqli_num_rows($get_icd_9_or_10_result)>0){
         $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
     }
   ?>
   <div style="height:350px;overflow-y: scroll;">

  <table class="table table-condensed">
    <tr>
      <td width="40%">
        <table class="table table-condensed" style="width:100%!important">
            <tr>
                <td>
                    <table style="width: 100%">
                        <td>
                            <input type="text"id="disease_name" onkeyup="search_diseases('<?=$configvalue_icd10_9;?>'),clear_other_input('disease_code')" placeholder="----Search Disease Name----" class="form-control">
                        </td>
                        <td>
                            <input type="text" id="disease_code" onkeyup="search_diseases('<?=$configvalue_icd10_9;?>'),clear_other_input('disease_name')" placeholder="----Search Disease Code----" class="form-control">
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=""><b>Select Diseases To Add</b></td>
            </tr>
            <tbody id="disease_suffred_table_selection">
            <?php
              $select_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
              while($row=mysqli_fetch_assoc($select_diseases)){
                extract($row);
                $disease_id="{$disease_ID}";
                echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\",\"$Registration_ID\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
              }
            ?>
            </tbody>
        </table>
      </td>
      <td width="60%">
        <table class="table" id="new_added_disease_list">
            <tr>
                <td colspan="4"><b>Added Diseases To Patient</b>
                </td>
            </tr>
            <tr>
                <td><b>S/No.</b></td>
                <td><b>Disease name</b></td>
                <td><b>Disease code</b></td>
                <td><b>Remove</b></td>
            </tr>

        </table>
      </td>
    </tr>
  </table>

  </div>
      <!-- <td colspan="2" style="text-align:right;"--> <input type="submit" name="btn_dialogded_diseases" value="Save" class= "art-button" onclick="dialog_added_diseases();" style="float:right;" >&emsp; <!--/td> -->

</div>
    <div id="Remove_Disease_Dialog">

    </div>
    <div id='discharge_dialogue' ></div>
<?php
    $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
   if(mysqli_num_rows($get_icd_9_or_10_result)>0){
       $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
   }
    ?>

<!-- NHIF VERIFICATION FUNCTION     -->
<script src="js/token.js"></script>

<script>
    function updateAuthorizationNo(Check_In_ID) {
        var AuthorizationNo = $('#AuthorizationNo').val();
        if(AuthorizationNo.length <12){
            alert("Invalid authorization Number !!!!.");
            $('#AuthorizationNo').css("border", "2px solid red");
            return false;

        }
        var Folio_Number    = $('#Folio_Number').val();
        var Insurance       = $('#Insurance').val();
        var Registration_ID = $('#Registration_ID').val();
        var Payment_Type    = $('#Payment_Type').val();
        var Patient_Bill_ID = $('#Patient_Bill_ID').val();
        var Check_In_ID     = $('#Check_In_ID').val();

        $.ajax({
                type: "GET",
                url: "updateAuthorizationNo.php",
                data: {AuthorizationNo: AuthorizationNo, Check_In_ID: Check_In_ID},
                    success: function (data) {
                    alert(data);
                    openItemDialog(Folio_Number,Insurance,Registration_ID,Payment_Type,Patient_Bill_ID,Check_In_ID);
                },
          });
        }
</script>
<script type="text/javascript">
    function Approval_Fail_Message() {
        alert("Patient bill must contain type of illness and consultant name / Take patient signature then click refresh");
    }
</script>

<script>

function change_discharge_dialogue(Admission_ID){
    var Registration_ID = $('#Registration_ID').val();
        $.ajax({
            url:'update_discherge_date.php',
            type:'post',
            data:{ Admission_ID:Admission_ID,Registration_ID:Registration_ID, dialogue:''},
            success:function(responce){
                $("#discharge_dialogue").dialog({
                        title: 'Discharge Date ',
                        width: '40%',
                        height: 300,
                        modal: true,
                });
                $("#discharge_dialogue").html(responce);
            }
        });
    }

    function update_discharge_date(Admission_ID){
        var update_dis_time = $('#DischargeDateTime').val();
        var update_ad_time = $('#update_ad_time').val();
        var Registration_ID = $('#Registration_ID').val();
        $.ajax({
            url:'update_discherge_date.php',
            type:'post',
            data:{update_dis_time:update_dis_time, Admission_ID:Admission_ID, Registration_ID:Registration_ID},
            success:function(result){
                alert(result);
            }
        });
    }
  

    function openItemDialog(Folio_Number, Insurance, Registration_ID, Payment_Type, Patient_Bill_ID,Check_In_ID) {
      console.log('chek '+Check_In_ID);
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

                $('#diagnosis_disease_id').select2();

            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'billingprocess.php?Folio_Number=' + Folio_Number + '&Insurance=' + Insurance + '&Registration_ID=' + Registration_ID + '&Payment_Type=' + Payment_Type + '&Patient_Bill_ID=' + Patient_Bill_ID +'&Check_In_ID='+Check_In_ID, true);
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
    function Search_Patient_Filtered() {
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

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered.php?Patient_Name=' + Patient_Name + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Name', true);
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

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered.php?Patient_Number=' + Patient_Number + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Number', true);
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

        myObjectSearchByName.open('GET', 'Search_Patient_Filtered.php?Patient_Phone_Number=' + Patient_Phone_Number + '&Insurance=' + Insurance + '&date_From=' + date_From + '&date_To=' + date_To + '&Branch=' + Branch + '&Control=Patient_Phone_Number', true);
        myObjectSearchByName.send();
    }
</script>

<script>
    $(document).ready(function () {
        $("#Display_Folio_Details").dialog({autoOpen: false, width: '95%', height: 700, title: 'BILLING PROCESS', modal: true});

        $("#Display_Patient_Signature").dialog({autoOpen: false, width: '60%', height: 200, title: 'PATIENT SIGNATURE', modal: true});

        $("#Add_Disease_Dialog").dialog({autoOpen: false, width: '60%', height: 450, title: 'ADD DISEASES', modal: true});
        $("#Remove_Disease_Dialog").dialog({autoOpen: false, width: '60%', height: 450, title: 'REMOVE DISEASES', modal: true});
        $('.ui-dialog-titlebar-close').click(function () {
            Get_Transaction_List();
        });

    });

    function search_diseases(disease_version){
        var disease_code=$("#disease_code").val();
        var disease_name=$("#disease_name").val();

       $.ajax({
           type:'GET',
           url:'search_disease_c_death.php',
           data:{disease_code:disease_code,disease_name:disease_name,disease_version:disease_version, search_valuedisease:''},
           success:function (data){
               //console.log(data);
               $("#disease_suffred_table_selection").html(data);
           },
           error:function (x,y,z){
               console.log(z);
           }
       });
    }

    function clear_other_input(disease_namedisease_code){
        $("#"+disease_namedisease_code).val("");
    }
    // the function called from search_disease_c_death.php but perform different function
    function add_death_reason(disease_ID){

      $.ajax({
        url:'search_disease_c_death.php',
        type:'post',
        data:{disease_ID:disease_ID, selected_disease:'added_disease'},
        success:function(results){
          search_diseases('<?=$configvalue_icd10_9;?>');
          var rowCount = $('#new_added_disease_list tr:last').index();
          $('#new_added_disease_list tbody').append('<tr id="child_'+rowCount+'"><td>'+rowCount+'</td>'+results+'<td><input type="button" value="X" style="color:red; " onclick="remove_disease('+rowCount+');"></td></tr>');
        }
      });
    }

    function dialog_added_diseases(){
      //credentials for openItemDialog to refresh
      var Folio_Number    = $('#Folio_Number').val();
      var Insurance       = $('#Insurance').val();
      var Registration_ID = $('#Registration_ID').val();
      var Payment_Type    = $('#Payment_Type').val();
      var Patient_Bill_ID = $('#Patient_Bill_ID').val();
      var Check_In_ID     = $('#Check_In_ID').val();

      var consultation_ID = $('#Consultation_ID').val();
      var patient_status  = $('#patient_status').val();
      var Employee_ID     = "<?=$_SESSION['userinfo']['Employee_ID']?>";
      var Employee_Name   = "<?=$_SESSION['userinfo']['Employee_Name']?>";
      var disease_list    = [];
      var consultants     = $("#Consultant_Name").val();
      var illness_type    = $("#illness_type").val();

        //check if patient attended by doctor
        if(consultation_ID  ==  ''){
            alert('THIS PATIENT HAS NOT BEEN ATTENDED BY A DOCTOR');
            return false;
        }
      // alert(consultation_ID);
      // return false;

      $( ".disease_list" ).each(function( index ) {
        disease_list.push($(this).val());
      });
      //remove the added diseases
      var rowCount = $('#new_added_disease_list tr:last').index();
      for(var i=0; i<=rowCount; i++){
        $('#child_'+i).remove();
      }
      //convert array of added diseases to json string
      var disease_list= JSON.stringify(disease_list);
      $.ajax({
        type: "POST",
        url: "add_disease_from_folio.php",
        data: { disease_list: disease_list, Employee_ID:Employee_ID, consultation_ID:consultation_ID,patient_status:patient_status},
        success: function(results){
          //alert(results);
          if(results == 'ok'){
            $("#Add_Disease_Dialog").dialog("close");
            openItemDialog(Folio_Number,Insurance,Registration_ID,Payment_Type,Patient_Bill_ID,Check_In_ID);
          }else{
            alert('Something Wrong !!!');
          }
        }
      });
    }
    function remove_disease(item){
      $('#child_'+item).remove();
      return false;
    }

    function display_add_disease_dialog(){
      $("#Add_Disease_Dialog").dialog("open");
    }
    function display_remove_disease_dialog(Folio_Number,Registration_ID,Patient_Bill_ID,Check_In_ID,Consultation_ID){
      $("#Remove_Disease_Dialog").dialog("open");
      $.ajax({
        url:"remove_edit_Items_Bill.php",
        type:"post",
        data:{action:'remove_disease',Folio_Number:Folio_Number,Registration_ID:Registration_ID,Consultation_ID:Consultation_ID,Patient_Bill_ID:Patient_Bill_ID,Check_In_ID:Check_In_ID},
        success:function(result){
          //alert(result)
          $("#Remove_Disease_Dialog").html(result);
        }
      });
    }

    function close_remove_disease_dialog(){
            var Folio_Number    = $('#Folio_Number').val();
            var Insurance       = $('#Insurance').val();
            var Registration_ID = $('#Registration_ID').val();
            var Payment_Type    = $('#Payment_Type').val();
            var Patient_Bill_ID = $('#Patient_Bill_ID').val();
            var Check_In_ID     = $('#Check_In_ID').val();
            $("#Remove_Disease_Dialog").dialog("close");
            openItemDialog(Folio_Number,Insurance,Registration_ID,Payment_Type,Patient_Bill_ID,Check_In_ID);
    }

    function remove_added_disease(patient_status,consultation_ID,Consultant_Name,disease_code,count,e){
      e.closest('tr').remove();
      $.ajax({
        url:'remove_edit_Items_Bill.php',
        type:'post',
        data:{action:'delete',consultation_ID:consultation_ID,Consultant_Name:Consultant_Name,disease_code:disease_code,patient_status:patient_status},
        success:function(result){
          if(result == 'ok'){
            remove_disease(count);
          }
        }
      });
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
        $('#DateFrom').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
        });
        $('#DateFrom').datetimepicker({value: '', step: 01});
        
    });
</script>
<script type="text/javascript">
    function Preview_Details(Folio_Number,Sponsor_ID,Registration_ID,Patient_Bill_ID,Check_In_ID){
        var winClose=popupwindow('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID, 'e-CLAIM DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }

    function Preview_Bills_Report(){
      var Branch = document.getElementById("Branch").value;
      var date_From = document.getElementById("date_From").value;
      var date_To = document.getElementById("date_To").value;
      var Insurance = document.getElementById("Insurance").value;
      //var Payment_Type = document.getElementById("Payment_Type").value;
      var Payment_Type = 'Credit';
      var Patient_Number = document.getElementById("Patient_Number").value;
      var Search_Value = document.getElementById("Search_Value").value;
      var AuthorizationNo = document.getElementById("PatientAuthorizationNo").value;
      var current_patient_status = document.getElementById("current_patient_status").value;

      window.open('print_unapproved_bills_list.php?Branch=' + Branch + '&date_From=' + date_From + '&date_To=' + date_To + '&Insurance=' + Insurance + '&Payment_Type=' + Payment_Type+'&AuthorizationNo='+AuthorizationNo+'&Search_Value='+Search_Value+'&Patient_Number='+Patient_Number+'&current_patient_status='+current_patient_status);
    }
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>




<?php
include("./includes/footer.php");
?>
