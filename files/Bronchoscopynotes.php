<style>
            table,tr,td{ border-collapse:collapse !important; /*border:none !important;*/ }
            .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
        
        .spare table tr th {
            background: gray;
            border: 1px solid #fff;
        }
        .spare table tr:nth-child(even){
            background-color: #eee;
        }
        .spare table tr:nth-child(odd){
            background-color: #fff;
        }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
        </style>

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
    echo '<input type="button" name="Report_Button" id="Report_Button" value="BRONCHOSCOPY REPORT" class="art-button-green" onclick="Preview_Bronchoscopy_Report()">';
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
    $get_details = mysqli_query($conn,"select Surgery_Date_Time, Surgery_Date, Bronchoscopy_Notes_ID, Indication, vocal_cords, Trachea, Carina, Rt_Bronchial_tree, Rt_UL_Bronchus, Rt_ML_Bronchus, Rt_LL_Bronchus, Lt_Bronchial_tree, Lt_UL_Bronchus, Lt_LL_Bronchus, Liangula, Biopsy, Impression, Bal, Premedication, Comments
                                from tbl_bronchoscopy_notes where
                                Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num_procedure_details = mysqli_num_rows($get_details);
    if($num_procedure_details > 0){
        while ($data = mysqli_fetch_array($get_details)) {
            $Bronchoscopy_Notes_ID = $data['Bronchoscopy_Notes_ID'];
            $Surgery_Date_Time = $data['Surgery_Date_Time'];
            $Indication = $data['Indication'];
            $Premedication = $data['Premedication'];
            $vocal_cords = $data['vocal_cords'];
            $Trachea = $data['Trachea'];
            $consultation_ID = $data['consultation_ID'];
            $Carina = $data['Carina'];
            $Rt_Bronchial_tree = $data['Rt_Bronchial_tree'];
            $Rt_UL_Bronchus = $data['Rt_UL_Bronchus'];
            $Rt_ML_Bronchus = $data['Rt_ML_Bronchus'];
            $Rt_LL_Bronchus = $data['Rt_LL_Bronchus'];
            $Lt_Bronchial_tree = $data['Lt_Bronchial_tree'];
            $Lt_UL_Bronchus = $data['Lt_UL_Bronchus'];
            $Lt_LL_Bronchus = $data['Lt_LL_Bronchus'];
            $Liangula = $data['Liangula'];
            $Impression = $data['Impression'];
            $Biopsy = $data['Biopsy'];
            $Bal = $data['Bal'];
            $Comments = $data['Comments'];
            $Surgery_Date = $data['Surgery_Date'];

        }
    }else{
       $Bronchoscopy_Notes_ID = '';
            $Surgery_Date_Time = '';
            $Indication = '';
            $vocal_cords = '';
            $Trachea = '';
            $consultation_ID = '';
            $Carina = '';
            $Rt_Bronchial_tree = '';
            $Rt_UL_Bronchus = '';
            $Rt_ML_Bronchus = '';
            $Rt_LL_Bronchus = '';
            $Lt_Bronchial_tree = '';
            $Lt_UL_Bronchus = '';
            $Lt_LL_Bronchus = '';
            $Liangula = '';
            $Impression = '';
            $Biopsy = '';
            $Bal = '';
            $Comments = '';
            $Premedication = '';
            $Surgery_Date = '';
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

    //get Bronchoscopy_Notes_ID
    $get_Bronchoscopy_Notes_ID = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Bronchoscopy_Notes_ID);
    if($nm > 0){
        while ($dt = mysqli_fetch_array($get_Bronchoscopy_Notes_ID)) {
            $Bronchoscopy_Notes_ID = $dt['Bronchoscopy_Notes_ID'];
        }
    }else{
        $Bronchoscopy_Notes_ID = 0;
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
    <legend><b>BRONCHOSCOPY</b></legend>
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
                <input type="text" id='Procedure_Name' name='Procedure_Name' readonly="readonly" value="<?php echo $Product_Name; ?>">
            </td>
            <td style="text-align:right;" >Procedure Date</td>
            <td><input type="text" autocomplete="off" Placeholder="Procedure Date" name="Surgery_Date" id="Surgery_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Indication Of Procedure</td>
            <td colspan="4">
                        <textarea name="Indication" id="Indication" cols="100" rows="3" oninput='Update_Indication_Of_Procedure()' onkeypress='Update_Indication_Of_Procedure()' onkeyup='Update_Indication_Of_Procedure()'><?php echo $Indication; ?></textarea>
                    </td>
            </tr>
            <tr>
                <td style="text-align: right;">Premedication(s):</td>
                <td colspan="4">
                        <textarea name="Premedication" id="Premedication" cols="100" rows="3" oninput='Update_Indication_Of_Procedure()' onkeypress='Update_Indication_Of_Procedure()' onkeyup='Update_Indication_Of_Procedure()'><?php echo $Premedication; ?></textarea>
                    </td>
            </tr>

                <table  style="margin-top:5" width=100%>
                    <tr>
                        <td width="10%">
                            <table width="100%">
                                <tr>
                                    <td><b>FINDINGS</b></td>
                                    <td>
                                        <select name="Title" id="Title" onchange="Change_Description_Title()" style="font-size: 20px;">
                                            <option>~~~ Select Title ~~~</option>
                                            <option value="Vocal Cords">Vocal Cords</option>
                                            <option value="Trachea">Trachea</option>
                                            <option value="Carina">Carina</option>
                                            <option value="Rt Bronchial Tree">Rt Bronchial Tree</option>
                                            <option value="Rt UL Bronchus">Rt UL Bronchus</option>
                                            <option value="Rt ML Bronchus">Rt ML Bronchus</option>
                                            <option value="Rt LL Bronchus">Rt LL Bronchus</option>
                                            <option value="Lt Bronchial Trees">Lt Bronchial Trees</option>
                                            <option value="Lt UL Bronchus">Lt UL Bronchus</option>
                                            <option value="Lt LL Bronchus">Lt LL Bronchus</option>
                                            <option value="Liangula">Liangula</option>
                                            <option value="Impression">Impression</option>
                                            <option value="Biopsy">Biopsy</option>
                                            <option value="Bal">Bal</option>
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
                        <tr>
        <td style="text-align: right;" width="19%"><b>Procedure Comments</b></td>
        <td style="text-align: right" colspan="">
            <table width="100%">
                <tr>
                    <td><textarea id='Comments' name='Comments' style="width: 100%; height: 40px;" placeholder="Procedure Comments" oninput='Update_Content()' onkeypress='Update_Content()' onkeyup='Update_Content()'><?php echo $Comments; ?></textarea></td>
                    </tr>
                    <tr>
                    <td width="10%" style="text-align: right;">
                        <input type="button" name="Submit" id="Submit" value="SAVE INFORMATION(s)" class="art-button-green" onclick="Submit_Info()">                        
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
                    echo "<input type='button' value='PREVIEW REPORT' class='art-button-green' onclick='Preview_Bronchoscopy_Report()'>";
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
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Submit_Bronchoscopy()">
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
    function Preview_Bronchoscopy_Report(){
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
                    window.open("previewBronchoscopyreport.php?Registration_ID="+Registration_ID+"&Patient_Payment_ID="+Patient_Payment_ID+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID+"&PreviewBronchoscopyReport=PreviewBronchoscopyReportThisPage","_blank");
                }else{
                    $("#Preview_Warning").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET','Verify_Bronchoscopy_Report.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
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

        myObjectPreview.open('GET','Bronchoscopy_Detais_Preview.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Submit_Bronchoscopy(){
        var Indication = document.getElementById("Indication").value;
        var Comments = document.getElementById("Comments").value;
        var Premedication = document.getElementById("Premedication").value;
        
        var consultation_ID = '<?php echo $consultation_ID; ?>';
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
            }
        }; //specify name of function that will handle server response........

        myObjectUpper.open('GET', 'Go_To_Bronchoscopy_Scope.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Indication='+Indication+'&Premedication='+Premedication+'&Comments='+Comments+'&consultation_ID='+consultation_ID, true);
        myObjectUpper.send();
    }
</script>

<script type="text/javascript">
    function Bronchoscopy_Scope(){
        var Indication = document.getElementById("Indication").value;
        var Comments = document.getElementById("Comments").value;
        var Premedication = document.getElementById("Premedication").value;

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

        myObjectUpper.open('GET', 'Go_To_Bronchoscopy_Scope.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Indication='+Indication+'$Premedication='+Premedication+'&Comments='+Comments, true);
        myObjectUpper.send();
    }
</script>
<script type="text/javascript">
    function Update_Indication_Of_Procedure(){
		
		
        var Indication = document.getElementById("Indication").value;
        var Comments = document.getElementById("Comments").value;
        var Premedication = document.getElementById("Premedication").value;
        
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
		
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

        myObjectUpdateIndication.open('GET', 'Go_To_Bronchoscopy_Scope.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Indication='+Indication+'&Premedication='+Premedication+'&Comments='+Comments, true);
        myObjectUpdateIndication.send();
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
        if(Previous_Title == 'Vocal Cords'){
            Temp_ID = 'vocal_cords';
            Temp_Data = document.getElementById("vocal_cords").value;
        }else if(Previous_Title == 'Trachea'){
            Temp_ID = 'Trachea';
            Temp_Data = document.getElementById("Trachea").value;
        }else if(Previous_Title == 'Carina'){
            Temp_ID = 'Carina';
            Temp_Data = document.getElementById("Carina").value;
        }else if(Previous_Title == 'Rt Bronchial Tree'){
            Temp_ID = 'Rt_Bronchial_tree';
            Temp_Data = document.getElementById("Rt_Bronchial_tree").value;
        }else if(Previous_Title == 'Rt UL Bronchus'){
            Temp_ID = 'Rt_UL_Bronchus';
            Temp_Data = document.getElementById("Rt_UL_Bronchus").value;
        }else if(Previous_Title == 'Rt ML Bronchus'){
            Temp_ID = 'Rt_ML_Bronchus';
            Temp_Data = document.getElementById("Rt_ML_Bronchus").value;
        }else if(Previous_Title == 'Rt LL Bronchus'){
            Temp_ID = 'Rt_LL_Bronchus';
            Temp_Data = document.getElementById("Rt_LL_Bronchus").value;
        }else if(Previous_Title == 'Lt Bronchial Trees'){
            Temp_ID = 'Lt_Bronchial_tree';
            Temp_Data = document.getElementById("Lt_Bronchial_tree").value;
        }else if(Previous_Title == 'Lt UL Bronchus'){
            Temp_ID = 'Lt_UL_Bronchus';
            Temp_Data = document.getElementById("Lt_UL_Bronchus").value;
        }else if(Previous_Title == 'Lt LL Bronchus'){
            Temp_ID = 'Lt_LL_Bronchus';
            Temp_Data = document.getElementById("Lt_LL_Bronchus").value;
        }else if(Previous_Title == 'Liangula'){
            Temp_ID = 'Liangula';
            Temp_Data = document.getElementById("Liangula").value;
        }else if(Previous_Title == 'Impression'){
            Temp_ID = 'Impression';
            Temp_Data = document.getElementById("Impression").value;
        }else if(Previous_Title == 'Biopsy'){
            Temp_ID = 'Biopsy';
            Temp_Data = document.getElementById("Biopsy").value;
        }else if(Previous_Title == 'Bal'){
            Temp_ID = 'Bal';
            Temp_Data = document.getElementById("Bal").value;			
        }

        var Title = document.getElementById("Title").value;
        if(Title != '~~~ Select Title ~~~'){
            document.getElementById("Title_Area").innerHTML = Title;
            if(Title == 'Vocal Cords'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="vocal_cords" name="vocal_cords" oninput="Update_Content(\'vocal_cords\')" onkeypress="Update_Content(\'vocal_cords\')")></textarea>';
            }else if(Title == 'Carina'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Carina" name="Carina" oninput="Update_Content(\'Carina\')" onkeypress="Update_Content(\'Carina\')"></textarea>';
            }else if(Title == 'Trachea'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Trachea" name="Trachea" oninput="Update_Content(\'Trachea\')" onkeypress="Update_Content(\'Trachea\')"></textarea>';
            }else if(Title == 'Rt Bronchial Tree'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Rt_Bronchial_tree" name="Rt_Bronchial_tree" oninput="Update_Content(\'Rt_Bronchial_tree\')" onkeypress="Update_Content(\'Rt_Bronchial_tree\')"></textarea>';
            }else if(Title == 'Rt UL Bronchus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Rt_UL_Bronchus" name="Rt_UL_Bronchus" oninput="Update_Content(\'Rt_UL_Bronchus\')" onkeypress="Update_Content(\'Rt_UL_Bronchus\')"></textarea>';
            }else if(Title == 'Rt ML Bronchus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Rt_ML_Bronchus" name="Rt_ML_Bronchus" oninput="Update_Content(\'Rt_ML_Bronchus\')" onkeypress="Update_Content(\'Rt_ML_Bronchus\')"></textarea>';
            }else if(Title == 'Rt LL Bronchus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Rt_LL_Bronchus" name="Rt_LL_Bronchus" oninput="Update_Content(\'Rt_LL_Bronchus\')" onkeypress="Update_Content(\'Rt_LL_Bronchus\')"></textarea>';
            }else if(Title == 'Lt Bronchial Trees'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Lt_Bronchial_tree" name="Lt_Bronchial_tree" oninput="Update_Content(\'Lt_Bronchial_tree\')" onkeypress="Update_Content(\'Lt_Bronchial_tree\')"></textarea>';
            }else if(Title == 'Lt UL Bronchus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Lt_UL_Bronchus" name="Lt_UL_Bronchus" oninput="Update_Content(\'Lt_UL_Bronchus\')" onkeypress="Update_Content(\'Lt_UL_Bronchus\')"></textarea>';
            }else if(Title == 'Lt LL Bronchus'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Lt_LL_Bronchus" name="Lt_LL_Bronchus" oninput="Update_Content(\'Lt_LL_Bronchus\')" onkeypress="Update_Content(\'Lt_LL_Bronchus\')"></textarea>';
            }else if(Title == 'Liangula'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Liangula" name="Liangula" oninput="Update_Content(\'Liangula\')" onkeypress="Update_Content(\'Liangula\')"></textarea>';
            }else if(Title == 'Impression'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Impression" name="Impression" oninput="Update_Content(\'Impression\')" onkeypress="Update_Content(\'Impression\')"></textarea>';
            }else if(Title == 'Biopsy'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Biopsy" name="Biopsy" oninput="Update_Content(\'Biopsy\')" onkeypress="Update_Content(\'Biopsy\')"></textarea>';
            }else if(Title == 'Bal'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Bal" name="Bal" oninput="Update_Content(\'Bal\')" onkeypress="Update_Content(\'Bal\')"></textarea>';
			}	
        }
        else{
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

        myObjectUpdate.open('GET', 'Bronchoscopy_Update_Previous_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
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

        myObjectAutoUpdate.open('GET', 'Bronchoscopy_Auto_Update_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectAutoUpdate.send();
    }
</script>

<script type="text/javascript">
    function Get_Previous_Data(){
        var Title = document.getElementById("Title").value;
	
        if(Title == 'Vocal Cords'){
            Temp_ID = 'vocal_cords';
        }else if(Title == 'Carina'){
            Temp_ID = 'Carina';
        }else if(Title == 'Trachea'){
            Temp_ID = 'Trachea';
        }else if(Title == 'Rt Bronchial Tree'){
            Temp_ID = 'Rt_Bronchial_tree';
        }else if(Title == 'Rt UL Bronchus'){
            Temp_ID = 'Rt_UL_Bronchus';
        }else if(Title == 'Rt ML Bronchus'){
            Temp_ID = 'Rt_ML_Bronchus';
        }else if(Title == 'Rt LL Bronchus'){
            Temp_ID = 'Rt_LL_Bronchus';
        }else if(Title == 'Lt Bronchial Trees'){
            Temp_ID = 'Lt_Bronchial_tree';
        }else if(Title == 'Lt UL Bronchus'){
            Temp_ID = 'Lt_UL_Bronchus';
        }else if(Title == 'Lt LL Bronchus'){
            Temp_ID = 'Lt_LL_Bronchus';
        }else if(Title == 'Liangula'){
            Temp_ID = 'Liangula';
        }else if(Title == 'Impression'){
            Temp_ID = 'Impression';
        }else if(Title == 'Biopsy'){
            Temp_ID = 'Biopsy';
        }else if(Title=='Bal'){
			Temp_ID = 'Bal';
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

        myObjectGetPrevious.open('GET', 'Bronchoscopy_Previous_Contents.php?Temp_ID='+Temp_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID, true);
        myObjectGetPrevious.send();
    }
</script>


<script type="text/javascript">
    function Close_Diagnosis_Dialog(){
        $("#Add_Diagnosis").dialog("close");
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


                
<script>
//PRE MEDICATION SCRIPTS
// function mantainance_drugs(){
//        $.ajax({
//                 type:'POST',
//                 url:'add_bronchoscopy_items.php',           
//                 data:{add_maintanance:''},
//                 success:function(responce){
//                     $("#drugdialog").dialog({
//                         title: 'ADD CONSUMED SPARE FOR REQUISION NO: <?php //echo $Bronchoscopy_Notes_ID?>',
//                         width: 800, 
//                         height: 600, 
//                         modal: true
//                         });
//                     $("#drugdialog").html(responce);
//                     diaplay_maintanance()
//                 }
//             })
//     }

//     function diaplay_maintanance(){
//             var Bronchoscopy_Notes_ID = $("#Bronchoscopy_Notes_ID").val();
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {Bronchoscopy_Notes_ID:Bronchoscopy_Notes_ID, select_maintanance:''},
//                 cache: false,
//                 success: function (responce){                  
//                     $('#SpareConsumed').html(responce);
//                 }
//             });
//         }
//         function add_maintanance(Item_ID,){
//             var Registration_ID = $("#Registration_ID").val();
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {Item_ID:Item_ID,Registration_ID:Registration_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, insert_maintanance:''},
//                 cache: false,
//                 success: function (html){   
//                     $("#drugdialog").dialog('close');               
//                     diaplay_maintanance();
                    
//                 }
//             });
//         }
       
//         function remove_maintanance(Bronchoscopy_Notes_ID, Item_ID, Employee_ID){
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {Bronchoscopy_Notes_ID:Bronchoscopy_Notes_ID,Item_ID:Item_ID,Employee_ID:Employee_ID, removemaintanance:''},
//                 success: function (responce){                  
//                     diaplay_maintanance();
//                 }
//             });
//         }
//         function update_maintanance_time(Bronchoscopy_Notes_ID, Item_ID){
//             var Route = $("#Route"+Item_ID).val();
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {Bronchoscopy_Notes_ID:Bronchoscopy_Notes_ID,Route:Route,Item_ID:Item_ID, updatetimemaintanance:''},
//                 success: function (responce){                  
                   
//                 }
//             });
//         }
//         function update_maintanance_Amount(Bronchoscopy_Notes_ID, Item_ID){
//             var Amount = $("#Amount"+Item_ID).val();
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {Bronchoscopy_Notes_ID:Bronchoscopy_Notes_ID, Item_ID:Item_ID, Amount:Amount, updateAmountmaintanance:''},
//                 success: function (responce){                  
                   
//                 }
//             });
//         }
//         function search_maintanance_item(items){
//             $.ajax({
//                 type: 'POST',
//                 url: 'add_bronchoscopy_items.php',
//                 data: {items:items, search_maintanance_item:''},
//                 cache: false,
//                 success: function (html) {
//                     console.log(html);
//                     $('#Items_Fieldset').html(html);
//                 }
//             });
//         }
    
    
//     //END OF SPARE PARTS
 
//     $(document).ready(function(){
//           diaplay_maintanance();

//     });
// </script>



<?php
include "./includes/footer.php"
?>