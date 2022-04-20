<?php
    session_start();
    include("./includes/connection.php");
    $filter = '';
    if(isset($_GET['Start_Date'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = '';
    }
    
    if(isset($_GET['End_Date'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = '';
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_GET['sponsorID']) && strtolower($_GET['sponsorID'])!='all'){
        $sponsorID = $_GET['sponsorID'];
        //filter
        //$filter .= ' and ';
    }  else {
        $sponsorID = null;
    }
    //////////////////////////////////////////////////////////
    
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
    
    //////////////////////////////////////////////////////////
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($data = mysqli_fetch_array($select)){
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
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
                ->setCellValue('B1','ITEM CODE')
                ->setCellValue('C1','ITEM NAME')
                ->setCellValue('D1','QUANTITY DISPENSED')
                ->setCellValue('E1','BALANCE')
                ->setCellValue('F1','TOTAL BUYING PRICE')
                ->setCellValue('G1','TOTAL SELLING PRICE')
                ->setCellValue('H1','PROFIT/LOSS')
                ->setCellValue('I1','TOTAL STOCK VALUE');

        $categoryRow=1;
        $i=2;

        $temp = 1;

        $worksheetTitle='';

    if(isset($_GET['Search_Value'])){
        /*echo '<table width=100%>';*/
        //echo $Title;
                $temp = 1; $total_items = 0;
                if(isset($_SESSION['Pharmacy'])){
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                if(isset($sponsorID) && $sponsorID!='All'){
                    //filter by sponsor
                    $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and i.Product_Name like '%$Search_Value%' and  ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));

                   //$rows[] = mysqli_fetch_assoc($result);
                   //echo '</pre>';
                   //print_r($rows);
                   //exit; 
                  
                } else {
                $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                }
//                
//                echo '<pre>';
//                print_r($result);exit;
                //$num = mysqli_num_rows($result); echo $num; 
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $grand_total_total_stock_value=0;
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
//                  $Last_Buy_Price = $row['Last_Buy_Price'];
                    
                    if(isset($sponsorID) && $sponsorID!='All'){
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    } else {
                    $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    }
                    $total_selling_price=0;
                    $total_buying_price=0;
                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        $Price = $row2['Price'];
                        
                        $Dispense_Date_Time = $row2['Dispense_Date_Time'];
                        $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
                         if($Last_Buy_Price=="not_seted"){
                            $Last_Buy_Price = $row2['Last_Buy_Price']; 
                         }

                        
                        $dispenced_quantity=0;
                        if($Edited_Quantity != 0){
                            $total_items = $total_items + $Edited_Quantity;
                            $dispenced_quantity=$Edited_Quantity;
                        }else{
                            $total_items = $total_items + $Quantity;
                            $dispenced_quantity=$Quantity;
                        }
                        $total_buying_price+=($Last_Buy_Price*$dispenced_quantity); 
                        $total_selling_price+=($Price*$dispenced_quantity);
                    }
                    if($total_items<=0){ //if dsipensed quantity is zero then dont display that item
                        continue;
                    }
                    $sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $num_balance = mysqli_num_rows($sql_balance);
                    if($num_balance > 0){
                        while($sd = mysqli_fetch_array($sql_balance)){
                            $Item_Balance = $sd['Item_Balance'];
                        }
                    }else{
                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                        $Item_Balance = 0;
                    }
                    if($Item_Balance < 0){ $Item_Balance = 0; }

               
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  "".($temp)."")
                    ->setCellValue('B'.$i, $row['Product_Code'])
                    ->setCellValue('C'.$i, $Product_Name)
                    ->setCellValue('D'.$i, floor($total_items))
                    ->setCellValue('E'.$i, floor($Item_Balance))
                    ->setCellValue('F'.$i, floor($total_buying_price))
                    ->setCellValue('G'.$i, floor($total_selling_price))
                    ->setCellValue('H'.$i, floor(($total_selling_price-$total_buying_price)))
                    ->setCellValue('I'.$i, floor(($Item_Balance*$total_buying_price)));
    

                    $grand_total_buying_price+=($total_buying_price);
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_profit_or_loss+=($total_selling_price-$total_buying_price);
                    $grand_total_total_stock_value+=($Item_Balance*$total_buying_price);
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                    /*if(($temp%31) == 0){
                        echo $Title;
                    }*/
                    $categoryRow++;
                    $i++;
                    
                }
                
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  " ")
                    ->setCellValue('B'.$i, " ")
                    ->setCellValue('C'.$i," ")
                    ->setCellValue('D'.$i, " ")
                    ->setCellValue('E'.$i, "GRAND TOTAL")
                    ->setCellValue('F'.$i, floor($grand_total_buying_price))
                    ->setCellValue('G'.$i, floor($grand_total_selling_price))
                    ->setCellValue('H'.$i, floor($grand_total_profit_or_loss))
                    ->setCellValue('I'.$i, floor($grand_total_profit_or_loss));
    
       // echo '</table>';
    }else{
        /*echo '<table width=100%>';*/
        //echo $Title;
                $temp = 1; $total_items = 0;
                if(isset($_SESSION['Pharmacy'])){
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                if(isset($sponsorID) && $sponsorID!='All'){
                    //filter by sponsor
                    $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));

                   //$rows[] = mysqli_fetch_assoc($result);
                   //echo '</pre>';
                   //print_r($rows);
                   //exit; 
                  
                } else {
                $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                }
                //$num = mysqli_num_rows($result); echo $num; 
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $grand_total_total_stock_value=0;
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
//                    $Last_Buy_Price = $row['Last_Buy_Price'];

//                    $Dispense_Date_Time = $row['Dispense_Date_Time'];
//                    $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
//                    if($Last_Buy_Price=="not_seted"){
//                        $Last_Buy_Price = $row['Last_Buy_Price']; 
//                     }
                    //select i.Item_ID, i.Product_Name From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID'
                    if(isset($sponsorID) && $sponsorID!='All'){
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    } else {
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    }
                    
                    $total_selling_price=0;
                    $total_buying_price=0;
                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        $Price = $row2['Price'];
                        
                         $Dispense_Date_Time = $row2['Dispense_Date_Time'];
                        $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
                         if($Last_Buy_Price=="not_seted"){
                            $Last_Buy_Price = @$row2['Last_Buy_Price']; 
                         } 
                         
                        $dispenced_quantity=0;
                        if($Edited_Quantity != 0){
                            $total_items = $total_items + $Edited_Quantity;
                            $dispenced_quantity=$Edited_Quantity;
                        }else{
                            $total_items = $total_items + $Quantity;
                            $dispenced_quantity=$Quantity;
                        }
                        $total_buying_price+=($Last_Buy_Price*$dispenced_quantity); 
                        $total_selling_price+=($Price*$dispenced_quantity);
                    }
                    if($total_items<=0){ //if dsipensed quantity is zero then dont display that item
                        continue;
                    }
                    $sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn));
                    $num_balance = mysqli_num_rows($sql_balance);
                    if($num_balance > 0){
                        while($sd = mysqli_fetch_array($sql_balance)){
                            $Item_Balance = $sd['Item_Balance'];
                        }
                    }else{
                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                        $Item_Balance = 0;
                    }
                    if($Item_Balance < 0){ $Item_Balance = 0; }
                
                     $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  "".($temp)."")
                    ->setCellValue('B'.$i, $row['Product_Code'])
                    ->setCellValue('C'.$i, $Product_Name)
                    ->setCellValue('D'.$i, $total_items)
                    ->setCellValue('E'.$i, floor($Item_Balance))
                    ->setCellValue('F'.$i, floor($total_buying_price))
                    ->setCellValue('G'.$i, floor($total_selling_price))
                    ->setCellValue('H'.$i, floor(($total_selling_price-$total_buying_price)))
                    ->setCellValue('I'.$i, floor(($Item_Balance*$total_buying_price)));
    
                    $grand_total_buying_price+=($total_buying_price);
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_profit_or_loss+=($total_selling_price-$total_buying_price);
                    $grand_total_total_stock_value+=($Item_Balance*$total_buying_price);
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                    /*if(($temp%31) == 0){
                        echo $Title;
                    }*/
                    $categoryRow++;
                    $i++;
                }
           
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, "GRAND TOTAL")
                    ->setCellValue('F'.$i, floor($grand_total_buying_price))
                    ->setCellValue('G'.$i, floor($grand_total_selling_price))
                    ->setCellValue('H'.$i, floor($grand_total_profit_or_loss))
                    ->setCellValue('I'.$i, floor($grand_total_total_stock_value));
    
    }

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':'.'C'.$i);
 // Rename worksheet
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle('DISPENSED REPORT');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$Sub_Department_Name.' excel report.xls"');
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