
<?php

include("./includes/connection.php");

$Guarantor_Name = "All";
$toDate = '';
$fromDate = '';

if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $sponsorName = $_GET['Sponsor'];
    $SubCategory = $_GET['SubCategory'];
    $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Procedure' AND ilc.Status = 'served'";
    if ($sponsorName != 'All') {
        $filter .=" AND pp.Sponsor_ID='$sponsorName'";

        $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$sponsorName'") or die(mysqli_error($conn));

        $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
    }

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
}

$htm = "<center><img src='branchBanner/branchBanner1.png' width='100%' ></center>";
$htm.="<p align='center'><b>NUMBER OF PROCEDURE DONE REPORT <br/><br/>FROM</b> " . $fromDate . " <b>TO</b> " . $toDate . ""
        . "<br/><br/>"
        . "<b>Sponsor:</b>$Guarantor_Name"
        . "</p>";

 $sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter $filterSub GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
// echo $sqlCat;exit; 
    $querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn));
 if(mysqli_num_rows($querySubcategory) >0 ){
    while ($row1 = mysqli_fetch_array($querySubcategory)) {
        $subcategory_name = $row1['Item_Subcategory_Name'];
        $subcategory_id = $row1['Item_Subcategory_ID'];
        $filter .="  AND i.Item_Subcategory_ID='$subcategory_id'";


        $number_of_item = "SELECT i.Product_Name,i.Item_ID,Guarantor_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter GROUP BY Product_Name";

        // $htm.= $number_of_item;exit;
        $number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));

        $sn = 1;
        $grandTotal = 0;
     
        if(mysqli_num_rows($number_of_item_results) >0 ){
        
        $htm .= "<h1 style='margin:10px 0px 10px 0px;width:100%;border-bottom:1px solid #A3A3A3;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($subcategory_name)) . "</h1>";

    $htm .= "<table class='display' id='numberTests' width='100%'> 
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Procedure Name</th>
                    <th style='text-align:left;width:15%'>Quantity</th>
                </tr>
                <tr>
                <td colspan='3'><hr/></td>
                </tr>
            </thead>";
        while ($row = mysqli_fetch_assoc($number_of_item_results)) {

            $number_item_count = "SELECT i.Item_ID FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_Sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID $filter AND ilc.Item_ID='" . $row['Item_ID'] . "'";

            //die($number_item_count);
            $number_of_item_count_results = mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
            $itemCount = mysqli_num_rows($number_of_item_count_results);
            //$itemCount=  mysqli_fetch_assoc($number_of_item_count_results)['Number_Of_Item'];

            $htm.= '<tr>';
            $htm.= '<td>' . $sn++ . '</td>';
            $htm.= '<td>' . $row['Product_Name'] . '</td>';
            $htm.= '<td style="text-align:left;">' . $itemCount . '</td>';
            //$htm.= '<td style="text-align:left;">'.$row['Guarantor_Name'].'</td>';//
            $htm.= '</tr>';
        }
         $htm.= '</table><br/>';
        }
    }
 }



include("MPDF/mpdf.php");

$mpdf = new mPDF();

$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
?>



