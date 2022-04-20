<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include("./includes/connection.php");

include_once("./functions/database.php");
include_once("./functions/stockledger.php");
include_once("./functions/items.php");

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = mysqli_real_escape_string($conn,$_GET['Sub_Department_ID']);
} else {
    $Sub_Department_ID = 0;
}

if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
} else {
    $Item_Category_ID = 0;
}

if (isset($_GET['Classification'])) {
    $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
} else {
    $Classification = 0;
}

if (isset($_GET['Search_Value'])) {
    $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
} else {
    $Search_Value = '';
}

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

$num_records = "100";
if (isset($_GET['num_records'])) {
    $num_records = mysqli_real_escape_string($conn,$_GET['num_records']);
}

$limit = "";

if ($num_records == '100')
    $limit = " LIMIT 100";
if ($num_records == '500')
    $limit = " LIMIT 500";
if ($num_records == '1000')
    $limit = " LIMIT 1000";
if ($num_records == 'all')
    $limit = "";

if (isset($_GET['FilterCategory'])) {
    if (strtolower($Classification) == "all") {
        $sql_select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Code, i.Unit_Of_Measure, i.Product_Name
                                        FROM tbl_items i
                                        WHERE  i.Can_Be_Stocked = 'yes'
                                        AND LOWER(i.Status)='available'
                                        ORDER BY i.Product_Name
                                        $limit") or die(mysqli_error($conn));
    } else {
        $sql_select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Code, i.Unit_Of_Measure, i.Product_Name
                                        FROM tbl_items i
                                        WHERE  i.Can_Be_Stocked = 'yes'
                                        AND LOWER(i.Status)='available'
                                        AND i.Classification = '{$Classification}'
                                        ORDER BY i.Product_Name
                                        $limit") or die(mysqli_error($conn));
    }
} else {
    if (strtolower($Classification) == "all") {
        $Product_Name_Search = Prepare_For_Like_Operator($Search_Value);
        $sql_select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Code, i.Unit_Of_Measure, i.Product_Name
                                        FROM tbl_items i
                                        WHERE  i.Can_Be_Stocked = 'yes'
                                        AND LOWER(i.Status)='available'
                                        AND i.Product_Name like '{$Product_Name_Search}'
                                        ORDER BY i.Product_Name
                                        $limit") or die(mysqli_error($conn));
    } else {
        $Product_Name_Search = Prepare_For_Like_Operator($Search_Value);
        $sql_select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Code, i.Unit_Of_Measure, i.Product_Name
                                        FROM tbl_items i
                                        WHERE  i.Can_Be_Stocked = 'yes'
                                        AND LOWER(i.Status)='available'
                                        AND i.Product_Name like '{$Product_Name_Search}'
                                        AND i.Classification = '{$Classification}'
                                        ORDER BY i.Product_Name
                                        $limit") or die(mysqli_error($conn));
    }
}
?>
<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
    $Grand_Stock = 0;
    $Title = '<tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td width="3%"><b>S/N</b></td>
                        <td width="5%"><b>ITEM CODE</b></td>
                        <td ><b>ITEM NAME</b></td>
                        <td width="5%"><b>UOM</b></td>
                        <td width="10%" style="text-align: right;"><b>B/F</b></td>
                        <td width="10%" style="text-align: right;"><b>INWARD</b></td>
                        <td width="10%" style="text-align: right;"><b>OUTWARD</b></td>
                        <td width="10%" style="text-align: right;"><b>BALANCE</b></td>
                        <td width="10%" style="text-align: right;"><b>UNIT PRICE</b></td>
                        <td width="10%" style="text-align: right;"><b>TOTAL</b></td>
                    </tr>
                    <tr><td colspan="9"><hr></td></tr>';
    echo $Title;
    $General_Grand_Total = 0;
    $temp = 1;
    while ($row = mysqli_fetch_array($sql_select)) {

        $Product_Name = $row['Product_Name'];
        $Product_Code = $row['Product_Code'];
        $Unit_Of_Measure = $row['Unit_Of_Measure'];
        $Item_ID = $row['Item_ID'];


        $Total_Average_Price = Get_Last_Buy_Price($Item_ID); //Tumeamua kutumia last buy price kwa mda kabla tunaangalia ni formula gani itumike kupata average price
        $Grand_Total = 0;

        $select = mysqli_query($conn,"SELECT * FROM tbl_stock_ledger_controler
                           WHERE Movement_Date BETWEEN '$Start_Date' AND '$End_Date'
                           AND Item_ID = '$Item_ID'
                           AND Sub_Department_ID = '$Sub_Department_ID'
                           ORDER BY Controler_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select); //echo $no; exit();
        if ($no > 0) {
            $controler = 'yes';
            $Total_inward = 0;
            $Total_outward = 0;
            $Running_Balance = 0;

            while ($data = mysqli_fetch_array($select)) {
                $Movement_Type = $data['Movement_Type'];
                $Internal_Destination = $data['Internal_Destination'];
                $External_Source = $data['External_Source'];


                $Movement_Date = $data['Movement_Date'];
                $Movement_Date_Time = $data['Movement_Date_Time'];
                $Registration_ID = $data['Registration_ID'];

                if ($controler == 'yes') {
                    $Pre_Balance = $data['Pre_Balance'];
                    $controler = 'no';
                }

                if ($Movement_Type == 'From External') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Without Purchase') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Open Balance') {
                    $Total_inward = $data['Post_Balance'];
                    $Total_outward = 0;
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Issue Note') {
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Dispensed') {
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'GRN Agains Issue Note') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Disposal') {
                    $Total_inward += 0;
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Return Outward') {
                    $Total_inward += 0;
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Return Inward') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Total_outward += 0;
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Return Inward Outward') {
                    $Total_inward += 0;
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Issue Note Manual') {
                    $Total_inward += 0;
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Stock Taking Under') {
                    $Total_inward += 0;
                    $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Stock Taking Over') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Total_outward += 0;
                    $Grand_Balance = $data['Post_Balance'];
                } else if ($Movement_Type == 'Received From Issue Note Manual') {
                    $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                    $Total_outward += 0;
                    $Grand_Balance = $data['Post_Balance'];
                }
            }

            if ($Grand_Balance > 0) {
                $Total = $Grand_Balance;

                echo "<tr><td ><b>" . $temp . "</b></td>";
                echo "<td ><b>{$Product_Code}</b></td>";
                echo "<td >{$Product_Name}</td>";
                echo "<td >{$Unit_Of_Measure}</td>";
                echo "<td style='text-align: right;'>{$Pre_Balance}</td>";
                echo "<td style='text-align: right;'>{$Total_inward}</td>";
                echo "<td style='text-align: right;'>{$Total_outward}</td>";
                echo "<td style='text-align: right;'>{$Grand_Balance}</td>";
                echo "<td style='text-align: right;'>" . number_format($Total_Average_Price) . "</td>";

                echo "<td style='text-align: right;'>" . number_format($Total * $Total_Average_Price) . "</td>";
                echo "</tr>";

                $temp++;
                if (($temp % 25) == 0) {
                    echo $Title;
                }
                $General_Grand_Total += ($Total * $Total_Average_Price);
            }
        } else {
            $select2 = mysqli_query($conn,"SELECT item_balance FROM tbl_items_balance
                                   WHERE Item_ID = '$Item_ID'
                                   AND Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $no2 = mysqli_num_rows($select2);
            $dat2 = mysqli_fetch_assoc($select2);
            if ($dat2['item_balance'] > 0) {
                $Total = $Grand_Balance;

                echo "<tr><td ><b>" . $temp . "</b></td>";
                echo"<td ><b>{$Product_Code}</b></td>";
                echo "<td >{$Product_Name}</td>";
                echo "<td >{$Unit_Of_Measure}</td>";
                echo "<td colspan='6'>No stock Movement</td>";
//                echo "<td style='text-align: right;'>0</td>";
//                echo "<td style='text-align: right;'>0</td>";
//                echo "<td style='text-align: right;'>0</td>";
//                echo "<td style='text-align: right;'>{$dat2['item_balance']}</td>";
//                echo "<td style='text-align: right;'>" . number_format($Total_Average_Price) . "</td>";
//
//                echo "<td style='text-align: right;'>" . number_format($dat2['item_balance'] * $Total_Average_Price) . "</td>";
                echo "</tr>";

                $temp++;
                if (($temp % 25) == 0) {
                    echo $Title;
                }
                $General_Grand_Total += ($dat2['item_balance'] * $Total_Average_Price);
                // $General_Grand_Total=0;
            }
        }
    }
    echo "<tr><td colspan='10' style='text-align: right;'><b>GRAND TOTAL : " . number_format($General_Grand_Total) . "</b></tr>";
    ?>
    <!--tr><td colspan="8"><hr></td></tr>
    <tr><td colspan="6" style="text-align: right;"><b>ESTIMATED GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Grand_Stock); ?></b></td></tr>
<tr><td colspan="8"><hr></td></tr-->
</table>