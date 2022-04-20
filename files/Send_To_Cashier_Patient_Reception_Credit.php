<?php
    @session_start();
    include("./includes/connection.php");
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    
    //delete all records which are not related whith selected patient but prepared by the current employee
    mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_id = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
    
    
    
    //get the last check in id
    $select_check_in_id = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_check_in_id)){
        $Check_In_ID = $row['Check_In_ID'];
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = '';
    }
    
    //get patient details(sponsor id & sponsor name)
    $select_Deatils = mysqli_query($conn,"select pr.Sponsor_ID, Guarantor_Name as Sponsor_Name from tbl_patient_registration pr, tbl_sponsor sp where
                                    sp.Sponsor_ID = pr.Sponsor_ID and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Deatils)){
        $Sponsor_ID = $row['Sponsor_ID'];
        $Sponsor_Name = $row['Sponsor_Name']; 
    }
    $Payment_Date_And_Time = 'SELECT NOW()';
    $Billing_Type = 'Outpatient Cash';
    $Receipt_Date = 'SELECT NOW()';
    $Transaction_status = 'submitted';
    //insert data into tbl_patient_payments_cache
    $insert_patient_qr = "INSERT INTO tbl_patient_payments_cache(
                          Registration_ID, Employee_ID, Check_In_ID,
                          Payment_Date_And_Time, Sponsor_ID, Sponsor_Name,
                          Billing_Type, Receipt_Date, Transaction_status,
                          branch_id)
                          
                          VALUES('$Registration_ID','$Employee_ID','$Check_In_ID',
                          (select now()),'$Sponsor_ID','$Sponsor_Name',
                          '$Billing_Type',(select now()),'$Transaction_status',
                          '$Branch_ID')";
    if(!mysqli_query($conn,$insert_patient_qr)){
        die(mysqli_error($conn));
    }else{
        //get last insert id
        $id_result = mysqli_query($conn,"SELECT Patient_Payment_Cache_ID FROM tbl_patient_payments_cache
                                    where Employee_ID = '$Employee_ID' and
                                        Registration_ID = '$Registration_ID' order by Patient_Payment_Cache_ID desc LIMIT 1") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($id_result);
        if($num_rows > 0){
            while($row = mysqli_fetch_array($id_result)){
                $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
            }
        }else{
            $Patient_Payment_Cache_ID = 0;
        }
        
        if($Patient_Payment_Cache_ID != 0){
            $sql_send = mysqli_query($conn,"insert into tbl_patient_payment_item_list_cache(
                        Patient_Payment_Cache_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Consultant_ID,Clinic_ID,finance_department_id)
                        
                        SELECT $Patient_Payment_Cache_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Consultant_ID,Clinic_ID,finance_department_id from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                
            if($sql_send){
                mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            }
        }
    }
    
    if($sql_send){ 
        header("Location: ./visitorform.php?VisitorForm=VisitorFormThisPage");
    }
?>