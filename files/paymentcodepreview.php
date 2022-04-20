<?php
session_start();
include("./includes/connection.php");
include './includes/constants.php';
include("./includes/connection_epayment.php");

if (isset($_GET['Payment_Code'])) {
    $Payment_Code = $_GET['Payment_Code'];
} else {
    $Payment_Code = '';
}if (isset($_GET['printreceipt'])) {
    $printreceipt = $_GET['printreceipt'];
} else {
    $printreceipt = '';
}
?>
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
<?php
if ($printreceipt == 'true') {
    $sql = "SELECT * FROM tbl_payments WHERE Payment_Code='$Payment_Code' ORDER BY Payment_Code DESC LIMIT 1";
    $rowRm = getRecord($sql)[0];
    ?>
    <div id="page-wrap"  style="font-weight:bold">

        <div id="identity">
            <center>
                <?php
                require_once("./includes/reportheaderreceipt.php");
                echo $htm;
                $htm = '';
                ?>
            </center> 
            <h2 style="text-align:center;font-weight: bolder"><b>ePayment Receipt</b></h2>
            <?php
            $total = 0;

            echo '<div id="customer" style="clear:both;">';
            echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
            echo ' <tr>
                       <td colspan="2">Name: ' . $rowRm['Patient_Name'] . ' </td></tr>';
            echo '
                    <tr>
                      <td>
                        Patient #: ' . $rowRm['Registration_ID'] . ' 
                      </td>
                       <td>
                        Amount: ' . number_format($rowRm['Amount_Paid'], 2) . ' 
                      </td>
                      <tr>
                      <td>
                        Receipt Number: ' . $rowRm['Payment_Receipt'] . ' 
                      </td>';
            echo '<td>Trans Ref: ' . $rowRm['Transaction_Ref'] . '</td></tr>';
             echo '</tr>
                    <tr>
                      <td>
                         Merch ID: ' . $rowRm['Merchant_ID'] . ' 
                      </td>
                       <td>
                          Batch No: ' . $rowRm['Batch_No'] . ' 
                      </td>
                      
                    </tr>
                    ';
            echo ' <tr>
                      <td>
                      Auth No: ' . $rowRm['Auth_No'] . ' 
                      </td>
                       <td>
                          Terminal_ID: ' . $rowRm['Terminal_ID'] . ' 
                      </td>
                      
                    </tr>
                    ';
             
                echo '</tr>
                    <tr>
                      <td>
                         Trans Date:' . date('j M, y H:i', strtotime($rowRm['Transaction_Date'] )). '
                      </td>
                       <td>
                          Pay Code: ' . $Payment_Code . ' 
                      </td>
                      
                    </tr>
                    ';
            echo '</table>';

            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            //get transaction details
            $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, btc.Amount_Required, btc.Transaction_Date_Time
							from tbl_bank_transaction_cache btc, tbl_patient_registration pr where
							pr.Registration_ID = btc.Registration_ID and
							btc.Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if ($num > 0) {
                while ($data = mysqli_fetch_array($select)) {
                    ?>
                    <table width="100%">
                        <tr>
                            <td width="40%" style="text-align: right;">PATIENT NAME : </td>
                            <td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align: right;">PATIENT NUMBER : </td>
                            <td><?php echo ucwords(strtolower($data['Registration_ID'])); ?></td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td width="40%" style="text-align: right;">INVOICE NUMBER : </td>
                            <td><?php echo $Payment_Code; ?></td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align: right;">AMOUNT REQUIRED : </td>
                            <td><?php echo number_format($data['Amount_Required']); ?></td>
                        </tr>
                        <tr>
                            <td width="40%" style="text-align: right;">DATE : </td>
                            <td><?php echo ucwords(strtolower($data['Transaction_Date_Time'])); ?></td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                    </table>
                    <?php
                }
            } else {
                echo "No details found";
            }
        }
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
