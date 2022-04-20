<?php
    include("./includes/header.php");
    include("./includes/connection.php");
     $temp=1;
	// $temp=++;
	 
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
		
    }


	if (isset($_GET['Registration_ID'])) {
		$Registration_ID = $_GET['Registration_ID'];
	} else {
		$Registration_ID = '';
	}
	
	if (isset($_GET['consultation_ID'])) {
		$consultation_ID = $_GET['consultation_ID'];
	} else {
		$consultation_ID = '';
	}
	
	
	if (isset($_GET['Payment_Item_Cache_List_ID'])) {
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	} else {
		$Payment_Item_Cache_List_ID = '';
	}
?>


<input type='button' onclick='window.close();' class='art-button-green' value='BACK'>

<br>
<br>

<fieldset>
	<legend align=center>PRE-OP SURGICAL - ANESTHESIA CHECKLIST</legend>

<?php
	$Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1"))['Admision_ID'];

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"SELECT Registration_ID,
                                    Old_Registration_Number, Patient_Name,Title,
                                    Date_Of_Birth,Gender,pr.Region,pr.District,pr.Ward,pr.Sponsor_ID,Member_Number,Member_Card_Expire_Date,
				    pr.Phone_Number,Email_Address,Occupation,Employee_Vote_Number,Emergence_Contact_Name,
				    Emergence_Contact_Number,Company,Employee_ID,Registration_Date_And_Time,Patient_Picture,Guarantor_Name
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        
										
				    where pr.Sponsor_ID = sp.Sponsor_ID  and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Title = $row['Title'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
				$Region = $row['Region'];
                $District = $row['District'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Ward = $row['Ward'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Member_Number = $row['Member_Number'];
				$Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Patient_Picture = $row['Patient_Picture'];
				$Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Occupation = $row['Occupation'];
                $Email_Address = $row['Email_Address'];
				$Guarantor_Name = $row['Guarantor_Name'];
				                
				
                
               }
			   
		$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	    
	   
	    
        }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
	            $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
                $Guarantor_Name=''; 
             			
        }
    }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
		        $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
		        $Guarantor_Name='';
				
        }
// $htm = '';
$Pre_Operative_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Pre_Operative_ID'];
if($Pre_Operative_ID > 0){
	$SN =1;
		$operative_details = mysqli_query($conn, "SELECT * FROM tbl_who_pre_operative_checklist  WHERE Pre_Operative_ID = '$Pre_Operative_ID'");
		while($surgical = mysqli_fetch_assoc($operative_details)){
			$doctor_list = $surgical['doctor_list'];
			$Theatre_Time = $surgical['Theatre_Time'];
			$Surgeon_filled = $surgical['Surgeon_filled'];
			$surgeon = $surgical['surgeon'];
			$nurse_instruments = $surgical['nurse_instruments'];
			$Anesthetist = $surgical['Anesthetist'];
			$Special_Information = $surgical['Special_Information'];
			$Handling_nurse = $surgical['Handling_nurse'];
			$surgery = $surgical['surgery'];
			$Operative_Date_Time = substr($surgical['Operative_Date_Time'], 0, -9);
			$can_proceed = $surgical['can_proceed'];
			$instruction = $surgical['instruction'];
			$Theatre_Signature = $surgical['Theatre_Signature'];
			$recovery_nurse = $surgical['recovery_nurse'];
			$Level_Status = $surgical['Level_Status'];
			$can_proceed = $surgical['can_proceed'];
		}
		
		$preoperative_item_list = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn < 9 AND Sn <> '0'");
		while($details = mysqli_fetch_array($preoperative_item_list)){
			$task_Name = $details['Task_Name'];
			$task_value = $details['Task_Value'];
			$Remarkss = $details['Remark'];
			$num = $details['Sn'];

			if($Remarkss == ''){
				$Remarkss = "<b> NO REMARK</b>";
			}
	
			$htm1 ="<tr>
						<td>$SN</td>
						<td>$task_Name</td>
						<td style='text-align: left; font-weight: bold;'>".strtoupper($task_value)."</td>
						<td>$Remarkss</td>
					</tr>";
			 $data1 .= $htm1;
			 $SN++;
		}
		
		$preoperative_item_list2 = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn BETWEEN 9 AND 20 GROUP BY Sn ORDER BY Sn ASC");
		$htm = "
		<tr>
			<th colspan='4'>TIME OUT PRIOR TO SKIN INCISION</th>
		</tr>";
		while($details = mysqli_fetch_array($preoperative_item_list2)){
			$task_Name = $details['Task_Name'];
			$task_value = $details['Task_Value'];
			$Remarkss = $details['Remark'];
			$num = $details['Sn'];

			if($Remarkss == ''){
				$Remarkss = "<b> NO REMARK</b>";
			}
	
			$htm2 ="<tr>
						<td>$num</td>
						<td>$task_Name</td>
						<td style='text-align: left; font-weight: bold;'>".strtoupper($task_value)."</td>
						<td>$Remarkss</td>
					</tr>";
			 $data2 .= $htm2;
			 $SN++;
			 // $num++;
		}

		// $data2 = $data;

		$preoperative_item_list3 = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn > 20 ORDER BY Sn ASC LIMIT 6");
		while($details = mysqli_fetch_array($preoperative_item_list3)){
			$task_Name = $details['Task_Name'];
			$task_value = $details['Task_Value'];
			$Remarkss = $details['Remark'];
			$num = $details['Sn'];

			if($Remarkss == ''){
				$Remarkss = "<b> NO REMARK</b>";
			}
	
			$htm3 ="<tr>
						<td>$SN</td>
						<td>$task_Name</td>
						<td style='text-align: left; font-weight: bold;'>".strtoupper($task_value)."</td>
						<td>$Remarkss</td>
					</tr>";
			 $data3 .= $htm3;
			 // $num++;
		}
}

?>
<?php

$admission_details = mysqli_query($conn, "SELECT hw.Hospital_Ward_Name, wr.room_name, ad.Bed_Name FROM tbl_admission ad, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE ad.Admision_ID = '$Admision_ID' AND wr.ward_room_id = ad.ward_room_id AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID");
echo '<center>';

echo "<h5><b>".ucwords(strtolower($Patient_Name))."</b>";
				echo " | ";
				echo "<b>".$Registration_ID."</b>";
				echo " | ";
				echo "<b>".$Gender."</b>";
				echo " | ";
				echo "<b>".$age."</b>";
				echo " | ";
				echo "<b>".$Guarantor_Name."</b></h5>";

// echo '<br/>';
    if(mysqli_num_rows($admission_details) > 0){
        while($wodini = mysqli_fetch_assoc($admission_details)){
            $Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
            $room_name = $wodini['room_name'];
            $Bed_Name = $wodini['Bed_Name'];


            echo "<h5>Ward: <b>".$Hospital_Ward_Name." </b> - Room/Bed Number: <b>".$room_name."/".$Bed_Name."</b></h5>
            ";
        }
    }else{
        echo "<span style='font-size: 18px; color: red; font-weight: bold'>THE PATIENT WAS NOT ADMITTED IN THE SYSTEM</span>";
    }

//  echo '<br/>';

$surgery_details = mysqli_query($conn, "SELECT i.Product_Name, sd.Sub_Department_Name, em.Employee_Name FROM tbl_item_list_cache ilc, tbl_items i, tbl_employee em, tbl_sub_department sd WHERE i.Item_ID = ilc.Item_ID and Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID");


while($ops = mysqli_fetch_assoc($surgery_details)){
	$Product_Name = $ops['Product_Name'];
	$Sub_Department_Name = $ops['Sub_Department_Name'];
	$Employee_Name = $ops['Employee_Name'];


	echo "<h5>Operation: <b>".$Product_Name." </b> - Surgeon: <b>".$Employee_Name."</b> - Location/Theatre: <b>".$Sub_Department_Name."</b></h5>
	</center>";
}
?>
<br>
    <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 90%; margin:0 5%">
        	<tr style='background: #dedede; position: static !important;'>
				<th style="text-align:left; width: 2%;" ><b>SN</b></th>
				<th style="text-align:left; width: 25%;" ><b>TASK</b></th>
				<th style="text-align:left; width: 5%;" ><b>CHECK</b></th>
				<th style="text-align:left; width: 15%;" ><b>REMARKS</b></th>
			</tr>
			<tr>
				<th colspan='4'>SIGN IN BEFORE INDUCTION OF ANESTHESIA</th>
			</tr>

			<?php
				if($Pre_Operative_ID > 0){
					echo $data1;
				}else{

				?>


			<tr>
				<td>1</td>
				<td >Patient confirmed indentity, site, procedure and consent</td>
				<td><input type='radio' id='checkbox1' name='checkbox1' value='yes'>Yes
					<input type='radio' id='checkbox1' name='checkbox1' value='no'>No
					<input type='radio' id='checkbox1' name='checkbox1' value='N/A'>N/A</td>
				<td><input type='text' id='remark1' placeholder='Fill the Remarks, If any' name='remark1'></td>
			</tr>

			<tr>
				<td>2</td>
				<td>Site marked (for non midline procedures)</td>
				<td><input type='radio' id='checkbox2' name='checkbox2' value='yes'>Yes
				<input type='radio' id='checkbox2' name='checkbox2' value='no'>No
				<input type='radio' id='checkbox2' name='checkbox2' value='N/A'>N/A</td>
				<td><input type='text' placeholder='Fill the Remarks, If any' name='remark2' id='remark2'></td>
			</tr>
			<tr>
				<td>3</td>
				<td>Anesthesia safety check completed</td>
				<td><input type='radio' id='checkbox3' name='checkbox3' value='yes'>Yes
					<input type='radio' id='checkbox3' name='checkbox3' value='no'>No
					<input type='radio' id='checkbox3' name='checkbox3' value='N/A'>N/A</td>
				<td><input type='text' id='remark3' placeholder='Fill the Remarks, If any' name='remark3'></td>
			</tr>

			<tr>
				<td>4</td>
				<td>Allergy?</td>
				<td><input type='radio' id='checkbox4' name='checkbox4' value='yes'>Yes
					<input type='radio' id='checkbox4' name='checkbox4' value='no'>No
					<input type='radio' id='checkbox4' name='checkbox4' value='N/A'>N/A</td>
				<td><input type='text' id='remark4' placeholder='Fill the Remarks, If any' name='remark4'></td>
			</tr>
			<tr>
				<td>5</td>
				<td>Difficult airway? Aspiration risk?</td>
				<td><input type='radio' id='checkbox5' name='checkbox5' value='yes'>Yes
					<input type='radio' id='checkbox5' name='checkbox5' value='no'>No
					<input type='radio' id='checkbox5' name='checkbox5' value='N/A'>N/A</td>
				<td><input type='text' id='remark5' placeholder='Fill the Remarks, If any' name='remark5'></td>
			</tr>
			<tr>
				<td>6</td>
				<td>Has the patient fasted?</td>
				<td><input type='radio' id='checkbox6' name='checkbox6' value='yes'>Yes
					<input type='radio' id='checkbox6' name='checkbox6' value='no'>No
					<input type='radio' id='checkbox6' name='checkbox6' value='N/A'>N/A</td>
				<td><input type='text' id='remark6' placeholder='Fill the Remarks, If any' name='remark6'></td>
			</tr>
			<tr>
				<td>7</td>
				<td>Risk of >500 ml blood loss? (7mls/kg in children)</td>
				<td><input type='radio' id='checkbox7' name='checkbox7' value='yes'>Yes
					<input type='radio' id='checkbox7' name='checkbox7' value='no'>No
					<input type='radio' id='checkbox7' name='checkbox7' value='N/A'>N/A</td>
				<td><input type='text' id='remark7' placeholder='Fill the Remarks, If any' name='remark7'></td>
			</tr>
			<tr>
				<td>8</td>
				<td >Adequate IV access in place and working</td>
				<td><input type='radio' id='checkbox8' name='checkbox8' value='yes'>Yes
					<input type='radio' id='checkbox8' name='checkbox8' value='no'>No
					<input type='radio' id='checkbox8' name='checkbox8' value='N/A'>N/A
				</td>
				<td><input type='text' id='remark8' placeholder='Fill the Remarks, If any' name='remark8'></td>
			</tr>
			<tr>
					<td colspan='2' style="text-align:right; font-weight: bold;">Remarks: The Operation can Proceed?</td>
					<td colspan='3'><input type='radio' id='can_proceed' name='can_proceed' value='yes'>Yes
									<input type='radio' id='can_proceed' name='can_proceed' value='no'>No
					</td>
				</tr>
			<tr>
				<td colspan="4" style="text-align: center;">
					<input type="button" value="SUBMIT FORM 1" onclick="save_level_1()" class='art-button-green'>
				</td>
			</tr>

			<?php
			
				}
			if($Level_Status > 1){
				echo $htm.$data2;
			}elseif($Pre_Operative_ID != 0){
			?>
			<tr>
				<th colspan='4' style='background: #dedede;'>TIME OUT PRIOR TO SKIN INCISION</th>
			</tr>
			<tr>
				<td>9</td>
				<td>Confirm all team members have introduced themselves-name & role</td>
				<td> <input type='radio' id='checkbox9' name='checkbox9' value='yes'>Yes
					<input type='radio' id='checkbox9' name='checkbox9' value='no'>No
					<input type='radio' id='checkbox9' name='checkbox9' value='N/A'>N/A</td>
				<td><input  id='remark9' placeholder='Fill the Remarks, If any' name='remark9' type='text'></td>
			</tr>
			<tr>
				<td>10</td>
				<td>Surgeon, Anesthetist and nurse verbally confirm: patient, side, procedure, position</td>
				<td><input type='radio' id='checkbox10' name='checkbox10' value='yes'>Yes
					<input type='radio' id='checkbox10' name='checkbox10' value='no'>No
					<input type='radio' id='checkbox10' name='checkbox10' value='N/A'>N/A</td>
				<td><input type='text' id='remark10' placeholder='Fill the Remarks, If any' name='remark10'></td>
			</tr>       
				
			<tr>
				<td>11</td>
				<td>Antibiotic Prophylaxis: given in the last 60mins?</td>
				<td><input type='radio' id='checkbox11' name='checkbox11' value='yes'>Yes
					<input type='radio' id='checkbox11' name='checkbox11' value='no'>No
					<input type='radio' id='checkbox11' name='checkbox11' value='N/A'>N/A</td>
				<td><input type='text' placeholder='Fill the Remarks, If any' name='remark11' id='remark11'></td>
			</tr>
						
			
			

			<tr>
				<td>12</td>
				<td>Essential imaging displayed? (X-RAYS/CT SCAN/MRI)</td>
				<td><input type='radio' id='checkbox12' name='checkbox12' value='yes'>Yes
					<input type='radio' id='checkbox12' name='checkbox12' value='no'>No
					<input type='radio' id='checkbox12' name='checkbox12' value='N/A'>N/A</td>
				<td><input type='text' placeholder='Fill the Remarks, If any' name='remark12' id='remark12'></td>
			</tr>
						
			<tr>
				<td>13</td>
				<td>Are the Supplies and Instrument sets correct before operation??</td>
				<td><input type='radio' id='checkbox13' name='checkbox13' value='yes'>Yes
					<input type='radio' id='checkbox13' name='checkbox13' value='no'>No
					<input type='radio' id='checkbox13' name='checkbox13' value='N/A'>N/A</td>
				<td><input type='text' id='remark13' placeholder='Fill the Remarks, If any' name='remark13' ></td>
			</tr>
						
			<tr>
				<td></td>
				<td><b>ANTICIPATED CRITICAL EVENTS</b></td>

						

			<tr>
				<td></td>
				<th style="text-align:left;">Surgeon reviews:</th>
			</tr>
			<tr>
				<td>14</td>
				<td>Are the suppplies and Instrument sets correct before operation?	</td>
				<td><input type='radio' id='checkbox14' name='checkbox14' value='yes'>Yes
					<input type='radio' id='checkbox14' name='checkbox14' value='no'>No
					<input type='radio' id='checkbox14' name='checkbox14' value='N/A'>N/A</td>
				<td><input type='text' id='remark14' placeholder='Fill the Remarks, If any' name='remark14'></td>
			</tr>
			<tr>
				<td>15</td>
				<td>What are the critical or unexpected steps:anticipated operative duration and blood loss?</td>
				<td><input type='radio' id='checkbox15' name='checkbox15' value='yes'>Yes
					<input type='radio' id='checkbox15' name='checkbox15' value='no'>No
					<input type='radio' id='checkbox15' name='checkbox15' value='N/A'>N/A</td>
				<td><input type='text' id='remark15' placeholder='Fill the Remarks, If any' name='remark15'></td>
			</tr>
						
					
			<tr>
				<td>16</td>
				<td>Anesthesia team reviews</td>
				<td><input type='radio' id='checkbox16' name='checkbox16' value='yes'>Yes
					<input type='radio' id='checkbox16' name='checkbox16' value='no'>No
					<input type='radio' id='checkbox16' name='checkbox16' value='N/A'>N/A</td>
				<td><input type='text' id='remark16' placeholder='Fill the Remarks, If any' name='remark16'></td>
			</tr>

			<tr>
				<td>17</td>
				<td>Patient specific reviews</td>
				<td><input type='radio' id='checkbox17' name='checkbox17' value='yes'>Yes
					<input type='radio' id='checkbox17' name='checkbox17' value='no'>No
					<input type='radio' id='checkbox17' name='checkbox17' value='N/A'>N/A</td>
				<td><input type='text' id='remark17' placeholder='Fill the Remarks, If any' name='remark17'></td>
			</tr>

			<tr>
				<td>18</td>
				<td>Nursing Team reviews:</td>
				<td><input type='radio' id='checkbox18' name='checkbox18' value='yes'>Yes
					<input type='radio' id='checkbox18' name='checkbox18' value='no'>No
					<input type='radio' id='checkbox18' name='checkbox18' value='N/A'>N/A</td>
				<td><input type='text' id='remark18' placeholder='Fill the Remarks, If any' name='remark18'></td>
			</tr>
			<tr>
				<td>19</td>
				<td>Sterility indication results, equipment issues or other concerns?</td>
				<td><input type='radio' id='checkbox19' name='checkbox19' value='yes'>Yes
					<input type='radio' id='checkbox19' name='checkbox19' value='no'>No
					<input type='radio' id='checkbox19' name='checkbox19' value='N/A'>N/A</td>
				<td><input type='text' id='remark19' placeholder='Fill the Remarks, If any' name='remark19'></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;">
					<input type="button" value="SUBMIT FORM 2" onclick="save_level_2()" class='art-button-green'>
				</td>
			</tr>
			<?php
			
				}
			if($Level_Status > 2){
				echo $data3;
			}elseif($Pre_Operative_ID != 0 && $Level_Status > 1){
			?>
				<tr>
					<th colspan='4' style='background: #dedede;'>SIGN OUT-PRIOR TO PATIENT LEAVING THEATRE</th>
				</tr> 
				<tr>
					<td></td>
					<td colspan='3'>NURSE VERBALLY CONFIRMS WITH THE TEAM:</td>
				</tr>     
				<tr>
					<td style="text-align:left;">20</td>
					<td >Actual procedure performed: Is it recorded in the Theatre register?</td>
					<td><input type='radio' id='checkbox20' name='checkbox20' value='yes'>Yes
						<input type='radio' id='checkbox20' name='checkbox20' value='no'>No
						<input type='radio' id='checkbox20' name='checkbox20' value='N/A'>N/A</td>
					<td><input type='text' id='remark20' placeholder='Fill the Remarks, If any' name='remark20'></td>
				</tr>

				<tr>
					<td style="text-align:left;">21</td>
					<td>Are the instrument,swab and needle counts corrects?</td>
					<td><input type='radio' id='checkbox21' name='checkbox21' value='yes'>Yes
					<input type='radio' id='checkbox21' name='checkbox21' value='no'>No
					<input type='radio' id='checkbox21' name='checkbox21' value='N/A'>N/A</td>
					<td><input type='text' placeholder='Fill the Remarks, If any' name='remark21' id='remark21'></td>
				</tr>
				<tr>
					<td style="text-align:left;">22</td>
					<td>Specimens: correct labeling, forms, preservative</td>
					<td><input type='radio' id='checkbox22' name='checkbox22' value='yes'>Yes
					<input type='radio' id='checkbox22' name='checkbox22' value='no'>No
					<input type='radio' id='checkbox22' name='checkbox22' value='N/A'>N/A</td>
					<td><input type='text' placeholder='Fill the Remarks, If any' name='remark22' id='remark22'></td>
				</tr>
				<tr>
					<td style="text-align:left;">23</td>
					<td>Is the patient on the Operation List?</td>
					<td><input type='radio' id='checkbox23' name='checkbox23' value='yes'>Yes
					<input type='radio' id='checkbox23' name='checkbox23' value='no'>No
					<input type='radio' id='checkbox23' name='checkbox23' value='N/A'>N/A</td>
					<td><input type='text' placeholder='Fill the Remarks, If any' name='remark23' id='remark23'></td>
				</tr>
				<tr>
					<td style="text-align:left;">24</td>
					<td>Equipment Issues/malfunctions reported</td>
					<td><input type='radio' id='checkbox24' name='checkbox24' value='yes'>Yes
					<input type='radio' id='checkbox24' name='checkbox24' value='no'>No
					<input type='radio' id='checkbox24' name='checkbox24' value='N/A'>N/A</td>
					<td><input type='text' placeholder='Fill the Remarks, If any' name='remark24' id='remark24'></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='3'>SURGEON, ANESTHETIST, NURSE REVIEW:</td>
				</tr>     
				<tr>
					<td style="text-align:left;">25</td>
					<td >Key concerns and post OP management</td>
					<td><input type='radio' id='checkbox25' name='checkbox25' value='yes'>Yes
						<input type='radio' id='checkbox25' name='checkbox25' value='no'>No
						<input type='radio' id='checkbox25' name='checkbox25' value='N/A'>N/A</td>
					<td><input type='text' id='remark25' placeholder='Fill the Remarks, If any' name='remark25'></td>
				</tr>
				<tr>
					<td style="text-align:left;">26</td>
					<td >Will the patient need ICU Admission_ID?</td>
					<td><input type='radio' id='checkbox26' name='checkbox26' value='yes'>Yes
						<input type='radio' id='checkbox26' name='checkbox26' value='no'>No
						<input type='radio' id='checkbox26' name='checkbox26' value='N/A'>N/A</td>
					<td><input type='text' id='remark26' placeholder='Fill the Remarks, If any' name='remark26'></td>
				</tr>

				<tr>
					<td colspan='2' style="text-align:right; font-weight: bold;">Completed By?</td>
					<td colspan='3'></td>
				</tr>
				<tr>
					<td colspan='2' style="text-align:right;">Surgeon :</td>
					<td colspan='3'>
						<select style='width:100%;' name='Surgeon_filled' id='Surgeon_filled' placeholder='Select Theater Nurse' required='required'>
							<option selected='selected' value=''></option>
											<?php
										$data = mysqli_query($conn,"SELECT Employee_ID, Employee_Name from tbl_employee WHERE Employee_Job_Code = 'Surgeon' AND Account_Status = 'Active' AND employee_signature IS NOT NULL");
											while($row = mysqli_fetch_array($data)){
										?>
										<option value='<?php echo $row['Employee_ID'];?>'>
										<?php echo $row['Employee_Name']; ?>
										</option>
										<?php
											}
										?>
						</select>

					</td>
					
					<td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
					<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
				</tr>

				<tr>
					<td colspan='2' style="text-align:right;">Nurse :</td>
					<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
					<td colspan='3'>
						<select style='width:100%;' name='nurse_instruments' id='nurse_instruments' placeholder='Select Theater Nurse' required='required'>
							<option selected='selected' value=''></option>
											<?php
										$data = mysqli_query($conn,"SELECT Employee_ID, Employee_Name from tbl_employee where Employee_Type LIKE '%nurse%' AND Account_Status = 'Active' AND employee_signature IS NOT NULL");
											while($row = mysqli_fetch_array($data)){
										?>
										<option value='<?php echo $row['Employee_ID'];?>'>
										<?php echo $row['Employee_Name']; ?>
										</option>
										<?php
											}
										?>
						</select>
					</td>
				
				</tr>
					
				<tr>
					<td colspan='2' style="text-align:right;">Anesthetist :</td>
					<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
					<td colspan='3'>
						<select style='width:100%;' name='Anesthetist' id='Anesthetist' placeholder='Select Theater Nurse' >
							<option selected='selected' value=''></option>
											<?php
										$data = mysqli_query($conn,"SELECT Employee_ID, Employee_Name from tbl_employee  WHERE Employee_Job_Code IN ('Anaesthesiologist','Endoscopist_and_Anaesthesiologist','Anaesthesia') AND Account_Status = 'Active' AND employee_signature IS NOT NULL");
											while($row = mysqli_fetch_array($data)){
										?>
										<option value='<?php echo $row['Employee_ID'];?>'>
										<?php echo $row['Employee_Name']; ?>
										</option>
										<?php
											}
										?>
						</select>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;">
					<input type="button" value="SUBMIT FINAL FORM" onclick="save_level_3()" class='art-button-green'>
				</td>
			</tr>

			<?php
			}
			?>
		</table>
</div>


</fieldset>
<?php
// if(isset($_POST['submittedProActiveCheckList'])){

	    $checkboxvalue1 = 'Patient confirmed indentity, site, procedure and consent';
	    $checkboxvalue2 = 'Site marked (for non midline procedures)';
	    $checkboxvalue3 = 'Anestesia safety check completed';
	    $checkboxvalue4 = 'Allergy?';
	    $checkboxvalue5 = 'Difficult airway? Aspiration risk?'; 
	    $checkboxvalue6 = 'Has patient fasted?';
	    $checkboxvalue7 = 'Risk of >500 mls blood loss? (7mls/kg in children)';
	    $checkboxvalue8 ='Adequate IV access in place and working';
	    $checkboxvalue9 ='Confirm all team members have introduced themselves-name & role';
	    $checkboxvalue10='Patient Fasted';
	    $checkboxvalue11='Surgeon, Anesthetist and nurse verbally confirm: patient, side, procedure, position';
	    $checkboxvalue12='Antibiotic Prophylaxis: given in the last 60mins?';
	    $checkboxvalue13='Essential imaging displayed? (X-RAY/CT SCAN/MRI)';
	    $checkboxvalue14='Are the suppplies and Instrument sets correct before operation?';
	    $checkboxvalue15='What are the critical or unexpected steps: anticipated operative duration and blood loss?';
	    $checkboxvalue16='Anesthesia team reviews';
	    $checkboxvalue17='Patient specific reviews';
	    $checkboxvalue18 ='Nursing Team reviews:';
	    $checkboxvalue19 ='Sterility indication results, equipment issues or other concerns?';
	    $checkboxvalue20 ='Actual procedure performed: Is it recorded in the Theatre register?';
	    $checkboxvalue21 ='Are the instrument,swab and needle counts corrects?';
	    $checkboxvalue22 ='Specimens: correct labeling, forms, preservative';
	    $checkboxvalue23 ='Is the patient on the Operation list?';
	    $checkboxvalue24 ='Equipment Issues/malfunctions reported?';
	    $checkboxvalue25 ='Key concerns and post OP management?';
	    $checkboxvalue26 ='Will the patient need ICU Admission_ID?';

include("includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>


<script>
    $(document).ready(function (e){
        $("#Anesthetist").select2();
        $("#nurse_instruments").select2();
        $("#Surgeon_filled").select2();
    });

	function save_level_1(){
		Registration_ID = '<?= $Registration_ID ?>';
		consultation_ID = '<?= $consultation_ID ?>';
		Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
		Admision_ID = '<?= $Admision_ID ?>';
		Employee_ID = "<?= $_SESSION['userinfo']['Employee_ID']?>";

		checkbox1 = $("input[name = 'checkbox1']:checked").val();
		checkbox2 = $("input[name = 'checkbox2']:checked").val();
		checkbox3 = $("input[name = 'checkbox3']:checked").val();
		checkbox4 = $("input[name = 'checkbox4']:checked").val();
		checkbox5 = $("input[name = 'checkbox5']:checked").val();
		checkbox6 = $("input[name = 'checkbox6']:checked").val();
		checkbox7 = $("input[name = 'checkbox7']:checked").val();
		checkbox8 = $("input[name = 'checkbox8']:checked").val();
		can_proceed = $("input[name = 'can_proceed']:checked").val();

		checkboxvalue1 = '<?= $checkboxvalue1 ?>';
		checkboxvalue2 = '<?= $checkboxvalue2 ?>';
		checkboxvalue3 = '<?= $checkboxvalue3 ?>';
		checkboxvalue4 = '<?= $checkboxvalue4 ?>';
		checkboxvalue5 = '<?= $checkboxvalue5 ?>';
		checkboxvalue6 = '<?= $checkboxvalue6 ?>';
		checkboxvalue7 = '<?= $checkboxvalue7 ?>';
		checkboxvalue8 = '<?= $checkboxvalue8 ?>';
		remark1 = $("#remark1").val();
		remark2 = $("#remark2").val();
		remark3 = $("#remark3").val();
		remark4 = $("#remark4").val();
		remark5 = $("#remark5").val();
		remark6 = $("#remark6").val();
		remark7 = $("#remark7").val();
		remark8 = $("#remark8").val();
		doc1 = '1';
		doc2 = '2';
		doc3 = '3';
		doc4 = '4';
		doc5 = '5';
		doc6 = '6';
		doc7 = '7';
		doc8 = '8';

		const checkbox = [checkbox1,checkbox2,checkbox3,checkbox4,checkbox5,checkbox6,checkbox7,checkbox8];
		const checkboxvalue = [checkboxvalue1,checkboxvalue2,checkboxvalue3,checkboxvalue4,checkboxvalue5,checkboxvalue6,checkboxvalue7,checkboxvalue8];
		const remark = [remark1,remark2,remark3,remark4,remark5,remark6,remark7,remark8];
		const doc = [doc1,doc2,doc3,doc4,doc5,doc6,doc7,doc8];
		
		if(confirm("You are about to submit this Form, Do you want to Proceed?")){
			$.ajax({
				type: "POST",
				url: "save_who_checklist.php",
				data: {
					checkbox:checkbox,
					checkboxvalue:checkboxvalue,
					remark:remark,
					doc:doc,
					Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
					consultation_ID:consultation_ID,
					Registration_ID:Registration_ID,
					Admision_ID:Admision_ID,
					can_proceed:can_proceed,
					Employee_ID:Employee_ID,
					Form_ID: 'form_1'
				},
				cache: false,
				success: function (response) {
					if(response == 200){
						alert("Form 1 was Submitted Successfully!");
						location.reload();
					}
				}
			});
		}
	}


	function save_level_2(){
		Registration_ID = '<?= $Registration_ID ?>';
		consultation_ID = '<?= $consultation_ID ?>';
		Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
		Admision_ID = '<?= $Admision_ID ?>';
		Employee_ID = "<?= $_SESSION['userinfo']['Employee_ID']?>";

		checkbox9 = $("input[name = 'checkbox9']:checked").val();
		checkbox10 = $("input[name = 'checkbox10']:checked").val();
		checkbox11 = $("input[name = 'checkbox11']:checked").val();
		checkbox12 = $("input[name = 'checkbox12']:checked").val();
		checkbox13 = $("input[name = 'checkbox13']:checked").val();
		checkbox14 = $("input[name = 'checkbox14']:checked").val();
		checkbox15 = $("input[name = 'checkbox15']:checked").val();
		checkbox16 = $("input[name = 'checkbox16']:checked").val();
		checkbox17 = $("input[name = 'checkbox17']:checked").val();
		checkbox18 = $("input[name = 'checkbox18']:checked").val();
		checkbox19 = $("input[name = 'checkbox19']:checked").val();
		checkbox20 = $("input[name = 'checkbox20']:checked").val();
		checkboxvalue9 = '<?= $checkboxvalue9 ?>';
		checkboxvalue10 = '<?= $checkboxvalue10 ?>';
		checkboxvalue11 = '<?= $checkboxvalue11 ?>';
		checkboxvalue12 = '<?= $checkboxvalue12 ?>';
		checkboxvalue13 = '<?= $checkboxvalue13 ?>';
		checkboxvalue14 = '<?= $checkboxvalue14 ?>';
		checkboxvalue15 = '<?= $checkboxvalue15 ?>';
		checkboxvalue16 = '<?= $checkboxvalue16 ?>';
		checkboxvalue17 = '<?= $checkboxvalue17 ?>';
		checkboxvalue18 = '<?= $checkboxvalue18 ?>';
		checkboxvalue19 = '<?= $checkboxvalue19 ?>';
		checkboxvalue20 = '<?= $checkboxvalue20 ?>';
		remark9 = $("#remark9").val();
		remark10 = $("#remark10").val();
		remark11 = $("#remark11").val();
		remark12 = $("#remark12").val();
		remark13 = $("#remark13").val();
		remark14 = $("#remark14").val();
		remark15 = $("#remark15").val();
		remark16 = $("#remark16").val();
		remark17 = $("#remark17").val();
		remark18 = $("#remark18").val();
		remark19 = $("#remark19").val();
		remark20 = $("#remark20").val();
		doc1 = '9';
		doc2 = '10';
		doc3 = '11';
		doc4 = '12';
		doc5 = '13';
		doc6 = '14';
		doc7 = '15';
		doc8 = '16';
		doc9 = '17';
		doc10 = '18';
		doc11 = '19';
		doc12 = '20';

		const checkbox = [checkbox9,checkbox10,checkbox11,checkbox12,checkbox13,checkbox14,checkbox15,checkbox16,checkbox17,checkbox18,checkbox19,checkbox20];
		const checkboxvalue = [checkboxvalue9,checkboxvalue10,checkboxvalue11,checkboxvalue12,checkboxvalue13,checkboxvalue14,checkboxvalue15,checkboxvalue16,checkboxvalue17,checkboxvalue18,checkboxvalue19,checkboxvalue20];
		const remark = [remark9,remark10,remark11,remark12,remark13,remark14,remark15,remark16,remark17,remark18,remark18,remark19,remark20];
		const doc = [doc1,doc2,doc3,doc4,doc5,doc6,doc7,doc8,doc9,doc10,doc11,doc12];
		
		if(confirm("You are about to submit this Form, Do you want to Proceed?")){
			$.ajax({
				type: "POST",
				url: "save_who_checklist.php",
				data: {
					checkbox:checkbox,
					checkboxvalue:checkboxvalue,
					remark:remark,
					doc:doc,
					Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
					consultation_ID:consultation_ID,
					Registration_ID:Registration_ID,
					Admision_ID:Admision_ID,
					Employee_ID:Employee_ID,
					Form_ID: 'form_2'
				},
				cache: false,
				success: function (response) {
					if(response == 200){
						alert("Form 2 was Submitted Successfully!");
						location.reload();
					}
				}
			});
		}
	}

	function save_level_3(){
		Registration_ID = '<?= $Registration_ID ?>';
		consultation_ID = '<?= $consultation_ID ?>';
		Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
		Admision_ID = '<?= $Admision_ID ?>';
		Pre_Operative_ID = '<?= $Pre_Operative_ID ?>';
		Employee_ID = "<?= $_SESSION['userinfo']['Employee_ID']?>";

		Anesthetist = $("#Anesthetist").val();
		nurse_instruments = $("#nurse_instruments").val();
		Surgeon_filled = $("#Surgeon_filled").val();

		if(Anesthetist == undefined || Anesthetist == ''){
			alert("Please Select The Anesthesist participated");
            $("#Anesthetist").css("border", "2px solid red");
            $("#Anesthetist").focus();
            exit();
		}

		if(nurse_instruments == undefined || nurse_instruments == ''){
			alert("Please Select The Nurse participated");
            $("#nurse_instruments").css("border", "2px solid red");
            $("#nurse_instruments").focus();
            exit();
		}

		if(Surgeon_filled == undefined || Surgeon_filled == ''){
			alert("Please Select The Nurse participated");
            $("#Surgeon_filled").css("border", "2px solid red");
            $("#Surgeon_filled").focus();
            exit();
		}
		
		checkbox21 = $("input[name = 'checkbox21']:checked").val();
		checkbox22 = $("input[name = 'checkbox22']:checked").val();
		checkbox23 = $("input[name = 'checkbox23']:checked").val();
		checkbox24 = $("input[name = 'checkbox24']:checked").val();
		checkbox25 = $("input[name = 'checkbox25']:checked").val();
		checkbox26 = $("input[name = 'checkbox26']:checked").val();

		checkboxvalue21 = '<?= $checkboxvalue21 ?>';
		checkboxvalue22 = '<?= $checkboxvalue22 ?>';
		checkboxvalue23 = '<?= $checkboxvalue23 ?>';
		checkboxvalue24 = '<?= $checkboxvalue24 ?>';
		checkboxvalue25 = '<?= $checkboxvalue25 ?>';
		checkboxvalue26 = '<?= $checkboxvalue26 ?>';

		remark21 = $("#remark21").val();
		remark22 = $("#remark22").val();
		remark23 = $("#remark23").val();
		remark24 = $("#remark24").val();
		remark25 = $("#remark25").val();
		remark26 = $("#remark26").val();

		doc13 = '21';
		doc14 = '22';
		doc15 = '23';
		doc16 = '24';
		doc17 = '25';
		doc18 = '26';

		const checkbox = [checkbox21,checkbox22,checkbox23,checkbox24,checkbox25,checkbox26];
		const checkboxvalue = [checkboxvalue21,checkboxvalue22,checkboxvalue23,checkboxvalue24,checkboxvalue25,checkboxvalue26];
		const remark = [remark21,remark22,remark23,remark24,remark25,remark26];
		const doc = [doc13,doc14,doc15,doc16,doc17,doc18];
		
		if(confirm("You are about to Finalize this Form, Do you want to Proceed?")){
			$.ajax({
				type: "POST",
				url: "save_who_checklist.php",
				data: {
					checkbox:checkbox,
					checkboxvalue:checkboxvalue,
					remark:remark,
					doc:doc,
					Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
					consultation_ID:consultation_ID,
					Registration_ID:Registration_ID,
					Admision_ID:Admision_ID,
					Employee_ID:Employee_ID,
					Anesthetist:Anesthetist,
					Surgeon_filled:Surgeon_filled,
					nurse_instruments:nurse_instruments,
					Form_ID: 'form_3'
				},
				cache: false,
				success: function (response) {
					if(response == 200){
						alert("The Final Form was Submitted Successfully!");
						document.location = './who_Checklist_Form.php?Pre_Operative_ID='+Pre_Operative_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID;
					}
				}
			});
		}
	}
</script>