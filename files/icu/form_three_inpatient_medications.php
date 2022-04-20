<?php

require_once('./../includes/connection.php');
$Registration_ID = $_GET['Reg_ID'];
$consultation_ID = $_GET['consultation_ID'];
$Status = $_GET['Status'];

$Statuses = $_GET['Status'];
if($Status == 'dispensed'){
    $Status = "('partial dispensed','dispensed')";
}else if($Status=='active'){
    $Status ="('$Status')";
}

$selectOpt = '';
$hidden = '';

if ($Status == 'outside' || $Status == 'others') {
    echo "<select name='Payment_Item_Cache_List_ID' onchange='Get_Last_Given_Time(this.value); testFucntion(this.value)' id='medication_name'  style='width:100%' >";
    $get_deseses = mysqli_query($conn, "SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='Pharmacy' LIMIT 10") or die(mysqli_error($conn));

    echo "<option value=''> Select Medication </option>";
    while ($row = mysqli_fetch_assoc($get_deseses)) {
        echo "<option value='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</option>";
    }
    echo "</select>";
}
else if($Status == 'Pending'){
    ?>
    <table style="width: 100%;" id="pending-medications" class="table table-striped table-bordered align-middle table-sm">
        <thead class="table-light">
        <tr>
            <th>S/NO</th>
            <th>MEDICATION</th>
            <th>DOSAGE</th>
            <th>DOSE QTY</th>
            <th>DISP QTY</th>
            <th>Receiving/ Rejection Remarks</th>
            <th style="text-align:center;">
                <label for="mark_all_receive" class="mt-1">Receive</label><br>
<!--                <input type="checkbox" name="check" id="mark_all_receive" onclick="checkUncheck()" />-->
            </th>
            <th>
                <label for="mark_all_reject" class="mt-1">Reject</label><br>
<!--                <input type="checkbox" name="check" id="mark_all_reject" onclick="checkUncheck()" />-->
            </th>
        </tr>
        </thead>
        <?php
// die("SELECT Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , ns.dispensed_qty, ns.id, ns.dose_qty, ns.Nurse_status, ns.NurseComment, Liquid_Item_Value, Statevalue, route_type	FROM tbl_item_list_cache ilc, tbl_items i, tbl_partial_dispense_history ns WHERE ns.item_cache_list_id = ilc.Payment_Item_Cache_List_ID AND   ilc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID =  '$consultation_ID' AND Registration_ID = '$Registration_ID') AND   ilc.Status IN  ('dispensed', 'partial dispensed') AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' AND ns.Nurse_status = 'Pending' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Item_Cache_List_ID   DESC");

$select_services = "SELECT Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , ns.dispensed_qty, ns.id, ns.dose_qty, ns.Nurse_status, ns.NurseComment, Liquid_Item_Value, Statevalue, route_type	FROM tbl_item_list_cache ilc, tbl_items i, tbl_partial_dispense_history ns WHERE ns.item_cache_list_id = ilc.Payment_Item_Cache_List_ID AND   ilc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID =  '$consultation_ID' AND Registration_ID = '$Registration_ID') AND   ilc.Status IN  ('dispensed', 'partial dispensed') AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' AND ns.Nurse_status = 'Pending' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Item_Cache_List_ID   DESC";


        // $select_services = "SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND   ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN ('dispensed', 'partial dispensed')  AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC     ";
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
                // $Edited_Quantity  = $items['Edited_Quantity'];
                $Statevalue = $items['Statevalue'];
                $Liquid_Item_Value = $items['Liquid_Item_Value'];
                $route_type = $items['route_type'];
                $Edited_Quantity  = $items['dispensed_qty'];
                $dose_qty = $items['dose_qty'];
                $id = $items['id'];
                $Nurse_status = $items['Nurse_status'];

                    if($dose_qty > 0){
                        // continue;
                    }else{
                        $dose_qty = 0;
                    }
                // $dose_qty='0';
                // continue;
                // $dscpensqry = mysqli_query($conn, "SELECT dose_qty,id, dispensed_qty, Nurse_status  FROM `tbl_partial_dispense_history` WHERE Item_ID = '$Item_ID' AND   patient_id = '$Registration_ID' AND Nurse_status='Pending'  AND item_cache_list_id='$Payment_Item_Cache_List_ID' ORDER BY `id` DESC LIMIT 1 ") or die(mysqli_error($conn));
                // if(mysqli_num_rows($dscpensqry)>0){
                //     while($rw = mysqli_fetch_assoc($dscpensqry)){


                //     }
                // }else{

                // }
                //select medicine last dose
                if($dosage_==""){
                    $dosage_=".";
                }
                $Quantity_remained=0;
                $Quantity_Given=0;

                $select_amountgiven =mysqli_query($conn, "SELECT SUM(Amount_Given) as Quantity_Given FROM tbl_inpatient_medicines_given    WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($select_amountgiven)>0){
                    while($rw = mysqli_fetch_assoc($select_amountgiven)){
                        $Quantity_Given = $rw['Quantity_Given'];
                    }
                }


                if($Discontinue_Status=="yes"){
                    // $style_for_discontinue="style='background:red; color:white;'";
                    // $enable_check_disc="checked='checked'";
                    // $disable_chck_opt="disabled='disabled'";
                    // $cancel_btn="<input type='button' onclick='cancel_discontinuetion($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID)'class='btn btn-primary' value='CANCEL DISCONTINUATION'>";
                    continue;
                }else{
                    $Discontinue_Status="";
                }
                
                echo "<tr>
                        <td style='text-align: center;'>$count_sn</td>
                        <td>$service_name</td>
                        <td>$dosage</td>
                        <td><input type='text' class='form-control text-center' id='Quantity_remained$Payment_Item_Cache_List_ID' $disable_chck_opt value='$dose_qty' readonly></td>
                        <td><input type='text' class='form-control text-center' id='Quantity_dispensed$Payment_Item_Cache_List_ID' $disable_chck_opt value='$Edited_Quantity' readonly></td>
                        <td><textarea rows='1' id='remarks_new$id' class='form-control'></textarea> </td></td>
                        <td><div class='checkbox_select text-center'><input type='checkbox' class='Payment_Item_Cache_List_ID'  $disable_chck_opt value='$id'></div></td>
                        <td><div class='checkbox_select text-center'><input type='checkbox' class='rejected_medications'  id='uncheckcheck$id' value='$Payment_Item_Cache_List_ID,$id'></div></td>
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
            <td colspan="5" id="feedback_message"></td>
            <td colspan="3">
                <input  type="button" class="btn btn-success rounded-0" onclick="receive_medication()" value="RECEIVE MEDICATIONS">
                <input  type="button" class="btn btn-danger rounded-0" onclick="rejectMedications()" value="REJECT MEDICATIONS">
            </td>
        </tr>
    </table>
    <?php
}
else if ($Statuses == 'active'){
    ?>
    <table class="table" class="table table-striped table-bordered align-middle table-sm">
        <tr style="font-size: 10px;">
            <td><b>S/No.</b></td>
            <td><b>MEDICATION</b></td>
            <td><b>DOSAGE</b></td>
            <td><b>DOSE QTY</b></td>
            <td><b>DISP QTY</b></td>

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
}

else if ($Statuses == 'dispensed'){
    ?>
    <table style="width: 100%;" xmlns="http://www.w3.org/1999/html" class="table table-striped table-bordered align-middle table-sm">
        <thead style="background-color: #f3f0f0;" class="table-light">
            <tr>
                <th class="text-center">SN</th>
                <th colspan="25" class="text-center">Medication</th>

            </tr>
        </thead>

        <?php


// die("SELECT DISTINCT(Product_Name), dispensed_qty,id, Nurse_status, NurseComment, i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type FROM tbl_partial_dispense_history pdh, tbl_item_list_cache ilc,	tbl_items i WHERE  ilc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID =  '$consultation_ID' AND Registration_ID = '$Registration_ID')   AND item_cache_list_id=Payment_Item_Cache_List_ID AND ilc.Status IN  $Status AND Nurse_status='Received'  ORDER BY `id` DESC");
// die("SELECT Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , ns.dispensed_qty,id, ns.Nurse_status, ns.NurseComment, Liquid_Item_Value, Statevalue, route_type	FROM tbl_item_list_cache ilc,	tbl_items i, tbl_partial_dispense_history ns WHERE ns.item_cache_list_id = ilc.Payment_Item_Cache_List_ID AND   ilc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID =  '$consultation_ID' AND Registration_ID = '$Registration_ID') AND   ilc.Status IN  $Status AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' AND ns.Nurse_status = 'Received' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Item_Cache_List_ID   DESC");

$select_services = "SELECT Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Transaction_Date_And_Time, Edited_Quantity , ns.dispensed_qty,id, ns.Nurse_status, ns.NurseComment, Liquid_Item_Value, Statevalue, route_type	FROM tbl_item_list_cache ilc,	tbl_items i, tbl_partial_dispense_history ns WHERE ns.item_cache_list_id = ilc.Payment_Item_Cache_List_ID AND   ilc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_ID =  '$consultation_ID' AND Registration_ID = '$Registration_ID') AND   ilc.Status IN  $Status AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' AND ns.Nurse_status = 'Received' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Item_Cache_List_ID   DESC";

        // $select_services = "SELECT Product_Name,i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,ipc.Round_ID,Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, route_type	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND       ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN  $Status AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC";


        $style_for_discontinue="";
        $enable_check_disc="";
        $disable_chck_opt="";
        $cancel_btn="";
        $Discontinue_Status="";
        $selected_services = mysqli_query($conn, $select_services) or die(mysqli_error($conn));
        if (mysqli_num_rows($selected_services) > 0) {

            $count_sn=1;
            $Edited_Quantity=0;
            while ($items = mysqli_fetch_assoc($selected_services)) {

                $service_name = $items ['Product_Name'];
                $Payment_Item_Cache_List_ID = $items ['Payment_Item_Cache_List_ID'];
                $dosage = $items ['Doctor_Comment'];
                $Round_ID = $items ['Round_ID'];
                $Item_ID = $items ['Item_ID'];
                $Date_Given = $items['Transaction_Date_And_Time'];
                // $Edited_Quantity  = $items['Edited_Quantity'];
                $Statevalue = $items['Statevalue'];
                $Liquid_Item_Value = $items['Liquid_Item_Value'];
                $route_type = $items['route_type'];

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
                    // if($Nurse_status == 'Rejected'){
                    //    $disablechckopt="disabled='disabled' title='This medication Was Rejected please consult pharmacy to rectfy their mistake'";
                    // }else{
                    // $disablechckopt="disabled='disabled' title='This medication Not received yet please recieve first'";
                    // }

                    continue;
                }else{
                    $disablechckopt='';
                    $Nurse_status='';
                }
                $select_amountgiven =mysqli_query($conn, "SELECT SUM(Amount_Given) as Quantity_Given FROM tbl_inpatient_medicines_given    WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($select_amountgiven)>0){
                    while($rw = mysqli_fetch_assoc($select_amountgiven)){
                        $Quantity_Given = $rw['Quantity_Given'];
                    }
                }
                if($Statevalue =='Liquid'){
                    $Quantity_remained =( ($Edited_Quantity * $Liquid_Item_Value ) - $Quantity_Given);
                    $DisplayStyle = "style='width:13; background:#247ddb;'";
                }else{
                    $Quantity_remained = $Edited_Quantity - $Quantity_Given;
                    $DisplayStyle = "style='width:13%;'";
                }

                if($Quantity_remained <= 0){
                    continue;
                }

                $select_service = "  SELECT * FROM tbl_inpatient_medicines_given WHERE Item_ID = '$Item_ID' AND  Registration_ID = '$Registration_ID' AND     consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' ORDER BY Time_Given desc LIMIT 1";
                $selected_service = mysqli_query($conn, $select_service) or die(mysqli_error($conn));
                $medication_time = "";
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
                $select_service_2 = " SELECT * FROM tbl_inpatient_medicines_given   WHERE Item_ID = '$Item_ID' AND   Registration_ID = '$Registration_ID' AND  consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' ORDER BY Given_Service_ID asc LIMIT 1   ";
                $selected_service_2 = mysqli_query($conn,$select_service) or die(mysqli_error($conn));

                if(mysqli_num_rows($selected_service_2)>0){
                    while($servc_rows=mysqli_fetch_assoc($selected_service_2)){
                        $Time_Given=$servc_rows['Time_Given'];
                        $first_time_given=$servc_rows['medication_time'];
                        $Discontinue_Status = $servc_rows ['Discontinue_Status'];
                    }
                }



                if($Discontinue_Status=="yes") {
                    // $style_for_discontinue="style='background:red; color:white;'";
                    // $enable_check_disc="checked='checked'";
                    // $disable_chck_opt="disabled='disabled'";
                    // $cancel_btn="<input type='button' onclick='cancel_discontinuetion($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID)'class='btn btn-primary' value='CANCEL DISCONTINUATION'>";
                    continue;
                } else {
                    $Discontinue_Status="";
                }

                $times = ['00', '01', '02', '03', '04', '05', '06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];


                echo "<tr style='$style_for_discontinue' class='content-medication mt' >
                        <td style='text-align: center; width: 2%;'>$count_sn</td>
                        <td style='width: 15%;'>$service_name</td>";
                foreach ($times as $time) {
                    $longTime = date_format(date_create('today')->setTime($time, 00), 'Y-m-d H:i');
                    echo "<td style='width: 3.45%;'>
                            <button type='text' data-time='$longTime'
                            data-id='$Item_ID' 
                            data-cache-id='$Payment_Item_Cache_List_ID' 
                            data-name='$service_name' 
                            data-dispensed='$Edited_Quantity' 
                            data-route='$route_type' 
                            data-dosage='$dosage' 
                            data-remaining='$Quantity_remained' class='medication-item'>". $time ."00</button>
                        </td>";
                }
                ?>
                
                <tr>
                    <td></td>
                    <td style="padding-bottom: 10px;" colspan="1" ><b>Date Ordered</b>: <br><?php echo $Date_Given; ?></td>
                    <td colspan="3"><b>First Time Given</b>: <br><?php echo $first_time_given; ?></td>
                    <td colspan="3"><b>Last Time Given</b>: <br><?= $medication_time ?></td>
                    <td colspan="3"><b>Time Lapsed</b>: <br><?= $lapse_time ? $lapse_time : 'Never given' ?></td>
                    <td colspan="2"><b>Route</b>: <br><span style='color:  #5dade2; font-weight: bold;'><?= $route_type ? $route_type : 'Not set'; ?></span></td>
                    <td colspan="3""><b>Amount Dispensed</b>: <br><?= $Edited_Quantity ?></td>
                    <td colspan="2""><b>Remaining</b>: <br><?= $Quantity_remained ?></td>
                    <td colspan="8"><b>Dosage</b>: <br><?= $dosage ?></td>

                    <!-- Removed Drip Rate -->
                    <!-- Removed Remarks -->
                    <!-- Discontinue? -->
                    <!-- Discontinue Reason -->
                    <!-- Removed Multiple Select -->
                </tr>

                <?php
                $count_sn++;

            }

        } else {
            echo "<tr><td colspan='30' style='color: red; text-align: center; padding: 10px; font-weight: bold;'>NO MEDICATION FOUND!</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <?php
}

?>
