<?php

@session_start();
include("./includes/connection.php");


$total = 0;
$temp = 1;
$data = '';
$sqlq = '';
$totalItem = '';
$totalDone = '';
$dataAmount = '';

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

//$filter = " AND DATE(Transaction_Date_And_Time)= DATE(NOW())  AND ilc.Sub_Department_ID='$Sub_Department_ID'";
$filter = " AND DATE(Transaction_Date_And_Time)= DATE(NOW())  ";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'  AND ilc.Sub_Department_ID='$Sub_Department_ID'";
}

if (!empty($SubCategory) && $SubCategory != 'All') {
    $filter .=" AND its.Item_Subcategory_ID='$SubCategory'";
}

//die($filter);

// echo  '$sqlq';exit; style="background-color:#037DB0;color:white;font-weight:bold"

echo '<table width ="100%"  >
            <thead >
                <tr>
                    <td ><b>SN</b></td>
                    <td width="30%"><b>TEST NAME</b></td>
                    <td><b>SENT</b></td>
                    <td width="10%"><b>DOCT. REMARKS</b></td>
                    <td><b>IMAGE ID</b></td>
                    <td><b>PROGRESS</b></td>
                    <td><b>REMARKS</b></td>
                    <td width="15%"><b>CLASSIFICATION</b></td>
                    <td width="15%"><b>IMAGING</b></td>
                    <td width="15%"><b>RADIOLOGIST</b></td>
                    <td width="15%"><b>REPORTEUR</b></td>
                </tr>
            </thead>';

$sqlq = "SELECT ilc.Price,ilc.Consultant,ilc.Consultant_ID,ilc.Clinic_ID,ilc.Transaction_Date_And_Time,ilc.Status,its.Item_ID, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID,ilc.Doctor_Comment, ilc.Payment_Cache_ID,ilc.remarks,pc.Billing_Type,ilc.Transaction_Type,ilc.payment_type,Require_Document_To_Sign_At_receiption
		from tbl_item_list_cache ilc,tbl_payment_cache pc, tbl_items its, tbl_sponsor sp 		  
                where ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                    ilc.item_id = its.item_id and
                    sp.Sponsor_ID=pc.Sponsor_ID and
                    ilc.status IN ('active','paid')  and 
                    ilc.removing_status='no' and
                    pc.Registration_ID='$Registration_ID' and
                                        ilc.Check_In_Type = 'Radiology' $filter
		 ";

//die($sqlq);

$select_Transaction_Items_Active = mysqli_query($conn,$sqlq) or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Transaction_Items_Active);

$transStatust = false;
while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
    $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $payment_type = strtolower($row['payment_type']);
    $require_approve = strtolower($row['Require_Document_To_Sign_At_receiption']);
    $The_Item_ID = $row['Item_ID'];
    $datetime = $row['Transaction_Date_And_Time'];
    $Clinic_ID = $row['Clinic_ID'];
    $Consultant = $row['Consultant'];
    $Consultant_ID = $row['Consultant_ID'];
    $Price = $row['Price'];
    $displ = '';

    $msg = '';

    if (($billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $transStatust = true;
        $msg = 'Radiology not paid.';
    } elseif ($billing_Type == 'outpatient credit' && $status == 'active' && $require_approve == 'mandatory') {
        $transStatust = true;
        $msg = 'Radiology not approved.';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        if ($pre_paid == '1') {
            if($payment_type == 'post'){
                $transStatust = false;
                //$msg = 'Procedure not paid.';
            } else {
                $transStatust = true;
                $msg = 'Radiology not paid.';
            }
        } elseif ($payment_type == 'pre') {
            $transStatust = true;
            $msg = 'Radiology not paid.';
        }
    }


    if ($transStatust) {

        $displ = '<td rowspan="2"style="text-align:center" colspan="6"><h5 style="color:#FF0E7B;font-size:20px" class="notpaid">' . $msg . '<h5></td>'
        . '<td><button type="button" class="art-button-green" onclick="removeFromList(' . $row["Payment_Item_Cache_List_ID"] . ')">Remove</button></td>';
        
        // @mfoy dn
        if($billing_Type=='inpatient cash'){
            if($_SESSION['userinfo']['can_create_inpatient_bill']!="yes"){//*****gkkcchief*****
                $displ .= "<td><button type='button' class='art-button-green' onclick='alert(\"Access Denied\")'>Send To Bil</button></td>";
            }else{
            $displ .= '<td><button type="button" class="art-button-green" onclick="sendToBill(' . $row["Payment_Item_Cache_List_ID"] . ')">Send To Bill</button></td>'; //@Mfoy dn

            }
        }
        // end @mfoy dn
            
    } else {
        //Getting the Old Values *****************//
        $select_rpt = "SELECT * FROM tbl_radiology_patient_tests WHERE Item_ID = '$The_Item_ID' AND Patient_Payment_Item_List_ID='" . $row["Payment_Item_Cache_List_ID"] . "' AND Registration_ID = '$Registration_ID'";
        $select_rpt_qry = mysqli_query($conn,$select_rpt) or die(mysqli_error($conn));
        $Sonographer_ID = 0;
        $Radiologist_ID = 0;
        $oldstatus = '';
        $oldremarks = '';
        $oldclas = '';
        while ($old_rpt = mysqli_fetch_assoc($select_rpt_qry)) {
            $oldstatus = $old_rpt['Status'];
            $oldremarks = $old_rpt['Remarks'];
            $oldclas = $old_rpt['Classification'];
            $Sonographer_ID = $old_rpt['Sonographer_ID'];
            $Radiologist_ID = $old_rpt['Radiologist_ID'];
        }
        $displ = '<td ><b>'.$The_Item_ID.'</b></td>';
        $displ .= '<td style="text-align:center"><select class="Procedureprogress" id="' . $row["Payment_Item_Cache_List_ID"] . '" name="status_' . $row["Payment_Item_Cache_List_ID"] . '" style="padding:5px;">
					                 <option>Select progress</option>
							 <option value="served">Done</option>
							 <option value="pending">Pending</option>
							 <option value="not done">Not Done</option>
						   </select>  
                                                   
                  <input type="hidden" name="status_pro_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $status . '"/>
                  <input type="hidden" name="billing_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $billing_Type . '"/> 
                  <input type="hidden" name="transaction_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $transaction_Type . '"/> 
                  <input type="hidden" name="payment_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $payment_type . '"/> 
                  <input type="hidden" name="require_approve_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $require_approve . '"/>      
                              
                  </td>';
        $displ .= "<td rowspan='2'><input type='hidden' value='" . $row['Payment_Item_Cache_List_ID'] . "' name='paymentItermCache[]'><textarea type='text' name='remarks_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row["Payment_Item_Cache_List_ID"] . "' style='' >" .($row['remarks']). "</textarea></td>";

        //classification
        $displ.="<td rowspan='2'><select name='classification_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "classification' style='width:100%' >
				<option></option>";
        $ord = "";
        $sp = "";
        $eeg = "";
        $echo = "";
        $ct = "";
        $ct = "";
        $ecg = "";
        $other = "";
        $ord = "";
        $sp = "";
        $eeg = "";
        $echo = "";
        $ct = "";
        $ct = "";
        $ecg = "";
        $other = "";
        $ultr = "";
        if ($oldclas == 'Ordinary Xray Register') {
            $ord = "selected";
        } elseif ($oldclas == 'Special Xray Register') {
            $sp = "selected";
        } elseif ($oldclas == 'Eeg Register') {
            $eeg = "selected";
        } elseif ($oldclas == 'Ultrasound Register') {
            $ultr = "selected";
        } elseif ($oldclas == 'Echo Cardiogram Register') {
            $echo = "selected";
        } elseif ($oldclas == 'Ct-Scan') {
            $ct = "selected";
        } elseif ($oldclas == 'Ecg') {
            $ecg = "selected";
        } elseif ($oldclas == 'Other') {
            $other = "selected";
        } //else {
        $displ.="  <option " . $ord . ">Ordinary Xray Register</option>
                                        <option " . $sp . ">Special Xray Register</option>
                                        <option " . $eeg . ">Eeg Register</option>
                                        <option " . $ultr . ">Ultrasound Register</option>
                                        <option " . $echo . ">Echo Cardiogram Register</option>
                                        <option " . $ecg . ">Ecg</option>
                                        <option " . $ct . ">Ct-Scan</option>
                                        <option " . $ct . ">MRI</option>
                                        <option " . $other . ">Other</option> ";
        $displ.="</select></td>";

        //Sonographer Select
        $displ.="<td rowspan='2'><select name='sonographer_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "sonographer' style='width:100%' >
				<option value=''>--Select--</option>";

        $select_sonographer = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND  Employee_Job_Code LIKE '%Sonographer%'";
        $select_sonographer_qry = mysqli_query($conn,$select_sonographer) or die(mysqli_error($conn));

        while ($sonog = mysqli_fetch_assoc($select_sonographer_qry)) {
            $sonogname = $sonog['Employee_Name'];
            $sonogid = $sonog['Employee_ID'];
            if ($Sonographer_ID == $sonogid) {
                $displ.= "<option value='" . $sonogid . "' selected='selected'>" . strtoupper($sonogname) . "</option>";
            } else {
                $displ.= "<option value='" . $sonogid . "'>" . strtoupper($sonogname) . "</option>";
            }
        }
        $displ.= '</select></td>';

        //Radiologist Select
        $displ.="<td rowspan='2'><select name='radiologist_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "radiologist'  style='width:100%' >
				<option value=''>--Select--</option>";

        $select_radiologist = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND  Employee_Job_Code LIKE '%Radiologist%'";
        $select_radiologist_qry = mysqli_query($conn,$select_radiologist) or die(mysqli_error($conn));
        while ($radist = mysqli_fetch_assoc($select_radiologist_qry)) {
            $radistname = $radist['Employee_Name'];
            $radistid = $radist['Employee_ID'];
            if ($Radiologist_ID == $radistid) {
                $displ.= "<option value='" . $radistid . "' selected='selected'>" . strtoupper($radistname) . "</option>";
            } else {
                $displ.= "<option value='" . $radistid . "'>" . strtoupper($radistname) . "</option>";
            }
        }
        $displ.= "<option value='NULL'>NOT APPLICABLE</option>";
        $displ.= '</select></td>';

        //Reporteur Select

        $RepEmpID = $_SESSION['userinfo']['Employee_ID'];
        $RepEmpName = $_SESSION['userinfo']['Employee_Name'];

        $displ.="<td rowspan='2'><select name='reporteur_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "reporteur'  style='width:100%' >
				<option value='$RepEmpID'>$RepEmpName</option>";

        $select_report = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND (Employee_Job_Code  NOT LIKE '%Radiologist%' OR Employee_Job_Code  NOT LIKE '%Sonographer%')";
        $select_report_qry = mysqli_query($conn,$select_report) or die(mysqli_error($conn));
        
        while ($report = mysqli_fetch_assoc($select_report_qry)) {
            $employeename = $report['Employee_Name'];
            $empid = $report['Employee_ID'];
            if ($RepEmpID != $empid) {
                $displ.= "<option value='" . $empid . "'>" . strtoupper($employeename) . "</option>";
            }
        }
        
        $displ.= "<option value='NULL'>NOT APPLICABLE</option>";
        $displ.= '</select></td>';

        //Buttons clicks

        $Item_ID = $row['Item_ID'];
        $Patient_Payment_Item_List_ID = $row['Payment_Item_Cache_List_ID'];
        $Patient_Payment_ID = $row['Payment_Cache_ID'];
        $test_name = $row['Product_Name'];

        $href = "radiologyviewimage.php?II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . '&RI=' . $Registration_ID;

        $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . '&RI=' . $Registration_ID;

        $add_parameters = '<button style="width:10%;" type="button" class="art-button-green" onclick="radiologyviewimage(\'' . $href . '\',\'' . $test_name . '\')">Imaging</button>';
        $commentsDescription = '<button style="width:10%;" type="button" class="art-button-green" onclick="commentsAndDescription(\'' . $href . '\',\'' . $test_name . '\')">Comments</button>';
        $remove  = '<button type="button" style="width:10%;" class="art-button-green" onclick="removeFromList(' . $row["Payment_Item_Cache_List_ID"] . ')">Remove</button>';
//        $displ.= '<td rowspan="2">' . $add_parameters . $commentsDescription . '<button type="button" style="width:60%;" class="art-button-green" onclick="removeFromList(' . $row["Payment_Item_Cache_List_ID"] . ')">Remove</button></td>';
    }
   
    echo "<tr>"
    . "<td width='5%' rowspan='4'><input type='text' size='3' value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
    echo "<td>" . $row['Product_Name'] . "</td>";
    echo "<td>" . $datetime . "</td>";
    echo "<td>" . $row['Doctor_Comment'] . "</td>";
    echo $displ;
    // }

    $temp++;
    $transStatust = false;
    echo "</tr>";
    $sql_select_oderd_clinic_result=mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_oderd_clinic_result)>0){
       $Clinic_Name=mysqli_fetch_assoc($sql_select_oderd_clinic_result)['Clinic_Name']; 
    }else{
        $Clinic_Name="";
    }
    $sql_select_oderd_result=mysqli_query($conn,"SELECT Employee_Name,Phone_Number FROM tbl_employee WHERE Employee_ID='$Consultant_ID'") or die(mysqli_error($conn));

    $Phone_Number1=mysqli_query($conn,"SELECT Phone_Number FROM tbl_employee WHERE Employee_ID='$Consultant_ID'") or die(mysqli_error($conn));
    
    $Employee_Name=mysqli_fetch_assoc($sql_select_oderd_result)['Employee_Name'];
    $Phone_Number=mysqli_fetch_assoc($Phone_Number1)['Phone_Number'];
    echo "<tr><td style='color:#0E7DC3' colspan='4' >Ordered From: <b>".strtoupper($Clinic_Name)."</b></td></tr>";
    echo "<tr><td style='color:#0E7DC3' colspan='4' >Ordered By: <b>".strtoupper($Employee_Name)."</b></td></tr>";
    echo "<tr><td style='color:#0E7DC3' colspan='4' >Doctor Phone Number: <b>".$Phone_Number."</b></td></tr>";
    echo "<tr><td colspan='2' style='text-align:right'>Price :<b>".number_format($Price)."</b></td><td colspan='8'> <center>$add_parameters   $commentsDescription    $remove </center> </td></tr>";
   
}
echo "</table>";

?>

<!-- @Mfoy dn -->
<script>
    function sendToBill(Payment_Item_Cache_List_ID){
        var selected_item = []; 
        var Registration_ID = '<?php echo $_GET['Registration_id'] ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID ?>';
        var Guarantor_Name = '<?php echo $Guarantor_Name ?>';
  
        var validate=0;
        selected_item.push(Payment_Item_Cache_List_ID);
        validate++;

    // console.log(selected_item);

        if(validate<=0){
            alert("Please Select Test You Want To Sent To Bill First");
        }else{
            if(confirm("Are you sure you want to bill the selected items?")){
            $.ajax({
                type:'POST',
                url:'ajax_creating_inpatient_bill.php',
                data:{selected_item:selected_item,Registration_ID:Registration_ID,Sponsor_ID:Sponsor_ID,Guarantor_Name:Guarantor_Name,Check_In_Type:"Radiology"},
                success:function(data){
                    console.log(selected_item);
                    console.log(data);
//                    if(data=='collected_successfullly'){
                        alert("Bill Created Successfully");
                        location.reload();
//                    }else{
//                        alert("Fail to create inpatient bill");
//                    }
                }
            });
        }
        }
    }
</script>
<!-- // @Mfoy dn -->
