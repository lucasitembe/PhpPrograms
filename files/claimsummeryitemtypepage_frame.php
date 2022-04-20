<?php
include("./includes/connection.php");
$temp = 1;
$total = 0;
$Title = '';

if (isset($_GET['month'])) {
    $month = $_GET['month'];
} else {
    $month = '';
}

if (isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}


$timestamp = strtotime("$month $year");
$Start_Date = date('Y-m-01', $timestamp);
$End_Date = date('Y-m-t', $timestamp); // A leap 
//echo $first_second.' '.$last_second;
//echo $Sponsor_ID;

$Title = '<tr><td colspan="8"><hr></td></tr>
    			<tr>
                            <td width=5%><b>SN</b></td>
                            <td><b>Item Category</b></td>
	            	    <td width="15%" style="text-align: right;"><b>AMOUNT</b></td><td></td>
		        </tr>
			<tr><td colspan="8"><hr></td></tr>';


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}




echo '<center><table width =100% border=0>';
echo $Title;

$getCategory = mysqli_query($conn,"SELECT Item_Category_Name,item_category_id FROM tbl_item_category") or die(mysqli_error($conn));

// $rowCat = mysqli_fetch_array($getCategory);
// print_r($rowCat);
// exit();
while ($rowCat = mysqli_fetch_array($getCategory)) {
    $results = mysqli_query($conn,"
                    SELECT SUM((Price-Discount)*Quantity) AS CATSUM
                    from tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_bills bl,
                    tbl_items t, tbl_item_subcategory ts,tbl_sponsor sp WHERE 
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID  and
                                pp.Bill_ID = bl.Bill_ID  and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id and
                                bl.Bill_Date between '$Start_Date' and '$End_Date' and 
                                sp.Sponsor_ID = pp.Sponsor_ID and
                                pp.Sponsor_ID = '$Sponsor_ID' and
			        ts.item_category_id='" . $rowCat['item_category_id'] . "'  AND
                                invoice_status='0'") or die(mysqli_error($conn));


    $cat = mysqli_fetch_assoc($results);



    echo '<tr><td>' . $temp . '</td>';
    echo "<td>" . ucwords(strtolower($rowCat['Item_Category_Name'])) . "</td>";
    echo "<td style='text-align: right;'>" . number_format($cat['CATSUM']) . "</td>";
    echo "</tr>";

    $total += $cat['CATSUM'];
    $temp++;
}

echo "<tr><td colspan='3'><hr></td></tr>";
echo "<tr><td colspan='3' style='text-align: right;'><b> GRAND TOTAL : " . number_format($total) . "</td></tr>";
echo "<tr><td colspan='3'><hr></td></tr>";
?>
<div id="invoice_success"></div>
</table>

<?php
   if ($total > 0) {
    echo "<div style=''>
           <input type='submit' value='CREATE INVOICE' class='art-button-green' onclick='Create_Invoice()' />
           <input type='submit' value='PRIVIEW INVOICE' class='art-button-green' onclick='invoice_preview()' style='float:right'/>
          </div>";
}
?>
</center>


<script type="text/javascript">
    function Create_Invoice() {
        var datastring = 'total=<?= $total ?>&Sponsor_ID=<?= $Sponsor_ID ?>&Date_From=<?= $Start_Date ?>&Date_To=<?= $End_Date ?>';
         
        if(!confirm('Are you sure you want to create this invoice')){
            exit;
        }
         
        $.ajax({
            type: 'GET',
            url: 'create_sponsor_invoice.php',
            data: datastring,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
            },
            success: function (data) {
                alert(data);
                window.location = window.location.href;
            }, complete: function (jqXHR, textStatus) {
                $('#progressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#progressStatus').hide();
            }
        });
    }

    function invoice_preview() {
        window.open('invoice_preview.php?Sponsor_ID=<?= $Sponsor_ID ?>&Date_From=<?= $Start_Date ?>&Date_To=<?= $End_Date ?>', '_blank');
    }

</script>