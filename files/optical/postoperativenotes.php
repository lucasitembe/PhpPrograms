<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
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

    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }else{
        $consultation_ID = 0;
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }

    //echo "<a href='#' class='art-button-green'>PATIENT FILE</a>";
    //echo "<a href='previewpostoperativereport.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage' class='art-button-green' target='_blank'>POST OPERATIVE REPORT</a>";


    //get procedure name
    $select = mysqli_query($conn,"select Product_Name, ilc.Status from tbl_items i, tbl_item_list_cache ilc where
                            i.Item_ID = ilc.Item_ID and
                            ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Check_In_Type = 'Surgery'") or die(mysqli_error($conn));
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
    $get_details = mysqli_query($conn,"select cutting_time,end_cutting_time,post_operative_remarks,type_of_surgery,duration_of_surgery,surgery_status,Post_operative_ID, Surgery_Date, Incision, Position, consultation_ID, Type_Of_Anesthetic
                                from tbl_post_operative_notes where
                                Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num_procedure_details = mysqli_num_rows($get_details);
    if($num_procedure_details > 0){
        while ($data = mysqli_fetch_array($get_details)) {
            $Post_operative_ID = $data['Post_operative_ID'];
            $Surgery_Date = $data['Surgery_Date'];
            $Incision = $data['Incision'];
            $Position = $data['Position'];
            $surgery_status=$data['surgery_status'];
            //$consultation_ID = $data['consultation_ID'];
            $Type_Of_Anesthetic = $data['Type_Of_Anesthetic'];
            $duration_of_surgery = $data['duration_of_surgery'];
            $type_of_surgery = $data['type_of_surgery'];
            $cutting_time = $data['cutting_time'];
            $end_cutting_time = $data['end_cutting_time'];
            $post_operative_remarks = $data['post_operative_remarks'];
        }
    }else{
        $Post_operative_ID = '';
        $Surgery_Date = '';
        $Incision = '';
        $Position = '';
        $Type_Of_Anesthetic = '';
        $duration_of_surgery='';
        $type_of_surgery='';
        $surgery_status='';
        $cutting_time = '';
        $end_cutting_time = '';
        $post_operative_remarks = '';
    }

    if(isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_Item_List_ID']) && isset($_GET['Patient_Payment_ID'])){
        echo "<a href='performsurgery.php?Section=".$Section."&consultation_ID=".$consultation_ID."&Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage' class='art-button-green'>BACK</a>";
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    //get patient details
    $select = mysqli_query($conn,"select Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number, sp.Sponsor_ID from
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
            $Sponsor_ID = $data['Sponsor_ID'];
        }
    }else{
        $Patient_Name = '';
        $Gender = '';
        $Date_Of_Birth = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Sponsor_ID = '';
    }

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $Age = $diff->y." Years, ";
    $Age .= $diff->m." Months, ";
    $Age .= $diff->d." Days";
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
    .field{
        margin-bottom:1.5em;
    }
</style>
<fieldset>
    <legend><b>SURGERY</b></legend>
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

    <div id="open_optica_data"></div>
    <!-- <center><div style="color:white" class="art-button-green" onclick="open_optica_data(<?php echo $Registration_ID?>)">Click here to get patient Optical Detail</div></center> -->
 
<?php
    //get selected participants
    $Assistant_surgeons_selected = '';
    $Surgeons_selected = '';
    $Nurses_selected = '';
    $Anaesthetics_selected = '';

    if($num_procedure_details > 0){
        $selected_assistant_surgeons = mysqli_query($conn,"select Employee_Name, pop.Employee_Type from tbl_employee emp, tbl_post_operative_participant pop where
                                            emp.Employee_ID = pop.Employee_ID and
                                            pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
        $nmz = mysqli_num_rows($selected_assistant_surgeons);
        if($nmz > 0){
            while ($row = mysqli_fetch_array($selected_assistant_surgeons)) {
                $Employee_Type = $row['Employee_Type'];
                if($Employee_Type == 'Nurse'){
                    $Nurses_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Assistant Surgeon'){
                    $Assistant_surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Surgeon'){
                    $Surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Anaesthetics'){
                    $Anaesthetics_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }
            }
        }
    }


    //get external participants
    $select_external = mysqli_query($conn,"select * from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
    $num_external = mysqli_num_rows($select_external);
    if($num_external > 0){
        while ($ext = mysqli_fetch_array($select_external)) {
            $External_Surgeons_Value = $ext['External_Surgeons'];
            $External_Assistant_Surgeon_Value = $ext['External_Assistant_Surgeon'];
            $External_Scrub_Nurse_Value = $ext['External_Scrub_Nurse'];
            $External_Anaesthetic_Value = $ext['External_Anaesthetic'];
        }
    }else{
        $External_Surgeons_Value = '';
        $External_Assistant_Surgeon_Value = '';
        $External_Scrub_Nurse_Value = '';
        $External_Anaesthetic_Value = '';
    }
?>
<fieldset>
    <!-- =================================================================================================================== -->

    <div class="data-table" style="display:grid;grid-template-columns:1fr 1fr;gap:2em;margin-top:30px;">
        <div class="left-data" style="margin-left:70px;">
            <div class="field">
                <label for=""><b>****</b></span>Procedure Name</label><br>
                <textarea id='Procedure_Name' name='Procedure_Name' required='required' style="width:80%; height: 30px;" readonly="readonly"><?php echo $Product_Name; ?></textarea>
            </div>

            <div class="field">
                <label>Procedure Date</label><br>
                <input type="text" autocomplete="off" name="date_From" id="date_From"  style="width:80%; height: 30px;" value="<?php echo $Today; ?>" readonly="readonly">
            </div>

            <div class='field'>
            <label> Type of Anesthetic</label><br>        
                <select style="width: 80%; height: 30px;"  id="Type_Of_Anesthetic" name="Type_Of_Anesthetic" onchange="Change_Type_Of_Anesthetic()" style="font-size: 15px;">
                    <option selected="selected"></option>
                    <option <?php if($Type_Of_Anesthetic == 'GA'){ echo "selected='selected'"; } ?>>GA</option>
                    <option <?php if($Type_Of_Anesthetic == 'REGIONAL'){ echo "selected='selected'"; } ?>>REGIONAL</option>
                    <option <?php if($Type_Of_Anesthetic == 'LOCAL'){ echo "selected='selected'"; } ?>>LOCAL</option>
                </select>       
            </div>

            <div class="field">
                <label for="">Starting Cutting Time</label><br>
                <input type='text' class="form-control ehms_date_time" id="cutting_time" value='<?= $cutting_time ?>' readonly="readonly" style='text-align:center;background:#FFFFFF;width:80%;height:30px; margin:0px;'>
            </div>
            <div class="field">
                <label>Ending Cutting Time</label><br>
                <input type='text' class="form-control ehms_date_time" id="end_cutting_time" value='<?= $end_cutting_time ?>' readonly="readonly" style='text-align:center;background:#FFFFFF;width:80%;height:30px;margin:0px;'>
            </div>

            <div class="field">
                <label for=""><b>****</b>Type Of Surgery</td></label><br>
                <select style="width:80%;padding: 5px;height:30px;" id='type_of_surgery' onchange="Change_Type_Of_Anesthetic()" name="type_of_surgery">
                    <option value="">Select Type Surgery</option>
                    <option <?php if($type_of_surgery=="Specialized"){echo "selected='selected'";}?>>Specialized</option>
                    <option <?php if($type_of_surgery=="Major"){echo "selected='selected'";}?>>Major</option>
                    <option <?php if($type_of_surgery=="Minor"){echo "selected='selected'";}?>>Minor</option>
                </select>
            </div>

            <div class="field">
                <label for=""><b>****</b>Duration Of Surgery</label><br>
                <input type="text" id="duration_of_surgery" value="<?= $duration_of_surgery ?>" onkeyup="Change_Type_Of_Anesthetic()" name='duration_of_surgery' placeholder="Enter Surgery Duration" style="width: 80%; height: 30px;"/>
            </div>
            
            

          

        </div> 
        <div class='right-data' style="margin-right:50px;">
            <div class="field">
                <label>Surgeon</label><br>
                    <textarea style="width:80%; height: 30px;" name="Surgeon" id="Surgeon"  value='' readonly="readonly"><?php echo $Surgeons_selected; if($External_Surgeons_Value != '') { echo '('.$External_Surgeons_Value.')**External'; } ?></textarea>
                    <input type='button' class='art-button-green' onclick="Select_Surgeon()" value='LIST' style="width:40px;">
            </div>

            <div class="field">
            <label>Assistant Surgeon</label><br>
                <textarea style="width: 80%; height: 30px;" name="Assistant_Surgeon" id="Assistant_Surgeon" readonly='readonly'><?php echo $Assistant_surgeons_selected; if($External_Assistant_Surgeon_Value != '') { echo '('.$External_Assistant_Surgeon_Value.')**External'; } ?></textarea>
                <input type='button' class='art-button-green' onclick="Select_Assistant_Surgeon()" value='LIST'style="width:40px;" >
            </div>

            <div class="field">
                <label>Scrub Nurse</label><br>
                <textarea style="width: 80%; height: 30px;" name="Crub_Nurse" id="Crub_Nurse" readonly="readonly"><?php echo $Nurses_selected; if($External_Scrub_Nurse_Value != '') { echo '('.$External_Scrub_Nurse_Value.')**External'; } ?></textarea>
                <input type='button' class='art-button-green' onclick="Select_Scrub_Nurse()" value='LIST' style="width:40px;">
            </div>
            <div class="field">
                <label>Anaesthetic</label><br>
                <textarea style="width: 80%; height: 30px;" name="Anaesthetic" id="Anaesthetic" readonly='readonly'><?php echo $Anaesthetics_selected; if($External_Anaesthetic_Value != '') { echo '('.$External_Anaesthetic_Value.')**External'; } ?></textarea>
                <input type='button' class='art-button-green' onclick="Select_Anaesthetic()" value='LIST' style="width:40px;">
            </div>
            <div class="field">
               <label> Incision</label><br>
                <textarea name="Incision" id="Incision" readonly="readonly" style="width: 80%; height: 30px;"><?php echo $Incision; ?></textarea>
                    <input type="button" value="SELECT" class='art-button-green' onclick="Get_Incision()" style="width:40px;">
            </div>

            <div class="field">
                <label>Position</label><br>
                <textarea name="Position" id="Position" readonly="readonly" style="width: 80%; height: 30px;"><?php echo $Position; ?></textarea>
                <input type="button" value="SELECT" class="art-button-green" onclick="Get_Positions()" style="width:40px;">
            </div>

            <div class="field">
                <label for=""><b>***</b> Status</label><br>
                <?php 
                $sql_result=mysqli_query($conn,"SELECT status_description FROM tbl_surgery_status") or die(mysqli_error($conn));
                ?>
                <select name="status" id="statuses" style="width: 80%; height: 30px;">
                    <option value="" disabled>Select Status</option>
                    <?php while($rows=mysqli_fetch_assoc($sql_result)){ 
                        if($surgery_status==$rows['status_description']){$selected="selected";}else{$selected="";}
                        ?>
                        <option value="<?=$rows['status_description'];?>" <?= $selected;?>><?=$rows['status_description'];?></option>
                    <?php } ?>
                </select>
                <input type="submit" class="art-button-green" onclick="add_status()" value="ADD NEW STATUS" style="width:40px;">
            </div>
        </div>

    </div>

  


    <!-- =================================================================================================================== -->
</fieldset>
<?php
    //get Preoperative Diagnosis and Postoperative Diagnosis
    $Preoperative_Diag = '';
    $Postoperative_Diag = '';
    if($num_procedure_details > 0){
        $select_diagnosis = mysqli_query($conn,"select d.disease_name, d.disease_code, pod.Diagnosis_Type from tbl_disease d, tbl_post_operative_diagnosis pod where
                                            d.Disease_ID = pod.Disease_ID and
                                            pod.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
        $mn = mysqli_num_rows($select_diagnosis);
        if($mn > 0){
            while ($row = mysqli_fetch_array($select_diagnosis)) {
                $Diagnosis_Type = $row['Diagnosis_Type'];
                if($Diagnosis_Type == 'Preoperative Diagnosis'){
                    $Preoperative_Diag .= ucwords(strtolower($row['disease_name'])).'; ';
                }else if($Diagnosis_Type == 'Postoperative Diagnosis'){
                    $Postoperative_Diag .= ucwords(strtolower($row['disease_name'])).'; ';
                }
            }
        }
    }
?>
<fieldset>

    <div class="data-table" style="display:grid;grid-template-columns:2fr 2fr 1fr;gap:2em;margin-top:30px;">
        <div style="margin-left:70px;"  class="field">
            <textarea id='Postoperative_Diagnosis' name='Postoperative_Diagnosis' style='width: 100%; height: 50px;' readonly='readonly'><?php echo $Postoperative_Diag; ?></textarea>
        </div>
        <div style="margin-left:180px;" class="field">
            <select id="Diagnosis_Type" id="Diagnosis_Type" onchange="Change_Diagnosis()" style="font-size: 20px;">
                <option selected="selected" value="Postoperative Diagnosis">Postoperative Diagnosis(findings)</option>
                <option value="Preoperative Diagnosis">Preoperative Diagnosis(Indication)</option>
            </select>
        </div>
        <div class="field">
            <input type="button" value="SELECT DIAGNOSIS" class="art-button-green" onclick="Add_Postoperative_Diagnosis()" style="margin-left:50px;" >
        </div>
    </div>
</fieldset>
<div id='open_other_optical_surgery_detail'></div>




<fieldset >
    <table width="100%">
    <tr>
        <td width="30%">
            <table width="100%">
                <tr>
                    <td><b>TITLE</b></td>
                    <td>
                        <select name="Title" id="Title" onchange="Change_Description_Title()" style="font-size: 20px;">
                            <option value="~~~ Select Title ~~~">~~~ Select Title ~~~</option>
                            <option value="Procedure description and closure">****Procedure description and closure</option>
                            <option value="Specimen taken">****Specimen taken</option>
                            <option value="Postoperative orders">****Postoperative orders</option>
                            <option value="Identification of prosthesis">Identification of prosthesis</option>
                            <option value="Estimated blood loss">Estimated blood loss</option>
                            <option value="Problems /Complications">Problems /Complications</option>
                            <option value="Drains">Drains</option>
                        </select>
                    </td>
                </tr>
            </table>
            <br/>
            <center>
            
            <script src="./booststrapcollapse/jquery-3.5.1.slim.min.js" ></script>
            <script src="./booststrapcollapse/popper.min.js" ></script>
            <script src="./booststrapcollapse/bootstrap.min.js" ></script>
                <table>
                    <tr>
                        <td>
                            <input type="button" name="Preview_Titles" id="Preview_Titles" value="PREVIEW" class="art-button-green" onclick="Preview_Title()">
                        </td>
                        <!-- <input type='submit' class="form-control art-button" id='open_other_optical_surgery_detail' value='Fill optical detail' onclick='open_other_optical_surgery_detail(<1?php echo $Registration_ID?>);'> -->
                        
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
        <td style="text-align:right"><b>Remarks</b></td>
        <td colspan="2">
            <textarea placeholder="Enter Remarks" id='post_operative_remarks'><?= $post_operative_remarks ?></textarea>
        </td>
    </tr>
    <tr>
        <td style="text-align: right" colspan="3">
            <input type="button" name="Pharmacy_Button" id="Pharmacy_Button" class="art-button-green" value="PHARMACEUTICAL" style='visibility: hidden;' onclick="Open_Medication_Dialogy()">
            <input type="button" name="Laboratory_Button" id="Laboratory_Button" class="art-button-green" value="LABORATORY TESTS" style='visibility: hidden;' onclick="Open_Labodatory_Dialogy()">
            <input type="button" name="" value="RADIOLOGY TEST" id='Radiology_Button' class="art-button-green"style='visibility: hidden;' onclick="Open_Radiology_Dialogy();">
            <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
            <div class="container" >



        <button type="button" class="btn btn-primary form-control art-button" data-toggle="collapse" data-target="#demo" style="overflow:auto;">Fill Eye detail</button>
                <div id="demo" class="collapse" id='open_other_optical_surgery_detail' style="overflow:auto;">
                    <div style="border:1px solid black;margin-top:10px;" >
                    
                    <table class='table table-striped'>
                        <thead>
                            <th>TYPE OF CATARACT</th>
                            <th>RE</th>
                            <th>LE</th>
                            
                        </thead>
                        <tbody>
                            <tr>
                            <td>
                                <select style="margin-left:100px" id="cataract" name="cataract">

                                <?php
                                
                                    
                                    $res=mysqli_query($conn,"SELECT disease_name FROM `tbl_disease` WHERE disease_name like '%cataract%'");
                                    while($row=mysqli_fetch_array($res))
                                    {

                                    ?>
                                    <option value="<?php echo $row[0]?>"><?php echo $row[0]; ?></option>
                                    <?php
                                    }

                                    ?>

                                </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="cataract_RE" name="cataract_RE">
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control" id="catarct_LE" name="catarct_LE">
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    </div>
                    <div style="border:1px solid black;margin-top:10px;" >
                    <table class='table table-striped 'style=' margin-top:10px'>
                        <caption style="text-align:center;">A-SCAN</caption>
                        <thead>
                            <td></td>
                            <td>RE</td>
                            <td>LE</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A-Scan</td>
                                <td>
                                    <input type="text" class="form-control" id="ascan_RE" name="ascan_RE" >
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="ascan_LE" name="ascan_LE" >
                                </td>
                            </tr>
                            <tr>
                                <td>Recomm IOL</td>
                                <td>
                                    <input type="text" class="form-control" id="recomm_RE" name="recomm_RE" >
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="recomm_LE" name="recomm_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>Inserted IOL</td>
                                <td>
                                    <input type="text" class="form-control" id="inserted_RE" name="inserted_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="inserted_LE" name="inserted_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>Incision</td>
                                <td>
                                    <input type="text" class="form-control" id="incision_RE" name="incision_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="incision_LE" name="incision_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>suture</td>
                                <td>
                                    <input type="text" class="form-control" id="suture_RE" name="suture_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="suture_LE" name="suture_LE">
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    </div>
                    <div style="border:1px solid black;margin-top:10px;">
                    <table class="table table-bordered table-striped">
                    <caption style="text-align:center">CHANGES AND CHALLANGES POSSIBLY AFFECTING THE OUTCOME</caption>
                    
                        <tr>
                            <th colspan="2"></th>
                            <th>RE</th>
                            <th colspan="2">LE</th>
                        </tr>
                        <tbody>
                            <tr>
                                <td>Corneal Opasity<td>
                                <td><input type="text" id="corneal_RE" name="corneal_RE" class="form-control"><td>
                                <td><input type="text" id="corneal_LE" name="corneal_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Synechinae<td>
                                <td><input type="text" id="synechinae_RE"  name="synechinae_RE" class="form-control"><td> 
                                <td><input type="text" name="synechinae_LE" id="synechinae_LE" class="form-control"><td>
                            </tr>  
                            <tr>
                                <td>Macular/Ratinal Disease<td>
                                <td><input type="test" id="macular_RE" id="macular_RE" class="form-control"><td>
                                <td><input type="text" id="macular_LE" name="macular_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Glaucoma<td>
                                <td><input type="text" id="glaucoma_RE" name="glaucoma_RE" class="form-control"><td>
                                <td><input type="text" id="glaucoma_LE" name="glaucoma_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Non glaucoma disc disese<td>
                                <td><input type="text" id="non_glaucoma_RE" name="non_glaucoma_RE" class="form-control"><td>
                                <td><input type="text" id="non_glaucoma_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Dislocated Lens<td>
                                <td><input type="text" id="lens_RE" name="lens_RE" class="form-control"><td>
                                <td><input type="text" id="lens_LE" name="lens_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Loose zonules Opasity<td>
                                <td><input type="text" id="loose_RE" name="loose_RE" class="form-control"><td>
                                <td><input type="text" name="loose_LE" id="loose_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Deep Socket<td>
                                <td><input type="text" name="deep_RE" id="deep_RE" class="form-control"><td>
                                <td><input type="text" id="deep_LE" name="deep_LE" class="form-control"><td>
                            </tr>
                            <tr>
                                <td>Small pupil<td>
                                <td><input type="text" name="small_pupil_RE" id="small_pupil_RE" class="form-control"><td>
                                <td><input type="text" name="small_pupil_LE" id="small_pupil_LE"  class="form-control"><td>
                            </tr>
                            
                            <td>Insuffient Block<td>
                                <td><input type="text" name="insufficient_RE" id="insufficient_RE"  class="form-control"><td>
                                <td><input type="text" name="insufficient_LE" id="insufficient_LE" class="form-control"><td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div style="border:1px solid black;margin-top;10px;" >
                    <table class='table table-striped table-hover'>
                        <caption style="text-align:center;">COMPLICATIONS</caption>
                        <thead>
                            <th></th>
                            <th>RE</th>
                            <th>LE</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>None</td>
                                <td>
                                    <input type="text" class="form-control" id="none_RE" name="none_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="none_LE" name="none_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>PC Tear w/o v-loss</td>
                                <td>
                                    <input type="text" class="form-control" id="pc_tear_RE" name="pc_tear_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="pc_tear_LE" name="pc_tear_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>Vitreous loss</td>
                                <td>
                                    <input type="text" class="form-control" id="vitrelous_RE" name="vitrelous_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="vitrelous_LE" name="vitrelous_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>Iris Prolapse</td>
                                <td>
                                    <input type="text" class="form-control" id="prolapse_RE" name="prolapse_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="prolapse_LE" name="prolapse_LE">
                                </td>
                            </tr>
                            <tr>
                                <td>Other,Specify</td>
                                <td>
                                    <input type="text" class="form-control" id="other_specify_RE" name="other_specify_RE">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="other_specify_LE" name="other_specify_LE">
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    </div>
                    <!--div style="border:1px solid black;margin-top;10px;" >
                    <table class='table table-striped table-hover'>
                        <caption style="text-align:center;">EXAMINATION OF THE OPERATED EYE</caption>
                        <thead>
                            <th>Oservation</th>
                            <th>RE</th>
                            <th>LE</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Corneal oedema</td>
                            <td><input type="text" id="corneal_oedema_RE" name="corneal_oedema_RE" class="form-control"></td>
                            <td><input type="text" class="form-control" id="corneal_oedema_LE"></td>
                        </tr>
                        <tr>
                                <td>Knots exposed</td>
                                <td><input type="text" class="form-control" name="knots_exposed_RE" id="knots_exposed_RE"></td>
                                <td><input type="text" class="form-control" name="knots_exposed_LE" id="knots_exposed_LE"></td>
                        </tr>
                        <tr>
                                <td>Fibrin</td>
                                <td><input type="text" class="form-control" name="fibrin_RE" id="fibrin_RE"></td>
                                <td><input type="text" class="form-control" name="fibrin_LE" id="fibrin_LE"></td>
                        </tr>
                        <tr>
                                <td>Hyphaema</td>
                                <td><input type="text" class="form-control" name="hyphaema_RE" id="hyphaema_RE"></td>
                                <td><input type="text" class="form-control" name="hyphaema_LE" id="hyphaema_LE"></td>
                        </tr>
                        <tr>
                                <td>Iris prolapse</td>
                                <td><input type="text" class="form-control" name="iris_prolapse_RE" id="iris_prolapse_RE"></td>
                                <td><input type="text" class="form-control" name="iris_prolapse_LE" id="iris_prolapse_LE"></td>
                        </tr>
                        <tr>
                                <td>Irregular pupil</td>
                                <td><input type="text" class="form-control" name="irregular_pupil_LE" id="irregular_pupil_LE"></td>
                                <td><input type="text" class="form-control" name="irregular_pupil_RE" id="irregular_pupil_RE"></td>
                        </tr>
                        <tr>
                                <td>IOP > 24mmHg</td>
                                <td><input type="text" class="form-control" nme="iopmmg_RE" id="iopmmg_RE"></td>
                                <td><input type="text" class="form-control" name="iopmmg_LE" id="iopmmg_LE"></td>>
                        </tr>
                        
                        <tr>
                            <td>VA W/PIN</td>
                            <td><input type="text" class="form-control" name="VA_WPIN_RE" id="VA_WPIN_RE"></td>
                            <td><input type="text" class="form-control" name="VA_WPIN_LE" id="VA_WPIN_LE" ></td>->
                        </tr>
                            <td>VA W/GLASSES</td>
                            <td><input type="text" class="form-control" name="VA_WGLASSES_RE" id="VA_WGLASSES_RE"></td>
                            <td><input type="text" class="form-control" name="VA_WGLASSES_LE" id="VA_WGLASSES_LE"></td>
                        </tr>
                            <td>IOP</td>
                            <td><input type="text" class="form-control" name="iop_RE" id="iop_RE" ></td>
                            <td><input type="text" class="form-control" name="iop_LE" id="iop_LE"></td>
                        </tr>
                        <tr>
                                <td>Date of Discharge</td>
                                <td colspan="2"><input type="date" class="form-control" nme="discharge_date" id="discharge_date"required></td>

                        </tr>
                        <tr>
                                <td>Return Date</td>
                                <td colspan="2"><input type="date" class="form-control" nme="return_date" id="return_date" required></td>

                        </tr>
                                        
                    </tbody>
                </table>
                </div-->
                
                <div style="border:1px solid black;margin-top;10px;" >
                    <table class='table table-striped table-hover'>
                        <caption style="text-align:center;">ATTACHMENT</caption>
                       
                        <tbody>
                        <tr>
                            <td>STICKER</td>
                            <td colspan="3"><input type="file" id="sticker" name="sticker" class="form-control"    onchange="readURL(this);"></td>
                            
                            <!-- <img id="blah" src="#" alt="your image" style="width:70%;"> -->
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2"><img id="blah" src="#" alt="Your Image Here" /></td>
                        </tr>
                        
                        <tr>
                        <input type="hidden" id='Registration_ID' name='Registration_ID' value="<?php echo $Registration_ID;?>">
                        <input type="hidden" id='Patient_Payment_Item_List_ID' name='Patient_Payment_Item_List_ID' value="<?php echo $Patient_Payment_Item_List_ID ;?>">
                        <input type="hidden" id='consultation_ID' name='consultation_ID' value="<?php echo $consultation_ID;?>">
                        <input type="hidden" id='Patient_Payment_ID' name='Patient_Payment_ID' value="<?php echo $Patient_Payment_ID;?>">
                        <input type="hidden" id='Payment_Item_Cache_List_ID' name='Payment_Item_Cache_List_ID' value="<?php echo $Payment_Item_Cache_List_ID;?>">
                            <td><input type="submit" name="save_optical_data" id="save_optical_data" value="DONE" class="art-button-green" style="align:right;"></td>
                        </tr>
                       
                    </tbody>
                </table>
                </div>
            </div>
            </div>
            
            
            </form>

            
            <input type="button" name="Submit" id="Submit" value="SAVE" class="art-button-green" onclick="Submit_Info()">
            
        </td>
    </tr>
    </table>
</fieldset>


<div id="Medication_Details" style="width:50%;">
    <center id='Medication_Area'>

    </center>
</div>


<div id="Investigation_Details" style="width:50%;">
    <center id='Investigation_Area'>

    </center>
</div>


<div id="Radiology_Details" style="width:50%;">
    <center id='Radiology_Area'>

    </center>
</div>


<div id="Add_Details" style="width:50%;">
    <center id='New_Items_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>
<div id="Add_Preoperative" style="width:50%;">
    <center id='Add_Preoperative_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>
<div id="Add_Postoperative" style="width:50%;">
    <center id='Add_Postoperative_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="Submit_Info">
    <center><b>Are you sure you want to save?</b></center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Submit_Information()">
                <input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Submit_Info2">
    <center><b>Are you sure you want to save without<br/>POST OPERATIVE ORDERS?</b></center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Submit_Information" id="Submit_Information" value="YES" class="art-button-green" onclick="Submit_Information()">
                <input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Submit_Dialog2()">
            </td>
        </tr>
    </table>
</div>

<div id="Select_Surgeon" style="width:50%;">
    <center>
        <table width="100%">
            <tr>
                <td width="40%">
                    <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Surgeon Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Surgeons()" oninput="Search_Surgeons()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED SURGEONS</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Doctors_Area'>
                                    <table width="100%">
                                        <?php
                                            $counter = 1;
                                            $get_surgeons = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_privileges pri where
                                                                        emp.Employee_ID = pri.Employee_ID and
                                                                        pri.Theater_Works = 'yes' and
                                                                        emp.Employee_Type = 'Doctor' and
                                                                        emp.Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                            $surgeon_num = mysqli_num_rows($get_surgeons);
                                            if($surgeon_num > 0){
                                                while ($data = mysqli_fetch_array($get_surgeons)) {
                                        ?>
                                                    <tr>
                                                        <td width="10%">
                                                            <input type="radio" name="D" id="D<?php echo $counter; ?>" onclick="Get_Selected_Surgeon('<?php echo $data['Employee_ID']; ?>')">
                                                        </td>
                                                        <td>
                                                            <label for="D<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
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
                                    <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Surgeons_Area'>
                                        <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;SURGEON NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected surgeons
                                            $selected_surgeon = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Post_operative_ID = '$Post_operative_ID' and
                                                                                pop.Employee_Type = 'Surgeon' and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_surgeons = mysqli_num_rows($selected_surgeon);
                                            if($num > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_surgeon)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedSurgeon" id="SelectedSurgeon" value="REMOVE" class="art-button-green" onclick="Remove_Surgeon(<?php echo $dtz['Participant_ID']; ?>)">
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
                    <table width="100%">
                        <tr>
                            <td width="20%"><b>EXTERNAL SURGEONS</b></td>
                            <td width="65%">
                                <input type="text" name="External_Surgeon" id="External_Surgeon" value="<?php echo $External_Surgeons_Value; ?>" autocomplete="off" placeholder="Exteral Surgeons" style="text-align: center;" onkeypress="Update_External_Participant('Surgeon')" oninput="Update_External_Participant('Surgeon')">
                            </td>
                            <td>
                                <input type="button" name="Close1" id="Close1" class="art-button-green" value="DONE" onclick="Close_Surgeon_Dialog(); Refresh_Surgeon_Values()">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</div>


<div id="Select_Assistant_Surgeon" style="width:50%;">
    <center>
        <table width="100%">
            <tr>
                <td width="40%">
                    <input type="text" name="Assistant_Surgeon_Name" id="Assistant_Surgeon_Name" placeholder="~~~ ~~~ Enter Assistant Surgeon Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Assistant_Surgeons()" oninput="Search_Assistant_Surgeons()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED ASSISTANT SURGEONS</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Assistant_Surgeon_Area'>
                                    <table width="100%">
                                    <?php
                                        $counter = 1;
                                        $get_assistant_surgeon = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_privileges pri where
                                                                                emp.Employee_ID = pri.Employee_ID and
                                                                                Employee_Type = 'Doctor' and
                                                                                pri.Theater_Works = 'yes' and
                                                                                Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                        $assistant_num = mysqli_num_rows($get_assistant_surgeon);
                                        if($assistant_num > 0){
                                            while ($data = mysqli_fetch_array($get_assistant_surgeon)) {
                                    ?>
                                                <tr>
                                                    <td width="10%">
                                                        <input type="radio" name="ASST" id="A<?php echo $counter; ?>" onclick="Get_Selected_Assistant('<?php echo $data['Employee_ID']; ?>')">
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
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Assistant_Surgeon_Area'>
                                    <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;ASSISTANT SURGEON NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected  assistant surgeons
                                            $selected_assistant_surgeon = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Post_operative_ID = '$Post_operative_ID' and
                                                                                pop.Employee_Type = 'Assistant Surgeon' and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_assistant_surgeons = mysqli_num_rows($selected_assistant_surgeon);
                                            if($num > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_assistant_surgeon)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedAssistantSurgeons" id="SelectedAssistantSurgeons" value="REMOVE" class="art-button-green" onclick="Remove_Assistant_Surgeon(<?php echo $dtz['Participant_ID']; ?>)">
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
                    <table width="100%">
                        <tr>
                            <td width="20%"><b>EXTERNAL ASSISTANT SURGEONS</b></td>
                            <td width="65%">
                                <input type="text" name="External_Assistant_Surgeon" id="External_Assistant_Surgeon" value="<?php echo $External_Assistant_Surgeon_Value; ?>" autocomplete="off" placeholder="Exteral Assistant Surgeons" style="text-align: center;" onkeypress="Update_External_Participant('Assistant_Surgeon')" oninput="Update_External_Participant('Assistant_Surgeon')">
                            </td>
                            <td>
                                <input type="button" name="Close2" id="Close2" class="art-button-green" value="DONE" onclick="Close_Assistant_Surgeon_Dialog(); Refresh_Selected_Assistant_Surgeons()">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</div>




<div id="Select_Scrub_Nurse" style="width:50%;">
    <center>
        <table width="100%">
            <tr>
                <td width="40%">
                    <input type="text" name="Nurse_Name" id="Nurse_Name" placeholder="~~~ ~~~ Enter Nurse Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Scrub_Nurse()" oninput="Search_Scrub_Nurse()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED NURSES</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Nurse_Area'>
                                    <table width="100%">
                                    <?php
                                        $counter = 1;
                                        $get_nurse = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Job_Code = 'Scrub Nurse' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                        $nurse_num = mysqli_num_rows($get_nurse);
                                        if($nurse_num > 0){
                                            while ($data = mysqli_fetch_array($get_nurse)) {
                                    ?>
                                                <tr>
                                                    <td width="10%">
                                                        <input type="radio" name="N" id="N<?php echo $counter; ?>" onclick="Get_Selected_Nurse('<?php echo $data['Employee_ID']; ?>')">
                                                    </td>
                                                    <td>
                                                        <label for="N<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
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
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Nurse_Area'>
                                    <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;NURSE NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected nurses
                                            $selected_surgeon = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Post_operative_ID = '$Post_operative_ID' and
                                                                                pop.Employee_Type = 'Nurse' and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_surgeons = mysqli_num_rows($selected_surgeon);
                                            if($num > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_surgeon)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedNurses" id="SelectedNurses" value="REMOVE" class="art-button-green" onclick="Remove_Nurse(<?php echo $dtz['Participant_ID']; ?>)">
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
                    <table width="100%">
                        <tr>
                            <td width="20%"><b>EXTERNAL SCRUB NURSES</b></td>
                            <td width="65%">
                                <input type="text" name="External_Scrub_Nurse" id="External_Scrub_Nurse" value="<?php echo $External_Scrub_Nurse_Value; ?>" autocomplete="off" placeholder="Exteral Scrub Nurses" style="text-align: center;" onkeypress="Update_External_Participant('Scrub_Nurse')" oninput="Update_External_Participant('Scrub_Nurse')">
                            </td>
                            <td>
                                <input type="button" name="Close3" id="Close3" class="art-button-green" value="DONE" onclick="Close_Nurse_Dialog(),Get_Selected_Nurse(0)">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</div>



<div id="Select_Anaesthetic" style="width:50%;">
    <center>
        <table width="100%">
            <tr>
                <td>
                    <input type="text" name="Anaesthetic_Name" id="Anaesthetic_Name" placeholder="~~~ ~~~ Enter Anaesthetic Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Anaesthesis()" oninput="Search_Anaesthesis()">
                </td>
                <td style="text-align: center;">
                    <b>LIST OF SELECTED ANAESTHETIC</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Anaesthetic_Area'>
                                    <table width="100%">
                                    <?php
                                        $counter = 1;
                                        $get_anaesthesis = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Job_Code = 'Anaesthesiologist' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                        $anaesthesis_num = mysqli_num_rows($get_anaesthesis);
                                        if($anaesthesis_num > 0){
                                            while ($data = mysqli_fetch_array($get_anaesthesis)) {
                                    ?>
                                                <tr>
                                                    <td width="10%">
                                                        <input type="radio" name="An" id="An<?php echo $counter; ?>" onclick="Get_Selected_Anaesthersis('<?php echo $data['Employee_ID']; ?>')">
                                                    </td>
                                                    <td>
                                                        <label for="An<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
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
                                <fieldset style='overflow-y: scroll; height: 250px; background-color: white;' id='Selected_Anaesthetic_Area'>
                                    <table width="100%">
                                            <tr>
                                                <td width="4%"><b>SN</b></td>
                                                <td><b>&nbsp;&nbsp;&nbsp;ANAESTHETIC NAME</b></td>
                                                <td width="12%" style="text-align: center;"><b>ACTION</b></td>
                                            </tr>
                                            <tr><td colspan="3"><hr></td></tr>
                                        <?php
                                            //select selected nurses
                                            $selected_surgeon = mysqli_query($conn,"select emp.Employee_Name, Participant_ID from tbl_post_operative_participant pop, tbl_employee emp where
                                                                                pop.Post_operative_ID = '$Post_operative_ID' and
                                                                               (pop.Employee_Type = 'Anaesthetics' OR emp.Employee_Job_Code='Anaesthesiologist')and
                                                                                pop.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                                            $num_selected_surgeons = mysqli_num_rows($selected_surgeon);
                                            if($num > 0){
                                                $temp = 0;
                                                while ($dtz = mysqli_fetch_array($selected_surgeon)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$temp; ?></td>
                                                    <td>&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dtz['Employee_Name']); ?></td>
                                                    <td width="12%" style="text-align: center;">
                                                        <input type="button" name="SelectedNurses" id="SelectedNurses" value="REMOVE" class="art-button-green" onclick="Remove_Anaesthetic(<?php echo $dtz['Participant_ID']; ?>)">
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

                    <table width="100%">
                        <tr>
                            <td width="20%"><b>EXTERNAL ANAESTHETIC</b></td>
                            <td width="65%">
                                <input type="text" name="External_Anaesthetic" id="External_Anaesthetic" value="<?php echo $External_Anaesthetic_Value; ?>" autocomplete="off" placeholder="Exteral Anaesthetic" style="text-align: center;" onkeypress="Update_External_Participant('Anaesthetic')" oninput="Update_External_Participant('Anaesthetic')">
                            </td>
                            <td>
                                <input type="button" name="Close3" id="Close3" class="art-button-green" value="DONE" onclick="Close_Anaesthetic_Dialog(),Get_Selected_Anaesthersis(0)">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</div>

<div id="Select_Incision">
    <table width="100%">
        <tr>
            <td width="25%" style="text-align: right;"><b>Select Incision</b></td>
            <td width="75%">
                <select name="Inc_Value" id="Inc_Value" onchange="Select_Inc()" style="font-size: 12px;">
                    <option selected="selected" value="">~~~ List of Incision ~~~</option>
                    
                    
<!--                    <option>Lower Midline</option>
                    <option>Upper Midline</option>
                    <option>Laparotomy</option>
                    <option>Para medianMc</option>
                    <option>Mc Burney's</option>
                    <option>Lanz</option>
                    <option>Pfannestiel</option>
                    <option>Kocher</option>
                    <option>Transverse</option>
                    <option>Abdiminal</option>
                    <option>Bucket handle</option>
                    <option>Chevron or Mercedes-Benz</option>
                    <option>Median sternotomy</option>
                    <option>Four trocar</option>-->
                    <?php 
                        $sql_select_incision_result=mysqli_query($conn,"SELECT Incision_name FROM tbl_Incision") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_incision_result)>0){
                            while($rows_incision=mysqli_fetch_assoc($sql_select_incision_result)){
                                $Incision_name=$rows_incision['Incision_name'];
                                echo "<option>$Incision_name</option>";
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="Incision_Value" id="Incision_Value" autocomplete="off" placeholder="  Select Incision from list above or type new Incision here" maxlength="100">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;">
                <input type="button" name="Submit_Incision" id="Submit_Incision" class="art-button-green" value="DONE" onclick="Update_Incision()">
                <input type="button" name="Close_Incision" id="Close_Incision" class="art-button-green" value="CANCEL" onclick="Close_Incision_Dialogy()">
            </td>
        </tr>
    </table>
</div>
<!-- ADD STATUS @Mfoy DN -->
<div id="add_status">
    <!-- <p>Add New Status</p><hr> -->
    <input type="text" name="status" id="status">
    <br><br>
    <span>
        <input type="button" style="text-align: right;" class="art-button-green" id="submit_status" value="ADD">
    </span>
</div>
<!-- END @Mfoy DN -->
<div id="Select_Position">
    <table width="100%">
        <tr>
            <td width="25%" style="text-align: right;"><b>Select Position</b></td>
            <td width="75%">
                <select name="Pos_Value" id="Pos_Value" onchange="Change_Position(); Select_Pos()" style="font-size: 15px;">
                    <option selected="selected"></option>
<!--                    <option <?php //if($Position == 'Supine'){ echo "selected='selected'"; } ?>>Supine</option>
                    <option <?php //if($Position == 'Prone'){ echo "selected='selected'"; } ?>>Prone</option>
                    <option <?php //if($Position == 'Left Lateral Decubitus'){ echo "selected='selected'"; } ?>>Left Lateral Decubitus</option>
                    <option <?php //if($Position == 'Right Lateral Decubitus'){ echo "selected='selected'"; } ?>>Right Lateral Decubitus</option>
                    <option <?php //if($Position == 'Lithotomy'){ echo "selected='selected'"; } ?>>Lithotomy</option>
                    <option <?php //if($Position == 'Trendelenburg'){ echo "selected='selected'"; } ?>>Trendelenburg</option>
                    <option <?php //if($Position == 'Reverse Trendelenburg'){ echo "selected='selected'"; } ?>>Reverse Trendelenburg</option>-->
                    <?php 
                        $sql_select_position_result=mysqli_query($conn,"SELECT Position_name FROM tbl_Position") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_position_result)){
                            while($position_rows=mysqli_fetch_assoc($sql_select_position_result)){
                               echo $Position_name=$position_rows['Position_name'];
                                ?>
                                    <option <?php if($Position == $Position_name){ echo "selected='selected'"; } ?>><?= $Position_name ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="Position_Value" id="Position_Value" autocomplete="off" placeholder="  Select Position from list above or type new Position here" maxlength="100">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;">
                <input type="button" name="Submit_Position" id="Submit_Position" class="art-button-green" value="DONE" onclick="Update_Position()">
                <input type="button" name="Close_Position" id="Close_Position" class="art-button-green" value="CANCEL" onclick="Close_Position_Dialogy()">
            </td>
        </tr>
    </table>
</div>

<div id="Preview_Titles_Div">
    <center id="Preview_Area">

    </center>
</div>
<div id="Mandatory">
    <span id="Mandatory_Area">

    </span>
</div>
<div id="Non_Supported_Item">
    <center>
        Selected Item is not supported by <?php echo $Guarantor_Name; ?><br/>
        Please change bill type.
    </center>
</div>
<script type="text/javascript">
    function Update_External_Participant(Participant_Type){
        var External_Participant = document.getElementById("External_"+Participant_Type).value;
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectExternalSurgeon = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectExternalSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectExternalSurgeon.overrideMimeType('text/xml');
        }

        myObjectExternalSurgeon.onreadystatechange = function () {
            dataExternalSurgeon = myObjectExternalSurgeon.responseText;
            if (myObjectExternalSurgeon.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectExternalSurgeon.open('GET', 'Update_External_Surgeon.php?Participant_Type='+Participant_Type+'&Registration_ID='+Registration_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&consultation_ID='+consultation_ID+'&External_Participant='+External_Participant, true);
        myObjectExternalSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Change_Position(){
        var Position = document.getElementById("Position").value;
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectChangePosition = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectChangePosition = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectChangePosition.overrideMimeType('text/xml');
        }

        myObjectChangePosition.onreadystatechange = function () {
            dataPosition = myObjectChangePosition.responseText;
            if (myObjectChangePosition.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectChangePosition.open('GET', 'Post_Operative_Change_Position.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID+'&Position='+Position+'&consultation_ID='+consultation_ID, true);
        myObjectChangePosition.send();
    }
</script>

<script type="text/javascript">
    function Change_Type_Of_Anesthetic(){
        var Type_Of_Anesthetic = document.getElementById("Type_Of_Anesthetic").value;
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var type_of_surgery=$("#type_of_surgery").val();
       var duration_of_surgery=$("#duration_of_surgery").val();
        if (window.XMLHttpRequest) {
            myObjectChangeType = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectChangeType = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectChangeType.overrideMimeType('text/xml');
        }

        myObjectChangeType.onreadystatechange = function () {
            dataRemove = myObjectChangeType.responseText;
            if (myObjectChangeType.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectChangeType.open('GET', 'Post_Operative_Change_Type_Of_Anesthetic.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Type_Of_Anesthetic='+Type_Of_Anesthetic+'&consultation_ID='+consultation_ID+'&type_of_surgery='+type_of_surgery+'&duration_of_surgery='+duration_of_surgery, true);
        myObjectChangeType.send();
    }
</script>

<script type="text/javascript">
    function Submit_Information(){
        $("#Submit_Info").dialog("close");
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Position = document.getElementById("Position").value;
        var cutting_time=$("#cutting_time").val();
        var end_cutting_time=$("#end_cutting_time").val();
        var post_operative_remarks=$("#post_operative_remarks").val();
        // var status = document.getElementById("statuses").value;
        var Type_Of_Anesthetic = document.getElementById("Type_Of_Anesthetic").value;
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Section = '<?php echo $Section; ?>'

        if (window.XMLHttpRequest) {
            myObjectSubmit = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSubmit = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSubmit.overrideMimeType('text/xml');
        }

        myObjectSubmit.onreadystatechange = function () {
            dataSubmit = myObjectSubmit.responseText;
            if (myObjectSubmit.readyState == 4) {
                console.log(dataSubmit);
              document.location = 'performsurgery.php?Section='+Section+'&Registration_ID='+Registration_ID+'&Patient_Payment_ID='+Patient_Payment_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID+'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
            }
        }; //specify name of function that will handle server response........

        myObjectSubmit.open('GET', 'Submit_Postoperative_Information.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Position='+Position+'&Type_Of_Anesthetic='+Type_Of_Anesthetic+'&Patient_Payment_ID='+Patient_Payment_ID+'&Sponsor_ID='+Sponsor_ID+'&cutting_time='+cutting_time+'&end_cutting_time='+end_cutting_time+'&post_operative_remarks='+post_operative_remarks, true);
        myObjectSubmit.send();
    }
</script>


<script type="text/javascript">
    function Submit_Info(){
        var status = document.getElementById("statuses").value;
       var type_of_surgery=$("#type_of_surgery").val();
       var duration_of_surgery=$("#duration_of_surgery").val();
       var cutting_time=$("#cutting_time").val();
       var end_cutting_time=$("#end_cutting_time").val();
       

        if(cutting_time==""){
            $("#cutting_time").css("border","2px solid red");
            $("#cutting_time").focus();
            exit;
        }
        if(end_cutting_time==""){
            $("#end_cutting_time").css("border","2px solid red");
            $("#end_cutting_time").focus();
            exit;
        }
        if(type_of_surgery==""){
            $("#type_of_surgery").css("border","2px solid red");
            $("#type_of_surgery").focus();
            exit;
        }
        if(duration_of_surgery==""){
            $("#duration_of_surgery").css("border","2px solid red");
            $("#duration_of_surgery").focus();
            exit;
        }
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if (window.XMLHttpRequest) {
            myObject_Info = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Info = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Info.overrideMimeType('text/xml');
        }

        myObject_Info.onreadystatechange = function () {
            data_info = myObject_Info.responseText;

            if (myObject_Info.readyState == 4) {

                var Mrejesho = data_info;
                if(Mrejesho == 'yes'){
                    $("#Submit_Info").dialog("open");
                }else if(Mrejesho == 'nop'){
                    $("#Submit_Info2").dialog("open");
                }else{
                    document.getElementById("Mandatory_Area").innerHTML = data_info;
                    $("#Mandatory").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObject_Info.open('GET', 'Submit_Info_Verify_Information.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&status='+status, true);
        myObject_Info.send();
    }
</script>

<script type="text/javascript">
    function Close_Mandatory_Dialog(){
        $("#Mandatory").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Submit_Dialog(){
        $("#Submit_Info").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Submit_Dialog2(){
        $("#Submit_Info2").dialog("close");
    }
</script>

<script type="text/javascript">
    function Remove_Disease(Diagnosis_ID){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectRemove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }

        myObjectRemove.onreadystatechange = function () {
            dataRemove = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
                document.getElementById("Postoperative_Selected_Disease_List_Area").innerHTML = dataRemove;
                Update_Selected_Diagnosis(Payment_Item_Cache_List_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectRemove.open('GET', 'Remove_Selected_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Diagnosis_ID='+Diagnosis_ID+'&consultation_ID='+consultation_ID, true);
        myObjectRemove.send();
    }
</script>


<script type="text/javascript">
    function Preoperative_Remove_Disease(Diagnosis_ID){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPreoperativeRemove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreoperativeRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreoperativeRemove.overrideMimeType('text/xml');
        }

        myObjectPreoperativeRemove.onreadystatechange = function () {
            dataPreoperativeRemove = myObjectPreoperativeRemove.responseText;
            if (myObjectPreoperativeRemove.readyState == 4) {
                document.getElementById("Preoperative_Selected_Disease_List_Area").innerHTML = dataPreoperativeRemove;
                Update_Preoperative_Selected_Diagnosis(Payment_Item_Cache_List_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectPreoperativeRemove.open('GET', 'Preoperative_Remove_Disease.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Diagnosis_ID='+Diagnosis_ID+'&consultation_ID='+consultation_ID, true);
        myObjectPreoperativeRemove.send();
    }
</script>

<script type="text/javascript">
    function Update_Preoperative_Selected_Diagnosis(Payment_Item_Cache_List_ID){
        if (window.XMLHttpRequest) {
            myObjectUpdatePre = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdatePre = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdatePre.overrideMimeType('text/xml');
        }

        myObjectUpdatePre.onreadystatechange = function () {
            dataUpdatePre = myObjectUpdatePre.responseText;
            if (myObjectUpdatePre.readyState == 4) {
                document.getElementById("Preoperative_Diagnosis").value = dataUpdatePre;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdatePre.open('GET', 'Update_Preoperative_Selected_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectUpdatePre.send();
    }
</script>

<script type="text/javascript">
    function Refresh_List_Of_Postoperative_Diagnosis(){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function () {
            dataRefresh = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
                document.getElementById("Postoperative_Selected_Disease_List_Area").innerHTML = dataRefresh;
            }
        }; //specify name of function that will handle server response........

        myObjectRefresh.open('GET', 'Refresh_List_Of_Postoperative_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectRefresh.send();
    }
</script>

<script type="text/javascript">
    function Refresh_List_Of_Preoperative_Diagnosis(){
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function () {
            dataPreRefresh = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
                document.getElementById("Preoperative_Selected_Disease_List_Area").innerHTML = dataPreRefresh;
            }
        }; //specify name of function that will handle server response........

        myObjectRefresh.open('GET', 'Refresh_List_Of_Preoperative_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectRefresh.send();
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
                document.getElementById("Postoperative_Diagnosis").value = dataUpdateDiagnosis;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateDiagnosis.open('GET', 'Update_Disease_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectUpdateDiagnosis.send();
    }
</script>



<script type="text/javascript">
    function Change_Diagnosis(){
        var Diagnosis_Type = document.getElementById("Diagnosis_Type").value;
        if(Diagnosis_Type == 'Preoperative Diagnosis'){
            document.getElementById("Textarea_Area"). innerHTML = "<textarea id='Preoperative_Diagnosis' name='Preoperative_Diagnosis' style='width: 100%; height: 50px;' readonly='readonly'></textarea>";
            document.getElementById("Diagnosis_Button_Area").innerHTML = '<input type="button" value="SELECT DIAGNOSIS" class="art-button-green" onclick="Add_Preoperative_Diagnosis()">';
            Load_Selected_Preoperative();
        }else{
            document.getElementById("Textarea_Area"). innerHTML = "<textarea id='Postoperative_Diagnosis' name='Postoperative_Diagnosis' style='width: 100%; height: 50px;' readonly='readonly'></textarea>";
            document.getElementById("Diagnosis_Button_Area").innerHTML = '<input type="button" value="SELECT DIAGNOSIS" class="art-button-green" onclick="Add_Postoperative_Diagnosis()">';
            Load_Selected_Postoperative();
        }
    }
</script>

<script type="text/javascript">
    function Load_Selected_Preoperative(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectGetPostoperative = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetPostoperative = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPostoperative.overrideMimeType('text/xml');
        }

        myObjectGetPostoperative.onreadystatechange = function (){
            dataPostop = myObjectGetPostoperative.responseText;
            if (myObjectGetPostoperative.readyState == 4) {
                document.getElementById('Preoperative_Diagnosis').value = dataPostop;
            }
        }; //specify name of function that will handle server response........
        myObjectGetPostoperative.open('GET','Load_Selected_Preoperative.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetPostoperative.send();
    }
</script>

<script type="text/javascript">
    function Load_Selected_Postoperative(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectGetPostoperative = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetPostoperative = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPostoperative.overrideMimeType('text/xml');
        }

        myObjectGetPostoperative.onreadystatechange = function (){
            dataPostop = myObjectGetPostoperative.responseText;
            if (myObjectGetPostoperative.readyState == 4) {
                document.getElementById('Postoperative_Diagnosis').value = dataPostop;
            }
        }; //specify name of function that will handle server response........
        myObjectGetPostoperative.open('GET','Load_Selected_Postoperative.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetPostoperative.send();
    }
</script>


<script type="text/javascript">
    function save_Incision(){
        var Incision_Value = document.getElementById("Incision_Value").value;
        $.ajax({
            type:'POST',
            url:'ajax_save_Incision.php',
            data:{Incision_Value:Incision_Value},
            success:function(data){
                console.log("incision feedback-->"+data)
                $("#Select_Incision").dialog("close");
            }
        });
    }
    function save_position(){
        var Position_Value = document.getElementById("Position_Value").value;
        $.ajax({
            type:'POST',
            url:'ajax_save_position.php',
            data:{Position_Value:Position_Value},
            success:function(data){
                console.log("position feedback--->"+data)
                $("#Select_Position").dialog("close");
            }
        });
    }
    function Update_Incision(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Incision_Value = document.getElementById("Incision_Value").value;
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectIncision = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectIncision = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectIncision.overrideMimeType('text/xml');
        }

        myObjectIncision.onreadystatechange = function () {
            dataIn = myObjectIncision.responseText;
            if (myObjectIncision.readyState == 4) {
                document.getElementById("Incision").value = dataIn;
                save_Incision();
            }
        }; //specify name of function that will handle server response........Post_Operative_Update_Previous_Contents

        myObjectIncision.open('GET', 'Update_Incision.php?Incision_Value='+Incision_Value+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectIncision.send();
    }
</script>

<script type="text/javascript">
    function Update_Position(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Position_Value = document.getElementById("Position_Value").value;
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectPosition = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPosition = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPosition.overrideMimeType('text/xml');
        }

        myObjectPosition.onreadystatechange = function () {
            dataPos = myObjectPosition.responseText;
            if (myObjectPosition.readyState == 4) {
                document.getElementById("Position").value = dataPos;
                save_position()
            }
        }; //specify name of function that will handle server response........Post_Operative_Update_Previous_Contents

        myObjectPosition.open('GET', 'Update_Position.php?Position_Value='+Position_Value+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectPosition.send();
    }
</script>


<script type="text/javascript">
    function Select_Inc(){
        var Incision = document.getElementById("Inc_Value").value;
        document.getElementById("Incision_Value").value = Incision;
    }
</script>

<script type="text/javascript">
    function Select_Pos(){
        var Position = document.getElementById("Pos_Value").value;
        document.getElementById("Position_Value").value = Position;
    }
</script>



<script type="text/javascript">
    function Get_Incision(){
        document.getElementById("Inc_Value").value = '';
        document.getElementById("Incision_Value").value = document.getElementById("Incision").value;
        $("#Select_Incision").dialog("open");
    }
</script>
<script type="text/javascript">
    function Get_Positions(){
        document.getElementById("Position_Value").value = '';
        document.getElementById("Position_Value").value = document.getElementById("Position").value;
        $("#Select_Position").dialog("open");
    }
</script>

<!-- @Mfoy dn -->
<script type="text/javascript">
    function add_status(){
        $("#add_status").dialog("open");
    }

    $("#submit_status").click(function (e){
        $.ajax({
        type:'POST',
        url:'ajax_surgery_status.php',
        data:{status:$("#status").val()},
        success:function(data){
            $("#statuses").html(data); 
            $("#add_status").dialog("close");
        }
    });
    });
</script>
<!-- // @Mfoy dn -->

<script type="text/javascript">
    function Close_Incision_Dialogy(){
        $("#Select_Incision").dialog("close");
    }
</script>

<script type="text/javascript">
    function Update_Content(Temp_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var Temp_Data = document.getElementById(Temp_ID).value;
        var consultation_ID = '<?php echo $consultation_ID; ?>';

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
        }; //specify name of function that will handle server response........Post_Operative_Update_Previous_Contents

        myObjectAutoUpdate.open('GET', 'Post_Operative_Auto_Update_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectAutoUpdate.send();
    }
</script>
<script type="text/javascript">
    function Close_Surgeon_Dialog(){
        $("#Select_Surgeon").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Assistant_Surgeon_Dialog(){
        $("#Select_Assistant_Surgeon").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Nurse_Dialog(){
        $("#Select_Scrub_Nurse").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Anaesthetic_Dialog(){
        $("#Select_Anaesthetic").dialog("close");
    }
</script>
<script type="text/javascript">
    function Get_Selected_Surgeon(Employee_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetSurgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetSurgeon.overrideMimeType('text/xml');
        }

        myObjectGetSurgeon.onreadystatechange = function (){
            dataSurgeon = myObjectGetSurgeon.responseText;
            if (myObjectGetSurgeon.readyState == 4) {
                document.getElementById('Surgeon').value = dataSurgeon;
                Refresh_Selected_Surgeons();
            }
        }; //specify name of function that will handle server response........
        myObjectGetSurgeon.open('GET','Get_Selected_Surgeon.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Selected_Surgeons(){
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
                document.getElementById('Selected_Surgeons_Area').innerHTML = dataRefreshSurgeon;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshSurg.open('GET','Refresh_Selected_Surgeons.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshSurg.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Selected_Assistant_Surgeons(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshAssistantSurg = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshAssistantSurg = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshAssistantSurg.overrideMimeType('text/xml');
        }

        myObjectRefreshAssistantSurg.onreadystatechange = function (){
            dataRefreshAssistantSurgeon = myObjectRefreshAssistantSurg.responseText;
            if (myObjectRefreshAssistantSurg.readyState == 4) {
                document.getElementById('Selected_Assistant_Surgeon_Area').innerHTML = dataRefreshAssistantSurgeon;
                get_assistat_surg_list();
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshAssistantSurg.open('GET','Refresh_Selected_Assistant_Surgeons.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshAssistantSurg.send();
    }
    
    function get_assistat_surg_list(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetAssistantSurgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetAssistantSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetAssistantSurgeon.overrideMimeType('text/xml');
        }

        myObjectGetAssistantSurgeon.onreadystatechange = function (){
            dataAssistantSurgeon = myObjectGetAssistantSurgeon.responseText;
            if (myObjectGetAssistantSurgeon.readyState == 4) {
                document.getElementById('Assistant_Surgeon').value = dataAssistantSurgeon;
            }
        }; //specify name of function that will handle server response........
        Employee_ID=0;
        myObjectGetAssistantSurgeon.open('GET','Get_Selected_Assistant_Surgeon.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetAssistantSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Surgeon_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRegresh = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRegresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegresh.overrideMimeType('text/xml');
        }

        myObjectRegresh.onreadystatechange = function (){
            dataRefreshValue = myObjectRegresh.responseText;
            if (myObjectRegresh.readyState == 4) {
                document.getElementById('Surgeon').value = dataRefreshValue;
            }
        }; //specify name of function that will handle server response........
        myObjectRegresh.open('GET','Refresh_Surgeon_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectRegresh.send();
    }
</script>

<script type="text/javascript">
    function Remove_Surgeon(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveSurgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveSurgeon.overrideMimeType('text/xml');
        }

        myObjectRemoveSurgeon.onreadystatechange = function (){
            dataRefreshSurgeon = myObjectRemoveSurgeon.responseText;
            if (myObjectRemoveSurgeon.readyState == 4) {
                document.getElementById('Selected_Surgeons_Area').innerHTML = dataRefreshSurgeon;
                Refresh_Surgeon_Values();
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveSurgeon.open('GET','Remove_Selected_Surgeons.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Remove_Anaesthetic(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveAnaesthetic = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveAnaesthetic = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveAnaesthetic.overrideMimeType('text/xml');
        }

        myObjectRemoveAnaesthetic.onreadystatechange = function (){
            dataRefreshAnaesthetic = myObjectRemoveAnaesthetic.responseText;
            if (myObjectRemoveAnaesthetic.readyState == 4) {
                document.getElementById('Selected_Anaesthetic_Area').innerHTML = dataRefreshAnaesthetic;
                Refresh_Anaesthetic_Values();
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveAnaesthetic.open('GET','Remove_Anaesthetic.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveAnaesthetic.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Anaesthetic_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectRegreshAnaesthetic = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRegreshAnaesthetic = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegreshAnaesthetic.overrideMimeType('text/xml');
        }

        myObjectRegreshAnaesthetic.onreadystatechange = function (){
            dataRefreshAnaesth = myObjectRegreshAnaesthetic.responseText;
            if (myObjectRegreshAnaesthetic.readyState == 4) {
                document.getElementById('Anaesthetic').value = dataRefreshAnaesth;
            }
        }; //specify name of function that will handle server response........
        myObjectRegreshAnaesthetic.open('GET','Refresh_Anaesthetic_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectRegreshAnaesthetic.send();
    }
</script>

<script type="text/javascript">
    function Remove_Assistant_Surgeon(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveAssistantSurgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveAssistantSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveAssistantSurgeon.overrideMimeType('text/xml');
        }

        myObjectRemoveAssistantSurgeon.onreadystatechange = function (){
            dataRefreshAssistantSurgeon = myObjectRemoveAssistantSurgeon.responseText;
            if (myObjectRemoveAssistantSurgeon.readyState == 4) {
                document.getElementById('Selected_Assistant_Surgeon_Area').innerHTML = dataRefreshAssistantSurgeon;
                Refresh_Assistant_Surgeon_Values();
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveAssistantSurgeon.open('GET','Remove_Selected_Assistant_Surgeons.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveAssistantSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Remove_Nurse(Participant_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRemoveNurse = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveNurse = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveNurse.overrideMimeType('text/xml');
        }

        myObjectRemoveNurse.onreadystatechange = function (){
            dataRefreshNurse = myObjectRemoveNurse.responseText;
            if (myObjectRemoveNurse.readyState == 4) {
                document.getElementById('Selected_Assistant_Surgeon_Area').innerHTML = dataRefreshNurse;
                Refresh_Nurses_Values();
                Refresh_Nurse()
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveNurse.open('GET','Remove_Selected_Nurse.php?Participant_ID='+Participant_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
        myObjectRemoveNurse.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Nurses_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshNurse = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshNurse = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshNurse.overrideMimeType('text/xml');
        }

        myObjectRefreshNurse.onreadystatechange = function (){
            dataRefreshNurseValue = myObjectRefreshNurse.responseText;
            if (myObjectRefreshNurse.readyState == 4) {
                document.getElementById('Crub_Nurse').value = dataRefreshNurseValue;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshNurse.open('GET','Refresh_Nurse_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectRefreshNurse.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Assistant_Surgeon_Values(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRegreshAssistantSurgeons = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRegreshAssistantSurgeons = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegreshAssistantSurgeons.overrideMimeType('text/xml');
        }

        myObjectRegreshAssistantSurgeons.onreadystatechange = function (){
            dataRefreshAssistantValue = myObjectRegreshAssistantSurgeons.responseText;
            if (myObjectRegreshAssistantSurgeons.readyState == 4) {
                document.getElementById('Assistant_Surgeon').value = dataRefreshAssistantValue;
            }
        }; //specify name of function that will handle server response........
        myObjectRegreshAssistantSurgeons.open('GET','Refresh_Assistant_Surgeons_Values.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectRegreshAssistantSurgeons.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Assistant(Employee_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetAssistantSurgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetAssistantSurgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetAssistantSurgeon.overrideMimeType('text/xml');
        }

        myObjectGetAssistantSurgeon.onreadystatechange = function (){
            dataAssistantSurgeon = myObjectGetAssistantSurgeon.responseText;
            if (myObjectGetAssistantSurgeon.readyState == 4) {
                document.getElementById('Assistant_Surgeon').value = dataAssistantSurgeon;
                Refresh_Selected_Assistant_Surgeons();
            }
        }; //specify name of function that will handle server response........
        myObjectGetAssistantSurgeon.open('GET','Get_Selected_Assistant_Surgeon.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetAssistantSurgeon.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Nurse(Employee_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetNurse = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetNurse = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetNurse.overrideMimeType('text/xml');
        }

        myObjectGetNurse.onreadystatechange = function (){
            Nursedata = myObjectGetNurse.responseText;
            if (myObjectGetNurse.readyState == 4) {
                document.getElementById('Crub_Nurse').value = Nursedata;
                Refresh_Nurse();
            }
        }; //specify name of function that will handle server response........
        myObjectGetNurse.open('GET','Get_Selected_Nurse.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetNurse.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Nurse(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshNurseArea = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshNurseArea = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshNurseArea.overrideMimeType('text/xml');
        }

        myObjectRefreshNurseArea.onreadystatechange = function (){
            dataRefreshNurseArea = myObjectRefreshNurseArea.responseText;
            if (myObjectRefreshNurseArea.readyState == 4) {
                document.getElementById('Selected_Nurse_Area').innerHTML = dataRefreshNurseArea;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshNurseArea.open('GET','Refresh_Nurse.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshNurseArea.send();
    }
</script>


<script type="text/javascript">
    function Get_Selected_Anaesthersis(Employee_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetAnaesthersis = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetAnaesthersis = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetAnaesthersis.overrideMimeType('text/xml');
        }

        myObjectGetAnaesthersis.onreadystatechange = function (){
            Anaesthersisdata = myObjectGetAnaesthersis.responseText;
            if (myObjectGetAnaesthersis.readyState == 4) {
                document.getElementById('Anaesthetic').value = Anaesthersisdata;
                Refresh_Anaesthersis();
            }
        }; //specify name of function that will handle server response........
        myObjectGetAnaesthersis.open('GET','Get_Selected_Anaesthersis.php?Employee_ID='+Employee_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetAnaesthersis.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Anaesthersis(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectRefreshAnaesthersis = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefreshAnaesthersis = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshAnaesthersis.overrideMimeType('text/xml');
        }

        myObjectRefreshAnaesthersis.onreadystatechange = function (){
            dataRefreshAnaesthersis = myObjectRefreshAnaesthersis.responseText;
            if (myObjectRefreshAnaesthersis.readyState == 4) {
                document.getElementById('Selected_Anaesthetic_Area').innerHTML = dataRefreshAnaesthersis;
            }
        }; //specify name of function that will handle server response........
        myObjectRefreshAnaesthersis.open('GET','Refresh_Anaesthersis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectRefreshAnaesthersis.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Preoperative_Diagnosis(Disease_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectGetPreoperative = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetPreoperative = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPreoperative.overrideMimeType('text/xml');
        }

        myObjectGetPreoperative.onreadystatechange = function (){
            dataPreop = myObjectGetPreoperative.responseText;
            if (myObjectGetPreoperative.readyState == 4) {
                document.getElementById('Preoperative_Diagnosis').value = dataPreop;
                Refresh_List_Of_Preoperative_Diagnosis();
            }
        }; //specify name of function that will handle server response........
        myObjectGetPreoperative.open('GET','Get_Selected_Preoperative_Diagnosis.php?Disease_ID='+Disease_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetPreoperative.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Postoperative_Diagnosis(Disease_ID){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectGetPostoperative = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetPostoperative = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPostoperative.overrideMimeType('text/xml');
        }

        myObjectGetPostoperative.onreadystatechange = function (){
            dataPostop = myObjectGetPostoperative.responseText;
            if (myObjectGetPostoperative.readyState == 4) {
                document.getElementById('Postoperative_Diagnosis').value = dataPostop;
                Refresh_List_Of_Postoperative_Diagnosis();
            }
        }; //specify name of function that will handle server response........
        myObjectGetPostoperative.open('GET','Get_Selected_Postoperative_Diagnosis.php?Disease_ID='+Disease_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectGetPostoperative.send();
    }
</script>

<script type="text/javascript">
    function Search_Anaesthesis(){
        var Anaesthetic_Name = document.getElementById("Anaesthetic_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Anaesthetic = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Anaesthetic = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Anaesthetic.overrideMimeType('text/xml');
        }

        myObject_Search_Anaesthetic.onreadystatechange = function (){
            dataAnaesthetic = myObject_Search_Anaesthetic.responseText;
            if (myObject_Search_Anaesthetic.readyState == 4) {
                document.getElementById('Anaesthetic_Area').innerHTML = dataAnaesthetic;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Anaesthetic.open('GET','Search_Anaesthetic.php?Anaesthetic_Name='+Anaesthetic_Name,true);
        myObject_Search_Anaesthetic.send();
    }
</script>
<script type="text/javascript">
    function Search_Scrub_Nurse(){
        var Nurse_Name = document.getElementById("Nurse_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Nurse = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Nurse = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Nurse.overrideMimeType('text/xml');
        }

        myObject_Search_Nurse.onreadystatechange = function (){
            dataNurse = myObject_Search_Nurse.responseText;
            if (myObject_Search_Nurse.readyState == 4) {
                document.getElementById('Nurse_Area').innerHTML = dataNurse;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Nurse.open('GET','Search_Nurses.php?Nurse_Name='+Nurse_Name,true);
        myObject_Search_Nurse.send();
    }
</script>

<script type="text/javascript">
    function Search_Surgeons(){
        var Doctror_Name = document.getElementById("Doc_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Surgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Surgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Surgeon.overrideMimeType('text/xml');
        }

        myObject_Search_Surgeon.onreadystatechange = function (){
            data9887 = myObject_Search_Surgeon.responseText;
            if (myObject_Search_Surgeon.readyState == 4) {
                document.getElementById('Doctors_Area').innerHTML = data9887;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Surgeon.open('GET','Search_Surgeons.php?Doctror_Name='+Doctror_Name,true);
        myObject_Search_Surgeon.send();
    }
</script>

<script type="text/javascript">
    function Search_Assistant_Surgeons(){
        var Assistant_Surgeon_Name = document.getElementById("Assistant_Surgeon_Name").value;
        if(window.XMLHttpRequest){
            myObject_Search_Assistant_Surgeon = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Assistant_Surgeon = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Assistant_Surgeon.overrideMimeType('text/xml');
        }

        myObject_Search_Assistant_Surgeon.onreadystatechange = function (){
            data = myObject_Search_Assistant_Surgeon.responseText;
            if (myObject_Search_Assistant_Surgeon.readyState == 4) {
                document.getElementById('Assistant_Surgeon_Area').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Assistant_Surgeon.open('GET','Search_Assistant_Surgeons.php?Assistant_Surgeon_Name='+Assistant_Surgeon_Name,true);
        myObject_Search_Assistant_Surgeon.send();
    }
</script>

<script type="text/javascript">
    function Change_Description_Title(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        var Previous_Title = document.getElementById("Title_Area").innerHTML;
        var Temp_ID = '';
        var Temp_Data = '';
        if(Previous_Title == 'Procedure description and closure'){
            Temp_ID = 'Procedure_And_Description';
            Temp_Data = document.getElementById("Procedure_And_Description").value;
        }else if(Previous_Title == 'Identification of prosthesis'){
            Temp_ID = 'Identification';
            Temp_Data = document.getElementById("Identification").value;
        }else if(Previous_Title == 'Estimated blood loss'){
            Temp_ID = 'Estimated_Blood';
            Temp_Data = document.getElementById("Estimated_Blood").value;
        }else if(Previous_Title == 'Problems /Complications'){
            Temp_ID = 'Problems';
            Temp_Data = document.getElementById("Problems").value;
        }else if(Previous_Title == 'Drains'){
            Temp_ID = 'Drains';
            Temp_Data = document.getElementById("Drains").value;
        }else if(Previous_Title == 'Specimen taken'){
            Temp_ID = 'Specimen';
            Temp_Data = document.getElementById("Specimen").value;
        }else if(Previous_Title == 'Postoperative orders'){
            Temp_ID = 'Postoperative';
            Temp_Data = document.getElementById("Postoperative").value;
        }

        var Title = document.getElementById("Title").value;
        if(Title != '~~~ Select Title ~~~'){
            if(Title == 'Procedure description and closure' || Title == 'Specimen taken' || Title == 'Postoperative orders'){
                document.getElementById("Title_Area").innerHTML = '<span style="color: black;"><b>****</b></span>'+Title;
            }else{
                document.getElementById("Title_Area").innerHTML = Title;
            }
            if(Title == 'Procedure description and closure'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Procedure_And_Description" name="Procedure_And_Description" oninput="Update_Content(\'Procedure_And_Description\')" onkeypress="Update_Content(\'Procedure_And_Description\')")></textarea>';
            }else if(Title == 'Identification of prosthesis'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Identification" name="Identification" oninput="Update_Content(\'Identification\')" onkeypress="Update_Content(\'Identification\')"></textarea>';
            }else if(Title == 'Estimated blood loss'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Estimated_Blood" name="Estimated_Blood" oninput="Update_Content(\'Estimated_Blood\')" onkeypress="Update_Content(\'Estimated_Blood\')"></textarea>';
            }else if(Title == 'Problems /Complications'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Problems" name="Problems" oninput="Update_Content(\'Problems\')" onkeypress="Update_Content(\'Problems\')"></textarea>';
            }else if(Title == 'Drains'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Drains" name="Drains" oninput="Update_Content(\'Drains\')" onkeypress="Update_Content(\'Drains\')"></textarea>';
            }else if(Title == 'Specimen taken'){
                Hide_Pharmacy_Dialog();
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Specimen" name="Specimen" oninput="Update_Content(\'Specimen\')" onkeypress="Update_Content(\'Specimen\')"></textarea>';
            }else if(Title == 'Postoperative orders'){
                document.getElementById("My_Textarea").innerHTML = '<textarea style="width: 100%; height: 90px; resize: none;" id="Postoperative" name="Postoperative" readonly="readonly"></textarea>';
                Display_Pharmacy_Dialog();
            }else{
                Hide_Pharmacy_Dialog();
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
                if(Title == 'Postoperative orders'){
                    Display_Pharmaceutical_And_Lab_Test_Given();
                }else{
                    Get_Previous_Data();
                }
            }
        }; //specify name of function that will handle server response........

        myObjectUpdate.open('GET', 'Post_Operative_Update_Previous_Contents.php?Temp_ID='+Temp_ID+'&Temp_Data='+Temp_Data+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectUpdate.send();
    }
</script>

<script type="text/javascript">
    function Display_Pharmacy_Dialog(){
            document.getElementById("Pharmacy_Button").style.visibility = 'visible';
            document.getElementById("Laboratory_Button").style.visibility = 'visible';
            document.getElementById("Radiology_Button").style.visibility = 'visible';
    }
</script>

<script type="text/javascript">
    function Hide_Pharmacy_Dialog(){
            document.getElementById("Pharmacy_Button").style.visibility = 'hidden';
            document.getElementById("Laboratory_Button").style.visibility = 'hidden';
            document.getElementById("Radiology_Button").style.visibility = 'hidden';
    }
</script>

<script type="text/javascript">
    function Display_Pharmaceutical_And_Lab_Test_Given(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObject_Pha_Lab = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Pha_Lab = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Pha_Lab.overrideMimeType('text/xml');
        }

        myObject_Pha_Lab.onreadystatechange = function () {
            dataPL = myObject_Pha_Lab.responseText;
            if (myObject_Pha_Lab.readyState == 4) {
                document.getElementById('Postoperative').value = dataPL;
            }
        }; //specify name of function that will handle server response........

        myObject_Pha_Lab.open('GET', 'Display_Pharmaceutical_And_Lab_Test_Given.php?consultation_ID='+consultation_ID, true);
        myObject_Pha_Lab.send();
    }
</script>

<script type="text/javascript">
    function Get_Previous_Data(){
        var Title = document.getElementById("Title").value;

        if(Title == 'Procedure description and closure'){
            Temp_ID = 'Procedure_And_Description';
        }else if(Title == 'Identification of prosthesis'){
            Temp_ID = 'Identification';
        }else if(Title == 'Estimated blood loss'){
            Temp_ID = 'Estimated_Blood';
        }else if(Title == 'Problems /Complications'){
            Temp_ID = 'Problems';
        }else if(Title == 'Drains'){
            Temp_ID = 'Drains';
        }else if(Title == 'Specimen taken'){
            Temp_ID = 'Specimen';
        }else if(Title == 'Postoperative orders'){
            Temp_ID = 'Postoperative';
        }

        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

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

        myObjectGetPrevious.open('GET', 'Post_Operative_Get_Previous_Contents.php?Temp_ID='+Temp_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectGetPrevious.send();
    }
</script>


<script type="text/javascript">
    function Search_Via_Disease_Code(){
        var Disease_Code = document.getElementById("Disease_Code").value;
        document.getElementById("Disease_Name").value = '';
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
                document.getElementById('Diseases_Fieldset').innerHTML = data12;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch1.open('GET', 'Search_Via_Disease_Code.php?subcategory_ID='+subcategory_ID+'&Disease_Code='+Disease_Code, true);
        myObjectSearch1.send();
    }
</script>

<script type="text/javascript">
    function Search_Via_Disease_Code_Pre(){
        var Pre_Disease_Code = document.getElementById("Pre_Disease_Code").value;
        document.getElementById("Pre_Disease_Name").value = '';
        var Pre_subcategory_ID = document.getElementById("Pre_subcategory_ID").value;

        if (window.XMLHttpRequest) {
            myObjectSearch4 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch4 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch4.overrideMimeType('text/xml');
        }

        myObjectSearch4.onreadystatechange = function () {
            data129 = myObjectSearch4.responseText;
            if (myObjectSearch4.readyState == 4) {
                document.getElementById('Diseases_Fieldset_Pre').innerHTML = data129;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch4.open('GET', 'Search_Via_Disease_Code_Pre.php?Pre_subcategory_ID='+Pre_subcategory_ID+'&Pre_Disease_Code='+Pre_Disease_Code, true);
        myObjectSearch4.send();
    }
</script>

<script type="text/javascript">
    function Search_Via_Disease_Name(){
        var Disease_Name = document.getElementById("Disease_Name").value;
        document.getElementById("Disease_Code").value = '';
        var subcategory_ID = document.getElementById("subcategory_ID").value;

        if (window.XMLHttpRequest) {
            myObjectSearch2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch2.overrideMimeType('text/xml');
        }

        myObjectSearch2.onreadystatechange = function () {
            data13 = myObjectSearch2.responseText;
            if (myObjectSearch2.readyState == 4) {
                document.getElementById('Diseases_Fieldset').innerHTML = data13;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch2.open('GET', 'Search_Via_Disease_Name.php?subcategory_ID='+subcategory_ID+'&Disease_Name='+Disease_Name, true);
        myObjectSearch2.send();
    }
</script>

<script type="text/javascript">
    function Search_Via_Disease_Name_Pre(){
        var Pre_Disease_Name = document.getElementById("Pre_Disease_Name").value;
        document.getElementById("Pre_Disease_Code").value = '';
        var Pre_subcategory_ID = document.getElementById("Pre_disease_category_ID").value;

        if (window.XMLHttpRequest) {
            myObjectSearch3 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch3 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch3.overrideMimeType('text/xml');
        }

        myObjectSearch3.onreadystatechange = function () {
            data139 = myObjectSearch3.responseText;
            if (myObjectSearch3.readyState == 4) {
                document.getElementById('Diseases_Fieldset_Pre').innerHTML = data139;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch3.open('GET', 'Search_Via_Disease_Name_Pre.php?Pre_subcategory_ID='+Pre_subcategory_ID+'&Pre_Disease_Name='+Pre_Disease_Name, true);
        myObjectSearch3.send();
    }
</script>

<script type="text/javascript">
    function Get_Diseases(){
        document.getElementById("Disease_Name").value = '';
        document.getElementById("Disease_Code").value = '';
        var subcategory_ID = document.getElementById("subcategory_ID").value;

        if (window.XMLHttpRequest) {
            myObjectGetDisease = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDisease = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDisease.overrideMimeType('text/xml');
        }

        myObjectGetDisease.onreadystatechange = function () {
            data14 = myObjectGetDisease.responseText;
            if (myObjectGetDisease.readyState == 4) {
                document.getElementById('Diseases_Fieldset').innerHTML = data14;
            }
        }; //specify name of function that will handle server response........

        myObjectGetDisease.open('GET', 'Search_Via_Disease_Name.php?subcategory_ID='+subcategory_ID+'&Disease_Name='+Disease_Name, true);
        myObjectGetDisease.send();
    }
</script>

<script type="text/javascript">
    function Add_Items() {
        if (window.XMLHttpRequest) {
            myObjectAddItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function () {
            dataAddItem = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById('New_Items_Area').innerHTML = dataAddItem;
                $("#Add_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectAddItem.open('GET', 'Postoperative_Add_Items.php', true);
        myObjectAddItem.send();
    }
</script>

<script type="text/javascript">
    function Open_Medication_Dialogy(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Section = '<?php echo $Section; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectMed_Details = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMed_Details = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMed_Details.overrideMimeType('text/xml');
        }

        myObjectMed_Details.onreadystatechange = function () {
            dataAddMedication = myObjectMed_Details.responseText;
            if (myObjectMed_Details.readyState == 4) {
                document.getElementById('Medication_Area').innerHTML = dataAddMedication;
                $("#Medication_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectMed_Details.open('GET', 'Postoperative_Add_Pharmaceutical_Items.php?consultation_ID='+consultation_ID+'&Section='+Section+'&Registration_ID='+Registration_ID, true);
        myObjectMed_Details.send();
    }
</script>

<script type="text/javascript">
    function Open_Labodatory_Dialogy(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Section = '<?php echo $Section; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectLab_Details = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectLab_Details = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectLab_Details.overrideMimeType('text/xml');
        }

        myObjectLab_Details.onreadystatechange = function () {
            dataAddLab = myObjectLab_Details.responseText;
            if (myObjectLab_Details.readyState == 4) {
                document.getElementById('Investigation_Area').innerHTML = dataAddLab;
                $("#Investigation_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectLab_Details.open('GET', 'Postoperative_Add_Laboratory_Items.php?consultation_ID='+consultation_ID+'&Section='+Section+'&Registration_ID='+Registration_ID, true);
        myObjectLab_Details.send();
    }
    function Open_Radiology_Dialogy(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Section = '<?php echo $Section; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectLab_Details = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectLab_Details = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectLab_Details.overrideMimeType('text/xml');
        }

        myObjectLab_Details.onreadystatechange = function () {
            dataAddLab = myObjectLab_Details.responseText;
            if (myObjectLab_Details.readyState == 4) {
                document.getElementById('Radiology_Area').innerHTML = dataAddLab;
                $("#Radiology_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectLab_Details.open('GET', 'Postoperative_Add_Radiology_Items.php?consultation_ID='+consultation_ID+'&Section='+Section+'&Registration_ID='+Registration_ID+'&Sponsor_ID='+Sponsor_ID, true);
        myObjectLab_Details.send();
    }
</script>

<script type="text/javascript">
    function Add_Preoperative_Diagnosis(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPre = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPre = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPre.overrideMimeType('text/xml');
        }

        myObjectPre.onreadystatechange = function () {
            dataPre = myObjectPre.responseText;
            if (myObjectPre.readyState == 4) {
                document.getElementById('Add_Preoperative_Area').innerHTML = dataPre;
                $("#Add_Preoperative").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPre.open('GET', 'Add_Preoperative_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectPre.send();
    }
</script>

<script type="text/javascript">
    function Close_Preoperative_Dialog(){
        $("#Add_Preoperative").dialog("close");
    }
</script>

<script type="text/javascript">
    function Add_Postoperative_Diagnosis(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Add_Postoperative_Area').innerHTML = dataPost;
                $("#Add_Postoperative").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'Add_Postoperative_Diagnosis.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectPost.send();
    }
</script>
<script type="text/javascript">
    function Select_Surgeon(){
        $("#Select_Surgeon").dialog("open");
    }
</script>

<script type="text/javascript">
    function Select_Assistant_Surgeon(){
        $("#Select_Assistant_Surgeon").dialog("open");
    }
</script>

<script type="text/javascript">
    function Select_Scrub_Nurse(){
        $("#Select_Scrub_Nurse").dialog("open");
    }
</script>

<script type="text/javascript">
    function Select_Anaesthetic(){
        $("#Select_Anaesthetic").dialog("open");
    }
</script>
<script type="text/javascript">
    function Close_Postoperative_Dialogy(){
        $("#Add_Postoperative").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Preoperative_Dialogy(){
        $("#Add_Preoperative").dialog("close");
    }
</script>

<script type="text/javascript">
    function Get_Sub_Categories(){
        var disease_category_ID = document.getElementById("disease_category_ID").value;
        document.getElementById("Disease_Name").value = '';
        document.getElementById("Disease_Code").value = '';

        if (window.XMLHttpRequest) {
            myObjectGetSub = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetSub = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetSub.overrideMimeType('text/xml');
        }

        myObjectGetSub.onreadystatechange = function () {
            data99 = myObjectGetSub.responseText;
            if (myObjectGetSub.readyState == 4) {
                document.getElementById('Disease_Sub_Category_Area').innerHTML = data99;
                //$("#Add_Postoperative").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetSub.open('GET', 'Postoperative_Get_Disease_Sub_Categories.php?disease_category_ID='+disease_category_ID, true);
        myObjectGetSub.send();
    }
</script>

<script type="text/javascript">
    function Get_Sub_Categories_Pre(){
        var Pre_disease_category_ID = document.getElementById("Pre_disease_category_ID").value;
        document.getElementById("Pre_Disease_Name").value = '';
        document.getElementById("Pre_Disease_Code").value = '';

        if (window.XMLHttpRequest) {
            myObjectGetPre = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetPre = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetPre.overrideMimeType('text/xml');
        }

        myObjectGetPre.onreadystatechange = function () {
            data99 = myObjectGetPre.responseText;
            if (myObjectGetPre.readyState == 4) {
                document.getElementById('Pre_Disease_Sub_Category_Area').innerHTML = data99;
                //$("#Add_Preoperative").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetPre.open('GET', 'Preoperative_Get_Disease_Sub_Categories.php?Pre_disease_category_ID='+Pre_disease_category_ID, true);
        myObjectGetPre.send();
    }
</script>

<script type="text/javascript">
    function Preview_Title(){
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
                document.getElementById('Preview_Area').innerHTML = data990;
                $("#Preview_Titles_Div").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreview.open('GET','Post_Operative_Preview.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPreview.send();
    }
</script>

<script>
    function Get_Details(Item_Name, Item_ID) {
        document.getElementById('Quantity').value = 1;
        document.getElementById('Dosage').value = '';
        var Temp = '';
        if (window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
    }
</script>



<script>
    function Get_Details_Laboratory(Item_Name, Item_ID) {
        document.getElementById('Quantity').value = 1;
        document.getElementById('Comment').value = '';
        var Temp = '';
        if (window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
    }
</script>

<script type="text/javascript">
    function Get_Item_Name(Item_Name,Item_ID){
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        document.getElementById("Quantity").value = 1;
        if(Transaction_Type == 'Credit'){
            if (window.XMLHttpRequest) {
                My_Object_Verify_Item = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Verify_Item.overrideMimeType('text/xml');
            }

            My_Object_Verify_Item.onreadystatechange = function () {
                dataVerify = My_Object_Verify_Item.responseText;
                if (My_Object_Verify_Item.readyState == 4) {
                    var feedback = dataVerify;
                    if(feedback == 'yes'){
                        Get_Details(Item_Name,Item_ID);
                    }else{
                        document.getElementById("Price").value = 0;
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Price").value = '';
                        $("#Non_Supported_Item").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID, true);
            My_Object_Verify_Item.send();
        }else{
            Get_Details(Item_Name,Item_ID);
        }
    }
</script>

<script type="text/javascript">
    function Get_Item_Name_Laboratory(Item_Name,Item_ID){
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        document.getElementById("Quantity").value = 1;
        if(Transaction_Type == 'Credit'){
            if (window.XMLHttpRequest) {
                My_Object_Verify_Item = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Verify_Item.overrideMimeType('text/xml');
            }

            My_Object_Verify_Item.onreadystatechange = function () {
                dataVerify = My_Object_Verify_Item.responseText;
                if (My_Object_Verify_Item.readyState == 4) {
                    var feedback = dataVerify;
                    if(feedback == 'yes'){
                        Get_Details_Laboratory(Item_Name,Item_ID);
                    }else{
                        document.getElementById("Price").value = 0;
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Price").value = '';
                        $("#Non_Supported_Item").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID, true);
            My_Object_Verify_Item.send();
        }else{
            Get_Details_Laboratory(Item_Name,Item_ID);
        }
    }
</script>

<script type="text/javascript">
    function getItemsList_Laboratory(){
        document.getElementById("Search_Value").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data2650 = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data2650;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','Post_Operative_Get_List_Of_Laboratory_Items_List.php?Item_Category_ID='+Item_Category_ID+'&Guarantor_Name='+Guarantor_Name+'&Sponsor_ID='+Sponsor_ID,true);
        myObject.send();
    }
</script>

<script>
    function Get_Item_Price(Item_ID, Guarantor_Name) {

        // alert(Guarantor_Name)

        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Billing_Type = '';
        if(Transaction_Type == 'Credit'){
            Billing_Type = 'Outpatient Credit';
        }else{
            Billing_Type = 'Outpatient Cash';
        }

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObject.onreadystatechange = function () {
            data = myObject.responseText;

            if (myObject.readyState == 4) {
                document.getElementById('Price').value = data;
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type+'&Sponsor_ID='+ "<?=$Sponsor_ID ?>", true);
        myObject.send();
    }
</script>

<script>
    function Get_Selected_Item() {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Section = '<?php echo strtolower($Section); ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Billing_Type = '';

        if(Section == 'inpatient' && Transaction_Type == 'Credit'){
            Billing_Type = 'Inpatient Credit';
        }else if(Section == 'inpatient' && Transaction_Type == 'Cash'){
            Billing_Type = 'Inpatient Cash';
        }else if(Section == 'outpatient' && Transaction_Type == 'Cash'){
            Billing_Type = 'Outpatient Cash';
        }else{
            Billing_Type = 'Outpatient Credit';
        }
        var Item_ID = document.getElementById("Item_ID").value;
        var Item_Name = document.getElementById("Item_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Dosage = document.getElementById("Dosage").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

        var Price = document.getElementById("Price").value;
        if (parseFloat(Price) > 0) {

        } else {
            if( Item_ID != null && Item_ID != ''){
                alert('Selected Item missing price.');
                exit;
            }
        }

        if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Dosage != null && Dosage != '' && Sub_Department_ID != null && Sub_Department_ID != '') {
            if (window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }
            myObject2.onreadystatechange = function () {
                dataAddM = myObject2.responseText;
                if (myObject2.readyState == 4) {
                    document.getElementById('Selected_Medications_Area').innerHTML = dataAddM;
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Price").value = '';
                    document.getElementById("Dosage").value = '';
                    document.getElementById("Search_Value").focus();
                    Display_Pharmaceutical_And_Lab_Test_Given();
                }
            }; //specify name of function that will handle server response........

            myObject2.open('GET', 'Post_Operative_Add_Pharmaceutical.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Dosage=' + Dosage+'&Transaction_Type='+Transaction_Type+'&consultation_ID='+consultation_ID+'&Sub_Department_ID='+Sub_Department_ID, true);
            myObject2.send();

        } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
            alertMessage();
        } else {
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").value = 1;
            }

            if(Sub_Department_ID == '' || Sub_Department_ID == null){
                document.getElementById("Sub_Department_ID").focus();
                document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
            }else{
                document.getElementById("Sub_Department_ID").style = 'border: 2px solid black';
            }

            if (Dosage == '' || Dosage == null) {
                document.getElementById("Dosage").focus();
                document.getElementById("Dosage").style = 'border: 3px solid red';
            } else {
                document.getElementById("Dosage").style = 'border: 2px solid black';
            }
        }
    }
</script>

<script type="text/javascript">
    function Get_Selected_Item_Laboratory(Check_in_type="Laboratory"){
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Section = '<?php echo strtolower($Section); ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Billing_Type = '';

        if(Section == 'inpatient' && Transaction_Type == 'Credit'){
            Billing_Type = 'Inpatient Credit';
        }else if(Section == 'inpatient' && Transaction_Type == 'Cash'){
            Billing_Type = 'Inpatient Cash';
        }else if(Section == 'outpatient' && Transaction_Type == 'Cash'){
            Billing_Type = 'Outpatient Cash';
        }else{
            Billing_Type = 'Outpatient Credit';
        }
        var Item_ID = document.getElementById("Item_ID").value;
        var Item_Name = document.getElementById("Item_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Comment = document.getElementById("Comment").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

        var Price = document.getElementById("Price").value;
        if (parseFloat(Price) > 0) {

        } else {
            if( Item_ID != null && Item_ID != ''){
                alert('Selected Item missing price.');
                exit;
            }
        }

        if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Sub_Department_ID != null && Sub_Department_ID != '') {
            if (window.XMLHttpRequest) {
                myObjectLabs = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectLabs = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectLabs.overrideMimeType('text/xml');
            }
            myObjectLabs.onreadystatechange = function () {
                dataLab = myObjectLabs.responseText;
                if (myObjectLabs.readyState == 4) {
                    document.getElementById('Selected_Investigation_Area').innerHTML = dataLab;
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Price").value = '';
                    document.getElementById("Comment").value = '';
                    document.getElementById("Search_Value").focus();
                    Display_Pharmaceutical_And_Lab_Test_Given();
                }
            }; //specify name of function that will handle server response........

            myObjectLabs.open('GET', 'Post_Operative_Add_Laboratory.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Comment=' + Comment+'&Transaction_Type='+Transaction_Type+'&consultation_ID='+consultation_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Check_in_type='+Check_in_type, true);
            myObjectLabs.send();

        } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
            alertMessage();
        } else {
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").value = 1;
            }

            if(Sub_Department_ID == '' || Sub_Department_ID == null){
                document.getElementById("Sub_Department_ID").focus();
                document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
            }else{
                document.getElementById("Sub_Department_ID").style = 'border: 2px solid black';
            }
        }
    }
</script>

<script type="text/javascript">
    function getItemsList(Item_Category_ID){
        document.getElementById("Search_Value").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data265 = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data265;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','Post_Operative_Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID='+Item_Category_ID+'&Guarantor_Name='+Guarantor_Name,true);
        myObject.send();
   }
</script>

<script type="text/javascript">
    function Remove_Medication(Payment_Item_Cache_List_ID){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            dataRem = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Selected_Medications_Area').innerHTML = dataRem;
                Display_Pharmaceutical_And_Lab_Test_Given();
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','Post_Operative_Remove_Medication.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&consultation_ID='+consultation_ID,true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function Remove_Investigation(Payment_Item_Cache_List_ID){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if(window.XMLHttpRequest) {
            myObjectlb = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectlb = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectlb.overrideMimeType('text/xml');
        }

        myObjectlb.onreadystatechange = function (){
            dataRemLab = myObjectlb.responseText;
            if (myObjectlb.readyState == 4) {
                document.getElementById('Selected_Investigation_Area').innerHTML = dataRemLab;
                Display_Pharmaceutical_And_Lab_Test_Given();
            }
        }; //specify name of function that will handle server response........
        myObjectlb.open('GET','Post_Operative_Remove_Laboratory.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&consultation_ID='+consultation_ID,true);
        myObjectlb.send();
    }
</script>

<script type="text/javascript">
    function Remove_Medication_Warning(){
        $("#Medication_Warning").dialog("open");
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(){
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Quantity").value = '';
        document.getElementById("Price").value = '';
        var Search_Value = document.getElementById("Search_Value").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if(window.XMLHttpRequest) {
            myObjectgetit = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectgetit = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectgetit.overrideMimeType('text/xml');
        }

        myObjectgetit.onreadystatechange = function (){
            data135 = myObjectgetit.responseText;
            if (myObjectgetit.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data135;
            }
        }; //specify name of function that will handle server response........
        myObjectgetit.open('GET','Post_Operative_Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Search_Value='+Search_Value+'&Guarantor_Name='+Guarantor_Name,true);
        myObjectgetit.send();
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered_Labolatory(){
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Quantity").value = '';
        document.getElementById("Price").value = '';
        var Search_Value = document.getElementById("Search_Value").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if(window.XMLHttpRequest) {
            myObjectgetLab = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectgetLab = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectgetLab.overrideMimeType('text/xml');
        }

        myObjectgetLab.onreadystatechange = function (){
            data1350 = myObjectgetLab.responseText;
            if (myObjectgetLab.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data1350;
            }
        }; //specify name of function that will handle server response........
        myObjectgetLab.open('GET','Post_Operative_Get_List_Of_Laboratory_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Search_Value='+Search_Value+'&Guarantor_Name='+Guarantor_Name+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectgetLab.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function () {
        $("#Add_Details").dialog({autoOpen: false, width: '50%', height: 500, title: 'Add Extra Surgery', modal: true});
        $("#Add_Preoperative").dialog({autoOpen: false, width: '80%', height: 500, title: 'Preoperative Diagnosis (indication)', modal: true});
        $("#Add_Postoperative").dialog({autoOpen: false, width: '80%', height: 500, title: 'Postoperative Diagnosis (findings)', modal: true});
        $("#Select_Scrub_Nurse").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Scrub Nurse', modal: true});
        $("#Select_Incision").dialog({autoOpen: false, width: '35%', height: 150, title: 'Select Incision', modal: true});
        $("#Select_Position").dialog({autoOpen: false, width: '35%', height: 150, title: 'Select Position', modal: true});
        // @Mfoy dn
        $("#add_status").dialog({autoOpen: false, width: '35%', height: 150, title: 'ADD STATUS', modal: true});
        // end @Mfoy dn
        $("#Select_Anaesthetic").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Anaesthesiologist', modal: true});
        $("#Submit_Info").dialog({autoOpen: false, width: '30%', height: 150, title: 'SAVE INFORMATION', modal: true});
        $("#Submit_Info2").dialog({autoOpen: false, width: '30%', height: 180, title: 'SAVE INFORMATION', modal: true});
        $("#Preview_Titles_Div").dialog({autoOpen: false, width: '60%', height: 450, title: 'PREVIEW ', modal: true});
        $("#Mandatory").dialog({autoOpen: false, width: '38%', height: 290, title: 'INFORMATION REQUIRED ', modal: true});
        $("#Select_Surgeon").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Surgeon', modal: true});
        $("#Select_Assistant_Surgeon").dialog({autoOpen: false, width: '60%', height: 400, title: 'Select Assistant Surgeon', modal: true});
        $("#Medication_Details").dialog({autoOpen: false, width: '75%', height: 500, title: 'ADD MEDICATION', modal: true});
        $("#Investigation_Details").dialog({autoOpen: false, width: '75%', height: 500, title: 'ADD INVESTIGATION', modal: true});
        $("#Radiology_Details").dialog({autoOpen: false, width: '75%', height: 500, title: 'ADD RADIOLOGY', modal: true});
        $("#Non_Supported_Item").dialog({autoOpen: false, width: '40%', height: 150, title: 'NON SUPPORTED ITEM', modal: true});
    });
</script>


<center><i><b><h4><span style="color: #037CB0;">Fields marked with </span><span style="color: black;"><b>****</b></span><span style="color: #037CB0;"> are compulsory</h4></b></b></i></center>
<?php
    include("./includes/footer.php");
?>
<!--<script src="css/jquery.js"></script>-->
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('.ehms_date_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.ehms_date_time').datetimepicker({value: '', step: 01});


    function open_optica_data(Registration_ID){
            $.ajax({
                type:'post',
                url: 'open_opticaL_data.php',
                data : {Registration_ID:Registration_ID},
                success : function(data){
                    $('#open_optica_data').dialog({
                        autoOpen:true,
                        width:'60%',
                        position: ['center',200],
                        title:'Patient Detail : '+Registration_ID ,
                        modal:true
                       
                    });  
                    $('#open_optica_data').html(data);
                    $('#open_optica_data').dialog('data');
                }
            })
        }

        function open_other_optical_surgery_detail(Registration_ID){
            $.ajax({
                type:'post',
                url: 'save_other_optical_surgery_detail.php',
                data : {Registration_ID:Registration_ID},
                success : function(data){
                    $('#open_other_optical_surgery_detail').dialog({
                        autoOpen:true,
                        width:'80%',
                        title:'Patient Detail : '+Registration_ID ,
                        modal:true
                       
                    });  
                    $('#open_other_optical_surgery_detail').html(data);
                    $('#open_other_optical_surgery_detail').dialog('data');
                }
            })
        }
   
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(1000)
                    .height(110);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
<?php

    include("./includes/connection.php");


$corneal_LE=mysqli_real_escape_string($conn,$_POST['corneal_LE']);
$corneal_RE=mysqli_real_escape_string($conn,$_POST['corneal_RE']);
$synechinae_LE=mysqli_real_escape_string($conn,$_POST['synechinae_LE']);
$synechinae_RE=mysqli_real_escape_string($conn,$_POST['synechinae_RE']);
$macular_LE=mysqli_real_escape_string($conn,$_POST['macular_LE']);
$macular_RE=mysqli_real_escape_string($conn,$_POST['macular_RE']);
$glaucoma_RE=mysqli_real_escape_string($conn,$_POST['glaucoma_RE']);
$glaucoma_LE=mysqli_real_escape_string($conn,$_POST['glaucoma_LE']);
$non_glaucoma_LE=mysqli_real_escape_string($conn,$_POST['non_glaucoma_LE']);
$non_glaucoma_RE=mysqli_real_escape_string($conn,$_POST['non_glaucoma_RE']);
$lens_LE=mysqli_real_escape_string($conn,$_POST['lens_LE']);
$$lens_RE=mysqli_real_escape_string($conn,$_POST['lens_RE']);
$loose_LE=mysqli_real_escape_string($conn,$_POST['loose_LE']);
$loose_RE=mysqli_real_escape_string($conn,$_POST['loose_RE']);
$deep_LE=mysqli_real_escape_string($conn,$_POST['deep_LE']);
$deep_RE=mysqli_real_escape_string($conn,$_POST['deep_RE']);
$small_pupil_RE=mysqli_real_escape_string($conn,$_POST['small_pupil_RE']);
$small_pupil_LE=mysqli_real_escape_string($conn,$_POST['small_pupil_LE']);
$insufficient_RE=mysqli_real_escape_string($conn,$_POST['insufficient_RE']);
$insufficient_LE=mysqli_real_escape_string($conn,$_POST['insufficient_LE']);
//$sticker=$_POST['sticker'];
$none_RE=mysqli_real_escape_string($conn,$_POST['none_RE']);
$none_LE=mysqli_real_escape_string($conn,$_POST['none_LE']);
$other_specify_RE=mysqli_real_escape_string($conn,$_POST['other_specify_RE']);
$other_specify_LE=mysqli_real_escape_string($conn,$_POST['other_specify_LE']);
$vitreous_loss_RE=mysqli_real_escape_string($conn,$_POST['vitreous_loss_RE']);
$vitreous_loss_LE=mysqli_real_escape_string($conn,$_POST['vitreous_loss_LE']);
$pc_tear_RE =mysqli_real_escape_string($conn,$_POST['pc_tear_RE']);
$pc_tear_LE =mysqli_real_escape_string($conn,$_POST['vitreous_loss_LE']);
$pc_tear_LE =mysqli_real_escape_string($conn,$_POST['vitreous_loss_LE']);


$suture_RE=mysqli_real_escape_string($conn,$_POST['suture_RE']);
$suture_LE=mysqli_real_escape_string($conn,$_POST['suture_LE']);
$incision_LE=mysqli_real_escape_string($conn,$_POST['incision_LE']);
$incision_RE=mysqli_real_escape_string($conn,$_POST['incision_RE']);
$recomm_RE=mysqli_real_escape_string($conn,$_POST['recomm_RE']);
$recomm_LE=mysqli_real_escape_string($conn,$_POST['recomm_LE']);
$inserted_RE=mysqli_real_escape_string($conn,$_POST['inserted_RE']);
$inserted_LE=mysqli_real_escape_string($conn,$_POST['inserted_LE']);
$ascan_RE=mysqli_real_escape_string($conn,$_POST['ascan_RE']);
$ascan_LE=mysqli_real_escape_string($conn,$_POST['ascan_LE']);
$optical_clinic_ID=mysqli_real_escape_string($conn,$_POST['optical_clinic_ID']);
$Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);

$Patient_Payment_Item_List_ID=mysqli_real_escape_string($conn,$_POST['Patient_Payment_Item_List_ID']);
$consultation_ID=mysqli_real_escape_string($conn,$_POST['consultation_ID']);
$Patient_Payment_ID=mysqli_real_escape_string($conn,$_POST['Patient_Payment_ID']);
$Payment_Item_Cache_List_ID=mysqli_real_escape_string($conn,$_POST['Payment_Item_Cache_List_ID']);

    $get_date=date("y-m-d h:m:s");
    $dateleo = strtotime($get_date);
    $filename= $dateleo."-".$_FILES['sticker']['name'];
    
    $temp_file_sticker=$_FILES['sticker']['tmp_name'];
    $uploads_dir="upload/";
    move_uploaded_file($temp_file_sticker,$uploads_dir.$filename);


    // $filename2=rand(1000,100000000)."-".$_FILES['notes']['name'];
    // $temp_file_notes=$_FILES['notes']['tmp_name'];
    // $uploads_dir2="upload/";
    // move_uploaded_file($temp_file_notes,$uploads_dir2.$filename2);

        $no=5;
    if(isset($_POST['save_optical_data'])){


        // if($corneal_LE =='' || $corneal_RE =='' || $synechinae_LE =='' || $synechinae_RE =='' || $macular_LE =='' || $macular_RE =='' || $glaucoma_RE =='' || $glaucoma_LE =='' || $non_glaucoma_LE =='' || $non_glaucoma_RE =='' || $lens_LE =='' || $lens_RE =='' || $loose_LE =='' || $loose_RE =='' || $deep_LE =='' || $deep_RE =='' || $small_pupil_RE =='' || $small_pupil_LE =='' || $insufficient_RE =='' || $insufficient_LE =='' || $cataract_RE =='' || $cataract_LE =='' || $ascan_RE =='' || $ascan_LE =='' || $recomm_RE =='' || $recomm_LE =='' || $inserted_RE =='' || $inserted_LE =='' || $incision_RE =='' || $incision_LE =='' || $suture_RE =='' || $suture_LE =='' || $none_RE =='' || $none_LE =='' || $pc_tear_RE =='' || $pc_tear_LE =='' || $vitrelous_RE =='' || $vitrelous_LE =='' || $other_specify_RE =='' || $other_specify_LE =='' || $iopmmg_RE =='' || $iopmmg_LE ==''){
        //     echo "<script>alert('Please Fill Atleast One of the Field Above');</script>";
        // }else{
            $insert_surgery="INSERT INTO tbl_surgery(corneal_LE, corneal_RE,
        synechinae_LE, synechinae_RE, macular_LE, macular_RE, glaucoma_RE, glaucoma_LE,
        non_glaucoma_LE, non_glaucoma_RE, lens_LE, lens_RE, loose_LE, loose_RE, deep_LE,
        deep_RE, small_pupil_RE, small_pupil_LE, insufficient_RE, insufficient_LE, sticker,
        cataract_RE, cataract_LE, ascan_RE, ascan_LE, recomm_RE, recomm_LE, inserted_RE, 
        inserted_LE, incision_RE, incision_LE, suture_RE, suture_LE, none_RE, none_LE, pc_tear_RE,
        pc_tear_LE, vitrelous_RE, vitrelous_LE, other_specify_RE, other_specify_LE, iopmmg_RE, 
        iopmmg_LE,Registration_ID,Patient_Payment_Item_List_ID,consultation_ID,Patient_Payment_ID,Payment_Item_Cache_List_ID)
         VALUES ('$corneal_LE','$corneal_RE','$synechinae_LE',
        '$synechinae_RE','$macular_LE','$macular_RE','$glaucoma_RE','$glaucoma_LE',
        '$non_glaucoma_LE','$non_glaucoma_RE','$lens_LE','$lens_RE','$loose_LE',
        '$loose_RE','$deep_LE','$deep_RE','$small_pupil_RE','$small_pupil_LE',
        '$insufficient_RE','$insufficient_LE','$filename',
        '$cataract_RE', '$cataract_LE', '$ascan_RE', '$ascan_LE', '$recomm_RE', '$recomm_LE', '$inserted_RE', 
        '$inserted_LE', '$incision_RE', '$incision_LE','$suture_RE', '$suture_LE',' $none_RE', '$none_LE','$pc_tear_RE',
        '$pc_tear_LE','$vitrelous_RE','$vitrelous_LE','$other_specify_RE','$other_specify_LE','$iopmmg_RE', 
        '$iopmmg_LE', '$Registration_ID','$Patient_Payment_Item_List_ID','$consultation_ID','$Patient_Payment_ID','$Payment_Item_Cache_List_ID')";

    if(mysqli_query($conn,$insert_surgery) or die(mysqli_error($conn))){
        echo "<script>alert('Data successfully saved')</script>";
        
    }
    else{
        echo "<script>alert('Data Not saved')</script>";
    }
        //}
    }
?>
