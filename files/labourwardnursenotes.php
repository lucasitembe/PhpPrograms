<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $requisit_officer = $_SESSION['userinfo']['Employee_Name'];

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Admission_Works'])) {
            if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
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

    $Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("d F Y", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
        $Note_Date_Time = @date("d F Y",strtotime($Today));
    }
?>
<a href='labour_atenal_neonatal_record.php?patient_id=<?php echo $Registration_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>&consultation_ID=<?php echo $Consultation_ID; ?>&NurseCommunicationPage=NurseCommunicationPageThisPage' class='art-button-green'>BACK</a>
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
    $select = mysqli_query($conn,"select Admission_Date_Time from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Admission_Date_Time = @date("d F Y H:i:s",strtotime($row['Admission_Date_Time']));
        }
    }else{
        $Admission_Date_Time = '0000/00/00 00:00:00';
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

    //get previous detaills
    $Button_Title = 'SAVE INFORMATION';
    $Message = "Are you sure you want to save information?";
    $Successfully_Alert = 'Information Saved Successfully';
    $Date_Title = 'Date';
    $select = mysqli_query($conn,"select * from tbl_labour_ward_notes where
                            Admision_ID = '$Admision_ID' and
                            Registration_ID = '$Registration_ID' and
                            Consultation_ID = '$Consultation_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $Button_Title = 'UPDATE INFORMATION';
        $Message = "Are you sure you want to update information?";
        $Successfully_Alert = 'Information Updated Successfully';
        $Date_Title = 'Last Update';
        while($data = mysqli_fetch_array($select)){
            $Note_ID = $data['Note_ID'];
            $Note_Date_Time = $data['Note_Date_Time'];
            $Palpation = $data['Palpation'];
            $Presentation = $data['Presentation'];
            $Position = $data['Position'];
            $Contraction = $data['Contraction'];
            $Liquir = $data['Liquir'];
            $Colour = $data['Colour'];
            $Pv_Examination = $data['Pv_Examination'];
            $OS = $data['OS'];
            $Membrane = $data['Membrane'];
            $Temperature = $data['Temperature'];
            $Purse = $data['Purse'];
            $Respiration = $data['Respiration'];
            $BP = $data['BP'];
            $FHR = $data['FHR'];
            $Remarks = $data['Remarks'];
            $Exp = $data['Exp'];
            $BMI = $data['BMI'];
            $Note_Date_Time = @date("d F Y H:i:s",strtotime($data['Note_Date_Time']));
        }
    }else{
        $Note_ID = '';
        $Note_Date_Time = '';
        $Palpation = '';
        $Presentation = '';
        $Position = '';
        $Contraction = '';
        $Liquir = '';
        $Colour = '';
        $Pv_Examination = '';
        $OS = '';
        $Membrane = '';
        $Temperature = '';
        $Purse = '';
        $Respiration = '';
        $BP = '';
        $FHR = '';
        $Remarks = '';
        $Exp = '';
        $BMI = '';
        $Note_Date_Time = $Today;
    }
?>
<br/><br/>
<fieldset>
    <legend align="left"><b>LABOUR LOARD NURSES NOTES</b></legend>
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
            <td style="text-align: right;">Date Of Admission</td>
            <td><input type="text" value="<?php echo $Admission_Date_Time; ?>" readonly="readonly"></td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
            <td style="text-align: right;"><?php echo $Date_Title; ?></td>
            <td><input type="text" readonly="readonly" value="<?php echo $Note_Date_Time; ?>"></td>
            <td style="text-align: right;">Employee Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Employee_Name)); ?>"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <legend><b>GENERAL EXAMINATION</b></legend>
    <table width=100% style='border: 0px;'>
        <tr>
            <td style="text-align: right;" width="10%">Palpation</td>
            <td>
                <select id="Palpation" name="Palpation">
                    <option value="">~~ select Palpation ~~</option>
                    <option <?php if($Palpation == 'LONGITUDINAL'){ echo "selected='selected'"; } ?>>LONGITUDINAL</option>
                    <option <?php if($Palpation == 'TRANSVERSE'){ echo "selected='selected'"; } ?>>TRANSVERSE</option>
                    <option <?php if($Palpation == 'OBLIQUE'){ echo "selected='selected'"; } ?>>OBLIQUE</option>
                </select>
            </td>
            <td style="text-align: right;">Presentation</td>
            <td>
                <select id="Presentation" name="Presentation">
                    <option value="">~~ select Presentation ~~</option>
                    <option <?php if($Presentation == 'CEPHALIC'){ echo "selected='selected'"; } ?>>CEPHALIC</option>
                    <option <?php if($Presentation == 'BREECH'){ echo "selected='selected'"; } ?>>BREECH</option>
                    <option <?php if($Presentation == 'FACE'){ echo "selected='selected'"; } ?>>FACE</option>
                    <option <?php if($Presentation == 'BROW'){ echo "selected='selected'"; } ?>>BROW</option>
                </select>
            </td>
            <td style="text-align: right;">Position</td>
            <td>
                <select id="Position" name="Position">
                    <option value="">~~ select Position ~~</option>
                    <option <?php if($Position == 'ROA'){ echo "selected='selected'"; } ?>>ROA</option>
                    <option <?php if($Position == 'LOA'){ echo "selected='selected'"; } ?>>LOA</option>
                    <option <?php if($Position == 'OPP'){ echo "selected='selected'"; } ?>>OPP</option>
                    <option <?php if($Position == 'POPP'){ echo "selected='selected'"; } ?>>POPP</option>
                </select>
            </td>
            <td style="text-align: right;">Contraction</td>
            <td>
                <select id="Contraction" name="Contraction">
                    <option value="">~~ select Contraction ~~</option>
                    <option <?php if($Contraction == 'MILD'){ echo "selected='selected'"; } ?>>MILD</option>
                    <option <?php if($Contraction == 'MODERATE'){ echo "selected='selected'"; } ?>>MODERATE</option>
                    <option <?php if($Contraction == 'STRONG'){ echo "selected='selected'"; } ?>>STRONG</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">Liquir</td>
            <td>
                <select name="Liquir" id="Liquir" onchange="Update_Liquir()">
                    <option value="">~~ select Liquir ~~</option>
                    <option <?php if($Liquir == 'Clear'){ echo "selected='selected'"; } ?>>Clear</option>
                    <option <?php if($Liquir == 'Meconium Light'){ echo "selected='selected'"; } ?>>Meconium Light</option>
                    <option <?php if($Liquir == 'Meconium Thick'){ echo "selected='selected'"; } ?>>Meconium Thick</option>
                </select>
            </td>
            <td style="text-align: right;">PV Examination</td>
            <td>
                <select name="Pv_Examination" id="Pv_Examination">
                    <option value="">~~ select PV Examination ~~</option>
                    <option <?php if($Pv_Examination == 'Vulva Health'){ echo "selected='selected'"; } ?>>Vulva Health</option>
                    <option <?php if($Pv_Examination == 'Vaginal Warmth and roomy'){ echo "selected='selected'"; } ?>>Vaginal Warmth and roomy</option>
                    <option <?php if($Pv_Examination == 'Cervix - Thin And Well Applied Or Effeced'){ echo "selected='selected'"; } ?>>Cervix - Thin And Well Applied Or Effeced</option>
                </select>
            </td>
            <td style="text-align: right;">OS Size</td>
            <td>
                <select name="OS" id="OS">
                    <option value="">~~ select OS ~~</option>
                    <option <?php if($OS == '2cm Dilated'){ echo "selected='selected'"; } ?>>2cm Dilated</option>
                    <option <?php if($OS == '3cm Dilated'){ echo "selected='selected'"; } ?>>3cm Dilated</option>
                    <option <?php if($OS == '4cm Dilated'){ echo "selected='selected'"; } ?>>4cm Dilated</option>
                    <option <?php if($OS == '5cm Dilated'){ echo "selected='selected'"; } ?>>5cm Dilated</option>
                    <option <?php if($OS == '6cm Dilated'){ echo "selected='selected'"; } ?>>6cm Dilated</option>
                    <option <?php if($OS == '7cm Dilated'){ echo "selected='selected'"; } ?>>7cm Dilated</option>
                    <option <?php if($OS == '8cm Dilated'){ echo "selected='selected'"; } ?>>8cm Dilated</option>
                    <option <?php if($OS == '9cm Dilated'){ echo "selected='selected'"; } ?>>9cm Dilated</option>
                    <option <?php if($OS == '10cm Dilated'){ echo "selected='selected'"; } ?>>10cm Dilated</option>
                </select>
            </td>
            <td style="text-align: right;">Membrane</td>
            <td>
                <select name="Membrane" id="Membrane">
                    <option value="">~~ select Membrane ~~</option>
                    <option <?php if($Membrane == 'INTACT'){ echo "selected='selected'"; } ?>>INTACT</option>
                    <option <?php if($Membrane == 'RUPTURED'){ echo "selected='selected'"; } ?>>RUPTURED</option>
                </select>
            </td>
        </tr>
        <tr id="Liquir_Area">
        <?php
            if($Liquir == 'Meconium Light' || $Liquir == 'Meconium Thick'){
        ?>
            <td style="text-align: right;">Colour</td>
            <td>
                <select name="Colour" id="Colour">
                    <option value="">~~ Select Colour ~~</option>
                    <option value="Greenish_Plus" <?php if($Colour == 'Greenish_Plus'){ echo "selected='selected'"; } ?>>Greenish+</option>
                    <option value="Greenish_Plus2" <?php if($Colour == 'Greenish_Plus2'){ echo "selected='selected'"; } ?>>Greenish++</option>
                    <option value="Yellowish_Plus" <?php if($Colour == 'Yellowish_Plus'){ echo "selected='selected'"; } ?>>Yellowish+</option>
                    <option value="Yellowish_Plus2" <?php if($Colour == 'Yellowish_Plus2'){ echo "selected='selected'"; } ?>>Yellowish++</option>
                </select>
            </td>
        <?php
            }else{
                echo "<td>&nbsp;</td>";
            }
        ?>
        </tr>
    </table>
</fieldset>

<fieldset>
    <legend><b>VITAL SIGN, EXP AND REMARKS</b></legend>
    <table width="100%">
        <tr>
            <td style="text-align: right;" width="10%">Temperature</td>
            <td><input type="text" size="10" name="Temperature" id="Temperature" placeholder="Temperature" autocomplete="off" value="<?php echo $Temperature; ?>"></td>
            <td style="text-align: right;">Purse</td>
            <td><input type="text" size="10" name="Purse" id="Purse" placeholder="Purse" autocomplete="off" value="<?php echo $Purse; ?>"></td>
            <td style="text-align: right;">Respiration</td>
            <td><input type="text" size="10" name="Respiration" id="Respiration" placeholder="Respiration" autocomplete="off" value="<?php echo $Respiration; ?>"></td>
            <td style="text-align: right;">BP</td>
            <td><input type="text" size="10" name="BP" id="BP" placeholder="BP" autocomplete="off" value="<?php echo $BP; ?>"></td>
            <td style="text-align: right;">FHR</td>
            <td><input type="text" size="10" name="FHR" id="FHR" placeholder="FHR" autocomplete="off" value="<?php echo $FHR; ?>"></td>
            <td style="text-align: right;">BMI</td>
            <td><input type="text" size="10" name="BMI" id="BMI" placeholder="BMI"  autocomplete="off" value="<?php echo $BMI; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Exp</td>
            <td colspan="3">
                <input type="text" name="Exp" id="Exp" placeholder="Exp" autocomplete="off" value="<?php echo $Exp; ?>">
            </td>
            <td style="text-align: right;">Remarks</td>
            <td colspan="7">
                <textarea name="Remarks" id="Remarks" style="width: 100%; height: 40px;"><?php echo $Remarks; ?></textarea>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset id="Button_Area">
    <table width="100%">
        <tr>
            <td style="text-align: right">
            <?php if($Button_Title == 'UPDATE INFORMATION'){ ?>
                <button class="art-button-green" onclick="Preview()">PREVIEW REPORT</button>
            <?php }else{ ?>
                <button class="art-button-green" onclick="Preview_Alert()">PREVIEW REPORT</button>
            <?php } ?>
                <input type="button" name="Save" id="Save" value="<?php echo $Button_Title; ?>" class="art-button-green" onclick="Confirm_Save_Information()">
            </td>
        </tr>
    </table>
</fieldset>

<div id="Confirm_Save_Information">
    <?php echo $Message; ?>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="YES" onclick="Save_Information()">
                <input type="button" class="art-button-green" value="CANCEL" onclick="Close_Confirm_Dialog()">
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

<div id="Successfully_Alert">
    <?php echo $Successfully_Alert; ?>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview()">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Close_Successfully_Alert()">
            </td>
        </tr>
    </table>
</div>

<div id="Preview_Alert_Message">
    <center>No report found. Please fill and save all required areas</center>
</div>

<script type="text/javascript">
    function Close_Successfully_Alert(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        window.open("nursecommunicationpage.php?Registration_ID="+Registration_ID+"&Admision_ID="+Admision_ID+"&consultation_ID="+Consultation_ID+"&NurseCommunicationPage=NurseCommunicationPageThisPage","_parent")
    }
</script>

<script type="text/javascript">
    function Preview_Alert(){
        $("#Preview_Alert_Message").dialog("open");
    }
</script>

<script type="text/javascript">
    function Update_Liquir(){
        var Liquir = document.getElementById("Liquir").value;
        if(Liquir == 'Meconium Light' || Liquir == 'Meconium Thick'){
            if (window.XMLHttpRequest) {
                myObjectLiquir = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectLiquir = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectLiquir.overrideMimeType('text/xml');
            }

            myObjectLiquir.onreadystatechange = function () {
                dataLiquir = myObjectLiquir.responseText;
                if (myObjectLiquir.readyState == 4) {
                    document.getElementById('Liquir_Area').innerHTML = '<td style="text-align: right;">Colour</td><td><select name="Colour" id="Colour"><option value="">~~ Select Colour ~~</option><option value="Greenish_Plus">Greenish+</option><option value="Greenish_Plus2">Greenish++</option><option value="Yellowish_Plus">Yellowish+</option><option value="Yellowish_Plus2">Yellowish++</option></select></td>';
                }
            };
            myObjectLiquir.open('GET', 'Update_Liquir.php', true);
            myObjectLiquir.send();
        }else{
            document.getElementById('Liquir_Area').innerHTML = '<td>&nbsp;</td>';
        }
    }
</script>

<script type="text/javascript">
    function Confirm_Save_Information(){
        var Colour = '0';
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Palpation = document.getElementById("Palpation").value;
        var Presentation = document.getElementById("Presentation").value;
        var Position = document.getElementById("Position").value;
        var Contraction = document.getElementById("Contraction").value;
        var Liquir = document.getElementById("Liquir").value;
        var Pv_Examination = document.getElementById("Pv_Examination").value;
        var OS = document.getElementById("OS").value;
        var Membrane = document.getElementById("Membrane").value;
        var Temperature = document.getElementById("Temperature").value;
        var Purse = document.getElementById("Purse").value;
        var Respiration = document.getElementById("Respiration").value;
        var BP = document.getElementById("BP").value;
        var FHR = document.getElementById("FHR").value;
        var BMI = document.getElementById("BMI").value;
        var Exp = document.getElementById("Exp").value;
        var Remarks = document.getElementById("Remarks").value;
        if(Liquir == 'Meconium Light' || Liquir == 'Meconium Thick'){
            Colour = document.getElementById("Colour").value;
        }
        if(Consultation_ID != null && Consultation_ID != '' && Admision_ID != null && Admision_ID != '' && Palpation != null && Palpation != '' && Presentation != null && Presentation != '' && Position != null && Position != '' && Contraction != null && Contraction != '' && Liquir != null && Liquir != '' && Pv_Examination != null && Pv_Examination != '' && OS != null && OS != '' && Membrane != null && Membrane != '' && Temperature != null && Temperature != '' && Purse != null && Purse != '' && Respiration != null && Respiration != '' && BP != null && BP != '' && FHR != null && FHR != '' && Exp != null && Exp != '' && BMI != '' && BMI != null){
            document.getElementById("Palpation").style = 'border: 1px solid black;';
            document.getElementById("Presentation").style = 'border: 1px solid black;';
            document.getElementById("Position").style = 'border: 1px solid black;';
            document.getElementById("Contraction").style = 'border: 1px solid black;';
            document.getElementById("Liquir").style = 'border: 1px solid black;';
            document.getElementById("Pv_Examination").style = 'border: 1px solid black;';
            document.getElementById("OS").style = 'border: 1px solid black;';
            document.getElementById("Membrane").style = 'border: 1px solid black;';
            document.getElementById("Temperature").style = 'border: 1px solid black;';
            document.getElementById("Purse").style = 'border: 1px solid black;';
            document.getElementById("Respiration").style = 'border: 1px solid black;';
            document.getElementById("BP").style = 'border: 1px solid black;';
            document.getElementById("FHR").style = 'border: 1px solid black;';
            document.getElementById("Exp").style = 'border: 1px solid black;';
            document.getElementById("BMI").style = 'border: 1px solid black;';
            if((Liquir == 'Meconium Light' || Liquir == 'Meconium Thick') && (Colour == '' || Colour == null)){
                document.getElementById("Colour").style = 'border: 2px solid red;';
            }else{
                $("#Confirm_Save_Information").dialog("open");
                document.getElementById("Colour").style = 'border: 1px solid black;';
            }
        }else{            
            if (Palpation == '' || Palpation == null) { document.getElementById("Palpation").style = 'border: 2px solid red;';
            }else{ document.getElementById("Palpation").style = 'border: 1px solid black;'; }

            if (Presentation == '' || Presentation == null) { document.getElementById("Presentation").style = 'border: 2px solid red;';
            }else{ document.getElementById("Presentation").style = 'border: 1px solid black;'; }

            if (Position == '' || Position == null) { document.getElementById("Position").style = 'border: 2px solid red;';
            }else{ document.getElementById("Position").style = 'border: 1px solid black;'; }

            if (Contraction == '' || Contraction == null) { document.getElementById("Contraction").style = 'border: 2px solid red;';
            }else{ document.getElementById("Contraction").style = 'border: 1px solid black;'; }

            if (Liquir == '' || Liquir == null) { document.getElementById("Liquir").style = 'border: 2px solid red;';
            }else{ document.getElementById("Liquir").style = 'border: 1px solid black;'; }

            if (Pv_Examination == '' || Pv_Examination == null) { document.getElementById("Pv_Examination").style = 'border: 2px solid red;';
            }else{ document.getElementById("Pv_Examination").style = 'border: 1px solid black;'; }

            if (OS == '' || OS == null) { document.getElementById("OS").style = 'border: 2px solid red;';
            }else{ document.getElementById("OS").style = 'border: 1px solid black;'; }

            if (Membrane == '' || Membrane == null) { document.getElementById("Membrane").style = 'border: 2px solid red;';
            }else{ document.getElementById("Membrane").style = 'border: 1px solid black;'; }

            if (Temperature == '' || Temperature == null) { document.getElementById("Temperature").style = 'border: 2px solid red;';
            }else{ document.getElementById("Temperature").style = 'border: 1px solid black;'; }

            if (Purse == '' || Purse == null) { document.getElementById("Purse").style = 'border: 2px solid red;';
            }else{ document.getElementById("Purse").style = 'border: 1px solid black;'; }

            if (Respiration == '' || Respiration == null) { document.getElementById("Respiration").style = 'border: 2px solid red;';
            }else{ document.getElementById("Respiration").style = 'border: 1px solid black;'; }

            if (BP == '' || BP == null) { document.getElementById("BP").style = 'border: 2px solid red;';
            }else{ document.getElementById("BP").style = 'border: 1px solid black;'; }

            if (FHR == '' || FHR == null) { document.getElementById("FHR").style = 'border: 2px solid red;';
            }else{ document.getElementById("FHR").style = 'border: 1px solid black;'; }

            if (Exp == '' || Exp == null) { document.getElementById("Exp").style = 'border: 2px solid red;';
            }else{ document.getElementById("Exp").style = 'border: 1px solid black;'; }

            if (BMI == '' || BMI == null) { document.getElementById("BMI").style = 'border: 2px solid red;';
            }else{ document.getElementById("BMI").style = 'border: 1px solid black;'; }
        }
    }
</script>

<script type="text/javascript">
    function Save_Information(){
        var Colour = '0';
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Palpation = document.getElementById("Palpation").value;
        var Presentation = document.getElementById("Presentation").value;
        var Position = document.getElementById("Position").value;
        var Contraction = document.getElementById("Contraction").value;
        var Liquir = document.getElementById("Liquir").value;
        var Pv_Examination = document.getElementById("Pv_Examination").value;
        var OS = document.getElementById("OS").value;
        var Membrane = document.getElementById("Membrane").value;
        var Temperature = document.getElementById("Temperature").value;
        var Purse = document.getElementById("Purse").value;
        var Respiration = document.getElementById("Respiration").value;
        var BP = document.getElementById("BP").value;
        var FHR = document.getElementById("FHR").value;
        var BMI = document.getElementById("BMI").value;
        var Exp = document.getElementById("Exp").value;
        var Remarks = document.getElementById("Remarks").value;
        if(Liquir == 'Meconium Light' || Liquir == 'Meconium Thick'){
            Colour = document.getElementById("Colour").value;
        }
        if(Consultation_ID != null && Consultation_ID != '' && Admision_ID != null && Admision_ID != '' && Palpation != null && Palpation != '' && Presentation != null && Presentation != '' && Position != null && Position != '' && Contraction != null && Contraction != '' && Liquir != null && Liquir != '' && Pv_Examination != null && Pv_Examination != '' && OS != null && OS != '' && Membrane != null && Membrane != '' && Temperature != null && Temperature != '' && Purse != null && Purse != '' && Respiration != null && Respiration != '' && BP != null && BP != '' && FHR != null && FHR != '' && Registration_ID != '' && Registration_ID != null){
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
                        $("#Confirm_Save_Information").dialog("close");
                        $("#Successfully_Alert").dialog("open");
                    }else{
                        $("#Unsuccessfully_Alert").dialog("open");
                        $("#Confirm_Save_Information").dialog("close");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectSave.open('GET','Labour_Ward_Nurse_Notes_Save_Info.php?Consultation_ID='+Consultation_ID+'&Admision_ID='+Admision_ID+'&Palpation='+Palpation+'&Presentation='+Presentation+'&Position='+Position+'&Contraction='+Contraction+'&Liquir='+Liquir+'&Pv_Examination='+Pv_Examination+'&OS='+OS+'&Membrane='+Membrane+'&Temperature='+Temperature+'&Purse='+Purse+'&Respiration='+Respiration+'&BP='+BP+'&FHR='+FHR+'&Remarks='+Remarks+'&Registration_ID='+Registration_ID+'&Exp='+Exp+'&Colour='+Colour+'&BMI='+BMI,true);
            myObjectSave.send();
        }
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
        window.open("patientprogresspreview.php?Registration_ID="+Registration_ID+"&Admision_ID="+Admision_ID+"&Consultation_ID="+consultation_ID+"&Employee_ID="+Employee_ID+"&PatientProgressReport=PatientProgressReportThisPage","_blank");
    }
</script>

<script type="text/javascript">
    function Preview(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Consultation_ID = '<?php echo $Consultation_ID; ?>';
        window.open("labourwardnursenotesreport.php?Registration_ID="+Registration_ID+"&Admision_ID="+Admision_ID+"&Consultation_ID="+Consultation_ID+"&LabourWardNurseNotesReport=LabourWardNurseNotesReportThisReport","_blank");
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function(){
        $("#Confirm_Save_Information").dialog({ autoOpen: false, width:'35%',height:140, title:'LABOUR WARD NOTES',modal: true});
        $("#Unsuccessfully_Alert").dialog({ autoOpen: false, width:'35%',height:140, title:'LABOUR WARD NOTES',modal: true});
        $("#Successfully_Alert").dialog({ autoOpen: false, width:'35%',height:140, title:'LABOUR WARD NOTES',modal: true});
        $("#Preview_Alert_Message").dialog({ autoOpen: false, width:'40%',height:140, title:'LABOUR WARD NOTES',modal: true});
        $("#Preview_Details").dialog({ autoOpen: false, width:'90%',height:550, title:'LABOUR WARD NOTES',modal: true});
    });
</script>
<?php
    include("./includes/footer.php");
?>