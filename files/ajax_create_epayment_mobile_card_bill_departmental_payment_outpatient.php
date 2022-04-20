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
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    
    
    
    
      //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID,
                                        Billing_Type, Receipt_Date, branch_id)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID',
                                                'Outpatient Cash',(select now()),'$Branch_ID')") or die(mysqli_error($conn));
            if($insert_data){
                //get the last Payment_Cache_ID (foreign key)
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
                    $select_Transaction_Items = mysqli_query($conn,
                                            "select clinic_location_id,Item_ID,Type_Of_Check_In,Consultant_ID,Clinic_ID,finance_department_id,Price,Discount,Quantity,Registration_ID,Comment,Sub_Department_ID
                                            from tbl_departmental_items_list_cache alc
                                            where 
                                                Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    while($dt = mysqli_fetch_array($select_Transaction_Items)){
                        $Item_ID = $dt['Item_ID'];
                        $Price = $dt['Price'];
                        $Discount = $dt['Discount'];
                        $Quantity = $dt['Quantity'];
                        $Consultant_ID = $dt['Consultant_ID'];
                        $Comment = $dt['Comment'];
                        $Clinic_ID = $dt['Clinic_ID'];
                        $clinic_location_id = $dt['clinic_location_id'];
                        $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                        $Sub_Department_ID = $dt['Sub_Department_ID'];
                        
                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                            Check_In_Type, Item_ID,Price,Discount,
                                            Quantity, Patient_Direction, Consultant_ID,
                                            Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                            Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Clinic_ID,clinic_location_id,finance_department_id
                                            ) values(
                                                '$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                '$Quantity','others','$Consultant_ID',
                                                'active','$Payment_Cache_ID',(select now()),
                                                '$Comment','$Sub_Department_ID','Cash',(select now()),'$Clinic_ID','$clinic_location_id','$finance_department_id')") or die(mysqli_error($conn));
                    }
                    
                    if($insert){
                        //delete all data from cache
                        mysqli_query($conn,"DELETE FROM tbl_departmental_items_list_cache WHERE Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                   echo "$Payment_Cache_ID";
                        }else{
                   echo "fail";
                    }
                }
            }