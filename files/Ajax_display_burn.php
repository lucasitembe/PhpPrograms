
<?php

include("./includes/connection.php");
    
session_start();
if(isset($_POST['burndialog'])){
    $select_burn_type = mysqli_query($conn, "SELECT * FROM tbl_burn_type") or die(mysqli_error($conn));
    $num=0;
    if((mysqli_num_rows($select_burn_type))>0){
        while($row = mysqli_fetch_assoc($select_burn_type)){ 
            $Burn_ID = $row['Burn_ID'];
            $type_burn = $row['type_burn'];
            $num++;
            echo "<tr><td>$num</td><td>$type_burn</td><td>
                <span><button class='btn btn-info' name='btn_editb'  onclick='edit_burn($Burn_ID)' >Edit</button>
                &nbsp;<button class='btn btn-danger' onclick='remove_burn($Burn_ID)' name='remove_btnb'>X</button>
                </span>    
            </td></tr>";
        }
    }else{
        echo "<tr><td colspan='3'>No result found</td></tr>";
    }
}
$Employee_ID  = $_SESSION['userinfo']['Employee_ID'];

if(isset($_POST['classific'])){
    $select_burn_classfication = mysqli_query($conn, "SELECT * FROM tbl_burn_classfication") or die(mysqli_error($conn));
    $num=0;
    if((mysqli_num_rows($select_burn_classfication))>0){
        while($row = mysqli_fetch_assoc($select_burn_classfication)){
            $Classfication_ID = $row['Classfication_ID'];
            $Classfication_of_burn = $row['Classfication_of_burn'];
            $num++;
            echo "<tr><td>$num</td><td>$Classfication_of_burn</td><td>
                <span><button class='btn btn-info' onclick='edit_burn($Classfication_ID)' >Edit</button>&nbsp;
                <button class='btn btn-danger' name='remove_btn' onclick='remove_classfication($Classfication_ID)'>X</button>
                </span>    
            </td></tr>";
        }
    }else{
        echo "<tr><td colspan='3'>No result found</td></tr>";
    }
}
if(isset($_POST['remove_btn'])){
    $Classfication_ID = $_POST['Classfication_ID'];
    $sql_remove = mysqli_query($conn, "DELETE FROM `tbl_burn_classfication` WHERE  Classfication_ID='$Classfication_ID'" ) or die(mysqli_error($conn));
    if(!$sql_remove){
        echo "failed";
    }else{
        echo "Success";
    }
}

if(isset($_POST['remove_btnb'])){
    $Burn_ID = $_POST['Burn_ID'];
    $sql_removes = mysqli_query($conn, "DELETE FROM `tbl_burn_type` WHERE  Burn_ID='$Burn_ID'" ) or die(mysqli_error($conn));
    if(!$sql_removes){
        echo "failed";
    }else{
        echo "Success";
    }
}
if(isset($_POST['btn_classfc'])){        
    $Classfication_of_burn = $_POST['Classfication_of_burn'];

    $sql_insert = mysqli_query($conn, "INSERT INTO tbl_burn_classfication (Classfication_of_burn, Employee_ID) VALUES('$Classfication_of_burn', '$Employee_ID' )") or die(mysqli_error($conn));
    if(!$sql_insert){
        echo "Failed";
    }else{
        echo "Done";
    }
    
    
}

if(isset($_POST['infobody'])){
    $Assessment_ID = $_POST['Assessment_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    ?>
    <tr style="background:#eaf0e3;">
    <td></td>
    <td>0000:00:00</td>
        <td><input type="checkbox" id="Airway" ></td>
        <td><input type="checkbox" id="Breathing" ></td>
        <td><input type="checkbox" id="Ciculation" ></td>
        <td><input type="checkbox" id="Level_of_consciousness" ></td>
        <td><input type="checkbox" id="Pain" ></td>
        <td><input type="checkbox" id="Fluid_intake"></td>
        <td><input type="checkbox" id="Fluid_output"></td>
        <td><input type="checkbox" id="Fluid_balance" ></td>
        <td><input type="checkbox" id="Body_temperature" ></td>
        <td><input type="checkbox" id="Food_nutrition_electrolytes" ></td>
        <td><input type="checkbox" id="Elimination" ></td>
        <td><input type="checkbox" id="Haygiene_body" ></td>
        <td><input type="checkbox" id="Haygiene_Oral_eyes" ></td>
        <td><input type="checkbox" id="Wounds" ></td>
        <td><input type="checkbox" id="Risk_of_pressure" ></td>
        <td><input type="checkbox" id="Drains" ></td>
        <td><input type="checkbox" id="Exercise" ></td>
        <td><input type="checkbox" id="Social_well_being" ></td>
        <td><input type="checkbox" id="Psychological_well_being" ></td>
        <td><input type="checkbox" id="Spiritual_well_being" ></td>
        <td><input type="checkbox" id="Environment_equipment" ></td>
        <td><input type="checkbox" id="Information_education" ></td>
        <td><input type="checkbox" id="tests" ></td>
        <td><input type="checkbox" id="Treatment" ></td>
        <td><input type="checkbox" id="Payments" ></td>
        <td><input type="checkbox" id="Rest_sleep" ></td>
        <td><input type="checkbox" id="Creative_activities" ></td>
        <td><input type="checkbox" id="Other_problems" ></td>                            
        
        <td><input type="button" name="info_btn" class="btn btn-primary" onclick="save_assessment_data('<?php echo $Assessment_ID;?>','<?php echo $Registration_ID;?>')" value="Save"></td>
    </tr>
    <?php
    $select_info = mysqli_query($conn, "SELECT ai.Employee_ID,Info_assessment_ID,Assessment_data, Employee_Name, ai.created_at  FROM tbl_assessment_information ai, tbl_employee e WHERE e.Employee_ID=ai.Employee_ID AND Assessment_ID='$Assessment_ID' ORDER BY Info_assessment_ID DESC") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_info)>0){
        while($info_rw = mysqli_fetch_assoc($select_info)){
            $Assessment_data = explode(',', $info_rw['Assessment_data']);
            $created_at = $info_rw['created_at'];
            $Info_assessment_ID = $info_rw['Info_assessment_ID'];
            $Employee_Name = $info_rw['Employee_Name'];
            $employee = $info_rw['Employee_ID'];
            foreach($Assessment_data as $data){
                
                if($data=="Airway"){
                    $Airway ="checked='checked'";
                   }
                if($data=="Breathing"){
                    $Breathing ="checked='checked'";
                   }
                if($data=="Ciculation"){
                    $Ciculation ="checked='checked'";
                   }
                   if($data=="Level_of_consciousness"){
                    $Level_of_consciousness ="checked='checked'";
                   }
                if($data=="Pain"){
                    $Pain ="checked='checked'";
                   }
                if($data=="Fluid_intake"){
                    $Fluid_intake ="checked='checked'";
                   }
                   if($data=="Fluid_output"){
                    $Fluid_output ="checked='checked'";
                   }
                if($data=="Fluid_balance"){
                    $Fluid_balance ="checked='checked'";
                   }
                if($data=="Body_temperature"){
                    $Body_temperature ="checked='checked'";
                   }
                   if($data=="Food_nutrition_electrolytes"){
                    $Food_nutrition_electrolytes ="checked='checked'";
                   }
                if($data=="Elimination"){
                    $Elimination ="checked='checked'";
                   }
                if($data=="Haygiene_body"){
                    $Haygiene_body ="checked='checked'";
                   }
                   if($data=="Haygiene_Oral_eyes"){
                    $Haygiene_Oral_eyes ="checked='checked'";
                   }
                if($data=="Wounds"){
                    $Wounds ="checked='checked'";
                   }
                if($data=="Risk_of_pressure"){
                    $Risk_of_pressure ="checked='checked'";
                   }
                   if($data=="Drains"){
                    $Drains ="checked='checked'";
                   }
                if($data=="Exercise"){
                    $Exercise ="checked='checked'";
                   }
                if($data=="Social_well_being"){
                    $Social_well_being ="checked='checked'";
                   }
                   if($data=="Social_well_being"){
                    $Social_well_being ="checked='checked'";
                   }
                if($data=="Psychological_well_being"){
                    $Psychological_well_being ="checked='checked'";
                   }
                if($data=="Spiritual_well_being"){
                    $Spiritual_well_being ="checked='checked'";
                   }
                if($data=="Environment_equipment"){
                    $Environment_equipment ="checked='checked'";
                   }
                if($data=="Information_education"){
                    $Information_education ="checked='checked'";
                   }
                if($data=="tests"){
                    $tests ="checked='checked'";
                   }
                   if($data=="Treatment"){
                    $Treatment ="checked='checked'";
                   }
                if($data=="Payments"){
                    $Payments ="checked='checked'";
                   }
                if($data=="Rest_sleep"){
                    $Rest_sleep ="checked='checked'";
                   }
                   if($data=="Creative_activities"){
                    $Creative_activities ="checked='checked'";  
                   } 
                if($data=="Other_problems"){
                    $Other_problems ="checked='checked'";
                   }
        }

        
        $Today_Date = mysqli_fetch_array(mysqli_query($conn,"select now() as today"))['today'];
        $time_elapsed = date_diff(date_create($created_at), date_create($Today_Date))->h;
         
      
        echo "    <tr >     
        <th> $Employee_Name  </th>
        <th> $created_at</th> 
        <td><input type='checkbox'   id=".'Airway_'."$Info_assessment_ID  $Airway> </td>
        <td><input type='checkbox'  id=".'Breathing_'."$Info_assessment_ID  $Breathing ></td>
        <td><input type='checkbox' id=".'Ciculation_'."$Info_assessment_ID  $Ciculation ></td>
        <td><input type='checkbox' id=".'Level_of_consciousness_'."$Info_assessment_ID  $Level_of_consciousness></td>
        <td><input type='checkbox' id=".'Pain_'."$Info_assessment_ID  $Pain></td>
        <td><input type='checkbox' id=".'Fluid_intake_'."$Info_assessment_ID  $Fluid_intake></td>
        <td><input type='checkbox' id=".'Fluid_output_'."$Info_assessment_ID  $Fluid_output></td>
        <td><input type='checkbox' id=".'Fluid_balance_'."$Info_assessment_ID  $Fluid_balance></td>
        <td><input type='checkbox' id=".'Body_temperature_'."$Info_assessment_ID  $Body_temperature></td>
        <td><input type='checkbox' id=".'Food_nutrition_electrolytes_'."$Info_assessment_ID  $Food_nutrition_electrolytes></td>
        <td><input type='checkbox' id=".'Elimination_'."$Info_assessment_ID  $Elimination></td>
        <td><input type='checkbox' id=".'Haygiene_body_'."$Info_assessment_ID  $Haygiene_body></td>
        <td><input type='checkbox' id=".'Haygiene_Oral_eyes_'."$Info_assessment_ID  $Haygiene_Oral_eyes></td>
        <td><input type='checkbox' id=".'Wounds_'."$Info_assessment_ID  $Wounds></td>
        <td><input type='checkbox' id=".'Risk_of_pressure_'."$Info_assessment_ID  $Risk_of_pressure></td>
        <td><input type='checkbox' id=".'Drains_'."$Info_assessment_ID  $Drains></td>
        <td><input type='checkbox' id=".'Exercise_'."$Info_assessment_ID  $Exercise></td>
        <td><input type='checkbox' id=".'Social_well_being_'."$Info_assessment_ID  $Social_well_being></td>
        <td><input type='checkbox' id=".'Psychological_well_being_'."$Info_assessment_ID  $Psychological_well_being></td>
        <td><input type='checkbox' id=".'Spiritual_well_being_'."$Info_assessment_ID  $Spiritual_well_being></td>
        <td><input type='checkbox' id=".'Environment_equipment_'."$Info_assessment_ID  $Environment_equipment></td>
        <td><input type='checkbox' id=".'Information_education_'."$Info_assessment_ID  $Information_education></td>
        <td><input type='checkbox' id=".'tests_'."$Info_assessment_ID  $tests></td>
        <td><input type='checkbox' id=".'Treatment_'."$Info_assessment_ID  $Treatment></td>
        <td><input type='checkbox' id=".'Payments_'."$Info_assessment_ID  $Payments></td>
        <td><input type='checkbox' id=".'Rest_sleep_'."$Info_assessment_ID  $Rest_sleep></td>
        <td><input type='checkbox' id=".'Creative_activities_'."$Info_assessment_ID  $Creative_activities></td>
        <td><input type='checkbox' id=".'Other_problems_'."$Info_assessment_ID  $Other_problems></td>                            
        
        <td>";
        
        if(($time_elapsed<3)&&($employee ==$Employee_ID)){
        echo "<input type='button' name='update_info_btn' class='btn btn-primary' onclick='Update_assessment_data(\"".$Info_assessment_ID."\")' value='Update'>";
        }
        echo "</td>
    </tr>";
        
        $Airway="";
        $Breathing="";
        $Ciculation="";
        $Level_of_consciousness="";
        $Pain=""; 
        $Fluid_intake="";
        $Fluid_output="";
        $Fluid_balance="";
        $Body_temperature="";
        $Food_nutrition_electrolytes="";
        $Elimination="";
        $Haygiene_body="";
        $Haygiene_Oral_eyes="";
        $Wounds="";
        $Risk_of_pressure="";
        $Drains="";
        $Exercise="";
        $Social_well_being="";
        $Spiritual_well_being="";
        $Environment_equipment="";
        $Information_education="";
        $tests="";
        $Treatment="";
        $Payments="";
        $Rest_sleep="";
        $Creative_activities="";
        $Other_problems="";?>

<script>
      
</script>
        <?php
    }    
}else{
    echo "<tr><th colspan='28' style='color:red;'>No any information</th></tr>";
}

}

if(isset($_POST['responce_load'])){
    $Registration_ID =$_POST['Registration_ID'];
    $Admision_ID =$_POST['Admision_ID'];
    $fbp_done=="";
    $fbp_not_done=="";
    $electrolyte_done=="";
    $electrolyte_not_done=="";
    $blood_grouping_x_matching_done=="";
    $blood_grouping_x_matching_not_done=="";
    $select_receiving = mysqli_query($conn, "SELECT Receiving_note_ID,FBP,management_given,blood_grouping_x_matching,other_investigation_done,electrolyte,tbsa,Condition_of_patient,bnr.Burn_ID,type_burn,date_of_burn,bc.Classfication_ID,bnr.Classfication_of_burn ,tbsa FROM tbl_burn_unit_receiving_notes bnr,tbl_burn_type bt, tbl_burn_classfication bc WHERE bnr.Burn_ID=bt.Burn_ID AND Registration_ID='$Registration_ID' AND Admision_ID='$Admision_ID' ORDER BY Receiving_note_ID LIMIT 1") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_receiving))>0){
        while($receiving_rw = mysqli_fetch_assoc($select_receiving)){
            $Receiving_note_ID = $receiving_rw['Receiving_note_ID'];
            $type_burn = $receiving_rw['type_burn'];
            $date_of_burn = $receiving_rw['date_of_burn'];
            $Classfication_of_burn=$receiving_rw['Classfication_of_burn']; 
            $electrolyte =$receiving_rw['electrolyte'];
            $FBP = $receiving_rw['FBP'];
            
            $blood_grouping_x_matching = $receiving_rw['blood_grouping_x_matching'];
            $other_investigation_done = $receiving_rw['other_investigation_done'];
            $management_given = $receiving_rw['management_given'];
            $tbsa = $receiving_rw['tbsa'];

           
            if($FBP=="Done"){
                $fbp_done ="checked='checked'";
            }else{
                $fbp_not_done="checked='checked'";
            }
            if($electrolyte=="Done"){
                $electrolyte_done="checked='checked'";
            }else{
                $electrolyte_not_done="checked='checked'";
            }
            if($blood_grouping_x_matching=="Done"){
                $blood_grouping_x_matching_done="checked='checked'";
            }else{
                $blood_grouping_x_matching_not_done="checked='checked'";
            }
            ?>
            <style media="screen">
                #table {
                    border:none !important;
                }

                .input{
                    width:30% !important;
                }

                .label-input{
                    width: 0% !important ;
                    text-align: right !important;

                }
            </style>
            <table width="100%" id="table">
                <tr>
                    <td class="label-input">Type of burn</td>
                    <td> <input type="text" class="form-control" value="<?php echo $type_burn;?>"></td>
                    <td class="label-input"> Date of burn</td>
                    <td><input type="text"  value="<?php echo $date_of_burn; ?>" class="date" id="date_of_burn"></td>
                </tr>
                <tr>
                    <td class="label-input">Classfication of burn</td>
                    <td><?php 
                        $select_burn_classfication = mysqli_query($conn, "SELECT * FROM tbl_burn_classfication") or die(mysqli_error($conn));
                        $num=0;
                        if((mysqli_num_rows($select_burn_classfication))>0){
                            while($row = mysqli_fetch_assoc($select_burn_classfication)){
                                $Classfication_ID = $row['Classfication_ID'];
                                $Classfication_of_burn = $row['Classfication_of_burn'];

                                echo " $Classfication_of_burn
                                    <input  type='checkbox' class='$Classfication_ID' value='$Classfication_of_burn'>&nbsp;&nbsp;
                                ";
                            }
                        }
                        ?> 
                    </td> 
                    <td class="label-input">TBSA in%</td>
                    <td><input type="text" id="tbsa" disable value="<?php echo $tbsa;?>" class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="4">
                    <table width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: max-content;" >Investigation Taken</th>
                                    <th colspan="2" style="width: max-content;">Remarks</th>                                    
                                </tr>
                                <tr>
                                    <th>Done</th>
                                    <th>Not Done</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="text-align: center">FBP</th>
                                    <td style="text-align: center"><input type="radio" name="FBP" id="fbp_done" <?php echo $fbp_done;?>></td>
                                    <td style="text-align: center"><input type="radio" name="FBP" id="fbp_not_done" <?php echo $fbp_not_done;?>></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center">Electrolyte</th>
                                    <td style="text-align: center"><input type="radio" name="electrolyte" id="electrolyte_done" <?php echo $electrolyte_done;?>></td>
                                    <td style="text-align: center"><input type="radio" value="<?php echo $electrolyte_not_done?>" name="electrolyte" id="electrolyte_not_done"></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center">Blood Grouping and X-matching</th>
                                    <td style="text-align: center"><input type="radio" id="blood_grouping_x_matching_done" name="blood_grouping_x_matching" <?php echo $blood_grouping_x_matching_done;?>></td>
                                    <td style="text-align: center"><input type="radio" id="blood_grouping_x_matching_not_done" name="blood_grouping_x_matching" <?php echo $blood_grouping_x_matching_not_done;?>></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center">Others Done</th>
                                    <td  colspan="2"><textarea name="" id="other_investigation_done"  rows="3"><?php echo $other_investigation_done;?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="label-input" style="padding: 2em;">Condition of patient</td>
                    <td><textarea name="" id="Condition_of_patient" value="" rows="3" class="form-control"><?php echo $Classfication_of_burn;?></textarea></td>
                    <td class="label-input" style="padding: 2em;">Managements given</td>
                    <td><textarea name="" id="management_given"  rows="3" class="form-control"><?php echo $management_given;?></textarea></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">
            <button onclick="update_receiving_note(<?php echo $Receiving_note_ID;?>)" class="art-button-green" style="width: 10em;" type="button" name="receiving_notes_update"> Update</button>

                    </td>
                </tr>
            </table>
            <!-- <div class="row">
    <div class="form-group">
        <label for=""class="col-md-2">Type of burn</label>
        <div class="col-md-4">
            <input type="text" class="form-control" value="<?php //echo $type_burn;?>"> -->
            <!-- <select name="Burn_ID" id="Burn_ID" class="form-control">
                <option value=""></option> -->
                <?php 
                    // $select_burn_type=mysqli_query($conn, "SELECT * FROM tbl_burn_type") or die(mysqli_error($conn));
                    // $num=0;
                    // if((mysqli_num_rows($select_burn_type))>0){
                    //     while($row = mysqli_fetch_assoc($select_burn_type)){
                    //         $Burn_ID = $row['Burn_ID'];
                    //         $type_burn = $row['type_burn'];

                    //         echo "<option value='$Burn_ID'>$type_burn</option>";
                    //     }
                    // }
                ?>
                
            <!-- </select> -->
        <!-- </div>
    </div>
    <div class="form-group">
        <label for="" class="col-md-2"> 
            Date of burn
        </label>
        <div class="col-md-4">
            <input type="text" disabled value="<?php echo $date_of_burn; ?>" class="form-control" id="date_of_burn">
        </div>
    </div>                    
    </div>
    <div class="row">
        <label for="" class="col-md-2">Classfication of burn</label><div class='col-md-4'> -->
        <?php 
        // $select_burn_classfication = mysqli_query($conn, "SELECT * FROM tbl_burn_classfication") or die(mysqli_error($conn));
        // $num=0;
        // if((mysqli_num_rows($select_burn_classfication))>0){
        //     while($row = mysqli_fetch_assoc($select_burn_classfication)){
        //         $Classfication_ID = $row['Classfication_ID'];
        //         $Classfication_of_burn = $row['Classfication_of_burn'];

        //         echo " $Classfication_of_burn
        //             <input  type='checkbox' class='$Classfication_ID' value='$Classfication_of_burn'>
        //            ";
        //     }
        // }
        ?>
         </div>
        <!-- <div class="form-group">
            <label class="col-md-2" for="">TBSA in% </label>
            <div class="col-md-4">
                <input type="text" id="tbsa" disable value="<?php echo $tbsa;?>" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <label for="" class="col-md-4">Condition of patient</label>
        <div class="col-md-8">
            <textarea name="" id="Condition_of_patient" value="<?php echo $Classfication_of_burn;?>" rows="3" class="form-control"></textarea>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <table class="table" style="width: auto;">
            <thead>
                <tr>
                    <th rowspan="2" style="width: max-content;" >Investigation Taken</th>
                    <th colspan="2" style="width: max-content;">Remarks</th>                                    
                </tr>
                <tr>
                    <th>Done</th>
                    <th>Not Done</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FBP</td>
                    <td><input type="radio" name="FBP" id="fbp_done" value=<?php echo $fbp_done;?>></td>
                    <td><input type="radio" name="FBP" id="fbp_not_done" value="<?php echo $fbp_not_done;?>"></td>
                </tr>
                <tr>
                    <td>Electrolyte</td>
                    <td><input type="radio" name="electrolyte" id="electrolyte_done" value="<?php echo $electrolyte_done;?>"></td>
                    <td><input type="radio" value="<?php echo $electrolyte_not_done?>" name="electrolyte" id="electrolyte_not_done"></td>
                </tr>
                <tr>
                    <td>Blood Grouping and X-matching</td>
                    <td><input type="radio" id="blood_grouping_x_matching_done" name="blood_grouping_x_matching" value="<?php echo $blood_grouping_x_matching_done;?>"></td>
                    <td><input type="radio" id="blood_grouping_x_matching_not_done" name="blood_grouping_x_matching" value="<?php echo $blood_grouping_x_matching_not_done;?>"></td>
                </tr>
                <tr>
                    <td>Others Done</td>
                    <td colspan="2"><textarea name="" id="other_investigation_done"  rows="3"></textarea><?php echo $other_investigation_done;?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <label for="" class="col-md-4">Managements given</label>
        <div class="col-md-8">
            <textarea name="" id="management_given"  rows="3" class="form-control"><?php echo $management_given;?></textarea>
        </div>
    </div> -->
   
    <!-- <div class="row">
        <div class="col-md-offset-10 col-md-2">
            <button onclick="update_receiving_note(<?php echo $Receiving_note_ID;?>)" class="art-button-green" type="button" name="receiving_notes"> Update</button>
        </div>
    </div> -->
            <?php
        }
    }else{
        ?>


<table width="100%" id="table">
                <tr>
                    <td class="label-input">Type of burn</td>
                    <td> 
                        <select name="Burn_ID" id="Burn_ID" class="form-control">
                        <option value=""></option>
                        <?php 
                            $select_burn_type=mysqli_query($conn, "SELECT * FROM tbl_burn_type") or die(mysqli_error($conn));
                            $num=0;
                            if((mysqli_num_rows($select_burn_type))>0){
                                while($row = mysqli_fetch_assoc($select_burn_type)){
                                    $Burn_ID = $row['Burn_ID'];
                                    $type_burn = $row['type_burn'];

                                    echo "<option value='$Burn_ID'>$type_burn</option>";
                                }
                            } 
                        ?>                
                        </select>
                    </td>
                    <td class="label-input"> Date of burn</td>
                    <td><input type="text"  value="" class="date" id="date_of_burn"></td>
                </tr>
                <tr>
                    <td class="label-input">Classfication of burn</td>
                    <td><?php 
                        $select_burn_classfication = mysqli_query($conn, "SELECT * FROM tbl_burn_classfication") or die(mysqli_error($conn));
                        $num=0;
                        if((mysqli_num_rows($select_burn_classfication))>0){
                            while($row = mysqli_fetch_assoc($select_burn_classfication)){
                                $Classfication_ID = $row['Classfication_ID'];
                                $Classfication_of_burn = $row['Classfication_of_burn'];

                                echo " $Classfication_of_burn
                                    <input  type='checkbox' class='$Classfication_ID' value='$Classfication_of_burn'>
                                ";
                            }
                        }
                        ?> 
                    </td>
                    <td class="label-input">TBSA in%</td>
                    <td><input type="text" id="tbsa"  class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="4">
                    <table width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: max-content;" >Investigation Taken</th>
                                    <th colspan="2" style="width: max-content;">Remarks</th>                                    
                                </tr>
                                <tr>
                                    <th>Done</th>
                                    <th>Not Done</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center">FBP</td>
                                    <td style="text-align: center"><input type="radio" name="FBP" id="fbp_done" value=""></td>
                                    <td style="text-align: center"><input type="radio" name="FBP" id="fbp_not_done" value=""></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">Electrolyte</td>
                                    <td style="text-align: center"><input type="radio" name="electrolyte" id="electrolyte_done" value=""></td>
                                    <td style="text-align: center"><input type="radio" value="" name="electrolyte" id="electrolyte_not_done"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">Blood Grouping and X-matching</td>
                                    <td style="text-align: center"><input type="radio" id="blood_grouping_x_matching_done" name="blood_grouping_x_matching" value=""></td>
                                    <td style="text-align: center"><input type="radio" id="blood_grouping_x_matching_not_done" name="blood_grouping_x_matching" value=""></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">Others Done</td>
                                    <td colspan="2"><textarea name="" id="other_investigation_done" class="form-control" rows="3"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <!-- <td class="label-input"></td> -->
                    <td colspan="2"><label class="text-center" style="align-content: center">Condition of patient</label><textarea name="" id="Condition_of_patient" value="" rows="2" class="form-control"></textarea></td>
                    <!-- <td class="label-input"></td> -->
                    <td colspan="2"><label class="text-center" style="align-content: center">Managements given</label><textarea name="" id="management_given"  rows="2" class="form-control"></textarea></td>
                </tr>
                <tr>
                    
                    <td colspan="4" style="text-align: right">
                        <button style="width:10em; color:white;" onclick="save_receiving_note('<?php echo $Registration_ID;?>','<?php echo $Admision_ID;?>')" class="art-button-green" type="button" name="receiving_notes"> SAVE</button>

                    </td>
                </tr>
            </table>

<!-- should be removed -->
    <!-- <div class="row">
    <div class="form-group">
        <label for=""class="col-md-2">Type of burn</label>
        <div class="col-md-4">
            <select name="Burn_ID" id="Burn_ID" class="form-control">
                <option value=""></option>
                <?php 
                    // $select_burn_type=mysqli_query($conn, "SELECT * FROM tbl_burn_type") or die(mysqli_error($conn));
                    // $num=0;
                    // if((mysqli_num_rows($select_burn_type))>0){
                    //     while($row = mysqli_fetch_assoc($select_burn_type)){
                    //         $Burn_ID = $row['Burn_ID'];
                    //         $type_burn = $row['type_burn'];

                    //         echo "<option value='$Burn_ID'>$type_burn</option>";
                    //     }
                    // } -->
                ?>
                
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-md-2">
            Date of burn
        </label>
        <div class="col-md-4">
            <input type="date" class="form-control" id="date_of_burn">
        </div>
    </div>                    
    </div>
    <div class="row">
        <label for="" class="col-md-2">Classfication of burn</label><div class='col-md-4'>
        <?php 
        // $select_burn_classfication = mysqli_query($conn, "SELECT * FROM tbl_burn_classfication") or die(mysqli_error($conn));
        // $num=0;
        // if((mysqli_num_rows($select_burn_classfication))>0){
        //     while($row = mysqli_fetch_assoc($select_burn_classfication)){
        //         $Classfication_ID = $row['Classfication_ID'];
        //         $Classfication_of_burn = $row['Classfication_of_burn'];

        //         echo " $Classfication_of_burn
        //             <input  type='checkbox' class='$Classfication_ID' value='$Classfication_of_burn'>
        //            ";
        //     }
        // }
        ?> </div>
        <div class="form-group">
            <label class="col-md-2" for="">TBSA in% </label>
            <div class="col-md-4">
                <input type="text" id="tbsa" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <label for="" class="col-md-4">Condition of patient</label>
        <div class="col-md-8">
            <textarea name="" id="Condition_of_patient"  rows="3" class="form-control"></textarea>
        </div>
    </div>
    <div class="row col-md-12">
        <table class="table" width="70%">
            <thead>
                <tr>
                    <th rowspan="2" width="60%" >Investigation Taken</th>
                    <th colspan="2" width="30%">Remarks</th>                                    
                </tr>
                <tr>
                    <th>Done</th>
                    <th>Not Done</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FBP</td>
                    <td><input type="radio" name="FBP" id="fbp_done"></td>
                    <td><input type="radio" name="FBP" id="fbp_not_done"></td>
                </tr>
                <tr>
                    <td>Electrolyte</td>
                    <td><input type="radio" name="electrolyte" id="electrolyte_done"></td>
                    <td><input type="radio" name="electrolyte" id="electrolyte_not_done"></td>
                </tr>
                <tr>
                    <td>Blood Grouping and X-matching</td>
                    <td><input type="radio" id="blood_grouping_x_matching_done" name="blood_grouping_x_matching"></td>
                    <td><input type="radio" id="blood_grouping_x_matching_not_done" name="blood_grouping_x_matching"></td>
                </tr>
                <tr>
                    <td>Others Done</td>
                    <td colspan="2"><textarea name="" id="other_investigation_done"  rows="3"></textarea></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <label for="" class="col-md-4">Managements given</label>
        <div class="col-md-8">
            <textarea name="" id="management_given"  rows="3" class="form-control"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-10 col-md-2">
            <button onclick="save_receiving_note('<?php echo $Registration_ID;?>','<?php echo $Admision_ID;?>')" class="art-button-green" type="button" name="receiving_notes"> Save</button>
        </div>
    </div>-->
<?php
    }
    
    
}

if(isset($_POST['consultation_request_btn'])){
    ?>
        <div class="col-md-12">
            <div class="box box-primary" >
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>PATIENT NAME (REG#)</th>
                                <th>ORDER DATE</th>
                                <th>ORDERED BY</th>
                                <th>ORDERED TO</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $num=0;
                            
                            $select_reqeust = mysqli_query($conn, "SELECT Date_of_request,rc.Registration_ID,Request_Consultation_ID,Employee_Name,Patient_Name,Request_from FROM tbl_patient_registration pr, tbl_employee e,tbl_request_for_consultation rc WHERE Request_to='$Employee_ID' AND Request_to=e.Employee_ID AND pr.Registration_ID=rc.Registration_ID AND rc.Request_Consultation_ID NOT IN (SELECT Request_Consultation_ID FROM tbl_consultation_request_replay)") or die(mysqli_error($select_reqeust));
                            if((mysqli_num_rows($select_reqeust))>0){
                            while($rq_rw =mysqli_fetch_assoc($select_reqeust)){
                                $Registration_ID = $rq_rw['Registration_ID'];
                                $Employee_Name =$rq_rw['Employee_Name'];
                                $Patient_Name = $rq_rw['Patient_Name'];
                                $Request_from = $rq_rw['Request_from'];
                                $Date_of_request = $rq_rw['Date_of_request'];
                                $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                                $num++;
                                echo "<tr>
                                <td>$num</td>
                                <td>$Patient_Name &nbsp; ( $Registration_ID)</td>
                                <td>$Date_of_request</td>
                                <td>$Request_from</td>
                                <td>$Employee_Name</td>
                                <td>
                                <div class='btn-group'>";
                                   // <span><a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-info btn-xs'>Preview</a>&nbsp;
                                  echo"  <a class='btn btn-primary btn-sm' name='preview_request' onclick='reply_request_consultation($Request_Consultation_ID, $Registration_ID)'> PREVIEW</a>

                                   </span>
                                </div>
                                </td>
                            </tr>";
                            }
                        }else{
                            echo "<tr>
                                    <td colspan='6' ><h4 class='text-center text-danger'>No any request</h4></td>
                                </tr>";
                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
} 

    if(isset($_POST['reply_body'])){
        $Registration_ID = $_POST['Registration_ID'];
        $Request_Consultation_ID = $_POST['Request_Consultation_ID'];
        $select_replay = mysqli_query($conn, "SELECT consultation_request_replay,Employee_Name,date_of_replay FROM tbl_employee e, tbl_consultation_request_replay cr WHERE Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID' AND e.Employee_ID=cr.Employee_ID") or die(mysqli_error($conn));
            $num = 0;
            if(mysqli_num_rows($select_replay)>0 ){
                while($replay_rw = mysqli_fetch_assoc($select_replay)){

                    $consultation_request_replay = $replay_rw['consultation_request_replay'];
                    $date_of_replay = $replay_rw['date_of_replay'];
                    $Employee_Name = $replay_rw['Employee_Name'];
                    $num++;
                    echo "<tr>
                            <td><p>$date_of_replay<p></td>
                            <td><p style='border-radius:0px 0px 12px 4px; background:#e3e1df     '>$consultation_request_replay</p></td>
                            <td>$Employee_Name</td>
                            </tr>
                            ";
                }
            }else{
                echo "<tr><td colspan='2'class='text-center text-danger' >No any replay for this request</td></tr>";
            }
    }

    if(isset($_POST['previous_assessment_info'])){
        $Registration_ID = $_POST['Registration_ID'];
        ?>
        <div class="col-md-12">
            <div class="box box-primary" >
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>DATE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $num=0;
                            
                            $select_data = mysqli_query($conn, "SELECT Assessment_ID, created_at FROM tbl_nurse_assessment WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                            if((mysqli_num_rows($select_data))>0){
                                while($assessment_rw = mysqli_fetch_assoc($select_data)){
                                    $created_at = $assessment_rw['created_at'];
                                    $Assessment_ID = $assessment_rw['Assessment_ID'];
                                    $num++;
                                    echo "<tr>
                                            <td>$num</td>
                                            <td>$created_at</td>
                                            <td>
                                            <input type='button' onclick='preview_assessment_record($Assessment_ID)'  class='btn btn-primary' value='PREVIEW'>
                                            </td>

                                        </tr>";
                                }
                            }else{
                                echo "<tr>
                                    <td colspan='3' class='text-center'>No any previous record</td>
                                </tr>";
                            }

    }

    if(isset($_POST['preview_record_assessment_info'])){
        $Assessment_ID = $_POST['Assessment_ID'];
        // $Admision_ID = $_POST['Admision_ID'];
        // $Registration_ID = $_POST['Registration_ID'];
        $select_assessment = mysqli_query($conn, "SELECT * FROM tbl_nurse_assessment WHERE Assessment_ID='$Assessment_ID' ") or die(mysqli_error($conn));
        if((mysqli_num_rows($select_assessment))>0){
            while($assessm_rw = mysqli_fetch_assoc($select_assessment)){
                
                $Assessment_ID = $assessm_rw['Assessment_ID'];
               $significant_life_criss = $assessm_rw['significant_life_criss'];
               $current_health_status = $assessm_rw['current_health_status'];
               $status  = $assessm_rw['status'];
               $medication_information = $assessm_rw['medication_information'];
             
               $social_history =$assessm_rw['social_history'];
               $relatives = $assessm_rw['relatives'];
               $nursing_history = $assessm_rw['nursing_history'];
               ?>
   <div class="box box-primary">
           <div class="box-body">
           <div class="row">
                   <div class="col-md-6">

                   <label for="" >Significant of life criss</label>
                   <div >
                   <textarea name="" id="significant_life_criss" disabled rows="2" class="form-control"><?php echo $significant_life_criss; ?></textarea>
                   </div>
                   </div>
                   <div class="col-md-6">
                   <label for="" >Patient perception of current health status</label>
                   <div >
                   <textarea name="" id="current_health_status" disabled rows="2" class="form-control"><?php echo $current_health_status; ?></textarea>
                   </div>
                   </div>                   
               </div>

               <br>
               <div class="row">
                   <div class="col-md-6">
                        <label for="" >Status</label>
                        <div >
                            <textarea name="" id="status" rows="2" class="form-control" disabled value=""><?php echo $status; ?></textarea>
                        </div>
                   </div>
                   <div class="col-md-6">
                        <label for="" >Medication information</label>
                        <div >
                            <textarea name="" id="medication_information" rows="3"disabled class="form-control"><?php echo $medication_information; ?></textarea>
                        </div>

                   </div>
                     
                   
               </div> 
               <br>
               <div class="row">
                   <div class="col-md-6">
                        <label for="" >Social history</label>
                        <div >
                            <textarea name="" id="social_history" rows="2" disabled class="form-control"><?php echo $social_history; ?></textarea>
                        </div>
                   </div>
                   <div class="col-md-6">
                    <label for="" >Relatives</label>
                    <div >
                        <textarea name="" id="relatives" rows="2" disabled class="form-control"><?php echo $relatives;  ?></textarea>
                    </div>
                   </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-2" style="padding: 2em; align-content:right;">
                       <button name="btn_assessment_info" type="button" onclick="add_assessment_information('<?php echo $Registration_ID;?>','<?php echo $Assessment_ID;?>')" class="art-button-green">+ ASSESSMENT INFO</button>
                   </div>
                   
                   <div class="col-md-10">
                       <label for="">Nursing history</label>
                       <textarea name="" id="nursing_history" disabled rows="2" class="form-control"><?php echo $nursing_history; ?></textarea>
                   </div>
                   
               </div>
           </div>
        </div>
   
           <?php
            }
        }
    }
?>
<script>
$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})
</script>