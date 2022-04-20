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
<a href='mtuha_book_11.php' class='art-button-green'>BACK</a>
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
    <legend align='center'><b>MTUHA BOOK 11 REPORT SETUP</b></legend>
    <div class="col-md-6">
        <input type='text' placeholder="Search Employee" id="Employee_Name" onkeyup="list_of_all_employee_body()" style='text-align:center;' class="form-control"/>
        <br/>
        <div class="box box-primary">
            <div class="box-header">
                <h4>
                    LIST OF ALL EMPLOYEE
                </h4>
            </div>
            <div class="box-body" style="height:370px;overflow-y: scroll;" id='list_of_all_employee_body'>
                
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <p style="margin-bottom:22px">Mtuha book 11 will filter data from the following selected employee</p>
        
        <div class="box box-primary">
            <div class="box-header">
                <h4>
                    LIST OF SELECTED EMPLOYEE
                </h4>
            </div>
            <div class="box-body" style="height:370px;overflow-y: scroll;" id='list_of_selected_employee_body'>
                
            </div>
        </div>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?> 
<script>
    function list_of_all_employee_body(){
        var Employee_Name=$("#Employee_Name").val();
        $.ajax({
            type:'POST',
            url:'ajax_list_of_all_employee_body_book_11.php',
            data:{Employee_Name:Employee_Name},
            success:function(data){
               $("#list_of_all_employee_body").html(data); 
            }
        });
    }
    
    function select_employee_for_mtuha_book_11(Employee_ID){
        $.ajax({
            type:'POST', 
            url:'ajax_select_employee_for_mtuha_book_11.php',
            data:{Employee_ID:Employee_ID},
            success:function(data){
                $("#list_of_selected_employee_body").html(data);
                display_selected_employee_for_mtuha_book_11()
            }
        });
    }
    function display_selected_employee_for_mtuha_book_11(){
        $.ajax({
            type:'POST',
            url:'ajax_display_selected_employee_for_mtuha_book_11.php',
            data:{},
            success:function(data){
                $("#list_of_selected_employee_body").html(data);
            }
        });
    }
    function remove_employee_for_mtuha_book_11(mtuha_book_11_report_employee_id){
        $.ajax({
            type:'POST',
            url:'ajax_remove_employee_for_mtuha_book_11.php',
            data:{mtuha_book_11_report_employee_id:mtuha_book_11_report_employee_id},
            success:function(data){
                display_selected_employee_for_mtuha_book_11()
            }
        });
    }
    $(document).ready(function(){
        list_of_all_employee_body()
        display_selected_employee_for_mtuha_book_11()
    });
</script>