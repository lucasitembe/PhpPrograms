<?php
session_start();
include("./includes/connection.php"); 
    $temp = 1;
    //get current date and time
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    
    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = $Filter_Value;
    }
    
    
    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = $Filter_Value;
    }

    if(isset($_SESSION['Storage'])){
        $Sub_Department_Name = $_SESSION['Storage'];
    }else{
        $Sub_Department_Name = '';
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    //get item name
    $sql_get_item = mysqli_query($conn,"select Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($sql_get_item);
    if($no > 0){
        while($r = mysqli_fetch_array($sql_get_item)){
            $Item_Name = $r['Product_Name'];
        }
    }else{
        $Item_Name = '';
    }
    $filter = '';
    if(isset($_GET['sponsorID']) && $_GET['sponsorID']!=''){
        $sponsorID = $_GET['sponsorID'];
        $filter .= " and ts.Sponsor_ID='$sponsorID'";
    }
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
    //////////////////////////////////////////////////////////
    
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
    $sub_dept=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID=$Sub_Department_ID"))['Sub_Department_Name'];
    function get_item_buying_price($Item_ID,$Dispense_Date_Time){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        $select_list_of_buying_price_result=mysqli_query($conn,"SELECT Approval_Date_Time,Selling_Price,Last_Buying_Price FROM tbl_requisition req INNER JOIN tbl_requisition_items reqi ON req.Requisition_ID=reqi.Requisition_ID WHERE Store_Need='$Sub_Department_ID' AND Item_ID='$Item_ID' AND req.Requisition_Status='Received' ORDER BY Approval_Date_Time DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_list_of_buying_price_result)>0){
            while($buying_price_rows=mysqli_fetch_assoc($select_list_of_buying_price_result)){
                $Approval_Date_Time=$buying_price_rows['Approval_Date_Time'];
                $Selling_Price=$buying_price_rows['Selling_Price'];
                $Last_Buying_Price=$buying_price_rows['Last_Buying_Price'];
                if($Dispense_Date_Time<$Approval_Date_Time){
                    
                }else{
                    if($Selling_Price==0){
                      return $Last_Buying_Price;
                    }
                    if(isset($_GET['buying_selling_price'])&&$_GET['buying_selling_price']=="original_buying_price"){
                        return $Last_Buying_Price;
                    }else{
                       return $Selling_Price;  
                    }
                }
            }
        }
        return "not_seted";
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
                ->setCellValue('B1','PATIENT NAME')
                ->setCellValue('C1','PATIENT NUMBER')
                ->setCellValue('D1','SPONSOR')
                ->setCellValue('E1','PHONE NUMBER')
                ->setCellValue('F1','DISPENSED DATE & TIME')
                ->setCellValue('G1','QUANTITY')
                ->setCellValue('H1','BUYING PRICE')
                ->setCellValue('I1','TOTAL BUYING PRICE')
                ->setCellValue('J1','SELLING PRICE')
                ->setCellValue('K1','TOTAL SELLING PRICE')
                ->setCellValue('L1','PROFIT / LOSS')
                ->setCellValue('M1','RECEIPT NUMBER');

         $categoryRow=1;
        $i=2;

        $worksheetTitle='';            
                $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
                $sql_select = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.price,Last_Buy_Price,i.Product_Name, pr.Phone_Number,pc.Billing_Type, ts.Guarantor_Name, pr.Registration_ID, ilc.Dispense_Date_Time, pr.Patient_Name, ilc.Patient_Payment_ID, ilc.Quantity, ilc.Edited_Quantity from
                                            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pr.Sponsor_ID = ts.Sponsor_ID and
                                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                            i.Item_ID = ilc.Item_ID and
                                            pr.Registration_ID = pc.Registration_ID and
                                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                            ilc.Status = 'dispensed' and
                                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                            i.Item_ID = '$Item_ID' $filter") or die(mysqli_error($conn));
           

                $num_rows = mysqli_num_rows($sql_select);
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $total_buying_price=0;
                $total_seling_price=0;
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($sql_select)){
                        $price=$row['price'];
//                        $Last_Buy_Price=$row['Last_Buy_Price'];
                    $Dispense_Date_Time = $row['Dispense_Date_Time'];
                    $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
                     if($Last_Buy_Price=="not_seted"){
                        $Last_Buy_Price = $row['Last_Buy_Price']; 
                     }
                        $ispenced_quantity=0;
                         if($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != ''){
                            $ispenced_quantity=$row['Edited_Quantity'];
                        }else{
                            $ispenced_quantity=$row['Quantity'];
                        }
                $quantity=($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != '')?$row['Edited_Quantity']:$row['Quantity'];
                $profit_loss=(($price*$ispenced_quantity)-($Last_Buy_Price*$ispenced_quantity));
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  "".($temp)."")
                    ->setCellValue('B'.$i, ucwords(strtolower($row['Patient_Name'])))
                    ->setCellValue('C'.$i, $row['Registration_ID'])
                    ->setCellValue('D'.$i, $row['Guarantor_Name'])
                    ->setCellValue('E'.$i, $row['Phone_Number'])
                    ->setCellValue('F'.$i, $row['Dispense_Date_Time'])
                    ->setCellValue('G'.$i, $quantity)
                    ->setCellValue('H'.$i, $Last_Buy_Price)
                    ->setCellValue('I'.$i, ($Last_Buy_Price*$ispenced_quantity))
                    ->setCellValue('J'.$i, $price)
                    ->setCellValue('K'.$i, ($price*$ispenced_quantity))
                    ->setCellValue('L'.$i, $profit_loss)
                    ->setCellValue('M'.$i, $row['Patient_Payment_ID']);
    
                $total_buying_price+=$Last_Buy_Price;
                $total_seling_price+=$price;
                $grand_total_buying_price+=($Last_Buy_Price*$ispenced_quantity);
                $grand_total_selling_price+=($price*$ispenced_quantity);
                $grand_total_profit_or_loss+=(($price*$ispenced_quantity)-($Last_Buy_Price*$ispenced_quantity));
                $temp++;
                $categoryRow++;
                $i++;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "GRAND TOTAL")
                ->setCellValue('G'.$i, " ")
                ->setCellValue('H'.$i, $total_buying_price)
                ->setCellValue('I'.$i, $grand_total_buying_price)
                ->setCellValue('J'.$i, $total_seling_price)
                ->setCellValue('K'.$i, $grand_total_selling_price)
                ->setCellValue('L'.$i, $grand_total_profit_or_loss)
                ->setCellValue('M'.$i, " ");
            }

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':'.'G'.$i);
 // Rename worksheet
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle('PATIENT LIST');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$sub_dept.' details list.xls"');
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