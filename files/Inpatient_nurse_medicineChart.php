<?php

    require_once('./includes/connection.php'); 
    session_start();
    if(isset($_POST['medicationCanceled'])){
        $consultation_ID = $_POST['consultation_ID'];
        $Registration_ID = $_POST['Registration_ID'];

        $select_services = "SELECT sg.Item_ID,Payment_Item_Cache_List_ID, given_time,sg.route_type,drip_rate,From_outside_amount,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name FROM tbl_inpatient_medicines_given sg, tbl_items it, tbl_employee em WHERE em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='$consultation_ID'  AND Discontinue_Status='yes' ORDER BY sg.Time_Given DESC  ";

        // die($select_services);
    
        $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn)); 
        echo "<table width='100%' style='font-size: 10px;' class='table'>";
        echo "<thead>"
        . "<tr>";
        echo "<th width='5%'> SN </th>";
        echo "<th> Medicine Name </th>";
        echo "<th> Dose </th>";
        echo "<th> Route </th>";
        echo "<th> Amount Given </th>";
        echo "<th width='11%'>Discontinued Time</th>";
        echo "<th>Nurse Remarks </th>";
        echo "<th width='5%'> Discontinued?</th>";
        echo "<th> Discontinue Reason </th>";
        echo "<th>From outside Amount</th>";
        echo "<th>Discontinued By </th>
        <th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "</tbody>";
    
    $sn = 1;
    while ($service = mysqli_fetch_assoc($selected_services)) {
        $Item_ID = $service['Item_ID'];
        $Product_Name = $service['Product_Name'];
        $given_time = $service['given_time'];
        $route_type = $service['route_type'];
        $Time_Given = $service['Time_Given'];
        $Amount_Given = $service['Amount_Given'];
        $Nurse_Remarks = $service['Nurse_Remarks'];
        $Discontinue_Status = $service['Discontinue_Status'];
        $Discontinue_Reason = $service['Discontinue_Reason'];
        $Employee_Name = $service['Employee_Name'];
        $From_outside_amount =$service['From_outside_amount'];
        $Payment_Item_Cache_List_ID = $service['Payment_Item_Cache_List_ID'];
        echo "<tr>";
        echo "<td >" . $sn . "</td>";
        echo "<td>" . $Product_Name . "</td>";
        echo "<td>" . $given_time . "</td>";
        echo "<td>" . $route_type . "</td>";
        echo "<td style='text-align:center;'>" . $Amount_Given . "</td>";
        echo "<td>" . $Time_Given . "</td>";
        echo "<td>" . $Nurse_Remarks . "</td>";
        echo "<td>" . $Discontinue_Status . "</td>";
        echo "<td>" . $Discontinue_Reason . "</td>";
        echo "<td>" . $From_outside_amount ."</td>";
        echo "<td>" . $Employee_Name . "</td>
        <td><input type='button' onclick='cancel_discontinuetion($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID )' class='btn btn-danger btn-sm' value='CANCEL'></td>";
        echo "</tr>";
        $sn++;
    }
    
    echo "</tbody>";
    echo "</table>";
    ?>
    <br>
    <br>
    <a href="#" id="printPreview2" name=""  style='text-align: center;width:15%;float: right;' class="art-button-green" value="PREVIEW LIST" onclick="Preview_Patient_Discontinued_List(<?php echo $Registration_ID;?>,<?php echo $consultation_ID;?>)">PREVIEW</a>
    <br>
 <?php 
    }

    if(isset($_POST['CompletedMedication'])){
        $consultation_ID = $_POST['consultation_ID'];
        $Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']); 
    $select_services = "SELECT DISTINCT(Product_Name),i.Item_ID,ilc.Payment_Item_Cache_List_ID,Doctor_Comment,Dispense_Date_Time, Transaction_Date_And_Time, Edited_Quantity , Liquid_Item_Value, Statevalue, dose	FROM 	tbl_payment_cache ipc,	tbl_item_list_cache ilc,	tbl_items i	WHERE   ipc.Registration_ID = '$Registration_ID'  AND       ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND    ilc.Status IN ('dispensed') AND   ipc.consultation_ID =  '$consultation_ID' AND i.Item_ID = ilc.Item_ID  AND  ilc.Check_In_Type = 'Pharmacy' GROUP BY Product_Name, ilc.Payment_Cache_ID ORDER BY ilc.Payment_Cache_ID   DESC     ";


        $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn)); 
        echo "<table width='100%' class='table'>";
        echo "<thead>"
        . "<tr>";
        echo "<th width='5%'> SN </th>";
        echo "<th> Medicine Name </th>";
        echo "<th> Dose </th>";
        echo "<th> Despensed Date </th><th>Dose Qty</th>";
        echo "<th>Qty Dispensed </th>";
        echo "<th>Dispense Type </th> ";
        echo "</tr>";
        echo "</thead>";
        echo "</tbody>";

        $sn = 1;
        while ($service = mysqli_fetch_assoc($selected_services)) {
        $Item_ID = $service['Item_ID'];
        $Product_Name = $service['Product_Name'];
        $given_time = $service['given_time'];
        $dose = $service['dose'];
        $Doctor_Comment = $service['Doctor_Comment'];
        $Dispense_Date_Time = $service['Dispense_Date_Time'];
        $Employee_Name = $service['Employee_Name'];
        $Payment_Item_Cache_List_ID = $service['Payment_Item_Cache_List_ID'];
        $Edited_Quantity  = $service['Edited_Quantity'];
        $Statevalue = $service['Statevalue'];
        $Liquid_Item_Value = $service['Liquid_Item_Value'];
        $route_type = $service['route_type'];
        if($dose > $Edited_Quantity){
            $dispensesType ='Partial Despense';
        }else{
            $dispensesType ='Full Despensed';
        }
        $dscpensqry = mysqli_query($conn, "SELECT dispensed_qty FROM `tbl_partial_dispense_history` WHERE Item_ID = '$Item_ID' AND   patient_id = '$Registration_ID'  AND item_cache_list_id='$Payment_Item_Cache_List_ID' ORDER BY `id` DESC LIMIT 1 ") or die(mysqli_error($conn));
        while($rw = mysqli_fetch_assoc($dscpensqry)){
            $Edited_Quantity  = $rw['dispensed_qty'];
        }
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
         if($Statevalue =='Liquid'){
            $Quantity_remained =( ($Edited_Quantity * $Liquid_Item_Value ) - $Quantity_Given);
            $DisplayStyle = "style='width:13; background:#247ddb;'";
         }else{
            $Quantity_remained = $Edited_Quantity - $Quantity_Given;
            $DisplayStyle = "style='width:13%;'";
         }

         if($Quantity_remained > 0){
             continue;
         }

        echo "<tr>";
        echo "<td >" . $sn . "</td>";
        echo "<td>" . $Product_Name . "</td>";
        echo "<td>" . $Doctor_Comment . "</td>";
        echo "<td>" . $Dispense_Date_Time . "</td><td>$dose</td>";
        echo "<td>" . $Edited_Quantity ."</td>";
        echo "<td>" . $dispensesType . "</td>";
        echo "</tr>";
        $sn++;
        }

        echo "</tbody>";
        echo "</table>";

    }

    if(isset($_POST['rejectmedicationid'])){
        $remarks_new = mysqli_real_escape_string($conn, $_POST['remarks_new']);
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Payment_Item_Cache_List_ID =$_POST['Payment_Item_Cache_List_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        $updateqyt = mysqli_query($conn, "UPDATE tbl_partial_dispense_history SET NurseComment='$remarks_new', EmployeeID='$Employee_ID',Nurse_status='Rejected', item_cache_list_id='$Payment_Item_Cache_List_ID',  Consultation_ID='$Consultation_ID', Receive_reject_date=NOW() WHERE id='$id'") or die(mysqli_error($conn));
        if($updateqyt){
            echo "Updated successful";
        }else{
            echo "Failed";
        }
    }



    if(isset($_POST['receivemedicationid'])){
        $selected_mediccation =  $_POST['selected_mediccation'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        foreach($selected_mediccation as $data){
            $data_array=explode("unganisha",$data);
            $id=mysqli_real_escape_string($conn,$data_array[0]);
            $remarks_new=mysqli_real_escape_string($conn,$data_array[1]);
            $updateqyt = mysqli_query($conn, "UPDATE tbl_partial_dispense_history SET NurseComment='$remarks_new', EmployeeID='$Employee_ID',Nurse_status='Received',  Consultation_ID='$Consultation_ID', Receive_reject_date=NOW() WHERE  id='$id'") or die(mysqli_error($conn));
            if($updateqyt){
                echo "Updated successful";
            }else{
                echo "Failed";
            }
        }
        
    }

    if(isset($_POST['returndicationid'])){
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Payment_Item_Cache_List_ID =$_POST['Payment_Item_Cache_List_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        $Quantity_remained = $_POST['Quantity_remained'];

        $selected_mediccation =  $_POST['selected_mediccation'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        foreach($selected_mediccation as $data){
            $data_array=explode("unganisha",$data);
            $Payment_Item_Cache_List_ID=mysqli_real_escape_string($conn,$data_array[0]);
            $Quantity_remained=mysqli_real_escape_string($conn,$data_array[1]);
            $id = mysqli_real_escape_string($conn,$data_array[2]);
            // die("UPDATE tbl_partial_dispense_history SET Nurse_status='Return', ReturnBy='$Employee_ID', Returndate=NOW(), ReturnedQty='$Quantity_remained' WHERE id='$id'");
            $updateqyt = mysqli_query($conn, "UPDATE tbl_partial_dispense_history SET Nurse_status='Return', ReturnBy='$Employee_ID', Returndate=NOW(), ReturnedQty='$Quantity_remained' WHERE id='$id'") or die(mysqli_error($conn));
            if($updateqyt){
                echo "Returned succeful";
            }else{
                echo "Failed";
            }
        }
       
    }