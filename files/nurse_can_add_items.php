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
<a href='itemsconfiguration.php' class='art-button-green'>
    BACK
</a>
<br/>
<br/>
<style>
    #attach_item_icon{
        transform: rotate(45deg);
    }
    #attach_item_icon2{
        transform: rotate(45deg);
    }
</style>

<fieldset>
    <legend align=center><b>SET ITEMS NURSE CAN ADD</b></legend>
    <div class="row">
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-6">
                    <input type="text"style="text-align: center"class="form-control"id="all_items_search_box" onkeyup="search_item_from_all_list()" placeholder="----------------Search----------------"/>
                </div>
                <div class="col-sm-6" style="text-align: center;">
                    <select style="height: 100%;" id="consultation_type" onchange="search_item_from_all_list();" class="form-control">
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
            <br/>
            <div class="box box-primary" >
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title" style="font-size:17px;color: black;">List of All Items </h4><img src="images/ajax-loader_1.gif" id="loder_img"width="" style="border-color:white;display: none "></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="set_items_to_be_ordered_by_nurse()"><i id="attach_item_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" style="height: 330px;overflow: auto" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div class="all_ietems_list_div">
                        <div id="all_item_list_body">
                            <table class="table">
                            <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' AND nurse_can_add='no' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
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
                <div class="col-sm-8">
                </div>
                <div style="text-align:center" class="col-sm-4">
                    <select style="text-align:center" id="consultation_type2" onchange="search_item_from_all_list2();" class="form-control">
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
                            <div class="col-sm-8"><p id="item_list_tittle" style="font-size:17px;color: black;"> List of Items Nurse Can Add </p><img src="images/ajax-loader_1.gif" id="loder_img2"width="" style="border-color:white;display: none "></div>
                            <div class="col-sm-4">
                                <a href="#" class="btn btn-default pull-right" onclick="remove_items_to_be_ordered_by_nurse()"><i id="attach_item_icon2" style="color: red;" class="fa fa-send fa-2x"></i></a>
                            </div>
                            <div class="col-sm-6" style="display:none">
                                <input type="text" style="text-align:center" class="form-control" id="attached_item_search_box" placeholder="--------Search--------" />
                            </div>
                        </div>
                        <div class="box-body">
                        <label><input type="checkbox" id="select_all_checkbox2"> Select All</label>
                        <div id="attached_item_body" >
                            <table class="table">
                            <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' AND nurse_can_add='yes' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
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
                                                            <input type='checkbox'class='Item_ID2' name='Item_ID2' value='$Item_ID'>$Product_Name <label>~~~($Item_Subcategory_Name)</label>
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
        </div>
    </div>
</fieldset>
 <script>
     $("#select_all_checkbox").click(function (e){
         $(".Item_ID").not(this).prop('checked', this.checked); 
         
     }); 

     $("#select_all_checkbox2").click(function (e){
         $(".Item_ID2").not(this).prop('checked', this.checked); 
         
     }); 

     function set_items_to_be_ordered_by_nurse(){
        var selected_items = []; 
        $(".Item_ID:checked").each(function() {
		    selected_items.push($(this).val());
	    });
        
        // if(consultation_type==""){
        //   $("#consultation_type").css("border","1px solid red");
        //   alert("Select Consultation type To attach Selected Item ");
        //   exit;
        // }else{
        //   $("#consultation_type").css("border","");  
        // }

        $("#loder_img").show();
        $.ajax({
            type:'POST',
            url:'ajax_set_items_nurse_can_add.php',
            data:{
                selected_items:selected_items
            },
            success:function(data){
               
                $("#attached_item_body").html(data);
                $("#loder_img").hide();
                refresh_content();
                
            }
        });
       //refresh_content() 
     }

        
     function remove_items_to_be_ordered_by_nurse(){
        var selected_items = []; 
        $(".Item_ID2:checked").each(function() {
		    selected_items.push($(this).val());
	    });
        
        // if(consultation_type==""){
        //   $("#consultation_type").css("border","1px solid red");
        //   alert("Select Consultation type To attach Selected Item ");
        //   exit;
        // }else{
        //   $("#consultation_type").css("border","");  
        // }

        $("#loder_img2").show();
        $.ajax({
            type:'POST',
            url:'ajax_remove_items_nurse_can_add.php',
            data:{
                selected_items:selected_items
            },
            success:function(data){
               
                $("#attached_item_body").html(data);
                $("#loder_img2").hide();
                refresh_content2();
                
            }
        });
       //refresh_content() 
     }


    function refresh_content(){
    //  var Item_Subcategory_ID=$("#Item_Subcategory_ID").val();
        $("#select_all_checkbox").prop("checked",false)

        // var sel = document.getElementById("Item_Subcategory_ID");
        // var sub_category_name= sel.options[sel.selectedIndex].text;
        var reload_all_added_items = "already added";
        
        // $("#item_list_tittle").html("List of Items attached to <b>"+sub_category_name+"</b>");
        $("#loder_img").show();
        $.ajax({
        type:'POST',
        url:'refresh_nurse_can_add_items.php',
        data:{
            reload_all_added_items:reload_all_added_items
        },
        beforesend:function(){
        $("#loder_img2").show(); 
        },
        success:function(data){
        $("#attached_item_body").html(data); 
        $("#loder_img2").hide();
        }
        });

        // alert("1");
        $.ajax({
         type:'POST',
         url:'refresh_all_item_nurse_can_add_list.php',
         data:{
             reload_all_added_items:reload_all_added_items
        },
         beforesend:function(){
            $("#loder_img").show(); 
         },
         success:function(data){
            //  alert("2");
              $("#all_item_list_body").html(data); 
              $("#loder_img").hide();
         }
        });
        // alert("3");
    }

    function refresh_content2(){
    //  var Item_Subcategory_ID=$("#Item_Subcategory_ID").val();
        $("#select_all_checkbox2").prop("checked",false)

        // var sel = document.getElementById("Item_Subcategory_ID");
        // var sub_category_name= sel.options[sel.selectedIndex].text;
        var reload_all_added_items = "already added";
        
        // $("#item_list_tittle").html("List of Items attached to <b>"+sub_category_name+"</b>");
        $("#loder_img2").show();
        $.ajax({
        type:'POST',
        url:'refresh_nurse_can_add_items.php',
        data:{
            reload_all_added_items:reload_all_added_items
        },
        beforesend:function(){
        $("#loder_img2").show(); 
        },
        success:function(data){
        $("#attached_item_body").html(data); 
        $("#loder_img2").hide();
        }
        });



        $.ajax({
         type:'POST',
         url:'refresh_all_item_nurse_can_add_list.php',
         data:{
             reload_all_added_items:reload_all_added_items
        },
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
        var consultation_type = $("#consultation_type").val();

        $.ajax({
            type:'POST',
            url:'search_all_items_nurse_can_add_list.php',
            data:{
                Product_Name:Product_Name,
                consultation_type: consultation_type
            },
            success:function(data){
                $("#all_item_list_body").html(data); 
            }
        });
    }

    function search_item_from_all_list2(){
    var consultation_type = $("#consultation_type2").val();
    var nurse_can_add = "yes";

    $.ajax({
        type:'POST',
        url:'search_all_items_nurse_can_add_list.php',
        data:{
            consultation_type: consultation_type,
            nurse_can_add: nurse_can_add
        },
        success:function(data){
            $("#attached_item_body").html(data); 
        }
    });
}


</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Item_Subcategory_ID').select2();
        $('#consultation_type').select2();
        $('#consultation_type2').select2();

    });
                        
</script>
<?php
include("./includes/footer.php");
?>


