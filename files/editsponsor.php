<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='editsponsorlist.php?EditSponsor=EditSponsorThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDistricts.php?Region_Name='+Region_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>

<br/><br/>
<?php
    if(isset($_POST['submittedAddNewSponsorForm']) && isset($_GET['Sponsor_ID'])){
	    $Sponsor_ID = $_GET['Sponsor_ID'];
	    $Guarantor_Name = mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);  
	    $Postal_Address = mysqli_real_escape_string($conn,$_POST['Postal_Address']);
	    $region = mysqli_real_escape_string($conn,$_POST['region']);
	    $District = mysqli_real_escape_string($conn,$_POST['District']);
	    $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
	    $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
	    $Membership_Id_Number_Status = mysqli_real_escape_string($conn,$_POST['Membership_Id_Number_Status']);
	    $Claim_Number_Status = mysqli_real_escape_string($conn,$_POST['Claim_Number_Status']);
	    $Required_Document_To_Sign_At_Receiption = mysqli_real_escape_string($conn,$_POST['Required_Document_To_Sign_At_Receiption']);
	    $Benefit_Limit = mysqli_real_escape_string($conn,$_POST['Benefit_Limit']);
        $consult_per_day = mysqli_real_escape_string($conn,$_POST['consult_per_day']);
        $folio_number_status = mysqli_real_escape_string($conn,$_POST['folio_number_status']); //
        $item_update_api = mysqli_real_escape_string($conn,$_POST['item_update_api']);
        $payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);
        $api_item_package = mysqli_real_escape_string($conn,$_POST['api_item_package']);

        $Authorization_No = mysqli_real_escape_string($conn,$_POST['Authorization_No']);
        $Verification = mysqli_real_escape_string($conn,$_POST['Verification']);
            
        if(isset($_POST['Support_Patient_From_Outside'])){
            $Support_Patient_From_Outside = 'yes';
        }else{
            $Support_Patient_From_Outside = 'no';
        }
        if(isset($_POST['sponsor_medicine_drug_duration_control'])){
            $sponsor_medicine_drug_duration_control = 'yes';
        }else{
            $sponsor_medicine_drug_duration_control = 'no';
        }

        if(isset($_POST['active_sponsor'])){
            $active_sponsor = 'active';
        }else{
            $active_sponsor = 'not active';
        }
        
        $auto_item_update_api = '0';
        if(isset($_POST['auto_item_update_api'])){
            $auto_item_update_api = '1';
        }
        
        if(isset($_POST['enab_auto_billing'])){
            $enab_auto_billing = 'yes';
        }else{
            $enab_auto_billing = 'no';
        }
        
        if(isset($_POST['add_sponsor_for_billing'])){
            $add_sponsor_for_billing = 'yes';
        }else{
            $add_sponsor_for_billing = 'no';
        }
    
        if(isset($_POST['Exemption'])){
            $Exemption = 'yes';
        }else{
            $Exemption = 'no';
        }
    
        
        if(isset($_POST['Free_Consultation_Sponsor'])){
            $Free_Consultation_Sponsor = 'yes';
        }else{
            $Free_Consultation_Sponsor = 'no';
        }


        if (isset($_POST['allow_pharmacy_sponsor'])) {
            $allow_pharmacy_sponsor = 'yes';
        } else {
            $allow_pharmacy_sponsor = 'no';
        }

       

	    $sql = "UPDATE tbl_sponsor SET 
			Guarantor_name = '$Guarantor_Name',
                        payment_method='$payment_method',
			postal_address = '$Postal_Address',
			region = '$region',
			district= '$District',
			ward = '$Ward',
			phone_number = '$Phone_Number',
			membership_id_number_status = '$Membership_Id_Number_Status',
			claim_number_status = '$Claim_Number_Status',
			folio_number_status = '$folio_number_status',
			require_document_to_sign_at_receiption = '$Required_Document_To_Sign_At_Receiption',
			benefit_limit = '$Benefit_Limit',
                        consult_per_day = '$consult_per_day',
                        Support_Patient_From_Outside = '$Support_Patient_From_Outside',
                        item_update_api='$item_update_api',
                        auto_item_update_api='$auto_item_update_api',
                        api_item_package='$api_item_package',
                        enab_auto_billing = '$enab_auto_billing',
                        add_sponsor_for_billing='$add_sponsor_for_billing',
                        Exemption = '$Exemption',
                        allow_dispense_control='$sponsor_medicine_drug_duration_control',
                        Free_Consultation_Sponsor = '$Free_Consultation_Sponsor',
                        active_sponsor = '$active_sponsor',
                        allow_pharmacy_sponsor = '$allow_pharmacy_sponsor', Authorization_No='$Authorization_No', Verification='$Verification'
			WHERE Sponsor_ID = '$Sponsor_ID'
			";
	    
	    if(mysqli_query($conn,$sql)){
		echo "<script type='text/javascript'>
			alert('SPONSOR UPDATED SUCCESSFULLY');
			document.location = 'editsponsor.php?Sponsor_ID=$Sponsor_ID&EditEmployee=EditEmployeeThisForm'
			</script>"; 
	    }
	    else {
		echo "<script type='text/javascript'>
			alert('THERE WAS A PROBLEM WHILE UPDATING');
			</script>";
	    }
    }
    
    if(isset($_GET['Sponsor_ID'])){
    	$Sponsor_ID = $_GET['Sponsor_ID'];
    	$Sponsor_qr = "SELECT * FROM tbl_sponsor WHERE Sponsor_ID= '$Sponsor_ID'";
    	$sponsor_result = mysqli_query($conn,$Sponsor_qr);
    	$sponsor_row = mysqli_fetch_assoc($sponsor_result);
    	$Guarantor_Name = $sponsor_row['Guarantor_Name'];
    	$postal_Address = $sponsor_row['Postal_Address'];
    	$Region = $sponsor_row['Region'];
    	$District = $sponsor_row['District'];
    	$Ward = $sponsor_row['Ward'];
    	$Phone_Number = $sponsor_row['Phone_Number'];
    	$Membership_Id_Number_Status = $sponsor_row['Membership_Id_Number_Status'];
    	$Claim_Number_Status = $sponsor_row['Claim_Number_Status'];
    	$Folio_Number_Status = $sponsor_row['Folio_Number_Status'];
    	$Require_Document_To_Sign_At_receiption = $sponsor_row['Require_Document_To_Sign_At_receiption'];
    	$Benefit_Limit = $sponsor_row['Benefit_Limit'];
        $consult_per_day = $sponsor_row['consult_per_day'];
        $Support_Patient_From_Outside = $sponsor_row['Support_Patient_From_Outside'];
        $enab_auto_billing = $sponsor_row['enab_auto_billing'];
        $add_sponsor_for_billing=$sponsor_row['add_sponsor_for_billing'];
        $Exemption = $sponsor_row['Exemption'];
        $Free_Consultation_Sponsor = $sponsor_row['Free_Consultation_Sponsor'];
        $item_update_api = $sponsor_row['item_update_api'];//
        $auto_item_update_api = $sponsor_row['auto_item_update_api'];
        $api_item_package = $sponsor_row['api_item_package'];
         $payment_method = $sponsor_row['payment_method'];
         $sponsor_medicine_drug_duration_control=$sponsor_row['allow_dispense_control'];
         $active_sponsor = $sponsor_row['active_sponsor'];
        $allow_pharmacy_sponsor = $sponsor_row['allow_pharmacy_sponsor'];
        $Authorization_No = $sponsor_row['Authorization_No'];
        $Verification = $sponsor_row['Verification'];
    }else{
    	$Guarantor_Name ='';
    	$postal_Address ='';
    	$Region ='';
    	$District ='';
    	$Ward ='';
    	$Phone_Number ='';
    	$Membership_Id_Number_Status ='';
    	$Claim_Number_Status ='';
    	$Folio_Number_Status ='';
    	$Require_Document_To_Sign_At_receiption ='';
    	$Benefit_Limit ='';
        $Support_Patient_From_Outside = '';
        $consult_per_day='';
        $enab_auto_billing='';
        $add_sponsor_for_billing='';
        $Exemption = '';
        $Free_Consultation_Sponsor = '';
        $item_update_api = '';
        $auto_item_update_api = '';
        $api_item_package = '';
        $payment_method = '';
        $sponsor_medicine_drug_duration_control='';
        $active_sponsor='';
        $allow_pharmacy_sponsor = '';
        $Authorization_No ='';
        $Verification='';
    }
?>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>EDIT SPONSOR</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Guarantor Name</b></td>
                                    <td width=60%><input type='text' name='Guarantor_Name' required='required' value='<?php echo $Guarantor_Name; ?>' size=70 id='Guarantor_Name' placeholder='Enter Guarantor Name'></td>
                                </tr>
                                <tr>
                            <td style='text-align: right;'>
                                <b>Payment Method</b>
                            </td>
                            <td>
                                <select name='payment_method' id='payment_method' required>
                                    <option value=""></option>
                                    <option value="cash" <?=($payment_method=='cash')?'selected':''?>>Cash</option>
                                    <option value="credit" <?=($payment_method=='credit')?'selected':''?>>Credit</option>  
                                </select>
                            </td>
                        </tr>
                                <tr>
                                    <td width=40% style='text-align: right;'><b>Postal Address</b></td>
                                    <td width=60%><textarea name='Postal_Address' id='Postal_Address' cols=20 rows=1 placeholder='Enter Postal Address'><?php echo $postal_Address; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Region</b></td>
                                    <td>
                                        <select name='region' id='region' onchange='getDistricts(this.value)'>
                                        <option selected='selected' value='<?php echo $Region; ?>'><?php echo $Region; ?></option>
					<?php
					    $data = mysqli_query($conn,"select * from tbl_regions");
					        while($row = mysqli_fetch_array($data)){
					    ?>
					    <option value='<?php echo $row['Region_Name'];?>'>
						<?php echo $row['Region_Name']; ?>
					    </option>
					<?php
					    }
					?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'>
                                        <b>District</b>
                                    </td>
                                    <td>
                                        <select name='District' id='District'>
                                        <option selected='selected'><?php echo $District; ?></option>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Ward</b></td>
                                    <td>
					<input type='text' name='Ward' id='Ward' value='<?php echo $Ward; ?>'> 
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Phone Number</b></td>
                                    <td>
                                        <input type='text' name='Phone_Number' value='<?php echo $Phone_Number; ?>' id='Phone_Number' placeholder='Enter Phone Number'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Membership ID Number Status</b></td>
                                    <td>
                                        <select name='Membership_Id_Number_Status' id='Membership_Id_Number_Status'>
					                        <option><?php echo $Membership_Id_Number_Status; ?></option>
                                            <option><?php if($Membership_Id_Number_Status=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Claim Form Number Status</b></td>
                                    <td>
                                        <select name='Claim_Number_Status' id='Claim_Number_Status'>
                                            <option selected='selected'><?php echo $Claim_Number_Status; ?></option>
                                            <option><?php if($Claim_Number_Status=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Approval Level</b></td>
                                    <td>
                                        <select name='Required_Document_To_Sign_At_Receiption' id='Required_Document_To_Sign_At_Receiption'>
                                            <option><?php echo $Require_Document_To_Sign_At_receiption; ?></option>
					    <option><?php if($Require_Document_To_Sign_At_receiption=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Folio Number Status</b></td>
                                    <td>
                                        <select name='folio_number_status' id='folio_number_status'>
					    <option><?php echo $Folio_Number_Status; ?></option>
                                            <option><?php if($Folio_Number_Status=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Verification And Authorization</b></td>
                                    <td>
                                        <select name='Verification' id='Verification'>
					                        <option><?php echo $Verification; ?></option>
                                            <option><?php if($Verification=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Authorization Number</b></td>
                                    <td>
                                        <select name='Authorization_No' id='Authorization_No'>
					                        <option><?php echo $Authorization_No; ?></option>
                                            <option><?php if($Authorization_No=='Not Mandatory'){ echo 'Mandatory'; }else{ echo 'Not Mandatory'; }?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Benefit Limit</b></td>
                                    <td>
                                        <input type='text' name='Benefit_Limit' id='Benefit_Limit' value='<?php echo $Benefit_Limit; ?>' placeholder='Enter Benefit Limit'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Consultation Per Day</b></td>
                                    <td>
                                        <input type='text' name='consult_per_day' id='consult_per_day' autocomplete='off' value='<?php echo $consult_per_day; ?>' placeholder='Enter Number Of Consultation Per Day'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="Support_Patient_From_Outside" id="Support_Patient_From_Outside" <?php if(strtolower($Support_Patient_From_Outside) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="Support_Patient_From_Outside"><b>Support Patients From Outside</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>Item Update API</b></td>
                                    <td>
                                        <textarea name='item_update_api' id='item_update_api' autocomplete='off' placeholder='Enter Item Update API'><?=$item_update_api; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="auto_item_update_api" id="auto_item_update_api" <?php if(strtolower($auto_item_update_api) =='1'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="auto_item_update_api"><b>Auto Item Update Using API</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><b>API Item Package</b></td>
                                    <td>
                                        <input type='text' name='api_item_package' id='api_item_package' autocomplete='off' value='<?php echo $api_item_package; ?>' placeholder='Enter API Item Update'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="enab_auto_billing" id="enab_auto_billing" <?php if(strtolower($enab_auto_billing) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="enab_auto_billing"><b>Enable Auto Billing</b></label>
                                    </td>
                                </tr>
                                   <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="add_sponsor_for_billing" id="add_sponsor_for_billing" <?php if(strtolower($add_sponsor_for_billing) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="add_sponsor_for_billing"><b>Select Sponsor For Mortuary Billing</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="Exemption" id="Exemption" <?php if(strtolower($Exemption) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="Exemption"><b>Cost Sharing Sponsor</b></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="Free_Consultation_Sponsor" id="Free_Consultation_Sponsor" <?php if(strtolower($Free_Consultation_Sponsor) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="Free_Consultation_Sponsor"><b>Accept Items Apperance in Advance when check-in patient </b></label>
                                    </td>
                                </tr>
                                <tr>
                                   <td style="text-align: right;" <?=($auto_item_update_api == 1 ? "" : "style='display:none'")?> >
                                        <input type="checkbox" name="sponsor_medicine_drug_duration_control" id="sponsor_medicine_drug_duration_control" <?php if(strtolower($sponsor_medicine_drug_duration_control) =='yes'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="sponsor_medicine_drug_duration_control"><b>Allow pharmacist to approve</b></label>
                                    </td> 
                                </tr>
                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="active_sponsor" id="active_sponsor" <?php if(strtolower($active_sponsor) =='active'){ echo "checked='checked'"; }?>>
                                    </td>
                                    <td>
                                        <label for="active_sponsor"><b> 
                                            <?php
                                            if(strtolower($active_sponsor) =='active'){ // ehms2gpitg2014 2021Massana2016 admingpitg@della
                                                echo "Dectivate This Sponsor"; 
                                            }else if(strtolower($active_sponsor) =='not active'){ 
                                                 echo "Activate This Sponsor"; 
                                            }?>
                                         </b>
                                        </label>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align: right;">
                                        <input type="checkbox" name="allow_pharmacy_sponsor" id="allow_pharmacy_sponsor" <?php if (strtolower($allow_pharmacy_sponsor) == 'yes') {
                                                                                                                                echo "checked='checked'";
                                                                                                                            } ?>>
                                    </td>
                                    <td>
                                        <label for="allow_pharmacy_sponsor"><b>Allow pharmacist to add Item</b></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewSponsorForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>
