<?php
session_start();
include 'repository.php';
date_default_timezone_set("Africa/Nairobi");

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $E_Name = '';
}

if ($_POST['Registration_ID']) {
    $registrationId = $_POST['Registration_ID'];
} else {
    $registrationId = '';
}

if ($_POST['consultation_ID']){
    $consultationId = $_POST['consultation_ID'];
} else {
    $consultationId = '';
}

if (isset($_POST['store']) && $_POST['store'] === 'form-three') {
    $name = clean($_POST['name']);
    $hour = clean($_POST['hour']);
    $value = clean($_POST['value']);
    $form_id = clean($_POST['form_id']);
    $employee_id = $_SESSION['userinfo']['Employee_ID'];

    $date = date('Y-m-d H:i:s');

    // check if records exists
    $query = "SELECT id FROM tbl_icu_form_three_records 
                WHERE record_id = '$form_id' AND name = '$name' AND hour = '$hour' 
                ORDER BY id DESC";

    $result = querySelectOne($query);

    if ($result){
        // update
        echo 'updating';
        $id = $result['id'];
        $query = "UPDATE `tbl_icu_form_three_records` SET `value` = '$value' 
                    WHERE `id` = '$id';";

        $id = queryInsertOne($query);
    } else {
        // insert
        echo 'inserting';
        $query = "INSERT INTO tbl_icu_form_three_records (record_id, employee_id, name, hour, value, created_at) 
                    VALUES ('$form_id', '$employee_id', '$name', '$hour', '$value', '$date')";

        $id = queryInsertOne($query);
    }

    return $id;
}

if (isset($_POST['store']) && $_POST['store'] === 'form-three-metadata') {
    $name = clean($_POST['name']);
    $value = clean($_POST['value']);
    $form_id = clean($_POST['form_id']);
    $employee_id = $_SESSION['userinfo']['Employee_ID'];

    $date = date('Y-m-d H:i:s');

    // check if records exists
    $query = "SELECT id FROM tbl_icu_form_three_metadata 
                WHERE record_id = '$form_id' AND name = '$name' 
                ORDER BY id DESC";

    $result = querySelectOne($query);

    if ($result){
        // update
        echo 'updating';
        $id = $result['id'];
        $query = "UPDATE `tbl_icu_form_three_metadata` SET `value` = '$value' 
                    WHERE `id` = '$id';";

        $id = queryInsertOne($query);
    } else {
        // insert
        echo 'inserting';
        $query = "INSERT INTO tbl_icu_form_three_metadata (record_id, employee_id, name, value, created_at) 
                    VALUES ('$form_id', '$employee_id', '$name', '$value', '$date')";

        $id = queryInsertOne($query);
    }
    return $id;
}

if (isset($_POST['store']) && $_POST['store'] === 'form-three-kin-details') {
    $name = clean($_POST['name']);
    $value = clean($_POST['value']);
    $Admision_ID = clean($_POST['Admision_ID']);

    $query = "UPDATE tbl_admission SET $name = '$value' WHERE Admision_ID = '$Admision_ID'";

    $result = queryInsertOne($query);

    print $result;
}

checkConnection($conn);

if (isset($_POST['submit-others'])) {
    $medication_type = mysqli_real_escape_string($conn, $_POST['medication_type']);
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $amount_given = mysqli_real_escape_string($conn, $_POST['amount_given']);
    $given_time = mysqli_real_escape_string($conn, $_POST['given_time']);
    $route_type = mysqli_real_escape_string($conn, $_POST['route_type']);
    $drip_rate = mysqli_real_escape_string($conn, $_POST['drip_rate']);
    $medication_time = mysqli_real_escape_string($conn, $_POST['medication_time']);

    if (empty($medication_type) || empty($Payment_Item_Cache_List_ID)) {
        echo "<script>
                alert('Medication Type and Medication Name is reqiured');
                window.location='Inpatient_Nurse_Medicine.php?" . $_SERVER['QUERY_STRING'] . "';
              </script>";
    }

    if ($medication_type == 'outside' || $medication_type == 'others') {
        $Round_ID = '';
        $Item_ID = $Payment_Item_Cache_List_ID;
        $Payment_Item_Cache_List_ID = 'NULL';
    } else {
        $Round_ID = $_POST['r_' . $Payment_Item_Cache_List_ID];
        $Item_ID = $_POST['t_' . $Payment_Item_Cache_List_ID];
        $Payment_Item_Cache_List_ID = $_POST['pcl_' . $Payment_Item_Cache_List_ID];
    }

    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    if ($amount_given < 1 || empty($amount_given)) {
        $amount_given = 0;
    }

    if (empty($Round_ID) || is_null($Round_ID)) {
        $Round_ID = 'NULL';
    }

    $discontinue_reason = $_POST['discontinue_reason'];
    $remarks = $_POST['remarks'];
    $From_outside_amount = $_POST['fromOutsideAmount'];
    $discontinue = $_POST['discontinue'];

    $medicine_status = "SELECT Discontinue_Status FROM tbl_inpatient_medicines_given WHERE Registration_ID='$registrationId' AND consultation_ID='$consultationId' AND Item_ID='$Item_ID' ORDER BY Time_Given DESC LIMIT 1";
    $results = mysqli_query($conn, $medicine_status) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($results);
    $medicine_status_results = $row['Discontinue_Status'];
    if ($medicine_status_results == 'yes') {
        print json_encode([
            'error' => 'This medication has been discontinued to this patient.'
        ]);
        exit();
    } else {

        $insert_services_given = "
		INSERT INTO
			tbl_inpatient_medicines_given(
				Payment_Item_Cache_List_ID,
                                Item_ID,
                                Time_Given,
				Amount_Given,
				Nurse_Remarks, From_outside_amount,
				Employee_ID,
				Registration_ID,
                                consultation_ID,
				Discontinue_Status,
				Discontinue_Reason,
                                Round_ID,
                                Medication_type,given_time,route_type,drip_rate,medication_time
				)
				VALUES(
				$Payment_Item_Cache_List_ID,
                                    '$Item_ID ',
				NOW(),
				'$amount_given',
				'$remarks', '$From_outside_amount',
				'$Employee_ID',
				'$registrationId',
                '$consultationId',
				'$discontinue',
				'$discontinue_reason',
                                $Round_ID,
                                '$medication_type','$given_time','$route_type','$drip_rate','$medication_time'
				)";

        $save_services_given = mysqli_query($conn, $insert_services_given);

        if (checkSqlSuccess($conn, $save_services_given)) {

            if ($medication_type == 'others') {
                bill($Item_ID, $amount_given);
            }
            if ($discontinue == 'yes') {
                print json_encode([
                    'error' => 'This medication has been discontinued to this patient.'
                ]);
                exit();
            } else {
                print json_encode([
                    'success' => 'This medication has been saved successfully.'
                ]);
                exit();
            }
        }
    }
}

function bill($Given_Service_ID, $amount_given)
{
    global $conn;
    $has_no_folio = false;
    $Folio_Number = '';
    $registrationId = $_POST['Registration_ID'];
    $sql_check = mysqli_query($conn, "select Check_In_ID from tbl_check_in
                                where Registration_ID = '$registrationId'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));


    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

    $sql_sponsor = mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$registrationId'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

    $selectInfo = mysqli_query($conn, "select Folio_Number,pp.Sponsor_ID,Guarantor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $registrationId . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

    if (mysqli_num_rows($selectInfo)) {
        $rowsInfos = mysqli_fetch_array($selectInfo);
        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Folio_Number = $rowsInfos['Folio_Number'];
        $Sponsor_ID = $rowsInfos['Sponsor_ID'];
        $Sponsor_Name = $rowsInfos['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rowsInfos['Claim_Form_Number'];

        $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Given_Service_ID . "";
        $check_if_covered = mysqli_query($conn, $sqlcheck) or die(mysqli_error($conn));
        if (mysqli_num_rows($check_if_covered) > 0) {
            $Billing_Type = "Inpatient Cash";
        } else {
            if (strtolower($Sponsor_Name) == 'cash') {
                $Billing_Type = "Inpatient Cash";
            } else {
                $Billing_Type = "Inpatient Credit";
            }
        }

        //get last check in id
    } else {
        include("./includes/Folio_Number_Generator_Emergency.php");
        $select = mysqli_query($conn, "SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
        $rows = mysqli_fetch_array($select);

        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Supervisor_ID = $_SESSION['Admission_Supervisor']['Employee_ID'];
        $Sponsor_ID = $rows['Sponsor_ID'];
        $Sponsor_Name = $rows['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rows['Claim_Form_Number'];

        $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Given_Service_ID . "";
        $check_if_covered = mysqli_query($conn, $sqlcheck) or die(mysqli_error($conn));
        if (mysqli_num_rows($check_if_covered) > 0) {
            $Billing_Type = "Inpatient Cash";
        } else {
            if (strtolower($Sponsor_Name) == 'cash') {
                $Billing_Type = "Inpatient Cash";
            } else {
                $Billing_Type = "Inpatient Credit";
            }
        }

        $has_no_folio = true;
    }

    $pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

    if ($pre_paid == '0') {
        include("./includes/Get_Patient_Transaction_Number.php");

        $sql = " insert into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$registrationId','$Supervisor_ID','$Employee_ID',
                                              (select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID',
                                              '$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID')";

        //die($sql);

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        if (checkSqlSuccess($result, $conn)) {

            //get the last patient_payment_id & date
            $select_details = mysqli_query($conn, "select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$registrationId' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
            $num_row = mysqli_num_rows($select_details);
            if ($num_row > 0) {
                $details_data = mysqli_fetch_row($select_details);
                $Patient_Payment_ID = $details_data[0];
                $Receipt_Date = $details_data[1];
            } else {
                $Patient_Payment_ID = 0;
                $Receipt_Date = '';
            }

            $queryName = mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
            $Guarantor_Name = mysqli_fetch_assoc($queryName)['Guarantor_Name'];
            //get data from tbl_item_list_cache
            $Item_ID = $Given_Service_ID;
            $Discount = 0;
            $Price = getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name);
            $Quantity = $amount_given;
            $Consultant = '';
            $Consultant_ID = $Employee_ID;


            //insert data to tbl_patient_payment_item_list
            if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                $insert = mysqli_query($conn, "INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time)  values('IPD Services','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW())") or die(mysqli_error($conn));
            }

            //check if this user has folio
            if ($has_no_folio) {
                $update_checkin_details = "
			UPDATE tbl_check_in_details
				SET Folio_Number='$Folio_Number'
					WHERE Registration_ID = '$registrationId' AND Check_In_ID = '$Check_In_ID' AND consultation_ID='" . $_GET['consultation_ID'] . "'";
                mysqli_query($conn, $update_checkin_details) or die(mysqli_error($conn));
            }
        }
    }
}

function getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name)
{
    global $conn;
    $Item_ID = $Item_ID;
    $Billing_Type = $Billing_Type;
    $Guarantor_Name = $Guarantor_Name;

    $Price = 0;

    $Sponsor_ID = 0;

    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
        $sp = mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
        $sp = mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    }

    $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";

    $itemSpecResult = mysqli_query($conn, $Select_Price);

    checkSqlSuccess($conn, $itemSpecResult);

    if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
        $Price = $row['price'];
        if ($Price == 0) {
            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn, $Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {
                $row = mysqli_fetch_assoc($itemGenResult);
                $Price = $row['price'];
            } else {
                $Price = 0;
            }
        }

        //echo $Select_Price;
    } else {
        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn, $Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemGenResult) > 0) {
            $row = mysqli_fetch_assoc($itemGenResult);
            $Price = $row['price'];
        }
    }

    return $Price;
}

function checkConnection($conn)
{
    if (!$conn) {
        print json_encode([
            'error' => "Couldn't establish databse connection;"
        ]);
        exit();
    }
}
