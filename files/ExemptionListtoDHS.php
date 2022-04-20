<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	// if(isset($_SESSION['userinfo']['Reception_Works'])){
	//     if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
	// 	header("Location: ./index.php?InvalidPrivilege=yes");
	//     }
	// }else{
	//     header("Location: ./index.php?InvalidPrivilege=yes");
	// }
    }
?>

<!-- <a href='registerpatient.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>ADD NEW CUSTOMER</a> -->
<a href="Patient_exempted_list.php" class='art-button-green'>PATIENT EXEMPTED</a>
<a href="exemption_report.php?from=exemption" class="art-button-green">EXEMPTION REPORT</a>
<a href='managementworkspage.php?ManagementWorksPage=ThisPage'  class='art-button-green'>BACK</a>

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
    function searchPatient(Patient_Name){
		document.getElementById("Patient_Number").value = '';
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number){
		document.getElementById("Search_Patient").value = '';
		document.getElementById("Phone_Number").value = '';
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Patient_Number="+Patient_Number+"'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Phone_Number(Phone_Number){
	
	var Patient_Name = document.getElementById("Search_Patient").value;
	var Patient_Number = document.getElementById("Search_Patient").value;
	
	if ((Patient_Name != '' && Patient_Name != null) && (Patient_Number != '' && Patient_Number != null)) {//All set
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Patient_Number="+Patient_Number+"&Patient_Name="+Patient_Name+"&Phone_Number="+Phone_Number+"'></iframe>";
	}else if((Patient_Name != '' && Patient_Name != null) && (Patient_Number == '' || Patient_Number == null)) {//Patient_Number not set
		document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Patient_Name="+Patient_Name+"&Phone_Number="+Phone_Number+"'></iframe>";
	}else if((Patient_Name == '' || Patient_Name == null) && (Patient_Number != '' && Patient_Number != null)){
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Patient_Number="+Patient_Number+"&Phone_Number="+Phone_Number+"'></iframe>";	
	}else{
	    document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?Phone_Number="+Phone_Number+"'></iframe>";
	}
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Old_Patient_Number(Old_Patient_Number){
    
    var Patient_Name = document.getElementById("Search_Patient").value;
    var Patient_Number = document.getElementById("Search_Patient").value;
    var Phone_Number = "";
    
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe_2_to_DHS.php?src=old&Patient_Number="+Patient_Number+"&Patient_Name="+Patient_Name+"&Phone_Number="+Phone_Number+"&Old_Patient_Number="+Old_Patient_Number+"&from=DHS'></iframe>";
   
    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td width=35%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient(this.value)'  placeholder='~~~~~~~~~~~~~  Search Patient Name  ~~~~~~~~~~~~~~~~~~~' autocomplete="off">
            </td>
	    <td width=30%>
		<input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' oninput='Search_Patient_Using_Number(this.value)'  placeholder='~~~~~~~~~~~  Search Patient Number  ~~~~~~~~~~~~~~~' autocomplete="off">
	    </td>
	    <td width=35%>
		<input type='text' name='Phone_Number' id='Phone_Number' style='text-align:center;' oninput='Search_Patient_Phone_Number(this.value)' placeholder='~~~~~~~~~~~~~  Search Phone Number  ~~~~~~~~~~~~~~~' autocomplete="off">
	    </td>
        <!-- <td width=20%>
<!--        <input type='text' name='Old_Phone_Number' id='Old_Phone_Number' style='text-align:center;' oninput='Search_Old_Patient_Number(this.value)' placeholder='~~~~~~~~~~~~~  Search Old Patient Number  ~~~~~~~~~~~~~~~' autocomplete="off">-->
        <!-- </td> --> 
        </tr>
        
    </table>
</center>
<br/>
<fieldset>  
            <legend align=center><b>EXEMPTION LIST</b></legend>
        <center> 
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=400px src='search_msamaha_patient_list_Iframe_to_DHS.php?Patient_Name="+Patient_Name+"&from=DHS'></iframe>
            </td>
        </tr> 
            </table>
        </center>
</fieldset><br/>
<script type="text/javascript" src="js/afya_card.js"></script>
<?php
    include("./includes/footer.php");
?>
<script>
$(document).ready(function(){
    check_if_afya_card_config_is_on()
    
});

function check_if_afya_card_config_is_on(){
    $.ajax({
        type:'POST',
        url:'ajax_check_if_afya_card_config_is_on.php',
        data:{function_module:"afya_card_module"},
        success:function(data){
            if(data=="enabled"){
                read_afya_card_infomation_and_process();
            }
        }
    });
}
function openpage(){
    alert("yes");
}
</script>