<?php
    session_start();
    include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = 0;
    }

    if(isset($_GET['Payment_Item_Cache_List_ID'])){
    	$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
    	$Payment_Item_Cache_List_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = 0;
    }
    
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
    	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
    	$Patient_Payment_Item_List_ID = 0;
    }

    if(isset($_GET['Diagnosis_ID'])){
    	$Diagnosis_ID = $_GET['Diagnosis_ID'];
    }else{
    	$Diagnosis_ID = 0;
    }

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}

	/*//get consultation_ID
	$select = mysqli_query($conn,"select consultation_ID from tbl_consultation where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $consultation_ID = $data['consultation_ID'];
        }
    }else{
        $consultation_ID = 0;
    }*/

    if($Employee_ID != 0 && $Payment_Item_Cache_List_ID != 0 && $consultation_ID != 0){
    	//check if data available into tbl_post_operative_notes
    	$select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num < 1){
	    	$insert = mysqli_query($conn,"insert into tbl_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //get Post_operative_ID
	    $get_Post_operative_ID = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $nm = mysqli_num_rows($get_Post_operative_ID);
	    if($nm > 0){
	    	while ($dt = mysqli_fetch_array($get_Post_operative_ID)) {
	    		$Post_operative_ID = $dt['Post_operative_ID'];
	    	}
	    }else{
	    	$Post_operative_ID = 0;
	    }

	    //delete selected Diagnosis_ID
	    $check = mysqli_query($conn,"delete from tbl_post_operative_diagnosis where Diagnosis_ID = '$Diagnosis_ID'") or die(mysqli_error($conn));
	}
?>
<table width="100%">
	<tr><td colspan="4"><hr></td></tr>
	<tr>
		<td width='4%'><b>SN</b></td>
		<td><b>DISEASE</b></td>
		<td width='20%'><b>CODE</b></td>
		<td width='8%'><b>ACTION</b></td>
	</tr>
	<tr><td colspan="4"><hr></td></tr>
<?php
	//get selected diagnosis disease
	$select = mysqli_query($conn,"select d.disease_code, d.disease_name, d.disease_ID, po.Diagnosis_ID 
						from tbl_disease d, tbl_post_operative_diagnosis po where
						d.disease_ID = po.Disease_ID and
						po.Diagnosis_Type = 'Postoperative Diagnosis' and
						po.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['disease_name']; ?></td>
				<td><?php echo $data['disease_code']; ?></td>
				<td>
					<input type="button" name="Remove_Button" id="Remove_Button" value="REMOVE" class="art-button-green" onclick="Remove_Disease(<?php echo $data['Diagnosis_ID']; ?>)">
				</td>
			</tr>
<?php
		}
	}
?>
</table>

