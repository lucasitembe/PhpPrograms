<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	if(isset($_GET['Product_Name'])){
		$Product_Name = $_GET['Product_Name'];
	}else{
		$Product_Name = '';
	}
        $filter_by_date="";
	if(isset($_GET['result_date'])){
		$result_date = $_GET['result_date'];
                if(!empty($result_date)){
                    $filter_by_date="AND result_date='$result_date'";
                }else{
                  $filter_by_date="AND sent_to_doctor='yes'"; 
                }
	}else{
		  $filter_by_date="AND sent_to_doctor='yes'";
	}
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}
	
//get patient name
        $sql_select_patient_name_result=mysqli_query($conn,"SELECT Gender,Registration_ID,Patient_Name,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age FROM tbl_patient_registration WHERE Registration_ID IN (SELECT Registration_ID FROM tbl_payment_cache WHERE Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID')) LIMIT 1") or die(mysqli_error($conn));
        $patient_detail_rows=mysqli_fetch_assoc($sql_select_patient_name_result);
        
        $Patient_Name=$patient_detail_rows['Patient_Name'];
        $Gender=$patient_detail_rows['Gender'];
        $Registration_ID=$patient_detail_rows['Registration_ID'];
        $age=$patient_detail_rows['age'];
        
    //get employees
    if($Employee_ID == '0'){
        $Employees = 'EMPLOYEES : ALL';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Employees = 'EMPLOYEE : '.$row['Employee_Name'];
            }
        }else{
            $Employees = 'EMPLOYEES : ALL';
        }
    }

	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}
        
       $test_result_ID_result=mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
       $test_result_ID=mysqli_fetch_assoc($test_result_ID_result)['test_result_ID'];
        
        $test_result_ID=(int)$test_result_ID;
        $row_check_if_exists = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='$test_result_ID' AND Saved='Yes' ")or die(mysqli_error($conn));

        //echo 'submitted and validated';
        $specimen="";
        if (mysqli_num_rows($row_check_if_exists) >= 0) {
            //get Specimen

            $checkSpecimenCollection = mysqli_query($conn,"SELECT Specimen_Name FROM tbl_laboratory_specimen ls JOIN tbl_specimen_results  sp ON ls.Specimen_ID=sp.Specimen_ID WHERE payment_item_ID='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
            $specimen = '';
            $track = 1;
            while ($row = mysqli_fetch_array($checkSpecimenCollection)) {
                if ($track == 1) {
                    $specimen = $row['Specimen_Name'];
                } else {
                    $specimen .=',' . $row['Specimen_Name'];
                    ;
                }

                $track ++;
            }
        }
        $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' AND validated='no' $filter_by_date ");
if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)>0){
    $provisional_status="<tr><td style='color:red'><b>PROVISIONAL RESULTS</b></td><tr>";
}else{
    $provisional_status="";
}
        
        $sql_select_item_category_result=mysqli_query($conn,"SELECT Item_Subcategory_Name FROM tbl_item_subcategory WHERE Item_Subcategory_ID=(SELECT Item_Subcategory_ID FROM tbl_items WHERE Item_ID=(SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'))") or die(mysqli_error($conn));
	$Item_Subcategory_Name= strtoupper(mysqli_fetch_assoc($sql_select_item_category_result)['Item_Subcategory_Name']);
        $htm = '<table align="center" style="font-size:9" width="100%">
                    <tr><td style="text-align:left" colspan="4"><img src="./branchBanner/branchBanner.png"></td></tr>
                    <tr><td style="text-align:center;" colspan="4"><h6>'.$Item_Subcategory_Name.' ANALYSIS REPORT</h6></td></tr>
                    <tr><td style="text-align:left"><b>PATIENT NAME : </b>'.$Patient_Name.'</td><td style="text-align:left"><b>REG# : </b>'.$Registration_ID.'</td><td style="text-align:left"><b>AGE : </b>'.$age.' years</td></tr>
                    <tr><td><b>SPECIMEN :</b>'.$specimen.'</td><td style="text-align:left"><b>SPECIMEN ID :</b>'.$Payment_Item_Cache_List_ID.'</td><td style="text-align:left"><b>SEX : </b>'.$Gender.'</td></tr>
                    <tr><td style="text-align:left" colspan="2"><b>TEST NAME : </b>'.$Product_Name.'</td></tr>
                '.$provisional_status.'
        <tr><td colspan="4"><hr style="width:100%;height:1px;margin-top:-0.1%;margin-bottom:-0.1%" /></td></tr>
</table>';
       $count_run=1;
   $sql_select_machine_run_time_result=mysqli_query($conn,"SELECT result_date FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' $filter_by_date GROUP BY result_date ORDER BY  intergrated_lab_results_id DESC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_machine_run_time_result)>0){
      while($run_time_rows=mysqli_fetch_assoc($sql_select_machine_run_time_result)){
         $result_date=$run_time_rows['result_date'];
        
        
  $htm.='
    <div width="70%" style="float:left"><table width="100%" style="font-size:9px!important;float:left">
    <tr>
        <td><b>S/No.</b></td>
        <td><b>PARAMETERS</b></td>
        <td><b>RESULTS</b></td>
        <td><b>NORMAL VALUE</b></td>
        <td><b>UNITS</b></td>
        <td><b>STATUS</b></td>
        <td><b>M</b></td>
        <td><b>V</b></td>
        <td><b>S</b></td>
    </tr>
    <tbodcy>';
  $count_run++;
        $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' $filter_by_date AND result_date='$result_date'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)>0){
            $count_sr=1;
           while($rows=mysqli_fetch_assoc($sql_select_patient_lab_intergrate_result)){
              $parameters= $rows['parameters'];
              $results= $rows['results'];
              $reference_range_normal_values= $rows['reference_range_normal_values'];
              $units= $rows['units'];
              $status_h_or_l= $rows['status_h_or_l'];
              $sample_test_id= $rows['sample_test_id'];
              $machine_type= $rows['machine_type'];
              $result_date= $rows['result_date'];
              $modified= $rows['modified'];
              $validated= $rows['validated'];
              $sent_to_doctor= $rows['sent_to_doctor'];
              $validated_by= $rows['validated_by'];
              $sent_to_doctor_by= $rows['sent_to_doctor_by'];
              
              $bold_conditional_row_left="";
              $bold_conditional_row_right="";
        if($parameters=="WBC"||$parameters=="RBC"||$parameters=="PLT"){
            $bold_conditional_row_left="<b>";
            $bold_conditional_row_right="</b>";  
        }
              
              $htm.= "<tr $bold_conditional_row><td colspan='10'><hr style='width:100%;height:0.1px;margin-top:-0.1%;margin-bottom:-0.1%'/></td></tr>";
               $htm.= "<tr>
                        <td>$bold_conditional_row_left $count_sr.$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $parameters$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $results$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $reference_range_normal_values$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $units$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $status_h_or_l$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $modified$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $validated$bold_conditional_row_right</td>
                        <td>$bold_conditional_row_left $sent_to_doctor$bold_conditional_row_right</td>
                    </tr>";
               $count_sr++;
           } 
        }
  
$htm.=" </tbody>
</table></div>"; 
if($Item_Subcategory_Name=="HAEMATOLOGY"){
$htm.="<div style='float:right;width:28%'>
    <table  border='1' width='100%'>
            <tr><td style='text-align:center'>WBC MESSAGE</td></tr>
            <tr><td style='height:150px'></td></tr>
    </table>
    <table  border='1' width='100%'>
            <tr><td style='text-align:center'>RBC MESSAGE</td></tr>
            <tr><td style='height:150px'></td></tr>
    </table>
    <table  border='1' width='100%'>
            <tr><td style='text-align:center'>PLT MESSAGE</td></tr>
            <tr><td style='height:150px'></td></tr>
    </table>
      
</div>";
}
$htm.="<div style='clear:both;'></div>";
$doctor_ordered="";
$$Transaction_Date_And_Time="";
$sql_select_employee_name_result=mysqli_query($conn,"SELECT Employee_Name,Transaction_Date_And_Time FROM tbl_employee emp,tbl_item_list_cache ilc WHERE emp.Employee_ID=ilc.Consultant_ID AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_employee_name_result)>0){
    $rows=mysqli_fetch_assoc($sql_select_employee_name_result);
    $doctor_ordered=$rows['Employee_Name'];
    $Transaction_Date_And_Time=$rows['Transaction_Date_And_Time'];
}

$TimeSubmitted="";
$Query = "SELECT Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' GROUP BY Payment_Item_Cache_List_ID";
$result2 = mysqli_query($conn,$Query) or die(mysqli_error($conn));
if(mysqli_num_rows($result2)>0){
    $rows_2=mysqli_fetch_assoc($result2);
    $TimeSubmitted=$rows_2['TimeSubmitted'];
}

$validated_by2=(int)$validated_by;
$select_result = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$validated_by2'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_result)>0){
    $validated_by=mysqli_fetch_assoc($select_result)['Employee_Name'];
}
$sent_to_doctor_by2=(int)$sent_to_doctor_by;
$select_result = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$sent_to_doctor_by2'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_result)>0){
    $sent_to_doctor_by=mysqli_fetch_assoc($select_result)['Employee_Name'];
}

$date1 = new DateTime($result_date);
        $date2 = new DateTime($TimeSubmitted);
        $diff = $date1->diff($date2);
        $lapsed_time = $diff->d . " days, ";
        $lapsed_time .= $diff->h . " hours, ";
        $lapsed_time .= $diff->i . " minutes,";
        $lapsed_time .= $diff->s . " Seconds";
        

$htm.=" 
            <table width='100%'>
                <tr>
                    <td colspan='4'><hr style='width:100%;height:1px;'/></td>
                </tr>
                <tr>
                    <td style='text-align:left'>Ordering Doctor:</td><td style='text-align:left'> $doctor_ordered </td>
                    <td style='text-align:right'>Order Date:</td><td style='text-align:left'>$Transaction_Date_And_Time</td>
                </tr>
                <tr>
                    <td style='text-align:left'>Specimen Collected:</td><td style='text-align:left'> $TimeSubmitted </td>
                    <td style='text-align:right'>Reviewed By:</td><td style='text-align:left'> $validated_by</td>
                </tr>
                <tr>
                    <td style='text-align:left'>Date Of Result:<td style='text-align:left'> $result_date </td></td>
                    <td style='text-align:right'>TAT:</td><td style='text-align:left'> $lapsed_time</td>
                </tr>
                <tr>
                    <td style='text-align:left'>Authorized By:</td><td style='text-align:left'> $sent_to_doctor_by </td>
                </tr>
            </table>
"; 
  }}       
	$date_n_time_printed=date("H:i:s");
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y} '.$date_n_time_printed.' |Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>