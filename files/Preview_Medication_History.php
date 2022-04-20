<?php
session_start();
include("./includes/connection.php");

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//select patient information
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $select_Patient = mysqli_query($conn, "SELECT pr.Registration_ID, pr.Old_Registration_Number, pr.Patient_Name, pr.Sponsor_ID, pr.Date_Of_Birth, pr.Gender, 
        								sp.Guarantor_Name, pr.Member_Number, pr.Phone_Number, pr.Occupation, pr.Registration_Date_And_Time
										from tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor sp where
										pc.Registration_ID = pr.Registration_ID and
										pc.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
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
        }
    } else {
        $Registration_ID = '';
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
} else {
    $Registration_ID = '';
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

    // die("SELECT ilc.Payment_Cache_ID, c.Consultation_Date_And_Time, emp.Employee_Name from 
    // tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_consultation c, tbl_employee emp where
    // pc.Payment_Cache_ID = '$Payment_Cache_ID' and
    // c.consultation_id = pc.consultation_id and
    // emp.Employee_ID = ilc.Consultant_ID and
    // ilc.Check_In_Type = 'Pharmacy' and
    // pc.Registration_ID = '$Registration_ID' group by c.consultation_id order by c.Consultation_Date_And_Time");

    //get all medication based on selected patient
    $select = mysqli_query($conn, "SELECT ilc.Payment_Cache_ID, c.Consultation_Date_And_Time, emp.Employee_Name from 
                                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_consultation c, tbl_employee emp where
                                -- ilc.Payment_Cache_ID = $Payment_Cache_ID and
                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                c.consultation_id = pc.consultation_id and
                                emp.Employee_ID = ilc.Consultant_ID and
                                ilc.Check_In_Type = 'Pharmacy' and
                                pc.Registration_ID = '$Registration_ID' group by c.consultation_id order by c.Consultation_Date_And_Time") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {

        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
    ?>

            <!----------------------------------------------------------------------------------------------------->
            <?php
            //  echo "<b style='font-size:200px'>$Payment_Cache_ID</b>";
            //                                    //get list of medications
            //                                   
            //                                    $sql_fetch_drugs = mysqli_query($conn,"select Product_Name, Doctor_Comment, Quantity,edited_quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
            //                                                            i.Item_ID = ilc.Item_ID and
            //                                                            Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
            //                                  
            //                                    
            //                                    echo $sql_fetch_drugs;
            //                                            echo "<b style='font-size:200px'>$Status</b>";
            //                                   
            //                                
            ?>


            <!------------------------------------------------------------------------------------------------------>
            <br />
            <table width="100%">
                <tr>
                    <td width="10%" style="text-align: right;"><b>Doctor Name</b></td>
                    <td>&nbsp;&nbsp;<?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
                    <td width="13%" style="text-align: right;"><b>Consultation Date : </b></td>
                    <td>&nbsp;&nbsp;<?php echo $data['Consultation_Date_And_Time']; ?></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%">
                            <tr>
                                <td width="5%"><b>SN</b></td>
                                <td><b>MEDICATION NAME</b></td>
                                <td width="10%"><b>QUANTITY</b></td>
                                <td width="20%"><b>DOSAGE</b></td>
                                <td width="10%"><b>STATUS</b></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                            </tr>
                            <?php
                            //get list of medications
                            $temp = 0;
                            // die("SELECT Product_Name, Doctor_Comment, Quantity,edited_quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
                            // i.Item_ID = ilc.Item_ID and Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type = 'Pharmacy'");


                            $medics = mysqli_query($conn, "SELECT Product_Name, Doctor_Comment, Quantity,edited_quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
                                                            i.Item_ID = ilc.Item_ID and
                                                            Payment_Cache_ID = '$Payment_Cache_ID' and Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
                            $numz = mysqli_num_rows($medics);
                            if ($numz > 0) {
                                echo "<tr><td colspan='5'><hr></td></tr>";
                                while ($rows = mysqli_fetch_assoc($medics)) {
                                    $item_status = $rows['Status'];
                                    if ($item_status == 'active' || $item_status == 'approved' || $item_status == 'removed' ) {
                                        $Status = 'Not Taken';

                                        //                                                if($rows['Status']=='1') $Status="Taken";
                                    }else if($item_status == 'partial dispensed'){
                                        $Status = 'Partial Dispensed';
                                    } 
                                    else {
                                        $Status = 'Taken';
                                    }
                                    $Quantity = $rows['Quantity'];
                                    if ($Quantity <= 0) {
                                        $Quantity = $rows['edited_quantity'];
                                    }
                            ?>
                                    <tr>
                                        <td><?php echo ++$temp; ?></td>
                                        <td><?php echo $rows['Product_Name']; ?></td>
                                        <td><?php echo $rows['edited_quantity']; ?></td>
                                        <td><?php echo $rows['Doctor_Comment']; ?></td>
                                        <td><?php echo $Status; ?></td>
                                    </tr>
                            <?php
                                }
                                echo "<tr><td colspan='5'><hr></td></tr>";
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