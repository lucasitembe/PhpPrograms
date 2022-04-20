<style type="text/css">
.patientList td,
th {
    text-align: center;
    font-size: 10px;
    background-color: white;

}

#thead {
    text-align: center;
    font-size: 10px;
    background-color: #ccc;
}
</style>
<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterClinic = ' ';
$filterIn = ' ';
$filterSub = ' ';
$filterDiagnosis = ' ';
$filterbilltype = '';
@$fromDate = $_POST['fromDate'];
@$toDate = $_POST['toDate'];
@$Filter_Category = $_POST['Filter_Category'];
// @$diagnosis_time = $_POST['diagnosis_time'];
@$medication_category = $_POST['medication_category'];
// @$start_age = $_POST['start_age'];
// @$end_age = $_POST['end_age'];
@$Medication_ID = $_POST['Medication_ID'];
@$diagnosis_type = $_POST['diagnosis_type'];
@$Clinic_ID = $_POST['Clinic_ID'];
@$Disease_id_subcategory = $_POST['Disease_id_subcategory'];

@$Diagnosis_ID = $_POST['Diagnosis_ID'];
@$sponsor = $_POST['sponsor'];


$filtermedication = " ";
if ($Medication_ID != 'all') {
    $filtermedication = " AND ilc.Item_ID=$Medication_ID ";
}


$filterbyclinic = "";
if ($Clinic_ID != 'all') {
    $filterbyclinic = " AND ilc.Sub_Department_ID='$Clinic_ID'";
} else {
    $filterbyclinic = "";
}


?>

<?php
// die($medication_category);
if ($medication_category == "opd_medication") {
    $filterbilltype = " AND pc.Billing_Type LIKE '%Outpatient%' ";
} else if ($medication_category == "ipd_medication") {
    $filterbilltype = " AND pc.Billing_Type LIKE '%Inpatient%'";
} else {
}
?>
<div id="type1_report" style="display:none; background-color:white;">

</div>
<div id="type2_report" style="background:white;">
    <?php

    echo "<br><hr><table width='100%' class='patientList'>";
    ?><thead id='thead'>
        <tr>
            <th id='thead' rowspan='4'>#</th>
            <th id='thead' style='width:30%;' rowspan='4'>MEDICATION </th>
            <th id='thead' style='width:10%;' rowspan='4'>QUANTITY DISPENSED</th>
            <th id='thead' style='width:10%;' rowspan='4'>REMAINING BALANCE</th>
            <th id='thead' style='width:10%;' rowspan='4'>BUYING PRICE</th>
            <th id='thead' style='width:10%;' rowspan='4'>TOTAL BUYING PRICE </th>
            <th id='thead' style='width:10%;' rowspan='4'>REMAINING BALANCE B/PRICE</th>
            <th id='thead' style='width:10%;' rowspan='4'>TOTAL SELLING PRICE Q/DISPENSED</th>
            <th id='thead' style='width:10%;' rowspan='4'>TOTAL SELLING PRICE Q/REMAINING</th>
            <th id='thead' style='width:10%;' rowspan='4'>TOTAL PROFIT </th>
            <!-- <th id='thead' style='width:30%;' colspan='6'><b>NUMBER OF PATIENTS</b></th> -->
        </tr>
        <!-- <tr>
            <th colspan='6' style='text-align:center;'> Age From $start_age To $end_age</th>
        </tr>
        <tr></tr> 
        <tr>
            <th style='width:10%;'>Male</th>
            <th style='width:10%;'>Female</th>
            <th style='width:10%;'>Total</th>
        </tr> -->
    </thead>
    <tbody>
        <?php
    $total_male_count = 0;
    $total_female_count = 0;
    $disease_name = "";
    $disease_ID = "";
    $num_count = 1;
    $total_qty = 0;

    // $servQuery = "SELECT MAX(sl.Movement_Date_Time),sl.Post_Balance,Price,pc.consultation_id,i.Product_Name,pc.Round_ID,pc.Registration_ID,ilc.Payment_cache_ID, COUNT(DISTINCT(pr.Registration_ID))  AS total,SUM(DISTINCT(Edited_Quantity)) AS Edited_Quantity,SUM(DISTINCT(Price * Edited_Quantity)) AS selling_price,i.Item_ID, pr.Registration_ID FROM tbl_item_list_cache ilc JOIN tbl_payment_cache pc ON ilc.Payment_cache_ID = pc.Payment_cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID JOIN tbl_items i ON i.Item_ID = ilc.Item_ID JOIN tbl_stock_ledger_controler sl ON sl.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Registration_ID=pr.Registration_ID  WHERE Dispense_Date_Time BETWEEN DATE('$fromDate') AND DATE('$toDate') AND pc.Sponsor_ID = '$sponsor' AND ilc.Check_In_Type = 'pharmacy' AND ilc.Status IN('dispensed','paid','partial dispensed') AND Edited_Quantity !=0 $filtermedication $filterbilltype $filterbyclinic  GROUP BY i.Item_ID";



$servQuery="SELECT sl.Movement_Date_Time,Post_Balance,Price,pc.consultation_id,i.Product_Name,pc.Round_ID,pc.Registration_ID,ilc.Payment_cache_ID,COUNT(DISTINCT(pr.Registration_ID)) AS total,SUM(DISTINCT(ilc.Edited_Quantity)) AS Edited_Quantity,SUM(DISTINCT(Price *Edited_Quantity)) AS selling_price,i.Item_ID,pr.Registration_ID FROM tbl_item_list_cache ilc JOIN tbl_payment_cache pc ON ilc.Payment_cache_ID = pc.Payment_cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID JOIN tbl_items i ON
i.Item_ID = ilc.Item_ID JOIN tbl_stock_ledger_controler sl ON sl.Item_ID = i.Item_ID JOIN tbl_payment_cache pp ON pp.Registration_ID = pr.Registration_ID WHERE sl.Movement_Date_Time =( SELECT MAX(sl.Movement_Date_Time) FROM tbl_stock_ledger_controler sl WHERE Item_ID = i.Item_ID AND Sub_Department_ID = $Clinic_ID GROUP BY i.Item_ID) AND Dispense_Date_Time BETWEEN DATE('$fromDate') AND DATE('$toDate') AND pc.Sponsor_ID = '$sponsor' AND ilc.Status IN('dispensed','paid','partial dispensed') AND Edited_Quantity != 0 $filtermedication $filterbilltype $filterbyclinic  GROUP BY i.Item_ID";
    // "SELECT pc.consultation_id,i.Product_Name,pc.Round_ID,pc.Registration_ID,ilc.Payment_cache_ID, COUNT(DISTINCT(pr.Registration_ID))  AS total,SUM(DISTINCT(Edited_Quantity)) AS Edited_Quantity,SUM(DISTINCT(Price * Edited_Quantity)) AS selling_price,i.Item_ID, pr.Registration_ID, pr.Gender,COUNT(DISTINCT(CASE WHEN pr.Gender='Male' THEN 1 END)) As male_count,COUNT(DISTINCT(CASE WHEN pr.Gender='Female' THEN 1 END)) As female_count,TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,Dispense_Date_Time) AS age FROM tbl_item_list_cache ilc JOIN tbl_payment_cache pc ON ilc.Payment_cache_ID = pc.Payment_cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID JOIN tbl_items i ON i.Item_ID = ilc.Item_ID JOIN tbl_payment_cache pp ON pp.Registration_ID=pr.Registration_ID  WHERE Dispense_Date_Time BETWEEN DATE('$fromDate') AND DATE('$toDate') AND pc.Sponsor_ID = '$sponsor' AND ilc.Check_In_Type = 'pharmacy' AND ilc.Status IN('dispensed','paid','partial dispensed') AND Edited_Quantity !=0 $filtermedication $filterbilltype $filterbyclinic  GROUP BY i.Item_ID,pr.Gender HAVING age BETWEEN $start_age AND $end_age"
//     SELECT sl.Movement_Date_Time, Post_Balance,
//     Price,
//     pc.consultation_id,
//     i.Product_Name,
//     pc.Round_ID,
//     pc.Registration_ID,
//     ilc.Payment_cache_ID,
//     COUNT(DISTINCT(pr.Registration_ID)) AS total,
//     SUM(DISTINCT(ilc.Edited_Quantity)) AS Edited_Quantity,
//     SUM(
//         DISTINCT(Price * Edited_Quantity)
//     ) AS selling_price,
//     i.Item_ID,
//     pr.Registration_ID
// FROM
//     tbl_item_list_cache ilc
// JOIN tbl_payment_cache pc ON
//     ilc.Payment_cache_ID = pc.Payment_cache_ID
// JOIN tbl_patient_registration pr ON
//     pr.Registration_ID = pc.Registration_ID
// JOIN tbl_items i ON
//     i.Item_ID = ilc.Item_ID
// JOIN tbl_stock_ledger_controler sl ON
//     sl.Item_ID = i.Item_ID
// JOIN tbl_payment_cache pp ON
//     pp.Registration_ID = pr.Registration_ID
// WHERE sl.Movement_Date_Time=(SELECT MAX(sl.Movement_Date_Time) FROM  tbl_stock_ledger_controler sl WHERE Item_ID=i.Item_ID AND Sub_Department_ID = 4 GROUP BY
//     i.Item_ID) AND ilc.Transaction_Date_And_Time=(SELECT MAX(ilc.Transaction_Date_And_Time) FROM  tbl_item_list_cache ilc WHERE ilc.Item_ID=i.Item_ID AND Sub_Department_ID = 4 GROUP BY
//     i.Item_ID) AND
//     Dispense_Date_Time BETWEEN DATE('2020/02/01 11:41') AND DATE('2020/02/15 11:41') AND pc.Sponsor_ID = '8' AND ilc.Check_In_Type = 'pharmacy' AND ilc.Status IN(
//         'dispensed',
//         'paid',
//         'partial dispensed'
//     ) AND Edited_Quantity != 0 AND ilc.Sub_Department_ID = '4'
// GROUP BY
//     i.Item_ID
    // die($servQuery);
    $select_services = mysqli_query($conn, $servQuery) or die(mysqli_error($conn));

    $num = 0;
    $Quantitys = 0;
    $Buying_Price = 0;
    $selling_price = 0;
    $Profitper_Item = 0;
    if (mysqli_num_rows($select_services) > 0) {
        while ($service_rw = mysqli_fetch_assoc($select_services)) {
            $consultation_id = $service_rw['consultation_id'];
            $Registration_ID = $service_rw['Registration_ID'];
            $Edited_Quantity = $service_rw['Edited_Quantity'];
            $Item_ID = $service_rw['Item_ID'];
            $Product_Name = $service_rw['Product_Name'];
            $selling_price = $service_rw['selling_price'];
            $Payment_cache_ID = $service_rw['Payment_cache_ID'];
            $Item_Balance = $service_rw['Post_Balance'];
            $selling_price_remaining = $service_rw['Post_Balance']*$service_rw['Price'];
            // $male_count = $service_rw['male_count'];
            // $female_count = $service_rw['female_count'];


            $ActualBuying_Price = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Buying_Price FROM tbl_grn_open_balance_items WHERE Item_ID='$Item_ID' ORDER BY Open_Balance_Item_ID DESC LIMIT 1"))['Buying_Price'];


            $Buyingprice = $ActualBuying_Price * $Edited_Quantity;

            $totalremainingbPrice=$service_rw['Post_Balance'] * $ActualBuying_Price;
            $Patients = 0;
            $num++;
            echo "<tr>  
                <td id='tdalign'>$num</td>
                <td style='text-align:left;'>$Product_Name</td>
                ";


            $Profitper_Item = $selling_price - $Buyingprice;
            echo "<td id='tdalign'> " . number_format($Edited_Quantity) . "</td>
                    <td id='tdalign'> " . number_format($Item_Balance) . "</td>
                <td style='text-align:right;'>" . number_format($ActualBuying_Price, 2) . "/=</td>
                <td style='text-align:right;'>" . number_format($Buyingprice, 2) . "/=</td>
                <td style='text-align:right;'>" . number_format($totalremainingbPrice, 2) . "/=</td>
                <td style='text-align:right;'>" . number_format($selling_price, 2) . "/=</td>
                <td style='text-align:right;'>" . number_format($selling_price_remaining, 2) . "/=</td>
                <td style='text-align:right;'>" . number_format($Profitper_Item, 2) . "/=</td>";

            // echo " <td id='tdalign' class='rowlist' onclick='view_patent_dialog($Item_ID, \"$Product_Name\", \"Male\")'>$male_count";
            // echo "</td>";
            // echo " <td id='tdalign' class='rowlist' onclick='view_patent_dialog($Item_ID, \"$Product_Name\", \"Female\")'>$female_count";
            // echo "</td>";


            //Total number of patient
            $Total_patient =  $male_count + $female_count;
            // echo " <td id='tdalign' class='rowlist' onclick='view_patent_dialog($Item_ID, \"$Product_Name\", \"All\")'>$Total_patient</td>";
            echo "
            </tr>";

            $Total_Quantitys += $Edited_Quantity;
            $total_female_counts += $female_count;
            $total_male_counts += $male_count;
            $Total_Patients = $total_male_counts + $total_female_counts;
            $Total_selling += $selling_price;
            $Total_buying += $Buyingprice;
            $Total_profit += $Profitper_Item;
        }
    } else {
        echo "<tr>
                    <td colspan='9' style='color:red; text-align:center;'><b>No result found</b></td>                            
            </tr>";
    }

    echo "<tr id='headerstyle'>
                <th colspan='2'>Total</th>
                <th id='tdalign'> " . number_format($Total_Quantitys) . "</th>
                <th></th>
                <th id='tdalign'>" . number_format($Total_buying, 2) . "/=</th>
                <th id='tdalign'>" . number_format($Total_selling, 2) . "/=</th>
                <th id='tdalign'>" . number_format($Total_profit, 2) . "/=</th>
            </tr>"
    ?>
    </tbody>
    </table>

    <!-- <th colspan='' id='tdalign' >" . $total_male_counts . " </th>
                <th colspan='' id='tdalign' >" . $total_female_counts . " </th>
                <th id='tdalign'>$Total_Patients</th> -->