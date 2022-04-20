<?php
include("./includes/connection.php");
if(isset($_GET['Start_Date'])){
    $Start_Date=$_GET['Start_Date'];
}
if(isset($_GET['End_Date'])){
    $End_Date=$_GET['End_Date'];
}
if(isset($_GET['Sponsor_ID'])){
    $Sponsor_ID=$_GET['Sponsor_ID'];
}
if(isset($_GET['Billing_Type'])){
    $Billing_Type=$_GET['Billing_Type'];
}
if(isset($_GET['Patient_Name'])){
    $Patient_Name=$_GET['Patient_Name'];
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
if(isset($_GET['Patient_Type'])){
    $Patient_Type=$_GET['Patient_Type'];
}
if(isset($_GET['Billing_Type'])){
    $Billing_Type=$_GET['Billing_Type'];
}


$Filter_Billing="";
//generate billing type 
	if($Patient_Type == 'All'){
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'pre'))";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'post'))";
		}
	}else if($Patient_Type == 'Outpatient'){
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Outpatient Cash')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Outpatient Credit')";
		}
	}else{
		if($Billing_Type == 'All'){
			$Filter_Billing = "(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')";
		}else if($Billing_Type == 'Cash'){
			$Filter_Billing = "(pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'pre')";
		}else{
			$Filter_Billing = "(pp.billing_type = 'Inpatient Credit' or (pp.billing_type = 'Inpatient Cash' and pp.payment_type = 'post'))";
		}
	}

   /* echo "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
        <tr><td></td></tr>
		<tr>
		    <td style='text-align: center;'><b>REVENUE COLLECTION BY PATIENT REPORT</b></td>
		</tr></table>";*/

  /** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Africa/Dar_es_Salaam');
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'PHPExcel-1.8.1/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

/* this return new column name*/
function newColumn($index){
	$columns = array('1'=>'D','2'=>'E','3'=>'F','4'=>'G','5'=>'H','6'=>'I','7'=>'J','8'=>'K','9'=>'L','10'=>'M');
	
	return $columns[$index];
}

function getSponsorName($Sponsor_ID){
	return mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = $Sponsor_ID"))['Guarantor_Name'];
}

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1','REVENUE COLLECTION BY PATIENT REPORT');

      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B2','SPONSOR NAME : '.getSponsorName($Sponsor_ID))
      ->setCellValue('B3','PATIENT TYPE : '.strtoupper($Patient_Type))
      ->setCellValue('B3','START DATE : '.strtoupper($Start_Date))
      ->setCellValue('E3','END DATE : '.strtoupper($End_Date))
      ->setCellValue('B4','BILL TYPE : '.strtoupper($Billing_Type));

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:H2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:D3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E3:G3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B4:H4');


////////////////////////////////////////////////////////////////////////////////////////////////////

        //select all patient at a specific time range

$sql_select_patient_at_a_specific_time_range_result=mysqli_query($conn,"SELECT Patient_Name,Registration_ID FROM tbl_patient_registration WHERE Sponsor_ID='$Sponsor_ID' AND Registration_ID IN(SELECT Registration_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) AND Registration_ID LIKE '%$Registration_ID%' AND Patient_Name LIKE '%$Patient_Name%' GROUP BY Registration_ID") or die(mysqli_error($conn));
    

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A7','SN')
      ->setCellValue('B7','Patient Name')
      ->setCellValue('C7','Attendance Date');
	
	 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C7:H7');

if(mysqli_num_rows($sql_select_patient_at_a_specific_time_range_result)>0){
    $count_sn=1;
    $count_patient=1;


	
    while($patient_rows=mysqli_fetch_assoc($sql_select_patient_at_a_specific_time_range_result)){
        $grand_total_per_patient=0;
        $Patient_Name=$patient_rows['Patient_Name'];
        $Registration_ID=$patient_rows['Registration_ID'];
        
		
		if($count_patient == 1){
				   	$count_patient =$count_patient+7;
				   }else{
				   	$count_patient++;
				   }
		
       

		$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.($count_patient),$count_sn)
      ->setCellValue('B'.($count_patient),$Patient_Name);
               $sql_select_attendance_date_result=mysqli_query($conn,"SELECT Visit_Date,Check_In_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_attendance_date_result)>0){

				   
		$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('C'.($count_patient),'Attendance Date');
				   
				   
				   
				   $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
                       		$Total_all  = 1;
                               if(mysqli_num_rows($select_item_sub_category_result)>0){
								   $Sub_count= 1;
                                  while($sub_c_rows=mysqli_fetch_assoc($select_item_sub_category_result)){
                                     $Item_Subcategory_Name=$sub_c_rows['Item_Subcategory_Name'];
                                     $Item_Subcategory_ID=$sub_c_rows['Item_Subcategory_ID'];

									  $objPHPExcel->setActiveSheetIndex(0)
      								->setCellValue(newColumn($Sub_count).($count_patient),$Item_Subcategory_Name);
									  $Sub_count++;
									  //$count_sn += $Sub_count;
									   $Total_all = $Sub_count;
                                  } 
                               }

				  
				  $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue(newColumn($Total_all).($count_patient),'TOTAL');
                   
                   
                   while($attendance_rows=mysqli_fetch_assoc($sql_select_attendance_date_result)){
                       $Visit_Date=$attendance_rows['Visit_Date'];
                       $Check_In_ID=$attendance_rows['Check_In_ID'];
                       $sub_total_per_date=0;

					   
					   $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('C'.(++$count_patient),$Visit_Date);
                       ///////////////////////////////money calculation
                       $colspan_value=1;
                               $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
                                if(mysqli_num_rows($select_item_sub_category_result)>0){
									$Sub_count= 1;
                                  while($sub_c_rows=mysqli_fetch_assoc($select_item_sub_category_result)){
                                     $Item_Subcategory_ID=$sub_c_rows['Item_Subcategory_ID'];
                                     ///////////////////////////////////////////////money amount per each category
                                    $total_amount=0;
                                    $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID' AND Item_ID IN(SELECT Item_ID FROM `tbl_items` i, tbl_item_subcategory its WHERE its.Item_Subcategory_ID=i.Item_Subcategory_ID AND its.Item_Subcategory_ID='$Item_Subcategory_ID') AND $Filter_Billing") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){
                                       
                                       while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                                           
                                         
                                          $Sponsor_ID=$items_rows['Sponsor_ID'];
                                          $Discount=$items_rows['Discount'];
                                          $Price=$items_rows['Price'];
                                          $Quantity=$items_rows['Quantity'];

                                           
//                                           $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
//                                           $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
                        ///////////////////////////////////////////////////////////////
//

//                                            if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
//                                                    $subtotal_msamaha += ($Quantity*($Price-$Discount));
//                                                    $number_of_service++;
//                                            }  else {
                                                 $total_amount +=($Quantity*($Price-$Discount));
//                                            }

                                            
                                       ///////////////////////////////////////////////////////////////
                                       }
                                    }
                                    $sub_total_per_date +=$total_amount;

									  
									  $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue(newColumn($Sub_count).($count_patient),$total_amount);
                                     ///////////////////////////////////end calculation
                                    $colspan_value++;
									  $Sub_count++;
                                  } 
                                  
                               }
                       ///////////////////////////////////////////////////
                               $grand_total_per_patient +=$sub_total_per_date;

					   $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue(newColumn($Total_all).($count_patient),$sub_total_per_date);

                       				           	//$count_patient++;

                   } 
				    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.(($count_patient+1)),'GRAND TOTAL')
      ->setCellValue(newColumn($Total_all).(($count_patient+1)),$grand_total_per_patient);
      $count_patient++;
				   

               }
        
        $count_sn++;
        	$count_patient++;

    }
}

$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));

$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));

$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));


 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

 /*
	the commented code below allow manual column width resizing,
	you may uncomment to get desired column width
 */
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

/*
	this loop set automatic column width as the size of the string length
*/
// for($i = 1; $i < 5; $i++) {
//     $objPHPExcel->getActiveSheet()->getColumnDimension(newColumn($i))
//         ->setAutoSize(true);
// }

$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=revenue_collection_by_patient_report.xls');
header('Cache-Control" max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter ->save('php://output');
exit;
?>