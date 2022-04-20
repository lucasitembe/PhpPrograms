<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
	    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
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

<?php
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
?>
<a href="patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks" class="art-button-green">BACK</a>
<br/><br/><br/><br/>
<fieldset>
    <legend align="center"><b>PATIENTS BILLING REPORTS</b></legend>
    <center>
        <table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='pendingbillingreport.php?PendingBillingReportWork=PendingBillingReportWorkThisPage'>
                        <button style='width: 100%; height: 100%'>PENDING BILLS REPORT</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='clearedbillingreport.php?ClearedBillingReport=ClearedBillingReportThisPage'>
                        <button style='width: 100%; height: 100%'>CLEARED BILLS REPORT</button>
                    </a>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>