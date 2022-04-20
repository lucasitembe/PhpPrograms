<?php
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='new_revenue_collection_summary.php' class='art-button-green'>BACK</a>
<br/>
<br/>
<fieldset>
    <legend align="center"><b>REVENUE COLLECTION SUMMARY REPORT ITEMS SETUP</b></legend>
        <div class="row">
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-7"><input type="text"style="text-align: center"class="form-control"id="all_items_search_box" onkeyup="search_item_from_all_list()" placeholder="----------------Search----------------"/></div>
                <div class="col-md-5">
                    <select id="consultation_type" class="form-control" onchange="search_item_from_all_list()">
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
                    <div class="col-sm-8"> <h4 class="box-title">List of All Items </h4><img src="images/ajax-loader_1.gif" id="loder_img"width="" style="border-color:white;display: none "></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_item_to_report_category()"><i id="attach_item_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" style="height: 330px;overflow: auto" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div class="all_ietems_list_div">
                        <div id="all_item_list_body">
                            <table class="table">
                            <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,revenue_report_category FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' ORDER BY Product_Name ASC LIMIT 100") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_category_result)>0){
                                    $count=1;
                                    while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                        $Item_ID=$category_rows['Item_ID'];
                                        $Product_Name=$category_rows['Product_Name'];
                                        $revenue_report_category=$category_rows['revenue_report_category'];
                                        echo "<tr>
                                                    <td>
                                                    $count
                                                    </td>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name <label>~~~($revenue_report_category)</label>
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
                <div class="col-sm-5">
                    <label>ITEM REPORT CATEGORY</label>
                </div>
                <div class="col-sm-6" id="subcategory">
                    <select class="form-control" id="Item_report_category"onchange="refresh_content()"> 
                        <option value="">~~~~Select Item Category~~~~</option> 
                        <option>Pharmacy</option>
                        <option>Laboratory</option>
                        <option>Radiology</option>
                        <option>Consumable</option>
                        <option>Accommodation</option>
                        <option>Ward Round</option>
                        <option>Surgeries-main theater</option>
                        <option>Surgical minor procedure</option>
                        <option>Diagnostic procedure and curative procedure</option>
                        <!--<option>Curative Procedure</option>-->
                        <option>Consultation</option>
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
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
     $("#select_all_checkbox").click(function (e){
         $(".Item_ID").not(this).prop('checked', this.checked);   
     });    
     function attach_item_to_report_category(){
        var selected_items = []; 
        $(".Item_ID:checked").each(function() {
		selected_items.push($(this).val());
	});
        
        var Item_report_category=$("#Item_report_category").val()
      
        if(Item_report_category==""){
          $("#subcategory").css("background","red");
          $("#subcategory").css("padding","4px");
          alert("Select Item report category To attach Selected Item(s)");
          exit;
        }else{
          $("#subcategory").css("background","");  
        }
        $("#loder_img").show();
        $.ajax({
            type:'POST',
            url:'ajax_attach_item_to_report_category.php',
            data:{selected_items:selected_items,Item_report_category:Item_report_category},
            success:function(data){
                console.log(selected_items)
                $("#loder_img").hide();
                $("#attached_item_body").html(data);
            }
        });
       //refresh_content() 
     }
function search_item_from_all_list(){
    var Product_Name = $("#all_items_search_box").val();
    var consultation_type = $("#consultation_type").val();
     $.ajax({
             type:'POST',
             url:'search_all_item_list_report.php',
             data:{Product_Name:Product_Name,consultation_type:consultation_type},
             success:function(data){
                  $("#all_item_list_body").html(data); 
             }
         });
}
function refresh_content(){
    var Item_report_category=$("#Item_report_category").val()
       $.ajax({
             type:'POST',
             url:'ajax_refresh_content_item_category_report.php',
             data:{Item_report_category:Item_report_category},
             success:function(data){
                  $("#attached_item_body").html(data); 
             }
         });  
}

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Item_report_category').select2();
        $('#consultation_type').select2();
    });                      
</script>
</script>
<?php
include("./includes/footer.php");
?>