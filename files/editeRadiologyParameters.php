<?php 

	require_once('includes/connection.php');
	
	isset($_GET['Parameter_ID']) ? $EditParam = $_GET['Parameter_ID'] : $EditParam = 0;

	$EditRow = "SELECT * FROM tbl_radiology_parameter WHERE Parameter_ID = '$EditParam'";
	$EditParam_qry = mysqli_query($conn,$EditRow) or die(mysqli_error($conn));
	if($EditParam_qry){
	$row=mysqli_fetch_assoc($EditParam_qry);
		echo '<table width=100%>
				<tr>
					<td width="40%">
						<strong>Enter Parameter Name:</strong>
					</td>
					<td>
                         <input type="text" name="Parameter_Name" style="padding-left:12px; height:28px;" id="Parameter_Name" required="required"
                         value="'.$row['Parameter_Name'].'" 
						 placeholder="Enter Parameter">
					</td>
					<td>
                        <button class="art-button-green" id="itemIDAdd" onClick="SaveEditedTo(\''.$EditParam.'\')" style="margin-left:13px !important;" >ADD</button>
					</td>
				</tr>
			  </table>
			
				<div id="Cached"></div>
				<div id="DelResults"></div>
				<div id="ItemParameters"></div>
		   ';
	} else {
		echo 'error';
	}
	
?>