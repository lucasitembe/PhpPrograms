<?php
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
    $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
    session_start();
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $sql_get_sangira_sangira_code_result=mysqli_query($conn,"SELECT bill_payment_code,payment_amount,patient_phone FROM tbl_card_and_mobile_payment_transaction WHERE Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND Employee_ID='$Employee_ID' ORDER BY card_and_mobile_payment_transaction_id DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_get_sangira_sangira_code_result)>0){
        $bill_payment_code=mysqli_fetch_assoc($sql_get_sangira_sangira_code_result);
        $amount=$bill_payment_code['payment_amount'];
        $patient_phone=$bill_payment_code['patient_phone'];
        $card_and_mobile_payment_transaction_id=$bill_payment_code['bill_payment_code'];
    }
    
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


                </div>
                <div style="font-size:30px">
                    <center><?php echo "Tumia SANGIRA Namba <h1>$card_and_mobile_payment_transaction_id kulipia Tsh. $amount.00 </h1> kwa kupitia NMB AU CRDB Wakala, Tigopesa,Mpesa,Airtel Money"; ?></center>
                </div>
                <div style="font-size:50px">
                    <center><b>0<?php echo $patient_phone;?><b></center>
                </div>
                <BR/><BR/>
                <div style="font-size:20px;text-align: right;">Powered By GPITG</div>
            </div>
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