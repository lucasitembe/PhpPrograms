<?php
    require_once('includes/connection.php');
    if(isset($_POST['msamahareport'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $msamaha_Items = mysqli_real_escape_string($conn, $_POST['msamaha_Items']);
        $filter = " ci.Check_In_Date_And_Time between '$start_date' and '$end_date' and sp.Exemption = 'yes' and ";

        if($msamaha_Items != null && $msamaha_Items != '' && $msamaha_Items != 0){
            $selectmsamaha = mysqli_query($conn,"SELECT msamaha_aina, msamaha_Items from tbl_msamaha_items where msamaha_Items = '$msamaha_Items' order by msamaha_aina") or die(mysqli_error($conn));
        }else{
            $selectmsamaha = mysqli_query($conn,"SELECT msamaha_aina, msamaha_Items from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
       }
       
        $ttyupasuaji=0;
        $ttydawa=0;
        $ttyProcedure=0;
        $total_pt=0;
        $ttyradiology=0;
        $ttyvipimo=0;
        $ttykulala=0;
        $ttykujifungua = 0;
        $ttyOthers=0;
        $ttyconsultation=0;
        $totalkujifungua=0;
        $ttyRadiology=0;
        if(mysqli_num_rows($selectmsamaha)>0){
            while($rdt = mysqli_fetch_assoc($selectmsamaha)){
                $msamaha_aina = $rdt['msamaha_aina'];
                $msamaha_Items = $rdt['msamaha_Items'];
                echo "<tr><td>$msamaha_aina </td>"; 
                $selectmsamahasql = mysqli_query($conn,"SELECT Check_In_ID,Gender, ci.msamaha_Items, ci.Anayependekeza_Msamaha, Check_In_Date_And_Time,  pr.Registration_ID from tbl_msamaha ms,tbl_patient_registration pr,  tbl_sponsor sp, tbl_check_in ci where	$filter pr.Sponsor_ID = sp.Sponsor_ID and 	ci.Registration_ID = pr.Registration_ID and	ms.Registration_ID = pr.Registration_ID  AND msamaha_Items ='$msamaha_Items'") or die(mysqli_error($conn));
                $count_male=0;
                $count_female=0;
                $TotalGender=0;
                if(mysqli_num_rows($selectmsamahasql)>0){
                    while($rw = mysqli_fetch_assoc($selectmsamahasql)){
                        $Registration_ID = $rw['Registration_ID'];
                        $Check_In_ID = $rw['Check_In_ID'];
                        $Gender =$rw['Gender'];
                        if(strtolower($Gender) =="male"){
                            $count_male++;
                        }
                        if(strtolower($Gender) =="female"){
                            $count_female++;
                        }
                       
                    }
                };
                $TotalGender= $count_female + $count_male; 
                echo "<td>$count_male</td><td>$count_female</td><td>$TotalGender</td>";
                $total_consultation=0;
                $total_consultation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount  from   tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci where ci.Check_In_Date_And_Time between '$start_date' and '$end_date' and   pp.Transaction_type = 'indirect cash' and    pp.Billing_Type IN (  'Outpatient Cash',   'Outpatient Credit')   and   pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and ci.Check_In_ID=pp.Check_In_ID   AND  Check_In_type='Doctor Room' AND ci.msamaha_Items='$msamaha_Items'"))['Amount'];
                
                $items = mysqli_query($conn,"SELECT Check_In_Type,pp.msamaha_Items, ppl.Price,ppl.Transaction_Date_And_Time,ppl.Quantity, ppl.Discount,    sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount from   tbl_items i,  tbl_patient_payments pp, tbl_patient_payment_item_list ppl where  i.Item_ID = ppl.Item_ID and    pp.Transaction_type = 'indirect cash' and    pp.Billing_Type IN ('Inpatient Cash',  'Outpatient Cash',  'Intpatient Credit',  'Outpatient Credit')   and   pp.Transaction_status <> 'cancelled' and     pp.Patient_Payment_ID = ppl.Patient_Payment_ID and ppl.Transaction_Date_And_Time between '$start_date' and '$end_date' AND  pp.msamaha_Items='$msamaha_Items' GROUP BY Check_In_type ") or die(mysqli_error($conn));
              
                $total_upasuaji=0;
                $total_dawa=0;
                $total_vipimo=0;
                $total_Radiology=0;
                $total_Procedure=0;
                $total_Others=0;
                $total_Kulala = 0;
                $total_kujifungua=0;
                $total_Physiotherapy=0;
                $Total_ambulance=0;
                $Total_pf3 = 0;
                $Total_mortuary=0;
                
                $nm = mysqli_num_rows($items);
                if ($nm > 0) {                      
                    while ($dt = mysqli_fetch_assoc($items)){
                        $Check_In_Type =  $dt['Check_In_Type'];
                        if((($Check_In_Type =="Kulala") || ($Check_In_Type =="Accomodation") )){
                            $total_Kulala =$dt['Amount'];
                        }else if($Check_In_Type=="Surgery"){
                            $total_upasuaji =$dt['Amount'];
                        }else if($Check_In_Type=="Pharmacy"){
                            $total_dawa =$dt['Amount'];
                        }else if( $Check_In_Type=="Laboratory" ){
                            $total_vipimo =$dt['Amount'];
                        }else if($Check_In_Type=="Radiology"){
                            $total_Radiology =$dt['Amount'];
                        }else if($Check_In_Type=="Procedure"){
                            $total_Procedure =$dt['Amount'];
                        }else if($Check_In_Type=="Others"){
                            $total_Others =$dt['Amount'];
                        }
                        
                    }
                
                }
                echo "<td>".number_format($total_consultation)."</td>";
                echo "<td>".number_format($total_vipimo )."</td>
                    <td>".number_format($total_Radiology )."</td>
                    <td>".number_format($total_dawa )."</td>
                    <td>".number_format($total_Procedure )."</td>
                    <td>".number_format($total_upasuaji)."</td>
                    <td>".number_format($total_Kulala)."</td>
                    <td>".number_format($total_kujifungua)."</td>
                    <td>".number_format($total_Others)."</td>
                    <td>".number_format($total_vipimo+$total_Radiology+$total_dawa+$total_Procedure+ $total_upasuaji+$total_Kulala+$total_Others)."</td>";
                
                echo "</tr>";
                $total_male +=$count_male;
                $total_female +=$count_female;
                $total_pt +=$TotalGender;
                $ttyconsultation +=$total_consultation;
                $ttyvipimo += $total_vipimo;
                $ttyradiology += $total_Radiology;
                $ttydawa += $total_dawa;
                $ttyProcedure += $total_Procedure;
                $ttykulala += $total_Kulala;
                $ttykujifungua += $total_kujifungua;
                $ttyothers += $total_Others; 
                $ttyupasuaji += $total_upasuaji;
                
            }
            
        }
        echo "
            <th>Total</th>
            <th>$total_male</th>
            <th>$total_female</th>
            <th>$total_pt</th>";
        echo "<th>".number_format($ttyconsultation, 2)."/=</th>";
        echo "<th>".number_format($ttyvipimo, 2)."/=</th>
            <th>".number_format($ttyRadiology, 2)."/=</th>
            <th>".number_format($ttydawa, 2)."/=</th>
            <th>".number_format($ttyProcedure, 2)."/=</th>
            <th>".number_format($ttyupasuaji, 2)."/=</th>
            <th>".number_format($ttykulala, 2)."/=</th>
            <th>".number_format($totalkujifungua, 2)."/=</th>
            <th>".number_format($ttyothers, 2)."/=</th>
            <th>".number_format($ttyconsultation + $ttyvipimo+$ttyRadiology+$ttydawa+$ttyProcedure+ $ttyupasuaji+$ttyKulala+$ttyothers, 2)." /=</th>";
        
        echo "</tr>";
    }