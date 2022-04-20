<?php
    declare(strict_types=1);
    include 'conn.php';

    class patientDetails extends DBConfig{
       
        public function processQuery($sql,$error_msg){
           
            $result =array();
            $Query= $this->connect()->query($sql)or die($this->connect()->errno.": ".$error_msg);
            while($data = $Query->fetch_assoc()){array_push($result,$data);}
            mysqli_free_result($Query);
            return json_encode($result);

        }

        public function getAllPatientDetails($Registration_ID){
            $sql = "SELECT patient_type,Status,service_no,dependancy_id,dependecny_service_no,military_unit, Old_Registration_Number,Title,Patient_Name, Date_Of_Birth,Patient_Picture,    Gender,Religion_Name,Denomination_Name, pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,    Member_Number,Member_Card_Expire_Date,payment_method,     pr.Phone_Number,Email_Address,Occupation,   Employee_Vote_Number,Emergence_Contact_Name,      Emergence_Contact_Number,Company,Registration_ID,     Employee_ID,Registration_Date_And_Time,Guarantor_Name,     Registration_ID,sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id,    village FROM tbl_patient_registration pr, tbl_denominations de, tbl_religions re, tbl_sponsor sp WHERE de.Denomination_ID=pr.Denomination_ID  AND re.Religion_ID=de.Religion_ID AND pr.Sponsor_ID = sp.Sponsor_ID AND      Registration_ID = '$Registration_ID'";
            return $this->processQuery($sql,"Fail to fetch patient Details");
        }

        public function getAllPatientDirectCash($Registration_ID,$Patient_Bill_ID){
            
            $sql1 = "SELECT SUM(ppl.Price *ppl.Quantity) AS TotalAmount, Product_Name, ppl.Patient_Payment_ID, Payment_Date_And_Time, Price, Discount, Quantity from  tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where    pp.Transaction_type = 'Direct cash' AND     pp.Transaction_status <> 'cancelled' and    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and    pp.Patient_Bill_ID = '$Patient_Bill_ID' and    ppl.Item_ID=i.Item_ID  and  i.Visible_Status='Others' AND   pp.Registration_ID = '$Registration_ID'";
            
            return $this->processQuery($sql1,"Failed to execute data 1");
         

        }

        public function getPatientTotalBillAll($Registration_ID,$Patient_Bill_ID){
            
            $sql2 = "SELECT SUM((ppl.Price-ppl.Discount) * ppl.Quantity) AS TotalAmountRequired from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where  i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') and Check_In_Type <> 'Mortuary' AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) AND pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.Registration_ID = '$Registration_ID'";
            return $this->processQuery($sql2,"Failed to execute data 2");
          

        }

        public function getPatientAllServiceBilled($Registration_ID,$Patient_Bill_ID){
            
            $sql3 = "SELECT SUM((ppl.Price-ppl.Discount) * ppl.Quantity) AS TotalAmount, Check_In_Type from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where  i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Billing_Type IN ( 'Outpatient Cash', 'Inpatient Cash') and pp.Pre_Paid IN ('1', '0' ) AND pp.Patient_Bill_ID = '$Patient_Bill_ID' AND pp.payment_type='post' and pp.Registration_ID = '$Registration_ID' GROUP BY Check_In_Type ";
            return $this->processQuery($sql3,"Failed to execute data 3");
          

        }

        public function getPtMsamahaComments($Registration_ID, $Payment_Cache_ID){
            
            $sql4 = "SELECT HOD_ID,Managment_Approver_ID,social_hod_comments,Social_Comments,document_approver, approvalStatus,managment_comments,Employee_Name, Employee_Title,  Social_Bill_ID, type_of_approval FROM tbl_social_bill_creation sb , tbl_employee e WHERE Payment_Cache_ID='$Payment_Cache_ID' AND sb.Employee_ID=e.Employee_ID AND Registration_ID='$Registration_ID'";
            return $this->processQuery($sql4,"Failed to execute data 4");
          
        }

        public function getClinicAttandanceConsultation($filter, $Clinic_ID){
            
            $sql5 = "SELECT count(case when Gender='Male' then 1 end) as male_count,count(case when Gender='Female' then 1 end) as female_count FROM tbl_consultation c, tbl_patient_registration pr WHERE pr.Registration_ID=c.Registration_ID   AND c.Clinic_ID='$Clinic_ID'  $filter ";
            return $this->processQuery($sql5,"Failed to execute data 5");
          
        }

     

        public function fetchSponsor($Sponsor, $SponsorStatus){
            
            $filter ="";
            $filter.=(strtolower($SponsorStatus)=='all' || $SponsorStatus=='' || $SponsorStatus==NULL ) ? "" : " active_sponsor='$SponsorStatus'";
            $filter.=(strtolower($Sponsor)=='all' || $Sponsor=='' || $Sponsor == NULL) ? "" : " AND Sponsor_ID='$Sponsor'";
            if($filter ==''){
                $filter .=" 1";
            }
            $sql7 = "SELECT * FROM tbl_sponsor WHERE  $filter ";
            return $this->processQuery($sql7,"Failed to execute data 7");
          
        }

        public function fetchEmployeeAssignedClinic($Employee_ID,$Clinic_ID,array $templates_status, $ClinicStatus){
            
            $filter = "";
            $clinic_template = "";
            if(sizeof($templates_status) > 0){
                $template = "";
                foreach($templates_status as $templates){
                    $clinic_template .= "'".$templates."',";
                }
                $template = rtrim($clinic_template,",");
                $filter .= " AND template IN ($template) ";
            }
            $filter.= (strtolower($Clinic_ID)=='all' || $Clinic_ID==NULL) ? "" : " AND ce.Clinic_ID='$Clinic_ID'";
            $filter.=(strtolower($ClinicStatus)=='all') ? "" : " AND Clinic_Status='$ClinicStatus'";
            
            $sql8 = "SELECT * FROM tbl_clinic c, tbl_clinic_employee ce,tbl_employee e WHERE c.clinic_id = ce.clinic_id and ce.employee_id = e.employee_id and e.employee_id = '$Employee_ID' $filter ";
            return $this->processQuery($sql8,"Failed to execute data 8");
          
           
        }

        public function OutPatient_CLinic_List($startdate, $enddate, $Sponsor_ID, $Clinic_ID, $Nature_Of_List){
            
            $filter='';
            $filter.= (strtolower($Clinic_ID)=='all' || $Clinic_ID==NULL) ? "" : " AND Clinic_ID='$Clinic_ID'";
            $filter.= (strtolower($Sponsor_ID)=='all' || $Sponsor_ID==NULL) ? "" : " AND pp.Sponsor_ID='$Sponsor_ID'";
            $filter.=($startdate =='' || $startdate ==NULL) ? "" : "  AND ppl.Transaction_Date_And_Time BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
            if($Nature_Of_List=='opd_pt_list'){
                $filter.=" AND ppl.Process_Status='not served' "; 
            }else if($Nature_Of_List=='consulted_list'){
                $filter.=" AND ppl.Process_Status='served' "; 
            }else if($Nature_Of_List=='my_pt_list'){
                $filter.=" AND ppl.Consultant_ID=''";
            }else if($Nature_Of_List=='from_nurse_station'){
                $filter.=" AND Nursing_Status='served'";
            }
            $sql9 ="SELECT pp.Patient_Payment_ID,pp.Registration_ID, Patient_Direction,Transaction_Date_And_Time,Patient_Payment_Item_List_ID, pp.Check_In_ID  FROM  tbl_patient_payments pp, tbl_patient_payment_item_list ppl where  Check_In_Type='Doctor Room' AND  pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID  $filter  ORDER BY  ppl.Transaction_Date_And_Time ASC LIMIT 30";
            return $this->processQuery($sql9,"Failed to execute data 9");
          
            
        }

        public function FetchAdmissionCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage , $admisiontime, $agetype,$admisionType, $reporttype ,$valuesType){
            
            $filter='';
            $values='';
            $filter.= (strtolower($Hospital_Ward_ID)=='all' || $Hospital_Ward_ID==NULL) ? "" : " AND Hospital_Ward_ID='$Hospital_Ward_ID'";
            

            $filter.= (strtolower($Sponsor_ID)=='all' || $Sponsor_ID==NULL || $Sponsor_ID=='' || $Sponsor_ID==0) ? "" : " AND pr.Sponsor_ID='$Sponsor_ID'";
            $filter.= (strtolower($admisionType)=='all' || $admisionType==NULL || $admisionType=='' ) ? "" : " AND ad.New_return_admission='$admisionType'";
            
            if($admisiontime =='withinObd' && $reporttype=='obd'){
                $filter.=" AND DATE(ad.Admission_Date_Time) = DATE('$startdate')";
            }else if($admisiontime =='within' && $reporttype=='within'){
                $filter.="  AND ad.Admission_Date_Time BETWEEN '$startdate' and '$enddate' ";
            }else if($admisiontime =='BeforeObd' && $reporttype=='obd'){
                $filter.=" AND ad.Admission_Status='Admitted'  AND DATE(Admission_Date_Time) < DATE('$startdate') ";
            }if($admisiontime =='obd' && $reporttype=='obd'){
                $filter.=" AND ad.Admission_Status='Admitted'  AND DATE(Admission_Date_Time) <= DATE('$enddate') ";
            }
           
            $values.= ($valuesType=='' || $valuesType==NULL) ? " count(case when Gender='Male' then 1 end) as male_count,count(case when Gender='Female' then 1 end) as female_count " : " pr.Registration_ID,Admision_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,ad.Discharge_Date_Time ";
       
            $filter.=($startage=='' || $startage==NULL ) ? "" : " AND TIMESTAMPDIFF($agetype,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$startage."' AND '".$endage."'";

            $sql10 ="SELECT $values FROM tbl_admission  ad, tbl_patient_registration  pr WHERE  ad.Registration_ID=pr.Registration_ID  AND ward_room_id <> ''    $filter ";
            return $this->processQuery($sql10,"Failed to execute data 10");
        }

        #===== ALL TRansfer counted here ======
        public function FetchTransferCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage , $admisiontime, $agetype, $admisionType,  $transferType, $valuesType){
            $filter='';
            $values='';
            if(strtolower($transferType)=='out' && $admisiontime=='within'){
                $filter.= " AND  transfer_status='received' AND out_ward_id='$Hospital_Ward_ID'  AND transfer_out_date BETWEEN '$startdate' and '$enddate'";
            } else if(strtolower($transferType)=='out' && $admisiontime=='before'){
                $filter.=" AND  transfer_status='received' AND ad.Admission_Status='Admitted'  AND transfer_out_date < '" . $startdate . "'";
            }else if(strtolower($transferType)=='in' && $admisiontime=='within'){
                $filter.=" AND  transfer_status='received' AND in_ward_id='$Hospital_Ward_ID'  AND transfer_in_date BETWEEN '$startdate' and '$enddate'";
            }else if(strtolower($transferType)=='in' && $admisiontime=='before'){
                $filter.=" AND  transfer_status='received' AND in_ward_id='$Hospital_Ward_ID' AND ad.Admission_Status='Admitted'  AND transfer_in_date < '$startdate";
            }else if(strtolower($transferType)=='pending' && $admisiontime=='within'){
                $filter.= " AND  transfer_status='pending' AND out_ward_id='$Hospital_Ward_ID'  AND transfer_out_date BETWEEN '$startdate' and '$enddate'";
            }else if(strtolower($transferType)=='in' && $admisiontime=='withinObd'){
                $filter.=" AND  transfer_status='received' AND in_ward_id='$Hospital_Ward_ID' AND ad.Admission_Status='Admitted'  AND DATE(transfer_in_date) = '$startdate'";
            }else if(strtolower($transferType)=='out' && $admisiontime=='withinObd'){
                $filter.= " AND  transfer_status='received' AND out_ward_id='$Hospital_Ward_ID'  AND DATE(transfer_out_date) = '$startdate'";
            }
            $values.= ($valuesType=='' || $valuesType==NULL) ? " count(case when Gender='Male' then 1 end) as male_count,count(case when Gender='Female' then 1 end) as female_count " : " toi.in_ward_id,pr.Registration_ID,Admision_ID, transfer_out_date, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,toi.transfer_in_date,ad.Discharge_Date_Time,toi.in_ward_id,toi.out_ward_id ";
            $filter.= (strtolower($Sponsor_ID)=='all' || $Sponsor_ID==NULL || $Sponsor_ID==0) ? "" : " AND pr.Sponsor_ID='$Sponsor_ID'";
            $filter.= (strtolower($admisionType)=='all' || $admisionType==NULL ) ? "" : " AND ad.New_return_admission='$admisionType'";
            
            $filter.=($startage=='' || $startage==NULL ) ? "" : " AND TIMESTAMPDIFF($agetype,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$startage."' AND '".$endage."'";
            $sql11 ="SELECT $values FROM tbl_transfer_out_in toi , tbl_admission ad, tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID  AND toi.Admision_ID=ad.Admision_ID  $filter ";
           
           return $this->processQuery($sql11,"Failed to execute data 11");
           
        }

        #======All Discharge counted here ===========
        public function FetchDischargeCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage , $admisiontime, $agetype, $admisionType, $dischargeType, $valuesType){
            $filter='';
            $values='';
            $filter.= (strtolower($Hospital_Ward_ID)=='all' || $Hospital_Ward_ID==NULL) ? "" : " AND Hospital_Ward_ID='$Hospital_Ward_ID'";
            $filter.= (strtolower($Sponsor_ID)=='all' || $Sponsor_ID==NULL || $Sponsor_ID==0) ? "" : " AND pr.Sponsor_ID='$Sponsor_ID'";
            $filter.= (strtolower($admisionType)=='all' || $admisionType==NULL ) ? "" : " AND ad.New_return_admission='$admisionType'";
            
            $filter.=($admisiontime =='within') ? " AND ad.pending_set_time BETWEEN '$startdate' and '$enddate'" : "   AND pending_set_time < '" . $startdate . "'";
            $filter.=($startage=='' || $startage==NULL ) ? "" : " AND TIMESTAMPDIFF($agetype,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$startage."' AND '".$endage."'";
            $filter.=($dischargeType=='' || $dischargeType==NULL ) ? "" : " $dischargeType";
            $values.= ($valuesType=='' || $valuesType==NULL) ? "count(case when Gender='Male' then 1 end) as male_count,count(case when Gender='Female' then 1 end) as female_count " : " pr.Registration_ID,Admision_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,ad.Discharge_Date_Time, pending_set_time ";

            $sql12 ="SELECT $values FROM tbl_discharge_reason dr , tbl_admission ad, tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID AND   ad.Discharge_Reason_ID=dr.Discharge_Reason_ID   $filter ";
            return $this->processQuery($sql12,"Failed to execute data 12");
            
        }

        public function fetchPatientCurrentAge($patient_date_of_birth){

            $query_date_time = mysqli_query($this->connect(), "SELECT now() as Date_Time") or die($this->db_connect->errno."Failed to fetch current patient age");
            $date_time = $query_date_time->fetch_assoc()['Date_Time'];

            $date1 = new DateTime($date_time);
            $date2 = new DateTime($patient_date_of_birth);
            $diff = $date1->diff($date2);
            return $diff->y . " Years, ".$diff->m . " Months, ".$diff->d . " Days";
        }

        public function fetchHospitalaWard($Hospital_Ward_ID, $wardType, $ward_status ){
            $filter='';
            $filter.= (strtolower($ward_status)=='all' || $ward_status==NULL) ? "" : " WHERE ward_status='$ward_status'";
            $filter.= (strtolower($wardType)=='all' || $wardType==NULL) ? "" : " AND ward_type ='$wardType'";
            $filter.= (strtolower($Hospital_Ward_ID)=='all' || $Hospital_Ward_ID==NULL) ? "" : " AND Hospital_Ward_ID='$Hospital_Ward_ID'";            
            
            $sql13 ="SELECT DISTINCT Hospital_Ward_Name, Hospital_Ward_ID FROM tbl_hospital_ward $filter ";
            return $this->processQuery($sql13,"Failed to execute data 13");
        }
    
        public function fetchRoundDisease($consultation_ID, $patientstatus){
            if(strtolower($patientstatus)=='inpatient'){
                $sql14 ="SELECT disease_name, diagnosis_type, wrd.disease_ID, wr.Employee_ID, e.Employee_Name as Consultant_Name, disease_code FROM tbl_ward_round_disease wrd,  tbl_ward_round wr, tbl_disease d, tbl_employee e WHERE  d.disease_ID=wrd.disease_ID AND wr.Round_ID=wrd.Round_ID AND wr.employee_ID = e.Employee_ID  AND consultation_ID='$consultation_ID'  ";
                //diagnosis_type IN ('diagnosis', 'provisional_diagnosis') AND
            }else{
                $sql14="SELECT  c.consultation_ID, d.disease_code, dc.Employee_ID, dc.diagnosis_type, dc.disease_ID, dc.Disease_Consultation_Date_And_Time, e.Employee_Name as Consultant_Name FROM  tbl_consultation c, tbl_disease_consultation dc, tbl_employee e, tbl_disease d WHERE  c.consultation_ID = dc.consultation_ID AND dc.employee_ID = e.Employee_ID  AND d.disease_ID = dc.disease_ID AND dc.consultation_ID = '$consultation_ID'  GROUP BY disease_code , diagnosis_type";
            }
            return $this->processQuery($sql14,"Failed to fetch diagnosis 14");
        }

        public function fetchWardRoom($Hospital_Ward_ID){
            $filter='';
            $filter.= (strtolower($Hospital_Ward_ID)=='all' || $Hospital_Ward_ID==NULL) ? "" : " AND Hospital_Ward_ID='$Hospital_Ward_ID'";            
            $sql15 ="SELECT sum(no_of_beds) as total FROM tbl_ward_rooms   WHERE ward_id='$Hospital_Ward_ID' ";
            return $this->processQuery($sql15,"Failed to fetch diagnosis 15");
        }
       
        public function  fetchPatientAdmWard($Registration_ID, $Admision_ID){
            $sql16 ="SELECT Hospital_Ward_Name,Kin_Name,Discharge_Date_Time,  Admission_Date_Time  FROM tbl_hospital_ward hw, tbl_admission a WHERE a.Hospital_Ward_ID=hw.Hospital_Ward_ID AND a.Registration_ID='$Registration_ID' AND Admision_ID='$Admision_ID'";
            return $this->processQuery($sql16,"Failed to fetch data 16");
        }
        public function fetchBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $Item_category_ID){

            
            $sql17="SELECT i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount,Billing_Type, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time,Transaction_Date_And_Time, ppl.Hospital_Ward_ID, pp.Sponsor_ID , Consultant from 	tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID and 	i.Item_ID = ppl.Item_ID and	pp.Transaction_type = 'indirect cash'   and 	pp.Transaction_status <> 'cancelled' and 	pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	pp.Patient_Bill_ID = '$Patient_Bill_ID' and 	ic.Item_category_ID = '$Item_category_ID' and 	pp.Registration_ID = '$Registration_ID' $Billing_Type";
            
            return $this->processQuery($sql17,"Failed to fetch data 17");
        }

        public function fetchRowExemption($Registration_ID, $Patient_Bill_ID){
            $sql18="SELECT tef.Nurse_Exemption_ID,amountsuggested, tef.created_at,maoniDHS, kiasikinachoombewamshamaha,Anaombewa,maoniDHS, tef.Exemption_ID FROM tbl_temporary_exemption_form tef, tbl_nurse_exemption_form nef, tbl_exemption_maoni_dhs emd  WHERE nef.Nurse_Exemption_ID=tef.Nurse_Exemption_ID AND tef.Registration_ID='$Registration_ID'   AND exemptionstatus<>'Cancelled' AND Patient_Bill_ID='$Patient_Bill_ID' AND tef.Exemption_ID=emd.Exemption_ID";            
            
            return $this->processQuery($sql18,"Failed to fetch data 18");
        }
       

        

        public function fetchConsultationAdmin($Check_In_ID){
            $sql19="SELECT Admission_ID, ToBe_Admitted, consultation_ID FROM `tbl_check_in_details` WHERE  Check_In_ID='$Check_In_ID'";
            return $this->processQuery($sql19,"Failed to fetch data 19");
        }
         public function  fetchBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type){
            
            $filter='';
            $filter.=(strtolower($Billing_Type)=='all' || $Billing_Type==NULL) ? "" : " AND Billing_Type='$Billing_Type'";
            $sql20 ="SELECT ic.Item_category_ID, ic.Item_Category_Name from 	tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 	ic.Item_Category_ID = isc.Item_Category_ID and 	isc.Item_Subcategory_ID = i.Item_Subcategory_ID and 	i.Item_ID = ppl.Item_ID and 	pp.Transaction_type = 'indirect cash' and 	pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND  pp.Pre_Paid IN ('1' , '0')  and 	pp.Patient_Bill_ID = '$Patient_Bill_ID' and 	pp.Transaction_status <> 'cancelled' and 	pp.Registration_ID = '$Registration_ID' $Billing_Type group by ic.Item_Category_ID";    
                // die($sql20);
            return $this->processQuery($sql20,"Failed to fetch data 20");
        }

        public function fetchNurseRequestExcemption($Registration_ID, $Patient_Bill_ID){
            $sql21 ="SELECT nef.Registration_ID, Employee_Name,Employee_Title, employee_signature, Nurse_Exemption_ID, nef.Employee_ID, nef.created_at,Jina_la_balozi,Nurse_Exemption_ID,Jina_la_balozi,simu_ya_balozi, maelezo_ya_nurse_mratibu, Check_In_ID FROM  tbl_employee e, tbl_nurse_exemption_form nef WHERE Registration_ID='$Registration_ID'  AND Patient_Bill_ID='$Patient_Bill_ID' AND nef.Employee_ID=e.Employee_ID";            
            return $this->processQuery($sql21,"Failed to fetch data 21");
        }

        public function fetchAdmisionDetails($Check_In_ID, $patientStatus){
            $filter='';
            if($patientStatus=='cld'){
                $date='pending_set_time';
            }else{
                $date =date('Y-m-d H:i:s');
            }
            $sql22="SELECT TIMESTAMPDIFF(DAY,Admission_Date_Time,'$date') AS NoOfDay, emp.Employee_Name,Discharge_Clearance_Status, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status,Discharge_Supervisor_ID, Discharge_Date_Time,Admission_Date_Time,Admission_Status,Discharge_Reason_ID,Kin_Name,Kin_Phone,Kin_Relationship, ad.Admision_ID, Cash_Clearer_ID, Credit_Clearer_ID, pending_set_time, pending_setter FROM tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp WHERE cd.Admission_ID = ad.Admision_ID and 	ad.Hospital_Ward_ID = hp.Hospital_Ward_ID  and 	emp.Employee_ID= ad.Admission_Employee_ID  and 	cd.Check_In_ID = '$Check_In_ID'";
           
            return $this->processQuery($sql22,"Failed to fetch data 22");
        }

        public function fetchDataByReceipt($Registration_ID, $Patient_Bill_ID, $Check_In_ID, $Billing_Type){
            $sql23 ="SELECT pp.Patient_Bill_ID, pp.Sponsor_ID,pp.Billing_Type, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where  	Registration_ID = '$Registration_ID' and 	pp.Transaction_status <> 'cancelled' and 	pp.Transaction_type = 'indirect cash' and pp.payment_type = 'post'   and  	pp.Patient_Bill_ID = '$Patient_Bill_ID' $Billing_Type order by pp.Patient_Payment_ID DESC";

            // and pp.Check_In_ID='$Check_In_ID'
            return $this->processQuery($sql23,"Failed to fetch data 23");
        }

        public function fetchItemByReceipt($Patient_Payment_ID){
            $sql24 ="SELECT ppl.Hospital_Ward_ID,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time, ppl.Price, ppl.Quantity, ppl.Discount,ppl.Patient_Payment_Item_List_ID FROM  	tbl_items i, tbl_patient_payment_item_list ppl where 	i.Item_ID = ppl.Item_ID and 	ppl.Patient_Payment_ID = '$Patient_Payment_ID'";
            return $this->processQuery($sql24,"Failed to fetch data 24");
        }

        public function fetchPatientTotalBill($Registration_ID,$Patient_Bill_ID, $Billing_Type){            
            $sql25 = "SELECT SUM((ppl.Price-ppl.Discount) * ppl.Quantity) AS TotalAmountRequired from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where  i.Item_ID = ppl.Item_ID and pp.Transaction_type = 'indirect cash' and pp.Transaction_status <> 'cancelled' and pp.Patient_Payment_ID = ppl.Patient_Payment_ID $Billing_Type and Check_In_Type <> 'Mortuary' AND pp.payment_type='post' and pp.Pre_Paid IN ('1', '0' ) AND pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.Registration_ID = '$Registration_ID'";
            return $this->processQuery($sql25,"Failed to execute data 25");
        }

        public function fetchEmployeeDetails($Employee_ID){
            $sql26 = "SELECT * FROM tbl_employee WHERE Employee_ID='$Employee_ID'";
            return $this->processQuery($sql26,"Failed to execute data 26");
        }

        public function fetchExemptionCreteria($seachValue, $msamahaitemId){
            $filter='';
            $filter.=empty($msamahaitemId) ? "" :" WHERE msamaha_Items='$msamahaitemId'";
            $filter.=empty($seachValue) ? " " : " WHERE msamaha_aina LIKE '%$seachValue%'";
            $sql27 = "SELECT * from tbl_msamaha_items $filter";
            return $this->processQuery($sql27,"Failed to execute data 27");
        }
    }
?>
