<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $requisit_officer = $_SESSION['userinfo']['Employee_Name'];

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_SESSION['userinfo'])) {
//        if (isset($_SESSION['userinfo']['Admission_Works'])) {
//            if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//                header("Location: ./index.php?InvalidPrivilege=yes");
//            }
//        } else {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    if(isset($_GET['consultation_ID'])){
        $Consultation_ID = $_GET['consultation_ID'];
    }else{
        $Consultation_ID = 0;
    }


    if(isset($_GET['Admision_ID'])){
        $Admision_ID = $_GET['Admision_ID'];
    }else{
        $Admision_ID = 0;
    }


    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("d F Y", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
?>
<a href='nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>&consultation_ID=<?php echo $Consultation_ID; ?>&NurseCommunicationPage=NurseCommunicationPageThisPage' class='art-button-green'>BACK</a>

<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        /*background-color:#eeeeee;
        cursor:pointer;*/
    }
</style>
<?php
    if (isset($_GET['Registration_ID'])) {
        $select = mysqli_query($conn,"select Member_Number, Patient_Name, Registration_ID, Gender, Guarantor_Name, Date_Of_Birth
                                from tbl_patient_registration pr, tbl_sponsor sp where
                                pr.Registration_ID = '$Registration_ID' and
                                sp.Sponsor_ID = pr.Sponsor_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Member_Number = $row['Member_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
            }
            //generate patient age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        } else {
            $Member_Number = '';
            $Patient_Name = '';
            $Gender = '';
            $Registration_ID = 0;
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }

    //get admission date
    $select = mysqli_query($conn,"select Admission_Date_Time, Admission_Status from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Admission_Date_Time = @date("d F Y H:i:s",strtotime($row['Admission_Date_Time']));
            $Admission_Status = $row['Admission_Status'];
        }
    }else{
        $Admission_Date_Time = '0000/00/00 00:00:00';
        $Admission_Status = '';
    }

    //get final diagnosis
    $Disease_Name = '';

    //Outpatient diagnosis
    $select = mysqli_query($conn,"select Disease_Name from tbl_disease_consultation dc, tbl_disease d where
                            dc.disease_ID = d.disease_ID and
                            dc.Consultation_ID = '$Consultation_ID' and
                            dc.diagnosis_type = 'diagnosis' group by d.disease_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Disease_Name .= $data['Disease_Name'].'; ';
        }
    }

    //Inpatient diagnosis
    $select = mysqli_query($conn,"select Disease_Name from tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_disease d where
                            d.disease_ID = wrd.disease_ID and
                            wrd.diagnosis_type = 'diagnosis' and
                            wrd.Round_ID = wr.Round_ID and
                            wr.Consultation_ID = '$Consultation_ID' group by d.disease_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Disease_Name .= $data['Disease_Name'].'; ';
        }
    }
?>
<br/><br/>
<fieldset>
    <legend align="left"><b>NURSE NOTES</b></legend>
    <table width="100%">
        <tr>
            <td width="10%" style="text-align: right;">Patient Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Age</td>
            <td><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
            <td width="10%" style="text-align: right;">Gender</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Number</td>
            <td><input type="text" readonly="readonly" value="<?php echo $Registration_ID; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Diagnosis</td>
            <td colspan="3">
                <textarea style="width: 100%;" id="Diagnosis" name="Diagnosis" readonly="readonly"><?php echo $Disease_Name; ?></textarea>
            </td>
            <td style="text-align: right;">Date Of Admission</td>
            <td><input type="text" value="<?php echo $Admission_Date_Time; ?>" readonly="readonly"></td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <table width = 100%>
        <tr>
            <td style="text-align: right;">Notes Date</td>
            <td><input type="text" readonly="readonly" value="<?php echo $Today; ?>"></td>
            <td style="text-align: right;">Nurse Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Employee_Name)); ?>"></td>
        </tr>
        <tr>
            <td width="15%" style="text-align: right;">Notes / Comments</td>
            <td colspan="3"><textarea style="width: 100%; height: 200px;" id="Notes" name="Notes" placeholder="Notes / Comments"></textarea></td>
        </tr>
    </table>
</fieldset>
<fieldset id="Button_Area">
    <table width="100%">
    <?php
        //get previous info
        $select_records = mysqli_query($conn,"SELECT Notes_ID from tbl_nurse_notes where Consultation_ID = '$Consultation_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no_rec = mysqli_num_rows($select_records);
    ?>
        <tr>
            <td style="text-align: right">
                <button class="art-button-green" onclick="Preview()">PREVIOUS NOTES
                    &nbsp;&nbsp;
                    <span style='background-color: red; border-radius: 10px; color: white; padding: 8px;'><?php echo $no_rec; ?></span>
                    &nbsp;&nbsp;
                </button>
                <input type="button" name="Save" id="Save" value="SAVE" class="art-button-green" onclick="Confirm_Save_Information()">
            </td>
        </tr>
    </table>
</fieldset>

<div id="Confirm_Save_Information">
    Are you sure you want to save?
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="YES" onclick="Save_Information()">
                <input type="button" class="art-button-green" value="CANCEL" onclick="Close_Confirm_Dialog()">
            </td>
        </tr>
    </table>
</div>
<div id="Successfully_Alert">
    Information Saved Successfully
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW ALL" onclick="Preview_General_Report()">
                <input type="button" class="art-button-green" value="PREVIEW LAST REPORT" onclick="Preview_Information()">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Close_Successfully_Dialog()">
            </td>
        </tr>
    </table>
</div>
<div id="Unsuccessfully_Alert">
    Prosess Fail! Please try again.
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="TRY" onclick="Save_Information()">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Close_Unsuccessfully_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Preview_Details">
    
</div>

<script type="text/javascript">
    function Confirm_Save_Information(){
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        var Admision_ID = '<?php echo $Consultation_ID; ?>';
        var Notes = document.getElementById("Notes").value;

        if(Consultation_ID != null && Consultation_ID != '' && Admision_ID != null && Admision_ID != '' && Notes != null && Notes != ''){
            document.getElementById("Notes").style = 'border: 1px solid black; width: 100%; height: 200px;';
            $("#Confirm_Save_Information").dialog("open");
        }else{
            if (Notes == '' || Notes == null) {
                document.getElementById("Notes").focus();
                document.getElementById("Notes").style = 'border: 2px solid red; width: 100%; height: 200px;';
            }else{
                document.getElementById("Notes").style = 'border: 1px solid black; width: 100%; height: 200px;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Save_Information(){
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Notes = document.getElementById("Notes").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if(Consultation_ID != null && Consultation_ID != '' && Admision_ID != null && Admision_ID != '' && Notes != null && Notes != ''){
            if(window.XMLHttpRequest){
                myObjectSave = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectSave = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSave.overrideMimeType('text/xml');
            }

            myObjectSave.onreadystatechange = function (){
                dataSave = myObjectSave.responseText;
                if (myObjectSave.readyState == 4) {
                    var feedback = dataSave;
                    if(feedback == 'yes'){
                        document.getElementById("Notes").value = '';
                        Update_Buttons();
                        $("#Successfully_Alert").dialog("open");
                        $("#Confirm_Save_Information").dialog("close");
                        $("#Unsuccessfully_Alert").dialog("close");
                    }else{
                        $("#Unsuccessfully_Alert").dialog("open");
                        $("#Confirm_Save_Information").dialog("close");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectSave.open('GET','Save_Patient_Notes_Info.php?Consultation_ID='+Consultation_ID+'&Admision_ID='+Admision_ID+'&Notes='+Notes+'&Registration_ID='+Registration_ID,true);
            myObjectSave.send();
        }
    }
</script>

<script type="text/javascript">
    function Update_Buttons(){
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectUpdate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdate.overrideMimeType('text/xml');
        }

        myObjectUpdate.onreadystatechange = function () {
            dataUpdate = myObjectUpdate.responseText;
            if (myObjectUpdate.readyState == 4) {
                document.getElementById('Button_Area').innerHTML = dataUpdate;
            }
        };
        myObjectUpdate.open('GET', 'Patient_Notes_Update_Button.php?Registration_ID='+Registration_ID+"&Admision_ID="+Admision_ID, true);
        myObjectUpdate.send();
    }
</script>

<script type="text/javascript">
    function Close_Confirm_Dialog(){
        $("#Confirm_Save_Information").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Unsuccessfully_Dialog(){
        $("#Unsuccessfully_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Successfully_Dialog(){
        $("#Successfully_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Preview_Button(){
        $("#Preview_Details").dialog("close");
    }
</script>
<script type="text/javascript">
    function Preview_Information(){
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var consultation_ID = '<?php echo $Consultation_ID; ?>';
        window.open("patientnotespreview.php?Registration_ID="+Registration_ID+"&Admision_ID="+Admision_ID+"&Consultation_ID="+consultation_ID+"&Employee_ID="+Employee_ID+"&PatientNotesReport=PatientNotesReportThisPage","_blank");
    }
</script>

<script type="text/javascript">
    function Preview(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            dataPreview = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById('Preview_Details').innerHTML = dataPreview;
                $("#Preview_Details").dialog("open");
            }
        };
        myObjectPreview.open('GET', 'Patient_Notes_Preview.php?Registration_ID='+Registration_ID+"&Admision_ID="+Admision_ID+"&Consultation_ID="+Consultation_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Preview_General_Report(){
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        window.open("patientnotesgeneralreport.php?Registration_ID="+Registration_ID+"&Admision_ID="+Admision_ID+"&Consultation_ID="+Consultation_ID+"PatientNotesGeneralReport=PatientNotesGeneralReportThisPage","_blank");
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function(){
        $("#Confirm_Save_Information").dialog({ autoOpen: false, width:'35%',height:140, title:'NURSING NOTES',modal: true});
        $("#Successfully_Alert").dialog({ autoOpen: false, width:'45%',height:140, title:'NURSING NOTES',modal: true});
        $("#Unsuccessfully_Alert").dialog({ autoOpen: false, width:'35%',height:140, title:'NURSING NOTES',modal: true});
        $("#Preview_Details").dialog({ autoOpen: false, width:'90%',height:550, title:'NURSING NOTES',modal: true});
    });
</script>
<?php
    include("./includes/footer.php");
?>