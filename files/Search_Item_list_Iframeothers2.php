<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Product_Name'])){
        $Product_Name = $_GET['Product_Name'];   
    }else{
        $Product_Name = '';
    }
    echo '<center><table width =100% border=1>';
    echo '<tr><td style="text-align: center;"><b>SN</b></td>
	    <td><b>ITEM TYPE</b></td>
		<td><b>PRODUCT NAME</b></td>
		    <td><b>STATUS</b></td>
			<td><b>CATEGORY NAME</b></td>
			    <td><b>SUBCATEGORY NAME</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,"
				    select i.Item_ID, i.Item_Type, i.Status, i.Product_Name, ic.Item_Category_Name, isc.Item_Subcategory_Name from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
					where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
					    isc.Item_category_ID = ic.Item_category_ID and
                                                Visible_Status = 'Others' and
                                                    i.Product_Name like '%$Product_Name%'") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td style='text-align: center;'>".$temp."</td>";
        echo "<td><a href='edititemothers.php?Item_ID=".$row['Item_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Item_Type']."</a></td>";
	echo "<td><a href='edititemothers.php?Item_ID=".$row['Item_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Product_Name']."</a></td>";
	echo "<td><a href='edititemothers.php?Item_ID=".$row['Item_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Status']."</a></td>";
        echo "<td><a href='edititemothers.php?Item_ID=".$row['Item_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Item_Category_Name']."</a></td>";
        echo "<td><a href='edititemothers.php?Item_ID=".$row['Item_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Item_Subcategory_Name']."</a></td>";
	$temp++;
    }   echo "</tr>";
?>
</table>
</center>