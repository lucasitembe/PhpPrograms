<?php
require_once('includes/connection.php');

	$Registration_ID = $_GET['RegID'];//
	$Employee_ID = $_GET['EmpID'];
    $Round_ID = $_GET['Round_ID'];

    include("./includes/Get_Patient_Check_In_Id.php");
    include("./includes/Get_Patient_Transaction_Number.php");
	
	$select_docservices = "
	SELECT * FROM tbl_inpatient_doctorservices_cache 
		WHERE 
			Registration_ID = '$Registration_ID' AND 
			Employee_ID = '$Employee_ID' LIMIT 1";
	$select_docservices_qry = mysqli_query($conn,$select_docservices) or die(mysqli_error($conn));
	
	while($services = mysqli_fetch_assoc($select_docservices_qry)){
		$reg_ID = $services['Registration_ID'];
		$emp_ID = $services['Employee_ID'];
		$payDate = date('Y-m-d H:i:s');
		$recDate = date('Y-m-d');
		$cons_ID = $services['consultation_id'];
		$folio = $services['Folio_Number'];
		$spID = $services['Sponsor_ID'];
		$branch_id = $services['branch_id'];
		$spName = $services['Sponsor_Name'];
		$billType = $services['Billing_Type'];	
	}
        $Claim_Form_Number='';
        
        $Consultant='';
        
        $qr_cons=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$emp_ID'") or die(mysqli_error($conn));
        $Consultant=  mysqli_fetch_assoc($qr_cons)['Employee_Name'];
        
        
        $qr_claim=  mysqli_query($conn,"SELECT Claim_Form_Number FROM tbl_patient_payments WHERE  Registration_ID='$reg_ID' AND Folio_Number='$folio' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($qr_claim) >0){
            $Claim_Form_Number=  mysqli_fetch_assoc($qr_claim)['Claim_Form_Number'];
        } 
        
        if($Claim_Form_Number =='NULL'){
            $Claim_Form_Number='';
        }
        //Bill patient
           $insert_pp = "INSERT INTO tbl_patient_payments(
                                    Registration_ID,
                                    Supervisor_ID,
                                    Employee_ID,
                                    Payment_Date_And_Time,
                                    Folio_Number,
                                    Round_ID,
                                    Claim_Form_Number,
                                    Sponsor_ID,
                                    Sponsor_Name,
                                    Billing_Type,
                                    Receipt_Date,
                                    branch_id,
                                    Check_In_ID,
                                    Patient_Bill_ID
                            ) VALUES(
                                    '$reg_ID',
                                    '$emp_ID',
                                    '$emp_ID',
                                    '$payDate',
                                    '$folio',
                                    '$Round_ID',
                                    '$Claim_Form_Number',
                                    '$spID',
                                    '$spName',
                                    '$billType',
                                    '$recDate',
                                    '$branch_id',
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
                                    Registration_ID = '$reg_ID' AND 
                                    Employee_ID = '$emp_ID' 
                                    ORDER BY Patient_Payment_ID DESC LIMIT 1
                                    ") or die(mysqli_error($conn));
                    $num_row = mysqli_num_rows($select_details);
                    if($num_row > 0){
                            while($details_data = mysqli_fetch_assoc($select_details)){
                                    $New_Patient_Payment_ID = $details_data['Patient_Payment_ID'];
                                    $Receipt_Date = $details_data['Receipt_Date'];
                            }
                    }
                    
                    
       
	
			$select_docservices2 = "
			SELECT * FROM tbl_inpatient_doctorservices_cache 
				WHERE 
					Registration_ID = '$Registration_ID' AND 
					Employee_ID = '$Employee_ID'";
			$select_docservices_qry2 = mysqli_query($conn,$select_docservices2) or die(mysqli_error($conn));
			
			while($services2 = mysqli_fetch_assoc($select_docservices_qry2)){
				$DS_Cache_ID = $services2['DS_Cache_ID'];
				$Item_ID = $services2['Item_ID'];
				$Price = $services2['Price'];
				$Quantity = $services2['Quantity'];
				$Amount = $services2['Amount'];
				$reg_ID = $services2['Registration_ID'];
				$emp_ID = $services2['Employee_ID'];
				$serviceDate = $services2['Date_Time'];
				$transDate = date('Y-m-d H:i:s');
				$cons_ID = $services2['consultation_id'];
				$folio = $services2['Folio_Number'];
				$spID = $services2['Sponsor_ID'];
				$branch_id = $services2['branch_id'];
				$spName = $services2['Sponsor_Name'];
				$billType = $services2['Billing_Type'];
				
				$Check_In_Type='IPD Services';
				
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
                                                '$Check_In_Type',
                                                '$Item_ID',
                                                '$Price',
                                                '$Quantity',
                                                '0',
                                                'Others',
                                                '$Consultant',
                                                '$emp_ID',
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

				
				if($insert_ppil_qry){
					//Save the Services Permanently
					$save_docservices = "INSERT INTO 
					tbl_inpatient_doctorservices(
						Item_ID,
						Price,
						Quantity,
						Amount,
						Registration_ID,
						Employee_ID,
						consultation_id,
						Date_Time,
						Folio_Number,
						Sponsor_ID,
						Sponsor_Name,
						Billing_Type,
						branch_id)
						VALUES(
							'$Item_ID',
							'$Price',
							'$Quantity',
							'$Amount',
							'$reg_ID',
							'$emp_ID',
							'$cons_ID',
							'$transDate',
							'$folio',
							'$spID',
							'$spName',
							'$billType',
							'$branch_id'
						)
					";	
					
					$save_docservices_qry = mysqli_query($conn,$save_docservices) or die(mysqli_error($conn));				

					if($save_docservices_qry){
						$delete_cache = "DELETE FROM tbl_inpatient_doctorservices_cache WHERE DS_Cache_ID = '$DS_Cache_ID'";
						$delete_cache_qry = mysqli_query($conn,$delete_cache) or die(mysqli_error($conn));
					}
				}
			}			
		 //End billing	
			echo 'Saved!';
			
   }
?>