<?php
include("Surgery.Mode.php");
    $Action = (isset($_GET['Action'])) ? $_GET['Action'] : 0;
    $Data_ID = (isset($_GET['Data_ID'])) ? $_GET['Data_ID'] : 0;
    $Item_ID = (isset($_GET['Item_ID'])) ? $_GET['Item_ID'] : 0;
    $Store_ID = (isset($_GET['Store_ID'])) ? $_GET['Store_ID'] : 0;
    $Theater_ID = (isset($_GET['Theater_ID'])) ? $_GET['Theater_ID'] : 0;
    $Sponsor_ID = (isset($_GET['Sponsor_ID'])) ? $_GET['Sponsor_ID'] : 0;
    $Employee_ID = (isset($_GET['Employee_ID'])) ? $_GET['Employee_ID'] : 0;
    $Quantity = (isset($_GET['Quantity'])) ? $_GET['Quantity'] : 0;
    $Product_Name = (isset($_GET['Product_Name'])) ? $_GET['Product_Name'] : 0;
    $Control_Type = (isset($_GET['Control_Type'])) ? $_GET['Control_Type'] : 0;
    $Consumable_ID = (isset($_GET['Consumable_ID'])) ? $_GET['Consumable_ID'] : 0;
    $Attachement_ID = (isset($_GET['Attachement_ID'])) ? $_GET['Attachement_ID'] : 0;
    $Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
    $Document_Number = (isset($_GET['Document_Number'])) ? $_GET['Document_Number'] : 0;
    $Selected_Surgery = (isset($_GET['Selected_Surgery'])) ? $_GET['Selected_Surgery'] : 0;
    $Sub_Department_ID = (isset($_GET['Sub_Department_ID'])) ? $_GET['Sub_Department_ID'] : 0;
    $Consultation_Type = (isset($_GET['Consultation_Type'])) ? $_GET['Consultation_Type'] : 0;

    $Payment_Item_Cache_List_ID = (isset($_GET['Payment_Item_Cache_List_ID'])) ? $_GET['Payment_Item_Cache_List_ID'] : 0;
    
    if($Employee_ID != 0 && $Theater_ID != 0 && $Store_ID != 0){
        return InsertSubDepartmentMerging($conn,$Store_ID,$Theater_ID,$Employee_ID);
    }
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
    $This_date_today = $Today." 00:00";
}


    if($Action == 'Merge Item' && $Selected_Surgery != 0 && $Employee_ID != 0 && $Item_ID != 0){
        $datas = json_decode(InsertItemMerging($conn,$Selected_Surgery,$Item_ID,$Employee_ID));
    }


    if($Action == 'Fetch Merged' && $Selected_Surgery != 0){
        $Merged_Surgery = json_decode(GetMergedSurgeries($conn,$Selected_Surgery), true);
        $num = 1;
        if(sizeof($Merged_Surgery) > 0){
            foreach($Merged_Surgery as $Items):
                $Item_Name = $Items['Product_Name'];
                $Data_ID = $Items['Data_ID'];

                echo "<tr>
                <td>".$num."</td>
                <td>".$Item_Name."</td>
                <td><input type='button' class='art-button-green' value='X' onclick='Remove_Item(".$Data_ID.")'></tr>";
                $num++;
            endforeach;
            
        }else{
            echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='3'>NO ITEM MERGED WITH THIS SURGERY</td></tr>";
        }
    }

    // $Surgical = json_decode(GetSurgicalItems($conn,$Consultation_Type,$Product_Name), true);


    if($Action == 'Search' && $Consultation_Type != ""){
        $Items =  json_decode(GetItemsForSurgery($conn,$Consultation_Type,$Product_Name),true);

        if(sizeof($Items) > 0){
            foreach($Items as $Items):
                $Item_Name = $Items['Product_Name'];
                $Item_ID = $Items['Item_ID'];

                echo "<tr>
                        <td><input type='radio' onclick='Add_Item(".$Item_ID.")'></td>";
                echo "<td>".$Item_Name."</td></tr>";
            endforeach;
            
        }else{
            echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='2'>NO ITEM FOUND WITH NAME ".$Product_Name."</td></tr>";
        }
    }

    if($Action == 'Get More Items' && $Sub_Department_ID != 0 && $Sponsor_ID != 0){
        // $Items_Loading =  json_decode(GetMoreSurgicalItems($conn,$Product_Name,$Sub_Department_ID,$Sponsor_ID));
        $filter = "";
        $filter .= ($Sub_Department_ID == "" || $Sub_Department_ID == NULL) ? "" : " AND itb.Sub_Department_ID = '$Sub_Department_ID'";
        $filter .= ($Sponsor_ID == "" || $Sponsor_ID == NULL) ? "" : " AND itp.Sponsor_ID = '$Sponsor_ID'";
        $filter .= ($Product_Name == "" || $Product_Name == NULL) ? "" : " AND it.Product_Name LIKE '%$Product_Name%'";

        $Items_Loading = mysqli_query($conn, "SELECT it.Product_Name, it.Item_ID, it.Unit_Of_Measure, itb.Item_Balance, itp.Items_Price FROM tbl_items it, tbl_items_balance itb, tbl_item_price itp WHERE it.Status = 'Available' AND it.Can_Be_Stocked = 'yes' AND it.Item_ID = itp.Item_ID AND it.Item_ID = itb.Item_ID AND itp.Items_Price > 0 AND Consultation_Type IN('Pharmacy','Other') $filter ORDER BY it.Item_ID ASC LIMIT 100") or die(mysqli_error($conn));

        if(mysqli_num_rows($Items_Loading) > 0){
            while($dts = mysqli_fetch_assoc($Items_Loading)){
                $Item_Name = $dts['Product_Name'];
                $Item_Balance = $dts['Item_Balance'];
                $Item_ID = $dts['Item_ID'];
                $Items_Price = $dts['Items_Price'];
                $Unit_Of_Measure = $dts['Unit_Of_Measure'];

                echo "<tr>
                        <td><input type='radio' onclick='Update_Quantity(".$Item_ID.")'></td>";
                echo "<td>".$Item_Name."</td>
                        <td>".$Unit_Of_Measure."</td>
                        <td style=' text-align: right;'><input type='text' id='Balance".$Item_ID."' value='".$Item_Balance."' disabled='disabled' style='text-align: right; border: none;'></td>
                        <td style=' text-align: right;'>".number_format($Items_Price)."</td></tr>";
            }
        }else{
            echo "<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='5'>NO ITEM FOUND WITH NAME ".strtoupper($Product_Name)."</td></tr>";
        }
    }

    if($Attachement_ID != 0){
        return RemoveSubDepartmentMerging($conn,$Attachement_ID);
    }

    if($Action == 'Fetch Patient' && $Registration_ID != 0){
        $Patient = json_decode(getPatientInfomations($conn,$Registration_ID), true);
        // print_r($Patient);
            if(sizeof($Patient) > 0){
                foreach($Patient AS $dt):
                    $Date_Of_Birth = $dt['Date_Of_Birth'];
                    $Patient_Name = $dt['Patient_Name'];
                    $Gender = $dt['Gender'];
                    $Guarantor_Name = $dt['Guarantor_Name'];
                    $Region = $dt['Region'];
                    $District = $dt['District'];
                    $Sponsor_ID = $dt['Sponsor_ID'];

                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($dt['Date_Of_Birth']);
                    $diff = $date1 -> diff($date2);
                    $age = $diff->y." Years, ";
                    $age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";
                    echo "<tr>
                                <td>".$Patient_Name."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$age."</td>
                                <td>".$Gender."</td>
                                <td>".$Guarantor_Name."</td>
                                <td>".$Region."/".$District."</td></tr></tbody>";
                endforeach;
            }

            // echo $Patient_Info;
    }

    if($Action == 'Fetch Medicine Attached' && $Sponsor_ID != 0 && $Item_ID != 0){
        $num = 1;
        // $List_Items = '';
        $Select_All_Items = json_decode(getAllPharmaceuticalDetails($conn,$Item_ID,$Sponsor_ID,$Sub_Department_ID), true);
        // print_r($Select_All_Items);
        $total = sizeof($Select_All_Items);
            if(sizeof($Select_All_Items) > 0){
        $track = 1;

                foreach($Select_All_Items AS $Itm):
                    $Product_Name = $Itm['Product_Name'];
                    $Unit_Of_Measure = $Itm['Unit_Of_Measure'];
                    $Items_Price = $Itm['Items_Price'];
                    $Item_Balance = $Itm['Item_Balance'];
                    $Service = $Itm['Item_ID'];

                    if($Item_Balance < 1){
                        $Item_Balance = 'NO BALANCE';
                    }

                    $Select_balance = mysqli_query($conn, "SELECT cci.Quantity, cci.Price FROM tbl_consumable_control cc, tbl_consumable_control_items cci WHERE cc.Control_ID = cci.Control_ID AND cc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND cci.Item_ID = '$Service'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($Select_balance)>0){
                            while($bal = mysqli_fetch_assoc($Select_balance)){
                                $Quantity = $bal['Quantity'];
                                $Price = $bal['Price'];

                                $Total = $Quantity * $Price;
                            }
                        }else{
                            $Quantity = 0;
                            $Price = $Items_Price;

                            $Total = $Quantity * $Price;
                        }

                    echo "<tr>
                            <td>".$num."</td>
                            <td>".$Product_Name."</td>
                            <td>".$Unit_Of_Measure."</td>
                            <td><input type='text' id='Balance".$Service."' value='".$Item_Balance."' disabled='disabled' style='text-align: right; border: none;'></td>
                            <td><input type='text' id='Price".$Service."' value='".$Items_Price."' disabled='disabled' style='text-align: right; border: none;'></td>
                            <td><input type='text' onkeyup='Update_Quantity(".$Service.")' placeholder='Enter Quantity'  value='".$Quantity."' id='Quantity".$Service."'></td>
                            <td><input type='text' id='Sub_Total".$Service."' placeholder='Sub Total' disabled='disabled' value='".number_format($Total)."' style='text-align: right; border: none;'></td></tr>";

                    // $List_Items .= $Service;
                    // if($total > 1){

                    // }
                    if ($total == 1) {
                        $List_Items = $Service;
                    } else {
                        if ($track < $total) {
                            $List_Items .=$Service . ',  ';
                        } else {
                            $List_Items .=$Service;
                        }
                    }
                    $num++;
            $track++;

                endforeach;

                // $All_Items = $List_Items;
                
                $Select_Remained_data = mysqli_query($conn, "SELECT cci.Item_ID, cci.Quantity, cci.Price, it.Product_Name, it.Unit_Of_Measure, itb.Item_Balance FROM tbl_consumable_control cc, tbl_consumable_control_items cci, tbl_items it, tbl_items_balance itb WHERE cc.Control_ID = cci.Control_ID AND cc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND it.Item_ID = cci.Item_ID AND itb.Item_ID = cci.Item_ID AND itb.Sub_Department_ID = '$Sub_Department_ID' AND cci.Item_ID NOT IN($List_Items)") or die(mysqli_error($conn));
                if(mysqli_num_rows($Select_Remained_data)>0){
                    while($list = mysqli_fetch_assoc($Select_Remained_data)){
                        $Product_Name_new = $list['Product_Name'];
                        $Unit_Of_Measure_new = $list['Unit_Of_Measure'];
                        $Price_new = $list['Price'];
                        $Item_Balance = $list['Item_Balance'];
                        $Item_ID = $list['Item_ID'];
                        $Quantity_new = $list['Quantity'];
    

                        $Total_new = $Quantity_new * $Price_new;

                        echo "<tr>
                                <td>".$num."</td>
                                <td>".$Product_Name_new."</td>
                                <td>".$Unit_Of_Measure_new."</td>
                                <td><input type='text' id='Balance".$Item_ID."' value='".$Item_Balance."' disabled='disabled' style='text-align: right; border: none;'></td>
                                <td><input type='text' id='Price".$Item_ID."' value='".$Price_new."' disabled='disabled' style='text-align: right; border: none;'></td>
                                <td><input type='text' onkeyup='Update_Quantity(".$Item_ID.")' placeholder='Enter Quantity'  value='".$Quantity_new."' id='Quantity".$Item_ID."'></td>
                                <td><input type='text' id='Sub_Total".$Item_ID."' placeholder='Sub Total' disabled='disabled' value='".number_format($Total_new)."' style='text-align: right; border: none;'></td></tr>";

                        // $Total = $Quantity * $Price;
                    $num++;

                    }
                }

            }
    }

    if($Action == 'Send Items' && $Payment_Item_Cache_List_ID != 0 && $Sponsor_ID != 0 && $Item_ID != 0 && $Consumable_ID != 0 && $Quantity != 0){
        $Insert_Consumables = json_decode(insertConsumableCacheList($conn,$Quantity,$Consumable_ID,$Control_Type,$Registration_ID,$Sponsor_ID,$Payment_Item_Cache_List_ID,$Employee_ID,$Sub_Department_ID), true);
    }

    if($Action == 'Get Total' && $Payment_Item_Cache_List_ID != 0){
        // $Total_Consumable = '';
        $Get_Total_Cost = json_decode(GetTotalConsumableCost($conn,$Payment_Item_Cache_List_ID), true);
            if(sizeof($Get_Total_Cost)>0){
                foreach($Get_Total_Cost AS $Total):
                    $Total_Cost = $Total['TotalCost'];
                    $Control_ID = $Total['Control_ID'];


                    $Total_Consumable += $Total_Cost;
                endforeach;
                echo "<table><tr>
                <td style='text-align: right;'><input type='button' class='art-button-green' value='ADD MISSING CONSUMABLES' style='padding: 10px 20px; border-radius: 10px; font-size: 15px; font-weight: bold;' onclick='Add_Items()'><input type='button' class='art-button-green' value='SUBMIT CONSUMABLES' style='padding: 10px 20px; border-radius: 10px; font-size: 15px; font-weight: bold;' onclick='Submit_Consumable()'></td> 
                <td style='text-align: right;'><h3>TOTAL CONSUMABLE COST : ".number_format($Total_Consumable, 2)."</h3><input type='text' style='display: none' id='Control_ID' value='".$Control_ID."'></td>
            </td>
            </table>";
            }
    }


    if($Action == 'Check Details' && $Payment_Item_Cache_List_ID != 0){
        $result = array();
            $Select_SubDepartment = mysqli_query($conn, "SELECT Quantity, Item_ID FROM tbl_consumable_control cc, tbl_consumable_control_items cci WHERE cc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND cc.Control_ID = cci.Control_ID AND cci.Quantity > 0") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_SubDepartment)>0){
                while($details = mysqli_fetch_assoc($Select_SubDepartment)){
                    array_push($result,$details);
                }
                echo json_encode($result);
            }
    
    }


    if($Action == 'Submit Document' && $Document_Number != 0){
        $Update_contol = mysqli_query($conn, "UPDATE tbl_consumable_control SET Control_Status = 'Dispensed' WHERE Control_ID = '$Document_Number'") or die(mysqli_error($conn));
            if($Update_contol){
                $Update_contol_items = mysqli_query($conn, "UPDATE tbl_consumable_control_items SET Control_Status = 'Dispensed' WHERE Control_ID = '$Document_Number' AND Quantity > 0") or die(mysqli_error($conn));

                echo 200;
            }else{
                echo 201;
            }
    }

    if($Action == 'Remove Surgery' && $Data_ID != 0){
        $Delete_Data = mysqli_query($conn, "DELETE FROM tbl_surgery_consumables WHERE Data_ID = '$Data_ID'") or die(mysqli_error($conn));
            if($Delete_Data){
                echo 200;
            }else{
                echo 201;
            }
    }

?>
