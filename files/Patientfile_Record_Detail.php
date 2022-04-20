<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
        
    } else {
        if (isset($_SESSION['userinfo']['Patient_Record_Works'])) {
            if ($_SESSION['userinfo']['Patient_Record_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get section for back buttons
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
$select_attachment = mysqli_query($conn, "SELECT Registration_ID FROM tbl_attachment WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_attachment)>0){
    echo "<a href='patient_other_attachment.php?Registration_ID=$Registration_ID&Fromrecordreview=Fromrecordreview' class='art-button-green'>OTHER ATTACHMENT</a>";
}
?>
<a href="patientfile_scroll.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>" class="art-button-green">PATIENT FILE SCROLL VIEW</a>
<input type="button" class="art-button-green" value="PREVIOUS PATIENT FILE" onclick="Preview_Previous_Patient_File(<?php echo $Registration_ID; ?>)">
<a href="surgeryrecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&SurgeryRecords=SurgeryRecordsThisPage" class="art-button-green">SURGERY RECORDS</a>
<a href="procedurerecords.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&ProcedureRecords=ProcedureRecordsThisPage" class="art-button-green">PROCEDURE RECORDS</a>
<input type="button" value="BACK" class="art-button-green" onclick="history.go(-1)"/>

<!-- <a href="all_patient_file_link_station.php?Registration_ID=<?=$Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a> -->

<?php
// if (isset($_GET['redirect']) && $_GET['redirect'] == 'true') {
//     if (isset($_SESSION['REDIRECT_TO_PROCEDURE'])) {
//         echo "<a href='" . $_SESSION['REDIRECT_TO_PROCEDURE'] . "'        class='art-button-green'>BACK</a>";
//     }

//     unset($_SESSION['REDIRECT_TO_PROCEDURE']);
// } else {
//     if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
//         echo "<a href='doctorspageoutpatientwork.php?Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' class='art-button-green'>BACK</a>";
//     } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
//         echo "<a href='laboratory_result_details.php?Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "' class='art-button-green'>BACK</a>";
//     } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
//         echo "<a href='RadiologyPatientTests_Doctor.php?Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&PatientType=&listtype=FromRec&Doctor=yes' class='art-button-green'>BACK</a>";
//     } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
//         echo "<a href='doctorsperformancepatientsummary.php?DoctorsPerformancePateintSummary=DoctorsPerformanceThisPage' class='art-button-green'>BACK</a>";
//     } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {

//         echo '<a href="doctorsperformancefilterdetails.php?Employee_ID=' . $_GET['Employee_ID'] . '&Date_From=' . $_GET['Date_From'] . '&Date_To=' . $_GET['Date_To'] . '&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage" class="art-button-green">BACK</a>';
//     } else {
        /*if(isset($_GET['fromPatientFile'])){
            if($_GET['fromPatientFile']=='true'){
              echo "<a href='Patientfile_Record.php?section=Patient&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
  
            }
            
        }elseif (isset ($_GET['Patient_Payment_ID']) && ($_GET['Patient_Payment_Item_List_ID'])) {
            if($_GET['position']=='out'){
             echo "<a href='doctorspageoutpatientwork.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=out' class='art-button-green'>BACK</a>";
       
            }elseif ($_GET['position']=='in') {
              echo "<a href='clinicalnotes.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in' class='art-button-green'>BACK</a>";
         
            }
            
        }elseif(isset ($_GET['Registration_ID']) && ($_GET['consultation_ID'])){
            if($_GET['position']=='out'){
                 echo "<a href='doctorspageinpatientwork.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&position=out' class='art-button-green'>BACK</a>";
            }else if($_GET['position']=='in'){
                  echo "<a href='inpatientclinicalnotes.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&item_ID=".$GET['item_ID']."&position=in' class='art-button-green'>BACK</a>";
  
            }
        
        }*/
//     }
// }
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
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
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
<style>
    table,tr,td{
        border:1px solid #ccc;	
        border-collapse:collapse !important;
    }
</style>

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
    $row = @mysqli_fetch_array($sql_Select_Current_Patient);
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

<div id="labResults" style="display: none">
    <div id="showLabResultsHere"></div>
</div>


<div id="labGeneral" style="display: none">
    <div id="showGeneral"></div>
</div>
<div id="historyResults1" style="display:none">

</div>

<div id="showdata" style="width:100%;overflow-x:hidden;display:none;overflow-y:scroll">
    <div id="my">
    </div>
</div>
<div id="showdataComm" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myComm">
    </div>
</div>
<div id="showdataResult" style="width:100%;overflow:hidden;display:none;background-color:white; border:none">
    <div id="myRs">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style='text-align:center'>
                    <select onchange="consultResult(this.value)" id="consType" style="padding:5px;margin:5px;font-size:18px;font-weight:100">
                        <option>SELECT CONSULTATION TYPE</option>
                        <option>Pharmacy</option>
                        <option>Laboratory</option>
                        <option>Radiology</option>
                        <option>Surgery</option>
                        <option>Procedure</option>
                        <option>Others</option>
                        <select>
                 </td>
                </tr>
                            <tr>
                                <td >
                                    <div align="center"  id="progressStatus"  style="display: none "><img src="images/ajax-loader_1.gif" style="border-color:white;"></div>

                                    <div id="myDiv" style="width:100%;text-align:center;overflow-x:hidden;height:400px;overflow-y:scroll">

                                    </div>

                                </td>
                            </tr>
                            </table>
                            </div>
                            </div>
                            <fieldset >                                
                                    <legend align="center" style='padding:10px; background-color:green; text-align:center'><h4><b> PATIENT FILE RECORDS </b> <h4>                               
                                    <span style='color:yellow;'><?php echo ucwords(strtolower($Patient_Name)) . "&nbsp;|&nbsp; ".$Registration_ID. "&nbsp;|&nbsp;" . $Gender . "&nbsp;|&nbsp; " . $Guarantor_Name . "&nbsp;|&nbsp;  (" . $age . ")"; ?></span>
                                
                                    </legend>

                                    
                            </fieldset>
                            <fieldset style='background: white; color:black'>
                                <div id="radPatTest" style="width:100%;height:400px;overflow-y:scroll;overflow-x:hidden">
                                    <?php include 'Patientfile_Record_Detail_Iframe.php'; ?>
                                </div>
                            </fieldset>
                            <!--END HERE-->
                            <script>
                                function consultResult(consultType, href, consultedDate, Registration_ID) {
                                    //alert(consultType+' '+href+' '+consultedDate+' '+Registration_ID);
                                    var datastring = href + '&consultedDate=' + consultedDate + '&consultType=' + consultType;

                                    $.ajax({
                                        type: 'GET',
                                        url: 'requests/PatientDetailsResults.php',
                                        data: datastring,
                                        beforeSend: function (xhr) {
                                            $("#progressStatus").show();
                                        },
                                        success: function (result) {
                                            $("#myDiv").html(result);
                                        }, complete: function () {
                                            $("#progressStatus").hide();
                                        }, error: function (err, msg, errorThrows) {
                                            alert(err);
                                        }
                                    });

                                }
                            </script>
                            <script >
                                function parentResult(href, PatientName, consultedDate, Registration_ID) {
                                    //alert(href+' '+PatientName+' '+consultedDate);
                                    $('#consType').attr("onchange", "consultResult(this.value,'" + href + "','" + consultedDate + "','" + Registration_ID + "')");
                                    $('#consType').val('');
                                    $("#myDiv").html('');
                                    $("#showdataResult").dialog("option", "title", "PATIENT RESULT ( " + PatientName + " ) | Date:" + consultedDate);
                                    $("#showdataResult").dialog("open");
                                }
                            </script>
                            <script>
                                $(document).ready(function () {
                                    $("#showdata").dialog({autoOpen: false, width: '90%', title: 'PATIENT RADIOLOGY IMAGING', modal: true, position: 'middle'});
                                    $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 500, title: 'PATIENT RESULT', modal: true, position: 'middle'});
                                    $("#showdataComm").dialog({autoOpen: false, width: '90%', height: 650, title: 'COMMENT AND DESCRIPTION', modal: true, position: 'center'});
                                    $('.fancybox').fancybox();

                                });
                            </script>
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
                                            $("#myComm").html(result);
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


                            <script type="text/javascript">
                                function Preview_Previous_Patient_File(Registration_ID) {
                                    var winClose = popupwindow('previouspatientfile.php?Registration_ID=' + Registration_ID, 'PREVIOUS PATIENT FILE', 1200, 600);
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

                            <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
                            <link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
                            <script src="js/jquery-1.8.0.min.js"></script>
                            <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
                            <script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>

                            <script>
                                $(document).ready(function () {
                                    $('.fancybox').fancybox();
                                });
                            </script>
                            <?php
                            include("./includes/footer.php");
                            ?>