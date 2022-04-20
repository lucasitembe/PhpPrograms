<?php
include("./includes/connection.php");
include("dhis2_functions.php");
if(isset($_POST['start_date'])){
   $start_date=$_POST['start_date'];
   $end_date=$_POST['end_date'];
   if(isset($_POST['agetype'])){
     $agetype = $_POST['agetype'];
   }
   
   $filter_gender=$_POST['filter_gender'];
    
   $filter_age = $_POST['filter_age'];
   //first select all ward
   $Total_abscondee=0;
   $ward=mysqli_query($conn,"SELECT * FROM tbl_hospital_ward hw , tbl_ward_rooms wr WHERE ward_type='ordinary_ward' AND hw.Hospital_Ward_ID=wr.ward_id  GROUP BY Hospital_Ward_ID") or die(mysqli_error($conn));
   if(mysqli_num_rows($ward)>0){
       $count=1;
       while($ward_rows=mysqli_fetch_assoc($ward)){
          $ward_name=$ward_rows['Hospital_Ward_Name'];
          $ward_id=$ward_rows['Hospital_Ward_ID'];
          
    //room number
          //  Check availability of bed 
          // $room_number=mysqli_query($conn,"SELECT sum(no_of_beds) as total FROM tbl_ward_rooms  wr, tbl_beds b WHERE ward_id='$ward_id' AND wr.ward_room_id=wr.ward_room_id") or die(mysqli_error($conn));
          // 
          $room_number=mysqli_query($conn,"SELECT sum(no_of_beds) as total FROM tbl_ward_rooms   WHERE ward_id='$ward_id' ") or die(mysqli_error($conn));
          if(mysqli_num_rows($room_number)>0){
              while($room_number=mysqli_fetch_assoc($room_number)){
                $no_of_beds=$room_number['total'];
                if($no_of_beds==NULL){
                    $no_of_beds=0;
                }
              }
        }
    
    //number of admission
    $admission=mysqli_query($conn,"SELECT count(Admision_ID) as count_admission FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> '' AND Admission_Date_Time BETWEEN  '$start_date' AND '$end_date' $filter_gender $filter_age") or die(mysqli_error($conn));
    while($rws = mysqli_fetch_assoc($admission)){
      $total_admin = $rws['count_admission'];
    }   

        $total_admission=mysqli_query($conn,"SELECT Discharge_Reason_ID, Hospital_Ward_ID, count(Admision_ID) as count_admission, TIMESTAMPDIFF(DAY,'$start_date','$end_date') as total_Days FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> ''  AND Admission_Status = 'Admitted' AND  Admission_Date_Time <= DATE('$end_date')  AND Admision_ID NOT IN (SELECT Admision_ID FROM tbl_transfer_out_in) $filter_gender $filter_age") or die(mysqli_error($conn));
        if(mysqli_num_rows($total_admission)>0){
            while($total_admission_number=mysqli_fetch_assoc($total_admission)){
              $no_of_admision=$total_admission_number['count_admission'];
              $discharge_id=$total_admission_number['Discharge_Reason_ID'];
              $total_Days=$total_admission_number['total_Days'];
              $Hospital_Ward_ID=$total_admission_number['Hospital_Ward_ID'];

              
              $date_between= dateRange( $start_date, $end_date) ;
              $OBD = 0;
              for($i=0; $i<=sizeof($date_between); $i++){
                  $date_between[$i]; 
                 //test new idea

                // ===ILIKUWEPO ==== $dataofindex_i=mysqli_query($conn,"SELECT  count(Admision_ID) as count_admission FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> ''  AND Admission_Status='Admitted' AND  Admission_Date_Time <= '$date_between[$i]' $filter_gender $filter_age") or die(mysqli_error($conn));

                 $dataofindex_i=mysqli_query($conn,"SELECT  count(Admision_ID) as count_admission FROM tbl_admission  admi, tbl_patient_registration  reg_table WHERE  admi.Registration_ID=reg_table.Registration_ID AND Hospital_Ward_ID='$ward_id' AND ward_room_id <> ''  AND Admission_Status='Admitted' AND  Admission_Date_Time <= '$date_between[$i]' AND Admision_ID NOT IN (SELECT Admision_ID FROM tbl_transfer_out_in) $filter_gender $filter_age") or die(mysqli_error($conn));

                  
                  if(mysqli_num_rows($dataofindex_i)>0){
                    while($btn_rw=mysqli_fetch_assoc($dataofindex_i)){
                      $admitted_between=$btn_rw['count_admission'];
                      
                    }
                  }
                  // selected pt admission transfered
                  $selecttransfer = mysqli_query($conn, "SELECT DISTINCT  toi.Admision_ID, count(toi.Admision_ID) as count_admission FROM tbl_transfer_out_in toi , tbl_admission ad, tbl_patient_registration reg_table WHERE ad.Registration_ID =reg_table.Registration_ID AND  transfer_status='received' AND ward_room_id <> ''  AND Admission_Status='Admitted' AND toi.Admision_ID=ad.Admision_ID AND out_ward_id='$ward_id' AND ad.Admission_Date_Time <= '$date_between[$i]' $filter_age  $filter_gender GROUP BY  toi.Admision_ID ORDER BY transfer_id ASC ") or die(mysqli_error($conn));
                  if(mysqli_num_rows($selecttransfer)>0){
                    while($rws = mysqli_fetch_assoc($selecttransfer)){
                      $admitted_between1=$btn_rw['count_admission'];
                    }
                  }else{
                    $admitted_between1=0;
                  }

                  //check if patient is discharged and within time filted
                  $discharged_btn_i = 0;
                  $dataofindex_i_discharging=mysqli_query($conn,"SELECT  count(Admision_ID) as count_discharge_between_interval, TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_time) as total_Days FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> ''  AND  Admission_Date_Time <= '$date_between[$i]' AND Discharge_Date_time BETWEEN '$date_between[$i]' AND '$end_date' $filter_gender $filter_age") or die(mysqli_error($conn));
                 
                  if(mysqli_num_rows($dataofindex_i_discharging)>0){
                    while($btn_rw=mysqli_fetch_assoc($dataofindex_i_discharging)){
                      $discharged_btn_i=$btn_rw['count_discharge_between_interval' ];
                      
                    }
                  }
                  //end within time
                  
                  //check if transfered in
                  $ward_transfer_in_patient =0;
                  $admitted_transfed_in=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_in FROM tbl_transfer_out_in tin, tbl_admission ad, tbl_patient_registration as reg_table   WHERE out_ward_id='$ward_id' AND ad.Admision_ID=tin.Admision_ID AND  ad.Registration_ID=reg_table.Registration_ID AND transfer_status='received' AND  Admission_Date_Time <= '$date_between[$i]'  AND transfer_in_date BETWEEN DATE('$end_date') AND DATE('$date_between[$i]')  $filter_gender $filter_age") or die(mysqli_error($conn));
                  if(mysqli_num_rows($admitted_transfed_in)>0){
                    while($admitted_transfed_in_patient=mysqli_fetch_assoc($admitted_transfed_in)){
                      $ward_transfer_in_patient=$admitted_transfed_in_patient['count_transfer_in'];
                    }
                  }
                  //end transfered in
                  
                  //check if transfer out
                  $ward_transfer_out_patient=0;
               
                  $transfer_out=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_out FROM tbl_transfer_out_in tin, tbl_admission ad, tbl_patient_registration as reg_table   WHERE in_ward_id='$ward_id' AND ad.Admision_ID=tin.Admision_ID AND  ad.Registration_ID=reg_table.Registration_ID AND transfer_status IN ('received, pending') AND  Admission_Date_Time <= '$date_between[$i]'  AND transfer_out_date BETWEEN DATE('$date_between[$i]')  AND DATE('$end_date')  $filter_gender $filter_age") or die(mysqli_error($conn));
                  if(mysqli_num_rows($transfer_out)>0){
                    while($transfer_out_patient=mysqli_fetch_assoc($transfer_out)){
                      $ward_transfer_out_patient=$transfer_out_patient['count_transfer_out'];
                    }
                  }
                  //end transfer out.
                  
                  // $OBD += ($admitted_between +$discharged_btn_i+$ward_transfer_in_patient+ $ward_transfer_out_patient);
                  $OBD += ($admitted_between +$admitted_between1 +$ward_transfer_in_patient-$discharged_btn_i- $ward_transfer_out_patient);
                  // exit();
              }
                //discharge live
                $no_discharged_alive=0;
                $discharging_reason=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.Discharge_condition='alive' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  DATE('$start_date') AND DATE('$end_date')  AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));
                if(mysqli_num_rows($discharging_reason)>0){
                  while($discharging=mysqli_fetch_assoc($discharging_reason)){
                    $no_discharged_alive=$discharging['count_condition'];
                  }
                }

                 //discharge dead
                $no_Discharged_dead=0;
                 $discharging_reason_dead=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_dead FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='dead' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  DATE('$start_date') AND DATE('$end_date')  AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));
                 if(mysqli_num_rows($discharging_reason_dead)>0){
                   while($discharging_dead=mysqli_fetch_assoc($discharging_reason_dead)){
                     $no_Discharged_dead=$discharging_dead['count_condition_dead'];
                   }
                 }


                 //patient abscondee
                 $no_Discharged_as_abscondee=0;
                 $discharged_absconded=mysqli_query($conn,"SELECT count(Admision_ID) as count_abscondee FROM tbl_admission as ad,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE ad.Discharge_Reason_ID=reason.Discharge_Reason_ID AND ad.Registration_ID=reg_table.Registration_ID AND reason.Discharge_Reason='Absconded' AND ad.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  DATE('$start_date') AND DATE('$end_date')  AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));

                 if(mysqli_num_rows($discharged_absconded)>0){
                   while($abscondee_rw=mysqli_fetch_assoc($discharged_absconded)){
                     $no_Discharged_as_abscondee=$abscondee_rw['count_abscondee'];
                   }
                 }

                 //transferd into the ward patient
                 $transfer_in=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_in FROM tbl_transfer_out_in WHERE out_ward_id='$ward_id' AND transfer_status='received' ") or die(mysqli_error($conn));
                 if(mysqli_num_rows($transfer_in)>0){
                   while($transfer_in_patient=mysqli_fetch_assoc($transfer_in)){
                     $ward_transfer_in_patients=$transfer_in_patient['count_transfer_in'];
                   }
                 }

                 //transferd out of the ward patient
                 $transfer_out=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_out FROM tbl_transfer_out_in WHERE in_ward_id='$ward_id' AND transfer_status='received'") or die(mysqli_error($conn));
                 if(mysqli_num_rows($transfer_out)>0){
                   while($transfer_out_patient=mysqli_fetch_assoc($transfer_out)){
                     $ward_transfer_out_patients=$transfer_out_patient['count_transfer_out'];
                   }
                 }
                 

                //percentage for death
                $total_discharge = $no_discharged_alive + $no_Discharged_dead +$no_Discharged_as_abscondee;
                if($total_discharge !=0){
                    $percente_of_dead = round((($no_Discharged_dead/$total_discharge)*100),3);
                }else{
                   $percente_of_dead=0;  
                }
                
               //Average daily inpatient census
               if($total_Days !=0){
                $AVGDIC = round(($OBD/$total_Days), 3);
               }
                //Average_admission
                $average_admission = $no_of_admision/$total_Days;
                $average_admission_in = round($average_admission,3);
                //Average Discharge
                $average_discharge = $total_discharge/$total_Days;
                if($total_Days !=0){
                $average_discharge_in = round(($total_discharge/$total_Days),3);
                }
                //Available bed days (ABD)
                $ABD = $no_of_beds*$total_Days;
                //end ABD
                //Inpatients/occupied Beds Days(OBD) Hii haifanyi kazi By Eng. Muga
                //$OBD = ($no_of_admision + $ward_transfer_in_patient) - ($total_discharge + $ward_transfer_out_patient);
                //Bed accupancy rate

                //Inpatients/occupied Beds Days(OBD) By Eng. Muga
                $bed_accupancy_rate=0;
                if($total_Days!=0&&$no_of_beds!=0){
                    $bed_accupancy_rate = round((($OBD/($ABD))*100),3);
                }
                
                //Average length stay in Hospital ALOS 

                $alos=0;
                if($total_discharge!=0){
                  $alos = round(($OBD/$total_discharge),3);  
                }
                
                //TOI
             //   $abd = $no_of_beds*$total_Days;
                $TOI=0;
                if($total_Days !=0){
                   $TOI = round((($ABD-$OBD)/$total_Days),3); 
                }
                //Turn over per Bed (TOB)
                $TOB=0;
                if($no_of_beds !=0){
                  $TOB = round(($total_discharge/$no_of_beds), 3);
                }
                //TOB
                $TOD=0;
                if($no_of_beds!=0){
                    $TOD =  round(($total_discharge/$no_of_beds),3);
                }
            }
        }
        //hii itakuja kuwekwa badae 
        // ."-->in".  ($no_of_admision + $ward_transfer_in_patients)
          echo "<tr>
                    <td style='text-align: center'>$count</td>
                    <td class='rows_list' style='text-align: center' onclick='open_ward_details($ward_id,\"$ward_name\")'>$ward_name</td>
                    <td class='rows_list' style='text-align: center' onclick='open_ward_details($ward_id,\"$ward_name\")'>".$no_of_beds."</td>
                    <td class='rows_list' style='text-align: center' onclick='open_admission_details($ward_id,\"$ward_name\")'>". $total_admin." </td>
                    <td class='rows_list' style='text-align: center' onclick='open_alive_dischargina_patient($ward_id,\"$ward_name\")'>".$no_discharged_alive."</td>
                    <td class='rows_list' style='text-align: center' onclick='open_dead_dischargina_patient($ward_id,\"$ward_name\")'>".$no_Discharged_dead."</td>
                    <td class='rows_list' style='text-align: center' onclick='open_abscond_discharged_patient($ward_id,\"$ward_name\")'>".$no_Discharged_as_abscondee."</td>
                    <td style='text-align: center'>".$percente_of_dead."</td>
                    <td style='text-align: center'>".$average_admission_in."</td>
                    <td style='text-align: center'>".$average_discharge_in."</td>
                    <td  style='text-align: center' >".$OBD."</td>
                    <td style='text-align: center'>".$AVGDIC."</td>
                    <td style='text-align: center'>".$bed_accupancy_rate."</td>
                    <td style='text-align: center'>".$alos."</td>
                    <td style='text-align: center'>".$TOI."</td>
                    <td style='text-align: center'>".$TOB."</td>
                </tr>";
          $count++;
          $total_hospital_admision +=$total_admin;
          $Total_abscondee += $no_Discharged_as_abscondee; 
          $total_bed +=$no_of_beds;
          $total_obd += $OBD;
          $total_bed_accupancy_rate += $bed_accupancy_rate;
          $total_hospital_average += $AVGDIC;
          $total_hospital_discharge += $average_discharge_in;
          $total_hospital_avg_admision += $average_admission_in;
          $total_avg_stayin_hospital +=$alos;
          $total_TOB +=$TOB; 
          $total_TOI += $TOI;
          $total_hospital_discharge_live += $no_discharged_alive;
          $total_hospital_no_Discharged_dead += $no_Discharged_dead;
       }
       $count_ward_no = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT(Hospital_Ward_ID)) as total_hospital_ward FROM tbl_hospital_ward hw , tbl_ward_rooms wr WHERE ward_type='ordinary_ward' AND hw.Hospital_Ward_ID=wr.ward_id "))['total_hospital_ward'];
        echo "<tr style='background: #dedede;'>
                <td colspan='2'><b>Total  $count_ward_no</b></td>
                <td style='text-align: center'>$total_bed</td>
                <td style='text-align: center'>$total_hospital_admision</td>
                <td style='text-align: center'>$total_hospital_discharge_live</td>
                <td style='text-align: center'>$total_hospital_no_Discharged_dead</td>
                <td style='text-align: center'>$Total_abscondee</td>
                <td style='text-align: center'></td>
                <td style='text-align: center'>$total_hospital_avg_admision</td>
                <td style='text-align: center'>$total_hospital_discharge</td>
                <td style='text-align: center'>$total_obd</td>
                <td style='text-align: center'>$total_hospital_average</td>
                <td style='text-align: center'>$total_bed_accupancy_rate</td>
                <td style='text-align: center'>$total_avg_stayin_hospital </td>
                <td style='text-align: center'>$total_TOI</td>
                <td style='text-align: center'>$total_TOB</td>
        </tr>";
        echo "<tr style='background: #dedede;'>
            <td colspan='2'><b>derivaries</b></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td> 
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
        </tr>";
        echo "<tr style='background: #dedede;'>
            <td colspan='2'><b>Days in period</b></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
            <td style='text-align: center'></td>
        </tr>";
   }
}