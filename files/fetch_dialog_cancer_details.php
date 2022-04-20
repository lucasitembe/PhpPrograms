<?php
include("./includes/connection.php");

    if(isset($_POST['cancer_id'])){
        $cancer_ID=$_POST['cancer_id'];
    }

    if(isset($_POST['name_cancer'])){
        $name_cancer=$_POST['name_cancer'];
    }
    if(isset($_POST['Registration_ID'])){
        $Registration_ID=$_POST['Registration_ID'];
    }
    $Patient_Payment_Item_List_ID  = $_POST['Patient_Payment_Item_List_ID'];

    $Diagnosis='';
    $Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'"))['consultation_ID']; 
    $select_pathorogy = mysqli_query($conn, "SELECT disease_name FROM tbl_disease_consultation dc, tbl_disease d WHERE dc.disease_ID=d.disease_ID AND diagnosis_type='diagnosis'  AND Consultation_ID='$Consultation_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_pathorogy)>0){
        while($rws = mysqli_fetch_assoc($select_pathorogy)){
            $Diagnosis = $rws['disease_name'];
        }
    }
    $HEIGHT =0;
    $WEIGHT = 0;
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
    $Select_Stage = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stage_name FROM tbl_cancer_stages cs, tbl_cancer_type ct WHERE cs.Stage_ID=ct.Stage_ID AND cancer_type_id='$cancer_ID'"))['stage_name'];
?>
<style>
    .button_pro{ 
         color:white !important;
         height:27px !important;   
    }
</style>
        <table class="table" style="background-color:white;">
            
            <tr>
                <td>
                    <b><center>Weight(kg):</center></b> 
                </td>
                <td>
                    <input type="text" name="weight" id="Weightboo" onkeyup="calculateBSA()" value="<?php echo $WEIGHT; ?>" class="form-control">
                </td>
                <td>
                    <b><center>Height(cm):</center></b> 
                </td>
                <td>
                    <input type="text" name="height" id="heightboo" onkeyup="calculateBSA()" value="<?php echo $HEIGHT; ?>" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>Body Surface(m2):</center></b> 
                </td>
                <td>
                    <input type="text" name="surface" id="bodysurface"  class="form-control">
                </td>
                <td>
                    <b><center>Diagnosis:</center></b> 
                </td>
                <td>
                    <input type="text" name="diagnosis" id="diagnosis" value='<?php echo $Diagnosis; ?>' class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>Stage:</center></b> 
                </td>
                <td>
                    <input type="text" name="stage" value="<?=$Select_Stage?>" id="stage" class="form-control">
                </td>
                <td>
                    <b><center>Weight Adjustment:</center></b> 
                </td>
                <td>
                    <input type="radio" name="weight" value="Yes" id="Yes">Yes
                    <input type="radio" name="weight" value="No" id="No">No
                </td>
            </tr>
            <tr>
                <td>
                    <b><center>% of Dose Adjustment:</center></b> 
                </td>
                <td>
                    <input type="text" name="dosead"  id="adjustmentdose" class="form-control" onkeyup="calculate_chemo_dose()">
                </td>
                <td>
                    <b><center>Allergies:</center></b> 
                </td>
                <td>
                   <input type="text" name="allergies" id="allergies" class="form-control">
                </td>
            </tr>

        </table>
                    <!-- <td>
                           <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;'/>
                        </td>
                          <td>
                           <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;'/>
                        </td> -->
   <?php
     $select_diagnosis=mysqli_query($conn,"SELECT Cancer_Name FROM tbl_cancer_type WHERE cancer_type_id='$cancer_ID'") or die(mysqli_error($conn));
     while($rowss=mysqli_fetch_assoc($select_diagnosis)){             
             $Cancer_Name =$rowss['Cancer_Name'];
     }
     echo   " <br>
            <div class='box box-primary' style='width:100%;'>
                        <div class='box-header'>
                            <div class='col-sm-12'><p id='sub_category_list_tittle' style='font-size:17px;text-align:center;font-weight: bold;'>$Cancer_Name</p></div>
                        </div>
                        
                        <div class='box-body'>
                            <table class='table' id='colum-addition'>
                           
                                   <tr width='50px;'>
                     
                        <td width='40%'>
                            <div class='title' style='text-align:center;'><b>Chemo Treatment</b></div>
                        </td>  
                        <td width='20%'>
                            <div class='title' style='text-align:center;'><b>Chemo Strength</b></div>
                        </td>                     
                        <td width='30%'>
                            <div class='title' style='text-align:center;'><b>Standard Duration</b></div>
                        </td>                        
                        <td width='5%'>
                            <div class='title' style='text-align:center;'><b>Selection</b></div>
                        </td>
                        <td width='5%'>
                             <input type='button'class='art-button-green' id='addrow1' value='Add'>
                        </td>
                    </tr>";
                    
                    
                        $adjNo=0;
                      $sql_data_cancer = mysqli_query($conn,"SELECT adjuvant,adjuvantstrenth, duration,date_and_time,adjuvant_ID FROM  tbl_adjuvant_duration WHERE cancer_type_id='$cancer_ID' AND status ='Enabled'");
                              if(mysqli_num_rows($sql_data_cancer)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer)){
                                      
                                      $adjuvant   =$values['adjuvant'];
                                      $duration   =$values['duration'];
                                      $adjuvant_ID=$values['adjuvant_ID'];
                                      $adjuvantstrenth = $values['adjuvantstrenth'];

                                      $array_name .= $adjuvantstrenth.","; 

                                      $adjNo++;
                                      echo "     <tr>
                                        <td>
                                        <input type='text' name='adjuvant[]' id='adjuvant[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$adjuvant'/>  
                                        </td> 
                                        <td>
                                        <input type='text' name='adjuvantstrenth[]' id='adjuvantstrenth_$adjNo' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center'  onkeyup='dosestreangth($adjNo)' value='$adjuvantstrenth' />  
                                        </td>                       
                                        <td>
                                        <input type='text' name='duration[]' id='duration[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$duration'/> 
                                        </td>
                                        
                                        <td>
                                        <input type='checkbox' class='valuone' style='width:50%;display:inline;height:20px;' value='$adjuvant_ID'/> 
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
                            <div class='title' style='text-align:center;'><b>Pre Hydration</b><br/>Physician to circle one in each column</div>
                        </td>
                    </tr>
                    <tr>
                       
                        <td width='50%'>
                            <table class='table' id='row-addition'>
                                <tr>
                                    <td>Name</td>
                                    <td>Volume</td>
                                    <td>Type</td>
                                    <td>Minutes</td>
                                    <td>Selection</td>
                                    <td> <input type='button'class='art-button-green' id='addrow_one' style='' value='Add'></td>
                                </tr>";
                                
                                              
                    
                      $sql_data_cancer_duration = mysqli_query($conn,"SELECT Physician_Item_name, physician_volume,physician_type,physician_minutes,date_and_time,physician_ID FROM tbl_physician WHERE cancer_type_id='$cancer_ID' AND status ='Enabled'") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_data_cancer_duration)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer_duration)){
                                      $Physician_Item_name = $values['Physician_Item_name'];
                                      $physician_volume=$values['physician_volume'];
                                      $physician_minutes=$values['physician_minutes'];
                                      $physician_type=$values['physician_type'];
                                      $physician_ID=$values['physician_ID'];
                                      echo "<tr>
                                            <td>
                                            <input type='text' name='Physician_Item_name[]' id='Physician_Item_name[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$Physician_Item_name'/>
                                            </td>
                                            <td>
                                            <input type='text' name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_volume'/>  
                                            </td>
                                            <td>
                                            <input type='text' name='type[]' id='type[]' autocomplete='off'style='width:100%;display:inline;height:30px;text-align:center' value='$physician_type'/> 
                                            </td>
                                            <td>
                                            <input type='text' name='minutes[]' id='minutes[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_minutes'/> 
                                            </td>
                                            <td>
                                            <input type='checkbox' class='physician' style='width:50%;display:inline;height:20px;' value='$physician_ID'/> 
                                            </td>
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
                <table class='table' style='background-color: white;' id='colum-addition_supportive'>
                    <tr>
                    <th>SN</th>
                    <th width='30%'>Supportive treatment</th>
                    <th width='8%'>Dose(mg)</th>
                    <th width='6%'>Route</th>
                    <th  width='8%'>Administration Time</th>
                    <th width='8%'>Frequence</th>
                    <th  width='20%'>Medication Instructions and Indications</th>
                    <th  width='8%'>selection</th>
                     <th>
                          <input type='button'class='art-button-green' id='addrow2' value='Add'>
                        </th>
                    </tr>";
                    
                                                                
                     $count=0;
                      $sql_data_cancer_supportive = mysqli_query($conn,"SELECT  supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,treatment_ID FROM  tbl_supportive_treatment WHERE cancer_type_id='$cancer_ID' AND status ='Enabled' ") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_data_cancer_supportive)>0){
                                  while($valuesv = mysqli_fetch_assoc($sql_data_cancer_supportive)){
                                      $count++;
                                      $supportive_treatment=$valuesv['supportive_treatment'];
                                      $Dose=$valuesv['Dose'];
                                      $Medication_Instructions=$valuesv['Medication_Instructions'];
                                      $Route=$valuesv['Route'];
                                      $Administration_Time=$valuesv['Administration_Time'];
                                      $Frequence=$valuesv['Frequence'];
                                      $treatment_ID=$valuesv['treatment_ID'];
                                    //  $Product_Name = $valuesv['Product_Name'];
                                      echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                           <input type='text' name='item[]' id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$supportive_treatment'/>
                        </td>
                        <td>
                            
                             <input type='text' name='dose[]' id='dose[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Dose'/>
                        </td>
                        <td>
                            <input type='text' name='route[]' id='route[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Route'/>
                        </td>
                          <td>
                           <input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Administration_Time'/>
                        </td>
                          <td>
                           <input type='text' name='frequence[]' id='frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Frequence'/>
                        </td>
                          <td>
                           <input type='text'  name='medication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Medication_Instructions'/>
                        </td>
                           <td>
                           <input type='checkbox' class='treatment' style='width:50%;display:inline;height:20px;' value='$treatment_ID'/> 
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
                        <th width='4%'>SN</th>
                        <th width='30%'>Chemotherapy Drug</th>
                        <th width='15%'>Dose(mg)</th>
                        <th  width='6%'>Route</th>
                        <th width='8%'>Admin Time</th>
                        <th  width='30%'>Frequency</th>
                        <th  width='5%'>selection</th>
                    </tr>";
                    //exclude as the streangth
                   // <th width='6%'>Volume(ml)</th>
                   
                   $count=0;
                   
                      $sql_data_cancer_drug = mysqli_query($conn,"SELECT chemotherapy_ID,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,chemotherapy_ID FROM tbl_chemotherapy_drug  WHERE cancer_type_id='$cancer_ID' AND status ='Enabled' ") or die(mysqli_error($conn));

                      $names_implode = explode(",",$array_name);
                      $count_name = 0;
                              if(mysqli_num_rows($sql_data_cancer_drug)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer_drug)){ 
                                      $count++;
                                  //    $chemotherapy_ID = $values['chemotherapy_ID'];
                                      $Chemotherapy_Drug=$values['Chemotherapy_Drug'];
                                      $Dose=$values['Dose'];
                                      $Volume=$values['Volume'];
                                      $Route=$values['Route'];
                                      $Admin_Time=$values['Admin_Time'];
                                      $Frequency=$values['Frequency'];
                                      $chemotherapy_ID=$values['chemotherapy_ID'];
                                    //  $Product_Name = $values['Product_Name'];
                                    //AND Chemotherapy_Drug=i.item_id
                                    
                                    $chemo_id .= $chemotherapy_ID.",";
                        echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                           <input type='text' name='item[]'  id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Chemotherapy_Drug ($Volume)'/>
                        </td>
                        <td>
                             <input type='text' name='drugdose[]' class='dose[]' id='dose".$chemotherapy_ID."' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;'  value=''/>
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
                           <input type='checkbox' class='drug'  autocomplete='off' style='width:50%;display:inline;height:20px;' value='$chemotherapy_ID'/>
                        </td>
                </tr> ";
                //excluded as the streangth
            //     <td>
            //     <input type='text'  name='volume[]' id='volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Volume'/>
            //  </td>
            $count_name++; 
                                  }
                              }else{
//                                  echo "empty data to diplay";onkeyup='changedose(this)'
                              }

                      $chemo_id_array = explode(',',$chemo_id);
           
               echo "</table>
                    </td> 
                </tr>
                
                            </table>
                            </br>
                            </br>
                         <div align='right'> <button class='art-button button_pro' onclick='save_patient_detals($cancer_ID)'>ASSIGN  PROTOCAL</button></div> 
                        </div>
                    </div>";
               
        ?>

<script>
    $(document).ready(function(){
        calculateBSA()
    })

    function calculateBSA(){
        var height = $("#heightboo").val();
        var weight = $("#Weightboo").val();
        var productbsa = (height * weight)/3600
        var CalBSA = Math.sqrt(productbsa);
        var calculatesba = CalBSA.toFixed(1);
        $("#bodysurface").val(calculatesba);
    }
                    
    $('#addrow1').click(function () {
        $.ajax({
            type: 'POST',
            url: 'Cancer_pharmacy_items.php',
            data: "",
            success: function (result) {
            var rowCount = parseInt($('#rowCount').val()) + 1;
            var newRow = "<tr class='addnewrow tr" + rowCount + "'><td>"+result+"</td><td><input style='width:100%' type='text' name='adjuvantstreanth[]' placeholder='streangth'></td><td><input name='adjuvant[]' id='adjuvant[]' type='text' class='adjuvant' id='" + rowCount + " ' style='width:100%'><td><input name='duration[]' id='duration[]' type='text' class='patient_dose' id='" + rowCount + " ' style='width:100%'></td><td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
            $('#colum-addition').append(newRow);
            document.getElementById('rowCount').value = rowCount;
            $("select").select2();
            }
        });
    });

    $('#addrow2').click(function () {
        $.ajax({
            type: 'POST',
            url: 'Cancer_pharmacy_items.php',
            data: "",
            success: function (result) {
            var rowCount = parseInt($('#rowCount').val()) + 1;
            var newRow = "<tr class='addnewrow tr" + rowCount + "'><td></td><td>"+result+"</td><td><input type='text' name='dose[]' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='route[]'  id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='admin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td>  <input type='text' name='frequence[]' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/> </td><td>  <input type='text'  name='medication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>  </td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
            $('#colum-addition_supportive').append(newRow);
            document.getElementById('rowCount').value = rowCount;
            $("select").select2();
            }
        });
    });
            $('#addrow_one').click(function () {
                var rowCount = parseInt($('#rowCount').val()) + 1;
                var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><input name='volume[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='type[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='minutes[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
                $('#row-addition').append(newRow);
                document.getElementById('rowCount').value = rowCount;
            });
            
                
            $(document).on('click', '.remove', function () {
                var id = $(this).attr('row_id');
                $('.tr' + id).remove().fadeOut();
            });


            function calculate_chemo_dose(){
                var get_names_array = '<?=$array_name?>';
                var get_chemo_array = '<?=$chemo_id?>';

                var new_name_string = get_names_array.replace(/,\s*$/,"");
                var new_chemo_string = get_chemo_array.replace(/,\s*$/,"");

                var names = new_name_string.split(",");
                var chemo = new_chemo_string.split(",");
                
                var bodysurface = document.getElementById('bodysurface').value;
                var adjustmentdose = document.getElementById('adjustmentdose').value;

                for(var i = 0;i < names.length; i++){
                    document.getElementById('dose'+chemo[i]).value = ((bodysurface*adjustmentdose*names[i])/100).toFixed(2);
                }
            }


            // function dosestreangth(adjNo){

            //     var adjuvantstrenth = $("#adjuvantstrenth_"+adjNo);
            //     var adjustmentdose = $("#adjustmentdose").val();
            //     var bodysurface = $("#bodysurface").val(); 
            //     var chemoDosage = (adjuvantstrenth * adjustmentdose * bodysurface)/100
            //     $("#dose"+adjNo).val(chemoDosage);
            //     //console.log(chemoDosage);
            //     alert(adjuvantstrenth);
            // }
</script>
         
            
             

