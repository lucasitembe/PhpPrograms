<?php
    include './includes/connection.php';

    # Get pharmacy patient details
    function getPatientDetails($conn,$Payment_Cache_Id){
        $result = array();

        $select_patient = mysqli_query($conn,"SELECT 
                                                tpr.Patient_Name,tpc.Billing_Type,ts.Guarantor_Name,tpr.Gender,tpr.Registration_Date,ts.allow_pharmacy_sponsor,
                                                tpr.Registration_ID,
                                                tpr.Member_Number,
                                                tpr.Date_Of_Birth,
                                                te.Employee_Name,
                                                tpr.Occupation,
                                                tpc.Sponsor_ID,
                                                tpr.Member_Card_Expire_Date,
                                                tilc.Check_In_Type,
                                                tpc.Payment_Cache_ID,
                                                tpr.Phone_Number,
                                                tilc.Patient_Payment_ID,
                                                tpc.Payment_Date_And_Time
                                              FROM 
                                                tbl_payment_cache AS tpc,tbl_patient_registration AS tpr,tbl_sponsor AS ts,tbl_employee AS te ,tbl_item_list_cache AS tilc
                                              WHERE 
                                                tpc.Payment_Cache_ID = '$Payment_Cache_Id' AND tpc.Registration_ID = tpr.Registration_ID AND te.Employee_ID = tpc.Employee_ID AND tilc.Check_In_Type ='Pharmacy' AND tpc.Sponsor_ID = ts.Sponsor_ID LIMIT 1") or die(mysqli_errno($conn));
        if(mysqli_num_rows($select_patient) > 0){
            while($data = mysqli_fetch_assoc($select_patient)){ array_push($result,$data); }
        }
        return json_encode($result);
    }

    
    # GET PATIENT RECIEPT
    function getReciept($conn,$Payment_Cache_Id){
        $result = array();
        $select_reciept = mysqli_query($conn,"SELECT 
                                                Patient_Payment_ID 
                                              FROM 
                                                tbl_item_list_cache 
                                              WHERE 
                                                Payment_Cache_ID = '$Payment_Cache_Id'");
        while($data = mysqli_fetch_assoc($select_reciept)){ array_push($result,$data); }
        return json_encode($result);
    }

    # GET ITEM TO BE DISPENSED || ITEMS WITH ACTIVE OR PARTIAL OR PAID STATUS
    function getPatientPharmacyItems($conn,$Payment_Cache_Id,$Sub_Department_id,$Registration_ID,$inTransaction_Type,$Billing_Type,$start_date){
        $result = array();
        $filter = '';

        #CHECK IF PAYMENT CACHE IS PAID 
        $check_payment_cache_is_paid = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Check_In_Type='Pharmacy' AND Payment_Cache_ID='$Payment_Cache_Id' AND (Status='paid' OR Status='approved')") or die(mysqli_errno($conn));
        $numbers = mysqli_num_rows($check_payment_cache_is_paid);

        if($numbers > 0){
            $filter = "AND ilc.Sub_Department_ID = '$Sub_Department_id'";
        }else{
            $filter = "";
        }

        $select_item = mysqli_query($conn, "SELECT 
                                                ilc.card_and_mobile_payment_status, ilc.card_and_mobile_payment_transaction_id, ilc.Status, ilc.Consultant_ID,ilc.Transaction_Date_And_Time,dispensed_quantity,dose,Payment_Item_Cache_List_ID,dosage_duration,ilc.Transaction_Type,Edited_Quantity,Quantity,
                                                ilc.Consultant_ID,Transaction_Date_And_Time,
                                                Product_Name,Price,Discount,Doctor_Comment,tpc.Billing_Type,tim.Item_ID, ilc.Sub_Department_ID 
                                            FROM 
                                                tbl_item_list_cache AS ilc,tbl_payment_cache AS tpc, tbl_items AS tim
                                            WHERE ilc.Item_ID = tim.Item_ID AND ilc.Payment_Cache_ID = tpc.Payment_Cache_ID AND tpc.Registration_ID  = '$Registration_ID' AND ilc.Transaction_Type = '$inTransaction_Type'
                                                AND ilc.Check_In_Type = 'Pharmacy' $filter AND tpc.Billing_Type = '$Billing_Type' AND (ilc.Status = 'active' OR ilc.Status = 'paid' OR ilc.Status = 'approved' 
                                                OR ilc.Status = 'partial dispensed') AND Transaction_Date_And_Time BETWEEN '$start_date' AND NOW()") or die(mysqli_error($conn));
        while($data = mysqli_fetch_assoc($select_item)){ array_push($result,$data); }
        return json_encode($result);
    }

    # GET ITEM BALANCE PER SUB DEPAETMENT
    function getItemBalance($conn,$Sub_Department_ID,$Item_ID){
        $result = array();
        $get_balance = mysqli_query($conn, "SELECT 
                                                tib.Item_Balance 
                                            FROM 
                                                tbl_items_balance AS tib 
                                            WHERE 
                                                tib.Item_ID = '$Item_ID' AND tib.Sub_Department_ID = '$Sub_Department_ID' LIMIT 1");
        while ($balance_row = mysqli_fetch_assoc($get_balance)) {
            array_push($result,$balance_row);
        }
        return json_encode($result);
    }

    # GET DISEASES CODE 
    function getDiseaseCode($conn,$Payment_Cache_ID){
        $code = "";
        if ($query = mysqli_query($conn, "SELECT consultation_id FROM tbl_payment_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' LIMIT 1")) :
            while ($get_consultation = mysqli_fetch_assoc($query)) :
                $consultation_id = $get_consultation['consultation_id'];
                $get_disease_id = mysqli_query($conn, "SELECT 
                                                            d.disease_code 
                                                        FROM 
                                                            tbl_disease_consultation dc, tbl_disease d  
                                                        WHERE 
                                                            dc.consultation_ID = '$consultation_id' AND  d.Disease_ID = dc.Disease_ID
                AND dc.diagnosis_type = 'diagnosis'") or die(mysqli_error($conn));
                while ($get_diseases = mysqli_fetch_assoc($get_disease_id)) :
                    $code .= $get_diseases['disease_code'] . ', ';
                endwhile;
            endwhile;
        endif;
        return $code;
    }
?>