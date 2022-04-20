<?php
include("./includes/connection.php");
$temp = 1;
$total = 0;
$Title = '';

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
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
                            <td><b>Invoice #</b></td>
                            <td><b>Invoice Date</b></td>
                            <td><b>Invoice Month</b></td>
                            <td><b>Invoice Year</b></td>
                            <td><b>Customer</b></td>
                            <td><b>Created By</b></td>
	            	    <td width="15%" style="text-align: right;"><b>AMOUNT</b></td><td></td>
                            <td>&nbsp;</td>
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

$results = mysqli_query($conn,"
                    SELECT Invoice_ID,Invoice_Number,invoice_date,invoice_month,invoice_year,amount,employee_name,Guarantor_Name
                    FROM tbl_invoice i
                    JOIN tbl_employee e ON e.employee_id=i.employee_id
                    JOIN tbl_sponsor s ON s.sponsor_id=i.sponsor_id
                    WHERE invoice_date BETWEEN '$Start_Date' AND '$End_Date' AND
                    i.sponsor_id='$Sponsor_ID'    
                    ORDER BY Invoice_ID DESC LIMIT 100
                    ") or die(mysqli_error($conn));

while ($row = mysqli_fetch_assoc($results)) {


    echo '<tr>'
    . '   <td>' . $temp . '</td>';
    echo '<td>' . $row['Invoice_Number'] . '</td>';
    echo '<td>' . $row['invoice_date'] . '</td>';
    echo '<td>' . $row['invoice_month'] . '</td>';
    echo '<td>' . $row['invoice_year'] . '</td>';
    echo '<td>' . $row['Guarantor_Name'] . '</td>';
    echo '<td>' . $row['employee_name'] . '</td>';
    echo "<td style='text-align: right;'>" . number_format($row['amount'], 2) . "</td>";
    echo "<td><a class='art-button-green' href='invoice_preview.php?ref=".$row['Invoice_ID']."' target='_blank'>Preview</a></td>";
    echo '';
    echo "</tr>";

    $total += $row['amount'];
    $temp++;
}

echo "<tr><td colspan='8'><hr></td></tr>";
echo "<tr><td colspan='7' style='text-align: right;'><b> GRAND TOTAL : </td><td style='text-align: right;'><b> " . number_format($total,2) . "</b> </td></tr>";
echo "<tr><td colspan='8'><hr></td></tr>";
?>
</table>
