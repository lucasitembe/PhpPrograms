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
    
    $query_string="section=".$_GET['section']."&Registration_ID=".$_GET['Registration_ID']."&Transaction_Type=".$_GET['Transaction_Type']."&Payment_Cache_ID=".$_GET['Payment_Cache_ID']."&NR=".$_GET['NR']."&Billing_Type=".$_GET['Billing_Type']."&Sub_Department_ID=".$_GET['Sub_Department_ID']."&PharmacyWorks=".$_GET['PharmacyWorks']."";
    $_SESSION['Registration_ID']=$_GET['Registration_ID'];
    $_SESSION['Transaction_Type']=$_GET['Transaction_Type'];
    $_SESSION['Payment_Cache_ID']=$_GET['Payment_Cache_ID'];
    $_SESSION['Sub_Department_ID']=$_GET['Sub_Department_ID'];
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

 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='revenuecenterpharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm' class='art-button-green'>
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
        $select_Patient = mysqli_query($conn,"select pr.Phone_Number, pr.Registration_ID, pr.Old_Registration_Number, pr.Title, pr.Patient_Name, sp.Sponsor_ID, pr.Date_Of_Birth, pr.Member_Card_Expire_Date,
                                        pr.Gender, pr.Region, pr.District, pr.Ward, sp.Guarantor_Name, pr.Member_Number, pr.Email_Address, pr.Occupation, pr.Employee_Vote_Number,
                                        pr.Emergence_Contact_Name, pr.Emergence_Contact_Number, pr.Company, emp.Employee_ID, pr.Registration_Date_And_Time, pc.Billing_Type, emp.Employee_Name, pc.Folio_Number
                                        from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
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
<br/>
<fieldset>  
    <legend align=right><b>REVENUE CENTER ~ PHARMACY PAYMENTS</b></legend>
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
				                <td style="text-align:right;">Claim Form Number</td>
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
					<option selected='selected'>Pharmacy</option> 
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
			    $sqlSt = $Check_Status."'dispensed'";
			    //check for dispensed
			    $select_Status = mysqli_query($conn,$sqlSt); 
			    $no = mysqli_num_rows($select_Status);
			    if($no > 0){
				  $sqlSt = $Check_Status."'approved'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
								if($no > 0){
								  $Transaction_Status_Title = 'APPROVED';
								}else{
								   $sqlSt = $Check_Status."'paid'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
									if($no > 0){
									  $Transaction_Status_Title = 'PAID';
									}else{
									   $Transaction_Status_Title = 'DISPENCED';
									}
								   //$Transaction_Status_Title = 'DISPENCED';
								}
			    }else{
				//check for paid
				$sqlSt = $Check_Status."'paid'";
				$select_Status = mysqli_query($conn,$sqlSt);
				$no = mysqli_num_rows($select_Status);
				if($no > 0){
				    $sqlSt = $Check_Status."'approved'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
								if($no > 0){
                                                                    $sqlSt = $Check_Status."'paid'";
								//check for active
								$select_Status = mysqli_query($conn,$sqlSt); 
								$no = mysqli_num_rows($select_Status);
								
									if($no > 0){
									  $Transaction_Status_Title = 'PAID';
									}else{
									   $Transaction_Status_Title = 'APPROVED';
									}
								  //$Transaction_Status_Title = 'APPROVED';
								}else{
								   $Transaction_Status_Title = 'PAID';
								}
				}else{
				    //check for approved
				    $sqlSt = $Check_Status."'approved'";
				    $select_Status = mysqli_query($conn,$sqlSt);
				    $no = mysqli_num_rows($select_Status);
				    if($no >0){
					$Transaction_Status_Title = 'APPROVED';
				    }else{
					//check for active
					$sqlSt = $Check_Status."'active'";
					$select_Status = mysqli_query($conn,$sqlSt);
					$no = mysqli_num_rows($select_Status);
					if($no > 0){
					    $Transaction_Status_Title = 'NOT YET APPROVED';
					}
				    }
				}
			    }
			    
			    if(!isset($_GET['Payment_Cache_ID'])){
				$Transaction_Status_Title = 'NO PATIENT SELECTED';
			    }
			//echo '<b>STATUS : '.$Transaction_Status_Title.'</b>'; 
                        
                        if($Transaction_Status_Title == 'APPROVED'){
                               /*  echo '<button class="art-button-green" type="button" style="float:left;" onclick="openItemDialog()">Add More Item(s)</button><img id="loader" style="float:left;display:none" src="images/22.gif"/>'; */
                            }
			?>
			<td style="text-align: center;" width="70%">
                <?php
                    $total = 0;
                    $data='';
                    $dataAmount='';
                    $zero_price_items_count=0;
                    $zero_price_items='';
                    $select_Transaction_Items_Active = mysqli_query($conn,"
                                            select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                            from tbl_item_list_cache ilc, tbl_Items its
                                            where ilc.item_id = its.item_id and
                                            (ilc.status = 'approved' or ilc.status = 'active') and
                                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                            ilc.Transaction_Type = '$Transaction_Type' and
                                            ilc.Check_In_Type = 'Pharmacy'"); 
        
      

                    $no = mysqli_num_rows($select_Transaction_Items_Active);    
                    //display all medications
                    if($no > 0){
                        while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                            $status = $row['status'];
                            if($row['Price'] == 0){
                                $zero_price_items_count +=1;
                                if(empty( $zero_price_items)){
                                     $zero_price_items=$row["Payment_Item_Cache_List_ID"].'achanisha';
                                }else{
                                     $zero_price_items .=$row["Payment_Item_Cache_List_ID"].'achanisha';
                                }
                            }
                        
                            if($row['Edited_Quantity'] == 0){  
                                $Quantity = $row['Quantity']; 
                            }else{ 
                                $Quantity = $row['Edited_Quantity'];
                            }
                            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                        }
                        if($zero_price_items_count > 0){
                            echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value="'.$zero_price_items.'"  />';
                        }else{
                            echo '<input type="hidden" name="Item_price_zero" id="Item_price_zero" value=""  />';
                        }
                        $dataAmount= "<td style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td>"; 
                    }
                    echo '<h3><b>AMOUNT REQUIRED : '.number_format($total).'</b></h3>';
                ?>
            </td>
            <td style='text-align: right;'>
    			<?php
                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                ?>
                        <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                <?php } ?>
                    <input type='button' name='Make_Payment' id='Make_Payment' value='Make Payment' onclick='Make_Payment_Laboratory()' class='art-button-green'>&nbsp;&nbsp;
    			<?php 
    			    if($Patient_Payment_ID != '' && $Transaction_Status_Title == 'PAID'){
    				  echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Receipt' onclick='Print_Receipt_Payment()' class='art-button-green'>";
    			    }
    			?>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>


 
</form>
<!--Dialog div-->
<div id="addTests" style="width:100%;overflow:hidden; display: none;" >
   
    <fieldset>
        <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
            <center>
                <table width = "100%" style="border:0 " border="1">
                <tr>
                    <td width="40%" style="text-align: center"><input type="text" name="search" id="search_medicene" placeholder="-----------------------------------------Search Item-------------------------------------------" onkeyup="searchMedicene(this.value)"></td><td width="50%" style="text-align: center"><button style="width:90%;font-size:20px; " name="submitadded" class="art-button-green" type="button" onclick="submitAddedItems()">Add Item(s)</button></td>
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
                            <tr>
                               <td style="width:35%" ><b>Description</b></td><td style="width:15%"><b>Price</b></td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </td>
                </tr>
            </table>
                   </center>
    </fieldset><br/>
    
</div>



<div id="ePayment_Window" style="width:50%;">
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>



<script>
   $(document).ready(function(){
      $("#ePayment_Window").dialog({ autoOpen: false, width:'55%',height:250, title:'Create ePayment Bill',modal: true});
   });
</script>

<script>
function Print_Receipt_Payment(){
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
      var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
      //winClose.close();
     //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');
    
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
        var itemzero = document.getElementById('Item_price_zero').value;
        if (itemzero != '' && itemzero != null){
            //alert('Some medication missing price. All medication with zero price will be ignored.');
            var Confirm_Message = confirm("Some medication missing price please try one of the options below\n\n 1.Inform Doctor to review the bill before payment process\n 2. Click OK to create transaction without unpriced medication.");
        }else{
            var Confirm_Message = confirm("Are you sure you want to perform transaction?");
        }
        
        if (Confirm_Message ) {
            if (itemzero != '' && itemzero != null){
                 //alert('seen');
              document.location = 'Patient_Billing_Pharmacy_Page.php?itemzero='+itemzero+'&Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Temp_Billing_Type2; ?>';
            } else{
               //  alert('seen2');
              document.location = 'Patient_Billing_Pharmacy_Page.php?Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Billing_Type=<?php echo $Temp_Billing_Type2; ?>';
            }
        }        
    }
</script>
<script type="text/javascript">
    function openItemDialog(){
     //Load data to the div
      $("#loader").show();
      $('#getSelectedTests').html('');
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
         var check=confirm("Are you sure you want to remove selected item");
    if(check){     
     $.ajax({
         type: 'POST',
         url: "change_items_info_pharmacy.php",
         data: "Payment_Item_Cache_List_ID="+item,
		 dataType:"json",
         success: function (data) {
		     // alert(data['data']);
              $('#patientItemsList').html(data['data']);
		$('#totalAmount').html(data['total_amount']);	
                //alert(data['data']);
             			  
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
         url: "change_items_info_pharmacy.php",
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
         url: "change_items_info_pharmacy.php",
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
         url: "change_items_info_pharmacy.php",
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

</script>



<script>
    function Pay_Via_Mobile_Function(Payment_Cache_ID){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php $Employee_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetDetails.open('GET','ePayment_Patient_Details_Departmental.php?Section=Pharmacy&Employee_ID='+Employee_ID+'&Registration_ID='+Registration_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObjectGetDetails.send();
    }
</script>


<script type="text/javascript">
    function Confirm_Create_ePayment_Bill(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectConfirm = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectConfirm.overrideMimeType('text/xml');
        }

        myObjectConfirm.onreadystatechange = function (){
            data2933 = myObjectConfirm.responseText;
            if (myObjectConfirm.readyState == 4) {
                var feedback = data2933;
                if(feedback == 'yes'){
                    Create_ePayment_Bill();
                }else if(feedback == 'not'){
                    alert("No Item Found!");
                }else{
                    alert("You are not allowed to create transaction whith zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........
        myObjectConfirm.open('GET','Confirm_ePayment_Patient_Details_Departmental.php?Section=Pharmacy&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObjectConfirm.send();
    }
</script>


<script type="text/javascript">
    function Create_ePayment_Bill(){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if(Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
            alert("Process Fail! You can not prepare a bill with zero amount");
        }else{
            if(Payment_Mode != null && Payment_Mode != ''){
                if(Payment_Mode == 'Bank_Payment'){
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        document.location = 'Departmental_Bank_Payment_Transaction.php?Section=Pharmacy&Registration_ID='+Registration_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID;
                    }
                }else if(Payment_Mode == 'Mobile_Payemnt'){
                    var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                }
            }else{
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>


<script type="text/javascript">
    function Print_Payment_Code(Payment_Code){
        var winClose=popupwindow('paymentcodepreview.php?Payment_Code='+Payment_Code+'&PaymentCodePreview=PaymentCodePreviewThisPage', 'PAYMENT CODE', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
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
        function pharmacyQuantityUpdate(Payment_Item_Cache_List_ID,Quantity) {
	     if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    } 
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','pharmacyQuantityUpdate.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Quantity='+Quantity,true);
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