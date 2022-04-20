<?php
include("./includes/connection.php");
if(isset($_POST['Start_Date'])){
    $Start_Date=$_POST['Start_Date'];
}
if(isset($_POST['End_Date'])){
    $End_Date=$_POST['End_Date'];
}
if(isset($_POST['Sponsor_ID'])){
    $Sponsor_ID=$_POST['Sponsor_ID'];
}
if(isset($_POST['Billing_Type'])){
    $Billing_Type=$_POST['Billing_Type'];
}
if(isset($_POST['Patient_Name'])){
    $Patient_Name=$_POST['Patient_Name'];
}
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}
if(isset($_POST['Patient_Type'])){
    $Patient_Type=$_POST['Patient_Type'];
}
if(isset($_POST['Billing_Type'])){
    $Billing_Type=$_POST['Billing_Type'];
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


//select all patient at a specific time range
echo "<table class='table'><tr><td width='50px'>S/No.</td><td width='15%'><b>Patient Name</b></td><td><b>Attendance Date</b></td></tr>";
$sql_select_patient_at_a_specific_time_range_result=mysqli_query($conn,"SELECT Patient_Name,Registration_ID FROM tbl_patient_registration WHERE Sponsor_ID='$Sponsor_ID' AND Registration_ID IN(SELECT Registration_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) AND Registration_ID LIKE '%$Registration_ID%' AND Patient_Name LIKE '%$Patient_Name%' GROUP BY Registration_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_patient_at_a_specific_time_range_result)>0){
    $count_sn=1;

    while($patient_rows=mysqli_fetch_assoc($sql_select_patient_at_a_specific_time_range_result)){
        $grand_total_per_patient=0;
        $Patient_Name=$patient_rows['Patient_Name'];
        $Registration_ID=$patient_rows['Registration_ID'];

        echo "<tr><td>$count_sn</td><td>$Patient_Name</td><td>";
               $sql_select_attendance_date_result=mysqli_query($conn,"SELECT Visit_Date,Check_In_ID FROM tbl_check_in WHERE Visit_Date BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
               if(mysqli_num_rows($sql_select_attendance_date_result)>0){

                   echo "<table class='table'><tr style='background:#dedede'><td><b>Attendance Date</b></td>";
                   $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));

                               if(mysqli_num_rows($select_item_sub_category_result)>0){
                                  while($sub_c_rows=mysqli_fetch_assoc($select_item_sub_category_result)){
                                     $Item_Subcategory_Name=$sub_c_rows['Item_Subcategory_Name'];
                                     $Item_Subcategory_ID=$sub_c_rows['Item_Subcategory_ID'];
                                     echo "<td><b>$Item_Subcategory_Name</b></td>";
                                  }
                               }
                   echo "<td>TOTAL</td></tr>";


                   while($attendance_rows=mysqli_fetch_assoc($sql_select_attendance_date_result)){
                       $Visit_Date=$attendance_rows['Visit_Date'];
                       $Check_In_ID=$attendance_rows['Check_In_ID'];
                       $sub_total_per_date=0;
                       echo "<tr><td>$Visit_Date</td>";
                       ///////////////////////////////money calculation
                       $colspan_value=1;
                               $select_item_sub_category_result=mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID  WHERE Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE DATE(Transaction_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date') AND Patient_Payment_ID IN (SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND DATE(Payment_Date_And_Time) BETWEEN DATE('$Start_Date') AND DATE('$End_Date')) GROUP BY Item_ID) GROUP BY its.Item_Subcategory_ID ASC") or die(mysqli_error($conn));
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
//                        ///////////////////////////////////////////////////////////////
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
                                    echo "<td><a href='#' onclick='preview_category_items(\"$Item_Subcategory_ID\",\"$Check_In_ID\");'>".number_format($total_amount)."</a></td>";
                                     ///////////////////////////////////end calculation
                                    $colspan_value++;
                                  }

                               }
                       ///////////////////////////////////////////////////
                               $grand_total_per_patient +=$sub_total_per_date;
                               echo "<td>".number_format($sub_total_per_date)."</td>";
                       echo "</tr>";

                   } echo "<tr><td colspan='$colspan_value'><b>GRAND TOTAL</b></td><td>". number_format($grand_total_per_patient)."</td></tr>";
                  echo "</table>";
               }
               echo  "</td></tr>";

        $count_sn++;

    }
}
echo "</table>";
