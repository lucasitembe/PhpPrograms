<?php
	session_start();
	include("./includes/connection.php");
        
//            $bill_Clinic_ID            =  $_SESSION['bill_Clinic_ID'];
//            $bill_clinic_location_id   = $_SESSION['bill_clinic_location_id'];
            $bill_working_department   =  $_SESSION['bill_working_department'];
             $Select_Name="";

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}
	
	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	
	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}
	
	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['PostPaid'])){
		$PostPaid = $_GET['PostPaid'];
	}else{
		$PostPaid = '';
	}
	if(isset($_GET['nursecommunication'])){
		$nursecommunication = $_GET['nursecommunication'];
		if($nursecommunication =='fromnursecommunication'){
			$filter=" AND nurse_can_add='yes'";
		}
	}else{
		$nursecommunication ='';
		$filter='';
	}
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

	$select = mysqli_query($conn,"select Patient_Name, Gender, Date_Of_Birth, Member_Number from tbl_patient_registration where
							Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Gender = $data['Gender'];
			$Member_Number = $data['Member_Number'];
		}
	}else{
		$Patient_Name = '';
		$Date_Of_Birth = '';
		$Gender = '';
		$Member_Number = '';
	}

	$get_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($get_sponsor);
	if($no > 0){
		while ($row = mysqli_fetch_array($get_sponsor)) {
			$Guarantor_Name = $row['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	$date1 = new DateTime($Today);
	$date2 = new DateTime($Date_Of_Birth);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";

?>
<table width="100%">
	<tr>
		<td width="10%" style="text-align: right;"><b>Patient Name </b>&nbsp;&nbsp;&nbsp;</td>
		<td><?php echo $Patient_Name; ?></td>
		<td width="10%" style="text-align: right;"><b>Patient Number &nbsp;&nbsp;&nbsp;</b></td>
		<td><?php echo $Registration_ID; ?></td>
		<td width="10%" style="text-align: right;"><b>Age&nbsp;&nbsp;&nbsp;</b></td>
		<td><?php echo $age; ?></td>
	</tr>
	<tr>
		<td width="10%" style="text-align: right;"><b>Sponsor Name&nbsp;&nbsp;&nbsp;</b></td>
		<td><?php echo $Guarantor_Name; ?></td>
		<td width="10%" style="text-align: right;"><b>Gender&nbsp;&nbsp;&nbsp;</b></td>
		<td><?php echo $Gender; ?></td>
		<td width="10%" style="text-align: right;"><b>Member Number&nbsp;&nbsp;&nbsp;</b></td>
		<td><?php echo $Member_Number; ?></td>
	</tr>
	<tr><td colspan="6"><hr></td></tr>
</table>
<table width="100%">
	<tr>
		<td width="30%">
			<fieldset>
			<table width="100%">
				<tr>
					<td style="text-align: center;">
						<select name='Item_Category_ID' id='Item_Category_ID' onchange="getItemsList(this.value,'<?php echo $Sponsor_ID?>')">
						    <option selected='selected'></option>
						    <?php
								$data = mysqli_query($conn,"select * from tbl_item_category") or die(mysqli_error($conn));
								while($row = mysqli_fetch_array($data)){
								    echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
								}
						    ?>   
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="Search_Product" id="Search_Product" autocomplete="off" placeholder="~~~ ~~~ ~~~ Enter Item Name ~~~ ~~~ ~~~" style="text-align: center;" oninput="getItemsListFiltered(this.value,'<?php echo $Sponsor_ID?>')" onkeyup="getItemsListFiltered(this.value,'<?php echo $Sponsor_ID?>')">
					</td>
				</tr>
				<tr>
					<td>
						<fieldset style='overflow-y: scroll; height: 300px;' id='Items_Fieldset'>
                                                        
                                                        
							<table class="table table-condensed table-striped">
							    <?php
									$result = mysqli_query($conn,"SELECT Product_Name,ip.Item_ID  FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID  WHERE Status='Available'  AND ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' and i.Item_Type<>'Pharmacy' $filter order by Product_Name LIMIT 100") or die(mysqli_error($conn));
									while($row = mysqli_fetch_array($result)){
									    echo "<tr>
										<td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
										    
										    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>',<?php echo $Sponsor_ID; ?>)">
									       
									       <?php
										echo "</td><td style='color:black; border:2px solid #ccc;text-align: left; padding-left:5px;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
									}
							    ?> 
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
			</fieldset>
		</td>
		<td>
			<fieldset>
				<table width="100%">
					<tr>
						<td width="9%"><b>Check In Type</b></td>
						<?php
						 if ($PostPaid == '1'){
							echo '<td width="9%"><b>Select Clinic</b></td>
							<td width="9%"><b>Location</b></td>';
						 }
						?>
						<td width="9%"><b>Department</b></td>
						<td ><b>Item Name</b></td>
	                    <td width="9%"><b>Price</b></td>
	                    <td width="9%"><b>Discount</b></td>
	                    <td width="9%"><b>Quantity</b></td>
	                    <td width="9%"><b>Action</b></td>
					</tr>
					<tr>
						<td width="9%">  
		                    <select name='Check_In_Type' id='Check_In_Type' required='required'> 
		                        <option selected='selected'></option> 
		                        <option>Dental</option>
		                        <option>Dialysis</option>
		                        <option>Dressing</option>
		                        <option>Ear</option>
		                        <option>Laboratory</option> 
		                        <option>Maternity</option>
		                        <option>Optical</option>
                            	<option>Pharmacy</option>
		                        <option>Procedure</option>
		                        <option>Physiotherapy</option>
		                        <option>Radiology</option> 
		                        <option>Theater</option>
                                        <option>Other</option>
		                    </select>
												</td>
												<?php 
												if ($PostPaid == '1'){
												?>
                                              <td sytle="width:4%">
											
                                                    <select required="" id="Clinic_ID">
                                                        <?php
                                                        $Select_Name = mysqli_fetch_assoc(mysqli_query($conn,"select Clinic_Name from tbl_clinic where Clinic_Status = 'Available' and Clinic_ID='$bill_Clinic_ID'"))['Clinic_Name'];
                                                        
                                                         ?>
                                                        <option value="<?php  echo $bill_Clinic_ID; ?>"><?php echo $Select_Name; ?></option>
                                                        <?php
                                                          $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                                                        $result = mysqli_query($conn,$Select_Consultant);
                                                           ?> 
                                                           <?php
                                                           while ($row = mysqli_fetch_array($result)) {
                                                               ?>
                                                               <option value="<?php  echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                                               <?php
                                                        } 
                                                           ?>
                                                    </select>
                                                </td>
                                               <td width="9%">
													   <select  style='width: 100%;height:30%'  name='clinic_location_id' id='clinic_location_id' required='required'>
													   <option value=""></option>
													   <?php
															$Select_Consultant = "select sub.Sub_Department_ID,sub.Sub_Department_Name from tbl_sub_department sub,tbl_department de WHERE sub.Department_ID= de.Department_ID AND Sub_Department_Status='active'";
															$result = mysqli_query($conn,$Select_Consultant);
															?>
															<?php
															while ($row = mysqli_fetch_array($result)) {
																?>
																<option value="<?php echo $row['Sub_Department_ID']; ?>"><?php echo $row['Sub_Department_Name']; ?></option>
																<?php
															}
															?>
                             <option value="<?php echo $bill_clinic_location_id; ?>"><?php echo $Select_Name2; ?></option>
                            <?php
                             $Select_Consultant = "select clinic_location_name from tbl_clinic_location WHERE enabled_disabled='enabled'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['clinic_location_id']; ?>"><?php echo $row['clinic_location_name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
												</td>
						<?php } ?>
                                                <td width="9%">
                                                     <select id='working_department' name='working_department'  style="width:100%">
                                             <?php
                                                        $Select_Name3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled' AND finance_department_id='$bill_working_department'"))['finance_department_name'];
                                                        
                                               ?>                
                            <option value="<?php echo $bill_working_department; ?>"><?php echo $Select_Name3; ?></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                                                </td>
						<td width="9%">
							<input type="text" name="Item_Name" id="Item_Name" autocomplete="off" placeholder="Item Name" readonly="readonly">
							<input type="hidden" id="Item_ID" name="Item_ID" value="">
						</td>
	                    <td width="9%"><input type="text" name="Price" id="Price" placeholder="Price" readonly="readonly"></td>
                            <td width="9%"><input type="text"readonly="" name="Discount" id="Discount" placeholder="Discount"></td>
	                    <td width="9%"><input type="text" name="Quantity" id="Quantity" placeholder="Quantity"></td>
	                    <td width="9%" style="text-align: center;"><input type="button" value="ADD" class="art-button-green" onclick="Add_Selected_Item()"></td>
					</tr>
				</table>
			</fieldset>
			<fieldset style='overflow-y: scroll; height: 280px; background-color: white;' id='Cached_Items'>
				<table width="100%">
					<tr><td colspan="8"><hr></td></tr>
					<tr>
						<td width="4%"><b>Sn</b></td>
						<td width="12%"><b>Check In Type</b></td>
						<td><b>Item Name</b></td>
						<td width="8%" style="text-align: right;"><b>Price</b>&nbsp;&nbsp;</td>
						<td width="8%" style="text-align: right;"><b>Discount</b>&nbsp;&nbsp;</td>
						<td width="8%" style="text-align: right;"><b>Quantity</b>&nbsp;&nbsp;</td>
						<td width="10%" style="text-align: right;"><b>Sub Total</b>&nbsp;&nbsp;</td>
						<td width="4%"></td>
					</tr>
					<tr><td colspan="8"><hr></td></tr>

					<?php
						$temp = 0; $Grand_Total = 0;
						$select = mysqli_query($conn,"select i.Product_Name, iic.Price, iic.Quantity, iic.Discount, iic.Check_In_Type, iic.Item_Cache_ID from tbl_items i, tbl_inpatient_items_cache iic where
												i.Item_ID = iic.Item_ID and
												iic.Employee_ID = '$Employee_ID' and
												iic.Registration_ID = '$Registration_ID' order by Item_Cache_ID desc") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($data = mysqli_fetch_array($select)) {
					?>
								<tr>
									<td><?php echo ++$temp; ?></td>
									<td><?php echo $data['Check_In_Type']; ?></td>
									<td><?php echo $data['Product_Name']; ?></td>
									<td style="text-align: right;"><?php echo number_format($data['Price']); ?>&nbsp;&nbsp;</td>
									<td style="text-align: right;"><?php echo number_format($data['Discount']); ?>&nbsp;&nbsp;</td>
									<td style="text-align: right;"><?php echo $data['Quantity']; ?>&nbsp;&nbsp;</td>
									<td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']);?>&nbsp;&nbsp;</td>
									<td>
										<input type="button" name="Remove" id="Remove" value="X" onclick="Remove_Item('<?php echo $data['Item_Cache_ID']; ?>','<?php echo $data['Product_Name']; ?>')">
									</td>
								</tr>
					<?php
								$Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
							}
						}
					?>
				</table>
			</fieldset>
			<table width="100%">
				<tr>
					<td width="75%" style="text-align: right;">
						<input type="button" name="Save_Information" id="Save_Information" class="art-button-green" value="SAVE INFORMATION" onclick="Save_Information_Verify()">
					</td>
					<td style="text-align: right;" id="Grand_Total_Area">
						<b>GRAND TOTAL : </b><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>