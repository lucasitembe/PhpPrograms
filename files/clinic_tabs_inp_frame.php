<div id="tabs">
    <ul>
        <li><h3><a href="#observation" style='font-size: small'>Findings</a></h3></li>
        <li><h3><a href="#diagnosis" style='font-size: small'>Diagnosis</a></h3></li>
        <li><h3><a href="#investigation" style='font-size: small'>Investigation & Results</a></h3></li>
        <li><h3><a href="#treatment" style='font-size: small'>Treatment</a></h3></li>
        <li><h3><a href="#remarks" style='font-size: small'>Remarks</a></h3></li>
    </ul>
    <div id="observation">
        <table width=100% style='border: 0px;'>
            <tr>
                <td width='15%' style="text-align:right;">Findings</td>
                <td>
                    <textarea required="required" style='width: 100%;resize: none;padding-left:5px;' id='Findings' <?php echo $auto_save_option ?> name='Findings'> <?php echo $Find; ?></textarea>
                    <?php echo $Findings; ?>

                </td>
            </tr>
        </table>
    </div>
    <div id="diagnosis">
        <table width=100% style='border: 0px;'>

            <tr><td style="text-align:right;width:15% ">Provisional Diagnosis</td><td><input style='width: 89%;display:inline' type='text' class='provisional_diagnosis' readonly='readonly' id='provisional_diagnosis' name='provisional_diagnosis' value=' <?php echo $provisional_diagn; ?>'>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'></a>
                    <?php echo $provisional_diagnosis; ?></td></tr>
            <tr><td style="text-align:right;width:15% ">Differential Diagnosis</td><td><input style='width: 89%;display:inline' class='diferential_diagnosis' type='text' readonly='readonly' id='diferential_diagnosis' name='diferential_diagnosis' value=' <?php echo $diferential_diagn; ?>'>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis'  value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'></a><?php echo $diferential_diagnosis; ?></td></tr>
            <tr><td style="text-align:right;width:15% "><b>Final Diagnosis </b></td><td><input style='width: 89%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value=' <?php echo $diagn; ?>'>
                    <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')">
                    <?php echo $diagnosis; ?>
                </td></tr>
        </table>
    </div>
    <div id="investigation">
        <table width=100% style='border: 0px;'>
            <tr>
                <td style="text-align:right;">Laboratory</td>
                <td>
                    <textarea style='width: 89%;display:inline' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Labory; ?></textarea>
                    <input type='button' class='art-button confirmGetItem' id='select_Laboratory' name='select_Laboratory'  value='Select' <?php if ($provisional_diagnosis == '' || $provisional_diagnosis == null) { ?> onclick = "confirmDiagnosis('Laboratory')" <?php } else { ?> onclick="getItem('Laboratory')" <?php } ?> ></a>
                    <?php echo $Laboratory; ?>
                </td>
            </tr>
            <tr>
                <td width='15%' style="text-align:right;">Comments For Laboratory</td>
                <td>
                    <input type='text' id='Comment_For_Laboratory' name='Comment_For_Laboratory' <?php echo $auto_save_option ?> value='<?php echo $Comment_For_Lab; ?>'>
                    <?php echo $Comment_For_Laboratory; ?>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Radiology</td>
                <td><textarea style='width: 89%;display:inline' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='Radiology'><?php echo $Rady; ?></textarea>
                    <input type='button' class='art-button-green' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' onclick="getItem('Radiology')"></a>
                    <?php echo $Radiology; ?>
                </td>
            </tr>
            <tr>
                <td width='15%' style="text-align:right;">Comments For Radiology</td>
                <td>
                    <input type='text' id='Comment_For_Radiology' <?php echo $auto_save_option ?> name='Comment_For_Radiology' value='<?php echo $Comment_For_Rad; ?>'>
                    <?php echo $Comment_For_Radiology; ?>
                </td>
            </tr>
            <tr>
                <td width='15%' style="text-align:right;">Doctor's Investigation Comments</td>
                <td>
                    <textarea style='resize: none;' id='investigation_comments' <?php echo $auto_save_option ?> name='investigation_comments'><?php echo $investigation_comm; ?></textarea>
                    <?php echo $investigation_comments; ?>
                </td>
            </tr>
        </table>
    </div>
    <div id="treatment">
        <table width=100% style='border: 0px;'>
             <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='Procedure'><?php echo $Pharcy; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Pharmacy')"></a>
                    <?php echo $Pharmacy; ?>
                </td>
            </tr>
            <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='Procedure'><?php echo $Procr; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Procedure')">
                    <?php echo $Procedure; ?>
                </td>
            </tr>

            <tr><td width='15%' style="text-align:right;">Procedure Comments</td><td><input type='text' <?php echo $auto_save_option ?> id='ProcedureComments' name='Comment_For_Procedure' style='width: 83%;' value='<?php echo $Comment_For_Proc; ?>'>
                    <?php echo $Comment_For_Procedure; ?>
                    <input type="button" value="PERFORM PROCEDURE" class="art-button-green" onclick="Perform_Procedure()">
                    <!-- <a href="doctorprocedurelistinpatient.php?sectionpatnt=doctor_with_patnt&Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&item_ID=<?php echo $_GET['item_ID'] ?>" class="art-button-green">PERFORM PROCEDURE</a> --></td></tr>

            <tr><td style="text-align:right;">Surgery</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Surgery'  id='provisional_diagnosis' name='Procedure'><?php echo $Surgry; ?></textarea>
                    <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Surgery')"></a>
                    <?php echo $Surgery; ?>
                </td>
            </tr>
            <tr><td width='15%' style="text-align:right;">Sugery Comments</td>
                <td>
                    <input style='width: 67%;' <?php echo $auto_save_option ?> type='text' id='SugeryComments' name='Comment_For_Surgery' value='<?php echo $Comment_For_Surg; ?>'>
                    <?php echo $Comment_For_Surgery; ?>
                    <a href='performsurgery.php?Section=Inpatient&consultation_ID=<?php echo $consultation_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&PerformSurgery=PerformSurgeryThisPage' target="_parent" class="art-button-green">PERFORM SURGERY(Post Operative Report)</a>
                </td>
            </tr>
           
        </table>
    </div>
    <div id="remarks">
        <table width=100% style='border: 0px;'>
            <tr>
                <td width='15%' style="text-align:right;">Remarks</td>
                <td>
                    <textarea style='resize: none;' <?php echo $auto_save_option ?> id='remarks' name='remarks'><?php echo $remk; ?></textarea>
                    <?php echo $remarks; ?>
                </td>
            </tr>
            <tr>
                <td width='15%' style="text-align:right;">Patient Status</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td>
                                <select id='dischargedPatient' name="dischargedPatient" style='width:40%;padding:4px;text-align: left;'>
                                    <option></option>
                                    <option>Continue</option>
                                    <option>Discharge</option>
                                    </select>
                                </td>
                                <td id="dis_reasons" style="display:none">
                                    <select id='Discharge_Reason_ID' name='Discharge_Reason_ID' required='required' style='width:31%'>
                                        <?php
                                        $select_discharge_reason = "SELECT * FROM tbl_discharge_reason";
                                        $reslt = mysqli_query($conn,$select_discharge_reason);
                                        while ($output = mysqli_fetch_assoc($reslt)) {
                                            ?>
                                            <option value='<?php echo $output['Discharge_Reason_ID']; ?>'><?php echo $output['Discharge_Reason']; ?></option>    
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            <td width="30%" style="text-align: center;">
                                <?php
                                //check status
                                $slct = mysqli_query($conn,"select Spectacle_ID from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and Patient_Type = 'Inpatient'") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($slct);
                                ?>
                                <input type="checkbox" name="Optical_Option" id="Optical_Option" class="art-button-green" value="REQUIRE SPECTACLE" <?php if ($nm > 0) {
                                    echo "checked='checked'";
                                } ?> onclick="Pequire_Spectacle(<?php echo $Registration_ID; ?>)">
                                <label for="Optical_Option"><b>REQUIRE SPECTACLE</b></label>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Others</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='otherconstype'  ><?php echo $Othrs; ?></textarea>
                    <input type='button'  value='Select'  class='art-button-green' onclick="getItem('Others')">
                     <?php echo $Others ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    $('#dischargedPatient').change(function () {
        var status = $('#dischargedPatient').val();
        if (status === 'Discharge') {
            $('#dis_reasons').show();
            $('#Discharge_Reason_ID,.disch_req').css('border', '3px solid red');
            $('#remarksss,.disch_req').attr('required', 'required');
        } else {
            $('#Discharge_Reason_ID,.disch_req').css('border', '1px solid #ccc');
            $('#dis_reasons').hide();
            $('.disch_req').attr('required', false);
        }
    });
</script>




