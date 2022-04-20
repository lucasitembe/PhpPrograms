<?php include("./includes/header.php"); ?>
<script>
    function Confirm_Approval_Trnsaction(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID, Sponsor_Name, Check_In_ID, AuthorizationNo,full_disease_data) {
      if(AuthorizationNo.trim() == ''){
        alert('Patient misses Authorization Number\n Please Authize first ');
        return false;
      }

	 if(AuthorizationNo.length < 12){
		alert("AUTHORIZATION NUMBER IS LESS THAN 12 DIGITS");
        return false;
      }

        if (Control_Quantity == 'yes') {
            var r = confirm("Are you sure you want to approve this transaction?\n\nClick OK to proceed");
            if (r == true) {
                Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name,Check_In_ID,full_disease_data);
            }
        } else {
            alert("Process Fail! Some transactions contain zero quantity");
        }
    }



    //Aprove patient

    function patient_approval() {
        var Registration_ID = document.getElementById('Registration_ID').value;
        var Folio_Number = document.getElementById('Folio_Number').value;
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        var Patient_Bill_ID = document.getElementById('Patient_Bill_ID').value;
        var Control_Quantity = document.getElementById('Control_Quantity').value;
        var Patient_Payment_ID = document.getElementById('Patient_Payment_ID').value;
        var Signature_value = document.getElementById('Signature_value').value;
        var n = Signature_value.length;
        if (n < 5) {
            alert('Fill patient signature first');
        } else {
            Confirm_Approval_Trnsaction_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID);
        }
    }


    function Confirm_Approval_Trnsaction_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Control_Quantity, Patient_Payment_ID) {
        //if(Control_Quantity == 'yes'){
        var r = confirm("Are you sure you want to approve this transaction?\n\nClick OK to proceed");
        if (r == true) {
            Approval_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID);
        }
        //}else{
        //  alert("Process Fail! Some transactions contain zero quantity");
        //}
    }

</script>
<script type='text/javascript'>
        function Approval(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID, Sponsor_Name,Check_In_ID,full_disease_data) {
        var Bill_ID = '';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data2121 = myObject.responseText;
            if (myObject.readyState == 4) {
                //Approval_ID.disabled = 'disabled';
                document.getElementById('Approval_Area').innerHTML = data2121;
                var Bill_ID = generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name,full_disease_data);
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Patient_Payment_ID=' + Patient_Payment_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, true);
        myObject.send();

        alertToastr('', 'Approving bill. please wait..... ', 'info', '', false);
        $('#progressStatus').show();
    }
    //Genarate bill
    function generateBill(Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID, Sponsor_Name,full_disease_data) {
        var Bill_ID = '';
        $.ajax({
            type: "GET",
            url: "Generate_Bill.php",
            'async':false,
            dataType: "json",
            data: {Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID,full_disease_data:full_disease_data},
            beforeSend: function (xhr) {
                alertToastr('', 'Generating bill. please wait..... ', 'info', '', false);
            },
            success: function (data) {
                if (data.status == '200') {
                    alertToastr('', 'Bill Generated successifully.', 'success', '', false);
                        Bill_ID = data.data;
                    //if (Sponsor_Name === "NHIF") {
                        SendBill(data.data, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID);
                    //}
                } else if (data.status == 300) {
                    alertToastr('', 'Failed to generate bill. ' + data, 'error', '', false);
                } else {
                    alertToastr('', 'An error has occured.please try again. ' + data, 'error', '', false);
                }
            }
        });
        return Bill_ID;
    }
    function SendBill(Bill_ID, Sponsor_ID, Patient_Payment_ID, Registration_ID, Folio_Number, Patient_Bill_ID) {
        $.ajax({
            type: "GET",
            url: "createXMLBill.php",
            data: {Bill_ID: Bill_ID, Sponsor_ID: Sponsor_ID, Patient_Payment_ID: Patient_Payment_ID, Registration_ID: Registration_ID, Folio_Number: Folio_Number, Patient_Bill_ID: Patient_Bill_ID,Location:"InitialStage"},
            beforeSend: function (xhr) {
                $('#progressStatus').show();
                alertToastr('', 'Sending bill. please wait..... ', 'info', '', false);
                console.log("before sending");
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                if (data.StatusCode == '200') {
                    alertToastr('', data.Message, 'success', '', false);
                } else if (data.StatusCode == '0') {
                    alertToastr('', 'There is a problem with connectivity, please check your connection!', 'error', '', false);
                } else {
                    alertToastr('', data.Message, 'error', '', false);
                }

            }, complete: function (x, y) {
                console.log(x+y);
                $('#progressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR+textStatus+errorThrown);
                $('#progressStatus').hide();
            }
        });
    }

    // function createClaim(){
    //   $.ajax({
    //     url:'create_claim.php',
    //     type:'post',
    //     data:{Registration_ID:Registration_ID,AuthorizationNo:AuthorizationNo,disease_code:disease_code,item_id:item_id,unit_price:unit_price,quantity:quantity,discount:discount},
    //     success:function(){
    //
    //     }
    //   });
    // }
    //Approval patient signature
    function Approval_Patient(Registration_ID, Folio_Number, Sponsor_ID, Patient_Bill_ID, Patient_Payment_ID) {
        var Signature_value = document.getElementById('Signature_value').value;
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data2121 = myObject.responseText;
            if (myObject.readyState == 4) {

                //alert(data2121);
                //Approval_ID.disabled = 'disabled';
                document.getElementById('Approval_Area').innerHTML = data2121;
                document.getElementById('Signature_value').value = '';
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill_Patient.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID + '&Folio_Number=' + Folio_Number + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Signature_value=' + Signature_value, true);
        myObject.send();
    }
</script>

<script>
    $('#Signature_value').signature('draw', $('#signatureJSON').val());
    $('#Signature_value').signature({disabled: true});
</script>


<?php
/// include("./includes/header.php");
include("./includes/connection.php");
$temp = 1;
$total = 0;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

$Date_From = '';
$Date_To = '';
?>


<!--    Datepicker script-->
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>

<!--<script src="script.js"></script>-->

<!--[if IE]>
<script type="text/javascript" src="js/excanvas.js"></script>
<![endif]-->

<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="js/jquery.signature.js"></script>
<!--<script src="script.js"></script>-->
<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<!--<script src="script.responsive.js"></script>-->
<link type="text/css" href="css/jquery.signature.css" rel="stylesheet">

<!-- <script>
    $(function () {
        $("#date_From").datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            //buttonImageOnly: true,
            //showOn: "both",
            dateFormat: "yy-mm-dd",
            //showAnim: "bounce"
        });

    });

    $(function () {
        $("#date_To").datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            //buttonImageOnly: true,
            //showOn: "both",
            dateFormat: "yy-mm-dd",
            //showAnim: "bounce"
        });

    });
</script> -->

<!--    end of datepicker script-->


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='./new_payment_method.php' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

      //get today's date
      $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
      while($date = mysqli_fetch_array($sql_date_time)){
          $Current_Date_Time = $date['Date_Time'];
      }
      $Filter_Value = substr($Current_Date_Time,0,11);
      $Start_Date = $Filter_Value.' 00:00';
      $End_Date = $Current_Date_Time;
?>

<br/><br/>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<center>
    <fieldset>
        <legend align='center'><b>REVENUE COLLECTION BY SANGIRA NUMBER</b></legend>
        <!--<br/>-->
        <style>
            table,tr,td{
                border-collapse:collapse !important;
                border:none !important;

            }
            tr:hover{
                background-color:#eeeeee;
                cursor:pointer;
            }
        </style>

        <!--<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <table width=100%>
            <tr>
            
                
                <!-- <td width="5%" style="text-align: right;"><b>Start Date</b></td> -->
                <td style='width:10%; text-align: center; color:black; border:2px solid #ccc;text-align:center;' >
                    <input type='text' name='Date_From' id='date_From' required='required' style='text-align: center;' value="<?php echo $Start_Date; ?>"readonly>
                </td>
                <!-- <td width="5%" style="text-align: right;"><b>End Date</b></td> -->
                <td style='text-align: center;width:10%; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='text' name='Date_To' id='date_To' required='required' style='text-align: center;' value="<?php echo $End_Date; ?>"readonly>
                </td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Number" id="Registration_ID"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Sangira Number" id="sangira_code"/></td>
                <!-- <td width="5%" style="text-align: right;"><b>Cashier Name</b></td> -->
                <td style='text-align: center;width:10%; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <select name='cashier' id='cashier' required='required' onchange='filter_list_of_patient_sent_to_cashier()'>
                        <option value="">All Cashier Name</option>
                        <?php
                        $data = mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Account_Status='active'") or die(mysqli_error($conn));
                        while ($row = mysqli_fetch_array($data)) {
                            echo '<option value="' . $row['Employee_ID'] . '">' . $row['Employee_Name'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <!-- <td width="5%" style="text-align: right;"><b>Transaction</b></td> -->
                <td>
                    <select id='transaction' onchange='filter_list_of_patient_sent_to_cashier()'>
                        <option value="">All Transaction</option>
                        <option value="completed">complete Transaction</option>
                        <option value="pending">pending Transaction</option>
                    </select>
                </td>
                <td><select   name='payment_direction' id='payment_direction' style='text-align: center;width:100%;display:inline' onchange="filter_list_of_patient_sent_to_cashier()" id="sangira_status">
                        <option value="All">All Banks</option>
                        <option value="to_nmb">NMB Bank</option>
                        <option value="to_crdb">CRDB Bank</option>
                    </select></td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='filter_list_of_patient_sent_to_cashier()'>
                </td>
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <td><input type='button' onclick='previewrevenuecollection()' value='PREVIEW' class='art-button-green'/></td>
                </td>
                
                <td style='text-align: center; text-align: center; color:black; border:2px solid #ccc;text-align:center;'>
                    <td><input type='button' onclick='filter_list_of_patient_sent_to_cashier_excel()' value='PREVIEW-EXCEL' class='art-button-green'/></td>
                </td>
            </tr>
        </table>
</center>
</fieldset>
<fieldset>
<div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">
            <tr  style='background: #dedede;'>
                <td style="width:50px"><b>S/No</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Number</b></td>
                <td><b>Gender</b></td>
                <td><b>Employee</b></td>
                <td><b>Sangira Number</b></td>
                <td><b>Amount</b></td>
                <td><b>Created Date</b></td>
                <td><b>Bank</b></td>
                <td><b>Status</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<script>
    $(document).ready(function () {
        $('select').select2();
    });
    
    function filter_list_of_patient_sent_to_cashier(){
        var start_date=$('#date_From').val();
        var end_date=$('#date_To').val();
        var cashier=$('#cashier').val();
        var transaction=$('#transaction').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var sangira_code=$('#sangira_code').val();
        var payment_direction=$('#payment_direction').val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_pendingcashier.php',
            data:{start_date:start_date,
                end_date:end_date,
                cashier:cashier,
                transaction:transaction,
                Registration_ID:Registration_ID,
                Patient_Name:Patient_Name,
                sangira_code:sangira_code,
                payment_direction:payment_direction,
            },
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
            }
        });
    }
    
    function filter_list_of_patient_sent_to_cashier_excel(){
        var start_date=$('#date_From').val();
        var end_date=$('#date_To').val();
        var cashier=$('#cashier').val();
        var transaction=$('#transaction').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var sangira_code=$('#sangira_code').val();
        var payment_direction=$('#payment_direction').val();
        
        document.location = 'cashiercollection_excel_file.php?cashier='+cashier+"&start_date="+start_date+"&end_date="+end_date+"&transaction="+transaction+"&Registration_ID="+Registration_ID+"&Patient_Name="+Patient_Name+"&sangira_code="+sangira_code+"&payment_direction="+payment_direction;
    }
    
    function previewrevenuecollection(){
        var start_date=$('#date_From').val();
        var end_date=$('#date_To').val();
        var cashier=$('#cashier').val();
        var transaction=$('#transaction').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var sangira_code=$('#sangira_code').val();
        var payment_direction=$('#payment_direction').val();

       window.open('cashiercollection_pdf.php?start_date='+start_date+'&end_date='+end_date+'&cashier='+cashier+'&transaction='+transaction+"&Registration_ID="+Registration_ID+"&Patient_Name="+Patient_Name+"&sangira_code="+sangira_code+"&payment_direction="+payment_direction, '_blank');
    }
    $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#date_From').datetimepicker({value: '', step: 01});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#date_To').datetimepicker({value: '', step: 01});
</script>
<?php
include("./includes/footer.php");
?>
