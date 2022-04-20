<?php

@session_start();
include("./includes/connection.php");
// error_reporting(!E_NOTICE || !E_WARNING);
$temp = 1;
$GrandTotal = 0;
$Sub_Total = 0;

//get employee details
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

//get Patient Bill ID
if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = 0;
}

//get insurance name
if (isset($_GET['Insurance'])) {
    $Insurance = $_GET['Insurance'];
} else {
    $Insurance = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}

$isReceipt = false;
$q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($q);
$exist = mysqli_num_rows($q);
if ($exist > 0) {
    $Paper_Type = $row['Paper_Type'];
    $Include_Sponsor_Name_On_Printed_Receipts = $row['Include_Sponsor_Name_On_Printed_Receipts'];
    if ($Paper_Type == 'Receipt') {
        $isReceipt = true;
        echo "Sponsor name: " +$Include_Sponsor_Name_On_Printed_Receipts;
    }
}else{
    $Include_Sponsor_Name_On_Printed_Receipts = 'yes';
}
?>
<!DOCTYPE html >
<html>

    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

        <title>EHMS SYSTEM:: PATIENT GATE PASS</title>

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
                    // $htm = '';
                    ?>
                </center> 
                <div style="clear:both"></div>

                <?php
             
                $total = 0;

                $datetime = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYD"))['TODAYD'];
                //select printing date and time


                echo '<div id="customer" style="clear:both;">';

                $select_Transaction_Items =mysqli_query($conn, "SELECT pp.Pre_Paid,pp.Employee_ID,pp.Billing_Type,pp.Payment_Date_And_Time,preg.Patient_Name,preg.Registration_ID,preg.Old_Registration_Number,Guarantor_Name, sp.Exemption, pp.payment_type FROM tbl_patient_payments pp, tbl_employee emp, tbl_patient_registration preg,  tbl_sponsor sp WHERE emp.Employee_ID = pp.Employee_ID AND preg.Registration_ID = pp.Registration_ID AND sp.Sponsor_ID = preg.Sponsor_ID AND pp.Check_In_ID = '$Check_In_ID' LIMIT 1") or die(mysqli_error($conn));
                echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
                while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                    $Pre_Paid = $row['Pre_Paid'];
                    //echo $row['Employee_ID'];exit;
                    echo '<tr><td colspan="5"><hr height="2px"></td></tr>';
                    //SELECT BILLING TYPE
                    if (strtolower($row['Billing_Type']) == 'outpatient cash' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')) {
                        $Billing_Type = 'Cash';
                    } elseif (strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) {
                        $Billing_Type = 'Credit';
                    }

                    
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
                      <td>';
                      if($Billing_Type == 'Credit'){
                        echo '<b>DEDIT NOTE NUMBER :</b> ' . $Patient_Payment_ID . ' </td>';
                      }else{
                        echo 'Receipt Number: ' . $Patient_Payment_ID . ' </td>';
                      }
                        
                      if(strtolower($Include_Sponsor_Name_On_Printed_Receipts) == 'no'){
                        echo '<td>&nbsp;</td>';
                      }else{
                        echo '<td>Sponsor: ' . $row['Guarantor_Name'] . '</td>';
                      }
                    echo '</tr>
                    <tr>
                      <td>
                         Mode: ' . $row['Billing_Type'] . ' 
                      </td>
                       <td>
                       Date: ' . $Payment_Date_And_Time . ' 
                      </td>
                      
                    </tr>
                    ';

                    if($Pre_Paid == 1 && strtolower($Billing_Type) == 'cash'){
                        echo '<tr><td colspan="4" style="text-align: center;"><h3>PRE / POST PAID TRANSACTION RECEIPT</h3></td></tr>';
                    }
                }
                echo '</table>';

                echo '</div>';
               

                $subtotal = 0;
                $total = 0;
                $select_Transaction_Items = mysqli_query($conn,"select * from
			    tbl_employee emp, tbl_patient_registration preg,
				tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where emp.employee_id = pp.employee_id and
				preg.registration_id = pp.registration_id and
				pp.patient_payment_id = ppl.patient_payment_id and
				t.item_id = ppl.item_id and
				t.item_subcategory_id = ts.item_subcategory_id and
				ts.item_category_id = ic.item_category_id and
				pp.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
                if (mysqli_num_rows($select_Transaction_Items) > 0) {
                    echo '<table id="items" width="100%" border="0" cellpadding="0" cellspacing="0">';


                    echo "<tr><td colspan='4'><hr height='3px'></td></tr>";
                    echo '<tr>';
                    echo "<th width='4%' style='text-align:left;'>Qty</th>";
                    echo "<th style='text-align:center;'>Item</th>";
                    echo "<th style='text-align:right;'>Total</th>";
                    echo "</tr>";
                    echo "<tr><td colspan='10'><hr height='3px'></td></tr>";

                    while ($row = mysqli_fetch_assoc($select_Transaction_Items)) {

                        echo '<tr class="item-row">';
                        echo '<td class="qty" width="4%">' . $row['Quantity'] . '</td>';
                        if ($isReceipt) {
                            echo "<td class='description'>" . substr($row['Product_Name'], 0, 16) . " </td>";
                        } else {
                            echo "<td class='description'>" . $row['Product_Name'] . " </td>";
                        }
                      
                        echo '<td style="text-align:right;"><span class="price" >' . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity']));
                        echo '</td>'
                        . '
                        </tr>';
                        $subtotal = $subtotal + (($row['Price'] - $row['Discount']) * $row['Quantity']);

                        echo "<tr><td width='11px' height='4px'></td></tr>";
                    }
                    echo "</table>";
                   
                }
               
                $htm = '';
                $total = $total + $subtotal;
                $subtotal = 0;
               

                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td colspan='4'><hr height='3px'></td></tr>";
                echo "<tr><td style='text-align: right;font-weight:bold;' colspan='4'><strong> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . "</strong></td></tr>";
                echo "<tr><td colspan='4'><hr height='3px'></td></tr>";
                // Turn off all error reporting
                error_reporting(0);

                //select the name of the employee who made the transaction based on employee id we got above
                $select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
                while ($row = mysqli_fetch_array($select_Employee_Name)) {
                    $Employee_Name = $row['Employee_Name'];
                }

                if ($isReceipt) {
                    echo '<tr class="enddesc">'
                    . '<td colspan="" style="text-align:left;">Prepared By: ' . $Employee_Name . ''
                    . '<td  style="text-align:right">&nbsp;&nbsp;<span style="font-size: small;">Sign</span></b>________________</td>'
                    . '</tr>';
                } else {
                    echo '<tr class="enddesc">'
                    . '<td colspan="" style="text-align:left;">Prepared By: ' . $Employee_Name . ''
                    . '<td  style="text-align:right">&nbsp;&nbsp;<span style="font-size: small;">Patient Signature</span></b>______________________________</td>'
                    . '</tr>';
                }


                echo '</table>';
                ?>

            </div></div>
            
<script>

// function returnHome(){
//     return false;
// }
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
