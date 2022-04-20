<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Category_Name'])){
        $Category_Name = $_GET['Category_Name'];   
    }else{
        $Category_Name = '';
    }
    echo '<center><table width = 100%>';
    echo '<tr id="thead"><td style="width:10%;"><b>SN</b></td>
		<td><b>SUBCATEGORY NAME</b></td>
		<td><b>CATEGORY NAME</b></td></tr>';
    $select_category_name = mysqli_query($conn,"
				    select * from tbl_item_subcategory isc,tbl_item_category ic 
					where 	isc.Item_category_ID=ic.Item_category_ID and Item_Subcategory_Name like '%$Category_Name%' order by Item_Subcategory_Name") or die(mysqli_error($conn));

    while($row = mysqli_fetch_array($select_category_name)){
        echo "<tr><td style='text-align: center;' id='thead'>".$temp."</td>";
        echo "<td><a href='editsubcategory.php?Item_Subcategory_ID=".$row['Item_Subcategory_ID']."&editsubcategory.php=editsubcategory.phpThisForm' target='_parent' style='text-decoration: none;'>".strtoupper($row['Item_Subcategory_Name'])."</a></td>";
	 echo "<td><a href='editsubcategory.php?Item_Subcategory_ID=".$row['Item_Subcategory_ID']."&editsubcategory.php=editsubcategory.phpThisForm' target='_parent' style='text-decoration: none;'>".strtoupper($row['Item_Category_Name'])."</a></td>"; 
	$temp++;
    }echo "</tr>";
?>
</table>
</center>