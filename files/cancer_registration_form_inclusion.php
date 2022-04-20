<?php

include("./includes/connection.php");
$select_patient_data = mysqli_query($conn, "SELECT HIV_sero_status,Date_diagnosis_HIV,Chronic_disease,Date_of_Incidence,symptoms_date,Basis_of_diagnosis,Other,ER_Status,PR_Status,Her_Status,Primary_site,Secondary_Site,Morphology,M_code,Stage_T,Stage_N,Stage_M,Gleason_score,Baseline_PSA,Metastasis,Non_Metastasis,Other_Staging,date_and_time,saved_by FROM tbl_cancer_registration WHERE Registration_ID='$Registration_ID' AND  cancer_id='$cancer_id'" ) or die(mysqli_error($conn));
if(mysqli_num_rows($select_patient_data)>0){
    while($dta_rw = mysqli_fetch_assoc($select_patient_data)){
        $HIV_sero_status = explode(",", $dta_rw['HIV_sero_status']);
        $Date_diagnosis_HIV = $dta_rw['Date_diagnosis_HIV'];
        $Chronic_disease = $dta_rw['Chronic_disease'];
        $Date_of_Incidence = $dta_rw['Date_of_Incidence'];
        $symptoms_date = $dta_rw['symptoms_date'];
        $Basis_of_diagnosis =  explode(",", $dta_rw['Basis_of_diagnosis']);
        $Other = $dta_rw['Other'];
        $ER_Status = $dta_rw['ER_Status'];
        $PR_Status = $dta_rw['PR_Status'];
        $Her_Status = $dta_rw['Her_Status'];
        $Primary_site = $dta_rw['Primary_site'];
        $Secondary_Site = $dta_rw['Secondary_Site'];
        $Morphology = $dta_rw['Morphology'];
        $M_code = $dta_rw['M_code'];
        $Stage_T = $dta_rw['Stage_T'];
        $Stage_N = $dta_rw['Stage_N'];
        $Stage_M = $dta_rw['Stage_M'];
        $Gleason_score = $dta_rw['Gleason_score'];
        $Baseline_PSA = $dta_rw['Baseline_PSA'];
        $Metastasis = $dta_rw['Metastasis'];
        $Other_Staging = $dta_rw['Other_Staging'];
        $date_and_time = $dta_rw['date_and_time'];
        $Non_Metastasis = $dta_rw['Non_Metastasis'];
        

        foreach($HIV_sero_status as $HIV_status){
            if($HIV_status =="SP_on_ART"){
                $SP_on_ART ="checked='checked'";
            }
            if($HIV_status =="SN"){
                $SN = "checked='checked'";
            }
            if($HIV_status == "Unknown3"){
                $Unknown3 ="checked='checked'";
            }
            if($HIV_status == "SP_not_on_ART"){
                $SP_not_on_ART ="checked='checked'";
            }
        }

        foreach($Basis_of_diagnosis as $basis_diagnosis){
            if($basis_diagnosis =="Histology"){
                $Histology ="checked='checked'";
            }
            if($basis_diagnosis =="Clinical_Only"){
                $Clinical_Only = "checked='checked'";
            }
            if($basis_diagnosis == "Cytology_Hematology"){
                $Cytology_Hematology ="checked='checked'";
            }
            if($basis_diagnosis == "FNAC"){
                $FNAC ="checked='checked'";
            }
            if($basis_diagnosis == "Clinical_Investigation"){
                $Clinical_Investigation ="checked='checked'";
            }
        }
        if($ER_Status =="Negative"){
            $ER_Status_Negative ="checked='checked'";
        }else{
            $ER_Status_Positive ="checked='checked'";
        }
        if($PR_Status =="Negative"){
            $PR_Status_Negative ="checked='checked'";
        }else{
            $PR_Status_Positive ="checked='checked'";
        }
        if($HER_Status =="Negative"){
            $HER_Status_Negative ="checked='checked'";
        }else{
            $HER_Status_Positive ="checked='checked'";
        }
        if($Metastasis =="Yes"){
            $Metastasis_Yes ="checked='checked'";
        }else{
            $Metastasis_no ="checked='checked'";
        }
        if($Non_Metastasis =="Low"){
            $Non_Metastasis_low ="checked='checked'";
        }else if($Non_Metastasis=="High"){
            $Non_Metastasis_high ="checked='checked'";
        }else{
            $Non_Metastasis_Intermediate ="checked='checked'";
        }
    }
}

$select_treatment = mysqli_query($conn, "SELECT Intuition,Surgery,Surgery_Date,Targeted_therapy_Date,Radiotherapy,Radiotherapy_Date,Immunotherapy,Immunotherapy_Date,Targeted_therapy,Chemotherapy,Chemotherapy_Date,Hormone_therapy,Hormone_therapy_Date,Chemo,Other_chemo FROM tbl_cancer_treat WHERE  Registration_ID='$Registration_ID' AND  cancer_registration_id='$cancer_id'" ) or die(mysqli_error($conn));
if(mysqli_num_rows($select_treatment)>0){
    while($treat_rw = mysqli_fetch_assoc($select_treatment)){
        $Intuition = explode(",",$treat_rw['Intuition']);
        $Surgery = $treat_rw['Surgery'];
        $Surgery_Date = $treat_rw['Surgery_Date'];
        $Radiotherapy = $treat_rw['Radiotherapy'];
        $Radiotherapy_Date = $treat_rw['Radiotherapy_Date'];
        $Immunotherapy = $treat_rw['Immunotherapy'];
        $Immunotherapy_Date = $treat_rw['Immunotherapy_Date'];
        $Targeted_therapy = $treat_rw['Targeted_therapy'];
        $Chemotherapy = $treat_rw['Chemotherapy'];
        $Chemotherapy_Date = $treat_rw['Chemotherapy_Date'];
        $Hormone_therapy = $treat_rw['Hormone_therapy'];
        $Hormone_therapy_Date = $treat_rw['Hormone_therapy_Date'];
        $Targeted_therapy_Date= $treat_rw['Targeted_therapy_Date'];
        $Chemo = explode(",", $treat_rw['Chemo']);
        $Other_chemo = $treat_rw['Other_chemo'];
        
        foreach($Intuition as $intuits){
            if($intuits =="Curative"){
                $Curative ="checked='checked'";
            }
            if($intuits =="Palliative"){
                $Palliative = "checked='checked'";
            }
            if($intuits == "Unknown_value"){
                $Unknown_value ="checked='checked'";
            }
           
        }
        foreach($Chemo as $chemod){
            if($chemod =="Adjuvant"){
                $Adjuvant ="checked='checked'";
            }
            if($chemod =="neoadjuvant"){
                $neoadjuvant = "checked='checked'";
            }
            if($chemod == "chemo_radiation"){
                $chemo_radiation ="checked='checked'";
            }
            if($chemod == "Palliative_chemotherapy"){
                $Palliative_chemotherapy ="checked='checked'";
            }
           
        }
        if($Surgery =="Yes"){
            $Surgery_Yes ="checked='checked'";
        }else{
            $Surgery_no ="checked='checked'";
        }
        if($Radiotherapy =="Yes"){
            $Radiotherapy_Yes ="checked='checked'";
        }else{
            $Radiotherapy_no ="checked='checked'";
        }
        if($Immunotherapy =="Yes"){
            $Immunotherapy_Yes ="checked='checked'";
        }else{
            $Immunotherapy_no ="checked='checked'";
        }
        if($Chemotherapy =="Yes"){
            $Chemotherapy_Yes ="checked='checked'";
        }else{
            $Chemotherapy_no ="checked='checked'";
        }
        if($Hormone_therapy =="Yes"){
            $Hormone_therapy_Yes ="checked='checked'";
        }else{
            $Hormone_therapy_no ="checked='checked'";
        }
        if($Targeted_therapy =="Yes"){
            $Targeted_therapy_Yes ="checked='checked'";
        }else{
            $Targeted_therapy_no ="checked='checked'";
        }
    }
}

$select_regimen =mysqli_query($conn, "SELECT A_Regimen,A_Regimen_Start_Date,A_Regimen_End_Date,A_condition,A_Other_Condition,B_Regimen,B_Regimen_Start_Date,B_Regimen_End_Date,B_condition,B_Other_Condition,C_Any_other_result,General_progress_of_desease FROM tbl_therapy_regimen  WHERE Registration_ID='$Registration_ID' AND  cancer_registration_id='$cancer_id'" ) or die(mysqli_error($conn));
if(mysqli_num_rows($select_regimen)>0){
    while($regimen_rw = mysqli_fetch_assoc($select_regimen)){
        $A_Regimen = $regimen_rw['A_Regimen'];
        $A_Regimen_Start_Date = $regimen_rw['A_Regimen_Start_Date'];
        $A_Regimen_End_Date = $regimen_rw['A_Regimen_End_Date'];
        $A_Regimen_End_Date = $regimen_rw['A_Regimen_End_Date'];
        $A_Other_Condition = $regimen_rw['A_Other_Condition'];
        $B_Regimen = $regimen_rw['B_Regimen'];
        $B_Regimen_Start_Date = $regimen_rw['B_Regimen_Start_Date'];
        $B_Regimen_End_Date = $regimen_rw['B_Regimen_End_Date'];
        $B_condition = explode(",", $regimen_rw['B_condition']);
        $B_Other_Condition = $regimen_rw['B_Other_Condition'];
        $C_Any_other_result =explode(",", $regimen_rw['C_Any_other_result']);
        $General_progress_of_desease = $regimen_rw['General_progress_of_desease'];
        $A_condition = explode(",", $regimen_rw['A_condition']);

        if($General_progress_of_desease =="Cured"){
            $Cured ="checked='checked'";
        }else if($General_progress_of_desease=="Recured"){
            $Recured ="checked='checked'";
        }else{
            $Unknown2 ="checked='checked'";
        }
        foreach($C_Any_other_result as $C_any){
            if($C_any =="Treatment_completed"){
                $Treatment_completed ="checked='checked'";
            }
            if($C_any =="Lost_before_treatment"){
                $Lost_before_treatment = "checked='checked'";
            }
            if($C_any == "Lost_during_treatment"){
                $Lost_during_treatment ="checked='checked'";
            }
            if($C_any == "Referred"){
                $Referred ="checked='checked'";
            }
            if($C_any == "PCT"){
                $PCT ="checked='checked'";
            }
            if($C_any == "Treatment_ongoing"){
                $Treatment_ongoing ="checked='checked'";
            }
           
        }
        foreach($A_condition as $A_cond){
            if($A_cond =="A_Complete_remission"){
                $A_Complete_remission ="checked='checked'";
            }
            if($A_cond =="A_Partial_remission"){
                $A_Partial_remission = "checked='checked'";
            }
            if($A_cond == "A_Stable_disease"){
                $A_Stable_disease ="checked='checked'";
            }
            if($A_cond == "A_Progression"){
                $A_Progression ="checked='checked'";
            }
        }
        foreach($B_condition as $B_cond){
            if($B_cond =="B_Complete_remission"){
                $B_Complete_remission ="checked='checked'";
            }
            if($B_cond =="B_Partial_remission"){
                $B_Partial_remission = "checked='checked'";
            }
            if($B_cond == "B_Stable_disease"){
                $B_Stable_disease ="checked='checked'";
            }
            if($B_cond == "B_Progression"){
                $B_Progression ="checked='checked'";
            }
        }
    }
}

$select_other_information = mysqli_query($conn, "SELECT Institution,Ward_Unit,Lab_number,Adverse_events,supportive_care,one_Line,two_Line,three_Line,Date_at_last_Contact,last_status_contact,cause_of_death,Other_causes  FROM tbl_cancer_other_information WHERE Registration_ID='$Registration_ID' AND  cancer_registration_id='$cancer_id'" ) or die(mysqli_error($conn));

if(mysqli_num_rows($select_other_information)>0){
    while($other_info_rw = mysqli_fetch_assoc($select_other_information)){
        $Institution = $other_info_rw['Institution'];
        $Ward_Unit = $other_info_rw['Ward_Unit'];
        $Lab_number = $other_info_rw['Lab_number'];
        $Adverse_events = $other_info_rw['Adverse_events'];
        $supportive_care = $other_info_rw['supportive_care'];
        $one_Line = $other_info_rw['one_Line'];
        $two_Line = $other_info_rw['two_Line'];
        $three_Line = $other_info_rw['three_Line'];
        $Date_at_last_Contact = $other_info_rw['Date_at_last_Contact'];
        $last_status_contact =  $other_info_rw['last_status_contact'];
        $cause_of_death = explode(",", $other_info_rw['cause_of_death']);
        $Other_causes = $other_info_rw['Other_causes'];
       

        foreach($cause_of_death as $death){
            if($death =="This_cancer"){
                $This_cancer ="checked='checked'";
            }
            if($death =="Unknown_causes"){
                $Unknown_causes = "checked='checked'";
            }
            if($death == "Trauma"){
                $Trauma ="checked='checked'";
            }
           
        }
        
            if($last_status_contact =="Alive"){
                $Alive ="checked='checked'";
            }else  if($last_status_contact =="Dead"){
                $Dead = "checked='checked'";
            }else{
                $Unknown_status ="checked='checked'";
            }

    }
}

?>    

<div class="box box-primary" style="height: 800px;overflow: auto">
    <div class="box-body" >
    <table class="table">
        <tr>
            <td style="width:10%;text-align:right " ><b>HIV Sero-Status:</b></td>
            <td colspan="2" width="35%">
                SN<input type='checkbox' name='SN' id='SN' <?php echo $SN; ?>>
                SP on ART<input type='checkbox' name='SP_on _ART' <?php echo $SP_on_ART; ?>>
                SP not on ART<input type='checkbox' name='SP_not_on_ART' id='SP_not_on_ART' <?php echo $SP_not_on_ART; ?>>
                Unknown<input type='checkbox' name='Unknown3' id='Unknown3' value='Unknown' <?php echo $Unknown3; ?>>
                    
            </td>               
            <td style="width:15%;text-align:right " >
                <b>Date diagnosis HIV:</b>
                <input type='text' style="width:100%;"  name='Date_diagnosis_HIV' id='Date_diagnosis_HIV' class="date" placeholder="Date" value="<?php echo $Date_diagnosis_HIV; ?>">
            </td>
            <td style="width:40%;text-align:left; " >
                <b>Chronic disease at diagnosis specified:</b>
                <input type='text' style="width:100%;" value="<?php echo $Chronic_disease; ?>"  name='Chronic_disease' id='Chronic_disease'>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient"> B:  TUMOUR</div>
                <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px" >
                        <tr>
                            <td style="width:10%;text-align:right "><b>Date of Incidence:</b></td>
                            <td colspan="" width="10%">
                                <input type='text' style="width:100%;" name='Date_of_Incidence' id='Date_of_Incidence' class="date" placeholder="Date" value="<?php echo $Date_of_Incidence;?>">
                            </td>
                            <td colspan="" ><b>Onset of symptoms date:</b>
                                <input type='text' style="width:100%;" name='symptoms_date' id='symptoms_date' class="date" placeholder="Date"  value="<?php echo $symptoms_date;?>">
                        </td>
                        <td style="width:30%;text-align:right " ><b>Other:</b>
                            <input type='text' style="width:100%;" name='Other' id='Other' value="<?php echo $Other;?>">
                        </td>
                        </tr> 
                        <tr>
                            <td style="width:10%;text-align:right " ><b>Basis of Diagnosis:</b></td>
                            <td colspan="3">Histology<input type='checkbox' name='Histology' id='Histology' <?php echo $Histology;?>value='Histology'>Clinical Only<input type='checkbox' <?php echo $Clinical_Only;?> name='Clinical_Only' id='Clinical_Only' value='Clinical_Only'>Cytology/Hematology<input type='checkbox' <?php echo $Cytology_Hematology;?> name='Cytology_Hematology' id='Cytology_Hematology' value='Cytology_Hematology'>FNAC<input type='checkbox' <?php echo $FNAC;?> name='FNAC' id='FNAC' value='FNAC'>Clinical-Investigation(x-ray/CTscan etc)<input type='checkbox' <?php echo $Clinical_Investigation;?> name='Clinical_Investigation' id='Clinical_Investigation' value='Clinical_Investigation'>
                            </td>
                         
                       
                        </tr> 
                        <tr>
                            <td style="width:10%;text-align:right " ><b>For breast cancer:</b></td>
                            <td colspan="3"><b>ER Status</b>0.Negative<input type='radio' name='ER_Status' <?php echo $ER_Status_Negative?> id='ER_Status_Negative' value=''>1.Positive<input type='radio' name='ER_Status' id='ER_Status_Positive' <?php echo $ER_Status_Positive;?>><b>PR Status</b>0.Negative<input type='radio' name='PR_Status' <?php echo $PR_Status_Negative;?> id='PR_Status_Negative' value=''>1.Positive<input type='radio' name='PR_Status' id='PR_Status_Positive' <?php echo $PR_Status_Positive;?>><b>Her Status</b>0.Negative<input type='radio' name='Her_Status' id='Her_Status_Negative' <?php echo $Her_Status_Negative;?>>1.Positive<input type='radio' name='Her_Status' id='Her_Status_Positive' <?php echo $Her_Status_Positive;?>></td>
                        </tr>
                        <tr>
                            <td style="width:10%;text-align:right "><b>Primary site/Diagnosis Code</b></td><td  width="30%"><input type='text' style="width:100%;" name='Primary_site' id='Primary_site' value="<?php echo $Primary_site;?>" ></td>
                            
                            <td colspan="2" style="width:20%;text-align:center "><b>Stage:T:</b>
                                <input type='text' style="width:100%;" name='Stage_T' id='Stage_T' value="<?php echo $Stage_T;?>" >
                            </td>
                        </tr>
                        <tr>
                            <td style="width:10%;text-align:right "><b>Morphology</b></td><td colspan=""><input type='text' style="width:100%;" name='Morphology' id='Morphology'  value="<?php echo $Morphology;?>"></td>
                            <td style="width:10%;text-align:right "><b>M-code:</b></td><td colspan=""><input type='text' style="width:100%;" name='M_code' id='M_code' value=" <?php echo $M_code;?>">(8000/3 if morphology unspecified)</td>
                        </tr>
                        <tr>
                            <td style="width:10%;text-align:right "><b>   [C80.9 if unknown origin site] Secondary Site/Diagnosis code:</b></td><td colspan=""><input type='text'  name='Stage_T' id='Stage_T'   value="<?php echo $Stage_T;?>"></td>
                            <td colspan=""><b>N:</b><input type='text'  name='Stage_N' id='Stage_N'><b>Gleason score:</b><input type='text'  name='Gleason_score' id='Gleason_score'  value="<?php echo $Gleason_score;?>"></td>
                            <td colspan="">M:<input type='text'  name='Stage_M' id='Stage_M'><b>Baseline PSA:</b><input type='text'  name='Baseline_PSA' id='Baseline_PSA' value="<?php echo $Baseline_PSA;?>"></td>
            <!--                <td><b>Gleasson score:</b></td><td colspan=""><input type='text'  name='incidence' id='incidence'></td>
                            <td><b>Baseline PSA:</b></td><td colspan=""><input type='text'  name='incidence' id='incidence'></td>-->
                        </tr>
                        <table>
                                <tr>
                            <td style="width:10%;text-align:right " ><b>Risk classification:</b></td>
                            <td colspan="2" width="40%">Metastasis:1.Yes<input type='radio'  name='Metastasis' id='Metastasis_Yes'  <?php echo $Metastasis_Yes;?>>2.No<input type='radio' name='Metastasis'   <?php echo $Metastasis_No;?> id='Metastasis_No' value=''>Non-metastasis:1.Low risk<input type='radio' name='Non_metastasis' id='Non_metastasis_Low_risk'  <?php echo $Non_Metastasis_low;?> value=''>2.Intermediate risk<input type='radio' name='Non_metastasis'  <?php echo $Non_Metastasis_Intermediate;?> id='Non_metastasis_Intermediate_risk' value=''>3.High risk<input type='radio' name='Non_metastasis' id='Non_metastasis_High_risk'  <?php echo $Non_Metastasis_high;?>><div align="right"><b>Other Staging:</b></div></td>
                        
                        <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Other_Staging' id='Other_Staging' value=" <?php echo $Secondary_Site;?>"></td>
                        </tr>
                        </table>
                    
                    </table>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient"> C:  TREATMENT</div>
                <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px"> 
                    <td style="width:10%;text-align:right " ><b>Intuition:</b></td>
                        <td colspan="">Curative<input type='checkbox' name='Curative' id='Curative' <?php echo $Curative;?>value=''>Palliative<input type='checkbox' name='Palliative' id='Palliative' <?php echo $Palliative;?>value=''>Unknown<input type='checkbox' name='Unknown_value' id='Unknown_value' <?php echo $Unknown_value;?>></td>
                </table>
            <div style="width:96%;font-size:larger;border:1px solid #000; margin-left:20px;  background:#ccc;" id="outpatient"> TREATMENT  </div>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                    <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px"> 
                    <tr>
                    <td colspan=""><b style="padding: 1em;">Surgery </b>1:<input type='radio' name='Surgery' id='Surgery_Yes' <?php echo $Surgery_Yes;?>>Yes<input type='radio' name='Surgery' id='Surgery_No' <?php echo $Surgery_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="fom-control" placeholder="Date" value="<?php echo $Surgery_Date;?>" name='Surgery_Date' id='Surgery_Date'/></td>
                    <td colspan=""><b>Radiotherapy </b>1:<input type='radio' name='Radiotherapy' id='Radiotherapy_Yes' <?php echo $Radiotherapy_Yes;?>>Yes<input type='radio' name='Radiotherapy' id='Radiotherapy_No' <?php echo $Radiotherapy_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date"value="<?php echo $Radiotherapy_Date;?>" name='Radiotherapy_Date' id='Radiotherapy_Date'/></td>
                    <td colspan=""><b>Immunotherapy </b>1:<input type='radio' name='Immunotherapy' id='Immunotherapy_Yes' <?php echo $Immunotherapy_Yes;?>>Yes<input type='radio' name='Immunotherapy' id='Immunotherapy_No' <?php echo $Immunotherapy_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" value="<?php echo $Immunotherapy_Date;?>" name='Immunotherapy_Date' id='Immunotherapy_Date'/></td>
                    <!--<b>Date:</b><input type='text' name='incidence' id='incidence'>-->
                    </tr>
                    <tr>
                    <td colspan=""><b>Targeted therapy </b>1:<input type='radio' name='Targeted_therapy' id='Targeted_therapy_Yes' <?php echo $Targeted_therapy_Yes;?>>Yes<input type='radio' name='Targeted_therapy' id='Targeted_therapy_No' <?php echo $Targeted_therapy_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' class="date" placeholder="Date"value="<?php echo $Targeted_therapy_Date;?>" name='Targeted_therapy_Date' id='Targeted_therapy_Date'/></td>
                    <td colspan=""><b>Chemotherapy </b>1:<input type='radio' name='Chemotherapy' id='Chemotherapy_Yes' <?php echo $Chemotherapy_Yes;?>>Yes<input type='radio' name='Chemotherapy' id='Chemotherapy_No' <?php echo $Chemotherapy_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" name='Chemotherapy_Date' value="<?php echo $Chemotherapy_Date;?>" id='Chemotherapy_Date'/></td>
                    <td colspan=""><b>Hormone therapy</b>1:<input type='radio' name='Hormone_therapy' id='Hormone_therapy_Yes' <?php echo $Hormone_therapy_Yes;?>>Yes<input type='radio' name='Hormone_therapy' id='Hormone_therapy_No' <?php echo $Hormone_therapy_No;?>>No   <b>Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:45%;display:inline' class="date" placeholder="Date" value="<?php echo $Hormone_therapy_Date;?>" name='Hormone_therapy_Date' id='Hormone_therapy_Date'/></td>
                    
                    </tr>
                    <table class="table">
                    <tr>
                        <td style="width:10%;text-align:right" ><b>Chemo:</b></td>
                        <td colspan="2" width="30%">Adjuvant<input type='checkbox' name='Adjuvant' id='Adjuvant' <?php echo $Adjuvant;?>>neoadjuvant<input type='checkbox' name='Adjuvant' id='neoadjuvant' <?php echo $neoadjuvant;?>>chemo-radiation<input type='checkbox' name='chemo_radiation' id='chemo_radiation' <?php echo $chemo_radiation;?>>Palliative-chemotherapy<input type='checkbox' name='Palliative_chemotherapy' id='Palliative_chemotherapy' <?php echo $Palliative_chemotherapy;?>></td>
                        <td style="width:10%;text-align:right " ><b>Other chemo:</b></td>
                    <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='Other_chemo' id='Other_chemo' value="<?php echo $Other_chemo;?>"></td>
                    </tr>
                        
                </table>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div style="width:96%;font-size:larger;border:1px solid #000; margin-left:20px;  background:#ccc;" id="outpatient">
                Therapy regimen:
                </div>
                    <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                            <tr>
                            <td style="width:20%;text-align:right "><b>A:  Treatment 1st Line:</b></td></td>
                            <td style="width:10%;text-align:right "><b>Regimen:</b></td><td colspan="" ><input type='text' style="width:70%;" name='A_Regimen' id='A_Regimen' value="<?php echo $A_Regimen;?>"></td>
                            <td colspan=""><b> Start Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='A_Regimen_Start_Date' value="<?php echo $A_Regimen_Start_Date;?>" id='A_Regimen_Start_Date'/></td>
                            <td colspan=""><b> End Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='A_Regimen_End_Date' value="<?php echo $A_Regimen_End_Date;?>" id='A_Regimen_End_Date'/></td>
                        </tr>
                        <table>
                                <tr>
                            <td style="width:4%;text-align:right " ><b></b></td>
                            <td colspan="2" width="30%">Complete remission<input <?php echo $A_Complete_remission; ?> type='checkbox' name='A_Complete_remission' id='A_Complete_remission' value='Complete remission'>Partial remission<input <?php echo $A_Partial_remission; ?> type='checkbox' name='A_Partial_remission' id='A_Partial_remission' value='Partial remission'>Stable disease<input <?php echo $A_Stable_disease; ?> type='checkbox' name='A_Stable_disease' id='A_Stable_disease' value='Stable disease'>Progression<input <?php echo $A_Progression; ?> type='checkbox' name='A_Progression' id='A_Progression' value='Progression'><div align="right"><b>Other Condition:</b></div></td>
                        
                        <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='A_Other_Condition' id='A_Other_Condition' value="<?php echo $A_Other_Condition; ?>"></td>
                        </tr>
                        <table class="table">
                            <tr>
                            <td style="width:20%;text-align:right "><b>B:   Treatment 2st Line:</b></td></td>
                            <td style="width:10%;text-align:right "><b>Regimen:</b></td><td colspan="" ><input type='text' style="width:70%;" name='B_Regimen' id='B_Regimen' value="<?php echo $B_Regimen;?>"></td>
                            <td colspan=""><b> Start Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='B_Regimen_Start_Date' value="<?php echo $B_Regimen_Start_Date;?>" id='B_Regimen_Start_Date'/></td>
                            <td colspan=""><b> End Date:</b> <input type="text" autocomplete="off" style='text-align: center;width:50%;display:inline' class="date" placeholder="Date" name='B_Regimen_End_Date' value="<?php echo $B_Regimen_End_Date;?>" id='B_Regimen_End_Date'/></td>
                        </tr>
                        <table class="table">
                                <tr>
                            <td style="width:4%;text-align:right " ><b></b></td>
                            <td colspan="2" width="30%">Complete remission<input type='checkbox' name='B_Complete_remission' id='B_Complete_remission' <?php echo $B_Complete_remission; ?> value='Complete remission'>Partial remission<input type='checkbox' name='B_Partial_remission' id='B_Partial_remission' <?php echo $B_Partial_remission; ?> value='Partial remission'>Stable disease<input type='checkbox' name='B_Stable_disease' id='B_Stable_disease' <?php echo $B_Stable_disease; ?> value='Stable disease'>Progression<input type='checkbox' name='B_Progression' id='B_Progression' <?php echo $B_Progression; ?> value='Progression'><div align="right"><b>Other Condition:</b></div></td>
                        
                        <td style="width:10%;text-align:right " ><input type='text' style="width:100%;" name='B_Other_Condition' id='B_Other_Condition' value="<?php echo $B_Other_Condition;?>"></td>
                        </tr>
                            <tr>
                            <td style="width:10%;text-align:right " ><b>C: Any other result:</b></td>
                            <td colspan="3"><b>Treatment completed</b><input type='checkbox' name='Treatment_completed' id='Treatment_completed' <?php echo $Treatment_completed; ?>   value='Treatment completed'><b>Lost before treatment</b><input type='checkbox' name='Lost_before_treatment' id='Lost_before_treatment' <?php echo $Lost_before_treatment; ?>  value='Lost before treatment'><b>Lost during treatment</b><input type='checkbox' name='Lost_during_treatment' id='Lost_during_treatment' <?php echo $Lost_during_treatment; ?>  value='Lost during treatment'><b>Referred</b><input type='checkbox' name='Referred' id='Referred' <?php echo $Recured; ?>  value='Referred'>PCT<input type='checkbox' name='PCT' id='PCT' <?php echo $PCT; ?>  value='PCT'><b>Treatment ongoing</b><input type='checkbox' name='Treatment_ongoing' id='Treatment_ongoing' <?php echo $Treatment_ongoing; ?>  value='Treatment ongoing'></td>
                        </tr>
                            <tr>
                            <td style="width:10%;text-align:right " ><b>General progress of the disease:</b></td>
                            <td colspan="3"><b>Cured</b><input type='radio' name='Cured' id='Cured' <?php echo $Cured; ?>><b>Recurred</b><input type='radio' name='Cured' id='Recurred' <?php echo $Recured; ?>><b>Unknown</b><input type='radio' name='Cured' id='Unknown2' <?php echo $Unknown2; ?>></td>
                        </tr>
                        </table>
                    
                        </table>
                    
                    </table> 
                       
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                    D:  SOURCE OF INFORMATION
                    </div>
                            <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                                    <tr>
                                <!--<td style="width:10%;text-align:right "><b>A:  Treatment 1st Line:</b></td></td>-->
                                <td style="width:10%;text-align:right "><b>Institution:</b></td><td colspan="" ><input type='text' style="width:90%;" name='Institution' id='Institution' value="<?php echo $Institution;?>"></td>
                                <td colspan=""><b> Ward/Unit:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Ward_Unit' id='Ward_Unit' value="<?php echo $Ward_Unit;?>"/></td>
                                <td colspan=""><b> Lab number:</b> <input type="text" autocomplete="off" style='width:60%;display:inline' name='Lab_number' id='Lab_number' value="<?php echo $Lab_number;?>"/></td>
                            </tr>
                                
                            </table>
                                        <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                    E:  ADVERSE EVENTS AND SUPPORTIVE CARE
                    </div>
                            <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                                <td colspan=""><b> Adverse events or complications after initiation of treatments:</b> <input type="text" autocomplete="off" style='width:40%;display:inline' name='Adverse_events' id='Adverse_events' value="<?php echo $Adverse_events;?>" /></td>
                                <td colspan=""><b> supportive care on pain medication, anti-diarrhea or anti-nausea:</b> <input type="text" autocomplete="off" style='width:39%;display:inline' name='supportive care' id='supportive care' value="<?php echo $supportive_care;?>"/></td>
                            </table>
                                                    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                    F:  FOLLOWUP (Registrar)
                    </div>
                            <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                                    
                                <tr>
                                <td style="width:10%;text-align:right "><b>Total cycles received/administered:</b></td></td>
                                <td style="width:10%;text-align:right "><b>1st Line:</b></td><td colspan="" ><input type='text' style="width:70%;" name='one_Line' id='one_Line' value="<?php echo $one_Line;?>">cycles</td>
                                <td colspan=""><b> 2nd Line:</b> <input type="text" autocomplete="off" style='width:50%;display:inline' name='two_Line' id='two_Line' value="<?php echo $two_Line;?>"/>cycles</td>
                                <td colspan=""><b> 3rd Line:</b> <input type="text" autocomplete="off" style='width:50%;display:inline' name='three_Line' id='three_Line' value="<?php echo $three_Line;?>" />cycles</td>
                            </tr>
                            <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                                <td colspan="" width="50%"><b> Date at last Contact:</b> <input type="text" autocomplete="off" style='text-align: center;width:40%;display:inline' name='Date_at_last_Contact' id='Date_at_last_Contact' value="<?php echo $Date_at_last_Contact; ?>" class="date"/></td>
                                <td colspan=""><b> Status at last contact:</b> Alive<input type="radio" autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Alive' <?php echo $Alive;?> />
                            Dead<input type="radio" <?php echo $Dead;?>  autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Dead' />Unknown<input type="radio" <?php echo $Unknown_status;?>  autocomplete="off" style='text-align: center;width:10%;display:inline' name='last_status_contact' id='Unknown_status' value='Unknown_status'/></td>
                            </table>
                            <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                            
                                <td colspan=""><b> Cause of death if known:</b> This cancer<input type="checkbox" autocomplete="off" style='text-align: center;width:10%;display:inline' name='This_cancer' id='This_cancer' <?php echo $This_cancer;?>  value='This cancer'/>
                            Trauma<input type="checkbox" autocomplete="off" <?php echo $Trauma;?>  style='text-align: center;width:10%;display:inline'  name='Trauma' id='Trauma' value='Trauma'/>Unknown<input type="checkbox"  <?php echo $Unknown_causes;?>  autocomplete="off" style='width:10%;display:inline' name='Unknown_causes' id='Unknown_causes' value='Unknown_causes'/></td>
                                <td colspan="" width="50%"><b> Other:</b> <input type="text" autocomplete="off" style='width:40%;display:inline' name='Other_causes' value="<?php echo $Other_causes; ?>" id='Other_causes'/></td>
                                
                            </table>
                            </table>

            </td>
        </tr>
    </table>
       
    </div>
