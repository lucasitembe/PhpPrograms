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
                    <a href="Direct_departmental_payment_outpatient.php">
                        <button style="width:100%">Direct Departmental Payment-OUTPATIENTS</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="Direct_departmental_payment_inpatient.php">
                        <button style="width:100%">Direct Departmental Payment-INPATIENTS</button>
                    </a>
                </td>
            </tr>
     
  
        </table>
    </center>
</fieldset>

<?php
    include("./includes/footer.php");
?>
