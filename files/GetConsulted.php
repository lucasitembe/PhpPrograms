
<?php
    include("./includes/connection.php");
	
    if(isset($_GET['Employee_IDs'])){
        $Employee_ID = $_GET['Employee_IDs'];
    }else{
        $Employee_ID = "";
    }
    $temp=1;
	echo '<br>';
	
    /* $Select_patients = mysqli_query($conn,"select * from  
			tbl_patient_payments c,tbl_patient_registration pr,tbl_patient_payment_item_list ppl
                          WHERE 
									ppl.Consultant_ID = '$Employee_ID' AND 
								   c.Registration_ID=pr.Registration_ID  AND 
									ppl.Process_Status='not served'
										AND Patient_Direction = 'Direct To Doctor' AND
										c.Patient_Payment_ID=ppl.Patient_Payment_ID 
										GROUP BY pr.Registration_ID ORDER BY pr.Registration_ID ASC ") or die(mysqli_error($conn)); */
 
 /* //  $result = mysqli_query($conn,$Select_patients);
    $Select_patients = mysqli_query($conn,"select * from  
			tbl_patient_registration pr,tbl_consultation co
                          WHERE 
									co.employee_ID = '$Employee_ID' AND 
								  co.Registration_ID=pr.Registration_ID  AND 
									co.Process_Status='served' 
									GROUP BY pr.Registration_ID ORDER BY pr.Registration_ID ASC ") or die(mysqli_error($conn));
     */
	 
	$Select_patients = mysqli_query($conn," SELECT * FROM tbl_patient_registration pr,tbl_sponsor s,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,
		  tbl_patient_payments pp
		  WHERE pr.Registration_ID = c.Registration_ID AND
		  pr.Sponsor_ID = s.Sponsor_ID AND
		  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  c.Employee_ID = '$Employee_ID' AND
		  c.Process_Status = 'served' AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND
		 
		  ppl.Process_Status = 'served' GROUP BY pr.Registration_ID ORDER BY pr.Registration_ID ASC ") or die(mysqli_error($conn));
	 $no = mysqli_num_rows($Select_patients);
        
        if($no>0){
		
		echo '<center><table width =90% border="1px">';
    echo '<tr>	<td width ="3%" style="text-align:center;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td width ="10%"><b>PATIENT NO</b></td>
				<td><b>TRANSFER TO DOCTOR</b></td>
				
				<td><b>REASON FOR TRANSFER</b></td>
				<td style="text-align:center;"><b>SAVE</b></td>
		</tr>'; 
		
		while($row = mysqli_fetch_array($Select_patients)){
		
        echo "<tr><td style='text-align:center;'>".$temp."</td><td>".ucwords(strtolower($row['Patient_Name']))."</td>";
		
		echo "<td style='text-align:center;'>".$row['Registration_ID']."</td>";
	
    $temp++;
	?>
		
	<?php
	
	
	//id="user_<?php echo $row['Item_ID'];"  where Employee_Type='Doctor'
	?>
		<td>
			<select name="employee_ID" id='user_<?php echo $row['Registration_ID'];?>' required='required' >
				<option selected='selected' ></option>
					<?php
							$consults=mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor'");
							{
							while($row_ = mysqli_fetch_array($consults)){
							$Employee_ID = $row_['Employee_ID'];
							$Employee_Name = $row_['Employee_Name'];
					?>
				<option value="<?php echo $Employee_ID;?>"> <?php echo $Employee_Name;?> </option>
			
					<?php
						}
						}
					?>
			</select>
	     </td>
		 
		<!-- <td>
			<select name="employee_ID" id='Clinic_<?php echo $row['Registration_ID'];?>' onchange='disableElement("<?php echo $row['Registration_ID'];?>","Clinic_")'>
					<option selected='selected' ></option>
						<?php
								$clinic=mysqli_query($conn,"Select * from  tbl_clinic ");
								{
								while($rows = mysqli_fetch_array($clinic)){
								$Clinic_Name = $rows['Clinic_Name'];
						?>
					<option id="user_<?php echo $Clinic_ID;?>" value="<?php echo $Clinic_ID;?>">
								<?php echo $Clinic_Name;?>		 			 
					</option>
						<?php
							}
							}
						?>
			</select>
	     </td> -->
		 <td><input type='text' id='reason_<?php echo $row['Registration_ID'];?>'></td>
		 <td style='text-align:center;'>
			<input type="checkbox"  onchange="saveUpdate(this,'<?php echo $row['Registration_ID'];?>')">
		 </td>
 <?php 
  } 
}

else{

echo "<b style='color:red;font-size:20px;'>PATIENT NOT FOUND TO THIS DOCTOR</b>";
}  
?>
 </tr>
</table></center>

