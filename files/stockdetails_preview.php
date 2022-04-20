<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php

include("./includes/connection.php");

include_once("./functions/database.php");
include_once("./functions/stockledger.php");
include_once("./functions/department.php");
include_once("./functions/items.php");

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = mysqli_real_escape_string($conn,$_GET['Sub_Department_ID']);
    $Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);
} else {
    $Sub_Department_ID = 0;
    $Sub_Department_Name = '';
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

$htm = "<table width ='100%' height = '30px'>";
$htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
$htm .= "<tr>";
$htm .= "<td style='text-align: center;'><h2> STOCK DETAILS SUMMARY REPORT</h2></td>";
$htm .= "</tr>";
$htm .= "</table><br/>";

$htm .= "<table>";
$htm .= "<tr>";
$htm .= "<td><b>START DATE :</b> </td><td> {$Start_Date} </td>";
$htm .= "<td><b>END DATE :</b> </td><td> {$End_Date} </td>";
$htm .= "</tr>";
$htm .= "<tr>";
$htm .= "<td><b>LOCATION :</b> </td><td> {$Sub_Department_Name} </td>";
$htm .= "<td></td><td> </td>";
$htm .= "</tr>";
$htm .= "</table>";
$htm .= "<br/>";

$htm .= "<table id='items' width='100%' >";
$title = "<thead><tr>";
$title .= "<td width='5%'><b>S/N</b></td>";
$title .= "<td width='5%'><b>ITEM CODE</b></td>";
$title .= "<td ><b>ITEM NAME</b></td>";
$title .= "<td width='5%'><b>UOM</b></td>";
$title .= "<td width='6%' style='text-align: right;'><b>B/F</b></td>";
$title .= "<td width='10%' style='text-align: right;'><b>INWARD</b></td>";
$title .= "<td width='10%' style='text-align: right;'><b>OUTWARD</b></td>";
$title .= "<td width='10%' style='text-align: right;'><b>BALANCE</b></td>";
$title .= "<td width='10%' style='text-align: right;'><b>UNIT PRICE</b></td>";
$title .= "<td width='10%' style='text-align: right;'><b>TOTAL</b></td>";
$title .= "</tr></thead>";

$Grand_Stock = 0;
$temp = 1;
$htm .= $title;
$General_Grand_Total = 0;

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
    $no = mysqli_num_rows($select); //$htm .= $no; exit();
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

            $htm .= "<tr><td >" . $temp . "<b>.</b></td>";
            $htm .= "<td ><b>{$Product_Code}</b></td>";
            $htm .= "<td >".str_replace('/', ' / ', $Product_Name)."</td>";
            $htm .= "<td >{$Unit_Of_Measure}</td>";
            $htm .= "<td style='text-align: right;'>{$Pre_Balance}</td>";
            $htm .= "<td style='text-align: right;'>{$Total_inward}</td>";
            $htm .= "<td style='text-align: right;'>{$Total_outward}</td>";
            $htm .= "<td style='text-align: right;'>{$Grand_Balance}</td>";
            $htm .= "<td style='text-align: right;'>" . number_format($Total_Average_Price) . "</td>";
            $htm .= "<td style='text-align: right;'>" . number_format($Total * $Total_Average_Price) . "</td>";

            $htm .= "</tr>";
            $temp++;
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

            $htm .= "<tr><td >" . $temp . "<b>.</b></td>";
            $htm .= "<td ><b>{$Product_Code}</b></td>";
            $htm .= "<td >".str_replace('/', ' / ', $Product_Name)."</td>";
            $htm .= "<td >{$Unit_Of_Measure}</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>{$dat2['item_balance']}</td>";
            $htm .= "<td style='text-align: right;'>" . number_format($Total_Average_Price) . "</td>";

            $htm .= "<td style='text-align: right;'>" . number_format($dat2['item_balance'] * $Total_Average_Price) . "</td>";
            $htm .= "</tr>";

            $temp++;
            if (($temp % 25) == 0) {
                $htm .= $Title;
            }
            $General_Grand_Total += ($dat2['item_balance'] * $Total_Average_Price);
        }
    }
}
$htm .= "<tr><td colspan='10' style='text-align: right;'><b>GRAND TOTAL : " . number_format($General_Grand_Total) . "</b></tr>";
$htm .= "</table>";

$htm .= "<style>";
$htm .= "body { font-size: 14px; }";
$htm .= "table#items tr td { font-size: 10px; }";
$htm .= "table#items { border-collapse: collapse; border: 1px solid black; }";
$htm .= "table#items td { border: 1px solid black; padding:3px 5px; }";
$htm .= "</style>";

ini_set('memory_limit', '256M');
include("./functions/makepdf.php");
?>