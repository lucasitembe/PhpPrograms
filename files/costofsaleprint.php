<?php
@session_start();
include("./includes/connection.php");
include_once("./functions/items.php");

$classifications = array();
foreach (Get_Item_Classification() as $value) {
    $classifications[] = $value['Name'];
}


$Start_Date = filter_input(INPUT_GET, 'Start_Date');
$End_Date = filter_input(INPUT_GET, 'End_Date');
$Item_Classification = filter_input(INPUT_GET, 'Item_Classification');
$Sub_Department_ID = filter_input(INPUT_GET, 'Sub_Department_ID');

//echo $Start_Date.' '.$End_Date.' '.$Item_Category_ID.' '.$Sub_Department_ID;

$filterIss = " AND Issue_Date BETWEEN '$Start_Date' AND '$End_Date' AND rqi.Item_Status='received' and i.Item_Type !='Pharmacy'";
$filterIssManual = " AND DATE(Created_Date_And_Time) BETWEEN '$Start_Date' AND '$End_Date' AND iss.status='saved' and i.Item_Type !='Pharmacy'";
$filterIssPharmacy = " AND DATE(Dispense_Date_Time) BETWEEN '$Start_Date' AND '$End_Date' AND il.Status='dispensed'";

$SubDepartmentName='ALL DEPARTMENTS';

if (!empty($Sub_Department_ID) && $Sub_Department_ID !== 'All') {
    $filterIss .="  AND rq.Store_Need = '$Sub_Department_ID'";
    $filterIssManual .=" AND iss.Store_Need = '$Sub_Department_ID'";
    $filterIssPharmacy .=" AND il.Sub_Department_ID = '$Sub_Department_ID'";
    
    $Sub_Department_Query = "SELECT sd.Sub_Department_Name
                                                                FROM tbl_sub_department sd WHERE Sub_Department_ID='$Sub_Department_ID'";

$SubDepartmentName = mysqli_fetch_assoc(mysqli_query($conn,$Sub_Department_Query))['Sub_Department_Name'];
}//$SubDepartmentName

   $data = "<table width ='100%' >
		    <tr>
                    <td width ='100%' > <img src='./branchBanner/branchBanner.png'></td></tr>
		    <tr><td style='text-align: center;'><span><b>COST OF SALE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>" . strtoupper($SubDepartmentName) . "</b></span></td></tr>
                     <tr><td style='text-align: center;'><b>From </b>".$Start_Date." <b> To</b> ".$End_Date."</td></tr>
           </table>
		    ";


$data .= "
        <table width='100%'>
          <tr><td colspan='3'><hr></td></tr>
          <tr>
           <td ><b>Classifications</b></td><td style='text-align:right;'><b>Cost Of Sale</b></td>
          </tr>
          <tr><td colspan='3'><hr></td></tr>
        ";

if (!empty($Item_Classification) && $Item_Classification != 'All') {
    $array = array($Item_Classification);
    $classifications = array_intersect($classifications, $array);
}

$grandTotal = 0;
foreach ($classifications as $value) {

    $costSale = 0;
    if ($value == 'Pharmaceuticals') {
        $sql_select_pharmacy = mysqli_query($conn,"SELECT il.Item_ID,IF(il.Edited_Quantity > 0, il.Edited_Quantity,il.Quantity) AS Qty,il.Price FROM tbl_item_list_cache il, tbl_items i WHERE
                                    i.Item_ID = il.Item_ID AND
                                    i.Classification='Pharmaceuticals' $filterIssPharmacy") or die(mysqli_error($conn));

        while ($rowPharm = mysqli_fetch_array($sql_select_pharmacy)) {
            $Quantity = $rowPharm['Qty'];
            $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($rowPharm['Item_ID']);

            if (!empty($last_buying_prices)) {
                $last_buying_price = $last_buying_prices[0]["Buying_Price"];
            } else {
                $last_buying_price = 0;
            }

            $costSale +=$Quantity * $last_buying_price;
        }
    } else {

        $sql_select = mysqli_query($conn,"SELECT rqi.Item_ID,rqi.Quantity_Received FROM tbl_issues iss, tbl_requisition rq, tbl_requisition_items rqi, tbl_items i WHERE
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    rqi.Requisition_ID = rq.Requisition_ID AND
                                    i.Item_ID = rqi.Item_ID AND
                                    i.Classification='$value' $filterIss") or die(mysqli_error($conn));

        $sql_select_manual = mysqli_query($conn,"SELECT ii.Item_ID,ii.Quantity_Issued FROM tbl_issuesmanual iss,  tbl_issuemanual_items ii, tbl_items i WHERE
                                    iss.Issue_ID = ii.Issue_ID AND
                                    i.Item_ID = ii.Item_ID AND
                                    i.Classification='$value' $filterIssManual") or die(mysqli_error($conn));

        while ($row = mysqli_fetch_array($sql_select)) {
            $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($row['Item_ID']);

            if (!empty($last_buying_prices)) {
                $last_buying_price = $last_buying_prices[0]["Buying_Price"];
                //print_r($last_buying_prices);
            } else {
                $last_buying_price = 0;
            }

            $costSale +=$row['Quantity_Received'] * $last_buying_price;
        }

        while ($rowManul = mysqli_fetch_array($sql_select_manual)) {
            $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($rowManul['Item_ID']);

            if (!empty($last_buying_prices)) {
                $last_buying_price = $last_buying_prices[0]["Buying_Price"];
                //print_r($last_buying_prices);
            } else {
                $last_buying_price = 0;
            }

            $costSale +=$rowManul['Quantity_Issued'] * $last_buying_price;
        }
    }

    $grandTotal +=$costSale;


    $data .= "<tr>";
    $data .= "<td style='font-size:19px '>" . $value . "</td>";
    $data .= "<td style='font-size:19px;text-align:right '>" . number_format($costSale,2) . "</td>";
    $data .= "</tr>";
}

$data .= "
          <tr><td colspan='3'><hr></td></tr>
          <tr>
           <td colspan='2' style='text-align:right;width:100%;font-size:19px'><b>Grand Total:&nbsp;&nbsp;&nbsp;" . number_format($grandTotal,2) . "</b></td>
          </tr>
          <tr><td colspan='3'><hr></td></tr>
        ";

$data .= "</table>";


include("MPDF/mpdf.php");

    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P'); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($data);
    $mpdf->Output();
    exit;
