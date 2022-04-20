<?php

    include("connection.php");

    $Current_Sub_Department_Name = $_SESSION['Theater_Department_Name'];

    $Current_Sub_Department_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_ID FROM tbl_sub_department Sub_Department_Name = '$Current_Sub_Department_Name'"))['Sub_Department_ID'];
    $display = "<option value='".$Current_Sub_Department_ID."' selected='selected'>".$Current_Sub_Department_Name."</option>";

    $Select_All_Department = mysqli_query($conn, "SELECT Sub_Department_Name, Sub_Department_ID FROM tbl_sub_department sd, tbl_department dp WHERE dp.Department_ID = sd.Department_ID AND dp.Department_Location IN ('Surgery','Theater') AND dp.Department_Status = 'Active' AND sd.Sub_Department_Status = 'Active' AND sd.Sub_Department_ID <> '$Current_Sub_Department_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_All_Department)>0){
            while($dep = mysqli_fetch_assoc($Select_All_Department)){
                $Sub_Department_Name = $dep['Sub_Department_Name'];
                $Sub_Department_ID = $dep['Sub_Department_ID'];

                $display .= "<option value='".$Sub_Department_ID."' selected='selected'>".$Sub_Department_Name."</option>";
            }
        }

    function data($conn,$query){
        $sql_query = mysqli_query($conn,$query);
        $result = array();
        while($data = mysqli_fetch_assoc($sql_query)){
            array_push($result,$data);
        }
        return json_encode($result);
    }

    //SELECTING ALL DEPARTMENT
    function GetSubdepartmentForSurgery($conn,$Department_Location, $SubDepartment_Status){
        return data($conn, "SELECT Sub_Department_Name, Sub_Department_ID, Department_Name FROM tbl_sub_department sd, tbl_department dp WHERE dp.Department_ID = sd.Department_ID AND dp.Department_Location = '$Department_Location' AND dp.Department_Status = '$SubDepartment_Status' AND sd.Sub_Department_Status = '$SubDepartment_Status'");
    }

    //SELECTING ALL ITEMS
    function GetItemsForSurgery($conn,$Consultation_Type, $Product_Name){
        $filter = "";
        if($Consultation_Type == "Pharmacy"){
            $check = " IN ('Others','Pharmacy')";
        }else{
            $check = " = '$Consultation_Type'";
        }
        $filter .= ($Product_Name == "" || $Product_Name == NULL) ? "" : " AND Product_Name LIKE '%$Product_Name%'";
        $filter .= ($Consultation_Type == "" || $Consultation_Type == NULL) ? "" : " AND Consultation_Type $check";

        return data($conn, "SELECT Product_Name, Item_ID FROM tbl_items WHERE Status = 'available' $filter");
    }

    //MERGING SURGERY DEPARTMENT TO THE STORE 
    function InsertSubDepartmentMerging($conn,$Store_ID, $Theater_ID,$Employee_ID){
        $filter = "";
        $filter .= ($Theater_ID == "" || $Theater_ID == NULL) ? "" : " WHERE Theater_ID = '$Theater_ID'";
        
        $Select_SubDepartment = mysqli_query($conn, "SELECT Theater_ID FROM tbl_attached_theater $filter") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_SubDepartment) > 0){
                return 201;
            }else{
            $Insert_SubDepartment = mysqli_query($conn, "INSERT INTO tbl_attached_theater(Theater_ID, Store_ID, Employee_ID, Attached_DateTime) VALUES('$Theater_ID', '$Store_ID', '$Employee_ID', NOW())") or die(mysqli_error($conn));
                if($Insert_SubDepartment){
                    return 200;
                }
            }
    }

    //REMOVING SUBDEPARTMENT
    function RemoveSubDepartmentMerging($conn,$Attachement_ID){
        $Select_SubDepartment = mysqli_query($conn, "SELECT Attachement_ID FROM tbl_attached_theater WHERE Attachement_ID = '$Attachement_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_SubDepartment) > 0){
                $Delete_Department = mysqli_query($conn, "DELETE FROM tbl_attached_theater WHERE Attachement_ID = '$Attachement_ID'") or die(mysqli_error($conn));
                    if($Delete_Department){
                        return 200;
                    }else{
                        return 201;
                    }
            }else{
                return 300;
            }
    }
    //MERGING PHARMACY ITEMS TO THE SURGERY 
    function InsertItemMerging($conn,$Surgery_ID, $Consumable_ID,$Employee_ID){        
        $Select_Mergerd_Item = mysqli_query($conn, "SELECT Consumable_ID FROM tbl_surgery_consumables WHERE Surgery_ID = '$Surgery_ID' AND Consumable_ID = '$Consumable_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_Mergerd_Item) > 0){
                return 201;
            }else{
            $Insert_Merged_Item = mysqli_query($conn, "INSERT INTO tbl_surgery_consumables(Surgery_ID, Consumable_ID, Employee_ID, Merged_DateTime) VALUES('$Surgery_ID', '$Consumable_ID', '$Employee_ID', NOW())") or die(mysqli_error($conn));
                if($Insert_Merged_Item){
                    return 200;
                }
            }
    }

    //SELECTING SURGICAL ITEMS 
    function GetSurgicalItems($conn,$Consultation_Type,$Product_Name){
        $filter = "";
        $filter .= ($Consultation_Type == "" || $Consultation_Type == NULL) ? "" : " AND Consultation_Type = '$Consultation_Type'";
        $filter .= ($Product_Name == "" || $Product_Name == NULL) ? "" : " AND Product_Name LIKE '%$Product_Name%'";

        return data($conn, "SELECT Product_Name, Item_ID FROM tbl_items WHERE Status = 'Available' $filter") or die(mysqli_error($conn));
    }


    //GET MORE SURGICAL ITEMS
    function GetMoreSurgicalItems($conn,$Product_Name,$Sub_Department_ID,$Sponsor_ID){
        $filter = "";
        $filter .= ($Sub_Department_ID == "" || $Sub_Department_ID == NULL) ? "" : " AND itb.Sub_Department_ID = '$Sub_Department_ID'";
        $filter .= ($Sponsor_ID == "" || $Sponsor_ID == NULL) ? "" : " AND itp.Sponsor_ID = '$Sponsor_ID'";
        $filter .= ($Product_Name == "" || $Product_Name == NULL) ? "" : " AND it.Product_Name LIKE '%$Product_Name%'";

        return data($conn, "SELECT it.Product_Name, it.Item_ID, it.Unit_Of_Measure, itb.Item_Balance, itp.Items_Price FROM tbl_items it, tbl_items_balance itb, tbl_item_price itp WHERE it.Status = 'Available' AND it.Can_Be_Stocked = 'yes' AND it.Item_ID = itp.Item_ID AND it.Item_ID = itb.Item_ID AND itp.Items_Price > 0 AND Consultation_Type IN('Pharmacy','Others') $filter ORDER BY it.Item_ID ASC LIMIT 100") or die(mysqli_error($conn));
    }

    //SELECTING MERGED ITEMS
    function GetMergedSurgeries($conn,$Selected_Surgery){
        return data($conn, "SELECT it.Product_Name, sc.Data_ID FROM tbl_surgery_consumables sc, tbl_items it WHERE it.Item_ID = sc.Consumable_ID AND sc.Surgery_ID = '$Selected_Surgery'");
    }

    //GET PATIENT INFOMATION
    function getPatientInfomations($conn,$Registration_ID){
        return data($conn,"SELECT Patient_Name, Date_Of_Birth, Gender,pr.Region, pr.District, Guarantor_Name, sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id, village FROM tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Sponsor_ID = sp.Sponsor_ID and Registration_ID = '$Registration_ID'");
    }

    // GET ALL PHARMACEUTICAL ITEMS DETAILS
    function getAllPharmaceuticalDetails($conn,$Item_ID,$Sponsor_ID,$Sub_Department_ID){
        $x = array();
        $data = mysqli_query($conn, "SELECT it.Product_Name, it.Unit_Of_Measure, itp.Items_Price, it.Item_ID, itb.Item_Balance FROM tbl_items_balance itb, tbl_item_price itp, tbl_items it WHERE itb.Sub_Department_ID = '$Sub_Department_ID' AND it.Item_ID = itb.Item_ID AND it.Item_ID = itp.Item_ID AND itp.Sponsor_ID = '$Sponsor_ID' AND it.Item_ID IN(SELECT Consumable_ID FROM tbl_surgery_consumables WHERE Surgery_ID = '$Item_ID')") or die(mysqli_error($conn));

        while($d = mysqli_fetch_assoc($data)){
            array_push($x,$d);
        }
        return json_encode($x);
    }


    //INSERTING CONSUMABLES
    function insertConsumableCacheList($conn,$Quantity,$Consumable_ID,$Control_Type,$Registration_ID,$Sponsor_ID,$Payment_Item_Cache_List_ID,$Employee_ID,$Sub_Department_ID){

        $Items_Price = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Items_Price FROM tbl_item_price WHERE Item_ID = '$Consumable_ID' AND Sponsor_ID = '$Sponsor_ID'"))['Items_Price'];
            if($Items_Price != 0 || $Items_Price != NULL){
                $Control_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Control_ID FROM tbl_consumable_control WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Registration_ID = '$Registration_ID'"))['Control_ID'];
                    if(($Control_ID)>0){
                        $Items_Control_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Items_Control_ID FROM tbl_consumable_control_items WHERE Control_ID = '$Control_ID' AND Item_ID = '$Consumable_ID'"))['Items_Control_ID'];
                            if($Items_Control_ID>0){
                                return data($conn, "UPDATE tbl_consumable_control_items SET Quantity = '$Quantity', Price = '$Items_Price' WHERE Items_Control_ID = '$Items_Control_ID'");
                            }else{
                                return data($conn, "INSERT INTO tbl_consumable_control_items(Quantity, Price, Item_ID, Control_ID, Date_Time) VALUES('$Quantity', '$Items_Price', '$Consumable_ID', '$Control_ID', NOW())");
                            }
                    }else{
                        $Insert_Control = mysqli_query($conn, "INSERT INTO tbl_consumable_control (Payment_Item_Cache_List_ID, Registration_ID, Control_Type, Date_Time, Employee_ID, Sub_Department_ID) VALUES('$Payment_Item_Cache_List_ID', '$Registration_ID', '$Control_Type', NOW(), '$Employee_ID','$Sub_Department_ID')");
                            if($Insert_Control){
                                $Control_ID = mysqli_insert_id($conn);
                                return data($conn, "INSERT INTO tbl_consumable_control_items(Quantity, Price, Item_ID, Control_ID, Date_Time) VALUES('$Quantity', '$Items_Price', '$Consumable_ID', '$Control_ID', NOW())");
                            }
                    }
            }else{
                echo "FAILED";
            }
    }

    function GetTotalConsumableCost($conn,$Payment_Item_Cache_List_ID){
        return data($conn, "SELECT cc.Control_ID, (Quantity*Price) AS TotalCost FROM tbl_consumable_control cc, tbl_consumable_control_items cci WHERE cc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND cc.Control_ID = cci.Control_ID");
    }
?>