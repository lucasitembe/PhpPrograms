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
    <input type="text" hidden="hidden"   name="itemzero" id="itemzero" value="<?php echo $_GET['itemzero']; ?>">

    <input type="text" hidden="hidden"   name="Transaction_Type" id="Transaction_Type" value="<?php echo $_GET['Transaction_Type']; ?>">
    <input type="text" hidden="hidden"   name="Payment_Cache_ID" id="Payment_Cache_ID" value="<?php echo $_GET['Payment_Cache_ID']; ?>">
    <input type="text" hidden="hidden"   name="Sub_Department_ID" id="Sub_Department_ID" value="<?php echo $_GET['Sub_Department_ID']; ?>">  
    <input type="text" hidden="hidden"   name="Billing_Type" id="Billing_Type" value="<?php echo $_GET['Billing_Type']; ?>">  
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
        var Registration_ID = $("#registration_id").val();
        var auth_code = $("#auth_code").val();    
        var terminal_id = $("#terminal_id").val();    
        var itemzero = $("#itemzero").val();
        var Transaction_Type = $("#Transaction_Type").val();
        var Payment_Cache_ID = $("#Payment_Cache_ID").val();
        var Sub_Department_ID = $("#Sub_Department_ID").val();
        var Temp_Billing_Type2 = $("#Billing_Type").val();
         
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
        
          if (itemzero != '' && itemzero != null){
                    document.location = 'Patient_Billing_Radiology_Page.php?itemzero='+itemzero+'&Transaction_Type='+Transaction_Type+'&Payment_Cache_ID='+Payment_Cache_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Registration_ID='+Registration_ID+'&Billing_Type='+Temp_Billing_Type2+'&manual_offline=offline'+'&auth_code='+auth_code+'&terminal_id='+terminal_id;
            } else{
                     document.location = 'Patient_Billing_Radiology_Page.php?Payment_Cache_ID='+Payment_Cache_ID+'&Transaction_Type='+Transaction_Type+'&Sub_Department_ID='+Sub_Department_ID+'&Registration_ID='+Registration_ID+'&Billing_Type='+Temp_Billing_Type2+'&manual_offline=offline'+'&auth_code='+auth_code+'&terminal_id='+terminal_id;
          }
           
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

