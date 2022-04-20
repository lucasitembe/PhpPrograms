<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['edit_items'] != 'yes'){
           header("Location: ./index.php?InvalidPrivilege=yes");
        }
        }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
        }
?>
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
</style>
<input type="button" class="art-button-green" onclick="history.go(-1)" value="[BACK]">
<fieldset>
    <legend align='center'>INCLUSIVE SERVICE SETUP </legend>
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <input type='text' placeholder="~~~~~~~~~~Enter Item Name~~~~~~~~~~~~" id="Item_name" onkeyup='search_item()' style='text-align:center'>
    </div>
    <div class="col-md-2">
    </div>
    <div class="col-md-6" style='height:400px;overflow-y:scroll'>
        <table class='table' style='background:#FFFFFF'>
            <caption><b>LIST OF ALL ITEMS</b></caption>
            <tr>
                <th>S/No.</th>
                <th>ITEM NAME</th>
                <th>SPONSOR</th>
            </tr>
            <tbody id='list_of_all_item'>
            
            </tbody>
        </table>
    </div>
    <div class="col-md-6" style='height:400px;overflow-y:scroll'>
        <table class='table' style='background:#FFFFFF' >
            <caption><b>LIST OF SELECTED ITEM</b></caption>
            <tr>
                <th>S/No.</th>
                <th>ITEM NAME</th>
                <th>SPONSOR NAME</th>
                <th>REMOVE</th>
            </tr>
            <tbody id='list_of_saved_item'>

            </tbody>
        </table>
    </div>
    
</fieldset>
<script>
    $(document).ready(function () {
        $('select').select2();
        search_item();
        attached_item();
    })
</script>
<script>
    $(document).ready(function(){
        // search_item();
        // attached_item();
    });
    function search_item(){
        var Item_name = $("#Item_name").val();
        $.ajax({
            type:'POST',
            url: 'Ajax_theater_setup.php',
            data:{Item_name:Item_name, searchitems:''},
            success:function(responce){
                $("#list_of_all_item").html(responce)
            }
        });
    }

    function save_free_item(Item_ID){
        var Sponsor_ID = $("#Sponsor_ID_"+Item_ID).val();
        if(Sponsor_ID==''){
            $("#Sponsor_ID").css("border","2px solid red");
            exit;
        }else{
            $("#Sponsor_ID").css("border","2px solid gray");
        }
        if(confirm("Are you sure you want to free this service for this sponsor??")){
            $.ajax({
                type:'POST',
                url: 'Ajax_theater_setup.php',
                data:{Item_ID:Item_ID,Sponsor_ID:Sponsor_ID, saveitem:''},
                success:function(responce){
                    console.log(responce);
                    attached_item();
                }
            });
        }
    }
    
    function attached_item(){
        $.ajax({
            type:'POST',
            url: 'Ajax_theater_setup.php',
            data:{ dispalysaved:''},
            success:function(responce){
                $("#list_of_saved_item").html(responce)
            }
        });
    }

    function remove_item(Inclusive_ID){
        if(confirm("Are you sure want to remove this servicee")){
            $.ajax({
                type:'POST',
                url: 'Ajax_theater_setup.php',
                data:{Inclusive_ID:Inclusive_ID, removeitem:''},
                success:function(responce){
                    if(responce=='No'){
                        console.log(responce)
                        alert("Failed to remove");
                    }else if(responce=='Exist'){
                        alert("Service already attached");
                    }else{
                    attached_item() 
                    }
                    
                }
            });
        }
    }
</script>

<?php
include("./includes/footer.php");
?>
