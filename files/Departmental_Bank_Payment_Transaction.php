<?php

session_start();
include("./includes/connection.php");
include_once("./functions/items.php");
$Amount = 0;
$Control = 'false';


//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = 0;
}


 ///remove for item dublication
    $sql_select_dublicate_item_result=mysqli_query($conn,"SELECT MAX(Payment_Item_Cache_List_ID) AS Payment_Item_Cache_List_ID FROM `tbl_item_list_cache` WHERE `Payment_Cache_ID`='$Payment_Cache_ID' GROUP BY Item_ID,`Quantity` HAVING COUNT(Item_ID)>1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_dublicate_item_result)>0){
       while($dublicate_item_rows=mysqli_fetch_assoc($sql_select_dublicate_item_result)){
           $Payment_Item_Cache_List_ID_r=$dublicate_item_rows['Payment_Item_Cache_List_ID'];
           $sql_delete_dublicate_item_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET removing_status='yes',Status='removed' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID_r'") or die(mysqli_error($conn));
       } 
    }
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

if (isset($_GET['Billing_Type'])) {
    $Billing_Type = $_GET['Billing_Type'];
} else {
    $Billing_Type = '';
}
if (isset($_GET['kutokaphamacy'])) {
    $kutokaphamacy = $_GET['kutokaphamacy'];
} else {
    $kutokaphamacy = '';
}

if ($Section == 'Pharmacy') {
    $Status = 'approved';
} else {
    $Status = 'active';
}

$otherlocs = '';
if ($Section == 'MainPharmacy') {
    $Section = 'Pharmacy';
    $Status = 'active';
    $otherlocs = '&olc=Pharmacy';
}

$src = "";
$pharmacylockupTable = "";
if (isset($_GET['src']) && $_GET['src']) {
    $src = $_GET['src'];
    if ($src == 'patlist') {
        $pharmacylockupTable = "tbl_pharmacy_items_list_cache";
    } elseif ($src == 'inpatlist') {
        $pharmacylockupTable = "tbl_pharmacy_inpatient_items_list_cache";
    }
}
if(isset($_GET['from_phamacy_cutomer_payment'])){
    /////////////////////////////////////////////////////////////////
    $Status = 'approved';
    $Section='Pharmacy';
    /////////////////////////////////////////////////////////////////
}
//die("bvbvb==>");
//$Payment_Cache_ID=15525;
//change patient depatment kwa anayohudumiwa now
  if(isset($_SESSION['Pharmacy_ID'])){
    $new_Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    $update = "update tbl_item_list_cache set Sub_Department_ID ='$new_Sub_Department_ID' where
                Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type='Pharmacy'";  
  mysqli_query($conn,$update) or die(mysqli_error($conn));
  }
    
//calculate Amount based on Section submitted
if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
    
    if($Section=="Others"){
         $ph = "select Price,Discount, Quantity, Edited_Quantity
                                                                            from tbl_item_list_cache where status = '$Status' and
                                                                            Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                            
                                                                            Transaction_Type = 'Cash' and
                                                                            Check_In_Type = '$Section' and
                                                                            ePayment_Status = 'pending'";
    }else if($Section=="Procedure"){
      $ph = "select Price,Discount, Quantity, Edited_Quantity
                                                                            from tbl_item_list_cache where status = '$Status' and
                                                                            Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                           
                                                                            Transaction_Type = 'Cash' and
                                                                            Check_In_Type = '$Section' and
                                                                            ePayment_Status = 'pending'";  
    }else{
      $ph = "select Price,Discount, Quantity, Edited_Quantity
                                                                            from tbl_item_list_cache where status = '$Status' and
                                                                            Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                            
                                                                            Transaction_Type = 'Cash' and
                                                                            Check_In_Type = '$Section' and
                                                                            ePayment_Status = 'pending'";
    
    }
   // echo "$Status $Payment_Cache_ID $Sub_Department_ID $Section";
    if (!empty($src)) {
        $ph = "select Price, Quantity, Discount, 0 AS Edited_Quantity
                    from tbl_items t, $pharmacylockupTable alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'";
       
    }
    
    $select = mysqli_query($conn,$ph) or die(mysqli_error($conn));
      
  $num_of_row= mysqli_num_rows($select);
  //die("selectuuuu$num_of_row $Sub_Department_ID $Section $Status $Payment_Cache_ID");
} else {
   
    if (strtolower($Section) == 'pharmacy') {
        $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
									from tbl_item_list_cache ilc where (ilc.status = 'approved' or ilc.status = 'active') and
									ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
									ilc.Sub_Department_ID = '$Sub_Department_ID' and
									ilc.Transaction_Type = 'Cash' and
									ilc.Check_In_Type = '$Section' and
									ilc.ePayment_Status = 'pending'";
        if (!empty($src)) {
            $ph = "select Price, Quantity, Discount, 0 AS Edited_Quantity
                    from tbl_items t, $pharmacylockupTable alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'";
        }
        
        $select = mysqli_query($conn,$ph) or die(mysqli_error($conn));
    } else {
        if($Section=="Others"){
            
              $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
								from tbl_item_list_cache ilc where ilc.status = '$Status' and
								ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
								
								ilc.Transaction_Type = 'Cash' and
								ilc.Check_In_Type = '$Section' and
								ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn) . 'this');
   
        
        }else if($Section=="Procedure"){
        $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
								from tbl_item_list_cache ilc where ilc.status = '$Status' and
								ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
								
								ilc.Transaction_Type = 'Cash' and
								ilc.Check_In_Type = '$Section' and
								ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn) . 'this');
    }else{
            
        
        $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
								from tbl_item_list_cache ilc where ilc.status = '$Status' and
								ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
								
								ilc.Transaction_Type = 'Cash' and
								ilc.Check_In_Type = '$Section' and
								ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn) . 'this');
        }
    }
}

 $num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Qty = 0;
        if ($data['Edited_Quantity'] > 0) {
            $Qty = $data['Edited_Quantity'];
        } else {
            $Qty = $data['Quantity'];
        }

        $Amount += (($data['Price'] - $data['Discount']) * $Qty);
    }
} else {
    $Amount = 0;
}

$HAS_ERROR = false;
Start_Transaction();
//die("imefika==>$Amount");
if ($Amount > 0) {
    //Insert into Item List Cahce if its phamacy origin
    if (!empty($src)) {
        $select = mysqli_query($conn,"select * from $pharmacylockupTable where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select);
        if ($num_rows > 0) {
            while ($data = mysqli_fetch_array($select)) {
                //$Folio_Number = $data['Folio_Number'];
                $Sponsor_ID = $data['Sponsor_ID'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Billing_Type = $data['Billing_Type'];
                //$Claim_Form_Number = $data['Claim_Form_Number'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Fast_Track = $data['Fast_Track'];
            }

            require_once './includes/Folio_Number_Generator.php';
            //generate transaction type
            if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                $Transaction_Type = 'Cash';
            } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
                $Transaction_Type = 'Credit';
            }
        }
        
        $consultation_id = NULL;
        if($src == 'inpatlist') {
            //get current consultation_id
            $slct = mysqli_query($conn,"select consultation_ID from tbl_check_in_details where 
        						Registration_ID = '$Registration_ID' and
        						Admit_Status = 'admitted'
        						order by Check_In_Details_ID desc limit 1") or die(mysqli_error($conn));
            $no_slct = mysqli_num_rows($slct);
            if ($no_slct > 0) {
                while ($dtz = mysqli_fetch_assoc($slct)) {
                    $consultation_id = $dtz['consultation_ID'];
                }
            } 
        }

 
        //insert data to payment cache
        //consultation id removed from the query due to some reson which lead for the failure of the system
        //incase anything happen add consultation_id in the query below
        $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
 
        if (!$insert_data) {
            $HAS_ERROR = true;
            die("1");
        }
        //get the last Payment_Cache_ID (foreign key)
        $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        
        if ($no > 0) {
            
            while ($row = mysqli_fetch_array($select)) {
                $Payment_Cache_ID = $row['Payment_Cache_ID'];
            }
           // die("$Payment_Cache_ID");
            //insert data
            $select_details = mysqli_query($conn,"select * from $pharmacylockupTable
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            $returned_rows=mysqli_num_rows($select_details);
            //die("$returned_rows");
            while ($dt = mysqli_fetch_array($select_details)) {
                $Item_ID = $dt['Item_ID'];
                $Price = $dt['Price'];
                $Discount = $dt['Discount'];
                $Quantity = $dt['Quantity'];
                $Consultant_ID = $dt['Consultant_ID'];
                $Dosage = $dt['Dosage'];
                $Clinic_ID = $dt['Clinic_ID'];
                $clinic_location_id=$dt['clinic_location_id'];
                
                if ($src == 'patlist') {
                    $finance_department_id=$dt['finance_department_id'];
                } elseif ($src == 'inpatlist') {
                    $finance_department_id=$dt['working_department'];
                }
                
                $insertPhar = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                            Check_In_Type, Item_ID,Price,Discount,
                                            Quantity, Patient_Direction, Consultant_ID,
                                            Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                            Sub_Department_ID, Transaction_Type, Service_Date_And_Time,
                                            Employee_Created, Created_Date_Time,Clinic_ID,clinic_location_id,finance_department_id) values(
                                                'Pharmacy','$Item_ID','$Price','$Discount',
                                                '$Quantity','others','$Consultant_ID',
                                                '$Status','$Payment_Cache_ID',(select now()),
                                                '$Dosage','$Sub_Department_ID','$Transaction_Type',(select now()),
                                                '$Employee_ID',(select now()),'$Clinic_ID','$clinic_location_id','$finance_department_id')") or die(mysqli_error($conn));

                if (!$insertPhar) {
                    $HAS_ERROR = true;
                    die("2");
                }
                //$sql_selectPayment_Item_Cache_List_ID=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'ORDER BY Payment_Item_Cache_List_ID DESC LIMIT 1") or die(mysqli_error($conn));
                //echo mysqli_fetch_assoc($sql_selectPayment_Item_Cache_List_ID)['Payment_Item_Cache_List_ID'];
                // Commit_Transaction();
                //die("------>$Payment_Cache_ID---->$insertPhar");
            }
        }
    }
 
	if (!empty($src)) {
	   //delete all data from cache
		$delt1 = mysqli_query($conn,"delete from $pharmacylockupTable where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
		if (!$delt1) {
			$HAS_ERROR = true;
                        die("3");
		}
    //End Inserting

	}
    

    $insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date, Source) 
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()),'Revenue Center')") or die(mysqli_error($conn) . 'One');
    if (!$insert) {
        $HAS_ERROR = true;
        die("4");
    }
    $select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn) . 'two');
    $no = mysqli_num_rows($select_result);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_result)) {
            $Transaction_ID = $row['Transaction_ID'];
        }
    } else {
        $Transaction_ID = 0;
    }
    if ($Transaction_ID != 0) {
        //get Invoice_Number
//        $get_invoice = mysqli_query($conn,"select Invoice_Number from tbl_bank_invoice_numbers where Invoice_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//        $mynum = mysqli_num_rows($get_invoice);
//        if ($mynum > 0) {
//            while ($data2 = mysqli_fetch_array($get_invoice)) {
//                $Invoice_Number = $data2['Invoice_Number'];
//            }
        $retrieve_rs = mysqli_query($conn,"SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
        $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
        $hospital_id = $data_hosp_id['hospital_id'];

        $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT) . str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);

            //update code
            $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
            if (!$update) {
                $HAS_ERROR = true;
                die("5");
            }
            //update item transaction number (tbl_item_list_cache)
            if (strtolower($Billing_Type) == 'inpatient cash') {
                if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                    $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                ilc.Status = '$Status' and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Inpatient Cash' or pc.Billing_Type = 'Inpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
                } else {
                    if (strtolower($Section) == 'pharmacy') {
                        $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                (ilc.status = 'approved' or ilc.status = 'active') and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Inpatient Cash' or pc.Billing_Type = 'Inpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
                    } else {
                        $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                ilc.Status = '$Status' and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Inpatient Cash' or pc.Billing_Type = 'Inpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
                    }
                }
            } else {
                if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                    $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                (ilc.status = 'approved' or ilc.status = 'active') and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
               
                    // $select_items=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'ORDER BY Payment_Item_Cache_List_ID DESC LIMIT 1") or die(mysqli_error($conn));
                      
                   // $nummmmmm=mysqli_num_rows($select_items);
                    //die("humoooo $Payment_Cache_ID $nummmmmm"); 
               
                } else {
                    if (strtolower($Section) == 'pharmacy') {
                        $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                (ilc.status = 'approved' or ilc.status = 'active') and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
                    } else {
                        $select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
							                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
							                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
							                                ilc.Status = '$Status' and
							                                ilc.Transaction_Type = 'Cash' and
							                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							                                (pc.Billing_Type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') and
							                                ilc.ePayment_Status = 'pending' and
							                                ilc.Check_In_Type = '$Section'") or die(mysqli_error($conn));
                    }
                    
                }
                
            }

            $num = mysqli_num_rows($select_items);
           // die("$num ==>");
            if ($num > 0) {
                while ($data = mysqli_fetch_array($select_items)) {
                    $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                    $result = mysqli_query($conn,"update tbl_item_list_cache set Transaction_ID = '$Transaction_ID', ePayment_Status = 'Served' where
															Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                    if (!$result) {
                        $HAS_ERROR = true;
                        die("6");
                    }
                }
            }else{
               $HAS_ERROR = true; 
               die("7-->");
            }

            if (!$HAS_ERROR) {         
                Commit_Transaction();
                $_SESSION['Transaction_ID'] = $Transaction_ID;
                if(isset($_GET['from_revenue_phamacy'])&&$_GET['from_revenue_phamacy']=="yes"){
                  $from_revenue_phamacy="&from_revenue_phamacy=yes";  
                }else{
                    $from_revenue_phamacy="";
                }
                header("Location: ./crdbtransactiondetails.php?Section=Departmental&CRDBTransactionDetails=CRDBTransactionDetailsThisPage&Payment_Cache_ID='$Payment_Cache_ID'&kutokaphamacy='$kutokaphamacy'$from_revenue_phamacy" . $otherlocs);
            } else {
                Rollback_Transaction();
                $Registration_ID=$_GET['Registration_ID'];
                $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
                 ?>
                    <script>
                        alert("Process Fail...try again")
                        document.location="pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?= $Registration_ID ?>&Transaction_Type=Cash&Payment_Cache_ID=<?= $Payment_Cache_ID ?>&NR=True&PharmacyWorks=PharmacyWorksThisPage"
                    </script>
            <?php
           }
       //}
    }
}
?>