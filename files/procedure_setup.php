<?php
@session_start();
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href="Procedure.php" class="art-button-green">BACK</a>
<fieldset>
    <legend align='center'><b>PROCEDURE LEGEND</b></legend>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary" style="height: 400px;overflow-y: scroll">
                <div class="box-header">
                    <div class="col-md-6"><h4 class="box-title">LIST OF ALL PROCEDURE</h4></div>
                    <div class="col-md-6">
                        <input type='text' placeholder="Search Procedure..." onkeyup="filter_search_all_procedure()" id='procedure_name' class="form-control"/>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <td width='50px'>S/No</td>
                            <td>Procedure Name</td>
                            <td width='50px'>Action</td>
                        </tr>
                        <tbody id='list_of_all_procedure_body'></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary" style="height: 400px;overflow-y: scroll">
                <div class="box-header">
                    <h4 class="box-title">LIST OF SELECTED PROCEDURE</h4>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <td width='50px'>S/No</td>
                            <td>Procedure Name</td>
                            <td width='50px'>Action</td>
                        </tr>
                        <tbody id='list_of_selected_procedure_body'></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>
<script>
    function filter_search_all_procedure(){
       var procedure_name=$("#procedure_name").val();
        $.ajax({
            type:'POST',
            url:'ajax_filter_search_all_procedure.php',
            data:{procedure_name:procedure_name},
            success:function(data){
                $("#list_of_all_procedure_body").html(data);
            }
        });
    }
    function shift_this_item_to_setup(Item_ID){
       $.ajax({
            type:'POST',
            url:'ajax_shift_this_item_to_setup.php',
            data:{Item_ID:Item_ID},
            success:function(data){
                $("#list_of_selected_procedure_body").html(data);
            }
        }); 
    }
    function remove_this_item_to_setup(Item_ID){
       $.ajax({
            type:'POST',
            url:'ajax_remove_this_item_to_setup.php',
            data:{Item_ID:Item_ID},
            success:function(data){
                $("#list_of_selected_procedure_body").html(data);
            }
        });  
    }
    $(document).ready(function (){
        filter_search_all_procedure();
        shift_this_item_to_setup(0)
    });
</script>
