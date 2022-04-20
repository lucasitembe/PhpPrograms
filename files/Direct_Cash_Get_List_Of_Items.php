<?php
	session_start();
	include("./includes/connection.php");
        
        $catfilter='';
	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
               $catfilter=" and  c.Item_Category_ID = '$Item_Category_ID' ";
	}

	if(isset($_GET['Item_Name'])){
		$Item_Name = $_GET['Item_Name'];
	}else{
		$Item_Name = '';
	}
?>
<table width=100%>
    <?php
        	$result = mysqli_query($conn,"select i.Item_ID, i.Product_Name 
        							from tbl_items i, tbl_item_subcategory iss, tbl_item_category c where
        							c.Item_Category_ID = iss.Item_Category_ID and
        							iss.Item_Subcategory_ID = i.Item_Subcategory_ID and
        							i.Visible_Status = 'Others' and
        							i.Product_Name like '%$Item_Name%' $catfilter order by Product_Name limit 200") or die(mysqli_error($conn));
      
        while($row = mysqli_fetch_array($result)){
            echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;'>"; 
    ?>
            <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
    <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."<label></td></tr>";
        }
    ?> 
</table>