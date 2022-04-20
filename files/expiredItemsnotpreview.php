<?php
@session_start();
include("includes/connection.php");

$Start_Date = filter_input(INPUT_GET, 'Start_Date');
$End_Date = filter_input(INPUT_GET, 'End_Date');
$itemtype = filter_input(INPUT_GET, 'itemtype');
$Search_Value = filter_input(INPUT_GET, 'Search_Value');
$Search_batch = filter_input(INPUT_GET, 'Search_batch');

$filter = "   tpo.Expire_Date <= CURDATE()  AND Grn_Status='RECEIVED'";
if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "   tpo.Expire_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' AND Grn_Status='RECEIVED'";
}

if (!empty($Search_Value)) {
    $filter .= " AND  Product_Name LIKE '%$Search_Value%'";
}if (!empty($Search_batch)) {
    $filter .= " AND  Grn_Purchase_Order_ID ='$Search_batch'";
}

//$data .= $filter;

$filtergrn=array('',0);
$filterdate=array('','0000-00-00');

$sql_num = mysqli_query($conn,"SELECT Product_Name,Grn_Purchase_Order_ID,Expire_Date FROM tbl_purchase_order_items tpo JOIN tbl_items ti ON ti.Item_ID=tpo.Item_ID  WHERE $filter order by Expire_Date desc LIMIT 100") or die(mysqli_error($conn));


$data = "<table width ='100%' class='nobordertable'>
		    <tr>
                    <td width ='100%' > <img src='./branchBanner/branchBanner.png'></td></tr>
		    <tr><td style='text-align: center;'><span><b>EXPIRING ITEMS</b></span></td></tr>";

if (isset($Start_Date) && !empty($Start_Date) && isset($End_Date) && !empty($End_Date)) {
                    $data .= " <tr><td style='text-align: center;'><b>From </b>" . $Start_Date . " <b> To</b> " . $End_Date . "</td></tr>";
}
          $data .= " </table>
		    ";

$temp = 1;
$data .= '<center><table width = 100% border=0>';
$data .= "<tr id='thead'>
			<td width=5% style='text-align: left;'><b>Sn</b></td>
			<td width=30% style='text-align: left;'><b>Item Name</b></td>
			<td width=25%><b>GRN ID (Batch No)</b></td>
			<td width=20%><b>Expire Date</b></td>
		    </tr>";

while ($row = mysqli_fetch_array($sql_num)) {
 if(!in_array($row['Grn_Purchase_Order_ID'], $filtergrn) && !in_array($row['Expire_Date'], $filterdate)){   
    if (!empty($Search_batch)) {
        if ($row['Grn_Purchase_Order_ID'] == $Search_batch) {
            $data .= " <tr>
                    <td>$temp</td>
                    <td>" . $row['Product_Name'] . "</td>
                    <td>" . $row['Grn_Purchase_Order_ID'] . "</td>
                    <td>" . $row['Expire_Date'] . "</td>
                </tr>";
            $temp++;
        }
    } else {
        $data .= " <tr>
                    <td>$temp</td>
                    <td>" . $row['Product_Name'] . "</td>
                    <td>" . $row['Grn_Purchase_Order_ID'] . "</td>
                    <td>" . $row['Expire_Date'] . "</td>
                </tr>";
        $temp++;
    }
 }
}

$data .= '</table>';

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();
