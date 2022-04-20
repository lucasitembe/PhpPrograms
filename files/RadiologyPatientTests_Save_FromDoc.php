<?php 
	@session_start();
	require_once('includes/connection.php');
	$II = 0;
	$RI = 0;
	$PPI = 0;
	$PPILI = 0;
	$theinput = '';
	$inputname = '';
	$sn = 0;
	$from='';
	if(isset($_GET['II'])) $II = $_GET['II'];
	if(isset($_GET['RI'])) $RI = $_GET['RI'];
	if(isset($_GET['PPI'])) $PPI = $_GET['PPI'];
	if(isset($_GET['PPILI'])) $PPILI = $_GET['PPILI'];
	if(isset($_GET['sn'])) $sn = $_GET['sn'];
	if(isset($_GET['theinput'])) $theinput = $_GET['theinput'];
	if(isset($_GET['inputname'])) $inputname = $_GET['inputname'];
	if(isset($_GET['from'])) $from = $_GET['from'];
	

	$Registration_ID = $RI;
	include("./includes/Get_Patient_Check_In_Id.php");
    include("./includes/Get_Patient_Transaction_Number.php");
	
	$Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID'];
	$Employee_ID=$_SESSION['userinfo']['Employee_ID'];

	//get claim form number_format
	$select_cfn = "SELECT Claim_Form_Number FROM tbl_patient_payments WHERE Registration_ID = '$RI' ORDER BY Patient_Payment_ID DESC LIMIT 1";
	$select_cfn_qry = mysqli_query($conn,$select_cfn) or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($select_cfn_qry);
	if($num_rows > 0){
		while($cfn_data = mysqli_fetch_assoc($select_cfn_qry)){
			$Claim_Form_Number = $cfn_data['Claim_Form_Number'];
		}
	}else{
		$Claim_Form_Number = '';
	}
	//claim form number
	
	//Get the Column name
	switch($inputname){
		case "radi":
			$column = "Radiologist_ID";
			break;
		case "sono":
			$column = "Sonographer_ID";
			break;
		case "clas":
			$column = "Classification";
			break;
		case "rema":
			$column = "Remarks";
			break;
		case "stat":
			$column = "Status";
			break;
		default:
			$column = '';
	}
	//Column name
	
	//Check if the Data exists
	$select_rpt = "SELECT * FROM tbl_radiology_patient_tests WHERE Item_ID = '$II' AND Registration_ID = '$RI'";
	$select_rpt_qry = mysqli_query($conn,$select_rpt) or die(mysqli_error($conn));
	
	//If Radiology Patient Test Exists//#########################################################################
	if(mysqli_num_rows($select_rpt_qry) > 0){
	   // $update_rpt_qry =false;
		$update_rpt = "UPDATE tbl_radiology_patient_tests 
			SET $column = '$theinput', Date_Time =  NOW()
			WHERE 
			Item_ID = '$II' AND 
			Registration_ID = '$RI' AND
			Patient_Payment_Item_List_ID = '$PPILI'";
		$update_rpt_qry = mysqli_query($conn,$update_rpt) or die(mysqli_error($conn));
		if($update_rpt_qry){
			$respond = 'Data updated';
		} else {
			$respond = 'Error';
		}
		
		//echo ($PPILI);exit;
		
		if($column == 'Status'){
			if($theinput == 'done'){
				//Update Item Status For Payments
				//$update_ppil = "UPDATE tbl_patient_payment_item_list SET Status = 'served' WHERE Patient_Payment_Item_List_ID = '$PPILI'";
				//$update_ppil_qry = mysqli_query($conn,$update_ppil) or die(mysqli_error($conn));
				
             	// $update_ilc = "UPDATE tbl_item_list_cache SET Status = 'served' WHERE Payment_Item_Cache_List_ID = '$PPILI'";
				// $update_ilc_qry = mysqli_query($conn,$update_ilc) or die(mysqli_error($conn));
				
				//if($update_ilc_qry) {
					$respond .=' - Served'; 
					//*********Copy data for Payments*************
					$select_pc = "
					SELECT *,ilc.Transaction_type FROM 
						tbl_payment_cache pc,
						tbl_item_list_cache ilc
							WHERE
								pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
								pc.Payment_Cache_ID = '$PPI' LIMIT 1
							";
					$select_pc_qry = mysqli_query($conn,$select_pc) or die(mysqli_error($conn));
					if(mysqli_num_rows($select_pc_qry) > 0){
						while($pcdata = mysqli_fetch_assoc($select_pc_qry)){
							$Registration_ID = $pcdata['Registration_ID'];
							$consultation_id = $pcdata['consultation_id'];
							$Payment_Date_And_Time = $pcdata['Payment_Date_And_Time'];
							$Folio_Number = $pcdata['Folio_Number'];
							$Sponsor_ID = $pcdata['Sponsor_ID'];
							$Sponsor_Name = $pcdata['Sponsor_Name'];
							$Billing_Type = $pcdata['Billing_Type'];
										
							$Receipt_Date = $pcdata['Receipt_Date'];
							$Transaction_status = $pcdata['Transaction_status'];
							$Transaction_type = $pcdata['Transaction_type'];
							$branch_id = $pcdata['branch_id'];
							
							//die($Billing_Type.' - '.$Transaction_type);
					if(($Billing_Type=='Outpatient Cash' && $Transaction_type=="Cash") || ($Billing_Type=='Outpatient Credit' && $Transaction_type=="Cash")){
					 
                       $update_ilc = "UPDATE tbl_item_list_cache SET Status = 'served' WHERE Payment_Item_Cache_List_ID = '$PPILI'";
				       $update_ilc_qry = mysqli_query($conn,$update_ilc) or die(mysqli_error($conn));
				        //$respond .= "tbl_item_list_cache";
			        }elseif($Billing_Type=='Inpatient Cash' || $Billing_Type=='Inpatient Credit' || ($Billing_Type=='Outpatient Credit' && $Transaction_type=="Credit")){
					  //$respond .= "tbl_patient_payment_item_list";
			           $insert_pp = "INSERT 
								INTO tbl_patient_payments(
									Registration_ID,
									Supervisor_ID,
									Employee_ID,
									Payment_Date_And_Time,
									Folio_Number,
									Claim_Form_Number,
									Sponsor_ID,
									Sponsor_Name,
									Billing_Type,
									Transaction_status,
									branch_id,
									Check_In_ID,
									Patient_Bill_ID
								) VALUES(
									'$Registration_ID',
									'$Supervisor_ID',
									'$Employee_ID',
									NOW(),
									'$Folio_Number',
									'$Claim_Form_Number',
									'$Sponsor_ID',
									'$Sponsor_Name',
									'$Billing_Type',
									'$Transaction_status',									
									'$branch_id',
									'$Check_In_ID',
									'$Patient_Bill_ID'								
								)";
							$insert_pp_qry = mysqli_query($conn,$insert_pp) or die(mysqli_error($conn));
							if($insert_pp_qry){
								$respond .= " - Patient Receipt Added";
							}
								
							//get the last patient_payment_id & date
							$select_details = mysqli_query($conn,"
								SELECT Patient_Payment_ID, Receipt_Date 
									FROM tbl_patient_payments 
									WHERE 
									Registration_ID = '$Registration_ID' AND 
									Employee_ID = '$Employee_ID' 
									ORDER BY Patient_Payment_ID DESC LIMIT 1
									") or die(mysqli_error($conn));
							$num_row = mysqli_num_rows($select_details);
							if($num_row > 0){
								while($details_data = mysqli_fetch_assoc($select_details)){
									$New_Patient_Payment_ID = $details_data['Patient_Payment_ID'];
									$Receipt_Date = $details_data['Receipt_Date'];
								}
								
								//Select The Served Item Details tbl_item_list_cache
								$select_ilc = "SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$PPILI'";
								$select_ilc_qry = mysqli_query($conn,$select_ilc) or die(mysqli_error($conn));
								//if(mysqli_num_rows($select_ilc_qry) > 0){
									while($ilc_data = mysqli_fetch_assoc($select_ilc_qry)){
										$Item_ID = $ilc_data['Item_ID'];
										$Price = $ilc_data['Price'];
										$Discount = $ilc_data['Discount'];
										$Consultant = $ilc_data['Consultant'];
										$Consultant_ID = $ilc_data['Consultant_ID'];										
									}
								//}
								
								//Insert Data into  tbl_patient_payment_item_list table
								$insert_ppil = "
									INSERT INTO  tbl_patient_payment_item_list(
										Check_In_Type,
										Item_ID,
										Price,
										Quantity,
										Discount,
										Patient_Direction,
										Consultant,
										Consultant_ID,
										Status,
										Patient_Payment_ID,
										Transaction_Date_And_Time,
										ServedDateTime,
										ServedBy,
										ItemOrigin
									) VALUES (
										'Radiology',
										'$Item_ID',
										'$Price',
										'1',
										'$Discount',
										'Others',
										'$Consultant',
										'$Consultant_ID',
										'Served',
										'$New_Patient_Payment_ID',
										NOW(),
										NOW(),
										'$Employee_ID',
										'Doctor'
									)
								";
								//Run the Query
								$insert_ppil_qry = mysqli_query($conn,$insert_ppil) or die(mysqli_error($conn));
								
								if($insert_ppil_qry) {
									$respond .= " - Patient Payment Updated";
									$update_ilc = "UPDATE tbl_item_list_cache SET Status = 'served' WHERE Payment_Item_Cache_List_ID = '$PPILI'";
				                    $update_ilc_qry = mysqli_query($conn,$update_ilc) or die(mysqli_error($conn));
								}
			                }	
							
								
								
							}else{
								$New_Patient_Payment_ID = 0;
								$Receipt_Date = '';
								$respond .= '- Pay not done';
							}
						}
					}
					//*********Payments****************************
					
				//} else {
					//$respond .=' - Not Served';
				//}
			}
		}
		
	} else {
	//If Test Exists//
	
		//Radiology Patient Test Doesn't Exist//********************************************************************************
		$insert_rpt = "INSERT INTO 
		tbl_radiology_patient_tests($column, Item_ID, Registration_ID, Patient_Payment_Item_List_ID, Date_Time) 
		VALUES('$theinput', '$II', '$RI', '$PPILI', NOW())";
		$insert_rpt_qry = mysqli_query($conn,$insert_rpt) or die(mysqli_error($conn));
		
		//Check if Data added
		if($insert_rpt_qry){
			$respond = 'Data added';
		} else {
			$respond = 'Error';
		}	

		if($column == 'Status'){
			if($theinput == 'done'){
				//Update Item Status For Payments
				$update_ppil = "
					UPDATE tbl_item_list_cache SET Status = 'served' WHERE Payment_Item_Cache_List_ID = '$PPILI'
							";
				$update_ppil_qry = mysqli_query($conn,$update_ppil) or die(mysqli_error($conn));
				if($update_ppil_qry) {
					$respond .=' - Served'; 
					
					//*********Copy data for Payments*************
					$select_pc = "
					SELECT * FROM 
						tbl_payment_cache pc,
						tbl_item_list_cache ilc
							WHERE
								pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
								pc.Payment_Cache_ID = '$PPI' LIMIT 1
							";
					$select_pc_qry = mysqli_query($conn,$select_pc) or die(mysqli_error($conn));
					
					if(mysqli_num_rows($select_pc_qry) > 0){
						while($pcdata = mysqli_fetch_assoc($select_pc_qry)){
							$Registration_ID = $pcdata['Registration_ID'];
							//$Employee_ID = $pcdata['Employee_ID'];
							$consultation_id = $pcdata['consultation_id'];
							$Payment_Date_And_Time = $pcdata['Payment_Date_And_Time'];
							$Folio_Number = $pcdata['Folio_Number'];
							$Sponsor_ID = $pcdata['Sponsor_ID'];
							$Sponsor_Name = $pcdata['Sponsor_Name'];
							//$Billing_Type = $pcdata['Billing_Type'];
							
							//Create Billing Type
							if(($pcdata['Billing_Type']=='Outpatient Credit') && ($pcdata['Transaction_Type']=='Credit')){
								$Billing_Type = 'Outpatient Credit'; 
							}else if(($pcdata['Billing_Type']=='Inpatient Credit') && ($pcdata['Transaction_Type']=='Cash')){
								$Billing_Type = 'Inpatient Cash';                          
							}else if(($pcdata['Billing_Type']=='Inpatient Credit') && ($pcdata['Transaction_Type']=='Credit')) {
								$Billing_Type = 'Inpatient Credit';
							}else if(($pcdata['Billing_Type']=='Inpatient Cash') && ($pcdata['Transaction_Type']=='Cash')){
								$Billing_Type = 'Inpatient Cash';
							} else {							
								$Billing_Type = NULL;
							} 							
									
							$Receipt_Date = $pcdata['Receipt_Date'];
							$Transaction_status = $pcdata['Transaction_status'];
							$Transaction_type = $pcdata['Transaction_type'];
							$branch_id = $pcdata['branch_id'];
							
							$insert_pp = "INSERT 
								INTO tbl_patient_payments(
									Registration_ID,
									Supervisor_ID,
									Employee_ID,
									Payment_Date_And_Time,
									Folio_Number,
									Claim_Form_Number,
									Sponsor_ID,
									Sponsor_Name,
									Billing_Type,
									Transaction_status,
									branch_id,
									Check_In_ID,
									Patient_Bill_ID
								) VALUES(
									'$Registration_ID',
									'$Supervisor_ID',
									'$Employee_ID',
									'$Payment_Date_And_Time',
									'$Folio_Number',
									'$Claim_Form_Number',
									'$Sponsor_ID',
									'$Sponsor_Name',
									'$Billing_Type',
									'$Transaction_status',									
									'$branch_id'
									'$Check_In_ID',
									'$Patient_Bill_ID'								
								)";
							$insert_pp_qry = mysqli_query($conn,$insert_pp) or die(mysqli_error($conn));
							if($insert_pp_qry){
								//get the last patient_payment_id & date
								$select_details = mysqli_query($conn,"
									SELECT Patient_Payment_ID, Receipt_Date 
										FROM tbl_patient_payments 
										WHERE 
										Registration_ID = '$Registration_ID' AND 
										Employee_ID = '$Employee_ID' 
										ORDER BY Patient_Payment_ID DESC LIMIT 1
										") or die(mysqli_error($conn));
								$num_row = mysqli_num_rows($select_details);
								if($num_row > 0){
									while($details_data = mysqli_fetch_assoc($select_details)){
										$New_Patient_Payment_ID = $details_data['Patient_Payment_ID'];
										$Receipt_Date = $details_data['Receipt_Date'];
									}
									
									//Select The Served Item Details tbl_item_list_cache
									$select_ilc = "SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$PPILI'";
									$select_ilc_qry = mysqli_query($conn,$select_ilc) or die(mysqli_error($conn));
									//if(mysqli_num_rows($select_ilc_qry) > 0){
										while($ilc_data = mysqli_fetch_assoc($select_ilc_qry)){
											$Item_ID = $ilc_data['Item_ID'];
											$Price = $ilc_data['Price'];
											$Discount = $ilc_data['Discount'];
											$Consultant = $ilc_data['Consultant'];
											$Consultant_ID = $ilc_data['Consultant_ID'];										
										}
									//}
									
									//Insert Data into  tbl_patient_payment_item_list table
									$insert_ppil = "
										INSERT INTO  tbl_patient_payment_item_list(
											Check_In_Type,
											Item_ID,
											Price,
											Quantity,
											Discount,
											Patient_Direction,
											Consultant,
											Consultant_ID,
											Status,
											Patient_Payment_ID,
											Transaction_Date_And_Time
										) VALUES (
											'Radiology',
											'$Item_ID',
											'$Price',
											'1',
											'$Discount',
											'Others',
											'$Consultant',
											'$Consultant_ID',
											'Served',
											'$New_Patient_Payment_ID',
											NOW()									
										)
									";
									
									//Run the Query
									$insert_ppil_qry = mysqli_query($conn,$insert_ppil) or die(mysqli_error($conn));
									
									if($insert_ppil_qry) {
										$respond .= " - Patient Payment Saved";
									}								
									
								}else{
									$New_Patient_Payment_ID = 0;
									$Receipt_Date = '';
								}								
								
							} else {
								$respond .= '- Payment Not Done';
							}
						}//select_pc_qry While
					}
					//*********Payments****************************
					
				} else {//update_ppil_qry
					$respond .=' - Not Served';
				}
			}//Status = Done
		}//Column = Status
		
	}
	
	echo $respond;
?>