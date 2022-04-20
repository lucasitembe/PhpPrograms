<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
require_once("nhif3/constants.php");
include_once("./includes/connection.php");
include_once './includes/constants.php';
include_once './includes/billing.function.inc.php';
include_once("./UUID.php");

//die(json_encode(array('Message'=>'System is under maintenance, Dont Worry Proceed, Claim will be sent Latter')));
/////new lines
    header("Content-Type:application/json");
    require_once('nhif3/ServiceManager.php');
    $manager=new ServiceManager();
    $result="";
       /////////
$temp = 1;
// $GrandTotal = 0;
// $total = 0;
// $sub_Total = 0;
$patient_number = 1;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//arrays definitions
$sampleItems = array();
$sampleDiseases = array();
//$sampleItems=array();
//get all details from the URL
//Branch
/*
  if(isset($_GET['Bill_ID'])){
  $Bill_ID = $_GET['Bill_ID'];

  }else{
  $Bill_ID = '';
  }
 */

if(isset($_GET['Location'])){
  $Location = $_GET['Location'];

  }else{
  $Location = '';
  }
//get employee ID
$FolioID = gen_uuid();
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}
if (isset($_GET['Bill_ID'])) {
    $Bill_ID = $_GET['Bill_ID'];
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}
/*if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
}*/

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
}

if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
}


    //GET HOSPITL NAME AND FACILITY CODE....
    $settings = "SELECT Hospital_Name,facility_code FROM tbl_system_configuration";
    $settings_result = mysqli_query($conn,$settings) or die(mysqli_error($conn));
    while ($settings_row = mysqli_fetch_assoc($settings_result)) {
        $hosp_name = $settings_row['Hospital_Name'];
        $FacilityCode = $settings_row['facility_code'];
    }

    /*check for consultation notes */

    function isConsulted($Bill_ID, $conn){
        
       
        $New_Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID FROM `tbl_patient_payments` WHERE Bill_ID = '$Bill_ID'"))['Check_In_ID'];
       
        $select111 = mysqli_query($conn,"SELECT cd.Admission_ID, cd.consultation_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Check_In_ID = '$New_Check_In_ID'");
        if(mysqli_num_rows($select111) > 0){
            $patient_type ='Inpatient';            
            $consultation_ID = mysqli_fetch_assoc($select111)['consultation_ID'];
        }else{
            $patient_type ='Outpatient';
           
        }

        if($patient_type =='Inpatient'){
            $results = mysqli_query($conn, "SELECT wd.Disease_ID,   diagnosis_type,disease_name,Round_Disease_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d    WHERE   wd.disease_ID = d.disease_ID AND    wr.Round_ID = wd.Round_ID AND    wr.consultation_ID ='$consultation_ID' GROUP BY Disease_ID");
            
        }else{
            $results = mysqli_query($conn,"SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID,Employee_Name, c.consultation_ID,ch.employee_ID, ch.cons_hist_Date,consultation_histry_ID,ch.course_of_injuries, c.Registration_ID FROM tbl_consultation c,tbl_consultation_history ch, tbl_patient_payment_item_list ppl,tbl_employee e, tbl_patient_payments pp WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and c.consultation_ID=ch.consultation_ID and e.Employee_ID=ch.employee_ID and pp.Check_In_ID = '$New_Check_In_ID' and pp.Patient_Payment_ID=ppl.Patient_Payment_ID ");
        }

        if(mysqli_num_rows($results) > 0){
            return true;
        }else{         
            $alternative = mysqli_query($conn,"SELECT e.Employee_Name, ch.consultation_ID, e.Employee_ID, ch.cons_hist_Date,ch.consultation_histry_ID,ch.course_of_injuries, cn.Registration_ID FROM tbl_check_in cn, tbl_check_in_details cd, tbl_consultation_history ch, tbl_employee e WHERE e.Employee_ID = ch.employee_ID AND ch.consultation_ID = cd.consultation_ID AND cn.Check_In_ID = cd.Check_In_ID AND cn.Check_In_ID = '$New_Check_In_ID'");

            if(mysqli_num_rows($alternative) > 0){
                return true;
            }

        }
        return false;
    }
    /* create unique folio numnber */


 function Generate_Unique_Folio($Registration_ID = null, $Bill_ID = null, $claim_date = null, $conn = null){
	    /* check if a patient file has case notes  */
        if(!isConsulted($Bill_ID,$conn)){
            die(json_encode(array('Message' => 'This Claim Has No Doctor Case Notes')));
        }
        $last_folio ="";
        /*	check if an inpatient has been fully discharged */
        if($claim_date == null){
            die(json_encode(array('Message' => 'This Patient Has been not Fully Discharged')));
        }

        $claim_month = intval(date('m',strtotime($claim_date)));
        $claim_year = date('Y',strtotime($claim_date));

	 	//test if invoice is already created
        $dateObj   = DateTime::createFromFormat('!m',  $claim_month);
        $monthName = $dateObj->format('F');
        $select_invoice = mysqli_query($conn,"SELECT Invoice_ID FROM tbl_invoice WHERE invoice_month = '$monthName' AND invoice_year = '$claim_year'");
        if(mysqli_num_rows($select_invoice) > 0){
            die(json_encode(array('Message' => 'Invoice Is Already Created for This Month')));
        }

        $check_folio = mysqli_query($conn,"SELECT MONTH(sent_date) AS sent_date, Folio_No FROM tbl_claim_folio WHERE  Bill_ID = $Bill_ID");

        if(mysqli_num_rows($check_folio) > 0){
            $last_folio = mysqli_fetch_assoc($check_folio)['Folio_No'];
        }else{

            $select_last_folio = mysqli_query($conn,"SELECT MAX(Folio_No) AS Folio_No FROM tbl_claim_folio WHERE claim_month = '$claim_month' AND claim_year = '$claim_year'");

            if(mysqli_num_rows($select_last_folio) > 0){
            $folio_details = mysqli_fetch_assoc($select_last_folio);

                $last_folio = $folio_details['Folio_No']+1;

            }else{
            $last_folio = 1;
            }

            mysqli_query($conn,"INSERT INTO tbl_claim_folio (Registration_ID, Bill_ID, sent_date, Folio_No, claim_month, claim_year) VALUES($Registration_ID,$Bill_ID,(SELECT NOW()),'$last_folio',$claim_month,$claim_year)");
        }

	return $last_folio;
}

$bill_qr = "SELECT * FROM tbl_patient_payments pp,tbl_sponsor s WHERE Bill_ID ='$Bill_ID' AND s.Sponsor_ID=pp.Sponsor_ID GROUP BY Folio_Number ";
$billcount = mysqli_num_rows($bill_result = mysqli_query($conn,$bill_qr)) or die(mysqli_error($conn));

static $countprice = 0;
while ($bill_row = mysqli_fetch_assoc($bill_result)) {

    $Bill_Date_And_Time = $bill_row['Payment_Date_And_Time'];
    $Date_From = $bill_row['Payment_Date_And_Time'];
    $Date_To = $bill_row['Payment_Date_And_Time'];
    $Guarantor_Name = $bill_row['Guarantor_Name'];
    $selecteach = mysqli_query($conn,"select sum((price - discount)*quantity) as Bill_Amount,pp.Check_In_ID from   tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and    pp.Bill_ID = '$Bill_ID' and ppl.Status<>'removed'") or die(mysqli_error($conn));

    $eachperson = mysqli_fetch_assoc($selecteach);

    $countprice = $eachperson['Bill_Amount'];
    $Check_In_ID= $eachperson['Check_In_ID'];
}

//Insurance
if (isset($_GET['Insurance'])) {
    $Insurance = $_GET['Insurance'];
} else {
    $Insurance = '';
}

//Payment_Type
if (isset($_GET['Payment_Type'])) {
    $Payment_Type = $_GET['Payment_Type'];
} else {
    $Payment_Type = '';
}

//Patient_Type
if (isset($_GET['Patient_Type'])) {
    $Patient_Type = $_GET['Patient_Type'];
} else {
    $Patient_Type = '';
}

$folio_data = "";
$folio_items = "";
$folio_disease = "";
$New_Check_In = "";
$Sponsor_Name = $Guarantor_Name;
//claim registratio array


$select_Patient_Details = mysqli_query($conn,"SELECT pr.Patient_Name,e.Employee_Name,pr.Date_Of_Birth, pr.Phone_Number,pr.Member_Number,pr.Gender,CAST(pp.Payment_Date_And_Time AS DATE) as  Payment_Date_And_Time,pp.Claim_Form_Number,pp.Patient_Payment_ID,pp.Check_In_ID, pp.Registration_ID,pp.Billing_Type,pp.Sponsor_Name, pp.Folio_Number from    tbl_patient_registration pr, tbl_patient_payments pp,tbl_employee e 	where pr.registration_id = pp.registration_id and     e.employee_id = pp.employee_id and Bill_ID = '$Bill_ID' GROUP 	BY pp.Check_In_ID    ") or die(mysqli_error($conn));

//display all items
while ($row2 = mysqli_fetch_array($select_Patient_Details)) {
    $Patient_Payment_ID = $row2['Patient_Payment_ID'];
    $Registration_ID = $row2['Registration_ID'];
    $Today = Date("Y-m-d");
    $Date_Of_Birth = $row2['Date_Of_Birth'];
    $Admission_Date_Time = null;
    $Discharge_Date_Time = null;
	$New_Check_In = $row2['Check_In_ID'] ;

	$Attendance_Date = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'"))['Visit_Date'];

    $select111 = mysqli_query($conn,"SELECT cd.Admission_ID, DATE(ad.Admission_Date_Time) AS Admission_Date_Time, DATE(Discharge_Date_Time) AS Discharge_Date_Time FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
    if(mysqli_num_rows($select111) > 0){

		//DELETE FREE ROUNDS
		$round_results = mysqli_query($conn, "SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE i.Item_ID = ppl.Item_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND i.Product_Name = 'Free Round' AND pp.Check_In_ID = '$Check_In_ID'");
		while($round_row = mysqli_fetch_assoc($round_results)){
			$del_round_id = $round_row['Patient_Payment_Item_List_ID'];
			mysqli_query($conn, "DELETE FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID = '$del_round_id'");
		}
			   	

		$admission_results = mysqli_fetch_assoc($select111);
		$typecode ='IN';
		$Admission_Date_Time = $admission_results['Admission_Date_Time'];
		$Discharge_Date_Time = $admission_results['Discharge_Date_Time'];

		$Bill_Date_And_Time = $Discharge_Date_Time;
		if(date('Y-m-d', strtotime($Admission_Date_Time)) ==  date('Y-m-d', strtotime($Discharge_Date_Time))){
		$typecode ='OUT';
    $Admission_Date_Time = null;
		$Discharge_Date_Time = null;

    	$last_folio = Generate_Unique_Folio($Registration_ID, $Bill_ID, $row2['Payment_Date_And_Time'], $conn);
	}else{
    	$last_folio = Generate_Unique_Folio($Registration_ID, $Bill_ID, $Discharge_Date_Time, $conn);
	}

	}else{
		$typecode ='OUT';
    	$last_folio = Generate_Unique_Folio($Registration_ID, $Bill_ID, $row2['Payment_Date_And_Time'], $conn);
	}
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y;
    //$FolioID = gen_uuid();
    $Folio_Number = $row2['Folio_Number'];

    $AuthorizationNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT AuthorizationNo FROM tbl_check_in WHERE Check_In_ID ='" . $row2['Check_In_ID'] . "'"))['AuthorizationNo'];
	$patient_fullname  = trim($row2['Patient_Name']);
    if (sizeof(explode(' ', $patient_fullname)) >= 3) {
        $lastname = explode(' ', $patient_fullname)[sizeof(explode(' ', $patient_fullname)) - 1];
    } else {
        $lastname = explode(' ', $patient_fullname)[1];
    }

    $results = mysqli_query($conn,"SELECT     ic.Item_Category_Name,e.Employee_Name,t.Item_ID,t.item_kind,t.Generic_ID,t.Product_Code,t.Product_Code,t.NHIFItem_Type, pp.Patient_Payment_ID AS pid, t.Product_Name,   pp.Check_In_ID,  pp.Claim_Form_Number, pp.Receipt_Date, ppl.Treatment_Authorization_No, ppl.Price AS Price, SUM(ppl.Quantity) AS Quantity, ppl.Discount AS Discount,ppl.Transaction_Date_And_Time     from 	tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,     tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic 	where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	pr.registration_id = pp.registration_id and 	e.employee_id = pp.employee_id and ppl.Status<>'removed'  and 	ic.item_category_id = ts.item_category_id and 	ts.item_subcategory_id = t.item_subcategory_id and 	t.item_id = ppl.item_id and 	pp.Bill_ID='$Bill_ID' AND pp.Check_In_ID = '$Check_In_ID' GROUP BY t.Product_Code") or die(mysqli_error($conn));

    $patient_file = null;
    $consultation_ID = null;
    $RegistrationID = null;

    while ($row3 = mysqli_fetch_array($results)) {
        $FolioItemID = gen_uuid();
        $Patient_Payment_ID = $row3['pid'];
        $Check_In_ID = $row3['Check_In_ID'];

     //force get payment id for testing
      /*
        check if item is generic, if not (i.e brand) find its generic item
      */
      $Product_Code = $row3['Product_Code'];
      $item_kind = $row3['item_kind'];
      $Generic_ID = $row3['Generic_ID'];
      if($item_kind == 'brand'){
        $generic_result = mysqli_query($conn,"SELECT Product_Code FROM tbl_items WHERE Item_ID = '$Generic_ID'");
        $Product_Code = mysqli_fetch_assoc($generic_result)['Product_Code'];
        //die(json_encode(array('code' => $Product_Code)));
      }

        $ItemName = $row3['Product_Name'];
        $ItemCategoryName = $row3['Item_Category_Name'];

        //Folio Items array
        if(empty($last_folio)){
          die(json_encode(array('ehms_error' => 'folio number should not be null')));
        }
	$ApprovalRefNo = (empty($row3['Treatment_Authorization_No'])?null:$row3['Treatment_Authorization_No']);
        $itemsfolioarray = array(
            'FolioItemID' => $FolioItemID,
            'FolioID' => $FolioID,
            'ItemCode' => $Product_Code,
            'OtherDetails' => '',
            'ItemQuantity' => (int) $row3['Quantity'],
            'UnitPrice' => (double) $row3['Price'],
            'AmountClaimed' => ($row3['Price'] - $row3['Discount']) * $row3['Quantity'],
            'ApprovalRefNo' => $ApprovalRefNo,
            'CreatedBy' => $row3['Employee_Name'],
            'DateCreated' => str_replace(' ', 'T', $row3['Transaction_Date_And_Time']) . '.000+03:00',
            'LastModifiedBy' => $row3['Employee_Name'],
            'LastModified' => str_replace(' ', 'T', $row3['Transaction_Date_And_Time']) . '.000+03:00'
        );
        array_push($sampleItems, $itemsfolioarray);
        

    $test_execution = false;
    $diagnosis_query = mysqli_query($conn,"SELECT efd.Disease_ID, efd.Disease_Code AS nhif_code, efd.Consultant_ID as Employee_ID, efd.Consultation_Time AS Disease_Consultation_Date_And_Time, efd.Consultant_Name AS Consultant_Name, diagnosis_type  FROM tbl_edited_folio_diseases efd WHERE Bill_ID = $Bill_ID AND efd.diagnosis_type = 'diagnosis' group by disease_code");
    if(mysqli_num_rows($diagnosis_query) > 0){
        $diagnosis_result = $diagnosis_query;
       
        $test_execution = true;
    }else{
         $diagnosis_query = " SELECT c.consultation_ID,c.Registration_ID,d.nhif_code,d.disease_name,(SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name,dc.diagnosis_type,   dc.Disease_Consultation_Date_And_Time,dc.disease_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID,  dc.employee_ID  FROM 	tbl_patient_payment_item_list ppl  JOIN 	tbl_consultation c ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID 	 JOIN 	tbl_disease_consultation dc ON dc.Consultation_ID = c.Consultation_ID  JOIN 	tbl_disease d ON d.Disease_ID = dc.disease_ID 	 WHERE (dc.diagnosis_type = 'diagnosis') AND ppl.Patient_Payment_ID = $Patient_Payment_ID  group by d.nhif_code";

        $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));
        

    }
/*
	if(mysqli_num_rows($diagnosis_result) < 1){
		$diagnosis_query = "SELECT d.nhif_code as disease_code,dc.diagnosis_type,c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = dc.Employee_ID) as Consultant_Name
     FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID
     AND c.Registration_ID = '$Registration_ID'
     AND dc.diagnosis_type IN('provisional_diagnosis', 'diagnosis')
     AND date(dc.Disease_Consultation_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = (select ci.check_in_id from tbl_check_in ci, tbl_patient_payments pp where pp.check_in_id = ci.Check_In_ID and pp.bill_id = $Patient_Payment_ID LIMIT 1 ))";

	$diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));
	}*/

if(mysqli_num_rows($diagnosis_result) < 1){

        $diagnosis_query = "SELECT d.nhif_code, dc.diagnosis_type, dc.Disease_Consultation_Date_And_Time, c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = dc.Employee_ID) as Consultant_Name   FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID   AND c.Registration_ID = '$Registration_ID' 	 AND disease_code != ''  AND dc.diagnosis_type IN('provisional_diagnosis', 'diagnosis')    AND date(dc.Disease_Consultation_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID ='$New_Check_In ') GROUP by d.nhif_code";

        $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));

}
						//die(json_encode(array('rows'=>mysqli_num_rows($diagnosis_result))));



        if (mysqli_num_rows($diagnosis_result) > 0) {
            while ($diagnosis_row = mysqli_fetch_array($diagnosis_result)) {
                $FolioDiseaseID = gen_uuid();
                $DateCreated = $diagnosis_row['Disease_Consultation_Date_And_Time'];
               // $Disease_Name = $diagnosis_row['disease_name'];
                $Consultant_Name = $diagnosis_row['Consultant_Name'];
                $DiseaseCode = $diagnosis_row['nhif_code'];
                //$consultation_ID = $diagnosis_row['consultation_ID'];
                //$RegistrationID = $diagnosis_row['Registration_ID'];
                $diagnosis_type = $diagnosis_row['diagnosis_type'];

                if($diagnosis_type=="diagnosis"){
                    $disease_status="Final";
                }else{
                    $disease_status="Provisional";
                }

                //Folio Disease array
                $diseasefolioarray = array(
                    'FolioDiseaseID' => $FolioDiseaseID,
                    'DiseaseCode' => $DiseaseCode,
                    'FolioID' => $FolioID,
//                    'Remarks' => '',
                    'Status'=>"$disease_status",
                    'CreatedBy' => $Consultant_Name,
                    'DateCreated' => str_replace(' ', 'T', $DateCreated) . '.000+03:00',
                    'LastModifiedBy' => $Consultant_Name,
                    'LastModified' => str_replace(' ', 'T', $DateCreated) . '.000+03:00'
                );
                  array_push($sampleDiseases, $diseasefolioarray);

            }

			/*
            $select_con2 = mysqli_query($conn," SELECT dw.diagnosis_type,d.disease_code,w.consultation_ID,w.Registration_ID,d.nhif_code,dw.Round_Disease_Date_And_Time,dw.disease_ID, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = w.Employee_ID) as Consultant_Name
    	FROM tbl_ward_round w,tbl_ward_round_disease dw, tbl_disease d
    	WHERE w.Round_ID=dw.Round_ID AND d.Disease_ID = dw.Disease_ID
    	AND (dw.diagnosis_type = 'diagnosis' or dw.diagnosis_type='provisional_diagnosis')
        AND w.Process_Status = 'served'
    	AND w.Consultation_ID ='$consultation_ID' ") or die(mysqli_error($conn));



            if (mysqli_num_rows($select_con2) > 0) {
                while ($diagnosis_row1 = mysqli_fetch_array($select_con2)) {
                    $FolioDiseaseID1 = gen_uuid();
                    $DateCreated = $diagnosis_row1['Round_Disease_Date_And_Time'];
                    $Disease_Name = $diagnosis_row1['disease_name'];
                    $Consultant_Name1 = $diagnosis_row1['Consultant_Name'];
                    $DiseaseCode1 = $diagnosis_row1['nhif_code'];
                    $consultation_ID = $diagnosis_row1['consultation_ID'];
                    $RegistrationID = $diagnosis_row1['Registration_ID'];
                    $diagnosis_type1 = $diagnosis_row1['diagnosis_type'];
                    if($diagnosis_type1=="diagnosis"){
                        $disease_status1="Final";
                    }else{
                        $disease_status1="Provisional";
                    }

                    //Folio Disease array
                    $diseasefolioarray1 = array(
                        'FolioDiseaseID' => $FolioDiseaseID1,
                        'DiseaseCode' => $DiseaseCode1,
                        'FolioID' => $FolioID,
                        //'Remarks' => '',
                        'Status'=>"$disease_status1",
                        'CreatedBy' => $Consultant_Name1,
                        'DateCreated' => str_replace(' ', 'T', $DateCreated) . '.000+03:00',
                        'LastModifiedBy' => $Consultant_Name1,
                        'LastModified' => str_replace(' ', 'T', $DateCreated) . '.000+03:00'
                    );
                    array_push($sampleDiseases, $diseasefolioarray1);

                    //echo "<pre/>";
                    //print_r($diseasefolioarray);
                    //exit();
                }
            }*/
        }

        if($test_execution == false){
		  $select_con2 = mysqli_query($conn," SELECT dw.diagnosis_type,d.disease_code,w.consultation_ID,w.Registration_ID,d.nhif_code,dw.Round_Disease_Date_And_Time as Disease_Consultation_Date_And_Time, dw.disease_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = w.Employee_ID) as Consultant_Name
            	FROM tbl_ward_round w,tbl_ward_round_disease dw, tbl_disease d
            	WHERE w.Round_ID=dw.Round_ID AND d.Disease_ID = dw.Disease_ID
            	AND dw.diagnosis_type = 'diagnosis'
                AND w.Process_Status = 'served'
            	AND w.Consultation_ID IN(SELECT co.consultation_ID FROM tbl_consultation co, tbl_check_in ci WHERE co.Registration_ID=ci.Registration_ID  AND ci.Check_In_ID='$Check_In_ID')  group by d.disease_code") or die(mysqli_error($conn));
        }else{
            $select_con2=$diagnosis_result;
        }

              if (mysqli_num_rows($select_con2) > 0) {
                while ($diagnosis_row1 = mysqli_fetch_array($select_con2)) {
                            $FolioDiseaseID1 = gen_uuid();
                            $DateCreated = $diagnosis_row1['Disease_Consultation_Date_And_Time'];
                            //$Disease_Name = $diagnosis_row1['disease_name'];
                            $Consultant_Name1 = $diagnosis_row1['Consultant_Name'];
                            $DiseaseCode1 = $diagnosis_row1['nhif_code'];
                            //$consultation_ID = $diagnosis_row1['consultation_ID'];
                            //$RegistrationID = $diagnosis_row1['Registration_ID'];
                            $diagnosis_type1 = $diagnosis_row1['diagnosis_type'];
                            if($diagnosis_type1=="diagnosis"){
                                $disease_status1="Final";
                            }else{
                                $disease_status1="Provisional";
                            }

                            //Folio Disease array
                            $diseasefolioarray1 = array(
                                'FolioDiseaseID' => $FolioDiseaseID1,
                                'DiseaseCode' => $DiseaseCode1,
                                'FolioID' => $FolioID,
                                //'Remarks' => '',
                                'Status'=>"$disease_status1",
                                'CreatedBy' => $Consultant_Name1,
                                'DateCreated' => str_replace(' ', 'T', $DateCreated) . '.000+03:00',
                                'LastModifiedBy' => $Consultant_Name1,
                                'LastModified' => str_replace(' ', 'T', $DateCreated) . '.000+03:00'
                            );
                            array_push($sampleDiseases, $diseasefolioarray1);

                            //echo "<pre/>";
                            //print_r($diseasefolioarray);
                            //exit();
                        }
                    }

        //ward
    }
	$FolioDiseases = $sampleDiseases;
    // remove duplicated diseases
    $data = array();
    $temp = array_unique(array_column($sampleDiseases, 'DiseaseCode'));
    $FolioDiseases = array_intersect_key($sampleDiseases, $temp);
	foreach($FolioDiseases as $value){
		array_push($data,$value);
	}
		//die(json_encode($data));

	$FolioDiseases = $data;
 /* =================== generate form 2AB ====================== */

 $patient_file = base64_encode(getPatientFileSummery($consultation_ID, $Registration_ID,$Bill_ID,$typecode,$conn));

    //die(json_encode(array('text'=>$patient_file))); 
    $claim_details_array = (getPatientClaimFile($Bill_ID,$conn));
    //die(json_encode(array('text'=>$claim_details_array)));
    $comparison_array =array();
    $claim_details_array2 = $claim_details_array;
    $comparison_array_item_code = array();

    foreach ($claim_details_array as $value) {

      $index = 0;
      $item_code = $value['Product_Code'];

      foreach ($claim_details_array2 as $value2) {

        if($item_code == $value2['Product_Code']){
          $index+= $value2['Quantity'];
        }
      }
      if(!in_array($item_code, $comparison_array_item_code)){
        array_push($comparison_array,array('Product_Code'=>$item_code, 'Quantity'=>$index));
        array_push($comparison_array_item_code, $item_code);
      }
    }
	$unique_items_arr = [];
	$new_sample_items = [];

	foreach($sampleItems as $item){
		if(!in_array($item['ItemCode'], $unique_items_arr)){
			array_push($unique_items_arr, $item['ItemCode']);
			array_push($new_sample_items, $item);
		}else{
			$unq_count=0;
			foreach($unique_items_arr as $unq){
				
				if($item['ItemCode'] === $unq){
					
					$new_sample_items[$unq_count]['ItemQuantity'] = $new_sample_items[$unq_count]['ItemQuantity']+$item['ItemQuantity'];
					
				}
				$unq_count++;
			}
		}
	        //print_r($item);
		//echo "<br>";
	}

	//print_r($unique_items_arr);
	//print_r(($new_sample_items));

    $sampleItems = $new_sample_items;
    //die(json_encode($comparison_array));
    $comparison_status = array();
    if(sizeof($sampleItems) == sizeof($comparison_array)){
      foreach ($comparison_array as $value) {
        $temp_item = $value['item_code'];
        $temp_quantity = $value['Quantity'];
        foreach ($sampleItems as $key ) {
          if ($key['ItemCode'] == $temp_item) {
            if($temp_quantity == $key['ItemQuantity']){
              array_push($comparison_status,$temp_item);
            }
          }
        }
      }


      //die(json_encode(array('Message' => 'The same array' )));
    }else{

      die(json_encode(array('Message' => 'Item missmatch' )));
    }

    if(sizeof($comparison_status) > 0){
        die(json_encode(array('Message' => 'Quantity Missmatch on these items '.implode(', ', $comparison_status))));
      }

      $claim_status = mysqli_fetch_assoc(mysqli_query($conn,"SELECT claim_status FROM tbl_bills WHERE Bill_ID = $Bill_ID"))['claim_status'];
      if($claim_status == 1){
	
        die(json_encode(array('Message' => 'Claim File Not Generated Succesifully')));
      }
      /**========== Create back up PT FILE by Eng. Muga===================*/

      $PatientFilePath = $Bill_ID.'_patient_file.txt';
      $file1 = fopen('/var/www/html/ehmsbmc/NHIF_FILE/'.$PatientFilePath,'wb');
      fwrite($file1,$patient_file);
      fclose($file1);

      /**========== END Create back up PT FILE Eng. Muga ===================*/

      /**========== Create back up form 2AB Eng. Muga ===================*/
      $ClaimFilePath = $Bill_ID.'_claim_file.txt';
      // $file2 = fopen(,'wb');
      $claim_file = file_get_contents('/var/www/html/ehmsbmc/NHIF_FILE/'.$ClaimFilePath);

      mysqli_query($conn,"INSERT INTO `tbl_claim_data`(`Bill_ID`, `ClaimFilePath`, `PatientFilePath`, `Status`) VALUES ($Bill_ID, '$ClaimFilePath', '$PatientFilePath', 'sent')"); 
      
      // remember to update SubmissionID from NHIF responce And Submission Number.
      /**========== Create back up form 2AB Eng. Muga ===================*/

    $facility_code = mysqli_fetch_array(mysqli_query($conn,"SELECT facility_code FROM tbl_system_configuration"))['facility_code'];
    //nhif folio

    $check_folio = mysqli_query($conn,"SELECT Folio_No , claim_month, claim_year, sent_date FROM tbl_claim_folio WHERE  Bill_ID = '$Bill_ID'");
    $display_folio = '';
    $claim_month = '';
    $claim_year = '';

    if(mysqli_num_rows($check_folio) > 0){
    $claim_info = mysqli_fetch_assoc($check_folio);
    $last_folio = $claim_info['Folio_No'];
    $claim_month = $claim_info['claim_month'];
    $claim_year = $claim_info['claim_year'];
    }else{
        die('no folio no');
        $last_folio = $Folio_Number;
    }
  //$last_folio = date("Y-m")."/".sprintf("%'.07d\n",$last_folio);
   $display_folio = $last_folio;
   $last_folio = sprintf("%'.07d\n",$last_folio);

   $serial_data = $facility_code.'/'.$claim_month.'/'.$claim_year.'/'.$display_folio;
	//die(json_encode(array('error'=>$serial_data)));
    $folioarray = array(
        'FolioID' => $FolioID,
        'FacilityCode' => $FacilityCode,
        'ClaimYear' => (int)date('Y', date(strtotime($Bill_Date_And_Time))),
        'ClaimMonth' => (int)date('m', date(strtotime($Bill_Date_And_Time))),
        'BillNo' => (int)$Bill_ID,
        'FolioNo' => (int)$last_folio,
        'SerialNo' => $serial_data,
        'CardNo' => str_replace(' ','',trim($row2['Member_Number'])),
        'FirstName' => explode(' ', trim($row2['Patient_Name']))[0],
        'LastName' => $lastname,
        'PatientTypeCode' => $typecode,
        'Gender' => $row2['Gender'],
        'DateOfBirth' => $row2['Date_Of_Birth'],
       // 'Age' => $age,
        'TelephoneNo' => $row2['Phone_Number'],
        'PatientFileNo' => $consultation_ID,
        'PatientFile' => $patient_file,
        'ClaimFile' => $claim_file,
        'AuthorizationNo' => trim($AuthorizationNo),
        //'AttendanceDate' => str_replace(' ', 'T', $row2['Payment_Date_And_Time']) . '+03:00',
        'AttendanceDate' => $Attendance_Date,
        'PatientTypeCode' => $typecode,
        'DateAdmitted' => $Admission_Date_Time,
        'DateDischarged' => $Discharge_Date_Time,
        'PractitionerNo' => '',
        'FolioDiseases' => $FolioDiseases,
        'FolioItems' => $sampleItems,
        'CreatedBy' => $row2['Employee_Name'],
        //'DateCreated' => str_replace(' ', 'T', $row2['Payment_Date_And_Time']) . '.00+03:00',
        'DateCreated' => $row2['Payment_Date_And_Time'],
        'LastModifiedBy' => $row2['Employee_Name'],
        //'LastModified' => str_replace(' ', 'T', $row2['Payment_Date_And_Time']),
        'LastModified' => $row2['Payment_Date_And_Time'],
    );
}
//Folio array

$entities = array('entities' => array(
        $folioarray)
);


$foliodata = json_encode($entities);
 //die($foliodata); ===== die folio ili upate form2AB and Case Note Inayokwenda NHIF BY Eng. Muga
 
error_log(''.$foliodata.',  ',3,"/var/www/html/ehms/claims.log");


// Checking Sender Previllege
$SenderID = $_SESSION['userinfo']['Employee_ID'];
$sendResults = mysqli_fetch_assoc((mysqli_query($conn,"SELECT Final_Claim_Sender FROM tbl_privileges WHERE Employee_ID = $SenderID")))['Final_Claim_Sender'];
$deliveryStatus = "";
if($sendResults == 'yes'  && $Location != 'InitialStage'){
    // Sending Claim to NHIF API
    $deliveryStatus=$manager->SubmitFolios($foliodata);
}else{
    die(json_encode(array("Message"=>"Claim Has been Created Succesifully")));
}


$Status = json_decode($deliveryStatus);


if ($Status->StatusCode == 200 || ($Status->StatusCode == 406 && strpos(strtolower($Status->Message),"already been submitted"))) {
    mysqli_query($conn,"UPDATE tbl_bills SET e_bill_delivery_status=1 WHERE Bill_ID='$Bill_ID'") or die(mysqli_error($conn));
}
echo $deliveryStatus;

