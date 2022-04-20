<?php 
    declare(strict_types = 1);
    require 'db.config.php';

    class CommonRepo extends DBConfig{

        public $db_connect;

        function __construct(){
            $this->db_connect = $this->connect();
        }

        private function queryExecute($sql_query){
            $result = array();
            while($details = $sql_query->fetch_assoc()){ array_push($result,$details); }
            mysqli_free_result($sql_query);
            return json_encode($result);
        }

        public function fetchOutpatientList($clinic_id,$status,$start_Date,$end_Date,$patient_name,$patient_number,$patient_phone_number){
            $filter = "";
            $filter .= ($patient_name == "" || $patient_name == null) ? "" : " AND tpr.Patient_Name LIKE '%$patient_name%' ";
            $filter .= ($patient_number == "" || $patient_number == null) ? "" : " AND tpr.Registration_ID = '$patient_number' ";
            $filter .= ($patient_phone_number == "" || $patient_phone_number == null) ? "" : " AND tpr.Phone_Number = '$patient_phone_number' ";

            $query = $this->db_connect->query("SELECT * FROM `tbl_patient_payment_item_list` AS tppil,`tbl_patient_payments` AS tpp, tbl_patient_registration AS tpr WHERE tppil.`Clinic_ID` = $clinic_id AND tppil.`Patient_Payment_ID` = tpp.`Patient_Payment_ID` AND tpp.Registration_ID = tpr.Registration_ID AND tpp.Billing_Type IN ('Outpatient Cash','Outpatient Credit') AND tppil.`Status` = '$status' AND tppil.Transaction_Date_And_Time BETWEEN '$start_Date' AND '$end_Date' $filter LIMIT 50") or die($this->db_connect->errno." Failed to fetch outpatient customer list");
            return $this->queryExecute($query);
        }

        public function fetchPatientCurrentAge($patient_date_of_birth){
            $query_date_time = $this->db_connect->query("SELECT now() as Date_Time") or die($this->db_connect->errno."Failed to fetch current patient age");
            $date_time = $query_date_time->fetch_assoc()['Date_Time'];

            $date1 = new DateTime($date_time);
            $date2 = new DateTime($patient_date_of_birth);
            $diff = $date1->diff($date2);
            return $diff->y . " Years, ".$diff->m . " Months, ".$diff->d . " Days";
        }

        public function fetchWardsAssignedToEmployee($employee_id){
            $query = $this->db_connect->query("SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$employee_id'))") or die($this->db_connect->error." Failed while fetching assigned ward to employee");
            return $this->queryExecute($query);
        }

        public function fetchCurrentDateTime(){
            $query_date_time = $this->db_connect->query("SELECT now() as Date_Time") or die($this->db_connect->errno."Failed to fetch current patient age");
            $date_time = $query_date_time->fetch_assoc()['Date_Time'];
            return $date_time;
        }

        public function fetchInpatientListByWard($hospital_ward_id,$patient_name,$patient_number){
            $filter = "";
            $filter .= ($patient_name == "" || $patient_name == null) ? "" : " AND pr.Patient_Name LIKE '%$patient_name%' ";
            $filter .= ($patient_number == "" || $patient_number == null) ? "" : " AND pr.Registration_ID = '$patient_number' ";

            $query = $this->db_connect->query("SELECT tcid.consultation_ID,ta.Registration_ID,pr.Patient_Name,pr.Sponsor_ID,pr.Gender,pr.Phone_Number,pr.Date_Of_Birth,ts.Guarantor_Name,ta.Admision_ID FROM tbl_admission as ta,tbl_patient_registration as pr, tbl_sponsor AS ts, tbl_check_in_details AS tcid WHERE ta.Admission_Status = 'admitted' AND ta.Registration_ID = pr.Registration_ID AND ta.Hospital_Ward_ID = $hospital_ward_id AND ta.Admision_ID = tcid.Admission_ID AND  pr.Sponsor_ID = ts.Sponsor_ID $filter ORDER BY Admision_ID DESC LIMIT 25 ") or die($this->db_connect->errno."Failed to fetch inpatient List");
            return $this->queryExecute($query);
        }

        public function fetchVitals(){
            $query = $this->db_connect->query("SELECT * FROM tbl_vital") or die($this->db_connect->error." Failed while fetching assigned ward to employee");
            return $this->queryExecute($query);
        }

        public function fetchDiseases($disease_name){
            $filter = "";
            $filter .= ($disease_name == "") ? "" : " AND disease_name LIKE '%$disease_name%' OR disease_code LIKE '%$disease_name%' ";

            $icd_config_value = $this->db_connect->query("SELECT `configvalue` FROM `tbl_config` WHERE `configname`='Icd_10OrIcd_9'") or die($this->db_connect->errno." Fail to fetch diagnosis config");
            $icd_config_ = $icd_config_value->fetch_assoc()['configvalue'];

            $select_diseases = $this->db_connect->query("SELECT * FROM `tbl_disease` WHERE disease_version = '$icd_config_' $filter LIMIT 15") or die($this->db_connect->errno." Failed fetch diseases");
            return $this->queryExecute($select_diseases);
        }

        public function createConsultation($Patient_Payment_Item_List_ID,$Registration_ID,$Employee_ID){
            $get_last_consultation = $this->db_connect->query("SELECT * FROM `tbl_consultation` WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID = '$Registration_ID' LIMIT 1");

            if($get_last_consultation->num_rows < 1){
                $create_new_consultation = $this->db_connect->query("INSERT INTO tbl_consultation(employee_ID,Registration_ID,Patient_Payment_Item_List_ID,Process_Status) VALUES ('$Employee_ID','$Registration_ID','$Patient_Payment_Item_List_ID','pending')") or die($this->db_connect->error." Fail to create new consultation");
                if(!$create_new_consultation){
                    die($this->db_connect->error." Failed to create new consultation");
                }else{
                    $Get_Last_Consultation = $this->db_connect->query("SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID = $Registration_ID ORDER BY consultation_ID DESC LIMIT 1 ");
                    $Patient_Last_Consultation = $Get_Last_Consultation->fetch_assoc()['consultation_ID'];
                    $create_new_consultation = $this->db_connect->query("INSERT INTO tbl_consultation_history(consultation_ID,employee_ID,Saved) VALUES ('$Patient_Last_Consultation','$Employee_ID','no')") or die($this->db_connect->error." Fail to create new consultation history");
                }
            }
            $select_consultation_details = $this->db_connect->query("SELECT * FROM tbl_consultation AS tc,tbl_patient_registration AS tpr, tbl_sponsor AS ts WHERE tc.Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID AND tpr.Registration_ID = tc.Registration_ID AND tpr.Sponsor_ID = ts.Sponsor_ID ") or die($this->db_connect->errno." Fail to fetch last consultation for for the patient");
            return $this->queryExecute($select_consultation_details);
        }

        public function fetchDiseasesAddedInConsultation($consultation_id,$diagnosis_type,$patient_type){

            if($patient_type == "inpatient"){
                $get_last_null_round_for_employee = $this->db_connect->query("SELECT Round_ID FROM tbl_ward_round WHERE consultation_ID = $consultation_id AND Process_Status IS NULL ORDER BY Round_ID DESC LIMIT 1") or die($this->db_connect->errno."FAIL TO FETCH LAST WARD ROUND FOR THE PATIENT");

                $Round_ID = $get_last_null_round_for_employee->fetch_assoc()['Round_ID'];
                $query = $this->db_connect->query("SELECT * FROM tbl_ward_round_disease as twrd,tbl_disease as td WHERE twrd.Round_ID = $Round_ID AND twrd.disease_ID = td.disease_ID AND twrd.diagnosis_type = '$diagnosis_type'") or die($this->db_connect->errno." Error while fetching during consultation");
                return $this->queryExecute($query);
            }else{
                $query = $this->db_connect->query("SELECT * FROM tbl_disease_consultation as tdc,tbl_disease as td WHERE tdc.consultation_ID = $consultation_id AND tdc.disease_ID = td.disease_ID AND tdc.diagnosis_type = '$diagnosis_type'") or die($this->db_connect->errno." Error while fetching during consultation");
                return $this->queryExecute($query);
            }
        }

        public function createNewDiagnosis($consultation_id,$disease_id,$employee_id,$diagnosis_type,$patient_type){
            if($patient_type == "inpatient"){
                $get_last_null_round_for_employee = $this->db_connect->query("SELECT Round_ID FROM tbl_ward_round WHERE consultation_ID = $consultation_id AND Employee_ID = $employee_id AND Process_Status IS NULL") or die($this->db_connect->errno."FAIL TO FETCH LAST WARD ROUND FOR THE PATIENT");
                $Round_ID = $get_last_null_round_for_employee->fetch_assoc()['Round_ID'];

                $query = $this->db_connect->query("INSERT INTO tbl_ward_round_disease(disease_ID,diagnosis_type,Round_Disease_Date_And_Time,Round_ID) VALUES('$disease_id','$diagnosis_type',NOW(),'$Round_ID')") or die($this->db_connect->error." Error while adding new diagnosis");
                return ($query) ?  100 : 200;
            }else{
                $check_if_diagnosis_is_added_already = $this->db_connect->query("SELECT * FROM tbl_disease_consultation WHERE disease_ID = $disease_id AND consultation_id = $consultation_id AND diagnosis_type = '$diagnosis_type' LIMIT 1 ") or die($this->db_connect->errno." Failed to check if diagnosis exists");

                if($check_if_diagnosis_is_added_already->num_rows > 0){
                    return 300;
                }else{
                    $query = $this->db_connect->query("INSERT INTO tbl_disease_consultation(disease_ID,consultation_ID,employee_ID,diagnosis_type,Disease_Consultation_Date_And_Time) VALUES('$disease_id','$consultation_id','$employee_id','$diagnosis_type',NOW())") or die($this->db_connect->errno." Error while adding new diagnosis");
                    return ($query) ?  100 : 200;
                }
            }
        }

        public function removeDiseasesForOutpatient($consultation_id,$disease_id,$diagnosis_type){
            $query = $this->db_connect->query("DELETE FROM tbl_disease_consultation WHERE consultation_ID = $consultation_id AND disease_ID = $disease_id AND diagnosis_type = '$diagnosis_type'") or die($this->db_connect->errno." Fail to remove disease");
            return ($query) ? 100 : 200;
        }


        public function fetchItems($sponsor_id,$consultation_type,$procedure_name_){
            $filter = "";
            $filter .= ($procedure_name_ == "") ? "" : "AND ti.Product_Name LIKE '%$procedure_name_%'";
            
            $select_procedure_with_prices = $this->db_connect->query("SELECT * FROM tbl_items AS ti,tbl_item_price AS tip WHERE ti.Consultation_Type = '$consultation_type' AND tip.Item_ID = ti.Item_ID AND tip.Sponsor_ID = $sponsor_id AND tip.Items_Price > 0 $filter LIMIT 100");
            return $this->queryExecute($select_procedure_with_prices);
        }

        public function fetchSingleItemDetails($item_id,$sponsor_id,$Sub_Department_ID){
            if($Sub_Department_ID == ""){
                $sql = "SELECT * FROM tbl_items AS ti,tbl_item_price AS tip WHERE ti.Item_ID = '$item_id' AND tip.Item_ID = ti.Item_ID AND tip.Sponsor_ID = $sponsor_id";
            }else{
                $sql = "SELECT * FROM tbl_items AS ti,tbl_item_price AS tip,tbl_items_balance AS tib WHERE ti.Item_ID = '$item_id' AND tip.Item_ID = ti.Item_ID AND tip.Sponsor_ID = $sponsor_id AND tib.Sub_Department_ID = $Sub_Department_ID AND tib.Item_ID = '$item_id'";
            }

            $select_single_items = $this->db_connect->query($sql) or die($this->db_connect->errno.": Error while fetching items");
            return $this->queryExecute($select_single_items);
        }

        public function fetchSubdepartment($department_location){
            $select_sub_departments =  $this->db_connect->query("SELECT Sub_Department_Name, Sub_Department_ID, Department_Name FROM tbl_sub_department sd, tbl_department dp WHERE dp.Department_ID = sd.Department_ID AND dp.Department_Location IN ('$department_location')");
            return $this->queryExecute($select_sub_departments);
        }

        public function createItemForPatient($Consultation_ID,$Registration_ID,$Employee_ID,$Sponsor_ID,$Check_In_Type,$Item_ID,$Quantity,$Sub_Department_ID,$Doctor_Comment,$Procedure_Location){
            $select_payment_cache_for_the_consultation = $this->db_connect->query("SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_id = $Consultation_ID AND Registration_ID = $Registration_ID") or die($this->db_connect->errno." Fail to fetch payment cache");

            $select_patient_sponsor_payment_method = $this->db_connect->query("SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'") or die($this->db_connect->errno." Failed to fetch sponsor payment method");
            $Payment_Method = $select_patient_sponsor_payment_method->fetch_assoc()['payment_method'];
            $billing_type = "Outpatient ".ucfirst($Payment_Method);

            if($select_payment_cache_for_the_consultation->num_rows < 1){
                $create_new_payment_cache_id = $this->db_connect->query("INSERT INTO tbl_payment_cache (Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Sponsor_ID,Billing_Type,Receipt_Date,Transaction_type,Order_Type,branch_id) VALUES ('$Registration_ID','$Employee_ID',$Consultation_ID,NOW(),'$Sponsor_ID','$billing_type',NOW(),'indirect cash','normal',1)") or die($this->db_connect->error." Error while creating payment cache id");
                if(!$create_new_payment_cache_id){ return 300; }
            }

            $select_last_payment_cache = $this->db_connect->query("SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_id = $Consultation_ID AND Registration_ID = $Registration_ID ") or die($this->db_connect->errno." Fail to select payment id for this transaction");
            $Payment_Cache_ID = $select_last_payment_cache->fetch_assoc()['Payment_Cache_ID'];

            $Get_Item_Price = $this->db_connect->query("SELECT Items_Price FROM tbl_item_price WHERE Item_ID = $Item_ID AND Sponsor_ID = $Sponsor_ID") or die($this->db_connect->errno." Failed to fetch item price");
            $Item_Price = $Get_Item_Price->fetch_assoc()['Items_Price'];

            $Get_Consultant_Details = $this->db_connect->query("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID' LIMIT 1") or die($this->db_connect->errno."Fail to read consultant details");
            $Consultant_Name = $Get_Consultant_Details->fetch_assoc()['Employee_Name'];

            $Payment_Type = (strtolower($Payment_Method) == 'cash') ? "pre" : "post";

            $create_item = $this->db_connect->query("INSERT INTO tbl_item_list_cache (Check_In_Type,Category,Item_ID,Price,Quantity,Patient_Direction,Consultant,Status,Employee_Created,Payment_Cache_ID,Transaction_Date_And_Time,Process_Status,Doctor_Comment,Sub_Department_ID,Transaction_Type,payment_type,Procedure_Location,Consultant_ID) VALUES ('$Check_In_Type','indirect cash','$Item_ID','$Item_Price','$Quantity','other','$Consultant_Name','active','$Employee_ID','$Payment_Cache_ID',NOW(),'inactive','$Doctor_Comment','$Sub_Department_ID','$Payment_Method','$Payment_Type','$Procedure_Location','$Employee_ID')") or die($this->db_connect->error." Fail to create selected item");
            return (!$create_item) ? 200 : 100;
        }

        public function fetchActiveItemGivenToAPatient($Registration_ID,$Check_In_Type,$Consultation_ID){
            $filter = "";
            $filter .= ($Check_In_Type == "Procedure") ? " AND tilc.Status = 'active' " : "";

            $Get_Last_Payment_ID = $this->db_connect->query("SELECT `Payment_Cache_ID` FROM `tbl_payment_cache` WHERE `Registration_ID` = '$Registration_ID' AND consultation_id = '$Consultation_ID' ORDER BY `Payment_Cache_ID` DESC LIMIT 1 ") or die($this->db_connect->errno." :: Fail to fetch last payment id");

            if($Get_Last_Payment_ID->num_rows > 0){
                $Last_Payment_Cache_ID = $Get_Last_Payment_ID->fetch_assoc()['Payment_Cache_ID'];
                $Get_Active_Items = $this->db_connect->query("SELECT * FROM tbl_item_list_cache AS tilc, tbl_items AS ti WHERE tilc.Payment_Cache_ID = $Last_Payment_Cache_ID AND tilc.Check_In_Type = '$Check_In_Type' AND ti.Item_ID = tilc.Item_ID $filter") or die($this->db_connect->error." :: Fail to get active items");
                return $this->queryExecute($Get_Active_Items);
            }else{
                return json_encode(array());
            }
        }

        public function removeItemFromAPatient($Payment_Item_Cache_List_ID){
            $delete_query = $this->db_connect->query("DELETE FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die($this->db_connect->error." :: Fail to delete the Items");
            return (!$delete_query) ? 200 : 100;
        }


        public function fetchEmployeeAssignedClinic($Employee_ID,$Clinic_ID,array $templates_status){
            $filter = "";
            $clinic_template = "";
            if(sizeof($templates_status) > 0){
                $template = "";
                foreach($templates_status as $templates){
                    $clinic_template .= "'".$templates."',";
                }
                $template = rtrim($clinic_template,",");
                $filter .= " WHERE template IN ($template) ";
            }

            $Fetch_Assigned_Clinic = $this->db_connect->query("SELECT * FROM tbl_clinic $filter ") or die($this->db_connect->errno." :: Error while getting Employee Assigned Clinic's");
            return $this->queryExecute($Fetch_Assigned_Clinic);
        }

        public function userAuthentication($Supervisor_Username,$Supervisor_Password){
            $encrypted_password = md5($Supervisor_Password);
            $validate_user = $this->db_connect->query("SELECT * FROM tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p WHERE b.branch_id = be.branch_id AND be.employee_id = e.employee_id AND e.employee_id = p.employee_id AND p.Given_Username = '{$Supervisor_Username}' AND p.Given_Password  = '{$encrypted_password}' AND p.Session_Master_Priveleges = 'yes';");
            return ($validate_user->num_rows > 0) ? 100 : 200;
        }

        public function fetchSinglePatientDetailsForParticularConsultation($Registration_ID,$Patient_Payment_Item_List_ID){
            $get_patient_details = $this->db_connect->query("SELECT * FROM `tbl_patient_payment_item_list` AS tppil,tbl_patient_payments AS tpp, tbl_patient_registration AS tpr,tbl_sponsor AS ts,tbl_items AS ti WHERE tppil.`Patient_Payment_Item_List_ID` = $Patient_Payment_Item_List_ID AND tppil.`Patient_Payment_ID` = tpp.`Patient_Payment_ID` AND tpp.Registration_ID = tpr.Registration_ID AND tpr.Registration_ID = $Registration_ID AND tpr.Sponsor_ID = ts.Sponsor_ID AND tppil.Item_ID = ti.Item_ID") or die($this->db_connect->errno." :: Failed to fetch patient details");
            return $this->queryExecute($get_patient_details);
        }

        public function fetchClinicTemplate($Clinic_ID){
            $get_clinic_template = $this->db_connect->query("SELECT * FROM tbl_clinic WHERE Clinic_ID = $Clinic_ID") or die($this->db_connect->error." : Error while getting clinic template");
            return $this->queryExecute($get_clinic_template);
        }

        # Extension Needed
        public function createPhysiotherapyNotesOutpatient($Patient_Payment_Item_List_ID,$Registration_ID,
            $Employee_ID,$main_complain,$history_of_past_illness,$past_medical_history,
            $relevant_social_and_family_history,$case_type,
            $general_observation,$local_examination,$neurovascular,
            $functional,$procedure_comments,$evolution){
            $save_notes = $this->db_connect->query("UPDATE tbl_consultation SET maincomplain = '$main_complain',Employee_ID = '$Employee_ID',history_present_illness = '$history_of_past_illness',general_observation='$general_observation',Comments_For_Procedure='$procedure_comments',remarks='$evolution',Process_Status='saved',Type_of_patient_case='$case_type',past_medical_history='$past_medical_history',local_examination='$local_examination',family_social_history='$relevant_social_and_family_history',Clinic_consultation_date_time=NOW(),Consultation_Date_And_Time=NOW() WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID = '$Registration_ID'") or die($this->db_connect->error." : Failed to save physiotherapy for the patient.");

            if($save_notes){
                $Get_Last_Consultation = $this->db_connect->query("SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID = $Registration_ID ORDER BY consultation_ID DESC LIMIT 1 ");
                $Patient_Last_Consultation = $Get_Last_Consultation->fetch_assoc()['consultation_ID'];
                $save_notes = $this->db_connect->query("UPDATE tbl_consultation_history SET maincomplain = '$main_complain',Employee_ID = '$Employee_ID',history_present_illness = '$history_of_past_illness',general_observation='$general_observation',Comments_For_Procedure='$procedure_comments',remarks='$evolution',Saved ='yes',past_medical_history='$past_medical_history',local_examination='$local_examination',family_social_history='$relevant_social_and_family_history',cons_hist_Date=NOW() WHERE  consultation_ID = '$Patient_Last_Consultation'") or die($this->db_connect->error." : Failed to save physiotherapy for the patient");

                if($save_notes){
                    $this->db_connect->query("UPDATE tbl_patient_payment_item_list SET Status = 'Served' WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID");
                }
            }
            return ($save_notes) ? 100 : 200;
        }

        public function fetchPatientInformationByRegistrationNumber($Registration_ID){
            $Patient_Information = $this->db_connect->query("SELECT * FROM tbl_patient_registration WHERE Registration_ID = $Registration_ID ") or die($this->db_connect->errno." : Failed to fetch patient information");
            return $this->queryExecute($Patient_Information);
        }

        public function createInpatientNotes($Admission_ID,$Consultation_ID,$Registration_ID,$Employee_ID,$main_complain,$Findings,$neurovascula,$functional,$remarks){
            $create_notes = $this->db_connect->query("INSERT INTO tbl_ward_round (Employee_ID,Registration_ID,consultation_ID,Findings,remarks,clinical_history,Ward_Round_Date_And_Time,Process_Status) VALUES ('$Employee_ID','$Registration_ID','$Consultation_ID','$Findings','$remarks','$main_complain',NOW(),'served')") or die($this->db_connect->errno.": Failed to save inpatient notes");
            return ($create_notes) ? 100 : 200;
        }

        public function fetchBalanceForItem($Sub_Department_ID,$Item_ID){
            $Get_Item_Balance = $this->db_connect->query("SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = $Sub_Department_ID AND Item_ID = $Item_ID ") or die($this->db_connect->errno."Error while fetch item balance");
            return $Get_Item_Balance->fetch_assoc()['Item_Balance'];
        }

        /**
         * @object mixed
         *  Consultation_ID,Employee_ID,Registration_ID,Sponsor_ID,Patient_Type('Inpatient' or 'Outpatient'),Quantity,Check_In_Type,Item_ID,Sub_Department_ID,Doctor_Comment,Procedure_Location
         */
        public function addItemFormInpatient($Patient_Transaction_Details){
            $Consultation_ID = $Patient_Transaction_Details['Consultation_ID'];
            $Employee_ID = $Patient_Transaction_Details['Employee_ID'];
            $Registration_ID = $Patient_Transaction_Details['Registration_ID'];
            $Sponsor_ID = $Patient_Transaction_Details['Sponsor_ID'];
            $Patient_Type = $Patient_Transaction_Details['Patient_Type'];
            $Quantity = $Patient_Transaction_Details['Quantity'];
            $Check_In_Type = $Patient_Transaction_Details['Check_In_Type'];
            $Item_ID = $Patient_Transaction_Details['Item_ID'];
            $Sub_Department_ID = $Patient_Transaction_Details['Sub_Department_ID'];
            $Doctor_Comment = isset($Patient_Transaction_Details['Doctor_Comment']) ? $Patient_Transaction_Details['Doctor_Comment'] : "" ;
            $Procedure_Location = isset($Patient_Transaction_Details['Procedure_Location']) ? $Patient_Transaction_Details['Procedure_Location'] : "" ; 

            $Get_Sponsor_Payment_Type = $this->db_connect->query("SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = $Sponsor_ID") or die($this->db_connect->errno."");
            $Payment_Method = ucfirst($Get_Sponsor_Payment_Type->fetch_assoc()['payment_method']);

            $Check_If_Available_Payment_Cache_Available_For_Today = $this->db_connect->query("SELECT Payment_Cache_ID FROM `tbl_payment_cache` WHERE date(`Payment_Date_And_Time`) = current_date AND consultation_id = $Consultation_ID");

            if($Check_If_Available_Payment_Cache_Available_For_Today->num_rows == 0){
                $Select_Last_Payment_Details = $this->db_connect->query("SELECT * FROM `tbl_patient_payments` WHERE `Registration_ID` = $Registration_ID ORDER BY `Patient_Payment_ID` DESC LIMIT 1 ") or die($this->db_connect->errno.": Fail read last patient details");
                $Folio_Number = $Select_Last_Payment_Details->fetch_assoc()['Folio_Number'];
                $billing_type = $Patient_Type." ".$Payment_Method;

                $Create_New_Payment_Cache = $this->db_connect->query("INSERT INTO tbl_payment_cache (Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Sponsor_ID,Billing_Type,Receipt_Date,Folio_Number,Transaction_type,Order_Type,branch_id) VALUES ('$Registration_ID','$Employee_ID',$Consultation_ID,NOW(),'$Sponsor_ID','$billing_type',NOW(),'$Folio_Number','indirect cash','normal',1)") or die($this->db_connect->errno.": Fail to create payment for today");
                if(!$Create_New_Payment_Cache){ die(": Fail to create new payment"); }
            }

            $Get_Last_Payment_Cache_ID = $this->db_connect->query("SELECT Payment_Cache_ID FROM `tbl_payment_cache` WHERE date(`Payment_Date_And_Time`) = current_date AND consultation_id = $Consultation_ID");
            $Payment_Cache_ID = $Get_Last_Payment_Cache_ID->fetch_assoc()['Payment_Cache_ID'];

            $Get_Item_Price = $this->db_connect->query("SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID = $Sponsor_ID AND Item_ID = $Item_ID") or die($this->db_connect->errno.": Fail read item price");
            $Item_Price = $Get_Item_Price->fetch_assoc()['Items_Price'];
            
            $Insert_Item_In_Payment_Cache = $this->db_connect->query("INSERT INTO tbl_item_list_cache (Check_In_Type,Category,Item_ID,Price,Quantity,Patient_Direction,Status,Employee_Created,Payment_Cache_ID,Transaction_Date_And_Time,Process_Status,Doctor_Comment,Sub_Department_ID,Transaction_Type,payment_type,Procedure_Location,Consultant_ID,dose) VALUES ('$Check_In_Type','indirect cash','$Item_ID','$Item_Price','$Quantity','other','active','$Employee_ID','$Payment_Cache_ID',NOW(),'inactive','$Doctor_Comment','$Sub_Department_ID','$Payment_Method','$Payment_Method','$Procedure_Location','$Employee_ID','$Quantity')") or die($this->db_connect->error.": Failed create item for the patient");
            return (!$Insert_Item_In_Payment_Cache) ? 200 : 100;
        }


        public function autoSavePatientNotes($inputObject){
            $Consultation_ID = $inputObject['Consultation_ID'];
            $Employee_ID = $inputObject['Employee_ID'];
            $Registration_ID = $inputObject['Registration_ID'];
            $Column_Name = $inputObject['column_name'];
            $Column_Value = $inputObject['column_value'];
            $Round_ID = 0;

            $get_last_null_round_for_employee = $this->db_connect->query("SELECT Round_ID FROM tbl_ward_round WHERE consultation_ID = $Consultation_ID AND Employee_ID = $Employee_ID AND Process_Status IS NULL") or die($this->db_connect->errno."FAIL TO FETCH LAST WARD ROUND FOR THE PATIENT");

            if($get_last_null_round_for_employee->num_rows > 0){
                $Round_ID = $get_last_null_round_for_employee->fetch_assoc()['Round_ID'];
            }else{
                $create_new_ward_round_for_the_employee = $this->db_connect->query("INSERT INTO tbl_ward_round (Employee_ID,Registration_ID,consultation_ID) VALUES ('$Employee_ID','$Registration_ID','$Consultation_ID') ") or die($this->db_connect->errno."FAIL TO CREATE A NEW WARD ROUND FOR THE PATIENT");
                $Round_ID = ($create_new_ward_round_for_the_employee) ? $this->db_connect->insert_id : 0;
            }
            $update_notes = $this->db_connect->query("UPDATE tbl_ward_round SET $Column_Name = '$Column_Value' WHERE Round_ID = $Round_ID ") or die($this->db_connect->error."FAIL TO CREATE ROUND NOTES FOR THE PATIENT");

            return ($update_notes) ? 100 : 200;
        }
    }
?>
