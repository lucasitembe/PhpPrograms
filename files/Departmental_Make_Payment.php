<?php
@session_start();
include("./includes/constants.php");
include("./includes/connection.php");
require_once './functions/items.php';
$location = '';
if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    $location = 'location=otherdepartment&';
}

if (!isset($_SESSION['supervisor'])) {
    header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
}
 
         if(isset($_GET['auth_code'])){
             $auth_code=$_GET['auth_code'];
         }else{
             $auth_code='';
         }
          if(isset($_GET['terminal_id'])){
             $terminal_id=$_GET['terminal_id'];
         }else{
             $terminal_id='';
         }
         $manual_offline=$_GET['manual_offline'];
         
//---get supervisor id 
if (isset($_SESSION['supervisor'])) {
    if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
        if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
            $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
        } else {
            $Supervisor_ID = '';
        }
    } else {
        $Supervisor_ID = '';
    }
} else {
    $Supervisor_ID = '';
}
//end of fetching supervisor id
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

if (isset($_GET['Consultant_ID'])) {
    $Consultant_ID = $_GET['Consultant_ID'];
} else {
    $Consultant_ID = '';
}

//get registration id
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

$Payment_Code = '';
if (isset($_GET['Payment_Code'])) {
    $Payment_Code = $_GET['Payment_Code'];
}

$trans_id_rem = '';
if (isset($_GET['trans_id_rem'])) {
    $trans_id_rem = $_GET['trans_id_rem'];
}

$trans_id = '';
if (isset($_GET['trans_id'])) {
    $trans_id = $_GET['trans_id'];
}
//get sponsor id && name
$get_spo = mysqli_query($conn,"SELECT sp.Sponsor_ID, sp.Guarantor_Name from tbl_sponsor sp, tbl_patient_registration pr where
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$nm = mysqli_num_rows($get_spo);
if ($nm > 0) {
    while ($dtails = mysqli_fetch_array($get_spo)) {
        $Sponsor_ID = $dtails['Sponsor_ID'];
        $Guarantor_Name = $dtails['Guarantor_Name'];
    }
} else {
    $Sponsor_ID = '';
    $Guarantor_Name = '';
}

//get claim form number
if (isset($_GET['Claim_Form_Number'])) {
    $Claim_Form_Number = $_GET['Claim_Form_Number'];
} else {
    $Claim_Form_Number = '';
}

//get visit type
if (isset($_GET['Visit_Type'])) {
    $Visit_Type = $_GET['Visit_Type'];
} else {
    $Visit_Type = '';
}

$is_manul_epay = false;
if (isset($_GET['src']) && !empty($_GET['src']) && $_GET['src'] == 'manualepay') {
    $src = $_GET['manualepay'];
    $is_manul_epay = true;
}

//get visit controler to control folio number generation
if (isset($_GET['Visit_Controler'])) {
    $Visit_Controler = $_GET['Visit_Controler'];
} else {
    $Visit_Controler = '';
}


if ($is_manul_epay) {
    //get check in id
    $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' AND DATE(Check_In_Date)=DATE(NOW()) order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    $nums = mysqli_num_rows($select);
    if ($nums > 0) {
        while ($rows = mysqli_fetch_array($select)) {
            $Check_In_ID = $rows['Check_In_ID'];
        }
    } else {

        //generate check in id
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


    

    //get Last Patient Bill Id
    $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if ($nm > 0) {
        while ($dts = mysqli_fetch_array($slct)) {
            $Patient_Bill_ID = $dts['Patient_Bill_ID'];
        }
    } else {

        $insert_bill = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID) values('$Registration_ID')") or die(mysqli_error($conn));
        if ($insert_bill) {
            //get inserted Patient Bill Id
            $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID limit 1") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($slct);
            if ($nm > 0) {
                while ($dts = mysqli_fetch_array($slct)) {
                    $Patient_Bill_ID = $dts['Patient_Bill_ID'];
                }
            }
        }
    }

    //details for referencing
    $select = mysqli_query($conn,"select Transaction_ID, Amount_Required, Source from tbl_bank_transaction_cache where
		    						Transaction_ID = '$trans_id' order by Transaction_ID desc limit 1") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($data = mysqli_fetch_array($select)) {
            $Transaction_ID = $data['Transaction_ID'];
            $Amount_Required = $data['Amount_Required'];
            $Source = $data['Source'];
        }
    } else {
        $Transaction_ID = 0;
        $Amount_Required = 0;
        $Source = '';
    }


    //Check if reception or revenue center transaction

    if (strtolower($Source) == 'reception') {
        $Sponsor_ID = mysqli_fetch_assoc(mysqli_query($conn,"select Sponsor_ID from tbl_bank_items_detail_cache where Transaction_ID = '$Transaction_ID'"))['Sponsor_ID'];
        $get_items = mysqli_query($conn,"select * from tbl_bank_items_detail_cache where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($get_items);

        //check if folio generated
        $check_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
        $num_folio = mysqli_num_rows($check_folio);

        if ($num_folio > 0) {
            while ($dt = mysqli_fetch_array($check_folio)) {
                $Folio_Number = $dt['Folio_Number'];
            }
        } else {
            //require_once (LIVE_SERVER_ROOT_URL."/final_one/files/includes/Folio_Number_Generator_ePayment.php");
            require_once ("Folio_Number_Generator_ePayment.php");
        }

        if ($no > 0) {
            $Control = 1;
            while ($row = mysqli_fetch_array($get_items)) {

                if ($Control == 1) {
                    //echo $row['Sponsor_ID']."here<br/>";
                    $sql = "INSERT INTO tbl_patient_payments(
										Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number,
										Sponsor_ID, Billing_Type, 
										Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,Patient_Bill_ID,auth_code,terminal_id,manual_offline)

										VALUES ('" . $Registration_ID . "','" . $Employee_ID . "','" . $Employee_ID . "',
										(select now()),'" . $Folio_Number . "',
										'" . $row['Sponsor_ID'] . "','" . $row['Billing_Type'] . "',
										(select now()),'" . $Branch_ID . "','" . $Check_In_ID . "','CRDB','','$Patient_Bill_ID','$auth_code','$terminal_id','$manual_offline')";
                    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                    if ($result) {
                        //get receipt number
                        $get_receipt = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments where 
		                    								Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                        if ($get_receipt) {
                            while ($ddt = mysqli_fetch_array($get_receipt)) {
                                $Patient_Payment_ID = $ddt['Patient_Payment_ID'];
                            }
                        } else {
                            $Patient_Payment_ID = 0;
                        }
                    }

                    $locField = "Consultant_ID";
                    if ($row['Patient_Direction'] == 'Direct To Clinic' || $row['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                        $locField = "Clinic_ID";
                    }

                    $Control = 2;
                    $insert_items = "INSERT INTO tbl_patient_payment_item_list(
		                						Check_In_Type, Item_ID,Item_Name, Discount, Price, 
		                						Quantity, Patient_Direction, Consultant, $locField, 
		                						Patient_Payment_ID, Transaction_Date_And_Time) 
											values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
												,'" . $row['Quantity'] . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
												,'" . $Patient_Payment_ID . "',(select now()))";
                    $result2 = mysqli_query($conn,$insert_items);
                } else {
                    $insert_items = "INSERT INTO tbl_patient_payment_item_list(
		                						Check_In_Type, Item_ID,Item_Name, Discount, Price, 
		                						Quantity, Patient_Direction, Consultant, $locField, 
		                						Patient_Payment_ID, Transaction_Date_And_Time) 
											values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['comments'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
												,'" . $row['Quantity'] . "','" . $row['Patient_Direction'] . "','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
												,'" . $Patient_Payment_ID . "',(select now()))";
                    $result2 = mysqli_query($conn,$insert_items);
                }
            }
            if ($result && $result2) {
                //update transaction details
                $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'Completed' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn) . '12');
                if ($update) {
                    //update remote
                    $update_remote = file_get_contents("http://" . EPAY_SERVER_URL . "/api/update_remote.php?src=manual&Payment_Code=$Payment_Code");
                    $remote_status = $update_remote->STATUS;
                }
            }
        }
    } else if (strtolower($Source) == 'revenue center') {
        //get pending items (unpaid items prepared)
        $get_total = mysqli_query($conn,"select Price, Discount, Quantity, Edited_Quantity from tbl_item_list_cache 
	    								where Transaction_ID = '$Transaction_ID' and
	    								(Status = 'active' or Status = 'approved') and
	    								Transaction_Type = 'Cash' and Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
        $num_p = mysqli_num_rows($get_total);
        if ($num_p > 0) {
            $Total_pending = 0;
            $Qty = 0;
            while ($th = mysqli_fetch_array($get_total)) {
                if ($th['Edited_Quantity'] > 0) {
                    $Qty = $th['Edited_Quantity'];
                } else {
                    $Qty = $th['Quantity'];
                }
                $Total_pending += (($th['Price'] - $th['Discount']) * $Qty);
            }

            //get items
            $get_items = mysqli_query($conn,"select p.Registration_ID, p.Folio_Number, p.Sponsor_ID, p.Sponsor_Name, p.Billing_Type, ilc.Price, ilc.Discount, ilc.Edited_Quantity, ilc.Quantity, ilc.Item_ID,
	    										ilc.Check_In_Type, ilc.Consultant, ilc.Consultant_ID, ilc.Payment_Item_Cache_List_ID from 
	    										tbl_payment_cache p, tbl_item_list_cache ilc where
	    										ilc.Payment_Cache_ID = p.Payment_Cache_ID and
	    										ilc.Transaction_ID = '$Transaction_ID' and
	    										(ilc.Status = 'active' or ilc.Status = 'approved') and
	    										ilc.Transaction_Type = 'Cash'") or die(mysqli_error($conn));
            $numz = mysqli_num_rows($get_items);
            if ($numz > 0) {
                $Control = 1;
                while ($row = mysqli_fetch_array($get_items)) {
                    $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
                    //get quantity
                    if ($row['Edited_Quantity'] > 0) {
                        $Qty = $row['Edited_Quantity'];
                    } else {
                        $Qty = $row['Quantity'];
                    }

                    if ($Control == 1) {
                        $sql = "INSERT INTO tbl_patient_payments(
											Registration_ID, Supervisor_ID, Employee_ID,
											Payment_Date_And_Time, Folio_Number,
											Sponsor_ID, Billing_Type, 
											Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,Patient_Bill_ID,auth_code,terminal_id,manual_offline)

											VALUES ('" . $row['Registration_ID'] . "','" . $Employee_ID . "','" . $Employee_ID . "',
											(select now()),'" . $row['Folio_Number'] . "',
											'" . $row['Sponsor_ID'] . "','" . $row['Billing_Type'] . "',
											(select now()),'" . $Branch_ID . "','" . $Check_In_ID . "','CRDB','','$Patient_Bill_ID','$auth_code','$terminal_id','$manual_offline')";
                        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        if ($result) {
                            //get receipt number
                            $get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where 
			                    								Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                            if ($get_receipt) {
                                while ($ddt = mysqli_fetch_array($get_receipt)) {
                                    $Patient_Payment_ID = $ddt['Patient_Payment_ID'];
                                    $Payment_Date_And_Time = $ddt['Payment_Date_And_Time'];
                                }
                            } else {
                                $Patient_Payment_ID = 0;
                                $Payment_Date_And_Time = '';
                            }
                        }
                        $Control = 2;
                        $insert_items = "INSERT INTO tbl_patient_payment_item_list(
			                						Check_In_Type, Item_ID, Discount, Price, 
			                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
			                						Patient_Payment_ID, Transaction_Date_And_Time) 
												values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
													,'" . $Qty . "','others','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
													,'" . $Patient_Payment_ID . "',(select now()))";
                        $result2 = mysqli_query($conn,$insert_items) or die(mysqli_error($conn));


                        if ($result2) {
                            mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where 
													Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                        }
                    } else {
                        $insert_items = "INSERT INTO tbl_patient_payment_item_list(
			                						Check_In_Type, Item_ID, Discount, Price, 
			                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
			                						Patient_Payment_ID, Transaction_Date_And_Time) 
												values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
													,'" . $Qty . "','others','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
													,'" . $Patient_Payment_ID . "',(select now()))";
                        $result2 = mysqli_query($conn,$insert_items) or die(mysqli_error($conn));

                        if ($result2) {
                            mysqli_query($conn,"update tbl_item_list_cache set 
													Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where 
													Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                        }
                    }
                }
                if ($result && $result2) {

                    /* mysqli_query($conn,"update tbl_item_list_cache set ePayment_Status = 'Served' where 
                      Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn)); */

                    if ($A_Paid > $Total_pending) {
                        $Remainder = ($A_Paid - $Total_pending);
                        //refund patient
                        $refund = mysqli_query($conn,"insert into (Transaction_ID, Amount)
	    												values('$Transaction_ID','$Remainder')") or die(mysqli_error($conn));
                    }
                    //update transaction details
                    $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'Completed' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//                       echo EPAY_SERVER_URL . "/api/update_remote.php?src=manual&Payment_Code=$Payment_Code";
//                       exit;
                    if ($update) {
                        //update remote
                        $update_remote = file_get_contents("http://" . EPAY_SERVER_URL . "/api/update_remote.php?src=manual&Payment_Code=$Payment_Code");
                        $remote_status = $update_remote->STATUS;
                    }
                }
            }
        } else {
            echo "string";
            //code.....................
        }
    }

    //End Checking 
    if ($Patient_Payment_ID != 0) {
        header("Location: ./departmentalothersworks.php?" . $location . "Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
    } else {
        header("Location: ./departmentalothersworkspage.php?" . $location . "Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
    }
} else {


//get folio number
    if ($Visit_Controler == 'new' || $Visit_Type == 'CT SCAN' || $Visit_Type == 'PATIENT FROM OUTSIDE') {
        if ($Visit_Controler == 'new') {
            $Type_Of_Check_In = 'Afresh';
        } else {
            $Type_Of_Check_In = $Visit_Type;
        }

        //Create New Check in
        $Create = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date,Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status,Branch_ID, 
                                    Saved_Date_And_Time, Check_In_Date,Type_Of_Check_In, Folio_Status) 
                                VALUES ('$Registration_ID',(select now()),'$Employee_ID',
                                    (select now()),'saved','$Branch_ID',
                                    (select now()),(select now()),'$Type_Of_Check_In','generated')") or die(mysqli_error($conn));
        if ($Create) {
            //select Check In ID
            $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
            $slct_num = mysqli_num_rows($slct);
            if ($slct_num > 0) {
                while ($dt = mysqli_fetch_array($slct)) {
                    $Check_In_ID = $dt['Check_In_ID'];
                }
            } else {
                $Check_In_ID = '';
            }
        } else {
            $Check_In_ID = '';
        }

        //Create Patient Bill ID
        $Create_PBI = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        if ($Create_PBI) {
            $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
            $num_Create = mysqli_num_rows($slct_bill_id);
            if ($num_Create > 0) {
                while ($td = mysqli_fetch_array($slct_bill_id)) {
                    $Patient_Bill_ID = $td['Patient_Bill_ID'];
                }
            } else {
                $Patient_Bill_ID = '';
            }
        } else {
            $Patient_Bill_ID = '';
        }

        include("./includes/Folio_Number_Generator_temp.php");


        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    } else {
        //get last folio number, Claim form number
        $get_folio = mysqli_query($conn,"select Claim_Form_Number, Folio_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
        $num_get = mysqli_num_rows($get_folio);
        if ($num_get > 0) {
            while ($dd = mysqli_fetch_array($get_folio)) {
                $Claim_Form_Number = $dd['Claim_Form_Number'];
                $Folio_Number = $dd['Folio_Number'];
            }
        } else {
            //create folio number
            include("./includes/Folio_Number_Generator_temp.php");
            $Claim_Form_Number = '';
        }

        //Get last Patient bill ID
        $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        $num_Create = mysqli_num_rows($slct_bill_id);
        if ($num_Create > 0) {
            while ($td = mysqli_fetch_array($slct_bill_id)) {
                $Patient_Bill_ID = $td['Patient_Bill_ID'];
            }
        } else {
            //Create Patient Bill ID
            $Create_PBI = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
            if ($Create_PBI) {
                $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                $num_Create = mysqli_num_rows($slct_bill_id);
                if ($num_Create > 0) {
                    while ($td = mysqli_fetch_array($slct_bill_id)) {
                        $Patient_Bill_ID = $td['Patient_Bill_ID'];
                    }
                } else {
                    $Patient_Bill_ID = '';
                }
            } else {
                $Patient_Bill_ID = '';
            }
        }

        //Get Check in id
        //select Check In ID
        $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
        $slct_num = mysqli_num_rows($slct);
        if ($slct_num > 0) {
            while ($dt = mysqli_fetch_array($slct)) {
                $Check_In_ID = $dt['Check_In_ID'];
            }
        } else {
            $Check_In_ID = '';
        }

        if (empty($Check_In_ID)) {
            $Create = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date,Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status,Branch_ID, 
                                    Saved_Date_And_Time, Check_In_Date,Type_Of_Check_In, Folio_Status) 
                                VALUES ('$Registration_ID',(select now()),'$Employee_ID',
                                    (select now()),'saved','$Branch_ID',
                                    (select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));

            $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
            $dt = mysqli_fetch_array($slct);
            $Check_In_ID = $dt['Check_In_ID'];
        }
    }

    $typeOfCheckInOthers = false;
    $hasotherdeparments = false;

// die($Check_In_ID);
//get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

        if ($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0) {
            //select all data from cache table
            $select = mysqli_query($conn,"select * from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
            $num_rows = mysqli_num_rows($select);
            if ($num_rows > 0) {
                while ($data = mysqli_fetch_array($select)) {
                    $Sponsor_ID = $data['Sponsor_ID'];
                    $Sponsor_Name = $data['Sponsor_Name'];
                    $Billing_Type = $data['Billing_Type'];
                    $Sponsor_Name = $data['Sponsor_Name'];
                    $Fast_Track = $data['Fast_Track'];

                    if ($data['Type_Of_Check_In'] != 'Others') {
                        $hasotherdeparments = true;
                    }if ($data['Type_Of_Check_In'] == 'Others') {
                        $typeOfCheckInOthers = true;
                    }
                }


                //generate transaction type
                if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                    $Transaction_Type = 'Cash';
                } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
                    $Transaction_Type = 'Credit';
                }

                if ($hasotherdeparments == true) {
                    //Create consultation if new visit
                    if ($Visit_Controler == 'new') {
                        $create_consultation = mysqli_query($conn,"insert into tbl_consultation(Employee_ID, Registration_ID, Consultation_Date_And_Time)
                                                    values('$Consultant_ID','$Registration_ID',(select now()))") or die(mysqli_error($conn));
                        if ($create_consultation) {
                            $slct_con = mysqli_query($conn,"select consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or die(mysqli_error($conn));
                            $num_cons = mysqli_num_rows($slct_con);
                            if ($num_cons > 0) {
                                while ($dtcon = mysqli_fetch_array($slct_con)) {
                                    $consultation_ID = $dtcon['consultation_ID'];
                                }
                            } else {
                                $consultation_ID = null;
                            }
                        } else {
                            $consultation_ID = null;
                        }
                    } else {
                        $consultation_ID = null;
                    }
                }

                // create consultation_id
                if ($consultation_ID == null) {
                   // insert consultation id

                    $insert_consultation_id = "INSERT  INTO tbl_consultation(Registration_ID,Consultation_Date_And_Time) VALUES('$Registration_ID',NOW())";
                    $consultation_result = mysqli_query($conn,$insert_consultation_id);

                        $select_consult = mysqli_query($conn,"SELECT consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or 
                        die(mysqli_error($conn));
                            $num_consltation = mysqli_num_rows($select_consult);
                            if ($num_consltation > 0) {
                                while ($dtconsu = mysqli_fetch_array($select_consult)) {
                                    $consultation_ID = $dtconsu['consultation_ID'];
                                }
                            }
    // end consultation id
                }

                if ($hasotherdeparments == true) {

                    //insert data to payment cache
                    if ($Visit_Controler == 'continue') {
                        $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
                    } else {
                        $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, consultation_id, Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$consultation_ID','$Fast_Track')") or die(mysqli_error($conn));
                    }
                } else {
                    $insert_data = true;
                }

                if ($insert_data) {
                    if ($typeOfCheckInOthers == true && $hasotherdeparments == false) {
                        //insert details to tbl_patient_payments
                        $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,Fast_Track,auth_code,terminal_id,manual_offline)
                                                        
                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Fast_Track','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));


                        if ($insert_details) {
                            //get the last Patient Payment ID
                            $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date, Payment_Date_And_Time from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($select)) {
                                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                    $Receipt_Date = $row['Receipt_Date'];
                                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                }

                                //insert data to tbl_patient_payment_item_list
                                $select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

                                while ($dt = mysqli_fetch_array($select_details)) {
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
                                    $Discount = $dt['Discount'];
                                    $Quantity = $dt['Quantity'];
                                    $Consultant_ID = $Employee_ID;
                                    $Comment = $dt['Comment'];
                                    $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                                    $Clinic_ID = $dt['Clinic_ID'];
                                    $finance_department_id = $dt['finance_department_id'];
                                    $Sub_Department_ID = $dt['Sub_Department_ID'];
                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price,Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
                                                            
                                                            VALUES ('$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','others',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()),'$Clinic_ID','$finance_department_id','$Sub_Department_ID')") or die(mysqli_error($conn));
                                }
                                if ($insert) {
                                    //delete all data from cache
                                    mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                    header("Location: ./departmentalothersworks.php?" . $location . "Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                                } else {
                                    header("Location: ./departmentalothersworkspage.php?" . $location . "Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                                }
                            }
                        }
                    } else {
                        //get the last Payment_Cache_ID (foreign key)
                        $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select);
                        if ($no > 0) {
                            while ($row = mysqli_fetch_array($select)) {
                                $Payment_Cache_ID = $row['Payment_Cache_ID'];
                            }
                            //insert data
                            $select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            $numrows = mysqli_num_rows($select_details);
                            if ($numrows > 0) {
                                while ($dt = mysqli_fetch_array($select_details)) {
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
                                    $Discount = $dt['Discount'];
                                    $Quantity = $dt['Quantity'];
                                    $Comment = $dt['Comment'];
                                    $Sub_Department_ID = $dt['Sub_Department_ID'];
                                    $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                                    $Clinic_ID = $dt['Clinic_ID'];

                                    if (empty($Sub_Department_ID) || is_null($Sub_Department_ID)) {
                                        $Sub_Department_ID = 'NULL';
                                    }

                                    if ($Type_Of_Check_In != 'Others') {
                                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,Discount,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Status,Clinic_ID
                                                ) values(
                                                    '$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                    '$Comment',$Sub_Department_ID,'$Transaction_Type',(select now()),'paid','$Clinic_ID')") or die(mysqli_error($conn));
                                    }
                                }
                            }

                            if ($insert) {
                                //insert details to tbl_patient_payments
                                $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,Fast_Track,auth_code,terminal_id,manual_offline)
                                                        
                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Fast_Track','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));


                                if ($insert_details) {
                                    //get the last Patient Payment ID
                                    $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date, Payment_Date_And_Time from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                                    $num = mysqli_num_rows($select);
                                    if ($num > 0) {
                                        while ($row = mysqli_fetch_array($select)) {
                                            $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                            $Receipt_Date = $row['Receipt_Date'];
                                            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                        }

                                        //insert data to tbl_patient_payment_item_list
                                        $select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

                                        while ($dt = mysqli_fetch_array($select_details)) {
                                            $Item_ID = $dt['Item_ID'];
                                            $Price = $dt['Price'];
                                            $Discount = $dt['Discount'];
                                            $Quantity = $dt['Quantity'];
                                            $Consultant_ID = $Employee_ID;
                                            $Comment = $dt['Comment'];
                                            $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                                            $Clinic_ID = $dt['Clinic_ID'];
                                            $finance_department_id = $dt['finance_department_id'];
                                            $Sub_Department_ID = $dt['Sub_Department_ID'];



                                            $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price,Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
                                                            
                                                            VALUES ('$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','others',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()),'$Clinic_ID','$finance_department_id','$Sub_Department_ID')") or die(mysqli_error($conn));
                                        }
//Sending to gaccounting
                                        $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);
                                        $Product_Array = array();

                                        $Product_Name_Array = array(
                                            'source_name' => 'ehms_sales_cash',
                                            'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
                                            'debit_entry_ledger' => 'CASH IN HAND',
                                            'credit_entry_ledger' => 'SALES',
                                            'sub_total' => $payDetails['TotalAmount'],
                                            'source_id' => $Patient_Payment_ID,
                                            'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                                            'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
                                        );

                                        array_push($Product_Array, $Product_Name_Array);
                                        $endata = json_encode($Product_Array);
                                        $acc = gAccJournalEntry($endata);
                                        if ($insert) {
                                            //update tbl_item_list_cache
                                            mysqli_query($conn,"update tbl_item_list_cache set
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date' where
                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));

                                            //delete all data from cache
                                            mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                            header("Location: ./departmentalothersworks.php?" . $location . "Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                                        } else {
                                            header("Location: ./departmentalothersworkspage.php?" . $location . "Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                                        }
                                    }
                                }
                            } else {
                                header("Location: ./departmentalothersworkspage.php?" . $location . "Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                            }
                        }
                    }
                }
            }
        }
    }
}
?>