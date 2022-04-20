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
?>


<script src="js/mobilepayment.js"></script>

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
<a href="patientbilling.php?patientbilling=ThispagePatientBill" class='art-button-green'>IPD/OPD PAYMENTS</a>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='patientbillingMobile.php?PatientBilling=PatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 


<br/><br/><br/>
<fieldset>  
    <legend align=center><b>PROCESSED MOBILE PAYMENTS</b></legend>
    <table width='100%'>
        <tr>
            <td>
                <fieldset style='height:600px'>
                    <fieldset>
                        <table width=100%>
                            <tr>
                                <td colspan="4" >
                                    <input type="text" id='searchName' placeholder='Search Patient' onkeyup="searchPatientListMobile()"><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" >
                                    <input type="text" id='searchPaymentCode' placeholder='Search Payment Code' onkeyup="searchPatientListMobile()">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset style="overflow-y:scroll;height:490px">
                        <table width="100%" id='item_search'>
                            <tr>
                                <th width="10%">Code</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                            <tbody id='MobilePatientList'>
                                <?php
                                    $patientQr = "SELECT * FROM tbl_patient_registration pr, tbl_mobile_payment mp 
                                                  WHERE pr.Registration_ID = mp.Registration_ID 
                                                  AND mp.payment_status <> 'seen' GROUP BY payment_code";
                                    $data = mysqli_query($conn,$patientQr);
                                    while($row = mysqli_fetch_array($data)){
                                    ?><tr><td width="10%"><?php echo $row['payment_code'];?></td>
                                          <td><?php echo $row['Patient_Name']; ?></td>
                                          <td><?php echo $row['payment_status']; ?></td>
                                          <td>
                                              <?php
                                                if($row['payment_status'] == 'sent' ){
                                                    ?>
                                                        <center><input type="button" value="check online status" onclick="updatePaymentStatus('<?php echo $row['payment_code'];?>')" /><input type="button" value="CancelOnline" onclick="CancelOnline('<?php echo $row['payment_code']; ?>')"></center>
                                                    <?php
                                                }elseif($row['payment_status'] == 'pending'){
                                                    ?>
                                                        <center><input type="button" value="cancel" onclick="cancelPayment('<?php echo $row['payment_code'];?>')" /><input type="button" value="send" onclick="sendToCloud('<?php echo $row['payment_code'];?>')" /></center>
                                                    <?php
                                                }elseif($row['payment_status'] == 'paid'){
                                                    ?>
                                                        <center><input type="button" value="seen" onclick="seen('<?php echo $row['payment_code'];?>')" /></center>
                                                    <?php
                                                }
                                              ?>
                                          </td>
                                          <td><input type="radio" name='choose' onclick="getPatientAndMobileItems('<?php echo $row['payment_code']; ?>','<?php echo $row['Patient_Name']; ?>')"></td>
                                      </tr><?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>    
            </td>

            <td style="width:50%">
                <fieldset style='height:600px'>
                    <fieldset>
                        <table width=100%>
                            <tr>
                                <td colspan="4" >
                                    <input type="text" id='Name' readonly="readonly" placeholder='Patient' onkeyup="itemSearch(this.value)"><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" >
                                    <input type="text" id='PaymentCode' readonly="readonly" placeholder='Payment Code' onkeyup="itemSearch(this.value)">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset style="overflow-y:scroll;height:490px">
                        <table width="100%" id='item_search'>
                            <tr>
                                <th width="5%">SN</th>
                                <th>Item</th>
                                <th width="10%">Discount</th>
                                <th width="10%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Amount</th>
                            </tr>
                            <tbody id='patientItems'>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>
            </td>
        </tr>
    </table>

</fieldset><br/>
<?php
    include("./includes/footer.php");
?>