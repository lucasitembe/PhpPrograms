<?php
  error_reporting(E_ERROR);
//include("./includes/header.php");
include("./includes/connection.php");
 //get folio number
    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = 0;
    }

    //get Patient Bill ID
    if(isset($_GET['Patient_Bill_ID'])){
        $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
    }else{
        $Patient_Bill_ID = 0;
    }

    //get insurance name
    if(isset($_GET['Insurance'])){
        $Insurance = $_GET['Insurance'];
    }else{
        $Insurance = '';
    }
    
    //get registration ID
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get insurance name
    $select = mysqli_query($conn,"select Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
    						sp.Sponsor_ID = pr.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Guarantor_Name = $data['Guarantor_Name'];
    	}
    }else{
    	$Guarantor_Name = '';
    }
  //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	$Date_Time = $row['datetime'];
    }

 $select_Transaction_Items = mysqli_query($conn,"
            select * from
                tbl_patient_registration pr,tbl_check_in ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				ch.registration_id = pr.registration_id and
				(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
				pr.registration_id = pp.registration_id and
				PP.Patient_Bill_ID = '$Patient_Bill_ID' and
				e.employee_id = pp.employee_id and
				ic.item_category_id = ts.item_category_id and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id and
				pp.folio_number = '$Folio_Number' and
				pr.registration_id = '$Registration_ID' group by pp.Patient_Payment_ID, pp.Patient_Bill_ID order by pp.Patient_Payment_ID limit 1") or die(mysqli_error($conn));
 
  $htm .= "<center><table width='100%' >";
    $htm .= "";
    while($row = mysqli_fetch_array($select_Transaction_Items)){
       $Patient_Name = $row['Patient_Name'];
       $Folio_Number = $row['Folio_Number'];
       $Sponsor_Name = $row['Sponsor_Name'];
       $Gender = $row['Gender'];
       $Date_Of_Birth = $row['Date_Of_Birth'];
       $Member_Number = $row['Member_Number'];
       $Billing_Type = $row['Billing_Type'];
       $Occupation = $row['Occupation'];
       $Visit_Date = $row['Visit_Date'];
       $Patient_Payment_ID = $row['Patient_Payment_ID'];
       $Employee_Vote_Number = $row['Employee_Vote_Number'];
       $Phone_Number = $row['Phone_Number'];
    }
	
	$date1 = new DateTime(Date("Y-m-d"));
	$date2 = new DateTime($Date_Of_Birth);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days";

    //get visit date
    $select_visit = mysqli_query($conn,"select Visit_Date from tbl_check_in where
				    Check_In_ID = (select Check_In_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					where pp.patient_payment_id = ppl.patient_payment_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Registration_ID = '$Registration_ID' and
                    pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1)
					order by pp.Patient_Payment_ID limit 1)") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_visit);
    if($num > 0){
    	while($row = mysqli_fetch_array($select_visit)){
    	    $Visit_Date = $row['Visit_Date'];
    	}
    }else{
        $Visit_Date = '';
    }


    //get authorisation number
    $AuthorizationNo = '';
    $select_visit = mysqli_query($conn,"select c.AuthorizationNo from tbl_patient_payments pp, tbl_check_in c where
    				c.Check_In_ID = pp.Check_In_ID and
					pp.Folio_Number = '$Folio_Number' and
					pp.Registration_ID = '$Registration_ID' and
                    pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1)") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_visit);
    if($num > 0){
    	while($row = mysqli_fetch_array($select_visit)){
    		if($row['AuthorizationNo'] != null && $row['AuthorizationNo'] != ''){
				$AuthorizationNo .= $row['AuthorizationNo'].'; ';
    		}
    	}
    }else{
        $AuthorizationNo = '';
    }

    if($Billing_Type =="Outpatient Credit"){
	$patient_type = "Outpatient";
	$patient_status = "OUT";
    }else{
	$patient_type = "Inpatient";
	$patient_status = "IN";
    }

    $diagnosis_query = "SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
           FROM tbl_consultation c,tbl_disease_consultation dc, tbl_disease d
	   WHERE c.Consultation_ID=dc.Consultation_ID AND d.Disease_ID = dc.Disease_ID
	   AND dc.diagnosis_type = 'diagnosis'
	   AND c.Patient_Payment_Item_List_ID IN (SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl WHERE ppl.Patient_Payment_ID=$Patient_Payment_ID)";
    $diagnosis_result = mysqli_query($conn,$diagnosis_query);
    $diagnosis = "";
    $Consultant_Name = "";
    while($diagnosis_row = mysqli_fetch_assoc($diagnosis_result)){
	$diagnosis.=$diagnosis_row['disease_code']."; ";
	$Consultant_Name = $diagnosis_row['Consultant_Name']."; ";
    }
    

    //select diagnosis details inpatients    
    $select_con = mysqli_query($conn,"SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
        FROM tbl_ward_round c,tbl_ward_round_disease dc, tbl_disease d
        WHERE c.Round_ID =dc.Round_ID AND d.Disease_ID = dc.Disease_ID
        AND dc.diagnosis_type = 'diagnosis'
        AND c.Patient_Payment_Item_List_ID IN (
            SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
            ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
            pp.Folio_Number = '$Folio_Number' and
            pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn));
    $no_of_rows = mysqli_num_rows($select_con);
    if($no_of_rows > 0){
        while($diagnosis_row = mysqli_fetch_array($select_con)){
            $diagnosis.=$diagnosis_row['disease_code']."; ";
            //$Consultant_Name = $diagnosis_row['Consultant_Name'];
        }
    }
    
    
    
    $company_query = "SELECT Company_Name FROM tbl_company";
    $company_result = mysqli_query($conn,$company_query);
    $company_row = mysqli_fetch_assoc($company_result);
    $company = $company_row['Company_Name'];
    $htm .="<tr>
              <th style='text-align:center;' colspan='3'>PATIENT INFORMATION<br/></th>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td style='width:50%'><b>Name:</b><span class='valuePadding'>Hunter of the waste gate</span></td><td style='width:30%'><b>Dirt of Bith:</b><span class='valuePadding'>04/12/2015</span></td><td style='width:20%'><b>Genda:</b><span class='valuePadding'>Female</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Registration ID:</b><span class='valuePadding'>132456</span></td><td style='width:33%'><b>Sponsor:</b><span class='valuePadding'>NHIF</span></td><td style='width:33%'><b>Phone:</b><span class='valuePadding'>+255789234567</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Member Number:</b><span class='valuePadding'>5678567</span></td><td style='width:33%'><b>Billing Type:</b><span class='valuePadding'>Outpatient Cash</span></td><td style='width:33%'><b>Folio #:</b><span class='valuePadding'>1542</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Age:</b><span class='valuePadding'>58 Years, 1 Months, 30 Days</span></td><td style='width:33%'><b>Registered Date:</b><span class='valuePadding'>2015-05-13</span></td><td style='width:33%'><b>Claim #:</b><span class='valuePadding'>1542</span></td>
            </tr>
             <tr>
              <td style='width:50%'><b>Country:</b><span class='valuePadding'>Tanzania</span></td><td style='width:33%'><b>Region:</b><span class='valuePadding'>Dar es salaam</span></td><td style='width:33%'><b>District:</b><span class='valuePadding'>Kinondoni</span></td>
            </tr>
            ";

    $htm .= '</table></center>';
    
    $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '1316' AND Registration_ID='31388' AND Validated != 'Yes' AND tr.payment_item_ID='44576' GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
    $data = '';
        //die($selectQuery);
        $GetResults = mysqli_query($conn,$selectQuery);
        $data .= "<center><table class='' style='width:100%'>";
        $data .= "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th width='' style='text-align:left'>Parameters</th>
                <th width='' style='text-align:center'>Results</th>
                <th width='' style='text-align:left'>UoM</th>
                <th width='' style='text-align:left'>M</th>
                <th width='' style='text-align:left'>V</th>
                <th width='' style='text-align:left'>S</th>
				<th width='' style='text-align:left'>Normal Value</th>
                <th width='' style='text-align:left'>Status</th>
                <th width='' style='text-align:left'>Previous results</th>
            </tr>";

        $testID = '';
        $paymentID = '';
        $Validated = false;

        $datamsg = mysqli_num_rows($GetResults);
        $sn=1;
        while ($row2 = mysqli_fetch_assoc($GetResults)) {
            $testID = $row2['test_result_ID'];
            $paymentID = $row2['payment_item_ID'];
            $input = '';

            if ($row2['result_type'] == 'Quantitative') {

                $input = '<input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only">';
            } else if ($row2['result_type'] == 'Qualitative') {
                $input = '<input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '">';
            }

            $data .= '<tr>';
            $data .= '<td>' . $sn++ . '</td>';
            $data .= '<td>' . $row2['Parameter_Name'] . '</td>';
            $data .= '<td>' . $input . '</td>';

            $data .= '<input type="hidden" class="parameterName" value="' . $row2['Parameter_Name'] . '">';
            $data .= '<input type="hidden" class="paymentID" value="' . $row2['test_result_ID'] . '">';
            $data .= '<input type="hidden" class="productID" value="' . $id . '">';
            $data .= '<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
            $data .= '<td>' . $row2['unit_of_measure'] . '</td>';

            if ($row2['modified'] == "Yes") {
                $data .= '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
            } else {
                $data .= '<td></td>';
            }

            if ($row2['Validated'] == "Yes") {
                $data .= '<td>&#x2714;</td>';
                $Validated = true;
            } else {
                $data .= '<td></td>';
            }
            if ($row2['Submitted'] == "Yes") {
                $data .= '<td>&#x2714;</td>';
            } else {
                $data .= '<td></td>';
            }


            $data .= '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';


            $data .= '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

            $lower = $row2['lower_value'];
            $upper = $row2['higher_value'];
            $rowResult = $row2['result'];
            $Saved = $row2['Saved'];
            $result_type = $row2['result_type'];
            if ($result_type == "Quantitative") {
                if ($rowResult > $upper) {
                    $data .= '<td><p style="color:rgb(255,0,0)">H</p></td>';
                } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                    $data .= '<td><p style="color:rgb(255,0,0)">L</p></td>';
                } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                    $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                } else {
                    $data .= '<td><p style="color:rgb(0,128,0)"></p></td>';
                }
            } else if ($result_type == "Qualitative") {
                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
            } else {
                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
            }


            //Get previous test results
            $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $id . "' AND tr.payment_item_ID<>'" . $ppil . "'";
            $Queryhistory = mysqli_query($conn,$historyQ);
            $myrows = mysqli_num_rows($Queryhistory);
            if ($myrows > 0) {
                //echo $historyQ;
                $data .= '<td>
                <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                        $myrows . ' Previous result(s)'
                        . '</p>
               
                </td>';
            } else {

                //echo $historyQ;
                $data .= '<td>No previous results</td>';
            }


            // if($row2['Validated']=='Yes'){
            $data .= '<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" readonly="true" style="display:none">';
            /*  } else {
              $data .= '<input type="checkbox" class="validated" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'" style="display:none">';
              } */

            //place submit here
            // if($row2['Submitted']=='Yes'){
            $data .= '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" disabled="true" style="display:none">';
            /* } else {
              $data .= '<input type="checkbox" class="Submitted" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'" style="display:none">';
              } */


            $data .= '</tr>';
        }
        
        $data .= '</table></center>';
        
       


$disp='<style>
    #wrapper{
        width:100%;
        margin:10px auto; 
        padding:10px; 
        //border:1px solid blue;
        font-family:"Times New Roman",Times,serif; //Gill, Helvetica, sans-serif
    }
    #labpatientInfo,
    #labResultsInfo{
         clear: both;
         margin:10px; 
    }
    #labResultsInfo{
       
       // border:1px solid #006400;
    }
    #labpatientInfo{
       
       // border:1px solid #000;
    }
    #labResults{
        //border:1px solid #ffd96e;
    }
    #labResultsInfo h1{
        margin: 0;
        padding: 0;
        font-size:14px;
        font-family: monospace,courier;
    }
    
   .valuePadding{
        padding-left: 7px;
   }
</style>';

$disp .='<div id="wrapper">';
    $disp .='<img src="branchBanner/branchBanner.png" width="100%" height="auto">';
     $disp .='<div id="labpatientInfo">
                     '.$htm.'
    </div>';
     $disp .='<div id="labResultsInfo">
        <h1 align="center">Date and time gathered: '.date('d/m/Y H:m:s').'</h1>
        <div id="labResults">
           '.$data.'
        </div>
    </div>';
 $disp .='</div>';
 
 //echo $disp;
 
 include("MPDF/mpdf.php");
$mpdf = new mPDF();
$mpdf->WriteHTML($disp);
$mpdf->Output();
         