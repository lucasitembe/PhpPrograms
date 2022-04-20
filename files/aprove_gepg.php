<?php
@session_start();
include("./includes/connection.php");
if(isset($_SESSION['supervisor'])) {
    if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
        if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
            $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
        }else{
            $Supervisor_ID = '';
        }
    }else{
         $Supervisor_ID = '';
    }
}else{
         $Supervisor_ID = '';
}
//end of fetching supervisor id   

//get branch ID
if(isset($_SESSION['userinfo'])){
if(isset($_SESSION['userinfo']['Branch_ID'])){
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
}else{
$Branch_ID = '';
}
}else{
$Branch_ID = '';
}
//end of fetching branch ID

//get employee id
if(isset($_SESSION['userinfo']['Employee_ID'])){
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}else{
$Employee_ID = '';
}


//insert related information to generate receipt
//get data from cache table then insert

//time to insert data  
    function Start_Transaction(){
        mysqli_query($conn,"START TRANSACTION");
    }

    function Commit_Transaction(){
        mysqli_query($conn,"COMMIT");
    }

    function Rollback_Transaction(){
        mysqli_query($conn,"ROLLBACK");
    }
    
    $str = $_POST['amount'];
    // $paidamount = $_POST['paidamount'];
    // $billID = $_POST['billID'];
    // $auth_code = $_POST['auth_code'];
    // $REGID = $_POST['REG'];
    $exploded=explode(",",$str);
    $amout = $exploded[0];
    $paidamount =$exploded[1];;
    $billID =$exploded[2];;
    $auth_code =$exploded[3];;
    $REGID =$exploded[4];;
    //$auth_code=$_POST['auth_code'];
///fuction to process feedback url
    
   Start_Transaction();
   $an_error_occured=FALSE;

//select all pending bill item
$sql_select_all_pending_transaction_result=mysqli_query($conn,"SELECT Registration_ID,Employee_ID,card_and_mobile_payment_transaction_id,Payment_Cache_ID FROM tbl_card_and_mobile_payment_transaction WHERE transaction_status='pending'") or die(mysqli_error($conn));// AND DATE(transaction_date_time)=CURDATE()
if(mysqli_num_rows($sql_select_all_pending_transaction_result)>0){
    while($transaction_id_rows=mysqli_fetch_assoc($sql_select_all_pending_transaction_result)){
        $Payment_Cache_ID=$transaction_id_rows['Payment_Cache_ID'];
        $Employee_ID=$transaction_id_rows['Employee_ID'];
        $Registration_ID=$transaction_id_rows['Registration_ID'];
        
        if($amout == $paidamount){
          
            ///if payment is completed create payment receipt in ehms and change item status to paid

            //select detail for creating receipt
            $sql_select_receipt_details_result=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$REGID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_receipt_details_result)>0){
                $check_in_id_rows=mysqli_fetch_assoc($sql_select_receipt_details_result);
                $Check_In_ID=$check_in_id_rows['Check_In_ID'];
//                echo "===>1";
                $sql_other_payment_detail_result=mysqli_query($conn,"SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_other_payment_detail_result)>0){
                   $other_payment_deta_rows=mysqli_fetch_assoc($sql_other_payment_detail_result);
                   $Folio_Number=$other_payment_deta_rows['Folio_Number'];
                   $Sponsor_ID=$other_payment_deta_rows['Sponsor_ID'];
                   $Billing_Type=$other_payment_deta_rows['Billing_Type'];
                   
//                   echo "===>2";
                   //get bill id
//                   $sql_get_bill_id_result=mysqli_query($conn,"SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$REGID' AND Status='active'") or die(mysqli_error($conn));
//                   if(mysqli_num_rows($sql_get_bill_id_result)>0){
//                       $Patient_Bill_ID=mysqli_fetch_assoc($sql_get_bill_id_result)['Patient_Bill_ID'];
//                       
//                         //get Last Patient Bill Id
                        $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($slct);
                        if ($nm > 0) {
                            while ($dts = mysqli_fetch_array($slct)) {
                                $Patient_Bill_ID = $dts['Patient_Bill_ID'];

                            }
                        } else {
                            $insert_bill = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID) values('$Registration_ID')") or die(mysqli_error($conn));
                            if ($insert_bill) {
                                //get inserted Patient Bill Id
                                $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID limit 1") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($slct);
                                if ($nm > 0) {
                                    while ($dts = mysqli_fetch_array($slct)) {
                                        $Patient_Bill_ID = $dts['Patient_Bill_ID'];

                                    }
                                }
                            }
                        }
//                       
//                       
                        ///create receipt id
                       $sql_create_receipt_result=mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date) VALUES('$REGID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$auth_code','GEPG Online',NOW(),CURDATE())") or die(mysqli_error($conn));
                   
                       if(!$sql_create_receipt_result){
                           $an_error_occured=TRUE;
                       }
//                       echo "===>3";
                       $Patient_Payment_ID = mysql_insert_id();
//                       echo "pay_id=>$Patient_Payment_ID";
//                       if($card_and_mobile_payment_transaction_id=="55"){echo "breaking_point";break;}
                       //select receipt item
                       $sql_select_receipt_items_result=mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE gepg_bill_id='$billID'") or die(mysqli_error($conn));
                       if(mysqli_num_rows($sql_select_receipt_items_result)>0){
                           while($receipt_item_rows=mysqli_fetch_assoc($sql_select_receipt_items_result)){
                               $Check_In_Type=$receipt_item_rows['Check_In_Type'];
                               $Category=$receipt_item_rows['Category'];
                               $Item_ID=$receipt_item_rows['Item_ID'];
                               $Discount=$receipt_item_rows['Discount'];
                               $Price=$receipt_item_rows['Price'];
                               $Quantity=$receipt_item_rows['Quantity'];
                               $Edited_Quantity=$receipt_item_rows['Edited_Quantity'];
                               $Patient_Direction=$receipt_item_rows['Patient_Direction'];
                               $Consultant=$receipt_item_rows['Consultant'];
                               $Consultant_ID=$receipt_item_rows['Consultant_ID'];
                               $Sub_Department_ID=$receipt_item_rows['Sub_Department_ID'];
                               $Clinic_ID=$receipt_item_rows['Clinic_ID'];
                               $finance_department_id=$receipt_item_rows['finance_department_id'];
                               $clinic_location_id=$receipt_item_rows['clinic_location_id'];
                               //echo "===>4";
                               if($Edited_Quantity > 0){
                                  $Quantity=$Edited_Quantity;
                               }
                               
                               //create receipt item
                               $sql_select_receipt_item_result=mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die(mysqli_error($conn));
                               if(!$sql_select_receipt_item_result){
                                   $an_error_occured=TRUE;
                               }

                               $select_cache_info = "select * from tbl_payment_cache where payment_cache_id = '$Payment_Cache_ID'";
                                $result = mysqli_query($conn,$select_cache_info);
                                while($row = mysqli_fetch_array($result)){
                                    $Registration_ID = $row['Registration_ID'];
                                    $Folio_Number = $row['Folio_Number'];
                                    $Sponsor_ID = $row['Sponsor_ID'];
                                    $Sponsor_Name = $row['Sponsor_Name'];
                                    $Transaction_status = 'active';
                                    $Transaction_type = 'indirect cash';
                                    //select the last claim form number
                                    $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
                                                                            Folio_number = '$Folio_Number' and
                                                                                Registration_ID = '$Registration_ID' and
                                                                                    Sponsor_ID = '$Sponsor_ID'
                                                                                        order by patient_payment_id desc limit 1");
                                    while($row = mysqli_fetch_array($select_claim_form)){
                                        $Claim_Form_Number = $row['Claim_Form_Number'];
                                    }
                                }
                               
                            //    $insert = "insert into tbl_patient_payments(
                            //     Registration_ID,Supervisor_ID,Employee_ID,
                            //         Payment_Date_And_Time,Folio_Number,
                            //             Sponsor_ID,Sponsor_Name,Billing_Type,
                            //                 Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Patient_Bill_ID,auth_code,manual_offline)
                                            
                            // values('$REGID','$Supervisor_ID','$Employee_ID',
                            //             (select now()),'$Folio_Number',
                            //                 '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                            //                     (select now()),'$Transaction_status','$Transaction_type','$Branch_ID','$Patient_Bill_ID','$auth_code','gepg_online')";
                            //    $sql_select_receipt_item_result2=mysqli_query($conn,$insert);
                            //    if(!$sql_select_receipt_item_result2){
                            //        $an_error_occured=TRUE;
                            //    }
                               //update all paid item under this transaction
                               $sql_update_transaction_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE `Payment_Cache_ID`='".$Payment_Cache_ID_paid."'") or die(mysqli_error($conn));
                               if(!$sql_update_transaction_result){
                                   $an_error_occured=TRUE;
                               }
                               //update paid item
                               $sql_update_paid_item_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE gepg_bill_id='$billID'") or die(mysqli_error($conn));
                               if(!$sql_update_paid_item_result){
                                  $an_error_occured=TRUE;
                               }
                           }
                       }
//                   }
                }
            }
          } else {
              echo 'Fail'.$amout.'>'.$paidamount;
          }
        //////////////////////////////////
    }
}
//comit or rol back transaction
if(!$an_error_occured){
    Commit_Transaction();
    echo $Patient_Payment_ID;
}else{
    Rollback_Transaction(); 
    echo "";
}


