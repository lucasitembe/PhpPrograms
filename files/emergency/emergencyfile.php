<label style="text-align: left;">A: Vitals</label>
<table style="width: 100%;">
    <?php
    
    $index = 0;
    $out = '';
    $in = '';
    $query = mysqli_query($conn, "SELECT Nurse_DateTime FROM tbl_nurse WHERE Registration_ID = '$Registration_ID' GROUP BY Nurse_DateTime") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($query)) {
        $columndata = '';
        $rowdata = '';
        $vdate = $row['Nurse_DateTime'];
        $sql_select_item_sub_category_result = mysqli_query($conn, "SELECT tbl_vital.Vital,tbl_nurse_vital.Vital_Value,tbl_nurse.Nurse_DateTime,tbl_nurse.bmi FROM `tbl_vital`,tbl_nurse_vital,tbl_nurse
        WHERE tbl_vital.Vital_ID = tbl_nurse_vital.Vital_ID AND tbl_nurse_vital.Nurse_ID = tbl_nurse.Nurse_ID AND tbl_nurse.Registration_ID = '$Registration_ID' AND tbl_nurse.Nurse_DateTime = '$vdate'") or die(mysqli_error($conn));
        if (mysqli_num_rows($sql_select_item_sub_category_result) > 0) {
            while ($rows = mysqli_fetch_assoc($sql_select_item_sub_category_result)) {
                $vitalName = $rows['Vital'];
                $vitalValue = $rows['Vital_Value'];
                $columndata .= '<td>' . $vitalName . '</td>';
                $rowdata .= '<td>' . $vitalValue . '</td>';
            }
            $out = '<tr style="background: #f5f5f5;"><td>S/N</td>' . $columndata . '<td>Date and Time</td></tr>';
            $in .= '<tr><td>' . ++$index . '</td>' . $rowdata . '<td>' . $vdate . '</td></tr>';
        }
    }
    echo $out;
    ?>
    <tbody>
        <?php echo $in; ?>
    </tbody>
</table>
<br/>
<label style="text-align: left;">B: Complain</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/N</b></td>
        <td style="width:15%"><b>Doctor`s Name</b></td>
        <td style="width:60%"><b>Complain</b></td>
        <td style="width:10%"><b>Date and Time</b></td>
    </tr>
    <tbody>
        <?php
        $index = 0;
        $query = mysqli_query($conn, "select te.Employee_Name, tc.maincomplain, tc.cons_hist_Date from tbl_consultation_history tc, tbl_employee te where tc.employee_ID = te.Employee_ID AND tc.consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
        if (mysqli_num_rows($query) > 0) {
            while ($rows = mysqli_fetch_assoc($query)) {
                $Employee_Name = $rows['Employee_Name'];
                $maincomplain = $rows['maincomplain'];
                $Consultation_Date_And_Time = $rows['cons_hist_Date'];
                echo '<tr><td>' . ++$index . '</td><td>' . $Employee_Name . '</td><td>' . $maincomplain . '</td><td>' . $Consultation_Date_And_Time . '</td></tr>';
            }
        }
        ?>
    </tbody>
</table>
<br/>
<label style="text-align: left;">C: Diagnosis</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/N</b></td>
        <td style="width:20%"><b>Doctor`s Name</b></td>
        <td style="width:20%"><b>Diagnosis Type</b></td>
        <td style="width:20%"><b>Diagnosis Name</b></td>
        <td style="width:20%"><b>Diagnosis Code</b></td>
        <td style="width:15%"><b>Date and Time</b></td>
    </tr>
    <tbody>
        <?php
        $results = mysqli_query($conn, "SELECT DISTINCT(dc.employee_ID),e.Employee_Name,e.Employee_Type FROM tbl_disease_consultation dc, tbl_employee e WHERE e.Employee_ID = dc.Employee_ID AND dc.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
        $sn = 1;
        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_array($results)) {

                //selecting diagnosois
                $doctorsName = $row['Employee_Name'];
                $employee_Type = ucfirst(strtolower($row['Employee_Type']));


                $diagnosis_qr = "SELECT diagnosis_type,disease_name,Disease_Consultation_Date_And_Time,disease_code FROM tbl_disease_consultation dc,tbl_disease d
		    WHERE dc.consultation_ID =$consultation_ID AND dc.employee_ID='" . $row['employee_ID'] . "' AND 
		    dc.disease_ID = d.disease_ID";
                $result = mysqli_query($conn, $diagnosis_qr) or die(mysqli_error($conn));
                $provisional_diagnosis = '';
                $diferential_diagnosis = '';
                $diagnosis = '';



                if (@mysqli_num_rows($result) > 0) {
                    $temp = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo'
                            <tr><td>' . $temp++ . '</td><td>' . $doctorsName . '</td>
                            ';
                        if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                            $disease_code = $row['disease_code'];
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
                            $provisional_diagnosis .= $row['disease_name'] . " (" . $disease_code . ") ~ " . $dignosis_Date . "<br/>";
                            $diferential_diagnosis = '';
                            $diagnosis = '';
                            echo '<td>Provisional diagnosis</td>';
                            echo '<td>' . $row['disease_name'] . '</td>';
                            echo '<td>' . $disease_code . '</td>';
                            echo '<td>' . $dignosis_Date . '</td>';
                        }
                        if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                            $diferential_diagnosis .= ' ,' . $row['disease_name'];
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
                            $disease_code = $row['disease_code'];
                            $provisional_diagnosis = '';
                            echo '<td>Diferential diagnosis</td>';
                            echo '<td>' . $row['disease_name'] . '</td>';
                            echo '<td>' . $disease_code . '</td>';
                            echo '<td>' . $dignosis_Date . '</td>';
                        }
                        if ($row['diagnosis_type'] == 'diagnosis') {
                            $diagnosis .= ' ,' . $row['disease_name'];
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
                            $disease_code = $row['disease_code'];
                            echo '<td>Final diagnosis</td>';
                            echo '<td>' . $row['disease_name'] . '</td>';
                            echo '<td>' . $disease_code . '</td>';
                            echo '<td>' . $dignosis_Date . '</td>';
                        }
                        echo '</tr>';
                    }
                }
            }
        }
        ?>
    </tbody>
</table>
<br/>
<label style="text-align: left;">D: INVESTIGATION </label><br/>
<label style="text-align: left;"> RADIOLOGY</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/N</b></td>
        <td style="width:20%;"><b>Ordered By</b></td>
        <td style="width:15%;"><b>Ordered Date</b></td>
        <td style="width:10%;"><b>Doctor Comment</b></td>
        <td style="width:10%;"><b>Status</b></td>
        <td style="width:15%;"><b>Investigation Type</b></td>
        <td style="width:20%;"><b>Investigation Name</b></td>
        <td style="width:15%;"><b>Report</b></td>
    </tr>
    <tbody>
        <?php
        $index = 0;
        $qrLab = "SELECT ep.Employee_Name,it.Product_Name, itl.Check_In_Type,
    itl.Status,
    itl.Service_Date_And_Time,itl.Item_ID,itl.Payment_Item_Cache_List_ID,itl.Doctor_Comment from tbl_payment_cache pc, tbl_item_list_cache itl,tbl_items it, tbl_employee ep where itl.Item_ID = it.Item_ID AND itl.Consultant_ID = ep.Employee_ID AND itl.Payment_Cache_ID = pc.Payment_Cache_ID AND pc.consultation_id = '$consultation_ID' and itl.Check_In_Type in ( 'Radiology')";
        $result = mysqli_query($conn, $qrLab) or die(mysqli_error($conn));
        while ($rows = mysqli_fetch_assoc($result)) {
            $Employee_Name = $rows['Employee_Name'];
            $Created_Date_Time = $rows['Service_Date_And_Time'];
            $Status = $rows['Status'];
            $Check_In_Type = $rows['Check_In_Type'];
            $Product_Name = $rows['Product_Name'];
            $Doctor_Comment = $rows['Doctor_Comment'];
            $Item_ID = $rows['Item_ID'];
            if ($Status == "served") {
                $Status = '<span style="color:blue;text-align:center;">Done</span>';
                $comm = "<div class='col-md-4'><a class='no_color' href='RadiologyTests_Print.php?RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $rows['Payment_Item_Cache_List_ID'] . "' title='Click to view comment added by radiologist' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a></div>";
            } else {
                $Status = '<span style="color:red;text-align:center;">Not Done</span>';
                $comm='';
            }
            echo '<tr><td>' . ++$index . '</td><td>' . $Employee_Name . '</td><td>' . $Created_Date_Time . '</td><td>' . $Doctor_Comment . '</td><td>' . $Status . '</td><td>' . $Check_In_Type . '</td><td>' . $Product_Name . '</td><td>' . $comm . '</td><td></td></tr>';
        }
        ?>
    </tbody>
</table>
<br/>

<label style="text-align: left;"> LABORATORY</label>
<table class="table">
    <tr style="background: #f5f5f5;">
    <td style="width:5%"><b>S/N</b></td>
        <td style="width:20%;"><b>Ordered By</b></td>
        <td style="width:15%;"><b>Ordered Date</b></td>
        <td style="width:15%;"><b>Doctor Comment</b></td>
        <td style="width:10%;"><b>Status</b></td>
        <td style="width:15%;"><b>Investigation Type</b></td>
        <td style="width:20%;"><b>Investigation Name</b></td>
        <td style="width:15%;"><b>Laboratory Result</b></td>
    </tr>
    <tbody>
        <?php
        $index = 0;
        $qrLab = "SELECT ep.Employee_Name,it.Product_Name, itl.Check_In_Type,
    itl.Status,
    itl.Service_Date_And_Time,itl.Doctor_Comment from tbl_payment_cache pc, tbl_item_list_cache itl,tbl_items it, tbl_employee ep where itl.Item_ID = it.Item_ID AND itl.Consultant_ID = ep.Employee_ID AND itl.Payment_Cache_ID = pc.Payment_Cache_ID AND pc.consultation_id = '$consultation_ID' and itl.Check_In_Type in ('Laboratory')";
        $result = mysqli_query($conn, $qrLab) or die(mysqli_error($conn));
        while ($rows = mysqli_fetch_assoc($result)) {
            $Employee_Name = $rows['Employee_Name'];
            $Created_Date_Time = $rows['Service_Date_And_Time'];
            $Doctor_Comment = $rows['Doctor_Comment'];
            $Status = $rows['Status'];
            $Check_In_Type = $rows['Check_In_Type'];
            $Product_Name = $rows['Product_Name'];
            if ($Status == "Sample Collected") {
                $Status = '<span style="color:blue;text-align:center;">Done</span>';
                $lab_result = '<input type="button" class="art-button-green" value="Results" onclick="open_lab_result()"/>';
            } else {
                $Status = '<span style="color:red;text-align:center;">Not Done</span>';
                $lab_result='';
            }
            echo '<tr><td>' . ++$index . '</td><td>' . $Employee_Name . '</td><td>' . $Created_Date_Time . '</td><td>' . $Doctor_Comment . '</td><td>' . $Status . '</td><td>' . $Check_In_Type . '</td><td>' . $Product_Name . '</td><td>'.$lab_result.'</td></tr>';
        }
        ?>
    </tbody>
</table>
<br/>


<label style="text-align: left;">E: TREATMENTS</label><br/>
<label style="text-align: left;">PROCEDURE</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/N</b></td>
        <td style="width:15%;"><b>Ordered By</b></td>
        <td style="width:10%;"><b>Ordered Date</b></td>
        <td style="width:15%;"><b>Saved By</b></td>
        <td style="width:10%;"><b>Saved Date</b></td>
        <td style="width:10%;"><b>Investigation Type</b></td>
        <td style="width:15%;"><b>Procedure Name</b></td>
        <td style="width:10%;"><b>Doctor Comment</b></td>
        <td style="width:25%;"><b>Remarks</b></td>
        <td style="width:10%;"><b>Status</b></td>
    </tr>
    <tbody>
        <?php
        $index = 0;
        $qrLab = "SELECT ep.Employee_Name,it.Product_Name, itl.Check_In_Type,itl.Doctor_Comment,
        itl.Status,itl.Transaction_Date_And_Time,itl.remarks,itl.ServedDateTime,itl.Consultant from tbl_payment_cache pc, tbl_item_list_cache itl,tbl_items it, tbl_employee ep where itl.Item_ID = it.Item_ID AND itl.Consultant_ID = ep.Employee_ID AND itl.Payment_Cache_ID = pc.Payment_Cache_ID AND pc.consultation_id = '$consultation_ID' and itl.Check_In_Type in ('Procedure')";
        // die($qrLab);
        $result = mysqli_query($conn, $qrLab) or die(mysqli_error($conn));
        while ($rows = mysqli_fetch_assoc($result)) {
            $Employee_Name = $rows['Employee_Name'];
            $Consultant = $rows['Consultant'];
            $Transaction_Date_And_Time = $rows['Transaction_Date_And_Time'];
            $Status = $rows['Status'];
            $Check_In_Type = $rows['Check_In_Type'];
            $Product_Name = $rows['Product_Name'];
            $Doctor_Comment = $rows['Doctor_Comment'];
            $remarks = $rows['remarks'];
            $ServedDateTime = $rows['ServedDateTime'];
            
            if ($Status == "served") {
                $Status = '<span style="color:blue;text-align:center;">Done</span>';
            } else {
                $Status = '<span style="color:red;text-align:center;">Not Done</span>';
            }
            echo '<tr><td>' . ++$index . '</td><td>' .$Consultant. '</td><td>' . $Transaction_Date_And_Time . '</td><td>' .$Employee_Name. '</td><td>' .$ServedDateTime. '</td><td>' . $Check_In_Type . '</td><td>' . $Product_Name . '</td><td>'.$Doctor_Comment.'</td><td>'.$remarks.'</td><td>'.$Status.'</td></tr>';
        }
        ?>
    </tbody>
</table>

<br/>

<label style="text-align: left;">PHARMACY</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/N</b></td>
        <td style="width:15%;"><b>Ordered By</b></td>
        <td style="width:15%;"><b>Doctor Quantity</b></td>
        <td style="width:15%;"><b>Dose Quantity</b></td>
        <td style="width:15%;"><b>Dispensed Quantity</b></td>
        <td style="width:15%;"><b>Ordered Date</b></td>
        <!-- <td style="width:15%;"><b>Saved By</b></td> -->
        <!-- <td style="width:15%;"><b>Saved Date</b></td> -->
        <td style="width:15%;"><b>Investigation Type</b></td>
        <td style="width:15%;"><b>Medication Name</b></td>
        <td style="width:10%;"><b>Doctor Comment</b></td>
        <!-- <td style="width:10%;"><b>Remarks</b></td> -->
        <td style="width:10%;"><b>Status</b></td>
    </tr>
    <tbody>
        <?php
        $index = 0;
        $qrLab = "SELECT ep.Employee_Name,it.Product_Name, itl.Check_In_Type,itl.Doctor_Comment,
        itl.Status,itl.Service_Date_And_Time,itl.remarks,itl.ServedDateTime,itl.dose,itl.Edited_Quantity,itl.Quantity,itl.Transaction_Date_And_Time from tbl_payment_cache pc, tbl_item_list_cache itl,tbl_items it, tbl_employee ep where itl.Item_ID = it.Item_ID AND itl.Consultant_ID = ep.Employee_ID AND itl.Payment_Cache_ID = pc.Payment_Cache_ID AND pc.consultation_id = '$consultation_ID' and itl.Check_In_Type in ('Pharmacy')";
        $result = mysqli_query($conn, $qrLab) or die(mysqli_error($conn));
        while ($rows = mysqli_fetch_assoc($result)) {
            $Employee_Name = $rows['Employee_Name'];
            $Created_Date_Time = $rows['Service_Date_And_Time'];
            $Status = $rows['Status'];
            $Check_In_Type = $rows['Check_In_Type'];
            $Product_Name = $rows['Product_Name'];
            $Doctor_Comment = $rows['Doctor_Comment'];
            $remarks = $rows['remarks'];
            $ServedDateTime = $rows['ServedDateTime'];
            $Quantity = $rows['Quantity'];
            $dose = $rows['dose'];
            $Edited_Quantity = $rows['Edited_Quantity'];
            $Transaction_Date_And_Time = $rows['Transaction_Date_And_Time'];
            
            if ($Status == "dispensed") {
                $Status = '<span style="color:blue;text-align:center;">Dispensed</span>';
            }else if ($Status == "active") {
                $Status = '<span style="color:blue;text-align:center;">Not Approved</span>';
            }else if ($Status == "paid") {
                $Status = '<span style="color:blue;text-align:center;">Paid</span>';
            } else {
                $Status = '<span style="color:red;text-align:center;">Not Dispensed</span>';
            }
            echo '<tr><td>' . ++$index . '</td><td>' .$Employee_Name. '</td><td>' . $Quantity . '</td><td>' . $dose . '</td><td>' . $Edited_Quantity . '</td><td>' . $Transaction_Date_And_Time . '</td><td>' . $Check_In_Type . '</td><td>' . $Product_Name . '</td><td>'.$Doctor_Comment.'</td><td>'.$Status.'</td></tr>';
        }
        ?>
    </tbody>
</table>

<br/>
<!-- 
<label style="text-align: left;">F: Consumables</label>
<table class="table">
    <tr style="background: #f5f5f5;">
        <td style="width:5%"><b>S/No</b></td>
        <td style="width:25%"><b>Item Name</b></td>
        <td style="width:20%"><b>Quantity Given</b></td>
        <td style="width:25%"><b>Saved By</b></td>
        <td style="width:25%"><b>Date and Time</b></td>
    </tr>
    <tbody id='patient_sent_to_cashier_tbl'>

    </tbody>
</table> -->
<br><!-- comment -->
<br>
<div id="lab_result"></div>
<div id="labGeneral" style="display: none">
            <div id="showGeneral"></div>

        </div>
<script>
    function open_lab_result(){
        var Registration_ID =<?php echo $Registration_ID;?>;
        var Patient_Payment_ID =<?php echo $Patient_Payment_ID;?>;
        var Patient_Payment_Item_List_ID =<?php echo $Patient_Payment_Item_List_ID;?>;
        var consultation_ID =<?php echo $consultation_ID;?>;
         //alert(Registration_ID);
        $.ajax({
                type:'get',
                url: 'testResults.php',
                data : {
                    Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                    Patient_Payment_ID:Patient_Payment_ID,
                    Registration_ID:Registration_ID,
                    consultation_id:consultation_ID
               },
               success : function(data){
                $('#lab_result').html(data);
                    $('#lab_result').dialog({
                        autoOpen:true,
                        width:'90%',
                        
                        position: ['center',105],
                        title:'LABORATORY RESULT',
                        modal:true
                    });  
                    $('#lab_result').html(data);
               }
           });
    }
</script>