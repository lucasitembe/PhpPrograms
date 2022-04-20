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

    $htm = "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
        <tr><td></td></tr>
		<tr>
		    <td style='text-align: center;'><b>REVENUE COLLECTION BY PATIENT REPORT</b></td>
		</tr></table>";
	$htm .= "<table width='100%'>
				<tr><td><span style='font-size: x-small;'><b>SPONSOR NAME ~ </b>".strtoupper($Guarantor_Name)."</span></td>
                                <td><span style='font-size: x-small;'><b>PATIENT TYPE ~ </b>".strtoupper($Patient_Type)."</span></td></tr>
				<tr><td><span style='font-size: x-small;'><b>START DATE ~ </b>".$Start_Date."</span></td>"
                . "<td><span style='font-size: x-small;'><b>BILL TYPE ~ </b>".strtoupper($Billing_Type)."</span></td></tr>
				<tr><td><span style='font-size: x-small;'><b>END DATE ~ </b>".$End_Date."</span></td></tr>
			</table>";
////////////////////////////////////////////////////////////////////////////////////////////////////

        //select all patient at a specific time range
$htm .="<table border='1' width='100%' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10><tr><td width='50px'>S/No.</td><td width='15%'><b>Patient Name</b></td><td><b>Attendance Date $Registration_ID $Patient_Name</b></td></tr>";
$sql_select_patient_at_a_specific_time_range_result=mysqli_query($conn,"SELECT Patient_Name,Registration_ID FROM tbl_patient_registration WHERE Sponsor_ID='$Sponsor_ID' AND Registration_ID IN(SELECT Registration_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) AND Registration_ID LIKE '%$Registration_ID%' AND Patient_Name LIKE '%$Patient_Name%' GROUP BY Registration_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_patient_at_a_specific_time_range_result)>0){
    $count_sn=1;
    
    while($patient_rows=mysqli_fetch_assoc($sql_select_patient_at_a_specific_time_range_result)){
        $grand_total_per_patient=0;
        $Patient_Name=$patient_rows['Patient_Name'];
        $Registration_ID=$patient_rows['Registration_ID'];
        
        $htm .="<tr><td>$count_sn</td><td>$Patient_Name</td><td>";
               $sql_select_attendance_date_result=mysqli_query($conn,"SELECT Visit_Date,Check_In_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_attendance_date_result)>0){
                   
                   $htm .="<table border='1' width='100%' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10><tr style='background:#dedede'><td><b>Attendance Date</b></td>";
//                   $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
                    $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i, tbl_item_subcategory its,tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE its.Item_Subcategory_ID=i.Item_Subcategory_ID AND i.Item_ID=ppl.Item_ID AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));

                               if(mysqli_num_rows($select_item_sub_category_result)>0){
                                  while($sub_c_rows=mysqli_fetch_assoc($select_item_sub_category_result)){
                                     $Item_Subcategory_Name=$sub_c_rows['Item_Subcategory_Name'];
                                     $Item_Subcategory_ID=$sub_c_rows['Item_Subcategory_ID'];
                                     $htm .="<td><b>$Item_Subcategory_Name</b></td>";
                                  } 
                               }
                   $htm .="<td>TOTAL</td></tr>";
                   
                   
                   while($attendance_rows=mysqli_fetch_assoc($sql_select_attendance_date_result)){
                       $Visit_Date=$attendance_rows['Visit_Date'];
                       $Check_In_ID=$attendance_rows['Check_In_ID'];
                       $sub_total_per_date=0;
                       $htm .="<tr><td>$Visit_Date</td>";
                       ///////////////////////////////money calculation
                       $colspan_value=1;
//                               $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
                                $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i, tbl_item_subcategory its,tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE its.Item_Subcategory_ID=i.Item_Subcategory_ID AND i.Item_ID=ppl.Item_ID AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
                               if(mysqli_num_rows($select_item_sub_category_result)>0){
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
                                    $htm .="<td>".number_format($total_amount)."</td>";
                                     ///////////////////////////////////end calculation
                                    $colspan_value++;
                                  } 
                                  
                               }
                       ///////////////////////////////////////////////////
                               $grand_total_per_patient +=$sub_total_per_date;
                               $htm .="<td>".number_format($sub_total_per_date)."</td>";
                       $htm .="</tr>";
                       
                   } $htm .="<tr><td colspan='$colspan_value'><b>GRAND TOTAL</b></td><td>". number_format($grand_total_per_patient)."</td></tr>";
                  $htm .="</table>";
               }
               $htm .= "</td></tr>";
        
        $count_sn++;
        
    }
}
$htm .="</table>";
        
        
////////////////////////////////////////////////////////////////////////////////////////////////////

        
include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();

//	$mpdf->SetDisplayMode('fullpage');
//	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
//	// LOAD a stylesheet
//	$stylesheet = file_get_contents('patient_file.css');
//	$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
//	$mpdf->WriteHTML($data, 2);
//
//	$mpdf->WriteHTML($htm);
//	$mpdf->Output();
?>