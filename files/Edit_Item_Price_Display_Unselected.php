<?php
	session_start();
	include("./includes/connection.php");
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

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Status'])){
    	$Status = $_GET['Status'];
    }else{
    	$Status = '';
    }

?>
<?php if(strtolower($Status) == 'category'){ ?>
<table width="100%">
	<?php
	    $temp = 0;
	    $Title3 = '<tr><td colspan="3"><hr></td></tr>
	                <tr>
	                    <td width="6%"><b>SN</b></td>
	                    <td><b>CATEGORY NAME</b></td>
	                    <td width="13%" style="text-align: center;"><b>ACTION</b></td>
	                </tr>
	                <tr><td colspan="3"><hr></td></tr>';
	    echo $Title3;
	    $select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	        while ($data = mysqli_fetch_array($select)) {
	        	$Item_Category_ID = $data['Item_Category_ID'];

	        	//check if any unselected item available
	        	$check = mysqli_query($conn,"select i.Item_ID, i.Product_Name from tbl_items i, tbl_item_subcategory isu where
	        							isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	        							isu.Item_category_ID = '$Item_Category_ID' and
			                            Item_ID NOT IN (select Item_ID from tbl_edit_price_cache where Employee_ID = '$Employee_ID') order by Product_Name limit 1") or die(mysqli_error($conn));
	        	$check_num = mysqli_num_rows($check);
	        	if($check_num  > 0){
?>
		            <tr>
		                <td><?php echo ++$temp; ?></td>
		                <td><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></td>
		                <td style="text-align: center;">
		                    <input type="button" name="Add_Selected_Cat" id="Add_Selected_Cat" value=">> >>" onclick="Add_Selected_Category(<?php echo $Item_Category_ID; ?>)">
		                </td>
		            </tr>
<?php
		            if(($temp%15) == 0){
		                echo $Title3;
		            }
	        	}
	        }
	    }
	?>
</table>
<?php } else if(strtolower($Status) == 'subcategory'){ ?>

<?php }else{ ?>
		<table width="100%">
			<?php
			    $Title2 = '<tr><td colspan="3"><hr></td></tr>
			                <tr>
			                    <td width="6%"><b>SN</b></td>
			                    <td><b>ITEM NAME</b></td>
			                    <td width="13%" style="text-align: center;"><b>ACTION</b></td>
			                </tr>
			                <tr><td colspan="3"><hr></td></tr>';
			    echo $Title2;
			    $temp = 0;
			    $select = mysqli_query($conn,"select i.Item_ID, i.Product_Name from tbl_items i where
			                            Item_ID NOT IN (select Item_ID from tbl_edit_price_cache where Employee_ID = '$Employee_ID') order by Product_Name limit 150") or die(mysqli_error($conn));
			    $num = mysqli_num_rows($select);
			    if($num > 0){
			        while ($data = mysqli_fetch_array($select)) {
			?>
			            <tr>
			                <td><?php echo ++$temp; ?></td>
			                <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
			                <td style="text-align: center;">
			                    <input type="button" name="Add_Selected" id="Add_Selected" value=">> >>" onclick="Add_Selected_Item(<?php echo $data['Item_ID']; ?>)">
			                </td>
			            </tr>
			<?php
			            if(($temp%51) == 0){
			                echo $Title2;
			            }
			        }
			    }
			?>
		</table>
<?php } ?>