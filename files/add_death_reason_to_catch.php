<?php
 include("./includes/connection.php");
    session_start();
    if(isset($_GET['dischargestatus']) || isset($_GET['normalDischarge'])){

    $dischargestatus = $_GET['dischargestatus'];
    $disease_ID=$_GET['disease_ID'];
    $Registration_ID=$_GET['Registration_ID'];
    $consultation_ID = $_GET['consultation_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    //TEST IF ARLEADY ADDED
    $sql_select_disease_result=mysqli_query($conn,"SELECT disease_ID FROM  tbl_discharge_diagnosis WHERE disease_ID='$disease_ID' AND consultation_ID='$consultation_ID' AND   Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_disease_result)>0){

    }else{
        $sql_insert_death_result=mysqli_query($conn,"INSERT INTO  tbl_discharge_diagnosis(Registration_ID,disease_ID,consultation_ID, Employee_ID) VALUES('$Registration_ID','$disease_ID', '$consultation_ID', '$Employee_ID')") or die("Dischrge diagnosis Error".mysqli_errno($conn));
    }
    $select_added_disease_result=mysqli_query($conn,"SELECT Discharge_diagnosis_ID,disease_name,disease_code FROM  tbl_discharge_diagnosis dd, tbl_disease d WHERE d.disease_ID=dd.disease_ID AND Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID'") or die("------".mysqli_error($conn));
        if(mysqli_num_rows($select_added_disease_result)>0){
            $count=1;
            while($added_reason_cache_rows=mysqli_fetch_assoc($select_added_disease_result)){
                $Discharge_diagnosis_ID=$added_reason_cache_rows['Discharge_diagnosis_ID'];
                $disease_name=$added_reason_cache_rows['disease_name'];
                $disease_code=$added_reason_cache_rows['disease_code'];
                echo "<tr>
                            <td style='50px'>$count</td>
                            <td>$disease_name</td>
                            <td>$disease_code</td>
                            <td>
                                <input type='button' value='X' onclick='remove_added_discharge_disease($Discharge_diagnosis_ID)'/>
                            </td>
                    </tr>";
                $count++;
            }
        }
    }else if(isset($_GET['dischargestatusdeath'])){
        $disease_ID=$_GET['disease_ID'];
        $Registration_ID=$_GET['Registration_ID'];
        $sql_select_disease_result=mysqli_query($conn,"SELECT disease_code FROM tbl_disease_caused_death_cache WHERE disease_code IN(SELECT disease_code FROM tbl_disease WHERE disease_ID='$disease_ID') AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_disease_result)>0){
    
        }else{
            $sql_insert_death_result=mysqli_query($conn,"INSERT INTO tbl_disease_caused_death_cache(Registration_ID,disease_name,disease_code) VALUES('$Registration_ID',(SELECT disease_name FROM tbl_disease WHERE disease_ID='$disease_ID'),(SELECT disease_code FROM tbl_disease WHERE disease_ID='$disease_ID'))") or die(mysqli_error($conn));
        }
        $select_added_disease_result=mysqli_query($conn,"SELECT disease_death_ID,disease_name,disease_code FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_added_disease_result)>0){
                $count=1;
                while($added_reason_cache_rows=mysqli_fetch_assoc($select_added_disease_result)){
                    $disease_death_ID=$added_reason_cache_rows['disease_death_ID'];
                    $disease_name=$added_reason_cache_rows['disease_name'];
                    $disease_code=$added_reason_cache_rows['disease_code'];
                    echo "<tr>
                                <td style='50px'>$count</td>
                                <td>$disease_name</td>
                                <td>$disease_code</td>
                                <td>
                                    <input type='button' value='X' onclick='remove_added_death_disease($disease_death_ID,$Registration_ID)'/>
                                </td>
                        </tr>";
                    $count++;
                }
            }
    }else if(isset($_POST['savednormalDischarge'])){
        $consultation_ID = $_POST['consultation_ID'];
        $updatedata = mysqli_query($conn, "UPDATE tbl_discharge_diagnosis SET savestatus='served'  WHERE consultation_ID='$consultation_ID'  ") or die(mysqli_error($conn));


        $select_added_disease_result=mysqli_query($conn,"SELECT Discharge_diagnosis_ID,disease_name,disease_code FROM  tbl_discharge_diagnosis dd, tbl_disease d WHERE d.disease_ID=dd.disease_ID  AND consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_added_disease_result)>0){
            
            $dischargeDiagnosis='';
            while($added_reason_cache_rows=mysqli_fetch_assoc($select_added_disease_result)){
                $Discharge_diagnosis_ID=$added_reason_cache_rows['Discharge_diagnosis_ID'];
                $disease_name=$added_reason_cache_rows['disease_name'];
                $disease_code=$added_reason_cache_rows['disease_code'];
                
                $dischargeDiagnosis.=$disease_name."(".$disease_code.")";
                
               
               
            }
        }
        echo "<textarea rows='1' id='textdischargeDiagnosis' readonly>".$dischargeDiagnosis."</textarea>";
    }else if(isset($_POST['saveddeathDischarge'])){
        $Registration_ID = $_POST['Registration_ID'];
        $consultation_ID = $_POST['consultation_ID'];
        $Employee_ID =$_SESSION['userinfo']['Employee_ID'];
        $Admision_ID = $_POST['Admision_ID'];
        
        $select_added_disease_result=mysqli_query($conn,"SELECT disease_death_ID,disease_name,disease_code FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        $dischargeDiagnosis='';
        if(mysqli_num_rows($select_added_disease_result)>0){
            $count=1;
            while($added_reason_cache_rows=mysqli_fetch_assoc($select_added_disease_result)){
                $disease_name=$added_reason_cache_rows['disease_name'];
                $disease_code=$added_reason_cache_rows['disease_code'];
                $disease_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT disease_ID FROM tbl_disease WHERE disease_name='$disease_name'"))['disease_ID'];
                //insert into discharge diagnosis 
                $sql_insert_death_result=mysqli_query($conn,"INSERT INTO  tbl_discharge_diagnosis(Registration_ID,disease_ID,consultation_ID, Employee_ID) VALUES('$Registration_ID','$disease_ID', '$consultation_ID', '$Employee_ID')") or die("Dischrge diagnosis Error".mysqli_errno($conn));
                $dischargeDiagnosis.=$disease_name."(".$disease_code.")";               
            }
        }
        echo "<textarea rows='1' id='textdischargeDiagnosis' readonly>".$dischargeDiagnosis."</textarea>";
    }

    if(isset($_POST['openDialog'])){ 
 ?>
     <table class="table">
            <tr>
                <td style="width:40%">
                    Enter Death Time
                </td>
                <td colspan="2" >
                    <input type="date"  id="death_date_time" placeholder="Enter Death Time"/>
                </td>
            </tr>
            
            <tr>
                <td style="height:230px!important;overflow: scroll">
                    <table class="table table-condensed" style="width:100%!important">
                        <tr>
                            <td>
                                <table style="width: 100%">
                                    <td>
                                        <input type="text"id="disease_name" onkeyup="search_disease_c_death(this.value)" placeholder="----Search Disease Name----" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" id="disease_code" onkeyup="search_disease_c_death(this.value)" placeholder="----Search Disease Code----" class="form-control">
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=""><b>Select Disease Caused Death</b></td>
                        </tr>
                        <tbody id="disease_suffred_table_selection">
                        <?php
                            $deceased_diseases=mysqli_query($conn,"SELECT disease_ID,disease_name,disease_code FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
				while($row=mysqli_fetch_assoc($deceased_diseases)){
					extract($row);
                                        $disease_id="{$disease_ID}";
				echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
				}
			?>
                        </tbody>
                    </table>
                </td>	
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <td colspan="4"><b>Disease Suffered From/ Leave Blank for others</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>S/No.</b></td>
                            <td><b>Disease name</b></td>
                            <td><b>Disease code</b></td>
                            <td><b>Remove</b></td>
                        </tr>
                        <tbody id="disease_suffred_table">
                            
                        </tbody>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <table class="table">
            <tr>
                <td  colspan="2">
                    Select cause of Death:
                
                
                    
                    <select id="course_of_death" style="width:50%!important">
                        <option value=""></option>
                        <?php 
                       
                            $sql_select_course_of_death_result=mysqli_query($conn,"SELECT deceased_reasons_ID,deceased_reasons FROM tbl_deceased_reasons") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_course_of_death_result)>0){
                                while($d_course_rows=mysqli_fetch_assoc($sql_select_course_of_death_result)){
                                    
                                    $deceased_reasons2=$d_course_rows['deceased_reasons'];
                                    $deceased_reasons_ID=$d_course_rows['deceased_reasons_ID'];
                                    
                                            if($deceased_reasons==$deceased_reasons2){
                                                $selected_course="selected='selected'";
                                            }else{
                                                $selected_course=" ";
                                            }
                                    echo "<option value='$deceased_reasons_ID' $selected_course>$deceased_reasons2</option>";
                                }
                            }else{
                                echo "No result found";
                            }
                           
                           
                         ?>
                    </select>
                   
                 <input type="button" value="ADD CAUSE OF DEATH" onclick="open_add_reason_dialogy()" class="art-button-green">
                      </td>   
                    </tr>
                  
               <td colspan="" >
                    Select Doctor Confirm Death:
                <?php 
                 $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                ?>
                
                    <select id="Docto_confirm_death_name" style="width:45%!important">
                        <option value=""></option>
                        <?php 
                           
                            $sql_select_doctors_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_doctors_result)>0){
                                while ($doctors_rows=mysqli_fetch_assoc($sql_select_doctors_result)){
                                    $doctor_cd_name=$doctors_rows['Employee_Name'];
                                    $doctor_cd_id=$doctors_rows['Employee_ID'];
                                    $selected="";
                                   if($Employee_ID==$doctor_cd_id)
                                   {
                                      $selected="selected='selected'";
                                   }
                                    echo "<option value='$doctor_cd_name' $selected>$doctor_cd_name</option>";
                                }
                            }
                          ?>
                    </select>
                </td>
                
        
                <td colspan="">
            Relative Name :
                
                
                    <input style="width:73%"  type="text"   name="" id="relative_name" class="" >
      
                  </td>
                
            </tr>
            
            <!--kuntacode-->
            
            <tr>
                <td colspan="">
            Relationship Type:
                
            <input style="width:50%!important"  type="text"   name="" id="relationship_type" class="" placeholder="Enter Relationship Type">
      
                  </td>
              
                <td colspan="">
           Relative Phone Number :
                
           <input onkeyup="validate_number()" maxlength="10"  x-autocompletetype="tel"   type="tel" style="width:50%" type="text"   name="" id="relative_phone_number" class="" placeholder="Enter Relative Phone Number" onkeyup="numberOnly(this)">
      
                  </td>
                
            </tr>
         
            <tr>
                <td colspan="">
              Relative Address :
              
              <input style="width:40%"  type="text"   name="relative_Address" id="relative_Address" class=""  placeholder="Enter Relative Address" >
      
                  </td>
                
                   <td colspan="">
              Death Before/After of arrive :
               
                  <select name="dead_after_before" id='dead_after_before'>
                      <option value=""></option>
                    <option  value="dead_before">Dead Before arrive to Hospital</option>
                    <option  value="dead_after">Dead After arrive to Hospital</option>
                   
                </select> 
                  </td>
                
            </tr>
              <tr>
                  <td colspan="">
                        Place of Death : <input style="width:30%" type="text"   name="" id="place_of_death" class="" placeholder="Enter Place of Death" >
      
                  </td>
			    <td>
                    Do you what to send Body to Mortuary After Certify Death?
                    <input value='yes' required="required" type='radio' name='send_notsend_to_morgue' id='send_notsend_to_morgue_yes'>
                    <b style="color:red"> YES </b>  &bsim;OR 
                    <input value='no' required="required" type='radio' name='send_notsend_to_morgue' id='send_notsend_to_morgue'>
                    <b style="color:red"> NO </b>
                    
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="text" id="Discharge_Reason_txt" hidden="hidden">
                    <input type="button" value="Certify Death" class="art-button-green pull-right" onclick="doctor_confirm_death('<?php echo $Registration_ID; ?>')">
                </td>
            </tr>
        </table>
 <?php 

} ?>
