<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}
?>
<!--START HERE-->
<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
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
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>
        <!--Script to display patient optional photo-->
        <!--PATIENT PHOTO SCRIPT START-->

        <script>
            function displayPatientPhoto() {
                document.getElementById('photo').onclick = function () {
                    if (document.getElementById('photo').checked) {
                        //use css style to display the photo
                        document.getElementById("PatientPhoto").style.display = "block";
                    } else {
                        document.getElementById("PatientPhoto").style.display = "none";
                    }
                };
                //hide on initial page load
                document.getElementById("PatientPhoto").style.display = "none";
            }

            window.onload = function () {
                displayPatientPhoto();
            };
        </script>


        <!--PATIENT PHOTO SCRIPT END-->
        <script type="text/javascript">
            function gotolink() {
                var url = "<?php
        if ($Registration_ID != '') {
            echo "Registration_ID=$Registration_ID&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById('patientlist').value;

                if (patientlist == 'MY PATIENT LIST') {
                    document.location = "doctorcurrentpatientlist.php?" + url;
                } else if (patientlist == 'CLINIC PATIENT LIST') {
                    document.location = "clinicpatientlist.php?" + url;
                } else if (patientlist == 'CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

     

        <label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' class='btn-default'>
            <select id='patientlist' name='patientlist'>
                <!--	<option></option>-->
                <option>
                    MY PATIENT LIST
                </option>
                <option>
                    CLINIC PATIENT LIST
                </option>
                <option>
                    CONSULTED PATIENT LIST
                </option>
            </select>
            <input type='button' value='VIEW' onclick='gotolink()'>
        </label>
        <a href='patientsignoff.php?<?php
        if ($Registration_ID != '') {
            echo "Registration_ID=$Registration_ID&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            SIGN OFF
        </a>
        <!--Lab Results
        <a href='laboratoryresult.php?<?php
        if ($Registration_ID != '') {
            echo "Registration_ID=$Registration_ID&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            LABORATORY RESULTS
        </a>
    <?php
    }
}
?>
-->
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>
        <a href='./doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage' class='art-button-green'>
            BACK
        </a>
    <?php
    }
}
?>
<br/><br/>
<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
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

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<script>
    function Search_Patient() {
//        var Date_From = document.getElementById('Date_From').value;
//        var Date_To = document.getElementById('Date_To').value;
//        var Patient_Name = document.getElementById("Patient_Name").value;
//        
//        document.getElementById('Patients_Area').innerHTML = "<iframe width='100%' height=350px src='laboratoryresults_Iframe_Search.php?Patient_Name=" + Patient_Name + "&Date_From=" + Date_From + "&Date_To=" + Date_To+"></iframe>";
//   
    }
</script>
 <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
       
<fieldset>
    <center style='background: #006400 !important;color: white;'>
        <b>LABORATORY RESULTS</b>
    </center>
</fieldset>
<fieldset>
    <center>
        <table width="100%" align="center">
            <tr>
                <td style="text-align: center">
                     <?php 
                  $Today_Date = mysqli_query($conn,"select now() as today");
                    while($row = mysqli_fetch_array($Today_Date)){
                        $Today = $row['today'];
                    }
                    $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
                    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
                        $today_start=$start_dt_row['cast(current_date() as datetime)'];
                    }
                ?>
                    <input type="text" autocomplete="off"value="<?= $today_start ?>" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" value="<?= $Today ?>"style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">

                    <input type="text" name="Patient_Name" id="Patient_Name" style="text-align: center;width:35%;display:inline" placeholder="~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~" oninput="filterPatient()"  />
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<fieldset>
    <center>
        <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

        <table width="100%">
            <tr>
                <td >
                    <div id="Patients_Area" style="height: 400px;overflow-y: auto;overflow-x: hidden">
<?php include 'laboratoryresults_Iframe.php'; ?>
                    </div>
                    <!--<iframe width='100%' height=350px src='laboratoryresults_Iframe.php'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patientsResultInfo').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>
<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById("Patient_Name").value;

        if (Date_From == '' || Date_To == '') {
            alert('Please enter both dates to filter');
            exit;
        }

        $.ajax({
            type: 'GET',
            url: 'laboratoryresults_Iframe.php',
            data: 'Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To,
            cache: false,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html != '') {
                    $("#progressStatus").hide();
                    $("#Patients_Area").html(html);

                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#patientsResultInfo').DataTable({
                        "bJQueryUI": true

                    });
                }
            }, error: function (html) {

            }
        });

    }
</script>



<?php
include("./includes/footer.php");
?>
        <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
        <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
        <script src="media/js/jquery.js" type="text/javascript"></script>
        <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
        <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
        <script src="css/jquery-ui.js"></script>