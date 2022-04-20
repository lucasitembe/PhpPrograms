<?php
include("./includes/connection.php");
@session_start();
$htm = "";
?>

<!DOCTYPE html >
<html>

    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

        <title>EHMS SYSTEM:: PATIENT RECEIPT</title>

        <style>
            body {

                font-family:courier;
                font-size:12px; 
                font-weight:bold; 

            }
            table{
                font-family:courier;
                font-weight: normal;
                font-weight:600;
            }




        </style>
    </head>

    <body>
        <div id="page-wrap"  style="font-weight:bold">

            <div id="identity">
                <center>
                    <?php
                    include("./includes/reportheaderreceipt.php");
                    echo $htm;
                    $htm = '';
                    ?>
                </center> 
                <div style="clear:both"></div>

                <?php
               if (isset($_GET['Patient_Payment_ID'])) {
                    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
                } else {
                    $Patient_Payment_ID = 0;
                }
                  $total = 0;

                $datetime = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYD"))['TODAYD'];
                //select printing date and time

                echo '<div id="customer" style="clear:both;">';

                $select_Transaction_Items = mysqli_query($conn,
                "select pp.Employee_ID,pp.Billing_Type,pp.Payment_Date_And_Time,preg.Patient_Name,preg.Registration_ID,preg.Old_Registration_Number,Guarantor_Name from  tbl_patient_payment_item_list ppl INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                JOIN tbl_items t ON t.Item_ID = ppl.Item_ID
                JOIN tbl_employee emp ON emp.Employee_ID = pp.Employee_ID
                JOIN tbl_patient_registration preg ON preg.Registration_ID = pp.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = preg.Sponsor_ID
                where ppl.Patient_Payment_ID = '$Patient_Payment_ID' limit 1");

                echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
                while ($row = mysqli_fetch_array($select_Transaction_Items)) {

                    //echo $row['Employee_ID'];exit;
                    echo '<tr><td colspan="5"><hr height="2px"></td></tr>';
                    //SELECT BILLING TYPE
                    if (strtolower($row['Billing_Type']) == 'outpatient cash' || strtolower($row['Billing_Type']) == 'inpatient cash') {
                        $Billing_Type = 'Cash';
                    } elseif (strtolower($row['Billing_Type']) == 'outpatient credit') {
                        $Billing_Type = 'Credit';
                    }

                    // Turn off all error reporting
                    error_reporting(0);
                    //select the id of employee who made the transaction
                    $Employee_ID = $row['Employee_ID'];
                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                    echo ' <tr>
                       <td colspan="2">Name: ' . $row['Patient_Name'] . ' </td></tr>';
                    echo '
                    <tr>
                      <td>
                        Patient #: ' . $row['Registration_ID'] . ' 
                      </td>
                       <td>
                        Old #: ' . $row['Old_Registration_Number'] . ' 
                      </td>
                      <tr>
                      <td>
                        Receipt Number: ' . $Patient_Payment_ID . ' 
                      </td>
                      <td>
                        Sponsor: ' . $row['Guarantor_Name'] . ' 
                      </td>
                    </tr>
                    <tr>
                      <td>
                         Mode: ' . $row['Billing_Type'] . ' 
                      </td>
                       <td>
                       Date: ' . $Payment_Date_And_Time . ' 
                      </td>
                      
                    </tr>
                    ';
                }
                echo '</table>';

                echo '</div>';
             
                
                $select_category = mysqli_query($conn,"select * from tbl_item_category") or die(mysqli_error($conn));

                $isReceipt = false;
                $q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($q);
                $exist = mysqli_num_rows($q);
                if ($exist > 0) {
                    $Paper_Type = $row['Paper_Type'];
                    if ($Paper_Type == 'Receipt') {
                        $isReceipt = true;
                    }
                }

                $temp = 1;
                $total = 0;
                echo '<center><table width =100% border=0>';
                echo "<tr><td colspan='10'><hr height='3px'></td></tr>";
                echo '<tr id="thead"><td width = "2%">Sn</td><td><b>Check in type</b></td>
                <td><b>Location</b></td>
                <td><b>Item</b></td>
                <td><b>Edited By</b></td>
                <td><b>Reason</b></td>
                <td><b>Date & Time</b></td>
                <td style="text-align: left;"><b>Price</b></td>
                <td style="text-align: left;"><b>Discount</b></td>
                 <td style=""><b>Quantity</b></td>
                 <td style="text-align: left;"><b>Sub total</b></td></tr>';
                echo "<tr><td colspan='10'><hr height='3px'></td></tr>";
    
    $select_Transaction_Items = mysqli_query($conn,
       "select ppi.Check_In_Type,ppi.changed_By,ppi.changed_Reasons,e.Employee_Name,ppi.changed_Date, ppi.Patient_Direction, t.Product_Name, ppi.Price, ppi.Discount, ppi.Quantity,ppi.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
	   FROM tbl_transaction_items_history ppi LEFT JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppi.Patient_Payment_ID 
	   JOIN tbl_items t ON t.item_id = ppi.item_id LEFT JOIN tbl_employee e ON e.Employee_ID=ppi.changed_By
	   WHERE
            pp.Patient_Payment_ID = '$Patient_Payment_ID' 
            and Patient_Payment_Item_List_ID <> '$Patient_Payment_Item_List_ID' order by changed_Date") or die(mysqli_error($conn)); 

    $num_rows=  mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	$pilc=0;//$row['Payment_Item_Cache_List_ID'];
        $ppil=$row['Patient_Payment_Item_List_ID'];
        echo "<tr><td style='width:5%'>".$temp."</td>";
        echo "<td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td title='".number_format($row['Price'])."'>".$row['Product_Name']."</td>";
        echo "<td>".$row['Employee_Name']."</td>";
        echo "<td>".$row['changed_Reasons']."</td>";
        echo "<td>".$row['changed_Date']."</td>";
        echo "<td  style='text-align: right;width:6%'>".($row['Price']?number_format($row['Price']):0)."</td>";
        echo "<td  style='text-align:right;width:60px'>".($row['Discount']?number_format($row['Discount']):0)."</td>";
        echo "<td  style=''>".($row['Quantity']?number_format($row['Quantity']):0)."</td>";
        echo "<td  style='text-align: right;width:60px'>".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."</td>";
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
	$temp++;
    }   echo "</tr>";
        echo "<tr><td colspan=11 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>
     </table></center>";

    
?>

    
<script>
    window.print(false);
    CheckWindowState();

    function PrintWindow() {
        window.print();
        CheckWindowState();
    }

    function CheckWindowState() {
        if (document.readyState == "complete") {
            window.close();
        } else {
            setTimeout("CheckWindowState()", 2000);
        }
    }
</script>


    </body>

</html>