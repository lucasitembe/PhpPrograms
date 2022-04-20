<!--<form name="" action="Savedialysisclinicalnotes.php" id="saveDoctorsOder" method="post" >-->
<table class="table" style="border:none !important; border-color:transparent !important;background-color:#fff;">
            <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Indication;</td>
            <td>
                <textarea id="" rows="1" name="indication" id="indication" class="provisional_diagnosis"></textarea>
                <input type='button'  name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis","<?= $consultation_id ?>","<?= $Payment_Cache_ID ?>")'> <?php echo $provisional_diagnosis2; ?>
            </td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Diagnosis</td>
            <td><textarea id="" rows="1" name="diagnosis" class="final_diagnosis"></textarea>
                    <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis','<?= $consultation_id ?>','<?= $Payment_Cache_ID ?>')"><?php echo $diagnosis2; ?> 
                </td>
            </tr>
            <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Medication</td>
            <td><textarea id="" rows="1" name="medication" id="medication" class="Treatment"></textarea>
                <input type="button" name="showItems" value="Order" class="art-button-green" id="showItems" onclick="addItems('<?php echo $Registration_ID; ?>')"/></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Mode</td>
            <td><textarea id="" rows="1" name="mode" class="mode"></textarea></td>
            </tr>
            <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">ACCESS AVF - a Tunneled-t Untunneled -u;</td>
            <td><textarea id="" rows="1" name="access" class="access"></textarea></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Duration</td>
            <td><textarea id="" rows="1" name="duration" class="duration"></textarea></td>
            </tr>
            <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">UF/UFR;</td>
            <td><textarea id="" rows="1" name="uf_ufr" class="uf_ufr"></textarea></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">QB (PUMP)</td>
            <td><textarea id="" rows="1" name="qb" class="qb"></textarea></td>
            </tr>
            <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">DIALYSATE SPEED;</td>
            <td><textarea id="" rows="1" name="dialysate" class="dialysate"></textarea></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">BATH (NA)</td>
            <td><textarea id="" rows="1" name="bath" class="bath_na"></textarea></td>
            </tr>
            <tr>
                
                <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">BATH (K);</td>
            <td><textarea id="" rows="1" name="dialysate" class="bath_k"></textarea></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">BATH (HCO3)</td>
            <td><textarea id="" rows="1" name="bath" class="bath_hco3"></textarea></td>
            </tr>
            <tr>
                <tr>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Amount of Heparin;</td>
            <td><textarea id="" rows="1" name="dialysate" class="amount_of_heparine"></textarea></td>
            <td style="font-size:13;margin-right:5px;font-weight:bold;" width="20%">Type of Dialysis</td>
            <td>
                <select style="width: 100%; height: 100%" class="dialysis_type">
                    <option></option>
                    <?php 
                        $sql_select_added_title_result=mysqli_query($conn,"SELECT `id`, `name` FROM `tbl_dialysis_type_confg`") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_added_title_result)>0){
                             $count=1;
                            while($added_level_rows=mysqli_fetch_assoc($sql_select_added_title_result)){
                                $document_approval_level_title_id=$added_level_rows['id'];
                                $document_approval_level_title=$added_level_rows['name'];
                                echo "<option>$document_approval_level_title</option>";
                            }
                        }
                    ?>
                </select>
            </td>
            </tr>
            
            <tr>
                <td style="font-size:13;margin-right:5px;font-weight:bold;" width="100%" colspan="4">
                    <table class="table" style="border:none !important; border-color:transparent !important;background-color:#fff;">
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="10%">Prescription:</td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="5%">Session Cycle:</td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="7%"><input type="text" name="saveDoctorsOder" class="sessioncicle"></td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="5%">
                            <select style="width: 100%" class="sessioncicleunits">
                                <option></option>
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                            </select>
                        </td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="5%">Session Time:</td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="7%"><input type="text" name="saveDoctorsOder" class="sessiontime"></td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="5%">
                            <select style="width: 100%" class="sessiontimeunits">
                                <option></option>
                                <option>Minutes</option>
                                <option>Hour</option>
                            </select>
                        </td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="7%">Duration</td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="7%"><input type="text" name="saveDoctorsOder" class="duartions"></td>
                        <td style="font-size:13;margin-right:5px;font-weight:bold;" width="7%">
                            <select style="width: 100%" class="durationunits">
                                <option></option>
                                <option>day</option>
                                <option>Week</option>
                                <option>Month</option>
                                <option>Year</option>
                            </select>
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
        <tr><td colspan="4"><center>
            <input type="submit" class="art-button-green" onclick="saveDialysisPriscription('<?php echo $Registration_ID; ?>','<?php echo $Consultant_employee; ?>')" value="SAVE DATA">
                        <input type="hidden" name="saveDoctorsOder">
                        <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?php echo $Registration_ID; ?>">
                        <input type="hidden" name="recentConsultaionTyp" id="recentConsultaionTyp">
                        <input type="hidden" name="consultation_id" id="consultation_id">
                        <input type="hidden" name="Payment_Cache_ID" id="Payment_Cache_ID">
                        <input type="hidden" name="ordered_by" id="ordered_by" value="<?php echo $Consultant_employee; ?>">
                        </center></td></tr>
</table>
<!--</form>-->
<script>
    
    function showpatientprescription(id){
        $.ajax({
            type: 'POST',
            url: 'ajax_dialysis_prescriptin.php',
            data: {
                showpatientprescription:"showpatientprescription",
                prescription_id:id,
             },
            success: function (html) {
                $('#showpatientprescriptiondata').html(html);
                $("#showpatientprescription").dialog("open");
            }
        });
    }
    
    function updateStatus(id,prescription_id){
        $.ajax({
            type: 'POST',
            url: 'ajax_dialysis_prescriptin.php',
            data: {
                updatestatusdone:"updatestatusdone",
                prescription_id:prescription_id,
                id:id,
             },
            success: function (html) {
                $('#showpatientprescriptiondata').html(html);
            }
        });
    }
    
    function saveDialysisPriscription(Registration_ID, Consultant_employee){
      var Registration_ID = $("#Registration_ID").val();
      var Consultant_employee = $("#Consultant_employee").val();
      var indication = $(".provisional_diagnosis").val();
      var diagnosis = $(".final_diagnosis").val();
      var medication = $(".Treatment").val();
      var mode = $(".mode").val();
      var access = $(".access").val();
      var duration = $(".duration").val();
      var uf_ufr = $(".uf_ufr").val();
      var qb = $(".qb").val();
      var dialysate = $(".dialysate").val();
      var bath_na = $(".bath_na").val();
      var bath_k = $('.bath_k').val();
      var bath_hco3 = $(".bath_hco3").val();
      var amount_of_heparine = $(".amount_of_heparine").val();
      var dialysis_type = $(".dialysis_type").val();
      var sessioncicle = $(".sessioncicle").val();
      var sessioncicleunits = $(".sessioncicleunits").val();
      var sessiontime = $(".sessiontime").val();
      var sessiontimeunits = $(".sessiontimeunits").val();
      var duartions = $(".duartions").val();
      var durationunits = $(".durationunits").val();
      var ordered_by = $("#ordered_by").val();
      var index = 0;
      
        if(indication == '' || indication == null){
          $('.provisional_diagnosis').css({'border':'2px solid red'});
          $('.provisional_diagnosis').focus();
          index++;
      }
      
      if(diagnosis == '' || diagnosis == null){
          $('.final_diagnosis').css({'border':'2px solid red'});
          $('.final_diagnosis').show();
          index++;
      }
      if(medication == '' || medication == null){
          $('.Treatment').css({'border':'2px solid red'});
          $('.Treatment').show();
          index++;
      }
      if(mode == '' || mode == null){
          $('.mode').css({'border':'2px solid red'});
          $('.mode').show();
          index++;
      }
      if(access == '' || access == null){
          $('.access').css({'border':'2px solid red'});
          $('.access').show();
          index++;
      }
      if(duration == '' || duration == null){
          $('.duration').css({'border':'2px solid red'});
          $('.duration').show();
          index++;
      }
      if(uf_ufr == '' || uf_ufr == null){
          $('.uf_ufr').css({'border':'2px solid red'});
          $('.uf_ufr').show();
          index++;
      }
      if(qb == '' || qb == null){
          $('.qb').css({'border':'2px solid red'});
          $('.qb').show();
          index++;
      }
      if(dialysate == '' || dialysate == null){
          $('.dialysate').css({'border':'2px solid red'});
          $('.dialysate').show();
          index++;
      }
      if(bath_na == '' || bath_na == null){
          $('.bath_na').css({'border':'2px solid red'});
          $('.bath_na').show();
          index++;
      }
      if(bath_k != '' && bath_k == null){
          $('.bath_k').css({'border':'2px solid red'});
          $('.bath_k').show();
          index++;
      }
      if(bath_hco3 == '' || bath_hco3 == null){
          $('.bath_hco3').css({'border':'2px solid red'});
          $('.bath_hco3').show();
          index++;
      }
      if(amount_of_heparine == '' || amount_of_heparine == null){
          $('.amount_of_heparine').css({'border':'2px solid red'});
          $('.amount_of_heparine').show();
          index++;
      }
      if(dialysis_type == '' || dialysis_type == null){
          $('.dialysis_type').css({'border':'2px solid red'});
          $('.dialysis_type').show();
          index++;
      }
      if(sessioncicleunits == '' || sessioncicleunits == null){
          $('.sessioncicleunits').css({'border':'2px solid red'});
          $('.sessioncicleunits').show();
          index++;
      }
      if(sessioncicle == '' || sessioncicle == null){
          $('.sessioncicle').css({'border':'2px solid red'});
          $('.sessioncicle').show();
          index++;
      }
      if(sessiontime == '' || sessiontime == null){
          $('.sessiontime').css({'border':'2px solid red'});
          $('.sessiontime').show();
          index++;
      }
      if(sessiontimeunits == '' || sessiontimeunits == null){
          $('.sessiontimeunits').css({'border':'2px solid red'});
          $('.sessiontimeunits').show();
          index++;
      }
      if(duartions == '' || duartions == null){
          $('.duartions').css({'border':'2px solid red'});
          $('.duartions').show();
          index++;
      }
      if(durationunits == '' || durationunits == null){
          $('.durationunits').css({'border':'2px solid red'});
          $('.durationunits').show();
          index++;
      }
      
      if(index > 0){
          exit;
      }else{
           $.ajax({
                type: 'POST',
                url: 'Savedialysisclinicalnotes.php',
                data: {
                    Registration_ID:Registration_ID,
                    Consultant_employee:Consultant_employee,
                    indication:indication,
                    diagnosis:diagnosis,
                    medication:medication,
                    mode:mode,
                    access:access,
                    duration:duration,
                    uf_ufr:uf_ufr,
                    qb:qb,
                    dialysate:dialysate,
                    bath_na:bath_na,
                    bath_k:bath_k,
                    bath_hco3:bath_hco3,
                    amount_of_heparine:amount_of_heparine,
                    dialysis_type:dialysis_type,
                    sessioncicle:sessioncicle,
                    sessioncicleunits:sessioncicleunits,
                    sessiontime:sessiontime,
                    sessiontimeunits:sessiontimeunits,
                    duartions:duartions,
                    durationunits:durationunits,
                    ordered_by:ordered_by,
                    saveDoctorsOder:"saveDoctorsOder"
                },
                success: function (html) {
                    if(html == "ok"){
                        $("#ordered_by").val("");
                        $(".durationunits").val("");
                        $(".duartions").val("");
                        $(".sessiontimeunits").val("");
                        $(".sessiontime").val("");
                        $(".sessioncicleunits").val("");
                        $(".sessioncicle").val("");
                        $(".dialysis_type").val("");
                        $(".amount_of_heparine").val("");
                        $(".bath_hco3").val("");
                        $('.bath_k').val("");
                        $(".bath_na").val("");
                        $(".dialysate").val("");
                        $(".qb").val("");
                        $(".uf_ufr").val("");
                        $(".duration").val("");
                        $(".access").val("");
                        $(".mode").val("");
                        $(".Treatment").val("");
                        $(".final_diagnosis").val("");
                        $(".provisional_diagnosis").val("");
                        alert("Save Successfully.");
                    }else{
                         alert("Data save Fail");
                    }
            }
        });
      }
      
    }
    
    
    function doneDiagonosisselect1() {
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        updateDoctorConsult();
        $("#showdataConsult").dialog("close");
    }
    
    function getDiseaseFinal(Consultation_Type,consultation_id,Payment_Cache_ID) {
        var Registration_ID = $("#Registration_ID").val();
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }

        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        document.getElementById("consultation_id").value = consultation_id;
        document.getElementById("Payment_Cache_ID").value = Payment_Cache_ID;
        var ul = 'doctoritemselectajax.php';
        if (Consultation_Type == 'diagnosis' || Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis') {
            ul = 'dialysisdiagnosisselect.php';
        }

        var url2 = 'Consultation_Type=' + Consultation_Type + '&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_id+'&payment_cache_ID='+Payment_Cache_ID;
        $.ajax({
                type: 'GET',
                url: ul,
                data: url2,
                success: function (html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }
    
   function updateDoctorConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo$Registration_ID ?>&Patient_Payment_ID='<?php echo$_GET['Patient_Payment_ID'] ?>'&consultation_ID='<?php echo $consultation_id ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
        type: 'GET',
                url: 'requests/itemdoctorselectupdate.php',
                data: url2,
                cache: false,
                success: function (html) {
                //alert(html);
                html=html.trim();
                var Consultation_Type = html.split('<$$$&&&&>');
                if (Consultation_Type[0].trim() == 'provisional_diagnosis') {
                $('.provisional_diagnosis').attr('value', Consultation_Type[1]);
                if ($('.provisional_diagnosis').val() != '') {
                $('.confirmGetItem').attr("onclick", "getItem('Laboratory')");
                } else {
                $('.confirmGetItem').attr("onclick", "confirmDiagnosis('Laboratory')");
                }
                } else if (Consultation_Type[0].trim() == 'diferential_diagnosis') {
                //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                $('.diferential_diagnosis').attr('value', Consultation_Type[1]);
                } else if (Consultation_Type[0].trim() == 'diagnosis') {
                $('.final_diagnosis').attr('value', Consultation_Type[1]);
                }
            }
        });
    }
    
    function updateConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=Treatment&Registration_ID=<?php echo$Registration_ID ?>&Patient_Payment_ID='<?php echo $Payment_Cache_ID_og; ?>'&consultation_ID='<?php echo $consultation_id ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
                type: 'GET',
                url: 'requests/itemselectupdate.php',
                data: url2,
                cache: false,
                success: function (html) {
                //alert(html);
                html=html.trim();
                var departs = html.split('tenganisha');
                for (var i = 0; i < departs.length; i++) {
                var Consultation_Type = departs[i].split('<$$$&&&&>');
                //alert(Consultation_Type[0]);
//                if (Consultation_Type[0].trim() == 'Treatment') {
//                    $('.Treatment').html(Consultation_Type[1]);
//                }
                
                    if (Consultation_Type[0].trim() == 'Treatment') {
                        $('.Treatment').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Laboratory') {
                        $('.laboratoryinvestidation').html(Consultation_Type[1]);
                    } 
                }
             }
        });
    }
    
    function doneDiagonosisselect() {
        updateConsult();
        $("#showdataConsult").dialog("close");
    }
</script>