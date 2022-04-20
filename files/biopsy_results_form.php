<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    th{
        text-align:right;
    }
   
</style>
<?php
   session_start();
   include("./includes/connection.php");
   

    if (isset($_GET['Date_From'])) {
        $Date_From = $_GET['Date_From'];
    } else {
        $Date_From = '';
    }
    
    
    if (isset($_GET['Date_To'])) {
        $Date_To = $_GET['Date_To'];
    } else {
        $Date_To = '';
    }


    $Registration_ID = $_GET['Registration_ID'];
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    // $Employee_ID = $_GET['Employee_ID'];
    $consultation_ID = $_GET['consultation_ID'];
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	
    
?>

<fieldset>
    <table class="table table-bordered" style="background: #FFFFFF">
        <caption><b>PATIENT DETAILS</b></caption>
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td style='text-align:right'><b>REGISTRATION No.</b></td>
            <td><b>WARD</b></td>
            <td><b>DOCTOR </b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            
        </tr>
        <?php 
            $Patient_Name ="";
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region ="";
            $District ="";
            $Ward ="";
            $village ="";
            $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name,Date_Of_Birth,Region,District,Ward,village,Gender FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_patient_information_result)>0){
                while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
                    $Patient_Name =$pat_details_rows['Patient_Name'];
                    $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
                    $Region =$pat_details_rows['Region'];
                    $District =$pat_details_rows['District'];
                    $Ward =$pat_details_rows['Ward'];
                    $village =$pat_details_rows['village'];
                    $Gender =$pat_details_rows['Gender'];
                }
            }
             //today function
            $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                //select doctor name
                $doctor_id=mysqli_query($conn,"SELECT consultant_ID, Payment_Date_And_Time FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID= '$Payment_Item_Cache_List_ID '") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_id)){
                    $doctor_id2 = $row['consultant_ID'];
                }


                $doctor_name1 = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID= '$doctor_id2'") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_name1)){
                    $doctor_name = $row['Employee_Name'];
                }
                
                //select admission ward 
                $Hospital_Ward_Name="";
                $sql_select_admission_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID=(SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status<>'Discharged' ORDER BY Admision_ID DESC LIMIT 1)") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_admission_ward_result)>0){
                    $Hospital_Ward_Name=mysqli_fetch_assoc($sql_select_admission_ward_result)['Hospital_Ward_Name'];
                }else{
                    $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
                }
                echo "<tr>
                    <td>$Patient_Name</td>
                    <td style='text-align:right'>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>$Employee_Name</td>
                    <td>$age</td>
                    <td>$Gender </td>
                  </tr>";
                  $Biopsy_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Biopsy_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID= '$Payment_Item_Cache_List_ID '"))['Biopsy_ID'];
                
                  if($Biopsy_ID > 0){

                            //   die("SELECT * FROM tbl_histological_examination WHERE Biopsy_ID = '$Biopsy_ID'");
                            $select_biopsy = mysqli_query($conn, "SELECT * FROM tbl_histological_examination WHERE Biopsy_ID = '$Biopsy_ID'") or die(mysqli_error($conn));
                                while($rows = mysqli_fetch_array($select_biopsy)){
                                    $autospy = $rows['autospy'];
                                    $Priority2 = $rows['Priority'];
                                    $birth_region = $rows['birth_region'];
                                    $birth_village = $rows['birth_village'];
                                    $birth_district = $rows['birth_district'];
                                    $birth_year = $rows['birth_year'];
                                    $resident_year = $rows['resident_year'];
                                    $Doctor_collected = $rows['Employee_ID'];
                                    $Requested_Datetime = $rows['Requested_Datetime'];
                                    $New_Case = $rows['New_Case'];
                                    $relevant_clinical_data = $rows['relevant_clinical_data'];
                                    $Laboratory_Number = $rows['Laboratory_Number'];
                                    $Site_Biopsy = $rows['Site_Biopsy'];
                                    $Previous_Laboratory = $rows['Previous_Laboratory'];
                                    $Duration_Condition = $rows['Duration_Condition'];
                                    $Comments = $rows['Comments'];
                                    $Referred_From = $rows['Referred_From'];
                                    
                                    echo $Laboratory_Number;
                                    $Doctor_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Doctor_collected'"))['Employee_Name'];

                                    
                                }

                        $select_biopsy_data = mysqli_query($conn, "SELECT specimen_results_Employee_ID, TimeCollected, time_received, Employee_ID_receive, sample_quality FROM tbl_specimen_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID'");
                            while($data = mysqli_fetch_array($select_biopsy_data)){
                                $specimen_results_Employee_ID = $data['specimen_results_Employee_ID'];
                                $TimeCollected = $data['TimeCollected'];
                                $time_received = $data['time_received'];
                                $Employee_ID_receive = $data['Employee_ID_receive'];
                                $sample_quality = $data['sample_quality'];

                                
                                if($sample_quality == 'Suitable'){
                                    $quality = 'Satisfactory';
                                }else{
                                    $quality = 'Unsatisfactory';
                                }

                            $Employee_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$specimen_results_Employee_ID'"))['Employee_Name'];
                            $Employee_received_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID_receive'"))['Employee_Name'];

                            }
                  }
        ?>
    </table>
  
    <!--div for adding clinical information -->
    <form action="" id="clinical">
        <table class="table table-bordered" >

        <!-- <caption style="font-size: 16px;"><b>BIOPSY/HISTOLOGICAL EXAMINATION REQUESTING FORM</b></caption> -->
        
                <tbody >
                    <tr>
                        <th>PRIORITY:</th>
                        <th colspan='2' style='text-align: left;'>
                        
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Urgent' <?php if($Priority2 == "Urgent") { echo "checked"; } ?> >&nbsp;Urgent &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name='Priority' id='Priority' onchange='update_save_biopsy()' value='Routine' <?php if($Priority2 == "Routine") { echo "checked"; } ?> >&nbsp;Routine
                        </th>
                        <th>Biopsy/Autospy</th>
                        <th><input name="aortic" class="form_group" id="autospy" placeholder='Biopsy/Autospy'readonly='readonly' type="text" class="inp"  value="<?php echo $autospy; ?>"></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th>ADDRESS</th>
                        <th colspan='2' style='text-align: center;'>PLACE OF BIRTH</th>
                        <th colspan='2' style='text-align: center;'>PRESENT RESIDENCE</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Region</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_region"readonly='readonly'  placeholder='Birth Region'  value="<?php echo $birth_region; ?>" type="text" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs" type="text" class="inp" value="<?php echo $Region; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>District</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_district"readonly='readonly'  placeholder='Birth District' type="text"  value="<?php echo $birth_district; ?>" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs" type="text" class="inp" value="<?php echo $District; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Village/Town</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_village" readonly='readonly' placeholder='Birth Village'  value="<?php echo $birth_village; ?>" type="text" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs"readonly='readonly' placeholder='Village' type="text" class="inp" value="<?php echo $village; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Year Resided</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $resident_year; ?>"></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $birth_year; ?>"></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 18px; text-align: center;'>
                        <th colspan='6' style='text-align: center;'>LABORATORY RESULT TEAM</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Collected By</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $Doctor_collected_name; ?>"></th>
                        <th>Date & Time</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $Requested_Datetime; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Submitted By</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                        <th>Date & Time</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $TimeCollected; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Received By</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $Employee_received_name; ?>"></th>
                        <th>Date & Time</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $time_received; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Laboratory No:</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="Laboratory_Number"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='Laboratory No' type="text" class="inp" value="<?php echo $Laboratory_Number; ?>"></th>
                        <th>Code No:</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year" readonly='readonly' placeholder='Code No' type="text" class="inp" value="<?php echo $Biopsy_ID; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>New Case</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="New_Case"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='New Case' type="text" class="inp" value="<?php echo $New_Case; ?>"></th>
                        <th>Referred From:</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="Referred_From"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='Referred From' type="text" class="inp" value="<?php echo $Referred_From; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Site of Biopsy</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="Site_Biopsy"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='Site of Biopsy' type="text" class="inp" value="<?php echo $Site_Biopsy; ?>"></th>
                        <th>Previous Laboratory No:</th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="Previous_Laboratory"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='Previous Laboratory No' type="text" class="inp" value="<?php echo $Previous_Laboratory; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Duration of Condition</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="Duration_Condition"  onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' placeholder='Duration of Condition' type="text" class="inp" value="<?php echo $Duration_Condition; ?>"></th>
                        <th>SPECIMEN QUALITY</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year" readonly='readonly' placeholder='Year Resided' type="text" class="inp" value="<?php echo $quality; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Relevant Clinical Data</th>
                        <th colspan='5'>
                            <textarea style="resize:none;padding-left:5px;" required="required" onkeyup='update_results_biopsy(<?= $Biopsy_ID ?>)' id="relevant_clinical_data"  name="relevant_clinical_data"><?php echo $relevant_clinical_data; ?></textarea>
                        </th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Comments</th>
                        <th colspan='5'>
                            <textarea style="resize:none;padding-left:5px;" required="required" onkeyup="update_results_biopsy(<?= $Biopsy_ID ?>)" id="Comments"  name="Comments"><?= $Comments; ?></textarea>
                        </th>
                    </tr>
                </tbody>
        </table>
        <input type="text" name="Biopsy_ID" id="Biopsy_ID" class="art-button-green" style='display: none;' value='<?php echo $Biopsy_ID ?>'>
        <input type="button" id="clinical_btn" style="border-radius:0px" value="SAVE RESULTS" class="btn art-button pull-right" onclick='save_biopsy_results(<?= $Biopsy_ID; ?>,<?= $Employee_ID ?>)'>
    </form>


    

<!--div for adding long axis view-->
    <div class="table-responsive" style = "overflow-x: hidden;" id="long">
    <form action="">
        <table class="table table-bordered" id="table">
        

    </fieldset>

    <div id="echorcadiorgram_records"></div>
    <div id="echorcadiorgram_records_paediatric"></div>