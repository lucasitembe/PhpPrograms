<?php
    include '../includes/connection.php';

    # Get pharmacy patient details
    function getPatientDetails($conn,$Payment_Cache_Id){
        $result = array();

        $select_patient = mysqli_query($conn,"SELECT 
                                                tpr.Patient_Name,tpc.Billing_Type,ts.Guarantor_Name,tpr.Gender,tpr.Registration_Date,
                                                tpr.Registration_ID,tpr.Member_Number,tpr.Date_Of_Birth,te.Employee_Name,tpr.Occupation,
                                                tpc.Sponsor_ID,tpr.Member_Card_Expire_Date,tilc.Check_In_Type,tpc.Payment_Cache_ID,tpr.Phone_Number,
                                                tilc.Patient_Payment_ID,tilc.Payment_Date_And_Time
                                              FROM 
                                                tbl_payment_cache AS tpc,tbl_patient_registration AS tpr,tbl_sponsor AS ts,tbl_employee AS te ,tbl_item_list_cache AS tilc
                                              WHERE 
                                                tpc.Payment_Cache_ID = '$Payment_Cache_Id' AND tpc.Registration_ID = tpr.Registration_ID AND te.Employee_ID = tpc.Employee_ID AND tilc.Check_In_Type ='Pharmacy' AND tpc.Sponsor_ID = ts.Sponsor_ID LIMIT 1") or die(mysqli_errno($conn));
        if(mysqli_num_rows($select_patient) > 0){
            while($data = mysqli_fetch_assoc($select_patient)){ array_push($result,$data); }
        }
        mysqli_free_result($select_patient);
        return json_encode($result);
    }

    
    # get patient reciept
    function getReciept($conn,$Payment_Cache_Id){
        $result = array();
        $select_reciept = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID = '$Payment_Cache_Id' AND Check_In_Type = 'Pharmacy'");
        while($data = mysqli_fetch_assoc($select_reciept)){ array_push($result,$data); }
        mysqli_free_result($select_reciept);
        return json_encode($result);
    }

    # get item with items to be dispensed
    function getPatientPharmacyItems($conn,$Payment_Cache_Id,$Sub_Department_id,$Registration_ID,$inTransaction_Type,$Billing_Type,$start_date){
        $result = array();
        $filter = '';

        # check if item is paid
        $check_payment_cache_is_paid = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Check_In_Type='Pharmacy' AND Payment_Cache_ID='$Payment_Cache_Id' AND (Status='paid' OR Status='approved' OR Status='partial dispensed')") or die(mysqli_errno($conn));
        $numbers = mysqli_num_rows($check_payment_cache_is_paid);

        $filter = ($numbers > 0) ? "AND ilc.Sub_Department_ID = '$Sub_Department_id'" : "";

        $select_item = mysqli_query($conn, "SELECT ilc.Status,dispensed_quantity,dose,Payment_Item_Cache_List_ID,dosage_duration,ilc.Transaction_Type,Edited_Quantity,Quantity,Product_Name,Price,Discount,Doctor_Comment,tpc.Billing_Type,tim.Item_ID, ilc.Sub_Department_ID,Consultant_ID,Transaction_Date_And_Time
                                            FROM tbl_item_list_cache AS ilc,tbl_payment_cache AS tpc, tbl_items AS tim
                                            WHERE ilc.Item_ID = tim.Item_ID AND ilc.Payment_Cache_ID = tpc.Payment_Cache_ID AND tpc.Registration_ID  = '$Registration_ID' AND ilc.Transaction_Type = '$inTransaction_Type' AND ilc.Check_In_Type = 'Pharmacy' $filter AND tpc.Billing_Type = '$Billing_Type' AND (ilc.Status = 'active' OR ilc.Status = 'paid' OR ilc.Status = 'approved' OR ilc.Status = 'free'
                                            OR ilc.Status = 'partial dispensed') AND Transaction_Date_And_Time BETWEEN '$start_date' AND NOW() ORDER BY Transaction_Date_And_Time DESC") or die(mysqli_error($conn));
        while($data = mysqli_fetch_assoc($select_item)){ array_push($result,$data); }
        mysqli_free_result($select_item);
        return json_encode($result);
    }

    # get item balance for department
    function getItemBalance($conn,$Sub_Department_ID,$Item_ID){
        $result = array();
        $get_balance = mysqli_query($conn, "SELECT tib.Item_Balance FROM tbl_items_balance AS tib WHERE tib.Item_ID = '$Item_ID' AND tib.Sub_Department_ID = '$Sub_Department_ID' LIMIT 1");
        while ($balance_row = mysqli_fetch_assoc($get_balance)) {
            array_push($result,$balance_row);
        }
        mysqli_free_result($get_balance);
        return json_encode($result);
    }


    # get disease code
    function getDiseaseCode($conn,$Payment_Cache_ID){
        $code = "";
        if ($query = mysqli_query($conn, "SELECT consultation_id FROM tbl_payment_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' LIMIT 1")) :
            while ($get_consultation = mysqli_fetch_assoc($query)) :
                $consultation_id = $get_consultation['consultation_id'];
                $get_disease_id = mysqli_query($conn, "SELECT d.disease_code FROM tbl_disease_consultation dc, tbl_disease d WHERE dc.consultation_ID = '$consultation_id' AND  d.Disease_ID = dc.Disease_ID
                AND dc.diagnosis_type = 'diagnosis'") or die(mysqli_error($conn));
                while ($get_diseases = mysqli_fetch_assoc($get_disease_id)) :
                    $code .= $get_diseases['disease_code'] . ', ';
                endwhile;
            endwhile;
        endif;
        return $code;
    }

    #change change free items to free
    function changeFreeItemStatus($conn,$Payment_Cache_ID){
        # get sponsor for the transaction
        $sponsor_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_payment_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' LIMIT 1"))['Sponsor_ID'];
        
        # get free item to update status
        $selectItems = mysqli_query($conn,"SELECT Item_ID,Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID'");
        while($data = mysqli_fetch_assoc($selectItems)){
            $Item_ID = $data['Item_ID'];
            $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];

            #check if is free items
            $selectItm = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(Item_ID) AS number FROM tbl_free_items WHERE item_id='$Item_ID' AND sponsor_id = '$sponsor_ID'"))['number'];
            if($selectItm > 0){
                mysqli_query($conn,"UPDATE tbl_item_list_cache SET status ='free' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND status = 'active' LIMIT 1");
            }
        } 
    }

    # Get all system configuration
    function getSystemConfiguration($conn){
        $result = array();
        $systemConfiguration = mysqli_query($conn,"SELECT Change_Medication_Location,Enable_Add_More_Medication,Display_Send_To_Cashier_Button FROM tbl_system_configuration");

        while($data = mysqli_fetch_assoc($systemConfiguration)){
            array_push($result,$data);
        }
        mysqli_free_result($systemConfiguration);
        return json_encode($result);
    }
    
    # substitute medication from medication
    function substituteMedication($conn,$Payment_Item_Cache_List_ID,$old_item_Id,$new_item_id,$new_item_price,$Employee_ID){
        $updateMedication = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Item_ID = '$new_item_id',item_substituted='$old_item_Id',Price='$new_item_price',substituted_by='$Employee_ID' WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_errno($conn));
        echo ($updateMedication) ? "ok" : "bad";
    }