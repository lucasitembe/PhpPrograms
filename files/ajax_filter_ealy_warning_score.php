<?php
include("includes/connection.php");
$Hospital_Ward_ID=$_POST['Hospital_Ward_ID'];

// $SelectRadiItems = "SELECT *
// 							FROM 
// 							tbl_admission ad,
// 							tbl_patient_registration pr,
// 							tbl_hospital_ward hw,
//                                                         tbl_ward_rooms wr
// 								WHERE
//                                                                 ad.ward_room_id=wr.ward_room_id AND
// 								ad.Registration_ID = pr.Registration_ID AND
// 								ad.Admission_Status = 'Admitted' AND 
// 								ad.Hospital_Ward_ID = hw.Hospital_Ward_ID 
//                                                                 AND  ward_type='ordinary_ward'
//                                                                 AND ad.Hospital_Ward_ID = '$Hospital_Ward_ID'  
// 					";	
$SelectRadiItems = "SELECT *
							FROM 
							tbl_admission ad,
							tbl_patient_registration pr,
							tbl_hospital_ward hw,
                                                        tbl_ward_rooms wr
								WHERE
                                                                ad.ward_room_id=wr.ward_room_id AND
								ad.Registration_ID = pr.Registration_ID AND
								ad.Admission_Status = 'Admitted' AND 
								ad.Hospital_Ward_ID = hw.Hospital_Ward_ID 
                                                                AND  ward_type='ordinary_ward'
                                                                AND ad.Hospital_Ward_ID = '$Hospital_Ward_ID'  
					";	

					
					$sql_select = mysqli_query($conn,$SelectRadiItems) or die(mysqli_error($conn));
					$count_sn = 1;
                                        if(mysqli_num_rows($sql_select)){
                                            while($rows = mysqli_fetch_assoc($sql_select)){
                                                    $PatientName = $rows['Patient_Name'];
                                                    $Registration_ID = $rows['Registration_ID'];
                                                    $Ward_Name = $rows['Hospital_Ward_Name'];
                                                    $Bed_Name = $rows['Bed_Name'];
                                                    $room_name = $rows['room_name'];
                                                    $score_vt=0;
                                                    $check_if_have="no";
                                                    $sql_select_warnig_score_result=mysqli_query($conn,"SELECT Temperature,Resp_Bpressure,Pulse_Blood,oxygen_saturation FROM tbl_nursecommunication_observation WHERE Registration_ID='$Registration_ID' ORDER BY Observation_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                                    $num_of_rows=mysqli_num_rows($sql_select_warnig_score_result);
                                                    if($num_of_rows){
                                                       $scr_rows=mysqli_fetch_assoc($sql_select_warnig_score_result);
                                                       $Temperature=$scr_rows['Temperature'];
                                                       $Resp_Bpressure=$scr_rows['Resp_Bpressure'];
                                                       $Pulse_Blood=$scr_rows['Pulse_Blood'];
                                                       $oxygen_saturation=$scr_rows['oxygen_saturation'];
                                                       if($Temperature<36){
                                                          $score_vt +=2; 
                                                       }
                                                       if($Pulse_Blood>=120){
                                                         $score_vt +=1;  
                                                       }
                                                       if($oxygen_saturation<92){
                                                           $score_vt +=2;
                                                       }
                                                       if($Resp_Bpressure>=1){
                                                           $score_vt +=1;
                                                       }
                                                       $check_if_have="yes";
                                                    }
                                                    $remarks="";
                                                    $style_code="";
                                                    if($check_if_have=="yes"){
                                                        if($score_vt<=1){
                                                            $remarks="Low";
                                                        }elseif($score_vt>=2&&$score_vt<=4){
                                                            $remarks="Medium";
                                                        }elseif($score_vt>4){
                                                            $remarks="High"; 
                                                            $style_code="style='background:red;color:white'";
                                                        }
                                                    
                                                    }else{
                                                       $remarks="No Observation Found"; 
                                                    }
                                                    echo "<tr $style_code>
                                                              <td>$count_sn.</td>
                                                              <td>$PatientName</td>
                                                              <td>$Registration_ID</td>
                                                              <td>$room_name--<b>($Bed_Name)</b></td>
                                                              <td style='text-align:center'>$score_vt</td>
                                                              <td>$remarks</td>
                                                         </tr>";
                                                    $count_sn++;
                                            }
                                        }
						

