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
<a href='edititems.php?EditItem=EditItemThisForm' class='art-button-green'>
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
    <legend align=center><b>ATTACH ITEM TO TEMPLATE</b></legend>
    <div class="row">
        <div class="col-sm-5">
            <input type="text"style="text-align: center"class="form-control"id="all_items_search_box" onkeyup="search_item_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" >
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List of All Items </h4><img src="images/ajax-loader_1.gif" id="loder_img"width="" style="border-color:white;display: none "></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_item_to_template()"><i id="attach_item_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" style="height: 330px;overflow: auto" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div class="all_ietems_list_div">
                        <div id="all_item_list_body">
                            <table class="table">
                            <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' ORDER BY Product_Name ASC LIMIT 100") or die(mysqli_error($conn));
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
                <div class="col-sm-3" style="text-align:right">
                    <label>SELECT TEMPLATE</label>
                </div>
                <div class="col-sm-6" id="">
                    <select class="form-control" id="template_name" onchange="refresh_content()"> 
                        <option value="">Select Template</option>
                        <option value="Echocardiogram">Echocardiogram</option>
                        <option value="Colonoscopy">Colonoscopy</option>
                        <option value="Upper Git">Upper Git</option>
                        <option value="Broncoscopy">Broncoscopy</option>
                        <option value="Speech Therapy">Speech Therapy</option>
                        <option value="Audiology">Audiology</option>
                        <option value="Speech Therapy">Speech Therapy</option>
                        <option value="Optical">Optical</option>
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
     function attach_item_to_template(){
        var selected_items = []; 
        $(".Item_ID:checked").each(function() {
		selected_items.push($(this).val());
	});
        
        var template_name=$("#template_name").val();

        if(template_name==""){
          $("#template_name").css("border","1px solid red");
          alert("Select template To attach Selected Item ");
          exit;
        }else{
          $("#template_name").css("border","");  
        }
        if(selected_items==""){
          alert("Select Item/s To Attach ");
          exit;
        }else{
          console.log(selected_items);
        }
      
       
        $("#loder_img").show();

        $.ajax({
            type:'POST',
            url:'ajax_attach_item_to_template.php',
            data:{
                    selected_items:selected_items,
                    template_name:template_name
                },
            success:function(data){
                $("#loder_img").hide();
                $("#attached_item_body").html(data);
                refresh_content()
                
            }
        });
       //refresh_content() 
     }
     function refresh_content(){
         $(".Item_ID").prop("checked",false);

         var template_name=$("#template_name").val();
         
         /*$("#item_list_tittle").html("List of Items attached to <b>"+template_name+"</b>")*/
         $("#loder_img").show();
         $.ajax({
             type:'POST',
             url:'refresh_template_item_list.php',
             data:{template_name:template_name},
             beforesend:function(){
                $("#loder_img").show(); 
             },
             success:function(data){
               $("#attached_item_body").html(data); 
               $("#loder_img").hide();
             }
         });
         /*$.ajax({
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
         });*/
     }
function remove_function(template_id){
    var template_name=$("#template_name").val();
    $.ajax({
             type:'POST',
             url:'remove_template_item.php',
             data:{template_id:template_id,template_name:template_name},
             success:function(data){
                  $("#attached_item_body").html(data); 
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


