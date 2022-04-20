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
<a href='mtuha_book_11.php' class='art-button-green'>
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
    <legend align=center><b>ATTACH ITEM TO MAIN TREATMENT</b></legend>
    <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center"class="form-control"id="all_category_search_box" onkeyup="search_item_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Item</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_item_to_main_treatment()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_category_list_body">
                        <table class="table">
                        <?php 
                            $sql_select_item_result=mysqli_query($conn,"SELECT Item_ID,	Product_Name FROM tbl_items i WHERE i.Status='Available' AND Consultation_Type='Procedure' LIMIT 50") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_item_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_item_result)){
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
                    <label>SELECT MAIN TREATMENT</label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control" id="Treatment_ID" onchange="refresh_content()"> 
                        <option value="">---------Select Main Treatment------------------</option> 
                        <?php 
                            $sql_select_main_department_result=mysqli_query($conn,"SELECT * FROM tbl_mtuha_treatment  WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_main_department_result)>0){
                                while($idar_kuu_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                                   $name_of_treatment=$idar_kuu_rows['name_of_treatment'];
                                   $Treatment_ID=$idar_kuu_rows['Treatment_ID'];
                                   echo "<option value='$Treatment_ID'>$name_of_treatment</option>";   
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
                            <input id="Treatment_ID" style="display:none" value="<?php echo $Treatment_ID;?>">
                            <div class="col-sm-12"><p id="category_list_tittle" style="font-size:17px">Item attached to </p></div>
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
         $(".Item_ID").not(this).prop('checked', this.checked); 
         
     });    
     function attach_item_to_main_treatment(){
        var selectedCategory = []; 
        $(".Item_ID:checked").each(function() {
		selectedCategory.push($(this).val());
	});
        var Treatment_ID=$("#Treatment_ID").val()
        if(Treatment_ID==""){
          $("#Treatment_ID").css("border","1px solid red");
          alert("Select Main Treatment To attach Selected Item");
          exit;
        }else{
          $("#Treatment_ID").css("border","");  
        } 
        $.ajax({
            type:'POST',
            url:'ajax_attach_category_to_main_treatment_mtuha.php',
            data:{selectedCategory:selectedCategory,Treatment_ID:Treatment_ID},
            success:function(data){
                $("#attached_category_body").html(data);
                refresh_content()
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         var Treatment_ID=$("#Treatment_ID").val();
         $("#select_all_checkbox").prop("checked",false)

         var sel = document.getElementById("Treatment_ID");
         var name_of_treatment= sel.options[sel.selectedIndex].text;
         
         $("#category_list_tittle").html("List of Item attached to <b>"+name_of_treatment+"</b>")
         $.ajax({
             type:'POST',
             url:'refresh_category_main_treatment_list_mtuha.php',
             data:{Treatment_ID:Treatment_ID},
             success:function(data){
               $("#attached_category_body").html(data);  
             }
         });
         $.ajax({
             type:'POST',
             url:'refresh_all_item_list_mtuha.php',
             data:{Treatment_ID:Treatment_ID},
             success:function(data){
                  $("#all_category_list_body").html(data); 
             }
         });
     }
function search_item_from_all_list(){
    var Product_Name = $("#all_category_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_all_item_list_mtuha.php',
             data:{Product_Name:Product_Name},
             success:function(data){
                  $("#all_category_list_body").html(data); 
             }
         });
}
</script>