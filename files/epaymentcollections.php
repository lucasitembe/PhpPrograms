<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
    }
    
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
			echo "<a href='generalledgercenter.php?GeneralLedgerWorks=GeneralLedgerWorksThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>

 
  
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<br/><br/>
<fieldset> 
    <br/><br/><br/>
    <legend align=right><b>ePAYMENTS COLLECTIONS REPORTS</b></legend>
    <center>
        <table width="60%">
            <tr>
                <td width='100%'>
                  <!-- <a href="epaymenttransactionreport.php?CompletedPendingTransactions=CompletedPendingTransactionsThisPage"> -->
                  <a href="epaymentcollection.php?ePaymentCollection=ePaymentCollectionThisForm">
                   <button style='width: 100%; height: 40px;'>
                        Completed & Pending Transactions
                   </button> 
                  </a>
                </td>
            </tr>
            <tr>
                <td> <a href="#">
                   <button style='width: 100%; height: 40px;'>
                        General Payments
                   </button> 
                  </a>
                </td>
            </tr>
        </table>
    </center>
    <br/><br/><br/>
</fieldset>

 
  
 

<?php
    include("./includes/footer.php");
?>