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
    <legend align=center><b>ATTACH CATEGORY TO MAIN DEPARTMENT</b></legend>
    <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center"class="form-control"id="all_category_search_box" onkeyup="search_category_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Category</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_category_to_main_department()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_category_list_body">
                        <table class="table">
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_item_category") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $Item_Category_ID=$category_rows['Item_Category_ID'];
                                    $Item_Category_Name=$category_rows['Item_Category_Name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='category_id' name='category_id' value='$Item_Category_ID'>$Item_Category_Name
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
                    <label>SELECT MAIN DEPARTMENT</label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control" id="idara_id"onchange="refresh_content()"> 
                        <option value="">---------Select Main Department------------------</option> 
                        <?php 
                            $sql_select_main_department_result=mysqli_query($conn,"SELECT * FROM tbl_idara_kuu  WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_main_department_result)>0){
                                while($idar_kuu_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                                   $idara_name=$idar_kuu_rows['idara_name'];
                                   $idara_id=$idar_kuu_rows['idara_id'];
                                   echo "<option value='$idara_id'>$idara_name</option>";   
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
                            <div class="col-sm-12"><p id="category_list_tittle" style="font-size:17px">Category attached to </p></div>
                            <div class="col-sm-6" style="display:none">
                                <input type="text" style="text-align:center" class="form-control" id="attached_category_search_box" placeholder="--------Search--------" />
                            </div>
                        </div>
                        <div class="box-body" id="attached_category_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
 <script>
     $("#select_all_checkbox").click(function (e){
         $(".category_id").not(this).prop('checked', this.checked); 
         
     });    
     function attach_category_to_main_department(){
        var selectedCategory = []; 
        $(".category_id:checked").each(function() {
		selectedCategory.push($(this).val());
	});
        var idara_id=$("#idara_id").val()
        if(idara_id==""){
          $("#idara_id").css("border","1px solid red");
          alert("Select Main Department To attach Selected Category");
          exit;
        }else{
          $("#idara_id").css("border","");  
        }
        $.ajax({
            type:'POST',
            url:'ajax_attach_category_to_main_department.php',
            data:{selectedCategory:selectedCategory,idara_id:idara_id},
            success:function(data){
                $("#attached_category_body").html(data);
                refresh_content()
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         var idara_id=$("#idara_id").val();
         $("#select_all_checkbox").prop("checked",false)

         var sel = document.getElementById("idara_id");
         var idara_name= sel.options[sel.selectedIndex].text;
         
         $("#category_list_tittle").html("List of Category attached to <b>"+idara_name+"</b>")
         $.ajax({
             type:'POST',
             url:'refresh_category_main_department_list.php',
             data:{idara_id:idara_id},
             success:function(data){
               $("#attached_category_body").html(data);  
             }
         });
         $.ajax({
             type:'POST',
             url:'refresh_all_category_list.php',
             data:{idara_id:idara_id},
             success:function(data){
                  $("#all_category_list_body").html(data); 
             }
         });
     }
function search_category_from_all_list(){
    var Item_Category_Name = $("#all_category_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_all_category_list.php',
             data:{Item_Category_Name:Item_Category_Name},
             success:function(data){
                  $("#all_category_list_body").html(data); 
             }
         });
}
</script>