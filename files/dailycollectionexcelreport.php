<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	$filter = '';
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}
	
	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get sponsor
    if($Sponsor_ID == '0'){
        $Guarantor = "ALL";
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Guarantor = strtoupper($row['Guarantor_Name']);
            }
        }else{
            $Guarantor = 'ALL';
        }
    }

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

	$date_from = strtotime($Start_Date); // Convert date to a UNIX timestamp  
	$date_to = strtotime($End_Date); // Convert date to a UNIX timestamp

	
	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}

    require_once 'PHPExcel-1.8.1/Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    			//FIRST ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','DAILY COLLECTION REPORT FROM '.date("d-m-Y", strtotime($Start_Date)).' TO '.date("d-m-Y", strtotime($End_Date)));
                //SECOND ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2','SPONSOR: '.$Guarantor);
                //THIRD ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A3','SN')
                ->setCellValue('B3','TRANSACTIONS DATE')
                ->setCellValue('C3','CASH')
                ->setCellValue('D3','CREDIT')
                // ->setCellValue('E3','MSAMAHA')
                ->setCellValue('F3','CANCELLED')
                ->setCellValue('G3','TOTAL');
    //mergin cells
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));

	$rowCount=4;
	$temp = 1;
	$no_of_days = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Msamaha = 0;
	$Grand_Total_Cancelled = 0;

	// Loop from the start date to end date and output all dates inbetween  
	for ($i=$date_from; $i<=$date_to; $i+=86400) {
		$Total_Cash = 0;
		$Total_Credit = 0;
		$Total_Msamaha = 0;
		$Total_Cancelled = 0;
		$no_of_days++;
		$Current_Date = date("Y-m-d", $i);
		
		//get details
		$select = mysqli_query($conn,"select pp.Pre_Paid,sp.Exemption,pp.Billing_Type, pp.Transaction_status, sum((price - discount)*quantity) as Amount, pp.payment_type,pp.Payment_Code,pp.manual_offline
								from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_sponsor sp where
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								sp.Sponsor_ID = pp.Sponsor_ID and
								$filter
								pp.Receipt_Date = '$Current_Date'
								group BY ppl.patient_payment_id order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($data = mysqli_fetch_array($select)){
				// if(isset($_SESSION['systeminfo']['Inpatient_Prepaid']) && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
					// if(((strtolower($data['Billing_Type']) == 'outpatient cash'&&$data['Pre_Paid']==0) || (strtolower($data['Billing_Type']) == 'inpatient cash'&& $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
					// 	$Total_Cash += $data['Amount'];
					// }else if(($data['Exemption']!='yes') && (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit') && strtolower($data['Transaction_status']) != 'cancelled'){
					// 	$Total_Credit += $data['Amount'];
					// }else if(strtolower($data['Transaction_status']) == 'cancelled'){
					// 	$Total_Cancelled += $data['Amount'];
					// }else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	                //     $Total_Msamaha += $data['Amount'];;
			            
                    // } 
				// }else{
					if(((strtolower($data['Billing_Type']) == 'outpatient cash'&& $data['Pre_Paid']==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'  && ($data['auth_code'] != '' || $data['manual_offline'] = 'manual' || ($data['Payment_Code'] != '' && ($data['payment_mode']== 'CRDB' || $data['payment_mode']== 'CRDB..' )))){
						$Total_Cash += $data['Amount'];
					}else if((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') ||  (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
						$Total_Credit += $data['Amount'];
					}else if(strtolower($data['Transaction_status']) == 'cancelled'){
						$Total_Cancelled += $data['Amount'];
					}else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	                    $Total_Msamaha += $data['Amount'];;
			            
                    } 
				// }
			}
		}

		$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount,  "".$temp."")
                ->setCellValue('B'.$rowCount,  "".date("d-m-Y", strtotime($Current_Date))."--".date("l", strtotime($Current_Date))."")
                ->setCellValue('C'.$rowCount,  "".trim($Total_Cash)."")
                ->setCellValue('D'.$rowCount,  "".trim($Total_Credit)."")
                // ->setCellValue('E'.$rowCount,  "".trim($Total_Msamaha)."")
                ->setCellValue('F'.$rowCount,  "".trim($Total_Cancelled)."")
                ->setCellValue('G'.$rowCount,  "".trim($Total_Cash + $Total_Credit)."");

		//get grand total
		$Grand_Total_Cash += $Total_Cash;
		$Grand_Total_Credit += $Total_Credit;
		$Grand_Total_Cancelled += $Total_Cancelled;
		$Grand_Total_Msamaha += $Total_Msamaha;

		++$temp;
		++$rowCount;
	}
		$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount,  'GRAND TOTAL')
                ->setCellValue('C'.$rowCount,  "".trim($Grand_Total_Cash)."")
                ->setCellValue('D'.$rowCount,  "".trim($Grand_Total_Credit)."")
                // ->setCellValue('E'.$rowCount,  "".trim($Grand_Total_Msamaha)."")
                ->setCellValue('F'.$rowCount,  "".trim($Grand_Total_Cancelled)."")
                ->setCellValue('G'.$rowCount,  "".trim($Grand_Total_Cash + $Grand_Total_Credit)."");

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$rowCount.':B'.$rowCount);

        ++$rowCount;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount,  'NUMBER OF DAYS : '.$no_of_days);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$rowCount.':G'.$rowCount);

 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
$worksheetTitle='Daily Patient Attendance';
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->setTitle($worksheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$worksheetTitle.' from '.date_format(date_create($Start_Date),'d-m-Y g:iA').' to '.date_format(date_create($End_Date),'d-m-Y g:iA').'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>