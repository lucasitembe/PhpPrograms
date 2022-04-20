<?php
session_start();
include("./includes/connection.php");

if (isset($_GET['Registration_ID'])) {
    $Registration_ID=$_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//select patient information
if (!empty($Registration_ID)) {
    $select_Patient = mysqli_query($conn, "SELECT pr.Old_Registration_Number, pc.Payment_Cache_ID, pr.Patient_Name, pr.Sponsor_ID, pr.Date_Of_Birth, pr.Gender, 
        								sp.Guarantor_Name, pr.Member_Number, pr.Phone_Number, pr.Occupation, pr.Registration_Date_And_Time
										from tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor sp where
										pc.Registration_ID = pr.Registration_ID and
										pc.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Phone_Number = $row['Phone_Number'];
            $Occupation = $row['Occupation'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Payment_Cache_ID = $row['Payment_Cache_ID'];
        }
    } else {
        $Old_Registration_Number = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Phone_Number = '';
        $Occupation = '';
        $Registration_Date_And_Time = '';
        $Payment_Cache_ID = '';
    }
} else {
    $Old_Registration_Number = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Phone_Number = '';
    $Occupation = '';
    $Registration_Date_And_Time = '';
}

//calculate patient age
$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, ";
$age .= $diff->m . " Months, ";
$age .= $diff->d . " Days";
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="12%" style="text-align: right;">Patient Name</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Patient Number</td>
            <td><input type="text" value="<?php echo $Registration_ID; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td width="12%" style="text-align: right;">Patient Age</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($age)); ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Gender</td>
            <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Phone Number</td>
            <td><input type="text" value="<?php echo $Phone_Number; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td width="12%" style="text-align: right;">Registered Date</td>
            <td><input type="text" value="<?php echo $Registration_Date_And_Time; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Occupation</td>
            <td><input type="text" value="<?php echo $Occupation; ?>" readonly="readonly"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 340px; background-color: white;' id='Medication_Area'>
    <?php

    $select = mysqli_query($conn, "SELECT pc.Payment_Cache_ID, pc.Payment_Date_And_Time, pc.Employee_ID, emp.Employee_Name from 
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_employee emp where
                                emp.Employee_ID = pc.Employee_ID and
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                pc.Registration_ID = '$Registration_ID' and
                                ilc.Check_In_Type = 'Pharmacy' AND ilc.Status IN('partial dispensed','dispensed')
                                 group by pc.Payment_Cache_ID order by pc.Payment_Cache_ID ASC") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {

        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $Doctor_prescribed = $data['Employee_Name'];
            $Employee_ID_eee = $data['Employee_ID'];

            ?>

            <br />
            <table width="100%">
                <tr style='background: #dedede; font-size: 14px;'>
                    <td width="10%" style="text-align: right;"><b>Ordering Doctor :</b></td>
                    <td>&nbsp;&nbsp;<?php echo ucwords(strtolower($Doctor_prescribed)); ?></td>
                    <td width="13%" style="text-align: right;"><b>Consultation Date : </b></td>
                    <td>&nbsp;&nbsp;<?php echo $data['Payment_Date_And_Time']; ?></td>
                </tr>
                <tr style='background: #dedede; font-size: 14px;'>
                    <td colspan="4">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%" style="border: none;">
                            <tr style='background: #dedede; font-size: 14px;'>
                                <td width="5%"><b>SN</b></td>
                                <td><b>MEDICATION NAME</b></td>
                                <td width="10%"><b>QUANTITY</b></td>
                                <td width="20%"><b>DOSAGE</b></td>
                                <td width="10%"><b>STATUS</b></td>
                            </tr>
                
                            <?php
                            //get list of medications

                            $temp = 0;

                            $medics = mysqli_query($conn, "SELECT Product_Name, Doctor_Comment, Quantity,Edited_Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
                                                            i.Item_ID = ilc.Item_ID and
                                                            Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type = 'Pharmacy' AND ilc.Status IN('partial dispensed','dispensed')") or die(mysqli_error($conn));
                            $numz = mysqli_num_rows($medics);
                            if ($numz > 0) {
                                echo "<tr><td colspan='5'><hr></td></tr>";
                                while ($rows = mysqli_fetch_assoc($medics)) {
                                    $Quantity = $rows['Quantity'];
                                    $Edited_Quantity = $rows['Edited_Quantity'];
                                    $item_status = $rows['Status'];
                                    if($item_status == 'partial dispensed'){
                                        $Status = 'Partial Dispensed';
                                    } 
                                    elseif($item_status == 'dispensed'){
                                        $Status = 'Dispensed';
                                    }else{
                                        $Status = 'Not Dispensed';
                                    }
                                    if ($Edited_Quantity > 0) {
                                        $Dispensed_Quantity = $Edited_Quantity;
                                    }else{
                                        $Dispensed_Quantity = $Quantity;
                                    }
                                ?>
                                    <tr style='font-size: 13px;'>
                                        <td><?php echo ++$temp; ?></td>
                                        <td><?php echo $rows['Product_Name']; ?></td>
                                        <td><?php echo $Dispensed_Quantity; ?></td>
                                        <td><?php echo $rows['Doctor_Comment']; ?></td>
                                        <td><?php echo $Status; ?></td>
                                    </tr>
                            <?php
                                }
                                echo "<tr><td colspan='5'><hr></td></tr>";
                            }else{
                                echo '<tr>
                                <td colspan=5><center><span style="font-size: 20px; font-weight: bold; text-align: center; color: red;">THERE&#39;S NO PREVIOUS MEDICATION ORDERED FOR THIS PATIENT</center></span></td>
                            </tr>';
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </table><br />
        <?php
        }
    }
    ?>
</fieldset>