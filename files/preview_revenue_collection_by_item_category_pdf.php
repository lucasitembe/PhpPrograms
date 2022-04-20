<?php
@session_start();
include("./includes/connection.php");
    $end_date = $_GET['end_date'];
    $start_date = $_GET['start_date']; 
    
    $fileter = '';
if(isset($_GET['Sponsor_ID']) && !empty($_POST['Sponsor_ID'])){
   $Sponsor_ID=$_GET['Sponsor_ID']; 
   $fileter = " AND pp.Sponsor_ID = '$Sponsor_ID'";
}else{
   $Sponsor_ID=""; 
}
    $htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>REVENUE COLLECTION BY ITEM CATEGORY REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.date("d-m-Y h:i:s", strtotime($start_date)).'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.date("d-m-Y h:i:s", strtotime($end_date)).'</b></td></tr>
            </table>';
    
    
    
    	$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
				<thead>
			    <tr>
			        <td width="3%"><b>SN</b></td>
			        <td width="25%"><b>ITEM CATEGORY NAME</b></td>
                    <td width="12%" style="text-align: left;"><b>NO OF SERVICE</b></td>
                    <td width="12%" style="text-align: left;"><b>NO OF PATIENT</b></td>
			        <td width="12%" style="text-align: right;"><b>CASH</b></td>
			        <td width="12%" style="text-align: right;"><b>CREDIT</b></td>
			        <td width="12%" style="text-align: right;"><b>MSAMAHA</b></td>
			        <td width="12%" style="text-align: right;"><b>TOTAL</b></td>
			    </tr></thead>';
        
               $grand_total_cash=0;
                        $grand_total_credit=0;
                        $grand_total_msamaha=0;
                        //Lab subcategory
                                 $query_sub_cat = mysqli_query($conn,"SELECT ic.Item_Category_Name,ic.Item_Category_ID FROM  tbl_item_category ic ORDER BY ic.Item_Category_Name ASC") or die(mysqli_error($conn));
//                                 $query_sub_cat = mysqli_query($conn,"SELECT ic.Item_Category_Name,ic.Item_Category_ID FROM `tbl_items` i, tbl_item_subcategory its, tbl_item_category ic  WHERE its.Item_Subcategory_ID=i.Item_Subcategory_ID AND ic.Item_Category_ID=its.Item_Category_ID  AND Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date') GROUP BY its.Item_Category_ID ORDER BY ic.Item_Category_Name ASC") or die(mysqli_error($conn));
                                 $count_sn=1;
                                if(mysqli_num_rows($query_sub_cat)>0){
                                   while ($row = mysqli_fetch_array($query_sub_cat)) {
                                     $Item_Category_ID=$row['Item_Category_ID'];
                                     $Item_Category_Name=$row['Item_Category_Name'];
                                
                               
                                $ubtotal_cash=0;
                                $ubtotal_credit=0;
                                $subtotal_msamaha=0;
                                $number_of_service=0;
                                $number_of_patient=array();
                            ////calculate amount from each unit
                            //select all items
//                                   $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,pp.Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_items i,tbl_item_subcategory its,tbl_item_category ic WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND i.Item_ID=ppl.Item_ID AND its.Item_Subcategory_ID=i.Item_Subcategory_ID AND ic.Item_Category_ID=its.Item_Category_ID  AND ic.Item_Category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
                                  $sql_select_all_items_with_price_result_item_id=mysqli_query($conn,"SELECT i.Item_ID FROM tbl_items i,tbl_item_subcategory its,tbl_item_category ic WHERE  its.Item_Subcategory_ID=i.Item_Subcategory_ID AND ic.Item_Category_ID=its.Item_Category_ID  AND ic.Item_Category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
                               if(mysqli_num_rows($sql_select_all_items_with_price_result_item_id)>0){
                                 while($item_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result_item_id)){
                                  $Item_ID= $item_rows['Item_ID'];
                                  $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT pp.Registration_ID,ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,pp.Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.Item_ID='$Item_ID' $fileter") or die(mysqli_error($conn));  
                                   if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){

                                       while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                                          $payment_type=$items_rows['payment_type'];
                                          $Billing_Type=$items_rows['Billing_Type'];
                                          $Pre_Paid=$items_rows['Pre_Paid'];
                                          $Sponsor_ID=$items_rows['Sponsor_ID'];
                                          $Discount=$items_rows['Discount'];
                                          $Price=$items_rows['Price'];
                                          $Quantity=$items_rows['Quantity'];
                                          $Registration_ID=$items_rows['Registration_ID'];
                                          if(!in_array($Registration_ID, $number_of_patient)){
                                              array_push($number_of_patient, $Registration_ID);
                                          }
                                          
//                                           $number_of_service++;
                                           $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                                           $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
                        ///////////////////////////////////////////////////////////////


                                            if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
                                                    $subtotal_msamaha += ($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                            }  else {

                                                if(((strtolower($Billing_Type) == 'outpatient cash'|| strtolower($Billing_Type)=="patient from outside") && $Pre_Paid == '0') or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                    $ubtotal_cash+=($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                                }elseif((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit')){
                                                    $ubtotal_credit += ($Quantity*($Price-$Discount));
                                                    $number_of_service++;
                                                }
                                            }
                                       ///////////////////////////////////////////////////////////////
                                       }
                                 }
                               }
                                                }
                                    if($subtotal_msamaha==0&&$ubtotal_cash==0&&$ubtotal_credit==0){
                                      //continue;
                                    }
                                    $grand_total_cash+=$ubtotal_cash;
                                    $grand_total_credit+=$ubtotal_credit;
                                    $grand_total_msamaha+=$subtotal_msamaha;
                                    
                                    
                               $htm .= "<tr>
                                        <td>$count_sn</td>
                                        <td class='department_row' >".strtoupper($Item_Category_Name)."</b></td>
                                        <td>".number_format($number_of_service)."</td>
                                        <td>".number_format(sizeof($number_of_patient))."</td>
                                        <td>".number_format($ubtotal_cash)."</td>
                                        <td>".number_format($ubtotal_credit)."</td>
                                        <td>".number_format($subtotal_msamaha)."</td>
                                        <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                                    </tr>";
                               $count_sn++;
                            }
                        }
            
         $htm .= '<tr>
                <td colspan="2"><b>GRAND TOTAL</b></td>
                <td><b>'. number_format($grand_total_no_of_service).'</b></td>
                <td><b>'. number_format($grand_total_no_of_patient).'</b></td>
                <td><b>'. number_format($grand_total_cash).'</b></td>
                <td><b>'. number_format($grand_total_credit).'</b></td>
                <td><b>'. number_format($grand_total_msamaha).'</b></td>
                <td><b>'. number_format($grand_total_cash+$grand_total_credit).'</b></td>
            </tr>';
    
    
    
    $htm .= "</table>";
	include("./MPDF/mpdf.php");
        $Employee_Name=$_SESSION['userinfo']['Employee_Name'];
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;