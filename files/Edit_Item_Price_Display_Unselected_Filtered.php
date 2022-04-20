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

    if(isset($_GET['Item_Category_ID'])){
    	$Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
    	$Item_Category_ID = 0;
    }

	if($Item_Category_ID != '0'){
?>

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
	    $select = mysqli_query($conn,"select i.Item_ID, i.Product_Name from tbl_item_subcategory iss, tbl_item_category ic, tbl_items i where
	    						ic.Item_Category_ID = iss.Item_Category_ID and
	    						iss.Item_Subcategory_ID = i.Item_Subcategory_ID and
	    						ic.Item_Category_ID = '$Item_Category_ID' and
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
