<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
//    if(isset($_SESSION['userinfo']['can_edit_claim_bill'])){
//   	    if($_SESSION['userinfo']['can_edit_claim_bill'] != 'yes'){
//  	 			header("Location: ./index.php?InvalidPrivilege=yes");
//  	 	    }
//
//      }
  //   if(isset($_SESSION['userinfo'])){
	// 	if(isset($_SESSION['userinfo']['Modify_Cash_information'])){
	// 	    if($_SESSION['userinfo']['Modify_Cash_information'] != 'yes' && $_SESSION['userinfo']['Modify_Credit_Information'] != 'yes'){
	// 			header("Location: ./index.php?InvalidPrivilege=yes");
	// 	    }else{
	// 		@session_start();
	// 	    }
	// 	}else{
	// 	    header("Location: ./index.php?InvalidPrivilege=yes");
	// 	}
  //   }else{
	// @session_destroy();
	//     header("Location: ../index.php?InvalidPrivilege=yes");
  //   }
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
	  //   if((strto   lower($Billing_Type) == 'outpatient cash') || (strtolower($Billing_Type) == 'inpatient cash')){
		// if(strtolower($_SESSION['userinfo']['Modify_Cash_information'] != 'yes') && ($_SESSION['userinfo']['Modify_Credit_Information'] != 'yes')){
		//     header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage");
		// }
	  //  }
    if((strtolower($Billing_Type) == 'outpatient credit') || (strtolower($Billing_Type) == 'inpatient credit')){
//		   if(strtolower($_SESSION['userinfo']['can_edit_claim_bill'] != 'yes')){
//		    header("Location: ./index.php");
//		}
	    }
	}
	// if(isset($_GET['Patient_Payment_ID'])){
	//     $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	//     $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_patient_payments
	// 					    where Patient_Payment_ID = '$Patient_Payment_ID'");
	//     while($row = mysqli_fetch_array($select_billing_type)){
	// 	$Billing_Type = $row['Billing_Type'];
	//     }
	//     if((strtolower($Billing_Type) == 'outpatient cash') || (strtolower($Billing_Type) == 'inpatient cash')){
	// 	if(strtolower($_SESSION['userinfo']['Modify_Cash_information'] != 'yes') && ($_SESSION['userinfo']['Modify_Credit_Information'] != 'yes')){
	// 	    header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage");
	// 	}
	//     }elseif((strtolower($Billing_Type) == 'outpatient credit') || (strtolower($Billing_Type) == 'inpatient credit')){
	// 	if(strtolower($_SESSION['userinfo']['Modify_Cash_information'] != 'yes') && ($_SESSION['userinfo']['Modify_Credit_Information'] != 'yes')){
	// 	    header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage");
	// 	}
	//     }
	// }
    ?>


<?php
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['from']) && $_GET['from'] == "ebill") { ?>
        <a href='revenuecollectionbyfolio.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage' class='art-button-green'>
            BACK
        </a>
 <?php }else{
    if (($_SESSION['userinfo']['Modify_Cash_information'] == 'yes') || ($_SESSION['userinfo']['Modify_Credit_Information'] == 'yes')) {
        ?>
        <a href='edittransactionlist.php?ModifyTransaction=ModifyTransactionThisPage' class='art-button-green'>
            BACK
        </a>
<?php }}
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
        ?>
    <?php }
} ?>


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
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>

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
					    where pp.Sponsor_ID = sp.Sponsor_ID and
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
                $Patient_Payment_ID=$row['Patient_Payment_ID'];
                $Sub_Department_ID=$row['Sub_Department_ID'];
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
            $Patient_Payment_ID='';
            $Sub_Department_ID='';
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
            $Patient_Payment_ID='';
            $Sub_Department_ID='';
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
				    <select name='Consultant' disabled='disabled' id='Consultant' value='<?php echo $Consultant; ?>'>
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

                        echo "<td style='text-align: center;color:green;font-size:20px' id='tansactionedited'>

                             </td>";

                    ?>


                </tr>
            </table>
        </center>
</fieldset>
<fieldset>
        <center>
            <table width=100%>
		<tr>
		    <td>

                    <!-- Get Patient ID -->
                    <?php if(isset($_GET['Patient_Payment_ID'])){ $Patient_Payment_ID = $_GET['Patient_Payment_ID']; }else{ $Patient_Payment_ID = 0; } ?>


			<div style="width:100%; height:210px;overflow-x:hidden;overflow-y:scroll" >
			   <?php
			     include 'Patient_Billing_Edit_Transaction_Iframe.php';
			   ?>
			</div>
		    </td>
		</tr>
                <tr>
<!--                <td style="float:right">
                     <a href="edittransactionlist.php?ModifyTransaction=ModifyTransactionThisPage" style="width:70px" class="art-button-green">Done</a>
                </td>-->

                    <td style="float:right">
                        <input type="button" value="Print receipt" class='art-button-green' onclick="Print_Receipt_Payment('<?php echo $Patient_Payment_ID; ?>')" >
                    </td>
<?php if ($deliveryStatus != 1) { ?>
                        <td style="float:right">
                            <input type="button" value="Add Item" class='art-button-green' id='Add_Item'>
                        </td>
<?php } ?>


                </tr>
            </table>
        </center>
</fieldset>

<div id="Item2Edit" style="display: none;">
   <div align="center" style="display:none;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
    <div id="Item2EditData"></div>

</div>


<div id="Item2Add" style="display: none">
 <table width="100%">
	<tr>
		<td width="8%"><b>Check In Type</b></td>
		<td width="12%"><b>Patient Direction</b></td>
		<td width="15%"><b>Consultant</b></td>
		<td width="7%" style="text-align: left;">Item Name</td>
		<td width="8%" style="text-align: right;"><b>Price</b></td>
		<td width="8%" style="text-align: right;"><b>Discount</b></td>
		<td width="8%" style="text-align: center;"><b>Quantity</b></td>
	</tr>
	<tr>
		<td>
			<select name="Type_Of_Check_In" id="Type_Of_Checks_In">
                            <option value="IPD Services">IPD Services</option>
                            <option value="Doctor Room">Doctor Room</option>
                            <option value="Dental">Dental</option>
                            <option value="Dialysis">Dialysis</option>
                            <option value="Dressing">Dressing</option>
                            <option value="Ear">Ear</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Matenity">Matenity</option>
                            <option value="Optical">Optical</option>
                            <option value="Procedure">Procedure</option>
                            <option value="Physiotherapy">Physiotherapy</option>
                            <option value="Radiology">Radiology</option>
                            <option value="Theater">Theater</option>
			</select>
		</td>
		<td>
			<select id="Patient_Direction" id="Patient_Direction">
				<option <?php if(strtolower($Patient_Direction) == 'others' || strtolower($Patient_Direction) == 'other'){ echo "selected='selected'"; }?>>Othes</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to doctor'){ echo "selected='selected'"; }?>>Direct To Doctor</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to doctor via nurse station'){ echo "selected='selected'"; }?>>Direct To Doctor Via Nurse Station</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to clinic'){ echo "selected='selected'"; }?>>Direct To Clinic</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to clinic via nurse station'){ echo "selected='selected'"; }?>>Direct To Clinic Via Nurse Station</option>
			</select>
		</td>
		<td id="Consultant_Area">
                    <input type="text" readonly="true" id='consultant_Name' value="">
		</td>
		<td width="30%">
			<input type="text" name="Pro_Name" id="New_Item_Name" readonly="readonly" value="">
                        <input type="hidden" name="Pro_ID" id="Pro_ID_New_Item" value="<?php echo $Sponsor_ID;?>">
                        <input type="hidden" name="Pro_ID" id="Patient_Payment_ID" value="<?php echo $Patient_Payment_ID;?>">
                        <input type="hidden" name="Pro_ID" id="Registration_ID" value="<?php echo $Registration_ID;?>">
                        <input type="hidden" name="Pro_ID" id="Employee_ID" value="">
                        <input type="hidden" name="Pro_ID" id="Item_ID" value="">


		</td>
		<td>
			<input type="text" name="Price" id="New_Item_Price" value="" readonly="readonly" style="text-align: right;">
		</td>
		<td>
			<input type="text" name="Price" id="New_Item_Discount" value="0" style="text-align: right;">
		</td>
		<td>
			<input type="text" name="Price" id="New_Item_Quantity" value="1" style="text-align: center;">
		</td>
	</tr>
	<tr>

	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<tr>
		<td colspan="7" style="text-align: right;">
                        <input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" class="art-button-green" style="display:none">
			<input type="button" name="Item_Button" id="New_Item_Button" value="SELECT ITEM" class="art-button-green" onclick="Open_Item_Dialogy(<?php echo $Sponsor_ID; ?>)">
			<input type="button" name="Submit" id="New_Item_Submit" value="SAVE" class="art-button-green">
			<!--<input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Cancel_Edit_Process()">-->
		</td>
	</tr>
</table>

</div>

<div id="" style="display:none">

</div>

<div id="List_OF_Doctors" style="display: none">
	<center>
		<table width="100%">
			<tr>
				<td>
					<input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" >
				</td>
			</tr>
			<tr>
				<td>
					<fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
						<table width="100%" id='viewNames'>
						<?php
							$counter = 0;
							$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
							$doctors_num = mysqli_num_rows($get_doctors);
							if($doctors_num > 0){
								while ($data = mysqli_fetch_array($get_doctors)) {
						?>
                                                                 <tr class="doctor_name" id="<?php echo $data['Employee_ID'];?>" name="<?php echo $data['Employee_Name'];?>">
									<td style='text-align: right;'>
										<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
									</td>
									<td>
										<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
									</td>
								</tr>
						<?php
								}
							}
						?>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
	</center>
</div>



<div id="AllItems" style="display:none">

    <table width = 100%>
    <tr>
	<td style='text-align: center;'>
	<select name='Item_Category_ID' id='Item_Category_ID'>
            <option value="" selected='selected'></option>
	    <?php
			$data = mysqli_query($conn,"select * from tbl_item_category order by Item_Category_Name");
			while($row = mysqli_fetch_array($data)){
			    echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
			}
	    ?>
	</select>
	</td>
    </tr>
    <tr>
        <td><input type='text' id='Search_Product_Name' name='Search_Product_Name' placeholder='~~~ ~~ Search Item Name ~~ ~~~' style='text-align: center;' autocomplete="off"></td>
    </tr>
    <tr>
	<td id='Items_Areaz'>
	    <fieldset style='overflow-y: scroll; height: 300px;' id='Items_Area'>
			<table width="100%" id="displayItem">
			    <?php
					$result = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE  Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID')  order by Product_Name LIMIT 100") or die(mysqli_error($conn));
					while($row = mysqli_fetch_array($result)){
					    echo "<tr>
						<td style='color:black; border:2px solid #ccc;text-align: left;'>";
				?>
						    <input type='radio' class="itemNum" name='selection' item='<?php echo $row['Item_ID']; ?>' id='<?php echo $row['Item_ID']; ?>a' value='<?php echo $row['Product_Name']; ?>'>
				<?php
						echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."a'>".$row['Product_Name']."</label></td></tr>";
					}
			    ?>
			</table>
	    </fieldset>
	</td>
    </tr>
</table>

</div>
<?php
    include("./includes/footer.php");
?>
<script>
    $('#New_Item_Button').on('click',function(){
         $('#AllItems').dialog({
          modal:true,
          width:'30%',
          minHeight:200,
          resizable:true,
          draggable:true,
          title:"Select Item",
        });

    });

    $('.itemNum').on('click',function(){
        var ItemName=$(this).val();
        var id=$(this).attr('item');
        var Billing_Type=$('#Billing_Type').val();
        $('#Item_ID').val(id);
        var Sponsor=$('#Pro_ID_New_Item').val();
        $('#New_Item_Name').val(ItemName);
        $('#AllItems').dialog('close');
        $.ajax({
        type:'POST',
        url:"requests/Sub_Item_price.php",
        data:"action=ViewItem&item="+id+"&Sponsor="+Sponsor+'&Billing_Type='+Billing_Type,
         success:function(html){
            $('#New_Item_Price').val(html);
        }
        });
    });

    $('#New_Item_Submit').on('click',function(){
        var Type_Of_Check_In=$('#Type_Of_Checks_In').val();
        var Item_ID=$('#Item_ID').val();
        var Discount=$('#New_Item_Discount').val().replace(',', '');
        var Price=$('#New_Item_Price').val().replace(',', '');
        var Quantity=$('#New_Item_Quantity').val();
        var Patient_Direction=$('#Patient_Direction').val();
        var Consultant=$('#Consultant').val();
        var Patient_Payment_ID=$('#Patient_Payment_ID').val();
        $.ajax({
        type:'POST',
        url:"requests/Sub_Item_price.php",
        data:"action=AddNewItem&Check_In_Type="+Type_Of_Check_In+"&Item_ID="+Item_ID+'&Discount='+Discount+'&Price='+Price+'&Quantity='+Quantity+'&Patient_Direction='+Patient_Direction+'&Consultant='+Consultant+'&Patient_Payment_ID='+Patient_Payment_ID,
         success:function(html){
                alert(html);

        }
        });

    });

        $( "#Item2Add" ).on( "dialogclose", function( event, ui ) {
          var url=window.location.href;
          location.href=url;

        });



    $('.EditItem').on('click',function(){
        var ID=$(this).attr('id');
        var pp=$(this).attr('pp');
        $('#Item2Edit').dialog({
          modal:true,
          width:'70%',
          minHeight:200,
          resizable:true,
          draggable:true,
          title:"Edit Item",
        });
        $.ajax({
        type:'POST',
        url:"Patient_Billing_Item_Edit_Transaction.php",
        data:"action=ViewItem&Patient_Payment_Item_List_ID="+ID+'&Patient_Payment_ID='+pp,
         success:function(html){
             $('#Item2EditData').html(html);
        }
        });
    });

        $("#Item2Edit").on( "dialogclose", function( event, ui ) {
          var url=window.location.href;
          location.href=url;

        });


    $('.RemoveItem').on('click',function(){
       var ID=$(this).attr('id');
       if(confirm("Are you sure you want to remove this item?")){
        $.ajax({
        type:'POST',
        url:"CheckUnProcessedItem.php",
        data:"action=DeleteItem&Patient_Payment_Item_List_ID="+ID,
         success:function(html){
             alert(html);
            var url=window.location.href;
            location.href=url;
        }
        });
       }
    });
    function Get_Selected_Doctor(getDoctor_name){
        $("#consultant_Name").val(getDoctor_name);
    }

    $('#Add_Item').on('click',function(){
       $('#Item2Add').dialog({
              modal:true,
              width:'70%',
              minHeight:200,
              resizable:true,
              draggable:true,
              title:"Add Item",
       });
    });


$('#Doctors_List').on('click',function(){
   $('#List_OF_Doctors').dialog({
          modal:true,
          width:'30%',
          minHeight:200,
          resizable:true,
          draggable:true,
          title:"Select Doctor",
   });

});


 $('.doctor_name').on('click',function(){
   var employee_ID=$(this).attr('id');
   $('#Employee_ID').val(employee_ID);
   var employee_name=$(this).attr('name');
   $('#Consultant_Area').html('<input type="text" readonly="true" value="'+employee_name+'">');
   $('#List_OF_Doctors').dialog('close');
 });

 $('#Patient_Direction').on('change',function(){
    var direction=$(this).val();
    if(direction=='Direct To Doctor'){
        $('#Doctors_List').show();
    }else{
        $('#Doctors_List').hide();
    }
 });


 $('#Doc_Name').on('input',function(){
     var name=$(this).val();
     $.ajax({
        type:'POST',
        url:"Search_Doctors.php",
        data:"action=SearchItem&name="+name,
         success:function(html){
              $('#viewNames').html(html);

        }
        });

 });


 $('#Search_Product_Name').on('input',function(){
   var category=$('#Item_Category_ID').val();
   var Sponsor_ID='<?= $Sponsor_ID ?>';
   var item=$(this).val();
    $.ajax({
        type:'POST',
        url:"Search_Item_Names.php",
        data:"action=SearchProduct&item="+item+'&category='+category+'&Sponsor_ID='+Sponsor_ID,
         success:function(html){
              $('#displayItem').html(html);

        }
      });

 });

 $('#Item_Category_ID').on('change',function(){
     var category=$(this).val();
     var item=$('#Search_Product_Name').val();
    $.ajax({
        type:'POST',
        url:"Search_Item_Names.php",
        data:"action=SearchProductCategory&item="+item+'&category='+category,
         success:function(html){
              $('#displayItem').html(html);

        }
      });
 });
</script>

<script>
function Print_Receipt_Payment(Patient_Payment_ID){
 //alert(Patient_Payment_ID);
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
       var winClose=popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);
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

$(document).read(function(){
  $('.ZebraDialog').css('z-index','999');
});

</script>
