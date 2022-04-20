<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['disease_group_id'])){
		$disease_group_id = $_GET['disease_group_id'];
	}else{
		$disease_group_id = 0;
	}
        $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
       if(mysqli_num_rows($get_icd_9_or_10_result)>0){
           $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
       }
	//get diseases
	$selectDisease=mysqli_query($conn,"SELECT * FROM tbl_disease_group_mapping dgm where 
							dgm.disease_group_id = '$disease_group_id'") or die(mysqli_error($conn));
if (mysqli_num_rows($selectDisease)>0) {
	$query="SELECT disease_name, d.disease_id, d.disease_code from tbl_disease d LEFT JOIN tbl_disease_group_mapping dgm ON dgm.disease_id = d.disease_id  where 
	dgm.disease_group_id <> '$disease_group_id' AND disease_version='$configvalue_icd10_9' order by d.disease_name limit 200";
} else {
	$query="SELECT disease_name, d.disease_id, d.disease_code from tbl_disease d LEFT JOIN tbl_disease_group_mapping dgm ON dgm.disease_id = d.disease_id  where 
	disease_version='$configvalue_icd10_9' order by d.disease_name limit 200";
}

	$select = mysqli_query($conn,$query) or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "<table width='100%'>";
		while ($data = mysqli_fetch_array($select)) {
?>
<tr>
    <td>
        <input type="button" name="Add" id="Add" value="<<" onclick="Add_Disease('<?php echo $data['disease_id']; ?>')">
    </td>
    <!-- <td width="5%"><?php //echo ++$temp; ?></td> -->
    <td width="95%"><?php echo ucwords($data['disease_name'].' - (<b>'.$data['disease_code']); ?></td>
</tr>
<?php
		}
		echo "</table>";
	}
?>