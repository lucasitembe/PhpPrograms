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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    $select_system_configuration = mysqli_query($conn,"select Default_Patient_Direction, Require_Patient_Phone_Number, Registration_Mode, Include_Exemption_Sponsors_In_Normal_Registration, Use_managament_approval_bill, Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments, Allow_Direct_Departmental_Payments_Auto_Billing, Reception_Must_Fill_Exemption_Missing_Information, Allow_Pharmacy_To_Dispense_Multiple_Patients, 
                                                Pharmacy_Additional_Instruction, Allow_Aditional_Instructions_On_Pharmacy_Menu, Allow_Pharmaceutical_Dispensing_Above_Actual_Balance, All_Items_Payments, Pharmacy_Patient_List_Displays_Only_Current_Checked_In, 
                                                Allow_Cashier_To_Approve_Pharmaceutical, Filtered_Pharmacy_Patient_List, Reception_Picking_Items, Direct_departmental_payments, Show_Pharmaceutical_Before_Payments,Display_Send_To_Cashier_Button, 
                                                Enable_Add_More_Medication, Enable_Inpatient_To_Check_Again, Allow_Direct_Cash_Outpatient, Change_Medication_Location,Dispense_Credit_Patients_after_24_hrs from tbl_system_configuration where Branch_ID = '$Branch_ID'");
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
        $Allow_Pharmaceutical_Dispensing_Above_Actual_Balance = $row['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'];
    	$All_Items_Payments = $row['All_Items_Payments'];
    	$Allow_Aditional_Instructions_On_Pharmacy_Menu = $row['Allow_Aditional_Instructions_On_Pharmacy_Menu'];
        $Pharmacy_Additional_Instruction = $row['Pharmacy_Additional_Instruction'];
        $Allow_Pharmacy_To_Dispense_Multiple_Patients = $row['Allow_Pharmacy_To_Dispense_Multiple_Patients'];
        $Reception_Must_Fill_Exemption_Missing_Information = $row['Reception_Must_Fill_Exemption_Missing_Information'];
        $Dispense_Credit_Patients_after_24_hrs = $row['Dispense_Credit_Patients_after_24_hrs'];
        $Allow_Direct_Departmental_Payments_Auto_Billing = $row['Allow_Direct_Departmental_Payments_Auto_Billing'];
        $Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments = $row['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments'];
        $Include_Exemption_Sponsors_In_Normal_Registration = $row['Include_Exemption_Sponsors_In_Normal_Registration'];
        $Registration_Mode = $row['Registration_Mode'];
        $Require_Patient_Phone_Number = $row['Require_Patient_Phone_Number'];
        $Default_Patient_Direction = $row['Default_Patient_Direction'];
        $Use_managament_approval_bill = $row['Use_managament_approval_bill'];
    }
?>
<br/><br/>
<fieldset>  
            <legend align=center><b>RECEPTION, PHARMACY & REVENUE CENTER CONFIGURATION</b></legend>
        <center><br/><table width = "100%">
            <tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Reception_Configuration' id='Reception_Configuration' value='yes' disabled='disabled' <?php if(strtolower($Reception_Picking_Items) =='yes'){ echo 'checked="checked"'; }?>>
                    Allow Receptionist to pick items
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    
                    <?php
                        if(strtolower($Direct_departmental_payments) == 'yes'){
                            $Status = 'Allow direct departmental payments';
                        }else{
                            $Status = 'Direct departmental payments not allowed';
                        }
                    
                    ?>
                    <input type='checkbox' name='Reception_Configuration' id='Reception_Configuration' value='yes' disabled='disabled' <?php if(strtolower($Direct_departmental_payments) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo $Status; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    
                    <?php
                        if(strtolower($Show_Pharmaceutical_Before_Payments) == 'yes'){
                            $Status = 'Show medication before payment process';
                        }else{ 
                            $Status = 'Show medication before payment process';
                        }
                    
                    ?>
                    <input type='checkbox' name='Medication_Display' id='Medication_Display' value='yes' disabled='disabled' <?php if(strtolower($Show_Pharmaceutical_Before_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo $Status; ?>
                </td>
            </tr>
            <tr>
                 <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    
                    <input type='checkbox' name='Enable_Add_More_Medication' id='Enable_Add_More_Medication' value='yes' disabled='disabled' <?php if(strtolower($Enable_Add_More_Medication) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Enable Pharmacy To Add More Medication(s)"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    
                    <?php
                        if(strtolower($Display_Send_To_Cashier_Button) == 'yes'){
                            $Status = 'Display Send To Cashier Button';
                        }else{ 
                            $Status = 'Display Send To Cashier Button';
                        }
                    
                    ?>
                    <input type='checkbox' name='Medication_Display' id='Medication_Display' value='yes' disabled='disabled' <?php if(strtolower($Display_Send_To_Cashier_Button) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo $Status; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    
                    <?php
                        if(strtolower($Enable_Inpatient_To_Check_Again) == 'yes'){
                            $Status = 'Patient Allowed To Check In Again while Admitted';
                        }else{
                            $Status = 'Patient Allowed To Check In Again while Admitted';
                        }
                    
                    ?>
                    <input type='checkbox' name='Medication_Display' id='Medication_Display' value='yes' disabled='disabled' <?php if(strtolower($Enable_Inpatient_To_Check_Again) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo $Status; ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Direct_Cash_Outpatient' id='Allow_Direct_Cash_Outpatient' value='yes' disabled='disabled' <?php if(strtolower($Allow_Direct_Cash_Outpatient) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Direct Cash Outpatient"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Change_Medication_Location' id='Change_Medication_Location' value='yes' disabled='disabled' <?php if(strtolower($Change_Medication_Location) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Pharmacist To Change Medication Location"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Filtered_Pharmacy_Patient_List' id='Filtered_Pharmacy_Patient_List' value='yes' disabled='disabled' <?php if(strtolower($Filtered_Pharmacy_Patient_List) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Pharmacy List Starts With Filtered Dates"; ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Cashier_To_Approve_Pharmaceutical' id='Allow_Cashier_To_Approve_Pharmaceutical' value='yes' disabled='disabled' <?php if(strtolower($Allow_Cashier_To_Approve_Pharmaceutical) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Cashier To Approve Credit Pharmaceutical Transactions"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Pharmacy_Patient_List_Displays_Only_Current_Checked_In' id='Pharmacy_Patient_List_Displays_Only_Current_Checked_In' value='yes' disabled='disabled' <?php if(strtolower($Pharmacy_Patient_List_Displays_Only_Current_Checked_In) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Pharmacy Patients Lists Display Only Current Checked In Credit and All Cash Patients"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='All_Items_Payments' id='All_Items_Payments' value='yes' disabled='disabled' <?php if(strtolower($All_Items_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow All Items Payment"; ?>
                </td>
            </tr>
            <tr>
                <?php $Title = 'This setting will add more instructions on pharmacy menu&#xA;e.g Outpatient Cash (Approval & Dispense) in which (Approval & Dispense) is additional instructuon'; ?>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Pharmaceutical_Dispensing_Above_Actual_Balance' id='Allow_Pharmaceutical_Dispensing_Above_Actual_Balance' value='yes' disabled='disabled' <?php if(strtolower($Allow_Pharmaceutical_Dispensing_Above_Actual_Balance) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Pharmaceutical Dispensing Above the Actual Balance"; ?>
                </td>
		        <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Aditional_Instructions_On_Pharmacy_Menu' id='Allow_Aditional_Instructions_On_Pharmacy_Menu' value='yes' disabled='disabled' <?php if(strtolower($Allow_Aditional_Instructions_On_Pharmacy_Menu) =='yes'){ echo 'checked="checked"'; }?>>
                    <label title="<?php echo $Title; ?>"><?php echo "Allow Aditional Instructions On Pharmacy Menu"; ?></label>
                </td>
                <td style="text-align: right;">
                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: right;"><b>Pharmacy Additional Menu Instructuon</b></td>
                                <td>
                                    <input type="text" title="<?php echo $Title; ?>" name="Pharmacy_Additional_Instruction" id="Pharmacy_Additional_Instruction" value="<?php echo $Pharmacy_Additional_Instruction; ?>" placeholder='Pharmacy Additional Instructions' readonly='readonly'>
                                </td>
                            </tr>
                        </table>
                    </td>
		</tr>
		<tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Pharmacy_To_Dispense_Multiple_Patients' id='Allow_Pharmacy_To_Dispense_Multiple_Patients' value='yes' disabled='disabled' <?php if(strtolower($Allow_Pharmacy_To_Dispense_Multiple_Patients) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Pharmacy To Dispense Multiple Patients"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Reception_Must_Fill_Exemption_Missing_Information' id='Reception_Must_Fill_Exemption_Missing_Information' value='yes' disabled='disabled' <?php if(strtolower($Reception_Must_Fill_Exemption_Missing_Information) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Reception must fill exemption missing information during Check-In process"; ?>
                </td>
		
		 <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Dispense_Credit_Patients_after_24_hrs' id='Dispense_Credit_Patients_after_24_hrs' value='yes' disabled='disabled' <?php if(strtolower($Dispense_Credit_Patients_after_24_hrs) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Pharmacy To Dispense Credit medication after 24 hrs"; ?>
                </td>
            </tr>
        <tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Allow_Direct_Departmental_Payments_Auto_Billing' id='Allow_Direct_Departmental_Payments_Auto_Billing' value='yes' disabled='disabled' <?php if(strtolower($Allow_Direct_Departmental_Payments_Auto_Billing) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Allow Direct Departmental Payments Auto Billing"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments' id='Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments' value='yes' disabled='disabled' <?php if(strtolower($Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Display Cash Bill Button On Inpatient Departmental Payments"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Include_Exemption_Sponsors_In_Normal_Registration' id='Include_Exemption_Sponsors_In_Normal_Registration' value='yes' disabled='disabled' <?php if(strtolower($Include_Exemption_Sponsors_In_Normal_Registration) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Include Exemption Sponsors In Normal Registration Pages"; ?>
                </td>
            </tr>
        <tr>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <table width="100%">
                        <tr>
                            <td style="text-align: right;" width="30%">Registration Mode</td>
                            <td><b><?php echo ucwords(strtolower($Registration_Mode)); ?></b></td>
                        </tr>
                    </table>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <input type='checkbox' name='Require_Patient_Phone_Number' id='Require_Patient_Phone_Number' value='yes' disabled='disabled' <?php if(strtolower($Require_Patient_Phone_Number) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Patient Phone Number is Mandatory In Registration Process"; ?>
                </td>
                <td style='text-align: left; color:black; border:2px solid #ccc;'>
                    <table width="100%">
                        <tr>
                            <td style="text-align: right;" width="60%">Reception Default Patient Direction</td>
                            <td><b><?php echo ucwords(strtolower($Default_Patient_Direction)); ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                <input type='checkbox' name='Use_managament_approval_bill' id='Use_managament_approval_bill' value='yes' disabled='disabled' <?php if(strtolower($Use_managament_approval_bill) =='yes'){ echo 'checked="checked"'; }?>>
                    <?php echo "Use managament approval bill"; ?>
                </td>
            </tr>
        <tr>
                <td style="text-align: right;" colspan="3">
                    <a href='updatereceptionconfiguration.php?UpdateReceptionConf=UpdateReceptionConfThisPage' class='art-button-green' title='Update Configuration'>Change Settings</a>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>