<?php
@session_start();
include("includes/connection.php");

$Start_Date = filter_input(INPUT_GET, 'Start_Date');
$End_Date = filter_input(INPUT_GET, 'End_Date');
$itemtype = filter_input(INPUT_GET, 'itemtype');
$Search_Value = filter_input(INPUT_GET, 'Search_Value');
$Search_batch = filter_input(INPUT_GET, 'Search_batch');

$filter = "   tpo.Expire_Date <= CURDATE() ";
if (isset($Start_Date) && !empty($Start_Date) && isset($End_Date) && !empty($End_Date)) {
    $filter = "   tpo.Expire_Date BETWEEN '" . $Start_Date . "' AND '" . $End_Date . "'";
}

if (!empty($Search_Value)) {
    $filter .= " AND  Product_Name LIKE '%$Search_Value%'";
}

if (!empty($Search_batch)) {
    $filter .= " AND  Grn_Purchase_Order_ID ='$Search_batch'";
}

$filtergrn=array('',0);
$filterdate=array('','0000-00-00');

$sql_num = mysqli_query($conn,"SELECT Product_Name,Expire_Date,batch_no,Manufacture_Date FROM tbl_grn_open_balance_items tpo JOIN tbl_items ti ON ti.Item_ID=tpo.Item_ID WHERE $filter ORDER BY Expire_Date DESC LIMIT 100") or die(mysqli_error($conn));

if(isset($_GET['Start_Date'])){ ?>

<legend style="background-color:#006400;color:white;padding:8px;" align=right><b>List of expired/Expiring items ~ 
    <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?>
    </b>
</legend>
<?php
}
$temp = 1;
echo '<tr><td colspan="5"><hr></td></tr>';
echo '<center><table width = 100% border=0>';
echo "<tr id='thead'>
			<td width=5% style='text-align: left;'><b>Sn</b></td>
			<td width=30% style='text-align: left;'><b>Item Name</b></td>
			<td width=25%><b>GRN ID (Batch No)</b></td>
			<td width=25%><b>Manufacture Date</b></td>
			<td width=20%><b>Expire Date</b></td>
		    </tr>";
echo '<tr><td colspan="4"><hr></td></tr>';

while ($row = mysqli_fetch_array($sql_num)) {
 if( !in_array($row['Expire_Date'], $filterdate)){   
    if (!empty($Search_batch)) {
        if ($row['Grn_Purchase_Order_ID'] == $Search_batch) {
            echo " <tr>
                    <td>$temp</td>
                    <td>" . $row['Product_Name'] . "</td>
                    <td>" . $row['Grn_Purchase_Order_ID'] . "</td>
                    <td>" . $row['Expire_Date'] . "</td>
                </tr>";
            $temp++;
        }
    } else {
        echo " <tr>
                    <td>$temp</td>
                    <td>" . $row['Product_Name'] . "</td>
                    <td>" . $row['batch_no'] . "</td>
                    <td>" . $row['Manufacture_Date'] . "</td>
                    <td>" . $row['Expire_Date'] . "</td>
                </tr>";
        $temp++;
    }
 }
}

echo '</table>';

