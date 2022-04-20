<?php
include("./includes/connection.php");
@session_start();
// Turn off all error reporting
error_reporting(!E_NOTICE || !E_WARNING);
$htm = "";

$payment_code = 0;
if (isset($_GET['payment_code'])) {
    $payment_code = $_GET['payment_code'];
}

$p_id = 0;
if (isset($_GET['p_id'])) {
    $p_id = $_GET['p_id'];
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
}

// start 
// check for the maximun number of receipt to print:
$select_receipt_limit =  "SELECT max_receipt FROM tbl_receipt_config";
if ($result_receipt_limit = mysqli_query($conn,$select_receipt_limit)) {
    while ($row = mysqli_fetch_assoc($result_receipt_limit)) {
    $receipt_max = $row['max_receipt'];    
}
}else{
    echo "<script>".mysqli_error($conn)."</scipt>";
}



// select the number of receipt already printed:
$select_printed_receipt_count = "SELECT receipt_count FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'";
if ($result_printed_receipt = mysqli_query($conn,$select_printed_receipt_count)) {
    while ($row1 = mysqli_fetch_assoc($result_printed_receipt)) {
    $printed_receipt = $row1['receipt_count'];
}

echo "<script>This is not valid {$printed_receipt} receipt</script>";    
}else{
    echo "<script>".mysqli_error($conn)."</script>";
}


if ($printed_receipt >= $receipt_max) {
?>
    <input type="hidden" name="receipt" id="printed_receipt" value="<?=$printed_receipt?>">
    <input type="hidden" name="receiptp" id="max_receipt" value="<?=$max_receipt?>"> 
  <script type="text/javascript">
  var doc = document.getElementById("max_receipt").value;

  alert("You have reached maximum Recipt count " + doc)
  window.close();
  // location.replace("http://localhost:8080/march/final_one/files/employeeperfomancereport.php");
  </script>

<?php
}else{


    $Sselect_receipt_count = "SELECT receipt_count FROM tbl_patient_payments 
        WHERE Patient_Payment_ID = '$Patient_Payment_ID'";
            // $receipt_count = 0;
        if ($select_result = mysqli_query($conn,$Sselect_receipt_count)) {
            while ($rows = mysqli_fetch_assoc($select_result)) {
                $receipt_count = $rows['receipt_count'];
            }

            //update receipt count 
            $receipt_count = $receipt_count + 1;
            if ( ($update_receipt_count = mysqli_query($conn,"UPDATE tbl_patient_payments 
                        SET receipt_count='$receipt_count' WHERE Patient_Payment_ID ='$Patient_Payment_ID' "))){
                echo "<script>successfullt updated " .$receipt_count . "</script>";
                }else{ 
                    echo  "<script>".mysqli_error($conn)."</script>";
                }
             } else{
                echo "<script>".mysqli_error($conn)."</script>";
             }


}

?>
<script type="text/javascript">
var doc = document.getElementById("max_receipt").value;
var doc1 = document.getElementById("printed_receipt").value;
// if (doc1 >= doc) {
    alert("malopa" + doc + doc1)

// };
</script>


<?php


$isReceipt = false;
$q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($q);
$exist = mysqli_num_rows($q);
if ($exist > 0) {
    $Paper_Type = $row['Paper_Type'];
    $Include_Sponsor_Name_On_Printed_Receipts = $row['Include_Sponsor_Name_On_Printed_Receipts'];
    if ($Paper_Type == 'Receipt') {
        $isReceipt = true;
    }
} else {
    $Include_Sponsor_Name_On_Printed_Receipts = 'yes';
}
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

if ($p_id==0) {
    $select_P_ID = mysqli_query($conn,"select P_ID from tbl_bank_api_payments_details  where Payment_Code='$payment_code'  ORDER BY Payment_ID DESC") or die(mysqli_error($conn));
    $rowPID = mysqli_fetch_assoc($select_P_ID);
    
    $p_id=$rowPID['P_ID'];
}

$select_Transaction_Items = mysqli_query($conn,
        "select * from tbl_bank_api_payments_details  where Payment_Code='$payment_code' AND P_ID='$p_id'") or die(mysqli_error($conn));

echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $Pre_Paid = $row['Pre_Paid'];
    //echo $row['Employee_ID'];exit;
    echo '<tr><td colspan="5"><hr height="2px"></td></tr>';



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
                        Amount Paid: ' . $row['Amount_Paid'] . ' 
                      </td>
					  </tr>
                      <tr>
                      <td>
                        Receipt Number: ' . $row['Payment_Receipt'] . ' 
                      </td>';
    echo '<td>Trans Ref: ' . $row['Transaction_Ref'] . '</td>';
    echo '</tr>
                    <tr>
                      <td>
                         Trans Date: ' . $row['Transaction_Date'] . ' 
                      </td>
                       <td>
                       Terminal ID ' . $row['Terminal_ID'] . ' 
                      </td>
                      
                    </tr>
                    ';
    echo '
                    <tr>
                      <td>
                         Merchant ID: ' . $row['Merchant_ID'] . ' 
                      </td>
                       <td>
                       Batch # ' . $row['Batch_No'] . ' 
                      </td>
                      
                    </tr>
                    ';

    echo '
                    <tr>
                      <td>
                         Auth #: ' . $row['Auth_No'] . ' 
                      </td>
                       <td>
                       Payment Code # ' . $payment_code . ' 
                      </td>
                      
                    </tr>
                    ';
}
echo '<tr><td colspan="5"><hr height="2px"></td></tr>';

echo '</table>';

echo '</div>';
?>

            </div></div>
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