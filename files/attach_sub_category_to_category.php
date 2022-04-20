<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<a href='main_department_category_item_attachment_configuration.php' class='art-button-green'>
    BACK
</a>
<br/>
<br/>
<style>
    #attach_cat_icon{
        transform: rotate(45deg);
    }
</style>
<fieldset>
    <legend align=center><b>ATTACH SUB CATEGORY TO CATEGORY</b></legend>
    <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center"class="form-control"id="all_sub_category_search_box" onkeyup="search_sub_category_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Sub Category</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_sub_category_to_category()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_sub_category_list_body">
                        <table class="table">
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $Item_Subcategory_ID=$category_rows['Item_Subcategory_ID'];
                                    $Item_Subcategory_Name=$category_rows['Item_Subcategory_Name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_Subcategory_ID' name='Item_Subcategory_ID' value='$Item_Subcategory_ID'>$Item_Subcategory_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-5">
                    <label>SELECT CATEGORY</label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control" id="Item_Category_ID"onchange="refresh_content()"> 
                        <option value="">---------Select Category------------------</option> 
                        <?php 
                            $sql_select_main_department_result=mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_main_department_result)>0){
                                while($idar_kuu_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                                   $Item_Category_Name=$idar_kuu_rows['Item_Category_Name'];
                                   $Item_Category_ID=$idar_kuu_rows['Item_Category_ID'];
                                   echo "<option value='$Item_Category_ID'>$Item_Category_Name</option>";   
                                } 
                            }
                        ?>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br/>
                    <div class="box box-primary" style="height: 400px;overflow: auto">
                        <div class="box-header">
                            <div class="col-sm-12"><p id="sub_category_list_tittle" style="font-size:17px">Sub Category attached to </p></div>
                            <div class="col-sm-6" style="display:none">
                                <input type="text" style="text-align:center" class="form-control" id="attached_sub_category_search_box" placeholder="--------Search--------" />
                            </div>
                        </div>
                        <div class="box-body" id="attached_sub_category_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
 <script>
     $("#select_all_checkbox").click(function (e){
         $(".Item_Subcategory_ID").not(this).prop('checked', this.checked); 
         
     });    
     function attach_sub_category_to_category(){
        var selected_sub_Category = []; 
        $(".Item_Subcategory_ID:checked").each(function() {
		selected_sub_Category.push($(this).val());
	});
        
        var Item_Category_ID=$("#Item_Category_ID").val()
        if(Item_Category_ID==""){
          $("#Item_Category_ID").css("border","1px solid red");
          alert("Select Category To attach Selected Sub Category");
          exit;
        }else{
          $("#Item_Category_ID").css("border","");  
        }
        $.ajax({
            type:'POST',
            url:'ajax_attach_sub_category_to_category.php',
            data:{selected_sub_Category:selected_sub_Category,Item_Category_ID:Item_Category_ID},
            success:function(data){
               
                $("#attached_sub_category_body").html(data);
                refresh_content()
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         var Item_Category_ID=$("#Item_Category_ID").val();
         $("#select_all_checkbox").prop("checked",false)

         var sel = document.getElementById("Item_Category_ID");
         var category_name= sel.options[sel.selectedIndex].text;
         
         $("#sub_category_list_tittle").html("List of Category attached to <b>"+category_name+"</b>")
         $.ajax({
             type:'POST',
             url:'refresh_category_list.php',
             data:{Item_Category_ID:Item_Category_ID},
             success:function(data){
               $("#attached_sub_category_body").html(data);  
             }
         });
         $.ajax({
             type:'POST',
             url:'refresh_all_sub_category_list.php',
             data:{Item_Category_ID:Item_Category_ID},
             success:function(data){
                  $("#all_sub_category_list_body").html(data); 
             }
         });
     }
function search_sub_category_from_all_list(){
    var Item_Subcategory_Name = $("#all_sub_category_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_all_sub_category_list.php',
             data:{Item_Subcategory_Name:Item_Subcategory_Name},
             success:function(data){
                  $("#all_sub_category_list_body").html(data); 
             }
         });
}
</script>