<?php
declare(strict_types=1);
require 'db.config.php';

class PharmacyRepo extends DBConfig{

    public $db_connect;

    function __construct(){
        $this->db_connect = $this->connect();
    }

    /**
     * @param $sql_query
     * @param $error_msg
     * @return false|string|void
     */
    public function processSingleFetchQuery($sql_query, $error_msg){
        $result = [];
        $query = $this->db_connect->query($sql_query) or die($this->db_connect->errno." : ".$error_msg);
        while($details = $query->fetch_assoc()){ $result[] = $details; }
        mysqli_free_result($query);
        return json_encode($result);
    }

    public function readSponsorDetails($Sponsor_ID,$filter){
        $filter_ = ($filter == "all") ? "" : " WHERE Sponsor_ID = $Sponsor_ID ";
        $sql = "SELECT * FROM tbl_sponsor $filter_ ";
        return $this->processSingleFetchQuery($sql,"Failed to fetch Sponsor Details");
    }

    public function getPatientDetails($Payment_Cache_Id){
        $sql = "SELECT ts.auto_item_update_api,ts.allow_dispense_control,ts.allow_pharmacy_sponsor,tpr.Patient_Name,tpc.Billing_Type,ts.Guarantor_Name,tpr.Gender,tpr.Registration_Date,tpr.Registration_ID,tpr.Member_Number,tpr.Date_Of_Birth,te.Employee_Name,tpr.Occupation,tpc.Sponsor_ID,tpr.Member_Card_Expire_Date,tilc.Check_In_Type,tpc.Payment_Cache_ID,tpr.Phone_Number,tilc.Patient_Payment_ID,tilc.Payment_Date_And_Time FROM tbl_payment_cache AS tpc,tbl_patient_registration AS tpr,tbl_sponsor AS ts,tbl_employee AS te ,tbl_item_list_cache AS tilc WHERE tpc.Payment_Cache_ID = '$Payment_Cache_Id' AND tpc.Registration_ID = tpr.Registration_ID AND te.Employee_ID = tpc.Employee_ID AND tilc.Check_In_Type ='Pharmacy' AND tpc.Sponsor_ID = ts.Sponsor_ID LIMIT 1";
        return $this->processSingleFetchQuery($sql,"Failed to fetch Patient Details");
    }

    public function getPharmacyReceipt($Payment_Cache_Id){
        $sql = "SELECT Patient_Payment_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID = $Payment_Cache_Id AND Check_In_Type = 'Pharmacy'";
        return $this->processSingleFetchQuery($sql,"Error while fetching Patient Receipt");
    }

    public function getItemBalance($Sub_Department_ID,$Item_ID){
        $sql = "SELECT tib.Item_Balance FROM tbl_items_balance AS tib WHERE tib.Item_ID = $Item_ID AND tib.Sub_Department_ID = $Sub_Department_ID LIMIT 1";
        return $this->processSingleFetchQuery($sql,"Error while fetching item balance");
    }

    public function consultants(){
        $sql = "SELECT Employee_ID, Employee_Name from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'";
        return $this->processSingleFetchQuery($sql,"Error while fetching Consultants");
    }

    public function allReasonForItemRemoval(){
        $sql = "SELECT * FROM tbl_item_removal";
        return $this->processSingleFetchQuery($sql,"Error while fetching Item Removal reasons");
    }

    public function getAllDispensers(){
        $sql = "SELECT Employee_ID,Employee_Name FROM `tbl_employee` WHERE `Employee_Type` IN ('Pharmacy','Pharmacist')";
        return $this->processSingleFetchQuery($sql,"Error while fetching Dispensers");
    }

    public function configuration(){
        $sql = "SELECT Change_Medication_Location,Enable_Add_More_Medication,Display_Send_To_Cashier_Button FROM tbl_system_configuration";
        return $this->processSingleFetchQuery($sql,"Error while fetching Configuration");
    }

    public function reasonForRemoval(){
        $sql = "SELECT * FROM tbl_item_removal WHERE status = 'active'";
        return $this->processSingleFetchQuery($sql,"Error while fetching active reasons");
    }

    public function medicationForReturnPatient($Payment_Cache_ID){
        $sql1 = "SELECT Patient_Payment_ID,Payment_Item_Cache_List_ID,ilc.Edited_Quantity,ilc.dispensed_quantity,Product_Name,it.Item_ID,Discount,Price,Quantity,Doctor_Comment,Dispense_Date_Time FROM tbl_item_list_cache ilc,tbl_items it WHERE ilc.Item_ID=it.Item_ID AND ilc.Status IN ('dispensed','partial dispense') AND Check_In_Type = 'Pharmacy' AND Payment_Cache_ID='$Payment_Cache_ID'";
        return $this->processSingleFetchQuery($sql1,"Error while fetching Medication to return 1");
    }

    public function getConsultant($Consultant_ID){
        return mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Consultant_ID'"))['Employee_Name'];
    }

    public function sendToMedicationCashier($Payment_Cache_ID,$Transaction_Type,$newSub_Department_ID,$Check_In_Type,$selectedItem){
        $count = 0;
        foreach($selectedItem as $itemDetails) {
            $selectedID = $itemDetails['id'];
            $dose_qty = $itemDetails['doseqty'];
            $dispensed_qty = $itemDetails['dispensedqty'];
            $update_response = $this->processUpdateQuery("tbl_item_list_cache",
                array(array("Status","=","'approved'"),array('dose',"=",$dose_qty),array("Edited_Quantity","=",$dispensed_qty),array("Sub_Department_ID","=",$newSub_Department_ID)),
                array(array("Status","IN",("('active','partial dispensed')")),array("Transaction_Type","=","'{$Transaction_Type}'"),array("Payment_Cache_ID","=",$Payment_Cache_ID),array("Check_In_Type","=","'{$Check_In_Type}'"),array("Payment_Item_Cache_List_ID","=",$selectedID)),"Failed to update item with id ".$selectedID);
            if($update_response == 1) { $count++; }
        }
        return ($count > 0) ? "Medication Sent To Cashier Successful" : "Operation fail";
    }

    # @Pass
    public function getQuantityDispensed($dates_From,$dates_To,$Sponsor_ID,$search_item,$sub_department_id){
        $result = array();
        $filter = "";
        $filter .= ($search_item != "") ? " AND i.Product_Name LIKE '%$search_item%' ": "" ;
        $sponsor_filter = ($Sponsor_ID == "all") ? " " : " AND pr.Sponsor_ID = $Sponsor_ID ";
        $filter .= ($sub_department_id != "") ? " AND ilc.Sub_Department_ID = $sub_department_id AND tib.Sub_Department_ID = ilc.Sub_Department_ID " : "";

        if($sub_department_id != ""){
            $sql = "SELECT i.Product_Code,SUM(ilc.dispensed_quantity*ilc.Price) AS sum_amount,SUM(ilc.dispensed_quantity) as quantity_dispensed,ilc.Item_ID,i.Product_Name,tib.Item_Balance FROM tbl_items_balance tib,tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pc.Payment_Cache_ID = ilc.Payment_Cache_ID and i.Item_ID = ilc.Item_ID and pr.Registration_ID = pc.Registration_ID and ilc.Dispense_Date_Time between '$dates_From' and '$dates_To' and pr.Sponsor_ID = ts.Sponsor_ID and ilc.Patient_Payment_ID IS NOT NULL AND i.Item_ID = ilc.Item_ID AND ilc.Item_ID = tib.Item_ID AND ilc.Sub_Department_ID = '$sub_department_id' AND tib.Sub_Department_ID = ilc.Sub_Department_ID GROUP BY ilc.Item_ID ";
        } else{
            $sql = "SELECT ilc.Price,i.Item_ID,i.Product_Code,SUM(ilc.dispensed_quantity)AS total_dispense,(SELECT SUM(Item_Balance) FROM tbl_items_balance WHERE `Item_ID`=ilc.Item_ID) AS Item_Balance,i.Product_Name FROM tbl_items_balance tib,tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pc.Payment_Cache_ID = ilc.Payment_Cache_ID and i.Item_ID = ilc.Item_ID and pr.Registration_ID = pc.Registration_ID and ilc.Dispense_Date_Time between '$dates_From' and '$dates_To' and pr.Sponsor_ID = ts.Sponsor_ID and ilc.Status IN ('dispensed','partial dispensed') AND i.Item_ID = ilc.Item_ID AND ilc.Item_ID = tib.Item_ID $filter  AND tib.Sub_Department_ID = ilc.Sub_Department_ID AND pr.Sponsor_ID = 2436 GROUP BY i.Item_ID ";
        }

        return $this->processSingleFetchQuery($sql,"Error while fetching active reasons");
    }

    # @Pass
    public function getConsultationDiseasesCode($Consultation_ID,$diagnosis_type){
        $final_diagnosis = array();
        $diagnosis_qr = mysqli_query($this->db_connect,"SELECT diagnosis_type,disease_name,Disease_Consultation_Date_And_Time,disease_code FROM tbl_disease_consultation dc,tbl_disease d
            WHERE dc.consultation_ID = $Consultation_ID AND dc.disease_ID = d.disease_ID AND diagnosis_type = '$diagnosis_type'") or die('ERROR WHILE FETCHING DIAGNOSIS');
        if(mysqli_num_rows($diagnosis_qr) > 0){
            while($data = mysqli_fetch_array($diagnosis_qr)){ array_push($final_diagnosis,$data); }
            return json_encode($final_diagnosis);
        }else{
            return 'No Diagnosis Found';
        }
    }

    # @Pass
    public function getPharmacyItems($Payment_Cache_ID,$Registration_ID,$Billing_Type,$Transaction_Type,$Sub_Department_ID){
        $result = array();
        $select_medication = mysqli_query($this->db_connect,"SELECT Item_ID FROM `tbl_item_list_cache` WHERE `Payment_Cache_ID` = $Payment_Cache_ID AND Check_In_Type = 'Pharmacy' ") or die(mysqli_errno($this->db_connect));
        while($medication = mysqli_fetch_assoc($select_medication)){
            $Item_ID = $medication['Item_ID'];
            $check_if_item_exist = mysqli_query($this->db_connect,"SELECT Item_ID FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID' ") or die(mysqli_errno($this->db_connect));
            if(mysqli_num_rows($check_if_item_exist) == 0){
                $add_item_ = mysqli_query($this->db_connect,"INSERT INTO tbl_items_balance (Item_ID,Item_Balance,Item_Temporary_Balance,Sub_Department_ID,Sub_Department_Type,Reorder_Level,Reorder_Level_Status) VALUES ($Item_ID,0,0,$Sub_Department_ID,'Pharmacy',0,'normal')");
                if(!$add_item_){ die(mysqli_error($this->db_connect)." :: Not added"); }
            }
        }
        $select_item = mysqli_query($this->db_connect,"SELECT *,tilc.Transaction_Date_And_Time,tilc.Status,tilc.Sub_Department_ID FROM `tbl_item_list_cache` AS tilc, tbl_payment_cache AS tpc, tbl_items AS ti, tbl_items_balance AS tib,tbl_employee AS te WHERE tilc.`Payment_Cache_ID` = $Payment_Cache_ID AND tilc.Check_In_Type = 'Pharmacy' AND tilc.`Payment_Cache_ID` = tpc.Payment_Cache_ID AND ti.Item_ID = tilc.Item_ID AND tilc.Status IN ('active','paid','approved','free','partial dispensed') AND tilc.Transaction_Type = '$Transaction_Type' AND tpc.Registration_ID  = '$Registration_ID' AND tpc.Billing_Type = '$Billing_Type' AND tib.Sub_Department_ID = $Sub_Department_ID AND tib.Item_ID = tilc.Item_ID AND tilc.Consultant_ID = te.Employee_ID") or die(mysqli_error($this->db_connect)."Error while Fetching Items");
        while($data = mysqli_fetch_assoc($select_item)){ array_push($result,$data); }
        mysqli_free_result($select_item);
        return json_encode($result);
    }

    # @Pass
    public function dispenseItemsPharmacy($Employee_ID,$Registration_ID,$selectedItem,$Sub_Department_ID){
        $has_error = false;
        mysqli_begin_transaction($this->db_connect);
        foreach($selectedItem as $itemData){
            $selectedItemid = $itemData['id'];
            $dose_qty = $itemData['doseqty'];
            $dispensed_qty = $itemData['dispensedqty'];
            $dose_duration = $itemData['dose_duration'];

            $selectItem = "SELECT dispensed_quantity,Quantity,Edited_Quantity,Patient_Payment_ID,itm.Item_ID,ch.status FROM tbl_item_list_cache AS ch, tbl_items AS itm WHERE itm.Item_ID=ch.Item_ID AND ch.status IN ('paid','free')  and Payment_Item_Cache_List_ID = $selectedItemid";
            $select_item = mysqli_query($this->db_connect,$selectItem) or die(mysqli_error($this->db_connect) . ' : while getting the item');
            if(mysqli_num_rows($select_item) > 0){
                while($data = mysqli_fetch_assoc($select_item)){
                    $Item_ID = $data['Item_ID'];
                    $dispensed_quantity = $data['dispensed_quantity'];
                    $___status = $data['status'];
                    $Patient_Payment_ID = $data['Patient_Payment_ID'];
                    $total_dispensed = $dispensed_quantity + $dispensed_qty;
                    $sts = $total_dispensed < $dose_qty ? "partial dispensed" : "dispensed";

                    $update_item_list_cache_item = "UPDATE tbl_item_list_cache SET status = '$sts',Dispensor='$Employee_ID',Dispense_Date_Time =NOW(), Edited_Quantity = $dispensed_qty,dosage_duration = '$dose_duration',dose = $dose_qty, dispensed_quantity = $total_dispensed where Payment_Item_Cache_List_ID = '$selectedItemid'";
                    $update_item_dispense = mysqli_query($this->db_connect,$update_item_list_cache_item) or die(mysqli_error($this->db_connect));
                    if($update_item_dispense){
                        $insert_history_query = "INSERT INTO `tbl_partial_dispense_history`(`employee_id`, `patient_id`, `item_id`, `item_cache_list_id`, `dose_qty`, `dispensed_qty`) VALUES ('$Employee_ID', '$Registration_ID','$Item_ID','$selectedItemid', '$dose_qty', '$dispensed_qty')";
                        mysqli_query($this->db_connect,$insert_history_query) or die(mysqli_error($this->db_connect)." ERROR WHILE INSERTING HISTORY CONTACT ADMIN FOR SUPPORT");

                        $item_balance = mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' AND Sub_Department_ID = '$Sub_Department_ID'"))['Item_Balance'] or die(mysqli_error($this->db_connect));
                        if($item_balance > 0 && $item_balance >= $dispensed_qty){

                            mysqli_query($this->db_connect,"UPDATE tbl_items_balance SET Item_Balance = (Item_Balance - '$dispensed_qty'),Item_Temporary_Balance  = (Item_Temporary_Balance - '$dispensed_qty') WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID'") or die(mysqli_error($this->db_connect)." : WHILE UPDATE TABLE BALANCE");

                            $update_receipt = mysqli_query($this->db_connect,"UPDATE tbl_patient_payment_item_list SET Status = 'Served' WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID'") or die(mysqli_errno($this->db_connect));

                            if($update_receipt){
                                if($___status == 'free'){
                                    $insert_to_stock_legder_for_control = mysqli_query($this->db_connect,"INSERT INTO tbl_stock_ledger_controler(Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time) VALUES($Item_ID,$Sub_Department_ID,'Dispensed',$Registration_ID,$item_balance,($item_balance - $dispensed_qty),NOW(),NOW())") or die(mysqli_error($this->db_connect).": ERROR WHILE AUDITING TRANSACTION");
                                    if(!$insert_to_stock_legder_for_control){ $has_error = true; }
                                }else{
                                    $insert_to_stock_legder_for_control = mysqli_query($this->db_connect,"INSERT INTO tbl_stock_ledger_controler(Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number) VALUES($Item_ID,$Sub_Department_ID,'Dispensed',$Registration_ID,$item_balance,($item_balance - $dispensed_qty),NOW(),NOW(),$Patient_Payment_ID)") or die(mysqli_error($this->db_connect).": ERROR WHILE AUDITING TRANSACTION");
                                    if(!$insert_to_stock_legder_for_control){ $has_error = true; }
                                }
                            }
                        }else{
                            $has_error = true;
                        }
                    }else{
                        $has_error = true;
                    }
                }
            }
        }
        if(!$has_error) {
            mysqli_commit($this->db_connect);
            return 'Medication Dispensed Successfully';
        }else{
            mysqli_rollback($this->db_connect);
            return 'SOMETHING WENT WRONG CONTACT ADMINISTRATOR FOR SUPPORT';
        };
    }

    # @Pass
    public function Folio($Sponsor_ID,$Folio_Branch_ID,$Check_In_ID){
        $con = $this->connect();
        $Today_Date = mysqli_query($con,"SELECT NOW() as today");

        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date;
        }

        $current_folio = mysqli_query($con, "SELECT Folio_Number, Folio_date FROM tbl_folio WHERE sponsor_id = '$Sponsor_ID' AND Branch_ID = '$Folio_Branch_ID' ORDER BY folio_id DESC LIMIT 1");
        $no = mysqli_num_rows($current_folio);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($current_folio)) {
                $Folio_Number = $row['Folio_Number'];
                $Folio_date = $row['Folio_date'];
            }
        } else {
            $Folio_Number = 1;
            $Folio_date = 0;
        }

        $Current_Month_and_year = substr($Today, 0, 7);
        $Last_Folio_Month_and_year = substr($Folio_date, 0, 7);

        if ($Last_Folio_Month_and_year == $Current_Month_and_year) {
            mysqli_query($con, "INSERT into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id) VALUES(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($con));
            $Folio_Number = $Folio_Number + 1;
        } else {
            mysqli_query($con, "INSERT into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id) VALUES (1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
            $Folio_Number = 1;
        }
        # update Folio_Status
        mysqli_query($con, "UPDATE tbl_check_in set Folio_Status = 'generated' WHERE Check_In_ID = '$Check_In_ID'") or die(mysqli_error($con));
    }

    # @Pass
    public function billAndDispenseMedicationCredit($Payment_Cache_ID,$Transaction_Type,$Billing_Type,$Registration_ID,$Check_In_Type,$Sub_Department_ID,$selectedItem,$Folio_Branch_ID,$Sponsor_ID,$Employee_ID){
        $HAS_ERROR = false;
        $Check_In_ID = 0;
        $Patient_Bill_ID = 0;

        mysqli_begin_transaction($this->db_connect);
        foreach ($selectedItem as $selectedItems) {
            $selectedItemid = $selectedItems['id'];
            mysqli_query($this->db_connect, "UPDATE tbl_item_list_cache SET Status = 'approved',Sub_Department_ID='$Sub_Department_ID' WHERE Payment_Item_Cache_List_ID = $selectedItemid");
        }

        $Check_In_ID = mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID'] or die(mysqli_error($this->db_connect)." : fail to get checkin id");
        # get last active bill ID if no create new one
        $select = mysqli_query($this->db_connect,"SELECT Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID'  AND Status='active' ORDER BY Patient_Bill_ID desc limit 1") or die(mysqli_error($this->db_connect));
        $nums = mysqli_num_rows($select);
        if($nums > 0){
            while ($row = mysqli_fetch_array($select)) { $Patient_Bill_ID = $row['Patient_Bill_ID']; }
        }else{
            $insert = mysqli_query($this->db_connect,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($this->db_connect)."Failed to create new bill id");
            if($insert){
                $select = mysqli_query($this->db_connect,"SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID = '$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1") or die(mysqli_error($this->db_connect)."Failed to check bill id");
                $nums = mysqli_num_rows($select);
                while ($row = mysqli_fetch_array($select)) { $Patient_Bill_ID = $row['Patient_Bill_ID']; }
            }
        }

        # get folio number
        $getFolioNumber = $this->db_connect->query("SELECT Folio_Number FROM `tbl_payment_cache` WHERE Payment_Cache_ID = $Payment_Cache_ID");
        $folioNo = $getFolioNumber->fetch_assoc()['Folio_Number'];

        # Folio
        $select_folio_checkin = mysqli_query($this->db_connect, "SELECT Folio_Number, Claim_Form_Number from tbl_patient_payments where Registration_ID = $Registration_ID AND Check_In_ID = $Check_In_ID  AND Sponsor_ID = $Sponsor_ID order by Patient_Payment_ID desc limit 1") or die(mysqli_error($this->db_connect));
        if(mysqli_num_rows($select_folio_checkin) > 0){
            $rows_info = mysqli_fetch_array($select_folio_checkin);
            $Claim_Form_Number = $rows_info['Claim_Form_Number'];
            $Folio_Number = $rows_info['Folio_Number'];
        }else{
            $checkin_details = mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Check_In_ID, Type_Of_Check_In,Folio_Status FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' order by Check_In_ID DESC LIMIT 1")) or die(mysqli_error($this->db_connect));
            if (strtolower($checkin_details['Type_Of_Check_In']) == 'continuous') {
                $select_folio = mysqli_query($this->db_connect, "SELECT Folio_Number from tbl_patient_payments WHERE Registration_ID = '$Registration_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($this->db_connect));
                if(mysqli_num_rows($select_folio) > 0){
                    mysqli_query($this->db_connect, "UPDATE tbl_check_in SET Folio_Status = 'generated' WHERE Check_In_ID = '$Check_In_ID'") or die(mysqli_error($this->db_connect));
                }else{
                    $this->Folio($Sponsor_ID,$Folio_Branch_ID,$Check_In_ID);
                }
            }else if (strtolower($checkin_details['Type_Of_Check_In']) == 'pending'){
                $this->Folio($Sponsor_ID,$Folio_Branch_ID,$Check_In_ID);
            }
        }


        $Sponsor_Name = mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = $Sponsor_ID LIMIT 1"))['Guarantor_Name'] or die(mysqli_errno($this->db_connect));
        if (strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'inpatient credit') {
            $Hospital_Ward_ID = mysqli_fetch_assoc(mysqli_query($this->db_connect,"SELECT Hospital_Ward_ID from tbl_admission where Registration_ID = $Registration_ID order by Admision_ID DESC LIMIT 1"))['Hospital_Ward_ID'] or die(mysqli_error($this->db_connect));
        }else{
            $Hospital_Ward_ID = null;
        }

        $create_payment_reciept = " INSERT into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Payment_Date_And_Time,Folio_Number,Claim_Form_Number,Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Check_In_ID,Patient_Bill_ID,payment_type,Hospital_Ward_ID)
                                        VALUES('$Registration_ID','$Employee_ID','$Employee_ID',(select now()),'$folioNo','$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'active','indirect cash',
                                        '$Folio_Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Transaction_Type','$Hospital_Ward_ID')";

        if(mysqli_query($this->db_connect,$create_payment_reciept)){
            $select_patient_payment_id = mysqli_query($this->db_connect, "SELECT Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1");
            $no = mysqli_num_rows($select_patient_payment_id);
            if ($no > 0) {
                while ($row2 = mysqli_fetch_array($select_patient_payment_id)) {
                    $Patient_Payment_ID = $row2['Patient_Payment_ID'];
                    $Payment_Date_And_Time = $row2['Payment_Date_And_Time'];
                }
            }
        }

        if ($Patient_Payment_ID == "") {
            $insert = mysqli_query($this->db_connect, "INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Payment_Date_And_Time,Folio_Number,Claim_Form_Number,Sponsor_ID,Sponsor_Name,Billing_Type,
                                                Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Check_In_ID,Patient_Bill_ID,payment_type)
                                                VALUES('$Registration_ID','$Employee_ID','$Employee_ID',(select now()),'$folioNo','$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'active','indirect cash',
                                                '$Folio_Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Transaction_Type')");
            if ($insert) {
                $get_latest_patient_payment_id = mysqli_query($this->db_connect, "SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE  Registration_ID = '$Registration_ID' ORDER By Patient_Payment_ID DESC LIMIT 1");
                while ($get  = mysqli_fetch_assoc($get_latest_patient_payment_id)) {
                    $Patient_Payment_ID = $get['Patient_Payment_ID'];
                }
            }
        }


        foreach ($selectedItem as $selectedItems) {
            $SelectedItemID = $selectedItems['id'];
            $dose_qty = $selectedItems['doseqty'];
            $dispensed_qty = $selectedItems['dispensedqty'];
            $dose_duration = $selectedItems['dose_duration'];

            $out_query = "SELECT itm.Product_Name,ch.Clinic_ID,ch.dispensed_quantity,ch.dose,ch.finance_department_id, ch.clinic_location_id,itm.Last_Buy_Price,itm.Consultation_Type,ch.Quantity,ch.Edited_Quantity,ch.Consultant_ID, ch.Consultant,ch.Patient_Direction,ch.Price,ch.Discount,ch.Item_ID,ch.Check_In_Type,ch.Payment_Item_Cache_List_ID FROM tbl_item_list_cache ch join tbl_items itm on itm.Item_ID=ch.Item_ID where ch.Check_In_Type='Pharmacy' and ch.Transaction_Type = 'Credit' AND (ch.status = 'active' OR ch.status = 'approved' OR ch.status = 'partial dispensed') AND ch.Payment_Item_Cache_List_ID = '$SelectedItemID' ";

            $select_medication = mysqli_query($this->db_connect, $out_query) or die(mysqli_error($this->db_connect));
            while ($row3 = mysqli_fetch_array($select_medication)) {
                $Payment_Item_Cache_List_ID = $row3['Payment_Item_Cache_List_ID'];
                $Check_In_Type = $row3['Check_In_Type'];
                $Item_ID = $row3['Item_ID'];
                $Discount = $row3['Discount'];
                $Price = $row3['Price'];
                $Patient_Direction = $row3['Patient_Direction'];
                $Consultant = $row3['Consultant'];
                $Consultant_ID = $row3['Consultant_ID'];
                $Clinic_ID = $row3['Clinic_ID'];
                $clinic_location_id = $row3['clinic_location_id'];
                $finance_department_id = $row3['finance_department_id'];
                $dispensed_quantity = $row3['dispensed_quantity'];
                $dose = $row3['dose'];
                $Treatment_Authorization_No = $row3['Treatment_Authorization_No'];
                $Treatment_Authorizer = $row3['Treatment_Authorizer'];

                $total_dispensed = $dispensed_quantity + $dispensed_qty;
                if ($total_dispensed < $dose || $total_dispensed < $dose_qty) {
                    $sts = "partial dispensed";
                } else {
                    $sts = "dispensed";
                }

                if ($row3['Edited_Quantity'] > 0) {
                    $Quantity = $row3['Edited_Quantity'];
                } else {
                    $Quantity = $row3['Quantity'];
                }

                if($Employee_ID != "" || $Employee_ID != null){
                    $insert_history = mysqli_query($this->db_connect, "INSERT INTO `tbl_partial_dispense_history`(`employee_id`, `patient_id`, `item_id`, `item_cache_list_id`, `dose_qty`, `dispensed_qty`) VALUES ('$Employee_ID', '$Registration_ID', '$Item_ID', '$SelectedItemID', '$dose_qty', '$dispensed_qty')") or die(mysqli_error($this->db_connect));
                    if ($insert_history) {
                        if($Registration_ID != "" || $Registration_ID != null){
                            $Consultant_Name = str_replace("'","#&39;",$Consultant);
                            $Insert_Data_To_tbl_patient_payment_item_list = "INSERT INTO tbl_patient_payment_item_list(check_In_type,item_id,discount,price,quantity,patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Clinic_ID,finance_department_id,clinic_location_id,Sub_Department_ID,Treatment_Authorization_No,Treatment_Authorizer) VALUES('$Check_In_Type','$Item_ID','$Discount','$Price','$dispensed_qty','$Patient_Direction','$Consultant_Name','$Consultant_ID','$Patient_Payment_ID',(select now()),'$Clinic_ID','$finance_department_id','$clinic_location_id','$Sub_Department_ID','$Treatment_Authorization_No','$Treatment_Authorizer')";

                            if (!mysqli_query($this->db_connect, $Insert_Data_To_tbl_patient_payment_item_list)) {
                                $HAS_ERROR = true;
                                die('Some went wrong =>' . $Patient_Payment_ID);
                            }else{
                                $get_latest_patient_payment_id = mysqli_query($this->db_connect, "SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE  Registration_ID = '$Registration_ID'");
                                while ($get  = mysqli_fetch_assoc($get_latest_patient_payment_id)) {
                                    $Patient_Payment_ID = $get['Patient_Payment_ID'];
                                }

                                $result = "UPDATE tbl_item_list_cache SET status = '$sts', Dispensor='$Employee_ID', Sub_Department_ID = '$Sub_Department_ID',Patient_Payment_ID = '$Patient_Payment_ID', Dispense_Date_Time =NOW(),Payment_Date_And_Time = '$Payment_Date_And_Time', Edited_Quantity = $dispensed_qty,dose = '$dose_qty', dispensed_quantity = '$total_dispensed',dosage_duration = '$dose_duration' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Check_In_Type='Pharmacy'";

                                if (mysqli_query($this->db_connect, $result)) {
                                    $medication = mysqli_query($this->db_connect, "SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($this->db_connect));
                                    $nm = mysqli_num_rows($medication);
                                    if ($nm > 0) {
                                        while ($dt = mysqli_fetch_array($medication)) {
                                            $Pre_Balance = $dt['Item_Balance'];
                                        }
                                    }

                                    $upd = mysqli_query($this->db_connect, "UPDATE tbl_items_balance set Item_Balance = (Item_Balance - '$dispensed_qty'), Item_Temporary_Balance  = (Item_Temporary_Balance - '$dispensed_qty') WHERE Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($this->db_connect));
                                    if (!$upd) { $HAS_ERROR = true;die("04"); }

                                    $insert_audit = mysqli_query($this->db_connect, "INSERT INTO tbl_stock_ledger_controler(Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number) VALUES('$Item_ID','$Sub_Department_ID','Dispensed','$Registration_ID','$Pre_Balance',($Pre_Balance - $Quantity),(select now()),(select now()),'$Patient_Payment_ID')") or die(mysqli_error($this->db_connect));
                                    if (!$insert_audit) { $HAS_ERROR = true;die("02"); }
                                } else {
                                    $HAS_ERROR = true; die("03");
                                }
                            }
                        }
                    }
                }
            }
        }
        if(!$HAS_ERROR){
            mysqli_commit($this->db_connect);
            return 'Medication Dispensed Successful';
        }else{
            mysqli_rollback($this->db_connect);
            return'SOMETHING WENT WRONG CONTACT ADMINISTRATOR FOR SUPPORT';
        }
    }

    # @Pass
    public function filterQuantityDispenseMedicationReport($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Employee_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID){
        $result = array();
        $count = 0;
        $filter = "";

        if ($Sponsor_ID != 'all') {
            $filter .= "  AND sp.Sponsor_ID='$Sponsor_ID' ";
        }

        if ($Employee_ID != 'all') {
            $filter .= "  AND ilc.Dispensor='$Employee_ID' ";
        }

        if ($Bill_Type == 'All') {
            if ($Payment_Mode == 'Cash') {
                $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " and (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') ";
            }
        } else if ($Bill_Type == 'Outpatient') {
            if ($Payment_Mode == 'All') {
                $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') ";
            } else if ($Payment_Mode == 'Cash') {
                $filter .= " and pp.Billing_Type = 'Outpatient Cash' ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " and pp.Billing_Type = 'Outpatient Credit' ";
            }
        } else if ($Bill_Type == 'Inpatient') {
            if ($Payment_Mode == 'All') {
                $filter .= " and (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') ";
            } else if ($Payment_Mode == 'Cash') {
                $filter .= " and pp.Billing_Type = 'Inpatient Cash' ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " and pp.Billing_Type = 'Inpatient Credit' ";
            }
        }

        if (!empty($Search_Patient)) {
            $filter .= " AND pr.Patient_Name LIKE '%" . $Search_Patient . "%'";
        }

        $filter .= " and pp.Transaction_status <> 'cancelled' ";

        if (isset($_GET['End_Date']) && $End_Date != '' && isset($_GET['Start_Date']) && $Start_Date != '') {
            $filter .= " AND ilc.Dispense_Date_Time  BETWEEN '$Start_Date' AND '$End_Date' ";
        }

        $select = mysqli_query($this->db_connect, "SELECT pp.Patient_Payment_ID, pp.Registration_ID,sp.Guarantor_Name, pr.Date_Of_Birth, pr.Gender, pr.Patient_Name, pp.Billing_Type From
                            tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc WHERE ilc.Patient_Payment_ID = pp.Patient_Payment_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and ilc.Sub_Department_ID=$Sub_Department_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.Patient_Payment_ID IS NOT NULL 
                            $filter group by ilc.Patient_Payment_ID ORDER BY ilc.Patient_Payment_ID") or die(mysqli_error($this->db_connect));

        if(mysqli_num_rows($select) > 0){
            while ($data = mysqli_fetch_assoc($select)) {
                $res = array();
                array_push($result,$data);

                $Patient_Payment_ID = $data['Patient_Payment_ID'];
                $medication = mysqli_query($this->db_connect, "SELECT emp.Employee_Name AS Dispensor_Name,i.Product_Name,ilc.Edited_Quantity,ilc.Quantity,(ilc.dispensed_quantity*ilc.Price) as sub_total_from_dispense_qty,(ilc.Quantity*ilc.Price) as sub_total_from_qty,(ilc.Edited_Quantity*ilc.Price) as sub_total_from_edited_dispense_qty, ilc.Price, ilc.Discount, ilc.Consultant_ID, ilc.Doctor_Comment,ilc.dispensed_quantity, ilc.Dispense_Date_Time FROM
                                    tbl_item_list_cache ilc, tbl_items i, tbl_employee emp WHERE ilc.Sub_Department_ID=$Sub_Department_ID AND ilc.Item_ID = i.Item_ID AND ilc.Dispensor = emp.Employee_ID  AND ilc.Dispense_Date_Time BETWEEN '$Start_Date' AND '$End_Date' AND ilc.Check_In_Type = 'Pharmacy' AND ilc.Patient_Payment_ID = '$Patient_Payment_ID' ORDER BY ilc.Payment_Item_Cache_List_ID ASC") or die(mysqli_error($this->db_connect));

                while($rows = mysqli_fetch_assoc($medication)){ array_push($res,$rows);}
                array_push($result[$count],$res);
                $count++;
            }
        }
        return json_encode($result);
    }

    # @Pass
    public function getTodayDateTimeFromDB(){
        $Today_Date = mysqli_query($this->db_connect, "SELECT now() as today");
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date;
        }
        return $Today;
    }

    public function fetchPatientCurrentAge($patient_date_of_birth){
        $query_date_time = $this->db_connect->query("SELECT now() as Date_Time") or die($this->db_connect->errno."Failed to fetch current patient age");
        $date_time = $query_date_time->fetch_assoc()['Date_Time'];

        $date1 = new DateTime($date_time);
        $date2 = new DateTime($patient_date_of_birth);
        $diff = $date1->diff($date2);
        return $diff->y . " Years, ".$diff->m . " Months, ".$diff->d . " Days";
    }

    # @Pass under construction
    public function getQuantityDispensedPatientDetails($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID,$Sponsor_ID){
        $result = array();
        $sponsor_filter = ($Sponsor_ID == "all") ? " " : " AND ts.Sponsor_ID=$Sponsor_ID AND pc.Sponsor_ID=$Sponsor_ID ";
        $query = mysqli_query($this->db_connect,"SELECT ilc.Dispense_Date_Time,ilc.price,Last_Buy_Price,i.Product_Name, pr.Phone_Number,pc.Billing_Type, ts.Guarantor_Name, pr.Registration_ID, ilc.Dispense_Date_Time, pr.Patient_Name, ilc.Patient_Payment_ID, ilc.Quantity, ilc.Edited_Quantity,ilc.dispensed_quantity from
                                            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pr.Sponsor_ID = ts.Sponsor_ID and
                                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and i.Item_ID = ilc.Item_ID and pr.Registration_ID = pc.Registration_ID and
                                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Status IN ('dispensed','partial dispensed') and ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                            i.Item_ID = '$Item_ID' $sponsor_filter");

        while($data = mysqli_fetch_assoc($query)){ array_push($result,$data); }
        mysqli_free_result($query);
        return json_encode($result);
    }

    # @Pass
    public function getItemName($Item_ID){
        $sql = "SELECT Product_Name FROM tbl_items WHERE Item_ID = $Item_ID LIMIT 1";
        return mysqli_fetch_assoc(mysqli_query($this->db_connect,$sql))['Product_Name'];
    }

    # @Pass
    public function patientDemographicDetails($Registration_ID){
        $result = array();
        $Query = "SELECT * FROM tbl_patient_registration pr, tbl_sponsor sp WHERE sp.Sponsor_ID = pr.Sponsor_ID AND pr.Registration_ID = '$Registration_ID'";
        $select_Patient = mysqli_query($this->db_connect,$Query) or die(mysqli_error($this->db_connect));
        while($data = mysqli_fetch_assoc($select_Patient)){ array_push($result,$data); }
        mysqli_free_result($select_Patient);
        return json_encode($result);
    }

    # @Pass
    public function clinicLists(){
        $result = array();
        $Query = "SELECT sub.Sub_Department_ID,sub.Sub_Department_Name from tbl_sub_department sub,tbl_department de WHERE sub.Department_ID= de.Department_ID AND Department_Location='Clinic'";
        $select_Patient = mysqli_query($this->db_connect,$Query) or die(mysqli_error($this->db_connect));
        while($data = mysqli_fetch_assoc($select_Patient)){ array_push($result,$data); }
        mysqli_free_result($select_Patient);
        return json_encode($result);
    }

    # @Pass
    public function workingDepartment(){
        $result = array();
        $Query = "SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'";
        $select_Patient = mysqli_query($this->db_connect,$Query) or die(mysqli_error($this->db_connect));
        while($data = mysqli_fetch_assoc($select_Patient)){ array_push($result,$data); }
        mysqli_free_result($select_Patient);
        return json_encode($result);
    }

    #
    public function notDispensedMedication($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID){
        $result = array();
        $count = 0;
        $filter = "";

        $filter .= " AND ilc.Transaction_Date_And_Time  BETWEEN '$Start_Date' AND '$End_Date'   AND ilc.Sub_Department_ID = '$Sub_Department_ID' ";
        if ($Bill_Type == 'All') {
            if ($Payment_Mode == 'Cash') {
                $filter .= " AND (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " AND (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') ";
            }
        } else if ($Bill_Type == 'Outpatient') {
            if ($Payment_Mode == 'All') {
                $filter .= " AND (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') ";
            } else if ($Payment_Mode == 'Cash') {
                $filter .= " AND pp.Billing_Type = 'Outpatient Cash' ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " and pp.Billing_Type = 'Outpatient Credit' ";
            }
        } else if ($Bill_Type == 'Inpatient') {
            if ($Payment_Mode == 'All') {
                $filter .= " AND (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') ";
            } else if ($Payment_Mode == 'Cash') {
                $filter .= " AND pp.Billing_Type = 'Inpatient Cash' ";
            } else if ($Payment_Mode == 'Credit') {
                $filter .= " AND pp.Billing_Type = 'Inpatient Credit' ";
            }
        }

        if (!empty($Search_Patient)) {
            $filter .= " AND pr.Patient_Name LIKE '%" . $Search_Patient . "%'";
        }

        if ($Sponsor_ID != 'all')
            $filter .= "  AND sp.Sponsor_ID='$Sponsor_ID' ";


        $select = mysqli_query($this->db_connect, "SELECT pp.Payment_Cache_ID, pp.Registration_ID, sp.Guarantor_Name, pr.Date_Of_Birth, pr.Gender, pr.Patient_Name,pp.Billing_Type,ilc.status FROM tbl_payment_cache pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc WHERE pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND pr.Sponsor_ID = sp.Sponsor_ID AND pr.Registration_ID = pp.Registration_ID AND ilc.Check_In_Type = 'Pharmacy' AND ilc.status <> 'dispensed' $filter GROUP BY pp.Payment_Cache_ID order by Payment_Cache_ID") or die(mysqli_error($this->db_connect));

        if(mysqli_num_rows($select) > 0){
            while ($data = mysqli_fetch_assoc($select)) {
                $res = array();
                array_push($result,$data);
                $Payment_Cache_ID = $data['Payment_Cache_ID'];

                $medication = mysqli_query($this->db_connect, "SELECT i.Product_Name, ilc.Quantity, ilc.Price, ilc.Discount, ilc.Consultant_ID, ilc.Doctor_Comment, ilc.Transaction_Date_And_Time,ilc.Status  from
                                tbl_item_list_cache ilc, tbl_items i,  tbl_payment_cache pc where
                                i.Item_ID = ilc.Item_ID and ilc.Payment_Cache_ID='$Payment_Cache_ID' and
                                pc.Payment_Cache_ID='$Payment_Cache_ID' and
                                i.Item_ID = ilc.Item_ID and
                                ilc.Check_In_Type = 'Pharmacy' and pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                ilc.status <> 'dispensed' order by ilc.Transaction_Date_And_Time desc") or die(mysqli_error($this->db_connect));

                while($rows = mysqli_fetch_assoc($medication)){ array_push($res,$rows);}
                array_push($result[$count],$res);
                $count++;

            }
        }

        return json_encode($result);
    }

    # @Pass
    public function getDispenseList($Start_date,$end_date,$Patient_Name,$Patient_Number,$Bill_Type){
        $result = array();
        $filter = "";
        $filter .= ($Patient_Number != "") ? " AND pc.Registration_ID = $Patient_Number " : "";
        $filter .= ($Patient_Name != "") ? " AND pr.Patient_Name LIKE '%$Patient_Name%' " : "";
        $filter .= ($Bill_Type == "all") ? " AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit','Outpatient Cash') " : "AND pc.Billing_Type IN ('$Bill_Type') ";
        $sql_get_dispense_list = "SELECT Dispense_Date_Time,pc.Payment_Cache_ID,Patient_Name,pr.Registration_ID,Dispense_Date_Time,Billing_Type,ts.Guarantor_Name FROM tbl_patient_registration pr,tbl_payment_cache pc,tbl_item_list_cache ilc, tbl_sponsor ts WHERE pr.Registration_ID=pc.Registration_ID $filter AND pc.Sponsor_ID = ts.Sponsor_ID AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND Check_In_Type IN('Pharmacy') AND ilc.Status='dispensed' AND Dispense_Date_Time BETWEEN '$Start_date' AND '$end_date' GROUP BY pc.Payment_Cache_ID,Dispense_Date_Time ORDER BY Dispense_Date_Time DESC ";

        $dispense_list = mysqli_query($this->db_connect,$sql_get_dispense_list) or die(mysqli_errno($this->db_connect)) ;
        while($data = mysqli_fetch_assoc($dispense_list)){ array_push($result,$data); }
        return json_encode($result);
    }

    # @Pass
    public function checkReasonsForIfConfigured(){
        $sql_check_configuration = "SELECT Change_Medication_Location FROM tbl_system_configuration";
        $result =  mysqli_fetch_assoc(mysqli_query($this->db_connect,$sql_check_configuration))['Change_Medication_Location'];
        return $result;
    }

    # @Pass
    public function removeItem($reason_for_remove,$Patient_Payment_Item_List,$Item_ID){
        $sql = "UPDATE tbl_item_list_cache SET Status = 'removed',remarks='$reason_for_remove' WHERE Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List' AND Item_ID = $Item_ID lIMIT 1 ";
        $remove_query = mysqli_query($this->db_connect,$sql);
        return (!$remove_query) ? 'Some went wrong '.mysqli_error($this->db_connect) : 'updated';
    }

    # @Pass
    public function itemPerStatus($Payment_Cache_ID){
        $result = array();
        $sql = "SELECT * FROM tbl_item_list_cache AS tilc,tbl_items AS ti WHERE tilc.Payment_Cache_ID = $Payment_Cache_ID AND tilc.Status = 'removed' AND tilc.Item_ID = ti.Item_ID";
        $__selected_items = mysqli_query($this->db_connect,$sql);
        while($data = mysqli_fetch_assoc($__selected_items)){ array_push($result,$data); }
        mysqli_free_result($__selected_items);
        return json_encode($result);
    }

    # @Pass
    public function updateItemStatus($Patient_Payment_Item_List){
        $sql = "UPDATE tbl_item_list_cache SET Status = 'active' WHERE Payment_Item_Cache_List_ID = $Patient_Payment_Item_List";
        $__update_item = mysqli_query($this->db_connect,$sql);
        return (!$__update_item) ? "Error Failed to update contact admin for support : ".mysqli_errno($this->db_connect) : 1;
    }



    public function addNewReasons($new_reasons){
        $sql = "INSERT INTO tbl_item_removal (description,status) VALUES ('$new_reasons','active')";
        $__insert_reasons = mysqli_query($this->db_connect,$sql);
        return (!$__insert_reasons) ? "Fail to update : ".mysqli_error($this->db_connect) : 1;
    }

    public function enableAndDisableReasons($Id,$status){
        $sql = "UPDATE tbl_item_removal SET status = '$status' WHERE id = $Id";
        $__update_status = mysqli_query($this->db_connect,$sql);
        return (!$__update_status) ? "Fail to update : ".mysqli_error($this->db_connect) : 1;
    }

    public function checkBalanceForDuplicateItems($Payment_Cache_ID){
        $result = array();
        $get_medication_ordered = $this->db_connect->query("SELECT tilc.Item_ID, COUNT(*) c,SUM(Quantity) as Quantity,SUM(Edited_Quantity) AS Edited_Quantity,Item_Balance FROM tbl_item_list_cache AS tilc,tbl_items_balance AS tsb WHERE tilc.`Payment_Cache_ID` = $Payment_Cache_ID AND tilc.Sub_Department_ID = tsb.Sub_Department_ID AND tilc.Item_ID = tsb.Item_ID AND tilc.Status IN ('active','partial dispensed') GROUP BY Item_ID HAVING c > 1 ");

        while($data = $get_medication_ordered->fetch_assoc()){
            if((int)$data['Edited_Quantity'] > (int)$data['Item_Balance']){
                array_push($result,$this->getItemName($data['Item_ID']));
            }
        }
        return json_encode($result);
    }

    public function returnMedication($Payment_Cache_ID,$return_medication,$Employee_ID,$Sub_Department_ID,$Registration_ID){
        $ERROR_OCCURRED = 0;
        $status = "";
        mysqli_begin_transaction($this->db_connect);
        foreach($return_medication as $item_list){
            $returned_item_array=explode("Join",$item_list);
            $Payment_Item_Cache_List_ID=$returned_item_array[0];
            $Item_ID=$returned_item_array[1];
            $Patient_Payment_ID=$returned_item_array[2];
            $Previous_qty=$returned_item_array[3];
            $Return_Qty=$returned_item_array[4];

            $sql_save_returned_item_history_result=$this->db_connect->query("INSERT INTO tbl_returned_dispensed_item_to_pharmacy_hstry(Registration_ID,Item_ID,Payment_Item_Cache_List_ID,previous_quantity,returned_quantity,received_by,received_date,received_pharmacy) VALUES('$Registration_ID','$Item_ID','$Payment_Item_Cache_List_ID','$Previous_qty','$Return_Qty','$Employee_ID',NOW(),'$Sub_Department_ID')") or die($this->db_connect->error);
            if(!$sql_save_returned_item_history_result){
                $ERROR_OCCURRED++;
            }

            $Edited_Quantity=$Previous_qty-$Return_Qty;
            $status =($Previous_qty == $Return_Qty) ? "status = 'active',dose = '0',dispensed_quantity='0'," : "";

            # record transaction in stock ledger
            $calculate_balance = $this->db_connect->query("SELECT Pre_Balance,Post_Balance FROM tbl_stock_ledger_controler WHERE Item_ID  = '$Item_ID' AND Sub_Department_ID = '$Sub_Department_ID' ORDER BY Controler_ID DESC LIMIT 1");

            if ($calculate_balance->num_rows > 0) {
                while ($data = $calculate_balance->fetch_assoc()) {
                    $pre_balance = $data['Pre_Balance'];
                    $post_balance = $data['Post_Balance'];
                    (int)$new_balance = (int)$post_balance + (int)$Return_Qty;

                    $numbers = $this->db_connect->query("SELECT * FROM tbl_returned_dispensed_item_to_pharmacy_hstry WHERE Item_ID = '$Item_ID' AND Registration_ID = '$Registration_ID' AND received_by = '$Employee_ID' ORDER BY returned_dispensed_item_to_pharmacy_hstry_id DESC LIMIT 1");

                    while ($data = $numbers->fetch_assoc()) {
                        $doc_numbers = $data['returned_dispensed_item_to_pharmacy_hstry_id'];
                        $update_record_in_stock_ledger = $this->db_connect->query("INSERT INTO tbl_stock_ledger_controler(Item_ID,Sub_Department_ID,Document_Number,Movement_Type,Registration_ID,Pre_Balance,Post_Balance,Movement_Date,Movement_Date_Time) 
                                VALUES('$Item_ID','$Sub_Department_ID','$doc_numbers','Return','$Registration_ID','$post_balance','$new_balance',NOW(),NOW())") or die(($this->db_connect->error));
                        if (!$update_record_in_stock_ledger) {
                            $ERROR_OCCURRED++;
                        }
                    }
                }
            }

            //reduce from patient file and bill Quantity
            $sql_reduce_from_patient_file_result = $this->db_connect->query("UPDATE tbl_item_list_cache SET $status Edited_Quantity='$Edited_Quantity', dispensed_quantity='$Edited_Quantity'  WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die($this->db_connect->error);
            if (!$sql_reduce_from_patient_file_result) { $ERROR_OCCURRED++; }

            # FOR SAIFEE HOSPITAL
            // $sql_reduce_from_patient_file_result = $this->db_connect->query("UPDATE tbl_item_list_cache SET $status Quantity='$Edited_Quantity'  WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die($this->db_connect->error);
            // if (!$sql_reduce_from_patient_file_result) { $ERROR_OCCURRED++; }

            //Reduce quantity from patient bill
            $sql_reduce_quantity_in_patient_bill_result = $this->db_connect->query("UPDATE tbl_patient_payment_item_list SET Quantity='$Edited_Quantity' WHERE Item_ID='$Item_ID' AND Patient_Payment_ID='$Patient_Payment_ID'") or die($this->db_connect->error);
            if (!$sql_reduce_quantity_in_patient_bill_result) {
                $ERROR_OCCURRED++;
            }

            //update stock balance
            $sql_update_item_stock_balance_result = $this->db_connect->query("UPDATE tbl_items_balance SET Item_Balance=Item_Balance+'$Return_Qty',Item_Temporary_Balance=Item_Temporary_Balance+'$Return_Qty' WHERE Item_ID='$Item_ID' AND Sub_Department_ID='$Sub_Department_ID'") or die($this->db_connect->error);
            if (!$sql_update_item_stock_balance_result) {
                $ERROR_OCCURRED++;
            }
        }
        if ($ERROR_OCCURRED <= 0) {
            mysqli_commit($this->db_connect);
            echo "Quantity returned successfully";
        } else {
            mysqli_rollback($this->db_connect);
            echo "fail to return";
        }
    }

    #not complete
    public function summaryPatientFile($Registration_ID){
        $result = array();
        $getPatientMedication = mysqli_query($this->db_connect,"SELECT tpc.Payment_Cache_ID,ti.Product_Name,tpc.consultation_id,tilc.Status,c.Consultation_Date_And_Time,Employee_Name,tilc.Doctor_Comment,maincomplain,systemic_observation,disease_name,general_observation
            FROM tbl_payment_cache AS tpc,tbl_item_list_cache AS tilc, tbl_items AS ti, tbl_consultation AS c,tbl_employee AS e,tbl_disease_consultation AS tdc,tbl_disease AS td
            WHERE tpc.Registration_ID = $Registration_ID AND tpc.Payment_Cache_ID = tilc.Payment_Cache_ID AND td.disease_ID = tdc.disease_ID AND c.employee_ID = e.Employee_ID AND tdc.consultation_ID = c.consultation_ID AND tpc.consultation_id = c.consultation_ID AND ti.Item_ID = tilc.Item_ID GROUP BY tpc.consultation_ID ORDER BY tpc.Payment_Cache_ID DESC");

        while($data = mysqli_fetch_assoc($getPatientMedication)){
            array_push($result,$data);
        }
    }

    # @Pass
    public function checkIfIsInRestrictMode($Sponsor_ID,$Item_ID){
        $sql_query = $this->db_connect->query("SELECT iut.IsRestricted FROM tbl_items i, tbl_item_update_tem iut WHERE i.Item_ID = $Item_ID AND iut.ItemCode = i.Product_Code  AND iut.sponsor_id = $Sponsor_ID GROUP BY i.Item_ID") or die(mysqli_errno($this->db_connect)." Error check item in restricted mode");
        return $sql_query->fetch_assoc()['IsRestricted'];
    }

    public function storeDetail($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID,$treatment_authorization_no,$Employee_ID){
        $sql_query = $this->db_connect->query("UPDATE tbl_item_list_cache SET Treatment_Authorization_No = '$treatment_authorization_no',Treatment_Authorizer = $Employee_ID WHERE Item_ID = $Item_ID AND Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die($this->db_connect->error." = Error Found while Update verify record");
        if(!$sql_query){
            echo 100;
        }else{
            echo 200;
        }
    }

    public function fetchOutpatientList($Patient_Name,$Patient_Number,$Phone_Number){
        $filter = "";
        $filter .= ($Patient_Name != "") ? " AND tpr.Patient_Name LIKE '%$Patient_Name%' " : "";
        $filter = ($Patient_Number != "" && is_numeric($Patient_Number)) ? " AND tpr.Registration_ID = $Patient_Number " : "";
        $filter .= ($Phone_Number != "") ? " AND tpr.Phone_Number = $Phone_Number " : "";

        $sql = "SELECT tpr.`Registration_ID`, ( SELECT Admission_Status FROM tbl_admission AS ta WHERE ta.`Registration_ID` = tpr.`Registration_ID` ORDER BY Admision_ID DESC limit 1 ) as Admission_Status, tpr.`Patient_Name`,tpr.Gender,ts.Guarantor_Name,tpr.Phone_Number,tpr.Date_Of_Birth FROM `tbl_patient_registration` AS tpr,tbl_sponsor AS ts WHERE tpr.`Patient_Name` != '' AND tpr.Sponsor_ID = ts.Sponsor_ID  $filter LIMIT 20";
        return $this->processSingleFetchQuery($sql,"ERROR WHILE FETCHING OUTPATIENT LIST");
    }

    public function fetchSingleInpatientDetails($Patient_Name,$Patient_Number){
        $sql = "SELECT tpr.Patient_Name,thw.Hospital_Ward_Name FROM `tbl_admission` as ta,`tbl_hospital_ward` as thw,tbl_patient_registration AS tpr WHERE ta.`Registration_ID` = $Patient_Number AND ta.`Hospital_Ward_ID` = thw.`Hospital_Ward_ID` AND ta.`Registration_ID` = tpr.`Registration_ID` ORDER BY ta.`Admision_ID` DESC LIMIT 1";
        return $this->processSingleFetchQuery($sql,"ERROR WHILE FETCHING OUTPATIENT LIST");
    }

    # Instruct
    public function fetchPharmacySubDepartmentByEmployee($Employee_ID){
        $sql = "SELECT sdep.Sub_Department_ID,Sub_Department_Name,privileges FROM tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed WHERE dep.department_id = sdep.department_id AND ed.Employee_ID = '$Employee_ID' AND ed.Sub_Department_ID = sdep.Sub_Department_ID AND Department_Location = 'Pharmacy' AND sdep.Sub_Department_Status = 'active'";
        return $this->processSingleFetchQuery($sql,"ERROR WHILE FETCHING SUB DEPARTMENTS");
    }

    public function authenticateUser($AuthDetails){
        $Supervisor_Username = $AuthDetails['Username'];
        $Supervisor_Password = md5($AuthDetails['Password']);

        $sql_check = "SELECT * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p WHERE b.branch_id = be.branch_id and e.employee_id = be.employee_id AND e.employee_id = p.employee_id AND p.Given_Username = '{$Supervisor_Username}' and p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';";

        $authenticate = $this->db_connect->query($sql_check) or die($this->db_connect->errno."ERROR WHILE AUTHENTICATE USER");
        return ($authenticate->num_rows > 0) ? 100 : 200;
    }
}
?>