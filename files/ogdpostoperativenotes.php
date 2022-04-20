<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

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
    
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }

    //Get Patient_Payment_Item_List_ID
    //get Payment_Cache_ID
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $consultation_id = $data['consultation_id'];
        }
    }else{
        $Payment_Cache_ID = 0;
        $consultation_id = 0;
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

    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $Patient_Payment_Item_List_ID = 0;
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


    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
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

    if(isset($Sponsor)){
        $Sponsor = $_GET['Sponsor'];
    }else{
        $Sponsor = 'All';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '0';
    }



    echo "<a href='#' class='art-button-green'>PATIENT FILE</a>";
    echo '<input type="button" name="Report_Button" id="Report_Button" value="COLONOSCOPY REPORT" class="art-button-green" onclick="Preview_Colonoscopy_Report()">';
    //echo "<a href='previewcolonoscopyreport.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PreviewColonoscopyReport=PreviewColonoscopyReportThisPage' class='art-button-green' target='_blank'>COLONOSCOPY REPORT</a>";
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
            $Back_Link = "proceduredocotorpatientinfo.php?section=Procedure&Registration_id=".$Registration_ID."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&Sub_Department_ID=".$Sub_Department_ID."&ProcedurePatientInfo=ProcedurePatientInfoThisPage";
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
    $get_details = mysqli_query($conn,"select Surgery_Date_Time, Ogd_Post_operative_ID,Others ,Indication_Of_Procedure, Quality_Of_Bowel, consultation_ID, Surgery_Date, Endoscorpic_Internvention,
                                Endoscorpic_Internvention, Type_And_Dose, Adverse_Event_Resulting, Management_Recommendation, Extent_Of_Examination, Commobility
                                from tbl_ogd_post_operative_notes where
                                Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num_procedure_details = mysqli_num_rows($get_details);
    if($num_procedure_details > 0){
        while ($data = mysqli_fetch_array($get_details)) {
            $Ogd_Post_operative_ID = $data['Ogd_Post_operative_ID'];
            $Surgery_Date_Time = $data['Surgery_Date_Time'];
            $Surgery_Date = $data['Surgery_Date'];
            $Indication_Of_Procedure = $data['Indication_Of_Procedure'];
            $Quality_Of_Bowel = $data['Quality_Of_Bowel'];
            $consultation_ID = $data['consultation_ID'];
            $Endoscorpic_Internvention = $data['Endoscorpic_Internvention'];
            $Type_And_Dose = $data['Type_And_Dose'];
            $Adverse_Event_Resulting = $data['Adverse_Event_Resulting'];
            $Management_Recommendation = $data['Management_Recommendation'];
            $Extent_Of_Examination = $data['Extent_Of_Examination'];
            $Commobility = $data['Commobility'];
			$Others=$data['Others'];
        }
    }else{
        $Ogd_Post_operative_ID = 0;
        $Surgery_Date = $Today;
        $Indication_Of_Procedure = '';
        $Quality_Of_Bowel = '';
        $consultation_ID = '';
        $Endoscorpic_Internvention = '';
        $Type_And_Dose = '';
        $Adverse_Event_Resulting = '';
        $Management_Recommendation = '';
        $Extent_Of_Examination = '';
        $Commobility = '';
		$Others='';
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

    //get Ogd_Post_operative_ID
    $get_Ogd_Post_operative_ID = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Ogd_Post_operative_ID);
    if($nm > 0){
        while ($dt = mysqli_fetch_array($get_Ogd_Post_operative_ID)) {
            $Ogd_Post_operative_ID = $dt['Ogd_Post_operative_ID'];
        }
    }else{
        $Ogd_Post_operative_ID = 0;
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
    <legend><b>OGD ~ COLONOSCOPY</b></legend>
    <table width="100%"> 
        <td  width="9%" style="text-align: right;">Patient Name</td>
        <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
        <td width="9%" style="text-align: right;">Sponsor Name</td>
        <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Gender</td>
        <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Age</td>
        <td><input type="text" value="<?php echo $Age; ?>" readonly="readonly"></td>
    </table>
</fieldset>
<fieldset>
    <table width=100%>
        <tr>
            <td style="text-align:right;" width="12%">Procedure Name</td>
            <td width=30%>
                <textarea id='Procedure_Name' name='Procedure_Name' required='required' style="width: 100%; height: 20px;" readonly="readonly"><?php echo $Product_Name; ?></textarea>
            </td>
            <td style="text-align:right;" >Procedure Date</td>
            <td><input type="text" autocomplete="off" name="Surgery_Date" id="Surgery_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
            <td style="text-align: right;">Comorbidity</td>
            <td><input type="text" name="Commobility" id="Commobility" autocomplete="off" placeholder="Comorbidity" value="<?php echo $Commobility; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Indication Of Procedure</td>
            <td>
                <select name="Indication_Of_Procedure" id="Indication_Of_Procedure" onchange="Update_Indication_Of_Procedure()" style="font-size: 14px;">
                    <option selected="selected"></option>
                    <option <?php if($Indication_Of_Procedure == 'Iron deficit anemia') { echo "selected='selected'"; } ?>>Iron deficit anemia</option>
                    <option <?php if($Indication_Of_Procedure == 'Hematochezia') { echo "selected='selected'"; } ?>>Hematochezia</option>
                    <option <?php if($Indication_Of_Procedure == 'Uncomplicated lower abdominal pain of at least 2 months duration') { echo "selected='selected'"; } ?>>Uncomplicated lower abdominal pain of at least 2 months duration</option>
                    <option <?php if($Indication_Of_Procedure == 'Change bowel habits (Predominant). Compilation of at least 2 months duration') { echo "selected='selected'"; } ?>>Change bowel habits (Predominant). Compilation of at least 2 months duration</option>
                    <option <?php if($Indication_Of_Procedure == 'Uncomplicated diarrhea') { echo "selected='selected'"; } ?>>Uncomplicated diarrhea</option>
                    <option <?php if($Indication_Of_Procedure == 'Evolution of known UC And CD') { echo "selected='selected'"; } ?>>Evolution of known UC And CD</option>
                    <option <?php if($Indication_Of_Procedure == 'Screening of colonel cancer in patient with UC/CD') { echo "selected='selected'"; } ?>>Screening of colonel cancer in patient with UC/CD</option>
                   <option <?php if($Indication_Of_Procedure == 'Survellum off colon polypedum') { echo "selected='selected'"; } ?>>Survellum off colon polypedum</option>
				   <option <?php if($Indication_Of_Procedure == 'Survellum off colon polypedum') { echo "selected='selected'"; } ?>>Survellum off colon polypedum</option>
					<option <?php if($Indication_Of_Procedure == 'Others') { echo "selected='selected'"; } ?>>Others</option>
                
					
				</select>
				
					<br />
				<input type='text' id='Others_val' name='Others_val' value='<?php echo $Others;?>' placeholder='Other Indication Of Procedure' style='display:none' oninput='Update_Indication_Of_Procedure()' onkeypress='Update_Indication_Of_Procedure()' onkeyup='Update_Indication_Of_Procedure()'>
		
            </td>
            <td style="text-align: right;">Type & dose of sedation</td>
            <td><input type="text" name="Dose_Of_Sedation" id="Dose_Of_Sedation" autocomplete="off" placeholder="Type & Dose Of Sedation" value="<?php echo $Type_And_Dose; ?>"></td>
            <td style="text-align: right;">Extent Of Examination</td>
            <td><input type="text" name="Extent_Of_Examination" id="Extent_Of_Examination" autocomplete="off" placeholder="Extent Of Examination" value="<?php echo $Extent_Of_Examination; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Quality Of Bowel Preparation</td>
            <td>
                <select name="Quality_Of_Bowel" id="Quality_Of_Bowel" onchange="Update_Quality_Of_Bowel()" style="font-size: 17px;">
                    <option selected="selected"></option>
                    <option <?php if($Quality_Of_Bowel == 'Adequate'){ echo "selected='selcted'"; } ?>>Adequate</option>
                    <option <?php if($Quality_Of_Bowel == 'Fair'){ echo "selected='selcted'"; } ?>>Fair</option>
                    <option <?php if($Quality_Of_Bowel == 'Poorly'){ echo "selected='selcted'"; } ?>>Poorly</option>
                    <option <?php if($Quality_Of_Bowel == 'Not Applicable'){ echo "selected='selcted'"; } ?>>Not Applicable</option>
                </select>
            </td>
            <td style="text-align: right;">Adverse event resulting Intervention</td>
            <td colspan="3"><input type="text" name="Adverse_Event" id="Adverse_Event" autocomplete="off" placeholder="Adverse event resulting Intervention" value="<?php echo $Adverse_Event_Resulting; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Endoscorpic Internvention Done</td>
            <td><textarea id='Endoscorpic_internvention' name='Endoscorpic_internvention' style="width: 100%; height: 20px;" placeholder="Endoscorpic Internvention Done"><?php echo $Endoscorpic_Internvention; ?></textarea></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <table width="100%">
    <tr>
        <td width="10%">
            <table width="100%">
                <tr>
                    <td><b>FINDINGS</b></td>
                    <td>
                        <select name="Title" id="Title" onchange="Change_Description_Title()" style="font-size: 20px;">
                            <option>~~~ Select Title ~~~</option>
                            <option value="Anal Lesions">Anal Lesions</option>
							<option value="PR">PR</option>
                            <option value="Haemoral">Haemorroids</option>
                            <option value="Rectum">Rectum</option>
							<option value="Sigmoid Colon">Sigmoid Colon</option>
							<option value="Descending Colon">Descending Colon</option>
							<option value="Splenic Flexure">Splenic Flexure</option>
							<option value="Transverse Colon">Transverse Colon</option>
							 <option value="Hepatic Flexure">Hepatic Flexure</option>
                            <option value="Ascending Colon">Ascending Colon</option>
                            <option value="Caecum">Caecum</option>
                            <option value="Terminal Ileum">Terminal Ileum</option>
                        </select>
                    </td>
                </tr>
            </table>
            <br/>
            <center>
                <table>
                    <tr>
                        <td><input type="button" name="Preview_Findings" id="Preview_Findings" value="PREVIEW FINDINGS" class="art-button-green" onclick="Preview_Findings()"></td>
                    </tr>
                </table>
            </center>
        </td>
        <td>
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
    <tr>
        <td width="5%" style="text-align: right;"><b>DIAGNOSIS</b></td>
        <?php
            $selected_diagnosis = '';
            //get selected diagnosis disease
            $select_diagnosis = mysqli_query($conn,"select d.disease_code, d.disease_name, d.disease_ID 
                                    from tbl_disease d, tbl_ogd_post_operative_diagnosis po where
                                    d.disease_ID = po.Disease_ID and
                                    po.Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_diagnosis);
            if($num > 0){
                while ($dtz = mysqli_fetch_array($select_diagnosis)) {
                    $selected_diagnosis .= $dtz['disease_name'].'('.$dtz['disease_code'].');  ';
                }
            }
        ?>
        <td style="text-align: right" colspan="">
            <table width="100%">
                <tr>
                    <td>
                        <textarea id='Diagnosis' name='Diagnosis' style="width: 100%; height: 40px;" placeholder="Diagnosis" readonly="readonly"><?php echo $selected_diagnosis; ?></textarea>
                    </td>
                    <td width="10%" style="text-align: right;">
                        <input type="button" name="Diagnosis_List" id="Diagnosis_List" value="LIST" class="art-button-green" onclick="Add_Diagnosis()">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;" width="19%"><b>MANAGEMENT RECOMMENDATIONS</b></td>
        <td style="text-align: right" colspan="">
            <table width="100%">
                <tr>
                    <td><textarea id='Management_recommendation' name='Management_recommendation' style="width: 100%; height: 20px;" placeholder="Management recommendation"><?php echo $Management_Recommendation; ?></textarea></td>
                    <td width="10%" style="text-align: right;">
                        <input type="button" name="Submit" id="Submit" value="SAVE" class="art-button-green" onclick="Submit_Info()">                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
</fieldset>

<div id="Saved_Info">
    <center>
        Procedure saved successfully<br/><br/>
    </center>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <?php
                    echo "<input type='button' value='PREVIEW REPORT' class='art-button-green' onclick='Preview_Colonoscopy_Report()'>";
                    echo "&nbsp;&nbsp;&nbsp;<input type='button' value='CLOSE' class='art-button-green' onclick='Close_Saved_Dialog()'>";
                ?>                
            </td>
        </tr>
    </table>
</div>

<div id="Add_Diagnosis" style="width:50%;">
    <center id='Add_Diagnosis_Area'>
        
    </center>
</div>

<div id="Submit_Info">
    <center><b>Are you sure you want to save?</b></center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Submit_Colonoscopy()">
                <input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Preview_Findings_Area">
    <center id="Findings_Area">

    </center>
</div>

<div id="Preview_Warning">
    <center>
        No report generated please save information first
    </center>
</div>

<script type="text/javascript">
    function Preview_Colonoscopy_Report(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function () {
            dataVerify = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = dataVerify;
                if(feedback == 'yes'){
                    window.open("previewcolonoscopyreport.php?Registration_ID="+Registration_ID+"&Patient_Payment_ID="+Patient_Payment_ID+"&Patient_Payment_Item_List_ID="+Patient_Payment_Item_List_ID+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID+"&PreviewColonoscopyReport=PreviewColonoscopyReportThisPage","_blank");
                }else{
                    $("#Preview_Warning").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET','Verify_Colonoscopy_Report.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectVerify.send();
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
    function Close_Saved_Dialog(){
        window.open("<?php echo $Back_Link; ?>","_parent");
        //$("#Saved_Info").dialog("close");
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

        myObjectPreview.open('GET','Post_Operative_Ogd_Preview.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function searchDisease(){
        var disease_code = document.getElementById("disease_code").value;
        var disease_name = document.getElementById("disease_name").value;
        var subcategory_ID = document.getElementById("subcategory_ID").value;
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectSearch4 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch4 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch4.overrideMimeType('text/xml');
        }

        myObjectSearch4.onreadystatechange = function () {
            data129 = myObjectSearch4.responseText;
            if (myObjectSearch4.readyState == 4) {
                document.getElementById('Disease_List_Area').innerHTML = data129;
            }
        }; //specify name of function that will handle server response........

        if(disease_name != null && disease_name != ''){
            myObjectSearch4.open('GET', 'Ogd_Post_Operative_Search_Disease.php?subcategory_ID='+subcategory_ID+'&disease_name='+disease_name+'&disease_code='+disease_code+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID, true);
        }else{
            myObjectSearch4.open('GET', 'Ogd_Post_Operative_Search_Disease.php?subcategory_ID='+subcategory_ID+'&disease_code='+disease_code+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID, true);
        }
        myObjectSearch4.send();
    }
</script>

<script type="text/javascript">
    function  Clear_Disease_Code(){
        document.getElementById("disease_code").value = '';
    }
</script>

<script type="text/javascript">
    function  Clear_Disease_Name(){
        document.getElementById("disease_name").value = '';
    }
</script>

<script type="text/javascript">
    function Submit_Colonoscopy(){
        var Endoscorpic_internvention = document.getElementById("Endoscorpic_internvention").value;
        var Dose_Of_Sedation = document.getElementById("Dose_Of_Sedation").value;
        var Adverse_Event = document.getElementById("Adverse_Event").value;
        var Management_recommendation = document.getElementById("Management_recommendation").value;
        var Commobility = document.getElementById("Commobility").value;
        var Extent_Of_Examination = document.getElementById("Extent_Of_Examination").value;

        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
        var Date_From = '<?php echo $Date_From; ?>';
        var Date_To = '<?php echo $Date_To; ?>'; 
        var Sponsor = '<?php echo $Sponsor; ?>'; 
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';


        if (window.XMLHttpRequest) {
            myObjectUpper = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpper = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpper.overrideMimeType('text/xml');
        }

        myObjectUpper.onreadystatechange = function () {
            dataUpdateQuality = myObjectUpper.responseText;
            if (myObjectUpper.readyState == 4) {
                $("#Submit_Info").dialog("close");
                $("#Saved_Info").dialog("open");
                //window.location = "gitpostoperativenotes.php?Registration_ID="+Registration_ID+"&Patient_Payment_ID="+Patient_Payment_ID+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID+"&Date_From="+Date_From+"&Date_To="+Date_To+"&Sponsor="+Sponsor+"&Sub_Department_ID="+Sub_Department_ID+"&GitPostOperativeNotes=GitPostOperativeNotesThisPage";
            }
        }; //specify name of function that will handle server response........

        myObjectUpper.open('GET', 'Go_To_Upper_Git_Scope.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Endoscorpic_internvention='+Endoscorpic_internvention+'&Dose_Of_Sedation='+Dose_Of_Sedation+'&Adverse_Event='+Adverse_Event+'&Management_recommendation='+Management_recommendation+'&Commobility='+Commobility+'&Extent_Of_Examination='+Extent_Of_Examination, true);
        myObjectUpper.send();
    }
</script>

<script type="text/javascript">
    function Upper_Git_Scope(){
        var Endoscorpic_internvention = document.getElementById("Endoscorpic_internvention").value;
        var Dose_Of_Sedation = document.getElementById("Dose_Of_Sedation").value;
        var Adverse_Event = document.getElementById("Adverse_Event").value;
        var Management_recommendation = document.getElementById("Management_recommendation").value;
        var Commobility = document.getElementById("Commobility").value;
        var Extent_Of_Examination = document.getElementById("Extent_Of_Examination").value;

        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
        var Date_From = '<?php echo $Date_From; ?>';
        var Date_To = '<?php echo $Date_To; ?>'; 
        var Sponsor = '<?php echo $Sponsor; ?>'; 
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectUpper = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpper = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpper.overrideMimeType('text/xml');
        }

         //specify name of function that will handle server response........

        myObjectUpper.open('GET', 'Go_To_Upper_Git_Scope.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Endoscorpic_internvention='+Endoscorpic_internvention+'&Dose_Of_Sedation='+Dose_Of_Sedation+'&Adverse_Event='+Adverse_Event+'&Management_recommendation='+Management_recommendation+'&Commobility='+Commobility+'&Extent_Of_Examination='+Extent_Of_Examination, true);
        myObjectUpper.send();
    }
</script>

<script type="text/javascript">
    function Update_Quality_Of_Bowel(){
        var Quality_Of_Bowel = document.getElementById("Quality_Of_Bowel").value;
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';


        if (window.XMLHttpRequest) {
            myObjectUpdateQuality = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateQuality = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateQuality.overrideMimeType('text/xml');
        }

        myObjectUpdateQuality.onreadystatechange = function () {
            dataUpdateQuality = myObjectUpdateQuality.responseText;
            if (myObjectUpdateQuality.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectUpdateQuality.open('GET', 'Update_Quality_Of_Bowel.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Quality_Of_Bowel='+Quality_Of_Bowel, true);
        myObjectUpdateQuality.send();
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
		
		
        var Indication_Of_Procedure = document.getElementById("Indication_Of_Procedure").value;
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

        myObjectUpdateIndication.open('GET', 'Update_Indication_Of_Procedure.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Indication_Of_Procedure='+Indication_Of_Procedure+'&others='+others, true);
        myObjectUpdateIndication.send();
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

        myObjectGetSelectedDisease.open('GET', 'Ogd_Get_Selected_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&disease_ID='+disease_ID, true);
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

        myObjectRemove.open('GET', 'Ogd_Remove_Selected_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Diagnosis_ID='+Diagnosis_ID, true);
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

        myObjectUpdateDiagnosis.open('GET', 'Ogd_Update_Disease_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectUpdateDiagnosis.send();
    }
</script>


<script type="text/javascript">
    function Change_Description_Title(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';

        var Previous_Title = document.getElementById("Title_Area").innerHTML;
        var Temp_ID = '';
        var Temp_Data = '';
        if(Previous_Title == 'Anal Lesions'){
            Temp_ID = 'Anal_lessor';
            Temp_Data = document.getElementById("Anal_lessor").value;
        }else if(Previous_Title == 'Haemoral'){
            Temp_ID = 'Haemoral';
            Temp_Data = document.getElementById("Haemoral").value;
        }else if(Previous_Title == 'PR'){
            Temp_ID = 'PR';
            Temp_Data = document.getElementById("PR").value;
        }else if(Previous_Title == 'Sigmoid Colon'){
            Temp_ID = 'Sigmoid_Colon';
            Temp_Data = document.getElementById("Sigmoid_Colon").value;
        }else if(Previous_Title == 'Descending Colon'){
            Temp_ID = 'Dex_colon';
            Temp_Data = document.getElementById("Dex_colon").value;
        }else if(Previous_Title == 'Splenic Flexure'){
            Temp_ID = 'Splenic_Flexure';
            Temp_Data = document.getElementById("Splenic_Flexure").value;
        }else if(Previous_Title == 'Transverse Colon'){
            Temp_ID = 'Ple_Tran_Col';
            Temp_Data = document.getElementById("Ple_Tran_Col").value;
        }else if(Previous_Title == 'Hepatic Flexure'){
            Temp_ID = 'Hepatic_Flexure';
            Temp_Data = document.getElementById("Hepatic_Flexure").value;
        }else if(Previous_Title == 'Ascending Colon'){
            Temp_ID = 'Ascending_Colon';
            Temp_Data = document.getElementById("Ascending_Colon").value;
        }else if(Previous_Title == 'Caecum'){
            Temp_ID = 'Caecum';
            Temp_Data = document.getElementById("Caecum").value;
        }else if(Previous_Title == 'Terminal_Ileum'){
            Temp_ID = 'Terminal_Ileum';
            Temp_Data = document.getElementById("Terminal_Ileum").value;
        }else if(Previous_Title=='Rectum'){
			Temp_ID = 'Rectum';
            Temp_Data = document.getElementById("Rectum").value;
			
		}

        var Title = document.getElementById("Title").value;
        if(Title != '~~~ Select Title ~~~'){
            document.getElementById("Title_Area").innerHTML = Title;
            if(Title == 'Anal Lesions'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Anal_lessor" name="Anal_lessor" oninput="Update_Content(\'Anal_lessor\')" onkeypress="Update_Content(\'Anal_lessor\')")></textarea>';
            }else if(Title == 'Haemoral'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Haemoral" name="Haemoral" oninput="Update_Content(\'Haemoral\')" onkeypress="Update_Content(\'Haemoral\')"></textarea>';
            }else if(Title == 'PR'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="PR" name="PR" oninput="Update_Content(\'PR\')" onkeypress="Update_Content(\'PR\')"></textarea>';
            }else if(Title == 'Sigmoid Colon'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Sigmoid_Colon" name="Sigmoid_Colon" oninput="Update_Content(\'Sigmoid_Colon\')" onkeypress="Update_Content(\'Sigmoid_Colon\')"></textarea>';
            }else if(Title == 'Descending Colon'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Dex_colon" name="Dex_colon" oninput="Update_Content(\'Dex_colon\')" onkeypress="Update_Content(\'Dex_colon\')"></textarea>';
            }else if(Title == 'Splenic Flexure'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Splenic_Flexure" name="Splenic_Flexure" oninput="Update_Content(\'Splenic_Flexure\')" onkeypress="Update_Content(\'Splenic_Flexure\')"></textarea>';
            }else if(Title == 'Transverse Colon'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Ple_Tran_Col" name="Ple_Tran_Col" oninput="Update_Content(\'Ple_Tran_Col\')" onkeypress="Update_Content(\'Ple_Tran_Col\')"></textarea>';
            }else if(Title == 'Hepatic Flexure'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Hepatic_Flexure" name="Hepatic_Flexure" oninput="Update_Content(\'Hepatic_Flexure\')" onkeypress="Update_Content(\'Hepatic_Flexure\')"></textarea>';
            }else if(Title == 'Ascending Colon'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Ascending_Colon" name="Ascending_Colon" oninput="Update_Content(\'Ascending_Colon\')" onkeypress="Update_Content(\'Ascending_Colon\')"></textarea>';
            }else if(Title == 'Caecum'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Caecum" name="Caecum" oninput="Update_Content(\'Caecum\')" onkeypress="Update_Content(\'Caecum\')"></textarea>';
            }else if(Title == 'Terminal Ileum'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Terminal_Ileum" name="Terminal_Ileum" oninput="Update_Content(\'Terminal_Ileum\')" onkeypress="Update_Content(\'Terminal_Ileum\')"></textarea>';
            }else if(Title=='Rectum'){
				    document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Rectum" name="Rectum" oninput="Update_Content(\'Rectum\')" onkeypress="Update_Content(\'Rectum\')"></textarea>';
			}else{
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Default_Area" name="Default_Area" readonly="readonly" oninput="Update_Content()" onkeypress="Update_Content()"></textarea>';
            }
			
			
			
        }else{
            document.getElementById("Title_Area").innerHTML = '';
            document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Default_Area" name="Default_Area" readonly="readonly")></textarea>';
        }
		
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

        myObjectUpdate.open('GET', 'Ogd_Post_Operative_Update_Previous_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectUpdate.send();
    }
</script>

<script type="text/javascript">
    function Update_Content(Temp_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Temp_Data = document.getElementById(Temp_ID).value;
        if (window.XMLHttpRequest) {
            myObjectAutoUpdate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAutoUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAutoUpdate.overrideMimeType('text/xml');
        }

        myObjectAutoUpdate.onreadystatechange = function () {
            dataAutoUpdate = myObjectAutoUpdate.responseText;
            if (myObjectAutoUpdate.readyState == 4) {
            }
        }; //specify name of function that will handle server response........

        myObjectAutoUpdate.open('GET', 'Ogd_Post_Operative_Auto_Update_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectAutoUpdate.send();
    }
</script>

<script type="text/javascript">
    function Get_Previous_Data(){
        var Title = document.getElementById("Title").value;
	
        if(Title == 'Anal Lesions'){
            Temp_ID = 'Anal_lessor';
        }else if(Title == 'Haemoral'){
            Temp_ID = 'Haemoral';
        }else if(Title == 'PR'){
            Temp_ID = 'PR';
        }else if(Title == 'Sigmoid Colon'){
            Temp_ID = 'Sigmoid_Colon';
        }else if(Title == 'Descending Colon'){
            Temp_ID = 'Dex_colon';
        }else if(Title == 'Splenic Flexure'){
            Temp_ID = 'Splenic_Flexure';
        }else if(Title == 'Transverse Colon'){
            Temp_ID = 'Ple_Tran_Col';
        }else if(Title == 'Hepatic Flexure'){
            Temp_ID = 'Hepatic_Flexure';
        }else if(Title == 'Ascending Colon'){
            Temp_ID = 'Ascending_Colon';
        }else if(Title == 'Caecum'){
            Temp_ID = 'Caecum';
        }else if(Title == 'Terminal Ileum'){
            Temp_ID = 'Terminal_Ileum';
        }else if(Title=='Rectum'){
			Temp_ID = 'Rectum';
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

        myObjectGetPrevious.open('GET', 'Ogd_Post_Operative_Get_Previous_Contents.php?Temp_ID='+Temp_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectGetPrevious.send();
    }
</script>


<script type="text/javascript">
    function Close_Diagnosis_Dialog(){
        $("#Add_Diagnosis").dialog("close");
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
        }; //specify name of function that will handle server response........

        myObjectDiag.open('GET', 'OGD_Add_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID, true);
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
        $("#Saved_Info").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0', modal: true});
        $("#Preview_Findings_Area").dialog({autoOpen: false, width: '60%', height: 450, title: 'PREVIEW FINDINGS', modal: true});
        $("#Preview_Warning").dialog({autoOpen: false, width: '35%', height: 150, title: 'eHMS 2.0', modal: true});
		
		var Indication_Of_Procedure=$('#Indication_Of_Procedure').val();
		if(Indication_Of_Procedure=='Others'){
			$('#Others_val').show();
		}
    });
</script>

<?php
    include("./includes/footer.php");
?>
