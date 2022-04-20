<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Patient_Transfer']) && $_SESSION['userinfo']['Patient_Transfer'] == 'yes') {
//	    //if(){
//                die('Reception Page');
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	   // }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    //if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {

    if (isset($_GET['section']) && $_GET['section'] == 'reception') {
//            echo "<a href='doctorspagetransfered.php?section=reception' class='art-button-green'>
//               TRANSFERED PATIENTS
//             </a>";
        echo "<a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
				 BACK
			  </a>";
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DocInpatient') {
        echo "<a href='doctorspagetransfered.php?Section=DocInpatient' class='art-button-green'>
               TRANSFERED PATIENTS
             </a>";
        echo "<a href='doctorsinpatientworkspage.php' class='art-button-green'>BACK</a>";
    } elseif (isset($_GET['section']) && $_GET['section'] == 'revenuecenter') {
//              echo "<a href='doctorspagetransfered.php?section=revenuecenter' class='art-button-green'>
//               TRANSFERED PATIENTS
//             </a>";
        echo "<a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='doctorspagetransfered.php' class='art-button-green'>
               TRANSFERED PATIENTS
             </a>";
        ?>
        <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
    // }
}
?>




<script >
    function getpatients(transfers_IDs, Patient_Name, Patient_Number, Phone_Number) {
        var transfer_type = document.getElementById('transfer_type').value;
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;

        if (transfers_IDs == '') {
            transfers_IDs = document.getElementById('transfers_IDs').value;
            if (Date_From == '' || Date_To == '') {
                alert('Please enter both dates to filter');
                exit;
            }
        }

        if (Patient_Name == '' && Patient_Name != null) {
            Patient_Name = '';
        }
        if (Patient_Number == '' && Patient_Number != null) {
            Patient_Number = '';
        }
        if (Phone_Number == '' && Phone_Number != null) {
            Phone_Number = '';
        }
        //alert(Patient_Name+' '+Patient_Number);

        $("#progressStatus").show();

        if (transfers_IDs == 'Select a doctor') {
            alert('Please choose a doctor');
            $("#progressStatus").hide();
            exit;
        } else if (transfers_IDs == 'Select clinic') {
            alert('Please choose clinic');
            $("#progressStatus").hide();
            exit;
        }

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        var transfers_IDs = document.getElementById('transfers_IDs').value;
        //alert(Employee_ID);
        //document.location='getpatients.php?Employee_ID='+Employee_ID;
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'getpatients.php?transfers_IDs=' + transfers_IDs + '&Patient_Name=' + Patient_Name + '&Patient_Number=' + Patient_Number + '&Phone_Number=' + Phone_Number + '&transfer_type=' + transfer_type + '&Date_From=' + Date_From + '&Date_To=' + Date_To, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4 && mm.status == 200) {
            $("#progressStatus").hide();
            document.getElementById('Selected_patient').innerHTML = data;
            getEmployeeNotIn();
        }

    }

</script>
<script>
    function  getEmployeeNotIn() {
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        var transfer_type = document.getElementById('transfer_type').value;
        $.ajax({
            type: 'GET',
            url: 'getpatients.php',
            data: 'transfers_IDs=' + transfers_IDs + '&getTransferee=true' + '&transfer_type=' + transfer_type,
            success: function (result) {
                document.getElementById('transfers_TO_IDs').innerHTML = result;
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>
<script type="text/javascript" language="javascript">
    function saveUpdate(box, Registration_ID) {

        var Employee_ID = document.getElementById('user_' + Registration_ID).value;
        //	var Clinic = document.getElementById('Clinic_'+Registration_ID).value;
        var Reason = document.getElementById('reason_' + Registration_ID).value;
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        var didthis = document.getElementById('didthis').value;
        var Patient_Direction = '';
        if (Employee_ID != '') {
            Patient_Direction = 'doctor';
        } else {
            Patient_Direction = 'clinic';
        }
        /* if(Employee_ID !='' && transfers_IDs != '')
         {
         Employee_ID.setAttribute("disabled","disabled");
         Employee_ID.setAttribute("disabled","disabled");
         } */
        if (window.XMLHttpRequest) {
            mm2 = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm2 = new ActiveXObject('Microsoft.XMLHTTP');
            mm2.overrideMimeType('text/xml');
        }
//document.location='GetPatientUpdate.php?Registration_ID='+Registration_ID+'&Employee_ID='+Employee_ID+'&Reason='+Reason+'&didthis='+didthis+'&transfers_IDs='+transfers_IDs;
        mm2.onreadystatechange = function () {
            if (mm2.readyState == 4) {
                box.setAttribute("disabled", "disabled");
                var data2 = mm2.responseText;
                alert(data2);
            }
        }
        mm2.open('GET', 'GetPatientUpdate.php?Registration_ID=' + Registration_ID + '&Employee_ID='
                + Employee_ID + '&Reason=' + Reason + '&didthis=' + didthis + '&transfers_IDs=' + transfers_IDs + '&Patient_Direction='
                + Patient_Direction, true);
        mm2.send();
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        var Patient_Name = document.getElementById("Search_Patient").value;
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        var Patient_Number = '';
        var Phone_Number = '';
        //alert(Patient_Name);
       // if (Patient_Name != '' && Patient_Name != null) {
            getpatients(transfers_IDs, Patient_Name, Patient_Number, Phone_Number);
        //}
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number) {
        var Patient_Number = document.getElementById("Patient_Number").value;
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        Patient_Name = '';
        Phone_Number = '';
        if (Patient_Number != '' && Patient_Number != null) {
            getpatients(transfers_IDs, Patient_Name, Patient_Number, Phone_Number);
        }
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Phone_Number(Phone_Number) {

        var Phone_Number = document.getElementById("Phone_Number").value;
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        Patient_Name = '';
        Patient_Number = '';
        if (Phone_Number != '' && Phone_Number != null) {
            getpatients(transfers_IDs, Patient_Name, Patient_Number, Phone_Number);
        }
    }
</script>

<br><br>
<!--  transfer form-->
<style>
    table, tr,td{
        border:none !important;
    }
</style>
<center>
    <form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
        <fieldset style="width:90%;margin-top:10px;background-color:white;height:500px;">
            <legend align="center" style="background-color:#037DB0;padding:10px; color:white;width:30%;font-size:18px;"><b>TRANSFER WORKS</b>
            </legend>
            <br/>

            <div align="center" style="display:none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            <br/>
            <table  class='' width='100%' border='0'>
                <tr>
                    <td width='15%' style="text-align:right;font-size:14px;padding-top:10px"><b>TRANSFER TYPE</b></td>

                    <td width='20%'  >
                        <select name="transfer_type" id="transfer_type" onchange="getPatientType(this.value)" style='width:80%;padding:5px;font-size:15px;'>
                            <option value="Doctor_To_Doctor">From Doctor To Doctor</option>
                            <option value="Doctor_To_Clinic">From Doctor To Clinic</option>
                            <?php
                            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
                                ?>
                                <option value="Clinic_To_Clinic">From Clinic To Clinic</option>
                                <option value="Clinic_To_Doctor">From Clinic To Doctor</option>
                                <?php
                            }
                            ?>
                        </select>

                    </td>
                    <td width='15%' style="text-align:right;font-size:14px;padding-top:10px"><b><span id="transferTypeTitleFrom">FROM DOCTOR</span></b></td>

                    <td width='20%'  >
                        <select name="transfers_IDs" id="transfers_IDs" onchange="getpatients(this.value, '', '', '')" style='width:80%;padding:5px;font-size:15px;'>

                            <?php
                            $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
                            $Employee_IDS = $_SESSION['userinfo']['Employee_ID'];

                            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
                                echo '<option  value="' . $Employee_IDS . '">
								' . $Employee_Name . '
							</option>';
                            } else {
                                echo '<option selected="selected" value="Select a doctor">Select a doctor</option>';

                                $consult = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' ");
                                while ($row = mysqli_fetch_array($consult)) {
                                    $Employee_IDS = $row['Employee_ID'];
                                    $Employee_Name = $row['Employee_Name'];
                                    ?>
                                    <option  value="<?php echo $Employee_IDS; ?>">
                                        <?php echo $Employee_Name; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                    </td>
                    <td width='15%' style="text-align:right;font-size:14px;padding-top:10px"><b><span id="transferTypeTitleTo">TO DOCTOR</span></b></td>

                    <td width='20%' >
                        <select name="transfers_TO_IDs" id="transfers_TO_IDs" style='width:80%;padding:5px;font-size:15px;'>
                            <?php
                            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
                                echo "<option selected='selected' >Select a doctor</option>";

                                $consults = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' AND Employee_ID != '$Employee_IDS'  AND  Account_Status='active'");
                                while ($row_ = mysqli_fetch_array($consults)) {
                                    $Employee_ID = $row_['Employee_ID'];
                                    $Employee_Name = $row_['Employee_Name'];
                                    echo "<option value='" . $Employee_ID . "'>" . $Employee_Name . "</option>";
                                }
                            }
                            ?>
                        </select>

                    </td>
                    <td >
                        <button type="button" class="art-button-green" onclick="transferPatient()">Transfer</button>

                    </td>
                </tr>
                <tr>
                    <td style="" colspan='7'>&nbsp;</td>
                </tr>	
                <tr style='margin-top:20px;'>
                    <td width='100%' colspan='7' style='text-align: center;'>
                        <input type="text" autocomplete="off" style='text-align: center;width:12.5%;display:inline' id="Date_From" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:12.5%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                        <input type="button" value="Filter" class="art-button-green" onclick="getpatients('', '', '', '')">
                        <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;width:21%;display:inline' oninput='searchPatient(this.value)'  placeholder='~~~~ Search Patient Name  ~~~~'/>

                        <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;width:21%;display:inline' oninput='Search_Patient_Using_Number(this.value)'  placeholder='~~~~  Search Patient Number  ~~~~'/>

                        <input type='text' name='Phone_Number' id='Phone_Number' style='text-align:center;width:21%;display:inline' oninput='Search_Patient_Phone_Number(this.value)' placeholder='~~~~  Search Phone Number  ~~~~'/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;" colspan='7'>
                        <div id="Selected_patient" style="width:100%;overflow-x:hidden;overflow-y:scroll;height:300px;" >
                            <?php
                            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
                                //DIE('jujjj');
                                $temp = 1;
                                echo '<br>';

                                // $Select_patients = mysqli_query($conn,"select * from  
                                // tbl_patient_payments c,tbl_patient_registration pr,tbl_patient_payment_item_list ppl
                                // WHERE 
                                // ppl.Consultant_ID = '$Employee_ID_FROM' AND 
                                // c.Registration_ID=pr.Registration_ID  AND 
                                // ppl.Process_Status <> 'signedoff' AND Patient_Direction IN ('Direct To Clinic','Direct To Doctor Via Nurse Station','Direct To Doctor') AND Billing_Type='Outpatient Credit'
                                // AND c.Patient_Payment_ID=ppl.Patient_Payment_ID $search
                                // GROUP BY pr.Registration_ID ORDER BY pr.Registration_ID ASC ") or die(mysqli_error($conn));
                                $filter = ' AND DATE(pl.Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) ';

                                $qr = "
	   select pr.Registration_ID,pr.Patient_Name,Check_In_ID,Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,'0' AS consultationID from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp
		      where sp.sponsor_id = pr.sponsor_id and
		      pp.Registration_ID = pr.Registration_ID and
		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and pl.Process_Status <> 'signedoff' and
                      pl.Patient_Direction = 'Direct To Doctor' and pl.Consultant_ID = " . $Employee_IDS . "  and
                      pp.Transaction_status != 'cancelled' AND
		      pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " $filter GROUP BY pp.Registration_ID order by pl.Transaction_Date_And_Time
	
		  "
                                ;

                                $Select_patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
                                //  $result = mysqli_query($conn,$Select_patients);



                                $no = mysqli_num_rows($Select_patients);
                                // echo ($qr);exit; 
                                if ($no > 0) {

                                    echo '<center><table width =90% border="1px">';
                                    echo '<tr>	<td width ="3%" style="text-align:center;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td width ="10%"><b>PATIENT NO</b></td>
				<td><b>TRANS DATE</b></td>
				
				<td><b>Reason</b></td>
				<td style="text-align:right;">
				  <input type="checkbox" name="transferAll"  id="transferAll" style="margin-right:10px;" onclick="checktransferAll(this)"/><label for="transferAll"><b>All</b></label></td>
		</tr><tr><td colspan="6"><hr/></td></tr>';

                                    while ($row = mysqli_fetch_array($Select_patients)) {

                                        //Get check-in ID
                                        $Registration_ID = $row['Registration_ID'];
                                        $select_checkin = "SELECT Check_In_ID,Type_Of_Check_In FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' AND Check_In_ID='" . $row['Check_In_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
                                        //echo $select_checkin;exit;
                                        $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
                                        //echo $row['Check_In_ID'].'kl<br/>';
                                        $date = date('Y-m-d', strtotime($row['Transaction_Date_And_Time']));
                                        if (mysqli_num_rows($select_checkin_qry) > 0) {
                                            echo "<tr><td style='text-align:center;'>" . $temp . "</td><td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";

                                            echo "<td style='text-align:center;'>" . $row['Registration_ID'] . "</td>";

                                            $temp++;
                                            ?>
                                            <td>
                                                <?php echo $date; ?>
                                            </td>
                                            <td>
                                             <!--<input type='text' id='reason_<?php echo $row['Registration_ID']; ?>'>-->
                                                <textarea style='width:100%;height:15px '  id='reason_<?php echo $row['Registration_ID']; ?>' name='<?php echo $row['Registration_ID']; ?>'></textarea>
                                            </td>
                                            <td style='text-align:center;'>
                                                <input type="checkbox" ppil="<?php echo $row['Patient_Payment_Item_List_ID']; ?>" ckid="<?php echo $row['Check_In_ID']; ?>" class="tansfersPatient patient_id_<?php echo $row['Registration_ID']; ?>" consID="<?php echo $row['consultationID']; ?>" id="<?php echo $row['Registration_ID']; ?>">
                                            </td>
                                            <?php
                                        }
                                    }
                                } else {

                                    echo "<b style='color:red;font-size:20px;color:#c0c0c0'>No patient(s) available</b>";
                                }

                                echo '</tr></table></center>';
                            }
                            ?>
                        </div>

                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
    <!--  End ofform-->
    <input type="hidden" id="didthis"  value="<?php echo $_SESSION['userinfo']['Employee_ID']; ?>" >
</center>
<script src="js\jquery.js"></script>
<script>
                            function checktransferAll(instance) {
                                //alert('cicked');
                                if ($(instance).is(":checked")) {
                                    //alert('Checked');
                                    $(".tansfersPatient").each(function () {
                                        // $(this).attr('checked',true);
                                        this.checked = true;
                                    });
                                } else {
                                    $(this).attr('checked', false);
                                    $(".tansfersPatient").each(function () {
                                        this.checked = false;
                                    });
                                }
                            }
</script>
<script>
    function transferPatient() {
        var datastring = '';
        var dataInfo = '';
        var count = 0;
        var i = 1;
        var transfers_TO_IDs = document.getElementById('transfers_TO_IDs').value;
        var transfers_IDs = document.getElementById('transfers_IDs').value;
        var transfer_type = document.getElementById('transfer_type').value;
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;

//         alert(transfer_type);
//            exit;

        if (transfers_TO_IDs == 'Select a doctor') {
            alert('Please select doctor to transfer patient');
            $("#progressStatus").hide();
            exit;
        } else if (transfers_TO_IDs == 'Select clinic') {
            alert('Please choose clinic');
            $("#progressStatus").hide();
            exit;
        }
        $(".tansfersPatient").each(function () {
            if ($(this).is(':checked')) {
                var ppil = $(this).attr('ppil');
                var reg_id = $(this).attr('id');
                var consID = $(this).attr('consID');
                var reason = $('#reason_' + reg_id).val();
                var ckid = $(this).attr('ckid');
                if (i == 1) {
                    dataInfo = ppil + '~~' + reg_id + '~~' + reason + '~~' + ckid + '~~' + consID;
                } else {
                    dataInfo += '^$*^%$' + ppil + '~~' + reg_id + '~~' + reason + '~~' + ckid + '~~' + consID;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alert('Select patient to transfer');
            exit;
        }

        datastring = 'user=' + transfers_TO_IDs + 'us' + transfers_IDs + '&dataInfo=' + dataInfo + '&transfer_type=' + transfer_type+ '&Date_From=' + Date_From + '&Date_To=' + Date_To;
        // alert(datastring);exit;
        $.ajax({
            type: 'POST',
            url: 'requests/transferPatient.php',
            data: datastring,
            cache: false,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {

                var data = html.split('$$$$$');
                alert(data[0]);
                //document.getElementById('Selected_patient').innerHTML = data[1];
                $("#Selected_patient").html(data[1]);
                $("#progressStatus").hide();
            }, error: function (html) {

            }
        });
    }
</script>
<script>
    function getPatientType(transfer_type) {
        $("#progressStatus").show();
<?php
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
    ?>
            document.getElementById('Selected_patient').innerHTML = '';
    <?php
}
?>

        $.ajax({
            type: 'GET',
            url: 'get_transfering_doctor_clinic.php',
            data: 'transfer_type=' + transfer_type + '&getTransfers=true',
            success: function (result) {
                if (result != '') {
                    $("#progressStatus").hide();

<?php
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
    ?>
                        var data = result.split('tegnanisha');
    <?php
}
?>

                    if (transfer_type == 'Doctor_To_Doctor') {
                        document.getElementById('transferTypeTitleFrom').innerHTML = 'FROM DOCTOR';
                        document.getElementById('transferTypeTitleTo').innerHTML = 'TO DOCTOR';

<?php
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
    ?>
                            document.getElementById('transfers_IDs').innerHTML = data[0];
                            document.getElementById('transfers_TO_IDs').innerHTML = data[1];
    <?php
} else {
    ?>
                            document.getElementById('transfers_IDs').innerHTML = result;
    <?php
}
?>

<?php
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
    ?>
                            document.getElementById('transfers_TO_IDs').innerHTML = '';

    <?php
}
?>

                    } else if (transfer_type == 'Doctor_To_Clinic') {
                        var data = result.split('tegnanisha');
                        document.getElementById('transferTypeTitleFrom').innerHTML = 'FROM DOCTOR';
                        document.getElementById('transferTypeTitleTo').innerHTML = 'TO CLINIC';
                        document.getElementById('transfers_IDs').innerHTML = data[0];
                        document.getElementById('transfers_TO_IDs').innerHTML = data[1];
                    } else if (transfer_type == 'Clinic_To_Clinic') {
                        document.getElementById('transferTypeTitleFrom').innerHTML = 'FROM CLINIC';
                        document.getElementById('transferTypeTitleTo').innerHTML = 'TO CLINIC';
                        document.getElementById('transfers_IDs').innerHTML = result;
                        document.getElementById('transfers_TO_IDs').innerHTML = '';
                    } else if (transfer_type == 'Clinic_To_Doctor') {

                        var data = result.split('tegnanisha');
                        document.getElementById('transferTypeTitleFrom').innerHTML = 'TO CLINIC';
                        document.getElementById('transferTypeTitleTo').innerHTML = 'FROM DOCTOR';
                        document.getElementById('transfers_IDs').innerHTML = data[1];
                        document.getElementById('transfers_TO_IDs').innerHTML = data[0];

                    }

                }

            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#viewlabPatientList,#patientLabList').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 1});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 1});
        
         $('select').select2();
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="css/jquery-ui.js"></script>

<?php
include("./includes/footer.php");
?>
