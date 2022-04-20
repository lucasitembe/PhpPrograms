<?php
	session_start();
	include("./includes/connection.php");
?>

<table width = 100%>
    <tr>
	<td style='text-align: center;'>
	<select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)'>
	    <option selected='selected'></option>
	    <?php
			$data = mysqli_query($conn,"select * from tbl_item_category order by Item_Category_Name");
			while($row = mysqli_fetch_array($data)){
			    echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
			}
	    ?>   
	</select>
	</td>
    </tr>
    <tr>
        <td><input type='text' id='Search_Value' name='Search_Value' onkeyup="getItemsListFiltered(this.value)" placeholder='~~~ ~~ Search Item Name ~~ ~~~' style='text-align: center;' autocomplete="off"></td>
    </tr>
    <tr>
	<td>
	    <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset'>
			<table width=100%>
			    <?php
					$result = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items order by Product_Name LIMIT 100");
					while($row = mysqli_fetch_array($result)){
					    echo "<tr>
						<td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
						    
						    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
					       
					       <?php
						echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
					}
			    ?> 
			</table>
	    </fieldset>		
	</td>
    </tr>
</table> 