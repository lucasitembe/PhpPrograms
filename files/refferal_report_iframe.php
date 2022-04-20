<?php
include("./includes/connection.php");

    if(isset($_POST['referred_from_hospital_id_for_male'])){
        $referred_from_hospital_id = $_POST['referred_from_hospital_id_for_male'];
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $hospital_name=$_POST['hospital_name'];
        $starting_date = date("Y-m-d",strtotime($start_date));
        $ending_date = date("Y-m-d",strtotime($end_date));
        //select male patients
        echo '
            <div class="row" style="padding:10px">
            <h3 style="text-align:center">Male Referred Patients List From '.$starting_date.' to  '.$ending_date.'</h3>
            </div>
            <table class="table table-bordered" style="background: #FFFFFF; overflow-y: scroll;overflow-x: height:auto;">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Patient Name</th>
                    <th>Checkin Date</th>
                    <th>Referral Reason </th>
                    <th style="text-align:center;">Attachment</th>
                </tr>
                </thead>
                 <tbody>';
                 $male_patients_sql=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_referred_patients rp,tbl_check_in ch WHERE pr.Registration_ID=rp.Registration_ID AND pr.Registration_ID=ch.Registration_ID AND rp.Check_In_ID=ch.Check_In_ID AND Referral_Hospital_ID='$referred_from_hospital_id' AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND pr.Gender = 'Male'") or die(mysqli_error($conn));
                 $count = 1;
                 while($patient_male_rows=mysqli_fetch_assoc($male_patients_sql)){
                   $patien_male_name=$patient_male_rows['Patient_Name'];
                   $Check_In_Date_And_Time = $patient_male_rows['Check_In_Date_And_Time'];
                   $referral_letter = $patient_male_rows['referral_letter'];
                   $referral_reason =$patient_male_rows['referral_reason'];

                  echo '
                   <tr>
                        <td>'.$count.'</td>
                        <td>'.$patien_male_name.'</td>
                        <td>'.$Check_In_Date_And_Time.'</td>
                        <td>'.$referral_reason.'</td>
                        <td>';
                   echo "<a href='excelfiles/$referral_letter' class='btn btn-info btn-sm' style='width:100%;' rel='gallery' target='_blank'  title='Refferal Latter attached'> <span>Letter</span><i class='fa fa-cog fa-spin fa-1x fa-fw' aria-hidden='true'></i>
                   </a>";
                        
                  echo'</td> 
                   </tr>
                   ';
                   $count++;
                    }
                 echo '</tbody></table>';
    }


    if(isset($_POST['referred_from_hospital_id_for_female'])){
        $referred_from_hospital_id = $_POST['referred_from_hospital_id_for_female'];
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $hospital_name=$_POST['hospital_name'];
        $starting_date = date("Y-m-d",strtotime($start_date));
        $ending_date = date("Y-m-d",strtotime($end_date));
        //select male patients
        echo '
        <div class="row" style="padding:10px">
            <h3 style="text-align:center">Male Referred Patients List From '.$starting_date.' to  '.$ending_date.'</h3>
       </div>
            <table class="table table-bordered" style="background: #FFFFFF">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Patient Name</th>
                    <th>Checkin Data</th>
                    <th>Referral Reason </th>
                    <th width="10%" style="text-align:center;">Attachment</th>
                </tr>
                </thead>
                 <tbody>';
                 $male_patients_sql=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_referred_patients rp,tbl_check_in ch WHERE pr.Registration_ID=rp.Registration_ID AND pr.Registration_ID=ch.Registration_ID AND rp.Check_In_ID=ch.Check_In_ID AND Referral_Hospital_ID='$referred_from_hospital_id' AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND pr.Gender = 'Female'") or die(mysqli_error($conn));
                    $count = 1;
                 while($patient_male_rows=mysqli_fetch_assoc($male_patients_sql)){
                   $patien_female_name=$patient_male_rows['Patient_Name'];
                   $Check_In_Date_And_Time = $patient_male_rows['Check_In_Date_And_Time'];
                   $referral_letter = $patient_male_rows['referral_letter'];
                   $referral_reason =$patient_male_rows['referral_reason'];
                  
                  echo '
                   <tr> 
                        <td>'.$count.'</td>
                        <td>'.$patien_female_name.'</td>
                        <td>'.$Check_In_Date_And_Time.'</td>
                        <td>'.$referral_reason.'</td>
                        <td>';
                   echo "<a href='excelfiles/$referral_letter' class='btn btn-info btn-sm' style='width:100%;' rel='gallery' target='_blank'  title='Refferal Latter attached'><span>Letter</span> <i class='fa fa-cog fa-spin fa-1x fa-fw' aria-hidden='true'></a>";
                        
                  echo'</td> </tr>
                   ';
                   $count++;
                    }
                 echo '</tbody></table>';
    }

?>