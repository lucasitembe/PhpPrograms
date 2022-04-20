<div id="investigation">
    <form action="#" method='post' onsubmit="return validateForm();" enctype="multipart/form-data">
        <h3 style="margin-left: 100px">Investigation & Results</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;">Laboratory</td><td><textarea style='width:88%;resize:none;padding-left:5px;' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Laboratory; ?></textarea>

                        <!-- <input type='button'  id='select_Laboratory' name='select_Laboratory'  value='Select'  class='art-button confirmGetItem' 
                               < ?php if ($provisional_diagnosis == '' || $provisional_diagnosis == null) { ?> onclick = "confirmDiagnosis('Laboratory')" < ?php } else { ?> onclick="getItem('Laboratory')" < ?php } ?> >< ?php echo $Laboratory2; ?> </td></tr> -->

                               <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Laboratory')"><?php echo $Laboratory2; ?></td></tr>

                            <tr><td style="text-align:right;">Radiology</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='provisional_diagnosis'><?php echo $Radiology; ?></textarea>
                       
                            <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Radiology')"><?php echo $Radiology2; ?></td></tr>

            </table>
            <table>
                <tr>
                
                    <td colspan="8"></td>
                    <td>
                        <div style="width:100%;text-align:right;padding-right:10px;">
                            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
                            <input type="hidden" name="Patient_Payment_ID" value="<?php echo $_GET['Patient_Payment_ID'] ?>"/>
                            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
                            <input type="button" value="PERFORM RADIOLOGY" class="art-button-green" onclick="perform_radiology()">
                            <input type="button" value="PERFORM LABORATORY" class="art-button-green" onclick="perform_laboratory()">
                            <input type="submit" class="art-button-green" value='SAVE' onclick="return confirm('Are sure you want to save information?')">
                            <input type='hidden' name='submit_investigation' value='true'/> 
                        </div>

                    </td>
                </tr>
            </table>
        </center>
    </form>
</div>
<script>
    function perform_radiology(){
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
        window.open("emergency_radiologypatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Registration_id=" + Registration_ID + "&consultation_ID=" + consultation_ID + "&ProcedureWorks=ProcedureWorksThisPage", "_parent");
        }
        }; //specify name of function that will handle server response........

        myObjectPerform.open('GET', 'emergency_radiologypatientinfo.php?Registration_ID=' + Registration_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&consultation_ID=' + consultation_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectPerform.send();
        }

        function perform_laboratory(){
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
            // window.open("emergency_laboratory.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Registration_ID=" + Registration_ID + "&consultation_ID=" + consultation_ID + "&ProcedureWorks=ProcedureWorksThisPage", "_blank");
            // }
            window.open("laboratory.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Registration_ID=" + Registration_ID + "&consultation_ID=" + consultation_ID + "&ProcedureWorks=ProcedureWorksThisPage", "_blank");
            }
            }; //specify name of function that will handle server response........

            // myObjectPerform.open('GET', 'emergency_laboratory.php?patient_id=' + Registration_ID + '&payment_id=' + Patient_Payment_ID + '&consultation_ID=' + consultation_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);

            myObjectPerform.open('GET', 'laboratory.php?patient_id=' + Registration_ID + '&payment_id=' + Patient_Payment_ID + '&consultation_ID=' + consultation_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
            myObjectPerform.send();
        }
</script>