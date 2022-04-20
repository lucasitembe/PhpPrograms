<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = 0;
	}

	
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	//get items details required
    $select = mysqli_query($conn,"select consultation_ID from tbl_consultation where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $consultation_ID = $data['consultation_ID'];
        }
    }else{
        $consultation_ID = 0;
    }

    $temp = 0;
    $select_surgery = mysqli_query($conn,"select  i.Product_Name, ilc.Status, ilc.Transaction_Type, pc.Billing_Type, ilc.Doctor_Comment, ilc.Payment_Item_Cache_List_ID
                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items i where
                                    pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                    i.Item_ID = ilc.Item_ID and
                                    ilc.Check_In_Type = 'Surgery' and
                                    pc.consultation_id = '$consultation_ID' and
                                    ilc.Status <> 'removed' and
                                    ilc.Consultant_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num_surgery = mysqli_num_rows($select_surgery);
    echo "<fieldset style='overflow-y: scroll; background-color: white; height: 200px;'>";
    if($num_surgery > 0){
    	$temp = 0;
		echo '<table width="100%">';
		while ($data = mysqli_fetch_array($select_surgery)) {
?>
			<tr>
				<td width="2%"><?php echo ++$temp; ?></td>
				<td><?php echo $data['Product_Name']; ?></td>
				<td width="12%"><input type="button" value="POST OPERATIVE" class="art-button-green" onclick="Preview_Operative_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)"></td>
				<td width="12%"><input type="button" value="COLONOSCOPY" class="art-button-green" onclick="Preview_Colonoscopy_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)"></td>
				<td width="12%"><input type="button" value="UPPER GIT SCOPE" class="art-button-green" onclick="Preview_Upper_Git_Scope_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)"></td>
				<td width="8%"><input type="button" value="ALL" class="art-button-green" onclick="Preview_Operative_All_Report(<?php echo $Registration_ID; ?>,<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $data['Payment_Item_Cache_List_ID']; ?>)"></td>
			</tr>
<?php
		}
		echo '</table>';
		echo '</fieldset>';
    }
?>