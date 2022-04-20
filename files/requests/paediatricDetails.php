<?php
 include("../includes/connection.php");
 if(isset($_POST['CheckExistence'])){
 $regno=$_POST['regno'];
 $checkExist=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
 $getNumbers=mysqli_num_rows($checkExist);
 if($getNumbers>0){
  //echo $getNumbers;
 }else{
	 $insertQuery="INSERT INTO `tbl_paediatric`(`Registration_ID`, `Have_TT`, `Current_Hiv_Status`, `Date_Of_BCG_Vaccination`, `Date_Of_Pentavalent_Vaccination`, `Date_Of_Pnuemoccocal_Vaccination`, `Date_Of_OPC0_Vaccination`, `Date_Of_Measles_Vaccination`, `Vitamin_Six_Months`, `Vitamin_Twelve_Months`, `Vitamin_Eighteen_Months`, `Vitamin_Twenty_Four_Months`, `Vitamin_Thirty_Six_Months`, `Vitamin_Fourty_Eight_Months`, `Fifty_Nine_Months`, `Abendazole_Six_Months`, `Abendazole_Twelve_Months`, `Abendazole_Eighteen_Months`, `Abendazole_Twenty_Four_Months`, `Abendazole_Thirty_Months`, `Received_IPT`, `Vaccination_Comment`, `Partiner_Name`, `Planning_Consultation`, `Previous_Preginancies`, `Number_Of_Births`, `Aborted_Preginancies`, `Children_Died_Under_Seven`, `Number_Of_Current_Children`, `Age_Of_Last_Child`, `Contraceptive_Method`, `Recommended_Date_For_Review_Consultation`, `Other_Comments`, `General_Appearance`, `Ent`, `Neck`, `Heart`, `Lungs`, `Abdomen`, `Spine`, `Extremities`, `Genitalia`, `Skin`, `Neurological`, `Physical_Health`, `Hearing`, `Speech`, `Vision`, `Vomiting`, `Social_Behavior`, `Posture`, `Large_Movoment`, `Fine_Movement`, `Recognition`, `Comprehension`, `Interaction`, `Milestone_Comment`, `Breast_Feeding_Month1`, `Breast_Feeding_Month2`, `Breast_Feeding_Month3`, `Breast_Feeding_Month4`, `Breast_Feeding_Month5`, `Breast_Feeding_Month6`, `Formula_Month1`, `Formula_Month2`, `Formula_Month3`, `Formula_Month4`, `Formula_Month5`, `Formula_Month6`, `Formula_Breast_Month1`, `Formula_Breast_Month2`, `Formula_Breast_Month3`, `Formula_Breast_Month4`, `Formula_Breast_Month5`, `Formula_Breast_Month6`, `Prophylaxis_Cotrimoxazole_Type1_1`, `Prophylaxis_Cotrimoxazole_Type2_2`, `Prophylaxis_Cotrimoxazole_Type1_3`, `Prophylaxis_Cotrimoxazole_Type1_5`, `Prophylaxis_Cotrimoxazole_Type1_7`, `Prophylaxis_Cotrimoxazole_Type1_9`, `Prophylaxis_Cotrimoxazole_Type1_11`, `Prophylaxis_Cotrimoxazole_Type2_4`, `Prophylaxis_Cotrimoxazole_Type2_6`, `Prophylaxis_Cotrimoxazole_Type2_8`, `Prophylaxis_Cotrimoxazole_Type2_10`, `Prophylaxis_Cotrimoxazole_Type2_12`, `ARV_Therapy`, `Child_Hiv_Status_Four_Weeks`, `Child_Hiv_Status_Six_Weeks`, `Hiv_Related_Comments`, `Diahorrea_Duration`, `Dehydration_Degree`, `Stool_Blood`, `Fever`, `Vomiting2`, `Anything_Else`, `Treatment`, `Oral_Rehydration_Solution`, `Iv_Medication`, `Zinc`, `Anyother_Treatment`, `Treatment_Duration`, `Outcome`, `Paediatric_Registration_Date`) VALUES ('$regno','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')";
	 $query=mysqli_query($conn,$insertQuery);
	 if($query){
	  echo 'success';
	 
	 }else{
	 echo 'failed';
	 
	 }
 }
 
 }
 
 if(isset($_POST['view'])){
     if($_POST['view']=='view1'){
         echo '<center> 
             <table  class="hiv_table" border="0" >
                        <tr>
                            <td>
                            <table>
                                <tr>
                                    <td width="20"></td><td width="80%"><textarea id="textarea1" rows="20" cols="180">
                                        
                                    </textarea> </td><td width="20"></td>                      
                                </tr>
                            </table>
                            </td>
                            </tr>
                        <tr>
                        <td>
                            <table width="98%"  border="0" >
                                <tr>
                                    
                                    <td colspan="4" width="50%">&nbsp;</td> <td style="padding:0px;"><button class="art-button-green" id="saveView1">Save</button><a class="art-button-green"> &nbsp;&nbsp;&nbsp;allegies &nbsp;&nbsp;&nbsp;</a></td>
                                    <td><a class="art-button-green">Special Conditions</a></td><td><a class="art-button-green">Latest Vital Signs</a></td><td width="10%">&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                            </table>
                          </td>                       
                        </tr>
                    </table>
                 </center>'; 
     } elseif ($_POST['view']=='view2') {
	      $regno=$_POST['regno'];
          $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
          $result= mysqli_fetch_assoc($queryData);
            
         echo '<center> <table class="hiv_table" border="0" >
                        <tr>
                            <td width="30%"class="powercharts_td_left">Have They Had TT?</td><td>
                                <select id="">
                                    <option>'.$result['Have_TT'].'</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                    <option>Unknown</option>
                                </select></td>
                            <td <td width="30%"class="powercharts_td_left">HIV Status</td><td>
                                <select>
                                    <option>'.$result['Current_Hiv_Status'].'</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>0</option>
                                    <option>-VE</option>
                            </select></td>
                        </tr>
                        <tr>
                        <tr>
                            <td class="powercharts_td_left">Date of BCG Vaccination</td><td><input name="" type="text" value='.$result['Date_Of_BCG_Vaccination'].'></td>
                            <td <td width="30%"class="powercharts_td_left">Date Of OPC0 Vaccination</td><td><input name="" type="text" value='.$result['Date_Of_OPC0_Vaccination'].'></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Date Of Pentavalenta Vaccination</td><td><input name="" type="text" value='.$result['Date_Of_Pentavalent_Vaccination'].'></td>
                            <td <td width="30%"class="powercharts_td_left">Date Of Measles Vaccination</td><td><input name="" type="text" value='.$result['Date_Of_Measles_Vaccination'].'></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Date Of Polio Pnueomoccocal Vaccination</td><td><input name="" type="text" value='.$result['Date_Of_Pnuemoccocal_Vaccination'].'></td></td>
                            <td <td width="30%"class="powercharts_td_left" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td><td colspan="3"><hr></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Vitamin a Dose Received?</td><td colspan="3">
                            <table width="100%">
                                <tr>
                                    <td class="powercharts_td_left">6 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Six_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td class="powercharts_td_left">36 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Thirty_Six_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">12 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Twenty_Four_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td><td class="powercharts_td_left">48 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Fourty_Eight_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">18 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Eighteen_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td><td class="powercharts_td_left">59 Months</td><td>
                                    <select>
                                        <option>'.$result['Fifty_Nine_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">24 Months</td><td>
                                    <select>
                                        <option>'.$result['Vitamin_Twenty_Four_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td><td colspan="2"></td><td></td>
                                </tr>
                            </table>



                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Mabendazole/Abendazole kila 6 Months</td><td colspan="3">                            <table width="100%">
                                <tr>
                                    <td class="powercharts_td_left">6 Months</td><td>
                                    <select>
                                        <option>'.$result['Abendazole_Six_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Does Not Apply</option>
                                    </select></td><td class="powercharts_td_left">24 Months</td><td>                                    <select>
                                        <option>'.$result['Abendazole_Twenty_Four_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Does Not Apply</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">12 Months</td><td>                                    <select>
                                        <option>'.$result['Abendazole_Twelve_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Does Not Apply</option>
                                    </select></td><td class="powercharts_td_left">30 Months</td><td>                                    <select>
                                        <option>'.$result['Abendazole_Thirty_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Does Not Apply</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">18 Months</td><td>                                    <select>
                                        <option>'.$result['Abendazole_Eighteen_Months'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Does Not Apply</option>
                                    </select></td><td colspan="2"></td>
                                </tr>
                            </table>
                        <tr>
                            <td></td><td colspan="3"><hr></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Malaria Net Voucher? IPTC Received?</td><td colspan="3">
                                    <select>
                                        <option>'.$result['Received_IPT'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Other Vaccination/Disease Prevention Related Comments?</td><td colspan="3" rowspan="3"><textarea>'.$result['Vaccination_Comment'].'</textarea></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>

                        <tr style="text-align:center;margin-left:200px">
                           <td class="powercharts_td_left"></td><td style="text-align:center"><button class="art-button-green" id="saveView2" name="'.$regno.'" style="width: 200px">Save</button></td> 
                        </tr>
                    </table></center>
               ';   
         
        }elseif ($_POST['view']=='view3'){
		    $regno=$_POST['regno'];
            $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
            $result= mysqli_fetch_assoc($queryData);
            echo '<center>  <table class="hiv_table" border="0" >
                        <tr>
                            <td <td width="30%"class="powercharts_td_left">ID Number</td><td colspan="3"><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Husband name/ Partner name</td><td colspan="3"><input id="partiner" name="" type="text" value="'.$result['Partiner_Name'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Is this family planning consultation?</td><td colspan="3"><input id="consultation" name="" type="text" value="'.$result['Planning_Consultation'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How many previous pregnancies?</td><td colspan="3"><input id="pastPregnaces" name="" type="text" value="'.$result['Previous_Preginancies'].'"></td></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How many births?</td><td colspan="3"><input id="births" name="" type="text" value="'.$result['Number_Of_Births'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How many still births/aborted pregnancies?</td><td colspan="3"><input id="stillbirths" name="" type="text" value="'.$result['Aborted_Preginancies'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How many children who died within 7 days of birth?</td><td colspan="3"><input id="7daysdeaths" name="" type="text" value="'.$result['Children_Died_Under_Seven'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How many current children?</td><td colspan="3"><input id="currentChildren" name="" type="text" value="'.$result['Number_Of_Current_Children'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Age of last child</td><td colspan="3"><input id="lastChildAge" name="" type="text" value="'.$result['Age_Of_Last_Child'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Method of contraception being chosen</td><td colspan="3"><input id="contraceptionMethod" name="" type="text" value="'.$result['Contraceptive_Method'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Recommended date for review consultation</td><td colspan="3"><input id="consultationRenewal" name="" type="text" value="'.$result['Recommended_Date_For_Review_Consultation'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Other comments</td><td colspan="3"><input id="comments" name="" type="text" value="'.$result['Other_Comments'].'"></td>
                        </tr>
                        
                        <tr><td class="powercharts_td_left"></td><td style="text-align:center"><button class="art-button-green" name="'.$regno.'" id="saveView3" style="width:200px">Save</button></td></tr>
                    </table></center>'; 
        }elseif ($_POST['view']=='view4') {
		    $regno=$_POST['regno'];
            $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
            $result= mysqli_fetch_assoc($queryData);
            echo '<center> <table class="hiv_table" border="0" style="width:80%">
                        <tr>
                            <td <td width="30%"class="powercharts_td_left">General appearance</td><td colspan="3"><input id="appearance" name="" type="text" value="'.$result['General_Appearance'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Ent</td><td colspan="3"><input id="Ent" name="" type="text" value="'.$result['Ent'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Neck</td><td colspan="3"><input id="Neck" name="" type="text" value="'.$result['Neck'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Heart</td><td colspan="3"><input id="Heart" name="" type="text" value="'.$result['Heart'].'"></td></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Lungs</td><td colspan="3"><input id="Lungs" name="" type="text" value="'.$result['Lungs'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Abdomen</td><td colspan="3"><textarea id="Abdomen">"'.$result['Abdomen'].'"</textarea></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Spine</td><td colspan="3"><input id="Spine" name="" type="text" value="'.$result['Spine'].'"></td>
                        </tr>
<!--                        <tr>
                            <td class="powercharts_td_left">Spine</td><td colspan="3"><input name="" type="text" ></td>
                        </tr>-->
                        <tr>
                            <td class="powercharts_td_left">Extremities</td><td colspan="3"><input id="Extremities" name="" type="text" value="'.$result['Extremities'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Genitalia</td><td colspan="3"><input id="Genitalia" name="" type="text" value="'.$result['Genitalia'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Skin</td><td colspan="3"><input id="Skin" name="" type="text" value="'.$result['Skin'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Neurological</td><td colspan="3"><input id="Neurological" name="" type="text" value="'.$result['Neurological'].'"></td>
                        </tr>
                        <tr><td class="powercharts_td_left"></td><td><button class="art-button-green" id="saveView4" name="'.$regno.'" style="width:200px">Save</button></td></tr>
                    </table></center>'; 
    }elseif($_POST['view']=='view5'){
	    $regno=$_POST['regno'];
        $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
        $result= mysqli_fetch_assoc($queryData);
           
        echo '<center>  <table class="hiv_table" border="0" style="width:85%">
                        <tr>
                            <td <td width="30%"class="powercharts_td_left">Physical health</td><td colspan="3"><input id="Physical" name="" type="text" value="'.$result['Physical_Health'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Hearing</td><td colspan="3"><input id="Hearing" name="" type="text" value="'.$result['Hearing'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Speech</td><td colspan="3"><input id="Speech" name="" type="text" value="'.$result['Speech'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Vision</td><td colspan="3"><input id="Vision" name="" type="text" value="'.$result['Vision'].'"></td></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Vomiting</td><td colspan="3"><input id="Vomiting" name="" type="text" value="'.$result['Vomiting'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Social behaviour</td><td colspan="3"><textarea id="Social">'.$result['Social_Behavior'].'</textarea></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Posture</td><td colspan="3"><input id="Posture" name="" type="text" value='.$result['Posture'].'></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Large movements</td><td colspan="3"><input id="Largemovements" name="" type="text" value="'.$result['Large_Movoment'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Fine movements</td><td colspan="3"><input id="Finemovements" name="" type="text" value="'.$result['Fine_Movement'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Recognition</td><td colspan="3"><input id="Recognition" name="" type="text" value="'.$result['Recognition'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Comprehension</td><td colspan="3"><input id="Comprehension" name="" type="text" value="'.$result['Comprehension'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Interaction</td><td colspan="3"><input id="Interaction" name="" type="text" value="'.$result['Interaction'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" rowspan="2">Other milestone comments</td><td colspan="3" rowspan="2"><textarea id="Othercomments">'.$result['Milestone_Comment'].'</textarea></td>
                        </tr>
                      
                    </table>
                    <div><button class="art-button-green" id="saveView5" name="'.$regno.'" style="width:200px">Save</button></div>
                </center>';
    }elseif ($_POST['view']=='view6') {
	    $regno=$_POST['regno'];
        $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
        $result= mysqli_fetch_assoc($queryData);
        echo '<center>
                 <table width="80%" class="hiv_table" border="0" >
                        <tr>
                        <td colspan="4">

                            <table width="100%" border="0">
                                <tr>
                                <th width="10%">&nbsp;</th><th>Breast Feeding Only:</th><th>Formula Only</th><th>Formula And Breast Feeding</th>
                            </tr>
                     <tr>
                                <td class="powercharts_td_left">Month 1</td><td>
                                    <select id="Feeding_Month1">
                                        <option>'.$result['Breast_Feeding_Month1'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Formula_Month1">
                                        <option>'.$result['Formula_Month1'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month1">
                                        <option>'.$result['Formula_Breast_Month1'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                       </select>
                                    </td>
                            </tr>
                            <tr>
                                <td class="powercharts_td_left">Month 2</td><td>
                                    <select id="Feeding_Month2">
                                        <option>'.$result['Breast_Feeding_Month2'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Formula_Month2">
                                        <option>'.$result['Formula_Month2'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month2">
                                            <option>'.$result['Formula_Breast_Month2'].'</option>
                                            <option>Yes</option>
                                            <option>No</option>
                                            <option>Unknown</option>
                                        </select>
                                    </td>
                            </tr>
                            <tr>
                                <td class="powercharts_td_left">Month 3</td><td>
                                    <select id="Feeding_Month3">
                                        <option>'.$result['Breast_Feeding_Month3'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                    <select id="Formula_Month3">
                                        <option>'.$result['Formula_Month3'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month3">
                                        <option>'.$result['Formula_Breast_Month3'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td class="powercharts_td_left">Month 4</td><td>
                                    <select id="Feeding_Month4">
                                        <option>'.$result['Breast_Feeding_Month4'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Formula_Month4">
                                        <option>'.$result['Formula_Month4'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month4">
                                        <option>'.$result['Formula_Breast_Month4'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td class="powercharts_td_left">Month 5</td><td>
                                    <select id="Feeding_Month5">
                                        <option>'.$result['Breast_Feeding_Month5'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Formula_Month5">
                                        <option>'.$result['Formula_Month5'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month5">
                                            <option>'.$result['Formula_Breast_Month5'].'</option>
                                            <option>Yes</option>
                                            <option>No</option>
                                            <option>Unknown</option>
                                        </select>
                                    </td>
                            <tr>
                                <td class="powercharts_td_left">Month 6</td><td>
                                    <select id="Feeding_Month6">
                                        <option>'.$result['Breast_Feeding_Month6'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Formula_Month6">
                                        <option>'.$result['Formula_Month6'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                                    <td>
                                        <select id="Breast_Month6">
                                        <option>'.$result['Formula_Breast_Month6'].'</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Unknown</option>
                                    </select></td>
                            </tr>
                            </table>
                            </td>
                        </tr>

                        <tr>
                        <tr>
                            <td width="100%" colspan="4"><hr></td>
                        </tr>
                            <td>
                            <table width="100%">
                            <tr>
                                <td class="powercharts_td_left" width="25%">How many current children?</td><td colspan="3" rowspan="4"> <textarea rows="6" cols="50">
                                                                                                                                        </textarea></td>
                                </tr><tr>
                                <td class="powercharts_td_left" width="25%">&nbsp;</td>
                                </tr><tr>
                                <td class="powercharts_td_left" width="25%">&nbsp;</td>
                          </tr>
                             <tr>
                                <td class="powercharts_td_left" width="25%">&nbsp;</td>
                        </tr>
                    </table>
                     </tr>
                    </table>
                 <div><button class="art-button-green" id="saveView6"  name="'.$regno.'" style="width:200px">Save</button></div>
             </center>';
       }elseif ($_POST['view']=='view7') {
	    $regno=$_POST['regno'];
        $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
        $result= mysqli_fetch_assoc($queryData);
       
         echo '<center>
           <table class="hiv_table" border="0" >
                        <tr>
                            <td <td width="30%"class="powercharts_td_left">Prophylaxis Dawa ya Cotrimoxazole 1</td><td><input id="Cotrimoxazole1_1" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_1'].'"></td>
                            <td class="powercharts_td_left">Prophylaxis Dawa ya Cotrimoxazole 2</td><td><input id="Cotrimoxazole2_2" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_2'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">3</td><td><input id="Cotrimoxazole1_3" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_3'].'"></td>
                            <td class="powercharts_td_left">4</td><td><input id="Cotrimoxazole2_4" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_4'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">5</td><td><input id="Cotrimoxazole1_5" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_5'].'"></td>
                            <td class="powercharts_td_left">6</td><td><input id="Cotrimoxazole2_6" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_6'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">7</td><td><input id="Cotrimoxazole1_7" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_7'].'"></td>
                            <td class="powercharts_td_left">8</td><td><input id="Cotrimoxazole2_8" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_8'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">9</td><td><input id="Cotrimoxazole1_9" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_9'].'"></td>
                            <td class="powercharts_td_left">10</td><td><input id="Cotrimoxazole2_10" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_10'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">11</td><td><input id="Cotrimoxazole1_11" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type1_11'].'"></td>
                            <td class="powercharts_td_left">12</td><td colspan="3"><input id="Cotrimoxazole2_12" name="" type="text" value="'.$result['Prophylaxis_Cotrimoxazole_Type2_12'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">ARV Therapy Prescribed</td><td><input id="ARVTherapy" name="" type="text" value="'.$result['ARV_Therapy'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">HIV Positive Chile, HIV Status at 4 Weeks From Birth</td><td><input id="HIVStatus" name="" type="text" value="'.$result['Child_Hiv_Status_Four_Weeks'].'"></td>
                            <td class="powercharts_td_left">HIV Positive Chile, HIV Status at 6 Weeks From Birth</td><td><input id="HIVPositive" name="" type="text" value="'.$result['Child_Hiv_Status_Six_Weeks'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Other HIV or ARV Related Comments</td><td colspan="3" rowspan="7"><textarea id="ARVComments">"'.$result['Hiv_Related_Comments'].'"</textarea></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                    </table>
               <div><button class="art-button-green" id="saveView7" name="'.$regno.'" style="width:200px">Save</button></div>
           
                </center>';  
    }elseif ($_POST['view']=='view8') {
	    $regno=$_POST['regno'];
        $queryData=mysqli_query($conn,"SELECT * FROM  tbl_paediatric WHERE Registration_ID='$regno'");
        $result= mysqli_fetch_assoc($queryData);
           
        echo ' <center><table class="hiv_table" border="0" style="width:80%" >
                        <tr>
                            <td <td width="30%" class="powercharts_td_left">How long have they have diahorrea?</td><td colspan="3"><input id="diahorrea" name="" type="text" value="'.$result['Diahorrea_Duration'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Degree of dehydration</td><td colspan="3"><input id="dehydration" name="" type="text" value="'.$result['Dehydration_Degree'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Blood in the stool?</td><td colspan="3"><input id="Bloodinstool" name="" type="text" value="'.$result['Stool_Blood'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Fever?</td><td colspan="3"><input id="Fever" name="" type="text" value="'.$result['Fever'].'"></td></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Vomitting?</td><td colspan="3"><input id="Vomitting" name="" type="text" value="'.$result['Vomiting2'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Anything else?</td><td colspan="3"><textarea id="Anythingelse">'.$result['Anything_Else'].'</textarea></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Treatment</td><td colspan="3"><input id="Treatment" name="" type="text" value="'.$result['Treatment'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Oral rehydration solution</td><td colspan="3"><input id="Oralsolution" name="" type="text" value="'.$result['Oral_Rehydration_Solution'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">IV medicaion, how much?</td><td colspan="3"><input id="IVmedicaion" name="" type="text" value="'.$result['Iv_Medication'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Zinc?</td><td colspan="3"><input id="Zinc" name="" type="text" value="'.$result['Zinc'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Any other treatment?</td><td colspan="3"><input id="othertreatment" name="" type="text" value="'.$result['Anyother_Treatment'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Length of stay for treatment</td><td colspan="3"><input id="treatmentLength" name="" type="text" value="'.$result['Treatment_Duration'].'"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Outcome</td><td colspan="3"><textarea id="Outcome">'.$result['Outcome'].'</textarea></td>
                        </tr>
                    </table>
                <div><button class="art-button-green" id="saveView8" name="'.$regno.'" style="width:200px">Save</button></div>
            </center>';
    }
     
     
     
 }
 
 if(isset($_POST['action'])){
     if($_POST['action']=='Allegies'){
        // echo 'tumefika'; 
    }elseif ($_POST['action']=='Vaccination'){
    $regno=$_POST['regno'];	
    $HadTT=  mysqli_real_escape_string($conn,$_POST['HadTT']);
    $BCGVaccination=  mysqli_real_escape_string($conn,$_POST['BCGVaccination']);
    $PentavalentaVaccination= mysqli_real_escape_string($conn,$_POST['PentavalentaVaccination']);
    $PnueomoccocalVaccination= mysqli_real_escape_string($conn,$_POST['PnueomoccocalVaccination']);
    $DoseReceived6Months= mysqli_real_escape_string($conn,$_POST['DoseReceived6Months']);
    $DoseReceived12Months= mysqli_real_escape_string($conn,$_POST['DoseReceived12Months']);
    $DoseReceived18Months= mysqli_real_escape_string($conn,$_POST['DoseReceived18Months']);
    $DoseReceived24Months= mysqli_real_escape_string($conn,$_POST['DoseReceived24Months']);
    $DoseReceived36Months= mysqli_real_escape_string($conn,$_POST['DoseReceived36Months']);
    $DoseReceived48Months= mysqli_real_escape_string($conn,$_POST['DoseReceived48Months']);
    $DoseReceived59Months= mysqli_real_escape_string($conn,$_POST['DoseReceived59Months']);
    $Mabendazole6Months=  mysqli_real_escape_string($conn,$_POST['Mabendazole6Months']);
    $Mabendazole12Months= mysqli_real_escape_string($conn,$_POST['Mabendazole12Months']);
    $Mabendazole18Months= mysqli_real_escape_string($conn,$_POST['Mabendazole18Months']);
    $Mabendazole24Months= mysqli_real_escape_string($conn,$_POST['Mabendazole24Months']);
    $Mabendazole30Months= mysqli_real_escape_string($conn,$_POST['Mabendazole30Months']);
    $IPTCReceived=  mysqli_real_escape_string($conn,$_POST['IPTCReceived']);
    $OtherVaccination=  mysqli_real_escape_string($conn,$_POST['OtherVaccination']);
    $HIVStatus=  mysqli_real_escape_string($conn,$_POST['HIVStatus']);
    $OPC0VaccinationDate=  mysqli_real_escape_string($conn,$_POST['OPC0VaccinationDate']);
    $MeaslesVaccinationDate=  mysqli_real_escape_string($conn,$_POST['MeaslesVaccinationDate']);
     $query="UPDATE tbl_paediatric SET Have_TT='$HadTT',Current_Hiv_Status='$HIVStatus',Date_Of_BCG_Vaccination='$BCGVaccination',Date_Of_Pentavalent_Vaccination='$PentavalentaVaccination',Date_Of_Pnuemoccocal_Vaccination='$PnueomoccocalVaccination',Date_Of_OPC0_Vaccination='$OPC0VaccinationDate',Date_Of_Measles_Vaccination='$MeaslesVaccinationDate',Vitamin_Six_Months='$DoseReceived6Months',
        Vitamin_Twelve_Months='$DoseReceived12Months',Vitamin_Eighteen_Months='$DoseReceived18Months',Vitamin_Twenty_Four_Months='$DoseReceived24Months',Vitamin_Thirty_Six_Months='$DoseReceived36Months',Vitamin_Fourty_Eight_Months='$DoseReceived48Months',Fifty_Nine_Months='$DoseReceived59Months',Abendazole_Six_Months='$Mabendazole6Months',Abendazole_Twelve_Months='$Mabendazole12Months',
        Abendazole_Eighteen_Months='$Mabendazole18Months',Abendazole_Twenty_Four_Months='$Mabendazole24Months',Abendazole_Thirty_Months='$Mabendazole30Months',Received_IPT='$IPTCReceived',Vaccination_Comment='$OtherVaccination' WHERE Registration_ID='$regno'";
        $runQuery=  mysqli_query($conn,$query);
        if($runQuery){
            echo '<script>alert("Successfully saved!");</script>';
        } else {
            echo '<script>alert("Sorry an error has occured,data saving failured!");</script>';
        }
    }elseif ($_POST['action']=='Growth'){
		$regno=$_POST['regno'];
        $partiner=  mysqli_real_escape_string($conn,$_POST['partiner']);
        $consultation= mysqli_real_escape_string($conn,$_POST['consultation']);
        $pastPregnaces= mysqli_real_escape_string($conn,$_POST['pastPregnaces']);
        $births=  mysqli_real_escape_string($conn,$_POST['births']);
        $stillbirths=  mysqli_real_escape_string($conn,$_POST['stillbirths']);
        $sevendaysdeaths=  mysqli_real_escape_string($conn,$_POST['sevendaysdeaths']);
        $currentChildren=  mysqli_real_escape_string($conn,$_POST['currentChildren']);
        $lastChildAge=  mysqli_real_escape_string($conn,$_POST['lastChildAge']);
        $contraceptionMethod= mysqli_real_escape_string($conn,$_POST['contraceptionMethod']);
        $consultationRenewal= mysqli_real_escape_string($conn,$_POST['consultationRenewal']);
        $comments= mysqli_real_escape_string($conn,$_POST['comments']);
        $query="UPDATE tbl_paediatric SET Partiner_Name='$partiner',Planning_Consultation='$consultation',Previous_Preginancies='$pastPregnaces',Number_Of_Births='$births',Aborted_Preginancies='$stillbirths',Children_Died_Under_Seven='$sevendaysdeaths',Number_Of_Current_Children='$currentChildren',Age_Of_Last_Child='$lastChildAge',Contraceptive_Method='$contraceptionMethod',Recommended_Date_For_Review_Consultation='$consultationRenewal',Other_Comments='$comments' WHERE Registration_ID='$regno'";
        $runQuery=  mysqli_query($conn,$query);
        if($runQuery){
             echo '<script>alert("Successfully saved!")</script>';
        } else {
            echo '<script>alert("Sorry an error has occured,data saving failured!")</script>';
        }
        
    }elseif ($_POST['action']=='Physical'){
        $regno=$_POST['regno'];
        $appearance=  mysqli_real_escape_string($conn,$_POST['appearance']);
        $Ent=  mysqli_real_escape_string($conn,$_POST['Ent']);
        $Neck= mysqli_real_escape_string($conn,$_POST['Neck']);
        $Heart= mysqli_real_escape_string($conn,$_POST['Heart']);
        $Lungs= mysqli_real_escape_string($conn,$_POST['Lungs']);
        $Abdomen=  mysqli_real_escape_string($conn,$_POST['Abdomen']);
        $Spine=  mysqli_real_escape_string($conn,$_POST['Spine']);
        $Extremities=  mysqli_real_escape_string($conn,$_POST['Extremities']);
        $Genitalia=  mysqli_real_escape_string($conn,$_POST['Genitalia']);
        $Skin=  mysqli_real_escape_string($conn,$_POST['Skin']);
        $Neurological=  mysqli_real_escape_string($conn,$_POST['Neurological']);
        $query="UPDATE tbl_paediatric SET General_Appearance='$appearance',Ent='$Ent',Neck='$Neck',Heart='$Heart',Lungs='$Lungs',Abdomen='$Abdomen',Spine='$Spine',Extremities='$Extremities',Genitalia='$Genitalia',Skin='$Skin',Neurological='$Neurological' WHERE Registration_ID='$regno'";
        $runQuery=  mysqli_query($conn,$query);
        if($runQuery){
            echo '<script>alert("Successfully saved!")</script>';
        } else {
            echo '<script>alert("Sorry an error has occured,data saving failured!")</script>';
        }
        
    }elseif ($_POST['action']=='Development'){
	$regno=$_POST['regno'];
    $Physical= mysqli_real_escape_string($conn,$_POST['Physical']);
    $Hearing=  mysqli_real_escape_string($conn,$_POST['Hearing']);
    $Speech=  mysqli_real_escape_string($conn,$_POST['Speech']);
    $Vision=  mysqli_real_escape_string($conn,$_POST['Vision']);
    $Vomiting=  mysqli_real_escape_string($conn,$_POST['Vomiting']);
    $Social=  mysqli_real_escape_string($conn,$_POST['Social']);
    $Posture=  mysqli_real_escape_string($conn,$_POST['Posture']);
    $Largemovements=  mysqli_real_escape_string($conn,$_POST['Largemovements']);
    $Finemovements=  mysqli_real_escape_string($conn,$_POST['Finemovements']);
    $Recognition=  mysqli_real_escape_string($conn,$_POST['Recognition']);
    $Comprehension=  mysqli_real_escape_string($conn,$_POST['Comprehension']);
    $Interaction=  mysqli_real_escape_string($conn,$_POST['Interaction']);
    $Othercomments=  mysqli_real_escape_string($conn,$_POST['Othercomments']);
    $query="UPDATE tbl_paediatric SET Physical_Health='$Physical',Hearing='$Hearing',Speech='$Speech',Vision='$Vision',Vomiting='$Vomiting',Social_Behavior='$Social',Posture='$Posture',Large_Movoment='$Largemovements',Fine_Movement='$Finemovements',Recognition='$Recognition',Comprehension='$Comprehension',Interaction='$Interaction',Milestone_Comment='$Othercomments' WHERE Registration_ID='$regno'";
    $runQuery=  mysqli_query($conn,$query);
        if($runQuery){
            echo '<script>alert("Successfully saved!")</script>';
        } else {
             echo '<script>alert("Sorry an error has occured,data saving failured!")</script>';
        }
    }elseif ($_POST['action']=='Nutriation'){
	    $regno=$_POST['regno'];
        $Feeding_Month1= mysqli_real_escape_string($conn,$_POST['Feeding_Month1']);
        $Feeding_Month2= mysqli_real_escape_string($conn,$_POST['Feeding_Month2']);
        $Feeding_Month3= mysqli_real_escape_string($conn,$_POST['Feeding_Month3']);
        $Feeding_Month4= mysqli_real_escape_string($conn,$_POST['Feeding_Month4']);
        $Feeding_Month5= mysqli_real_escape_string($conn,$_POST['Feeding_Month5']);
        $Feeding_Month6= mysqli_real_escape_string($conn,$_POST['Feeding_Month6']);
        $Formula_Month1= mysqli_real_escape_string($conn,$_POST['Formula_Month1']);
        $Formula_Month2= mysqli_real_escape_string($conn,$_POST['Formula_Month2']);
        $Formula_Month3= mysqli_real_escape_string($conn,$_POST['Formula_Month3']);
        $Formula_Month4= mysqli_real_escape_string($conn,$_POST['Formula_Month4']);
        $Formula_Month5= mysqli_real_escape_string($conn,$_POST['Formula_Month5']);
        $Formula_Month6= mysqli_real_escape_string($conn,$_POST['Formula_Month6']);
        $Breast_Month1=  mysqli_real_escape_string($conn,$_POST['Breast_Month1']);
        $Breast_Month2=  mysqli_real_escape_string($conn,$_POST['Breast_Month2']);
        $Breast_Month3=  mysqli_real_escape_string($conn,$_POST['Breast_Month3']);
        $Breast_Month4=  mysqli_real_escape_string($conn,$_POST['Breast_Month4']);
        $Breast_Month5=  mysqli_real_escape_string($conn,$_POST['Breast_Month5']);
        $Breast_Month6=  mysqli_real_escape_string($conn,$_POST['Breast_Month6']);
        $query="UPDATE tbl_paediatric SET Breast_Feeding_Month1='$Feeding_Month1',Breast_Feeding_Month2='$Feeding_Month2',Breast_Feeding_Month3='$Feeding_Month3',Breast_Feeding_Month4='$Feeding_Month4',Breast_Feeding_Month5='$Feeding_Month5',Breast_Feeding_Month6='$Feeding_Month6',Formula_Month1='$Formula_Month1',Formula_Month2='$Formula_Month2',Formula_Month3='$Formula_Month3',Formula_Month4='$Formula_Month4',Formula_Month5='$Formula_Month5',Formula_Month6='$Formula_Month6',Formula_Breast_Month1='$Breast_Month1',Formula_Breast_Month2='$Breast_Month2',Formula_Breast_Month3='$Breast_Month3',Formula_Breast_Month4='$Breast_Month4',Formula_Breast_Month5='$Breast_Month5',Formula_Breast_Month6='$Breast_Month6' WHERE Registration_ID='$regno'";
        $runQuery=  mysqli_query($conn,$query);
       if($runQuery){
            echo '<script>alert("Successfully saved!");</script>';
        } else {
            echo '<script>alert("Sorry an error has occured,data saving failured!");</script>';
        } 
 
    }elseif ($_POST['action']=='Children') {
    $regno=$_POST['regno'];
    $Cotrimoxazole1_1=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_1']);    
    $Cotrimoxazole1_3=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_3']);
    $Cotrimoxazole1_5=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_5']);
    $Cotrimoxazole1_7=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_7']);
    $Cotrimoxazole1_9=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_9']);
    $Cotrimoxazole1_11=  mysqli_real_escape_string($conn,$_POST['Cotrimoxazole1_11']);
    $ARVTherapy= mysqli_real_escape_string($conn,$_POST['ARVTherapy']);
    $HIVStatus= mysqli_real_escape_string($conn,$_POST['HIVStatus']);
    $ARVComments= mysqli_real_escape_string($conn,$_POST['ARVComments']);
    $Cotrimoxazole2_2= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_2']);
    $Cotrimoxazole2_4= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_4']);
    $Cotrimoxazole2_6= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_6']);
    $Cotrimoxazole2_8= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_8']);
    $Cotrimoxazole2_10= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_10']);
    $Cotrimoxazole2_12= mysqli_real_escape_string($conn,$_POST['Cotrimoxazole2_12']);
    $HIVPositive= mysqli_real_escape_string($conn,$_POST['HIVPositive']);
    $query="UPDATE tbl_paediatric SET Prophylaxis_Cotrimoxazole_Type1_1='$Cotrimoxazole1_1',Prophylaxis_Cotrimoxazole_Type2_2='$Cotrimoxazole2_2',Prophylaxis_Cotrimoxazole_Type1_3='$Cotrimoxazole1_3',Prophylaxis_Cotrimoxazole_Type1_5='$Cotrimoxazole1_5',Prophylaxis_Cotrimoxazole_Type1_7='$Cotrimoxazole1_7',Prophylaxis_Cotrimoxazole_Type1_9='$Cotrimoxazole1_9',Prophylaxis_Cotrimoxazole_Type1_11='$Cotrimoxazole1_11',Prophylaxis_Cotrimoxazole_Type2_4='$Cotrimoxazole2_4',Prophylaxis_Cotrimoxazole_Type2_6='$Cotrimoxazole2_6',Prophylaxis_Cotrimoxazole_Type2_8='$Cotrimoxazole2_8',Prophylaxis_Cotrimoxazole_Type2_10='$Cotrimoxazole2_10',Prophylaxis_Cotrimoxazole_Type2_12='$Cotrimoxazole2_12',ARV_Therapy='$ARVTherapy',Child_Hiv_Status_Four_Weeks='$HIVStatus',	Child_Hiv_Status_Six_Weeks='$HIVPositive',Hiv_Related_Comments='$ARVComments' WHERE Registration_ID='$regno'";
    $runQuery=  mysqli_query($conn,$query);
    if($runQuery){
           echo '<script>alert("Successfully saved!")</script>';
        } else {
            echo '<script>alert("Sorry an error has occured,data saving failured!")</script>';
        } 
    }elseif ($_POST['action']=='Diahorrea') {
	    $regno=$_POST['regno'];
        $diahorrea=  mysqli_real_escape_string($conn,$_POST['diahorrea']);
        $dehydration=  mysqli_real_escape_string($conn,$_POST['dehydration']);
        $Bloodinstool=  mysqli_real_escape_string($conn,$_POST['Bloodinstool']);
        $Fever=  mysqli_real_escape_string($conn,$_POST['Fever']);
        $Vomitting=  mysqli_real_escape_string($conn,$_POST['Vomitting']);
        $Anythingelse=  mysqli_real_escape_string($conn,$_POST['Anythingelse']);
        $Vomitting=  mysqli_real_escape_string($conn,$_POST['Vomitting']);
        $Treatment=  mysqli_real_escape_string($conn,$_POST['Treatment']);
        $Oralsolution=  mysqli_real_escape_string($conn,$_POST['Oralsolution']);
        $IVmedicaion=  mysqli_real_escape_string($conn,$_POST['IVmedicaion']);
        $Zinc=  mysqli_real_escape_string($conn,$_POST['Zinc']);
        $othertreatment=  mysqli_real_escape_string($conn,$_POST['othertreatment']);
        $treatmentLength=  mysqli_real_escape_string($conn,$_POST['treatmentLength']);
        $Outcome=  mysqli_real_escape_string($conn,$_POST['Outcome']);
        $query="UPDATE tbl_paediatric SET Diahorrea_Duration='$diahorrea',Dehydration_Degree='$dehydration',Stool_Blood='$Bloodinstool',Fever='$Fever',Vomiting2='$Vomitting',	Anything_Else='$Anythingelse',Treatment='$Treatment',Oral_Rehydration_Solution='$Oralsolution',Iv_Medication='$IVmedicaion',Zinc='$Zinc',Anyother_Treatment='$othertreatment',Treatment_Duration='$treatmentLength',Outcome='$Outcome' WHERE Registration_ID='$regno'";
        $runQuery=  mysqli_query($conn,$query);
        if($runQuery){
            echo '<script>alert("Successfully saved!")</script>';
        } else {
             echo '<script>alert("Sorry an error has occured,data saving failured!")</script>';
        }    
    }
    
    
 }
?>


<script>
    $('#saveView1').click(function(){
       var textarea1=$('#textarea1').val();
       $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'action=Allegies&textarea1='+textarea1,
        cache:false,
        success:function(html){
          alert("Data successfully saved!");
            //$('#historyResults1').html(html);
        }
      });
    });
    
    $('#saveView2').click(function(){
	    var regno=$(this).attr('name');
        var HadTT=$('#HadTT').val();
        var BCGVaccination=$('#BCGVaccination').val();
        var PentavalentaVaccination=$('#PentavalentaVaccination').val();
        var PnueomoccocalVaccination=$('#PnueomoccocalVaccination').val();
        var DoseReceived6Months =$('#DoseReceived6Months').val();
        var DoseReceived12Months=$('#DoseReceived12Months').val();
        var DoseReceived18Months=$('#DoseReceived18Months').val();
        var DoseReceived24Months=$('#DoseReceived24Months').val();
        var DoseReceived36Months=$('#DoseReceived36Months').val();
        var DoseReceived48Months=$('#DoseReceived48Months').val();
        var DoseReceived59Months=$('#DoseReceived59Months').val();
        var Mabendazole6Months =$('#Mabendazole6Months').val();
        var Mabendazole12Months =$('#Mabendazole12Months').val();
        var Mabendazole18Months =$('#Mabendazole18Months').val();
        var Mabendazole24Months =$('#Mabendazole24Months').val();
        var Mabendazole30Months =$('#Mabendazole30Months').val();
        var IPTCReceived=$('#IPTCReceived').val();
        var OtherVaccination=$('#OtherVaccination').val();
        var HIVStatus=$('#HIVStatus').val();
        var OPC0VaccinationDate=$('#OPC0VaccinationDate').val();
        var MeaslesVaccinationDate=$('#MeaslesVaccinationDate').val();
    $.ajax({
    type:'POST', 
    url:'requests/paediatricDetails.php',
    data:'action=Vaccination&HadTT='+HadTT+'&BCGVaccination='+BCGVaccination+'&PentavalentaVaccination='+PentavalentaVaccination+'&PnueomoccocalVaccination='+PnueomoccocalVaccination+'&DoseReceived6Months='+DoseReceived6Months+'&DoseReceived12Months='+DoseReceived12Months+'&DoseReceived18Months='+DoseReceived18Months+'&DoseReceived24Months='+DoseReceived24Months+'&DoseReceived36Months='+DoseReceived36Months+'&DoseReceived48Months='+DoseReceived48Months+'&DoseReceived59Months='+DoseReceived59Months+'&Mabendazole6Months='+Mabendazole6Months+'&Mabendazole12Months='+Mabendazole12Months+'&Mabendazole18Months='+Mabendazole18Months+'&Mabendazole24Months='+Mabendazole24Months+'&Mabendazole30Months='+Mabendazole30Months+'&IPTCReceived='+IPTCReceived+'&OtherVaccination='+OtherVaccination+'&HIVStatus='+HIVStatus+'&OPC0VaccinationDate='+OPC0VaccinationDate+'&MeaslesVaccinationDate='+MeaslesVaccinationDate+'&regno='+regno,
    cache:false,
    success:function(html){
        alert("Data successfully saved!");
        //$('#historyResults1').html(html);
    }
    });
    });
    
    
     $('#saveView3').click(function(){
	   var regno=$(this).attr('name');
        var partiner=$('#partiner').val();
        var consultation=$('#consultation').val();
        var pastPregnaces=$('#pastPregnaces').val();
        var births=$('#births').val();
        var stillbirths=$('#stillbirths').val();
        var sevendaysdeaths=$('#7daysdeaths').val();
        var currentChildren=$('#currentChildren').val();
        var lastChildAge=$('#lastChildAge').val();
        var contraceptionMethod=$('#contraceptionMethod').val();
        var consultationRenewal=$('#consultationRenewal').val();
        var comments=$('#comments').val();
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'action=Growth&partiner='+partiner+'&consultation='+consultation+'&pastPregnaces='+pastPregnaces+'&births='+births+'&stillbirths='+stillbirths+'&sevendaysdeaths='+sevendaysdeaths+'&currentChildren='+currentChildren+'&lastChildAge='+lastChildAge+'&contraceptionMethod='+contraceptionMethod+'&consultationRenewal='+consultationRenewal+'&comments='+comments+'&regno='+regno,
        cache:false,
        success:function(html){
            alert("Data successfully saved!");
            //$('#historyResults1').html(html);
        }
        });
       });
       
       
        $('#saveView4').click(function(){
		var regno=$(this).attr('name');
		alert(regno);
		exit();
         var appearance=$('#appearance').val();
         var Ent=$('#Ent').val();
         var Neck=$('#Neck').val();
         var Heart=$('#Heart').val();
         var Lungs=$('#Lungs').val();
         var Abdomen=$('#Abdomen').val();
         var Spine=$('#Spine').val();
         var Extremities=$('#Extremities').val();
         var Genitalia=$('#Genitalia').val();
         var Skin=$('#Skin').val();
         var Neurological=$('#Neurological').val();
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'action=Physical&appearance='+appearance+'&Ent='+Ent+'&Neck='+Neck+'&Heart='+Heart+'&Lungs='+Lungs+'&Abdomen='+Abdomen+'&Spine='+Spine+'&Extremities='+Extremities+'&Genitalia='+Genitalia+'&Skin='+Skin+'&Neurological='+Neurological+'&regno='+regno,
        cache:false,
        success:function(html){
          alert("Data successfully saved!");
            //$('#historyResults1').html(html);
        }
        });
       });
         
    $('#saveView5').click(function(){
	    var regno=$(this).attr('name');
        var Physical=$('#Physical').val();
        var Hearing=$('#Hearing').val();
        var Speech=$('#Speech').val();
        var Vision=$('#Vision').val();
        var Vomiting=$('#Vomiting').val();
        var Social=$('#Social').val();
        var Posture=$('#Posture').val();
        var Largemovements=$('#Largemovements').val();
        var Finemovements=$('#Finemovements').val();
        var Recognition=$('#Recognition').val();
        var Comprehension=$('#Comprehension').val();
        var Interaction=$('#Interaction').val();
        var Othercomments=$('#Othercomments').val();
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'action=Development&Physical='+Physical+'&Hearing='+Hearing+'&Speech='+Speech+'&Vision='+Vision+'&Vomiting='+Vomiting+'&Social='+Social+'&Posture='+Posture+'&Largemovements='+Largemovements+'&Finemovements='+Finemovements+'&Recognition='+Recognition+'&Comprehension='+Comprehension+'&Interaction='+Interaction+'&Othercomments='+Othercomments+'&regno='+regno,
        cache:false,
        success:function(html){
           alert("Data successfully saved!");
            //$('#historyResults1').html(html);
        }
        });
    });
    
    
    $('#saveView6').click(function(){
	var regno=$(this).attr('name');
    var Feeding_Month1=$('#Feeding_Month1').val();
    var Feeding_Month2=$('#Feeding_Month2').val();
    var Feeding_Month3=$('#Feeding_Month3').val();
    var Feeding_Month4=$('#Feeding_Month4').val();
    var Feeding_Month5=$('#Feeding_Month5').val();
    var Feeding_Month6=$('#Feeding_Month6').val();
    var Formula_Month1=$('#Formula_Month1').val();
    var Formula_Month2=$('#Formula_Month2').val();
    var Formula_Month3=$('#Formula_Month3').val();
    var Formula_Month4=$('#Formula_Month4').val();
    var Formula_Month5=$('#Formula_Month5').val();
    var Formula_Month6=$('#Formula_Month6').val();
    var Breast_Month1=$('#Breast_Month1').val();
    var Breast_Month2=$('#Breast_Month2').val();
    var Breast_Month3=$('#Breast_Month3').val();
    var Breast_Month4=$('#Breast_Month4').val();
    var Breast_Month5=$('#Breast_Month5').val();
    var Breast_Month6=$('#Breast_Month6').val();

  $.ajax({
  type:'POST', 
  url:'requests/paediatricDetails.php',
  data:'action=Nutriation&Feeding_Month1='+Feeding_Month1+'&Feeding_Month2='+Feeding_Month2+'&Feeding_Month3='+Feeding_Month3+'&Feeding_Month4='+Feeding_Month4+'&Feeding_Month5='+Feeding_Month5+'&Feeding_Month6='+Feeding_Month6+'&Formula_Month1='+Formula_Month1+'\n\
               &Formula_Month2='+Formula_Month2+'&Formula_Month3='+Formula_Month3+'&Formula_Month4='+Formula_Month4+'&Formula_Month5='+Formula_Month5+'&Formula_Month6='+Formula_Month6+'&Breast_Month1='+Breast_Month1+'&Breast_Month2='+Breast_Month2+'&Breast_Month3='+Breast_Month3+'&Breast_Month4='+Breast_Month4+'&Breast_Month5='+Breast_Month5+'&Breast_Month6='+Breast_Month6+'&regno='+regno,
  cache:false,
  success:function(html){
     alert("Data successfully saved!");
      //$('#historyResults1').html(html);
  }
  });
});


$('#saveView7').click(function(){
    var regno=$(this).attr('name');
    var Cotrimoxazole1_1=$('#Cotrimoxazole1_1').val();
    var Cotrimoxazole1_3=$('#Cotrimoxazole1_3').val();
    var Cotrimoxazole1_5=$('#Cotrimoxazole1_5').val();
    var Cotrimoxazole1_7=$('#Cotrimoxazole1_7').val();
    var Cotrimoxazole1_9=$('#Cotrimoxazole1_9').val();
    var Cotrimoxazole1_11=$('#Cotrimoxazole1_11').val();
    var ARVTherapy=$('#ARVTherapy').val();
    var HIVStatus=$('#HIVStatus').val();
    var ARVComments=$('#ARVComments').val();
    var Cotrimoxazole2_2=$('#Cotrimoxazole2_2').val();
    var Cotrimoxazole2_4=$('#Cotrimoxazole2_4').val();
    var Cotrimoxazole2_6=$('#Cotrimoxazole2_6').val();
    var Cotrimoxazole2_8=$('#Cotrimoxazole2_8').val();
    var Cotrimoxazole2_10=$('#Cotrimoxazole2_10').val();
    var Cotrimoxazole2_12=$('#Cotrimoxazole2_12').val();
    var HIVPositive=$('#HIVPositive').val();
    $.ajax({
    type:'POST', 
    url:'requests/paediatricDetails.php',
    data:'action=Children&Cotrimoxazole1_1='+Cotrimoxazole1_1+'&Cotrimoxazole1_3='+Cotrimoxazole1_3+'&Cotrimoxazole1_5='+Cotrimoxazole1_5+'&Cotrimoxazole1_7='+Cotrimoxazole1_7+'&Cotrimoxazole1_9='+Cotrimoxazole1_9+'&Cotrimoxazole1_11='+Cotrimoxazole1_11+'&ARVTherapy='+ARVTherapy+'&HIVStatus='+HIVStatus+'&ARVComments='+ARVComments+'&Cotrimoxazole2_2='+Cotrimoxazole2_2+'&Cotrimoxazole2_4='+Cotrimoxazole2_4+'&Cotrimoxazole2_6='+Cotrimoxazole2_6+'&Cotrimoxazole2_8='+Cotrimoxazole2_8+'&Cotrimoxazole2_10='+Cotrimoxazole2_10+'&Cotrimoxazole2_12='+Cotrimoxazole2_12+'&HIVPositive='+HIVPositive+'&regno='+regno,
    cache:false,
    success:function(html){
      alert("Data successfully saved!");
        //$('#historyResults1').html(html);
    }
    });
});


$('#saveView8').click(function(){
    var regno=$(this).attr('name');
    var diahorrea=$('#diahorrea').val();
    var dehydration=$('#dehydration').val();
    var Bloodinstool=$('#Bloodinstool').val();
    var Fever=$('#Fever').val();
    var Vomitting=$('#Vomitting').val();
    var Anythingelse=$('#Anythingelse').val();
    var Treatment =$('#Treatment').val();
    var Oralsolution=$('#Oralsolution').val();
    var IVmedicaion =$('#IVmedicaion').val();
    var Zinc=$('#Zinc').val();
    var othertreatment=$('#othertreatment').val();
    var treatmentLength=$('#treatmentLength').val();
    var Outcome=$('#Outcome').val();
    $.ajax({
    type:'POST', 
    url:'requests/paediatricDetails.php',
    data:'action=Diahorrea&diahorrea='+diahorrea+'&dehydration='+dehydration+'&Bloodinstool='+Bloodinstool+'&Fever='+Fever+'&Vomitting='+Vomitting+'&Anythingelse='+Anythingelse+'&Treatment='+Treatment+'&Oralsolution='+Oralsolution+'&IVmedicaion='+IVmedicaion+'&Zinc='+Zinc+'&othertreatment='+othertreatment+'&treatmentLength='+treatmentLength+'&Outcome='+Outcome+'&regno='+regno,
    cache:false,
    success:function(html){
        alert("Data successfully saved!");
        //$('#historyResults1').html(html);
    }
    });
});

</script>