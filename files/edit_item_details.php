<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    } 
?>
<style>
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
<?php 
if(isset($_GET['from_procure_ment'])){
   ?>
 <a href='procurementworkspage.php?ProcurementWorkPage=ProcurementWorkPageThisPage' class='art-button-green'>
        BACK
</a>
<?php 
}else{
?>
 <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
</a>
<?php
}
?>
<fieldset>
    <legend align="center"><b>ITEM LIST</b></legend>
    <center>
    <table width='100%'>
        <tr>
           
            <td width='8%' style="text-align:right">Category </td>
	    <td width='20%'>
                <select id='Item_Category_ID' style='padding:3px' name='Item_Category_ID' onchange="get_item_sub_category()">
                    <option value="All">ALL</option>
		    <?php
		    $cat_qr = "SELECT * FROM tbl_item_category";
		    $cat_result = mysqli_query($conn,$cat_qr);
		    while($cat_row = mysqli_fetch_assoc($cat_result)){
			?>
			<option value="<?php echo $cat_row['Item_Category_ID']; ?>"><?php
			echo $cat_row['Item_Category_Name'];
			?></option>
			<?php
		    }
		    ?>
		</select>
	    </td>
            <td width='8%' style="text-align:right">Sub Category </td>
	    <td width='20%'>
                <select id='Item_Subcategory_ID' style='padding:3px' name='Item_Category_ID' onchange=" get_item_from_selected_category_n_sub_category()">
                    <option value="All">ALL</option>
		   
		</select>
	    </td>
            <td width='25%'>
                <input type="text" onkeyup="getItemName()" style="padding:3px; text-align: center;" id="item_name" placeholder=" ~~~ Search Item by name~~~ ">
            </td>
            <td width='25%'>
                <input type="text" onkeyup="getItemName()" style="padding:3px; text-align: center;" id="folio_number" placeholder=" ~~~ Search Item by folio number~~~ ">
            </td>
        </tr>
</table>
</center>
    <div style="height:400px;overflow-y: scroll;overflow-x: hidden" id="list_of_item">
    <table class="table table-bordered" style="background: #FFFFFF">
        <tr>
            <th style="width:50px">
                S/No.
            </th>
            <th style="width:45%">ITEM NAME</th>
            <th style="width:20%">UNIT OF MEASURE</th>
            <th style="width:20%">FOLIO NUMBER</th>
            <th style="width:10%">UPDATE</th>
        </tr>
        <?php 
            $sql_select_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Code,Product_Name,item_folio_number FROM tbl_items WHERE Status='Available'LIMIT 100") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_items_result)>0){
                $i=1;
               while($item_rows=mysqli_fetch_assoc($sql_select_items_result)){
                  $Item_ID= $item_rows['Item_ID'];
                  $Product_Code= $item_rows['Product_Code'];
                  $Product_Name= $item_rows['Product_Name'];
                  $item_folio_number= $item_rows['item_folio_number'];
                  echo "<tr>
                            <td>$i</td>
                            <td>$Product_Name</td>
                            <td style='text-align:center'>
                            <select>
                                <option></option>
                            </select>
                            </td>
                            <td><input type='text' value='$item_folio_number' id='$Item_ID' class='form-control' style='text-align:center' placeholder='enter folio number'></td>
                            <td>
                                <button class='art-button-green' onclick='update_folio_number(\"$Item_ID\")'>UPDATE</button>
                            </td>
                        </tr>
                      ";
                  $i++;
               } 
            }
        ?>
    </table>
 </div>
</fieldset>
<script>
    function get_item_sub_category(){
        var Item_Category_ID=$("#Item_Category_ID").val();
        var Item_Subcategory_ID=$("#Item_Subcategory_ID").val();
        $.ajax({
            type:'GET',
            url:'get_item_sub_category.php',
            data:{Item_Category_ID:Item_Category_ID},
            success:function (data){
                $("#Item_Subcategory_ID").html(data);
                get_item_from_selected_category_n_sub_category();
            }
        });
        
    }
    function get_item_from_selected_category_n_sub_category(){
        var Item_Category_ID=$("#Item_Category_ID").val();
        var Item_Subcategory_ID=$("#Item_Subcategory_ID").val();
        
        $.ajax({
            type:'GET',
            url:'get_item_from_selected_category_n_sub_category.php',
            data:{Item_Category_ID:Item_Category_ID,Item_Subcategory_ID:Item_Subcategory_ID},
            success:function(data){
                console.log(data);
                $("#list_of_item").html(data);
            }
        });
    }
    function update_folio_number(Item_ID){
        console.log(Item_ID)
       var item_folio_number=$("#"+Item_ID).val();
       $.ajax({
            type:'GET',
            url:'update_item_folio_number.php',
            data:{item_folio_number:item_folio_number,Item_ID:Item_ID},
            success:function(data){
                console.log(data);
                if(data=="updated"){
                    alert("Folio number updated Successfully");
                }else{
                    alert("Fail to update folio number..please try again");
                }
            }
        });  
    }
    function getItemName(){
        var folio_number=$("#folio_number").val();
        var item_name=$("#item_name").val();
        
        $.ajax({
            type:'GET',
            url:'edit_item_details_search.php',
            data:{folio_number:folio_number,item_name:item_name},
            success:function(data){
                console.log(data);
                $("#list_of_item").html(data);
            }
        });
    }
</script>
<?php
    include("./includes/footer.php");
?>