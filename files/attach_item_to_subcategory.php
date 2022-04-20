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
    #attach_item_icon{
        transform: rotate(45deg);
    }
</style>

<fieldset>
    <legend align=center><b>ATTACH ITEM TO SUB CATEGORY</b></legend>
    <div class="row">
        <div class="col-sm-5">
            <input type="text"style="text-align: center"class="form-control"id="all_items_search_box" onkeyup="search_item_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" >
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Items </h4><img src="images/ajax-loader_1.gif" id="loder_img"width="" style="border-color:white;display: none "></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_item_to_sub_category()"><i id="attach_item_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" style="height: 330px;overflow: auto" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div class="all_ietems_list_div">
                        <div id="all_item_list_body">
                            <table class="table">
                            <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_category_result)>0){
                                    $count=1;
                                    while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                        $Item_ID=$category_rows['Item_ID'];
                                        $Product_Name=$category_rows['Product_Name'];
                                        $Item_Subcategory_Name=$category_rows['Item_Subcategory_Name'];
                                        echo "<tr>
                                                    <td>
                                                    $count
                                                    </td>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name <label>~~~($Item_Subcategory_Name)</label>
                                                        </label>
                                                    </td>

                                              </tr>";
                                        $count++;
                                    }
                                }
                            ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="row">
                <div class="col-sm-3">
                    <label>SUB CATEGORY</label>
                </div>
                <div class="col-sm-4" id="subcategory">
                    <select class="form-control" id="Item_Subcategory_ID"onchange="refresh_content()"> 
                        <option value="">~~~~Select Sub Category~~~~</option> 
                        <?php 
                            $sql_select_sub_c_result=mysqli_query($conn,"SELECT * FROM tbl_item_subcategory") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_sub_c_result)>0){
                                while($sub_c_rows=mysqli_fetch_assoc($sql_select_sub_c_result)){
                                   $Item_Subcategory_Name=$sub_c_rows['Item_Subcategory_Name'];
                                   $Item_Subcategory_ID=$sub_c_rows['Item_Subcategory_ID'];
                                   echo "<option value='$Item_Subcategory_ID'>$Item_Subcategory_Name</option>";   
                                } 
                            }
                        ?>
                    </select>

                </div>
                <div class="col-sm-2">
                    <select id="Item_Type" style="width:100%;height: 30px">
                        <option value="">Item Type</option>
                        <option value="Pharmacy">Pharmacy and Consumable</option>
                        <option>Service</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="consultation_type" class="form-control">
                        <option value="">Select Consultation Type</option>
                        <option>Pharmacy</option>
                        <option>Laboratory</option>
                        <option>Radiology</option>
                        <option>Surgery</option>
                        <option>Procedure</option>
                        <option>Optical</option>
                        <option>Others</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br/>
                    <div class="box box-primary" style="height: 400px;overflow: auto">
                        <div class="box-header">
                            <div class="col-sm-12"><p id="item_list_tittle" style="font-size:17px"> Items attached to </p></div>
                            <div class="col-sm-6" style="display:none">
                                <input type="text" style="text-align:center" class="form-control" id="attached_item_search_box" placeholder="--------Search--------" />
                            </div>
                        </div>
                        <div class="box-body" id="attached_item_body">

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
     function attach_item_to_sub_category(){
        var selected_items = []; 
        $(".Item_ID:checked").each(function() {
		selected_items.push($(this).val());
	});
        
        var Item_Subcategory_ID=$("#Item_Subcategory_ID").val()
        var consultation_type=$("#consultation_type").val()
        var Item_Type=$("#Item_Type").val()
        if(consultation_type==""){
          $("#consultation_type").css("border","1px solid red");
          alert("Select Consultation type To attach Selected Item ");
          exit;
        }else{
          $("#consultation_type").css("border","");  
        }
        if(consultation_type==""){
          $("#Item_Type").css("border","1px solid red");
          alert("Select Item type To attach Selected Item ");
          exit;
        }else{
          $("#Item_Type").css("border","");  
        }
        if(Item_Subcategory_ID==""){
          $("#subcategory").css("background","red");
          alert("Select Sub Category To attach Selected Item ");
          exit;
        }else{
          $("#subcategory").css("background","");  
        }
        $("#loder_img").show();
        $.ajax({
            type:'POST',
            url:'ajax_attach_item_to_sub_category.php',
            data:{selected_items:selected_items,Item_Subcategory_ID:Item_Subcategory_ID,consultation_type:consultation_type,Item_Type:Item_Type},
            success:function(data){
               
                $("#attached_item_body").html(data);
                refresh_content()
                
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         var Item_Subcategory_ID=$("#Item_Subcategory_ID").val();
         $("#select_all_checkbox").prop("checked",false)

         var sel = document.getElementById("Item_Subcategory_ID");
         var sub_category_name= sel.options[sel.selectedIndex].text;
         
         $("#item_list_tittle").html("List of Items attached to <b>"+sub_category_name+"</b>")
         $("#loder_img").show();
         $.ajax({
             type:'POST',
             url:'refresh_sub_category_list.php',
             data:{Item_Subcategory_ID:Item_Subcategory_ID},
             beforesend:function(){
                $("#loder_img").show(); 
             },
             success:function(data){
               $("#attached_item_body").html(data); 
               $("#loder_img").hide();
             }
         });
         $.ajax({
             type:'POST',
             url:'refresh_all_item_list.php',
             data:{Item_Subcategory_ID:Item_Subcategory_ID},
             beforesend:function(){
                $("#loder_img").show(); 
             },
             success:function(data){
                  $("#all_item_list_body").html(data); 
                  $("#loder_img").hide();
             }
         });
     }
function search_item_from_all_list(){
    var Product_Name = $("#all_items_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_all_item_list.php',
             data:{Product_Name:Product_Name},
             success:function(data){
                  $("#all_item_list_body").html(data); 
             }
         });
}


</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Item_Subcategory_ID').select2();
    });
                        
</script>
<?php
include("./includes/footer.php");
?>


