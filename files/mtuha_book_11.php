<?php 
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
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
<a href='mtuha_book_11_report_setup.php' class='art-button-green'>REPORT SETUP</a>
<a href="add_matibabu.php" class='art-button-green'>ADD TREATMENT</a>
<a href="attach_matibabu.php" class='art-button-green'>TREATMENT ATTACHMENT</a>
<a href='governmentReports.php' class='art-button-green'>BACK</a>
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
<fieldset>
    <legend align="center"><b>MTUHA BOOK 11</b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td>
                    <select style="width:100%;height: 30px" id="diagnosis_type">
                        <option value="All">All Diagnosis</option>
                        <option value="diagnosis">Final Diagnosis</option>
                        <option value="provisional_diagnosis">Provisional Diagnosis</option>
                        <option value="diferential_diagnosis">Differential Diagnosis</option>
                    </select>
                </td>
                <td>
                    <select style="width:100%;height: 30px" id="process_status">
                        <option value="All">All Procedure</option>
                        <option value="sent_procedure">Sent Procedure</option>
                        <option value="done_procedure">Done Procedure</option>
                    </select>
                </td>
                <td><input type="button" value="FILTER" onclick="filter_mtuha_book_11_report()" class="art-button-green"/></td>
                <td><input type="button" value="EXPORT TO EXCEL" onclick="export_mtuha_book_11_report_to_excel()" class="art-button-green"/></td>
                <!--<td><input type="button" value="Card/Mobile CONFIRM PAYMENT" onclick="confirm_mobile_payment()" class="art-button-green"/></td>-->
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden" id="mtuha_report_book_body">
       
    </div>
</fieldset>
<div id="mtuha_book_11_div"></div>
<div id="mtuha_book_11_truma"></div>
<div id="mtuha_book_11_treatment"></div>
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
    
    function filter_mtuha_book_11_report(){
       var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       var diagnosis_type=$('#diagnosis_type').val()
       var process_status=$('#process_status').val()
               document.getElementById('mtuha_report_book_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_filter_mtuha_book_11_report.php',
            data:{start_date:start_date,end_date:end_date,diagnosis_type:diagnosis_type,process_status:process_status},
            success:function(data){
               $("#mtuha_report_book_body").html(data)  
            }
        });
    }
    function export_mtuha_book_11_report_to_excel(){
       var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       var diagnosis_type=$('#diagnosis_type').val()
       var process_status=$('#process_status').val()
       window.open("export_mtuha_book_11_report_to_excel.php?start_date="+start_date+"&end_date="+end_date+"&diagnosis_type="+diagnosis_type+"&process_status="+process_status,"_blank")
    }
    
</script>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
function open_mtuha_book_11_detail(subcategory_ID,subcategory_description){
       var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       var diagnosis_type=$('#diagnosis_type').val()
       var process_status=$('#process_status').val()
       $.ajax({
           type:'POST',
           url:'ajax_open_mtuha_book_11_detail.php',
           data:{subcategory_ID:subcategory_ID,start_date:start_date,end_date:end_date,diagnosis_type:diagnosis_type,process_status:process_status},
           success:function(data){
                $("#mtuha_book_11_div").html(data);
               $("#mtuha_book_11_div").dialog({
                        title: subcategory_description+' :- DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    }); 
                   
           }
       });
    }

    function open_mtuha_book_11_cause_of_trauma(hosp_course_injury_ID,subcategory_description){
       var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       $.ajax({
           type:'POST',
           url:'ajax_open_mtuha_book_11_detail_trauma.php',
           data:{start_date:start_date,end_date:end_date,hosp_course_injury_ID:hosp_course_injury_ID},
           success:function(data){
                $("#mtuha_book_11_truma").html(data);
               $("#mtuha_book_11_truma").dialog({
                        title: subcategory_description+' :- DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    }); 
                   
           }
       });
    }

    function open_mtuha_book_11_to_come_again(to_come_again_id,to_come_again_reason){
        var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       $.ajax({
           type:'POST',
           url:'ajax_open_mtuha_book_11_detail_trauma.php',
           data:{start_date:start_date,end_date:end_date,to_come_again_id:to_come_again_id,to_come_again_reason:to_come_again_reason},
           success:function(data){
                $("#mtuha_book_11_truma").html(data);
               $("#mtuha_book_11_truma").dialog({
                        title: to_come_again_reason+' :- DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    }); 
                   
           }
       });
    }
    function open_mtuha_book_11_treatment_details(Treatment_ID ,name_of_treatment){
        var start_date=$('#start_date').val()
       var end_date=$('#end_date').val()
       var process_status=$('#process_status').val()
       $.ajax({
           type:'POST',
           url:'ajax_open_mtuha_book_11_treatment_details.php',
           data:{start_date:start_date,end_date:end_date,Treatment_ID:Treatment_ID,name_of_treatment:name_of_treatment,process_status:process_status},
           success:function(data){
                $("#mtuha_book_11_treatment").html(data);
               $("#mtuha_book_11_treatment").dialog({
                        title: name_of_treatment+' :- DETAILS ',
                        width: '70%',
                        height: 450,
                        modal: true,
                    }); 
                   
           }
       });
    }
    
</script>