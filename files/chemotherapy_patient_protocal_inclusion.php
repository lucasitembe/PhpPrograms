<?php 
session_start();


$select_patient_details =mysqli_query($conn, "SELECT * FROM tbl_cancer_patient_details WHERE Registration_ID='$Registration_ID' AND Patient_protocal_details_ID='$Patient_protocal_details_ID'") or die(mysqli_error($conn));
while($details_rw = mysqli_fetch_assoc($select_patient_details)){
    $weight = $details_rw['weight'];
    $height = $details_rw['height'];
    $body_surface = $details_rw['body_surface'];
    $diagnosis = $details_rw['diagnosis'];
    $weight_adjustment = $details_rw['weight_adjustment'];
    $allergies = $details_rw['allergies'];
    $dose_adjustment = $details_rw['dose_adjustment'];
    $stage = $details_rw['stage'];

    if($weight_adjustment =="No"){
         $weight_adjustment_no ="checked='checked'";
     }else{
         $weight_adjustment_yes ="checked='checked'";
     }
}
$select_vitals = mysqli_query($conn, "SELECT Vital, nv.Vital_ID, Vital_Value FROM tbl_nurse n, tbl_vital v, tbl_nurse_vital nv  WHERE Registration_ID='$Registration_ID' AND v.Vital_ID=nv.Vital_ID AND nv.Nurse_ID=n.Nurse_ID AND Vital ='HEIGHT' ORDER BY nv.Nurse_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_vitals)>0){
        while($vitls_rw = mysqli_fetch_assoc($select_vitals)){
            $Vital= $vitls_rw['Vital'];
            $Vital_Value = $vitls_rw['Vital_Value']; 
            $Vital_ID = $vitls_rw['Vital_ID'];
            $HEIGHT = $Vital_Value;
            
            
        }
    }
   
    $select_vitals = mysqli_query($conn, "SELECT Vital, nv.Vital_ID, Vital_Value FROM tbl_nurse n, tbl_vital v, tbl_nurse_vital nv  WHERE Registration_ID='$Registration_ID' AND v.Vital_ID=nv.Vital_ID AND nv.Nurse_ID=n.Nurse_ID AND Vital = 'WEIGHT' ORDER BY nv.Nurse_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_vitals)>0){
        while($vitls_rw = mysqli_fetch_assoc($select_vitals)){
            $Vital= $vitls_rw['Vital'];
            $Vital_Value = $vitls_rw['Vital_Value']; 
            $Vital_ID = $vitls_rw['Vital_ID'];
            $WEIGHT = $Vital_Value;            
        }
    }
 ?> 
<table class="table" style="background-color:white;">
         
         <tr>
             <td>
                 <b><center>Weight(kg):</center></b> 
             </td>
             <td>
                <?php echo $WEIGHT;?>
                 <input type="text"  id="Weightboo" onkeyup="calculateBSA()" class="form-control" value="" style="text-align: center; width:90%; display:inline;" name="Cycle_details[]">
             </td>
             <td>
                 <b><center>Height(cm):</center></b> 
             </td>
             <td>
                 <input type="text"  id="heightboo" onkeyup="calculateBSA()" class="form-control"  style="text-align: center;" value="<?php echo $HEIGHT;?>" name="Cycle_details[]">
             </td>
         </tr>
         <tr>
             <td>
                 <b><center>Body Surface(m2):</center></b> 
             </td>
             <td>
                 <input type="text"  id="bodysurface" class="form-control"  style="text-align: center;" value="<?php echo $body_surface;?>" name="Cycle_details[]">
             </td>
             <td>
                 <b><center>Diagnosis:</center></b> 
             </td>
             <td>
                 <input type="text"  id="diagnosis"  style="text-align: center;" class="form-control" value="<?php echo $diagnosis;?>" name="Cycle_details[]">
             </td>
         </tr>
         <tr>
             <td>
                 <b><center>Stage:</center></b> 
             </td>
             <td>
                 <input type="text"  id="stage" class="form-control"  style="text-align: center;" value="<?php echo $stage;?>" name="Cycle_details[]">
             </td>
             <td>
                 <b><center>Weight Adjustment:</center></b> 
             </td>
             <td>
                 <input type="radio"  name="Cycle_details[]" value="Yes" id="Yes" <?php echo $weight_adjustment_yes;?>>Yes
                 <input type="radio"  name="Cycle_details[]" value="No" id="No"  <?php echo $weight_adjustment_no;?>  >No
             </td>
         </tr>
         <tr>
             <td>
                 <b><center>% of Dose Adjustment:</center></b> 
             </td>
             <td>
                 <input type="text" name="Cycle_details[]"  id="adjustmentdose" class="form-control" value="<?php echo $dose_adjustment;?>"   style="text-align: center;" onkeyup="calculate_chemo_dose()">
             </td>
             <td>
                 <b><center>Allergies:</center></b> 
             </td>
             <td>
                <input type="text" name="Cycle_details[]" id="allergies" class="form-control" value="<?php echo $allergies;?>"  style="text-align: center;" >
             </td>
         </tr>

     </table>
     <?php

   
  echo   " <br>
         <div class='box box-primary' style='width:100%;'>
                     <div class='box-header'>
                         <div class='col-sm-12'><p id='sub_category_list_tittle' style='font-size:17px;text-align:center;font-weight: bold;'>$name_cancer</p></div>
                     </div>
                     
                     <div class='box-body'>
                         <table class='table' id='colum-addition'>
                        
                                <tr width='50px;'>
                  
                     <td width='50%'>
                         <div class='title' style='text-align:center;'><b>Chemo Treatment</b></div>
                     </td>                     
                     <td width='50%'>
                         <div class='title' style='text-align:center;'><b>Standard Duration</b></div>
                     </td>
                     
                     
                     
                 </tr>";
                 
                 
               
                   $sql_data_cancer = mysqli_query($conn,"SELECT adjuvant,duration, adjuvantstrenth FROM  tbl_patient_adjuvant_duration WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
                           if(mysqli_num_rows($sql_data_cancer)>0){
                               while($values = mysqli_fetch_assoc($sql_data_cancer)){
                                   
                                   $adjuvant   =$values['adjuvant'];
                                   $duration   =$values['duration'];
                                //    $adjuvant_ID=$values['adjuvant_ID'];
                                   $adjuvantstrenth = $values['adjuvantstrenth'];

                                   $array_name .= $adjuvantstrenth.","; 
                                   echo "     <tr>
                     <td>
                       <input type='text' name='adjuvant[]' id='adjuvant[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$adjuvant'/>  
                       <input type='text' name='adjuvantstrenth[]' id='adjuvantstrenth' autocomplete='off' style='width:100%;display:none;height:30px; text-align:center'   value='$adjuvantstrenth' />  

                     </td>
                     
                     <td>
                        <input type='text' name='duration[]' id='duration[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$duration'/> 
                     </td>
                    
                    
                 </tr> 
                 <tr id='colum-addition'> 
                 </tr> ";
                               }
                           }else{
//                                  echo "empty data to diplay";
                           }
                 
            
                  echo "<tr style='margin-top:30px;'>
                 <td>
                      
               <tr>
                    <table class='table' style='background-color: white;margin:0% 0% 0% 5%;width:90%' >
                    </br>
                      </br>

                 <tr>
                   
                     <td width='50%'>
                         <div class='title' style='text-align:center;'><b>Pre Hydration </b><br/>Physician to circle one in each column</div>
                     </td>
                 </tr>
                 <tr>
                    
                     <td width='50%'>
                         <table class='table' id='row-addition'>
                             <tr>
                                <td>Name</td>
                                <td>Location</td>
                                <td>Balance</td>
                                <td>Volume</td>
                                <td>Type</td>
                                <td>Minutes</td>
                                <td>Select</td>
                             </tr>";
                             
                                           
                 
                   $sql_data_cancer_duration = mysqli_query($conn,"SELECT Physician_Item_name,physician_ID, physician_volume,physician_type,physician_minutes,date_and_time FROM tbl_patient_physician WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                           if(mysqli_num_rows($sql_data_cancer_duration)>0){
                               while($values = mysqli_fetch_assoc($sql_data_cancer_duration)){
                                    $Physician_Item_name =$values['Physician_Item_name'];
                                   $physician_volume=$values['physician_volume'];
                                   $physician_minutes=$values['physician_minutes'];
                                   $physician_type=$values['physician_type'];
                                   $physician_ID=$values['physician_ID'];
                                   //  $Product_Name = $valuesv['Product_Name'];
                                 $select_medicine = mysqli_query($conn,   "SELECT Sub_Department_Name, Sub_Department_ID FROM tbl_department d,tbl_sub_department s where  d.Department_ID = s.Department_ID and s.Sub_Department_Status='active' and d.Department_Location  = 'Pharmacy' ");
                            
                                 $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
                                 $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                 
                                 $select_doctor_clinic_phamarcy = mysqli_query($conn, "SELECT Sub_Department_Name, ed.Sub_Department_ID from tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed where dep.department_id = sdep.department_id and ed.Employee_ID = '$Employee_ID' and ed.Sub_Department_ID = sdep.Sub_Department_ID and Department_Location = 'Pharmacy' and sdep.Sub_Department_Status = 'active' LIMIT 1 " ) or die(mysqli_error($conn));
                                 
                                   echo "<tr>
                                        <td>$Physician_Item_name</td>
                                        <input type='text' name='item[]' id='item[]' autocomplete='off' style='width:100%;display:none;height:30px;'  class='item_name_$physician_ID' value='$Physician_Item_name' />
                                         <td>";
                               
                                         echo  "<select id='Sub_Department_ID_$physician_ID' onchange='get_pharmacy_item_balance($physician_ID)' name='physselectedPharmacy[]'>";
                                         while($doctor_phamarcy_rw = mysqli_fetch_assoc($select_doctor_clinic_phamarcy) ){
                                             $pharmacy_id = $doctor_phamarcy_rw['Sub_Department_ID'];
                                             $Sub_Department_Name = $doctor_phamarcy_rw['Sub_Department_Name'];
                                             echo  "<option value='$pharmacy_id'>$Sub_Department_Name </option>";
                                         }
                                         if(mysqli_num_rows($select_medicine)>0){
                                             while($med_rw = mysqli_fetch_assoc($select_medicine) ){
                                                 $pharmacy_id = $med_rw['Sub_Department_ID'];
                                                 $Sub_Department_Name = $med_rw['Sub_Department_Name'];
                                                 echo  "<option value='$pharmacy_id'>$Sub_Department_Name </option>";
                                             }
                                         }else{
                                             echo  "No result found";
                                         }
                                        echo "</select>
                                        </td>
                                        <td><input type='text' style='text-align:center; ' id='balance_$physician_ID' value=''  readonly></td>
                                        <td>
                                         <input type='text' name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_volume'/>  
                                         </td>
                                         <td>
                                         <input type='text' name='type[]' id='type[]' autocomplete='off'style='width:100%;display:inline;height:30px;text-align:center' value='$physician_type'/> 
                                         </td>
                                         <td>
                                         <input type='text' name='minutes[]' id='minutes[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_minutes'/> 
                                         </td>
                                         <td><input type='checkbox' class='physician_ID' style='width:50%;display:inline;height:20px;'  onclick='get_pharmacy_item_balance($physician_ID)' id='selvalue_$physician_ID' value='$physician_ID' /></td>
                                     </tr> ";
                               }
                           }else{
//                                  echo "empty data to diplay";
                           }
               
              
                    echo "</table>
                        <input type='hidden' id='rowCount' value='1'>
                     </td>
                 </tr>
            
                 </table>
               </tr>
                 </td>
             </tr>
             
                                 <tr style='margin-top:30px;'>
           
                 <td>
             </br>
             <div class='title' style='text-align:center;'><b>Supportive Treatment</b></div>
             <table class='table' style='background-color: white;' id='colum-addition_supportive'>
                 <tr>
                 <th width='2%'>SN</th>
                 <th width='30%'>Supportive treatment</th>
                 <th width='12%'>Location</th>
                 <th width='9%'>Balance</th>
                 <th width='8%'>Dose(mg)</th>
                 <th width='5%'>Route</th>
                 <th  width='6%'>Administration Time</th>
                 <th width='7%'>Frequence</th>
                 <th  width='20%'>Medication Instructions and Indications</th>
                 <th width='5'>Select</th>
                 </tr>";
                 
                                                             
                  $count=0;
                   $sql_data_cancer_supportive = mysqli_query($conn,"SELECT patient_supportive_ID, supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions FROM  tbl_patient_supportive_treatment WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
                           if(mysqli_num_rows($sql_data_cancer_supportive)>0){
                               while($valuesv = mysqli_fetch_assoc($sql_data_cancer_supportive)){
                                   $count++;
                                   $patient_supportive_ID = $valuesv['patient_supportive_ID'];
                                   $supportive_treatment=$valuesv['supportive_treatment'];
                                   $Dose=$valuesv['Dose'];
                                   $Medication_Instructions=$valuesv['Medication_Instructions'];
                                   $Route=$valuesv['Route'];
                                   $Administration_Time=$valuesv['Administration_Time'];
                                   $Frequence=$valuesv['Frequence'];
                                   
                                 //  $Product_Name = $valuesv['Product_Name'];
                                 $select_medicine = mysqli_query($conn,   "SELECT Sub_Department_Name, Sub_Department_ID FROM tbl_department d,tbl_sub_department s where  d.Department_ID = s.Department_ID and s.Sub_Department_Status='active' and d.Department_Location  = 'Pharmacy' ");
                            
//
//
$doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$select_doctor_clinic_phamarcy = mysqli_query($conn, "SELECT Sub_Department_Name, ed.Sub_Department_ID from tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed where dep.department_id = sdep.department_id and ed.Employee_ID = '$Employee_ID' and ed.Sub_Department_ID = sdep.Sub_Department_ID and Department_Location = 'Pharmacy' and sdep.Sub_Department_Status = 'active' LIMIT 1 " ) or die(mysqli_error($conn));

     echo "<tr><td>
                 <center>$count</center>  
                     </td>
                     <td>
                    
                        <input type='text' name='item[]' id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;'  class='item_name_$patient_supportive_ID' value='$supportive_treatment' />
                     </td>
                     <td>";
                               
                                echo  "<select id='Sub_Department_ID_$patient_supportive_ID' onchange='get_pharmacy_item_balance($patient_supportive_ID)' name='treatselectedPharmacy[]'>";
                                while($doctor_phamarcy_rw = mysqli_fetch_assoc($select_doctor_clinic_phamarcy) ){
                                    $pharmacy_id = $doctor_phamarcy_rw['Sub_Department_ID'];
                                    $Sub_Department_Name = $doctor_phamarcy_rw['Sub_Department_Name'];
                                    echo  "<option value='$pharmacy_id'>$Sub_Department_Name </option>";
                                }
                                if(mysqli_num_rows($select_medicine)>0){
                                    while($med_rw = mysqli_fetch_assoc($select_medicine) ){
                                        $pharmacy_id = $med_rw['Sub_Department_ID'];
                                        $Sub_Department_Name = $med_rw['Sub_Department_Name'];
                                        echo  "<option value='$pharmacy_id'>$Sub_Department_Name </option>";
                                    }
                                }else{
                                    echo  "No result found";
                                }
                            echo "</select>
                     </td>
                     <td><input type='text' style='text-align:center; ' id='balance_$patient_supportive_ID' value=''  readonly></td>
                     <td>
                          <input type='text' name='treatmentdose[]' id='dose[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center;' value='$Dose'/>
                     </td>
                     <td>
                         <input type='text' name='route[]' id='route[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center;' value='$Route'/>
                     </td>
                       <td>
                        <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center;' value='$Administration_Time'/>
                     </td>
                       <td>
                        <input type='text' name='frequence[]' id='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center;' value='$Frequence'/>
                     </td>
                    <td>
                        <input type='text'  name='medication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center;' value='$Medication_Instructions'/>
                    </td>
                     <td>
                     <input type='checkbox' class='treatment' style='width:50%;display:inline;height:20px;'  onclick='get_pharmacy_item_balance($patient_supportive_ID)' id='selvalue_$patient_supportive_ID' value='$patient_supportive_ID' /> 
                  </td>
                     </tr><tr id='colum-addition_supportive'> 
                 </tr>";
                               }
                           }else{
//                                  echo "empty data to diplay";
                           }
                   
         
            echo "</table>
                 </td>
             </tr>


            
                        <tr style='margin-top:30px;'>
           
                 <td>
             </br>
             <div class='title' style='text-align:center;'><b>Chemotherapy Drug</b></div>
             <table class='table' style='background-color: white;'>
                 <tr>
                 <th width='2%'>SN</th>
                 <th width='30%'>Chemotherapy Drug</th>
                 <th width='12%'>Location</th>
                 <th width='8%'>Balance</th>
                 <th width='9%'>Dose(mg)</th>                
                 <th  width='5%'>Route</th>                    
                 <th width='8%'>End Time</th>
                 <th  width='20%'>Frequency</th>
                 <th width='5'>Select</th>
                 </tr>";
                 
                       // <th width='6%'>Volume(ml)</th> named as strength                                      
                  $count=0;
                //  die("SELECT patient_chemotherapy_ID,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency FROM tbl_patient_chemotherapy_drug  WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID'");
                   $sql_data_cancer_drug = mysqli_query($conn,"SELECT patient_chemotherapy_ID,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency FROM tbl_patient_chemotherapy_drug  WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                           if(mysqli_num_rows($sql_data_cancer_drug)>0){
                               while($values = mysqli_fetch_assoc($sql_data_cancer_drug)){ 
                                   $count++;
                                   $Chemotherapy_Drug=$values['Chemotherapy_Drug'];
                                   $patient_chemotherapy_ID = $values['patient_chemotherapy_ID'];
                                   $Dose=$values['Dose'];
                                   $Volume=$values['Volume'];
                                   $Route=$values['Route'];
                                   $Admin_Time=$values['Admin_Time'];
                                   $Frequency=$values['Frequency'];
                                   $select_medicine = mysqli_query($conn,   "SELECT Sub_Department_Name, Sub_Department_ID FROM tbl_department d,tbl_sub_department s where  d.Department_ID = s.Department_ID and s.Sub_Department_Status='active' and
                                   d.Department_Location  = 'Pharmacy' ");
                                 //  $Product_Name = $values['Product_Name'];
                                 //AND Chemotherapy_Drug=i.item_id
                                 $chemo_id .= $patient_chemotherapy_ID.",";
                                   echo "<tr><td>
                 <center>$count</center>  
                     </td>
                     <td>
                     <input type='text' name='itemdrug[]'  id='item[]' autocomplete='off' style='width:100%;display:none;height:30px; ' class='chemo_item_name_$patient_chemotherapy_ID'  value='$Chemotherapy_Drug'/>

                        <input type='text' name='itemdrug[]'  id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;'   value='$Chemotherapy_Drug ( $Volume)'/>
                     </td>
                     <td>
                            <select id='chemo_Sub_Department_ID_$patient_chemotherapy_ID' name='drugselectedPharmacy[]' onchange='get_item_balance($patient_chemotherapy_ID)'>";
                            while($phamarcy_rw = mysqli_fetch_assoc($select_doctor_clinic_phamarcy) ){
                                $pharmacy_id = $phamarcy_rw['Sub_Department_ID'];
                                $Sub_Department_Name = $phamarcy_rw['Sub_Department_Name'];
                                echo  "<option value='$pharmacy_id'>$Sub_Department_Name </option>";
                            } 
                            if(mysqli_num_rows($select_medicine)>0){
                                while($med_rw = mysqli_fetch_assoc($select_medicine) ){
                                    $pharmacy_id = $med_rw['Sub_Department_ID'];
                                    $Sub_Department_Name = $med_rw['Sub_Department_Name'];
                                    echo "<option value='$pharmacy_id'>$Sub_Department_Name</option>";
                                }
                            }else{
                                echo "<option value=''>No result found</option>";
                            }
                        echo"</select>
                     </td>
                     <td><input type='text' id='chemo_balance_$patient_chemotherapy_ID' placeholder='Balance' readonly='readonly' value=''></td>
                     <td>
                          <input type='text' name='chemodrugdose[]' id='dose$patient_chemotherapy_ID' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Dose'/>
                     </td>
                    
                     <td>
                         <input type='text' name='route[]' id='route[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Route'/>
                     </td>
                       <td>
                        <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Admin_Time'/>
                     </td>                     
                   
                       <td>
                        <input type='text' name='frequence[]'  id='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Frequency'/>
                     </td>
                     <td>
                     <input type='checkbox' class='drug'  autocomplete='off' style='width:50%;display:inline;height:20px;' value='$patient_chemotherapy_ID' onclick='get_item_balance($patient_chemotherapy_ID)' id='chemo_selvalue_$patient_chemotherapy_ID'/>
                  </td>
                       
             </tr> ";
             //this is included as the streangth 
        //      <td>
        //      <input type='text'  name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Volume'/>
        //   </td>
                               }
                           }else{
//                                  echo "empty data to diplay";
                           }
                           $chemo_id_array = explode(',',$chemo_id);
        
            echo "</table>
                 </td>
             </tr>
         </table>
         <br/>
         <table class='table'>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Employee Assigned<th>
                    <th>Date of status</th>
                </tr>
            </thead>
            <tbody id='protocal_stutus'>
                        ";
                   
$select_patient_protocal_status =mysqli_query($conn, "SELECT Protocal_status,Remarks,Status_date,Employee_Name  FROM tbl_cancer_patient_details, tbl_employee e WHERE Registration_ID='$Registration_ID' AND Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Employee_changed_status=e.Employee_ID ") or die(mysqli_error($conn));
            $num=0;
    if(mysqli_num_rows($select_patient_protocal_status)>0){
    while($details_rw = mysqli_fetch_assoc($select_patient_protocal_status)){
        $Protocal_status = $details_rw['Protocal_status'];
        $Remarks = $details_rw['Remarks'];
        $Employee_changed_status = $details_rw['Employee_Name'];
        $Status_date = $details_rw['Status_date'];
       
        $num++;
        echo "<tr>
            <td>$num</td>
            <td>$Protocal_status</td>
            <td>$Remarks</td>
            <td>$Employee_changed_status</td>
            <td>$Status_date</td>
            </tr>";
        
    }
}else{
    echo "<tr><td colspan='6' style='color:green; align-text:center;' class='text-center'>Protocal status is on Progress .......</td></tr>";
}
    echo "</tbody>
    <table>";
     ?>
