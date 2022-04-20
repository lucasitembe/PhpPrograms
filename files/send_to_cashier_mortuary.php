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

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }


    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }


    if($Employee_ID != 0 && $Registration_ID !=0){

            $select_check_in = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where 
            Registration_ID = '$Registration_ID' order by Check_In_ID DESC limit 1") or die(mysqli_error($conn)); 
            $nums = mysqli_num_rows($select_check_in);
            if($nums > 0){
                while ($data = mysqli_fetch_array($select_check_in)) {
                    $Check_In_ID = $data['Check_In_ID'];
                }
            }else{
                $insert = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, Check_In_Date_And_Time, Check_In_Status, Branch_ID, Check_In_Date, Type_Of_Check_In) VALUES ('$Registration_ID',(select now()),'$Employee_ID', NOW(), 'pending', '$Branch_ID', NOW(), 'Afresh')") or die(mysqli_error($conn));
                if($insert){
                    $select_check_in = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' and Check_In_Date = '$Today' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

                    $nums = mysqli_num_rows($select_check_in);
                    if($nums > 0){
                        while ($data = mysqli_fetch_array($select_check_in)) {
                            $Check_In_ID = $data['Check_In_ID'];
                        }
                    }
                }
            }


            $Hospital_Ward_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1"))['Hospital_Ward_ID'];
            $Bill_datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_patient_payments WHERE Registration_ID = '$Registration_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1"))['Patient_Bill_ID'];
                if($Bill_datas > 0) {
                    $Patient_Bill_ID = $Bill_datas;
                }else{
                    $Insert_Bill = mysqli_query($conn, "INSERT INTO tbl_patient_bill (Registration_ID, Patient_Status, Date_Time, Status) VALUES ('$Registration_ID', 'Inpatient', NOW(), 'active')") or die(mysqli_error($conn));
    
                    if($Insert_Bill){
                        $Patient_Bill_ID = mysqli_insert_id($conn);
                    }
                }


                $select = mysqli_query($conn,"SELECT Payment_Cache_ID, Sponsor_ID, Billing_Type, branch_id from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                Employee_ID = '$Employee_ID' AND Receipt_Date = CURDATE()");

                $num_rows = mysqli_num_rows($select);
                if($num_rows > 0){
                    while($data = mysqli_fetch_array($select)){
                        //$Folio_Number = $data['Folio_Number'];
                        $Sponsor_ID = $data['Sponsor_ID'];
                        $Billing_Type = $data['Billing_Type'];
                        $Payment_Cache_ID = $data['Payment_Cache_ID'];
                        $branch_id = $data['branch_id'];
                    }
                 
                
                    //generate transaction type
                    if(strtolower($Billing_Type) != ''){
                        $Transaction_Type = 'Cash';
                    }
        
                    // echo $Registration_ID."+".$Branch_ID."+".$Employee_ID."+".$Check_In_ID."+".$Today."+".$Patient_Bill_ID."+".$Hospital_Ward_ID."+".$Sponsor_ID."+".$Billing_Type."+".$Payment_Cache_ID."+".$Transaction_Type;
                    // exit();

                    if($Payment_Cache_ID > 0){
                        $insert_payment_ID = mysqli_query($conn, "INSERT INTO tbl_patient_payments (Registration_ID, Check_In_ID, Sponsor_ID, Billing_Type, Receipt_Date, Transaction_status, payment_type, branch_id, payment_mode, Patient_Bill_ID, Hospital_Ward_ID, Pre_Paid, Payment_Date_And_Time, Employee_ID) VALUES('$Registration_ID', '$Check_In_ID', '$Sponsor_ID', '$Billing_Type', NOW(), 'active', 'post', '$branch_id', 'normal', '$Patient_Bill_ID', '$Hospital_Ward_ID','1', NOW(), '$Employee_ID')") or die(mysqli_error($conn));


                        if($insert_payment_ID){
                            $Patient_Payment_ID = mysqli_insert_id($conn);

                            $select_details = mysqli_query($conn,"SELECT * from tbl_item_list_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' AND  Check_In_Type = 'Mortuary'") or die(mysqli_error($conn));
                        
                                while($dt = mysqli_fetch_array($select_details)){
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
                                    $Discount = $dt['Discount'];
                                    $Quantity = $dt['Quantity'];
                                    $Dosage = $dt['Dosage'];
                                    $Clinic_ID = $dt['Clinic_ID'];
                                    $clinic_location_id = $dt['clinic_location_id'];
                                    $Check_In_Type = $dt['Check_In_Type'];
                                    $finance_department_id = $dt['finance_department_id'];
                                    $Sub_Department_ID = $dt['Sub_Department_ID'];
                                    $Payment_Item_Cache_List_ID = $dt['Payment_Item_Cache_List_ID'];

                                    $insert = mysqli_query($conn, "INSERT INTO tbl_patient_payment_item_list (Check_In_Type, Item_ID, Price, Discount, Quantity, Patient_Direction, Status, Patient_Payment_ID, Transaction_Date_And_Time, Sub_Department_ID,Clinic_ID,clinic_location_id,finance_department_id
                                    ) VALUES ('Mortuary', '$Item_ID', '$Price', '$Discount', '$Quantity', 'others', 'served', '$Patient_Payment_ID', NOW(), '$Sub_Department_ID', '$Clinic_ID','$clinic_location_id','$finance_department_id')") or die(mysqli_error($conn));

                                    if($insert){

                                        $Update_cache = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Status = 'paid', Patient_Payment_ID = '$Patient_Payment_ID' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
    
                                    }

                                }

                        }
                    }
                    echo "ITEM WAS ADDED SUCCESSFULLY TO THE PATIENT BILL!";
                }

                        if($insert_payment_ID){
                            header("Location: ./add_mortuary_services.php?Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&MortuaryServices=MortuaryServicesThisPage&Check_In_Type=Mortuary");
                        }
    }

?>