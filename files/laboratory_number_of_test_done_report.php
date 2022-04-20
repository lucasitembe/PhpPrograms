<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href="Laboratory_Reports.php?LaboratoryResultsThisPage=ThisPage" class="art-button-green">BACK</a>
<br/>
<br/>
<fieldset>
    <legend align="center"><b>NUMBER OF TEST DONE REPORT</b></legend>
    <!--<div class="row">-->
    <table align="center" class="table">
        <td style="width: 50%"></td>
                <td><input type="text" name="date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" autocomplete="off" style="text-align: center;" readonly="readonly"></td>
                <td><input type="text" name="date2" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~" autocomplete="off" style="text-align: center;" readonly="readonly"></td>
                <td>
                    <input type='button' class="art-button-green" value="FILTER" onclick="filter_test_report()"/>
                </td>
            </table>
        <div class="col-md-12" style="height:400px;background: #FFFFFF;overflow-y: scroll">
            <table class="table">
                <caption><b>NUMBER OF TEST DONE REPORT</b></caption>
                <tr>
                    <td width="5%"><b>S/No.</b></td>
                    <td><b>Number Of Patient</b></td>
                    <td><b>Number Of Test </b></td>
                    <td><b>Date</b></td>
                </tr>
                <tbody id="number_of_test_done_report_body">
                    
                </tbody>
            </table>
        </div>
    <!--</div>-->
</fieldset>
<script>
    function filter_test_report(){
        var start_date=$("#date").val();
        var end_date=$("#date2").val();
        var validate=0;
        if(start_date==""){
            $("#date").css("border","2px solid red");
            validate++;
        }else{
           $("#date").css("border",""); 
        }
        if(end_date==""){
            $("#date2").css("border","2px solid red");
            validate++;
        }else{
           $("#date2").css("border",""); 
        }
        if(validate<=0){
         document.getElementById('number_of_test_done_report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

          $.ajax({
              type:'POST',
              url:'ajax_number_of_test_done_report.php',
              data:{start_date:start_date,end_date:end_date},
              success:function(data){
                  $("#number_of_test_done_report_body").html(data);
              }
            });  
        }
    }
</script>
<?php
include("./includes/footer.php");
?>