<?php
include("includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
$Employee_ID = $_GET['Current_Employee_ID'];
$consultation_ID = $_GET['consultation_ID'];

?>

<div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
            <tr>
                <th colspan='4'><h3>RADIOTHERAPY TREATMENT REQUEST</h3></th>
            </tr>
            <tr>
                <td style="text-align: right; width: 20%">Intent of Treatment</td>
                <td>
                    <select name="Intent_of_Treatment" id="Intent_of_Treatment" style='width:100%'>
                        <option value="Radical">Radical Treatment</option>
                        <option value="Palietive">Palietive Treatment</option>
                    </select>
                </td>
                <td style="text-align: right; width: 20%">Treatment Phase</td>
                <td>
                    <select name="Treatment_Phase" id="Treatment_Phase" onchange='check_phase(<?= $consultation_ID ?>)' style='width:100%'>
                        <option selected='selected'>Phase I</option>
                        <option>Phase II</option>
                        <option>Phase III</option>
                        <option>Phase IV</option>
                    </select>
                </td>
            </tr>
            <tr style="background: #fff !important;">
                <td style="text-align: right; width: 20%">Total Tumor Dosage</td>
                <td><input type="text" name="Tumor_Dose" id="Tumor_Dose" style='width:100%' onkeyup='add_Phase(<?= $consultation_ID ?>)' required></td>
                <td style="text-align: right; width: 20%">Total Number of Fraction</td>
                <td><input type="text" name="Number_of_Fraction" id="Number_of_Fraction" style='width:100%' onkeyup='add_Phase(<?= $consultation_ID ?>)' required></td>
            </tr>
            <tr>
                <td style="text-align: right; width: 20%">Dose Per Fraction</td>
                <td><input type="text" name="Dose_per_Fraction" id="Dose_per_Fraction" style='width:100%' onkeyup='add_Phase(<?= $consultation_ID ?>)' required></td>
                <td style="text-align: right; width: 20%">Name of Site</td>
                <td>
                    <select style="height:27px; width: 100%;" onchange="add_Phase(<?= $consultation_ID ?>)" name='name_of_site' id='name_of_site'>
                        <option value="">-- Select Name of Site --</option>
                        <option value="Head">Head</option>
                        <option value="Head & Neck">Head & Neck</option>
                        <option value="Chest">Chest</option>
                        <option value="Abdomen">Abdomen</option>
                        <option value="Pelvis">Pelvis</option>
                        <option value="Lower Extrimities">Lower Extrimities</option>
                        <option value="Upper Extrimities">Upper Extrimities</option>
                    </select>
                        
                </td>
            </tr>
            <tr style="background: #fff !important;">
                <td style="text-align: right; width: 20%">Number of Fields</td>
                <td><select style='width: 100%;' name="Number_of_Fields" id="Number_of_Fields" style='width:100%' onchange='add_Phase(<?= $consultation_ID ?>)' required>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan='4'>
                    <legend></legend>
                </td>
            </tr>
            <tr style="background: #fff !important;">
                <td colspan="4" id="previous_data" style='overflow-y: scroll; overflow-x: hidden;'>
                </td>
            </tr>

        </table>
</div>




