<?php
    @session_start();
    include("./includes/connection.php");
    $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
        $location='location=otherdepartment&';
    }
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	   $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	   $Employee_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
	   $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
	   $Branch_ID = 0;
    }
    
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = '';
    }

    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }else{
        $consultation_ID = 0;
    }

    if(isset($_GET['Spectacle_ID'])){
        $Spectacle_ID = $_GET['Spectacle_ID'];
    }else{
        $Spectacle_ID = 0;
    }
    if(isset($_GET['finance_department_id'])){
		$finance_department_id = $_GET['finance_department_id'];
	}else{
		$finance_department_id = '';
    }
    if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
        $Clinic_ID = '';
        
	}

    //get folio number and claim form number
    $slct = mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_consultation where consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if($nm > 0){
        while ($rw = mysqli_fetch_array($slct)) {
            $Patient_Payment_Item_List_ID = $rw['Patient_Payment_Item_List_ID'];
        }
    }else{
        $Patient_Payment_Item_List_ID = 0;
    }

    $slct2 = mysqli_query($conn,"select Folio_Number, Claim_Form_Number, Check_In_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    $nms = mysqli_num_rows($slct2);
    if($nms > 0){
        while ($dataz = mysqli_fetch_array($slct2)) {
            $Folio_Number = $dataz['Folio_Number'];
            $Claim_Form_Number = $dataz['Claim_Form_Number'];
            $Check_In_ID = $dataz['Check_In_ID'];
        }
    }else{
        $Folio_Number = '';
        $Claim_Form_Number = '';
        $Check_In_ID = '';
    }

    //create check in id
    if($Check_In_ID == '' || $Check_In_ID == null){
        $inserts = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
                                    Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
                                VALUES ('$Registration_ID',(select now()),'$Employee_ID',
                                        (select now()),'saved','$Branch_ID',
                                        (select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));
        if($inserts){
            $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
            while($rows = mysqli_fetch_array($select)) {
                $Check_In_ID = $rows['Check_In_ID'];
            }   
        }
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        //select all data from the departmental cache table
        $select = mysqli_query($conn,"select * from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select);
        if($num_rows > 0){
            while($data = mysqli_fetch_array($select)){
                $Sponsor_ID = $data['Sponsor_ID'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Billing_Type = $data['Billing_Type'];
                $Sponsor_Name = $data['Sponsor_Name'];
            }
            
            
            //generate transaction type
            if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
                $Transaction_Type = 'Cash';
            }else if(strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit'){
                $Transaction_Type = 'Credit';
            }
            
            //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id,consultation_ID)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$consultation_ID')") or die(mysqli_error($conn));
            if($insert_data){
                //get the last Payment_Cache_ID (foreign key)
                
                $select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    // while($row11= mysqli_fetch_array($select)){
                    //     $Payment_Cache_ID = $row11['Payment_Cache_ID'];
                    // }
                    $Payment_Cache_ID = mysqli_fetch_assoc($select)['Payment_Cache_ID'];
                    //insert data
                    $select_details = mysqli_query($conn,"SELECT * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    $numrows = mysqli_num_rows($select_details);
                    if($numrows > 0){
                        while($dt = mysqli_fetch_array($select_details)){
                            $Item_ID = $dt['Item_ID'];
                            $Price = $dt['Price'];
                            $Quantity = $dt['Quantity'];
                            $Comment = $dt['Comment'];
                            $Sub_Department_ID = $dt['Sub_Department_ID'];
                            $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                            $Clinic_ID = $dt['Clinic_ID'];
                            $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(finance_department_id,
                                                Check_In_Type, Item_ID,Price,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Status
                                                ) values('$finance_department_id',
                                                    '$Type_Of_Check_In','$Item_ID','$Price',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                    '$Comment','$Sub_Department_ID','$Transaction_Type',(select now()),'paid')") or die (mysqli_error($conn));
                                                   echo "inserted in item list";
                        }

                    }
                    $select_items = mysqli_query($conn,"SELECT ilc.Sub_Department_ID,ilc.Quantity,ilc.Clinic_ID,ilc.finance_department_id,ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, Consultant, 
										ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status, ilc.Item_ID, ilc.Consultant_ID, pc.Billing_Type
	                                    from tbl_payment_cache pc, tbl_item_list_cache ilc where
	                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and ilc.Check_In_Type = 'Optical' and
	                                    pc.Payment_Cache_ID = '$Payment_Cache_ID' and
	                                    pc.Registration_ID = '$Registration_ID' order by Payment_Item_Cache_List_ID desc limit 1") or die(mysqli_error($conn));
    	            $no = mysqli_num_rows($select_items);
                    if($no > 0){
                    //generate receipt
                    $create_receipt = mysqli_query($conn,"INSERT into tbl_patient_payments(
                                                    Registration_ID,Supervisor_ID,Employee_ID,
											Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
											Sponsor_ID,Sponsor_Name,Billing_Type,
											Receipt_Date,Branch_ID,Check_In_ID)
	                                    
									values('$Registration_ID','$Consultant_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Claim_Form_Number',
										'$Sponsor_ID','$Sponsor_Name','$Billing_Type',
										(select now()),'$Branch_ID','$Check_In_ID')") or die(mysqli_error($conn));

		            if($create_receipt){
                            $get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                        $num_rec = mysqli_num_rows($get_receipt);
                        if($num_rec > 0){
		    		    while ($row = mysqli_fetch_array($get_receipt)) {
		    			    $Patient_Payment_ID = $row['Patient_Payment_ID'];
		    			    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                        }

                        while ($rm = mysqli_fetch_array($select_items)) {
                            $Payment_Item_Cache_List_ID = $rm['Payment_Item_Cache_List_ID'];
                            $Clinic_ID=$rm['Clinic_ID'];
                            $sql = "insert into tbl_patient_payment_item_list(Check_In_Type, Item_ID, Discount, Price, Quantity,
		    											Patient_Direction, Consultant, Consultant_ID, Patient_Payment_ID,
		    											Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID) 

		    										values('".$rm['Check_In_Type']."','".$rm['Item_ID']."','".$rm['Discount']."','".$rm['Price']."','".$rm['Quantity']."',
		    											'Others','".$rm['Consultant']."','".$rm['Consultant_ID']."',".$Patient_Payment_ID.",(select now()),'$Clinic_ID','$finance_department_id','$Sub_Department_ID')";
                        $insert_item = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        if($insert_item){
		    				$update = mysqli_query($conn,"update tbl_item_list_cache set Status = 'paid', 
		    										Patient_Payment_ID = '$Patient_Payment_ID', 
		    										Payment_Date_And_Time = '$Payment_Date_And_Time' where
		    										Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                    }
                        }
                        }
                        
                    }

                    // echo $Payment_Cache_ID;
                    if($insert){
                        //check if patient exists into tbl_check_in
                        $check = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' limit 1") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($check);
                        if($num < 1){
                            $insert2 = mysqli_query($conn,"INSERT INTO tbl_check_in(
                                                    Registration_ID, Visit_Date, 
                                                    Employee_ID, Check_In_Date_And_Time, Check_In_Status, 
                                                    Branch_ID, Saved_Date_And_Time, Check_In_Date,
                                                    Type_Of_Check_In, Folio_Status) 
                                                    VALUES ('$Registration_ID',(select now()),
                                                            '$Employee_ID',(select now()),'saved',
                                                            '$Branch_ID',(select now()),(select now()),
                                                            'Afresh','generated')") or die(mysqli_error($conn));
                            
                            //delete all data from cache
                            // mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            // $update = mysqli_query($conn,"update tbl_spectacles set Spectacle_Status = 'done' where Spectacle_ID = '$Spectacle_ID'") or die(mysqli_error($conn));
                            header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
                            
                            mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                            $update = mysqli_query($conn,"update tbl_spectacles set Spectacle_Status = 'done' where Spectacle_ID = '$Spectacle_ID'") or die(mysqli_error($conn));

                            header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
                        }else{
                            //delete all data from cache
                            // mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            // $update = mysqli_query($conn,"update tbl_spectacles set Spectacle_Status = 'done' where Spectacle_ID = '$Spectacle_ID'") or die(mysqli_error($conn));
                            // header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
                            $check=mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                            $update = mysqli_query($conn,"update tbl_spectacles set Spectacle_Status = 'done' where Spectacle_ID = '$Spectacle_ID'") or die(mysqli_error($conn));
                                header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
                            echo"Deleted and updated";
                            
                            
                            
                        }
                    }else{
                        header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
                    }
                }
            }
        }}
    }
}

?>