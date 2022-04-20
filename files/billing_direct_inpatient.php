<?php
include("./includes/header.php");
include("./includes/connection.php");
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            //@session_start();
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<!-- link menu --> 
<style>
    .alnright { text-align: right; }
</style>
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Checked In - Outpatient List'){
	    document.location = "searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
	}else if (patientlist=='Checked In - Inpatient List') {
	    document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Outpatient') {
	    document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Inpatient') {
	    document.location = "DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='AdHOC Payments - Outpatient') {
	    document.location = "continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
	}else if (patientlist=='Patient From Outside') {
	    document.location = "#";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<!--
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
	Direct Cash - Outpatient
    </option>
    <option>
	Direct Cash - Inpatient
    </option>
    <option>
	AdHOC Payments - Outpatient
    </option>
    <option>
	Patient From Outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> -->

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        <a href='Direct_Cash_Inpatient.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>

<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->


<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>

<?php
//    select patient information
if (isset($_GET['Registration_ID'])) {
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

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
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
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
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
}
?>



<!-- get employee id-->
<?php
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
?>

<?php
	//get last folio number, patient bill id and check in id
	$select = mysqli_query($conn,"select Folio_Number, Check_In_ID, Patient_Bill_ID from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Folio_Number = $data['Folio_Number'];
			$Check_In_ID = $data['Check_In_ID'];
			$Patient_Bill_ID = $data['Patient_Bill_ID'];
		}
	}else{
        $Folio_Number = '';
        $Check_In_ID = '';
        $Patient_Bill_ID = '';
	}
?>

    <fieldset>  
        <legend align="right"><b>DIRECT CASH ~ INPATIENT LIST: REVENUE CENTER</b></legend>
        <center> 
            <table width="100%">
                <tr>
                    <td width='10%' style="text-align:right;">Patient Name</td>
                    <td width='15%' ><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td width='11%'style="text-align:right;">Gender</td>
                    <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                    <td width='11%'style="text-align:right;">Registration Number</td>
                    <td width='12%'><input type='text' name='Registration_Number' disabled='disabled' id='Registration_Number' value='<?php echo $Registration_ID; ?>'></td>
                    <td style="text-align:right;">Prepared By</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Billing Type</td>
                    <td>
                        <select name='Billing_Type' id='Billing_Type' required='required'>
                            <option>Inpatient Cash</option> 
                        </select>
                    </td>
                    <td style="text-align:right;">Occupation</td>
                    <td>
                        <input type='text' name='Occupation' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                    </td>
                    <td style="text-align:right;">Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                    <td style="text-align:right;">Supervised By</td>
                    <?php
                    if (isset($_SESSION['supervisor'])) {
                        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                                $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                            } else {
                                $Supervisor = "Unknown Supervisor";
                            }
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                    ?>
                    <td><input type='text' name='Supervisor_Name' id='Supervisor_Name' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Type Of Check In</td>
                    <td>  
                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='type_Of_Check_In(this.value)' onclick='examType()'>
                            <option>Direct Cash</option>
                        </select>
                    </td>
                    <td style="text-align:right;">Registered Date</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                    <td style="text-align:right;">Sponsor Name</td>
                    <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td> 
                    <td style="text-align:right;">Folio Number</td>
                    <td><input type='text' name='Folio_Number' disabled='disabled' id='Folio_Number' value='<?php echo $Folio_Number; ?>'></td> 
                </tr>
                 <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Clinic
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td style="text-align:right">
                        <b style="color:red">Select Department</b>
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='finance_department_id' id='finance_department_id'>
                            <option selected='selected'></option>
                            <?php
                             $select_department = "select * from tbl_finance_department where enabled_disabled = 'enabled'";
                            $result = mysqli_query($conn,$select_department);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['finance_department_id']; ?>"><?php echo $row['finance_department_name']."--".$row['finance_department_code']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                   <td style="text-align:right">
                        Cash Deposit For
                    </td>
                    <td>
                        <select  style='width: 100%;height:30%'  name='clinic_location_id' id='clinic_location_id' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select sub.Sub_Department_ID,sub.Sub_Department_Name from tbl_sub_department sub,tbl_department de WHERE sub.Department_ID= de.Department_ID AND Department_Location='Deposit'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Sub_Department_ID']; ?>"><?php echo $row['Sub_Department_Name']; ?></option>
                                <?php
                            }
                            ?>
                        </select> 
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>
        <center>
            <table width=100%>
                <tr>
                    <td width=25%>
                        <table width = 100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        <option selected='selected'></option>
                                        <?php
                                            $data = mysqli_query($conn,"select c.Item_Category_ID, c.Item_Category_Name from 
                                                                    tbl_item_category c, tbl_item_subcategory sc, tbl_items i where
                                                                    c.Item_Category_ID = sc.Item_Category_ID and
                                                                    sc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                                                    i.Visible_Status = 'Others' group by c.Item_Category_ID order by c.Item_Category_Name") or die(mysqli_error($conn));
                                            while($row = mysqli_fetch_array($data)){
                                                echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
                                            }
                                        ?>   
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type='text' id='Search_Value' name='Search_Value' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name' autocomplete="off"></td>
                            </tr>               
                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 250px;' id='Items_Fieldset'>
                                        <table width=100%>
                                            <?php
                                                $result = mysqli_query($conn,"select Item_ID, Product_Name from tbl_items where Visible_Status = 'Others' order by Product_Name limit 200");
                                                while($row = mysqli_fetch_array($result)){
                                                    echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;'>"; 
                                            ?>
                                                    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
                                            <?php
                                                echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."<label></td></tr>";
                                                }
                                            ?> 
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <table width=100%>
                                    <tr>
                                        <td>Item Name</td>
                                        <td>Item Description</td>
                                        <td>Amount</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">
                                            <input type="text" name="Item_Name" id="Item_Name" readonly="readonly" placeholder='Item Name'>
                                            <input type="hidden" id="Item_ID" name="Item_ID" value="">
                                        </td>
                                        <td width="40%"> 
                                            <input type='text' name='Item_Description' id='Item_Description' placeholder='Item Description' placeholder="off" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type='text' name='Amount' id='Amount' placeholder='Amount' autocomplete="off">
                                        </td>
                                        <td style='text-align: center;'>
                                            <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='test_if_item_arleady_added()'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <fieldset style='overflow-y: scroll; height: 215px;' id="Transaction_Area">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="5%"><b>SN</b></td>
                                                        <td><b>ITEM NAME</b></td>
                                                        <td><b>ITEM DESCRIPTION</b></td>
                                                        <td width="9%" style="text-align: right;"><b>AMOUNT</b></td>
                                                        <td width="5%"></td>
                                                    </tr>
                                                <?php
                                                    //display items
                                                    $Grand_Total = 0;
                                                    $select = mysqli_query($conn,"select dcc.Cache_ID, dcc.Item_Description, dcc.Amount, i.Product_Name
                                                                            from tbl_direct_cash_cache dcc, tbl_items i where 
                                                                            dcc.Registration_ID = '$Registration_ID' and
                                                                            dcc.Item_ID = i.Item_ID and
                                                                            Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                                    $num = mysqli_num_rows($select);
                                                    if($num > 0){
                                                        $temp = 0;
                                                        while ($data = mysqli_fetch_array($select)) {
                                                ?>
                                                            <tr>
                                                                <td><?php echo ++$temp; ?></td>
                                                                <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
                                                                <td><?php echo $data['Item_Description']; ?></td>
                                                                <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Amount'], 2) : number_format($data['Amount'])); ?></td>
                                                                <td style="text-align: center;">
                                                                    <input type="button" value="X" onclick="Remove_Item('<?php echo $data['Item_Description']; ?>',<?php echo $data['Cache_ID']; ?>);" style="color: red;">
                                                                </td>
                                                            </tr>
                                                <?php
                                                            $Grand_Total += $data['Amount'];
                                                        }
                                                    }
                                                ?>
                                                </table>
                                            </fieldset>
                                        </td>
                                    </tr>
                         <tr>
                             <td style="text-align: right;" id="Payment_Button_Area" colspan="2">
                                           <div style="width:100%;text-align:center;">
                                        <?php if ($num > 0) { ?>
                                            <div style="float:left;width: 40%">
                                                <?php 
                                                    if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment') {
//                                                     echo " <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' style='width:100%;float:left' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>";  
                                                        echo '<input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;';
                                                    }
                                                ?>
                                            </div>

                                            <div style="float:right;width: 40%">
                                            <?php  if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>

                                            <input type="button" name="Make_Payment" id="Make_Payment" value="MAKE PAYMENTS" style='width:100%;float:left' class="art-button-green" onclick="Make_Payments();">
                                             <img id="offlineProgressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none;">&nbsp;&nbsp;
		
                                                <?php } ?>
                                            </div>
                                                <?php } else { ?>
                                            <div style="float:left;width: 40%">
                                            <?php 

                                            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment') {
                                              echo " <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' style='width:100px'  name='Pay_Via_Mobile' class='art-button-green' onclick='Make_Payment_Warning()'>";  
                                              
                                            }
                                            ?>
                                            </div>

                                            <div style="float:right;width:40%">
                                            <?php  if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                                                <input type="button" name="Make_Payment" id="Make_Payment" style="width:100px" value="MAKE PAYMENTS" class="art-button-green" onclick="Make_Payment_Warning();">

                                              <?php } //end checking make payment button visibility
                                               } ?>
                                            </div>
                                        </div>   
                                        </td>
                                        <input type="text" hidden="hidden" id="grand_total_txt" value="<?php echo $Grand_Total; ?>">
                                        <td colspan="2" style="text-align: right;" id="Total_Area">
                                            
                                        <?php
                                            echo '<b>GRAND TOTAL  '.(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Grand_Total, 2) : number_format($Grand_Total)).'  '.$_SESSION['hospcurrency']['currency_code'].'</b>';
                                        ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        
                                    </tr>
                                </table> 
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
<div id="myDiaglog" style="display:none;">
    
    
</div>


        <!-- ePayment pop up windows -->
        <div id="ePayment_Window" style="width:50%;" >
            <span id='ePayment_Area'>
                <table width=100% style='border-style: none;'>
                    <tr>
                        <td>

                        </td>
                    </tr>
                </table>
            </span>
        </div>


    <script type="text/javascript">
        function Get_Item_Name(Item_Name,Item_ID){
            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
            
            document.getElementById("Item_Description").value = '';
            document.getElementById("Amount").value = '';

            document.getElementById("Item_Description").focus();
        }
        
   function validate_number(){
       var patient_phone_number=$("#patient_phone_number").val();
       var patient_phone_number=$("#patient_phone_number").val();
       var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
       if(patient_phone_number_=="")patient_phone_number_=0;
       $("#patient_phone_number").val("0"+parseInt(patient_phone_number_));
    }
    
        function request_control_number(){
        
          var selected_Payment_Item_Cache_List_ID = []; 
          var grand_total_price=0;
//        $(".Payment_Item_Cache_List_ID_selected").each(function() {
//            selected_Payment_Item_Cache_List_ID.push($(this).val());
//           var Payment_Item_Cache_List_ID=$(this).val();
//           
//           grand_total_price+=$("total_price"+Payment_Item_Cache_List_ID).val();
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
//	});
      
         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val();
           
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
	});
        
        
        var selected_Payment_Item_Cache_List_ID = [];
        $(".Payment_Item_Cache_List_ID_selected").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
          var validate=0;
        var patient_phone_number=$("#patient_phone_number").val();
        var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
        if(patient_phone_number_==""){
            $("#patient_phone_number").css("border","2px solid red");
            $("#patient_phone_number").focus();
            validate++;
        }else{
           $("#patient_phone_number").css("border",""); 
        }
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
            
        if(validate<=0){
            $("#progress_dialog").show();
            $.ajax({
            type:'POST',
            url:'gepg_processing_payfolder/index.php',
            data:{Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID,grand_total:grand_total_price,selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID},
            success:function(data){
                if(data=="fail"){
                    alert("Process fail...please try again");  
                }else{
                    $("#progress_dialog").hide();
                    //alert("Payment Created Successfully");
//                    filter_items_by_department();
                    $("#patient_bill_dialog").dialog("close");
                    setTimeout(function(){read_return_controlnumber(data)}, 3000);
                    
                }
               console.log(data);
            }
        });
        }
        }else{
            alert("Select Item to create e-Bill");
            $(".select_item_column").css("background","red");
        }
      
    }
    </script>

    <script>
         function Pay_Via_Mobile_Function() {
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Employee_ID = '<?php $Employee_ID; ?>';
            if (window.XMLHttpRequest) {
                myObjectGetDetails = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
            }

            myObjectGetDetails.onreadystatechange = function () {
                data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
                    document.getElementById('ePayment_Area').innerHTML = data29;
                    $("#ePayment_Window").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectGetDetails.open('GET','directCash_Patient_Details.php?Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID, true);
            myObjectGetDetails.send();//tbl_reception_items_list_cache
        }
    </script>
    
     <script type="text/javascript">
                function Verify_ePayment_Bill() {
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Employee_ID = '<?php echo $Employee_ID; ?>';
                
                if (window.XMLHttpRequest){
                myObjectVerify = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                 myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                 myObjectVerify.overrideMimeType('text/xml');
                }
                myObjectVerify.onreadystatechange = function (){
                data2912 = myObjectVerify.responseText;
                 if (myObjectVerify.readyState == 4) {
                var feedback = data2912;
                if (feedback == 'yes'){
                 Create_ePayment_Bill();
                } else{
                alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
                }
                }; //specify name of function that will handle server response........
                myObjectVerify.open('GET', 'Verify_directCash_ePayment_Bill.php?P_Type=Credit_Patient&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
                myObjectVerify.send();
                }
    </script>
    
    
    <script type="text/javascript">
        
      function Create_ePayment_Bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
        alert("Process Fail! You can not prepare a bill with zero amount");
        } else{
        if (Payment_Mode != null && Payment_Mode != ''){
        if (Payment_Mode == 'Bank_Payment'){
        var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                if (Confirm_Message == true) {
        document.location = 'directCash_Prepare_Bank_Payment_Transaction.php?src=dircashinp&Section=departmental&Registration_ID=' + Registration_ID;
        }
        } else if (Payment_Mode == 'Mobile_Payemnt'){
        var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                if (Confirm_Message == true) {
        document.location = "#";
        }
        }
        } else{
        document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
        }
        
        }
        }   
    </script>
    
    
    
    
    
     <script>
        $(document).ready(function () {
            $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
        });
    </script>
    
    
    
    <script type="text/javascript">
        function test_if_item_arleady_added(){
       var Registration_ID = '<?php echo $Registration_ID; ?>';
       var Item_ID = document.getElementById("Item_ID").value;
       $.ajax({
           type:'GET',
           url:'test_if_item_arleady_added.php',
           data:{Registration_ID:Registration_ID,Item_ID:Item_ID},
           success:function(data){
               console.log(data);
               if(data=="item_exist"){
                 alert("Item already Added");  
               }else{
                  Get_Selected_Item() 
               }
           }
       });
    }
        function Get_Selected_Item(){
            var Item_ID = document.getElementById("Item_ID").value;
            var Item_Description = document.getElementById("Item_Description").value;
            var Amount = document.getElementById("Amount").value;
            var Item_Name = document.getElementById("Item_Name").value;
            var Registration_ID = '<?php echo $Registration_ID; ?>';
             var Clinic_ID = document.getElementById("Clinic_ID").value;
             var clinic_location_id = document.getElementById("clinic_location_id").value;
             var finance_department_id = document.getElementById("finance_department_id").value;
                                    if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Select clinic first")
                                        exit;
                                    }
                                      if(clinic_location_id==''|| clinic_location_id==null){
                                        alert("Select clinic location first")
                                        exit;
                                    }
                                    if(finance_department_id==''|| finance_department_id==null){
                                        alert("Select Department first")
                                        exit;
                                    }
                                  //  alert(Clinic_ID)
            if(Item_ID != null && Item_ID != '' && Item_Description != null && Item_Description != '' && Amount != null && Amount != ''){
                if(window.XMLHttpRequest){
                    myObjectGetItem = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetItem.overrideMimeType('text/xml');
                }

                myObjectGetItem.onreadystatechange = function (){
                    data29 = myObjectGetItem.responseText;
                    if (myObjectGetItem.readyState == 4) {
                        document.getElementById('Transaction_Area').innerHTML = data29;
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Item_Description").value = '';
                        document.getElementById("Amount").value = '';
                        document.getElementById("Item_Name").value = '';
                        Update_Total();
                        Update_Payment_Button();
                        alert("Item added successfully");
                    }
                }; //specify name of function that will handle server response........
                
                myObjectGetItem.open('GET','Direct_Cash_Get_Selected_Item.php?Item_ID='+Item_ID+'&Item_Description='+Item_Description+'&Amount='+Amount+'&Registration_ID='+Registration_ID+"&Clinic_ID="+Clinic_ID+"&finance_department_id="+finance_department_id+"&clinic_location_id="+clinic_location_id,true);
                myObjectGetItem.send();
            }else{
                if(Item_Name == null || Item_Name == ''){
                    alert("Please select Item first");
                }else{
                    if(Item_Description == null || Item_Description == ''){
                        document.getElementById("Item_Description").style = 'border: 3px solid red';
                    }

                    if(Amount == null || Amount == ''){
                        document.getElementById("Amount").style = 'border: 3px solid red';
                    }
                }
            }
        }
    </script>

    <script type="text/javascript">
        function Remove_Item(Product_Name,Cache_ID){
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Confirm_Message = confirm("Are you sure you want to remove \n"+Product_Name);
        
            if (Confirm_Message == true) {
                if(window.XMLHttpRequest){
                    myObjectRemove = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectRemove.overrideMimeType('text/xml');
                }

                myObjectRemove.onreadystatechange = function (){
                    data390 = myObjectRemove.responseText;
                    if (myObjectRemove.readyState == 4) {
                        document.getElementById('Transaction_Area').innerHTML = data390;
                        Update_Total();
                        Update_Payment_Button();
                    }
                }; //specify name of function that will handle server response........
                
                myObjectRemove.open('GET','Direct_Cash_Remove_Item.php?Cache_ID='+Cache_ID+'&Registration_ID='+Registration_ID,true);
                myObjectRemove.send();
            }
        }
    </script>

    <script type="text/javascript">
        function Update_Total(){
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            if(window.XMLHttpRequest){
                myObjectUpdate = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdate.overrideMimeType('text/xml');
            }

            myObjectUpdate.onreadystatechange = function (){
                data390 = myObjectUpdate.responseText;
                if (myObjectUpdate.readyState == 4) {
                    document.getElementById('Total_Area').innerHTML = "<b>GRAND TOTAL   "+data390+'   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    document.getElementById('grand_total_txt').value = data390;
                }
            }; //specify name of function that will handle server response........
            
            myObjectUpdate.open('GET','Direct_Cash_Update_Total.php?Registration_ID='+Registration_ID,true);
            myObjectUpdate.send();
        }
    </script>

    <script type="text/javascript">
        function Update_Payment_Button(){
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            if(window.XMLHttpRequest){
                myObjectUpdateButton = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdateButton = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdateButton.overrideMimeType('text/xml');
            }

            myObjectUpdateButton.onreadystatechange = function (){
                data999 = myObjectUpdateButton.responseText;
                if (myObjectUpdateButton.readyState == 4) {
                    document.getElementById('Payment_Button_Area').innerHTML = data999;
                }
            }; //specify name of function that will handle server response........
            
            myObjectUpdateButton.open('GET','Direct_Cash_Update_Payment_Button.php?Registration_ID='+Registration_ID,true);
            myObjectUpdateButton.send();
        }
    </script>

    <script type="text/javascript">
        function getItemsListFiltered(Item_Name){
            var Item_Category_ID = document.getElementById("Item_Category_ID").value;
            if(window.XMLHttpRequest){
                myObjectGetItem = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectGetItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItem.overrideMimeType('text/xml');
            }

            myObjectGetItem.onreadystatechange = function (){
                data9909 = myObjectGetItem.responseText;
                if (myObjectGetItem.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = data9909;
                }
            }; //specify name of function that will handle server response........
            
            if(Item_Category_ID != null && Item_Category_ID != ''){
                myObjectGetItem.open('GET','Direct_Cash_Get_List_Of_Items.php?Item_Name='+Item_Name+'&Item_Category_ID='+Item_Category_ID,true);
            }else{
                myObjectGetItem.open('GET','Direct_Cash_Get_List_Of_Items.php?Item_Name='+Item_Name,true);
            }
            
            myObjectGetItem.send();
        }
        
        
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var registration_id = '<?php echo $Registration_ID; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result)
             document.location = 'Direct_Cash_Make_Payment_Inpatient.php?Registration_ID='+registration_id+'&Sponsor_ID='+Sponsor_ID+'&manual_offline=manual';
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }

    </script>

    <script type="text/javascript">
        
           function offline_transaction(amount_required,reg_id){
               var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var uri = '../epay/offlinepaymentnewehmsonly.php';
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Sponsor_ID:Sponsor_ID},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                 //   alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
        
        
        
        
        function Make_Payments(){
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
           
          var  amount_required = document.getElementById('grand_total_txt').value;

            //var Confirm_Message = confirm("Are you sure you want to make payment?");
           // if(Confirm_Message == true){
                //alert(amount_required)
                offline_transaction(amount_required,Registration_ID);
                //document.location = 'Direct_Cash_Make_Payment_Inpatient.php?Registration_ID='+Registration_ID+'&Sponsor_ID='+Sponsor_ID;
           // }
            
            /*if(window.XMLHttpRequest){
                myObjectMakePayment = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectMakePayment = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectMakePayment.overrideMimeType('text/xml');
            }

            myObjectMakePayment.onreadystatechange = function (){
                data123 = myObjectMakePayment.responseText;
                if (myObjectMakePayment.readyState == 4) {
                    alert(data123)
                }
            }; //specify name of function that will handle server response........
            
            myObjectMakePayment.open('GET','Direct_Cash_Make_Payment.php?Registration_ID='+Registration_ID+'&Sponsor_ID='+Sponsor_ID,true);
            myObjectMakePayment.send();*/
        }
    </script>

    <script type="text/javascript">
        function Make_Payment_Warning(){
            alert("No Items found")
        }
        
        
            function create_epayment_mobile_card_bill(){
                                
                                
                               
                                 var clinic_location_id = document.getElementById("clinic_location_id").value;
                                   if (clinic_location_id == '') {
                                    alert("Please select clinic location to continue");
                                    exit;
                                }
                                var finance_department_id = document.getElementById("finance_department_id").value;
                                if (finance_department_id == '') {
                                    alert("Please select working_department to continue");
                                    exit;
                                }
                                var Clinic_ID = document.getElementById("Clinic_ID").value;
                                      if(Clinic_ID==''|| Clinic_ID==null){
                                        alert("Please Select clinic to continue")
                                       exit;  
                                    }
                                
                                    var Registration_ID='<?php echo $Registration_ID; ?>';
                                    var Sponsor_ID='<?php echo $Sponsor_ID; ?>';
                                   
                                    if(confirm("Are You sure you want to go to Mobile/Card Payment")){
                                        $.ajax({
                                            type:'GET',
                                            url:'ajax_create_epayment_mobile_card_bill_cash_deposit_inpatient.php',
                                            data:{Registration_ID:Registration_ID,Sponsor_ID:Sponsor_ID,finance_department_id:finance_department_id},
                                            success:function(data){
                                                if(data=="fail"){
                                                    alert(data+"Process Fail...Please Try Again");
                                                }else{
                                                   var Payment_Cache_ID=data;
                                                    document.location = "./patient_sent_to_cashier_payment.php?Registration_ID=<?php echo $Registration_ID ?>&Payment_Cache_ID="+Payment_Cache_ID;
                                                }
                                            }
                                        });
                                    }
                            }
    </script>
<?php
    include("./includes/footer.php");
?>