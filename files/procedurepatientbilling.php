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
	    }else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./departmentalsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>




<?php
    if(isset($_GET['Transaction_Type'])){
	$Transaction_Type = $_GET['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    if(isset($_GET['Payment_Cache_ID'])){
	$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }
    if(isset($_GET['Billing_Type'])){
	$Temp_Billing_Type2 = $_GET['Billing_Type'];
    }else{
	$Temp_Billing_Type2 = '';
    }
?>


<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
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
    <option>-->
<!--	OUTPATIENT CREDIT-->
<!--    </option>
    <option>
	INPATIENT CASH
    </option>-->
<!--    <option>-->
<!--	INPATIENT CREDIT-->
<!--    </option>
    <option>
	PATIENT FROM OUTSIDE
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> -->


<?php
    //if(isset($_SESSION['userinfo'])){
    //    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <!--<a href='#' class='art-button-green'>-->
    <!--    VIEW - EDIT-->
    <!--</a>-->
<?php //} } ?>


<?php
    //if(isset($_SESSION['userinfo'])){
    //    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <!--<a href='#' class='art-button-green'>-->
    <!--    VIEW MY DATA-->
    <!--</a>-->
<?php //} } ?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='revenuecenterprocedurelist.php?Billing_Type=OutpatientCash&RadiologyList=RadiologyListThisForm' class='art-button-green'>
        BACK
    </a>
<?php } } ?>

<!-- old date function -->
<?php
    /*$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }*/
?>
<!-- end of old date function -->


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->


<!--popup window-->
<!-- not used-->
<!-- not used-->
<!-- not used-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>


<script type='text/javascript'>
    function di(){
        alert("All");
		$( "#d").attr("hidden","false").dialog();
	}
    function b(val){
        alert(val);
    }
</script>
<div id='d' title='CATEGORIES' hidden='hidden'>
    <a href='#' id='s' onclick="b('s')">ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
</div>
<!-- not used-->
<!-- not used-->
<!-- end of popup window-->






<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Payment_Cache_ID'])){ 
        $Payment_Cache_ID = $_GET['Payment_Cache_ID']; 
        $select_Patient = mysqli_query($conn,"select * from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
		$Temp_Billing_Type = $row['Billing_Type'];
		$Consultant = $row['Employee_Name'];
		$Folio_Number = $row['Folio_Number'];
		
		
		if(strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit' ){
		    $Billing_Type = 'Outpatient '.$Transaction_Type;
		}elseif(strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit' ){
		    $Billing_Type = 'Inpatient '.$Transaction_Type;
		}
		
		
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            
	     $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
	    /*}
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }*/
	   
	    
	    
	    
	    
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = ''; 
	    $Consultant = '';
	    $Folio_Number = '';
	    $Billing_Type = '';
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $Consultant = '';
	    $Folio_Number = '';
	    $Billing_Type = '';
        }
?>











<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>





<!-- get receipt number and receipt date-->
    <?php
	if(isset($_GET['Patient_Payment_ID'])){
	    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
	    $Patient_Payment_ID = '';
	}
	if(isset($_GET['Payment_Date_And_Time'])){
	    $Payment_Date_And_Time = $_GET['Payment_Date_And_Time'];
	}else{
	    $Payment_Date_And_Time = '';
	}
    
    ?>
<!-- end of getting receipt number and receipt date-->


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b>DEPARTMENT PAYMENTS ~ PROCEDURE</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%'><b>Patient Name</b></td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%'><b>Card Expire Date</b></td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%'><b>Gender</b></td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td><b>Receipt Number</b></td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td><b>Billing Type</b></td> 
				<td>
                                    <select name='Billing_Type' id='Billing_Type'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<td><b>Claim Form Number</b></td>
                                <!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
				<?php
					$select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
					$no = mysqli_num_rows($select_claim_status);
					if($no > 0){
					    while($row = mysqli_fetch_array($select_claim_status)){
						$Claim_Number_Status = $row['Claim_Number_Status'];
					    }
					}else{
					    $Claim_Number_Status = '';
					}
				    ?>
				    <?php if(strtolower($Claim_Number_Status) == 'mandatory'){ ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' placeholder='Claim Form Number'></td>
				    <?php } else { ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
				    <?php } ?>
                                <td><b>Occupation</b></td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				
				<td><b>Receipt Date & Time</b></td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td><b>Type Of Check In</b></td>
                                <td>  
				    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
					<option selected='selected'>Pharmacy</option> 
				    </select>
				</td>
                                <td><b>Patient Age</b></td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td><b>Registered Date</b></td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				
				<td><b>Folio Number</b></td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td><b>Patient Direction</b></td>
                                <td>
                                    <select id='direction' name='direction' required='required'> 
					<option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td><b>Sponsor Name</b></td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
				<td><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td><b>Consultant</b></td>
                                <td>
				    <select name='Consultant' id='Consultant'>
					<option selected='selected'><?php echo $Consultant; ?></option>
				    </select>
				</td>
                                <td><b>Registration Number</b></td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                                <td><b>Member Number</b></td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
				
				<td><b>Supervised By</b></td>
				
				<?php
				    if(isset($_SESSION['supervisor'])) {
					if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
					    if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
						$Supervisor = $_SESSION['supervisor']['Employee_Name'];
					    }else{
						$Supervisor = "Unknown Supervisor";
					    }
					}else{
						$Supervisor = "Unknown Supervisor";
					}
				    }else{
						$Supervisor = "Unknown Supervisor";
				    }
				?> 
                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                            </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>

<fieldset>   
        <center>
            <table width=100%>
		<tr>
		   <td style='text-align: center;'>
			<?php
			    if(isset($_GET['Payment_Cache_ID'])){
				$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
			    }else{
				$Payment_Cache_ID = '';
			    }
			   
			   /*
			    if(isset($_SESSION['Pharmacy'])){
				$Sub_Department_Name = $_SESSION['Pharmacy'];
			    }else{
				$Sub_Department_Name = '';
			    }
			    */
			    if(isset($_GET['Sub_Department_ID'])){
				$Sub_Department_ID = $_GET['Sub_Department_ID']; 
			    }else{
				$Sub_Department_ID = 0;
			    }
			   
			    
			    $Transaction_Status_Title = ''; 
			    //create sql
			    $Check_Status = "select Status, Transaction_Type from tbl_item_list_Cache where
						Transaction_Type = '$Transaction_Type' and
						    Payment_Cache_ID = '$Payment_Cache_ID' and
							Sub_Department_ID = '$Sub_Department_ID' and
							    status = ";
			    
				//check for paid
				$sqlSt = $Check_Status."'paid'";
				$select_Status = mysqli_query($conn,$sqlSt);
				$no = mysqli_num_rows($select_Status);
				if($no > 0){
				    $Transaction_Status_Title = 'PAID';
				}else{
                                    //check for active
                                    $sqlSt = $Check_Status."'active'";
                                    $select_Status = mysqli_query($conn,$sqlSt);
                                    $no = mysqli_num_rows($select_Status);
                                    if($no > 0){
                                        $Transaction_Status_Title = 'NOT PAID';
                                    }				    
				}
			    
			    if(!isset($_GET['Payment_Cache_ID'])){
				$Transaction_Status_Title = 'NO PATIENT SELECTED';
			    }
                             $_SESSION['Transaction_Status_Title']=$Transaction_Status_Title;
                           
			echo '<b>STATUS : '.$Transaction_Status_Title.'</b>'; 
			?>
			
                   </td>
		   <td style='text-align: right;' width=30%>
		    
			<?php
			    if($Transaction_Status_Title == 'NOT PAID'){
                        ?>
                        <input type='button' name='Make_Payment' id='Make_Payment' value='Make Payment' onclick='Make_Payment_Laboratory()' class='art-button-green'>
                        <?php }
			//	echo "<a href='Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Temp_Billing_Type2."' class='art-button-green'>
			//		    Make Payment
			//		</a>";
			//    }
			?>
			
			<?php 
			    if($Patient_Payment_ID != '' && $Transaction_Status_Title == 'PAID'){
				// echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
				// Print Receipt
			    // </a>";
				
				   echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Receipt' onclick='Print_Receipt_Payment()' class='art-button-green'>";
			    }
			?>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>


<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
			<!-- get Sub_Department_ID from the URL -->
			<?php if(isset($_GET['Sub_Department_ID'])) { $Sub_Department_ID = $_GET['Sub_Department_ID']; }else{ $Sub_Department_ID = 0; } ?>
			<div id="patientItemsList" style='height:200px;overflow-y:scroll;overflow-x:hidden'>
                            <center><b>List of Items </b></center>
                            <?php include "Patient_Billing_Procedure_Iframe.php";?>
                        </div>
                       
		    </td>
		</tr>
        <tr id="totalAmount">
          <?php echo $dataAmount; ?>
        </tr>
	    </table>		
	    </table>
        </center>
</fieldset>
<script>
function Print_Receipt_Payment(){
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");

    var data = "<?php echo $Patient_Payment_ID; ?>"
    if(checkForMaximmumReceiptrinting(data) === 'true'){

      var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
      //winClose.close();
     //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');


      $.ajax({
                    type:"POST",
                    url:"update_receipt_count.php",
                    async:false,
                    data:{payment_id:data},
                    success:function(result){
                        console.log(result)
                    }
                })

}else{
        alert("You have exeded maximumu print count")
        return false;
    }
    
}
  


 function checkForMaximmumReceiptrinting(theId){
    
    var theCount = '';
    $.ajax({
                    type:"POST",
                    url:"compare_receipt_count.php",
                    async:false,
                    data:{payment_id:theId},
                    success:function(result){
                        // alert(result)
                        theCount = result;
                        console.log(theCount)
                                                
                    }
                })

return theCount;
}
 
function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<script>
    function Make_Payment_Laboratory() {
        var Confirm_Message = confirm("Are you sure you want to perform transaction?");
        if (Confirm_Message == true) {
            document.location = 'Patient_Billing_Laboratory_Page.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>';
        }
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>


<?php
    include("./includes/footer.php");
?>