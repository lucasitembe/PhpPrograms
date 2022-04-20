<link rel="stylesheet" href="table.css" media="screen"> 

<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //section to help back buttons
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = '';
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
<!--    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT LIST
    </a>-->
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
    <a href='chemotherapytreatment.php?PatientRecords=PatientRecordsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>


<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
    <script language="javascript" type="text/javascript">
        function searchPatient(){
            var Patient_Name = document.getElementById("Search_Patient").value;
            var Patient_Number = document.getElementById("Patient_Number").value;
            var Phone_Number = document.getElementById("Phone_Number").value;

            document.getElementById('Search_Iframe').src = 'cancer_patient_search_iframe.php?Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Phone_Number='+Phone_Number+'&section=<?php echo $section; ?>';
        }
    </script>
<script language="javascript" type="text/javascript">
//    function searchPatient(Patient_Name){
//        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='patientfile_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
//    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td width=30%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient()' placeholder='Search Patient Name'>
            </td>
            <td width=30%>
                <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' oninput='searchPatient()' placeholder='Search Patient Number'>
            </td>
            <td width=30%>
                <input type='text' name='Phone_Number' id='Phone_Number' style='text-align: center;' oninput='searchPatient()' placeholder='Search Phone Number'>
            </td>
        </tr>

<!--        <tr>-->
<!--            <td>-->
<!--                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>-->
<!--            </td>-->
<!--        </tr>-->

    </table>
</center>
<br>
<fieldset>  
            <legend align=center><b>PATIENTS LIST</b></legend>
                <center>
                    <table width=100% border=0>
                        <tr>
                            <td style="border: 1px #ccc solid;">
                                <iframe id='Search_Iframe' width='100%' height="460px" src='Cancer_patient_iframe.php?Patient_Name=Patient_Name&section=<?php echo $section; ?>'></iframe>
                           
                            </td>
                        </tr>
                    </table>
                </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>