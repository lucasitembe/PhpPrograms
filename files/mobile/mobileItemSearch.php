<?php
	include("../includes/connection.php");

	if(isset($_GET['item_searchName'])){
		$item_searchName = $_GET['item_searchName'];
	}else{
		$item_searchName = '';
	}

?>
<tr>
    <th width="10%">SN</th>
    <th>Item</th>
</tr>
    <?php
    	$qr = "SELECT * FROM tbl_items WHERE Visible_Status <> 'Others' AND Product_Name LIKE '%$item_searchName%' ";

    	if(isset($_GET['Item_Type'])){
    		$Item_Type = $_GET['Item_Type'];
    		if($Item_Type!=''){
    			$qr.= " AND Item_Type = '$Item_Type'";
    		}
    	}

    	if(isset($_GET['Item_Category_ID'])){
    		$Item_Category_ID = $_GET['Item_Category_ID'];
    			
    		if($Item_Category_ID!=''){
    			$qr.= " AND Item_Subcategory_ID IN (SELECT Item_Subcategory_ID FROM  tbl_item_subcategory WHERE Item_category_ID='$Item_Category_ID' ) ";
    		}
    	}

        $data = mysqli_query($conn,$qr);
        while($row = mysqli_fetch_array($data)){
        ?><tr><td width="10%"><?php echo $row['Item_ID'];?></td><td><?php echo $row['Product_Name']; ?></td><td><input type="radio" class='choose' name='choose' id='choose' onclick="selectItem('<?php echo $row['Item_ID'];?>','<?php echo $row['Product_Name']; ?>')"></td></tr><?php
        }
    ?>