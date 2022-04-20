<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    session_start();
?>
<?php
	if(isset($_GET)){
		$Registration_ID=$_GET['Registration_ID'];
	}else{
		$Registration_ID=$_GET['Registration_ID'];
	}
?>

<center>
		<table width=100%>
			    <tr id="thead">
				<td style='text-align: left;border: 1px #ccc solid;'><b>SN</b></td>
				<td style='text-align: left;border: 1px #ccc solid;'><b>Department</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Last Modified</b></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><b>Action</b></td>
			    </tr>
			    <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>1</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Allegies</td>
                                <td style='text-align: left;border: 1px #ccc solid;'>
                                <?php
                                    echo date('j F, Y',strtotime(date('Y-m-d')));
                                ?>                                
                                </td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Allegies&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>2</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Attachment</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><?php echo date("j F, Y");?></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Attachment&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>3</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Cecap</td>
                                <td style='text-align: left;border: 1px #ccc solid;'>
				    <?php
                                        $last_date = mysqli_query($conn,
							"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as Patient_Payment_ID,pr.Gender as Gender,
								pp.Sponsor_Name as Sponsor_Name,pp.Billing_Type as Billing_Type,pp.Registration_ID as Registration_ID,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Receipt_Date,
								il.Process_Status as Status,il.Check_In_Type as Check_In_Type,ppr.Result_Datetime,'Revenue Center' as Doctors_Name,il.Item_ID as Item_ID
								FROM tbl_patient_payment_item_list as il
								join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
								join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
								join tbl_patient_payment_results as ppr on ppr.Patient_ID=pp.Registration_ID
								 WHERE Check_In_Type ='Cecap' and pr.Registration_ID='$Registration_ID'
								    and il.Process_Status !='inactive' LIMIT 1
								union all
								
							    SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pr.Gender as Gender,
							       pc.Sponsor_Name as Sponsor_Name,pc.Billing_Type as Billing_Type,pc.Registration_ID as Registration_ID,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Receipt_Date,
							       il.Process_Status as Status,il.Check_In_Type as Check_In_Type,ppr.Result_Datetime,il.Consultant as Doctors_Name,i.Item_ID as Item_ID
								  FROM tbl_item_list_cache as il
								  JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
								  JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
								  JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
								  join tbl_patient_payment_results as ppr on ppr.Patient_ID=pc.Registration_ID
								      WHERE Check_In_Type ='Cecap' 
									  and pr.Registration_ID = '$Registration_ID' 
										and il.Process_Status !='inactive' LIMIT 1 
								  ") or die(mysqli_error($conn));
					
					//while loop to select result_datetime
					while($last_date_info=mysqli_fetch_array($last_date)){
					    //return data
					    $date=$last_date_info['Result_Datetime'];
					    //echo date('j F, Y H:i:s',strtotime($date));
					    
					}
					echo date('j F, Y',strtotime(date('Y-m-d')));
					
					
                                    ?>
				</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Cecap&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>4</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Doctors Review</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><?php echo date("j F, Y");?></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Consultation&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>5</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Laboratory</td>
                                <td style='text-align: left;border: 1px #ccc solid;'> 
                                    <?php
                                        $last_date = mysqli_query($conn,
							"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as Patient_Payment_ID,pr.Gender as Gender,
								pp.Sponsor_Name as Sponsor_Name,pp.Billing_Type as Billing_Type,pp.Registration_ID as Registration_ID,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Receipt_Date,
								il.Process_Status as Status,il.Check_In_Type as Check_In_Type,ppr.Result_Datetime,'Revenue Center' as Doctors_Name,il.Item_ID as Item_ID
								FROM tbl_patient_payment_item_list as il
								join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
								join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
								join tbl_patient_payment_results as ppr on ppr.Patient_ID=pp.Registration_ID
								 WHERE Check_In_Type ='Laboratory' and pr.Registration_ID='$Registration_ID'
								    and il.Process_Status !='inactive' LIMIT 1
								union all
								
							    SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pr.Gender as Gender,
							       pc.Sponsor_Name as Sponsor_Name,pc.Billing_Type as Billing_Type,pc.Registration_ID as Registration_ID,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Receipt_Date,
							       il.Process_Status as Status,il.Check_In_Type as Check_In_Type,ppr.Result_Datetime,il.Consultant as Doctors_Name,i.Item_ID as Item_ID
								  FROM tbl_item_list_cache as il
								  JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
								  JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
								  JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
								  join tbl_patient_payment_results as ppr on ppr.Patient_ID=pc.Registration_ID
								      WHERE Check_In_Type ='Laboratory' 
									  and pr.Registration_ID = '$Registration_ID' 
										and il.Process_Status !='inactive' LIMIT 1 
								  ") or die(mysqli_error($conn));
					
					//while loop to select result_datetime
					while($last_date_info=mysqli_fetch_array($last_date)){
					    //return data
					    $date=$last_date_info['Result_Datetime'];
					    echo date('j F, Y H:i:s',strtotime($date));
					}
					
					
					
                                    ?>
                                </td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Laboratory&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>6</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Maternity</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><?php echo date("j F, Y");?></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Maternity&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>7</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Radiology</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><?php echo date("j F, Y");?></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>&section=Radiology&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
                            <tr>
                                <td style='text-align: left;border: 1px #ccc solid;'>8</td>
				<td style='text-align: left;border: 1px #ccc solid;'>Vital Sign</td>
                                <td style='text-align: left;border: 1px #ccc solid;'><?php echo date("j F, Y");?></td>
                                <td style='text-align: left;border: 1px #ccc solid;'><a href="view_departmental_patient_file.php?Registration_ID=<?php echo $Registration_ID?>section=Vitalsign&ViewDepartimentalPatientFile=PatientFileThisPage" class='art-button-green' style="text-decoration: none">View</a></td>
			    </tr>
		</table>
	    </center>