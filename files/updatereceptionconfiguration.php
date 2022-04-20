<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<a href='receptionconfigurationpage.php?ReceptionConfiguration=ReceptionConfigurationThisPage' class='art-button-green'>BACK</a>

<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    $select_system_configuration = mysqli_query($conn,"select Allow_Direct_Departmental_Payments_Auto_Billing,Default_Patient_Direction, Require_Patient_Phone_Number, Registration_Mode, Include_Exemption_Sponsors_In_Normal_Registration, Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments, Reception_Must_Fill_Exemption_Missing_Information, Allow_Pharmacy_To_Dispense_Multiple_Patients,  Pharmacy_Additional_Instruction, Allow_Aditional_Instructions_On_Pharmacy_Menu, 
                                                Allow_Pharmaceutical_Dispensing_Above_Actual_Balance, All_Items_Payments, Pharmacy_Patient_List_Displays_Only_Current_Checked_In, Allow_Cashier_To_Approve_Pharmaceutical, Filtered_Pharmacy_Patient_List, Reception_Picking_Items, Direct_departmental_payments, 
                                                Show_Pharmaceutical_Before_Payments, Display_Send_To_Cashier_Button,Enable_Add_More_Medication, Enable_Inpatient_To_Check_Again, Allow_Direct_Cash_Outpatient,Change_Medication_Location,Dispense_Credit_Patients_after_24_hrs from tbl_system_configuration where Branch_ID = '$Branch_ID'");
    while($row = mysqli_fetch_array($select_system_configuration)){
        $Reception_Picking_Items = $row['Reception_Picking_Items'];
        $Direct_departmental_payments = $row['Direct_departmental_payments'];
        $Show_Pharmaceutical_Before_Payments = $row['Show_Pharmaceutical_Before_Payments'];
        $Display_Send_To_Cashier_Button = $row['Display_Send_To_Cashier_Button'];
    	$Enable_Add_More_Medication = $row['Enable_Add_More_Medication'];
        $Enable_Inpatient_To_Check_Again = $row['Enable_Inpatient_To_Check_Again'];
        $Allow_Direct_Cash_Outpatient = $row['Allow_Direct_Cash_Outpatient'];
        $Change_Medication_Location = $row['Change_Medication_Location'];
        $Filtered_Pharmacy_Patient_List = $row['Filtered_Pharmacy_Patient_List'];
        $Allow_Cashier_To_Approve_Pharmaceutical = $row['Allow_Cashier_To_Approve_Pharmaceutical'];
        $Pharmacy_Patient_List_Displays_Only_Current_Checked_In = $row['Pharmacy_Patient_List_Displays_Only_Current_Checked_In'];
        $All_Items_Payments = $row['All_Items_Payments'];
    	$Allow_Pharmaceutical_Dispensing_Above_Actual_Balance = $row['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'];
    	$Allow_Aditional_Instructions_On_Pharmacy_Menu = $row['Allow_Aditional_Instructions_On_Pharmacy_Menu'];
        $Pharmacy_Additional_Instruction = $row['Pharmacy_Additional_Instruction'];
        $Allow_Pharmacy_To_Dispense_Multiple_Patients = $row['Allow_Pharmacy_To_Dispense_Multiple_Patients'];
        $Reception_Must_Fill_Exemption_Missing_Information = $row['Reception_Must_Fill_Exemption_Missing_Information'];
        $Dispense_Credit_Patients_after_24_hrs=$row['Dispense_Credit_Patients_after_24_hrs'];
        $Allow_Direct_Departmental_Payments_Auto_Billing = $row['Allow_Direct_Departmental_Payments_Auto_Billing'];
        $Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments = $row['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments'];
        $Include_Exemption_Sponsors_In_Normal_Registration = $row['Include_Exemption_Sponsors_In_Normal_Registration'];
        $Registration_Mode = $row['Registration_Mode'];
        $Require_Patient_Phone_Number = $row['Require_Patient_Phone_Number'];
        $Default_Patient_Direction = $row['Default_Patient_Direction'];
    }
?>


<?php
    if(isset($_POST['update_link'])){
        $Pharmacy_Additional_Instruction = $_POST['Pharmacy_Additional_Instruction'];
        $Registration_Mode = $_POST['Registration_Mode'];
        $Default_Patient_Direction = $_POST['Default_Patient_Direction'];

        if(isset($_POST['Reception_Configuration'])){
            $Reception_Picking_Items = 'yes';
        }else{
            $Reception_Picking_Items = 'no';
        }

        if(isset($_POST['Direct_Departmental_Configuration'])){
            $Direct_Departmental_Configuration = 'yes';
        }else{
            $Direct_Departmental_Configuration = 'no';
        }
        
        if(isset($_POST['Medication_Display'])){
            $Medication_Display = 'yes';
        }else{
            $Medication_Display = 'no';
        }
        
	
        if(isset($_POST['Enable_Add_More_Medication'])){
            $Enable_Add_More_Medication = 'yes';
        }else{
            $Enable_Add_More_Medication = 'no';
        }
	
        if(isset($_POST['Display_Send_To_Cashier_Button'])){
            $Display_Send_To_Cashier_Button = 'yes';
        }else{
            $Display_Send_To_Cashier_Button = 'no';
        }

        if(isset($_POST['Enable_Inpatient_To_Check_Again'])){
            $Enable_Inpatient_To_Check_Again = 'yes';
        }else{
            $Enable_Inpatient_To_Check_Again = 'no';
        }

        if(isset($_POST['Allow_Direct_Cash_Outpatient'])){
            $Allow_Direct_Cash_Outpatient = $_POST['Allow_Direct_Cash_Outpatient'];
        }else{
            $Allow_Direct_Cash_Outpatient = 'no';
        }
        
        if(isset($_POST['Change_Medication_Location'])){
            $Change_Medication_Location = $_POST['Change_Medication_Location'];
        }else{
            $Change_Medication_Location = 'no';
        }
        
        if(isset($_POST['Filtered_Pharmacy_Patient_List'])){
            $Filtered_Pharmacy_Patient_List = $_POST['Filtered_Pharmacy_Patient_List'];
        }else{
            $Filtered_Pharmacy_Patient_List = 'no';
        }

        if(isset($_POST['Allow_Cashier_To_Approve_Pharmaceutical'])){
            $Allow_Cashier_To_Approve_Pharmaceutical = $_POST['Allow_Cashier_To_Approve_Pharmaceutical'];
        }else{
            $Allow_Cashier_To_Approve_Pharmaceutical = 'no';
        }

        if(isset($_POST['Pharmacy_Patient_List_Displays_Only_Current_Checked_In'])){
            $Pharmacy_Patient_List_Displays_Only_Current_Checked_In = $_POST['Pharmacy_Patient_List_Displays_Only_Current_Checked_In'];
        }else{
            $Pharmacy_Patient_List_Displays_Only_Current_Checked_In = 'no';
        }

        if(isset($_POST['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'])){
            $Allow_Pharmaceutical_Dispensing_Above_Actual_Balance = $_POST['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'];
        }else{
            $Allow_Pharmaceutical_Dispensing_Above_Actual_Balance = 'no';
        }
	
        if(isset($_POST['All_Items_Payments'])){
            $All_Items_Payments = $_POST['All_Items_Payments'];
        }else{
            $All_Items_Payments = 'no';
        }
        
        if(isset($_POST['Allow_Aditional_Instructions_On_Pharmacy_Menu'])){
            $Allow_Aditional_Instructions_On_Pharmacy_Menu = $_POST['Allow_Aditional_Instructions_On_Pharmacy_Menu'];
        }else{
            $Allow_Aditional_Instructions_On_Pharmacy_Menu = 'no';
        }

        if(isset($_POST['Allow_Pharmacy_To_Dispense_Multiple_Patients'])){
            $Allow_Pharmacy_To_Dispense_Multiple_Patients = $_POST['Allow_Pharmacy_To_Dispense_Multiple_Patients'];
        }else{
            $Allow_Pharmacy_To_Dispense_Multiple_Patients = 'no';
        }

        if(isset($_POST['Reception_Must_Fill_Exemption_Missing_Information'])){
            $Reception_Must_Fill_Exemption_Missing_Information = $_POST['Reception_Must_Fill_Exemption_Missing_Information'];
        }else{
            $Reception_Must_Fill_Exemption_Missing_Information = 'no';
        }
	
        if(isset($_POST['Dispense_Credit_Patients_after_24_hrs'])){
            $Dispense_Credit_Patients_after_24_hrs = $_POST['Dispense_Credit_Patients_after_24_hrs'];
        }else{
            $Dispense_Credit_Patients_after_24_hrs = 'no';
        }

        if(isset($_POST['Allow_Direct_Departmental_Payments_Auto_Billing'])){
            $Allow_Direct_Departmental_Payments_Auto_Billing = $_POST['Allow_Direct_Departmental_Payments_Auto_Billing'];
        }else{
            $Allow_Direct_Departmental_Payments_Auto_Billing = 'no';
        }

        if(isset($_POST['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments'])){
            $Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments = $_POST['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments'];
        }else{
            $Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments = 'no';
        }

        if(isset($_POST['Include_Exemption_Sponsors_In_Normal_Registration'])){
            $Include_Exemption_Sponsors_In_Normal_Registration = $_POST['Include_Exemption_Sponsors_In_Normal_Registration'];
        }else{
            $Include_Exemption_Sponsors_In_Normal_Registration = 'no';
        }
        
        if(isset($_POST['Require_Patient_Phone_Number'])){
            $Require_Patient_Phone_Number = $_POST['Require_Patient_Phone_Number'];
        }else{
            $Require_Patient_Phone_Number = 'no';
        }


        $update_query = mysqli_query($conn,"update tbl_system_configuration set Reception_Picking_Items = '$Reception_Picking_Items', 
                                        Direct_departmental_payments = '$Direct_Departmental_Configuration', 
                                        Show_Pharmaceutical_Before_Payments = '$Medication_Display',
                                        Display_Send_To_Cashier_Button = '$Display_Send_To_Cashier_Button',
                                        Enable_Add_More_Medication = '$Enable_Add_More_Medication',
                                        Enable_Inpatient_To_Check_Again = '$Enable_Inpatient_To_Check_Again',
                                        Allow_Direct_Cash_Outpatient = '$Allow_Direct_Cash_Outpatient',
                                        Change_Medication_Location = '$Change_Medication_Location',
                                        Filtered_Pharmacy_Patient_List = '$Filtered_Pharmacy_Patient_List',
                                        Allow_Cashier_To_Approve_Pharmaceutical = '$Allow_Cashier_To_Approve_Pharmaceutical',
                                        Pharmacy_Patient_List_Displays_Only_Current_Checked_In = '$Pharmacy_Patient_List_Displays_Only_Current_Checked_In',
                                        All_Items_Payments = '$All_Items_Payments',
					Allow_Pharmaceutical_Dispensing_Above_Actual_Balance = '$Allow_Pharmaceutical_Dispensing_Above_Actual_Balance',
					Allow_Aditional_Instructions_On_Pharmacy_Menu = '$Allow_Aditional_Instructions_On_Pharmacy_Menu',
                                        Pharmacy_Additional_Instruction = '$Pharmacy_Additional_Instruction',
                                        Allow_Pharmacy_To_Dispense_Multiple_Patients = '$Allow_Pharmacy_To_Dispense_Multiple_Patients',
                                        Reception_Must_Fill_Exemption_Missing_Information = '$Reception_Must_Fill_Exemption_Missing_Information',
                                        Dispense_Credit_Patients_after_24_hrs='$Dispense_Credit_Patients_after_24_hrs',
                                        Allow_Direct_Departmental_Payments_Auto_Billing = '$Allow_Direct_Departmental_Payments_Auto_Billing',
                                        Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments = '$Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments',
                                        Include_Exemption_Sponsors_In_Normal_Registration = '$Include_Exemption_Sponsors_In_Normal_Registration',
                                        Registration_Mode = '$Registration_Mode',
                                        Require_Patient_Phone_Number = '$Require_Patient_Phone_Number',
                                        Default_Patient_Direction = '$Default_Patient_Direction'
                                        where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
        header("Location: ./receptionconfigurationpage.php?ReceptionConfiguration=ReceptionConfigurationThisPage");
        
    }


?>

<script type="text/javascript">
    //function enable_button(status)
    //{
    //status=!status;
    //document.myForm.update_link.visible = status;
    //}
     
    function ShoElement(){
        document.getElementById("update_link").style.visibility = 'visible';
    }
</script>

<br/><br/>
<fieldset>  
            <legend align=center><b>RECEPTION CONFIGURATION</b></legend>
    <form action='#' method='post' name='myForm' id='myForm' name='myForm' enctype="multipart/form-data"> 
        <center><br/>
            <table width = "100%">
                <tr>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Reception_Configuration' id='Reception_Configuration' onclick='ShoElement()' value='yes' <?php if(strtolower($Reception_Picking_Items) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Reception_Configuration">Allow Receptionist to pick items</label>
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Direct_Departmental_Configuration' id='Direct_Departmental_Configuration' onclick='ShoElement()' value='yes' <?php if(strtolower($Direct_departmental_payments) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Direct_Departmental_Configuration">Allow Direct Departmental Payment</label>
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Medication_Display' id='Medication_Display' onclick='ShoElement()' value='yes' <?php if(strtolower($Show_Pharmaceutical_Before_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Medication_Display">Show Medication Before Payment Process</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                     <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Enable_Add_More_Medication' id='Enable_Add_More_Medication' onclick='ShoElement()' value='yes' <?php if(strtolower($Enable_Add_More_Medication) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Enable_Add_More_Medication">Enable Pharmacy To Add More Medication(s)</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Display_Send_To_Cashier_Button' id='Display_Send_To_Cashier_Button' onclick='ShoElement()' value='yes' <?php if(strtolower($Display_Send_To_Cashier_Button) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Display_Send_To_Cashier_Button">Display Send To Cashier Button</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                     <td style='text-align: left; color:black; border:2px solid #ccc;display: none'>
                         <input type='checkbox' name='Enable_Inpatient_To_Check_Again'id='Enable_Inpatient_To_Check_Again' onclick='ShoElement()' value='no' <?php if(strtolower($Enable_Inpatient_To_Check_Again) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Enable_Inpatient_To_Check_Again">Patient Allowed To Check In Again while Admitted</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                     <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Allow_Direct_Cash_Outpatient' id='Allow_Direct_Cash_Outpatient' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Direct_Cash_Outpatient) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Allow_Direct_Cash_Outpatient">Allow Direct Cash Outpatient</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                     <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Change_Medication_Location' id='Change_Medication_Location' onclick='ShoElement()' value='yes' <?php if(strtolower($Change_Medication_Location) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Change_Medication_Location">Allow Pharmacist To Change Medication Location</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Filtered_Pharmacy_Patient_List' id='Filtered_Pharmacy_Patient_List' onclick='ShoElement()' value='yes' <?php if(strtolower($Filtered_Pharmacy_Patient_List) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Filtered_Pharmacy_Patient_List">Pharmacy List Starts With Filtered Dates</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Allow_Cashier_To_Approve_Pharmaceutical' id='Allow_Cashier_To_Approve_Pharmaceutical' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Cashier_To_Approve_Pharmaceutical) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Allow_Cashier_To_Approve_Pharmaceutical">Allow Cashier To Approve Credit Pharmaceutical Transactions</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                     <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Pharmacy_Patient_List_Displays_Only_Current_Checked_In' id='Pharmacy_Patient_List_Displays_Only_Current_Checked_In' onclick='ShoElement()' value='yes' <?php if(strtolower($Pharmacy_Patient_List_Displays_Only_Current_Checked_In) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Pharmacy_Patient_List_Displays_Only_Current_Checked_In">Pharmacy Patients Lists Display Only Current Checked In Credit and All Cash Patients</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                     <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='All_Items_Payments' id='All_Items_Payments' onclick='ShoElement()' value='yes' <?php if(strtolower($All_Items_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="All_Items_Payments">Allow All Items Payment</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <?php $Title = 'This setting will add more instructions on pharmacy menu&#xA;e.g Outpatient Cash (Approval & Dispense) in which (Approval & Dispense) is additional instructuon'; ?>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Allow_Pharmaceutical_Dispensing_Above_Actual_Balance' id='Allow_Pharmaceutical_Dispensing_Above_Actual_Balance' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Pharmaceutical_Dispensing_Above_Actual_Balance) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Allow_Pharmaceutical_Dispensing_Above_Actual_Balance">Allow Pharmaceutical Dispensing Above the Actual Balance</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
		    <?php $Title = 'This setting will add more instructions on pharmacy menu&#xA;e.g Outpatient Cash (Approval & Dispense) in which (Approval & Dispense) is additional instructuon'; ?>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox'  title="<?php echo $Title; ?>" name='Allow_Aditional_Instructions_On_Pharmacy_Menu' id='Allow_Aditional_Instructions_On_Pharmacy_Menu' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Aditional_Instructions_On_Pharmacy_Menu) =='yes'){ echo 'checked="checked"'; }?>>
                        <label title="<?php echo $Title; ?>" for="Allow_Aditional_Instructions_On_Pharmacy_Menu">Allow Aditional Instructions On Pharmacy Menu</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="text-align: right;">
                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: right;"><b>Pharmacy Additional Menu Instructuon</b></td>
                                <td>
                                    <input type="text" title="<?php echo $Title; ?>" name="Pharmacy_Additional_Instruction" id="Pharmacy_Additional_Instruction" value="<?php echo $Pharmacy_Additional_Instruction; ?>" placeholder='Pharmacy Additional Instructions' autocomplete="off" oninput="ShoElement()" onkeypress="ShoElement()" maxlength='25'>
                                </td>
                            </tr>
                        </table>
                    </td>
		</tr>
        <tr>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Allow_Pharmacy_To_Dispense_Multiple_Patients' id='Allow_Pharmacy_To_Dispense_Multiple_Patients' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Pharmacy_To_Dispense_Multiple_Patients) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Allow_Pharmacy_To_Dispense_Multiple_Patients">Allow Pharmacy To Dispense Multiple Patients</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Reception_Must_Fill_Exemption_Missing_Information' id='Reception_Must_Fill_Exemption_Missing_Information' onclick='ShoElement()' value='yes' <?php if(strtolower($Reception_Must_Fill_Exemption_Missing_Information) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Reception_Must_Fill_Exemption_Missing_Information">Reception must fill exemption missing information during Check-In process</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
            
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Dispense_Credit_Patients_after_24_hrs' id='Dispense_Credit_Patients_after_24_hrs' onclick='ShoElement()' value='yes' <?php if(strtolower($Dispense_Credit_Patients_after_24_hrs) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Dispense_Credit_Patients_after_24_hrs">Allow Pharmacy To Dispense Credit Patients after 24 hrs</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
        <tr>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Allow_Direct_Departmental_Payments_Auto_Billing' id='Allow_Direct_Departmental_Payments_Auto_Billing' onclick='ShoElement()' value='yes' <?php if(strtolower($Allow_Direct_Departmental_Payments_Auto_Billing) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Allow_Direct_Departmental_Payments_Auto_Billing">Allow Direct Departmental Payments Auto Billing</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments' id='Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments' onclick='ShoElement()' value='yes' <?php if(strtolower($Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments">Display Cash Bill Button On Inpatient Departmental Payments</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Include_Exemption_Sponsors_In_Normal_Registration' id='Include_Exemption_Sponsors_In_Normal_Registration' onclick='ShoElement()' value='yes' <?php if(strtolower($Include_Exemption_Sponsors_In_Normal_Registration) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Include_Exemption_Sponsors_In_Normal_Registration">Include Exemption Sponsors In Normal Registration Pages</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <table width="100%">
                            <tr>
                                <td style="text-align: right;" width="30%">Registration Mode</td>
                                <td>
                                    <select name="Registration_Mode" id="Registration_Mode" onchange="ShoElement()">
                                        <option <?php if(strtolower($Registration_Mode) == 'receiving patient names together'){ echo "selected='selected'"; } ?>>Receiving Patient Names Together</option>                                        
                                        <option <?php if(strtolower($Registration_Mode) == 'receiving patient names separately - middle name mandatory'){ echo "selected='selected'"; } ?>>Receiving Patient Names Separately - Middle Name Mandatory</option>                                        
                                        <option <?php if(strtolower($Registration_Mode) == 'receiving patient names separately - middle name not mandatory'){ echo "selected='selected'"; } ?>>Receiving Patient Names Separately - Middle Name Not Mandatory</option>                                        
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <input type='checkbox' name='Require_Patient_Phone_Number' id='Require_Patient_Phone_Number' onclick='ShoElement()' value='yes' <?php if(strtolower($Require_Patient_Phone_Number) =='yes'){ echo 'checked="checked"'; }?>>
                        <label for="Require_Patient_Phone_Number">Patient Phone Number is Mandatory In Registration Process</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;'>
                        <table width="100%">
                            <tr>
                                <td style="text-align: right;" width="60%">Reception Default Patient Direction</td>
                                <td>
                                    <select name="Default_Patient_Direction" id="Default_Patient_Direction" onchange="ShoElement()">
                                        <option <?php if(strtolower($Default_Patient_Direction) == 'direct to doctor'){ echo "selected='selected'"; } ?>>Direct To Doctor</option>                                        
                                        <option <?php if(strtolower($Default_Patient_Direction) == 'direct to clinic'){ echo "selected='selected'"; } ?>>Direct To Clinic</option>                                        
                                        <option <?php if(strtolower($Default_Patient_Direction) == 'none'){ echo "selected='selected'"; } ?>>None</option>                                        
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;" colspan="3">
                        <input type='submit' id='update_link' name='update_link' value='SAVE CHANGES' class='art-button-green' style='visibility: hidden;'>
                    </td>
                </tr>
            </table>
        </center>
    </form>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>