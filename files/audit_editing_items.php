<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
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
<a href="managementworkspage.php" class="art-button-green">BACK</a>
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
<fieldset>
    <legend align='center'><b>AUDIT TRAIL - ITEM(s) EDIT REPORT</b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Product Name" id='Product_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Product Number" id="Product_ID"/></td>
                <td><input type="button" value="FILTER" onclick="filter_list_of_patient_sent_to_cashier()" class="art-button-green"/></td>
                <td><input type="button" value="PRINT OUT REPORT" id="Preview_editpatient_pdf"  target='_blank' class="art-button-green"/></td>
                <!--<td><input type="button" value="Card/Mobile CONFIRM PAYMENT" onclick="confirm_mobile_payment()" class="art-button-green"/></td>-->
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <tr style='background: #dedede;'>
                <td style="width:50px"><b>S/No</b></td>
                <td width='7%' style='text-align: center;'><b>Product Code</b></td>
                <td width='20%' style='text-align: center;'><b>Item Name</b></td>
                <td style='text-align: center;'><b>Activity Done</b></td>
                <td width='15%' style='text-align: center;'><b>Edited Employee</b></td>
                <td width='10%' style='text-align: center;'><b>Edited DateTime</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>
<script>
    function filter_list_of_patient_sent_to_cashier(){
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        var Product_ID=$('#Product_ID').val();
        var Product_Name=$('#Product_Name').val();

        // $('#Preview_editpatient_pdf').attr('href','audit_trail_editreport_print.php?Product_Name=' + Product_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&ward=' + ward + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID+ '&Sponsor=' + Sponsor+'&row_num='+row_num);

        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'GET',
            url:'ajax_item_edit_report.php',
            data:{start_date:start_date,end_date:end_date,Product_ID:Product_ID,Product_Name:Product_Name},
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
            }
        });
    }
     $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<?php
    include("./includes/footer.php");
?>
