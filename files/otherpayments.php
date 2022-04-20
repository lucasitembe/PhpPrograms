<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<br/><br/><br/><br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>OTHER PAYMENTS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='addnewpatientotherpayments.php?AddNewSponsor=AddNewSponsorThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Patient
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='otherpaymentspatientlist.php?OtherPayments=OtherPaymentsThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Select Patient From The List
                        </button>
                    </a>
                </td> 
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>