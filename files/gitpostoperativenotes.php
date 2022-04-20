<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

//    if (isset($_SESSION['userinfo'])) {
//        if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
//            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
//                header("Location: ./index.php?InvalidPrivilege=yes");
//            }
//        } else {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        @session_destroy();
//        header("Location: ../index.php?InvalidPrivilege=yes");
//    }

    if (isset($_GET['Patient_Payment_ID'])) {
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    } else {
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $Patient_Payment_Item_List_ID = 0;
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Surgery = $new_Date;
    }

    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = $Today.' 00:00';
    }

    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = $Today.' 59:59';
    }

    if(isset($_GET['sectionpatnt'])){
        $sectionpatnt = $_GET['sectionpatnt'];
    }else{
        $sectionpatnt = '';
    }

    if(isset($_GET['Session'])){
        $Session = $_GET['Session'];
    }else{
        $Session = '';
    }

    if(isset($_GET['Sponsor'])){
        $Sponsor = $_GET['Sponsor'];
    }else{
        $Sponsor = 'all';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 0;
    }

    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
$Doctor_Comment="";
    //get Payment_Cache_ID and consultation_id
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID,ilc.Doctor_Comment from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $consultation_id = $data['consultation_id'];
            $consultation_id_to_use=$consultation_id;
            $Doctor_Comment = $data['Doctor_Comment'];
        }
    }else{
        $Payment_Cache_ID = 0;
        $consultation_id = 0;
    }

    echo "<a href='all_patient_file_link_station.php?Registration_ID=$Registration_ID&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=gitpostoperativenotes' target='_blank'class='art-button-green'>PATIENT FILE</a>";
    echo "<a href='previewuppergitscopereport.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PreviewUpperGitScopeReport=PreviewUpperGitScopeReportThisPage' class='art-button-green' target='_blank'>UPPER GIT SCOPE REPORT</a>";
    if(isset($_GET['Location']) && strtolower($_GET['Location']) == 'others'){
        echo "<a href='procedurepatientinfo.php?section=Procedure&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&Sub_Department_ID=".$Sub_Department_ID."' class='art-button-green'>BACK</a>";
        $Back_Link = "procedurepatientinfo.php?section=Procedure&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&Sub_Department_ID=".$Sub_Department_ID."&ProcedurePatientInfo=ProcedurePatientInfoThisPage";
    }else{
        if(strtolower($Session) == 'outpatient'){
            echo "<a href='proceduredocotorpatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."' class='art-button-green'>BACK</a>";
            $Back_Link = "proceduredocotorpatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&ProcedureDocotorPatientInfo=ProcedureDocotorPatientInfoThisPage";
        }else if(strtolower($Session) == 'inpatient'){
            echo "<a href='proceduredocotorpatientinfo.php?Session=Inpatient&sectionpatnt=doctor_with_patnt&Registration_id=".$Registration_ID."&consultation_ID=".$consultation_id."&ProcedureWorks=ProcedureWorksThisPage' class='art-button-green'>BACK</a>";
            $Back_Link = "proceduredocotorpatientinfo.php?Session=Inpatient&sectionpatnt=doctor_with_patnt&Registration_id=".$Registration_ID."&consultation_ID=".$consultation_id."&ProcedureWorks=ProcedureWorksThisPage";
        }else{
            echo "<a href='proceduredocotorpatientinfo.php?section=Procedure&sectionpatnt=doctor_with_patnt&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&Sub_Department_ID=".$Sub_Department_ID."&ProcedurePatientInfo=ProcedurePatientInfoThisPage' class='art-button-green'>BACK</a>";
            $Back_Link = "proceduredocotorpatientinfo.php?section=Procedure&sectionpatnt=doctor_with_patnt&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&Sub_Department_ID=".$Sub_Department_ID."&ProcedurePatientInfo=ProcedurePatientInfoThisPage";
        }
    }
    //get procedure name
    $select = mysqli_query($conn,"select Product_Name, ilc.Status from tbl_items i, tbl_item_list_cache ilc where
                            i.Item_ID = ilc.Item_ID and
                            ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($dt = mysqli_fetch_array($select)) {
            $Product_Name = $dt['Product_Name'];
            $Status = $dt['Status'];
        }
    }else{
        $Product_Name = '';
        $Status = '';
    }

    
    //get procedure dateils
    $get_details = mysqli_query($conn,"select * from tbl_git_post_operative_notes where
                                Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num_procedure_details = mysqli_num_rows($get_details);
    if($num_procedure_details > 0){
        while ($data = mysqli_fetch_array($get_details)) {
            $Git_Post_operative_ID = $data['Git_Post_operative_ID'];
            $Surgery_Date_Time = $data['Surgery_Date_Time'];
            $Surgery_Date = $data['Surgery_Date'];
            $Indication_Of_Procedure = $data['Indication_Of_Procedure'];
            $Comorbidities = $data['Comorbidities'];
            $consultation_ID = $data['consultation_ID'];
            $Management = $data['Management'];
            $Recommendations = $data['Recommendations'];
            $Medication_Used = $data['Medication_Used'];
            $Biopsy_Tailen = $data['Biopsy_Tailen'];
            $summary_of_assessment_bfr_procedure = $data['summary_of_assessment_bfr_procedure'];
            $Others=$data['Others'];
        }
    }else{
        $Git_Post_operative_ID = 0;
        $Surgery_Date = $Today;
        $Indication_Of_Procedure = '';
        $Comorbidities = '';
        $consultation_ID = '';
        $Management = '';
        $Recommendations = '';
        $Medication_Used = '';
        $Biopsy_Tailen = '';
	$Others='hhh';
        $summary_of_assessment_bfr_procedure="";
    }

    


    //get patient details
    $select = mysqli_query($conn,"select Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from 
                            tbl_patient_registration pr, tbl_sponsor sp where 
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Patient_Name = $data['Patient_Name'];
            $Gender = $data['Gender'];
            $Date_Of_Birth = $data['Date_Of_Birth'];
            $Guarantor_Name = $data['Guarantor_Name'];
            $Member_Number = $data['Member_Number'];
        }
    }else{
        $Patient_Name = '';
        $Gender = '';
        $Date_Of_Birth = '';
        $Guarantor_Name = '';
        $Member_Number = '';
    }

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $Age = $diff->y." Years, ";
    $Age .= $diff->m." Months, ";
    $Age .= $diff->d." Days";

    //get Git_Post_operative_ID
    $get_Ogd_Post_operative_ID = mysqli_query($conn,"select Git_Post_operative_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Ogd_Post_operative_ID);
    if($nm > 0){
        while ($dt = mysqli_fetch_array($get_Ogd_Post_operative_ID)) {
            $Git_Post_operative_ID = $dt['Git_Post_operative_ID'];
        }
    }else{
        $Git_Post_operative_ID = 0;
    }

    //get selected participants
    $Endoswrist_selected = '';
    $Anaesthesia_selected = '';

    if($num_procedure_details > 0){
        $Selected_Employee = mysqli_query($conn,"select Employee_Name, pop.Employee_Type from tbl_employee emp, tbl_git_post_operative_participant pop where
                                            emp.Employee_ID = pop.Employee_ID and
                                            pop.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
        $nmz = mysqli_num_rows($Selected_Employee);
        if($nmz > 0){
            while ($row = mysqli_fetch_array($Selected_Employee)) {
                $Employee_Type = $row['Employee_Type'];
                if($Employee_Type == 'Endoswrist'){
                    $Endoswrist_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Anaesthesia'){
                    $Anaesthesia_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }
            }
        }
    }
?>

<br/><br/>
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
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
</style>
<fieldset>
    <legend><b>OGD ~ UPPER GIT SCOPE</b></legend>
    <table width="100%"> 
        <tr><td  width="9%" style="text-align: right;">Patient Name</td>
        <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
        <td width="9%" style="text-align: right;">Sponsor Name</td>
        <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Gender</td>
        <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Age</td>
        <td><input type="text" value="<?php echo $Age; ?>" readonly="readonly"></td>
        <td style="text-align:right;" >Procedure Date</td>
        <td><input type="text" autocomplete="off" name="Procedure_Date" id="Procedure_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
        </tr>
        </table>
        <hr width="100%"/>

    <table width="100%">
        
            <!-- <td > -->
                <!-- <table class="table"> -->
            <tr width="100%">
                    <td style="width:13%; text-align:right" >RELEVANT CLINICAL DATA</td>
                        <td width='85%'>
                         <textarea readonly="readonly"> <?= $Doctor_Comment ?></textarea>
                    </td>
            </tr>
    </table>
    <table width="100%">
        
            
        <tr>
            <td width="100%">
               <b style="color:#FF0000">SUMMARY OF ASSESSMENT BEFORE THE PROCEDURE</b>
            </td>
        </tr>
            <tr>
               <td>
                 <textarea rows="4" class="form-control" id="summary_of_assessment_bfr_procedure" name="summary_of_assessment_bfr_procedure"placeholder="ENTER SUMMARY OF ASSESSMENT BEFORE THE     PROCEDURE"><?= $summary_of_assessment_bfr_procedure ?></textarea>
               </td>
            </tr>
</table>
<table width="100%">
        
                    <tr width="100%">
                        <td style="width:13%;text-align:right">PROVISIONAL DIAGNOSIS</td>
                        <td width="80%">
                            <?php 
                                $provisional_diagnosis="";
                                $sql_select_provisional_diagnosis_result=mysqli_query($conn,"SELECT disease_code,disease_name FROM tbl_disease d INNER JOIN tbl_disease_consultation dc ON d.disease_ID=dc.disease_ID WHERE dc.consultation_ID='$consultation_id_to_use'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_provisional_diagnosis_result)>0){
                                    while($provisional_diagn_rows=mysqli_fetch_assoc($sql_select_provisional_diagnosis_result)){
                                       $disease_code=$provisional_diagn_rows['disease_code'];
                                       $disease_name=$provisional_diagn_rows['disease_name'];
                                       $provisional_diagnosis.="$disease_name  <b>($disease_code)</b>,";
                                    }
                                }
                            ?>
                            <div class="well" style="background:#FFFFFF;width:100%">
                               <?= $provisional_diagnosis ?> 
                            </div>
                             
                        </td>
                    </tr>
                
    </table>
</fieldset>
<fieldset>
    <table width=100%>
        <tr>
            <td style="text-align:right;" width="10%">Procedure Name</td>
            <td width=70%>
                <textarea id='Procedure_Name' name='Procedure_Name' required='required' style="width: 100%;" readonly="readonly"><?php echo $Product_Name; ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;" width="10%">Comorbidities</td>
            <td><textarea id='Commobility' name='Commobility' style="width: 100%;" autocomplete="off" placeholder="Commobility"><?php echo $Comorbidities; ?></textarea></td>
        </tr>
        <tr>
            <td style="text-align: right;">Indication Of Procedure</td>
            <td>
                <select name="Indication_Of_Procedure" id="Indication_Of_Procedure" onchange="Update_Indication_Of_Procedure()" style="font-size: 14px;">
                    <option selected="selected"></option>
                    <option <?php if($Indication_Of_Procedure == 'Dysphasia / Odynophagia'){ echo "selected='selected'"; } ?>>Dysphasia / Odynophagia</option>
                    <option <?php if($Indication_Of_Procedure == 'Persistent nausea and vomiting'){ echo "selected='selected'"; } ?>>Persistent nausea and vomiting</option>
                    <option <?php if($Indication_Of_Procedure == 'Dyspepsia'){ echo "selected='selected'"; } ?>>Dyspepsia</option>
                    <option <?php if($Indication_Of_Procedure == 'Upper GI bleeding diagnosis / Sclerotherapy'){ echo "selected='selected'"; } ?>>Upper GI bleeding diagnosis / Sclerotherapy</option>
                    <option <?php if($Indication_Of_Procedure == 'Iron deficiency anaemia'){ echo "selected='selected'"; } ?>>Iron deficiency anaemia</option>
                    <option <?php if($Indication_Of_Procedure == 'Esophageal reflux'){ echo "selected='selected'"; } ?>>Esophageal reflux</option>
                    <option <?php if($Indication_Of_Procedure == 'Biopsy of lesions'){ echo "selected='selected'"; } ?>>Biopsy of lesions</option>
                    <option <?php if($Indication_Of_Procedure == 'Treatment of varies'){ echo "selected='selected'"; } ?>>Treatment of varies</option>
                    <option <?php if($Indication_Of_Procedure == 'Removal of foreign bodies'){ echo "selected='selected'"; } ?>>Removal of foreign bodies</option>
                    <option <?php if($Indication_Of_Procedure == 'Dilation'){ echo "selected='selected'"; } ?>>Dilation</option>
                    <option <?php if($Indication_Of_Procedure == 'Stenting'){ echo "selected='selected'"; } ?>>Stenting</option>
                   <option <?php if($Indication_Of_Procedure == 'Placement of feeding tube or drainage'){ echo "selected='selected'"; } ?>>Placement of feeding tube or drainage</option>
					<option <?php if($Indication_Of_Procedure == 'Others'){ echo "selected='selected'"; } ?>>Others</option>

				</select>
				<br />
            <input type='text' id='Others_val' name='Others_val' value='<?php echo $Others;?>' placeholder='Other Indication Of Procedure' style='display:none' oninput='Update_Indication_Of_Procedure()' onkeypress='Update_Indication_Of_Procedure()' onkeyup='Update_Indication_Of_Procedure()'>
			</td>
            <tr>
            <td style="text-align:right;">Endoscopist</td>
            <td >
                <textarea style="width: 100%; " name="Endoswrist" id="Endoswrist" readonly='readonly'><?php echo $Endoswrist_selected; ?>
                </textarea>
            </td>
        <!-- </tr> -->
        <!-- <tr> -->
            <td width="7%" style="text-align: right;"><input type='button' class='art-button-green' onclick="Select_Endoswrist()" value='LIST'></td>
        <tr>
            <td style="text-align: right;">Biopsy Taken</td>
            <td><textarea id='Biopsy_Tailen' name='Biopsy_Tailen' style="width: 100%;" placeholder="Biopsy Taken"><?php echo $Biopsy_Tailen; ?></textarea></td>
            
            <tr>
                <td style="text-align:right;">Anaesthesiologist</td>
            <td >
                <textarea style="width: 100%;" name="Anaesthesiologist" id="Anaesthesiologist" readonly='readonly'><?php echo $Anaesthesia_selected; ?></textarea>
            </td>
            <td width="7%" style="text-align: right;"><input type='button' class='art-button-green' onclick="Select_Anaesthesiologist()" value='LIST'></td>
        </tr>
        <tr>
            <td style="text-align: right;">Medication Used</td>
            <td>
                <input type="hidden" name="Management" id="Management" autocomplete="off" placeholder="Management" value="<?php echo $Management; ?>">
                <select name="Medication_Used" id="Medication_Used" onchange="Update_Medication_Used()" style="font-size: 20px;color:#000000!important" multiple>
                    <!--<option selected="selected"></option>-->
                    <option <?php if($Medication_Used == 'Xylocaine Spray') { echo "selected='selected'"; } ?>>Xylocaine Spray</option>
                    <option <?php if($Medication_Used == 'Propofol') { echo "selected='selected'"; } ?>>Propofol</option>
                    <option <?php if($Medication_Used == 'Others') { echo "selected='selected'"; } ?>>Others</option>
                    <option <?php if($Medication_Used == 'NIL') { echo "selected='selected'"; } ?>>NIL</option>
                    <option <?php if($Medication_Used == 'Diclofenac') { echo "selected='selected'"; } ?>>Diclofenac</option>
                    <option <?php if($Medication_Used == 'Flumazenil') { echo "selected='selected'"; } ?>>Flumazenil</option>
                    <option <?php if($Medication_Used == 'Dormicum') { echo "selected='selected'"; } ?>>Dormicum</option>
                </select>
            </td>
            <tr>
            <td style="text-align: right;">Performed By</td>
            <td>
                <input type="text" readonly="readonly" value="<?php echo $Employee_Name; ?>">
            </td>
        </tr>
        <!--<tr>
            <td style="text-align: right;">Recommendations</td>
            <td><textarea id='Recommendations' name='Recommendation' style="width: 100%; height: 20px;" placeholder="Recommendations"><?php echo $Recommendations; ?></textarea></td>            
             <td style="text-align:right;">Assistant</td>
            <td >
                <textarea style="width: 100%; height: 20px;" name="Assistant_Surgeon" id="Assistant_Surgeon" readonly='readonly'></textarea>
            </td>
            <td width="7%" style="text-align: right;"><input type='button' class='art-button-green' onclick="Select_Assistant_Surgeon()" value='LIST'></td> 
        </tr>-->
    </table>
</fieldset>
<fieldset>
    <table width="100%">
            <tr>
        <td width="10%">
            <table width="100%">
                <tr>
                    <td><b>FINDINGS</b></td>
                    <td width="30%">
                        <select name="Title" id="Title" onchange="Change_Description_Title()" style="font-size: 15px; width:100%">
                            <option>~~~ Select Title ~~~</option>
                            <optgroup label="Upper GIT">
                                <option>Normal</option>
                            </optgroup>
                            <optgroup label="OESOPHAGUS">
                                <option>Upper Part</option>
								<option>Middle Part</option>
                                <option>OG Junction</option>
                                <option>Hiatus Hernia</option>
                                <option>Other Lesion</option>
                            </optgroup>
                            <optgroup label="STOMACH">
                                <option>Cardia</option>
                                <option>Fundus</option>
                                <option>Body</option>
                                <option>Antrum</option>
                                <option>Pylorus</option>
                                <option>Cardia/Fundus/Body/Antrum/Pylorus</option>
                            </optgroup>
                            <optgroup label="DUODENUM">
                                <option>Duodenum - D1</option>
                                <option>Duodenum - D2</option>
                                <option>Duodenum - D3</option>
                            </optgroup>
                        </select>
                    </td>

                    <td align="right"><input type="button" name="Preview_Findings" id="Preview_Findings" value="PREVIEW FINDINGS" class="art-button-green" onclick="Preview_Findings()"></td>

                </tr>
            </table>
            <br/>
            <center>
                <!-- <table>
                    <tr>
                        <td><input type="button" name="Preview_Findings" id="Preview_Findings" value="PREVIEW FINDINGS" class="art-button-green" onclick="Preview_Findings()"></td>
                    </tr>
                </table> -->
            </center>
        </td>
		<tr>
        <td colspan="2">
            <table width="100%">
                <tr><td id="" style="text-align: left;"><h5><b id="Title_Area"></b></h5></td></tr>
                <tr>
                    <td width="80%" id="My_Textarea">
                        <textarea style="width: 100%; height: 90px; resize: none;" id="Default_Area" name="Default_Area" readonly="readonly"></textarea>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
        
    </table>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td width="10%" style="text-align:right"><b>DIAGNOSIS</b></td>
        <?php
            $selected_diagnosis = '';
            //get selected diagnosis disease
            $select_diagnosis = mysqli_query($conn,"select d.procedure_dignosis_code, d.procedure_dignosis_name, po.Diagnosis_ID 
										from tbl_procedure_diagnosis d, tbl_gti_post_operative_diagnosis po where
										d.procedure_diagnosis_id = po.procedure_diagnosis_id and
										po.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_diagnosis);
            if($num > 0){
                while ($dtz = mysqli_fetch_array($select_diagnosis)) {
                    $selected_diagnosis .= $dtz['procedure_dignosis_name'].'('.$dtz['procedure_dignosis_code'].');  ';
                }
            }
        ?>
            <td width="80%">
                <textarea id='Diagnosis' name='Diagnosis' style="width: 100%; height: 40px;" placeholder="Diagnosis"><?php echo $selected_diagnosis; ?></textarea>
            </td>
            <td width="7%" style="text-align: right;">
                <input type="button" name="Diagnosis_List" id="Diagnosis_List" value="LIST" class="art-button-green" onclick="Add_Diagnosis()">
            </td>
        </tr>
        <tr>
            <td style="text-align:right"><b>Endoscopic Procedures</b></td>
            <td style="text-align: right;" colspan="2">
                <textarea id='Recommendations' name='Recommendation' style="width: 100%;" placeholder="Management Recommendations"><?php echo $Recommendations; ?>
                </textarea>
            </td>
            
        </tr>
        <tr></tr>
        <tr>
            <td style="text-align: center; width:15%" colspan="3">
                <input type="button" style="width:15%" name="Submit" id="Submit" value="SAVE" class="art-button-green " onclick="Submit_Info()">
            </td>
        </tr>
    </table>
</fieldset>

<div id="Add_Diagnosis" style="width:50%;">
    <center id='Add_Diagnosis_Area'>
        
    </center>
</div>


<div id="Preview_Findings_Area">
    <center id="Findings_Area">

    </center>
</div>

<div id="Submit_Info">
    <center><b>Are you sure you want to save?</b></center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Save_Information()">
                <input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Select_Endoswrist">
    <center>
        <table width="100%">
            <tr>
                <td width="40%">
                    <input type="text" name="Endoswrist_Name" id="Endoswrist_Name" placeholder="~~~ ~~~ Enter Endoscopist Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Endoswrist()" oninput="Search_Endoswrist()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED ENDOSCOPISTS</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Endoswrist_Area'>
                                    <table width="100%">
                                    <?php
                                        $counter = 1;//emp.Employee_Job_Code = 'Endoscopist' and 
                                        $get_assistant_surgeon = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_privileges pri where 
                                                                                emp.Employee_ID = pri.Employee_ID and
                                                                                (emp.Employee_Job_Code = 'Endoscopist' or emp.Employee_Job_Code ='Endoscopist_and_Anaesthesiologist') and
                                                                                
                                                                                emp.Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                        $assistant_num = mysqli_num_rows($get_assistant_surgeon);
                                        if($assistant_num > 0){
                                            while ($data = mysqli_fetch_array($get_assistant_surgeon)) {
                                    ?>
                                                <tr>
                                                    <td width="10%">
                                                        <input type="radio" name="ASST" id="A<?php echo $counter; ?>" onclick="Get_Selected_Endoswrist('<?php echo $data['Employee_ID']; ?>')">
                                                    </td>
                                                    <td>
                                                        <label for="A<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                                    </td>
                                                </tr>
                                    <?php
                                                $counter++;
                                            }
                                        }
                                    ?>
                                    </table>
                                </fieldset>
                            </td>
                            <td width="60%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Endoswrist_Area'>
                                    <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;ENDOSWRIST NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>                                            
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected  Endoswrist
                                            $selected_endoswrist = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_git_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Git_Post_operative_ID = '$Git_Post_operative_ID' and
                                                                                pop.Employee_Type = 'Endoswrist' and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_assistant_surgeons = mysqli_num_rows($selected_endoswrist);
                                            if($num_selected_assistant_surgeons > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_endoswrist)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedEndoswrist" id="SelectedEndoswrist" value="REMOVE" class="art-button-green" onclick="Remove_Endoswrist(<?php echo $dtz['Participant_ID']; ?>)">
                                                    </td>
                                                </tr>
                                            <?php 
                                                }
                                            }
                                        ?>
                                        </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="2">
                    <input type="button" name="Close2" id="Close2" class="art-button-green" value="DONE" onclick="Close_Endoswrist_Dialog()">
                </td>
            </tr>
        </table>
    </center>
</div>

<div id="Select_Anaesthesiologist">
    <center>
        <table width="100%">
            <tr>
                <td width="40%">
                    <input type="text" name="Anaesthesiologist_Name" id="Anaesthesiologist_Name" placeholder="~~~ ~~~ Enter Anaesthesiologist Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Anaesthesiologist()" oninput="Search_Anaesthesiologist()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED ANAESTHESIOLOGIST</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Anaesthesiologist_Area'>
                                    <table width="100%">
                                    <?php
                                        $count = 1;
                                        $get_Anaesthesia = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where (Employee_Job_Code = 'Anaesthesiologist' or Employee_Job_Code = 'Endoscopist_and_Anaesthesiologist') and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                        $assistant_num = mysqli_num_rows($get_Anaesthesia);
                                        if($assistant_num > 0){
                                            while ($data = mysqli_fetch_array($get_Anaesthesia)) {
                                    ?>
                                                <tr>
                                                    <td width="10%">
                                                        <input type="radio" name="Anaes" id="An<?php echo $count; ?>" onclick="Get_Selected_Anaesthesiologist('<?php echo $data['Employee_ID']; ?>')">
                                                    </td>
                                                    <td>
                                                        <label for="An<?php echo $count; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                                    </td>
                                                </tr>
                                    <?php
                                                $count++;
                                            }
                                        }
                                    ?>
                                    </table>
                                </fieldset>
                            </td>
                            <td width="60%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Anaesthesiologist_Area'>
                                    <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;ANAESTHESIOLOGIST NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>                                            
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected  Anaesthesiologist
                                            $selected_Anaesthesiologist = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_git_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Git_Post_operative_ID = '$Git_Post_operative_ID' and
                                                                                pop.Employee_Type = 'Anaesthesia' and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_Anaesthesiologists = mysqli_num_rows($selected_Anaesthesiologist);
                                            if($num_selected_Anaesthesiologists > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_Anaesthesiologist)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedAnaesthesiologist" id="SelectedAnaesthesiologist" value="REMOVE" class="art-button-green" onclick="Remove_Anaesthesiologist(<?php echo $dtz['Participant_ID']; ?>)">
                                                    </td>
                                                </tr>
                                            <?php 
                                                }
                                            }
                                        ?>
                                        </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="2">
                    <input type="button" name="Close2" id="Close2" class="art-button-green" value="DONE" onclick="Close_Anaesthesiologist_Dialog()">
                </td>
            </tr>
        </table>
    </center>
</div>

<div id="Save_Info">
    <center>
        Procedure saved successfully<br/><br/>
    </center>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <?php
                    echo "<a href='previewuppergitscopereport.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PreviewUpperGitScopeReport=PreviewUpperGitScopeReportThisPage' class='art-button-green' target='_blank'>PREVIEW REPORT</a>";
                    echo "&nbsp;&nbsp;&nbsp;<input type='button' value='CLOSE' class='art-button-green' onclick='Close_Saved_Dialog()'>";
                ?>                
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    function Select_Endoswrist(){
        $("#Select_Endoswrist").dialog("open");
    }
</script>

<script type="text/javascript">
    function Submit_Info(){
        $("#Submit_Info").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Submit_Dialog(){
        $("#Submit_Info").dialog("close");
    }
</script>

<script type="text/javascript">
    function Select_Anaesthesiologist(){
        $("#Select_Anaesthesiologist").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Saved_Dialog(){
        window.open("<?php echo $Back_Link; ?>","_parent");
        //$("#Save_Info").dialog("close");
    }
</script>

<script type="text/javascript">
    function Search_Via_Disease_Code(){
        document.getElementById("disease_name").value = '';
        var Disease_Code = document.getElementById("disease_code").value;
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var subcategory_ID = document.getElementById("subcategory_ID").value;        

        if (window.XMLHttpRequest) {
            myObjectSearch1 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch1 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch1.overrideMimeType('text/xml');
        }

        myObjectSearch1.onreadystatechange = function () {
            data12 = myObjectSearch1.responseText;
            if (myObjectSearch1.readyState == 4) {
                document.getElementById('Disease_List_Area').innerHTML = data12;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch1.open('GET', 'Git_Search_Via_Disease_Code.php?subcategory_ID='+subcategory_ID+'&Disease_Code='+Disease_Code+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectSearch1.send();
    }
</script>

<script type="text/javascript">
    function Search_Via_Disease_Name(){
        var Disease_Name = document.getElementById("disease_name").value;
        document.getElementById("disease_code").value = '';
        var subcategory_ID = document.getElementById("subcategory_ID").value;
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectSearch2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch2.overrideMimeType('text/xml');
        }

        myObjectSearch2.onreadystatechange = function () {
            data13 = myObjectSearch2.responseText;
            if (myObjectSearch2.readyState == 4) {
                document.getElementById('Disease_List_Area').innerHTML = data13;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch2.open('GET', 'Git_Search_Via_Disease_Name.php?subcategory_ID='+subcategory_ID+'&Disease_Name='+Disease_Name+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectSearch2.send();
    }
</script>

<script type="text/javascript">
    function Search_Endoswrist(){
        var Doctror_Name = document.getElementById("Endoswrist_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Surgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Surgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Surgeon.overrideMimeType('text/xml');
        }

        myObject_Search_Surgeon.onreadystatechange = function (){
            data9887 = myObject_Search_Surgeon.responseText;
            if (myObject_Search_Surgeon.readyState == 4) {
                document.getElementById('Endoswrist_Area').innerHTML = data9887;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Surgeon.open('GET','Search_Endoscopist.php?Doctror_Name='+Doctror_Name,true);
        myObject_Search_Surgeon.send();
    }
</script>
<script type="text/javascript">
    function Search_Anaesthesiologist(){
        var Doctror_Name = document.getElementById("Anaesthesiologist_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Surgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Surgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Surgeon.overrideMimeType('text/xml');
        }

        myObject_Search_Surgeon.onreadystatechange = function (){
            data9887 = myObject_Search_Surgeon.responseText;
            if (myObject_Search_Surgeon.readyState == 4) {
                document.getElementById('Anaesthesiologist_Area').innerHTML = data9887;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Surgeon.open('GET','search_anathesia.php?Doctror_Name='+Doctror_Name,true);
        myObject_Search_Surgeon.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Anaesthesiologist(Employee_ID){
       
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        
//         alert(Patient_Payment_Item_List_ID);
        if(window.XMLHttpRequest){
            myObjectGetAnasthes = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetAnasthes = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetAnasthes.overrideMimeType('text/xml');
        }

        myObjectGetAnasthes.onreadystatechange = function (){
            dataAnaes = myObjectGetAnasthes.responseText;
            if (myObjectGetAnasthes.readyState == 4) {
                document.getElementById('Anaesthesiologist').value = dataAnaes;
                Refresh_Selected_Anaesthesiologist();
            }
        }; //specify name of function that will handle server response........
        myObjectGetAnasthes.open('GET','Get_Selected_Anaesthesiologist.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectGetAnasthes.send();
    }
</script>

<script type="text/javascript">
    function Preview_Findings(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            data990 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById('Findings_Area').innerHTML = data990;
                $("#Preview_Findings_Area").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreview.open('GET','Post_Operative_Git_Preview.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Selected_Anaesthesiologist(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshAnaes = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshAnaes = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshAnaes.overrideMimeType('text/xml');
        }

        myObjectRefreshAnaes.onreadystatechange = function (){
            dataRefreshAnaest = myObjectRefreshAnaes.responseText;
            if (myObjectRefreshAnaes.readyState == 4) {
                document.getElementById('Selected_Anaesthesiologist_Area').innerHTML = dataRefreshAnaest;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshAnaes.open('GET','Refresh_Selected_Anaesthesiologist.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshAnaes.send();
    }
</script>

<script type="text/javascript">
    function Remove_Anaesthesiologist(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveAnaest = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveAnaest = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveAnaest.overrideMimeType('text/xml');
        }

        myObjectRemoveAnaest.onreadystatechange = function (){
            dataRefreshAnaest2 = myObjectRemoveAnaest.responseText;
            if (myObjectRemoveAnaest.readyState == 4) {
                document.getElementById('Selected_Anaesthesiologist_Area').innerHTML = dataRefreshAnaest2;
                Refresh_Anaesthesiologist_Values();
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveAnaest.open('GET','Remove_Selected_Anaesthesiologist.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveAnaest.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Anaesthesiologist_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRegreshAnae3 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRegreshAnae3 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegreshAnae3.overrideMimeType('text/xml');
        }

        myObjectRegreshAnae3.onreadystatechange = function (){
            dataRefreshValueAnaes = myObjectRegreshAnae3.responseText;
            if (myObjectRegreshAnae3.readyState == 4) {
                document.getElementById('Anaesthesiologist').value = dataRefreshValueAnaes;
            }
        }; //specify name of function that will handle server response........
        myObjectRegreshAnae3.open('GET','Refresh_Anaesthesiologist_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRegreshAnae3.send();
    }
</script>

<script type="text/javascript">
    function Remove_Endoswrist(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveEndoswrist = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveEndoswrist = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveEndoswrist.overrideMimeType('text/xml');
        }

        myObjectRemoveEndoswrist.onreadystatechange = function (){
            dataRefreshEndoswrist = myObjectRemoveEndoswrist.responseText;
            if (myObjectRemoveEndoswrist.readyState == 4) {
                document.getElementById('Selected_Endoswrist_Area').innerHTML = dataRefreshEndoswrist;
                Refresh_Endoswrist_Values();
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveEndoswrist.open('GET','Remove_Selected_Endoswrist.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveEndoswrist.send();
    }
</script>


<script type="text/javascript">
    function Refresh_Endoswrist_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRegresh = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRegresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegresh.overrideMimeType('text/xml');
        }

        myObjectRegresh.onreadystatechange = function (){
            dataRefreshValue = myObjectRegresh.responseText;
            if (myObjectRegresh.readyState == 4) {
                document.getElementById('Endoswrist').value = dataRefreshValue;
            }
        }; //specify name of function that will handle server response........
        myObjectRegresh.open('GET','Refresh_Endoswrist_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRegresh.send();
    }    
</script>

<script type="text/javascript">
    function Close_Endoswrist_Dialog(){
        $("#Select_Endoswrist").dialog("close");
    }
</script>
<script type="text/javascript">
    function Close_Anaesthesiologist_Dialog(){
        $("#Select_Anaesthesiologist").dialog("close");
    }
</script>

<script type="text/javascript">
    function Get_Selected_Endoswrist(Employee_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetEndoswrist = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetEndoswrist = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetEndoswrist.overrideMimeType('text/xml');
        }

        myObjectGetEndoswrist.onreadystatechange = function (){
            dataEndoswrist = myObjectGetEndoswrist.responseText;
            if (myObjectGetEndoswrist.readyState == 4) {
                document.getElementById('Endoswrist').value = dataEndoswrist;
                Refresh_Selected_Endoswrist();
            }
        }; //specify name of function that will handle server response........
        myObjectGetEndoswrist.open('GET','Get_Selected_Endoswrist.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectGetEndoswrist.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Selected_Endoswrist(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshSurg = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshSurg = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshSurg.overrideMimeType('text/xml');
        }

        myObjectRefreshSurg.onreadystatechange = function (){
            dataRefreshSurgeon = myObjectRefreshSurg.responseText;
            if (myObjectRefreshSurg.readyState == 4) {
                document.getElementById('Selected_Endoswrist_Area').innerHTML = dataRefreshSurgeon;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshSurg.open('GET','Refresh_Selected_Endoswrist.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshSurg.send();
    }
</script>

<script type="text/javascript">
    function Save_Information(){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';

        var Procedure_Date = document.getElementById("Procedure_Date").value;
        var Indication_Of_Procedure = document.getElementById("Indication_Of_Procedure").value;
        var Biopsy_Tailen = document.getElementById("Biopsy_Tailen").value;
        var Management = document.getElementById("Management").value;
        var Commobility = document.getElementById("Commobility").value;
        var Medication_Used = document.getElementById("Medication_Used").value;
        var Recommendations = document.getElementById("Recommendations").value;
        var summary_of_assessment_bfr_procedure = document.getElementById("summary_of_assessment_bfr_procedure").value;

        var Date_From = '<?php echo $Date_From; ?>';
        var Date_To = '<?php echo $Date_To; ?>';
        var Sponsor = '<?php echo $Sponsor; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectUpdateAll = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateAll = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateAll.overrideMimeType('text/xml');
        }

        myObjectUpdateAll.onreadystatechange = function () {
            dataUpdateAll = myObjectUpdateAll.responseText;
            if (myObjectUpdateAll.readyState == 4) {
                //alert(dataUpdateAll)
                $("#Submit_Info").dialog("close");
                $("#Save_Info").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateAll.open('GET', 'Git_Update_All_Inputs.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Indication_Of_Procedure='+Indication_Of_Procedure+'&Patient_Payment_ID='+Patient_Payment_ID+'&Procedure_Date='+Procedure_Date+'&Biopsy_Tailen='+Biopsy_Tailen+'&Management='+Management+'&Commobility='+Commobility+'&Medication_Used='+Medication_Used+'&Recommendations='+Recommendations+"&summary_of_assessment_bfr_procedure="+summary_of_assessment_bfr_procedure, true);
        myObjectUpdateAll.send();
    }
</script>


<script type="text/javascript">
    function Update_Indication_Of_Procedure(){
		
		
		
        var Indication_Of_Procedure = document.getElementById("Indication_Of_Procedure").value;
		
		if(Indication_Of_Procedure=='Others'){
			document.getElementById('Others_val').style.display='inline';
			
		}else{
			document.getElementById('Others_val').style.display='none';
			document.getElementById('Others_val').value='';
		}
		
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
		var others=document.getElementById('Others_val').value;

        if (window.XMLHttpRequest) {
            myObjectUpdateIndication = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateIndication = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateIndication.overrideMimeType('text/xml');
        }

        myObjectUpdateIndication.onreadystatechange = function () {
            dataUpdateInd = myObjectUpdateIndication.responseText;
            if (myObjectUpdateIndication.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectUpdateIndication.open('GET', 'Git_Update_Indication_Of_Procedure.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Indication_Of_Procedure='+Indication_Of_Procedure+'&others='+others, true);
        myObjectUpdateIndication.send();
    }
</script>

<script type="text/javascript">
    function Update_Medication_Used(){
        var Medication_Used="";// = document.getElementById("Medication_Used").value;
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        var medication_used_array = [];
        $.each($("#Medication_Used option:selected"), function(){            
            medication_used_array.push($(this).val());
        });
        
      Medication_Used=medication_used_array.join();
//       
        if (window.XMLHttpRequest) {
            myObjectUpdateMedicationUsed = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateMedicationUsed = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateMedicationUsed.overrideMimeType('text/xml');
        }

        myObjectUpdateMedicationUsed.onreadystatechange = function () {
            dataUpdateMedication = myObjectUpdateMedicationUsed.responseText;
            if (myObjectUpdateMedicationUsed.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectUpdateMedicationUsed.open('GET', 'Git_Update_Medication_Used.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Medication_Used='+Medication_Used, true);
        myObjectUpdateMedicationUsed.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Disease(Patient_Payment_Item_List_ID,Payment_Item_Cache_List_ID,Registration_ID,disease_ID){
        if (window.XMLHttpRequest) {
            myObjectGetSelectedDisease = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetSelectedDisease = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetSelectedDisease.overrideMimeType('text/xml');
        }

        myObjectGetSelectedDisease.onreadystatechange = function () {
            dataGetDisease = myObjectGetSelectedDisease.responseText;
            if (myObjectGetSelectedDisease.readyState == 4) {
                document.getElementById("Selected_Disease_List_Area").innerHTML = dataGetDisease;
                Update_Selected_Diagnosis(Payment_Item_Cache_List_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectGetSelectedDisease.open('GET', 'Gti_Get_Selected_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&disease_ID='+disease_ID, true);
        myObjectGetSelectedDisease.send();
    }
</script>

<script type="text/javascript">
    function Remove_Disease(Diagnosis_ID){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectRemove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }

        myObjectRemove.onreadystatechange = function () {
            dataRemove = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
                document.getElementById("Selected_Disease_List_Area").innerHTML = dataRemove;
                Update_Selected_Diagnosis(Payment_Item_Cache_List_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectRemove.open('GET', 'Git_Remove_Selected_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Diagnosis_ID='+Diagnosis_ID, true);
        myObjectRemove.send();
    }
</script>

<script type="text/javascript">
    function Update_Selected_Diagnosis(Payment_Item_Cache_List_ID){
        if (window.XMLHttpRequest) {
            myObjectUpdateDiagnosis = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateDiagnosis = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateDiagnosis.overrideMimeType('text/xml');
        }

        myObjectUpdateDiagnosis.onreadystatechange = function () {
            dataUpdateDiagnosis = myObjectUpdateDiagnosis.responseText;
            if (myObjectUpdateDiagnosis.readyState == 4) {
                document.getElementById("Diagnosis").innerHTML = dataUpdateDiagnosis;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateDiagnosis.open('GET', 'Git_Update_Disease_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectUpdateDiagnosis.send();
    }
</script>

<script type="text/javascript">
    function Change_Description_Title(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        
//         alert(Registration_ID);
        
        var Previous_Title = document.getElementById("Title_Area").innerHTML;
        var Temp_ID = '';
        var Temp_Data = '';

        if(Previous_Title == 'Upper Part'){
            Temp_ID = 'Upper_Part';
            Temp_Data = document.getElementById("Upper_Part").value;
        }else if(Previous_Title == 'OG Junction'){
            Temp_ID = 'OG_Junction';
            Temp_Data = document.getElementById("OG_Junction").value;
        }else if(Previous_Title == 'Hiatus Hernia'){
            Temp_ID = 'Hiatus_Hernia';
            Temp_Data = document.getElementById("Hiatus_Hernia").value;
        }else if(Previous_Title == 'Other Lesion'){
            Temp_ID = 'Other_Lesion';
            Temp_Data = document.getElementById("Other_Lesion").value;
        }else if(Previous_Title == 'Cardia'){
            Temp_ID = 'Cardia';
            Temp_Data = document.getElementById("Cardia").value;
        }else if(Previous_Title == 'Fundus'){
            Temp_ID = 'Fundus';
            Temp_Data = document.getElementById("Fundus").value;
        }else if(Previous_Title == 'Body'){
            Temp_ID = 'Body';
            Temp_Data = document.getElementById("Body").value;
        }else if(Previous_Title == 'Antrum'){
            Temp_ID = 'Antrum';
            Temp_Data = document.getElementById("Antrum").value;
        }else if(Previous_Title == 'Pylorus'){
            Temp_ID = 'Pylorus';
            Temp_Data = document.getElementById("Pylorus").value;
        }else if(Previous_Title == 'Duodenum - D1'){
            Temp_ID = 'D1';
            Temp_Data = document.getElementById("D1").value;
        }else if(Previous_Title == 'Duodenum - D2'){
            Temp_ID = 'D2';
            Temp_Data = document.getElementById("D2").value;
        }else if(Previous_Title == 'Duodenum - D3'){
            Temp_ID = 'D3';
            Temp_Data = document.getElementById("D3").value;
        }else if(Previous_Title == 'Middle Part'){
			Temp_ID = 'Middle_Part';
            Temp_Data = document.getElementById("Middle_Part").value;
			
		}
		
		
                   
        var Title = document.getElementById("Title").value;
        if(Title != '~~~ Select Title ~~~'){
            document.getElementById("Title_Area").innerHTML = Title;
            if(Title == 'Upper Part'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Upper_Part" name="Upper_Part" oninput="Update_Content(\'Upper_Part\')" onkeypress="Update_Content(\'Upper_Part\')"></textarea>';
            }else if(Title == 'OG Junction'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="OG_Junction" name="OG_Junction" oninput="Update_Content(\'OG_Junction\')" onkeypress="Update_Content(\'OG_Junction\')"></textarea>';
            }else if(Title == 'Hiatus Hernia'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Hiatus_Hernia" name="Hiatus_Hernia" oninput="Update_Content(\'Hiatus_Hernia\')" onkeypress="Update_Content(\'Hiatus_Hernia\')"></textarea>';
            }else if(Title == 'Other Lesion'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Other_Lesion" name="Other_Lesion" oninput="Update_Content(\'Other_Lesion\')" onkeypress="Update_Content(\'Other_Lesion\')"></textarea>';
            }else if(Title == 'Cardia'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Cardia" name="Cardia" oninput="Update_Content(\'Cardia\')" onkeypress="Update_Content(\'Cardia\')"></textarea>';
            }else if(Title == 'Fundus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Fundus" name="Fundus" oninput="Update_Content(\'Fundus\')" onkeypress="Update_Content(\'Fundus\')"></textarea>';
            }else if(Title == 'Body'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Body" name="Body" oninput="Update_Content(\'Body\')" onkeypress="Update_Content(\'Body\')"></textarea>';
            }else if(Title == 'Antrum'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Antrum" name="Antrum" oninput="Update_Content(\'Antrum\')" onkeypress="Update_Content(\'Antrum\')"></textarea>';
            }else if(Title == 'Pylorus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Pylorus" name="Pylorus" oninput="Update_Content(\'Pylorus\')" onkeypress="Update_Content(\'Pylorus\')"></textarea>';
            }else if(Title == 'Duodenum - D1'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="D1" name="D1" oninput="Update_Content(\'D1\')" onkeypress="Update_Content(\'D1\')"></textarea>';
            }else if(Title == 'Duodenum - D2'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="D2" name="D2" oninput="Update_Content(\'D2\')" onkeypress="Update_Content(\'D2\')"></textarea>';
            }else if(Title == 'Duodenum - D3'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="D3" name="D3" oninput="Update_Content(\'D3\')" onkeypress="Update_Content(\'D3\')"></textarea>';
            }else if(Title == 'Middle Part'){
                    document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Middle_Part" name="Middle_Part" oninput="Update_Content(\'Middle_Part\')" onkeypress="Update_Content(\'Middle_Part\')"></textarea>';
		}else if(Title=="Normal"){
                        document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="upper_git_normal" name="upper_git_normal" oninput="Update_Content(\'upper_git_normal\')" onkeypress="Update_Content(\'upper_git_normal\')"></textarea>';
		          
                        }else if(Title=="Cardia/Fundus/Body/Antrum/Pylorus"){
                                                  document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Cardia_Fundus_Body_Antrum_Pylorus" name="Cardia_Fundus_Body_Antrum_Pylorus" oninput="Update_Content(\'Cardia_Fundus_Body_Antrum_Pylorus\')" onkeypress="Update_Content(\'Cardia_Fundus_Body_Antrum_Pylorus\')"></textarea>';
  
                        } else {
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Default_Area" name="Default_Area" oninput="Update_Content()" onkeypress="Update_Content()"></textarea>';
                document.getElementById("Default_Area").focus();
            }
        }else{
            document.getElementById("Title_Area").innerHTML = '';
            document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Default_Area" name="Default_Area" readonly="readonly")></textarea>';
        }
//          alert(Title);
        if (window.XMLHttpRequest) {
            myObjectUpdate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdate.overrideMimeType('text/xml');
        }

        myObjectUpdate.onreadystatechange = function () {
            dataUpdate = myObjectUpdate.responseText;
            if (myObjectUpdate.readyState == 4) {
                Get_Previous_Data();
            }
        }; //specify name of function that will handle server response........

        myObjectUpdate.open('GET', 'Git_Post_Operative_Update_Previous_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID, true);
        
       
        myObjectUpdate.send();
         console.log(dataUpdate);
    }
</script>

<script type="text/javascript">
    function Update_Content(Temp_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Temp_Data = document.getElementById(Temp_ID).value;
        
//          alert("jir");
        
//          alert(Payment_Item_Cache_List_ID);
		
        if (window.XMLHttpRequest) {
            myObjectAutoUpdate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAutoUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAutoUpdate.overrideMimeType('text/xml');
        }
       
        myObjectAutoUpdate.onreadystatechange = function () {
            dataAutoUpdate = myObjectAutoUpdate.responseText;
            if (myObjectAutoUpdate.readyState == 4) {
                //    alert(myObjectAutoUpdate.responseText)
            }
        }; //specify name of function that will handle server response........Cardia_Fundus_Body_Antrum_Pylorus

        myObjectAutoUpdate.open('GET', 'Git_Post_Operative_Auto_Update_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectAutoUpdate.send();
    }
</script>

<script type="text/javascript">
    function Get_Previous_Data(){
        var Title = document.getElementById("Title").value;

        if(Title == 'Upper Part'){
            Temp_ID = 'Upper_Part';
        }else if(Title == 'OG Junction'){
            Temp_ID = 'OG_Junction';
        }else if(Title == 'Hiatus Hernia'){
            Temp_ID = 'Hiatus_Hernia';
        }else if(Title == 'Other Lesion'){
            Temp_ID = 'Other_Lesion';
        }else if(Title == 'Cardia'){
            Temp_ID = 'Cardia';
        }else if(Title == 'Fundus'){
            Temp_ID = 'Fundus';
        }else if(Title == 'Body'){
            Temp_ID = 'Body';
        }else if(Title == 'Antrum'){
            Temp_ID = 'Antrum';
        }else if(Title == 'Pylorus'){
            Temp_ID = 'Pylorus';
        }else if(Title == 'Duodenum - D1'){
            Temp_ID = 'D1';
        }else if(Title == 'Duodenum - D2'){
            Temp_ID = 'D2';
        }else if(Title == 'Duodenum - D3'){
            Temp_ID = 'D3';
        }else if(Title=='Middle Part'){
			Temp_ID='Middle_Part';
		}else if(Title=='Normal'){
                    Temp_ID='upper_git_normal';
                }else if(Title=='Cardia/Fundus/Body/Antrum/Pylorus'){
                    Temp_ID='Cardia_Fundus_Body_Antrum_Pylorus';
                }

        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        
        if (window.XMLHttpRequest) {
            myObjectGetPrevious = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPrevious.overrideMimeType('text/xml');
        }

        myObjectGetPrevious.onreadystatechange = function () {
            PreviousData = myObjectGetPrevious.responseText;
            if (myObjectGetPrevious.readyState == 4) {
                document.getElementById(Temp_ID).value = PreviousData;
                document.getElementById(Temp_ID).focus();
            }
        }; //specify name of function that will handle server response........

        myObjectGetPrevious.open('GET', 'Git_Post_Operative_Get_Previous_Contents.php?Temp_ID='+Temp_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectGetPrevious.send();
    }
</script>
<script>
    function Get_Selected_Diagnosis(procedure_diagnosis_id,Git_Post_operative_ID){
        $.ajax({
            type:'GET',
            url:'add_selected_procedure_diagnosis.php',
            data:{procedure_diagnosis_id:procedure_diagnosis_id,Git_Post_operative_ID:Git_Post_operative_ID},
            success:function(data){
               $("#Selected_Diagnosis_List_Area").html(data);
            }
        });
    }
    function Search_Via_Diagnosis_Code(Git_Post_operative_ID){
       var procedure_dignosis_code=$("#procedure_dignosis_code").val();
       $.ajax({
            type:'GET',
            url:'Search_Via_Diagnosis_Code_or_Via_Diagnosis_Name.php',
            data:{procedure_dignosis_code:procedure_dignosis_code,Git_Post_operative_ID:Git_Post_operative_ID},
            success:function(data){
               $("#diagnosis_display_area").html(data);
            }
        }); 
    }
    function Search_Via_Diagnosis_Name(Git_Post_operative_ID){
       var procedure_dignosis_name=$("#procedure_dignosis_name").val();
       $.ajax({
            type:'GET',
            url:'Search_Via_Diagnosis_Code_or_Via_Diagnosis_Name.php',
            data:{procedure_dignosis_name:procedure_dignosis_name,Git_Post_operative_ID:Git_Post_operative_ID},
            success:function(data){
               $("#diagnosis_display_area").html(data);
            }
        });
    }
    function Remove_Diagnosis(Diagnosis_ID,Git_Post_operative_ID){
      //  alert(Git_Post_operative_ID)
       $.ajax({
            type:'GET',
            url:'remove_selected_procedure_diagnosis.php',
            data:{Diagnosis_ID:Diagnosis_ID,Git_Post_operative_ID:Git_Post_operative_ID},
            success:function(data){
               $("#Selected_Diagnosis_List_Area").html(data);
            }
        }); 
    }
</script>
<script type="text/javascript">
    function Close_Diagnosis_Dialog(){
        $("#Add_Diagnosis").dialog("close");
        var Git_Post_operative_ID = '<?php echo $Git_Post_operative_ID; ?>';
        $.ajax({
            type:'POST',
            url:'refresh_procedure_diagnosis.php',
            data:{Git_Post_operative_ID:Git_Post_operative_ID},
            success:function(data){
               $("#Diagnosis").html(data); 
            },
            error:function (x,y,z){
                $("#Diagnosis").html(z); 
            }
        });
    }
</script>

<script type="text/javascript">
    function Add_Diagnosis(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectDiag = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDiag = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDiag.overrideMimeType('text/xml');
        }

        myObjectDiag.onreadystatechange = function () {
            dataDiag = myObjectDiag.responseText;
            if (myObjectDiag.readyState == 4) {
                document.getElementById('Add_Diagnosis_Area').innerHTML = dataDiag;
                $("#Add_Diagnosis").dialog("open");
            }
        }; //specify name of function that will handle server response........Diagnosis

        myObjectDiag.open('GET', 'GIT_Add_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID, true);
        myObjectDiag.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function () {
        $("#Add_Diagnosis").dialog({autoOpen: false, width: '80%', height: 500, title: 'Diagnosis', modal: true});
        $("#Submit_Info").dialog({autoOpen: false, width: '30%', height: 150, title: 'SAVE INFORMATION', modal: true});
        $("#Save_Info").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0', modal: true});
        $("#Select_Endoswrist").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Endoscopist', modal: true});
        $("#Select_Anaesthesiologist").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Anaesthesiologist', modal: true});
        $("#Preview_Findings_Area").dialog({autoOpen: false, width: '60%', height: 450, title: 'PREVIEW FINDINGS', modal: true});
		
        $("select").select2();
		var Indication_Of_Procedure=$('#Indication_Of_Procedure').val();
		if(Indication_Of_Procedure=='Others'){
			$('#Others_val').show();
		}
		
    });
</script>

<?php
    include("./includes/footer.php");
?>
