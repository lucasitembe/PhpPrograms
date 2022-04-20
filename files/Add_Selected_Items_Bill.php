<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
   
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    
    
        $Consultant_ID =$_SESSION['userinfo']['Employee_ID'];// $_GET['Consultant_ID'];
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
    }else{
        $Billing_Type = '';
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    if(isset($_GET['Price'])){
        $Price =  str_replace(',', '', $_GET['Price']);
    }else{
        $Price = '';
    }
    //
    if(isset($_GET['Item_Name'])){
        $Item_Name = $_GET['Item_Name'];
    }else{
        $Item_Name = '';
    }
    
     if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = '';
    }
    
    //echo $Item_Name.' '.$Price.' '.$Item_ID.' '.$Guarantor_Name.' '.$Billing_Type.' '.$Item_ID.' '.$Quantity.' '.$Registration_ID.' '.$Folio_Number;
    $Consultant='';
        
        $qr_cons=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $Consultant=  mysqli_fetch_assoc($qr_cons)['Employee_Name'];
   
    $qr=  mysqli_query($conn,"SELECT * FROM tbl_patient_payments WHERE  Registration_ID='$Registration_ID' AND Folio_Number='$Folio_Number' AND Billing_Type='$Billing_Type' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($qr) >0){
            $row= mysqli_fetch_array($qr);
            $reg_ID=$Registration_ID;
            $emp_ID=$_SESSION['userinfo']['Employee_ID'];
            $payDate='NOW()';
            $folio=$Folio_Number;
            $Claim_Form_Number=$row['Claim_Form_Number'];
            $spID=$row['Sponsor_ID'];
            $spName=$row['Sponsor_Name'];
            $billType=$Billing_Type;
            $recDate = date('Y-m-d');
            $branch_id=$row['branch_id'];
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
                                    Claim_Form_Number,
                                    Sponsor_ID,
                                    Sponsor_Name,
                                    Billing_Type,
                                    Receipt_Date,
                                    branch_id
                            ) VALUES(
                                    '$reg_ID',
                                    '$emp_ID',
                                    '$emp_ID',
                                     NOW(),
                                    '$folio',
                                    '$Claim_Form_Number',
                                    '$spID',
                                    '$spName',
                                    '$billType',
                                    '$recDate',
                                    '$branch_id'									
                            )";
                    $insert_pp_qry = mysqli_query($conn,$insert_pp) or die(mysqli_error($conn));
    if($insert_pp_qry){
                   $New_Patient_Payment_ID=0;  
                   $Receipt_Date='';
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
                    		$Item_ID = $Item_ID;
				$Price = $Price;
				$Quantity = $Quantity;
				$reg_ID = $Registration_ID;
				$emp_ID = $Employee_ID;
				
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
                                                'Others'
                                        )
                                ";
                                //Run the Query
                                $insert_ppil_qry = mysqli_query($conn,$insert_ppil) or die(mysqli_error($conn));
                                
                            if($insert_ppil_qry){
                                echo 'added';
                            }  else {
                                echo 'error';
                            }    
                        
    }