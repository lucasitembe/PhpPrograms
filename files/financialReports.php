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
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<fieldset>
        <legend align=center><b>FINANCE WORKS</b></legend>
        <center>
	    <table width = 60%>
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
			<a href='#incomeStatement.php'>
			    <button style='width: 100%; height: 100%'>
				<b>Income Statement</b>
			    </button>
			</a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Income Statement</b>
                        </button>
                  
                    <?php } ?>
                </td>
                </tr><tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='revenue.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Revenue</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Revenue</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		<tr>
		    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='#expenditure.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Expenditure</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Expenditure</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		</tr>
	     <tr>
		    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='#balanceSheet.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Balance Sheet</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Balance Sheet</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		</tr>
	     <tr>
		    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='#accountReceivable.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Account Receivable</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Account Receivable</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		</tr>
	     <tr>
		    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='#accountPayable.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Account Payable</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Account Payable</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		</tr>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>