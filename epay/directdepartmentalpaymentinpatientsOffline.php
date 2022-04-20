<?php

include("../includes/connection.php");
$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$array_config_data['configData'][$configname] = strtolower($configvalue);
                                      
				}
                                
?>


<table  width="100%" cellpadding="6" cellspacing="0" border="0">
    <form id="offlineTransactionForm1" method="POST">
    <!-- hidden fields -->
    <input type="text" hidden="hidden"   name="amount" id="amount_required" value="<?php echo $_GET['amount_required']; ?>">
    <input type="text" hidden="hidden"   name="registration_id" id="registration_id" value="<?php echo $_GET['registration_id']; ?>">
    <input type="text" hidden="hidden"   name="Guarantor_Name" id="Guarantor_Name" value="<?php echo $_GET['Guarantor_Name']; ?>">

    <input type="text" hidden="hidden"   name="Claim_Form_Number" id="Claim_Form_Number" value="<?php echo $_GET['Claim_Form_Number']; ?>">
    <input type="text" hidden="hidden"   name="Consultant_ID" id="Consultant_ID" value="<?php echo $_GET['Consultant_ID']; ?>">
    <input type="text" hidden="hidden"   name="Folio_Number" id="Folio_Number" value="<?php echo $_GET['Folio_Number']; ?>">  
    <!-- end of hidden fields -->
        <tbody>
            <tr>
                <td><b>Transaction Mode</b></td>
                <td>
                      <select id="transaction_mode" onchange="get_terminals(this);" name="transaction_mode">
                        <option></option>
                        <?php if(isset($array_config_data['configData']) && $array_config_data['configData']['showManulaOrOffline']=='offline'){?>
                        <option>Offline</option>
                        <?php }else{ ?>
                        <option>Manual</option>
                        <?php 
                        } 
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Select Terminal</b></td>
                <td>
                    <select id="terminal_name" onchange="get_terminal_id(this);" name="terminal_name">
                        <option></option>
                       
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Terminal Id</b></td>
                <td>
                    <input type="text" readonly="readonly" id="terminal_id" name="terminal_id">
                </td>
            </tr>
            <tr>
                <td><b>Amount Required</b></td>
                <td>
                
                    <input type="text" readonly="readonly"  name="amount1" value="<?php echo number_format($_GET['amount_required']); ?>">
                </td>
            </tr>
            <tr>
                <td><b>Authorization Code</b></td>
                <td>
                    <input type="text" name="auth_code" id="auth_code">
                </td>
            </tr>
            <tr>
               
                <td colspan="2" align="right">
                    <input type="submit" class="art-button-green" id="saveOfflinePaymentBtn" value="SAVE TRANSACTION" name="saveTransaction">
                </td>
            </tr>
        </tbody>
        </form>
    </table>

<script type="text/javascript">
    $("#saveOfflinePaymentBtn").click(function(e){
        e.preventDefault();
        var amount = $("#amount_required").val();
        var registration_id = $("#registration_id").val();
        var auth_code = $("#auth_code").val();    
        var terminal_id = $("#terminal_id").val();    
        var Claim_Form_Number = $("#Claim_Form_Number").val();
        var Consultant_ID = $("#Consultant_ID").val();
        var Guarantor_Name = $("#Guarantor_Name").val();
        var Folio_Number = $("#Folio_Number").val();
        if(terminal_id==''){
            var txt = 'Please select the \" <b style="color:red;">Terminal</b> \"';
            ShowMsgDiag('Field Required!',txt);
            return;
        }
        if(auth_code==''){
            var txt = 'Please enter \" <b style="color:red;">Authorization Code</b> \" provided by CRDB';
            ShowMsgDiag('Field Required!',txt);
            return;
        }
           // document.location = 'Departmental_Make_Payment.php?Registration_ID=' + registration_id + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID + '&Visit_Type=' + Visit_Type + '&Visit_Controler=' + Visit_Controler+'&manual_offline=offline'+'&auth_code='+auth_code+'&terminal_id='+terminal_id; 
            document.location = 'Inpatient_Departmental_Make_Payment.php?Registration_ID=' + registration_id + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&manual_offline=offline'+'&auth_code='+auth_code+'&terminal_id='+terminal_id;
            
        // document.location = 'Direct_Cash_Make_Payment.php?Registration_ID='+registration_id+'&Sponsor_ID='+Sponsor_ID+'&terminal_id='+terminal_id+'&auth_code='+auth_code+'&manual_offline=offline';
           
        });

    function clearPaymentForm(){
        $("#auth_code").val('');
        $("#terminal_id").val('');
        $("#transaction_mode").val('');
        $("#terminal_name").html('<option>--select terminal--</option>');   
    }
    function ShowMsgDiag(dTitle,content){
        $("#errorMsgDiag").dialog({
                    title: dTitle,
                    width: '500',
                    buttons: [{
                        text: 'Okay',
                        click: function(){
                            $(this).dialog('close');
                        },
                    }],
                    modal: true,
                }).html(content);
    }
</script>

<div id="responseDiag" style="display:none;"></div>
<div id="errorMsgDiag" style="display:none;"></div>

