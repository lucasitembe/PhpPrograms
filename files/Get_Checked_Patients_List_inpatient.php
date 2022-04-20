<?php
@session_start();
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";

$filter = '';

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_To = '';
}

if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}

if (isset($_GET['Search_Value'])) {
    $Search_Value = $_GET['Search_Value'];
} else {
    $Search_Value = '';
}
$filter="";
if (isset($_GET['Search_Value_by_number'])) {
    $Search_Value_by_number = $_GET['Search_Value_by_number'];
    if(!empty($Search_Value_by_number)){
        $filter.="AND pr.Registration_ID like'%$Search_Value_by_number%'";
    }
} else {
    $Search_Value_by_number = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter .= "  AND ci.Check_In_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' ";
}

if (!empty($Search_Value)) {
    $filter .= " AND pr.Patient_Name like '%$Search_Value%'";
}

if ($Sponsor_ID != 'All') {
    $filter .=" AND sp.Sponsor_ID='$Sponsor_ID'";
}
?>
<legend style="background-color:#006400;color:white;padding:5px;" align='right'>LIST OF CHECKED IN PATIENTS</legend>
<table width=100% class='fixTableHead'>
    <thead>
        <tr style="background-color:#ccc;">
            <td width=5%><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>SPONSOR</b></td>
            <td width=10%><b>PHONE#</b></td>
            <td width=15%><b>CHECK-IN TYPE</b></td>
            <td width=20%><b>CHECKED-IN DATE</b></td>
            <td><b>EMPLOYEE NAME</b></td>
        </tr>
    </thead>

    <?php
    $temp = 0;
    $select = mysqli_query($conn,"select sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name
                                        from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pr.Registration_ID = ci.Registration_ID and
                                        ci.Employee_ID = emp.Employee_ID and
                                        pr.Sponsor_ID = sp.Sponsor_ID
                                       
                                        $filter   GROUP BY pr.Registration_ID") or die(mysqli_error($conn));

    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $select_checkin = "SELECT Check_In_ID,Type_Of_Check_In,Check_In_Date_And_Time FROM tbl_check_in WHERE Registration_ID = '" . $row['Registration_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
            //echo $select_checkin;exit;
            $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
            $checkin = mysqli_fetch_assoc($select_checkin_qry);
            $Check_In_ID = $checkin['Check_In_ID'];
            $Type_Of_Check_In = $checkin['Type_Of_Check_In'];
            $ToBe_Admitted_results = mysqli_query($conn,"SELECT ToBe_Admitted,Admit_Status FROM tbl_check_in_details WHERE Registration_ID = '" . $row['Registration_ID'] . "' AND Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));

            if (mysqli_num_rows($ToBe_Admitted_results) > 0) {
                $ToBe_Admitted = 'no';
                $admit_status = '';
                if (mysqli_num_rows($ToBe_Admitted_results) > 0) {
                    $rowCheck = mysqli_fetch_array($ToBe_Admitted_results);
                    $ToBe_Admitted = $rowCheck['ToBe_Admitted'];
                    $admit_status = $rowCheck['Admit_Status'];
                }



                if ($ToBe_Admitted == 'no' && $admit_status == 'not admitted') {
                    $has_claim = '0';
                    $Claim_Form_Number = '';
                    $select_claim = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where Registration_ID = '" . $row['Registration_ID'] . "' AND Check_In_ID='$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                    if (mysqli_num_rows($select_claim) > 0) {
                        $row_claim = mysqli_fetch_assoc($select_claim);

                        $Claim_Form_Number = $row_claim['Claim_Form_Number'];
                        $has_claim = '1';
                    }
                    $Patient_Name = str_replace("'", '', $row['Patient_Name']);
                    echo "<tr>
                    <td>" . ++$temp . "</td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name. "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Patient_Name'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Registration_ID'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Guarantor_Name'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Phone_Number'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $checkin['Type_Of_Check_In'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $checkin['Check_In_Date_And_Time'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Employee_Name'] . "</span></td>
                  </tr>";
                }
            } else {
                //Not in the checking details yet so display him

               // die("select Claim_Form_Number from tbl_patient_payments where Registration_ID = '" . $row['Registration_ID'] . " AND Check_In_ID='$Check_In_ID' order by Patient_Payment_ID desc limit 1");
                $has_claim = '0';
                $Claim_Form_Number = '';
                $select_claim = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where Registration_ID = '" . $row['Registration_ID'] . "' AND Check_In_ID='$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                if (mysqli_num_rows($select_claim) > 0) {
                    $row_claim = mysqli_fetch_assoc($select_claim);

                    $Claim_Form_Number = $row_claim['Claim_Form_Number'];
                    $has_claim = '1';
                }
                $Patient_Name = str_replace("'", '', $row['Patient_Name']);
                echo "<tr>
                    <td>" . ++$temp . "</td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Patient_Name'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Registration_ID'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Guarantor_Name'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Phone_Number'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $checkin['Type_Of_Check_In'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $checkin['Check_In_Date_And_Time'] . "</span></td>
                    <td><span onclick='Show_Admit_Patient(" . $row['Registration_ID'] . ",\"" . $Patient_Name . "\",\"" . strtolower($row['Guarantor_Name']) . "\",\"" . $has_claim . "\",\"" . $Claim_Form_Number . "\")' class='linkstyle' >" . $row['Employee_Name'] . "</span></td>
                  </tr>";
            }
        }
    }
    ?>
</table>