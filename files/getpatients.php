
<?php
include("./includes/connection.php");
session_start();
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
$search = '';
$Patient_Name = "";
$Patient_Number = "";
$Phone_Number = "";
$patient_direction = "";

if (isset($_GET['transfers_IDs'])) {
    $transfers_IDs = $_GET['transfers_IDs'];
} else {
    $transfers_IDs = "";
}

if (isset($_GET['transfer_type'])) {
    $transfer_type = $_GET['transfer_type'];
} else {
    $transfer_type = "";
}

if (isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != '') {
    $Patient_Name = $_GET['Patient_Name'];
    $search = "AND pr.Patient_Name LIKE '%$Patient_Name%'";
} else {
    $Patient_Name = "";
}
if (isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != '') {
    $Patient_Number = $_GET['Patient_Number'];
    $search = "AND pr.Registration_ID LIKE '%$Patient_Number%'";
} else {
    $Patient_Number = "";
}
if (isset($_GET['Phone_Number']) && $_GET['Phone_Number'] != '') {
    $Phone_Number = $_GET['Phone_Number'];
    $search = "AND pr.Phone_Number LIKE '%$Phone_Number%'";
    //ECHO 'SSS';
} else {
    $Phone_Number = "";
}

if (isset($_GET['getTransferee']) && $_GET['getTransferee'] == 'true') {
    $data = '';
    if ($_GET['transfer_type'] == 'Doctor_To_Doctor') {
        $data .= "<option selected='selected' >Select a doctor</option>";

        $consults = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' AND Employee_ID != '$transfers_IDs' AND  Account_Status='active'");
        while ($row_ = mysqli_fetch_array($consults)) {
            $Employee_ID = $row_['Employee_ID'];
            $Employee_Name = $row_['Employee_Name'];
            $data .= "<option value='" . $Employee_ID . "'>" . $Employee_Name . "</option>";
        }
    } else if ($_GET['transfer_type'] == 'Doctor_To_Clinic') {
        $data .= '<option selected="selected" value="Select clinic">Select clinic</option>';
        $consult_clinic = mysqli_query($conn,"Select * from tbl_clinic where Clinic_Status='Available' AND Clinic_ID != '$transfers_IDs'");
        while ($row = mysqli_fetch_array($consult_clinic)) {
            $Clinic_ID = $row['Clinic_ID'];
            $Clinic_Name = $row['Clinic_Name'];

            $data .='<option  value="' . $Clinic_ID . '">' . $Clinic_Name . '</option>';
        }
    } else if ($_GET['transfer_type'] == 'Clinic_To_Clinic') {
        $data .= '<option selected="selected" value="Select clinic">Select clinic</option>';
        $consult_clinic = mysqli_query($conn,"Select * from tbl_clinic where Clinic_Status='Available' AND Clinic_ID != '$transfers_IDs'");
        while ($row = mysqli_fetch_array($consult_clinic)) {
            $Clinic_ID = $row['Clinic_ID'];
            $Clinic_Name = $row['Clinic_Name'];

            $data .='<option  value="' . $Clinic_ID . '">' . $Clinic_Name . '</option>';
        }
    } else if ($_GET['transfer_type'] == 'Clinic_To_Doctor') {
        $data .= "<option selected='selected' >Select a doctor</option>";

        $consults = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' AND Employee_ID != '$transfers_IDs' AND  Account_Status='active'");
        while ($row_ = mysqli_fetch_array($consults)) {
            $Employee_ID = $row_['Employee_ID'];
            $Employee_Name = $row_['Employee_Name'];
            $data .= "<option value='" . $Employee_ID . "'>" . $Employee_Name . "</option>";
        }
    }

    echo $data;
} else {
     if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
         $transfers_IDs=$_SESSION['userinfo']['Employee_ID'];
     }
     
     
     $filter=' AND DATE(pl.Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) ';
    
          $Date_From=  filter_input(INPUT_GET, 'Date_From');
          $Date_To=  filter_input(INPUT_GET, 'Date_To');
         
       
        if(isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)){
          $filter="  AND Transaction_Date_And_Time BETWEEN '". $Date_From."' AND '".$Date_To."'";
        }
        
    $consultant='';  
    if ($transfer_type == 'Doctor_To_Doctor') {
        $patient_direction = "Direct To Doctor";
        $consultant=" and pl.Consultant_ID = " . $transfers_IDs . " ";
    } else if ($transfer_type == 'Doctor_To_Clinic') {
        $patient_direction = "Direct To Doctor";
        $consultant=" and pl.Consultant_ID = " . $transfers_IDs . " ";
    } else if ($transfer_type == 'Clinic_To_Clinic') {
        $patient_direction = "Direct To Clinic";
        $consultant=" and pl.Clinic_ID = " . $transfers_IDs . " ";
    } else if ($transfer_type == 'Clinic_To_Doctor') {
        $patient_direction = "Direct To Clinic";
        $consultant=" and pl.Clinic_ID = " . $transfers_IDs . " ";
    }

    $temp = 1;
    echo '<br>';

    // $Select_patients = mysqli_query($conn,"select * from  
    // tbl_patient_payments c,tbl_patient_registration pr,tbl_patient_payment_item_list ppl
    // WHERE 
    // ppl.Consultant_ID = '$transfers_IDs' AND 
    // c.Registration_ID=pr.Registration_ID  AND 
    // ppl.Process_Status <> 'signedoff' AND Patient_Direction IN ('Direct To Clinic','Direct To Doctor Via Nurse Station','Direct To Doctor') AND Billing_Type='Outpatient Credit'
    // AND c.Patient_Payment_ID=ppl.Patient_Payment_ID $search
    // GROUP BY pr.Registration_ID ORDER BY pr.Registration_ID ASC ") or die(mysqli_error($conn));

    $qr = "
	   select pr.Registration_ID,pr.Patient_Name,Check_In_ID,Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,'0' AS consultationID from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp
		      where sp.sponsor_id = pr.sponsor_id and
		      pp.Registration_ID = pr.Registration_ID
              $search 
			  and
		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and pl.Process_Status NOT IN ('signedoff','no show') and
                      pl.Patient_Direction = '$patient_direction' $consultant and
                      pp.Transaction_status != 'cancelled' AND
		      pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " $filter GROUP BY pp.Registration_ID order by pl.Transaction_Date_And_Time
	
		  "
    ;
    

    $Select_patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    //  $result = mysqli_query($conn,$Select_patients);
//echo $qr;exit;

    $no = mysqli_num_rows($Select_patients);
    // echo ($qr);exit; 
    if ($no > 0) {

        echo '<center><table width =90% border="1px">';
        echo '<tr>	<td width ="3%" style="text-align:center;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td width ="10%"><b>PATIENT NO</b></td>
				<td><b>TRANS DATE</b></td>
				
				<td><b>Reason</b></td>
				<td style="text-align:right;">
				  <input type="checkbox" name="transferAll"  id="transferAll" style="margin-right:10px;" onclick="checktransferAll(this)"/><label for="transferAll"><b>All</b></label></td>
		</tr><tr><td colspan="6"><hr/></td></tr>';

        while ($row = mysqli_fetch_array($Select_patients)) {

            //Get check-in ID
            $Registration_ID = $row['Registration_ID'];
            $select_checkin = "SELECT Check_In_ID,Type_Of_Check_In FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' AND Check_In_ID='" . $row['Check_In_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
            //echo $select_checkin;exit;
            $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
            //echo $row['Check_In_ID'].'kl<br/>';
            $date = date('Y-m-d', strtotime($row['Transaction_Date_And_Time']));
            if (mysqli_num_rows($select_checkin_qry) > 0) {
                echo "<tr><td style='text-align:center;'>" . $temp . "</td><td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";

                echo "<td style='text-align:center;'>" . $row['Registration_ID'] . "</td>";

                $temp++;
                ?>
                <td>
                    <?php echo $date; ?>
                </td>
                <td>
                 <!--<input type='text' id='reason_<?php echo $row['Registration_ID']; ?>'>-->
                    <textarea style='width:100%;height:15px '  id='reason_<?php echo $row['Registration_ID']; ?>' name='<?php echo $row['Registration_ID']; ?>'></textarea>
                </td>
                <td style='text-align:center;'>
                    <input type="checkbox" ppil="<?php echo $row['Patient_Payment_Item_List_ID']; ?>" ckid="<?php echo $row['Check_In_ID']; ?>" class="tansfersPatient patient_id_<?php echo $row['Registration_ID']; ?>" consID="<?php echo $row['consultationID']; ?>" id="<?php echo $row['Registration_ID']; ?>">
                </td>
                <?php
            }
        }
    } else {

        echo "<b style='color:red;font-size:20px;color:#c0c0c0'>No patient(s) available</b>";
    }
    ?>
    </tr>
    </table></center>
    <?php
}
?>
<script src="js\jquery.js"></script>
<script>
    $("#transferAll").click(function () {
        alert('clciked');
    });
</script>