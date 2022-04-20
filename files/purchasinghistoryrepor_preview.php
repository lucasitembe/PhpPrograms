<?php
session_start();
include_once("./includes/connection.php");
include_once("./functions/database.php");

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

if(isset($_GET['Item_ID'])){
    $Item_ID = $_GET['Item_ID'];
}else{
    $Item_ID = '';
}

//get item name
$select = mysqli_query($conn,"select Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
$nums = mysqli_num_rows($select);
if($nums > 0){
    while ($row = mysqli_fetch_array($select)) {
        $Product_Name = $row['Product_Name'];
    }
}else{
    $Product_Name = '';
}

if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
}else{
    $Sub_Department_ID = '';
}


//get sub department name
$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
    while($row = mysqli_fetch_array($select)){
        $Sub_Department_Name = $row['Sub_Department_Name'];
    }
}else{
    $Sub_Department_Name = '';
}

    $htm = "<table width ='100%' height = '30px'>";
    $htm .= "<tr>";
    $htm .= "<td>";
    $htm .= "<img src='./branchBanner/branchBanner.png' width=100%>";
    $htm .= "</td>";
    $htm .= "</tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2>PURCHASING HISTORY</h2></td>";
    $htm .= "</tr></table><br/>";

    $htm .= "<br/><br/>";

    $htm .= "<table>";
    $htm .= "<tr><td>Start Date : </td><td> {$Start_Date} </td></tr>";
    $htm .= "<tr><td>End Date : </td><td> {$End_Date} </td></tr>";
    $htm .= "<tr><td>Item Name : </td><td> {$Product_Name} </td></tr>";
    $htm .= "</table>";

    $htm .= "<br/><br/>";

    $htm .= "<center>";
    $htm .= "<table width='100%' border='0'><tbody><tr id='thead'>";
    $htm .= "<td width='20%'><b>Supplier</b></td>";
    $htm .= "<td width='20%'><b>Purchase Date</b></td>";
    $htm .= "<td width='20%'><b>Buying Price</b></td>";
    $htm .= "</tr><tr>";
    $htm .= "<td colspan='3'><hr></td></tr>";
    
    if ($Item_ID != '') {
        $Start_End_Date_Statement = "";
        if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != ""){
            $Start_Date = Get_Day_Beginning($Start_Date);
            $End_Date = Get_Day_Ending($End_Date);
            $Start_End_Date_Statement = "AND gpo.Created_Date_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }
        $Item_Purchase_History_SQL = mysqli_query($conn,"SELECT s.Supplier_Name, gpo.Created_Date_Time, poi.Buying_Price
                                                            FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo, tbl_supplier s
                                                            WHERE poi.Buying_Price IS NOT NULL
                                                            AND poi.Grn_Purchase_Order_ID IS NOT NULL
                                                            AND poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                                            AND gpo.supplier_id = s.Supplier_ID
                                                            AND poi.Item_ID = '$Item_ID'
                                                            {$Start_End_Date_Statement}
                                                            ORDER BY poi.Grn_Purchase_Order_ID DESC
                                                            LIMIT 10") or die(mysqli_error($conn));
        $Item_Purchase_History_Num = mysqli_num_rows($Item_Purchase_History_SQL);
        if($Item_Purchase_History_Num > 0){
            while($Item_Purchase_History_Data = mysqli_fetch_array($Item_Purchase_History_SQL)){
                $htm .= "<tr>";
                $htm .= "<td>".$Item_Purchase_History_Data['Supplier_Name']."</td>";
                $htm .= "<td>".$Item_Purchase_History_Data['Created_Date_Time']."</td>";
                $htm .= "<td>".$Item_Purchase_History_Data['Buying_Price']."</td>";
                $htm .= "</tr>";
            }
        } else {
            $htm .= "<tr><td style='text-align: center;' colspan='3'><h1>No Purchasing History Found</h1></td></tr>";
        }
    }
    
    $htm .= "</tbody></table>";
    $htm .= "</center>";

    include("./functions/makepdf.php");
?>