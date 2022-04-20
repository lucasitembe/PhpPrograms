<?php
include("./includes/header.php");
include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    $Today="";
    $today_start="";
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
    $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
        $today_start=$start_dt_row['cast(current_date() as datetime)'];
    }
    
         if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Employee_ID'])) {
              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            } else {
               $Employee_ID = 0;
            }
        }
?>
<a href="morguepageregistration.php" class="art-button-green">RegisterNew</a>
<a href="morguepage.php" class="art-button-green">BACK</a>
     <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
<br>
<br>
<br>
<fieldset> 
    <legend align='center'>MORGUE REGISTRATION</legend>
<!--start dialog codebook-->
<!--<div id="store_death_discharged_info" style="display:none">-->
    <table class="table">
      <tr>
                <td style="width: 15%; text-align: right;">
                    First Name :
                </td>
                <td>
                    <input  type="text" style="width: 100%"  name="" id="first_name" class="" placeholder="First Name">
                </td>
                <td style="width: 15%; text-align: right;">
                    Last Name :
                </td>
                 <td>
                    <input  type="text"  style="width: 100%"  name="" id="last_name" class="" placeholder="Last Name">
                </td>
            </tr>
            <tr>
                <td style="width: 15%; text-align: right;">
                    Select Sponsor:
                </td>
                <td>
                     <select name='Guarantor_Name' id='Guarantor_Name' required='required' style='border-color: red' onchange='MemberNumberMandate(this.value);setVerify(this.value); disable_member_number(this.value); Modify_Patient_Name();'>
                                        <option selected='selected'></option>
                                        <?php
                                        if (isset($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) && strtolower($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) == 'yes') {
                                            $data = mysqli_query($conn,"select * from tbl_sponsor") or die(mysqli_error($conn));
                                        } else {
                                            $data = mysqli_query($conn,"select * from tbl_sponsor WHERE Exemption = 'no'") or die(mysqli_error($conn));
                                        }

                                        while ($row = mysqli_fetch_array($data)) {

                                            echo '<option value="' . $row['Guarantor_Name'] . '">' . $row['Guarantor_Name'] . '</option>';
                                        }
                                        ?>
                        </select>
                </td>
                <td style="width: 15%; text-align: right;">
                    <b style='color: red'>Date Of Birth</b>
                </td>
                <td>
                    <input type='text' name='Date_Of_Birth' autocomplete='off' id='date2' value="<?= $patient_age ?>" style='border-color: red;width:30%' required='required' />&nbsp;&nbsp;<input type='text' autocomplete='off' id='datetime' oninput='calculatedate(this.value)' style='width:25%;text-align:center' placeholder="enter age" maxlength="3"  class="numberonly" />
                </td>
            </tr>
            <!--kuntacode add details from reception work-->
            <tr>
                <td style="width: 15%; text-align: right;">
                    Relative Name    
                </td>
                <td>
                    <input style="width:100%"  type="text"   name="" id="relative_name" class="" >
                </td>
                <td style="width: 15%; text-align: right;">
                    Relationship Type
                </td>
               <td>
                    <input style="width:100%!important"  type="text"   name="" id="relationship_type" class="" placeholder="Enter Relationship Type">
               </td>
                
           </tr>
          <tr>
                <td style="width: 15%; text-align: right;">
                   Relative Phone Number :
               </td>
                <td>
                    <input onkeyup="validate_number()" maxlength="10"  x-autocompletetype="tel"   type="tel"  style="width: 100%" type="text"   name="" id="relative_phone_number" class="" placeholder="Enter Relative Phone Number" onkeyup="numberOnly(this)">
              </td>
              <td style="width: 15%; text-align: right;">
                    Relative Address :
              </td>
              <td>
                    <input  style="width: 100%"  type="text"   name="relative_Address" id="relative_Address" class=""  placeholder="Enter Relative Address" >
              </td>
                
            </tr>
        
<!--               <tr>
                   <td colspan="2">
              Death Before/After of arrive :
               
                  <select name="dead_after_before" id='dead_after_before'>
                      <option value=""></option>
                    <option  value="dead_before">Dead Before arrive to Hospital</option>
                    <option  value="dead_after">Dead After arrive to Hospital</option>
                   
                </select> 
                  </td>
                
            </tr>-->
              <tr>
                <td style="width: 15%; text-align: right;">
                    Gender
                  </td>
                  <td>
                      <select name="gender" id="gender">
                          <option value=""></option>
                          <option>Male</option>
                          <option>Female</option>
                      </select>
                  </td>
                  <td style="width: 15%; text-align: right;">
                      Doctor confirm death
                      </td>
                                <td>
                               <select id="Docto_confirm_death_name" style="width:45%!important">
                        <option value=""></option>
                        <?php 
                           
                            $sql_select_doctors_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status = 'active'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_doctors_result)>0){
                                while ($doctors_rows=mysqli_fetch_assoc($sql_select_doctors_result)){
                                    $doctor_cd_name=$doctors_rows['Employee_Name'];
                                    $doctor_cd_id=$doctors_rows['Employee_ID'];
//                                    $selected="";
//                                   if($Employee_ID==$doctor_cd_id)
//                                   {
//                                      $selected="selected='selected'";
//                                   }
                                    echo "<option value='$doctor_cd_id'>$doctor_cd_name</option>";
//                                    echo $select_doctor; echo "mambooooo";
                                }
                            }
                          ?>
                    </select>
                  </td>
              
                        </tr>
                        <tr>
       
                <td colspan='4' style='text-align: center;'>
                
<!--                    id="send_notsend_to_morgue" hidden="hidden">
                    Do you what to send Body to Mortuary After Comfirm Death?
                    <input value='yes' required="required" type='radio' name='send_notsend_to_morgue' id='send_notsend_to_morgue_yes' class="hidden">
                    <b style="color:red"> YES </b>  &bsim;-->
                    
                    <input type="text" id="Discharge_Reason_txt" hidden="hidden">
                    <input type="button" value="Register Patient" style='padding: 5px 25px; font-weight: bold;' class="art-button-green" onclick="save_motuary_registration()">
                </td>
            </tr>
        </table>
</fieldset> 
    <!--</div>-->
<script type="text/javascript">
    $(document).ready(function () {
 $('select').select2();
    });
    
    
       function save_motuary_registration(){
               var first_name = $('#first_name').val();
               var last_name = $('#last_name').val();
               var relative_phone_number = $('#relative_phone_number').val();
               var relative_Address = $('#relative_Address').val();
               var relative_name = $('#relative_name').val();
               var relationship_type = $('#relationship_type').val();
               var Guarantor_Name = $('#Guarantor_Name').val();
               var gender = $('#gender').val();
               var date2 = $('#date2').val();
         
               var Employee_ID = '<?php echo $Employee_ID; ?>';
               
            if(gender == ''){
                alert("Please Specify Gender");
                exit();
            }

               if(first_name ==""){
                   alert("first name");
               }else if(last_name==""){
                   alert("last name");
               }else{
                   
            $.ajax({
            url:'save_motuary_registration.php',
            type:'post',
            data:{first_name:first_name,last_name:last_name,Guarantor_Name:Guarantor_Name,relative_phone_number:relative_phone_number,relative_Address:relative_Address,relative_name:relative_name,relationship_type:relationship_type,date2:date2,Employee_ID:Employee_ID,gender:gender},
            success:function(result){
//                 alert("successfully saved");
                 
                 save_admission_patient(result);
//                  document.location = result;
            }
        });
               }
         
               
    
    
    }
    
        function save_admission_patient(result){
            
             var Docto_confirm_death_name = $('#Docto_confirm_death_name').val();
              
              $.ajax({
            url:'save_admission_patient.php',
            type:'post',
            data:{result:result,Docto_confirm_death_name:Docto_confirm_death_name},
            success:function(result){
                  alert("successfuly saved");
                   document.location = result;
            }
        });
              
          }
       
    
        function calculatedate(age) {
        $.ajax({
            type: 'GET',
            url: 'getinfos.php',
            data: 'getage=' + age,
            cache: false,
            beforeSend: function (xhr) {
                $("#date2").attr('readonly', true);
            },
            success: function (html) {
                $("#date2").val(html);
            },
            complete: function (jqXHR, textStatus) {
                $("#date2").attr('readonly', false);
            },
            error: function (html) {

            }
        });
    }

</script>