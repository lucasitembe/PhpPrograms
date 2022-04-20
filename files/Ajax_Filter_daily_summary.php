
<style type="text/css">
    .patientList td,th{
        text-align: center;
		font-size:15px;
		background-color:white;
    }
</style>
<?php
@session_start();
include("./includes/connection.php"); 
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age']; 

$male_count1 = 0;
$female_count1 =0;


$Employee_ID=$_SESSION['userinfo']['Employee_ID'];


$select_ward = mysqli_query($conn, "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE ward_type='ordinary_ward' ORDER BY Hospital_Ward_ID") or die(mysqli_error($conn));


?>
            <table class="table table-condensed">
                <thead class="fixTableHead2">
                    <tr>
                        <th style='width:15%; height:50;' rowspan="3">Units</th>
                        <th rowspan="2" colspan="2" style='width:10%; height:50;'>Admission</th>
                        <th colspan="4" style='width:25%; height:50;'>Transfers</th>
                        <th colspan="4" style='width:25%; height:50;'>Discharges</th>
                        <th rowspan="2" colspan="2" style='width:10%; height:50;'>Abscondee</th>
                        <th rowspan="2" colspan="3" style='width:15%; height:50;'>No of patient Days</th>
                    </tr>
                    <tr>
                        <th colspan="2">In</th>
                        <th colspan="2">Out</th>
                        <th colspan="2">Live</th>
                        <th colspan="2">Deaths</th>
                    </tr>
                    <tr>                        
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>T</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_female_admission =0;
                        $total_male_admission=0;
                        $total_admission=0;
                        $total_transferIn_female=0;
                        $total_transferIn_male=0;
                        $total_transferIN=0;
                        $total_transferOut_female=0;
                        $total_transferOut_male=0;
                        $total_transferOut=0;
                        $total_discharge_live_female= 0;
                        $total_discharge_live_male=0;
                        $total_discharge_live=0;
                        $total_discharge_death_female=0;
                        $total_discharge_death_male=0;
                        $total_discharge_death=0;
                        $total_abscondee_male=0;
                        $total_abscondee_female=0;
                        $total_abscondee=0;
                        $add_total_male = 0;
                                $add_total_female = 0;
                                $Total_add = 0;
                                $sub_male =0;
                                $sub_female = 0;
                                $sub_total =0;
                                $now_male = 0;
                                $now_female= 0;
                                $now_total=0;
                                $now_male1=0;
                                $now_male_1 =0;
                                $now_female_1 =0;
                                $now_total_1 =0;
                        
                        if((mysqli_num_rows($select_ward))>0){
                            while($row= mysqli_fetch_assoc($select_ward)){
                                
                                $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
                                $Hospital_Ward_Name = $row['Hospital_Ward_Name'];

                                echo "<tr>
                                        <td>$Hospital_Ward_Name</td>
                                        ";
                                        //die("SELECT a.Registration_ID, Gender FROM  tbl_admission a, tbl_patient_registration pr where a.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$Hospital_Ward_ID' and Admission_Date_Time BETWEEN '0000/00/00 00:00' and '$toDate' ");
                                        //simple modification done here
                                $select_admission = mysqli_query($conn, "SELECT a.Registration_ID, Gender FROM  tbl_admission a, tbl_patient_registration pr where a.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$Hospital_Ward_ID' and Admission_Date_Time BETWEEN '0000/00/00 00:00' and '$toDate' AND Admission_Status='Admitted' ") or die(mysqli_error($conn));

                                $male_count=0;
                                $female_count=0;
                                $total_female_admission_1=0;
                                $total_male_admission_1=0;

                                if((mysqli_num_rows($select_admission))>0){
                                    while($patient_row =mysqli_fetch_assoc($select_admission)){
                                        $Gender = $patient_row['Gender'];
                                        $Registration_ID = $patient_row['Registration_ID'];

                                        if($Gender =='Female'){
                                            $female_count++;
                                        }else{
                                            $male_count++;
                                        }
                                    }
                                }
                                $total_female_admission +=$female_count;
                                $total_male_admission += $male_count;
                                $total_female_admission_1 +=$female_count;
                                $total_male_admission_1 += $male_count;
                                $total_admission =$total_female_admission +$total_male_admission;
                                echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\",\"admission\")'>$male_count</td>
                                        <td id='female_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"admission\")'>$female_count</td>
                                        ";
                                $Transfer_in_result=mysqli_query($conn,"SELECT tin.in_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tin.transfer_in_date,ad.Discharge_Date_Time,tin.in_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID  AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tin.Admision_ID AND transfer_status='received' AND tin.in_ward_id='$Hospital_Ward_ID' AND $Hospital_Ward_ID NOT IN(SELECT Hospital_Ward_ID FROM tbl_excepted_ward_InDIHSreports)  AND tin.transfer_in_date BETWEEN '$fromDate' and '$toDate' ORDER BY ad.Registration_ID"); 
                                    $male_count_in = 0;
                                    $female_count_in=0;
                                    $total_transferIn_female_1 =0;
                                $total_transferIn_male_1 =0;
                                if((mysqli_num_rows($Transfer_in_result))>0){
                                    while($in_rows = mysqli_fetch_assoc($Transfer_in_result)){
                                            $Registration_ID =$in_rows['Registration_ID'];
                                            $Gender = $in_rows['Gender'];

                                            if($Gender=='Female'){
                                                $female_count_in++;
                                            }else{
                                                $male_count_in++;
                                            }
                                    }
                                }
                                $total_transferIn_female +=$female_count_in;
                                $total_transferIn_male +=$male_count_in;
                                $total_transferIn_female_1 +=$female_count_in;
                                $total_transferIn_male_1 +=$male_count_in;
                                $total_transferIn = $total_transferIn_female + $total_transferIn_male;
                                echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\",\"in\")'>$male_count_in</td>
                                        <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\",\"in\")'>$female_count_in</td>
                                        ";
                                
                                    $Transfer_out_result=mysqli_query($conn,"SELECT tou.out_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tou.transfer_out_date,ad.Discharge_Date_Time,tou.out_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tou.Admision_ID AND transfer_status='received' AND tou.out_ward_id='$Hospital_Ward_ID' AND $Hospital_Ward_ID NOT IN(SELECT Hospital_Ward_ID FROM tbl_excepted_ward_InDIHSreports) AND tou.transfer_out_date BETWEEN '$fromDate' and '$toDate'  ORDER BY ad.Registration_ID");
                                        $male_count_out = 0;
                                        $female_count_out=0;
                                        $total_transferOut_female_1 =0;
                                    $total_transferOut_male_1 =0;
                                    if((mysqli_num_rows($Transfer_out_result))>0){
                                        while($out_rows = mysqli_fetch_assoc($Transfer_out_result)){
                                                $Registration_ID =$out_rows['Registration_ID'];
                                                $Gender = $out_rows['Gender'];
    
                                                if($Gender=='Female'){
                                                    $female_count_out++;
                                                }else{
                                                    $male_count_out++;
                                                }
                                                
                                        }
                                    }
                                    $total_transferOut_female +=$female_count_out;
                                    $total_transferOut_male +=$male_count_out;
                                    $total_transferOut_female_1 +=$female_count_out;
                                    $total_transferOut_male_1 +=$male_count_out;
                                    $total_transferOut = $$total_transferOut_male +$total_transferOut_female;
                                    echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"out\")'>$male_count_out</td>
                                            <td id='female_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"out\")'>$female_count_out</td>
                                            ";


                                            $discharge_live_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND  discharge_condition='alive' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Registration_ID ");

                                            $male_count_live = 0;
                                            $female_count_live=0;
                                            $total_discharge_live_female_1 =0;
                                            $total_discharge_live_male_1 =0;
                                        if((mysqli_num_rows($discharge_live_result))>0){
                                            while($live_rows = mysqli_fetch_assoc($discharge_live_result)){
                                                    $Registration_ID =$live_rows['Registration_ID'];
                                                    $Gender = $live_rows['Gender'];
        
                                                    if($Gender=='Female'){
                                                        $female_count_live++;
                                                    }else{
                                                        $male_count_live++;
                                                    }
                                                    
                                            }
                                        }
                                        $total_discharge_live_female +=$female_count_live;
                                        $total_discharge_live_male +=$male_count_live;
                                        $total_discharge_live_female_1 +=$female_count_live;
                                        $total_discharge_live_male_1 +=$male_count_live;
                                        $total_discharge_live = $total_discharge_live_female +$total_discharge_live_male;
                                        echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"alive\")'>$male_count_live</td>
                                                <td id='female_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"alive\")'>$female_count_live</td>
                                                ";
                                        
                                            $discharge_death_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND discharge_condition='dead' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Registration_ID ");

                                                $male_count_death = 0;
                                                $female_count_death=0;
                                                $total_discharge_death_female_1 =0;
                                                $total_discharge_death_male_1 =0;
                                            if((mysqli_num_rows($discharge_death_result))>0){
                                                while($death_rows = mysqli_fetch_assoc($discharge_death_result)){
                                                        $Registration_ID =$death_rows['Registration_ID'];
                                                        $Gender = $death_rows['Gender'];
            
                                                        if($Gender=='Female'){
                                                            $female_count_death++;
                                                        }else{
                                                            $male_count_death++;
                                                        }
                                                        
                                                }
                                            }
                                            $total_discharge_death_female +=$female_count_death;
                                            $total_discharge_death_male +=$male_count_death;
                                            $total_discharge_death_female_1 +=$female_count_death;
                                            $total_discharge_death_male_1 +=$male_count_death;
                                            $total_discharge_death = $total_discharge_death_female +$total_discharge_death_male;
                                    echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"dead\")'>$male_count_death</td>
                                    <td id='female_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"dead\")'>$female_count_death</td>
                                    ";
                                 

                            $discharge_Absconded_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID, discharge_condition FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID AND discharge_condition='absconde' AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Registration_ID ") or die(mysqli_error($conn));
                                           
                                    $male_count_Absconded = 0;
                                    $female_count_Absconded=0;
                                    $total_abscondee_male_1 =0;
                                    $total_abscondee_female_1 =0;
                                if((mysqli_num_rows($discharge_Absconded_result))>0){
                                    while($live_rows = mysqli_fetch_assoc($discharge_Absconded_result)){
                                            $Registration_ID =$live_rows['Registration_ID'];
                                            $Gender = $live_rows['Gender'];

                                            if($Gender=='Female'){
                                                $female_count_Absconded++;
                                            }else{
                                                $male_count_Absconded++;
                                            }
                                            
                                    }
                                }
                                $total_abscondee_male +=$female_count_Absconded;
                                $total_abscondee_female +=$male_count_Absconded;
                                $total_abscondee_male_1 +=$female_count_Absconded;
                                $total_abscondee_female_1 +=$male_count_Absconded;
                                $total_abscondee=$total_abscondee_male + $total_abscondee_female;
                                echo "<td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"Absconded\")'>$male_count_Absconded</td>
                                <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"Absconded\")'>$female_count_Absconded</td>
                                ";
                                
                                $add_total_male= $total_male_admission_1 +$total_transferIn_male_1;
                                $add_total_female =$total_female_admission_1+$total_transferIn_female_1;

                                $Total_add = $add_total_male +$add_total_female;

                                $sub_male = $total_abscondee_male_1 + $total_discharge_death_male_1+$total_discharge_live_male_1 +$total_transferOut_male_1;

                                $sub_female = $total_abscondee_female_1 + $total_discharge_death_female_1+$total_discharge_live_female_1+$total_transferOut_female_1;
                        
                                $sub_total = $sub_female + $sub_male;
                        
                                $now_male = $add_total_male -$sub_male ;
                                $now_female= $add_total_female - $sub_female;
                                $now_total=$Total_add - $sub_total;
                                $now_male_1 +=$now_male;
                                $now_female_1 +=$now_female;
                                $now_total_1 +=$now_total;
                                echo "<td>$now_male</td><td>$now_female</td><td>$now_total</td></tr>";

                                
                            }
                        }
                        
                     
            echo "<tr><th>SUB TOTAL</th>
                        <th>$total_male_admission</th>
                        <th>$total_female_admission</th>
                        <th>$total_transferIn_male</th>
                        <th>$total_transferIn_female</th>
                        <th>$total_transferOut_male</th>
                        <th>$total_transferOut_female</th>
                        <th>$total_discharge_live_male</th>
                        <th>$total_discharge_live_female</th>
                        <th>$total_discharge_death_male</th>
                        <th>$total_discharge_death_female</th>
                        <th>$total_abscondee_male</th>
                        <th>$total_abscondee_female</th>
                        <th>$now_male_1</th>
                        <th>$now_female_1</th>
                        <th>$now_total_1</th>
                    </tr>";
            echo "<tr><th>SUB TOTAL</th>
                        <th colspan='2'>$total_admission</th>
                        <th colspan='2'>$total_transferIn</th>
                        <th colspan='2'>$total_transferOut</th>
                        <th colspan='2'>$total_discharge_live</th>
                        <th colspan='2'>$total_discharge_death</th>
                        <th colspan='2'>$total_abscondee</th>
                        <th colspan='3'>$now_total_1</th>                       
                    </tr>";
                    ?>
                </tbody>
            </table>
          