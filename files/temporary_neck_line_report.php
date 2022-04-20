<?php
include("./includes/connection.php");
        if (isset($_GET['start_date'])) {
            $start_date = $_GET['start_date'];
        }
        if (isset($_GET['end_date'])) {
            $end_date = $_GET['end_date'];
        }

        if (isset($_GET['Registration_ID'])) {
            $Registration_ID = $_GET['Registration_ID'];
        }
        
        $neckLineSql = "SELECT * FROM tbl_dialysis_temporary_neck_line WHERE Registration_ID='$Registration_ID' group by temporary_neck_line_id";
        $selectNeckLine = mysqli_query($conn,$neckLineSql);
         //$neckLine = mysqli_fetch_array($selectNeckLine);
         while($neckLine = mysqli_fetch_array($selectNeckLine)){
            $htm .='<table width="100%" style="background-color:#fff;">
            <tr><h1>PROCESSED DATE: '; 
            $htm .= $neckLine["saved_date"]; 
             $htm .='</tr>
            <tr>
                    <td width="50%">
                        <h3>PREPARE FOR THE PROCEDURE</h3>
                        <label for="pre1"><input type="checkbox" name="Get_US_Neck"'; 
                        if($neckLine['Get_US_Neck']=='on'){$htm .= 'checked ';} 
                         $htm .='id="pre1">1. Get US Neck - Preferable with vasculaer probe. </label> <br>
                        <label for="pre2"><input type="checkbox" name="Get_catheter"'; 
                        if($neckLine['Get_catheter']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre2">2. Get catheter - 11 Fr (18G), 13.5cm average size. </label> <br>
                        <label for="pre3"><input type="checkbox" name="Get_sterile"'; 
                        if($neckLine['Get_sterile']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre3">3. Get sterile drapers.</label> <br>
                        <label for="pre4"><input type="checkbox" name="Lignocaine"'; 
                        if($neckLine['Lignocaine']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre4">4. Lignocaine. </label> <br>
                        <label for="pre5"><input type="checkbox" name="Sterile_KY_gel"'; 
                        if($neckLine['Sterile_KY_gel']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre5">5. Sterile KY gel. </label> <br>
                        <label for="pre6"><input type="checkbox" name="Syringes"'; 
                        if($neckLine['Syringes']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre6">6. Syringes - 10cc X 2</label> <br>
                        <label for="pre7"><input type="checkbox" name="Normal_saline"'; 
                        if($neckLine['Normal_saline']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre7">7. Normal saline. </label> <br>
                        <label for="pre8"><input type="checkbox" name="Heparin"'; 
                        if($neckLine['Heparin']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre8">8. Heparin 5000 unit/ml </label> <br>
                        <label for="pre9"><input type="checkbox" name="Surgical_Blade"'; 
                        if($neckLine['Surgical_Blade']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre9">9. Surgical Blade</label> <br>
                        <label for="pre10"><input type="checkbox" name="Povidone"'; 
                        if($neckLine['Povidone']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre10">10. Povidone - Iodine 20mls </label> <br>
                        <label for="pre11"><input type="checkbox" name="Sterile_gloves"'; 
                        if($neckLine['Sterile_gloves']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre11">11. Sterile gloves x3 pairs </label> <br>
                        <label for="pre12"><input type="checkbox" name="Female_condom"'; 
                        if($neckLine['Female_condom']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre12">12. Female condom </label> <br>
                        <label for="pre13"><input type="checkbox" name="Sutures"'; 
                        if($neckLine['Sutures']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre13">13. Sutures - Nylon 2-0 </label> <br>
                        <label for="pre14"><input type="checkbox" name="Dressing_Blandle"'; 
                        if($neckLine['Dressing_Blandle']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre14">14. Dressing Blandle 1 </label> <br>
                        <label for="pre15"><input type="checkbox" name="Suture_set"'; 
                        if($neckLine['Suture_set']=='on'){$htm .= 'checked ';}
                          $htm .='id="pre15">15. Suture set 1 </label> 
                        <br><br><br><br><br>

                        REMARKS:
                        <textarea name="prepare_procedure_remarks">'.$neckLine['prepare_procedure_remarks'];
                        $htm .='</textarea>
                    </td>
                    <td width="50%">
                        <h3>THE PROCEDURE</h3>
                        <label for="pro1"><input type="checkbox" name="Lie_patient"'; 
                        if($neckLine['Lie_patient']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro1">1. Lie the compliant patient on a procedure bed. </label> <br>
                        <label for="pro2"><input type="checkbox" name="Explain_what_you_are_doing"'; 
                        if($neckLine['Explain_what_you_are_doing']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro2">2. Explain what you are doing. </label> <br>
                        <label for="pro3"><input type="checkbox" name="Sterile_technique"'; 
                        if($neckLine['Sterile_technique']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro3">3. Sterile technique - scrub, cap and face mask </label> <br>
                        <label for="pro4"><input type="checkbox" name="Paint"'; 
                        if($neckLine['Paint']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro4">4. Paint with povidone and spirit </label> <br>
                        <label for="pro5"><input type="checkbox" name="Drape_the_patient"'; 
                        if($neckLine['Drape_the_patient']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro5">5. Drape the patient </label> <br>                    
                        <label for="pro6"><input type="checkbox" name="Drape_the_US_probe"'; 
                        if($neckLine['Drape_the_US_probe']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro6">6. Drape the US probe </label> <br>
                        <label for="pro7"><input type="checkbox" name="Arrange"'; 
                        if($neckLine['Arrange']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro7">7. Arrange your instruments </label> <br>
                        <label for="pro8"><input type="checkbox" name="Look_for_the_vein"'; 
                        if($neckLine['Look_for_the_vein']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro8">8. Look for the vein with the linear US probe </label> <br>
                        <label for="pro9"><input type="checkbox" name="Veno"'; 
                        if($neckLine['Veno']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro9">9. Veno - Pucture and insert guide wire ....remove the needle </label> <br>
                        <label for="pro10"><input type="checkbox" name="Check_the_position"'; 
                        if($neckLine['Check_the_position']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro10">10. Check the position of the guide wire </label> <br>
                        <label for="pro11"><input type="checkbox" name="Small_Cut"'; 
                        if($neckLine['Small_Cut']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro11">11. Small Cut - on the skin </label> <br>
                        <label for="pro12"><input type="checkbox" name="Dilate_the_track"'; 
                        if($neckLine['Dilate_the_track']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro12">12. Dilate the track </label> <br>
                        <label for="pro13"><input type="checkbox" name="Insert_the_catheter"'; 
                        if($neckLine['Insert_the_catheter']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro13">13. Insert the catheter via the Seldinger method  </label> <br>
                        <label for="pro14"><input type="checkbox" name="Suture_the_cathete"'; 
                        if($neckLine['Suture_the_cathete']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro14">14. Suture the catheter </label> <br>
                        <label for="pro15"><input type="checkbox" name="Heparin_lock"'; 
                        if($neckLine['Heparin_lock']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro15">15. Heparin lock the catheter </label> <br>
                        <label for="pro16"><input type="checkbox" name="Get_ride"'; 
                        if($neckLine['Get_ride']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro16">16. Get ride of the Sharps </label> <br>
                        <label for="pro17"><input type="checkbox" name="Control_CXR"'; 
                        if($neckLine['Control_CXR']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro17">17. Control CXR </label> <br>
                        <label for="pro18"><input type="checkbox" name="Counsel_the_patient"'; 
                        if($neckLine['Counsel_the_patient']=='on'){$htm .= 'checked ';} 
                        $htm .='id="pro18">18. Counsel the patient </label> <br> REMARKS:
                        <textarea name="procedure_remarks">'.$neckLine['procedure_remarks'].'</textarea></tr></table>';
         }

         echo $htm;
    ?>