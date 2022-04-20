<?php
//
$paramdescid1 = 0;
$paramname1 = '';
$paramid1 = 0;
$paramcomment1 = '';

 //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
	if(isset($_GET['PPILI'])){
		$Patient_Payment_Item_List_ID = $_GET['PPILI'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['PPI'])){
		$Patient_Payment_ID = $_GET['PPI'];
	}else{
		$Patient_Payment_ID = '';
	}
if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}

isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = '';
isset($_GET['PatientType']) ? $PatientType = $_GET['PatientType'] : $PatientType = '';
isset($_GET['Doctor']) ? $Doctor = $_GET['Doctor'] : $Doctor = '';

  include("./includes/header.php");
  include("./includes/connection.php");
  $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
  
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
  {
    if(isset($_SESSION['userinfo']['Radiology_Works']))
    {
      if($_SESSION['userinfo']['Radiology_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
    }else
      {
        header("Location: ./index.php?InvalidPrivilege=yes");
      }
    }else
    { @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Radiology_Works'] == 'yes')
            { 
            echo "<a href='radiologyviewimage_Doctor.php?RI=".$Registration_ID."&II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&listtype=".$listtype."&PatientType=".$PatientType."&Doctor=".$Doctor."' class='art-button-green'>BACK</a>";
            }
    }

    //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
    
$patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die (mysqli_error($conn));
    $rows_count = mysqli_num_rows($patient_details);
    if($rows_count > 0){
		while($row = mysqli_fetch_array($patient_details)){
			 $Patient_Name = $row['Patient_Name'];
			 $Gender = $row['Gender'];
			 $Date_Of_Birth = $row['Date_Of_Birth'];
			 
			 //calculate age
			 //$Date_Of_Birth = '1984-08-04';
			$date1 = new DateTime(date('Y-m-d'));
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age.= $diff->m." Months and ";
			$age.= $diff->d." Days ";
		}
    }
?> 
  
<?php 
	//Getting the Item Name
	$select_item = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
	$select_item_qry = mysqli_query($conn,$select_item) or die(mysqli_error($conn));
	while($theitem = mysqli_fetch_assoc($select_item_qry)){
		$item_name = $theitem['Product_Name'];
	}
?>  
<style> 
	table, tr ,td{
		border-collapse:collapse !important;
		border:none !important;
	}
</style>  
  
  
  <br><br><br>
	<fieldset>
		<legend align='center'><b>COMMENTS AND DESCRIPTION</b></legend>
		<center>
			<table width='70%' cellspacing="0" cellpadding="0">
				<strong><?php  echo $Patient_Name ."</strong>  | <strong> ". $Gender."</strong>  | <strong> ".$age."</strong> "; ?> <br />
				<strong> Test For: </strong> <?php echo $item_name; ?>				
			</table>
			
			<table width="50%">
			<?php 
			//Get all Parameters
			$select_allparams = "SELECT * FROM tbl_radiology_parameter";
			$select_allparams_qry = mysqli_query($conn,$select_allparams) or die(mysqli_error($conn));
			while($allparams = mysqli_fetch_assoc($select_allparams_qry)){
				$allparamid = $allparams['Parameter_ID'];
				$allparamname = $allparams['Parameter_Name'];
				
				//Getting the Old Comments
				$select_old = "
				SELECT * 
					FROM 
					tbl_radiology_discription rd,
					tbl_radiology_parameter rp
						WHERE
						rd.Parameter_ID = rp.Parameter_ID AND
						rd.Registration_ID = '$Registration_ID' AND
						rd.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND
						rd.Item_ID = '$Item_ID' AND
						rp.Parameter_ID = '$allparamid'
					";
				$select_old_qry = mysqli_query($conn,$select_old) or die(mysqli_error($conn));
				while($theold1 = mysqli_fetch_assoc($select_old_qry)){
					$paramdescid1 = $theold1['Radiology_Description_ID'];
					$paramname1 = $theold1['Parameter_Name'];
					$paramid1 = $theold1['Parameter_ID'];
					$paramcomment1 = $theold1['comments'];
				}				
				
			?>
				<tr <?php if($allparamid != $paramid1) echo "style='display:none;'"; ?> id="row<?php echo $allparamid; ?>">
					<td> 
						 <?php echo "<strong>" . $allparamname . "</strong>"; ?> 
						<textarea style="padding-left:10px;" id="i<?php echo $allparamid; ?>" readonly="readonly"><?php if($allparamid == $paramid1){ if(isset($paramcomment1)){ echo $paramcomment1; } }?> </textarea> 
					</td>
				</tr>
			<?php
			}
			?>
				<tr>
					
					<td>
						<?php
							//echo "<a style='float:left;' href='radiologyviewimage_Doctor.php?RI=".$Registration_ID."&II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&PatientType=".$PatientType."&listtype=".$listtype."' class='art-button-green'>GO BACK TO DOCTORS PAGE</a>";

							echo "<a style='' href='RadiologyTests_Print.php?RI=".$Registration_ID."&II=".$Item_ID."' class='art-button-green' target='_blank'>P R I N T</a>"; 
						$select_extist = "
							SELECT * 
								FROM tbl_radiology_patient_tests
								WHERE
									Registration_ID = '$Registration_ID' AND
									Item_ID = '$Item_ID' AND 
									Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'
									";
									
						$select_extist_qry = mysqli_query($conn,$select_extist) or die(mysqli_error($conn));
						$count = mysqli_num_rows($select_extist_qry);
						if($count > 0){
							while($existing = mysqli_fetch_assoc($select_extist_qry)){
								$OldResultsStatus = $existing['Results_Status'];
							}
						} else {
							$OldResultsStatus = '';							
						}		
							
						?>
						<div style='float:right;'>
							<span id='status_respond'></span>
							<strong>Results Status:</strong>
							<select disabled="disabled" name='results_status' id='results_status' onChange='UpdateStat(this.value)' style=' height:26px; width:100px; padding-left:10px; color:black;'>
								<option value=''>  </option>
								<option <?php if($OldResultsStatus == 'done') echo "selected=selected"; ?> value='done'> Done </option>
								<option <?php if($OldResultsStatus == 'pending') echo "selected=selected"; ?> value='pending'> Pending </option>
								<option <?php if($OldResultsStatus == 'not done') echo "selected=selected"; ?> value='not done'> Not Done </option>
							</select>
						</div>
					</td>
				</tr>
			</table>
		</center>
	</fieldset>   
<?php
    include("./includes/footer.php");
?>
