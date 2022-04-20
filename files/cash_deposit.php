<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href="new_payment_method.php" class="art-button-green">BACK</a>
<br/>
<br/>
<br/>
<fieldset>
    <legend align='center'><b>PATIENT PAYMENT</b></legend>
    <center>
        <table class="table" style="width:50%!important" >
            <tr>
                <td>
                    <a href="Direct_Cash_Outpatient.php">
                        <button style="width:100%"> Cash Deposit-OUTPATIENTS</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="Direct_Cash_Inpatient.php">
                        <button style="width:100%">Cash Deposit-INPATIENTS</button>
                    </a>
                </td>
            </tr>
            <?php 
                $patient_debt= 0;
                $select_patient_debt = mysqli_query($conn, "SELECT COUNT(Debt_cash_deposit_ID) as debitors from tbl_debt_cash_deposit  WHERE debit_status ='Active'") or die(mysqli_error($conn));
                while($countID = mysqli_fetch_assoc($select_patient_debt)){
                    $patient_debt = $countID['debitors'];
                }
            ?>
            <tr>
                <td>
                    <a href="Direct_Cash_debit.php">
                        <button style="width:100%">Cash Deposit-DEBIT <span class="badge " style="background-color: red;"><?php echo $patient_debt; ?></span></button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="cash_deposit_report.php">
                        <button style="width:100%">Cash Deposit-DEBIT </button>
                    </a>
                </td>
            </tr>  
        </table>
    </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>
