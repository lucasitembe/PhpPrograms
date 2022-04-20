<table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
    <thead style="font-weight:bold;background-color:#006400;color:white">
        <tr> 
        <td><b>S/No.</b></td>
         <td><b>MEDICATION</b></td>
         <td><b>LAST TIME GIVEN</b></td>
         <td><b>LAPSE TIME</b></td>
         <td><b>TIME GIVEN </b></td>
         <td><b>DOSAGE</b></td>
         <td><b>QUANTITY</b></td>
         <td><b>ROUTE</b ></td>
         <td><b>DRIP RATE</b ></td>
         <td><b>Remarks/Significant Events and Interventions</b></td>
         <td><b>Discontinue Service?</b></td>    
         <td><b>Reason</b></td>
         <td><b>Select<input type="checkbox" id="select_all_checkbox" class="hide" onclick="select_all_unselect_all()"></b></td>
        </tr>
       </thead>
       <tbody>
       <?php 
        
    // $select_services = "SELECT ilc.Status,Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time 
	// 	FROM 
	// 		tbl_payment_cache ipc,
	// 		tbl_item_list_cache ilc,
	// 		tbl_items i WHERE ipc.Registration_ID = '$Registration_ID' AND
    //                         ipc.Billing_Type IN ('Inpatient Cash','Inpatient Credit','Outpatient Cash','Outpatient Credit') AND    
    //                         ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND 
    //                         ipc.consultation_ID =  '$consultation_id' AND
    //                         i.Item_ID = ilc.Item_ID AND
    //                         ilc.Check_In_Type = 'Pharmacy' GROUP BY i.Item_ID
    //                          ORDER BY ipc.Payment_Cache_ID DESC
                        // ";
    $Today1=date("Y-m-d");

   
    $select_services = "SELECT ilc.Status,i.Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time FROM tbl_payment_cache ipc,tbl_item_list_cache ilc,tbl_items i WHERE ipc.Registration_ID = '$Registration_ID' AND  date(ilc.Dispense_Date_Time) = '$Today1'  AND ipc.Payment_Cache_ID = ilc.Payment_Cache_ID AND i.Item_ID = ilc.Item_ID AND ilc.Check_In_Type = 'Pharmacy' AND (ilc.Status = 'dispensed' OR ilc.Status = 'partial dispensed')  GROUP BY i.Item_ID ORDER BY ipc.Payment_Cache_ID DESC";
    // die($select_services );
    $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
    if (mysqli_num_rows($selected_services) > 0) {
        //$selectOpt .= "<option value=''> Select Medication </option>";
        
        $count_sn=1;
        while ($items = mysqli_fetch_assoc($selected_services)) {

            $service_name = $items ['Product_Name'];
            $Payment_Item_Cache_List_ID = $items ['Payment_Item_Cache_List_ID'];
            $dosage = $items ['Doctor_Comment'];
            $Round_ID = $items ['Round_ID'];
            $Item_ID = $items ['Item_ID'];
            $Status = $items ['Status'];
            $Date_Given = $items['Transaction_Date_And_Time'];
			
            //select medicine last dose
             $sql_select_last_medication_dose_result=mysqli_query($conn,"SELECT Doctor_Comment FROM tbl_payment_cache pc,tbl_item_list_cache ilc WHERE pc.Registration_ID = '$Registration_ID' AND
                            pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit','Outpatient Cash','Outpatient Credit') AND    
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND  
                            pc.consultation_ID =  '$consultation_id' AND
                            ilc.Check_In_Type = 'Pharmacy' AND 
                            Item_ID='$Item_ID'
                             ORDER BY Payment_Item_Cache_List_ID DESC LIMIT 1") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_last_medication_dose_result)>0){
                $dosage_=mysqli_fetch_assoc($sql_select_last_medication_dose_result)['Doctor_Comment'];
				
			}
			if($dosage_==$dosage){
				$dosage="";
			}
            if($dosage==""){
                $dosage=".";
                //$dosage_=".";
            }
			if($dosage_==""){
               // $dosage=".";
                $dosage_=".";
            }
            
           

            // $select_service = "SELECT * FROM tbl_inpatient_medicines_given 
            //     WHERE Item_ID = '$Item_ID' AND Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_id'  ORDER BY Time_Given desc LIMIT 1";

            $select_service = "SELECT * FROM tbl_inpatient_medicines_given 
            WHERE Item_ID = '$Item_ID' AND Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_id'  ORDER BY Time_Given desc LIMIT 1";

            $selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
            $medication_time="";
            if(mysqli_num_rows($selected_service)>0){
                while($servc_rows=mysqli_fetch_assoc($selected_service)){
                   $Time_Given=$servc_rows['Time_Given'];
                   $medication_time=$servc_rows['medication_time'];
                    $Discontinue_Status = $servc_rows ['Discontinue_Status'];
                }
            }

            //to select first time given
            $select_service_2 = "
            SELECT * FROM tbl_inpatient_medicines_given 
                WHERE Item_ID = '$Item_ID' AND 
                              Registration_ID = '$Registration_ID' AND
                              consultation_ID = '$consultation_id' 
                                 ORDER BY Given_Service_ID asc LIMIT 1
                        ";
            $selected_service_2 = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
            $medication_time="";
            if(mysqli_num_rows($selected_service_2)>0){
                while($servc_rows=mysqli_fetch_assoc($selected_service_2)){
                   $Time_Given=$servc_rows['Time_Given'];
                   $first_time_given=$servc_rows['medication_time'];
                    $Discontinue_Status = $servc_rows ['Discontinue_Status'];
                } 
            }
            
            $lapse_time="";
            if($medication_time!=""){
               //today function
                $Today_Date = mysqli_query($conn,"select now() as today");
                while($row = mysqli_fetch_array($Today_Date)){
                    $original_Date = $row['today'];
                    $new_Date = date("Y-m-d", strtotime($original_Date));
                    $Today = $new_Date;
                }
                //end
                 
                $date1 = new DateTime($Today);
		$date2 = new DateTime($medication_time);
		$diff = $date1 -> diff($date2);
		$lapse_time = $diff->d." D, ";
		$lapse_time .= $diff->h." Hrs, ";
		$lapse_time .= $diff->i." Min";
            }else{
                $medication_time="Never Given";
            }
            $style_for_discontinue="";
            $enable_check_disc="";
            $disable_chck_opt="";
            $cancel_btn="";
            if($Discontinue_Status=="yes"){
                $style_for_discontinue="style='background:red'";
                $enable_check_disc="checked='checked'";
                $disable_chck_opt="disabled='disabled'";
                $cancel_btn="<input type='button' onclick='cancel_discontinuetion($Item_ID,$Registration_ID)'class='btn btn-primary' value='CANCEL DISCONTINUATION'>";
            }
            
            if($Status != "active"){
            echo "<tr $style_for_discontinue>
                    <td>$count_sn</td>
                    <td>$service_name</td>
                    <td>$medication_time</td>
                    <td style='width:8%'><label>$lapse_time</label></td>    
                    <td><input type='text' class='form-control text-center date_n_time' id='medication_time_new$Payment_Item_Cache_List_ID' style='background:#FFFFFF' readonly placeholder='Medication Time' value='$startoriginal'></td>
                    <td><input type='text' id='dosage_new$Payment_Item_Cache_List_ID' value='$dosage_' style='display:none'><span style='color:red'>$dosage</span><b> => $dosage_</b></td>
                    <td><input type='text' class='form-control text-center' id='amount_given_new$Payment_Item_Cache_List_ID'></td>
                    <td style='width:13%' >
                        <div id='route_td_$Payment_Item_Cache_List_ID'><select name='route_type' id='route_type_new$Payment_Item_Cache_List_ID'>
                            <option></option>
                            <option value = 'Injection'>Injection</option>
                            <option value = 'Oral'>Oral</option>
                            <option value = 'Sublingual'>Sublingual</option>
                            <option value = 'Rectal'>Rectal</option>
                            <option value = 'Avaginal'>Avaginal</option>
                            <option value = 'Obular'>Obular</option>
                            <option value = 'Otic'>Otic</option>
                            <option value = 'Nasal'>Nasal</option>
                            <option value = 'Inhalation'>Inhalation</option>
                            <option value = 'Nebulazation'>Nebulazation</option>
                            <option value = 'Very_rarely_transdermal'>Very rarely transdermal</option>
                            <option value = 'Cutaneous'>Cutaneous</option>
                            <option value = 'Intramuscular'>Intramuscular</option>
                            <option value = 'Intravenous'>Intravenous</option>

                        </select></div>
                    </td>
                    <td><input type='text' class='form-control text-center' id='drip_rate_new$Payment_Item_Cache_List_ID'></td>
                    <td><textarea id='remarks_new$Payment_Item_Cache_List_ID'></textarea></td></td>
                    <td><input type='checkbox' class='' $enable_check_disc id='discontinue_$Payment_Item_Cache_List_ID'> $cancel_btn</td>
                    <td><input type='text' class='form-control' id='discontinue_reason_new$Payment_Item_Cache_List_ID'placeholder='Discontinue Reason'></td>
                    <td><div class='checkbox_select'><input type='checkbox' class='Payment_Item_Cache_List_ID checkbox'  value='$Payment_Item_Cache_List_ID'></div></td>
                 </tr>";
                 echo "<tr><td><input type='text'   id='Item_ID$Payment_Item_Cache_List_ID' style='display:none' value='$Item_ID'></td></tr>";
            }else{
                echo "<tr $style_for_discontinue>
                    <td>$count_sn</td>
                    <td>$service_name</td>
                    <td>$medication_time</td>
                    <td style='width:8%'><label>$lapse_time</label></td>    
                    <td><input type='text' class='form-control text-center date_n_time' id='medication_time_new$Payment_Item_Cache_List_ID' style='background:#FFFFFF' readonly placeholder='Medication Time'></td>
                    <td><input type='text' id='dosage_new$Payment_Item_Cache_List_ID' value='$dosage_' style='display:none'><span style='color:red'>$dosage</span><b> => $dosage_</b></td>
                    <td><input type='text' class='form-control text-center' id='amount_given_new$Payment_Item_Cache_List_ID'></td>
                    <td style='width:13%' >
                        <div id='route_td_$Payment_Item_Cache_List_ID'><select name='route_type' id='route_type_new$Payment_Item_Cache_List_ID'>
                            <option></option>
                            <option value = 'Injection'>Injection</option>
                            <option value = 'Oral'>Oral</option>
                            <option value = 'Sublingual'>Sublingual</option>
                            <option value = 'Rectal'>Rectal</option>
                            <option value = 'Avaginal'>Avaginal</option>
                            <option value = 'Obular'>Obular</option>
                            <option value = 'Otic'>Otic</option>
                            <option value = 'Nasal'>Nasal</option>
                            <option value = 'Inhalation'>Inhalation</option>
                            <option value = 'Nebulazation'>Nebulazation</option>
                            <option value = 'Very_rarely_transdermal'>Very rarely transdermal</option>
                            <option value = 'Cutaneous'>Cutaneous</option>
                            <option value = 'Intramuscular'>Intramuscular</option>
                            <option value = 'Intravenous'>Intravenous</option>

                        </select></div>
                    </td>
                    <td><input type='text' class='form-control text-center' id='drip_rate_new$Payment_Item_Cache_List_ID'></td>
                    <td><textarea id='remarks_new$Payment_Item_Cache_List_ID'></textarea></td></td>
                    <td>Not Dispensed</td>
                    <td><input type='text' class='form-control' id='discontinue_reason_new$Payment_Item_Cache_List_ID'placeholder='Discontinue Reason'></td>
                    <td style'color:red'>Not Dispensed</td>
                 </tr>";
            }
               
                $count_sn++;
                ?>
                
                <tr>
                    <td></td>
                    <td colspan="2" style="text-align: right"><b>DATE ORDERED</b> <span style="color:green;">---></span> </td><td colspan="2" style="text-align: left"><?php echo $Date_Given; ?></td></td>
                    <td colspan="2" style="text-align: right"><b>FIRST TIME GIVEN</b><span style="color:green;">---></span></td><td colspan="2" style="text-align: left"><?php echo $first_time_given; ?></td>
                </tr>
                    <tr><td colspan="13"><hr/></td></tr>

                <?php 
        }
        ?>
       <tr>
        <td colspan="11" id="feedback_message"></td>
        <td>
            <?php if($Status !="active"){
                ?>
            <a href="#" class="art-button-green" onclick="save_medication()">SAVE</a>
                <?php 
            }?>
        </td>
    </tr>
    <?php
    } else {
       echo "<tr><td colspan='13' style='text-align:center;'><b style='color:red'>NO MEDICATION FOUND!</b></td></tr>";
    }
    ?>
       </tbody>
</table>
<?php

//	$Consultation_ID= mysqli_real_escape_string($conn,$_GET['consultation_ID']);

	// $select_services = "SELECT given_time,route_type,drip_rate,From_outside_amount,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name "
    //             . "FROM tbl_inpatient_medicines_given sg, tbl_items it, tbl_employee em WHERE em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='$consultation_id' ORDER BY sg.Time_Given DESC  ";

    $select_services = "SELECT given_time,sg.route_type,drip_rate,From_outside_amount,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name "
                . "FROM tbl_inpatient_medicines_given sg, tbl_items it, tbl_employee em WHERE em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND date(sg.Time_Given)='$Today1' ORDER BY sg.Time_Given DESC  ";
 

	$selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn)); 
	echo "<table width='100%' border='1'>";
	echo "<thead style='font-weight:bold;background-color:#006400;color:white'>"
	  . "<tr>";
echo "<th width='5%'> SN </th>";
echo "<th> Medicine Name </th>";
echo "<th> Dose </th>";
echo "<th> Route </th>";
echo "<th> Amount Given </th>";
echo "<th width='11%'> Given time</th>";
// echo "<th width='11%'> Time Given</th>";
echo "<th>Nurse/Significant Events and Interventions </th>";
echo "<th width='5%'> Discontinued?</th>";
echo "<th > From Outside Amount</th>";
echo "<th> Given by </th>";
echo "</tr>";
echo "</thead>";

$sn = 1;
if(mysqli_num_rows($selected_services) > 0){
    while ($service = mysqli_fetch_assoc($selected_services)) {
        $Product_Name = $service['Product_Name'];
    $given_time = $service['given_time'];
    $route_type = $service['route_type'];
    $Time_Given = $service['Time_Given'];
    $medication_time = $service['medication_time'];
    
    $Amount_Given = $service['Amount_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $From_outside_amount = $service['From_outside_amount'];
    $Employee_Name = $service['Employee_Name'];
    echo "<tr>";
    echo "<td id='thead'>" . $sn . "</td>";
    echo "<td>" . $Product_Name . "</td>";
    echo "<td>" . $given_time. "</td>";
    echo "<td>" . $route_type . "</td>";
    echo "<td>" . $Amount_Given . "</td>";
    echo "<td>" . $Time_Given . "</td>";
    // echo "<td>" . $medication_time . "</td>";
    
    echo "<td>" . $Nurse_Remarks . "</td>";
    echo "<td>" . $Discontinue_Status . "</td>";
    echo "<td>" . $From_outside_amount ."</td>";
    echo "<td>" . $Employee_Name . "</td>";
    echo "</tr>";
    $sn++;
    }
}else{
    echo "<tr><td colspan='11' style='text-align:center;color:red'>No Records</td></tr>";
}
echo "</tbody>";
echo "</table>";
?>
