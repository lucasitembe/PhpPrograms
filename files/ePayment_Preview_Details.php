<?php
    include("./includes/connection.php");
    $temp = 1;
    
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
    
    echo '<legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>ePayment Revenue Collections</b></legend>';
    echo '<center><table width =100% border="1" id="dtTableperformancedetails" class="display" style="background-color:white;">';

    $Title = "<tr><td colspan='9'><hr></td></tr>
                <tr>
                    <td width = '4%'><b>SN</b></td>
                    <td style='text-align: left;' width='12%'><b>DATE & TIME</b></td>
                    <td style='text-align: left;'><b>PATIENT NAME</b></td>
                    <td width='10%' style='text-align: right;'><b>RECEIPT NO</b></td>
                    <td width='10%'>&nbsp;&nbsp;&nbsp;<b>BILL NUMBER</b></td>
                    <td width='12%'><b>TRANSACTION REF</b></td>
                    <td width='12%'><b>TRANSACTION DATE</b></td>
                    <td style='text-align: right;' width=7%><b>AMOUNT</b></td>
                </tr>
                <tr><td colspan='9'><hr></td></tr>";
    echo $Title;
    $select = mysqli_query($conn,"select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, 
                            sum((price*quantity)-(discount*quantity)) as Total from 
                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            PP.Transaction_status <> 'cancelled' and
                            pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn));
    //declare all total
    $Cash_Total = 0;
    
    while($row = mysqli_fetch_array($select)){
        $Payment_Code = $row['Payment_Code'];
        
        //get Transaction_Ref & Transaction_Date
        $slct = mysqli_query($conn,"select Transaction_Ref, Transaction_Date from tbl_bank_api_payments_details where Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($slct);
        if($no > 0){
            while ($dt = mysqli_fetch_array($slct)) {
                $Transaction_Ref = $dt['Transaction_Ref'];
                $Transaction_Date = $dt['Transaction_Date'];
            }
        }else{
            $Transaction_Ref = '';
            $Transaction_Date = '';
        }

        echo "<tr><td><b>".$temp.".</b></td>";
        echo "<td>".$row['Payment_Date_And_Time']."</td>";
        echo "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
        echo "<td style='text-align: right;'>".$row['Patient_Payment_ID']."</a></td>";
        echo "<td>&nbsp;&nbsp;&nbsp;".$Payment_Code."</td>";
        echo "<td>".$Transaction_Ref."</td>";
        echo "<td>".$Transaction_Date."</td>";
        if(((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'active')){
            echo "<td style='text-align: right;'>".number_format($row['Total'])."</td></tr>";
            $Cash_Total = $Cash_Total + $row['Total'];
        }
        $temp++;
        if(($temp%31) == 0){
            echo $Title;
        } 
    }
    echo "<tr><td colspan='9'><hr></td></tr>";
    echo "<tr><td colspan=7 style='text-align: left;'><b>GRAND TOTAL</b></td>";
    echo "<td style='text-align: right;'><b>".number_format($Cash_Total)."</b></td></tr>";
    echo "<tr><td colspan='9'><hr></td></tr>";
?></table></center>

