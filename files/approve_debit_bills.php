<?php
include("./includes/connection.php");
include("./includes/header.php");
$Registration_ID = $_GET['Registration_ID'];
$Debt_social_ID = $_GET['Debt_social_ID'];
session_start();

include('patient_demographic.php');
?>
<a href='patient_sent_from_reception.php' class='art-button-green'>
            BACK
        </a>
    <fieldset>
        <legend align='center'><b>DEBIT APPROVAL </b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style="text-align:right;">Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' readonly="readonly" id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' readonly="readonly" id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  readonly="readonly" value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' readonly="readonly" value='<?php echo $Registration_ID; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
                                <td>
                                    <select name='Billing_Type' id='Billing_Type'>
                                    <?php
                                        $select_bill_type = mysqli_query($conn,  "select payment_method from tbl_sponsor where Sponsor_ID='$Sponsor_ID' ") or die(mysqli_error($conn));

                                        $no_of_items = mysqli_num_rows($select_bill_type);
                                        if ($no_of_items > 0) {
                                            while ($data = mysqli_fetch_array($select_bill_type)){
                                                $payment_method = $data['payment_method'];
                                            }
                                            if ($payment_method == 'cash'){
                                                echo "<option selected='selected'>Outpatient Cash</option>";
                                            } else {
                                                echo "<option selected='selected'>Outpatient Credit</option> 
                                                    <option>Outpatient Cash</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' readonly="readonly" id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>' title="<?php echo $Guarantor_Name; ?>"></td>
                            <?php
                                //get consultation id
                                $select = mysqli_query($conn,"select consultation_id from tbl_payment_cache where
                                                        Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                                $num = mysqli_num_rows($select);
                                if($num > 0){
                                    while ($data = mysqli_fetch_array($select)) {
                                        $consultation_id = $data['consultation_id'];
                                    }
                                }else{
                                    $consultation_id = 0;
                                }

                                //get required details
                                $select = mysqli_query($conn,"select Folio_Number, Check_In_ID, Patient_Bill_ID, Claim_Form_Number from 
                                                        tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c where
                                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                        ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID and
                                                        c.consultation_ID = '$consultation_id'") or die(mysqli_error($conn));
                                $number = mysqli_num_rows($select);
                                if($number > 0){
                                    while ($row = mysqli_fetch_array($select)) {
                                        $Folio_Number = $row['Folio_Number'];
                                        $Check_In_ID = $row['Check_In_ID'];
                                        $Patient_Bill_ID = $row['Patient_Bill_ID'];
                                        $Claim_Form_Number = $row['Claim_Form_Number'];
                                    }
                                }else{
                                    $Folio_Number = '';
                                    $Check_In_ID = '';
                                    $Patient_Bill_ID = '';
                                    $Claim_Form_Number = '';
                                }
                            ?>
                                <td style="text-align:right;" >Claim Form Number</td>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' value="<?php echo $Claim_Form_Number; ?>" readonly="readonly"></td>
                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" value='<?php echo $Folio_Number; ?>' value="<?php echo $Folio_Number; ?>" readonly="readonly"></td>
                            </tr>
                            <tr>
                            </tr>
                            <tr>    
                                <td style="text-align:right;">Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' readonly="readonly" value='<?php echo $Member_Number; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' readonly="readonly" value='<?php echo $Phone_Number; ?>'></td>

                                <td style="text-align:right;">Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' readonly="readonly" value='<?php echo $Employee_Name; ?>'></td>
                                <td style="text-align:right;">Supervised By</td>
                                    <?php
                                    if (isset($_SESSION['supervisor'])) {
                                        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                                            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                                                $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                                            } else {
                                                $Supervisor = "Unknown Supervisor1";
                                            }
                                        } else {
                                            $Supervisor = "Unknown Supervisor2";
                                        }
                                    } else {
                                        $Supervisor = "Unknown Supervisor3";
                                    }
                                    ?> 
                                <td><input type='text' name='Supervisor_ID' id='Supervisor_ID' readonly="readonly" value='<?php echo $Supervisor; ?>'></td>
                            </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
    </fieldset>

    <br>
    <fieldset>
        <legend>DEBIT DETAILS</legend>
        <table class="table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Total Amount</th>
                    <th>BILL DATE</th>
                    <th>DEBIT CREATED BY</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $select_debit = mysqli_query($conn, "SELECT Total_debt,created_at,dts.Debt_ID, sent_date, Debt_social_ID, Debt_status FROM tbl_patient_debt_to_socialwalfare dts, tbl_patient_debt pd WHERE  pd.Registration_ID='$Registration_ID' AND  Debt_social_ID='$Debt_social_ID'") or die(mysqli_error($conn));
                    $sn= 0;
                    if(mysqli_num_rows($select_debit)>0){
                        while ($rows = mysqli_fetch_assoc($select_debit)) {
                            $sent_date = $rows['sent_date'];
                            $Total_debt = $rows['Total_debt'];
                            $Debt_social_ID = $rows['Debt_social_ID'];
                            $created_at = $rows['created_at'];
                            $Debt_status = $rows['Debt_status'];
                            $Debt_ID = $rows['Debt_ID'];
                            $sn++;
                            echo"<input id='Debt_ID' value='$Debt_ID' style='display:none'>";
                            echo "<tr>
                                        <td>$sn</td>
                                        <td>".number_format($Total_debt)."/=</td>
                                        <td></td>
                                        <td>$created_at</td>
                                        <td>$Debt_status</td>
                                        <td  rowspan='2'>";?><button class='btn btn-info' type='Button'  onclick='reduce_debt_cash_deposit(<?=$Debt_social_ID?>)' title='for cash deposit'>SEND TO CASHIER</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class='btn btn-info' type='Button' name='process_patient_debt' onclick='process_debt(<?=$Debt_social_ID?>)' title='for cash deposit'>PROCESS PATIENT</button></td>
                            </tr>
                            <?php
                            echo "<tr>
                                    <td colspan='4'>
                                        <textarea class='form-control' name='Reason_to_extend' id='Reason_to_extend' rows='1' > </textarea>
                                    </td>
                                    <td><input class='form-control' id='Amount_to_deposited' placeholder='Amount to be Deposited'></td>
                            </tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </fieldset>
    <script>
        function reduce_debt_cash_deposit(Debt_social_ID){
            var Reason_to_extend = $("#Reason_to_extend").val();
            var Patient_debt = $("#Amount_to_deposited").val();
            var Debt_ID = $("#Debt_ID").val();
            if(Reason_to_extend==''){
                $("#Reason_to_extend").css('border', '2px red solid');
                alert('Fill reseason why should  extend debit');
            }else if(Patient_debt ==''){
                alert('Amount to be deposited')
                $("#Amount_to_deposited").css("border", "2px red solid");
            }else{
                $("#Reason_to_extend").css('border', '');
                $("#Amount_to_deposited").css('border', '');
                //alert(Reason_to_extend);exit();
                $.ajax({
                    type:'POST',
                    url:'Ajax_patient_debt.php',
                    data:{Debt_social_ID:Debt_social_ID,Debt_ID:Debt_ID, Reason_to_extend:Reason_to_extend, Patient_debt:Patient_debt, debt_cash_deposit:''},
                    success:function(responce){
                        alert(responce);
                    }
                });
            }
        }

        function process_debt(Debt_social_ID){
            var Reason_to_extend = $("#Reason_to_extend").val();
            var Debt_ID = $("#Debt_ID").val();
            if(Reason_to_extend==''){
                $("#Reason_to_extend").css('border', '2px red solid');
                alert('Fill reseason why should  extend debit');
            }else{
                $("#Reason_to_extend").css('border', '');
                // alert(Debt_ID);exit();
                $.ajax({
                    type:'POST',
                    url:'Ajax_patient_debt.php',
                    data:{Debt_social_ID:Debt_social_ID,Debt_ID:Debt_ID, Reason_to_extend:Reason_to_extend, process_patient_debt:''},
                    success:function(responce){
                        alert(responce);
                    }
                });
            }
        }
    </script>
    <?php

        include("./includes/footer.php");
    ?>