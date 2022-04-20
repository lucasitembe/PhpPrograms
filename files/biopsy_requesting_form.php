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
	
    // $consultation_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND Patient_Payment_Item_List_ID IS NOT NULL AND Process_Status = 'served' ORDER BY consultation_ID DESC LIMIT 1"))['consultation_ID'];
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

                  $Biopsy_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Biopsy_ID FROM tbl_histological_examination WHERE consultation_ID = '$consultation_ID' AND Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID' AND DATE(Requested_Datetime) = CURDATE() AND Biposy_Status='pending'"))['Biopsy_ID'];

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
                        <th><input name="aortic" class="form_group" id="autospy" placeholder='Biopsy/Autospy' onkeyup='update_save_biopsy()' type="text" class="inp"  value="<?php echo $autospy; ?>"></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th colspan='2'>ADDRESS</th>
                        <th colspan='2' style='text-align: center;'>PLACE OF BIRTH</th>
                        <th colspan='2' style='text-align: center;'>PRESENT RESIDENCE</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th  colspan='2'>Region</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_region" onkeyup='update_save_biopsy()'  placeholder='Birth Region'  value="<?php echo $birth_region; ?>" type="text" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs" type="text" class="inp" value="<?php echo $Region; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th  colspan='2'>District</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_district" onkeyup='update_save_biopsy()'  placeholder='Birth District' type="text"  value="<?php echo $birth_district; ?>" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs" type="text" class="inp" value="<?php echo $District; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th  colspan='2'>Village/Town</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_village"  onkeyup='update_save_biopsy()' placeholder='Birth Village'  value="<?php echo $birth_village; ?>" type="text" class="inp" ></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="adult_inputs" onkeyup='update_save_biopsy()' placeholder='Village' type="text" class="inp" value="<?php echo $village; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th  colspan='2'>Year Resided</th>
                        <th colspan='2'><input name="aortic" class="form_group" id="birth_year"  onkeyup='update_save_biopsy()' placeholder='Year Resided' type="text" class="inp" value="<?php echo $resident_year; ?>"></th>
                        <th colspan='2'><input name="aortic1" class="form_group" id="resident_year"  onkeyup='update_save_biopsy()' placeholder='Year Resided' type="text" class="inp" value="<?php echo $birth_year; ?>"></th>
                    </tr>
                    <!-- <tr>
                        <th>SELECT TEST</th>
                        <th colspan='4'>
                            <textarea id='Postoperative_Diagnosis546654' name='Postoperative_Diagnosis546654' style='width: 100%; height: 50px;' readonly='readonly'><?php  ?></textarea>
                        </th>
                        <th>
                            <input type="button" value="SELECT TEST" class="art-button-green" onclick="Add_Postoperative_Diagnosis237567()" style="margin-left:50px;" >
                        </th>
                        Open_Labodatory_Dialogy
                    </tr> -->
                </tbody>
        </table>
        <input type="button" name="Laboratory_Button" id="Laboratory_Button" class="art-button-green" value="SELECT BIOPSY" onclick="Open_Labodatory_Dialogy2()">
        <input type="text" name="Biopsy_ID" id="Biopsy_ID" class="art-button-green" style='display: none;' value='<?php echo $Biopsy_ID ?>'>

            <input type="button" id="clinical_btn" style="border-radius:0px" value="SAVE DATA" class="btn art-button pull-right" onclick='save_biopsy_info(<?= $Biopsy_ID; ?>)'>
        
    </form>


    

<!--div for adding long axis view-->
    <div class="table-responsive" style = "overflow-x: hidden;" id="long">
    <form action="">
        <table class="table table-bordered" id="table">
        

    </fieldset>

    <div id="echorcadiorgram_records"></div>
    <div id="echorcadiorgram_records_paediatric"></div>