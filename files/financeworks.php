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
			<a href='voucherEntry.php'>
			    <button style='width: 100%; height: 100%'>
				<b>Voucher Entry</b>
			    </button>
			</a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Voucher Entry</b> 
                        </button>
                  
                    <?php } ?>
                </td>
                </tr><tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='creation.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Creation</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Creation</b> 
                        </button>
                  
                    <?php } ?>
                </td>
		<tr>
		    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='financialReports.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Financial Reports</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Financial Reports</b> 
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