<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
    if(isset($_POST['disable'])){
       $Item_Subcategory_ID=$_POST['Item_Subcategory_ID'];
        $sql_disable_item_sub_category_result=mysqli_query($conn,"UPDATE tbl_item_subcategory SET enabled_disabled='disabled' WHERE Item_Subcategory_ID='$Item_Subcategory_ID'") or die(mysqli_error($conn));
    }
    if(isset($_POST['enable'])){
       $Item_Subcategory_ID=$_POST['Item_Subcategory_ID'];
        $sql_disable_item_sub_category_result=mysqli_query($conn,"UPDATE tbl_item_subcategory SET enabled_disabled='enabled' WHERE Item_Subcategory_ID='$Item_Subcategory_ID'") or die(mysqli_error($conn));
    }
?>
<a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
    BACK
</a>
<fieldset>
            <legend align="center" ><b>DISABLE/ENABLE SUB CATEGORY</b></legend>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h4 class="box-title"></h4>
                        </div>
                        <div class="box-body">
                            <select onchange="filter_item_sub_category_by_consultation_type()" class='form-control' id="Item_category_ID">
                                <option value="All">All Category</option>
                                <?php 
                                  $sql_select_item_category_result=mysqli_query($conn,"SELECT Item_Category_ID,Item_Category_Name FROM tbl_item_category") or die(mysqli_error($conn));  
                                  if(mysqli_num_rows($sql_select_item_category_result)>0){
                                        while($item_category_rows=mysqli_fetch_assoc($sql_select_item_category_result)){
                                            $Item_Category_ID=$item_category_rows['Item_Category_ID'];
                                            $Item_Category_Name=$item_category_rows['Item_Category_Name'];
                                            echo "<option value='$Item_Category_ID'>$Item_Category_Name</option>";
                                        }
                                  }
                                 ?> 
                            </select>
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width:50px">S/No.</td>
                                    <td>Item Sub-category</td>
                                    <td style="width: 200px">Enable/Disable Sub-category</td>
                                </tr>
                                <tbody id="item_sub_c_body">
                                <?php 
                                    $sql_select_sub_dep_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_sub_dep_result)>0){
                                        $count_row=1;
                                        while($item_c_rows=mysqli_fetch_assoc($sql_select_sub_dep_result)){
                                           $Item_Subcategory_ID=$item_c_rows['Item_Subcategory_ID'];
                                           $Item_Subcategory_Name=$item_c_rows['Item_Subcategory_Name'];
                                           $enabled_disabled=$item_c_rows['enabled_disabled'];
                                           echo "<tr>
                                                    <td>$count_row</td>
                                                    <td>$Item_Subcategory_Name</td>
                                                    <td>
                                                        <form method='POST' action=''>
                                                        <input type='text' name='Item_Subcategory_ID' hidden='hidden'value='$Item_Subcategory_ID'/>
                                                            ";
                                                            
                                                    if($enabled_disabled=="enabled"){
                                                        echo "<input type='submit' name='disable' class='art-button-green' VALUE='DISABLE ITEM SUB CATEGORY'/>";
                                                    }else{
                                                        echo "<input type='submit' name='enable' class='btn btn-danger btn-sm btn-block' VALUE='ENABLE ITEM SUB CATEGORY'/>";
                                                    }
                                           echo "
                                                        </form>
                                                    </td>
                                                </tr>";
                                           $count_row++;
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
</fieldset>
<script>
    function filter_item_sub_category_by_consultation_type(){
       var Item_category_ID=$("#Item_category_ID").val();
       $.ajax({
           type:'GET',
           url:'pull_item_sub_category_under_this_consultation.php',
           data:{Item_category_ID:Item_category_ID},
           success:function(data){
               $("#item_sub_c_body").html(data);
           }
       });
    }
     $(document).ready(function () {
                        $("#Item_category_ID").select2();
                        });
</script>
<?php
    include("./includes/footer.php");
?>