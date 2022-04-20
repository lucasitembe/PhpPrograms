<?php
include_once("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
}else{
    $start_date=""; 
}
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date']; 
}else{
   $end_date=""; 
}
    //move finance id for item that has no id
    $sql_select_all_item_that_has_no_finance_id_result=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE finance_department_id='0' AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_item_that_has_no_finance_id_result)>0){
        while($item_list_rows=mysqli_fetch_assoc($sql_select_all_item_that_has_no_finance_id_result)){
            $Patient_Payment_ID=$item_list_rows['Patient_Payment_ID'];
            //select payment_code
            $sql_select_payment_code_result=mysqli_query($conn,"SELECT Payment_Code FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Payment_Code IS NOT NULL") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_payment_code_result)>0){
                $Payment_Code=mysqli_fetch_assoc($sql_select_payment_code_result)['Payment_Code'];
                $sql_select_finance_and_clinic_loc_id_result=mysqli_query($conn,"SELECT finance_department_id,clinic_location_id FROM tbl_bank_items_detail_cache WHERE Transaction_ID IN (SELECT Transaction_ID FROM tbl_bank_transaction_cache WHERE Payment_Code='$Payment_Code')") or die(mysqli_error($conn));
            
                if(mysqli_num_rows($sql_select_finance_and_clinic_loc_id_result)>0){
                    $finance_clin_id_rows=mysqli_fetch_assoc($sql_select_finance_and_clinic_loc_id_result);
                   $finance_department_id=$finance_clin_id_rows['finance_department_id'];
                   $clinic_location_id=$finance_clin_id_rows['clinic_location_id'];
                   //update finance dept id
                    $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id' WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
                    if($update_result){
                        echo "updated by Payment_Code\n";
                    }
                }
            }
            
            //select finance dept id,clinic location id
            $sql_select_finance_dept_id_and_clinic_location_result=mysqli_query($conn,"SELECT finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND finance_department_id<>'0' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_finance_dept_id_and_clinic_location_result)){
                $financ_loc_rows=mysqli_fetch_assoc($sql_select_finance_dept_id_and_clinic_location_result);
                $finance_department_id=$financ_loc_rows['finance_department_id'];
                $clinic_location_id=$financ_loc_rows['clinic_location_id'];
                //update finance dept id
                $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id' WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
            
                if($update_result){
                    echo "updated from item list catch\n";
                }
            }
            
            
        }
        
        $sql_select_all_item_that_has_no_finance_id_result=mysqli_query($conn,"SELECT pp.Patient_Payment_ID,Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID AND finance_department_id='0' AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_all_item_that_has_no_finance_id_result)>0){
        while($item_list_rows=mysqli_fetch_assoc($sql_select_all_item_that_has_no_finance_id_result)){
                $Patient_Payment_ID=$item_list_rows['Patient_Payment_ID'];
                $Registration_ID=$item_list_rows['Registration_ID'];
                $sql_select_finance_department_id_result=mysqli_query($conn,"SELECT finance_department_id FROM tbl_patient_payments pp,tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND finance_department_id<>'0' ORDER BY pp.Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_finance_department_id_result)>0){
                   $finance_department_id=mysqli_fetch_assoc($sql_select_finance_department_id_result)['finance_department_id']; 
                   //update finance dept id
                    $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id' WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));

                    if($update_result){
                        echo "updated by Registration_ID\n";
                    }
                }
            }
        }
        
        
        
//        $sql_select_all_item_that_has_no_finance_id_result2=mysqli_query($conn,"SELECT pp.Patient_Payment_ID,Registration_ID FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID AND Sub_Department_ID='0' AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
//        if(mysqli_num_rows($sql_select_all_item_that_has_no_finance_id_result2)>0){
//        while($item_list_rows=mysqli_fetch_assoc($sql_select_all_item_that_has_no_finance_id_result2)){
//                $Patient_Payment_ID=$item_list_rows['Patient_Payment_ID'];
//                $Registration_ID=$item_list_rows['Registration_ID'];
//                $sql_select_finance_department_id_result=mysqli_query($conn,"SELECT Sub_Department_ID,Item_ID FROM tbl_patient_payments pp,tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID=ppl.Patient_Payment_ID AND pp.Registration_ID='$Registration_ID' AND Sub_Department_ID<>'0' ORDER BY pp.Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
//                if(mysqli_num_rows($sql_select_finance_department_id_result)>0){
//                   
//                   //update finance dept id
//                   while($sub_d_rows=mysqli_fetch_assoc($sql_select_finance_department_id_result)){
//                    $Sub_Department_ID=$sub_d_rows['Sub_Department_ID']; 
//                    $Item_ID=$financ_loc_rows['Item_ID'];
//                    $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Sub_Department_ID='$Sub_Department_ID' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
//
//                    if($update_result){
//                        echo "SUB_DEPARTMENT updated by Registration_ID\n";
//                    }
//                   }
//                }
//            }
//        }
        
        
            
    }
        //move finance id for item that has no id
    $sql_select_all_item_that_has_no_finance_id_result2=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Sub_Department_ID='0' AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
    $returned_rows= mysqli_num_rows($sql_select_all_item_that_has_no_finance_id_result2);
    echo "juu returned_rows===>$returned_rows";
    if($returned_rows>0){
        while($item_list_rows=mysqli_fetch_assoc($sql_select_all_item_that_has_no_finance_id_result2)){
            $Patient_Payment_ID=$item_list_rows['Patient_Payment_ID'];
            
            //echo "juu::::Patient_Payment_ID===>$Patient_Payment_ID\n\n\n\n";
            //select finance dept id,clinic location id
            $sql_select_finance_dept_id_and_clinic_location_result2=mysqli_query($conn,"SELECT Sub_Department_ID,Item_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Sub_Department_ID<>'0'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_finance_dept_id_and_clinic_location_result2)){
                while($financ_loc_rows=mysqli_fetch_assoc($sql_select_finance_dept_id_and_clinic_location_result2)){
                    $Sub_Department_ID=$financ_loc_rows['Sub_Department_ID'];
                    $Item_ID=$financ_loc_rows['Item_ID'];
                  //update finance dept id
                    $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Sub_Department_ID='$Sub_Department_ID' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

                    if($update_result){
                        echo "SUB_DEPARTMENT==>$Sub_Department_ID Item_id==>$Item_ID ===>patient_p===>$Patient_Payment_ID updated from item list catch\n";
                    }  
                }
            }
        }
    }
	 echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
        //move finance id for item that has no id
    $sql_select_all_item_that_has_no_finance_id_result3=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Sub_Department_ID='0' AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Patient_Payment_ID") or die(mysqli_error($conn));
    $returned_rows= mysqli_num_rows($sql_select_all_item_that_has_no_finance_id_result3);
    echo "chini returned_rows===>$returned_rows";
    if($returned_rows>0){
        while($item_list_rows=mysqli_fetch_assoc($sql_select_all_item_that_has_no_finance_id_result3)){
            $Patient_Payment_ID=$item_list_rows['Patient_Payment_ID'];
            
           // echo "chini ::::Patient_Payment_ID===>$Patient_Payment_ID\n\n\n\n";
            $sql_select_payment_code_result2=mysqli_query($conn,"SELECT Payment_Code FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Payment_Code IS NOT NULL") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_payment_code_result2)>0){
               while($Payment_Code_rows=mysqli_fetch_assoc($sql_select_payment_code_result2)){
				$Payment_Code=$Payment_Code_rows['Payment_Code'];
             // echo "Payment_Code==>$Payment_Code \n";
               //
                //select transaction id
                 $sql_select_transaction_id_result=mysqli_query($conn,"SELECT Transaction_ID FROM tbl_bank_transaction_cache WHERE Payment_Code='$Payment_Code'") or die(mysqli_error($conn));
               // echo "---------------------------------------------------------------------";
                $trans_rows=mysqli_num_rows($sql_select_transaction_id_result);
               // echo "\n returned transction id rows $trans_rows";
                if($trans_rows>0){
                    $Transaction_ID=mysqli_fetch_assoc($sql_select_transaction_id_result)['Transaction_ID'];
                    echo "Transaction_ID===>$Transaction_ID\n";
                    //select subdepartment id
                     $sql_select_finance_dept_id_and_clinic_location_result2=mysqli_query($conn,"SELECT Sub_Department_ID,Item_ID FROM tbl_item_list_cache WHERE Transaction_ID='$Transaction_ID' AND Sub_Department_ID<>'0'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_finance_dept_id_and_clinic_location_result2)){
                        while($financ_loc_rows=mysqli_fetch_assoc($sql_select_finance_dept_id_and_clinic_location_result2)){
                            $Sub_Department_ID=$financ_loc_rows['Sub_Department_ID'];
                            $Item_ID=$financ_loc_rows['Item_ID'];
                          //update finance dept id
						  echo "subdepartment_id ===>$Sub_Department_ID\n";
                            $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Sub_Department_ID='$Sub_Department_ID' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

                            if($update_result){
                                echo "\n From---Transaction_id ----SUB_DEPARTMENT==>$Sub_Department_ID Item_id==>$Item_ID ===>patient_p===>$Patient_Payment_ID updated from bank transaction cache catch\n";
                            }  
                        }
                    }
                    ///fix from bank table
                    $sql_fix_from_bank_rslt=mysqli_query($conn,"SELECT clinic_location_id,Item_ID FROM tbl_bank_items_detail_cache WHERE Transaction_ID='$Transaction_ID' AND clinic_location_id<>'0'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_fix_from_bank_rslt)>0){
                        while($bank_item_rows=mysqli_fetch_assoc($sql_fix_from_bank_rslt)){
                           $Sub_Department_ID=$bank_item_rows['clinic_location_id'];
                           $Item_ID=$bank_item_rows['Item_ID'];
                           $update_result=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Sub_Department_ID='$Sub_Department_ID' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));

                            if($update_result){
                                echo "\n From---Transaction_id ----SUB_DEPARTMENT==>$Sub_Department_ID Item_id==>$Item_ID ===>patient_p===>$Patient_Payment_ID updated from bank item details cach catch\n";
                            }  
                        }
                    }
                    
                    }
		}}
        }
    }
    echo "********************************************";