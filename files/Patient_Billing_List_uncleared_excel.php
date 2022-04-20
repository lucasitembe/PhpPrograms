<?php
session_start();
include("./includes/connection.php");
$filter = "";
if (isset($_GET['Patient_Name'])) {
    $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
} else {
    $Patient_Name = '';
}

if (isset($_GET['Patient_Number'])) {
    $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);
} else {
    $Patient_Number = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = 0;
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = mysqli_real_escape_string($conn,$_GET['Hospital_Ward_ID']);
} else {
    $Hospital_Ward_ID = 0;
}

if (isset($_GET['patient_type'])) {
    $patient_type = mysqli_real_escape_string($conn,$_GET['patient_type']);
}

if($Patient_Name !='' ){
   $filter .=" AND Patient_Name='$Patient_Name'"; 
}

if($Patient_Number !='' ){
    $filter .=" AND pr.Registration_ID='$Patient_Number'"; 
}
if($Hospital_Ward_ID !='0' ){
    $filter .=" AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'"; 
}

if($Sponsor_ID !='0' ){
    $filter .=" AND pr.Sponsor_ID='$Sponsor_ID'"; 
}

if(!empty($patient_type) && $patient_type !='All' ){
    $filter .=" AND ad.Admission_Status='$patient_type'"; 
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}





$htm = "<table width ='100%' height = '30px'>
<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
<tr><td style='text-align: center;'><b><span style='font-size: x-small;'><u><h4> UNCLEARED BILLS PATIENT  LIST. </h4></u></span></b></td></tr></br>
<tr><td style='text-align: center;'><b><span style='font-size: x-small;'></span></b></td></tr>
</table>";
$htm.= "<table width ='100%' ><thead>";
        
        
        $htm .= ' <tr>
                    <td width="3%"><b>SN</b></td>
                    <td width="9%"><b>PATIENT NAME</b></td>
                    <td width="6%"><b>REG #:</b></td>
                    <td width="9%"><b>SPONSOR NAME</b></td>
                    <td width="6%"><b>AGE</b></td>
                    <td width="6%"><b>GENDER</b></td>
                    <td width="8%"><b>PHONE NUMBER</b></td>
                    <td width="12%"><b>WARD/ROOM/BED</b></td>
                    <td width="9%"><b>ADMISSION TIME</b></td>
                    <td width="9%"><b>PENDING SET TIME</b></td>
                    <td width="9%"><b>PENDING SETTER</b></td>
                    <td width="6%"><b>DAYS SPENT IN HOSP</b></td>
                    <td width="6%"><b>TIME SINCE DISCHARGE</b></td>
                    <td width="6%"><b>AMOUNT REQUIRED</b></td>
                    <td width="6%"><b>AMOUNT PAID</b></td>
                    <td width="6%"> <b>BALANCE</b></td>
        </tr></thead>
        ';
       $temp = 0;
       $selectpatient = mysqli_query($conn,"SELECT discharge_condition,pending_set_time,pending_setter, ad.Doctor_Status,payment_method,pr.Phone_Number,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name,room_name, Bed_Name,Admission_Date_Time, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name,Discharge_Reason, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,pending_set_time) AS NoOfDay, TIMESTAMPDIFF(DAY,pending_set_time,  CURDATE()) AS timepassed  from tbl_admission ad,tbl_ward_rooms wr, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw, tbl_discharge_reason dr where    cd.Admission_ID = ad.Admision_ID and  wr.ward_room_id=ad.ward_room_id AND   pr.Sponsor_ID = sp.Sponsor_ID and  pr.Registration_ID = ad.Registration_ID and   hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and  pr.Registration_ID=cd.Registration_ID  AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID and   (ad.Discharge_Clearance_Status = 'not cleared' or ad.Discharge_Clearance_Status='pending') $filter GROUP BY Admission_ID limit 200 ") or die(mysqli_error($conn));  
       // $num = mysqli_num_rows($select);
        if (mysqli_num_rows($selectpatient) > 0) {
            while ($row = mysqli_fetch_assoc($selectpatient)) {
                //calculate age
                $timepassed = $row['timepassed'];
                $Bed_Name = $row['Bed_Name'];
                $Admission_Date_Time =$row['Admission_Date_Time'];
                $room_name = $row['room_name'];
                $pending_setter = $row['pending_setter'];
                $pending_set_time = $row['pending_set_time'];
                $Gender = $row['Gender'];
                $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $pending_setter = $row['pending_setter'];
                $pending_set_time = $row['pending_set_time'];
                $Registration_ID = $row['Registration_ID'];
                $Phone_Number = $row['Phone_Number'];
                $payment_method = $row['payment_method'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";
                $NoOfDay = $row['NoOfDay'];
                $Check_In_ID=$row['Check_In_ID'];
                $Discharge_Reason_ID=$row['Discharge_Reason_ID'];
                $Doctor_Status=$row['Doctor_Status'];
                $Admission_Status=$row['Admission_Status'];
                $discharge_condition =$row['discharge_condition'];
              
                if($discharge_condition=="alive"){
                    $back_color="green;color:#FFFFFF";
                }else if($discharge_condition=="dead"){
                    $back_color="red;color:#FFFFFF"; 
                } elseif ($discharge_condition=="absconde") {
                    $back_color="black;color:#FFFFFF";  
                }
                if($Doctor_Status=="initial_pending" && $Admission_Status !='pending'){
                    $back_color="greenyellow;color:#000000;font-size:14px"; 
                 }
                
                 $PendingSeter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$pending_setter'"))['Employee_Name'];
            $select = mysqli_query($conn,"SELECT Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' and Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if ($num > 0) {
                while ($data = mysqli_fetch_array($select)) {
                    $Patient_Bill_ID = $data['Patient_Bill_ID'];
                }
            }    
            $jumla_yagharama_za_hospitali = 0;
            $Grand_Total=0;
            $Total_required = 0; 
                if (strtolower($payment_method) == 'cash') {
                    
                    $items = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from    tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where   ic.Item_Category_ID = isc.Item_Category_ID and    isc.Item_Subcategory_ID = i.Item_Subcategory_ID and i.Item_ID = ppl.Item_ID and  pp.Transaction_type = 'indirect cash' and     pp.Billing_Type IN ('Inpatient Cash','Outpatient Cash')  and pp.Pre_Paid IN ('1', '0') and pp.payment_type='post' AND  pp.Transaction_status <> 'cancelled' and   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID'  and pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                } else {
                    $items = mysqli_query($conn,"SELECT ppl.Hospital_Ward_ID,pp.Sponsor_ID,pp.Billing_Type,ppl.Consultant,i.Product_Name,ppl.Transaction_Date_And_Time,ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
                                tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
                                ic.Item_Category_ID = isc.Item_Category_ID and
                                isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                i.Item_ID = ppl.Item_ID and
                                pp.Transaction_type = 'indirect cash' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and pp.Pre_Paid IN ('1', '0') AND   pp.Billing_Type IN ('Outpatient Credit',  'Inpatient Credit') and pp.payment_type='post' AND   pp.Patient_Bill_ID = '$Patient_Bill_ID'  and
                                 
                                pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                } 
    
                        if (mysqli_num_rows($items) > 0) {
                            
                            while ($dt = mysqli_fetch_array($items)) {                       
                            
                                $Total_required += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                            }
                        
                        }
    
                       
                        $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where     pp.Transaction_type = 'direct cash' AND   pp.Transaction_status <> 'cancelled' and   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID' and ppl.Item_ID=i.Item_ID  and   pp.Registration_ID = '$Registration_ID' AND Visible_Status='Others' ") or die(mysqli_error($conn)); 
                        $nms = mysqli_num_rows($cal);
                        if ($nms > 0) {
                            while ($cls = mysqli_fetch_array($cal)) {
                                $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                            }
                        }
                        $jumla_yagharama_za_hospitali = $Total_required - $Grand_Total;
            if($jumla_yagharama_za_hospitali > 0){
                $htm.= "
                <tr id='thead'><td >".++$temp ."</td>
                    <td>".ucwords(strtolower($row['Patient_Name']))."</td>
                    <td> $Registration_ID</td>
                    <td> $Guarantor_Name</td>
                    <td> $age</td>
                    <td> $Gender</td>
                    <td> $Phone_Number</td>
                    <td> $Hospital_Ward_Name</td>
                    <td> $Admission_Date_Time</td>
                    <td> $pending_set_time</td>
                    <td> $PendingSeter</td>
                    <td> $NoOfDay</td>
                    <td> $timepassed</td>
                    <td>". number_format($Total_required)."</td> 
                    <td>". number_format($Grand_Total)."</td> 
                    <td>". number_format($jumla_yagharama_za_hospitali)."</td>           
                </tr>";
                $Totol_amount += $jumla_yagharama_za_hospitali; 
                $TTYrequired +=$Total_required;
                $TGrand_Total +=$Grand_Total;
                }
            }
            $htm.= "<tr id='thead'>
            <th colspan='13'>TOTAL</th><th>".number_format($TTYrequired)."/=</th>
            <th>".number_format($TGrand_Total)."/=</th>
            <th>".number_format($Totol_amount)."/=</th>
            </tr>";
        }else{
            $htm.= "<tr><td colspan='12' style='color:red;'>No result Found</td></tr>";
        }
        $htm.= "</table>";

            
    //SIMPLE EXCELL FUNCTION STARTS HERE 
    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename= medicalreportexcel.xls");
    echo $htm;