<?php
    @session_start();
    include("./includes/connection.php");
    include './audit_trail.php';

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    } else {
        $Registration_ID = 0;
    }

    if (isset($_GET['Item_ID'])) {
        $Item_ID = $_GET['Item_ID'];
    } else {
        $Item_ID = 0;
    }

    if(isset($_GET['Item_Name'])){
        $Item_Name = $_GET['Item_Name'];
    }else{
        $Item_Name = '';
    }

    if (isset($_GET['Dosage'])) {
        $Dosage = $_GET['Dosage'];
    } else {
        $Dosage = '';
    }
    if (isset($_GET['Clinic_ID'])) {
        $Clinic_ID = $_GET['Clinic_ID'];
    } else {
        $Clinic_ID = '';
    }
    if (isset($_GET['working_department'])) {
        $finance_department_id = $_GET['working_department'];
    } else {
        $finance_department_id = '';
    }
    if (isset($_GET['clinic_location_id'])) {
        $clinic_location_id = $_GET['clinic_location_id'];
    } else {
        $clinic_location_id = '';
    }

    if (isset($_GET['Quantity'])) {
        $Quantity = $_GET['Quantity'];
    } else {
        $Quantity = 0;
    }

    if (isset($_GET['Discount'])) {
        $Discount = $_GET['Discount'];
    } else {
        $Discount = 0;
    }

    if (isset($_GET['Consultant_ID'])) {
        $Consultant_ID = $_GET['Consultant_ID'];
    } else {
        $Consultant_ID = '';
    }

    if (isset($_GET['Billing_Type'])) {
        $Billing_Type = $_GET['Billing_Type'];
    } else {
        $Billing_Type = '';
    }

    if (isset($_GET['Guarantor_Name'])) {
        $Guarantor_Name = $_GET['Guarantor_Name'];
    } else {
        $Guarantor_Name = '';
    }

    if (isset($_GET['Sponsor_ID'])) {
        $Sponsor_ID = $_GET['Sponsor_ID'];
    } else {
        $Sponsor_ID = 0;
    }

    if (isset($_GET['Claim_Form_Number'])) {
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    } else {
        $Claim_Form_Number = '';
    }

    if (isset($_GET['Transaction_Mode'])) {
        $Transaction_Mode = $_GET['Transaction_Mode'];
    } else {
        $Transaction_Mode = 'Normal Transaction';
    }

    if (isset($_GET['Price'])) {
        $Price = $_GET['Price'];
    } else {
        $Price = 0;
    }

    if(isset($_GET['working_department'])){
        $working_department = $_GET['working_department'];
    }else{
        $working_department = 0;
    }


    $Brand_Id = $_SESSION['userinfo']['Branch_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    date_default_timezone_set("Africa/Nairobi");

    # Get today date
    $sql_date_time = mysqli_query($conn,"SELECT NOW() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.'00:00:00';
    $End_Date = $Current_Date_Time;

    $date=date_create($Filter_Value);
    $check =  date_format($date,"Y-m-d H:i:s");

    #Get payment cache created today
    $today_date_j=date('Y-m-d');

    # check if check in available for tha t day 
    $getCheckinOfTheFDay = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1 ");
    if(mysqli_num_rows($getCheckinOfTheFDay) < 1){
        # create payment 
        $createCheck = mysqli_query($conn,"INSERT INTO tbl_check_in (Registration_ID,Visit_Date,Employee_ID,Check_In_Date_And_Time,Check_In_Status,Branch_ID,Saved_Date_And_Time,Check_In_Date,Type_Of_Check_In,Folio_Status,Referral_Status,visit_type,pf3,Diceased,Type_of_patient_case)
                                           VALUES('$Registration_ID','$today_date_j','$Employee_ID',NOW(),'saved',1,NOW(),'$today_date_j','Afresh','pending','no','Afresh','no','no','new_case')");
        
        if(!$createCheck){
            die(mysqli_errno($conn));
        }
    }

    $get_payment_cache = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Sponsor_ID = '$Sponsor_ID'
                                             AND Receipt_Date = CURDATE() ORDER BY 
                                             Payment_Cache_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_payment_cache) > 0){
        while($data = mysqli_fetch_assoc($get_payment_cache)){
            $Payment_cache_id = $data['Payment_Cache_ID'];
        }
    }else{
        #create todays payment cache
        $new_payment_cache = mysqli_query($conn,"INSERT INTO tbl_payment_cache (Registration_ID,Employee_ID,Payment_Date_And_Time,Folio_Number,
                                                 Receipt_Date,Billing_Type,Sponsor_ID,Transaction_status,Order_Type,branch_id) 
                                                 VALUES ('$Registration_ID','$Employee_ID',NOW(),0,'$Filter_Value','$Billing_Type',
                                                 '$Sponsor_ID','active','normal','$Brand_Id')") or die(mysqli_error($conn));
        if($new_payment_cache){
            #Get payment cache created today
            $new_payment = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Payment_Date_And_Time BETWEEN '$check' AND '$Current_Date_Time' ORDER BY Payment_Cache_ID DESC LIMIT 1");
            while($data = mysqli_fetch_array($new_payment)){
                $Payment_cache_id = $data['Payment_Cache_ID'];
            }
        }else{
            echo "Something went wrong";
        }
    }

    #get sponsor payment method
    $payment_method = "";
    $get_payment_method = mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'");
    while($data = mysqli_fetch_assoc($get_payment_method)){
        $payment_method = $data['payment_method'];
    }

    #get item price 
    $itemSpecResult = mysqli_query($conn,"SELECT Items_Price from tbl_item_price where Item_ID = '$Item_ID' AND Sponsor_ID = '$Sponsor_ID'");
    while($price_data = mysqli_fetch_assoc($itemSpecResult)){
        $item_price = $price_data['Items_Price'];
    }

    if($payment_method == "credit"){
        $new_payment_cache = "Credit";
    }else if($payment_method == "cash"){
        $new_payment_cache = "Cash";
    }

    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $select_sub_department = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }

    # Insert the item in item list cache
    $insert_item_in_item_list_cache = mysqli_query($conn,"INSERT INTO tbl_item_list_cache (Check_In_Type,Consultant_ID,Transaction_Date_And_Time,Transaction_Type,
                                                            Category,Item_ID,Item_Name,Discount,dose,
                                                            discount_by,Price,Quantity,Edited_Quantity,Patient_Direction,Status,Employee_Created,
                                                            Created_Date_Time,Payment_Cache_ID,Sub_Department_ID,
                                                            Clinic_ID,brand_id,finance_department_id,Doctor_Comment) 
                                                            VALUES('Pharmacy',$Consultant_ID,NOW(),'$new_payment_cache','indirect $new_payment_cache','$Item_ID','$Item_Name','$Discount','$Quantity','$Employee_ID',
                                                            '$item_price','$Quantity','$Quantity','other','active','$Employee_ID',NOW(),'$Payment_cache_id',
                                                            '$Sub_Department_ID','$Clinic_ID','$Brand_Id','$working_department','$Dosage')") or die(mysqli_error($conn));

    if($insert_item_in_item_list_cache){
        echo 'Item Added Successfully';
    }else{
        echo mysqli_error($conn);
    }
?>
