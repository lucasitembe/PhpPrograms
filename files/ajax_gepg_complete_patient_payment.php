<?php
if(isset($_POST['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID=""; 
}
if(isset($_POST['Check_In_Type'])){
    $Check_In_Type=$_POST['Check_In_Type'];
}else{
    $Check_In_Type=""; 
}
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}else{
    $Registration_ID=""; 
}

//////////////////////////////////////////////////////////

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
    $Transaction_type = "Direct cash";
                    $get_items = mysqli_query($conn,"select p.Registration_ID, p.Folio_Number, p.Sponsor_ID, p.Sponsor_Name, p.Billing_Type, ilc.Price, ilc.Discount, ilc.Edited_Quantity, ilc.Quantity, ilc.Item_ID,
                                                ilc.Check_In_Type,ilc.Clinic_ID,ilc.finance_department_id,ilc.Consultant, ilc.Consultant_ID, ilc.Payment_Item_Cache_List_ID from 
                                                tbl_payment_cache p, tbl_item_list_cache ilc where
                                                ilc.Payment_Cache_ID = p.Payment_Cache_ID and
                                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                                ilc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                    $numz = mysqli_num_rows($get_items);
                    
                    if ($numz > 0) {
                       // echo "9";
                        while ($row = mysqli_fetch_array($get_items)) {
                            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
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
											(select now()),'" . $Branch_ID . "','" . $Check_In_ID . "','CRDB','$Payment_Code','$Patient_Bill_ID','$Transaction_Date','$Emp_ID','$Transaction_type','pre','$authorization_number','$manual_offline')";
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
                        if ($row['Patient_Direction'] == 'Direct To Clinic' || $row['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                            $locField = "Clinic_ID";
                                $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                                Check_In_Type, Item_ID,Item_Name, Discount, Price, 
                                                Quantity, Patient_Direction, Consultant, $locField, 
                                                Patient_Payment_ID, Transaction_Date_And_Time,finance_department_id) 
                                            values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                                ,'" . $Qty . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
                                                ,'" . $Patient_Payment_ID . "',(select now()),'$finance_department_id')";
                        }else{
                                $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                                Check_In_Type, Item_ID,Item_Name, Discount, Price, 
                                                Quantity, Patient_Direction, Consultant, $locField, 
                                                Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id) 
                                            values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                                ,'" . $Qty . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
                                                ,'" . $Patient_Payment_ID . "',(select now()),'$Clinic_ID','$finance_department_id')";
                        }
								//check if item arleady aded
                                                   $Item_ID=$row['Item_ID'];
                                                   $quantity_to_check=$row['Quantity'];
							$sql_select_payment_id=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID' AND Quantity='$quantity_to_check'") or die(mysqli_error($conn));
							if(mysqli_num_rows($sql_select_payment_id)<=0){
								$result2 = mysqli_query($conn,$insert_items) or die(mysqli_error($conn));
							}
								
				if($result2) {
									
                                    mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where 
													Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                                }
                        }
                        
                    }else{
                        echo "imegoma";
                    }
            