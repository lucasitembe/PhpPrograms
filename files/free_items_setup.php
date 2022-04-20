<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }


     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Current_Username = $_SESSION['userinfo']['Given_Username'];

    // $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE Free_Items_works='yes' AND Given_Username='$Current_Username'";

    // $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    // if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
        <!-- <script>
            var privalege= alert("You don't have the privelage to access this button")
                document.location="./index.php?InvalidPrivilege=yes";
        </script> -->
        <?php
    // }
    

   if(isset($_SESSION['userinfo'])){
?>
    <a href='msamahapanel.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>
        BACK
    </a>
<?php  }




?>

<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>

<fieldset>
    <legend align="center"> FREE ITEMS CONFIGURATION </legend>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>LIST OF ALL ITEMS</h4><br>
                </div>
               <input type="text" id='item_search_value' onkeyup="search_items()"placeholder="search item name" class="form-control" style="width:90%; text-align:center;"/></span></caption>
               <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_items'>SELECT ALL ITEMS
                            </label>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 420px;overflow-y: auto;overflow-x: auto">
                    <div id="items_list">
                        <table class="table">
                        <?php
                        $sql_result=mysqli_query($conn,"SELECT * FROM tbl_items  WHERE  Status='Available' AND Consultation_Type='Laboratory' LIMIT 20") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_result)>0){
                                        while($category_rows=mysqli_fetch_assoc($sql_result)){
                                            $Item_ID=$category_rows['Item_ID'];
                                                $Product_Name=$category_rows['Product_Name'];
                                                echo "<tr>
                                                            <td>
                                                                <label style='font-weight:normal'>
                                                                    <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
                                                                </label>
                                                            </td>
                                                            
                                                    </tr>";
                                        }
                                    }else{
                                        echo "<tr>
                                                    <td>
                                                        <label style='color:red;'>
                                                            SORRY, NO RESULT FOUND!
                                                        </label>
                                                    </td>
                                                    
                                            </tr>";
                                    }
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                  <h4>LIST OF SPONSORS</h4>
                </div>
                <input type="text" id='sponsor_search_value' onkeyup="search_sponsor()"placeholder="Search sponsor name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>
                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_sponsors'>SELECT ALL SPONSORS
                            </label>
                        </td>
                        <td width="5%">
                        <a class="btn btn-default pull-right btn-sm" onclick="add_free_items()" style="text-decoration:none!important;"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-1x"></i> ADD TO FREE ITEMS</a>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 417px;overflow-y: auto;overflow-x: hidden">
                    <div id="sponsors_list">
                        <table class="table">
                        <?php 
                        $sql_result=mysqli_query($conn,"SELECT * FROM tbl_sponsor LIMIT 20") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_result)>0){
                            while($sponsor_rows=mysqli_fetch_assoc($sql_result)){
                                $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                $Guarantor_Name =$sponsor_rows['Guarantor_Name'];
                                        echo  "<tr>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox' class='Sponsor_ID' name='Sponsor_ID' value='$Sponsor_ID'> $Guarantor_Name
                                                        </label>
                                                    </td>
                                                </tr>";
                                }
                            }else{
                                echo "<tr>
                                        <td>
                                            <label style='color:red;'>
                                                SORRY, NO RESULT FOUND!
                                            </label>
                                        </td>  
                                    </tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>LIST OF FREE ITEMS</h4>
                </div>

                <input type="text" id='search_free_item_value' onkeyup="search_free_item_sponsor()" placeholder="search by sponsor name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>

                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_free_items'>SELECT ALL FREE ITEMS
                            </label>
                        </td>
                        <td width="5%">
                        <a href="#" class="btn btn-default pull-right btn-sm" onclick="delete_free_items()" style="color:red;text-decoration:none!important;"><i id="attach_cat_icon"  class="fa fa-trash fa-1x"></i> DELETE FREE ITEM(S)</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center class="text-info">
                                <span id="msg"></span>
                            </center>
                        </td>
                    </tr>
                </table>

                <div class="loading" style="display:none">
                    <center>
                        <img style="border: none;" src="images/please_wait_loading.gif" alt="Loading gif" class="img img-responsive">
                    </center>
                </div>

                <div class="box-body" id="box-body"  style="height: 420px; overflow-y: auto; overflow-x: hidden">
                    <div id='free_items_list'>
                        <table class="table">
                            <?php 
                            
                                $get_sponsors = mysqli_query($conn,"SELECT DISTINCT f.sponsor_id, s.Guarantor_Name FROM tbl_free_items AS f, tbl_sponsor AS s WHERE f.sponsor_id = s.Sponsor_ID");
                                if(mysqli_num_rows($get_sponsors)){
                                while ($row1=mysqli_fetch_array($get_sponsors)) {
                                    $sponsor_id = $row1['sponsor_id'];
                            ?>
                                <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
                                    <th colspan="2"><?= $row1['Guarantor_Name'] ?></th>
                                </tr>
                            <?php 
                             $get_free_items = mysqli_query($conn,"SELECT free_item_id, item_id FROM tbl_free_items WHERE sponsor_id='$sponsor_id'  ORDER BY item_id ASC");
                             $i=1;
                             while ($row2=mysqli_fetch_array($get_free_items)) { 
                                 $item_id = $row2["item_id"];
                                 $item_name = mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id' AND Consultation_Type='Laboratory'");
                                 $row3 = mysqli_fetch_array($item_name);
                                 
                                 $item_name = $row3['Product_Name'];
                            ?>
                                <tr>
                                    <td width="1%" style="border:1px solid #328CAF!important;"><?= $i ?></td>
                                    <td width="99%" style="border:1px solid #328CAF!important;">
                                        <label for="free_item_id_<?= $row2['free_item_id'] ?>"  style='font-weight:normal'>
                                            <input type="checkbox" id="free_item_id_<?= $row2['free_item_id'] ?>" class="free_item_id" value="<?= $row2['free_item_id'] ?>"> <?= $row3['Product_Name'] ?>
                                        </label>
                                    </td>
                                </tr>
                            <?php $i++;}}}else{?>
                                <tr>
                                    <td width="10%"></td>
                                    <td width="90%">
                                        <label>
                                            <b style="color: red;">NO, FREE ITEM FOUND!</b>
                                        </label>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<script>

$("#select_all_items").click(function (e){
    $(".Item_ID").not(this).prop('checked', this.checked); 
});

$("#select_all_sponsors").click(function (e){
    $(".Sponsor_ID").not(this).prop('checked', this.checked); 
});

$("#select_all_free_items").click(function (e){
    $(".free_item_id").not(this).prop('checked', this.checked); 
});

function search_items(){
    var item_search_value = $("#item_search_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_item_search.php',
        data:{item_search_value:item_search_value},
        success:function(data){
            $("#items_list").html(data); 
        }
    });
}

function search_sponsor(){
    var sponsor_search_value = $("#sponsor_search_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_sponsor_search.php',
        data:{sponsor_search_value:sponsor_search_value},
        success:function(data){
            $("#sponsors_list").html(data); 
        }
    });
}

function search_free_item_sponsor(){
    var search_free_item_value = $("#search_free_item_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_search_free_item.php',
        data:{search_free_item_value:search_free_item_value},
        success:function(data){
            $("#free_items_list").html(data); 
        }
    });
}

function delete_free_items(){
    var selected_free_items = []; 
    $(".free_item_id:checked").each(function() {
        selected_free_items.push($(this).val());
    });

    if(selected_free_items==""){
        alert("Select free item to delete.");
    }else{
        if (confirm('Are you sure you want to delete? This action is irreversible!.')){
            $('#box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_delete_free_item.php',
                data:{selected_free_items:selected_free_items},
                success:function(data){
                    $('.loading').hide();$('#box-body').show();
                    // alert(data);
                    // alert('Successfully deleted free item(s)');
                    $("#msg").html('Successfully deleted free item(s)'); 
                    $("#free_items_list").load(" #free_items_list"); //consider space 
                    
                }
            });
        }
    } 
    
}

function add_free_items(){
    var selected_items = []; 
    $(".Item_ID:checked").each(function() {
        selected_items.push($(this).val());
    });

    var selected_sponsor = []; 
    $(".Sponsor_ID:checked").each(function() {
        selected_sponsor.push($(this).val());
    });

    if(selected_items==""){
        alert("Item must be selected first.");
    }else if(selected_sponsor==""){
        alert("Sponsor must be selected before submitting.");
    }
    else{
        if (confirm('Are you sure you want to add selected item(s) to free items?')){
            $('.box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_add_free_items.php',
                data:{selected_items:selected_items,selected_sponsor:selected_sponsor},
                success:function(data){
                    $('.loading').hide();$('.box-body').show();
                    $("#free_items_list").load(" #free_items_list"); //consider the space 
                    // alert('Successfully added free item(s)');
                    $("#msg").html('Successfully added free item(s)');
                }
            });
        }
    }
}

</script>

<?php
include("./includes/footer.php");
?>
