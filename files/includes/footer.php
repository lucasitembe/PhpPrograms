</div>
</div>
</div>


</article></div>
</div>
</div>
</div>
</div>
<footer class="art-footer">
    <input type="hidden" value="" id="broadcastmsg" />
    <input type="hidden" value="" id="notified" />
    <div class="art-footer-inner">
        <p><a href="http://www.gpitg.com/" target="_blank" title="GPITG LIMITED">GPITG LIMITED</a><span style="font-weight: bold;"></span> &copy; <?php echo date('Y'); ?>. All Rights Reserved.</p>

    </div>
</footer>

</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<link rel="stylesheet" href="css/jquery.notifyBar.css" />
<link rel="stylesheet" href="js/toastr/toastr.min.css" />
<script src="js/jquery.notifyBar.js" ></script>
<script src="js/toastr/toastr.min.js"  ></script>
<script src="js/bootstrap.min.js"></script>

<script src="js/toastr/toastr.function.inc.js"  ></script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
    var myVar;
    
    $(document).ready(function () {
        myVar = setInterval(function () {
            myTimer();
        }, 15000);
        
        // $.notifyBar({ cssClass: "success", html: "Your data has been changed!" }); 
    });
    
    function myTimer() {
        $.ajax({
            type: 'GET',
            url: 'broadcastmsg.php',
            data: 'action=read',
            dataType: 'json',
            success: function (result) {
                var broadcastmsg = $('#broadcastmsg').val();
                if (result.type != 'closed') {
                    if (broadcastmsg != result.msg && result.msg != '') {
                        $('#broadcastmsg').val(result.msg);
                        $('#notified').val('0');
                    }
                } else {
                    $('#broadcastmsg').val('');
                }

                if (broadcastmsg != '') {
                    if ($('#notified').val() == '0') {
                        $.notifyBar({cssClass: result.type, html: broadcastmsg, close: true, waitingForClose: true, closeOnClick: true});
                        $('#notified').val('1');
                    }
                }

            }, complete: function (jqXHR, textStatus) {
            }, error: function (jqXHR, textStatus, errorThrown) {
               // alert(errorThrown);
            }
        });
    }

    function myStopFunction() {
        clearInterval(myVar);
    }
    function viewPatientPhoto(patient_id){
                $.get("functions/getPatientPhoto.php",{patient_id:patient_id},function(data){
                    $("<div></div>").dialog({
                        title: 'Patient Photo',
                        width: '400',
                        height: '440',
                        modal: true,
                        resizable: false,
                        buttons: [{
                            text: 'Close',
                            click: function(){
                                $(this).dialog('close');
                            }
                        }],
                    }).html(data);
                });
            }
</script>
<div id="status_gepg_hidden_block" class="hide"></div>
<div id="control_no_gepg_hidden_block" class="hide"></div>
<div id="payment_confirmation_gepg_hidden_block" class="hide"></div>
<script>
    function request_control_number_from_gepg(Registration_ID,total,Patient_Name,Check_In_Type,Payment_Cache_ID){
            $("#gepg_progress_bar").show();
           $.ajax({
               type:'POST',
               url:'ajax_government_payment_gateway.php',
               data:{Amount_Required:total,Registration_ID:Registration_ID,Patient_Name:Patient_Name,Check_In_Type:Check_In_Type,Payment_Cache_ID:Payment_Cache_ID},
               success:function (data){
                   $("#status_gepg_hidden_block").html(data);
                  var TrxStsCode=$('TrxStsCode').html();
                  console.log("===>"+TrxStsCode);
                  if(TrxStsCode=="7101"){
                     $("#gepg_request_status").html("Successfull"); 
                     $("#gepg_request_status").css("color","green"); 
                  }else{
                     $("#gepg_request_status").html("Fail"); 
                     $("#gepg_request_status").css("color","red"); 
                  }
                   get_control_number_from_gepg(Payment_Cache_ID)
                   console.log(data);
                  // $("#gepg_progress_bar").hide();
               }
           });
    }
    function get_control_number_from_gepg(Payment_Cache_ID){
        $.ajax({
               type:'POST',
               url:'ajax_get_control_number_from_gepg.php',
               data:{Payment_Cache_ID:Payment_Cache_ID},
               success:function (data){
                   $("#control_no_gepg_hidden_block").html(data);
                 var pay_cntr_num= $('pay_cntr_num').html();
                 $("#control_number").html(pay_cntr_num);
                   console.log(data);
                   $("#gepg_progress_bar").hide();
               }
           });
    }
    function gepg_confirm_payment(Payment_Cache_ID,Check_In_Type,Registration_ID){
        $("#gepg_progress_bar").show();
        $.ajax({
            type:'POST',
            url:'ajax_gepg_confirm_payment.php',
            data:{Payment_Cache_ID:Payment_Cache_ID},
            success:function(data){
                //alert(data);
                $("#payment_confirmation_gepg_hidden_block").html(data);
                if($('detail').html()=="Not found."){
                    $("#gepg_payment_status").html("Payment Not Completed");
                    $("#gepg_payment_status").css("color","red"); 
                }else{
                    if(parseInt($('paid_amt').html())>=parseInt($('bill_amt').html())){
                       $("#gepg_payment_status").html("Payment Completed Successfully"); 
                       $("#gepg_payment_status").css("color","green");
                    }else{
                       $("#gepg_payment_status").html("Payment Not Completed");
                       $("#gepg_payment_status").css("color","red"); 
                    }  
                     
                }
                console.log(data);
                $("#gepg_progress_bar").hide();
            }
        });
    }
    function gepg_complete_patient_payment(Payment_Cache_ID,Check_In_Type,Registration_ID){
        $.ajax({
            type:'POST',
            url:'ajax_gepg_complete_patient_payment.php',
            data:{Payment_Cache_ID:Payment_Cache_ID,Check_In_Type:Check_In_Type,Registration_ID:Registration_ID},
            success:function(data){
                
            }
        });
    }
</script>
</body></html>
<?php
ob_end_flush();
mysqli_close($conn);