<?php 
  //die("reached");
	require_once('../includes/connection.php');
	
	
	isset($_GET['paraID']) ? $EditParam = $_GET['paraID'] : $EditParam = 0;
    
	if(isset($_GET['saveEdit'])){
	   $Parameter_Name=$_GET['Parameter_Name'];
	   mysql_query("UPDATE tbl_radiology_parameter SET Parameter_Name='$Parameter_Name' WHERE Parameter_ID = '$EditParam'") OR die(mysql_error());
	}
	
	$EditRow = "SELECT * FROM tbl_radiology_parameter WHERE Parameter_ID = '$EditParam'";
	$EditParam_qry = mysql_query($EditRow) or die(mysql_error());
	if($EditParam_qry){
	$row=mysql_fetch_assoc($EditParam_qry);
		echo '<table width=100%>
				<tr>
					<td width="40%">
						<strong>Edit Parameter Name:</strong>
					</td>
					<td>
                         <input type="text" name="Parameter_Name" style="padding-left:12px; height:28px;" id="Parameter_Name_Edit" required="required"
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
