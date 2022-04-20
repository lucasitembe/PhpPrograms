<div id="patientresultsummary" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
<table>
    <tr>
        <td><input type='text' id='Date_From1' style="text-align: center" value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
        <td><input type='text' id="Date_To1" style="text-align: center"value="<?= $End_Date ?>" readonly="readonly" placeholder="End Date"/></td>
        <td>
            <input type="button" value="PREVIEW PATIENT RESULT SUMMARY" class="art-button-green" onclick="filter_patient_result_summary(<?php echo $Registration_ID; ?>)">
        </td>
    </tr>
</table>
<form name="" action="Savedialysisclinicalnotes.php" id="savePatientResultsummary" method="post" >
    <table width="100%" style="background-color:#fff;">
        <tr><h4>HEMATOLOGY</h4></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">WCC (3.5-9.0 X 10^9/L) </td>
            <td><input type="text" name="wcc"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">HB (12M - 16 g/L)</td>
            <td><input type="text" name="hb"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">MCV (85-105 fL) </td>
            <td><input type="text" name="mcv"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Platelets (150 - 450 x 10^9/L)</td>
            <td><input type="text" name="platelets"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">ESR ( <20 mmhr)</td>
            <td><input type="text" name="esr"></td>
        </tr>
    </table>

    <table width="100%" style="background-color:#fff;">
        <tr><h4>BIOCHEMISTRY</h4></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Na (136 - 148 mmol/L)</td>
            <td><input type="text" name="na"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">K (3.8 - 5.0 mml/L)</td>
            <td><input type="text" name="k"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PRE Urea ( 2.1 - 7.1 mmol/L)</td>
            <td><input type="text" name="pre_urea"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PRE Creatinine (60 - 120 mol/L</td>
            <td><input type="text" name="pre_creatinine"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">URR</td>
            <td><input type="text" name="urr"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">POST Urea (2.1 - 7.1 mmol/L)</td>
            <td><input type="text" name="post_urea"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">POST Creatinine (60 - 120 mmol/l)</td>
            <td><input type="text" name="post_creatinine"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Glucose (plasm) (3.5-6.5 mol/L)</td>
            <td><input type="text" name="glucose"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">AST (<35 U/L)/ ATL (<45 U/L)</td>
            <td><input type="text" name="ast"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">ALP (50 - 150 U/L) / GGT (<40 U/L)</td>
            <td><input type="text" name="alp"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Total Bill (<20 Umol/L)</td>
            <td><input type="text" name="total_bill"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Total Protein (60 - 80 g/l)</td>
            <td><input type="text" name="total_protein"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Albumin (35 - 55 g/l)</td>
            <td><input type="text" name="albumin"></td>
        </tr>
    </table>

    <table width="100%" style="background-color:#fff;">
        <tr><h4>OTHER</h4></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Calcium (* adjusted)</td>
            <td><input type="text" name="calcium"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Phosphate</td>
            <td><input type="text" name="phosphate"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">PTH</td>
            <td><input type="text" name="pth"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Ferritin</td>
            <td><input type="text" name="ferritin"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Iron</td>
            <td><input type="text" name="iron"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Transferrin saturation</td>
            <td><input type="text" name="transferrin_saturation"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Serology</td>
            <td><input type="text" name="serology"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">HIV Test</td>
            <td><input type="text" name="hiv_test"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Hepatitis B</td>
            <td><input type="text" name="hepatitis_b"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">Hepatitis C</td>
            <td><input type="text" name="hepatitis_c"></td>
        </tr>
    </table>

    <table width="100%" style="background-color:#fff;">
        <tr><h4>ADEQUACY</h4></tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">URR</td>
            <td><input type="text" name="urr2"></td>
        </tr>
        <tr>
            <td style="font-size:15;margin-right:5px;" width="20%">KT/V</td>
            <td><input type="text" name="ktv"></td>
        </tr>
    </table>

        <table width="100%" style="background-color:#fff;">
        <tr><td><center>
        <?php
            // $this_page_from=$_GET['this_page_from'];
            if($this_page_from== 'patient_record'){?>
                <input type="hidden" class="art-button-green" value="SAVE PATIENT RESULT SUMMARY">
                        <input type="hidden" id="Registration_ID" name="Registration_ID" value="<?=$Registration_ID?>">
                        <input type="hidden" name="savePatientResultsummary">
                        </center></td></tr>
            <?php }else{?>
                <input type="submit" class="art-button-green" value="SAVE PATIENT RESULT SUMMARY">
                        <input type="hidden" id="Registration_ID" name="Registration_ID" value="<?=$Registration_ID?>">
                        <input type="hidden" name="savePatientResultsummary">
                        </center></td></tr>
            <?php }
        ?>
                        
        </table>
        </div>
    </form>
</div>