<?php
    session_start();
    include("./includes/connection.php");
    $temp = 1;
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Pharmacy'])) {
            if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get Sub_Department_ID & Name
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }


    $filterSponsor = '';
    if (isset($_GET['Start_Date'])) {
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = '';
    }

    if (isset($_GET['End_Date'])) {
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = '';
    }


    if (isset($_GET['Search_Patient'])) {
        $Search_Patient = mysqli_real_escape_string($conn,$_GET['Search_Patient']);
    }else{
        $Search_Patient = '';
    }

    if (isset($_GET['Sponsor'])) {
        $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor']);
    }else{
        $Sponsor = '';
    }

    if (isset($_GET['employeeID'])) {
        $employeeID = mysqli_real_escape_string($conn,$_GET['employeeID']);
    }else{
        $employeeID = '';
    }

    if (isset($_GET['Bill_Type'])) {
        $Bill_Type = mysqli_real_escape_string($conn,$_GET['Bill_Type']);
    }else{
        $Bill_Type = '';
    }

    if (isset($_GET['Payment_Mode'])) {
        $Payment_Mode = mysqli_real_escape_string($conn,$_GET['Payment_Mode']);
    }else{
        $Payment_Mode = '';
    }

    $filter = '';

    if (isset($_GET['End_Date']) && $End_Date != '' && isset($_GET['Start_Date']) && $Start_Date != '') {
        $filter = "   AND ilc.Dispense_Date_Time  BETWEEN '$Start_Date' AND '$End_Date'   AND ilc.Sub_Department_ID = '$Sub_Department_ID'";
    }


    if ($Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID='$Sponsor'";
    }

    if ($employeeID != 'All') {
        $filter .="  AND ilc.Dispensor='$employeeID'";
    }

    if (!empty($Search_Patient)) {
        $filter .= " AND preg.Patient_Name LIKE '%" . $Search_Patient. "%'";
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>

<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>TELLERS ACTIVITIES REPORT ~ <?php echo strtoupper($Sub_Department_Name); ?></b></legend>
<center>
    <?php
        //generate main sql
        if($Bill_Type == 'All'){
            if($Payment_Mode == 'All'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Cash'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Credit'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }
        }else if($Bill_Type == 'Outpatient'){
            if($Payment_Mode == 'All'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Cash'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                pp.Billing_Type = 'Outpatient Cash' and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Credit'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                pp.Billing_Type = 'Outpatient Credit' and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }
        }else if($Bill_Type == 'Inpatient'){
            if($Payment_Mode == 'All'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Cash'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                pp.Billing_Type = 'Inpatient Cash' and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }else if($Payment_Mode == 'Credit'){
                $qr = "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, pp.Folio_Number, pp.Claim_Form_Number, ilc.Consultant_ID,
                                preg.Patient_Name, sp.Guarantor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number, pp.Patient_Payment_ID, em.Employee_Name, pp.Receipt_Date,
                                preg.Member_Number, ilc.Transaction_Type,pp.Payment_Date_And_Time from
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_payments pp,tbl_employee em, tbl_patient_registration preg,tbl_sponsor sp where
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                preg.registration_id = pc.registration_id and
                                ilc.Consultant_ID = em.Employee_ID AND
                                ilc.status = 'dispensed' and
                                pp.Billing_Type = 'Inpatient Credit' and
                                sp.Sponsor_ID =preg.Sponsor_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pp.Patient_Payment_ID =ilc.Patient_Payment_ID 
                                $filter 
                                group by pc.Payment_Cache_ID, ilc.Patient_Payment_ID order by ilc.Dispense_Date_Time";
            }
        }
       

        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        $grand_total = 0;
        while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
            $total = 0;
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Cache_ID = $row['Payment_Cache_ID'];

            //GENERATE PATIENT YEARS, MONTHS AND DAYS
            $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

    ?>
            <br/>
            <table width="100%">
                <tr>
                    <td style="text-align: right;" width="9%">Patient Name :</td><td><?php echo ucwords(strtolower($row['Patient_Name'])); ?></td>
                    <td style="text-align: right;" width="9%">Patient # :</td><td><?php echo $row['Registration_ID']; ?></td>
                    <td style="text-align: right;" width="9%">Sponsor Name :</td><td><?php echo $row['Guarantor_Name']; ?></td>
                    <td style="text-align: right;" width="9%">Claim Form # :</td><td><?php echo $row['Claim_Form_Number']; ?></td>
                    <td style="text-align: right;" width="9%">Folio # :</td><td><?php echo $row['Folio_Number']; ?></td>
                    <td style="text-align: right;" width="9%">Consulting Doctor :</td><td><?php echo $row['Employee_Name']; ?></td>
                    <td style="text-align: right;" width="9%">Bill Date :</td><td><?php echo $row['Receipt_Date']; ?></td>
                </tr>
                <!-- <tr><td colspan="14"><hr></td><tr> -->
                <tr>
                    <td colspan="14">
                        <table width="100%">
    <?php
            //Select amount dispensed
            $select_Transaction = mysqli_query($conn,"select ppl.Discount, ppl.Price, ppl.Quantity, i.Product_Name from
                                                tbl_patient_payment_item_list ppl, tbl_item_list_cache ilc, tbl_items i where
                                                ppl.Patient_Payment_ID = ilc.Patient_Payment_ID and
                                                ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
                                                i.Item_ID = ilc.Item_ID and
                                                ppl.Item_ID = ilc.Item_ID and
                                                ilc.Check_In_Type = 'Pharmacy' and
                                                ilc.Status = 'dispensed'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Transaction);
            if($num > 0){
    ?>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>ITEM NAME</b></td>
                    <td width="13%" style="text-align: right;"><b>UNIT PRICE</b></td>
                    <td width="13%" style="text-align: right;"><b>DISCOUNT</b></td>
                    <td width="13%" style="text-align: right;"><b>QUANTITY</b></td>
                    <td width="13%" style="text-align: right;"><b>BILL NO</b></td>
                    <td width="13%" style="text-align: right;"><b>AMOUNT</b></td>
                </tr>
                <tr><td colspan="7"><hr></td></tr>
    <?php
                $Counter = 0;
                while ($dt = mysqli_fetch_array($select_Transaction)) {
    ?>
                    <tr>
                        <td width="5%"><?php echo ++$Counter; ?></td>
                        <td><?php echo $dt['Product_Name']; ?></td>
                        <td style="text-align: right;"><?php echo number_format($dt['Price']); ?></td>
                        <td style="text-align: right;"><?php echo number_format($dt['Discount']); ?></td>
                        <td style="text-align: right;"><?php echo number_format($dt['Quantity']); ?></td>
                        <td style="text-align: right;"><?php echo $Patient_Payment_ID; ?></td>
                        <td style="text-align: right;"><?php echo number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']); ?></td>
                    </tr>
    <?php
                    $total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                }
                //select dispensor
                $select_dispensor = mysqli_query($conn,"select Employee_Name from tbl_item_list_cache ilc, tbl_employee emp where 
                                                    Patient_Payment_ID = '$Patient_Payment_ID' and
                                                    emp.Employee_ID = ilc.Dispensor group by ilc.Patient_Payment_ID") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($select_dispensor);
                if($nm > 0){
                    while ($rw = mysqli_fetch_array($select_dispensor)) {
                        $Dispensor = $rw['Employee_Name'];
                    }
                }else{
                    $Dispensor = '';
                }
                echo '<tr><td colspan="7"><hr></td></tr>';
                echo '<tr><td colspan="3" style="text-align: left;"><b>Employee Dispense : </b>'.$Dispensor.'</td>
                            <td colspan="3" style="text-align: right;"><b>SUB TOTAL</td><td style="text-align: right;">'.number_format($total).'</td></tr>';
                echo '<tr><td colspan="7"><hr></td></tr>';
            }
            echo "</table><br/>";
            $grand_total +=$total;


            /*//select employee prepared
            $select_emp_prepare = mysqli_query($conn,"select Employee_Name AS Employee_Prepared from tbl_item_list_cache ilc, tbl_employee emp where 
                                                Patient_Payment_ID = '$Patient_Payment_ID' and
                                                emp.Employee_ID = ilc.Consultant_ID group by ilc.Patient_Payment_ID") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($select_emp_prepare);
            if($nm > 0){
                while ($rw = mysqli_fetch_array($select_emp_prepare)) {
                    $Employee_Prepared = $rw['Employee_Prepared'];
                }
            }else{
                $Employee_Prepared = '';
            }*/
            
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $row['Sponsor_Name'] . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $Employee_Prepared . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>" . $Dispensor . "</a></td>";
            //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=" . $row['Registration_ID'] . "&Transaction_Type=" . $row['Transaction_Type'] . "&Payment_Cache_ID=" . $row['Payment_Cache_ID'] . "&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;float:right'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total))."</a></td>";
            //echo "</tr>";
            $temp++;
    ?>
                        </table>
                    </td>
                </tr>
    <?php
        }
        echo '</table>';
        ?>


        </td>
        </tr>
    </table>
</center>
<p style="float: right;"><b>Total: <?php echo $_SESSION['hospcurrency']['currency_code'] . '  ' . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($grand_total, 2) : number_format($grand_total)) ?></b></p>