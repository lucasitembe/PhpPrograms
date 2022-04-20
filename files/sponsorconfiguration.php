<?php
//error_reporting(0);
    include("./includes/connection.php");
    include("./includes/header.php");
    include '../account/includes/LanguagesArray.php';
    $InputError = 0;
    include '../account/includes/CountriesArray.php';
    
    
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
    
    if(isset($_SESSION['Sponsor_Name'])){
       $Guarantor_Name=$_SESSION['Sponsor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    //current date
    $select = mysqli_query($conn,"select now() as Today") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select)){
	$Today = $row['Today'];
    }
    
    $select = mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
	while($data = mysqli_fetch_array($select)){
	    $Sponsor_ID = $data['Sponsor_ID'];
	    $Postal_Address = $data['Postal_Address'];
	    $Region = $data['Region'];
	    $District = $data['District'];
	    $Ward = $data['Ward'];
	}
    }
?>


<br/><br/>

<?php
	if (isset($_POST['submit'])) {
            include("./includes/connectionWeberp.php");
	

	    $sql = "INSERT INTO debtorsmaster (
							debtorno,
							name,
							address1,
							address2,
							address3,
							address4,
							address5,
							address6,
							currcode,
							clientsince,
							holdreason,
							paymentterms,
							discount,
							discountcode,
							pymtdiscount,
							creditlimit,
							salestype,
							invaddrbranch,
							taxref,
							customerpoline,
							typeid,
							language_id)
				VALUES ('" . $_POST['DebtorNo'] ."',
						'" . $_POST['CustName'] ."',
						'" . $_POST['Address1'] ."',
						'" . $_POST['Address2'] ."',
						'" . $_POST['Address3'] . "',
						'" . $_POST['Address4'] . "',
						'" . $_POST['Address5'] . "',
						'" . $_POST['Address6'] . "',
						'" . $_POST['CurrCode'] . "',
						'" . $Today . "',
						'" . $_POST['HoldReason'] . "',
						'" . $_POST['PaymentTerms'] . "',
						'" . $_POST['Discount']/100 . "',
						'" . $_POST['DiscountCode'] . "',
						'" . $_POST['PymtDiscount']/100 . "',
						'" . $_POST['CreditLimit'] . "',
						'" . $_POST['SalesType'] . "',
						'" . $_POST['AddrInvBranch'] . "',
						'" . $_POST['TaxRef'] . "',
						'" . $_POST['CustomerPOLine'] . "',
						'" . $_POST['typeid'] . "',
						'" . $_POST['LanguageID'] . "')";

			  //die($sql);
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        
			include("./includes/connection.php");
                        mysqli_query($conn,"UPDATE tbl_sponsor SET HasAccountacy=1 WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));

			//$BranchCode = mb_substr($_POST['DebtorNo'],0,4);
                        
                        echo " <script type='text/javascript'>
                                  alert('SPONSOR ADDED SUCCESSFUL');
                                </script>";
                        
                         unset($_SESSION['Sponsor_Name']);
                        
			header('Location:editsponsorlist.php?EditSponsor=EditSponsorThisForm');
		
	} 


        
?>


<center>
       <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW SPONSOR</b></legend>
                        <form action='<?php echo $_SERVER['PHP_SELF']?>' method='post' name='myForm' >
                           
                            <table class="selection" cellspacing="4">
                              <tr>
				<td valign="top">
                                    <table class="selection">
                                        <tr>
                                            <td style='text-align: right;'>Customer Code</td>
                                            <td><input type="text" data-type="no-illegal-chars" tabindex="1"  name="DebtorNo"  required="required"  value="<?php echo $Sponsor_ID; ?>" readonly='readonly' size="11" maxlength="10" /></td></tr>
                                        <tr>
                                            <td style='text-align: right;'>Customer Name</td>
                                            <td><input tabindex="2" type="text" name="CustName" required="required" size="42" maxlength="40" value="<?php echo $Guarantor_Name; ?>" readonly='readonly'/></td>
                                        </tr>
                                        <tr>
                                                <td style='text-align: right;'>Address Line 1 (Street)</td>
                                                <td><input tabindex="3" type="text" name="Address1" required="required" size="42" maxlength="40"  value="<?php echo $Ward; ?>" readonly='readonly'/></td>
                                        </tr>
                                        <tr>
                                                <td style='text-align: right;'>Address Line 2 (Street)</td>
                                                <td><input tabindex="4" type="text" name="Address2" size="42" maxlength="40"/></td>
                                        </tr>
                                        <tr>
                                                <td style='text-align: right;'>Address Line 3 (Suburb/City)</td>
                                                <td><input tabindex="5" type="text" name="Address3" size="42" maxlength="40" value="<?php echo $District; ?>" readonly='readonly'/></td>
                                        </tr>
                                        <tr>
                                                <td style='text-align: right;'>Address Line 4 (State/Province)</td>
                                                <td><input tabindex="6" type="text" name="Address4" size="42" maxlength="40" value="<?php echo $Region; ?>" readonly='readonly'/></td>
                                        </tr>
                                        <tr>
                                                <td style='text-align: right;'>Address Line 5 (Postal Code)</td>
                                                <td><input tabindex="7" type="text" name="Address5" size="22" maxlength="20" value="<?php echo $Postal_Address; ?>" readonly='readonly'/></td>
                                        </tr>
                                         <tr>
                                                    <td style='text-align: right;'>Country</td>
                                                    <td><select name="Address6">
                                        <?php
					    echo '<option selected="selected" value="Tanzania, United Rep. of">Tanzania, United Rep. of</option>';
					    foreach ($CountriesArray as $CountryEntry => $CountryName){
						echo '<option value="' . $CountryName . '">' . $CountryName  . '</option>';
					    }
                                        
                                        ?>
                                                    </select></td>
                                      </tr>
                                      <?php
                                      include("./includes/connectionWeberp.php");
                                     // include 'includes/ConnectDB_mysql.inc';
                                      
                                       // Show Sales Type drop down list
                                        $result=mysqli_query($conn,"SELECT typeabbrev, sales_type FROM salestypes ORDER BY sales_type");
                                        
                                        if (mysqli_num_rows($result)==0){
                                         $DataError =1;
                                         echo '<tr>
                                                         <td colspan="2">No sales types/price lists defined<br /><a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a></td>
                                                 </tr>';
                                        } else {
                                        echo '<tr>
                                                                <td>' . _('Sales Type') . '/' . _('Price List') . ':</td>
                                                           <td><select tabindex="9" name="SalesType" required="required">';

                                                    while ($myrow = mysqli_fetch_array($result)) {
                                                       echo '<option value="'. $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
                                                    } //end while loopre
                                                    //DB_data_seek($result,0);
                                            echo '</select></td>
                                                            </tr>';
                                            }
                                            
                                            // Show Customer Type drop down list
                                            $result=mysqli_query($conn,"SELECT typeid, typename FROM debtortype ORDER BY typename");
                                            if (mysqli_num_rows($result)==0){
                                                $DataError =1;
                                                echo '<a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a>';
                                                echo '<tr>
                                                                     <td colspan="2">No Customer types/price lists defined</td>
                                                      </tr>';
                                             } else {
                                                     echo '<tr>
                                                                     <td>' . _('Customer Type') . ':</td>
                                                                     <td><select tabindex="9" name="typeid" required="required">';

                                                     while ($myrow = mysqli_fetch_array($result)) {
                                                             echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
                                                     } //end while loop
                                                     //DB_data_seek($result,0);
                                                     echo '</select></td>
                                                             </tr>';
                                             }
                                           // $DateString = Date($_SESSION['DefaultDateFormat']);
	
	echo '</table></td>
			<td><table class="selection">
				<tr>
					<td style="text-align: right;">Discount Percent:</td>
					<td><input tabindex="11" type="text" class="number" name="Discount" value="0" size="5" maxlength="4" /></td>
				</tr>
				<tr>
					<td style="text-align: right;">Discount Code:</td>
					<td><input tabindex="12" type="text" name="DiscountCode" size="3" maxlength="2" /></td>
				</tr>
				<tr>
					<td style="text-align: right;">Payment Discount Percent:</td>
					<td><input tabindex="13" type="text" class ="number" name="PymtDiscount" value="0" size="5" maxlength="4" /></td>
				</tr>
				<tr>
					<td style="text-align: right;">Credit Limit:</td>
					<td><input tabindex="14" type="text" class="integer" name="CreditLimit" required="required" value="0" maxlength="14" /></td>
				</tr>
				<tr>
					<td style="text-align: right;">Tax Reference</td>
					<td><input tabindex="15" type="text" name="TaxRef" size="22" maxlength="20" /></td>
				</tr>';

                                    $result=mysqli_query($conn,"SELECT terms, termsindicator FROM paymentterms");
                                    if (mysqli_num_rows($result)==0){
                                            $DataError =1;
                                            echo '<tr><td colspan="2">There are no payment terms currently defined - go to the setup tab of the main menu and set at least one up first</td></tr>';
                                    } else {

                                            echo '<tr>
                                                            <td style="text-align: right;">Payment Terms:</td>
                                                            <td><select tabindex="15" name="PaymentTerms" required="required">';

                                            while ($myrow = mysqli_fetch_array($result)) {
                                                    echo '<option value="'. $myrow['termsindicator'] . '">' . $myrow['terms'] . '</option>';
                                            } //end while loop
                                            //DB_data_seek($result,0);

                                            echo '</select></td></tr>';
                                    }
                                    echo '<tr>
                                                    <td style="text-align: right;">Credit Status:</td>
                                                    <td><select tabindex="16" name="HoldReason" required="required">';

                                    $result=mysqli_query($conn,"SELECT reasoncode, reasondescription FROM holdreasons");
                                    if (mysqli_num_rows($result)==0){
                                            $DataError =1;
                                            echo '<tr>
                                                            <td colspan="2">There are no credit statuses currently defined - go to the setup tab of the main menu and set at least one up first</td>
                                                    </tr>';
                                    } else {
                                            while ($myrow = mysqli_fetch_array($result)) {
                                                    echo '<option value="'. $myrow['reasoncode'] . '">' . $myrow['reasondescription'] . '</option>';
                                            } //end while loop
                                            //DB_data_seek($result,0);
                                            echo '</select></td></tr>';
                                    }

                                    $result=mysqli_query($conn,"SELECT currency, currabrev FROM currencies");
                                    if (mysqli_num_rows($result)==0){
                                            $DataError =1;
                                            echo '<tr>
                                                            <td colspan="2">There are no currencies currently defined - go to the setup tab of the main menu and set at least one up first</td>
                                                    </tr>';
                                    } else {
                                            if (!isset($_POST['CurrCode'])){
                                                    $CurrResult = mysqli_query($conn,"SELECT currencydefault FROM companies WHERE coycode=1");
                                                    $myrow = mysql_fetch_row($CurrResult);
                                                    $_POST['CurrCode'] = $myrow[0];
                                            }
                                            echo '<tr>
                                                            <td style="text-align: right;">Customer Currency</td>
                                                            <td><select tabindex="17" name="CurrCode" required="required">';
                                            while ($myrow = mysqli_fetch_array($result)) {
                                                    if ($_POST['CurrCode']==$myrow['currabrev']){
                                                            echo '<option selected="selected" value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
                                                    } else {
                                                            echo '<option value="'. $myrow['currabrev'] . '">' . $myrow['currency'] . '</option>';
                                                    }
                                            } //end while loop
                                           // DB_data_seek($result,0);

                                            echo '</select></td>
                                                    </tr>';
                                    }

                                    echo '<tr>
                                                    <td>Language:</td>
                                                    <td><select name="LanguageID" required="required">';

                                    if (!isset($_POST['LanguageID']) OR $_POST['LanguageID']==''){
                                            $_POST['LanguageID']=$_SESSION['Language'];
                                    }

                                    foreach ($LanguagesArray as $LanguageCode => $LanguageName){
                                            if ($_POST['LanguageID'] == $LanguageCode){
                                                    echo '<option selected="selected" value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
                                            } else {
                                                    echo '<option value="' . $LanguageCode . '">' . $LanguageName['LanguageName']  . '</option>';
                                            }
                                    }
                                    echo '</select></td>
                                                    </tr>';

                                    echo '<tr>
                                                    <td style="text-align: right;">Customer PO Line on SO:</td>
                                                    <td><select tabindex="18" name="CustomerPOLine" required="required">
                                                            <option selected="selected" value="0">No</option>
                                                            <option value="1">Yes</option>
                                                            </select>
                                                    </td>
                                            </tr>
                                            <tr>
                                                    <td style="text-align: right;">Invoice Addressing:</td>
                                                    <td><select tabindex="19" name="AddrInvBranch" required="required">
                                                            <option selected="selected" value="0">Address to HO</option>
                                                            <option value="1">Address to Branch</option>
                                                            </select>
                                                    </td>
                                            </tr>
                                            </table></td>
                                            </tr>
                                            </table>';
                                   
                                        
                                      ?>
                            <div class="centre">
                                  <input tabindex="20" type="submit" name="submit" class="art-button-green" value="ADD NEW CUSTOMER" />
                            </div>         
                                            
                        </form>           
            </fieldset>
        </center></td></tr></table>
</center>


<?php
function locale_number_format($Number, $DecimalPlaces=0) {
	global $DecimalPoint;
	global $ThousandsSeparator;
	if(substr($_SESSION['Language'], 3, 2)=='IN') {// If country is India (??_IN.utf8). See Indian Numbering System in Manual, Multilanguage, Technical Overview.
		return indian_number_format(floatval($Number),$DecimalPlaces);
	} else {
		if (!is_numeric($DecimalPlaces) AND $DecimalPlaces == 'Variable'){
			$DecimalPlaces = trim($Number) - trim(intval($Number));
			if ($DecimalPlaces > 0){
				$DecimalPlaces--;
			}
		}
		return number_format(floatval($Number),$DecimalPlaces,$DecimalPoint,$ThousandsSeparator);
	}
}
    include("./includes/footer.php");
?>