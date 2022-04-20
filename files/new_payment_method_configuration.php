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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<fieldset>  
            <legend align=center><b>NEW CONFIGURATION CENTER</b></legend>
            <center>
                <?php 
                $sql_select_button_status_result=mysqli_query($conn,"SELECT new_payment_method_config_btn_name,visibility_status FROM tbl_new_payment_method_config_btn") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_button_status_result)>0){
                   while($btn_config_rows=mysqli_fetch_assoc($sql_select_button_status_result)){
                       $new_payment_method_config_btn_name=$btn_config_rows['new_payment_method_config_btn_name'];
                       $visibility_status=$btn_config_rows['visibility_status'];
                       if($new_payment_method_config_btn_name=="afya_card_pay"){
                           $afya_card_pay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="crdb_card_pay"){
                           $crdb_card_pay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="crdb_mobile_epay"){
                          $crdb_mobile_epay=$visibility_status; 
                       }
                       if($new_payment_method_config_btn_name=="create_out_patient_bill"){
                           $create_out_patient_bill=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="nmb_mobile_epay"){
                           $nmb_mobile_epay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="request_control_number"){
                           $request_control_number=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="afya_card_module"){
                           $afya_card_module=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="g_pesa_payment"){
                           $g_pesa_payment=$visibility_status;
                       }
                   }
                }
                $server_select = mysqli_query($conn, "SELECT extenal_server_status FROM tbl_epay_server_config ORDER BY setup_id DESC LIMIT 1");
                if(mysqli_num_rows($server_select)>0){
                    while($server = mysqli_fetch_assoc($server_select)){
                        $extenal_server_status = $server['extenal_server_status'];
                    }
                }
                ?>
                <table class="table table-bordered" style="width:40%!important;background:#FFFFFF;">
                    <caption><b>TURNING FUNCTION ON/OFF</b></caption>
                     <tr>
                         <th width="50px">S/No.</th>
                         <th>Function</th>
                         <th>Status</th>
                     </tr>
                     <tr>
                         <td>1.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('request_control_number')" value="Request Control Number" style="width:80%;" class="art-button-green"></center></td>
                        <td>
                             <?php if($request_control_number=="show"){?>
                                <input type="button" value="ON"style='width:60%' onclick="save_new_pay_btn_config('request_control_number')" class="art-button-green">
                            <?php }else{?>
                                <input type="button" value="OFF"style='width:60%;background:red' onclick="save_new_pay_btn_config('request_control_number')" class="art-button-green">
                            <?php } ?>
                        </td>
                     </tr>
                     <tr>
                         <td>2.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('create_out_patient_bill')" value="Create Outpatient Bill" style="width:80%;"class="art-button-green"></center></td>
                         <td>
                             <?php if($create_out_patient_bill=="show"){?>
                                    <input type="button" value="ON"style='width:60%' onclick="save_new_pay_btn_config('create_out_patient_bill')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF"style='width:60%;background:red' onclick="save_new_pay_btn_config('create_out_patient_bill')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>3.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('afya_card_pay')" value="Afya Card Pay" style="width:80%;"class="art-button-green"></center></td>
                         <td>
                             <?php if($afya_card_pay=="show"){?>
                                <input type="button" value="ON"style='width:60%' onclick="save_new_pay_btn_config('afya_card_pay')" class="art-button-green">
                             <?php }else{?>
                                <input type="button" value="OFF"style='width:60%;background: red' onclick="save_new_pay_btn_config('afya_card_pay')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>4.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('nmb_mobile_epay')" value="NMB  ePayment ECoSystem" style="width:80%;background:#F26F21" class="art-button-green"></center></td>
                         <td>
                             <?php if($nmb_mobile_epay=="show"){?>
                                    <input type="button" value="ON"style='width:60%' onclick="save_new_pay_btn_config('nmb_mobile_epay')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF"style='width:60%;background:red' onclick="save_new_pay_btn_config('nmb_mobile_epay')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>5.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('crdb_mobile_epay')" value="CRDB Mobile ePayment"style="width:80%;background: green" class="art-button-green"></center></td>
                         <td>
                             <?php if($crdb_mobile_epay=="show"){?>
                                    <input type="button" value="ON" style='width:60%'onclick="save_new_pay_btn_config('crdb_mobile_epay')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF" style='width:60%;background:red'onclick="save_new_pay_btn_config('crdb_mobile_epay')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>6.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('crdb_card_pay')" value="CRDB Card Payment"style="width:80%;background: green" class="art-button-green"></center></td>
                         <td>
                             <?php if($crdb_card_pay=="show"){?>
                                    <input type="button" value="ON" style='width:60%'onclick="save_new_pay_btn_config('crdb_card_pay')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF" style='width:60%;background:red'onclick="save_new_pay_btn_config('crdb_card_pay')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>7.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('afya_card_module')" value="Afya Card Module"style="width:80%;" class="art-button-green"></center></td>
                         <td>
                             <?php if($afya_card_module=="show"){?>
                                    <input type="button" value="ON" style='width:60%'onclick="save_new_pay_btn_config('afya_card_module')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF" style='width:60%;background:red'onclick="save_new_pay_btn_config('afya_card_module')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>8.</td>
                         <td><center><input type="button" onclick="save_new_pay_btn_config('g_pesa_payment')" value="G-PESA"style="width:80%;" class="art-button-green"></center></td>
                         <td>
                             <?php if($g_pesa_payment=="show"){?>
                                    <input type="button" value="ON" style='width:60%'onclick="save_new_pay_btn_config('g_pesa_payment')" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF" style='width:60%;background:red'onclick="save_new_pay_btn_config('g_pesa_payment')" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>9.</td>
                         <td><center><input type="button" onclick="epay_server_config()" value="EXTERNAL EPAY SERVER"style="width:80%;" class="art-button-green"></center></td>
                         <td>
                             <?php if($extenal_server_status=="active"){?>
                                    <input type="button" value="ON" style='width:60%' onclick="epay_server_config()" class="art-button-green">
                              <?php }else{?>
                                    <input type="button" value="OFF" style='width:60%;background:red' onclick="epay_server_config()" class="art-button-green">
                             <?php } ?>
                         </td>
                     </tr>
                     <tr>
                         <td>10.</td>
                         <td><center><input type="button" onclick="save_image_quality()" value="Save Image Quality"style="width:80%;" class="art-button-green"></center></td>
                         <td>
                             <?php 
                             $sql_select_image_quality_result=mysqli_query($conn,"SELECT image_quality_value FROM tbl_image_quality LIMIT 1") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_image_quality_result)>0){
                                $image_quality=mysqli_fetch_assoc($sql_select_image_quality_result)['image_quality_value'];
                            }else{
                               $image_quality=""; 
                            }
                             ?>
                             <input type="text" placeholder="1-100" id="image_quality" style="width:100px;text-align: center" value="<?= $image_quality ?>" onkeyup="validate_input()" class="form-control">
                             <lable id="image_quality_label"></label>
                         </td>
                     </tr>
                </table>
            </center>
</fieldset><br/>
<script>
    function epay_server_config(){
        var clicked_btn = 'data';
        $.ajax({
            type:'POST',
            url:'ajax_save_new_pay_server.php',
            data:{clicked_btn:clicked_btn},
            success:function(data){
                location.reload();
            }
        });
    }
</script>
<script>
    function save_new_pay_btn_config(clicked_btn){
        $.ajax({
            type:'POST',
            url:'ajax_save_new_pay_btn_config.php',
            data:{clicked_btn:clicked_btn},
            success:function(data){
                location.reload();
                console.log(data+clicked_btn)
            }
        });
    }
    function validate_input(){
      var image_quality=$("#image_quality").val(); 
      if(image_quality<0||image_quality>100){
          $("#image_quality").val("1");
      }
      $("#image_quality_label").html("not saved");
    }
    function save_image_quality(){
        var image_quality=$("#image_quality").val();
        if(image_quality!=""){
            $.ajax({
                type:'POST',
                url:'ajax_save_image_quality.php',
                data:{image_quality:image_quality},
                success:function(data){
                    
//                    alert(".>"+data+"<--");
                    if(data=="success"){
                        $("#image_quality_label").html("saved");
                        $("#image_quality_label").css("color","green");
                        $("#image_quality_label").css("font-weight","bold");
                        console.log(data);
                        $("#image_quality").css("border","");
                    }else{
                       $("#image_quality_label").html("fail"+data);
                        $("#image_quality_label").css("color","red");
                        $("#image_quality_label").css("font-weight","bold"); 
                    }
                }
            });
        }else{
          $("#image_quality").css("border","2px solid red");  
        }
    }
</script>
<?php
    include("./includes/footer.php");
?>