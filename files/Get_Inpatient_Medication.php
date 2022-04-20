<?php

require_once('./includes/connection.php'); //
 $Registration_ID = $_GET['Reg_ID'];
 $consultation_ID = $_GET['consultation_ID'];
 
 //select last consultation_id haivuti consultation sahii
//  $sql_select_last_consultation_id_result=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
//  if(mysqli_num_rows($sql_select_last_consultation_id_result)>0){
//     $consultation_ID=mysqli_fetch_assoc($sql_select_last_consultation_id_result)['consultation_ID'];
//  }
 $Status = $_GET['Status'];
 $Statuses = $_GET['Status'];
 if($Status == 'dispensed'){
     $Status = "('partial dispensed','dispensed')";
 }else if($Status=='active'){
    $Status ="('$Status')";
 }
 //========= new mofification on  =========
 // ======= ALTER TABLE `tbl_partial_dispense_history` ADD `Nurse_status` VARCHAR(15) NOT NULL DEFAULT 'Pending' AFTER `date`, ADD `NurseComment` TEXT NOT NULL AFTER `Nurse_status`, ADD `EmployeeID` INT NULL AFTER `NurseComment`, ADD `Consultation_ID` INT NULL AFTER `EmployeeID`; 
 //ALTER TABLE `tbl_partial_dispense_history` ADD `ReturnBy` INT NULL AFTER `Receive_reject_date`, ADD `Returndate` DATETIME NOT NULL AFTER `ReturnBy`, ADD `ReturnedQty` FLOAT NOT NULL AFTER `Returndate` 

$selectOpt = '';
$hidden = '';
if ($Status == 'outside' || $Status=='others') {
    echo  "<select name='Payment_Item_Cache_List_ID' onchange='Get_Last_Given_Time(this.value);testFucntion(this.value)' id='medication_name'  style='width:100%' >";
     $get_deseses=  mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='Pharmacy' LIMIT 10") or die(mysqli_error($conn));
    
   echo   "<option value=''> Select Medication </option>";
        while ($row = mysqli_fetch_assoc($get_deseses)) {
            echo  "<option value='".$row['Item_ID']."'>".$row['Product_Name']."</option>";
        }
    echo "</select>";  
}else if($Status == 'Pending'){
    ?>
    <!-- <hr/> -->
    <table class="table">
       <tr style="font-size: 10px;">
        <td><b>S/No.</b></td>
        <td><b>MEDICATION</b></td>
        <td><b>DOSAGE</b></td>
        <td><b>DOSE QTY</b></td>
        <td><b>DISP QTY</b></td>        
        <td><b>Receiving/ Rejection Remarks</b></td>  
        
        <td style="text-align:center;"><b>
            <!-- <input type="checkbox" name="check" id="mark_all" onclick="checkUncheck()" > -->
            Select to Receive
        </b></td>

        <td><b>Reject</b></td> 
       </tr>
       <tr>
           <td colspan="7">
               <hr/>
           </td>
       </tr>
        <?php 
            
        
    
        // $select_services = "SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND   ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN ('dispensed', 'partial dispensed')  AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC";
        
        // if($Registration_ID =='72293'){
        //     die("SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND   ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN ('dispensed', 'partial dispensed')  AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC");
        // }
        
         $Nurse_status='';   
        $style_for_discontinue="";
        $enable_check_disc="";
        $disable_chck_opt="";
        $cancel_btn="";
        $Discontinue_Status="";
        $selected_services = mysqli_query($conn,"SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity ,dispensed_qty,dose_qty,id, Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i, tbl_partial_dispense_history pdh	WHERE   ipc.Registration_ID = '$Registration_ID'  AND   ipc.Payment_Cache_ID = ilc.Payment_Cache_ID AND Edited_Quantity<>0	AND    ilc.Status IN ('dispensed', 'partial dispensed')  AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' AND Nurse_status='Pending'  AND item_cache_list_id=Payment_Item_Cache_List_ID GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC") or die(mysqli_error($conn));
        if (mysqli_num_rows($selected_services) > 0) {
           
            $count_sn=1;
            $Edited_Quantity=0;
            while ($items = mysqli_fetch_assoc($selected_services)) {
    
                $service_name = $items['Product_Name'];
                $Payment_Item_Cache_List_ID = $items['Payment_Item_Cache_List_ID'];
                $dosage_ = $items['Doctor_Comment'];
                $Item_ID = $items['Item_ID'];
                $Date_Given = $items['Transaction_Date_And_Time'];
                $Edited_Quantity  = $items['Edited_Quantity'];
                $Statevalue = $items['Statevalue'];
                $Liquid_Item_Value = $items['Liquid_Item_Value'];
                $route_type = $items['route_type'];

                $dispensed_qty =$items['dispensed_qty'];
                $dose_qty = $items['dose_qty'];
                $id = $items['id'];
                
                // $dscpensqry = mysqli_query($conn, "SELECT dose_qty,id, dispensed_qty, Nurse_status  FROM `tbl_partial_dispense_history` WHERE Item_ID = '$Item_ID' AND   patient_id = '$Registration_ID' AND Nurse_status='Pending'  AND item_cache_list_id='$Payment_Item_Cache_List_ID' ORDER BY `id` DESC LIMIT 1 ") or die(mysqli_error($conn));
                // if(mysqli_num_rows($dscpensqry)>0){
                //     while($rw = mysqli_fetch_assoc($dscpensqry)){
                //         $Edited_Quantity  = $rw['dispensed_qty'];
                //         $dose_qty = $rw['dose_qty'];
                //         $id = $rw['id'];
                //         $Nurse_status = $rw['Nurse_status'];

                //     }
                // }else{
                //     $dose_qty='0';
                //    continue;
                // }
                //select medicine last dose
                if($dosage_==""){
                    $dosage_=".";
                }
                $Quantity_remained=0;
                $Quantity_Given=0;
                
                //  $select_amountgiven =mysqli_query($conn, "SELECT SUM(Amount_Given) as Quantity_Given FROM tbl_inpatient_medicines_given    WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'"); 
                                 
                //  $Quantity_Given=mysqli_fetch_assoc($select_amountgiven)['Quantity_Given'];
                  
                // if($Discontinue_Status=="yes"){
                //      continue;
                // }else{
                //     $Discontinue_Status="";
                // }
                echo "<tr  style='font-size: 10px;' >
                        <td>$count_sn</td>
                        <td>$service_name</td>
                        <td>$dosage</td>
                        <td><input type='text' class='form-control text-center' id='Quantity_remained$Payment_Item_Cache_List_ID' $disable_chck_opt value='$dose_qty' readonly></td>
                        <td><input type='text' class='form-control text-center' id='Quantity_dispensed$Payment_Item_Cache_List_ID' $disable_chck_opt value='$Edited_Quantity' readonly></td>
                        
                       
                        <td><textarea rows='1' id='remarks_new$id'></textarea> </td></td>
                        
                        <td><div class='checkbox_select'><input type='checkbox' class='Payment_Item_Cache_List_ID'  $disable_chck_opt value='$id'></div></td>
                        <td><div class='checkbox_select'><input type='checkbox' class=''  id='uncheckcheck$id' value='$Payment_Item_Cache_List_ID' onclick='rejectmedication($id, $Payment_Item_Cache_List_ID)'></div></td>
                     </tr>";

                     echo "<tr><td><input type='text'   id='Item_ID$Payment_Item_Cache_List_ID' style='display:none' value='$Item_ID'></td></tr>";
                   
                    $count_sn++;
                    ?>
                    
    
                    <?php 
            }
           
        } else {
           echo "<tr><td colspan='7'><b style='color:red'>NO MEDICATION FOUND!</b></td></tr>";
        }
        ?>
        <tr>
            <td colspan="6" id="feedback_message"></td>
            <td>
                <?php if($Status !="active"  ){
                    ?>
                <input  type="button" class="art-button-green" onclick="receive_medication()" value="RECEIVE MEDICATIONS">
                    <?php 
                }?>
            </td>
           
        </tr>
    </table>
    <?php
}else if($Statuses == 'active'){
    ?>
    <!-- <hr/> -->
    <table class="table">
       <tr style="font-size: 10px;">
        <td><b>S/No.</b></td>
        <td><b>MEDICATION</b></td>
        <td><b>DOSAGE</b></td>
        <td><b>DOSE QTY</b></td>
        <td><b>DISP QTY</b></td>        
        
       </tr>
       <tr>
           <td colspan="5">
               <hr/>
           </td>
       </tr>
        <?php 
            
        
    
        $select_services = "SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND   ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN ('active', 'approved', 'paid')  AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC     ";
         $Nurse_status='';   
        $style_for_discontinue="";
        $enable_check_disc="";
        $disable_chck_opt="";
        $cancel_btn="";
        $Discontinue_Status="";
        $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
        if (mysqli_num_rows($selected_services) > 0) {
           
            $count_sn=1;
            $Edited_Quantity=0;
            while ($items = mysqli_fetch_assoc($selected_services)) {
    
                $service_name = $items ['Product_Name'];
                $Payment_Item_Cache_List_ID = $items ['Payment_Item_Cache_List_ID'];
                $dosage = $items ['Doctor_Comment'];
                $Item_ID = $items ['Item_ID'];
                $Date_Given = $items['Transaction_Date_And_Time'];
                $Edited_Quantity  = $items['Edited_Quantity'];
                $Statevalue = $items['Statevalue'];
                $Liquid_Item_Value = $items['Liquid_Item_Value'];
                $route_type = $items['route_type'];
              
                //select medicine last dose
                if($dosage_==""){
                    $dosage_=".";
                }
               
               
                echo "<tr  style='font-size: 12px;' >
                        <td>$count_sn</td>
                        <td>$service_name</td>
                        <td>$dosage</td>
                        <td>$dose_qty</td>
                        <td>$Edited_Quantity</td>                        
                        </tr>";
                   
                    $count_sn++;
                    ?>
                    
    
                    <?php 
            }
           
        } else {
           echo "<tr><td colspan='5'><b style='color:red'>NO MEDICATION ORDER FOUND!</b></td></tr>";
        }
        ?>
        
    </table>
    <?php
}else if($Statuses=='dispensed'){
?>
<hr/>
<table class="table">
   <tr style="font-size: 10px;"> 
    <td><b>S/No.</b></td>
    <td><b>MEDICATION</b></td>
    <td><b>LAST TIME GIVEN</b></td>
    <td><b>LAPSE TIME</b></td>
    <td><b>TIME GIVEN </b></td>
    <td><b>DOSAGE</b></td>
    <td><b>DISP QTY</b></td>
    <td><b>REMAINED QTY</b></td>
    <td><b>NURSE QTY</b></td>
    <td><b>ROUTE</b ></td>
    <td><b>DRIP RATE</b ></td>
    <td><b>Remarks/Significant Events and Interventions</b></td>
    <td><b>Discontinue Service?</b></td>    
    <td><b>Reason</b></td>
    <td><b>Select<input type="checkbox" id="select_all_checkbox" class="hide" onclick="select_all_unselect_all()"></b></td>
    
</tr>
   <tr>
       <td colspan="15">
           <hr/>
       </td>
   </tr>
    <?php 

    // $select_services = "SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND       ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN  $Status AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC     ";
        
    $style_for_discontinue="";
    $enable_check_disc="";
    $disable_chck_opt="";
    $cancel_btn="";
    $Discontinue_Status="";
    // $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
    $receivedDispensed = mysqli_query($conn, "SELECT DISTINCT(Product_Name), dispensed_qty,id, Nurse_status, NurseComment, i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type FROM tbl_partial_dispense_history pdh, tbl_item_list_cache ilc,	tbl_items i WHERE pdh.Item_ID = i.Item_ID AND   patient_id = '$Registration_ID' AND Consultation_ID =  '$consultation_ID'  AND item_cache_list_id=Payment_Item_Cache_List_ID AND ilc.Status IN  $Status AND Nurse_status='Received'  ORDER BY `id` DESC") or die(mysqli_error($conn)); 

    if (mysqli_num_rows($receivedDispensed) > 0) {
       
        $count_sn=1;
        $Edited_Quantity=0;
        while ($items = mysqli_fetch_assoc($receivedDispensed)) {

            $service_name = $items['Product_Name'];
            $Payment_Item_Cache_List_ID = $items['Payment_Item_Cache_List_ID'];
            $dosage = $items['Doctor_Comment'];
            $Round_ID = $items['Round_ID'];
            $Item_ID = $items['Item_ID'];
            $Date_Given = $items['Transaction_Date_And_Time'];
            $Edited_Quantity  = $items['Edited_Quantity'];
            $Statevalue = $items['Statevalue'];
            $Liquid_Item_Value = $items['Liquid_Item_Value'];
            $route_type = $items['route_type'];

            $id=$items['id'];
            $Edited_Quantity  = $items['dispensed_qty'];
            $Nurse_status = $items['Nurse_status'];
            $NurseComment = $items['NurseComment'];

			
            // $dscpensqry = mysqli_query($conn, "SELECT dispensed_qty,id, Nurse_status, NurseComment FROM `tbl_partial_dispense_history` WHERE Item_ID = '$Item_ID' AND   patient_id = '$Registration_ID'  AND item_cache_list_id='$Payment_Item_Cache_List_ID' ORDER BY `id` DESC  ") or die(mysqli_error($conn));
            // while($rw = mysqli_fetch_assoc($dscpensqry)){
            //     $id=$rw['id'];
            //     $Edited_Quantity  = $rw['dispensed_qty'];
            //     $Nurse_status = $rw['Nurse_status'];
            //     $NurseComment = $rw['NurseComment']; 
            // }
            //select medicine last dose
            if($dosage_==""){
                $dosage_=".";
            }

            $Quantity_remained=0;
            $Quantity_Given=0;
            if($Nurse_status !='Received'){
               continue;
            }else{
                $disablechckopt='';
                $Nurse_status='';
            }
             $select_amountgiven =mysqli_query($conn, "SELECT SUM(Amount_Given) as Quantity_Given FROM tbl_inpatient_medicines_given    WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
            //  if(mysqli_num_rows($select_amountgiven)>0){
            //      while($rw = mysqli_fetch_assoc($select_amountgiven)){
            //          $Quantity_Given = $rw['Quantity_Given'];
            //      }
            //  }
             $Quantity_Given = mysqli_fetch_assoc($select_amountgiven)['Quantity_Given'];
             if($Statevalue =='Liquid'){
                $Quantity_remained =( ($Edited_Quantity * $Liquid_Item_Value ) - $Quantity_Given);
                $DisplayStyle = " style='width:13; background:#247ddb;' ";
             }else{
                $Quantity_remained = $Edited_Quantity - $Quantity_Given;
                $DisplayStyle = "style='width:13%;'";
             }
             
            if($Quantity_remained <= 0){
                 continue;
            
            }
             

             $select_service = "  SELECT * FROM tbl_inpatient_medicines_given   WHERE Item_ID = '$Item_ID' AND  Registration_ID = '$Registration_ID' AND     consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' ORDER BY Time_Given desc LIMIT 1";
             $selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
             $medication_time="";
             $lapse_time="";
             if(mysqli_num_rows($selected_service)>0){
                while($servc_rows=mysqli_fetch_assoc($selected_service)){
                    $Time_Given=$servc_rows['Time_Given'];
                    $medication_time=$servc_rows['medication_time'];
                    $Discontinue_Status = $servc_rows ['Discontinue_Status'];
                    $Today_Date = mysqli_query($conn,"select now() as today");
                    while($row = mysqli_fetch_array($Today_Date)){
                        $original_Date = $row['today'];
                        $new_Date = date("Y-m-d", strtotime($original_Date));
                        $Today = $new_Date;
                    }
                //end
                 
                $date1 = new DateTime($Today);
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($medication_time);
                    $diff = $date1 -> diff($date2);
                    $lapse_time = $diff->d." D, ";
                    $lapse_time .= $diff->h." Hrs, ";
                    $lapse_time .= $diff->i." Min";
                }
             }else{
                $medication_time="Never Given";
             }
 
             //to select first time given
             $select_service_2 = " SELECT * FROM tbl_inpatient_medicines_given   WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' ORDER BY Time_Given asc LIMIT 1   ";
             $selected_service_2 = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
             
             if(mysqli_num_rows($selected_service_2)>0){
                 while($servc_rows=mysqli_fetch_assoc($selected_service_2)){
                    $Time_Given=$servc_rows['Time_Given'];
                    $first_time_given=$servc_rows['medication_time'];
                     $Discontinue_Status = $servc_rows ['Discontinue_Status'];
                 } 
             }
             
            
            
            if($Discontinue_Status=="yes"){
                continue;
            }else{
                $Discontinue_Status="";
            }
            echo "<tr  style='font-size: 10px;' >
                    <td>$count_sn</td>
                    <td>$service_name</td>
                    <td>$medication_time</td>
                    <td style='width:8%'><label>$lapse_time</label></td>    
                    <td><input type='text' class='form-control text-center date_n_time' id='medication_time_new$Payment_Item_Cache_List_ID' style='background:#FFFFFF' readonly placeholder='Medication Time'></td>
                    <td><input type='text' id='dosage_new$Payment_Item_Cache_List_ID' value='$dosage_' style='display:none'><span style='color:red'>$dosage</span><b> => $dosage_</b></td>
                    <td><input type='text' class='form-control text-center' id='Quantity_dispensed$Payment_Item_Cache_List_ID' $disable_chck_opt value='$Edited_Quantity' readonly></td>
                    <input type='text'  class='hide' id='Quantity_remainedID$Payment_Item_Cache_List_ID'  value='$id' >
                    <td><input type='text' class='form-control text-center' id='Quantity_remained$Payment_Item_Cache_List_ID'  value='$Quantity_remained' readonly></td>
                    <td><input type='text' $disablechckopt class='form-control text-center' id='amount_given_new$Payment_Item_Cache_List_ID'  ></td>
                    <td  $DisplayStyle>
                        <div id='route_td_$Payment_Item_Cache_List_ID'>
                        <select name='route_type' id='route_type_new$Payment_Item_Cache_List_ID' >
                            <option value='$route_type'>$route_type</option>   
                            <option value = 'Oral'>Oral</option>
                            <option value = 'Sublingual'>Sublingual</option>
                            <option value = 'Rectal'>Rectal</option>
                            <option value = 'Avaginal'>Avaginal</option>
                            <option value = 'Ocular'>Ocular</option>
                            <option value = 'Otic'>Otic</option>
                            <option value = 'Nasal'>Nasal</option>
                            <option value = 'Inhalation'>Inhalation</option>
                            <option value = 'Nebulazation'>Nebulazation</option>
                            <option value = 'Very_rarely_transdermal'>Very rarely transdermal</option>
                            <option value = 'Cutaneous'>Cutaneous</option>
                            <option value = 'Intramuscular'>Intramuscular</option>
                            <option value = 'Intravenous'>Intravenous</option>                         

                        </select>
                        </div>
                    </td>
                    <td><input type='text' class='form-control text-center' id='drip_rate_new$Payment_Item_Cache_List_ID'></td>
                    <td><textarea rows='1' id='remarks_new$Payment_Item_Cache_List_ID'></textarea></td></td>
                    <td><input type='checkbox' class='' $enable_check_disc id='discontinue_$Payment_Item_Cache_List_ID'> $cancel_btn</td>
                    <td><input type='text' class='form-control' id='discontinue_reason_new$Payment_Item_Cache_List_ID'placeholder='Discontinue Reason'></td>
                    <td><div class='checkbox_select'><input type='checkbox' class='Payment_Item_Cache_List_ID checkbox'  $disablechckopt value='$Payment_Item_Cache_List_ID'></div></td>
                    
                 </tr>";
                 echo "<tr><td><input type='text'   id='Item_ID$Payment_Item_Cache_List_ID' style='display:none' value='$Item_ID'></td></tr>";
               
                $count_sn++;
                ?>
                
                <tr style='font-size: 10px;'>
                    <td></td>
                    <td colspan="2" style="text-align: right"><b>DATE ORDERED</b> <span style="color:green;">---></span> </td><td colspan="2" style="text-align: left"><?php echo $Date_Given; ?></td></td>
                    <td colspan="2" style="text-align: right"><b>FIRST TIME GIVEN</b><span style="color:green;">---></span></td><td colspan="2" style="text-align: left"><?php echo $first_time_given; ?></td>
                    <td colspan="5"><b>Other Comments:</b> <?php echo $NurseComment; ?></td>
                </tr>
                    <tr><td colspan="15"><hr/></td></tr>

                <?php 
        }
       
    } else {
       echo "<tr><td colspan='15'><b style='color:red'>NO MEDICATION FOUND!</b></td></tr>";
    }
    ?>
    <tr>
        <td colspan="2">
        <?php if($Status !="active" && $Nurse_status='Received'){
                ?>
            <input type='button' class='btn btn-danger btn-sm' onclick='returnMedication()' value='RETURN MEDICATION'>
        <?php } ?>
        </td>
        <td colspan="12" id="feedback_message"></td>
        <td colspan="">
            <?php if($Status !="active" && $Nurse_status='Received'){
                ?>
            <a href="#" class="art-button-green" onclick="save_medication()">SAVE</a>
                <?php 
            }?>
        </td>
       
    </tr>
</table>
<?php
}

?>

<script>
    
</script>
