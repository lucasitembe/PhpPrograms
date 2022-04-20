<?php
    @session_start();
    include("./includes/connection.php");
    
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
    
    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get claim form number
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = 0;
    }
    
    //get Consultant_ID
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = 0;
    }

    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
    
    //get Folio Number
    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = 0;
    }
    
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
//        echo "===>1";
        //check if patient already checked in 
        $select_check_in = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where 
                                        Registration_ID = '$Registration_ID' and 
                                        Check_In_Date = '$Today'
                                        order by Check_In_ID desc limit 1") or die(mysqli_error($conn)); 
        $nums = mysqli_num_rows($select_check_in);
        if($nums > 0){
            while ($data = mysqli_fetch_array($select_check_in)) {
                $Check_In_ID = $data['Check_In_ID'];
//                echo "===>2";
            }
        }else{
            //insert data into check in table
            $insert = mysqli_query($conn,"INSERT INTO tbl_check_in(
                                    Registration_ID, Visit_Date, Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
                                    Check_In_Date, Type_Of_Check_In) 

                                    VALUES ('$Registration_ID',(select now()),'$Employee_ID',
                                            (select now()),'pending','$Branch_ID',
                                            (select now()),'Afresh')") or die(mysqli_error($conn));
//            echo "===>3";
            if($insert){
                $select_check_in = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where 
                                        Registration_ID = '$Registration_ID' and 
                                        Check_In_Date = '$Today'
                                        order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

                $nums = mysqli_num_rows($select_check_in);
                if($nums > 0){
//                    echo "===>4";
                    while ($data = mysqli_fetch_array($select_check_in)) {
                        $Check_In_ID = $data['Check_In_ID'];
                    }
                }
            }
        }

        //select all data from the reception cache table
       
            $select = mysqli_query($conn,"select * from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
       
        $num_rows = mysqli_num_rows($select);
        if($num_rows > 0){
            while($data = mysqli_fetch_array($select)){
                //$Folio_Number = $data['Folio_Number'];
                $Check_In_Type = $data['Check_In_Type'];
                $Sponsor_ID = $data['Sponsor_ID'];
                $Billing_Type = $data['Billing_Type'];
                $Claim_Form_Number = $data['Claim_Form_Number'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Fast_Track = $data['Fast_Track'];
                $Item_ID = $data['Item_ID'];
                $Discount = $data['Discount'];
                $Price = $data['Price'];
                $Quantity = $data['Quantity'];
                $Patient_Direction = $data['Patient_Direction'];
                $Consultant = $data['Consultant'];
                $Consultant_ID = $data['Consultant_ID'];
                $Clinic_ID = $data['Clinic_ID'];
                $finance_department_id = $data['finance_department_id'];
                $clinic_location_id = $data['clinic_location_id'];
            }
            
            $Transaction_Type = 'Cash';
            
            //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id,Fast_Track, Check_In_ID)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track','$Check_In_ID')") or die(mysqli_error($conn));
            if($insert_data){
                //get the last Payment_Cache_ID (foreign key)
                $select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
                    //insert data
                    $select_details = mysqli_query($conn,"SELECT * from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    
                    while($data = mysqli_fetch_array($select_details)){
                        $Check_In_Type = $data['Check_In_Type'];
                        $Sponsor_ID = $data['Sponsor_ID'];
                        $Billing_Type = $data['Billing_Type'];
                        $Claim_Form_Number = $data['Claim_Form_Number'];
                        $Sponsor_Name = $data['Sponsor_Name'];
                        $Fast_Track = $data['Fast_Track'];
                        $Item_ID = $data['Item_ID'];
                        $Discount = $data['Discount'];
                        $Price = $data['Price'];
                        $Quantity = $data['Quantity'];
                        $Patient_Direction = $data['Patient_Direction'];
                        $Consultant = $data['Consultant'];
                        $Consultant_ID = $data['Consultant_ID'];
                        
                        $Clinic_ID = $data['Clinic_ID'];
                        $finance_department_id = $data['finance_department_id'];
                        $clinic_location_id = $data['clinic_location_id'];
                        
                       $Patient_Direction=$data['Patient_Direction'];
                        if ($data['Patient_Direction'] == 'Direct To Clinic' || $data['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                            $Clinic_ID=$Consultant_ID;
                        }
                        $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
//                     die($Clinic_ID."==$finance_department_id==$clinic_location_id");
                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                            Check_In_Type, Item_ID,Price,Discount,
                                            Quantity, Patient_Direction,
                                            Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,Transaction_Type, Service_Date_And_Time,Clinic_ID,Consultant_ID,Sub_Department_ID,finance_department_id) values(
                                                'Doctor Room','$Item_ID','$Price','$Discount',
                                                '$Quantity','$Patient_Direction',
                                                'active','$Payment_Cache_ID',(select now()),
                                                '$Dosage','$Transaction_Type',(select now()),'$Clinic_ID','$Consultant_ID','$clinic_location_id','$finance_department_id')") or die(mysqli_error($conn));
                    }
                    $sq1 = mysqli_query($conn,"insert into tbl_check_in_details(Registration_ID,Check_In_ID,Folio_Number,Sponsor_ID)
                        values('$Registration_ID','$Check_In_ID','$Folio_Number','$Sponsor_ID')") or die(mysqli_error($conn));
                    if($sq1){
                        if($insert){
                            //delete all data from cache
                                mysqli_query($conn,"DELETE from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));                   
                                echo "success";
                            }else{
                                echo "fail";
                        }
                     }
                }
            }
        }
    }
?>