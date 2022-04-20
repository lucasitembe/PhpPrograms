<?php
session_start();
include("./includes/connection.php");

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
if (isset($_SESSION['Pharmacy'])) {
    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $select_sub_department = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}

$filterSponsor = '';
if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '';
}


if (isset($_GET['Search_Patient'])) {
    $Search_Patient = str_replace(" ", "%", mysqli_real_escape_string($conn,$_GET['Search_Patient']));
} else {
    $Search_Patient = '';
}

if (isset($_GET['Sponsor'])) {
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor']);
} else {
    $Sponsor = '';
}

if (isset($_GET['employeeID'])) {
    $employeeID = mysqli_real_escape_string($conn,$_GET['employeeID']);
} else {
    $employeeID = '';
}

if (isset($_GET['Bill_Type'])) {
    $Bill_Type = mysqli_real_escape_string($conn,$_GET['Bill_Type']);
} else {
    $Bill_Type = '';
}

if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = mysqli_real_escape_string($conn,$_GET['Payment_Mode']);
} else {
    $Payment_Mode = '';
}

// $filter = " and pp.Transaction_status <> 'cancelled' ";

if (isset($_GET['End_Date']) && $End_Date != '' && isset($_GET['Start_Date']) && $Start_Date != '') {
    $filter .= " AND ilc.Dispense_Date_Time  BETWEEN '$Start_Date' AND '$End_Date' and ilc.Sub_Department_ID = '$Sub_Department_ID'";
    $filter_date .=" AND ilc.Dispense_Date_Time  BETWEEN '$Start_Date' AND '$End_Date' and ilc.Sub_Department_ID = '$Sub_Department_ID'";
}


if ($Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID='$Sponsor' ";
}

if ($employeeID != 'All') {
    $filter .="  AND ilc.Dispensor='$employeeID' ";
    $filter_date .="  AND ilc.Dispensor='$employeeID'";
}


if ($Bill_Type == 'All') {
    if ($Payment_Mode == 'Cash') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') ";
    }
} else if ($Bill_Type == 'Outpatient') {
    if ($Payment_Mode == 'All') {
        $filter .= " and (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') ";
    } else if ($Payment_Mode == 'Cash') {
        $filter .= " and pp.Billing_Type = 'Outpatient Cash' ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and pp.Billing_Type = 'Outpatient Credit' ";
    }
} else if ($Bill_Type == 'Inpatient') {
    if ($Payment_Mode == 'All') {
        $filter .= " and (pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') ";
    } else if ($Payment_Mode == 'Cash') {
        $filter .= " and pp.Billing_Type = 'Inpatient Cash' ";
    } else if ($Payment_Mode == 'Credit') {
        $filter .= " and pp.Billing_Type = 'Inpatient Credit' ";
    }
}


if (!empty($Search_Patient)) {
    $filter .= " AND pr.Patient_Name LIKE '%" . $Search_Patient . "%'";
}


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>

<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>DISPENSED LIST</b></legend>
    <table width="100%">
<?php

    $Grand_Total = 0;
   
    $select = mysqli_query($conn,"SELECT ilc.Dispense_Date_Time, ilc.Dispensor,ilc.Patient_Payment_ID, pc.Registration_ID, sp.Guarantor_Name, pr.Date_Of_Birth, pr.Gender, pr.Patient_Name, pc.Billing_Type From
                            tbl_payment_cache pc, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc, tbl_items where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = pc.Registration_ID and
                            ilc.Check_In_Type = 'Pharmacy' and
                            ilc.Dispensor > 0 and 
                            (ilc.Quantity > 0 or ilc.Edited_Quantity > 0)
                            $filter
                            group by ilc.Dispense_Date_Time,ilc.Dispensor order by ilc.Dispense_Date_Time ASC") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        echo '<div style="border: 1px solid #c0c0c0;padding:5px;padding-top:0px;background-color:#037CB0;width:300px;border-radius:8px;">';
        echo '<h3 style="color:#fff;">Number of Patients: '.$num.'</h3>';
        echo '</div>';
        echo '<hr>';
        $temp = 0;
        while ($data = mysqli_fetch_array($select)) {
            $Total = 0;
            $Patient_Payment_ID = $data['Patient_Payment_ID'];
            $Date_Of_Birth = $data['Date_Of_Birth'];
            $Dispense_Date_Time = $data['Dispense_Date_Time'];
            $Dispensor = $data['Dispensor'];
            //Generate Age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

?>
            <tr>
                <td><?php echo ++$temp; ?><b>.</b></td>
                <td style="text-align: right;" width="7%">PATIENT NAME : </td>
                <td width="13%"><?php echo strtoupper($data['Patient_Name']); ?></td>
                <td style="text-align: right;" width="6%">GENDER : </td>
                <td width="6%"><?php echo strtoupper($data['Gender']); ?></td>
                <td style="text-align: right;" width="7%">AGE : </td>
                <td width="13%"><?php echo $age; ?></td>
                <td style="text-align: right;" width="8%">SPONSOR : </td>
                <td width="10%"><?php echo strtoupper($data['Guarantor_Name']); ?></td>
                <td style="text-align: right;" width="9%">RECEIPT #</td>
                <td width="6%"><?php echo $Patient_Payment_ID; ?></td>
                <td style="text-align: right;" width="5%">BILL : </td>
                <td width="14%"><?php echo $data['Billing_Type']; ?></td>
            </tr>
            <tr><td colspan="13"><hr></td></tr>
<?php
            //get medications


            
            $slct = mysqli_query($conn,"SELECT i.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Dispense_Date_Time, ilc.Price, ilc.Discount, ilc.Consultant_ID,ilc.Doctor_Comment, emp.Employee_Name as Dispensor_Name FROM tbl_items i, tbl_item_list_cache ilc, tbl_employee emp WHERE ilc.Dispense_Date_Time = '$Dispense_Date_Time' AND ilc.Dispensor = '$Dispensor' AND i.Item_ID = ilc.Item_ID and
            emp.Employee_ID = ilc.Dispensor AND ilc.Sub_Department_ID = $Sub_Department_ID GROUP BY ilc.Item_ID ORDER BY ilc.Dispense_Date_Time ASC") or die(mysqli_error($conn));



            $no = mysqli_num_rows($slct);
            if($no > 0){
                $count = 0;
?>
                    <tr>
                        <td colspan="13">
                            <table width="100%">
                                <tr>
                                    <td width="5%"><b>SN</b></td>
                                    <td width="25%"><b>PRODUCT NAME</b></td>
                                    <td width="9%"><b>DOSAGE</b></td>
                                    <td width="9%" style="text-align: right;"><b>PRICE</b></td>
                                    <td width="9%" style="text-align: right;"><b>DISCOUNT</b></td>
                                    <td width="9%" style="text-align: center;"><b>QUANTITY</b>&nbsp;&nbsp;&nbsp;</td>
                                    <td width="13%"><b>ORDERED BY</b></td>
                                    <td width="12%"><b>DISPENSED BY</b></td>
                                    <td width="13%"><b>DISPENSED DATE</b></td>
                                    <td width="10%" style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
                                </tr>
<?php
                while($row = mysqli_fetch_array($slct)){
                    //get employee ordered
                    $Consultant_ID = $row['Consultant_ID'];
                    $Quantity = $row['Quantity'];
                    $Edited_Quantity = $row['Edited_Quantity'];

                    if($Edited_Quantity > 0) {
                        $Quantity_amount = $Edited_Quantity;
                    }else{
                        $Quantity_amount = $Quantity;
                    }
                    $get_ord = mysqli_query($conn,"SELECT Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                    $get_no = mysqli_num_rows($get_ord);
                    if($get_no > 0){
                        while($rw = mysqli_fetch_array($get_ord)){
                            $Employee_Ordered = $rw['Employee_Name'];
                        }
                    }else{
                        $Employee_Ordered = '';
                    }
?>
                                <tr>
                                    <td><?php echo ++$count; ?></td>
                                    <td><?php echo $row['Product_Name']; ?></td>
                                    <td><?php echo $row['Doctor_Comment']; ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['Price']); ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['Discount']); ?></td>
                                    <td style="text-align: center;"><?php echo $Quantity_amount; ?>&nbsp;&nbsp;&nbsp;</td>
                                    <td><?php echo ucwords(strtolower($Employee_Ordered)); ?></td>
                                    <td><?php echo ucwords(strtolower($row['Dispensor_Name'])); ?></td>
                                    <td><?php echo $row['Dispense_Date_Time']; ?></td>
                                    <td style="text-align: right;"><?php echo number_format(($row['Price'] - $row['Discount']) * $Quantity_amount) ?>&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                
 <?php
                    //Get Grand Total & Total
                    $Total += (($row['Price'] - $row['Discount']) * $Quantity_amount);
                    $Grand_Total += (($row['Price'] - $row['Discount']) * $Quantity_amount);
                }

                mysqli_free_result($slct);
                echo '<tr><td colspan="10"><hr></td></tr>
                <tr><td colspan="8"><b>TOTAL</b></td><td style="text-align: right;">'.number_format($Total).'&nbsp;&nbsp;&nbsp;</td></tr>';
                echo '<tr><td colspan="9">&nbsp;</td></tr>';
                echo "</table></td></tr>";
            }

        }

        mysqli_free_result($select);
    }else{
        echo "<center><h4><b>NO TRANSACTION FOUND</b></h4></center>";
    }
?>
<tr><td colspan="12" style="text-align: right;"><h4><b>GRAND TOTAL</b></h4></td><td style="text-align: right;"><b><h4><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;</b></h4></td></tr>
</table>