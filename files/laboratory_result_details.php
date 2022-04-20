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

        <label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
            <select id='patientlist' name='patientlist'>
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
        <?php if (isset($_GET['Registration_ID'])) { ?>
         <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $Registration_ID; ?>" class='art-button-green'  target="_blank">PATIENT FILE</a>
        <?php } //else{ ?>
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

        <?php
        if (isset($_SESSION['userinfo'])) {
            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
                ?>
                <a href='./laboratoryresult.php?LaboratoryResultsThisPage=ThisPage' class='art-button-green'>
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
        <fieldset>
            <center style='background: #006400 !important;color: white;'>
                <b>DOCTORS WORKPAGE OUTPATIENT &nbsp;~~~&nbsp; PATIENT LABORATORY RESULT(S)</b>
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
        <hr>

        <div id="labResults" style="display: none">
            <div id="showLabResultsHere"></div>

        </div>


        <div id="labGeneral" style="display: none">
            <div id="showGeneral"></div>

        </div>
        <div id="historyResults1" style="display:none">

        </div> 
        <fieldset>
            <div style="oveflow-x:hidden;overflow-y:scroll;height:330px">
                <?php include 'testResults.php' ?>
            </div>
        </fieldset>    
        <hr>  


        <table width='100%'>
            <tr>

                <td>
                    <?php if (isset($_GET['Registration_ID'])) {
                        ?>
                        <a href='clinicalnotes.php?<?php
                        if ($Registration_ID != '') {
                            echo "Registration_ID=$Registration_ID&";
                        }
                        ?><?php
                        if (isset($_GET['Patient_Payment_ID'])) {
                            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
                        }
                        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
                        }
                        ?>SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
                            CLINICAL NOTES
                        </a>
                    <?php } else {
                        ?>
                        <input type='button' value='CLINICAL NOTES' class='art-button-green' onclick="alert('Choose Patient!');">
                    <?php } ?>
                    <input type='hidden' id='send_admition_form' name='send_admition_form'>
                    <?php if (isset($_GET['Registration_ID'])) { ?>
                        <label for='photo'>Display Patient Picture(Optional)</label> <input type='checkbox' name='photo' id='photo'/>
                    <?php }
                    ?>
                </td>
                <td>
                    <!--Lab Results-->
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
        </td>
        <!--<td>
           <a href='PatientRadiology_Served_Doctor.php' class='art-button-green'>RADIOLOGY RESULTS</a>
        </td>-->
    </tr>
</table>
<script  type="text/javascript">
    
     $(document).ready(function (){
        $('#labresult').DataTable({
            "bJQueryUI": true
        });
     });
</script>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<script>
                $(document).ready(function () {
                    $('.fancybox').fancybox();
                    
                });
                
                 function preview_lab_result(Product_Name,Payment_Item_Cache_List_ID){
                    window.open("preview_ntergrated_lab_result.php?Product_Name="+Product_Name+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID,"_blank");
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
<!--END HERE-->
<?php
include("./includes/footer.php");
?>