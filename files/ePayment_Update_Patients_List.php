<?php
session_start();
include("./includes/connection.php");
include './includes/constants.php';
$temp = 0;
$Grand_Total = 0;
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = '';
}

if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}

if (isset($_GET['Report_Type'])) {
    $Report_Type = $_GET['Report_Type'];
} else {
    $Report_Type = '';
}

if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = $_GET['Payment_Mode'];
} else {
    $Payment_Mode = '';
}



$cashier_id = '';
if(isset($_GET['cashier_id'])){
    $cashier_id = $_GET['cashier_id'];
}
$filter_cashier ="";
if (!empty($cashier_id) && $cashier_id != 'All') {
    $filter_cashier = " AND emp.Employee_ID='$cashier_id'";
}


$limit = "LIMIT 500";
if (isset($_GET['number_recordes'])) {
    $number_recordes = $_GET['number_recordes'];
}

if ($number_recordes == '500_Rec')
    $limit = "LIMIT 500";
else if ($number_recordes == '300_Rec')
    $limit = "LIMIT 300";
else if ($number_recordes == '100_Rec')
    $limit = "LIMIT 100";
else if ($number_recordes == '50_Rec')
    $limit = "LIMIT 50";
else if ($number_recordes == 'All')
    $limit = "";
?>
<legend align=right><b>ePayment Collections Reports ~ Pending Transactions</b></legend>
<center>
    <table width = 100%>
        <tr><td colspan="11"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="12%"><b>SPONSOR</b></td>
            <td width="15%" style="text-align: right;"><b>PREPARED DATE</b></td>
            <td width="15%" style="text-align: right;"><b>EMPLOYEE PREPARED</b></td>
            <td width="10%" style="text-align: right;"><b>BILL NUMBER</b></td>
            <td width="10%" style="text-align: right;"><b>AMOUNT REQUIRED</b></td>
            <td width="" style="text-align: right;">&nbsp;</td>
        </tr>
        <tr><td colspan="11"><hr></td></tr>
        <?php
        $select = mysqli_query($conn,"SELECT tc.Source, tc.Amount_Required, pr.Patient_Name, tc.Transaction_ID, tc.Payment_Code, sp.Guarantor_Name, emp.Employee_Name, tc.Transaction_Date_Time, pr.Registration_ID,Transaction_Status,Payment_Code
							from tbl_bank_transaction_cache tc, tbl_patient_registration pr, 
							tbl_sponsor sp, tbl_employee emp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID = tc.Employee_ID and
							tc.Registration_ID = pr.Registration_ID and 
							tc.Transaction_Date_Time between '$Date_From' and '$Date_To' and
                            (tc.Transaction_Status = 'pending' or tc.Transaction_Status = 'uploaded') $filter_cashier
							order by tc.Transaction_ID desc $limit") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Grand_Total += $data['Amount_Required'];
                $Transaction_ID = $data['Transaction_ID'];
                ?>
                <tr>
                    <td><?php echo ++$temp; ?></td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo ucwords(strtolower($data['Patient_Name'])); ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Registration_ID']; ?></label></td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Guarantor_Name']; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Transaction_Date_Time']; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Employee_Name']; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Payment_Code']; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo number_format($data['Amount_Required'],2); ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <?php //if ($data['Transaction_Status']=="pending"){ ?>
                        <button type="button" class="art-button-green" onclick="ecr_paycode('<?php echo $data['Payment_Code']; ?>')">Pay</button>
                    </td>
                    <td>
                        <?php
                            $Registration_ID=$data['Registration_ID'];
                            $Payment_Code=$data['Payment_Code'];
                        ?>
                         <input type="button" value="RE-SYNCHRONIZE" class="art-button-green" onclick="sync_epayments_force('<?= $Registration_ID ?>','<?= $Transaction_ID ?>','<?= $Payment_Code?>')">&nbsp;&nbsp;
                    </td>
                    <td style="text-align: right;">
                        <input type="button" class="art-button-green" value="PRINT DETAIL RECEIPT" onclick="Print_Receipt_Payment('<?php echo $data['Payment_Code'] ?>')">

                        <?php //}else{ echo '';} ?>
                    </td>
                </tr>
                <?php
            }
            echo '<tr><td colspan="11"><hr></td></tr>';
            echo '<tr><td colspan="7" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;">' . number_format($Grand_Total,2) . '</td></tr>';
            echo '<tr><td colspan="11"><hr></td></tr>';
        } else {
            
        }
        ?>
