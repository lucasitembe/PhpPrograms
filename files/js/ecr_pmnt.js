$(document).ready(function () {
    $("#print_receipt_msg").dialog({autoOpen: false, width: '30%', height: 200, title: 'Message', modal: true});
    $("#synchronize_msg").dialog({autoOpen: false, width: '30%', height: 100, title: 'Message', modal: true});
    $("#force_synchronization_message").dialog({autoOpen: false, width: '40%', height: 200, title: 'Message', modal: true});
});

function ecr_paycode(pay_code) {
    $.ajax({
        type: 'POST',
        url: 'http://127.0.0.1/Spireware/addpaymentcode.php',
        data: 'PaymentCode=' + pay_code,
        cache: false,
        success: function (html) {
        }
    });
}

function Print_Receipt_Payment(Payment_Code) {
    if (Payment_Code != null && Payment_Code != '') {
        if (window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }
        myObjectSearch.onreadystatechange = function () {
            var data = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {

                if (data == '' || data == null) {
                    $("#print_receipt_msg").dialog("open");
                    //alert("It seems payment for this bill number is not yet done.\n\nPlease click synchronize button to continue.");
                    exit;
                }
                
                if(checkForMaximmumReceiptrinting(data) === 'true'){

                window.open("invidualsummaryreceiptprint.php?Patient_Payment_ID=" + data + "&IndividualSummaryReport=IndividualSummaryReportThisForm");
               // window.location="invidualsummaryreceiptprint.php?Patient_Payment_ID=" + data + "&IndividualSummaryReport=IndividualSummaryReportThisForm";
                //var winClose = popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID=' + data + '&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);


                $.ajax({
                    type:"POST",
                    url:"update_receipt_count.php",
                    async:false,
                    data:{payment_id:data},
                    success:function(result){
                        console.log(result)
                    }
                })

}else{
        alert("You have exeded maximumu print count")
        return false;
    }

            }
        }; //specify name of function that will handle server response........

        myObjectSearch.open('GET', 'Epayment_Adhock_Search.php?src=receipt&Payment_Code=' + Payment_Code, true);
        myObjectSearch.send();
    }
}



function checkForMaximmumReceiptrinting(theId){
    
    var theCount = '';
    $.ajax({
                    type:"POST",
                    url:"compare_receipt_count.php",
                    async:false,
                    data:{payment_id:theId},
                    success:function(result){
                        // alert(result)
                        theCount = result;
                        console.log(theCount)
                                                
                    }
                })

return theCount;
}

function print_epayment(payment_code, p_id) {
    popupwindow('print_epay_payment_details.php?payment_code=' + payment_code + '&p_id=' + p_id, 'The popote', 420, 400);
}

function popupwindow(url, title, w, h) {
    var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
    return mypopupWindow;
}

function sync_epayments(EPAY_SERVER_URL, remoteTransID,Payment_Cache_ID,Registration_ID,kutokaphamacy,from_revenue_phamacy) {
    // alert('it is here');
    $("#sync").prop("disabled",true)
    var trans_id = "trans_id=" + remoteTransID;
    if (typeof(from_revenue_phamacy)==='undefined') from_revenue_phamacy = "no";
    $.ajax({
        type: 'POST',
        url: 'http://' + EPAY_SERVER_URL + "/updater/croner.php",
        data: trans_id,
        cache: false,
        beforeSend: function (xhr) {
            $('#progressStatus').show();
        },
        success: function (result) {
            //alert(result)
            sync_other_details(EPAY_SERVER_URL,Payment_Cache_ID,Registration_ID,kutokaphamacy,from_revenue_phamacy);
        },complete: function (jqXHR, textStatus) {
            sync_other_details(EPAY_SERVER_URL,Payment_Cache_ID,Registration_ID,kutokaphamacy,from_revenue_phamacy);
           // alert("complete")

        },error: function (jqXHR, textStatus, errorThrown) {
              sync_other_details(EPAY_SERVER_URL);
               $('#progressStatus').hide();
               //alert("transaction error");
        }
    });
}
function sync_other_details(EPAY_SERVER_URL,Payment_Cache_ID,Registration_ID,kutokaphamacy,from_revenue_phamacy) {
    // console.log('test');
    $.ajax({
        type: 'POST',
        url: 'http://' + EPAY_SERVER_URL + "/updater/Cron_Payments_Update.php",
        data: '',
        cache: false,
        success: function (data) {
            // alert(data);
            $("#synchronize_msg").dialog("open");
            $("#sync").prop("disabled",false)
            if(kutokaphamacy=='yes'){
                document.location = "Dispense_Medication.php?Payment_Cache_ID="+Payment_Cache_ID+"&Transaction_Type=Cash&Registration_ID="+Registration_ID; 
            }
            if(from_revenue_phamacy=="yes"){
                dispence_from_revenue_pharmacy(Payment_Cache_ID,Registration_ID);
            }
        },complete: function (jqXHR, textStatus) {
           // alert("complete")
            $('#progressStatus').hide();
        },error: function (jqXHR, textStatus, errorThrown) {
            $('#progressStatus').hide();
           // alert("error")
        }
    });
}
//function dispense_medication(){
// }
 function dispence_from_revenue_pharmacy(Payment_Cache_ID,Registration_ID){
     $.ajax({
           type:'GET',
           url:'dispence_from_revenue_center_phamacy.php',
           data:{Payment_Cache_ID:Payment_Cache_ID,Registration_ID:Registration_ID},
           success:function(data){
              // alert(data);
           }
       });
 }
  function sync_epayments_force(Registration_ID,Transaction_ID,Payment_Code){
     // alert(Payment_Code)
       $.ajax({
           type:'GET',
           url:'force_synchronization_of_payment_data.php',
           data:{Payment_Code:Payment_Code,Registration_ID:Registration_ID},
           beforeSend: function (xhr) {
            $('#progressStatus').show();
        },
           success:function(data){
               console.log("sync_epayments_force===>"+data)
               if(data=="Completed"){
                   force_move_data_for_receipt_print(Registration_ID,Transaction_ID,Payment_Code)
               }else{
                   //alert("payment is not completed:Please pay again");
                   var pay_code=Payment_Code;
                   var force_htm="<b>PAYMENT IS NOT COMPLETED...PLEASE PAY AGAIN</b><br/>\n\
                                \n\
                                <p><b>Or</b> If you have CRDB recept for this patient enter the authorization number and Syncronize.<b>Else Click Cancel And pay again<b></p>\n\
                                <input type='text' id='authorization_number' class='form-control' placeholder='Enter Authorization Number'>\n\
                                <div class='row'>\n\
                                <br/><div class='col-md-6'><input type='button' class='art-button pull-right' onclick='close_force_syncronization_dialog()' value='CANCEL'></div>\n\
                                <div class='col-md-6'><input type='button' class='art-button-green'onclick='force_sync_epay_data("+Registration_ID+","+Transaction_ID+",\""+pay_code+"\")' value='SYCHRONIZE'></div>\n\
                                </div>";
                     
                    $('#force_synchronization_message').html(force_htm)
                    $('#force_synchronization_message').dialog("open");  
                    $('#progressStatus').hide();  
               }
               
           }
       }); 
    }
    function close_force_syncronization_dialog(){
        $('#force_synchronization_message').dialog("close");
    }
    function force_sync_epay_data(Registration_ID,Transaction_ID,Payment_Code){
         
        var  authorization_number= $("#authorization_number").val();
        var userText = authorization_number.replace(/^\s+/, '').replace(/\s+$/, '');
         if(userText==""){
             $("#authorization_number").css("border","2px solid red");
             $("#authorization_number").focus();
             exit;
         }else{
            $("#authorization_number").css("border",""); 
         }
         
         force_move_data_for_receipt_print(Registration_ID,Transaction_ID,Payment_Code,authorization_number)
         $('#force_synchronization_message').dialog("close");
    }
    function force_move_data_for_receipt_print(Registration_ID,Transaction_ID,Payment_Code,authorization_number){
        
       $.ajax({
           type:'GET',
           url:'force_move_data_for_receipt_print.php',
           data:{Payment_Code:Payment_Code,Registration_ID:Registration_ID,Transaction_ID:Transaction_ID,authorization_number:authorization_number},
           beforeSend: function (xhr) {
            $('#progressStatus').show();
        },
           success:function(data){
            	//alert(data)
                console.log("force_move_data_for_receipt_print==>"+data);
               $("#synchronize_msg").dialog("open");
               $('#progressStatus').hide();
               
           },
       error:function(x,n,m){
           //alert(x+n+m)
       }
           
       }); 
    }