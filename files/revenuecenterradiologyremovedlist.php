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
	    document.location = "revenuecenterradiologylist.php?Billing_Type=OutpatientCash&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=OutpatientCredit&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=InpatientCash&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=InpatientCredit&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=PatientFromOutside&RadiologyList=RadiologyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option></option>
    <option>
	OUTPATIENT CASH
    </option>
    <option>
	PATIENT FROM OUTSIDE
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> -->


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<!----<a href="revenuecenterradiologyremovedlist.php" class="art-button-green">REMOVED ITEMS</a>---->
    <a href='./revenuecenterradiologylist.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
	var Registration_Number = document.getElementById('Registration_Number').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=370px src='Radiology_List_Iframe.php?Patient_Name="+Patient_Name+"&Registration_Number="+Registration_Number+"'></iframe>";
    }
</script>

<br/><br/>
<!--<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>

        </tr>
        
    </table>
</center>-->
  <fieldset>  
    <legend align='right'><b><?php echo strtoupper('Radiology Payments ~ '.$Page_Title); ?> </b></legend>
        <center>
            <table width=100% border=1>
              <tr>
                <td id='Search_Iframe'>
                    <div style="width: 100%;height: 350px">
                     <?php include 'Revenue_Center_Radiology_Removed_List_Iframe.php';?>   
                    </div>
                    <!--<iframe width='100%' height=350px src='Revenue_Center_Radiology_List_Iframe.php?Billing_Type=<?php echo $Page_Title; ?>'></iframe>-->
                </td>
              </tr>
            </table>
        </center>
   </fieldset><br/>
<?php
    include("./includes/footer.php");
?>