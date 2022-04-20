<?php 

	require_once('includes/connection.php');
	
	isset($_GET['Item_ID']) ? $Item_ID = $_GET['Item_ID'] : $Item_ID = 0;
	isset($_GET['Parameter_Name']) ? $Parameter_Name = mysqli_real_escape_string($conn,$_GET['Parameter_Name']) : $Parameter_Name = '';
	
//	$cached_data = '';
		$insert_cache = "INSERT INTO tbl_radiology_parameter_cache(Parameter_Name, Item_ID) VALUES('$Parameter_Name', '$Item_ID')";
		$insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
		
		if($insert_cache_qry){
			$select_cache = "
				SELECT * 
					FROM 
					tbl_radiology_parameter_cache rpc,
					tbl_items i
						WHERE
						rpc.Item_ID = i.Item_ID
			";
			
			$select_cache_qry = mysqli_query($conn,$select_cache) or die(mysqli_error($conn));
			$cached_data .= "<br><div class='ex1'><table width='100%' >";
			$cached_data .= "<tr>";
				$cached_data .= "<td colspan='4'>";
					$cached_data .= "<strong>NEW PARAMETERS</strong>";	
				$cached_data .= "</td>";
			$cached_data .= "</tr>";			
			$sn = 1;
			while($cached = mysqli_fetch_assoc($select_cache_qry)){
				$Item_Name = $cached['Product_Name'];	
				$Parameter_Name = $cached['Parameter_Name'];
				$Parameter_ID = $cached['Parameter_ID'];
				$Remove = "<button style='color:red;font-size:17px;font-weight:bold;' onClick='RemoveThis(".
				$Parameter_ID.")'>&times;</button>";
				
				$edit = '<button style="color:yellow;" onClick="editThisParamcache(\''.
				$Parameter_ID.'\',\''.$Parameter_Name.'\')"><img src="images/postediticon.png"/></button>';
				
				$cached_data .= "<tr id='row".$Parameter_ID."'>";
					$cached_data .= "<td width='10%'>" . $sn . "</td>";
					$cached_data .= "<td width='40%'><strong>" . $Item_Name . "</strong></td>";
					$cached_data .= "<td width='40%'><strong>" . $Parameter_Name . "</strong></td>";
					$cached_data .= "<td width='10%' align='right'>" . $edit.$Remove . "</td>";
				$cached_data .= "</tr>";
				
				$sn++;
				
			}
			$cached_data .= "<table></div>";
			$cached_data .= "<button style='float:right;' class='art-button-green' onClick='SaveAll()'>Save Parameters</button>";

		}
	echo $cached_data;
?>

<html>
    <style>
        
    div.ex1 {
    width: 100%;
    overflow-y: scroll;
}
    </style>
    
</html>