<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
 <a href='msamaha.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>
      BACK
    </a>
<br/>
<br/>
<style>
    #attach_cat_icon{
        transform: rotate(45deg);
    }
</style>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<fieldset>
    <legend align=center><b>ATTACH AGE TO LABORATORY TEST</b></legend>
     <div class="row">
         <div class="col-sm-4"></div>
         <div class="col-sm-5">
                 <b>From age </b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="From age" class="form-control numberonly" style='text-align: center;width:15%;display:inline;padding: 4px'/>
                 <b>To age </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="To age" class="form-control numberonly" style='text-align: center;width:15%;display:inline;padding: 4px'/>
                <input type="button" name="filter" value="SAVE" class="art-button-green" onclick="SAVE_AGES();">
         </div>
         </div>
    <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center"class="form-control"id="all_item_test_search_box" onkeyup="search_item_Name_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">LIST OF All LABORATORY TEST</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_laboratort_test_to_age()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_item_Name_list_body">
                        <table class="table">
                        <?php 
                         $count=1;
                            $sql_select_laboratory_test=mysqli_query($conn,"SELECT * FROM tbl_items LIMIT 10") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_laboratory_test)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_laboratory_test)){
                                    $Item_ID=$category_rows['Item_ID'];
                                    $Product_Name=$category_rows['Product_Name'];
                                    echo "<tr>
                                        <td>
                                            $count
                                           </td>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
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
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-5">
                    <label>SELECT AGE RANGE</label>
                </div>
                <div class="col-sm-7">
                    <select class="form-control" id="Age_ID"onchange="refresh_content()"> 
                        <option value="">---------Select AGE------------------</option> 
                        <?php 
                            $sql_select_age_range_result=mysqli_query($conn,"SELECT * FROM tbl_age_range") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_age_range_result)>0){
                                $value_one = mysqli_num_rows($sql_select_age_range_result);
                                while($age_range_rows=mysqli_fetch_assoc($sql_select_age_range_result)){
                                   $age_id=$age_range_rows['age_id'];
                                   $start_age=$age_range_rows['start_age'];
                                   $end_age=$age_range_rows['end_age'];
                                   echo "<option value='$age_id' id='Age_ID' name='Age_ID'>$start_age-----$end_age  </option>";   
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
                            <div class="col-sm-12"><p id="sub_category_list_tittle" style="font-size:17px">Laboratory test attached</p></div>
                            <div class="col-sm-6">
                                <label><input type="checkbox" id="select_all_checkbox1"> Select All</label> <input type="button"  onclick="delete_laboratory_test()" class="art-button-green" value="Delete" />
                                <!--<input type="text" style="text-align:center" class="form-control" id="attached_sub_category_search_box" placeholder="--------Search--------" />-->
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
         $(".Item_ID").not(this).prop('checked', this.checked); 
         
     });
       $("#select_all_checkbox1").click(function (e){
         $(".item_id").not(this).prop('checked', this.checked); 
         
     });
     
       function delete_laboratory_test(){
        var selected_laboratory_test = []; 
        $(".item_id:checked").each(function() {
		selected_laboratory_test.push($(this).val());
	});
//            alert(selected_laboratory_test);
            
           var Age_ID = $("#Age_ID").val();
       
          if(selected_laboratory_test==""){
              alert("Select laboratory test first");
              
          }
        $.ajax({
            type:'POST',
            url:'delete_laboratory_test.php',
            data:{selected_laboratory_test:selected_laboratory_test,Age_ID:Age_ID},
            success:function(data){
                console.log(data);
                alert("success deleted");
              refresh_content();
            }
        });
       //refresh_content() 
     }
     function attach_laboratort_test_to_age(){
        var selected_sub_Category = []; 
        $(".Item_ID:checked").each(function() {
		selected_sub_Category.push($(this).val());
	});
        
        var Age_ID=$("#Age_ID").val();
        if(Age_ID==""){
          $("#Age_ID").css("border","1px solid red");
          alert("Select age To attach laboratory test");
          exit;
        }else{
          $("#Age_ID").css("border","");  
        }
//          alert(Age_ID);
//          alert(selected_sub_Category);
        $.ajax({
            type:'POST',
            url:'ajax_attach_laboratory_test.php',
            data:{selected_sub_Category:selected_sub_Category,Age_ID:Age_ID},
            success:function(data){
                  refresh_content()
                $("#attached_sub_category_body").html(data);
                refresh_content();
            }
        });
       //refresh_content() 
     }
     
     function SAVE_AGES(){
         
         var start_age = $('#start_age').val();
         var end_age =$('#end_age').val();
         
         if(start_age ==''){
             alert("insert start age first");
         }
         if(end_age ==''){
             alert("insert end age first");
         }
         
            $.ajax({
             type:'POST',
             url:'insert_age_data.php',
             data:{start_age:start_age,end_age:end_age},
             success:function(data){
               alert("data saved successful");  
             }
         });
     }
     function refresh_content(){
//         var Item_ID=$("#Item_ID").val();
//         $("#select_all_checkbox").prop("checked",false)
//
//         var sel = document.getElementById("Item_ID");
//         var category_name= sel.options[sel.selectedIndex].text;
          var Age_ID=$("#Age_ID").val();
//         $("#sub_category_list_tittle").html("List of Category attached to <b>"+category_name+"</b>")
         $.ajax({
             type:'POST',
             url:'refresh_age_range_list.php',
             data:{Age_ID:Age_ID},
             success:function(data){
               $("#attached_sub_category_body").html(data);  
             }
         });
//         $.ajax({
//             type:'POST',
//             url:'refresh_laboratory_test.php',
//             data:{Item_ID:Item_ID},
//             success:function(data){
//                  $("#all_sub_category_list_body").html(data); 
//             }
//         });
     }
function search_item_Name_from_all_list(){
    var Item_Test_Name = $("#all_item_test_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_all_item_test_list.php',
             data:{Item_Test_Name:Item_Test_Name},
             success:function(data){
                  $("#all_item_Name_list_body").html(data); 
             }
         });
}
</script>
<script>
    $('.select').select2();
</script>   

<?php
    include("./includes/footer.php");
?>