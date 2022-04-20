<?php
include("./includes/connection.php");

$Guarantor_Name = "All";
$filterSub=' '; 
$toDate = '';
$fromDate = '';

if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $sponsorName = $_GET['Sponsor'];
    $SubCategory = $_GET['SubCategory'];
    $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
   

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
}

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

    // Set document properties
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','SN')
                ->setCellValue('B1','Examination Entity')
                ->setCellValue('C1','Total');

      $categoryRow=1;
        $i=2;

 $sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter $filterSub  and i.Consultation_Type='Radiology' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
// echo $sqlCat;exit; 
    $querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn));
 if(mysqli_num_rows($querySubcategory) >0 ){
    while ($row1 = mysqli_fetch_array($querySubcategory)) {
        $subcategory_name = $row1['Item_Subcategory_Name'];
        $subcategory_id = $row1['Item_Subcategory_ID'];

        $radiologyData=array();

    	$number_of_item = "SELECT i.Product_Name,i.Item_ID,Guarantor_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter AND i.Item_Subcategory_ID=$subcategory_id GROUP BY Product_Name";

        ++$categoryRow;
        ++$i;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$categoryRow,$subcategory_name);
						
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$categoryRow.':'.'C'.$categoryRow);
        // $htm.= $number_of_item;exit;
        $number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));

        $sn = 1;
        $grandTotal = 0;
		$entityTotal=0;
        if(mysqli_num_rows($number_of_item_results) >0 ){
      
        while ($row = mysqli_fetch_assoc($number_of_item_results)) {

            $number_item_count = "SELECT i.Item_ID FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID $filter AND ilc.Item_ID='" . $row['Item_ID'] . "'";

            //die($number_item_count);
            $number_of_item_count_results = mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
            $itemCount = mysqli_num_rows($number_of_item_count_results);
            //$itemCount=  mysqli_fetch_assoc($number_of_item_count_results)['Number_Of_Item'];

            /*
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($sn++)."")
                ->setCellValue('B'.$i, $row['Product_Name'])
                ->setCellValue('C'.$i, $itemCount);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                ++$categoryRow;
                */
                array_push($radiologyData, array(
                        'quantity'=>$itemCount,
                        'name'=>$row['Product_Name']
                    ));
                

        }
        array_multisort($radiologyData,SORT_DESC);

        foreach ($radiologyData as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($sn)."")
                ->setCellValue('B'.$i, $value['name'])
                ->setCellValue('C'.$i, $value['quantity']);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                ++$categoryRow;
				$sn++;
				$grandTotal+=$value['quantity'];
		}
        }
		$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "Total Examinations
				")
                ->setCellValue('C'.$i,  "".($grandTotal)."");
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':'.'B'.$i);
				++$i;
				++$categoryRow;
    }
}
 // Rename worksheet
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//  $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->setTitle('Radiology');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="radiology report from '.date_format(date_create($fromDate),'d-m-Y g:iA').' to '.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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



