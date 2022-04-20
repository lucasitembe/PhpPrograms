<?php
include("includes/header.php");
include("includes/connection.php");

/* * ***************************SESSION CONTROL****************************** */
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
/* * *************************** SESSION ********************************** */

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

?>
<?php if (isset($_GET['Registration_ID'])) { ?>
       <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $Registration_ID; ?>" class='art-button-green'  target="_blank">PATIENT FILE</a>
<?php } //else{  ?>
       <a href='PatientRadiology_Served_Doctor.php' class='art-button-green'> BACK </a>
<br><br>
<?php
$Supervisor_ID = $_SESSION['userinfo']['Employee_ID'];

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        //$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "
					    and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>
<div id="showdata" style="width:100%;height:350px;overflow-x:hidden;display:none;overflow-y:scroll">
        <div id="my">
        </div>
    </div>
    <div id="showdataComm" style="width:100%;overflow-x:hidden;height:450px;display:none;overflow-y:scroll">
        <div id="myComm">
        </div>
    </div>

<fieldset>
    <center style='background: #006400 !important;color: white;'>
        <b>DOCTORS WORKPAGE OUTPATIENT &nbsp;~~~&nbsp; PATIENT RADIOLOGY RESULT(S)</b>
    </center>
    <center>
        <table width=100%>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width='16%' style='text-align: right'>Patient Name</td>
                            <td width='26%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php
if (isset($Patient_Name)) {
    echo $Patient_Name;
}
?>'></td>
                        </tr>
                        <tr>
                            <td width='13%' style='text-align: right'>Expire Date</td>
                            <td width='16%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                        </tr>
                        <tr>
                            <td width='13%' style='text-align: right'>D.O.B</td>
                            <td width='16%'><input type='text' name='Date_Of_Birth' id='Date_Of_Birth' value='<?php echo $Date_Of_Birth; ?>' disabled='disabled'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Phone Number</td>
                            <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Region</td>
                            <td>
                                <input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'>
                            </td>
                        </tr>

                    </table>
                </td>

                <td>
                    <table width="100%">
                        <tr>
                            <td style='text-align: right'>Sponsor Name</td>
                            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Member Number</td>
                            <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
                        </tr>
                        <tr>
                        <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                        <td style='text-align: right'>Folio Number</td>
                        <td><input type='text' disabled='disabled' value='<?php echo $Folio_Number; ?>'>
                            <input type='hidden' name='Folio_Number' id='Folio_Number' value='<?php echo $Folio_Number; ?>'>
                        </td>
            </tr>
            <tr>
                <td style='text-align: right'>Registration Number</td>
                <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
                    <input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
                </td>
            </tr>
            <tr>
                <td style='text-align: right'>Receipt#</td>
                <td>
                    <input type='text' name='Patient_Payment_ID' id='Patient_Payment_ID' disabled='disabled' value='<?php echo $Patient_Payment_ID; ?>'>
                </td>
            </tr>
        </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style='text-align: right'>Gender</td>
                    <td><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Claim Form Number</td>
                    <td><input type='text' name='Admission_Claim_Form_Number' disabled='disabled'  id='Admission_Claim_Form_Number'<?php if ($Claim_Number_Status == "Mandatory") { ?> required='required'<?php } ?> value='<?php echo $Claim_Form_Number; ?>'></td>
                </tr>
                <tr>
                <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                <td style='text-align: right'>Bill Type</td>
                <td><input type='text' name='Billing_Type' disabled='disabled' id='Billing_Type' value='<?php echo $Billing_Type; ?>'>

                </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Consulting/Doctor</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
            </table>

        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style='text-align: right;width: 100%;'>
                        <fieldset id="PatientPhoto">
                            <legend>Patient Photo</legend>
                            <div>
                                <img src="patientImages/default.PNG" alt="PatientPhoto" width="100%"/>
                            </div>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        </tr>
        </table>
    </center>
</fieldset>
<fieldset style='margin-top:10px;'>
    <center>

        <table  class="hiv_table1" style="width:100%">
            <tr>
                <td id='Search_Iframe'>
                       
                    <div style="width:100%; height:200px;overflow-y:scroll;overflow-x:hidden">
                      <?php include 'RadiologyPatientTests_Iframe_Doctor.php'; ?>
                    </div>
                    <div style="width:100%;text-align:center"><?php echo $clinickNote; ?></div>
                 </td>
            </tr>
        </table>
    </center>
    
</fieldset>
<br/>

<script>
    function CloseImage() {
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId, inVal) {
        if (inVal == 'in') {
            var zoomVal = 10;
        } else {
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue) + zoomVal;
        document.getElementById(imgId).style.width = Sizevalue + '%';
    }


</script>
<script>
    function uploadImages() {
        $('#radimagingform').ajaxSubmit({
            beforeSubmit: function () {
                //alert('submiting');
            },
            success: function (result) {
                // alert(result);
                var data = result.split('<1$$##92>');
                if (data[0] != '') {
                    alert(data[0]);
                }
                // alert(data[1]);
                $('#my').html(data[1]);

            }

        });
        return false;
    }
</script>
<script >
    function radiologyviewimage(href, itemName) {
        var datastring = href;
        //alert(datastring);
        $("#showdata").dialog("option", "title", "PATIENT RADIOLOGY IMAGING ( " + itemName + " )");
        $.ajax({
            type: 'GET',
            url: 'requests/radiologyviewimage_doctor.php',
            data: datastring,
            success: function (result) {
                $('#my').html(result);
                $("#showdata").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>

<script >
    function commentsAndDescription(href, itemName) {
        var datastring = href;
        $("#showdataComm").dialog("option", "title", "COMMENT AND DESCRIPTION ( " + itemName + " )");
        //alert(datastring);
        $.ajax({
            type: 'GET',
            url: 'requests/RadiologyPatientTestsComments_doctor.php',
            data: datastring,
            success: function (result) {
                //alert(result);

                $('#myComm').html(result);
                $("#showdataComm").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });


    }
</script>
<script type="text/javascript">
    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#Patient_Picture').attr('src', e.target.result).width('30%').height('20%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML = "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>
<script>
    function SelectViewer(imgSrc) {
        parent.document.getElementById('imgViewerImg').src = imgSrc;
        parent.document.getElementById('imgViewer').style.visibility = 'visible';
    }
</script>
<script>
    function Show_Patient_File() {
        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }


</script>

<script>
    $(document).ready(function () {
        $("#showdata").dialog({autoOpen: false, width: '90%',height: 350, title: 'PATIENT RADIOLOGY IMAGING', modal: true, position: 'middle', draggable: true, resizable: true});
        $("#showdataComm").dialog({autoOpen: false, width: '90%', height: 450, title: 'COMMENT AND DESCRIPTION', modal: true, position: 'center'});
        $("#uploadImage").click(function (e) {//

            alert('Submiited');
        });
    });
</script>


<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
