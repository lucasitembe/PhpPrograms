<?php 
// ================+++ WAGONJWA  LIPA MADENI FILE By Mugaa+++===========================
include("./includes/connection.php");
session_start();
if(isset($_POST['debt_dialogs'])){
    if (isset($_POST['patient_debt'])) {
        $patient_debt = $_POST['patient_debt'];
    } else {
        $patient_debt = '0';
    }

    if(isset($_POST['Debt_ID'])){
        $Debt_ID = $_POST['Debt_ID'];
    }else{
        $Debt_ID=0;
    }

    ?>
    <div class="row col-md-12">
    <h4 style="color:red;"> This patient has debt of <?php echo number_format($patient_debt)."/="; ?>.</h4><br>
        Click Cancel button  if the patient deny debt. Or Click Process button to extend debt.
    </div>
    <br><br>
    <div class="col-md-12" id="send_data">
        <input type="text" id="Debt_ID" hidden value="<?= $Debt_ID ?>">
        <input type="button" id="send_data" Value="PROCESS" class="art-button-green pull-right hide"  onclick="process_patient_debt(<?= $Debt_ID ?>)">
        <input type="button" id="send_data" Value="CANCEL" class="art-button-green pull-right" onclick="close_dialog_button()">
    </div>
    <?php 
    }

    if(isset($_POST['process_debtg'])){
        $Debt_ID = $_POST['Debt_ID'];

        $update_debt = mysqli_query($conn, "UPDATE tbl_patient_debt SET Debt_status='Debt extended' WHERE Debt_ID='$Debt_ID'") or die(mysqli_error($conn));
        if($update_debt){
            echo "Debt extended";
        }else{
            echo "Failed to update";
        }
    }
?>
<?php 

if(isset($_POST['debt_dialog'])){
    if (isset($_POST['patient_debt'])) {
        $patient_debt = $_POST['patient_debt'];
    } else {
        $patient_debt = '0';
    }

    if(isset($_POST['Debt_ID'])){
        $Debt_ID = $_POST['Debt_ID'];
    }else{
        $Debt_ID=0;
    }

    //echo $patient_debt;
    ?>
    <div class="row col-md-12">
    <h4 style="color:red;"> This patient has debt of <?php echo number_format($patient_debt)."/="; ?>.</h4><br>
        Click send to cash deposit button,  if the patient pay whole debt. Or<br/> Click send to social walfare button, if the patient can't afford to pay whole debt  right now.
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    </div>
    
    <div class=" col-md-12" id="send_data">
        <center>
            <input type="text" id="Debt_ID" hidden value="<?= $Debt_ID ?>">
            <input type="button" id="send_data" Value="SEND TO SOCIAL WALFARE " class="art-button-green pull-right"  onclick="send_to_socialwalfare(<?= $Debt_ID ?>, <?= $patient_debt?>)">
            <input type="button" id="send_data" Value="SEND TO CASH DEPOSIT" class="art-button-green pull-right" onclick="send_to_cash_deposit(<?= $Debt_ID ?>)">
            <input type="button" id="send_data" Value="PROCESS" class="art-button-green pull-right hide"  onclick="process_patient_debt(<?= $Debt_ID ?>)">

        </center>
    </div>
    <?php 
}

    if(isset($_POST['debt_cash_deposit'])){
        if (isset($_POST['Patient_debt'])) {
            $Patient_debt = $_POST['Patient_debt'];
        } else {
            $Patient_debt = '0';
        }
    
        if(isset($_POST['Debt_ID'])){
            $Debt_ID = $_POST['Debt_ID'];
        }else{
            $Debt_ID=0;
        }
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

        if(isset($_POST['Debt_social_ID'])){
            $Debt_social_ID = $_POST['Debt_social_ID'];
            $Reason_to_extend = $_POST['Reason_to_extend'];

            $select_deni = mysqli_query($conn, "SELECT Debt_social_ID FROM tbl_social_reduce_debt WHERE Debt_social_ID='$Debt_social_ID'  ") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_deni)>0){
                echo "Patient arleady sent to cashier";
            }else{
                $lipia_deni_kidogo = mysqli_query($conn, "INSERT INTO tbl_social_reduce_debt(Debt_social_ID, Reason_to_extend, Amount_deposited, Extended_by )VALUES('$Debt_social_ID',  '$Reason_to_extend', '$Patient_debt', '$Employee_ID')") or die(mysqli_error($conn));
                if($lipia_deni_kidogo){
                    $select_debit = mysqli_query($conn, "SELECT Debt_ID FROM  tbl_debt_cash_deposit WHERE Debt_ID='$Debt_ID' AND created_at=CURDATE()") or die(mysqli_error($conn));
                    if(mysqli_num_rows($select_debit)>0){            
                        echo "Patient arleady sent to cashier";
                    }else{
                        $insert_deposit = mysqli_query($conn, "INSERT INTO tbl_debt_cash_deposit(Debt_ID,Patient_debt_amount, Employee_ID )VALUES('$Debt_ID', '$Patient_debt', '$Employee_ID') ") or die(mysqli_error($conn));
                        if(!$insert_deposit){
                            echo "Fail";
                        }else{
                            echo "Patient sent to cashier for cash deposit";
                        }
                    }
                }
            }
        }else{
            $select_debit = mysqli_query($conn, "SELECT Debt_ID FROM  tbl_debt_cash_deposit WHERE Debt_ID='$Debt_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_debit)>0){            
                echo "Patient arleady sent to cashier";
            }else{
                $insert_deposit = mysqli_query($conn, "INSERT INTO tbl_debt_cash_deposit(Debt_ID,Patient_debt_amount, Employee_ID )VALUES('$Debt_ID', '$Patient_debt', '$Employee_ID') ") or die(mysqli_error($conn));
                if(!$insert_deposit){
                    echo "Fail";
                }else{
                    echo "Patient sent to cashier for cash deposit";
                }
            }
        }
    }


    if(isset($_POST['process_patient_debt'])){
        $Debt_ID = $_POST['Debt_ID'];

        $update_debt = mysqli_query($conn, "UPDATE tbl_patient_debt SET Debt_status='Debt extended' WHERE Debt_ID='$Debt_ID'") or die(mysqli_error($conn));
        if($update_debt){
            echo "Debt extended";
        }else{
            echo "Failed to update";
        }
    }
    if(isset($_POST['process_debt'])){
        $Debt_ID = $_POST['Debt_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Patient_Bill_ID = $_POST['Patient_Bill_ID'];
        $patient_debt =$_POST['patient_debt'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        //AND sent_date=CURDATE()
        $select_debt_id = mysqli_query($conn, "SELECT Debt_ID FROM tbl_patient_debt_to_socialwalfare WHERE Debt_ID='$Debt_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_debt_id)<=0){
        $send_to_social = mysqli_query($conn, "INSERT INTO tbl_patient_debt_to_socialwalfare (Debt_ID,Patient_Bill_ID ,Registration_ID,Total_debt, sent_by  ) VALUES('$Debt_ID','$Patient_Bill_ID', '$Registration_ID','$patient_debt', '$Employee_ID')") or die(mysqli_error($conn));
        if($send_to_social){
            echo "Debt sent to social walfare";
        }else{
            echo "Failed to update";
        }
        }else{
            echo "Debt arleady sent to social walfare unit";
        }
    }

    if(isset($_POST['clinic_date_update'])){
        $clinicdate = $_POST['clinicdate'];

        $update_clinicdate = mysqli_query($conn, "UPDATE tbl_consultation SET Clinic_consultation_date_time=DATE('$clinicdate') WHERE Clinic_consultation_date_time='0000-00-00 00:00'") or die(mysqli_error($conn));
        if(!$update_clinicdate){
            echo "Failed to update";
        }else{
            echo "Updated";
        }
    }

    if(isset($_POST['paydebit'])){
        if (isset($_POST['Patient_debt'])) {
            $Patient_debt = $_POST['Patient_debt'];
        } else {
            $Patient_debt = '0';
        }
    
        if(isset($_POST['Debt_ID'])){
            $Debt_ID = $_POST['Debt_ID'];
        }else{
            $Debt_ID=0;
        }
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        echo '<input type="text" id="Patient_debt_'.$Debt_ID.'" hidden value="'. $Patient_debt.'">';
        ?>
        <div class="row col-md-12">
            <h4 style="color:red;"> This patient has debt of <?php echo number_format($Patient_debt).".00/="; ?>.</h4><br>
        </div> 
        <div class=" col-md-12" id="send_data">
            <center>
                
                <input type="button" id="send_data" Value="SEND TO SOCIAL WALFARE " class="art-button-green pull-right"  onclick="send_to_socialwalfare(<?= $Debt_ID ?>, <?= $Patient_debt?>)">
                <input type="button" id="send_data" Value="SEND TO CASH DEPOSIT" class="art-button-green pull-right" onclick="send_to_cash_deposit(<?= $Debt_ID ?>)">
                <input type="button" id="send_data" Value="PROCESS" class="art-button-green pull-right hide"  onclick="process_patient_debt(<?= $Debt_ID ?>)">
    
            </center>
        </div>
        <?php 
    }

    if(isset($_POST['authorization'])){
        if (isset($_POST['Member_Number'])) {
            $Member_Number = $_POST['Member_Number'];
        } else {
            $Member_Number = '0';
        }
    
        if(isset($_POST['Registration_ID'])){
            $Registration_ID = $_POST['Registration_ID'];
        }else{
            $Registration_ID=0;
        }
        if(isset($_POST['reason_for_approval'])){
            $reason_for_approval = $_POST['reason_for_approval'];
        }else{
            $reason_for_approval='none';
        }
        
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $exitpt = mysqli_query($conn, "SELECT Auth_ID FROM tbl_authorization  WHERE Registration_ID='$Registration_ID' AND Member_Number='$Member_Number' AND DATE(created_at)=CURDATE()") or die(mysqli_error($conn));
        if(mysqli_num_rows($exitpt)>0){
            $Auth_ID = mysqli_fetch_assoc($exitpt)['Auth_ID'];
            $updates_reason = mysqli_query($conn, "UPDATE tbl_authorization SET reason_for_approval='$reason_for_approval' WHERE Registration_ID='$Registration_ID' AND Member_Number='$Member_Number' AND DATE(created_at)=CURDATE()") or die(mysqli_error($conn));
            if($updates_reason){
                 echo "success";
            }
           
        }else{
            $recordauth = mysqli_query($conn, "INSERT INTO tbl_authorization(Registration_ID, Member_Number,reason_for_approval, Employee_ID) VALUES('$Registration_ID', '$Member_Number','$reason_for_approval', '$Employee_ID')") or die(mysqli_error($conn));
            if($recordauth){
                echo "success";
            }else{
                echo "No";
            }
        }
        
    }


    if(isset($_POST['Reason_authorization'])){
        if (isset($_POST['Member_Number'])) {
            $Member_Number = $_POST['Member_Number'];
        } else {
            $Member_Number = '0';
        }
    
        if(isset($_POST['Registration_ID'])){
            $Registration_ID = $_POST['Registration_ID'];
        }else{
            $Registration_ID=0;
        }

        if(isset($_POST['extenal_nhif_server_url'])){
            $extenal_nhif_server_url = $_POST['extenal_nhif_server_url'];
        }else{
            $extenal_nhif_server_url='';
        }
        
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

        echo "<h5 style='color:red;'>Reason for requesting new authorization while the patient still in consulted list</h5>
        <input type='text' id='reason_for_approval' placeholder='Enter Reason' style='width:80%;'>
        <input type='button' class='art-button-green' value='Save AND Authorize' onclick='savereason()'>";
    }

    if(isset($_POST['directadmitPT'])){
        $Registration_ID = $_POST['Registration_ID'];
        $ptdata = mysqli_query($conn, "SELECT Authorization_No,Verification, Member_Number,auto_item_update_api  FROM tbl_patient_registration pr, tbl_sponsor sp WHERE sp.Sponsor_ID=pr.Sponsor_ID AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

        if(mysqli_num_rows($ptdata)>0){
            while($row = mysqli_fetch_assoc($ptdata)){
                $Authorization_No = $row['Authorization_No'];
                $Verification = $row['Verification'];
                $Member_Number = $row['Member_Number'];
                $auto_item_update_api = $row['auto_item_update_api'];
                if($Verification =='Mandatory'){ // 2021Massana2016

                }
                 if($Authorization_No =='Mandatory'){
                    $authorization ="";
                    $readyonly ='readonly="readonly"';
                    
                }
            }
        }else{
            $authorization =" style ='display:none;' ";
            $readyonly='';
            $Authorization_No='';
            $Member_Number='';
        }
            
        $sql_select_external_nhif_server_url_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='NhifExternalServerUrl'") or die(mysqli_error($conn));
        $extenal_nhif_server_url=mysqli_fetch_assoc($sql_select_external_nhif_server_url_result)['configvalue'];

        if($nhif_server_configuration=="singleserver"){
            $extenal_nhif_server_url="";
        }
        ?>

            
                <table width="100%" style="border: 0px;">
                    <tbody><tr>
                            <td width="30%" style="text-align:right;">Patient Type</td>
                            <td>
                                <select  name="ToBeAdmitted" id="ToBeAdmitted" >
                                    <option value="yes">INPATIENT</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right' width="30%">Claim Form No / Billing No</td>
                            <td>
                                <input type='text' name='claim_form_number' readonly id='claim_form_number'>
                                <input type="hidden" id="need_claim" value=""/>
                            </td>

                        </tr>
                       

                        <tr style="" width="100%">
                            <td width="30%"style="text-align:right; ">
                                Continuation Sheet
                            </td>
                            <td>
                                <textarea name="ToBeAdmittedReason" id="ToBeAdmittedReason" style="resize: none;"></textarea>

                            </td>
                        </tr>
                        <tr>
                            <td width="30%" style="text-align:right;">Remarks</td>
                            <td>
                                <textarea name="remark" id="remark" style="resize: none;"></textarea>
                            </td>
                        </tr>
                        <div <?php echo $authorization ?> style="display: none;">
                        <?php if($Verification =='Mandatory'){ ?>
                        <tr >
                            <td style='text-align: right' width="30%">Member Number</td>
                            <td>
                                <input type='text' name='Member_Number' value="<?php echo $Member_Number; ?>" style='width:50%;' id='Member_Number'>
                                <input type='text' style="display: none;" name='hidden_card_number' value="<?php echo $Member_Number; ?>" style='width:50%;' id='hidden_card_number'>
                                <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF('<?php echo $Member_Number; ?>',  '<?= $extenal_nhif_server_url ?>')" class="btn btn-primary btn-xs" id="NHIF_Authorize" />
                                <div align="center" style="display:none" id="verifyprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                            </td>

                        </tr>
                         <tr  >
                            <td style="text-align: right;" width='14%'>Authorization Status <?php echo  $Authorization_No;?></td>
                            <td><input type='text' name='CardStatus' readonly="readonly" id='CardStatus'  <?=$readyonly; ?>></td>                          
                            
                        </tr>
                        <tr >
                            <td style='text-align: right'>Authorization Number</td>
                            <td>
                                <input type='text' name='AuthorizationNo' id='AuthorizationNo' maxlength="12"  <?=$readyonly; ?> style='width:45%;'>
                                <?php
                				
                                if($auto_item_update_api == 1){
                                    $sponsor_package_list = mysqli_query($conn,"SELECT * FROM tbl_nhif_scheme_package");
                                echo "<select name='package_id'  id='select_package' style='width:45%;'>
                                    <option value=''>Select Package</option>";
                                    while ($package = mysqli_fetch_object($sponsor_package_list)) {
                                        echo "<option value='".$package->package_id."'>".$package->package_name."</option>";
                                    }
                                echo "</select>   ";
                                }
                			?>
                        </td>
                        </tr>
                        <tr >
                            <td style='text-align: right'>Authorization Remarks</td>
                            <td ><textarea name="Remarks" rows="1" id="Remarks" readonly="readonly" style='resize: none;'  <?=$readyonly; ?>></textarea></td>
                        </tr>
                        </div>
                        <?php } ?>
                        <tr>
                            <td colspan="2" style="text-align:center">
                                <input type="hidden" id="patient_id" value=""/>
                                <input type="button" name="button" onclick="check_if_admited_or_in_admit_list('<?php echo $Authorization_No; ?>', '<?php echo $Registration_ID; ?>')" class="art-button-green" value="Admit Patient">
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
                
        <?php 
    }