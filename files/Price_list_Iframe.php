<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    $control = 'ALL';
    if(isset($_GET['Product_Name'])){
        $Product_Name = $_GET['Product_Name'];   
    }else{
        $Product_Name = '';
    }

    if(isset($_GET['Sponsor_ID'])){
    	$Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
    	$Sponsor_ID = 0;
    }
    if(isset($_GET['Item_Category_ID'])){
    	$Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
    	$Item_Category_ID = 0;
    }
?>

<?php
	$category_condition = ' group by Item_Category_Name,Product_Name';
    if(isset($_GET['Item_Category_ID'])){
		if($_GET['Item_Category_ID'] != 'ALL'){
			$control = 'specific';
			$category_condition = " and ic.Item_category_ID = ".$_GET['Item_Category_ID']." group by Item_Category_Name,Product_Name" ;
		}
    }


    echo '<center><table width =100%>';
    echo "<tr id='thead'><td style='text-align: center; width: 5%'><b>SN</b></td>
				<td width=20%><b>CATEGORY</b></td>
				<td><b>TYPE</b></td>
				<td><b>PRODUCT NAME</b></td>
				<td style='text-align: right; width: 10%;'><b>ITEM PRICE</b></td>
			</tr>";

	if($Sponsor_ID == 0){
		//get general items price
		$sql = "select i.Product_Name, gip.Items_Price, ic.Item_Category_Name, i.Consultation_Type from
								tbl_items i, tbl_general_item_price gip,
								tbl_item_subcategory isc, tbl_item_category ic where
								i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
								ic.Item_category_ID = isc.Item_category_ID and
								i.Product_Name like '%$Product_Name%' and
								i.Item_ID = gip.Item_ID".$category_condition;
		$select = mysqli_query($conn,$sql);
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($row = mysqli_fetch_array($select)) {				
				echo "<tr><td style='text-align: center;'>".$temp."</td>"; 
				echo "<td>".$row['Item_Category_Name']."</td>";
				echo "<td>".$row['Consultation_Type']."</td>";
		        echo "<td>".$row['Product_Name']."</td>";
		        echo "<td style='text-align: right; width: 10%;'>".number_format($row['Items_Price'])."</td>";
				$temp++;
			}
		}
	}else{
		if($control != 'ALL'){
			$select_categ = mysqli_query($conn,"select Item_ID from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic
											where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
											ic.Item_category_ID = isc.Item_category_ID and
											ic.Item_category_ID = '$Item_Category_ID' and
											Product_Name like '%$Product_Name%' order by Product_Name") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($select_categ);
			if($nm > 0){
				while ($itm = mysqli_fetch_array($select_categ)) {
					$Item_ID = $itm['Item_ID'];
					//get general items price
					$sql = "select i.Product_Name, ip.Items_Price, ic.Item_Category_Name, i.Consultation_Type from
							tbl_items i, tbl_item_price ip, tbl_item_subcategory isc, tbl_item_category ic where
							i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
							ic.Item_category_ID = isc.Item_category_ID and
							i.Product_Name like '%$Product_Name%' and
							ip.Sponsor_ID = '$Sponsor_ID' and
							i.Item_ID = '$Item_ID' and
							i.Item_ID = ip.Item_ID".$category_condition;
					$select = mysqli_query($conn,$sql);
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($row = mysqli_fetch_array($select)) {				
							echo "<tr><td style='text-align: center;'>".$temp."</td>"; 
							echo "<td>".$row['Item_Category_Name']."</td>";
							echo "<td>".$row['Consultation_Type']."</td>";
					        echo "<td>".$row['Product_Name']."</td>";
					        echo "<td style='text-align: right; width: 10%;'>".number_format($row['Items_Price'])."</td>";
							$temp++;
						}
					}else{
						//get general items price
						$sql = "select i.Product_Name, gip.Items_Price, ic.Item_Category_Name, i.Consultation_Type from
									tbl_items i, tbl_general_item_price gip,
									tbl_item_subcategory isc, tbl_item_category ic where
									i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
									ic.Item_category_ID = isc.Item_category_ID and
									i.Product_Name like '%$Product_Name%' and
									i.Item_ID = '$Item_ID' and
									i.Item_ID = gip.Item_ID".$category_condition;
						$select = mysqli_query($conn,$sql);
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($row = mysqli_fetch_array($select)) {				
								echo "<tr><td style='text-align: center;'>".$temp."</td>"; 
								echo "<td>".$row['Item_Category_Name']."</td>";
								echo "<td>".$row['Consultation_Type']."</td>";
						        echo "<td>".$row['Product_Name']."</td>";
						        echo "<td style='text-align: right; width: 10%;'>".number_format($row['Items_Price'])."</td>";
								$temp++;
							}
						}
					}
				}
			}
		}else{
			//get general items price
			$sql = "select i.Product_Name, ip.Items_Price, ic.Item_Category_Name, i.Consultation_Type from
					tbl_items i, tbl_item_price ip, tbl_item_subcategory isc, tbl_item_category ic where
					i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
					ic.Item_category_ID = isc.Item_category_ID and
					i.Product_Name like '%$Product_Name%' and
					ip.Sponsor_ID = '$Sponsor_ID' and
					i.Item_ID = ip.Item_ID".$category_condition;
			$select = mysqli_query($conn,$sql);
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($row = mysqli_fetch_array($select)) {				
					echo "<tr><td style='text-align: center;'>".$temp."</td>"; 
					echo "<td>".$row['Item_Category_Name']."</td>";
					echo "<td>".$row['Consultation_Type']."</td>";
			        echo "<td>".$row['Product_Name']."</td>";
			        echo "<td style='text-align: right; width: 10%;'>".number_format($row['Items_Price'])."</td>";
					$temp++;
				}
			}
		}
	}
?>
</table>
</center>