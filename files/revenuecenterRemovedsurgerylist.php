<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
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
?>
<?php
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
        if($Billing_Type == 'OutpatientCash'){
            $Page_Title = 'Outpatient Cash';
        }elseif($Billing_Type == 'OutpatientCredit'){
            $Page_Title = 'Outpatient Credit';
        }elseif($Billing_Type == 'InpatientCash'){
            $Page_Title = 'Inpatient Cash';
        }elseif($Billing_Type == 'InpatientCredit'){
            $Page_Title = 'Inpatient Credit';
        }elseif($Billing_Type == 'PatientFromOutside'){
            $Page_Title = 'Patient From Outside';
        }else{
            $Page_Title = '';
        }
    }else{
        $Billing_Type = '';
        $Page_Title = '';
    }
?>
   
   
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "revenuecenterprocedurelist.php?Billing_Type=OutpatientCash&ProcedureList=ProcedureListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterprocedurelist.php?Billing_Type=OutpatientCredit&ProcedureList=ProcedureListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterprocedurelist.php?Billing_Type=InpatientCash&ProcedureList=ProcedureListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterprocedurelist.php?Billing_Type=InpatientCredit&ProcedureList=ProcedureListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterprocedurelist.php?Billing_Type=PatientFromOutside&ProcedureList=ProcedureListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href="revenuecenterRemovedsurgerylist.php" class="art-button-green">REMOVED ITEMS</a>
    <a href='revenuecentersurgerylist.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
<br/><br/>
<fieldset>  
            <legend align='right'><b><?php echo strtoupper('Procedure Payments ~ '.$Page_Title); ?> </b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
                <div style="width: 100%;height: 350px">
                    <?php include 'Revenue_Center_Removed_Surgery_List_Iframe.php'; ?>
                </div>
		<!--<iframe width='100%' height=350px src='?Billing_Type=<?php echo $Page_Title; ?>'></iframe>-->
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>