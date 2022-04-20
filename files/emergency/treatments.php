<div id="treatment">
    <form action="#" method='post' onsubmit="return validateForm();" enctype="multipart/form-data">
        <h3 style="margin-left: 100px">Treatment</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='provisional_diagnosis'><?php echo $Pharmacy; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Pharmacy')"><?php echo $Pharmacy2; ?> </td></tr>
                <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Procedure; ?></textarea>
                        <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="confirm_final_diagnosis('Procedure')"><?php echo $Procedure2; ?></td></tr>
            </table>
            <table>
                <tr>
                    <td colspan="8"></td>
                    <td>
                        <div style="width:100%;text-align:right;padding-right:10px;">
                            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
                            <input type="hidden" name="Patient_Payment_ID" value="<?php echo $_GET['Patient_Payment_ID'] ?>"/>
                            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
                            <input type="button" value="PERFORM PROCEDURE" class="art-button-green" onclick="open_Procedure()">
                            <input type="submit" class="art-button-green" value='SAVE' onclick="return confirm('Are sure you want to save information?')"   >
                             
                            <input type='hidden' name='submit_treatment' value='true'/> 
                        </div>

                    </td>
                </tr>
            </table>
        </center>
    </form>
</div>
<div id="procedure" >

</div>
<script>
//  $(document).ready(function () {
//         $("#procedure").dialog({autoOpen: false, width: '85%', height: 400, title: 'PERFORM PROCEDURE', modal: true});

//     });
//     function open_Procedure() {
//         $("#procedure").dialog("open");
//     }

    function open_Procedure(){
        var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
    var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
    var Registration_ID = '<?php echo $Registration_ID; ?>';
    var consultation_ID = '<?php echo $consultation_ID; ?>';
    if (window.XMLHttpRequest){
    myObjectPerform = new XMLHttpRequest();
    } else if (window.ActiveXObject){
    myObjectPerform = new ActiveXObject('Micrsoft.XMLHTTP');
    myObjectPerform.overrideMimeType('text/xml');
    }
    myObjectPerform.onreadystatechange = function (){
    dataP = myObjectPerform.responseText;
    if (myObjectPerform.readyState == 4) {
    window.open("emergencyperformprocedure.php?Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Registration_id=" + Registration_ID, "_parent");
    }
    }; //specify name of function that will handle server response........

    myObjectPerform.open('GET', 'emergencyperformprocedure.php?Registration_ID=' + Registration_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&consultation_ID=' + consultation_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
    myObjectPerform.send();
     }
</script>