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


    $Registration_ID = '';
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['Registration_ID'])){
	$Select_Patient = "select * from tbl_patient_registration where registration_id = '$Registration_ID'";
	$result = mysqli_query($conn,$Select_Patient) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($result);
	$patient_name = $row['Patient_Name'];
    }else{
	$patient_name ='';
    }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<style>
    td span:hover{
       color:red;
       cursor: pointer;
    }
</style>
<br>
<br>
<br>
<fieldset>
    <legend align=center><b>INPATIENT BILLING WORKS</b></legend>
    <center>
	    <table width = 60%>
            <tr>
                <td>
                    <input type="hidden" name="employeeID" id='employeeID' value=""><input type='text' id='inpatientname' name='inpatientname' value='<?php echo $patient_name;?>' disabled='disabled'></td><td> <button type="button" href='admittedpatientlistbillingwork.php' onclick="openDialogPatient()" class='art-button-green'>Choose Patient</button>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a id="hrefCredit" href='creditbill.php?Registration_ID=<?php echo $Registration_ID;?>&InPatientsBillingWorks=InPatientsBillingWorks'>
                        <button  id='hrefCreditbtn' style='width: 100%; height: 100%'>
                            <b>Credit Bill</b>
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a id="hrefCash" href='cashbill.php?Registration_ID=<?php echo $Registration_ID;?>&InPatientsBillingWorks=InPatientsBillingWorks'>
                        <button id='hrefCashbtn' style='width: 100%; height: 100%'>
                            <b>Cash Bill</b>
                        </button>
                    </a>
                </td>
            </tr>
        </table>
        <div id="Display_Admitted_Patient_List" style="width:50%;" >
            <div id='Details_Area' >
        	<?php //include 'admittedpatientlistbillingworkdialog.php'; ?>
            </div>
        </div>
    </center>
</fieldset><br/>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<script>
   $(document).ready(function(){
      $("#Display_Admitted_Patient_List").dialog({ autoOpen: false, width:'90%',height:550, title:'CHOOSE THE PATIENT FROM THE LIST',modal: true});
      
   });
</script>
<script>
 function openDialogPatient(){
     if(window.XMLHttpRequest){
         patientOb = new XMLHttpRequest();
     }else if(window.ActiveXObject()){
         patientOb=new ActiveXObject("Microsoft.XMLHTTP");
         patientOb.overrideMimeType('text/xml');
     }
     
     patientOb.onreadystatechange=function (html){
         //alert(html);
         document.getElementById('Details_Area').innerHTML=patientOb.responseText;
         $("#Display_Admitted_Patient_List").dialog("open");
     };
     
     patientOb.open('GET','admittedpatientlistbillingworkdialog.php',true);
     
     patientOb.send();
     
     
 }
</script>
<script>
 function setPatient(Registration_ID,inpatientname,Billing_Type){
    // alert(Registration_ID+' '+inpatientname+' '+Billing_Type);
     document.getElementById('employeeID').value=Registration_ID;
     document.getElementById('inpatientname').value=inpatientname;
     document.getElementById('hrefCredit').setAttribute("href", "creditbill.php?Registration_ID="+Registration_ID+"&InPatientsBillingWorks=InPatientsBillingWorks");
     document.getElementById('hrefCash').setAttribute("href", "cashbill.php?Registration_ID="+Registration_ID+"&InPatientsBillingWorks=InPatientsBillingWorks");
     
     if(Billing_Type=='Inpatient Cash'){
           document.getElementById('hrefCreditbtn').setAttribute("disabled", "disabled");
           document.getElementById('hrefCashbtn').removeAttribute("disabled");
     }else if(Billing_Type=='Inpatient Credit'){
          document.getElementById('hrefCashbtn').setAttribute("disabled", "disabled");
          document.getElementById('hrefCreditbtn').removeAttribute("disabled");
     }
    $("#Display_Admitted_Patient_List").dialog("close"); 
 }
</script>
<script language="javascript" type="text/javascript">
    function searchPatient(){
	var Patient_Name = document.getElementById('Patient_Name').value; 
        
        if(window.XMLHttpRequest) {
		 ajaxTimeObjt = new XMLHttpRequest();
	     }
	     else if(window.ActiveXObject){ 
		 ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
		 ajaxTimeObjt.overrideMimeType('text/xml');
	     }
		 ajaxTimeObjt.onreadystatechange= function (){
			var data = ajaxTimeObjt.responseText;
			document.getElementById('Search_Iframe').innerHTML = data;
			}; //specify name of function that will handle server response....
                        
		 ajaxTimeObjt.open('GET','admittedpatientlistbillingwork_Iframe_dialog.php?Patient_Name='+Patient_Name,true);
		 ajaxTimeObjt.send();
	}
        //document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='admittedpatientlistbillingwork_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
   
</script>          
<?php
    include("./includes/footer.php");
?>