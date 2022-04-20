<?php
include("./includes/connection.php");
if(isset($_GET['Diagnosis_ID'])){
   $Diagnosis_ID= $_GET['Diagnosis_ID'];
}else{
  $Diagnosis_ID="";  
}
if(isset($_GET['Git_Post_operative_ID'])){
   $Git_Post_operative_ID= $_GET['Git_Post_operative_ID'];
}else{
  $Git_Post_operative_ID="";  
}

$sql_delete_selected_diagnosis_result=mysqli_query($conn,"DELETE FROM tbl_gti_post_operative_diagnosis WHERE Diagnosis_ID='$Diagnosis_ID'  AND saving_status='pending'") or die(mysqli_error($conn));

    ?>
        	<table width="100%" class="table table-condensed">
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td width='4%'><b>SN</b></td>
						<td><b>DIAGNOSIS</b></td>
						<td width='20%'><b>CODE</b></td>
						<td width='8%'><b>ACTION</b></td>
					</tr>
					<tr><td colspan="4"><hr></td></tr>
			<?php
				//get selected diagnosis disease
				$select = mysqli_query($conn,"select d.procedure_dignosis_code, d.procedure_dignosis_name, po.Diagnosis_ID 
										from tbl_procedure_diagnosis d, tbl_gti_post_operative_diagnosis po where
										d.procedure_diagnosis_id = po.procedure_diagnosis_id and
										po.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
			?>
						<tr>
							<td><?php echo ++$temp; ?></td>
							<td><?php echo $data['procedure_dignosis_name']; ?></td>
							<td><?php echo $data['procedure_dignosis_code']; ?></td>
							<td>
								<input type="button" name="Remove_Button" id="Remove_Button" value="REMOVE" class="art-button-green" onclick="Remove_Diagnosis(<?php echo $data['Diagnosis_ID']; ?>,<?= $Git_Post_operative_ID ?>)">
							</td>
						</tr>
			<?php
					}
				}
			?>
				</table>
        <?php
if($sql_delete_selected_diagnosis_result){
}else{
    
}
