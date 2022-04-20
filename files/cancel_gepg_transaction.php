<?php
include("./includes/header.php");

@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['from']) &&  $_SESSION['from']=="ebill"){
    unset($_SESSION['from']);
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href='edittransaction.php' class='art-button-green'>
    BACK
</a>
<br/>
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
        background: #CCCCCC;
        font-weight:bold;
    }
</style>
<fieldset>  
    <legend align=center><b>Cansel GePG Transaction</b></legend>
   <center> 
        <table>
            <tr>
            <td>
                <input type="tex" class="form-control" placeholder="Bill ID" style="background:#FFFFFF!important" id="billID"></td>
                <td><input type='button' value='Cansel Transaction' class='art-button-green' onclick='cancel_transaction()'/></td>
                <td></td>
                <td><input type="tex" class="form-control" placeholder="Bill ID" style="background:#FFFFFF!important" id="end_date"></td>
                <td><input type='button' value='FILTER' class='art-button-green' onclick='filter_caneled_transaction()'/></td>
            </tr>
        </table>
   </center>
    <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-hover">
            <tr>
                <td width="5%"><b>S/No.</b></td>
                <td>
                    <b>Employee Name</b>
                </td>
                <td>
                    <b>Patient Name</b>
                </td>
                <td>
                    <b>Bill ID</b>
                </td>
                <td>
                    <b>Bill Status</b>
                </td>
            </tr>
            <tbody id='report_body'>
            
            </tbody>
        </table>
    </div>
 </fieldset> 
<div id="selected_amount_div"></div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    function filter_caneled_transaction(){
       var end_date= $('#end_date').val();
       document.getElementById('report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_filter_gepg_cancel.php',
           data:{end_date:end_date},
           success:function(data){
               $("#report_body").html(data); 
           }
       });
    }

    function cancel_transaction(){
       var billID= $('#billID').val();
       var e_id = "<?php echo $_SESSION['userinfo']['Employee_ID']; ?>";
       document.getElementById('report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'gepg_processing_payfolder/bill/billCanelRequest.php',
           data:{billID:billID,employee:e_id},
           success:function(data){
               if (data === "7283"){
                   alert("Successfull Bill Cancelled");
                   $.ajax({
                        type:'POST',
                        url:'ajax_filter_gepg_cancel.php',
                        data:{end_date:billID},
                        success:function(data){
                            $("#report_body").html(data); 
                        }
                    });
               }else{
                 alert("Fail To Cancel Bill");
                 $("#report_body").html("");
               } 
           }
       });
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>