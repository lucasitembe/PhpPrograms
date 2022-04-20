<?php
	include("./includes/connection.php");
        include("./includes/constants.php");
//        include("medical_record_encrypt_decrypt.php");
	$Registration_ID = $_POST['Registration_ID'];
	$operation = $_POST['operation'];
	
//	$host = $_SERVER['REMOTE_ADDR'];
	$host = '192.168.43.4';
	$card_port = 9195;
	
	switch($operation){
		case 'get_card_number':{
			$message = 	"*SCARD#READ#CARDID#";
			$socket = socket_create(AF_INET, SOCK_STREAM, 0);
			if ($socket) {
				$result = socket_connect($socket, $host, $card_port);
				if ($result) {
					if (socket_write($socket, $message, strlen($message))){
						$result = socket_read($socket, 8192);
						if ($result){
							$response['data'] = $result;
							$response['code'] = 200;
							socket_close($socket);
						}
					}
				} else {
					$response['code'] = 100;
				}
			}
			$response['card_no']= trim($result);
//			$response['card_no']='1997 5679 7890 8900';
			echo  json_encode($response);
		};break;
		case 'register':{	
				$serial_no = $_POST['serial_no'];
				$card_no = $_POST['card_no'];
				$Employee_ID = $_POST['Employee_ID'];
			$result = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE 
			Registration_ID=$Registration_ID");
							$patient_info = mysqli_fetch_assoc($result);
							$patient_name = $patient_info['Patient_Name'];
							$title = $patient_info['Title'];
							$date_of_birth = $patient_info['Date_Of_Birth'];
							$gender = $patient_info['Gender'];
							$country = $patient_info['Country'];
							$region = $patient_info['Region'];
							$district = $patient_info['District'];
							$ward = $patient_info['Ward'];
							$village = $patient_info['village'];
							$Member_Number = $patient_info['Member_Number'];
			
							$issue_date = date('Y-m-d',time());
							$expire_date = date('Y-m-d',strtotime('+3 Year'));
							
                                                        
                                                        
                                                        
                                                        $card_data_array=array(
                                                            array(
                                                                $Registration_ID,
                                                                $patient_name,
                                                                $date_of_birth,
                                                                $gender,
                                                                $country,
                                                                $region,
                                                                $district,
                                                                $ward,
                                                                $village,
                                                                $card_no,
                                                                $serial_no,
                                                                $Employee_ID,
                                                                $issue_date,
                                                                $expire_date,
                                                                $Member_Number,
                                                            ),
                                                            array(),
                                                               
                                                        );
//							$card_data = '{global_id:'.$Registration_ID.',patient_name:'.$patient_name.',date_of_birth:'.$date_of_birth.',gender:'.$gender.',country:'.$country.',region:'.$region.',district:'.$district.',ward:'.$ward.',village:'.$village.',card_no:'.$card_no.',serial_no:'.$serial_no.',Employee_ID:'.$Employee_ID.',issue_date:'.$issue_date.',expire_date:'.$expire_date.'}';
							$card_data= json_encode($card_data_array);
                                                        $card_result = mysqli_query($conn,"SELECT card_serial_number FROM tbl_member_afya_card WHERE
							card_serial_number = '$serial_no'");
							if(mysqli_num_rows($card_result) < 1){
								$message = '*SCARD#WRITE#MEMBERDATA#' . $card_data . '#' . $serial_no . '#';
								$socket = socket_create(AF_INET, SOCK_STREAM, 0);
								if ($socket) {
									$result = socket_connect($socket, $host, $card_port);
									if ($result) {
										if (socket_write($socket, $message, strlen($message))) {
											$result = socket_read ($socket, 8192);
											if ($result) {
												if (is_integer(strpos($result, '90 00'))) {
													
													//$response['data'] = $result;
													$save_result = mysqli_query($conn,"INSERT INTO tbl_member_afya_card 
																(card_no,card_serial_number,date_of_issue,place_of_issue,expire_date,
																 Employee,Registration_ID) 
																 VALUES('$card_no','$serial_no','$issue_date','GPITG HOSPITAL','$expire_date','$Employee_ID','$Registration_ID')");
													if($save_result){
														$response['code'] = 200;
													}
												} else {
													$response['code'] = 400;
													$response['message'] = 'Kuna tatizo limetokea. Tafadhali jaribu tena.';
												}
											}
										}
									} else {
										$response['code'] = 100;	
									}
								}
							}else{
								$response['code'] = 300;
							}
								echo  json_encode($response);
							};break;
		case 'save_check_out_results':{
			echo getLaboratoryCheckupResults($Registration_ID);
		}break;
		case 'verify':{
			$Registration_ID = $_POST['Registration_ID'];
			
			$message = '*SCARD#READ#MEMBERDATA#';
					$socket = socket_create(AF_INET, SOCK_STREAM, 0);
					if ($socket) {
						$result = socket_connect($socket, $host, $card_port);
						if ($result) {
							if (socket_write($socket, $message, strlen($message))) {
								$result = socket_read ($socket, 8192);
								if ($result) {
                                                                      /*****gkcchief 2019******/
                                                                        $feedback_data1=json_decode($result,true);
                                                                        $feedback_data2=$feedback_data1[0];
//                                                                        echo "$result data  hapa hapa";
////                                                                        print_r($feedback_data1);
//                                                                        ECHO "<br>";
//                                                                        ECHO "<br>";
//                                                                        ECHO "<br>";
//                                                                        ECHO "<br>";
//                                                                        ECHO "<br>...........................";
//                                                                        print_r($feedback_data2);
//                                                                        echo "";
                                                                        $patient_name=$feedback_data2[1];
                                                                        $date_of_birth=$feedback_data2[2];
                                                                        $gender=$feedback_data2[3];
                                                                        $country=$feedback_data2[4];
                                                                        $region=$feedback_data2[5];
                                                                        $district=$feedback_data2[6];
                                                                        $ward=$feedback_data2[7];
                                                                        $village=$feedback_data2[8];
                                                                        $card_no=$feedback_data2[9];
                                                                        $serial_no=$feedback_data2[10];
                                                                        $Employee_ID=$feedback_data2[11];
                                                                        $issue_date=$feedback_data2[12];
                                                                        $expire_date=$feedback_data2[13];
                                                                        $Member_Number=$feedback_data2[14];
                                                                        $image_icon="images/save_icon.png";
//                                                                        }
                                                                        $image_icon_x="images/x.png";
                                                                        
                                                                        $date1 = new DateTime(date("Y-m-d"));
                                                                        $date2 = new DateTime(str_replace(' ','',$date_of_birth));
                                                                        $diff = $date1->diff($date2);
                                                                        $age = $diff->y;
                                                                        
                                                                        //select system data
                                                                        $sql_select_patient_system_data_result=mysqli_query($conn,"SELECT village,Patient_Name,Date_Of_Birth,Gender,Country,Region,District,Ward,Member_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                                                        if(mysqli_num_rows($sql_select_patient_system_data_result)>0){
                                                                            $system_info_rows=mysqli_fetch_assoc($sql_select_patient_system_data_result);
                                                                            $sys_Patient_Name=$system_info_rows['Patient_Name'];
                                                                            $sys_Date_Of_Birth=$system_info_rows['Date_Of_Birth'];
                                                                            $sys_Gender=$system_info_rows['Gender'];
                                                                            $sys_Country=$system_info_rows['Country'];
                                                                            $sys_Region=$system_info_rows['Region'];
                                                                            $sys_District=$system_info_rows['District'];
                                                                            $sys_village=$system_info_rows['village'];
                                                                            $sys_Ward=$system_info_rows['Ward'];
                                                                            $sys_Member_Number=$system_info_rows['Member_Number'];
                                                                            $date3 = new DateTime(date("Y-m-d"));
                                                                            $date4 = new DateTime(str_replace(' ','',$date_of_birth));
                                                                            $diff = $date3->diff($date4);
                                                                            $sys_age = $diff->y;
                                                                            
                                                                        }
                                                                        //select patient system card information 
                                                                        $sql_select_patient_system_card_information_result=mysqli_query($conn,"SELECT card_no,date_of_issue,expire_date FROM tbl_member_afya_card WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                                                        if(mysqli_num_rows($sql_select_patient_system_card_information_result)>0){
                                                                            $patient_card_info_rows=mysqli_fetch_assoc($sql_select_patient_system_card_information_result);
                                                                            $sys_card_no=$patient_card_info_rows['card_no'];
                                                                            $sys_date_of_issue=$patient_card_info_rows['date_of_issue'];
                                                                            $sys_expire_date=$patient_card_info_rows['expire_date'];
                                                                        }
                                                                        echo '<div class="box box-primary">
                                                                                <div class="box-body" >';
                                                                        echo "
                                                                            <table id='demographic_data'>
                                                                                    <thead>
                                                                                            <tr><th>ATTRIBUTE</th><th>CARD DATA</th><th>SYSTEM DATA</th><th style='text-align:right;'>REMARK</th></tr>
                                                                                    </thead>
                                                                                    <tbody class='verify_dialog_body'>
                                                                                            <tr id='patient_name'><td>Full Name: </td><td>$patient_name</td><td>$sys_Patient_Name</td>";
                                                                                            if($patient_name==$sys_Patient_Name){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                            echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                            
                                                                                           echo " <tr id='gender'><td>Gender: </td><td>$gender</td><td>$sys_Gender</td>";
                                                                                           if($gender==$sys_Gender){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='date_of_birth'><td>Age: </td><td>$age</td><td>$sys_age</td>";
                                                                                           if($age==$sys_age){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='country'><td>Country: </td><td>$country</td><td>$sys_Country</td>";
                                                                                           if($country==$sys_Country){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='region'><td>Region: </td><td>$region</td><td>$sys_Region</td>";
                                                                                           if($region==$sys_Region){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='district'><td>District: </td><td>$district</td><td>$sys_District</td>";
                                                                                           if($district==$sys_District){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='ward'><td>Ward: </td><td>$ward</td><td>$sys_Ward</td>";
                                                                                           if($ward==$sys_Ward){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='village'><td>Village: </td><td>$village</td><td>$sys_village</td>";
                                                                                           if($village==$sys_village){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='card_no'><td>Card Number: </td><td>$card_no</td><td>$sys_card_no</td>";
                                                                                           if($card_no==$sys_card_no){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           $issue_date=date("Y-m-d",strtotime($issue_date));
                                                                                           $sys_date_of_issue=date("Y-m-d",strtotime($sys_date_of_issue));
                                                                                           echo "<tr id='issue_date'><td>Issue Date: </td><td>$issue_date</td><td>$sys_date_of_issue</td>";
                                                                                           
                                                                                           if($issue_date==$sys_date_of_issue){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='expire_date'><td>Expire Date: </td><td>$expire_date</td><td>$sys_expire_date</td>";
                                                                                           $expire_date=date("y-m-d",$expire_date);
                                                                                           $sys_expire_date=date("y-m-d",$sys_expire_date);
                                                                                           if($expire_date==$sys_expire_date){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>";
                                                                                           echo "<tr id='Member_Number'><td>Member Number: </td><td>$Member_Number</td><td>$sys_Member_Number</td>";
                                                                                           if($Member_Number==$sys_Member_Number){

                                                                                            }else{
                                                                                               $image_icon=$image_icon_x; 
                                                                                            }
                                                                                           echo "<td><img class='review_image' src='$image_icon' ></td></tr>
                                                                                    </tbody>
                                                                            </table>
                                                                            ";
                                                                        echo "  
                                                                                <div id='medical_record_data'>
                                                                                </div>
                                                                                </div>
                                                                                </div>
                                                                                <center>
                                                                                    <input type='button' value='SUSPEND CARD' class='art-button-green' style='background:#FF6F05;'>
                                                                                    <input type='button' value='BLOCK CARD' class='art-button-green' style='background:red'>
                                                                                    <input type='button' name='save_afya_card' id='update_afya_card' style='background:green' class='art-button-green' value='UPDATE' onclick='update_afya_card($Registration_ID,\'update\',$Employee_ID;);'>
                                                                                    <input type='button' value='RETRIVE MEDICAL RECORD' onclick='retrive_medical_record_data(\"$card_no\")' class='art-button-green' style='background:'>
                                                                                </center>
                                                                                ";
								}
							}
						} else {
							$response['code'] = 400;	
						}
					}
//			echo  json_encode($response);
//                                        echo $result;
		}break;
		case 'update' : {
			$card_serial = $_POST['card_serial'];
			$update_details = $_POST['update_details'];
			$message = '*SCARD#WRITE#MEMBERDATA#' . $update_details . '#' . $card_serial . '#';
			$socket = socket_create(AF_INET, SOCK_STREAM, 0);
			if ($socket) {
				$result = socket_connect($socket, $host, $card_port);
				if ($result) {
					if (socket_write($socket, $message, strlen($message))) {
						$result = socket_read ($socket, 8192);
						if ($result) {
							if (is_integer(strpos($result, '90 00'))) {
								$response['code'] = 200;
							} else {
								$response['code'] = 300;
								$response['message'] = 'Kuna tatizo limetokea. Tafadhali jaribu tena.';
							}
						}
					}
				} else {
					$response['code'] = 400;	
				}
			}
			echo json_encode($response);
		};break;
                case 'read_card_information':{
                    ////////////////////////////////////////////////////////////////////////////////////////
                    $message = '*SCARD#READ#MEMBERDATA#';
					$socket = socket_create(AF_INET, SOCK_STREAM, 0);
					if ($socket) {
						$result = socket_connect($socket, $host, $card_port);
						if ($result) {
							if (socket_write($socket, $message, strlen($message))) {
								$result = socket_read ($socket, 8192);
								if ($result) {
                                                                    $feedback_data1=json_decode($result,true);
                                                                        
                                                                        $card_data=$feedback_data1[0];
//									$string = str_replace('{', '', $result);
//									$string = str_replace('}', '', $string);
//									$string = str_replace("\r\n", '', $string);
//									$props = explode(',', $string);
//									$array = array();
//									foreach ($props as $prop) {
//										$prop_parts = explode(':', $prop);
//										$key = preg_replace('/\s+/', '', $prop_parts[0]);
//										$value = trim($prop_parts[1]);
//										$array[$key] = $value;
//									}
//									$date1 = new DateTime(date("Y-m-d"));
//        							$date2 = new DateTime(str_replace(' ','',$array['date_of_birth']));
//        							$diff = $date1->diff($date2);
//        							$age = $diff->y;
//									
//									$card_data = array(
//														'patient_name'	=> $array['patient_name'],
//														'date_of_birth'	=> $age,
//														'gender'		=> $array['gender'],
//														'country'		=> $array['country'],
//														'region'		=> $array['region'],
////														'district'		=> str_replace(' ','',$array['district']),
//														'district'		=> $array['district'],
//														'ward'			=> $array['ward'],
//														'village'		=> $array['village'],
//														'card_no'		=> $array['card_no'],
//														'issue_date'	=> str_replace(' ','',$array['issue_date']),
//														'expire_date'	=> str_replace(' ','', $array['expire_date'])
//													);
//									
									$response['card']=$card_data;
//									$response['code']=200;
//									
										
									}else{
										$response['code'] = 300;
									}
								}
							}
						} else {
							$response['code'] = 400;	
						}
					
			echo  json_encode($response);
                    ////////////////////////////////////////////////////////////////////////////////////////
                }break;case "retrive_medical_record_data":{
                    /*****gkcchief april-2019******/
                    $card_no=$_POST['card_no'];
                    echo "                                                                                   
                        <h5 style='text-align:center'>MEDICAL RECORD DATA</h5>                                                                               
                        <hr/>";
                   
                     
                        $connected = fopen(ehms_mr_local_url.":80/","r");
                            $online_medical_record_data=[];
                            if($connected)
                            {
                               //get online medical record data and overide card medical record data
                               $online_medical_record_data=json_decode(get_online_mr_data($card_no),true);
//                               echo "<pre>";
//                               print_r($online_medical_record_data);
//                               echo "</pre>";
//                               die("-------------------------------");
                               //read card header data
                               $read_card_data=retrive_header_card_data($host, $card_port);
                               $serial_no=$read_card_data[10];
//                               ECHO $serial_no;
                               //wite both data to card
                               ////////////////////////////////////////////////////////////////////////////////////////////
                                         $card_data_array=array(
                                                            $read_card_data,
                                                            $online_medical_record_data,
                                                               
                                                        );
							$card_data= json_encode($card_data_array);
//							$card_data= json_encode($read_card_data);
//                                                        echo "header--------------------<br/><br/><br/><pre>";
//                                                        print_r($card_data_array);
//                                                        echo "</pre><br/><br/><br/>medical rec";
////                                                        print_r($online_medical_record_data);
//                                                        echo "<br/><br/><br/>------------------------<br/><br/><br/>";
////                                                        print_r($card_data);
//                                                        echo "---------------------<pre>";
//                                                         print_r(json_decode($card_data));
								$message = '*SCARD#WRITE#MEMBERDATA#' . $card_data . '#' . $serial_no . '#';
								$socket = socket_create(AF_INET, SOCK_STREAM, 0);
								if ($socket) {
									$result = socket_connect($socket, $host, $card_port);
									if ($result) {
										if (socket_write($socket, $message, strlen($message))) {
											$result = socket_read ($socket, 8192);
											if ($result) {
												if (is_integer(strpos($result, '90 00'))) {
													
													
												} else {
													echo $result.'Kuna tatizo limetokea. Tafadhali jaribu tena.';
												}
											}
										}
									} else {
//										$response['code'] = 100;	
									}
								}
//							}
                               ////////////////////////////////////////////////////////////////////////////////////////////
                                
                            } 
                            ///read information stored in patient card
                            /////////////////////////////////////////////////////////////////////////////////////////
                            /*****gkcchief april-2019******/
                            $message = '*SCARD#READ#MEMBERDATA#';
					$socket = socket_create(AF_INET, SOCK_STREAM, 0);
					if ($socket) {
						$result = socket_connect($socket, $host, $card_port);
						if ($result) {
							if (socket_write($socket, $message, strlen($message))) {
								$result = socket_read ($socket, 8192);
								if ($result) {
//									
                                                                        $feedback_data1=json_decode($result,true);
                                                                        
                                                                        $feedback_data2=$feedback_data1[1];
//                                                                        echo "-----------------------------------------------------:";
//                                                                        echo "<pre>";
//                                                                        print_r($feedback_data2);
//                                                                        echo "</pre>";
//                                                                        echo "-----------------------------------------------------:";
                                                                        echo "
                                                                                <table class='table table-border'>
                                                                                    <tr>
                                                                                        <th>S/No.</th>
                                                                                        <th>HOSPITAL ID</th>
                                                                                        <th>HOSPITAL NAME</th>
                                                                                        <th>CONSULTATION DATE</th>
                                                                                    </tr>
                                                                                    <tbody>";
                                                                         $count_sn=1;
                                                                         foreach($feedback_data2 as $medical_rec_data){
                                                                            $consultation_record_id=$medical_rec_data[0];
                                                                            $hospital_id=$medical_rec_data[1];
                                                                            $complain=$medical_rec_data[2];
                                                                            $consultation_date=$medical_rec_data[3];
                                                                            $final_diagnosis=$medical_rec_data[4];
                                                                            $ordered_item_pr_consultation=$medical_rec_data[5];
                                                                            $final_diagnosis_arr=explode("<<,>>",$final_diagnosis);
                                                                            $final_diagnosis_data= implode(",", $final_diagnosis_arr);
                                                                            //save patient medical information to local database
                                                                            /**************first check if data arleady saved to local database*/
                                                                            $sql_check_if_consulted_data_saved_result=mysqli_query($conn,"SELECT consultation_record_id FROM tbl_card_consultation_record WHERE consultation_record_id='$consultation_record_id'") or die(mysqli_error($conn));
                                                                            if(mysqli_num_rows($sql_check_if_consulted_data_saved_result)<=0){
                                                                                //save consultation data
                                                                                mysqli_query($conn,"INSERT INTO tbl_card_consultation_record(consultation_record_id,card_no,hospital_id,complain,consultation_date,received_local_date_time,final_diagnosis) VALUES('$consultation_record_id','$card_no','$hospital_id','$complain','$consultation_date',NOW(),'$final_diagnosis')") or die(mysqli_error($conn));
                                                                            }
                                                                            foreach($ordered_item_pr_consultation as $ordered_item_pr_consultation_data){
                                                                               $ordered_item_code=$ordered_item_pr_consultation_data[0];
                                                                               $Check_In_Type=$ordered_item_pr_consultation_data[1];
                                                                               $ordered_by=$ordered_item_pr_consultation_data[2];
                                                                               $Doctor_Comment=$ordered_item_pr_consultation_data[3];
                                                                               $status=$ordered_item_pr_consultation_data[4];
                                                                               $ordered_time=$ordered_item_pr_consultation_data[5];
                                                                               $consultation_record_id=$ordered_item_pr_consultation_data[6];
                                                                               
                                                                            //check if ordered item already saved
                                                                            $sql_check_if_ordered_item_already_saved_result=mysqli_query($conn,"SELECT ordered_item_code FROM tbl_card_ordered_item_record WHERE ordered_item_code='$ordered_item_code' AND consultation_record_id='$consultation_record_id'") or die(mysqli_error($conn));
                                                                                if(mysqli_num_rows($sql_check_if_ordered_item_already_saved_result)<=0){
                                                                                    //save orderd item
                                                                                    mysqli_query($conn,"INSERT INTO tbl_card_ordered_item_record(ordered_item_code,check_in_type,ordered_by,doctor_comments,status,ordered_time,consultation_record_id,received_local_time) VALUES('$ordered_item_code','$Check_In_Type','$ordered_by','$Doctor_Comment','$status','$ordered_time','$consultation_record_id',NOW())") or die(mysqli_error($conn));
                                                                                }
                                                                            
                                                                            }
                                                                            
                                                                            //select hospital name
                                                                            $Hospital_name="";
                                                                            $sql_select_hospital_name_result=mysqli_query($conn,"SELECT Hospital_name FROM tbl_ehms_mr_all_hospital_list_local WHERE ehms_mr_all_hospital_list_online_id='$hospital_id'") or die(mysqli_error($conn));
                                                                            if(mysqli_num_rows($sql_select_hospital_name_result)>0){
                                                                                $Hospital_name=mysqli_fetch_assoc($sql_select_hospital_name_result)['Hospital_name'];
                                                                            }
                                                                            echo "
                                                                                        <tr>
                                                                                            <td>$count_sn</td>
                                                                                            <td>$hospital_id</td>
                                                                                            <td>$Hospital_name</td>
                                                                                            <td>$consultation_date</td>
                                                                                        </tr>";
                                                                         }       
                                                                         echo "
                                                                                    </tbody>
                                                                                </table>
                                                                            ";
                                        }
                                        
                                     }
                                                                
                                  }
                                                                
                            }
                            /////////////////////////////////////////////////////////////////////////////////////////
                            
                            
                }
	}


	function getLaboratoryCheckupResults($Registration_ID){
		$lab_checkup_results=mysqli_query($conn,"SELECT i.Item_ID,tr.test_result_ID,i.Product_Name,tpr.result,tr.TimeSubmitted,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc INNER JOIN tbl_test_results tr ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID INNER JOIN tbl_tests_parameters_results tpr ON tpr.ref_test_result_ID=tr.test_result_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN tbl_items i ON i.Item_ID=ilc.Item_ID JOIN tbl_item_subcategory its ON i.Item_Subcategory_ID=its.Item_Subcategory_ID JOIN tbl_item_category ic ON its.Item_Category_ID=ic.Item_Category_ID WHERE tpr.Submitted='Yes' AND tpr.Validated='Yes' AND Registration_ID=$Registration_ID AND ilc.Payment_Cache_ID = (SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = $Registration_ID ORDER BY Payment_Cache_ID DESC LIMIT 1 ) GROUP BY tpr.ref_test_result_ID ");
		
		$patient_results = array();
		$error = (mysqli_num_rows($lab_checkup_results)?false:true);
		while($row = mysqli_fetch_assoc($lab_checkup_results)){
			array_push($patient_results,
				array(
					'Product_Name' => $row['Product_Name'],
					'Result' => $row['result'],
					'TimeSubmitted' => $row['TimeSubmitted']
				)
			);
		}
		$lab_results = array(
			'Registration_ID' => $Registration_ID,
			'results' => $patient_results,
			'error' => $error
		);
		return json_encode($lab_results);
	}
        function get_online_mr_data($card_no){
            /*****gkcchief april-2019******/
             $post_data_array=array(
                "username"=>"gpitg",
                "password"=>"gpitgmronline",
                "card_no"=>$card_no,
            );
           $post_data= json_encode($post_data_array);
            $post_data;
            $header = [
                'Content-Type: application/json',
                'Accept: application/json'
            ];
            $url = ehms_mr_local_url.'/ehms_mr_local/get_online_card_data.php';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_POST, true );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_POSTREDIR, 3);
            $result = curl_exec($ch);
            curl_close($ch); 
            return $result;
        }
        
        function retrive_header_card_data($host, $card_port){
            /*****gkcchief april-2019******/
//            echo "------------------------------";
            $feedback_data2=[];
            $message = '*SCARD#READ#MEMBERDATA#';
            $socket = socket_create(AF_INET, SOCK_STREAM, 0);
            if ($socket) {
                    $result = socket_connect($socket, $host, $card_port);
                    if ($result) {
                            if (socket_write($socket, $message, strlen($message))) {
                                $result = socket_read ($socket, 8192);
                                if ($result) {				
                                    $feedback_data1=json_decode($result,true);                                 
                                    $feedback_data2=$feedback_data1[0];   
                                }else{
                                    return $feedback_data['fail']="fail_to_retrive_data";
                                }
                            }
                    }else{
                        echo ",,,,,,,,,,,";
                    }
            }else{
                echo "::::::";
            }
            return $feedback_data2;
        }
?>
