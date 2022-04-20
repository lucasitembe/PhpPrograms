<?php
    /*+++++++++++++++++++ Designed and implimented  +++++++++++++++++++++++++++++
    ++++++++++++++++++++ by Eng. Eng. Msk moscow  Since 2020-07-13  ++++++++++++++*/
include("./includes/header.php");
@session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['Imbalance_bills'])){
?>
        <a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'> BACK </a>

<?php }else{
        echo "<a href='debitreductionreport.php' class='art-button-green'> BACK </a>";
    }
 ?>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>PATIENTS WITH IMBALANCE BILLS REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>Date To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <b>Debit Status :</b>
                    <select name="bill_staus" id="bill_staus" style='text-align: center;width:15%;display:inline'>
                        <option value="">--select status--</option>
                        <option value="All">All</option>
                        <option value="cleared">Paid</option>
                        <option value="Not cleared">Un Paid</option>
                        <option value="Debt extended">Debit Extended</option>                
        		    </select>
                    <b>Patient Number:</b><input type="text" class="form-control" style='text-align: center;width:10%;display:inline' placeholder="Enter Registartion No."  id='Registration_ID'>
                    
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="filter_data();"> 
                    <!-- <input type="button" name="filter" value="PREVIEW PDF" class="art-button-green" onclick="filter_data();">  -->

                </td>
            </tr>

        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td colspan='8'>
                    <div style="width:100%; height:450px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
            <?php
              
            ?>
        </table>

    </center>
</fieldset>
<div id="patientinservice"></div>
<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
</script>
<script type="text/javascript">

    function filter_data(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var bill_staus=$("#bill_staus").val();
        var Sponsor_ID=$("#Sponsor_ID").val();
       var Registration_ID=$("#Registration_ID").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'patientwith_imbalance_bills_iframe.php',
                    type:'post',
                    data:{fromDate:fromDate,toDate:toDate,Registration_ID:Registration_ID,bill_staus:bill_staus,Sponsor_ID:Sponsor_ID},
                    success:function(result){
                        if (result != '') {
                            $('#Search_Iframe').html(result);
                        }
                    }
                });
            }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }

    function open_dialogpaydebit(Debt_ID,Registration_ID, Patient_Name, Amount_remained){
        var Patient_debt = $("#Patient_debt_"+Debt_ID).val();
        
        $.ajax({
            type:'POST',
            url:'Ajax_patient_debt.php',
            data:{ Debt_ID:Debt_ID, Registration_ID:Registration_ID,Patient_debt:Patient_debt, paydebit:'' },
            success:function(responce){                
                $("#patientinservice").dialog({
                        title: 'PATIENTS NAME '+Patient_Name +" AMOUNT REMAINED "+ Amount_remained +".00/=",
                        width: '40%',
                        height: 350,
                        modal: true,
                });
                $("#patientinservice").html(responce);
            }
        });
    }
   
    function send_to_cash_deposit(Debt_ID){
        var Patient_debt = $("#Patient_debt_"+Debt_ID).val();
        if(confirm("Are you sure, Sending this patient to cashier to clear this debit?")){
            $.ajax({
                type:'POST',
                url:'Ajax_patient_debt.php',
                data:{Debt_ID:Debt_ID, Patient_debt:Patient_debt, debt_cash_deposit:''},
                success:function(responce){                
                    alert(responce);
                }
            });
        }
    }


    function process_patient_debt(Debt_ID){
        if(confirm("Are you sure you want to process this debit? ")){
            $.ajax({
                type:'POST',
                url:'Ajax_patient_debt.php',
                data:{ Debt_ID:Debt_ID, process_patient_debt:''},
                success:function(responce){
                    alert(responce)
                    location.reload();
                }
            });
        }
    }


    function send_to_socialwalfare(Debt_ID, patient_debt){
        var Registration_ID = $("#Registration_ID"+Debt_ID).val();
        var Patient_Bill_ID = $("#Patient_Bill_ID"+Debt_ID).val();
        alert(Patient_Bill_ID)
        if(confirm("Are you sure you want to send this patient to social walfare unit?")){        
            $.ajax({
                type:'POST',
                url:'Ajax_patient_debt.php',
                data:{ Debt_ID:Debt_ID, Registration_ID:Registration_ID, patient_debt:patient_debt, Patient_Bill_ID:Patient_Bill_ID, process_debt:''},
                success:function(responce){
                    alert(responce);
                // location.reload();
                }
            });
        }
    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').select2();
    });
</script>