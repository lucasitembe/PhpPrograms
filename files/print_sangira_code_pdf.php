<?php
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
    $amount=$_GET['amount'];
    $card_and_mobile_payment_transaction_id=$_GET['card_and_mobile_payment_transaction_id'];
    
    $amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT payment_amount FROM tbl_card_and_mobile_payment_transaction WHERE bill_payment_code = '$card_and_mobile_payment_transaction_id'"))['payment_amount'];
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
                    <center><?php echo "Tumia SANGIRA Namba <h1>$card_and_mobile_payment_transaction_id kulipia Tsh. $amount.00 </h1> Kwa NMB Wakala, NMB Benki,CRDB Wakala, Tigopesa,Mpesa,Airtel Money, Simbanking au CRDB Benki"; ?></center>
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