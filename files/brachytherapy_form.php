<?php
include("includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
$Employee_ID = $_GET['Current_Employee_ID'];
$consultation_ID = $_GET['consultation_ID'];

?>

<div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
            <tr>
                <th colspan='4'><h3>BRACHYTHERAPY TREATMENT REQUEST</h3></th>
            </tr>
            <tr style="background: #fff !important;">
                <td style="text-align: right; width: 20%">Total Number of Fraction</td>
                <td><input type="text" name="Number_of_Fraction" id="Number_of_Fraction" style='width:100%' onkeyup='add_Phase_brachy(<?= $consultation_ID ?>)' required></td>

                <td style="text-align: right; width: 20%">Dose Per Fraction</td>
                <td><input type="text" name="Dose_per_Fraction" id="Dose_per_Fraction" style='width:100%' onkeyup='add_Phase_brachy(<?= $consultation_ID ?>)' required></td>
            </tr>
            <tr>
                <td style="text-align: right; width: 20%">Type of Brachytherapy</td>
                <td>
                    <select style="height:27px; width: 100%;" onchange="add_Phase_brachy(<?= $consultation_ID ?>)" name='Type_of_brachytherapy' id='Type_of_brachytherapy'>
                        <option value="">-- Select Type of Brachytherapy --</option>
                        <option value="Intracavitary">Intracavitary</option>
                        <option value="Intestitial">Intestitial</option>
                    </select>   
                </td>
                <td colspan='2'></td>
            </tr>
            <tr>
                <td colspan='4'>
                    <legend></legend>
                </td>
            </tr>
            <tr style="background: #fff !important;">
                <td colspan="4" id="previous_data_brachytherapy" style='overflow-y: scroll; overflow-x: hidden;'>
                </td>
            </tr>

        </table>
</div>




