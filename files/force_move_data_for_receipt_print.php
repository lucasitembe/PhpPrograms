<?php
include("./includes/connection.php");
@session_start();
if(isset($_GET['Payment_Code'])){
    $Payment_Code=$_GET['Payment_Code'];  
}else{
    $Payment_Code=""; 
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];  
}else{
    $Registration_ID=""; 
}
if(isset($_GET['Transaction_ID'])){
    $Transaction_ID=$_GET['Transaction_ID'];  
}else{
    $Transaction_ID=""; 
}
if(isset($_GET['authorization_number'])){
    $authorization_number=$_GET['authorization_number']; 
if(empty($authorization_number)||$authorization_number==NULL||$authorization_number==""){
     $manual_offline="online";   
   }else{
      $manual_offline="waiting_ack"; 
   }
    
}else{
    $manual_offline="online";  
    $authorization_number=""; 
}

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
$Check_In_ID="";
$Transaction_Date_Time = "";
$Transaction_Date = "";
$Check_In_ID = "";
  $select = mysqli_query($conn,"select Check_In_ID,Branch_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
        $nums = mysqli_num_rows($select);
        if ($nums > 0) {
            while ($rows = mysqli_fetch_array($select)) {
                $Check_In_ID = $rows['Check_In_ID'];
                $Branch_ID = $rows['Branch_ID'];
                //echo "1";
            }
        }else{
            $inserts = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
		    								Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
		    								Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
		    							VALUES ('$Registration_ID',(select now()),'$Employee_ID',
		    									(select now()),'saved','$Branch_ID',
		    									(select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));
            if ($inserts) {
                $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                while ($rows = mysqli_fetch_array($select)) {
                    $Check_In_ID = $rows['Check_In_ID']; //new check in id
                }
            }
        }
       ///////////////////////////////
        $sql_select_bank_transaction_detail=mysqli_query($conn,"SELECT *FROM tbl_bank_transaction_cache WHERE Transaction_ID='$Transaction_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        $nums2 = mysqli_num_rows($sql_select_bank_transaction_detail);
        if ($nums2 > 0) {
            while ($rows2 = mysqli_fetch_array($sql_select_bank_transaction_detail)) {
                //$Employee_ID = $rows2['Employee_ID'];
                $Transaction_Date_Time = $rows2['Transaction_Date_Time'];
                $Transaction_Date = $rows2['Transaction_Date'];
               //  echo "2";
            }
        }
        //echo "registation=>$Registration_ID";
        /////////////////////////////////////////////
//        $select_sponsor_id=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
//        if ($nums3 > 0) {
//            while ($rows3 = mysqli_fetch_array($select_sponsor_id)) {
//                $Sponsor_ID = $rows3['Sponsor_ID'];
//               echo "3";
//            }
//        }
        ///////////////////////////////
// $sql_payment_details="INSERT INTO tbl_patient_payments (Registration_ID,Supervisor_ID,Employee_ID,Payment_Date_And_Time,Check_In_ID,Branch_ID,Sponsor_ID,Billing_Type,Receipt_Date,payment_mode,Patient_Bill_ID,Payment_Code,payment_type)
//     VALUES('$Registration_ID','$Employee_ID','$Transaction_Date_Time','$Check_In_ID','$Branch_ID','$Sponsor_ID')
//    ";
// 
 
 
 

    $select_employee = mysqli_query($conn,"select tc.Employee_ID, eb.Branch_ID from tbl_bank_transaction_cache tc
                                                    join tbl_branch_employee eb  ON tc.Employee_ID=eb.Employee_ID 
                                                    where Payment_Code = '$Payment_Code'  order by Transaction_ID desc limit 1") or die(mysqli_error($conn));

    $data_employee = mysqli_fetch_assoc($select_employee);
    $Emp_ID = $data_employee['Employee_ID'];
     
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Emp_ID = $_SESSION['userinfo']['Employee_ID'];
    }
    
    
    $Branch_ID = $data_employee['Branch_ID'];



        //get Last Patient Bill Id
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
        




        
//            $count = mysqli_query($conn,"SELECT COUNT(ad.Registration_ID) AS num FROM tbl_admission ad,tbl_check_in cn,tbl_patient_registration pr WHERE ad.Registration_ID= pr.Registration_ID AND cn.Registration_ID= pr.Registration_ID AND cn.Check_In_ID='$Check_In_ID'");
//            $num = mysqli_fetch_assoc($count)['num'];
//            if ($num > 0) {
                
                $Transaction_type = "Direct cash";
//            } else {
//                
//                $Transaction_type = "indirect cash";
//            }
        
       
           

             
                    $get_items = mysqli_query($conn,"select ilc.Patient_Direction,ilc.Sub_Department_ID,p.Registration_ID, p.Folio_Number, p.Sponsor_ID, p.Sponsor_Name, p.Billing_Type, ilc.Price, ilc.Discount, ilc.Edited_Quantity, ilc.Quantity, ilc.Item_ID,
                                                ilc.Check_In_Type,ilc.Clinic_ID,ilc.finance_department_id,ilc.Consultant, ilc.Consultant_ID, ilc.Payment_Item_Cache_List_ID from 
                                                tbl_payment_cache p, tbl_item_list_cache ilc where
                                                ilc.Payment_Cache_ID = p.Payment_Cache_ID and
                                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                                ilc.Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
                    $numz = mysqli_num_rows($get_items);
                    if($numz<=0){
                    $get_items = mysqli_query($conn,"select * from tbl_bank_items_detail_cache where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
                    $numz = mysqli_num_rows($get_items);
                    }
                    if ($numz > 0) {
                       // echo "9";
                        while ($row = mysqli_fetch_array($get_items)) {
                            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
                            $Sub_Department_ID = $row['Sub_Department_ID'];
                            //get quantity
							 $row['Consultant_ID'];
							
                           

                             
                                $sql = "INSERT INTO tbl_patient_payments(
											Registration_ID, Supervisor_ID, Employee_ID,
											Payment_Date_And_Time, Folio_Number,
											Sponsor_ID, Billing_Type, 
											Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,Patient_Bill_ID,Transaction_Date,ePayment_Invoice_Creator,Transaction_type,payment_type,auth_code,manual_offline)

											VALUES ('" .$Registration_ID . "','" . $Emp_ID . "','" . $Emp_ID . "',
											(select now()),'" . $row['Folio_Number'] . "',
											'" . $row['Sponsor_ID'] . "','" . $row['Billing_Type'] . "',
											(select now()),'" . $Branch_ID . "','" . $Check_In_ID . "','CRDB..','$Payment_Code','$Patient_Bill_ID','$Transaction_Date','$Emp_ID','$Transaction_type','pre','$authorization_number','$manual_offline')";
								//check if arleady existed
								$sql_select_payment=mysqli_query($conn,"SELECT Payment_Code FROM tbl_patient_payments WHERE Payment_Code='$Payment_Code' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
								if(mysqli_num_rows($sql_select_payment)<=0){
									$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
								}
								
                                $Patient_Payment_ID = 0;
                                $Payment_Date_And_Time = '';
                                
                                    //get receipt number
                                    $get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where 
			                    								Registration_ID='$Registration_ID' AND Payment_Code='$Payment_Code'") or die(mysqli_error($conn));
                                    if ($get_receipt) {
                                        while ($ddt = mysqli_fetch_array($get_receipt)) {
                                            $Patient_Payment_ID = $ddt['Patient_Payment_ID'];
                                            $Payment_Date_And_Time = $ddt['Payment_Date_And_Time'];
                                        }
                                    } else {
                                        $Patient_Payment_ID = 0;
                                        $Payment_Date_And_Time = '';
                                    }
                                
                           
						$locField = "Consultant_ID";
                          $Qty = $row['Edited_Quantity'];                      
                         if ($row['Edited_Quantity'] > 0) {
                                $Qty = $row['Edited_Quantity'];
                            } else {
                                $Qty = $row['Quantity'];
                            }
                        
                        $Clinic_ID=$row['Clinic_ID'];
                        $finance_department_id=$row['finance_department_id'];
//                        die($row['Patient_Direction'].$Clinic_ID);
                        $Sub_Department_ID=$row['Sub_Department_ID'];
                        if ($row['Patient_Direction'] == 'Direct To Clinic' || $row['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                            $locField = "Clinic_ID";
                            $Consultant_ID=$Clinic_ID;
//                            echo "doctor room";
                        if($row['Consultant_ID']==""||$row['Consultant_ID']==null){
                               $locField=""; 
                               $Consultant_ID='';
							   echo "imo";
                        }else{
							echo "haimo";
                           $locField.=",";
                           $Consultant_ID=$row['Consultant_ID'];
                           $Consultant_ID=",'$Clinic_ID'";
                        }
//				echo "$locField====>>>>$Consultant_ID";		
                                $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                                Check_In_Type, Item_ID,Item_Name, Discount, Price, 
                                                Quantity, Patient_Direction, Consultant, $locField 
                                                Patient_Payment_ID, Transaction_Date_And_Time,finance_department_id,Sub_Department_ID) 
                                            values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                                ,'" . $Qty . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "'$Consultant_ID
                                                ,'" . $Patient_Payment_ID . "',(select now()),'$finance_department_id','$Sub_Department_ID')";
                        }else{
							if($row['Consultant_ID']==""||$row['Consultant_ID']==null){
                               $locField=""; 
                               $Consultant_ID='';
							   echo "imo";
                        }else{
							echo "haimo";
                           $locField.=",";
                           $Consultant_ID=$row['Consultant_ID'];
                           $Sub_Department_ID=$row['Sub_Department_ID'];
                           $Consultant_ID=",'$Consultant_ID'";
                        }
                        if($row['Billing_Type']=="Inpatient Cash"||$row['Billing_Type']=="Inpatient Credit"){
                            $Clinic_ID=NULL;
                        }
						
                                $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                                Check_In_Type, Item_ID,Item_Name, Discount, Price, 
                                                Quantity, Patient_Direction, Consultant, $locField 
                                                Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID) 
                                            values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                                ,'" . $Qty . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "'$Consultant_ID
                                                ,'" . $Patient_Payment_ID . "',(select now()),'$Clinic_ID','$finance_department_id','$Sub_Department_ID')";
                        }
								//check if item arleady aded
                                                   $Item_ID=$row['Item_ID'];
                                                   $quantity_to_check=$row['Quantity'];
							$sql_select_payment_id=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID' AND Quantity='$quantity_to_check'") or die(mysqli_error($conn));
							if(mysqli_num_rows($sql_select_payment_id)<=0){
								$result2 = mysqli_query($conn,$insert_items) or die(mysqli_error($conn));
							}
								
								if ($result2) {
									$sql_update_complete_status=mysqli_query($conn,"UPDATE tbl_bank_transaction_cache SET Transaction_Status='Completed' WHERE Transaction_ID='$Transaction_ID'") or die(mysqli_error($conn));
                                
                                    mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where 
													Transaction_ID='$Transaction_ID'") or die(mysqli_error($conn));
                                }
                        }
                        
                    }else{
                        echo "imegoma";
                    }
            