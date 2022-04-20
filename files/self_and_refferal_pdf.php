<?php
include("./includes/connection.php");

if(isset($_GET['start_date'])){
          $start_date =$_GET['start_date'];
}else{
          $start_date ='0000-00-00 00:00';
}
if(isset($_GET['end_date'])){
          $end_date =$_GET['end_date'];
}else{
          $end_date ='0000-00-00 00:00';
}
$htm ="
          <table width='100%' border='0px'>
                    <tr>
                              <td style='text-align:center' colspan=''><img src='./branchBanner/branchBanner.png'></td>
                    </tr>
          
          </table><br/>
          <center><p style='text-align:center;'><b>SELF & REFERRAL REPORT FROM $start_date  TO  $end_date</b></p></center><br/>";
$htm .="
<table class='table' style='text-align: center;background: #FFFFFF' width='100%'>
        <tr>
            <td colspan='2'></td>
            <td style='text-align: center' colspan='3'><b>REFERRAL</b></td>
            <td style='text-align: center' colspan='3'><b>SELF REFERRAL</b></td>
            <td style='text-align: center' colspan='3'><b>EMERGENCY</b></td>
            <td style='text-align: center' colspan='3'><b>ROUTINE</b></td>
            <td style='text-align: center' colspan='3'><b>START</b></td>
            <td style='text-align: center' colspan='3'><b>TOTAL</b></td>
        </tr>
        <tr>
            <td rowspan='2' width='50px'><b>S/No.</b></td>
            <td rowspan='2' style='text-align: center'><b>REGIONS</b></td>
            <td colspan='15' style='text-align: center'><b>CATCHMENT AREA</b></td>
        </tr>
        <tr>
            <td style='text-align: center'><b>M</b></td>
            <td style='text-align: center'><b>F</b></td>
            <td style='text-align: center'><b>T</b></td>
            
            <td style='text-align: center'><b>M</b></td>
            <td style='text-align: center'><b>F</b></td>
            <td style='text-align: center'><b>T</b></td>
            
            <td style='text-align: center'><b>M</b></td>
            <td style='text-align: center'><b>F</b></td>
            <td style='text-align: center'><b>T</b></td>
            
            <td style='text-align: center'><b>M</b></td>
            <td style='text-align: center'><b>F</b></td>
            <td style='text-align: center'><b>T</b></td>
            
            <td style='text-align: center'><b>M</b></td>
            <td style='text-align: center'><b>F</b></td>
            <td style='text-align: center'><b>T</b></td>
        </tr>
        <tbody id='self_and_refl_rpt_body'>";
                    $count=1;
                    $sql_select_all_region_result2=mysqli_query($conn,"SELECT Region_ID,Region_Name FROM tbl_regions WHERE catchment_area='yes'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_all_region_result2)>0){
                              $sub_total_refferal_male=0;
                              $sub_total_refferal_female=0;
                              
                              $sub_total_self_referral_male=0;
                              $sub_total_self_referral_female=0;
                              
                              $sub_total_emergency_male=0;
                              $sub_total_emergency_female=0;
                              
                              $sub_total_routine_male=0;
                              $sub_total_routine_female=0;
                              
                              $sub_total_start_male=0;
                              $sub_total_start_female=0;
                              while($regions_rows=mysqli_fetch_assoc($sql_select_all_region_result2)){
                              $Region_Name=$regions_rows['Region_Name']; 
                              $Region_ID=$regions_rows['Region_ID']; 
                              
                              $refferal_male=0;
                              $refferal_female=0;
                              
                              $self_referral_male=0;
                              $self_referral_female=0;
                              
                              $emergency_male=0;
                              $emergency_female=0;
                              
                              $routine_male=0;
                              $routine_female=0;
                              
                              $start_male=0;
                              $start_female=0;
                              /*
                              <option value="1">Routine</option>
                              <option value="2">Emergency</option>
                              <option value="4">Self Referral</option>
                              <option value="5">Start</option>
                              <option value="3">Referral</option>
                              */
                              //count number of visit from a specific region date and time
                              $sql_select_count_attendance_by_region_result=mysqli_query($conn,"SELECT COUNT(pr.Registration_ID) AS total_attendance,Gender,visit_type FROM tbl_patient_registration pr,tbl_check_in ch WHERE pr.`Registration_ID`=ch.`Registration_ID` AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Region='$Region_Name' GROUP BY Gender,visit_type ") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_select_count_attendance_by_region_result)>0){
                    //                                 echo "<tr>
                    //                                        <td>ATTENDANCE</td>
                    //                                        <td>GENDER</td>
                    //                                        <td>VISIT TYPE</td>
                    //                                       </tr>";
                              while ($attendance_rows= mysqli_fetch_assoc($sql_select_count_attendance_by_region_result)){
                                        $total_attendance=$attendance_rows['total_attendance'];
                                        $Gender=$attendance_rows['Gender'];
                                        $visit_type=$attendance_rows['visit_type'];
                    //                                    echo "<tr>
                    //                                                <td>$total_attendance</td>
                    //                                                <td>$Gender</td>
                    //                                                <td>$visit_type</td>
                    //                                          </tr>";
                                        if($visit_type==1){
                                        if($Gender=="Male"){
                                        $routine_male=$total_attendance;
                                        }else{
                                        $routine_female=$total_attendance; 
                                        }
                                        }
                                        if($visit_type==2){
                                        if($Gender=="Male"){
                                        $emergency_male=$total_attendance;
                                        }else{
                                        $emergency_female=$total_attendance;
                                        }  
                                        }
                                        if($visit_type==3){
                                        if($Gender=="Male"){
                                        $refferal_male=$total_attendance;
                                        }else{
                                        $refferal_female=$total_attendance;
                                        }
                                        }
                                        if($visit_type==4){
                                        if($Gender=="Male"){
                                        $self_referral_male=$total_attendance;
                                        }else{
                                        $self_referral_female=$total_attendance;
                                        }
                                        }
                                        if($visit_type==5){
                                        if($Gender=="Male"){
                                        $start_male=$total_attendance;
                                        }else{
                                        $start_female=$total_attendance; 
                                        }
                                        }
                              }
                              }
                              
                              $sub_total_refferal_male +=$refferal_male;
                              $sub_total_refferal_female +=$refferal_female;
                              
                              $sub_total_self_referral_male +=$self_referral_male;
                              $sub_total_self_referral_female +=$self_referral_female;
                              
                              $sub_total_emergency_male +=$emergency_male;
                              $sub_total_emergency_female +=$emergency_female;
                              
                              $sub_total_routine_male +=$routine_male;
                              $sub_total_routine_female +=$routine_female;
                              
                              $sub_total_start_male +=$start_male;
                              $sub_total_start_female +=$start_female;
                              
                              $total_attanance_per_region=$refferal_male+$refferal_female+$self_referral_male+$self_referral_female+$emergency_male+$emergency_female+$routine_male+$routine_female+$start_male+$start_female;
                              $htm.= "<tr>
                                        <td>$count.</td>
                                        <td class='region_name_cls' onclick='open_selected_region_details($Region_ID,\"$Region_Name\")'>$Region_Name</td>
                                        <td style='text-align:center'>". number_format($refferal_male)."</td>
                                        <td style='text-align:center'>". number_format($refferal_female)."</td>
                                        <td style='text-align:center'>". number_format($refferal_male+$refferal_female)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_male)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_female)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_male+$self_referral_female)."</td>
                                        <td style='text-align:center'>". number_format($emergency_male)."</td>
                                        <td style='text-align:center'>". number_format($emergency_female)."</td>
                                        <td style='text-align:center'>". number_format($emergency_male+$emergency_female)."</td>
                                        <td style='text-align:center'>". number_format($routine_male)."</td>
                                        <td style='text-align:center'>". number_format($routine_female)."</td>
                                        <td style='text-align:center'>". number_format($routine_male+$routine_female)."</td>
                                        <td style='text-align:center'>". number_format($start_male)."</td>
                                        <td style='text-align:center'>". number_format($start_female)."</td>
                                        <td style='text-align:center'>". number_format($start_male+$start_female)."</td>
                                        <td style='text-align:center'><b>". number_format($total_attanance_per_region)."</b></td>
                                        </tr>";
                              $count++;
                    }
                    
                              }
                 
                  $htm.="  <tr><td colspan='2' style='text-align:center'><b>SUB TOTALS</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_female)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male+$sub_total_refferal_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male+$sub_total_self_referral_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male+$sub_total_emergency_female)."</b></td>


                    <td style='text-align:center'><b>". number_format($sub_total_routine_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_male+$sub_total_routine_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female+$sub_total_routine_male+$sub_total_routine_female+$sub_total_emergency_male+$sub_total_emergency_female+$sub_total_self_referral_male+$sub_total_self_referral_female+$sub_total_refferal_male+$sub_total_refferal_female)."</b></td>
                    </tr>
                    <tr>
                    <td colspan='2'></td><td colspan='15' style='text-align: center'><b>OUT SIDE CATCHMENT AREA</b></td>
                    </tr>";
                    
                    $count=1;
                    $sql_select_all_region_result2=mysqli_query($conn,"SELECT Region_ID,Region_Name FROM tbl_regions WHERE catchment_area='no'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_all_region_result2)>0){
                              $ocasub_total_refferal_male=0;
                              $ocasub_total_refferal_female=0;
                              
                              $ocasub_total_self_referral_male=0;
                              $ocasub_total_self_referral_female=0;
                              
                              $ocasub_total_emergency_male=0;
                              $ocasub_total_emergency_female=0;
                              
                              $ocasub_total_routine_male=0;
                              $ocasub_total_routine_female=0;
                              
                              $ocasub_total_start_male=0;
                              $ocasub_total_start_female=0;
                              while($regions_rows=mysqli_fetch_assoc($sql_select_all_region_result2)){
                              $Region_Name=$regions_rows['Region_Name']; 
                              $Region_ID=$regions_rows['Region_ID']; 
                              
                              $refferal_male=0;
                              $refferal_female=0;
                              
                              $self_referral_male=0;
                              $self_referral_female=0;
                              
                              $emergency_male=0;
                              $emergency_female=0;
                              
                              $routine_male=0;
                              $routine_female=0;
                              
                              $start_male=0;
                              $start_female=0;
                              /*
                              <option value="1">Routine</option>
                              <option value="2">Emergency</option>
                              <option value="4">Self Referral</option>
                              <option value="5">Start</option>
                              <option value="3">Referral</option>
                              */
                              //count number of visit from a specific region date and time
                              $sql_select_count_attendance_by_region_result=mysqli_query($conn,"SELECT COUNT(pr.Registration_ID) AS total_attendance,Gender,visit_type FROM tbl_patient_registration pr,tbl_check_in ch WHERE pr.`Registration_ID`=ch.`Registration_ID` AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Region='$Region_Name' GROUP BY Gender,visit_type ") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_select_count_attendance_by_region_result)>0){
                    //                                 echo "<tr>
                    //                                        <td>ATTENDANCE</td>
                    //                                        <td>GENDER</td>
                    //                                        <td>VISIT TYPE</td>
                    //                                       </tr>";
                              while ($attendance_rows= mysqli_fetch_assoc($sql_select_count_attendance_by_region_result)){
                                        $total_attendance=$attendance_rows['total_attendance'];
                                        $Gender=$attendance_rows['Gender'];
                                        $visit_type=$attendance_rows['visit_type'];
                    //                                    echo "<tr>
                    //                                                <td>$total_attendance</td>
                    //                                                <td>$Gender</td>
                    //                                                <td>$visit_type</td>
                    //                                          </tr>";
                                        if($visit_type==1){
                                        if($Gender=="Male"){
                                        $routine_male=$total_attendance;
                                        }else{
                                        $routine_female=$total_attendance; 
                                        }
                                        }
                                        if($visit_type==2){
                                        if($Gender=="Male"){
                                        $emergency_male=$total_attendance;
                                        }else{
                                        $emergency_female=$total_attendance;
                                        }  
                                        }
                                        if($visit_type==3){
                                        if($Gender=="Male"){
                                        $refferal_male=$total_attendance;
                                        }else{
                                        $refferal_female=$total_attendance;
                                        }
                                        }
                                        if($visit_type==4){
                                        if($Gender=="Male"){
                                        $self_referral_male=$total_attendance;
                                        }else{
                                        $self_referral_female=$total_attendance;
                                        }
                                        }
                                        if($visit_type==5){
                                        if($Gender=="Male"){
                                        $start_male=$total_attendance;
                                        }else{
                                        $start_female=$total_attendance; 
                                        }
                                        }
                              }
                              }
                              
                              $ocasub_total_refferal_male +=$refferal_male;
                              $ocasub_total_refferal_female +=$refferal_female;
                              
                              $ocasub_total_self_referral_male +=$self_referral_male;
                              $ocasub_total_self_referral_female +=$self_referral_female;
                              
                              $ocasub_total_emergency_male +=$emergency_male;
                              $ocasub_total_emergency_female +=$emergency_female;
                              
                              $ocasub_total_routine_male +=$routine_male;
                              $ocasub_total_routine_female +=$routine_female;
                              
                              $ocasub_total_start_male +=$start_male;
                              $ocasub_total_start_female +=$start_female;
                              
                              $ocatotal_attanance_per_region=$refferal_male+$refferal_female+$self_referral_male+$self_referral_female+$emergency_male+$emergency_female+$routine_male+$routine_female+$start_male+$start_female;
                              $htm.= "<tr>
                                        <td>$count.</td>
                                        <td class='region_name_cls' onclick='open_selected_region_details($Region_ID,\"$Region_Name\")'>$Region_Name</td>
                                        <td style='text-align:center'>". number_format($refferal_male)."</td>
                                        <td style='text-align:center'>". number_format($refferal_female)."</td>
                                        <td style='text-align:center'>". number_format($refferal_male+$refferal_female)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_male)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_female)."</td>
                                        <td style='text-align:center'>". number_format($self_referral_male+$self_referral_female)."</td>
                                        <td style='text-align:center'>". number_format($emergency_male)."</td>
                                        <td style='text-align:center'>". number_format($emergency_female)."</td>
                                        <td style='text-align:center'>". number_format($emergency_male+$emergency_female)."</td>
                                        <td style='text-align:center'>". number_format($routine_male)."</td>
                                        <td style='text-align:center'>". number_format($routine_female)."</td>
                                        <td style='text-align:center'>". number_format($routine_male+$routine_female)."</td>
                                        <td style='text-align:center'>". number_format($start_male)."</td>
                                        <td style='text-align:center'>". number_format($start_female)."</td>
                                        <td style='text-align:center'>". number_format($start_male+$start_female)."</td>
                                        <td style='text-align:center'><b>". number_format($ocatotal_attanance_per_region)."</b></td>
                                        </tr>";
                              $count++;
                    }
                    
                              }
                 
                  $htm.="  <tr><td colspan='2' style='text-align:center'><b>SUB TOTALS OCA</b></td>
                    <td  style='text-align:center'><b>". number_format($ocasub_total_refferal_male)."</b></td>
                    <td  style='text-align:center'><b>". number_format($ocasub_total_refferal_female)."</b></td>
                    <td  style='text-align:center'><b>". number_format($ocasub_total_refferal_male+$ocasub_total_refferal_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($ocasub_total_self_referral_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_self_referral_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_self_referral_male+$ocasub_total_self_referral_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($ocasub_total_emergency_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_emergency_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_emergency_male+$ocasub_total_emergency_female)."</b></td>


                    <td style='text-align:center'><b>". number_format($ocasub_total_routine_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_routine_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_routine_male+$ocasub_total_routine_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($ocasub_total_start_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_start_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($ocasub_total_start_male+$ocasub_total_start_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($ocasub_total_start_male+$ocasub_total_start_female+$ocasub_total_routine_male+$ocasub_total_routine_female+$ocasub_total_emergency_male+$ocasub_total_emergency_female+$ocasub_total_self_referral_male+$ocasub_total_self_referral_female+$ocasub_total_refferal_male+$ocasub_total_refferal_female)."</b></td>
                    </tr>  
                    <tr><td colspan='2' style='text-align:center'><b>SUB TOTALS CA</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_female)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male+$sub_total_refferal_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male+$sub_total_self_referral_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male+$sub_total_emergency_female)."</b></td>


                    <td style='text-align:center'><b>". number_format($sub_total_routine_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_male+$sub_total_routine_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female+$sub_total_routine_male+$sub_total_routine_female+$sub_total_emergency_male+$sub_total_emergency_female+$sub_total_self_referral_male+$sub_total_self_referral_female+$sub_total_refferal_male+$sub_total_refferal_female)."</b></td>
                    </tr>  
                    <tr><td colspan='2' style='text-align:center'><b>GRAND TOTAL</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male+$ocasub_total_refferal_male)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_female+$ocasub_total_refferal_female+$ocasub_total_refferal_female)."</b></td>
                    <td  style='text-align:center'><b>". number_format($sub_total_refferal_male+$sub_total_refferal_female+$ocasub_total_refferal_male)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male+$ocasub_total_self_referral_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_female+$ocasub_total_self_referral_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_self_referral_male+$sub_total_self_referral_female+$ocasub_total_self_referral_male+$ocasub_total_self_referral_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male+$ocasub_total_emergency_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_female+$ocasub_total_emergency_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_emergency_male+$sub_total_emergency_female+$ocasub_total_emergency_male+$ocasub_total_emergency_female)."</b></td>


                    <td style='text-align:center'><b>". number_format($sub_total_routine_male+$ocasub_total_routine_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_female+$ocasub_total_routine_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_routine_male+$sub_total_routine_female+$ocasub_total_routine_male+$ocasub_total_routine_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$ocasub_total_start_male)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_female+$ocasub_total_start_female)."</b></td>
                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female+$ocasub_total_start_male+$ocasub_total_start_female)."</b></td>

                    <td style='text-align:center'><b>". number_format($sub_total_start_male+$sub_total_start_female+$sub_total_routine_male+$sub_total_routine_female+$sub_total_emergency_male+$sub_total_emergency_female+$sub_total_self_referral_male+$sub_total_self_referral_female+$sub_total_refferal_male+$sub_total_refferal_female+$ocasub_total_start_male+$ocasub_total_start_female+$ocasub_total_routine_male+$ocasub_total_routine_female+$ocasub_total_emergency_male+$ocasub_total_emergency_female+$ocasub_total_self_referral_male+$ocasub_total_self_referral_female+$ocasub_total_refferal_male+$ocasub_total_refferal_female)."</b></td>
                    </tr>  
        </tbody>
    </table>
";

include("MPDF/mpdf.php");

    $mpdf=new mPDF('','A4'); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();