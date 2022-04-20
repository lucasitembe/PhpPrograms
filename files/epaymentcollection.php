<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
?>
    <a href='epaymentcollectionreports.php?ePaymentCollectionReports=ePaymentCollectionReportsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } ?>

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
<br/><br/>
<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>
<!-- end of the function -->
<fieldset>
    <center>
        <table width = 90%>
            <tr>
                <td  style='text-align: center;'>
                    <select name="Payment_Mode" id="Payment_Mode">
                        <option selected="selected" value="">~~~~ select mode ~~~~</option>
                        <option value="Bank_Payment">Bank Payment</option>
                        <option value="Mobile_Payemnt">Mobile Payment</option>
                    </select>
                    <select name="Report_Type" id="Report_Type">
                        <option selected="selected" value="">~~~~ select type ~~~~</option>
                        <option>Pending Transactions</option>
                        <option>Completed Transactions</option>
                        <option>Others</option>
                    </select>
                    <select id='Terminal_ID' class="select2-default" style='text-align: center;width:15%;display:inline'>
                        <option selected value="all">All Terminals</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT DISTINCT(Terminal_ID)  FROM tbl_bank_api_payments_details ORDER BY Terminal_ID ASC
                                ") or die(mysqli_error($conn));

                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Terminal_ID']; ?>"><?php echo $data['Terminal_ID']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                 <input type='text' name='Date_From' id='Date_From' style='text-align: center;width:15%;display:inline' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
                 <input type='text' name='Date_To' id='Date_To'  style='text-align: center;width:15%;display:inline' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
                 <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Update_Patient_List()">

                 <input type="button" value="PREVIEW" class="art-button-green" onclick="Preview_Report()">
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white;' id='Patient_List'>
    <legend align=right><b>ePayment Collections Reports</b></legend>
    <center>
        <table width = 100%>
            <tr><td colspan="8"><hr></td></tr>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="10%"><b>PATIENT NUMBER</b></td>
                <td width="12%"><b>SPONSOR</b></td>
                <td width="15%" style="text-align: right;"><b>PREPARED DATE</b></td>
                <td width="15%" style="text-align: right;"><b>EMPLOYEE PREPARED</b></td>
                <td width="10%" style="text-align: right;"><b>BILL NUMBER</b></td>
                <td width="10%" style="text-align: right;"><b>AMOUNT REQUIRED</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
        </table>
    </center>
</fieldset>
<center><span style='color: #037CB0;'><i>Click transaction detail to view more information</i></span></center>

<div id="DisplayTransactionDetails" style="width:50%;" >
    <center id='Details_Area'>
    <table width=100% style='border-style: none;'>
        <tr>
        <td>
            
        </td>
        </tr>
    </table>
    </center>
</div>
<script>
    function Update_Patient_List(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Report_Type = document.getElementById("Report_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Terminal_ID = document.getElementById("Terminal_ID").value;

        if(Report_Type == null || Report_Type == '' || Payment_Mode == null || Payment_Mode == ''){
            if(Report_Type == null || Report_Type == ''){
                document.getElementById("Report_Type").focus();
                document.getElementById("Report_Type").style = 'border: 3px solid red;';
            }else{
                document.getElementById("Report_Type").style = 'border: 3px solid white;';
            }
            if(Payment_Mode == null || Payment_Mode == ''){
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red;';
            }else{
                document.getElementById("Payment_Mode").style = 'border: 3px solid white;';
            }
        }else{
            document.getElementById("Report_Type").style = 'border: 3px solid white;';
            document.getElementById("Payment_Mode").style = 'border: 3px solid white;';
            document.getElementById('Patient_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if(window.XMLHttpRequest){
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Patient_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            if(Payment_Mode == 'Bank_Payment' && Report_Type == 'Pending Transactions'){
                myObject.open('GET','ePayment_Update_Patients_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Report_Type='+Report_Type+'&Payment_Mode='+Payment_Mode,true);
                myObject.send();
            }else if(Payment_Mode == 'Bank_Payment' && Report_Type == 'Completed Transactions'){
                myObject.open('GET','ePayment_Paid_Patients_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Report_Type='+Report_Type+'&Payment_Mode='+Payment_Mode+'&Terminal_ID='+Terminal_ID,true);
                myObject.send();
            }else{
                myObject.open('GET','ePayment_Update_Patients_List2.php',true);
                myObject.send();
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Report_Type = document.getElementById("Report_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Terminal_ID = document.getElementById("Terminal_ID").value;

        window.open('epaymentcollectionpreview.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Report_Type='+Report_Type+'&Payment_Mode='+Payment_Mode+'&Terminal_ID='+Terminal_ID,'_blank');
    }
</script>

<script>
    function open_Dialog(Transaction_ID,Source){
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function (){
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
            document.getElementById('Details_Area').innerHTML = data29;
            $("#DisplayTransactionDetails").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetDetails.open('GET','Patient_Transaction_Details.php?Transaction_ID='+Transaction_ID+'&Source='+Source,true);
        myObjectGetDetails.send();
    }
</script>

<script>
   $(document).ready(function(){
      $("#DisplayTransactionDetails").dialog({ autoOpen: false, width:'90%',height:400, title:'PATIENT TRANSACTION DETAILS',modal: true});      
   });
</script>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:1});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:1});
    </script>
    <!--End datetimepicker-->

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="js/ecr_pmnt.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<?php
    include("./includes/footer.php");
?>