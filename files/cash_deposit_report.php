<?php
include("./includes/header.php");
@session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
    <a href='cash_deposit.php' class='art-button-green'> BACK </a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>CASH DEPOSIT REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>Date To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;                    
                    <b>Patient Number:</b><input type="text" class="form-control" style='text-align: center;width:10%;display:inline' placeholder="Enter Registartion No."  id='patient_number'>                    
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="filter_data();"> 
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
        var patient_number = $("#patient_number").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'cash_deposit_report_iframe.php',
                    type:'POST',
                    data:{fromDate:fromDate,patient_number:patient_number,toDate:toDate,bill_staus:bill_staus,Sponsor_ID:Sponsor_ID},
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
   
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').select2();
    });
</script>