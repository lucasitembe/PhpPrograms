<?php
@session_start();
include("./includes/connection.php");
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age']; 
@$Hospital_Ward_ID =$_POST['Hospital_Ward_ID']; 

//convert range date into timestring 
$str_fromDate = strtotime($fromDate);
$str_toDate = strtotime($toDate);

$time_interval = $str_toDate - $str_fromDate;

$time_back = $str_fromDate - $time_interval;

$start_date = date("Y-m-d h:i", $time_back);
$end_date = date("Y-m-d h:i", $str_fromDate);




$Employee_ID=$_SESSION['userinfo']['Employee_ID'];


    $total_male_yesterday = 0;
    $total_female_yesterday = 0;
    $yesterday_total = 0;
    $total_female = 0;
    $total_male = 0;
    $add_for_totol = 0;
    $female_count=0;
    $male_count=0;
    $total_yesteday_admission=0;
    $total_yesteday_transferOut_female=0;
    $total_yesteday_transferOut_male=0;
    $total_yesteday_transferOut=0;
    $total_yesteday_discharge_live_female= 0;
    $total_yesteday_discharge_live_male=0;
    $total_yesteday_discharge_live=0;
    $total_yesteday_discharge_death_female=0;
    $total_yesteday_discharge_death_male=0;
    $total_yesteday_discharge_death=0;
    $total_yesteday_abscondee_male=0;
    $total_yesteday_abscondee_female=0;
    $total_yesteday_abscondee=0;
    $total_admission=0;
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
    $actual_male = 0;
    $actual_female = 0;
    $actual_total =  0;
    $add_total_male = 0;
    $add_total_female = 0;
    $Total_add = 0;
    $sub_male =0;
    $sub_female = 0;
    $sub_total =0;
    $now_male = 0;
        $now_female= 0;
        $now_total=0;
    
    ///==============================Yesterday query result==============================================//
    //die("SELECT a.Registration_ID, Gender FROM  tbl_admission a, tbl_patient_registration pr where a.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$Hospital_Ward_ID' and Admission_Date_Time BETWEEN '0000/00/00 00:00' and '$end_date' or Admission_Status='Admitted'");
    $select_yesterday_admission = mysqli_query($conn, "SELECT a.Registration_ID, Gender FROM  tbl_admission a, tbl_patient_registration pr where a.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$Hospital_Ward_ID' and Admission_Date_Time BETWEEN '0000/00/00 00:00' and '$end_date' AND Admission_Status='Admitted'") or die(mysqli_error($conn));

        $male_count=0;
        $female_count=0;

        if((mysqli_num_rows($select_yesterday_admission))>0){
            while($patient_row =mysqli_fetch_assoc($select_yesterday_admission)){
                $Gender = $patient_row['Gender'];
                $Registration_ID = $patient_row['Registration_ID'];

                if($Gender =='Female'){
                    $female_count++;
                }else{
                    $male_count++;
                }
            }
        }
        $total_yesterday_female_admission +=$female_count;
        $total_yesterday_male_admission += $male_count;
        $total_yesterday_admission =$total_yesterday_female_admission +$total_yesterday_male_admission;

    // $Transfer_yesterday_in_result=mysqli_query($conn,"SELECT tin.in_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tin.transfer_in_date,ad.Discharge_Date_Time,tin.in_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID  AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tin.Admision_ID AND transfer_status='received' AND tin.in_ward_id='$Hospital_Ward_ID' AND $Hospital_Ward_ID NOT IN(SELECT Hospital_Ward_ID FROM tbl_excepted_ward_InDIHSreports)  AND tin.transfer_in_date BETWEEN '$start_date' and '$end_date' ORDER BY ad.Admision_ID"); 
    //     $male_count_in = 0;
    //     $female_count_in=0;
    // if((mysqli_num_rows($Transfer_yesterday_in_result))>0){
    //     while($in_rows = mysqli_fetch_assoc($Transfer_yesterday_in_result)){
    //             $Registration_ID =$in_rows['Registration_ID'];
    //             $Gender = $in_rows['Gender'];

    //             if($Gender=='Female'){
    //                 $female_count_in++;
    //             }else{
    //                 $male_count_in++;
    //             }
    //     }
    // }
    // $total_yesterday_transferIn_female +=$female_count_in;
    // $total_yesterday_transferIn_male +=$male_count_in;
    // $total_yesterday_transferIn = $total_yesterday_transferIn_female + $total_yesterday_transferIn_male;

    //transfer_out
    // $Transfer_yesterday_out_result=mysqli_query($conn,"SELECT tou.out_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tou.transfer_out_date,ad.Discharge_Date_Time,tou.out_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tou.Admision_ID AND transfer_status='received'AND $Hospital_Ward_ID NOT IN(SELECT Hospital_Ward_ID FROM tbl_excepted_ward_InDIHSreports) AND tou.out_ward_id='$Hospital_Ward_ID' AND tou.transfer_out_date BETWEEN '$start_date' and '$end_date'  ORDER BY ad.Admision_ID");
    //             $male_count_out = 0;
    //             $female_count_out=0;
    //             $clickedbox="";
    //         if((mysqli_num_rows($Transfer_yesterday_out_result))>0){
    //             while($out_rows = mysqli_fetch_assoc($Transfer_yesterday_out_result)){
    //                     $Registration_ID =$out_rows['Registration_ID'];
    //                     $Gender = $out_rows['Gender'];

    //                     if($Gender=='Female'){
    //                         $female_count_out++;
    //                     }else{
    //                         $male_count_out++;
    //                     }
                        
    //             }
    //         }
    //         $total_yesterday_transferOut_female +=$female_count_out;
    //         $total_yesterday_transferOut_male +=$male_count_out;
    //         $total_yesterday_transferOut = $$total_yesterday_transferOut_male +$total_yesterday_transferOut_female;

    //discharge live
    // $discharge_live_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND  discharge_condition='alive' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$start_date' and '$end_date'  GROUP BY ad.Admision_ID ");

    //             $male_count_live = 0;
    //             $female_count_live=0;
                
    //         if((mysqli_num_rows($discharge_live_result))>0){
    //             while($live_rows = mysqli_fetch_assoc($discharge_live_result)){
    //                     $Registration_ID =$live_rows['Registration_ID'];
    //                     $Gender = $live_rows['Gender'];

    //                     if($Gender=='Female'){
    //                         $female_count_live++;
    //                     }else{
    //                         $male_count_live++;
    //                     }
                        
    //             }
    //         }
    //         $total_yesterday_discharge_live_female +=$female_count_live;
    //         $total_yesterday_discharge_live_male +=$male_count_live;
    //         $total_yesterday_discharge_live = $total_yesterday_discharge_live_female +$total_yesterday_discharge_live_male;

            // $discharge_yesterday_death_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND discharge_condition='dead' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$start_date' and '$end_date'  GROUP BY ad.Admision_ID ");

            //         $male_count_death = 0;
            //         $female_count_death=0;
                    
            //     if((mysqli_num_rows($discharge_yesterday_death_result))>0){
            //         while($death_rows = mysqli_fetch_assoc($discharge_yesterday_death_result)){
            //                 $Registration_ID =$death_rows['Registration_ID'];
            //                 $Gender = $death_rows['Gender'];

            //                 if($Gender=='Female'){
            //                     $female_count_death++;
            //                 }else{
            //                     $male_count_death++;
            //                 }
                            
            //         }
            //     }
            //     $total_yesterday_discharge_death_female +=$female_count_death;
            //     $total_yesterday_discharge_death_male +=$male_count_death;
            //     $total_yesterday_discharge_death = $total_yesterday_discharge_death_female +$total_yesterday_discharge_death_male;

                //abscond_result
                // $discharge_yesterday_Absconded_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID, discharge_condition FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID AND discharge_condition='absconde' AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$start_date' and '$end_date'  GROUP BY ad.Admision_ID ") or die(mysqli_error($conn));
                                           
                //                     $male_count_Absconded = 0;
                //                     $female_count_Absconded=0;
                                    
                //                 if((mysqli_num_rows($discharge_yesterday_Absconded_result))>0){
                //                     while($live_rows = mysqli_fetch_assoc($discharge_yesterday_Absconded_result)){
                //                             $Registration_ID =$live_rows['Registration_ID'];
                //                             $Gender = $live_rows['Gender'];

                //                             if($Gender=='Female'){
                //                                 $female_count_Absconded++;
                //                             }else{
                //                                 $male_count_Absconded++;
                //                             }
                                            
                //                     }
                //                 }
                //                 $total_yesterday_abscondee_male +=$female_count_Absconded;
                //                 $total_yesterday_abscondee_female +=$male_count_Absconded;
                //                 $total_yesterday_abscondee=$total_yesterday_abscondee_male + $total_yesterday_abscondee_female;

                    //=======calculation add for total============//
                    // $add_for_totol_male = $total_yesterday_male_admission +$total_yesterday_transferIn_male;
                    // $add_for_totol_female= $total_yesterday_female_admission +$total_yesterday_transferIn_female;
                    // $add_for_totol = $add_for_totol_female + $add_for_totol_male;
                    //===============for sub total =================//
                    // $for_sub_total_male = $total_yesterday_transferOut_male + $total_yesterday_discharge_live_male +$total_yesterday_discharge_death_male+$total_yesterday_abscondee_male;
                    // $for_sub_total_female = $total_yesterday_transferOut_female+ $total_yesterday_discharge_live_female +$total_yesterday_discharge_death_female +$total_yesterday_abscondee_female;

                    // $for_sub_total = $for_sub_total_male  +$for_sub_total_female;

                    //===============actual number of patient in ward==========================//
                    // $actual_male = $add_for_totol_male- $for_sub_total_male;
                    // $actual_female = $add_for_totol_female- $for_sub_total_female;
                    // $actual_total =  $add_for_totol -$for_sub_total;

                    $actual_male = $total_yesterday_male_admission;
                    $actual_female = $total_yesterday_female_admission;
                    $actual_total =  $actual_male + $actual_female;

                    // $actual_total =$total_yesterday_male_admission + $total_yesterday_female_admission;
    //==============================End of Yesterday query result========================================//
    //===============================Today query result ===============================================//
        $select_admission = mysqli_query($conn, "SELECT a.Registration_ID, Gender FROM  tbl_admission a, tbl_patient_registration pr where a.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$Hospital_Ward_ID'  and Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND  Admission_Status='Admitted' ") or die(mysqli_error($conn));

        $male_count=0;
        $female_count=0;

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
        $total_admission =$total_female_admission +$total_male_admission;

    $Transfer_in_result=mysqli_query($conn,"SELECT tin.in_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tin.transfer_in_date,ad.Discharge_Date_Time,tin.in_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID  AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tin.Admision_ID AND transfer_status='received' AND tin.in_ward_id='$Hospital_Ward_ID'  AND tin.transfer_in_date BETWEEN '$fromDate' and '$toDate' ORDER BY ad.Admision_ID"); 
        $male_count_in = 0;
        $female_count_in=0;
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
    $total_transferIn = $total_transferIn_female + $total_transferIn_male;

    //transfer_out
    $Transfer_out_result=mysqli_query($conn,"SELECT tou.out_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tou.transfer_out_date,ad.Discharge_Date_Time,tou.out_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tou.Admision_ID AND transfer_status='received' AND tou.out_ward_id='$Hospital_Ward_ID' AND tou.transfer_out_date BETWEEN '$fromDate' and '$toDate'  ORDER BY ad.Admision_ID");
                $male_count_out = 0;
                $female_count_out=0;
                $clickedbox="";
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
            $total_transferOut = $total_transferOut_male +$total_transferOut_female;

    //discharge live
    $discharge_live_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND  discharge_condition='alive' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Admision_ID ");

                $male_count_live = 0;
                $female_count_live=0;
                
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
            $total_discharge_live = $total_discharge_live_female +$total_discharge_live_male;

            $discharge_death_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID  AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND discharge_condition='dead' AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Admision_ID ");

                    $male_count_death = 0;
                    $female_count_death=0;
                    
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
                $total_discharge_death = $total_discharge_death_female +$total_discharge_death_male;

                //abscond_result
                $discharge_Absconded_result=mysqli_query($conn,"SELECT pr.Gender, pr.Registration_ID, discharge_condition FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID AND discharge_condition='absconde' AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate'  GROUP BY ad.Admision_ID ") or die(mysqli_error($conn));
                                           
                                    $male_count_Absconded = 0;
                                    $female_count_Absconded=0;
                                    
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
                                $total_abscondee=$total_abscondee_male + $total_abscondee_female;

                                $add_total_male= $actual_male+ $total_male_admission +$total_transferIn_male;
                                $add_total_female =$actual_female+ $total_female_admission+$total_transferIn_female;
                                $Total_add = $add_total_male +$add_total_female;
        //=========================================End of today result===================================//
        $sub_male = $total_abscondee_male + $total_discharge_death_male+$total_discharge_live_male +$total_transferOut_male;
        $sub_female = $total_abscondee_female + $total_discharge_death_female+$total_discharge_live_female+$total_transferOut_female;

        $sub_total = $sub_female + $sub_male;

        $now_male = $add_total_male -$sub_male ;
        $now_female= $add_total_male - $sub_female;
        $now_total=$Total_add - $sub_total;

       
?>
<table class="table">
              <tr>
                  <td>
                        <table class="table">
                        <thead>
                            <tr>
                                <th ></th>
                                <th class="vertical" rowspan="3">ADD Total</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Number of Patients <br/> at 8:00 am Yesteday</td>
                              
                                <td><?php echo $actual_male; ?></td>
                                <td><?php echo $actual_female; ?></td>
                                <td><?php echo $actual_total; ?></td>
                                
                            </tr>
                            <tr>
                                <td colspan="2" style="border: none;">Admission</td>
                                
                                <td><?php echo  $total_male_admission; ?></td>
                                <td><?php echo  $total_female_admission; ?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"admission\")'> $total_admission</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Transfer In</td>
                                
                                <td><?= $total_transferIn_male?></td>
                                <td><?= $total_transferIn_female?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"in\")'> $total_transferIn</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Total</td>
                                
                                <td><?php echo $add_total_male; ?></td>
                                <td><?php echo $add_total_female; ?></td>
                                <td><?php echo $Total_add; ?></td>
                                
                            </tr>
                        </tbody>
                        </table>
                  </td>
                  <td>
                  <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="vertical" rowspan="7">SUB for Total</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">New Total</td>                                
                                <td><?php echo $add_total_male; ?></td>
                                <td><?php echo $add_total_female; ?></td>
                                <td><?php echo $Total_add; ?></td>
                                
                            </tr>
                            <tr>
                                <td colspan="2">Discharges Live</td>
                                
                                <td><?php echo $total_discharge_live_male; ?></td>
                                <td><?php echo $total_discharge_live_female; ?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"alive\")'> $total_discharge_live</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Transfer Out</td>
                                
                                <td><?php echo $total_transferOut_male; ?></td>
                                <td><?php echo $total_transferOut_female; ?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"out\")'> $total_transferOut</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Death</td>
                                
                                <td><?php echo $total_discharge_death_male;?></td>
                                <td><?php echo $total_discharge_death_female;?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"dead\")'> $total_discharge_death</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Absconde</td>
                                
                                <td><?php echo $total_abscondee_male; ?></td>
                                <td><?php echo $total_abscondee_female; ?></td>
                                <?php echo " <td id='male_list' onclick='open_summary_details($Hospital_Ward_ID,\"$Hospital_Ward_Name\", \"Absconded\")'> $total_abscondee</td>";?>
                            </tr>
                            <tr>
                                <td colspan="2">Total</td>
                                
                                <td><?php echo $now_male; ?></td>
                                <td><?php echo $now_female; ?></td>
                                <td><?php echo $now_total; ?></td>
                            </tr>
                        </tbody>
                        </table>
                  </td>
                  </td>
              </tr>
                
          </table>