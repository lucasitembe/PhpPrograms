<?php
  include('../includes/connection.php');

  $names = array('Time' ,'Potassium','Sodium','Calcium','Magnesium','Chloride','Bicarbonate','BUN',
  'Cteatinine','bBlood Glucose','HB','HCT','RBC','WBC','Neutrophil','Basophil','Esinophil','Esr',
  'PT/Cont','APTT/Cont','INR','PLT','MP');

  $first_list = array('time','potassium','sodium','calcium','magnesium','chloride',
  'bicarbonate','bun','cteatinine','bblood_glucose','hb','hct','rbc',
  'neutrophil','basophil','esinophil');

  $second = array('Total Blirrubin','Total Protein','Albumin','A/G Ratio','Amylase','AST','ALT','ALP','SGOT','SGPT','CK','CK - MB','LDH',
  'Troponin','Myglobin','D-Dinner','pm','aptt_cont','inr','plt','esr','pt_cont','wbc');

  $patient_id = (isset($_GET['patient_id'])) ? $_GET['patient_id'] : 0;
  $get_form_four_record = mysqli_query($conn,"SELECT * FROM tbl_icu_form_four WHERE Registration_ID = $patient_id ORDER BY id DESC");
  
  if(mysqli_num_rows($get_form_four_record) > 0){

    while($raw_data = mysqli_fetch_array($get_form_four_record)){
        $Employee_ID = $raw_data['employee_id'];
        $saved_date = $raw_data['saved_date'];
        $first_row_string = explode(',',$raw_data['first_row_string']);
        $second_row_string = explode(',',$raw_data['second_row_string']);
        $image_input = explode(',',$raw_data['image_input']);

        $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ";
        $select_employee_name = mysqli_query($conn,$select_employee_name);
        while($employee_row = mysqli_fetch_array($select_employee_name)):
            $get_employee_name = $employee_row['Employee_Name'];
        endwhile;

?>

<div class="p-2 pt-3 border-bottom">
    <h5 class="text-center">Done By : <b><?=$get_employee_name?></b> Done On : <b><?=$saved_date?></b></h5>
    <div class="section">
        <table>
            <?php for($i=0;$i < sizeof($names); $i++) : ?>
            <tr>
            <td width="30.5%" style="padding: 5px;text-align:end"><?=$names[$i];?></td>
            <td width=""><input type="text" disabled value="<?=$first_row_string[$i]?>"></td>
            </tr>
            <?php endfor; ?>
        </table>

        <table>
        <?php for($i=0;$i < sizeof($second); $i++) : ?>
        <tr>
            <td width="30.5%" style="padding: 5px;text-align:end"><?=$second[$i];?></td>
            <td><input type="text" disabled value="<?=$second_row_string[$i]?>"></td>
        </tr>
        <?php endfor; ?> 
        </table>
        <table>
            <tr style="background-color: #eee;">
                <td colspan="2">Key for bedsore management 1.Keep clean and open | 2.Wash with normal saline and cover with dry gauze | <br> 3.Specified Management</td>
            </tr>
            <tr style="background-color: #eee;">
                <td colspan="">Location,Staging,Description and Treatment.</td>
                <td>Skin Assesment: [Bedsose,Lipsore,Rash,Bruise,Blister,Edema,Ulcers]</td>
            </tr>
            <tr>
                <td>1. Nose</td>
                <td><input type="text" id="image_input" value="<?=$image_input[0]?>"></td>
            </tr>
            <tr>
                <td>2. Mouth</td>
                <td><input type="text" id="image_input" value="<?=$image_input[1]?>"></td>
            </tr>
            <tr>
                <td>3. Neck</td>
                <td><input type="text" id="image_input" value="<?=$image_input[2]?>"></td>
            </tr>
            <tr>
                <td>4. Shoulders</td>
                <td><input type="text" id="image_input" value="<?=$image_input[3]?>"></td>
            </tr>
            <tr>
                <td>5. Scapular</td>
                <td><input type="text" id="image_input" value="<?=$image_input[4]?>"></td>
            </tr>
            <tr>
                <td>6. Chest</td>
                <td><input type="text" id="image_input" value="<?=$image_input[5]?>"></td>
            </tr>
            <tr>
                <td>7. Ribs</td>
                <td><input type="text" id="image_input" value="<?=$image_input[6]?>"></td>
            </tr>
            <tr>
                <td>8. Elbows</td>
                <td><input type="text" id="image_input" value="<?=$image_input[7]?>"></td>
            </tr>
            <tr>
                <td>9. Abdomen</td>
                <td><input type="text" id="image_input" value="<?=$image_input[8]?>"></td>
            </tr>
            <tr>
                <td>10. Pubic Region</td>
                <td><input type="text" id="image_input" value="<?=$image_input[9]?>"></td>
            </tr>
            <tr>
                <td>11. Genital</td>
                <td><input type="text" id="image_input" value="<?=$image_input[10]?>"></td>
            </tr>
            <tr>
                <td>12. Buttock</td>
                <td><input type="text" id="image_input" value="<?=$image_input[11]?>"></td>
            </tr>
            <tr>
                <td>13. Thigh</td>
                <td><input type="text" id="image_input" value="<?=$image_input[12]?>"></td>
            </tr>
            <tr>
                <td>14. Knee</td>
                <td><input type="text" id="image_input" value="<?=$image_input[13]?>"></td>
            </tr>
            <tr>
                <td>15. Ankle</td>
                <td><input type="text" id="image_input" value="<?=$image_input[14]?>"></td>
            </tr>
            <tr>
                <td>16. Toes</td>
                <td><input type="text" id="image_input" value="<?=$image_input[15]?>"></td>
            </tr>
            </table>
    </div>
</div>

<?php 
      }
    }else{
        echo"
            <div style='color:red;padding:2em'><center><h3>Data Not Found</h3></center></div>
        ";
    }
?>