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
		    header("Location: ./supervisorauthenticationMobile.php?InvalidSupervisorAuthentication=yes");
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
<style type='text/css'>

    input[type=button]{
        font-size: 20px;
    }

</style>

<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Checked In - Outpatient List'){
	    document.location = "searchCheckedInMobile.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
	}else if (patientlist=='Checked In - Inpatient List') {
	    document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Outpatient') {
	    document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='AdHOC Payments - Outpatient') {
	    document.location = "continueoutpatientbillingMobile.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
	}else if (patientlist=='Patient from outside') {
	    document.location = "tempregisterpatient.php?RegistrationNewPatient=RegistrationNewPatientThisPage";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Chagua Orodha Ya Wagonjwa</option>
    <option>
	Checked In - Outpatient List
    </option>
    <option>
	Checked In - Inpatient List
    </option>
    <option>
	AdHOC Payments - Outpatient
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label>
<a href="patientbillingMobileProcessing.php?Mobilepayment=ThisPageMobilePayment" class='art-button-green'>List Of Patients - Mobile Payments</a>
<a href="patientbilling.php?patientbilling=ThispagePatientBill" class='art-button-green'>IPD/OPD PAYMENTS</a>
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

<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID

                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
        }
?>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>

<script src="js/mobilepayment.js"></script>
<script src="js/functions.js"></script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b>REVENUE(MOBILE) CENTER</b></legend>
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%'>Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%'>Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%'>Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td>Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number'></td>
                            </tr> 
                            <tr>
                    <td>Billing Type</td>
				    <td>
					<select name='Billing_Type' id='Billing_Type'  onchange='getPrice()' required='required'>
					    <option>Outpatient Cash</option>
                        <option>Inpatient Cash</option>
                    </select>
                </td>
				
                <td>Claim Form Number</td> 
    				<?php
                        $Claim_Number_Status='';
    				    $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
    				    while($row = mysqli_fetch_array($select_claim_status)){
    					   $Claim_Number_Status = $row['Claim_Number_Status'];
    				    }
                    ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' <?php if(strtolower($Claim_Number_Status) == 'mandatory'){ ?> required='required' <?php } ?> ></td>
                    <td>Occupation</td>
                    <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				
				<td>Receipt Date & Time</td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden'>
				</td>
                </tr>
                <tr>
                    <td>Type Of Check In</td>
                    <td>  
    				<select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'>
    				    <option selected="selected"></option>
                        <option>Radiology</option>
    				    <option>Dialysis</option>
    				    <option>Physiotherapy</option>
    				    <option>Optical</option> 
    				    <option>Doctor Room</option>
    				    <option>Dressing</option>
    				    <option>Matenity</option>
    				    <option>Cecap</option>
    				    <option>Laboratory</option>
    				    <option>Pharmacy</option>
    				    <option>Theater</option>
    				    <option>Dental</option>
    				    <option>Ear</option>
    				</select>
    				</td>
                <td>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                <td>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
				<td>Payment Code</td>
                    <td><input type='text' name='payment_code' id='payment_code' readonly='readonly' ></td>
                </tr>
                <tr>
                    <td>Patient Direction</td>
                    <td>
                        <select id='direction' name='direction' onclick='getDoctor()' required='required'>
                            <option></option>
                            <option>Direct To Doctor</option>
                            <option>Direct To Doctor Via Nurse Station</option>
                            <option>Direct To Clinic</option>
                            <option>Direct To Clinic Via Nurse Station</option>
                        </select>
                    </td>
                    <td>Sponsor Name</td>
                    <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                    <td>Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				<td>Prepared By</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>
                    <td>Consultant</td>
                    <td>
				        <select name='Consultant_ID' id='Consultant_ID' required='required'>
                            <option></option>
        					<?php
        					$Select_Consultant = "select * from tbl_clinic"; 
        					$result = mysqli_query($conn,$Select_Consultant);
        					?> 
        					<?php
        					while($row = mysqli_fetch_array($result)){
        					    ?>
        					    <option value='<?php echo $row['Clinic_ID']; ?>'><?php echo $row['Clinic_Name']; ?></option>
        					<?php
        					}
        					
        					$Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor'"; 
        					$result = mysqli_query($conn,$Select_Doctors);
        					?> 
        					<?php
        					while($row = mysqli_fetch_array($result)){
        					    ?>
        					    <option value='<?php echo $row['Employee_ID']; ?>'><?php echo $row['Employee_Name']; ?></option>
        					<?php
        					}
    					
        					?>
    				    </select>
				    </td>
                    <td>Registration Number</td>
                    <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                    <td>Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
				<td>Supervised By</td>
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

<!-- Items For Selection -->
<fieldset style='display: inline;width:48%;height:400px'>
    <fieldset>
        <table width=100%>
            <tr>
                <td colspan="4" >
                    <input type="text" id='searchName' placeholder='Search Item' onkeyup="itemSearch(this.value)"><br><br>
                </td>
            </tr>
            <tr>
                <td>Type:</td>
                <td>
                    <select id='Item_Type' onchange='itemSearch()'>
                        <option selected="selected"></option> 
                        <option>Service</option>
                        <option>Pharmacy</option>
                    </select>
                </td>
                <td>Category:</td>
                <td>
                    <select id='Item_Category_ID' onchange='itemSearch()'>
                        <option selected="selected"></option>
                        <?php
                            $data = mysqli_query($conn,"select * from tbl_item_category ");
                            while($row = mysqli_fetch_array($data)){
                                ?><option value='<?php echo $row['Item_Category_ID']; ?>' ><?php echo $row['Item_Category_Name']; ?></option><?php
                            }
                        ?> 
                    </select>
                    <input type='button' value='Direct Cash' id='directCash' onclick="toglePaymentMode(this)">
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="overflow-y:scroll;height:290px">
        <table width="100%" id='item_search'>
            <tr>
                <th width="10%">SN</th>
                <th>Item</th>
            </tr>
                <?php
                    $data = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Visible_Status <> 'Others'");
                    while($row = mysqli_fetch_array($data)){
                    ?><tr><td width="10%"><?php echo $row['Item_ID'];?></td><td><?php echo $row['Product_Name']; ?></td><td><input type="radio" name='choose' class='choose' id='choose' onclick="selectItem('<?php echo $row['Item_ID'];?>','<?php echo $row['Product_Name']; ?>')"></td></tr><?php
                    }
                ?>
        </table>
    </fieldset>
</fieldset>

<!-- Selected Items -->
<fieldset style='display: inline;float:right;width:48%;height:400px'>
    <fieldset>
        <table width=100%>
        <tr>
            <td>Item:</td>
            <td colspan="5" ><input type="text" readonly="readonly" id='item_name'></td>
        </tr>
        <tr>
            <td>Discount</td>
            <td>Price</td>
            <td>Balance</td>
            <td>Qty</td>
            <td>Amount</td>
            <script>
                function printReceipt(){
                    //code
                    //print receipt on the next tab
                    window.open("individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&IndividualPaymentReport=IndividualPaymentReportThisPage","_blank");
                    //reload page 
                    window.location = "patientbillingMobile.php?UpdatePayments=UpdatePaymentsThisPage&Redirect=VitualRedirect&Action=Major";
                    //document.getElementById("myForm").reset();
                }
            </script>
            <?php
            if(@$Patient_Payment_ID != 0){          
                ?><td>
                <!--PRINTING RECEIPT-->
            
                <!--END OF PRINT RECEIPT-->
                <input type='button' id='print_receipt' name='print_receipt' value='RECEIPT' class='art-button-green' onclick='printReceipt();'>
                <?php
                }else{
                    echo '<td>&nbsp;</td>';
                }
                ?>
        </tr>
        <tr>
            <td>
                <input type='text' name='Discount' id='Discount' placeholder='Discount' onchange='numberOnly(this);getPrice()' onkeyup='numberOnly(this);getPrice()' onkeypress="numberOnly(this);" value=0>
            </td>
            <td>
                <input type='text' name='Price' id='Price' placeholder='Price' readonly="readonly"   onchange='numberOnly(this,"no")' onkeyup='numberOnly(this,"no")' onkeypress="numberOnly(this,'no');">
            </td>
            <td>
                <input type='text' name='Balance' id='Balance' placeholder='Balance' value=1 disabled='disabled'>
            </td>
            <td>
                <input type='text' name='Quantity' id='Quantity' required='required' placeholder='Quantity' onchange="numberOnly(this,'no');getPrice()" onkeyup="numberOnly(this,'no');getPrice()" onkeypress="numberOnly(this,'no')" >
            </td>
            <td>
                <input type='text' name='Amount' id='Amount' placeholder='Amount' disabled='disabled'>
            </td>
            <td style='text-align: center;'>
                <input type='button' name='submit' id='submit' <?php
            if($Registration_ID!=''){ 
                $select_pending_codes = "SELECT * FROM tbl_mobile_payment 
                                WHERE Registration_ID = $Registration_ID AND (payment_status='pending' OR payment_status='sent') GROUP BY payment_code";
                                $pending_result = mysqli_query($conn,$select_pending_codes) or die(mysqli_error($conn));
                if(mysqli_num_rows($pending_result)>0){
                    ?>disabled='disabled'<?php
                }
            }else{
                ?>disabled='disabled'<?php
            }
            ?> onclick="add('<?php echo $Registration_ID; ?>'<?php if(isset($_GET['visit_status'])){ echo ",'".$_GET['visit_status']."'"; } ?>)" value='ADD'>
            </td>
        </tr>
        </table>
    </fieldset>
    <fieldset style="overflow-y:scroll;height:240px">
        <table width="100%">
            <tr>
                <th>Item</th>
                <th width="10%">discount</th>
                <th width="10%">price</th>
                <th width="10%">quantity</th>
                <th width="10%">ammount</th><th></th>
            </tr>
            <tbody id='itemlist'>
            <?php
            if($Registration_ID!=''){ 
                $select_pending_codes = "SELECT * FROM tbl_mobile_payment 
                                WHERE Registration_ID = $Registration_ID AND payment_status='pending' GROUP BY payment_code";
                                $pending_result = mysqli_query($conn,$select_pending_codes) or die(mysqli_error($conn));
                if(mysqli_num_rows($pending_result)>0){
                ?>
                <tr>
                    <td colspan="5"><center><h4>The Folowing Payment Code(s) Were Left Pending</h4></center>
                    </td>
                </tr>
                <?php
                }
                while($pending_row = mysqli_fetch_assoc($pending_result)){
                    ?>
                    <tr><td><?php echo $pending_row['payment_code']; ?></td><td><?php echo $pending_row['payment_date_and_time']; ?></td><td><input type="button" value="Continue" onclick="loadCode('<?php echo $pending_row['payment_code']; ?>')"></td><td><input type="button" value="Send" onclick="sendToCloud('<?php echo $pending_row['payment_code']; ?>')"></td><td><input type="button" value="Cancel" onclick="cancelPayment('<?php echo $pending_row['payment_code']; ?>')"></td><td></td></tr>
                    <?php
                }
            }
            if($Registration_ID!=''){ 
                $select_pending_codes = "SELECT * FROM tbl_mobile_payment 
                                WHERE Registration_ID = $Registration_ID AND payment_status='sent' GROUP BY payment_code";
                                $pending_result = mysqli_query($conn,$select_pending_codes) or die(mysqli_error($conn));
                if(mysqli_num_rows($pending_result)>0){
                ?>
                <tr>
                    <td colspan="5"><center><h4>The Folowing Payment Code(s) Are Not Paid</h4></center>
                    </td>
                </tr>
                <?php
                }
                while($pending_row = mysqli_fetch_assoc($pending_result)){
                    ?>
                    <tr><td><?php echo $pending_row['payment_code']; ?></td><td><?php echo $pending_row['payment_date_and_time']; ?></td><td><input type="button" value="check online status" onclick="updatePaymentStatus('<?php echo $row['payment_code'];?>')" ></td><td></td><td><input type="button" value="CancelOnline" onclick="CancelOnline('<?php echo $pending_row['payment_code']; ?>')"></td><td></td></tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </fieldset>
    <input type='button' id='btnSend' value="send" disabled="disabled" onclick="sendToCloud()"><input type='button' id='btnCancel' disabled="disabled" value="cancel" onclick="cancelPayment()">
</fieldset>
<?php
    include("./includes/footer.php");
?>