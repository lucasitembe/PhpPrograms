<?php
	session_start();
	include("./includes/connection.php");
?>
<select name="Item_ID" id="Item_ID">
	<?php
        $select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            echo '<option selected="selected" value="0">All</option>';
            while ($row = mysqli_fetch_array($select)) {
    ?>
        <option value="<?php echo $row['Item_ID']; ?>"><?php echo ucwords(strtolower($row['Product_Name'])); ?></option>
    <?php
            }
        }else{
            echo '<option selected="selected" value="">No Item Picked</option>';
        }
    ?>
</select>