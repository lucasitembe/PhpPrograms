<?php
    include("./includes/connection.php");
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }
    if(isset($_GET['Billtype'])){
        $Billtype = $_GET['Billtype'];
    }
    $filter='';
    if($Sponsor_ID != 'All'){
        $filter = " AND pp.Sponsor_ID='$Sponsor_ID'";
    }
    if($Billtype != 'All'){
        $filter .=" AND pp.Billing_Type lIKE '%$Billtype%'";
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID='';
    }
    $filterno='';
    if($Registration_ID != ''){
        $filterno = " AND pp.Registration_ID='$Registration_ID'";
    }
    $i=1;
    $htm = "<table width ='100%' height = '30px'>
        <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td style='text-align: center;'><b><span style='font-size: x-small;'><u>RECEPTION DAILY REPORT.</u></span></b></td></tr></br>
        <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($start_date))." TO ".@date("d F Y H:i:s",strtotime($end_date))."</span></b></td></tr>
    </table>";
    $htm.="<style>
    #theadtotal{
        font-size:20px;
    }
    </style>";
    $num=0;
// <td id='thead' ><b>DATE</b></td>
//  <td id='thead' ><b>SEX</b></td>
        // <td id='thead' ><b>AGE</b></td>
    $htm.= "<table width='100%' class='table-hover'> <thead>
    <tr>
        <td id='thead' ><b>SN</b></td>
        
        <td id='thead' ><b>PATIENT NAME (REG:#)</b></td>
       
        <td id='thead' ><b>SPONSOR</b></td>
        <td id='thead' ><b>DOCTOR'S NAME</b></td>
        <td id='thead' ><b>CONSULTATION</b></td>
        <td id='thead' ><b>RADIOLOGY</b></td>
        <th id='thead' >LABORATORY</th>
        <th id='thead' >PHARMACY</th>
        <th id='thead' >PROCEDURE</th>
        <th id='thead' >SURGERY</th>        
        <th id='thead' >OTHERS</th>
        <th id='thead' >DEPOSIT</th>
        <th id='thead' >BILL</th>
        <th id='thead' > CASH</th>
        <th id='thead' > CREDIT</th>
        <th id='thead' >DISCOUNT</th>
    </tr>
</thead>
<tbody id='discountbody'>";
$htm.= "<tr><td colspan='16' style='background:green; color:white; text-align:center;'>INPATIENT RESULT</td></tr>";
    $select_patient_details = mysqli_query($conn, "SELECT Patient_Payment_ID,Exemption, Check_In_ID ,pp.Pre_Paid,Transaction_type, pp.Registration_ID,payment_type, CAST(Payment_Date_And_Time AS DATE) AS  Payment_Date_And_Time,payment_method,sp.Guarantor_Name, pr.Patient_Name,pr.Gender, pr.Date_Of_Birth FROM tbl_patient_registration pr, tbl_sponsor sp, tbl_patient_payments pp WHERE Billing_Type IN ('Inpatient Cash', 'Inpatient Credit') AND  pr.Sponsor_ID = sp.Sponsor_ID and pp.Registration_ID = pr.Registration_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter  $filterno GROUP BY Patient_Name ORDER BY pp.Payment_Date_And_Time ASC") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_patient_details)>0){
        while($row = mysqli_fetch_assoc($select_patient_details)){
            $patient_name = $row['Patient_Name'];
            $gender = $row['Gender'];            
            $Sponsor_ID = $row['Sponsor_ID'];
            $payment_type = $row['payment_type'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Exemption = $row['Exemption'];
            $dob = $row['Date_Of_Birth'];
            $Check_In_ID = $row['Check_In_ID'];
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            $Transaction_type  = $row['Transaction_type'];
            $Pre_Paid = $row['Pre_Paid'];
            $then = $dob;
            $then = new DateTime($dob);
            $now = new DateTime();
            $sinceThen = $then->diff($now);
            $dob = $sinceThen->y.' Y '. $sinceThen->m.' M '. $sinceThen->d.' D';
            $Grand_Total=0;
            $Consultation =0;
            $total_Radiology=0;
            $total_lab_test =0;
            $total_dawa=0;
            $total_Kulala=0;  
            $total_Procedure=0;  
            $Total_Cash = 0;
            $Total_Credit=0;
            $Total_Discount=0;
            $jumla_yagharama_za_hospitali=0;
            $subtotal_credit = 0;
            $subtotal_cash=0;
            $total_Others=0;
            $total_upasuaji=0;
            $subtotal_cash_credit=0;
            $num++;
            // <td  >".$gender."</td><td>$Payment_Date_And_Time</td>
                    // <td  style='width:5%;box-sizing:border-box; '>".$dob."</td>
            $htm.= "<tr>
                    <td  style='width:3%;box-sizing:border-box;'>".$num."</td>
                    
                    <td  style='width:15%;box-sizing:border-box; '><h6>".ucfirst($patient_name)." ($Registration_ID)</h6></td>
                    
                    <td  >".($Guarantor_Name)."</td>";
                    
            $sql_payments_amaount = mysqli_query($conn, "SELECT  pp.Patient_Bill_ID, Visible_Status,Check_In_Type,pp.Registration_ID,ptl.Patient_Payment_ID,Patient_Payment_Item_List_ID, pp.Transaction_type,pp.Billing_Type, sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount, SUM(ptl.Discount) as Discounts, Consultant from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp, tbl_items i WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Billing_Type IN ('Inpatient Credit', 'Inpatient Cash')  and ptl.Patient_Payment_ID = pp.Patient_Payment_ID and ptl.Item_ID = i.Item_ID $filter   AND pp.Transaction_status <> 'cancelled' GROUP BY ptl.Check_In_Type ") or die(mysqli_error($conn));
            
            $Employee_Names='Not consulted';
    if(mysqli_num_rows($sql_payments_amaount)>0){
        while($rwdt = mysqli_fetch_assoc($sql_payments_amaount)){
            
            $Patient_Payment_Item_List_ID=$rwdt['Patient_Payment_Item_List_ID'];
            $Transaction_Date_And_Time = $rwdt['Transaction_Date_And_Time'];
            $Consultant = $rwdt['Consultant'];
            $Discount = $rwdt['Discounts'];
            $Visible_Status = $rwdt['Visible_Status'];
            $Transaction_type = $rwdt['Transaction_type'];
            $Patient_Bill_ID = $rwdt['Patient_Bill_ID'];
            $Check_In_Type = $rwdt['Check_In_Type'];
            $Billing_Type = $rwdt['Billing_Type'];
            $payment_method = $rwdt['payment_method'];
            //Total cash deposit
            $Grand_Total=0;
            $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where     pp.Transaction_type = 'direct cash' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND   pp.Transaction_status <> 'cancelled' and   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID' and ppl.Item_ID=i.Item_ID  and   pp.Registration_ID = '$Registration_ID' AND Visible_Status='Others' $filter") or die(mysqli_error($conn)); 
            $nms = mysqli_num_rows($cal);
            if ($nms > 0) {
                while ($cls = mysqli_fetch_array($cal)) {
                    $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                }
            }
        
            $sql_select_cash_pay = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID AND Pre_Paid='0' AND pp.payment_type IN ('pre', 'post')  AND pp.Transaction_status <> 'cancelled'  AND Billing_Type IN ('Inpatient Cash')  $filter") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_cash_pay) > 0) {
                while ($rw = mysqli_fetch_assoc($sql_select_cash_pay)) {
                    $subtotal_cash =$rw['Amount'];
                }
            }

            $sql_select_cash_pays_bill = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID AND Pre_Paid='1' AND pp.payment_type = 'post' AND pp.Transaction_status <> 'cancelled' $filter AND Billing_Type IN ( 'Inpatient Cash') ") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_cash_pays_bill) > 0) {
                while ($rws = mysqli_fetch_assoc($sql_select_cash_pays_bill)) {
                    $subtotal_cash_credit =$rws['Amount'];
                }
            }

            $sql_select_credit_pay = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID   AND pp.Transaction_status <> 'cancelled'  AND Billing_Type IN ( 'Inpatient Credit') $filter") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_credit_pay) > 0) {
                while ($rw = mysqli_fetch_assoc($sql_select_credit_pay)) {
                    $subtotal_credit =$rw['Amount'];
                }
            }
          
            if($Visible_Status == "Others" && $Transaction_type=='Direct cash' ){
                $Cash_deposit = $rwdt['Amount'];                
            }
            if((($Check_In_Type =="Others") || ($Check_In_Type =="Accomodation") || ($Check_In_Type =="Kulala"))){
                $total_Others =$rwdt['Amount'];
                
            }else if($Check_In_Type=="Surgery"){
                $total_upasuaji  =$rwdt['Amount'];
                
            }else if($Check_In_Type=="Pharmacy"){
                $total_dawa  =$rwdt['Amount']; 
                
            }else if( $Check_In_Type=="Laboratory" ){
                $total_lab_test =$rwdt['Amount'];
                
            }else if($Check_In_Type=="Radiology"){                
                $total_Radiology =$rwdt['Amount'];
                
            }else if($Check_In_Type=="Procedure"){
                $total_Procedure =$rwdt['Amount'];
                
            }else if($Check_In_Type=="Doctor Room"){
                $Consultation = $rwdt['Amount']; 
                $Employee_Names = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee e, tbl_consultation c, tbl_consultation_history ch WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.Employee_ID AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' "))['Employee_Name'];
            }

            $Total_Cash_deposit =$Grand_Total;
            
            $overal_total_paid += $Amountpaid;
            $Total_Discount +=$Discount;
            
        }
    }
           
            $overal_total_discount1 +=$Total_Discount;
            $grand_total_cash1+=$subtotal_cash;
            $grand_total_credit1+=($subtotal_credit );
            $Grand_total_cash_bill1 +=($subtotal_cash_credit);
            $Total_otheritem1 +=$total_Others; 
            $TotalProcedures1 += $total_Procedure;
            $TotalRadology1 +=$total_Radiology;
            $TotalConsultation1 +=$Consultation;
            $TotalLaboratory1 +=$total_lab_test;
            $TotalPharmacy1 += $total_dawa;
            $TotalAdmission +=$total_Kulala;
            $Subtotal_Surgery1 += $total_upasuaji;
            $TotalCredit +=$Total_Credit;
            $Grand_Total_cash_deposit1 +=$Total_Cash_deposit; 
    $htm.= "
                <td  style='font-size:8px;'>$Employee_Names</td>
                <td  >".number_format($Consultation)."</td>
                <td  >".number_format($total_Radiology)."</td>
                <td  >".number_format($total_lab_test)."</td>
                <td >".number_format($total_dawa)."</td>                
                <td >".number_format($total_Procedure)."</td>
                <td >".number_format($total_upasuaji)."</td>
                               
                <td >".number_format($total_Others)."</td>
                <td >".number_format($Grand_Total)."</td>
                <td >".number_format($subtotal_cash_credit)."</td>";
                $htm.= "<td >".number_format($subtotal_cash )."</td>
                <td>".number_format($subtotal_credit)."</td>";
               
               $htm.= " <td >".number_format($Total_Discount)."</td>
                                
        ";
    $htm.= "</tr>";
    }
    }else{
        $htm.= "<tr><td colspan='16' style='color:red; font-size:15px;'>No result found since $start_date to $end_date</td></tr>";
    }
    //===============+++ TOTAL FOR INPATIENT +++==============
    
    $htm.= "  <tr>
                <td id='thead'  colspan='4'><h5><b>TOTAL FOR INPATIENT</b></h5></td>
                <td id='thead' ><h5><b>".number_format($TotalConsultation1, 2)." </b></h5></td>
                <td id='thead' ><h5><b>".number_format($TotalRadology1, 2)." </b></h5></td>
                <td id='thead' ><h5><b>".number_format($TotalLaboratory1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($TotalPharmacy1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($TotalProcedures1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($Subtotal_Surgery1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($Total_otheritem1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($Grand_Total_cash_deposit1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($Grand_total_cash_bill1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($grand_total_cash1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($grand_total_credit1, 2)." </b></h5></td>
                <td id='thead'><h5><b>".number_format($overal_total_discount1, 2)." </b></h5></td>    
            </tr>";
    //===============+++ Start TOTAL FOR OUTPATIENT +++==============
    $htm.= "<tr><td colspan='16' style='background:green; color:white; text-align:center;'>OUTPATIENT RESULT</td></tr>";
    $nums=0;
    $select_patient_details = mysqli_query($conn, "SELECT Patient_Payment_ID,Exemption, Check_In_ID ,pp.Pre_Paid,Transaction_type, pp.Registration_ID,payment_type, CAST(Payment_Date_And_Time AS DATE) AS  Payment_Date_And_Time,payment_method,sp.Guarantor_Name, pr.Patient_Name,pr.Gender, pr.Date_Of_Birth FROM tbl_patient_registration pr, tbl_sponsor sp, tbl_patient_payments pp WHERE Billing_Type IN ('Outpatient Credit', 'Outpatient Cash')  AND pr.Sponsor_ID = sp.Sponsor_ID and pp.Registration_ID = pr.Registration_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter  $filterno GROUP BY Patient_Name ORDER BY pp.Payment_Date_And_Time ASC") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_patient_details)>0){
        while($row = mysqli_fetch_assoc($select_patient_details)){
            $patient_name = $row['Patient_Name'];
            $gender = $row['Gender'];            
            $Sponsor_ID = $row['Sponsor_ID'];
            $payment_type = $row['payment_type'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Exemption = $row['Exemption'];
            $dob = $row['Date_Of_Birth'];
            $Check_In_ID = $row['Check_In_ID'];
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            $Transaction_type  = $row['Transaction_type'];
            $Pre_Paid = $row['Pre_Paid'];
            $then = $dob;
            $then = new DateTime($dob);
            $now = new DateTime();
            $sinceThen = $then->diff($now);
            $dob = $sinceThen->y.' Y '. $sinceThen->m.' M '. $sinceThen->d.' D';
            $Grand_Total=0;
            $Consultation =0;
            $total_Radiology=0;
            $total_lab_test =0;
            $total_dawa=0;
            $total_Kulala=0;  
            $total_Procedure=0;  
            $Total_Cash = 0;
            $Total_Credit=0;
            $Total_Discount=0;
            $jumla_yagharama_za_hospitali=0;
            $subtotal_credit = 0;
            $subtotal_cash=0;
            $total_Others=0;
            $total_upasuaji=0;
            $subtotal_cash_bill_created=0;
            $nums++;
            $htm.= "<tr>
                    <td  style='width:3%;box-sizing:border-box;'>".$num."</td>
                    
                    <td  style='width:15%;box-sizing:border-box; '><h6>".ucfirst($patient_name)." ($Registration_ID)</h6></td>
                    
                    <td  >".($Guarantor_Name)."</td>";
                    
                    $sql_payments_amaount = mysqli_query($conn, "SELECT  pp.Patient_Bill_ID, Visible_Status,Check_In_Type,pp.Registration_ID,ptl.Patient_Payment_ID,Patient_Payment_Item_List_ID, pp.Transaction_type,pp.Billing_Type, sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount, SUM(ptl.Discount) as Discounts, Consultant from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp, tbl_items i WHERE Billing_Type IN ('Outpatient Credit', 'Outpatient Cash')  AND pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date'  and ptl.Patient_Payment_ID = pp.Patient_Payment_ID and ptl.Item_ID = i.Item_ID $filter   AND pp.Transaction_status <> 'cancelled' GROUP BY ptl.Check_In_Type ") or die(mysqli_error($conn));
            
                    $Employee_Names='Not consulted';
                    if(mysqli_num_rows($sql_payments_amaount)>0){
                        while($rwdt = mysqli_fetch_assoc($sql_payments_amaount)){
                            
                            $Patient_Payment_Item_List_ID=$rwdt['Patient_Payment_Item_List_ID'];
                            $Transaction_Date_And_Time = $rwdt['Transaction_Date_And_Time'];
                            $Consultant = $rwdt['Consultant'];
                            $Discount = $rwdt['Discounts'];
                            $Visible_Status = $rwdt['Visible_Status'];
                            $Transaction_type = $rwdt['Transaction_type'];
                            $Patient_Bill_ID = $rwdt['Patient_Bill_ID'];
                            $Check_In_Type = $rwdt['Check_In_Type'];
                            $Billing_Type = $rwdt['Billing_Type'];
                            $payment_method = $rwdt['payment_method'];
                            //Total cash deposit
                            $Grand_Total=0;
                            $cal = mysqli_query($conn, "SELECT ppl.Price,ppl.Discount,ppl.Quantity  from tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where     pp.Transaction_type = 'Direct cash' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND   pp.Transaction_status <> 'cancelled' and   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  pp.Patient_Bill_ID = '$Patient_Bill_ID' and ppl.Item_ID=i.Item_ID  and Billing_Type = 'Outpatient Cash'  AND  pp.Registration_ID = '$Registration_ID' AND Visible_Status='Others' $filter") or die(mysqli_error($conn)); 
                            $nms = mysqli_num_rows($cal);
                            if ($nms > 0) {
                                while ($cls = mysqli_fetch_array($cal)){
                                    $Grand_Total += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                                }
                            }
                        
                            $sql_select_cash_pay = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID AND Pre_Paid='0' AND pp.payment_type IN ('pre', 'post')  AND pp.Transaction_status <> 'cancelled'  AND Billing_Type IN ('Outpatient Cash')  $filter") or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_cash_pay) > 0) {
                                while ($rw = mysqli_fetch_assoc($sql_select_cash_pay)) {
                                    $subtotal_cash =$rw['Amount'];
                                }
                            }
                            $sql_select_cash_pays = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID AND Pre_Paid ='1' AND pp.payment_type = 'post' AND pp.Transaction_status <> 'cancelled' $filter AND Billing_Type IN ('Outpatient Cash') ") or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_cash_pays) > 0) {
                                while ($rws = mysqli_fetch_assoc($sql_select_cash_pays)) {
                                    $subtotal_cash_bill =$rws['Amount'];
                                }
                            }
                            $sql_select_cash_pay = mysqli_query($conn, "SELECT  sum((ptl.Price - ptl.Discount) * ptl.Quantity) as Amount from  tbl_patient_payment_item_list ptl, tbl_patient_payments pp WHERE pp.Registration_ID='$Registration_ID' and pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' and ptl.Patient_Payment_ID = pp.Patient_Payment_ID   AND pp.Transaction_status <> 'cancelled'  AND Billing_Type IN ('Outpatient Credit') $filter") or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_cash_pay) > 0) {
                                while ($rw = mysqli_fetch_assoc($sql_select_cash_pay)) {
                                    $subtotal_credit =$rw['Amount'];
                                }
                            }
                        
                            if($Visible_Status == "Others" && $Transaction_type=='Direct cash' ){
                                $Cash_deposit = $rwdt['Amount'];
                                
                            }
                            if((($Check_In_Type =="Others") || ($Check_In_Type =="Accomodation") || ($Check_In_Type =="Kulala"))){
                                $total_Others =$rwdt['Amount'];
                                
                            }else if($Check_In_Type=="Surgery"){
                                $total_upasuaji  =$rwdt['Amount'];
                                
                            }else if($Check_In_Type=="Pharmacy"){
                                $total_dawa  =$rwdt['Amount']; 
                                
                            }else if( $Check_In_Type=="Laboratory" ){
                                $total_lab_test =$rwdt['Amount'];
                                
                            }else if($Check_In_Type=="Radiology"){                
                                $total_Radiology =$rwdt['Amount'];
                                
                            }else if($Check_In_Type=="Procedure"){
                                $total_Procedure =$rwdt['Amount'];
                                
                            }else if($Check_In_Type=="Doctor Room"){
                                $Consultation = $rwdt['Amount']; 
                                $Employee_Names = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee e, tbl_consultation c, tbl_consultation_history ch WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.Employee_ID AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' "))['Employee_Name'];
                            }
        
                            $Total_Cash_deposit =$Grand_Total;
                            
                            $overal_total_paid += $Amountpaid;
                            $Total_Discount +=$Discount;
                            
                        }
                    }
                    if(($Consultation ==0) && ($total_Radiology ==0) && ($total_lab_test ==0) && ($total_dawa ==0)&& ($total_Procedure ==0) && ($total_upasuaji ==0) && ($total_Others ==0)){
                        $background="style='background:yellow; color:black; font-size:12px;'";
                        $Waliolipamadeni +=$Grand_Total;
                    }else{
                        $background="";
                    }
                    $overal_total_discount2 +=$Total_Discount;
                    $grand_total_cash2+=$subtotal_cash;
                    $grand_total_credit2+=($subtotal_credit );
                    $Grand_total_cash_bill2 +=($subtotal_cash_bill);
                    $Total_otheritem2 +=$total_Others; 
                    $TotalProcedures2 += $total_Procedure;
                    $TotalRadology2 +=$total_Radiology;
                    $TotalConsultation2 +=$Consultation;
                    $TotalLaboratory2 +=$total_lab_test;
                    $TotalPharmacy2 += $total_dawa;
                    $TotalAdmission +=$total_Kulala;
                    $Subtotal_Surgery2 += $total_upasuaji;
                    $Grand_Total_cash_deposit2 +=$Total_Cash_deposit; 
            $htm.= "
                        <td $background  style='font-size:8px;'>$Employee_Names</td>
                        <td $background  >".number_format($Consultation)."</td>
                        <td $background  >".number_format($total_Radiology)."</td>
                        <td $background  >".number_format($total_lab_test)."</td>
                        <td $background >".number_format($total_dawa)."</td>                
                        <td $background >".number_format($total_Procedure)."</td>
                        <td $background >".number_format($total_upasuaji)."</td>
                                       
                        <td $background >".number_format($total_Others)."</td>
                        <td $background >".number_format($Grand_Total)."</td>
                        <td $background >".number_format($subtotal_cash_bill)."</td>";
                        $htm.= "<td $background>".number_format($subtotal_cash)."</td>
                        <td $background>".number_format($subtotal_credit)."</td>";
                       
                       $htm.= " <td $background>".number_format($Total_Discount)."</td>
                                        
                ";
            $htm.= "</tr>";
            }
            }else{
                $htm.= "<tr><td colspan='16' style='color:red; font-size:15px;'>No result found since $start_date to $end_date </td></tr>";
            }
            //===============++ TOTAL FOR OUTPATIENT ++==============
            $htm.= "<tr>
            <td id='thead'  colspan='4'><h5><b>TOTAL FOR OUTPATIENT</b></h5></td>
            <td id='thead' ><h5><b>".number_format($TotalConsultation2, 2)." </b></h5></td>
            <td id='thead' ><h5><b>".number_format($TotalRadology2, 2)." </b></h5></td>
            <td id='thead' ><h5><b>".number_format($TotalLaboratory2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($TotalPharmacy2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($TotalProcedures2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($Subtotal_Surgery2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($Total_otheritem2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format(($Grand_Total_cash_deposit2-$Waliolipamadeni) , 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($Grand_total_cash_bill2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format(($grand_total_cash2-$Waliolipamadeni), 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($grand_total_credit2, 2)." </b></h5></td>
            <td id='thead'><h5><b>".number_format($overal_total_discount2, 2)." </b></h5></td>
            
            </tr>";
        
            //===============++ END TOTAL FOR OUTPATIENT ++==============
            //================++ START PATIENT FOR DEPOSIT ++============
         $htm.= "<tr><td colspan='11' style='background:yellow; color:white; font-size:20px;' ><h5><b>DEBIT BILLS PAID BY CASH DEPOSIT </b></h5></td><td colspan='5' style='background:yellow; color:white; font-size:20px;'><h5><b>".number_format($Waliolipamadeni, 2)."</b></h5></td><td></tr>";
            
            //================++ END PATIENT FOR DEPOSIT ++==============
            //=====================+ OVERALL TOTAL +====================== 
        
            $htm.= "<tr><td colspan='16'></td></tr><tr>
                    <td id='theadtotal'  colspan='4'><h5><b>OVERALL TOTAL INPATIENT AND OUTPATIENT </b></h5></td>
                    <td id='theadtotal' ><h5><b>".number_format(($TotalConsultation1 + $TotalConsultation2), 2)." </b></h5></td>
                    <td id='theadtotal' ><h5><b>".number_format(($TotalRadology1 +$TotalRadology2), 2)." </b></h5></td>
                    <td id='theadtotal' ><h5><b>".number_format(($TotalLaboratory1 + $TotalLaboratory2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($TotalPharmacy1+ $TotalPharmacy2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($TotalProcedures1 +$TotalProcedures2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($Subtotal_Surgery1 + $Subtotal_Surgery2) , 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($Total_otheritem1 + $Total_otheritem2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($Grand_Total_cash_deposit1 +$Grand_Total_cash_deposit2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($Grand_total_cash_bill1 +$Grand_total_cash_bill2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($grand_total_cash1 +$grand_total_cash2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($grand_total_credit1 +$grand_total_credit2), 2)." </b></h5></td>
                    <td id='theadtotal'><h5><b>".number_format(($overal_total_discount1 +$overal_total_discount2), 2)." </b></h5></td>
                    
                    </tr>";
            //=====================+ END  OVERALL TOTAL +====================== 
            $htm.= "</tbody></table>
            <br>
            NB: Others include Ambulance charges, Admissions charges and all consumables. ";

include("./MPDF/mpdf.php");
$mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
$mpdf->SetFooter('|{PAGENO}/{nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();