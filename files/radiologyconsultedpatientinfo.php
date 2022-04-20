<?php
include("./includes/header.php");
include("./includes/connection.php");

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

$issPorcessed = FALSE;
$queryString = '';
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
$Sub_Department_ID = 0;

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}

if (isset($_POST['process_procedure'])) {
    // echo 'Sent';exit;
    $paymentItermCache = $_POST['paymentItermCache'];
    $queryString = $_POST['queryString'];
    $Registration_ID = $_POST['Registration_ID'];
    $Item_ID = 0;

    $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));


    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

    $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

    foreach ($paymentItermCache as $value) {
        $status = $_POST['status_' . $value];
        $status_pro = $_POST['status_pro_' . $value];
        $billing_type = $_POST['billing_type_' . $value];
        $transaction_type = $_POST['transaction_type_' . $value];
        $classification = $_POST['classification_' . $value];
        $sonographer = $_POST['sonographer_' . $value];
        $radiologist = $_POST['radiologist_' . $value];
        $reporteur = $_POST['reporteur_' . $value];

            if ($status !== 'Select progress') {
                $checkPAymentStatus = "SELECT Item_ID,Registration_ID FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                $itemQuery = mysqli_query($conn,$checkPAymentStatus);
                $rows = mysqli_fetch_array($itemQuery);
                $Item_ID = $rows['Item_ID'];
                mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='" . $_POST['remarks_' . $value] . "',ServedDateTime=NOW(),ServedBy='" . $_SESSION['userinfo']['Employee_ID'] . "' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
                if (!radiologyUpdate($Item_ID, $status, $value, $Registration_ID, $classification, $sonographer, $radiologist, $reporteur)) {
                    die(mysqli_error($conn));
                }
            }
    }


    echo "<script type='text/javascript'>
                                alert('INFORMATION SAVED SUCCESSFULLY');
                                window.location='radiologyconsultedpatientinfo.php?" . $queryString . "'
                            </script>";
}

function radiologyUpdate($Item_ID, $Status, $PPILI, $RegistrationID, $classification, $sonographer, $radiologist, $reporteur) {
    $response = false;

    if (empty($sonographer)) {
        $sonographer = 'NULL';
    }
    if (empty($radiologist)) {
        $radiologist = 'NULL';
    }
    if (empty($reporteur)) {
        $reporteur = 'NULL';
    }

    if ($Status == 'served') {
        $Status = 'done';
    }

    $select_extist = "
		SELECT * 
			FROM tbl_radiology_patient_tests
			WHERE
				Registration_ID = '$RegistrationID' AND
				Item_ID = '$Item_ID' AND 
				Patient_Payment_Item_List_ID = '$PPILI'
				";
    $select_extist_qry = mysqli_query($conn,$select_extist) or die(mysqli_error($conn));

    $count = mysqli_num_rows($select_extist_qry);

    if ($count > 0) {
        while ($existing = mysqli_fetch_assoc($select_extist_qry)) {
            $RowID = $existing['Radiology_Test_ID'];
        }
        $update_status = "UPDATE tbl_radiology_patient_tests SET
                             Status = '$Status',
                             Classification='$classification',
                             Sonographer_ID=$sonographer,
                             Radiologist_ID=$radiologist,
                             Reporteur=$reporteur,
                             Date_Time=NOW()
                          WHERE Radiology_Test_ID = '$RowID'";
        $update_status_qry = mysqli_query($conn,$update_status) or die(mysqli_error($conn));
        if ($update_status_qry) {
            $response = true;
        } else {
            $response = false;
        }
    } else {
        $insert_status = "
		INSERT INTO 
			tbl_radiology_patient_tests(Registration_ID, Item_ID, Patient_Payment_Item_List_ID, Status,Classification,Sonographer_ID,Radiologist_ID,Reporteur,Date_Time) 
				VALUES('$RegistrationID', '$Item_ID', '$PPILI', '$Status','$classification',$sonographer,$radiologist,$reporteur,NOW())";
        $insert_status_qry = mysqli_query($conn,$insert_status) or die(mysqli_error($conn));
        if ($insert_status_qry) {
            $response = true;
        } else {
            $response = false;
        }
    }

    return $response;
}

$Registration_ID = $_GET['Registration_id'];
$SubCategory = $_GET['SubCategory'];
$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];
$Sponsor = strtolower($_GET['Sponsor']);


if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
echo "<a href='consultedpatientradiologylist.php?Date_From=$Date_From&Date_To=$Date_To&Sponsor=$Sponsor&SubCategory=$SubCategory' class='art-button-green'>
    BACK
</a>";

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>

<script type='text/javascript'>
    function di() {
        alert("All");
        $("#d").attr("hidden", "false").dialog();
    }
    function b(val) {
        alert(val);
    }
</script>

<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php
//    select patient information
if (isset($Registration_ID)) {
    // $Payment_Cache_ID = $_GET['Payment_Cache_ID']; 

    $selectPatQry = "SELECT * from tbl_patient_payments pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Registration_ID = '$Registration_ID'";

    $select_Patient = mysqli_query($conn,$selectPatQry) or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    // echo $no;exit;

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
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
            $Billing_Type = $row['Billing_Type'];
            $Consultant = $row['Employee_Name'];
            $Folio_Number = '';
            if (isset($_GET['typeconsultant']) && $_GET['typeconsultant'] == 'OTHERS_CONSULT') {
                $Folio_Number = '';
            } else {
                $Folio_Number = $row['Folio_Number'];
            }
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
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
        $Consultant = '';
        $Folio_Number = '';
        $Billing_Type = '';
    }
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
    $Consultant = '';
    $Folio_Number = '';
    $Billing_Type = '';
}


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['Payment_Date_And_Time'])) {
    $Payment_Date_And_Time = $_GET['Payment_Date_And_Time'];
} else {
    $Payment_Date_And_Time = '';
}
?>
<!-- end of getting receipt number and receipt date-->
<br/><br/>

<fieldset style="background-color:#EEEEEE">  
    <legend  align="right"><b>RADIOLOGY PROCESSING</b></legend>
    <center>
        <table width='100%' style="background-color:white"> 
            <tr> 
                <td>
                    <table width=100%>
                        <tr>
                            <td width='10%' style="text-align:right;">Patient Name</td>
                            <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                            <td width='12%' style="text-align:right;">Card Expire Date</td>
                            <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                            <td width='11%' style="text-align:right;">Gender</td>
                            <td width='12%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                            <td style="text-align:right;">Folio Number</td>
                            <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>

                        </tr> 
                        <tr>
                            <td style="text-align:right;">Billing Type</td> 
                            <td>
                                <select name='Billing_Type' id='Billing_Type' style="width:100%">
                                    <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                </select>
                            </td>
                            <td style="text-align:right;" >Claim Form Number</td>
                            <!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
                            <?php
                            $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
                            $no = mysqli_num_rows($select_claim_status);
                            if ($no > 0) {
                                while ($row = mysqli_fetch_array($select_claim_status)) {
                                    $Claim_Number_Status = $row['Claim_Number_Status'];
                                }
                            } else {
                                $Claim_Number_Status = '';
                            }
                            ?>
                            <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'  placeholder='Claim Form Number'></td>
                            <?php } else { ?>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
                            <?php } ?>
                            <td style="text-align:right;">Occupation</td>
                            <td>
                                <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                            </td>
                            <td style="text-align:right;">&nbsp; </td>
                            <td>&nbsp;</td>


                        </tr>
                        <tr>
                            <td style="text-align:right;">Patient Age</td>
                            <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                            <td style="text-align:right;">Registered Date</td>
                            <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                            <td style="text-align:right;">Supervised By</td>

                            <?php
                            if (isset($_SESSION['Radiology_Supervisor']['Employee_Name'])) {
                                if (isset($_SESSION['Radiology_Supervisor']['Employee_Name'])) {
                                    if ($_SESSION['Radiology_Supervisor']['Employee_Name'] != '') {
                                        $Supervisor = $_SESSION['Radiology_Supervisor']['Employee_Name'];
                                    } else {
                                        $Supervisor = "Unknown Supervisor";
                                    }
                                } else {
                                    $Supervisor = "Unknown Supervisor";
                                }
                            } else {
                                $Supervisor = "Unknown Supervisor";
                            }
                            ?> 
                            <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>

                        </tr>
                        <tr> 
                            <td style="text-align:right;">Patient Direction</td>
                            <td>
                                <select id='direction' name='direction' style="width:100%" > 
                                    <option selected='selected'>Others</option>
                                </select>
                            </td>
                            <td style="text-align:right;">Sponsor Name</td>
                            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            <td style="text-align:right;">Phone Number</td>
                            <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>

                        </tr>
                        <tr>
                            <td style="text-align:right;">Consultant</td>
                            <td>
                                <select name='Consultant' id='Consultant' style="width:100%">
                                    <option selected='selected'><?php echo $Consultant; ?></option>
                                </select>
                            </td>
                            <td style="text-align:right;">Registration Number</td>
                            <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                            <td style="text-align:right;">Member Number</td>
                            <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 

                        </tr> 
                    </table>
                </td> 
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style="background-color:#EEEEEE">   
    <center>
        <table width=100%>
            <tr>

                <td style='text-align: right;'>
                    <?php
                    $typeconsultant = '';
                    $Transaction_Status_Title = '';
                    if (isset($_GET['section']) && ($_GET['section'] == 'doctor' || $_GET['section'] == 'Doctorlist')) {
                        
                    } else if (isset($_GET['sectionpatnt']) && $_GET['sectionpatnt'] == 'doctor_with_patnt') {
                        
                    } else {
                        ?> 
                    <a href="prview_rad_test.php?source=consult&<?php echo $_SERVER['QUERY_STRING']?>&billtype=<?php echo $Billing_Type?>"  id="preview" target="_blank" class="art-button-green">PREVIEW</a>
                    
                        <input name="patient_file" id="patient_file" value="PATIENT FILE" onclick="Show_Patient_File(<?php echo $Registration_ID; ?>)" class="art-button-green" type="button">

                        <?php
                    }
                    ?>



                </td>
            </tr> 
        </table>
    </center>
</fieldset>


<fieldset >  

    <center>
        <center><b>LIST OF ITEMS </b></center>
        <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' name='myForm' id='myForm' enctype="multipart/form-data" width="50%">
            <table  style="background-color:white;width: 100%">
                <tr>
                    <td>

                        <div id="patientItemsList" style='height:200px;overflow-x:hidden; overflow-y:scroll;  '>
                           <?php include "radiologyconsulted_iframe.php"; ?>
                        </div>

                    </td>
                </tr>

            </table>
            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_id'] ?>">
            <input type="hidden" name="process_procedure" value="true"/>
            <input type="hidden" name="queryString" value="<?php echo $_SERVER['QUERY_STRING'] ?>"/>
        </form>
    </center>
</fieldset>

<div id="showdata" style="height:450px;margin:auto;  overflow-x:hidden;overflow-y:scroll;display:none ">
    <div id="my">
    </div>
</div>
<div id="showdataComm" style="width:100%;height:450px;margin:auto; overflow-x:hidden;overflow-y:scroll; display:none;">
    <div id="container" style="display:none">
        <div id="default">
            <h1>#{title}</h1>
            <p>#{text}</p>
        </div>
    </div>
    <div id="myComm">
    </div>
</div>

<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
    .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
    .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    #displaySelectedTests,#items_to_choose{
        overflow-y:scroll;
        overflow-x:hidden; 
    }
</style>

<script>
    function closeDialog() {
        if (confirm('Are you sure you want to close?')) {
            $("#showdataComm").dialog('close');
        }
    }
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
        // $("#showdata").dialog("option","title","PATIENT RADIOLOGY IMAGING ( "+itemName+" )");
        $.ajax({
            type: 'GET',
            url: 'requests/radiologyviewimage.php',
            data: datastring,
            success: function (result) {
                //alert(result);
                var data = result.split('<1$$##92>');
                if (data[0] != '') {
                    alert(data[0]);
                }
                $('#my').html(data[1]);
                // $("#showdata").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });

        $('#showdata').dialog({
            modal: true,
            width: '90%',
            height: 450,
            resizable: true,
            draggable: true,
        }).dialog("widget")
                .next(".ui-widget-overlay")
                .css({
                    background: "transparent",
                    opacity: 1
                });

        $("#showdata").dialog("option", "title", "PATIENT RADIOLOGY IMAGING ( " + itemName + " )");


    }
</script>

<script >
    function commentsAndDescription(href, itemName) {
        var datastring = href;

        //alert(datastring);
        $.ajax({
            type: 'GET',
            url: 'requests/RadiologyPatientTestsComments.php',
            data: datastring,
            success: function (result) {
                //alert(result);

                $('#myComm').html(result);

            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });

        $('#showdataComm').dialog({
            modal: true,
            width: '90%',
            height: 620,
            resizable: true,
            draggable: true,
        }).dialog("widget")
                .next(".ui-widget-overlay")
                .css({
                    background: "transparent",
                    opacity: 1
                });

        $("#showdataComm").dialog("option", "title", "COMMENT AND DESCRIPTION ( " + itemName + " )");

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

<script type='text/javascript'>

    $(document).ready(function () {
        if ($('.notpaid').length) {
            //alert('aaa');
            $("#process_patient").remove();
        }
        else if ($('.Procedureprogress').length == 0) {
            $("#process_patient,#preview").remove();
        }
         //autocomplete search box;

        $('select').select2();
        
         $container = $("#container").notify();
       

        //$("#showdata").dialog({ autoOpen: false, width:'90%',height:'450', title:'PATIENT RADIOLOGY IMAGING',modal: true,position:'middle'});
        //$("#showdataComm").dialog({ autoOpen: false, width:'90%',height:'450', title:'COMMENT AND DESCRIPTION',modal: true,position:'middle'});


    });
</script>
<script>
    function checkStatus() {
        var is_error = false;
        if ($('.notpaid').length) {
            alert("What are you trying to do? The patient hasn't paid and you are processing him! Don't do that.");
            exit;
        }
		
		  //check if atleaset one item is selected
        var chk = 0;
        $(".Procedureprogress").each(function () {
            var status = $(this).val();

            if (status == 'Select progress') {
                chk++;
            }
        });

        if (chk == $('.Procedureprogress').length) {
            alert("Please select progress status");
            $(".Procedureprogress").css('border', '1px solid red');
            exit;
        }

        $(".Procedureprogress").each(function () {
            var status = $(this).val();
            var id = $(this).attr('id');

             if (status == 'served') {

                var classification = $("#" + id + 'classification').val();
                var sonographer = $("#" + id + 'sonographer').val();
                var radiologist = $("#" + id + 'radiologist').val();
                var reporteur = $("#" + id + 'reporteur').val();

                if (classification == '') {
                    $("#" + id + 'classification').css('border', '1px solid red');
                    is_error = true;

                }
                if (sonographer == '') {
                    $("#" + id + 'sonographer').css('border', '1px solid red');
                    is_error = true;
                }
                if (radiologist == '') {
                    $("#" + id + 'radiologist').css('border', '1px solid red');
                    is_error = true;
                }
                // if (reporteur == '') {
                //     $("#" + id + 'reporteur').css('border', '1px solid red');
                //     is_error = true;
                // }


            }
        });

        if (is_error) {
            alert("The reded field are required.");
            exit;
        }

        if ($('.Procedureprogress').length) {
            if (confirm('Are you sure you want to save informations?')) {
                document.getElementById('myForm').submit();
            }
        } else {
            alert("It seems the patient have already been processed!");
            exit;
        }
    }
</script>
<script>
    function Show_Patient_File(Registration_ID) {
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=' + Registration_ID + '&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
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
    function CheckTheParam() {
        var param = document.getElementById('Parameter').value;
        var comments_id = "i" + param;
        var row_id = "row" + param;
        
        //alert(row_id);exit;
        var comm = document.getElementById('Comments').value;
        if (param == '') {
            alert('Please select parameter first.');
            comm[0].disabled = true;
        } else {
            comm[0].disabled = false;
        }
        
    }


    function SendSMS(department, receiver) {
        //alert(department + receiver);
        //exit;
        if (window.XMLHttpRequest) {
            sms = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            sms = new ActiveXObject('Micrsoft.XMLHTTP');
            sms.overrideMimeType('text/xml');
        }
        sms.onreadystatechange = AJAXSMS;
        sms.open('GET', 'SendSMS.php?Department=' + department + '&Receiver=' + receiver, true);
        sms.send();

        function AJAXSMS() {
            var smsrespond = sms.responseText;
            document.getElementById('SMSRespond').innerHTML = smsrespond;
        }
    }

</script>

<script>
    function CheckComments(ParamID, instance,PPILI,Item_ID,Registration_ID) {
       document.getElementById('SMSRespond').innerHTML = "";
       document.getElementById('radStatus').innerHTML = "";
        var parameterName = $(instance).find('option:selected').text();
        // var rowData='<tr><td width="22%" style="text-align:right;"> <strong>'+parameterName+'</strong> </td> <td> <textarea style="padding-left:10px;" id="i'+ParamID+'" readonly="readonly"> </textarea></td></tr>';

        // if($('#i'+ParamID).length==0 && parameterName !='Select Parameter'){
        // alert('found');
        // $('#tableComment').append(rowData);
        // }
        var Comments = document.getElementById('Comments').innerHTML;
        var PPILI = PPILI;
        var Item_ID = Item_ID;
        var Registration_ID = Registration_ID;
        
        if (window.XMLHttpRequest) {
            cc = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            cc = new ActiveXObject('Microsoft.XMLHTTP');
            cc.overrideMimeType('text/xml');
        }
        cc.onreadystatechange = AJAXPCC; //specify name of function that will handle server response....
        cc.open('GET', 'RadiologyPatientTestsComments_Check.php?PI=' + ParamID + '&C=' + Comments + '&PPILI=' + PPILI + '&RI=' + Registration_ID + '&II=' + Item_ID, true);
        cc.send();

        function AJAXPCC() {
            var oldcomment = cc.responseText;
            //document.getElementById('Comments').value = '';
            //document.getElementById('Commentsx').innerHTML = oldcomment;	
            document.getElementById('Comments').value = oldcomment;
        }
    }
</script>
<script>
    function SaveComments(PPILI, Item_ID, Registration_ID,Employee_ID) {
        //prepare data to add;
        var datastring;
        var chk = 1;
        $('.parametersValues').each(function () {
            var paraID = $(this).attr('id');
            var paraComm = $(this).val();
            paraComm = paraComm.replace(new RegExp('\r?\n', 'g'), '<br/>');

            if (chk == 1) {
                datastring = paraID + 'uiytregwhs' + paraComm;
            } else {
                datastring += '$$$$tenganisha###' + paraID + 'uiytregwhs' + paraComm;
            }
            chk++;
        });


        //alert(datastring);exit;
        var PPILI = PPILI;
        var Item_ID = Item_ID;
        var Registration_ID = Registration_ID;

        $.ajax({
            type: 'POST',
            url: 'RadiologyPatientTestsComments_Save.php',
            data: 'datastring=' + datastring + '&PPILI=' + PPILI + '&RI=' + Registration_ID + '&II=' + Item_ID + '&Employee_ID=' + Employee_ID,
            success: function (result) {
                if (result == '1') {
                    // setTimeout(function(){
                    create("default", {title: 'Success', text: 'Parameters saved successifully'});
                    refreshComment('II=' + Item_ID + '&PPILI=' + PPILI + '&RI=' + Registration_ID);
                    //},2000);
                } else {
                    alert(result);
                }
            }, error: function (x, y, z) {
                alert(z);
            }
        });
    }
</script>
<script >
    function refreshComment(href) {
        var datastring = href;

        //alert(datastring);
        $.ajax({
            type: 'GET',
            url: 'requests/RadiologyPatientTestsComments.php',
            data: datastring,
            success: function (result) {
                //alert(result);

                $('#myComm').html(result);

            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>
<script>
    function create(template, vars, opts) {
        return $container.notify("create", template, vars, opts);
    }
</script>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/jquery.notify.min.js"></script> 
<script src="js/jquery.form.js"></script>


<!--End datetimepicker-->
<?php
include("./includes/footer.php");
?>