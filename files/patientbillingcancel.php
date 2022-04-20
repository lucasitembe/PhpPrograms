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
		/*@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}*/
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<!-- CHECK USER PRIVILEGES BEFORE TO CONTINUE THE PROCESS-->
    <?php
	if(isset($_GET['Patient_Payment_ID'])){
	    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	    $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_patient_payments
						    where Patient_Payment_ID = '$Patient_Payment_ID'");
	    while($row = mysqli_fetch_array($select_billing_type)){
		$Billing_Type = $row['Billing_Type'];
	    }
	    if((strtolower($Billing_Type) == 'outpatient cash') || (strtolower($Billing_Type) == 'inpatient cash')){
		if(strtolower($_SESSION['userinfo']['Modify_Cash_information']) != 'yes'){
		    header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage");
		}
	    }elseif((strtolower($Billing_Type) == 'outpatient credit') || (strtolower($Billing_Type) == 'inpatient credit')){
		if(strtolower($_SESSION['userinfo']['Modify_Credit_Information']) != 'yes'){
		    header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage");
		}
	    }
	} 
    ?>



<?php
    if(isset($_SESSION['userinfo'])){
      if(($_SESSION['userinfo']['Modify_Cash_information'] == 'yes') || ($_SESSION['userinfo']['Modify_Credit_Information'] == 'yes')){ 
          if(isset($_GET['Cancel'])){
           echo "<a href='canceldirectTransactionsReport.php?Section=GeneralLedger&CancelDirectCashTransaction=CancelDirectCashTransactionThisPage' class='art-button-green'> BACK </a>";   
              
          }else{
             echo "<a href='edittransaction.php?EditTransaction=EditTransactionThisPage' class='art-button-green'> BACK </a>";     
          }
          
      }  
        
    }

?>

<!--Cancel=Report-->

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
<!-- not used-->
<!-- end of popup window-->


<?php
//    select patient information
    if(isset($_GET['Patient_Payment_ID'])){ 
        $Patient_Payment_ID = $_GET['Patient_Payment_ID']; 
        $select_Patient = mysqli_query($conn,"select *							
                                    from tbl_patient_registration pr, tbl_sponsor sp, tbl_patient_payments pp,
					tbl_patient_payment_item_list ppl, tbl_employee emp
					    where pr.Sponsor_ID = sp.Sponsor_ID and
						emp.employee_id = pp.employee_id and    
						    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Registration_ID = pr.Registration_ID and
							pp.Patient_Payment_ID = '$Patient_Payment_ID'
						    ") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
		$Billing_Type = $row['Billing_Type'];
                $Gender = $row['Gender'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
		$Folio_Number = $row['Folio_Number'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
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
		$Employee_Name = $row['Employee_Name'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
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
	    $Folio_Number = '';
            $Date_Of_Birth = '';
	    $Payment_Date_And_Time = '';
            $Gender = '';
            $Region = '';
            $District = '';
	    $Billing_Type = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Form_Number = '';
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
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
	    $Payment_Date_And_Time = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
	    $Billing_Type = '';
	    $Folio_Number = '';
            $Guarantor_Name = '';
            $Member_Number = '';
	    $Claim_Form_Number = '';
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
        }
?>

<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    getPrice();
	    var ItemListType = document.getElementById('Type').value;
	    getItemListType(ItemListType);
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetItemList.php?Item_Category_Name='+Item_Category_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('Item_Name').innerHTML = data;	
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
	    var Item_Category_Name = document.getElementById('Item_Category').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    getPrice();
	    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
	    mm.open('GET','GetItemListType.php?Item_Category_Name='+Item_Category_Name+'&Type='+Type,true);
	    mm.send();
	}
    function AJAXP2() {
	var data = mm.responseText; 
	document.getElementById('Item_Name').innerHTML = data;	
    }
</script>
<!-- end of filtering-->








<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
	var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    if (document.getElementById('direction').value =='Direct To Doctor Via Nurse Station' || document.getElementById('direction').value =='Direct To Doctor') {
		mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
		mm.open('GET','Get_Guarantor_Name.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=doctor',true);
		mm.send();
	    }
	    else{
		mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
		mm.open('GET','Get_Guarantor_Name.php?direction=clinic',true);
		mm.send();
	    }
	}
    function AJAXP3(){
	var data = mm.responseText;
	document.getElementById('Consultant').innerHTML = data;
    }
</script>
<!-- end of selection-->




<!-- pricing -->
<script type='text/javascript'>
    function getPrice() {
	
	var Product_Name = document.getElementById('Item_Name').value;
	var Billing_Type = document.getElementById('Billing_Type').value;
	var Guarantor_Name = document.getElementById('Guarantor_Name').value;
	
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
		mm.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
		mm.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
		mm.send();
	}
    function AJAXP4(){
	var data = mm.responseText;
	document.getElementById('Price').value = data;
	var price = document.getElementById('Price').value;
	var discount = document.getElementById('Discount').value;
	var quantity = document.getElementById('Quantity').value
	var ammount = 0;
	
	ammount = (price-discount)*quantity;
	document.getElementById('Amount').value = ammount;
    }
</script>






<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->
<?php
     
?>






<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>


<?php
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	$select_items = mysqli_query($conn,"select * from tbl_patient_payment_item_list
				    where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($select_items)){
	    $Check_In_Type = $row['Check_In_Type'];
	    $Patient_Direction = $row['Patient_Direction'];
	    $Consultant = $row['Consultant'];
	}
    }else{
	    $Check_In_Type = "";
	    $Patient_Direction = "";
	    $Consultant = "";
	    $Patient_Payment_Item_List_ID = 0;
    }
?>

<!-- submit all changes-->
<script type='text/javascript'>
    
    
    function submitted(){ 
        var cancel_reason=document.getElementById('cancel_reason').value;
	var Patient_Payment_ID = document.getElementById('Receipt_Number').value;
        
       var length=cancel_reason.trim().length;
         if(length<=10){
           alert('Reason must be at least 10 characters long!');
           return false;
         }else{
	//redirect to the cancel page then get back to this page
            var confirmDialogy = confirm("Are you sure you want to CANCEL this transaction?"); 
            if (confirmDialogy) {
                document.location = "./canceltransaction.php?Patient_Payment_ID="+Patient_Payment_ID+'&cancel_reason='+cancel_reason; 
            }  
         }
        

    }
</script>
<!-- end of submit-->


<form action='#' method='post' name='frmProduct' id='frmProduct' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b>Edit Transaction</b></legend>
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
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
				<td><b>Receipt Number</b></td>
                                <td><input type='text' name='Receipt_Number' readonly='readonly' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td><b>Billing Type</b></td>
				
				<?php if(isset($_GET['NR']) || isset($_GET['CP'])) { ?>
				    <?php if(strtolower($Guarantor_Name) == 'cash') {?>
				    <td>
					<select name='Billing_Type' id='Billing_Type'  onchange='getPrice()' required='required'>
					    <option selected='selected'></option>
					    <option>Outpatient Cash</option> 
					</select>
				    </td>
				    <?php }else{ ?>
					<td>
					<select name='Billing_Type' id='Billing_Type'  onchange='getPrice()' required='required'>
					    <option selected='selected'></option>
					    <option>Outpatient Cash</option>
					    <option>Outpatient Credit</option> 
					</select>
				    </td>
				    <?php } ?>
				<?php }else{ ?>
				<td>
                                    <select name='Billing_Type' id='Billing_Type' disabled='disabled'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<?php } ?>
				
                                <td><b>Claim Form Number</b></td>
				    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' disabled='disabled' value='<?php echo $Claim_Form_Number; ?>'></td> 
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
				    
				<!-- select type of check-in type only if item selected-->
				<?php if($Check_In_Type == ''){ ?>
				<select name='Type_Of_Check_In' id='Type_Of_Check_In' disabled='disabled' required='required' onchange='examType()' onclick='examType()'>
				<?php } else { ?>
				<select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'>
				<?php } ?>
				    <?php if(!isset($_GET['NR'])){ ?>
					<option selected='selected'><?php echo $Check_In_Type; ?></option>
				    <?php } else{ ?>
					<option selected='selected'><?php $Check_In_Type; ?></option>
				    <?php } ?>
				    
				    <option>Radiology</option>
				    <option>Dialysis</option>
				    <option>Physiotherapy</option>
				    <option>Optical</option> 
				    <option>Doctor Room</option>
				    <option>Dressing</option>
				    <option>Matenity</option>
				    <option>Cecap</option>
				    <option>Laboratory</option>
				    <option>Theater</option>
				    <option>Dental</option>
				    <option>Ear</option>
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
				    <?php if($Patient_Direction == ''){ ?>
                                    <select id='direction' name='direction' disabled='disabled' onclick='getDoctor()' required='required'>
				    <?php }else{ ?>
				    <select id='direction' name='direction' onclick='getDoctor()' required='required'>
				    <?php } ?>
					<?php if(!isset($_GET['NR']) && !isset($_GET['CP'])){ ?>
					    <option selected='selected'><?php echo $Patient_Direction; ?></option>
					<?php }else{ ?>
					<option selected='selected'></option>
					<?php } ?>
                                        <option>Direct To Doctor</option>
                                        <option>Direct To Doctor Via Nurse Station</option>
                                        <option>Direct To Clinic</option>
                                        <option>Direct To Clinic Via Nurse Station</option>
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
				    <?php if($Consultant == ''){ ?>
				    <select name='Consultant' id='Consultant' disabled='disabled' value='<?php echo $Consultant; ?>'>
				    <?php }else{ ?>
				    <select name='Consultant' id='Consultant' value='<?php echo $Consultant; ?>'>
				    <?php } ?>
					<?php if(!isset($_GET['NR']) && !isset($_GET['CP'])){ ?>
					    <option selected='selected'><?php echo $Consultant; ?></option>
					<?php }else{ ?>
					<option selected='selected'></option>
					<?php } ?>
					
					<?php
					$Select_Consultant = "select * from tbl_clinic"; 
					$result = mysqli_query($conn,$Select_Consultant);
					?> 
					<?php
					while($row = mysqli_fetch_array($result)){
					    ?>
					    <option><?php echo $row['Clinic_Name']; ?></option>
					<?php
					}
					
					$Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor'"; 
					$result = mysqli_query($conn,$Select_Doctors);
					?> 
					<?php
					while($row = mysqli_fetch_array($result)){
					    ?>
					    <option><?php echo $row['Employee_Name']; ?></option>
					<?php
					}
					
					?>
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
                            <tr>
                                
                                
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
                    <?php
                    
                        $Transaction_Status_Title = '';
                        //check if this transaction already cancelled
                        if(isset($_GET['Patient_Payment_ID'])){
                            $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
                        }else{
                            $Patient_Payment_ID = 0;
                        }
                        
                        $get_Transaction_status = mysqli_query($conn,"select Transaction_status from tbl_patient_payments
                                                                where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                        $num_row = mysqli_num_rows($get_Transaction_status);
                        if($num_row > 0){
                            while($row = mysqli_fetch_array($get_Transaction_status)){
                                $Transaction_status = $row['Transaction_status'];
                            }
                        }else{
                            $Transaction_status = '';
                        }
                        
                        
                        if(strtolower($Transaction_status) == 'cancelled'){
                            $result = mysqli_query($conn,"select * from tbl_cancelled_transaction_details ctd, tbl_employee emp
                                                    where ctd.Patient_Payment_ID = '$Patient_Payment_ID' and
                                                        ctd.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
                            while($row2 = mysqli_fetch_array($result)){
                                $Employee_Name = $row2['Employee_Name'];
                                $Cancelled_Date_And_Time = $row2['Cancelled_Date_And_Time'];
                                $reason=$row2['Cancel_transaction_reason'];
                            }
                            
                            if($reason=='' || $reason=='NULL'){
                                $transaction_reason='None';
                            }else{
                               $transaction_reason=$reason;
                            }
                            
                            echo "<td style='text-align: center;'>
                                    This transaction was cancelled by ".$Employee_Name.", Date and Time : ".$Cancelled_Date_And_Time." , Reason : ".$transaction_reason."
                                </td>";                     
                            
                            
                        }else{
                            echo "<td style='text-align: right;'>
                                <input type='button' name='submit' id='submit'  value='Cancel This Transaction' class='art-button-green'>
                                <input type='hidden' name='submittedCancelTransactionForm' value='true'/>
                                </td>";
                        }
                        
                        
                        
                        
                        
                        
                    ?>
                     
                    
                </tr>
            </table> 
            
            <div id="AllItems" style="display: none">
               
                <textarea id="cancel_reason"> 
                
                </textarea> 
                
                <br /><br />
                
                <center> <input type="button" value="Cancel Transaction"  onclick='return submitted()' class="art-button-green"></center>
                
            </div>
        </center>
</fieldset>
<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
                        
                    <!-- Get Patient ID -->
                    <?php if(isset($_GET['Patient_Payment_ID'])){ $Patient_Payment_ID = $_GET['Patient_Payment_ID']; }else{ $Patient_Payment_ID = 0; } ?>
			<iframe src="Patient_Billing_Cancel_Iframe.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>" width='100%' height=210px></iframe>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>



<!-------Cancel reason dialog-------->
<script>
$('#submit').on('click',function(){
         $('#AllItems').dialog({
          modal:true, 
          width:'40%',
          minHeight:200,
          resizable:true,
          draggable:true, 
          title:"Reason for cancelling Transaction",
        });  
        
    });

</script>

