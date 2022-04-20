<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District').innerHTML = data;
    }

//    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>
<a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage' class='art-button-green'>BACK</a>
<br/><br/>

<?php
if (isset($_POST['submittedAddNewSponsorForm'])) {
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
    $folio_number_status = mysqli_real_escape_string($conn,$_POST['folio_number_status']);
    $account_code = mysqli_real_escape_string($conn,$_POST['account_code']);
    $item_update_api = mysqli_real_escape_string($conn,$_POST['item_update_api']);
    $payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);
    $Authorization_No = mysqli_real_escape_string($conn, $_POST['Authorization_No']);

    $Verification = mysqli_real_escape_string($conn, $_POST['Verification']);
    $api_item_package = mysqli_real_escape_string($conn, $_POST['api_item_package']);


    if (isset($_POST['Support_Patient_From_Outside'])) {
        $Support_Patient_From_Outside = 'yes';
    } else {
        $Support_Patient_From_Outside = 'no';
    }
    
     $auto_item_update_api = '0';
    if (isset($_POST['auto_item_update_api'])) {
        $auto_item_update_api = '1';
    }

    if (isset($_POST['enab_auto_billing'])) {
        $enab_auto_billing = 'yes';
    } else {
        $enab_auto_billing = 'no';
    }
    if (isset($_POST['add_sponsor_for_billing'])) {
        $add_sponsor_for_billing = 'yes';
    } else {
        $add_sponsor_for_billing = 'no';
    }

    if (isset($_POST['Exemption'])) {
        $Exemption = 'yes';
    } else {
        $Exemption = 'no';
    }

    if (isset($_POST['Free_Consultation_Sponsor'])) {
        $Free_Consultation_Sponsor = 'yes';
    } else {
        $Free_Consultation_Sponsor = 'no';
    }

    $sql = "insert into tbl_sponsor(
                            Guarantor_name,payment_method,postal_address,region,
                            district,ward,phone_number,
                            membership_id_number_status,claim_number_status,
                            folio_number_status,require_document_to_sign_at_receiption,
                            benefit_limit,consult_per_day,Support_Patient_From_Outside,enab_auto_billing,add_sponsor_for_billing,Exemption,Free_Consultation_Sponsor,item_update_api,auto_item_update_api, Verification, Authorization_No, api_item_package)

                            values('$Guarantor_Name','$payment_method','$Postal_Address','$region',
                                        '$District','$Ward','$Phone_Number',
                                            '$Membership_Id_Number_Status','$Claim_Number_Status',
                                                '$folio_number_status','$Required_Document_To_Sign_At_Receiption',
                                                '$Benefit_Limit','$consult_per_day','$Support_Patient_From_Outside','$enab_auto_billing','$add_sponsor_for_billing','$Exemption','$Free_Consultation_Sponsor','$item_update_api','$auto_item_update_api', '$Verification', '$Authorization_No', '$api_item_package')";
 
    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysqli_errno($conn) . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('SPONSOR NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        } else {
            die(mysqli_error($conn));
        }
    } else {
        if (isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting']) == 'yes') {
            //ADDING LEDGERS TO GACCOUNT
            $data = array(
                'Ledger_Name' => $Guarantor_Name,
                'account_code' => $account_code
            );
            $endata = json_encode($data);
            $opts = array('http' =>
                array(
                    'method' => 'GET',
                    'header' => 'Content-type: application/json',
                    'content' => $endata
                )
            );
            $context = stream_context_create($opts);
            $acc = file_get_contents("http:/127.0.0.1/Final_One/gaccounting/Api/ledgerOnSponsorsFromEhms", false, $context);
            echo "<script>alert('" . $acc . "')</script>";
            $_SESSION['Sponsor_Name'] = $Guarantor_Name;
            echo "<script type='text/javascript'>			  
			    document.location = 'sponsorconfiguration.php?SponsorConfig=SponsorConfigThisPage';
			    </script>";
        }
    }
}
?>
<?php
if (isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting']) == 'yes') {
    $acc1 = file_get_contents("http://localhost/Final_One/gaccounting/Api/accounts");
    $acc2 = json_decode($acc1);
//echo "<pre/>";
//print_r($acc2);
//exit();
}
?>

<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW SPONSOR</b></legend>
                <table>
                    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

                        <tr>
                            <td width=40% style='text-align: right;'><b>Guarantor Name</b></td>
                            <td width=60%><input type='text' name='Guarantor_Name' required='required' size=70 id='Guarantor_Name' placeholder='Enter Guarantor Name' autocomplete='off'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'>
                                <b>Payment Method</b>
                            </td>
                            <td>
                                <select name='payment_method' id='payment_method' required>
                                    <option value=""></option>
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>  
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width=40% style='text-align: right;'><b>Postal Address</b></td>
                            <td width=60%><textarea name='Postal_Address' id='Postal_Address' cols=20 rows=1 placeholder='Enter Postal Address' autocomplete='off'></textarea></td>
                        </tr>
                        <tr>
                            <?php if (isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting']) == 'yes') { ?>
                            <tr>
                                <td width=40% style='text-align: right;'><b>Account</b></td>
                                <td width=60%>
                                    <select required="required" name='account_code' width=20% id='account_code'  >                              
                                        <option selected="selected" disabled="disabled" value="">--Select account--</option>
                                        <?php foreach ($acc2 as $acc) { ?>
                                            <option value="<?php echo $acc->acc_code; ?>"><?php echo $acc->acc_name; ?></option>
                                        <?php } ?>
                                    </select></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style='text-align: right;'><b>Region</b></td>
                            <td>
                                <select name='region' id='region' onchange='getDistricts(this.value)'>
                                    <option selected='selected' value='Dar es salaam'>Dar es salaam</option>
                                    <?php
                                    $data = mysqli_query($conn,"select * from tbl_regions");
                                    while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                        <option value='<?php echo $row['Region_Name']; ?>'>
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
                                    <option selected='selected'>Kinondoni</option>
                                    <option>Ilala</option>
                                    <option>Temeke</option>  
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Ward</b></td>
                            <td>
                                <input type='text' name='Ward' id='Ward' autocomplete='off'> 
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Phone Number</b></td>
                            <td>
                                <input type='text' name='Phone_Number' id='Phone_Number' autocomplete='off' placeholder='Enter Phone Number'>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Membership ID Number Status</b></td>
                            <td>
                                <select name='Membership_Id_Number_Status' id='Membership_Id_Number_Status' required='required'>
                                    <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Claim Form Number Status</b></td>
                            <td>
                                <select name='Claim_Number_Status' id='Claim_Number_Status' required='required'>
                                    <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Approval Level</b></td>
                            <td>
                                <select name='Required_Document_To_Sign_At_Receiption' id='Required_Document_To_Sign_At_Receiption' required='required'>
                                    <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Folio Number Status</b></td>
                            <td>
                                <select name='folio_number_status' id='folio_number_status' required='required'>
                                    <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Verification And Authorization</b></td>
                            <td>
                                <select name='Verification' id='Verification'>
                                <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Authorization Number</b></td>
                            <td>
                                <select name='Authorization_No' id='Authorization_No'>
                                <option></option>
                                    <option>Mandatory</option>
                                    <option>Not Mandatory</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Benefit Limit</b></td>
                            <td>
                                <input type='text' name='Benefit_Limit' id='Benefit_Limit' autocomplete='off' placeholder='Enter Benefit Limit'>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Consultation Per Day</b></td>
                            <td>
                                <input type='text' name='consult_per_day' id='consult_per_day' autocomplete='off' placeholder='Enter Number Of Consultation Per Day'>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                <input type="checkbox" name="Support_Patient_From_Outside" id="Support_Patient_From_Outside">
                            </td>
                            <td>
                                <label for="Support_Patient_From_Outside"><b>Support Patients From Outside</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Item Update API</b></td>
                            <td>
                                <textarea name='item_update_api' id='item_update_api' autocomplete='off' placeholder='Enter Item Update API'></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                <input type="checkbox" name="auto_item_update_api" id="auto_item_update_api">
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
                                <input type="checkbox" name="add_sponsor_for_billing" id="add_sponsor_for_billing">
                            </td>
                            <td>
                                <label for="add_sponsor_for_billing"><b>Select Sponsor For Mortuary  Billing</b></label>
                            </td>
                        </tr> 
                        <tr>
                            <td style="text-align: right;">
                                <input type="checkbox" name="enab_auto_billing" id="enab_auto_billing">
                            </td>
                            <td>
                                <label for="enab_auto_billing"><b>Enable Auto Billing</b></label>
                            </td>
                        </tr> 
                 
                        <tr>
                            <td style="text-align: right;">
                                <input type="checkbox" name="Exemption" id="Exemption">
                            </td>
                            <td>
                                <label for="Exemption"><b>Cost Sharing Sponsor</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                <input type="checkbox" name="Free_Consultation_Sponsor" id="Free_Consultation_Sponsor">
                            </td>
                            <td>
                                <label for="Free_Consultation_Sponsor"><b>Accept Items Apperance in Advance when check-in patient</b></label>
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
