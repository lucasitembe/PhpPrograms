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
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENT LIST') {
	    document.location = "laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option> Select List To View</option>
    <option>
	OUTPATIENT CASH
    </option>
<!--    <option>-->
<!--	OUTPATIENT CREDIT-->
<!--    </option>-->
    <option>
	INPATIENT CASH
    </option>
<!--    <option>
	PATIENT LIST
    </option>
    <option>
	PATIENT FROM OUTSIDE
    </option>-->
</select>
<!-- removed function
<input type='button' value='VIEW' onclick='gotolink()'>
-->
</label> 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<!----<a href="revenuecenterlaboratoryUnpaidlist.php" class="art-button-green">Removed Tests</a>----->
    <a href='./revenuecenterlaboratorylist.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
	var Registration_Number = document.getElementById('Registration_Number').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=370px src='Laboratory_List_Iframe.php?Patient_Name="+Patient_Name+"&Registration_Number="+Registration_Number+"'></iframe>";
    }
</script>

<br/><br/>
<fieldset>  
        <legend align='right'><b><?php echo strtoupper('Laboratory Payments ~ '.$Page_Title); ?> </b></legend>
        <center>
            <table width=100% border=1>
             <tr>
                <td>
                    <div id="Search_Iframe" style="width: 100%;height: 350px">
                       <!--Revenue_Center_Laboratory_List_Iframe.php--> 
                       <?php include 'Revenue_Center_Unpaid_Laboratory_List.php';?>

                    </div>
                </td>
             </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>