<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $query_string="section=".$_GET['section']."&Registration_ID=".$_GET['Registration_ID']."&Transaction_Type=".$_GET['Transaction_Type']."&Payment_Cache_ID=".$_GET['Payment_Cache_ID']."&NR=".$_GET['NR']."&Billing_Type=".$_GET['Billing_Type']."&Sub_Department_ID=".$_GET['Sub_Department_ID']."&LaboratoryWorks=".$_GET['LaboratoryWorks']."";
    $_SESSION['Transaction_Type']=$_GET['Transaction_Type'];
    $_SESSION['Payment_Cache_ID']=$_GET['Payment_Cache_ID'];
    $_SESSION['Sub_Department_ID']=$_GET['Sub_Department_ID'];
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
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&LaboratoryList=LaboratoryListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&LaboratoryList=LaboratoryListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&LaboratoryList=LaboratoryListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&LaboratoryList=LaboratoryListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=PatientFromOutside&LaboratoryList=LaboratoryListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option>Select List To View</option>
    <option>
	OUTPATIENT CASH
    </option>
    <option>
	INPATIENT CASH
    </option>
    <option>
	PATIENT FROM OUTSIDE
    </option>
</select>
</label> 
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&LaboratoryList=LaboratoryListThisForm' class='art-button-green'>
        BACK
    </a>
<?php } } ?>

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
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
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
  
        } else {
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
    } else {
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
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='Patient_Billing_Laboratory_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<br/>

<fieldset>  
        <legend align=right><b>PATIENT PAYMENTS ~ LABORATORY</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style="text-align:right;">Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%' style="text-align:right;">Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td style="text-align:right;">Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
				<td>
                                    <select name='Billing_Type' id='Billing_Type'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<td style="text-align:right;" >Claim Form Number</td>
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
                                <td style="text-align:right;">Occupation</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				
				<td style="text-align:right;">Receipt Date & Time</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Type Of Check In</td>
                                <td>  
				    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
					<option selected='selected'>Laboratory</option> 
				    </select>
				</td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registered Date</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				
				<td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' required='required'> 
					<option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
				<td style="text-align:right;">Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Consultant</td>
                                <td>
				    <select name='Consultant' id='Consultant'>
					<option selected='selected'><?php echo $Consultant; ?></option>
				    </select>
				</td>
                                <td style="text-align:right;">Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                                <td style="text-align:right;">Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
				
				<td style="text-align:right;">Supervised By</td>
				
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
			    if(isset($_SESSION['Laboratory'])){
				$Sub_Department_Name = $_SESSION['Laboratory'];
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
			    $sqlSt = $Check_Status."'dispensed'";
			    //check for dispensed
			    $select_Status = mysqli_query($conn,$sqlSt); 
			    $no = mysqli_num_rows($select_Status);
			    
                            //check for paid
                            $sqlSt = $Check_Status."'paid'";
                            $select_Status = mysqli_query($conn,$sqlSt);
                            $no = mysqli_num_rows($select_Status);
                            if($no > 0){
							
                                
								
								$sqlSt = $Check_Status."'active'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
								if($no > 0){
								  $Transaction_Status_Title = 'NOT PAID';
								}else{
								   $Transaction_Status_Title = 'PAID';
								}
								
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
                        
                        if($Transaction_Status_Title == 'NOT PAID'){
                              //  echo '<button class="art-button-green" type="button" style="float:left;" onclick="openItemDialog()">Add More Item(s)</button><img id="loader" style="float:left;display:none" src="images/22.gif"/>';
                           }
			?>
			
                   </td>
		   <td style='text-align: right;' width=30%>
		    
			<?php
			    if($Transaction_Status_Title == 'NOT PAID'){
                        ?>
                        <!--<input type='button' name='Make_Payment' id='Make_Payment' value='Make Payment' onclick='Make_Payment_Laboratory()' class='art-button-green'>-->
                        <?php }
			//	echo "<a href='Patient_Billing_Laboratory_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Temp_Billing_Type2."' class='art-button-green'>
			//		    Make Payment
			//		</a>";
			//    }
			?>
			
			<?php 
			    if($Patient_Payment_ID != '' && $Transaction_Status_Title == 'PAID'){
				echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
				Print Receipt
			    </a>";
			    }
				
		
			?>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>

</form>

<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
                        <form id="saveDiscount">
			<!-- get Sub_Department_ID from the URL -->
			<?php if(isset($_GET['Sub_Department_ID'])) { $Sub_Department_ID = $_GET['Sub_Department_ID']; }else{ $Sub_Department_ID = 0; } ?>
                        <div id="patientItemsList" style='height:200px;overflow-y:scroll;overflow-x:hidden'>
                            <center><b>List of Items </b></center>
                            <?php include "Patient_Billing_Unpaid_Laboratory_Iframe.php";?>
                        </div>
                        </form>
<!--			<iframe src='Patient_Billing_Laboratory_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>' width='100%' height=270px></iframe>-->
		    </td>
		</tr>
                <tr id="totalAmount">
                    <?php echo $dataAmount; ?>
                </tr>
           </table>
        </center>
</fieldset>
<!--Dialog div-->
<div id="addTests" style="width:100%;overflow:hidden;display: none;" >
   
    <fieldset>
        <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
            <center>
                <table width = "100%" style="border:0 " border="1">
                <tr>
                    <td width="40%" style="text-align: center"><input  type="text" name="search" id="search_medicene" placeholder="-----------------------------------------Search Item-------------------------------------------" onkeyup="searchMedicene(this.value)"></td><td width="50%" style="text-align: center"><button style="width:90%;font-size:20px; " name="submitadded" class="art-button-green" type="button" onclick="submitAddedItems()">Add Item(s)</button></td>
                </tr>   
                <tr>
                    <td width="40%" style="text-align: center"><b>Items</b></td><td width="50%" style="text-align: center"><b>Chosen Tests</b></td>
                </tr>
                <tr>
                <td width="40%">
                   <!--Show tests for the section--> 
                   <div id="items_to_choose" style="height:400px;">
                        <table id="loadDataFromItems">
                        </table>
                    </div>
                </td>
                <td width="50%">
                    <!--Display selected tests for the section--> 
                    <div id="displaySelectedTests"  style="height:400px;width:100% ">
                        <form id="addedItemForm" action="" method="post"> 
                        <table width="100%" id="getSelectedTests">
                            
                        </table>
                        </form>
                    </div>
                </td>
                </tr>
            </table>
                   </center>
    </fieldset><br/>
    
</div>
<script>
    function Make_Payment_Laboratory() {
        //Save discount before print
       // var datastring=$("form#saveDiscount").serialize();
        
        //end saving
        
        
        var Confirm_Message = confirm("Are you sure you want to perform transaction?");
        if (Confirm_Message == true) {
            document.location = 'Patient_Billing_Laboratory_Page.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>';
        }
    }
    
     function openItemDialog(){
     //Load data to the div
      $("#loader").show();
       $('#getSelectedTests').html('<tr><td style="" ><b>Description</b></td><td style=""><b>Price</b></td></tr>');
       $.ajax({
         type: 'GET',
         url: "search_item_for_test.php",
         data: "loadData=true&section=<?php echo $_GET['section']?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>",
	   success: function (data) {
		     // alert(data['data']);
              $('#loadDataFromItems').html(data);
          },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
      });
       $("#addTests").dialog("open");
               
     }
 
 function removeitem(item){
         // alert(item);
         var check=confirm("Are you sure you want to remove this quantity");
    if(check){     
     $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "Payment_Item_Cache_List_ID="+item,
		 dataType:"json",
         success: function (data) {
		     // alert(data['data']);
              $('#patientItemsList').html(data['data']);
				$('#totalAmount').html(data['total_amount']);			  
             			  
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
    }
 }
 
 function vieweRemovedItem(){
         // alert(item);
         
     $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "viewRemovedItem=true",
         dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);	          
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
 function addItem(item){
      $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "readdItem="+item,
          dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);         
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
 function showItem(){
      $.ajax({
         type: 'POST',
         url: "change_items_info.php",
         data: "show_all_items=true",
         dataType:"json",
         success: function (data) {
	    $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);    
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
  function submitAddedItems(){
     
     var datastring=$("form#addedItemForm").serialize();
     
   if(datastring!==''){     
     $.ajax({
         type: 'POST',
         url: "search_item_for_test.php",
         data: "addMoreItems=true&"+datastring+'&section=<?php echo $_GET['section']?>',
         success: function (data) {
		// alert(data);
             if(data=='saved'){
                showItem();
                $("#addTests").dialog("close");
             }//alert(data);
//              $('#patientItemsList').html(data);          
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
   }else{
       alert("No data set");
 }
  $("#loader").hide();
 }
function searchMedicene(search){
   if(search !==''){ 
    $.ajax({
         type: 'GET',
         url: "search_item_for_test.php",
         data: "section=<?php echo $_GET['section']?>&search_word="+search,
         success: function (data) {
            if(data !==''){
              $('#items_to_choose').html(data);   
             }
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
     }
}

function number_format(number, decimals, dec_point, thousands_sep) {
  //  discuss at: http://phpjs.org/functions/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56);
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ');
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '');
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.');
  //   returns 4: '67,00'
  //   example 5: number_format(1000);
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2);
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1);
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.');
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0);
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2);
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4);
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3);
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ');
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '');
  //  returns 14: '0.00000001'

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
#displaySelectedTests,#items_to_choose{
    overflow-y:scroll;
    overflow-x:hidden; 
}
</style>

    <script type='text/javascript'>
        function LaboratoryQuantityUpdate(Payment_Item_Cache_List_ID,Quantity) {
	     if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    } 
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','LaboratoryQuantityUpdate.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Quantity='+Quantity,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
            }
        }
        
         $(document).ready(function(){
            $("#addTests").dialog({ autoOpen: false, width:900,height:560, title:'Choose an Item',modal: true});
//       $(".ui-widget-header").css("background-color","blue");  
        
        $(".chosenTests").live("click",function(){
            //alert("chosen");
             var id=$(this).attr("id");
            if($(this).is(':checked')){
              
              
               $.ajax({
                    type: 'GET',
                    url: "search_item_for_test.php",
                    data: "section=<?php echo $_GET['section']?>&adthisItem="+id,
                    success: function (data) {
                        if(data !==''){
                         $('#getSelectedTests').append(data); 
                        }
                    },error: function (jqXHR, textStatus, errorThrown) {
                      alert(errorThrown);               
                    }
                });
              
            }else{
                $("#itm_id_"+id).remove();
            }
        });
        
         $(".ui-icon-closethick").click(function(){
//         $(this).hide();
            $("#loader").hide();
        });
        
    });
    </script>
<?php
    include("./includes/footer.php");
?>