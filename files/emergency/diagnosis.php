<div id="diagnosis">
    <form action="#" method='post' onsubmit="return validateForm();" enctype="multipart/form-data">
        <h3 style="margin-left: 100px">Diagnosis</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;width:15% ">Provisional Diagnosis</td><td><input style='width:88%;'  type='text' readonly='readonly'  class='provisional_diagnosis' id="provisional_diagnosis_comm" name='provisional_diagnosis' value='<?php echo $provisional_diagnosis; ?>'>
                        <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'> <?php echo $provisional_diagnosis2; ?> </td></tr>

                <tr>
                    <td style="text-align:right;">Differential Diagnosis</td><td><input style='width:88%;' type='text' readonly='readonly' id='diferential_diagnosis' class='diferential_diagnosis' name='diferential_diagnosis' value='<?php echo $diferential_diagnosis; ?>'>
                        <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'><?php echo $diferential_diagnosis2; ?> </td></tr>
                <tr>
                    <td style="text-align:right;"><b>Final Diagnosis </b></td><td><input style='width: 88%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value='<?php echo $diagnosis; ?>'>
                        <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')"><?php echo $diagnosis2; ?> </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="8"></td>
                    <td>
                        <div style="width:100%;text-align:right;padding-right:10px;">
                            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
                            <input type="hidden" name="Patient_Payment_ID" value="<?php echo $_GET['Patient_Payment_ID'] ?>"/>
                            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
                            <input type="submit" class="art-button-green" value='SAVE' onclick="return confirm('Are sure you want to save information?')"   >
                            <input type='hidden' name='submit_diagnosis' value='true'/> 
                        </div>

                    </td>
                </tr>
            </table>
        </center>
    </form>
</div>
