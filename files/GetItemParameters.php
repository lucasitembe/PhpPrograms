<?php 
	require_once('includes/connection.php');
	isset($_GET['Item_ID']) ? $Item_ID = $_GET['Item_ID'] : $Item_ID = 0;
	$item_params = "";
		$select_params = "
			SELECT * 
				FROM 
				tbl_radiology_parameter rp,
				tbl_items i
					WHERE
					rp.Item_ID = i.Item_ID AND
					rp.Item_ID = '$Item_ID'
		";
		
		$select_params_qry = mysqli_query($conn,$select_params) or die(mysqli_error($conn));
		$sn = 1;
		
		if(mysqli_num_rows($select_params_qry) > 0){
			$item_params .= "<br/><table width='100%'>";
			$item_params .= "<tr>";
				$item_params .= "<td colspan='4'>";
					$item_params .= "<strong>PREVIOUSLY ADDED PARAMETERS</strong>";	
				$item_params .= "</td>";
			$item_params .= "</tr>";
			while($param = mysqli_fetch_assoc($select_params_qry)){
				$Item_Name = $param['Product_Name'];	
				$Parameter_Name = $param['Parameter_Name'];
				$Parameter_ID = $param['Parameter_ID'];
				$Remove = "<button style='color:red;font-size:17px;font-weight:bold;' onClick='RemoveThisParam(".
				$Parameter_ID.")'>&times;</button>";
				
				$edit = '<button style="color:yellow;" onClick="editThisParam(\''.
				$Parameter_ID.'\',\''.$Parameter_Name.'\')"><img src="images/postediticon.png"/></button>';
				
				$item_params .= "<tr id='param".$Parameter_ID."'>";
					$item_params .= "<td width='10%'>" . $sn . "</td>";
					$item_params .= "<td width='40%'><strong>" . $Item_Name . "</strong></td>";
					$item_params .= "<td width='40%'><strong>" . $Parameter_Name . "</strong></td>";
					$item_params .= "<td width='10%' align='center'>" .$edit. $Remove . "</td>";
				$item_params .= "</tr>";
				$sn++;
			}
			$item_params .= "<table>";
		}
	echo $item_params;
?>

