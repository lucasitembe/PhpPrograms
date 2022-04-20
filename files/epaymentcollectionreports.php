<?php
    include("./includes/header.php");
    @session_start();
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
      if(isset($_SESSION['userinfo']['General_Ledger'])){
        if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		      header("Location: ./index.php?InvalidPrivilege=yes");
	     }
      }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
      }
    }else{
      @session_destroy();
      header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
  if(isset($_SESSION['userinfo'])){
    echo "<a href='generalledgercenter.php?GeneralLedger=GeneralLedgerThisForm' class='art-button-green'>BACK</a>";
  } 
?>
<br/><br/>
<fieldset>  
  <legend align=right><b>ePayment Collection Reports</b></legend>
    <center>
    <table width = "60%">
      <tr>
        <td>
            <a href="epaymentcollection.php?ePaymentCollection=ePaymentCollectionThisForm">
              <button style='width: 100%; height: 40px;'>
                  ePayment Collections Summary
              </button> 
            </a>
        </td>
      </tr>
      <tr>
        <td>
            <a href="crdbcollection.php?ePaymentCollectionDetails=ePaymentCollectionDetailsThisForm">
              <button style='width: 100%; height: 40px;'>
                  ePayment Collections Details
              </button> 
            </a>
        </td>
      </tr>
      <tr>
        <td>
            <a href="epaymentcollectionbyreceipt.php?ePaymentCollection=ePaymentCollectionThisForm">
              <button style='width: 100%; height: 40px;'>
                  ePayment Collections - By Receipts
              </button> 
            </a>
        </td>
      </tr>
      <tr>
        <td>
            <a href="epaymentreconciliation.php?ePaymentCollection=ePaymentCollectionThisForm">
              <button style='width: 100%; height: 40px;'>
                  ePayment Reconciliation Reports
              </button> 
            </a>
        </td>
      </tr>
      <!-- <tr>
        <td>
            <a href="epaymentreconciliationdetails.php?ePaymentReconciliationDetails=ePaymentReconciliationDetailsThisForm">
              <button style='width: 100%; height: 40px;'>
                  ePayment Reconciliation Details
              </button> 
            </a>
        </td>
      </tr> -->
    </table>
  </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>