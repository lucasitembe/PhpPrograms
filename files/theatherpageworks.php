<?php
include("./includes/connection.php");
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
?>

<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['From']) && $_GET['From'] == 'doctor') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'inpatientdoctorpage') {
        
    } else {
        if (isset($_SESSION['userinfo']['Theater_Works'])) {
            if ($_SESSION['userinfo']['Theater_Works'] != 'yes') {
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

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$emp_ID = $Employee_ID;
?>
<!--SURGEON NAME TABLE -->
<table width='100%' id='empViewer' style='background:rgba(136,136,136, .6);position:absolute;z-index:700;visibility:hidden;height:100%'>

    <tr>
        <td width='100%' style="text-align:right;">
    <center>
        <fieldset style='overflow-y:scroll;height:500px;width:50%'>
            <legend align='center' style="background:#037CB0;font-size:20px;color:white;width:100%;">SELECT SURGEON FROM LIST BELOW</legend>

            <table width='100%' style='background:white' border=1>
                <tr bgcolor="#037CB0" style='color:white;'>
                    <th >SN</th>
                    <th>NAME</th>
                    <th>ACTION</th>
                </tr>
                <?php
                $n = 1;
                $Employee_qr = "SELECT * FROM tbl_employee where Employee_Type='Doctor'	 ";
                $emp_result = mysqli_query($conn,$Employee_qr) or die(mysqli_error($conn));
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    //where Employee_Type='Doctor'		
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $emp_row['Employee_Name']; ?></td>
                        <td><input type='checkbox' value='Add' onclick="checkSurgen(this, '<?php echo $emp_row['Employee_Name']; ?>', '<?php echo $emp_row['Employee_ID']; ?>')"></td>
                    </tr>
                    <?php
                    $n++;
                }
                ?>
            </table>
        </fieldset>
    </center>
</td>
</tr>
<tr>
    <td style="text-align:center;vertical-align:top;">
        <input type='button' style="height:40px;width:50%"  value='DONE' onclick='CloseEmpViewer()'>
    </td>
</tr>

</table>
<!--END OF TABLE -->


<!--ASSISTANT SURGEON NAME TABLE -->
<table width='100%' id='empViewer2' style='background:rgba(136,136,136, .6);position:absolute;z-index:700;visibility:hidden;height:100%'>

    <tr>
        <td width='100%' style="text-align:right;">
    <center>
        <fieldset style='overflow-y:scroll;height:500px;width:50%'>
            <legend align='center' style="background:#037CB0;font-size:20px;color:white;width:100%;">SELECT ASSISTANT SURGEON FROM LIST BELOW</legend>

            <table width='100%' style='background:white' border=1>
                <tr bgcolor="#037CB0" style='color:white;'>
                    <th >SN</th>
                    <th>NAME</th>
                    <th>ACTION</th>
                </tr>
                <?php
                $n = 1;
                $Employee_qr = "SELECT * FROM tbl_employee where Employee_Type='Doctor'	 ";
                $emp_result = mysqli_query($conn,$Employee_qr) or die(mysqli_error($conn));
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    //where Employee_Type='Doctor'		
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $emp_row['Employee_Name']; ?></td>
                        <td><input type='checkbox' value='Add' onclick="checkSurgen2(this, '<?php echo $emp_row['Employee_Name']; ?>', '<?php echo $emp_row['Employee_ID']; ?>')"></td>
                    </tr>
                    <?php
                    $n++;
                }
                ?>
            </table>
        </fieldset>
    </center>
</td>
</tr>
<tr>
    <td style="text-align:center;vertical-align:top;">
        <input type='button' style="height:40px;width:50%"  value='DONE' onclick='CloseEmpViewer2()'>
    </td>
</tr>

</table>
<!--END OF TABLE -->

<!--SCRUB NURSE NAME TABLE -->
<table width='100%' id='empViewer3' style='background:rgba(136,136,136, .6);position:absolute;z-index:700;visibility:hidden;height:100%'>

    <tr>
        <td width='100%' style="text-align:right;">
    <center>
        <fieldset style='overflow-y:scroll;height:500px;width:50%'>
            <legend align='center' style="background:#037CB0;font-size:20px;color:white;width:100%;">SELECT NURSE FROM LIST BELOW </legend>

            <table width='100%' style='background:white' border=1>
                <tr bgcolor="#037CB0" style='color:white;'>
                    <th >SN</th>
                    <th>NAME</th>
                    <th>ACTION</th>
                </tr>
                <?php
                $n = 1;
                $Employee_qr = "SELECT * FROM tbl_employee where Employee_Type='Nurse'	 ";
                $emp_result = mysqli_query($conn,$Employee_qr) or die(mysqli_error($conn));
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    //where Employee_Type='Doctor'		
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $emp_row['Employee_Name']; ?></td>
                        <td><input type='checkbox' value='Add' onclick="checkSurgen3(this, '<?php echo $emp_row['Employee_Name']; ?>', '<?php echo $emp_row['Employee_ID']; ?>')"></td>
                    </tr>
                    <?php
                    $n++;
                }
                ?>
            </table>
        </fieldset>
    </center>
</td>
</tr>
<tr>
    <td style="text-align:center;vertical-align:top;">
        <input type='button' style="height:40px;width:50%"  value='DONE' onclick='CloseEmpViewer3()'>
    </td>
</tr>

</table>
<!--END OF TABLE -->

<!--SCRUB RUNNERS NURSE  NAME TABLE -->
<table width='100%' id='empViewer4' style='background:rgba(136,136,136, .6);position:absolute;z-index:700;visibility:hidden;height:100%'>

    <tr>
        <td width='100%' style="text-align:right;">
    <center>
        <fieldset style='overflow-y:scroll;height:500px;width:50%'>
            <legend align='center' style="background:#037CB0;font-size:20px;color:white;width:100%;">SELECT NURSE FROM LIST BELOW </legend>

            <table width='100%' style='background:white' border=1>
                <tr bgcolor="#037CB0" style='color:white;'>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>ACTION</th>
                </tr>
                <?php
                $n = 1;
                $Employee_qr = "SELECT * FROM tbl_employee where Employee_Type='Nurse'	 ";
                $emp_result = mysqli_query($conn,$Employee_qr) or die(mysqli_error($conn));
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    //where Employee_Type='Doctor'		
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $emp_row['Employee_Name']; ?></td>
                        <td><input type='checkbox' value='Add' onclick="checkSurgen4(this, '<?php echo $emp_row['Employee_Name']; ?>', '<?php echo $emp_row['Employee_ID']; ?>')"></td>
                    </tr>
                    <?php
                    $n++;
                }
                ?>
            </table>
        </fieldset>
    </center>
</td>
</tr>
<tr>
    <td style="text-align:center;vertical-align:top;">
        <input type='button' style="height:40px;width:50%"  value='DONE' onclick='CloseEmpViewer4()'>
    </td>
</tr>

</table>
<!--END OF TABLE -->

<!--SCRUB ANAESTHETIST  NAME TABLE -->
<table width='100%' id='empViewer5' style='background:rgba(136,136,136, .6);position:absolute;z-index:700;visibility:hidden;height:100%'>

    <tr>
        <td width='100%' style="text-align:right;">
    <center>
        <fieldset style='overflow-y:scroll;height:500px;width:50%'>
            <legend align='center' style="background:#037CB0;font-size:20px;color:white;width:100%;">SELECT ANAESTHETIST FROM LIST BELOW</legend>

            <table width='100%' style='background:white' border=1>
                <tr bgcolor="#037CB0" style='color:white;'>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>ACTION</th>
                </tr>
                <?php
                $n = 1;
                $Employee_qr = "SELECT * FROM tbl_employee where Employee_Type='Nurse'	 ";
                $emp_result = mysqli_query($conn,$Employee_qr) or die(mysqli_error($conn));
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    //where Employee_Type='Doctor'		
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $emp_row['Employee_Name']; ?></td>
                        <td><input type='checkbox' value='Add' onclick="checkSurgen5(this, '<?php echo $emp_row['Employee_Name']; ?>', '<?php echo $emp_row['Employee_ID']; ?>')"></td>
                    </tr>
                    <?php
                    $n++;
                }
                ?>
            </table>
        </fieldset>
    </center>
</td>
</tr>
<tr>
    <td style="text-align:center;">
        <input type='button' style="height:40px;width:50%;"  value='DONE' onclick='CloseEmpViewer5()'>
    </td>
</tr>

</table>
<!--END OF TABLE -->



<?php
$direction = '';
if ($_SESSION['userinfo']['Theater_Works'] == 'yes') {
    ?>
    <a href='theatherpageworkreport.php?Registration_ID=<?php echo filter_input(INPUT_GET, 'Registration_ID'); ?>' class='art-button-green'>REPORTS /TREATMENT </a>
<?php } ?>

<?php
//  if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ 
?>

<?php //  } else{ ?> <?php
if (isset($_GET['From'])) {
    ?>
    <a href='clinicalnotes.php?<?php
    if (isset($_GET['Registration_ID'])) {
        echo "Registration_ID=" . $_GET['Registration_ID'] . "&";
    }
    ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        BACK 
    </a>

    <?php
    $direction = 'dir=From&';
} elseif (isset($_GET['Section']) && $_GET['Section'] == 'inpatientdoctorpage') {
    ?>
    <a href='doctorspageinpatientwork.php?<?php
    if (isset($_GET['Registration_ID'])) {
        echo "Registration_ID=" . $_GET['Registration_ID'] . "&";
    }
    ?><?php
    if (isset($_GET['consultation_ID'])) {
        echo "consultation_ID=" . $_GET['consultation_ID'] . "";
    }
    ?>' class='art-button-green'>
        BACK 
    </a>
    <?php
    $direction = 'dir=Section&consultation_ID=' . $_GET['consultation_ID'] . '&Registration_ID=' . $_GET['Registration_ID'] . '&';
} else {
    ?>
    <a href='theatherpage.php' class='art-button-green'>
        BACK
    </a>
    <?php
}
?>

<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>
<!-- end of the function -->
<?php
$Registration_ID = '';
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_theather = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, 
						Guarantor_Name,Gender, Date_Of_Birth
				FROM 
						tbl_patient_registration pr, tbl_sponsor sp
				WHERE 
						pr.Registration_ID='$Registration_ID%' AND
						pr.sponsor_id = sp.sponsor_id  ") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_theather)) {

        $Registration_ID = $row['Registration_ID'];
        $Patient_Name = $row['Patient_Name'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];

        $Age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($Age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $Age = $diff->m . " Months";
        }
        if ($Age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $Age = $diff->d . " Days";
        }
    }
}


//insertation to database
//$Surgeon_Ids = explode(',',$_POST['Surgeon_Id']);

/* 	if(isset($_POST['submitthearteform'])){     
  $Surgeon_Ids = explode(',',$_POST['Crub_Nurse']);
  foreach($Surgeon_Ids as $emp){
  $theater_insert=mysqli_query($conn,"INSERT INTO tbl_theater_registration(Surgeon_ID)
  VALUES
  ($emp)");
  }


  } */
?>

<!--Script for surgeon name  -->
<script>
    function CloseEmpViewer() {
        document.getElementById('empViewer').style.visibility = 'hidden';
    }
</script>

<script>
    var surgenList = [];

    var Surg = function () {
        this.Employee_Name = '';
        this.Employee_ID = '';
    };

    function SelectViewer() {
        document.getElementById('empViewer').style.visibility = 'visible';
    }

    function checkSurgen(el, Surgeon, Surgeon_Id) {
        var check = el.checked;
        if (check) {
            addSurgen(Surgeon, Surgeon_Id);
        } else {
            removeSurgen(Surgeon, Surgeon_Id);
        }
    }

    function removeSurgen(Surgeon, Surgeon_Id) {
        for (var key = 0; key < surgenList.length; key++) {
            if (surgenList[key].Employee_Name == Surgeon && surgenList[key].Employee_ID == Surgeon_Id) {
                surgenList.splice(key, 1);
                break;
            }
        }
        displaySurgen();
    }

    function addSurgen(Surgeon, Surgeon_Id) {
        var newSurgen = new Surg();
        newSurgen.Employee_Name = Surgeon;
        newSurgen.Employee_ID = Surgeon_Id;
        surgenList.push(newSurgen);
        displaySurgen();
    }

    function displaySurgen() {
        var Surgeon_names = "";
        var Surgeon_ids = "";
        for (var key = 0; key < surgenList.length; key++) {
            if (Surgeon_names == "") {
                var Surgeon_names = surgenList[key].Employee_Name;
                var Surgeon_ids = surgenList[key].Employee_ID;
            } else {
                var Surgeon_names = Surgeon_names + ', ' + surgenList[key].Employee_Name;
                var Surgeon_ids = Surgeon_ids + "," + surgenList[key].Employee_ID;
            }
        }
        document.getElementById('Surgeon').value = Surgeon_names;
        document.getElementById('Surgeon_Id').value = Surgeon_ids;
    }
</script>
<!--end of  Script -->


<!--Script for surgeon assistant  -->
<script>
    function CloseEmpViewer2() {
        document.getElementById('empViewer2').style.visibility = 'hidden';
    }
</script>

<script>
    var surgenList2 = [];

    var Surg = function () {
        this.Employee_Name = '';
        this.Employee_ID = '';
    };

    function SelectViewer2() {
        document.getElementById('empViewer2').style.visibility = 'visible';
    }

    function checkSurgen2(el, Assistant_Surgeon, Assistant_Surg) {
        var check = el.checked;
        if (check) {
            addSurgen2(Assistant_Surgeon, Assistant_Surg);
        } else {
            removeSurgen2(Assistant_Surgeon, Assistant_Surg);
        }
    }

    function removeSurgen2(Assistant_Surgeon, Assistant_Surg) {
        for (var key = 0; key < surgenList2.length; key++) {
            if (surgenList2[key].Employee_Name == Assistant_Surgeon && surgenList2[key].Employee_ID == Assistant_Surg) {
                surgenList2.splice(key, 1);
                break;
            }
        }
        displaySurgen2();
    }

    function addSurgen2(Assistant_Surgeon, Assistant_Surg) {
        var newSurgen = new Surg();
        newSurgen.Employee_Name = Assistant_Surgeon;
        newSurgen.Employee_ID = Assistant_Surg;
        surgenList2.push(newSurgen);
        displaySurgen2();
    }

    function displaySurgen2() {
        var Assistant_Surgeon_names = "";
        var Assistant_Surgs = "";
        for (var key = 0; key < surgenList2.length; key++) {
            if (Assistant_Surgeon_names == "") {
                var Assistant_Surgeon_names = surgenList2[key].Employee_Name;
                var Assistant_Surgs = surgenList2[key].Employee_ID;
            } else {
                var Assistant_Surgeon_names = Assistant_Surgeon_names + ', ' + surgenList2[key].Employee_Name;
                var Assistant_Surgs = Assistant_Surgs + "," + surgenList2[key].Employee_ID;
            }
        }
        document.getElementById('Assistant_Surgeon').value = Assistant_Surgeon_names;
        document.getElementById('Assistant_Surg').value = Assistant_Surgs;
    }
</script>
<!--end of  Script -->


<!--Script for scrub nurse  -->
<script>
    function CloseEmpViewer3() {
        document.getElementById('empViewer3').style.visibility = 'hidden';
    }
</script>

<script>
    var surgenList3 = [];

    var Surg = function () {
        this.Employee_Name = '';
        this.Employee_ID = '';
    };

    function SelectViewer3() {
        document.getElementById('empViewer3').style.visibility = 'visible';
    }

    function checkSurgen3(el, Crub_Nurse, Crub_Nursing) {
        var check = el.checked;
        if (check) {
            addSurgen3(Crub_Nurse, Crub_Nursing);
        } else {
            removeSurgen3(Crub_Nurse, Crub_Nursing);
        }
    }

    function removeSurgen3(Crub_Nurse, Crub_Nursing) {
        for (var key = 0; key < surgenList3.length; key++) {
            if (surgenList3[key].Employee_Name == Crub_Nurse && surgenList3[key].Employee_ID == Crub_Nursing) {
                surgenList3.splice(key, 1);
                break;
            }
        }
        displaySurgen3();
    }

    function addSurgen3(Crub_Nurse, Crub_Nursing) {
        var newSurgen = new Surg();
        newSurgen.Employee_Name = Crub_Nurse;
        newSurgen.Employee_ID = Crub_Nursing;
        surgenList3.push(newSurgen);
        displaySurgen3();
    }

    function displaySurgen3() {
        var Crub_Nurse_names = "";
        var Crub_Nursings = "";
        for (var key = 0; key < surgenList3.length; key++) {
            if (Crub_Nurse_names == "") {
                var Crub_Nurse_names = surgenList3[key].Employee_Name;
                var Crub_Nursings = surgenList3[key].Employee_ID;
            } else {
                var Crub_Nurse_names = Crub_Nurse_names + ', ' + surgenList3[key].Employee_Name;
                var Crub_Nursings = Crub_Nursings + "," + surgenList3[key].Employee_ID;
            }
        }
        document.getElementById('Crub_Nurse').value = Crub_Nurse_names;
        document.getElementById('Crub_Nursing').value = Crub_Nursings;
    }
</script>
<!--end of  Script -->



<!--Script for runners nurse  -->
<script>
    function CloseEmpViewer4() {
        document.getElementById('empViewer4').style.visibility = 'hidden';
    }

</script>
<script>
    var surgenList4 = [];

    var Surg = function () {
        this.Employee_Name = '';
        this.Employee_ID = '';
    };

    function SelectViewer4() {
        document.getElementById('empViewer4').style.visibility = 'visible';
    }

    function checkSurgen4(el, Runners_Nurse, Runners_Nursing) {
        var check = el.checked;
        if (check) {
            addSurgen4(Runners_Nurse, Runners_Nursing);
        } else {
            removeSurgen4(Runners_Nurse, Runners_Nursing);
        }
    }

    function removeSurgen4(Runners_Nurse, Runners_Nursing) {
        for (var key = 0; key < surgenList4.length; key++) {
            if (surgenList4[key].Employee_Name == Runners_Nurse && surgenList4[key].Employee_ID == Runners_Nursing) {
                surgenList4.splice(key, 1);
                break;
            }
        }
        displaySurgen4();
    }

    function addSurgen4(Runners_Nurse, Runners_Nursing) {
        var newSurgen = new Surg();
        newSurgen.Employee_Name = Runners_Nurse;
        newSurgen.Employee_ID = Runners_Nursing;
        surgenList4.push(newSurgen);
        displaySurgen4();
    }

    function displaySurgen4() {
        var Runners_Nurse_names = "";
        var Runners_Nursings = "";
        for (var key = 0; key < surgenList4.length; key++) {
            if (Runners_Nurse_names == "") {
                var Runners_Nurse_names = surgenList4[key].Employee_Name;
                var Runners_Nursings = surgenList4[key].Employee_ID;
            } else {
                var Runners_Nurse_names = Runners_Nurse_names + ', ' + surgenList4[key].Employee_Name;
                var Runners_Nursings = Runners_Nursings + "," + surgenList4[key].Employee_ID;
            }
        }
        document.getElementById('Runners_Nurse').value = Runners_Nurse_names;
        document.getElementById('Runners_Nursing').value = Runners_Nursings;
    }
</script>
<!--end of  Script -->


<!--Script for Anaesthetist  -->
<script>
    function CloseEmpViewer5() {
        document.getElementById('empViewer5').style.visibility = 'hidden';
    }
</script>

<script>
    var surgenList5 = [];

    var Surg = function () {
        this.Employee_Name = '';
        this.Employee_ID = '';
    };

    function SelectViewer5() {
        document.getElementById('empViewer5').style.visibility = 'visible';
    }

    function checkSurgen5(el, Anaesthetist, Anaesthesing) {
        var check = el.checked;
        if (check) {
            addSurgen5(Anaesthetist, Anaesthesing);
        } else {
            removeSurgen5(Anaesthetist, Anaesthesing);
        }
    }

    function removeSurgen5(Anaesthetist, Anaesthesing) {
        for (var key = 0; key < surgenList5.length; key++) {
            if (surgenList5[key].Employee_Name == Anaesthetist && surgenList5[key].Employee_ID == Anaesthesing) {
                surgenList5.splice(key, 1);
                break;
            }
        }
        displaySurgen5();
    }

    function addSurgen5(Anaesthetist, Anaesthesing) {
        var newSurgen = new Surg();
        newSurgen.Employee_Name = Anaesthetist;
        newSurgen.Employee_ID = Anaesthesing;
        surgenList5.push(newSurgen);
        displaySurgen5();
    }

    function displaySurgen5() {
        var Anaesthetist_names = "";
        var Anaesthesings = "";
        for (var key = 0; key < surgenList5.length; key++) {
            if (Anaesthetist_names == "") {
                var Anaesthetist_names = surgenList5[key].Employee_Name;
                var Anaesthesings = surgenList5[key].Employee_ID;
            } else {
                var Anaesthetist_names = Anaesthetist_names + ', ' + surgenList5[key].Employee_Name;
                var Anaesthesings = Anaesthesings + "," + surgenList5[key].Employee_ID;
            }
        }
        document.getElementById('Anaesthetist').value = Anaesthetist_names;
        document.getElementById('Anaesthesing').value = Anaesthesings;
    }
</script>
<!--end of  Script -->


<center>
    <?php
    //if(!empty($_POST)){
    //	$Surgeon_Ids = explode(',',$_POST['Surgeon_Id']);
    //print_r($Surgeon_Ids);
//	}
    //Check for payment
    $selectItemID = '';
    // echo $Registration_ID;
    $Temp_Billing_Type = '';
    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    }
    //echo $Registration_ID;
    $rs = mysqli_query($conn,"SELECT Billing_Type FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1") or die(mysqli_error($conn));
    $Temp_Billing_Type = mysqli_fetch_assoc($rs)['Billing_Type'];
    $consultation_ID = '';
    if (isset($_GET['consultation_ID']) and ! empty($_GET['consultation_ID'])) {
//        echo "Hapa";
//        exit();
        $consultation_ID = $_GET['consultation_ID'];
        $select_Patient = mysqli_query($conn,"select *,ilc.Status from tbl_item_list_cache ilc LEFT JOIN  tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
                     JOIN tbl_items i ON i.Item_ID=ilc.Item_ID
                      WHERE pc.consultation_id='$consultation_ID' AND Billing_Type='$Temp_Billing_Type' AND pc.Registration_ID='$Registration_ID' AND ilc.Check_In_Type ='Surgery' AND ilc.Status <>'served'") or die(mysqli_error($conn)) or die(mysqli_error($conn));
        $selectItemID = "select ilc.Payment_Item_Cache_List_ID,ilc.Item_ID,i.Product_Name from tbl_item_list_cache ilc LEFT JOIN  tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
                     JOIN tbl_items i ON i.Item_ID=ilc.Item_ID
                      WHERE pc.consultation_id='$consultation_ID' AND Billing_Type='$Temp_Billing_Type' AND pc.Registration_ID='$Registration_ID' AND ilc.Check_In_Type ='Surgery' AND ilc.Status <>'served'";
    } else {
//        echo "Hapa";
//        exit();
        $select_Patient = mysqli_query($conn,"select *,ilc.Status from tbl_item_list_cache ilc LEFT JOIN  tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
                     JOIN tbl_items i ON i.Item_ID=ilc.Item_ID
                      WHERE pc.consultation_id=(SELECT Consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID='" . $_GET['Patient_Payment_Item_List_ID'] . "') AND Billing_Type='$Temp_Billing_Type' AND pc.Registration_ID='$Registration_ID' AND ilc.Check_In_Type ='Surgery' AND ilc.Status <>'served'") or die(mysqli_error($conn)) or die(mysqli_error($conn));

        $selectItemID = "select ilc.Payment_Item_Cache_List_ID,ilc.Item_ID,i.Product_Name from tbl_item_list_cache ilc LEFT JOIN  tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
                     JOIN tbl_items i ON i.Item_ID=ilc.Item_ID
                      WHERE pc.consultation_id=(SELECT Consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID='" . $_GET['Patient_Payment_Item_List_ID'] . "') AND Billing_Type='$Temp_Billing_Type' AND pc.Registration_ID='$Registration_ID' AND ilc.Check_In_Type ='Surgery' AND ilc.Status <>'served'";
    }
    $Temp_Billing_Type = '';
    $no = mysqli_num_rows($select_Patient);
    $Payment_Status = array();
    $totalItem = array();
    $Transaction_Status_Title = '';
    // $totalItem=array();
//    echo $no;
//    exit();
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Folio_Number = $row['Folio_Number'];
            $Payment_Cache_ID = $row['Payment_Cache_ID'];
            $Billing_Type = $row['Billing_Type'];
            $Transaction_Type = $row['Transaction_Type'];
            $Item_ID = $row['Item_ID'];
            $Product_Name = $row['Product_Name'];
            $Status = $row['Status'];
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];

            //   echo $Billing_Type.' '.$Transaction_Type.'  '. $Status.'<br/>';


            if ($Billing_Type == 'Outpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'paid') {
                $Payment_Status[] = 'Paid';
                $totalItem[] = $Item_ID . 'ilcID' . $Payment_Item_Cache_List_ID;
                $Transaction_Status_Title = 'PAID';
            } elseif ($Billing_Type == 'Outpatient Credit' && $Transaction_Type == 'Credit') {
                $Payment_Status[] = 'Not Billed';
                $totalItem[] = $Item_ID . 'ilcID' . $Payment_Item_Cache_List_ID;
                $Transaction_Status_Title = 'NOT BILLED';
            } elseif ($Billing_Type == 'Outpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'active') {
                $Payment_Status[] = 'Not Paid';
                $Transaction_Status_Title = 'NOT PAID';
                $onclick = 'onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
                $href = '#';
            } elseif ($Billing_Type == 'Inpatient Credit' || $Billing_Type == 'Inpatient Cash') {
                $Payment_Status[] = 'Not Billed';
                $totalItem[] = $Item_ID . 'ilcID' . $Payment_Item_Cache_List_ID;
                $Transaction_Status_Title = 'NOT BILLED';
                //$href ='#';
                // $onclick='onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
            } elseif ($Billing_Type == 'Outpatient Cash' && $Status == 'active') {
                $Payment_Status[] = 'Not Paid';
                $totalItem[] = $Item_ID . 'ilcID' . $Payment_Item_Cache_List_ID;
                $Transaction_Status_Title = 'NOT PAID';
                $href = '#';
                $onclick = 'onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
            } elseif ($Billing_Type == 'Outpatient Cash' && $Status == 'paid') {
                $Payment_Status[] = 'Paid';
                $totalItem[] = $Item_ID . 'ilcID' . $Payment_Item_Cache_List_ID;
                $Transaction_Status_Title = 'PAID';
            }
        }
    }

    // echo $Transaction_Status_Title;

    if (isset($_POST['submiopeartiveform'])) {

        $Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
        $Procedure_Name = mysqli_real_escape_string($conn,$_POST['Procedure_Name']);
        $date_From = mysqli_real_escape_string($conn,$_POST['date_From']);
        $Indication = mysqli_real_escape_string($conn,$_POST['Indication']);
        $Type_Of_Incision = mysqli_real_escape_string($conn,$_POST['Type_Of_Incision']);
        $Anaesthesia = mysqli_real_escape_string($conn,$_POST['Anaesthesia']);
        $Findings = mysqli_real_escape_string($conn,$_POST['Findings']);
        $Procedures = mysqli_real_escape_string($conn,$_POST['Procedures']);
        $Closure = mysqli_real_escape_string($conn,$_POST['Closure']);
        $Drains = mysqli_real_escape_string($conn,$_POST['Drains']);
        $Skin_Structure = mysqli_real_escape_string($conn,$_POST['Skin_Structure']);
        $consultation_ID = (isset($_GET['consultation_ID']) ? $_GET['consultation_ID'] : '');
        $Item_ID = mysqli_real_escape_string($conn,$_POST['Procedure_Name']);
        $ilcID = mysqli_real_escape_string($conn,$_POST['ilcID']);
        $Post_operative_ID = '';
        $dir = "post_operative_attachments/";
        // echo ($ilcID);

        if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            } else {
                $Employee_ID = 0;
            }
        }

        //  echo $consultation_ID.'  ';

        $post_operative = "INSERT INTO tbl_post_operative(Registration_ID,Procedure_Names,date_From,
							Indication,Type_Of_Incision,Anaesthesia,Findings,Procedures,Closure,Skin_Structure,
							Drains,Post_Operative_Date_Time,consultation_ID,Employee_ID)
                    VALUES
						('$Registration_ID','$Procedure_Name','$date_From','$Indication','$Type_Of_Incision',
						'$Anaesthesia','$Findings','$Procedures','$Closure','$Skin_Structure','$Drains',(Select now()),
							'$consultation_ID','$Employee_ID')";

        if (!mysqli_query($conn,$post_operative)) {
            die(mysqli_error($conn));
            $error = '1062yes';
            if (mysql_errno() . "yes" == $error) {
                $controlforminput = 'not valid';
            }
        } else {
            $Post_opartive2 = mysqli_query($conn,"SELECT Post_operative_ID from tbl_post_operative 
										WHERE 
											Employee_ID='$Employee_ID' AND
											Registration_ID='$Registration_ID' 
											order by Post_Operative_Date_Time DESC limit 1") or die(mysqli_error($conn));
            $opera = mysqli_fetch_array($Post_opartive2);

            $Post_operative_ID = $opera['Post_operative_ID'];


            $Surgeon_Ids = explode(',', $_POST['Surgeon_Id']);
            //$Operative_Status='surgeon';
            foreach ($Surgeon_Ids as $emp) {
                if ($emp != '') {
                    //	$Operative_Status='surgeon';			
                    $theater_insertion = mysqli_query($conn,"INSERT INTO tbl_theater_registration
									(Surgeon_ID,Post_operative_ID,Post_Operative_Date_Time,Operative_Status)
									VALUES 
										('$emp','$Post_operative_ID',(Select now()),'surgeon')");
                }
            }

            $Assistant_Surg = explode(',', $_POST['Assistant_Surg']);

            //	$Operative_Status1='assistant_surgeon';
            foreach ($Assistant_Surg as $emp2) {
                if ($emp2 != '') {
                    $theater_insertion2 = mysqli_query($conn,"INSERT INTO tbl_theater_registration
											(Surgeon_ID,Post_operative_ID,Post_Operative_Date_Time,Operative_Status)
										VALUES 
											('$emp2','$Post_operative_ID',(Select now()),'assistant_surgeon')");
                }
            }


            $Crub_Nursings = explode(',', $_POST['Crub_Nursing']);
            //$Operative_Status2='crub_nursing';
            foreach ($Crub_Nursings as $emp3) {
                if ($emp3 != '') {
                    $theater_insertion3 = mysqli_query($conn,"INSERT INTO tbl_theater_registration
										(Surgeon_ID,Post_operative_ID,Post_Operative_Date_Time,Operative_Status)
										VALUES 
											('$emp3','$Post_operative_ID',(Select now()),'crub_nursing')");
                }
            }

            $Runners_Nursings = explode(',', $_POST['Runners_Nursing']);
            //$Operative_Status3='runners_nurse';
            foreach ($Runners_Nursings as $emp4) {
                if ($emp4 != '') {
                    $theater_insertion4 = mysqli_query($conn,"INSERT INTO tbl_theater_registration
										(Surgeon_ID,Post_operative_ID,Post_Operative_Date_Time,Operative_Status)
										VALUES 
											('$emp4','$Post_operative_ID',(Select now()),'runners_nurse')");
                }
            }
            $Anaesthesings = explode(',', $_POST['Anaesthesing']);
            //	$Operative_Status4='Anaesthesing';
            foreach ($Anaesthesings as $emp5) {
                if ($emp5 != '') {
                    $theater_insertion5 = mysqli_query($conn,"INSERT INTO tbl_theater_registration
											(Surgeon_ID,Post_operative_ID,Post_Operative_Date_Time,Operative_Status
											)
										VALUES 
											('$emp5','$Post_operative_ID',(Select now()),'Anaesthesing')");
                }
            }

            if (trim($Transaction_Status_Title) == 'NOT BILLED') {
                // if(!empty($Item_ID)){
                // echo 'SUCCESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS';
                //start_billing
                //Retrieve patient info
                $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

                $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];
                $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

                $Claim_Form_Number = '';

                $select = mysqli_query($conn,"select Folio_Number,sp.Sponsor_ID,Sponsor_Name,Claim_Form_Number,Billing_Type,branch_id,Guarantor_Name from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID where pp.Registration_ID = '" . $Registration_ID . "' AND pp.Check_In_ID = '" . $Check_In_ID . "'  AND sp.Sponsor_ID = '" . $Sponsor_ID . "' order by pp.Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

                if (mysqli_num_rows($select)) {
                    $patinfo = mysqli_fetch_array($select);
                    $Claim_Form_Number = $patinfo['Claim_Form_Number'];
                    $folio = $patinfo['Folio_Number'];
                    $spID = $patinfo['Sponsor_ID'];
                    $branch_id = $patinfo['branch_id'];
                    $spName = $patinfo['Guarantor_Name'];
                    $billType = $patinfo['Billing_Type'];
                    $Guarantor_Name = $patinfo['Guarantor_Name'];

                    //get last check in id
                } else {
                    include("./includes/Folio_Number_Generator_Emergency.php");
                    $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
                    $patinfo = mysqli_fetch_array($select);

                    $Claim_Form_Number = $patinfo['Claim_Form_Number'];
                    $spID = $patinfo['Sponsor_ID'];
                    $branch_id = $patinfo['branch_id'];
                    $spName = $patinfo['Guarantor_Name'];
                    $Guarantor_Name = $patinfo['Guarantor_Name'];


                    if (strtolower($spName) == 'cash') {
                        $billType = "Inpatient Cash";
                    } else {
                        $billType = "Inpatient Credit";
                    }

                    $has_no_folio = true;
                }

                if ($Claim_Form_Number == 'NULL') {
                    $Claim_Form_Number = '';
                }

                $Consultant = '';

                $qr_cons = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$emp_ID'") or die(mysqli_error($conn));
                $Consultant = mysqli_fetch_assoc($qr_cons)['Employee_Name'];

                //get patient bill id
                $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                $nums = mysqli_num_rows($select);
                if ($nums > 0) {
                    while ($row = mysqli_fetch_array($select)) {
                        $Patient_Bill_ID = $row['Patient_Bill_ID'];
                    }
                } else {
                    //insert data to tbl_patient_bill
                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
                    if ($insert) {
                        $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                        $nums = mysqli_num_rows($select);
                        while ($row = mysqli_fetch_array($select)) {
                            $Patient_Bill_ID = $row['Patient_Bill_ID'];
                        }
                    }
                }
                //end of fetching patient bill id
                //Bill patient
                $insert_pp = "INSERT INTO tbl_patient_payments(
                                    Registration_ID,
                                    Supervisor_ID,
                                    Employee_ID,
                                    Payment_Date_And_Time,
                                    Folio_Number,
                                    Check_In_ID,
                                    Claim_Form_Number,
                                    Sponsor_ID,
                                    Sponsor_Name,
                                    Billing_Type,
                                    Receipt_Date,
                                    branch_id,
                                    Patient_Bill_ID
                            ) VALUES(
                                    '$Registration_ID',
                                    '$emp_ID',
                                    '$emp_ID',
                                    NOW(),
                                    '$folio',
                                    '$Check_In_ID',
                                    '$Claim_Form_Number',
                                    '$spID',
                                    '$spName',
                                    '$billType',
                                    NOW(),
                                    '$branch_id',
                                    '$Patient_Bill_ID'
                            )";
                $insert_pp_qry = mysqli_query($conn,$insert_pp) or die(mysqli_error($conn));
                if ($insert_pp_qry) {
                    //get the last patient_payment_id & date
                    $select_details = mysqli_query($conn,"
                            SELECT Patient_Payment_ID, Receipt_Date 
                                    FROM tbl_patient_payments 
                                    WHERE 
                                    Registration_ID = '$Registration_ID' AND 
                                    Employee_ID = '$emp_ID' 
                                    ORDER BY Patient_Payment_ID DESC LIMIT 1
                                    ") or die(mysqli_error($conn));
                    $num_row = mysqli_num_rows($select_details);
                    if ($num_row > 0) {
                        while ($details_data = mysqli_fetch_assoc($select_details)) {
                            $New_Patient_Payment_ID = $details_data['Patient_Payment_ID'];
                            $Receipt_Date = $details_data['Receipt_Date'];
                        }
                    }

                    //GET ITEM INFOS
                    $Price = '';
                    $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$spID'";
                    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemSpecResult) > 0) {
                        $row = mysqli_fetch_assoc($itemSpecResult);
                        $Price = $row['price'];
                        if ($Price == 0) {
                            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                                    where ig.Item_ID = '$Item_ID'";
                            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                            if (mysqli_num_rows($itemGenResult) > 0) {
                                $row = mysqli_fetch_assoc($itemGenResult);
                                $Price = $row['price'];
                            } else {
                                $Price = 0;
                            }
                        }
                        //echo $Select_Price;
                    } else {
                        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                               where ig.Item_ID = '$Item_ID'";
                        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                        if (mysqli_num_rows($itemGenResult) > 0) {
                            $row = mysqli_fetch_assoc($itemGenResult);
                            $Price = $row['price'];
                        } else {
                            $Price = 0;
                        }
                    }


                    // DIE($Price);
                    //now add iterm

                    $Check_In_Type = 'Surgery';

                    //Insert Data into  tbl_patient_payment_item_list table
                    $insert_ppil = "
                                        INSERT INTO  tbl_patient_payment_item_list(
                                                Check_In_Type,
                                                Item_ID,
                                                Price,
                                                Quantity,
                                                Discount,
                                                Patient_Direction,
                                                Consultant,
                                                Consultant_ID,
                                                Status,
                                                Patient_Payment_ID,
                                                Transaction_Date_And_Time,
                                                ServedDateTime,
                                                ServedBy,
                                                ItemOrigin
                                        ) VALUES (
                                                '$Check_In_Type',
                                                '$Item_ID',
                                                '$Price',
                                                '1',
                                                '0',
                                                'Others',
                                                '$Consultant',
                                                '$emp_ID',
                                                'Served',
                                                '$New_Patient_Payment_ID',
                                                NOW(),
                                                NOW(),
                                                '$emp_ID',
                                                'Doctor'
                                        )
                                ";
                    //Run the Query
                    $insert_ppil_qry = mysqli_query($conn,$insert_ppil) or die(mysqli_error($conn));
                }
            }

            if (isset($_FILES['attachment'])) {
                foreach ($_FILES['attachment']['tmp_name'] as $key => $tmp_name) {
                    $file_name = $_FILES['attachment']['name'][$key];
                    $file_size = $_FILES['attachment']['size'][$key];
                    $file_tmp = $_FILES['attachment']['tmp_name'][$key];
                    $file_type = $_FILES['attachment']['type'][$key];

                    if (!empty($file_name)) {
                        $file_name = $Post_operative_ID . $Registration_ID . date("YmdHsm") . $file_name;
                    }

                    $sql = "INSERT INTO tbl_post_operative_attachment(Post_operative_ID,name,type) VALUES ($Post_operative_ID,'$file_name','$file_type')";

                    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

                    if ($result) {
                        if (!file_exists($dir)) {
                            mkdir($dir);
                        }
                        move_uploaded_file($file_tmp, $dir . $file_name);
                    }
                }
            }

            $query = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='served',ServedDateTime=NOW(),ServedBy='$Employee_ID' WHERE Payment_Item_Cache_List_ID='$ilcID'") or die(mysqli_error($conn));

            if ($query) {
                echo "<script type='text/javascript'>
							alert('ADDED SUCCESSFUL');
							document.location = 'theatherpageworkreport.php?" . $direction . "Registration_ID=" . $Registration_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "';
							</script>";
            } else {
                echo "<script type='text/javascript'>
							alert('AN ERROR HAS OCCURED! PLEASE TRY AGAIN LATER.');
							</script>";
            }
        }
//} 
    }



    if (!in_array('Not Paid', $Payment_Status)) {


//             $itemIDarray=array();
//             $ilcIDarray=array();
//            foreach ($totalItem as $value) {
//                 $newIrray=  explode('ilcID', $value);
//                $itemIDarray[]=$newIrray[0];
//                $ilcIDarray[]=$newIrray[1];
//            }
        //  echo implode(',', $ilcIDarray);exit;
        //echo "select * from tbl_items  WHERE Item_ID IN (".  implode(',', $totalItem).")";
        //echo ($Temp_Billing_Type);exit;
        ?>
        <form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <fieldset style="width:95%;margin-top:10px;">
                <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
                        <?php echo $Patient_Name . ",Patient_No-" . $Registration_ID . "," . $Guarantor_Name . "," . $Gender . "," . $Age; ?></b>
                </legend>
                <p>
                <td  colspan="7" style="text-align:center;">
                    <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <?php echo '<b>STATUS : </b>SURGERY ' . $Transaction_Status_Title; ?>
                </td>

                </p>


                <hr style="width:100% "/>
                <?php
                if ($no > 0) {
                    ?>
                    <table width=100%>
                        <tr>
                            <td style="text-align:right;" width=13%>Type Of Procedure</td>
                            <td width=17%>
                                <select style="width:100%;" name='Procedure_Name' id='Procedure_Name' required='required' onchange="getItemListCacheID(this.options[this.selectedIndex].getAttribute('ilcID'))">
                                    <option selected="selected" style="width: 100%;padding: 4px;"></option>	
                                    <?php
                                    $choose_item = mysqli_query($conn,$selectItemID) or die(mysqli_error($conn)); //"select * from tbl_items  WHERE Item_ID IN (".  implode(',', $itemIDarray).")");
                                    while ($row_ = mysqli_fetch_array($choose_item)) {
//                                                          if($Item_ID==$row_['Item_ID']){
//                                                              
//                                                            echo "   <option value='".$row_['Item_ID']."' selected='selected'>".ucwords(strtolower($row_['Product_Name']))."</option>";
//                                                          }  else {
                                        ?>

                                        <option style="padding:2px; " value='<?php echo $row_['Item_ID']; ?>' ilcID="<?php echo $row_['Payment_Item_Cache_List_ID']; ?>"><?php echo ucwords(strtolower($row_['Product_Name'])); ?></option>
                                        <?php
                                    }//}
                                    ?>

                                </select>
                                <input type="hidden" name="ilcID" value="" id="ilcIDValue">
                            </td>

                            <td style="text-align:right;" width=13%>Surgeon </td>
                            <td width=50%>
                                <input type="text" name="Surgeon" id="Surgeon"  value=''>
                                <input type="hidden" name="Surgeon_Id" id="Surgeon_Id" value="">
                            </td>
                            <td width=7%>
                                <input type='button' class='art-button-green' onclick="SelectViewer()" value='LIST'>
                            </td>
                        </tr>	
                        <tr>

                            <td style="text-align:right;" >Date And Time</td>
                            <td><input type="text" autocomplete="off" name="date_From" id="date_From" required='required'></td>


                            <td style="text-align:right;"> Assistant Surgeon</td>
                            <td >
                                <input type="text" name="Assistant_Surgeon" id="Assistant_Surgeon" readonly='readonly'>
                                <input type="hidden" name="Assistant_Surg" id="Assistant_Surg" value=''>
                            </td>
                            <td>
                                <input type='button' class='art-button-green' onclick="SelectViewer2()" value='LIST'>
                            </td>


                        </tr>

                        <tr>
                            <td style="text-align:right;">Indication</td>
                            <td><input type="text" name="Indication" id="Indication" ></td>

                            <td style="text-align:right;">Scrub Nurse</td>
                            <td >
                                <input type="text" name="Crub_Nurse" id="Crub_Nurse" readonly>
                                <input type="hidden" name="Crub_Nursing" id="Crub_Nursing" value=''>
                            </td>
                            <td>
                                <input type='button' class='art-button-green' onclick="SelectViewer3()" value='LIST'>
                            </td>

                        </tr>
                        <tr>
                            <td style="text-align:right;">Type Of Incision</td><td><input type="text" name="Type_Of_Incision" id="Type_Of_Incision" ></td>

                            <td style="text-align:right;">Runner Nurse</td>
                            <td >
                                <input type="text" name="Runners_Nurse" id="Runners_Nurse"  readonly='readonly'>
                                <input type="hidden" name="Runners_Nursing" id="Runners_Nursing" value=''>
                            </td>
                            <td>
                                <input type='button' class='art-button-green' onclick="SelectViewer4()" value='LIST'>

                            </td>

                        </tr>

                        <tr>
                            <td style="text-align:right;">Anaesthesia</td>
                            <td width=20%>
                                <input type="text" name="Anaesthesia" id="Anaesthesia" >

                            </td>

                            <td style="text-align:right;">Anaesthetist</td>
                            <td>
                                <input type="text" name="Anaesthetist" id="Anaesthetist" readonly='readonly' >
                                <input type="hidden" name="Anaesthesing" id="Anaesthesing" value=''>
                            </td>
                            <td>
                                <input type='button' class='art-button-green' onclick="SelectViewer5()" value='LIST'>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table width="100%">
                        <tr>
                            <td style="text-align:right;">Findings</td>
                            <td>
                                <textarea name="Findings" id="Findings" style="resize:none;"></textarea>
                            </td>
                            <td style="text-align:right;">Procedures</td>
                            <td>
                                <textarea name="Procedures" id="Procedures" style="resize:none;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">Closure</td>
                            <td>
                                <textarea name="Closure" id="Closure" style="resize:none;"></textarea>
                            </td>
                            <td style="text-align:right;">Skin Structure</td>
                            <td colspan=3>
                                <textarea name="Skin_Structure" id="Skin_Structure" style="resize:none;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">Drains</td>
                            <td colspan=7>
                                <textarea name="Drains" id="Drains" style="resize:none;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">Attachments</td>
                            <td colspan=7>
                                <input name="attachment[]" id="attachment" type="file" multiple="true" style="resize:none;width:100%;padding:10px" />
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <div style="width:100%;text-align:right;padding-right:10px;">
                        <input type="submit" value="SAVE" class='art-button-green' name="submit" id="submit">

                        <input type='hidden' name='submiopeartiveform' value='true'/>
                        <input type='hidden' name='submitthearteform' value='true'/>
                    </div>
                </fieldset>
                <?php
            } else {
                ?>
                <h1 style="color:red;text-align:center">No surgery for this patient</h1>
                <?php
            }
            ?>     
        </form>	
    </center>

    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
                                    $('#date_From').datetimepicker({
                                        dayOfWeekStart: 1,
                                        lang: 'en',
                                        startDate: 'now'
                                    });
                                    $('#date_From').datetimepicker({value: '', step: 1});

    </script>
    <!--End datetimepicker-->


    <?php
} else {
    ?>
    <fieldset style="width:95%;margin-top:10px;">
        <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
                <?php echo $Patient_Name . ",Patient_No-" . $Registration_ID . "," . $Guarantor_Name . "," . $Gender . "," . $Age; ?></b>
        </legend>
        <table  class='hiv_table' width='100%'>
            <tr>
                <td  colspan="7" style="text-align:center;">
                    <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <?php echo '<b>STATUS : </b>SURGERY ' . $Transaction_Status_Title; ?>
                </td>

            </tr>

        </table>
    </fieldset>

    <?php
}
include("./includes/footer.php");
?>
<script>
    function getItemListCacheID(ilcID) {
        //var ilcID=$(instance).attr('ilcID');//document.instance.getAttribute('ilcID');
        // alert(ilcID);
        document.getElementById('ilcIDValue').value = ilcID;

    }
</script>