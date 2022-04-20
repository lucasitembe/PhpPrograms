<?php
    //include_once("./includes/connection.php");
    require_once ("database.php");
    require_once ("stockledger.php");

    function Get_Item_Classification() {
        return array(
            array("Name" => "Pharmaceuticals","Description" => "Pharmaceuticals"),
            array("Name" => "Disposables","Description" => "Disposables"),
            array("Name" => "Dental Materials","Description" => "Dental Materials"),
            array("Name" => "Radiology Materials","Description" => "Radiology Materials"),
            array("Name" => "Laboratory Materials","Description" => "Laboratory Materials"),
            array("Name" => "Stationaries","Description" => "Stationaries")
        );
    }

    function Get_Item_Consultation_Type() {
        return array(
            array("Name" => "Pharmacy","Description" => "Pharmacy"),
            array("Name" => "Laboratory","Description" => "Laboratory"),
            array("Name" => "Radiology","Description" => "Radiology"),
            array("Name" => "Surgery","Description" => "Surgery"),
            array("Name" => "Procedure","Description" => "Procedure"),
            array("Name" => "Optical","Description" => "Optical"),
            array("Name" => "Others","Description" => "Others")
        );
    }

    function Get_Item_Type() {
        return array(
            array("Name" => "Service","Description" => "Serviced Item"),
            array("Name" => "Pharmacy","Description" => "Pharmaceutical Item"),
            array("Name" => "Others","Description" => "Other Item")
        );
    }

    function Get_Unit_Of_Measure() {
        return array(
            array("Name" => "btl","Description" => "Btl"),
            array("Name" => "pc","Description" => "PC"),
            array("Name" => "vial","Description" => "Vial"),
            array("Name" => "tube","Description" => "Tube"),
            array("Name" => "kit","Description" => "KIT"),
            array("Name" => "nill","Description" => "NILL"),
            array("Name" => "box","Description" => "Box"),
            array("Name" => "cap","Description" => "Cap"),
            array("Name" => "tab","Description" => "Tab"),
            array("Name" => "tin","Description" => "Tin"),
            array("Name" => "LIST519","Description" => "LIST519"),
            array("Name" => "amp","Description" => "amp"),
            array("Name" => "bag","Description" => "Bag"),
            array("Name" => "vl","Description" => "VL"),
            array("Name" => "pkt","Description" => "Pkt"),
            array("Name" => "caps","Description" => "Caps"),
            array("Name" => "supp","Description" => "Supp"),
            array("Name" => "tabs","Description" => "Tabs"),
            array("Name" => "25g","Description" => "25g"),
            array("Name" => "ltr","Description" => "ltr"),
            array("Name" => "pair","Description" => "Pair"),
            array("Name" => "pct","Description" => "pct"),
            array("Name" => "20lt","Description" => "20lt"),
            array("Name" => "disc","Description" => "Disc"),
            array("Name" => "100","Description" => "100"),
            array("Name" => "ctn","Description" => "ctn"),
            array("Name" => "roll","Description" => "roll"),
            array("Name" => "25","Description" => "25"),
            array("Name" => "doz","Description" => "doz"),
            array("Name" => "each","Description" => "each"),
            array("Name" => "soln","Description" => "soln"),
            array("Name" => "inj","Description" => "inj"),
            array("Name" => "mt","Description" => "mt"),
            array("Name" => "stchts","Description" => "stchts"),
            array("Name" => "Pieces","Description" => "Pieces")
        );
    }

    function Get_Item($Item_ID) {
        $Item = array();

        $Item_Result = Get_From("tbl_items", array("Item_ID", "=", $Item_ID), array(), 1);
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item = $Item_Result["data"][0];
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_By_Name_Code($Product_Name, $Product_Code) {
        $Item = array();

        $Product_Name = str_replace("'%'", " ", $Product_Name);
        $Product_Name = str_replace("'x12'", " ", $Product_Name);
        $Product_Name = str_replace("Lugol's", "Lugol s", $Product_Name);

        $Product_Name = Prepare_For_Like_Operator($Product_Name);
        //$Product_Name = mysqli_real_escape_string($conn,$Product_Name);
        //$Product_Code = mysqli_real_escape_string($conn,$Product_Code);

        $Item_Result = Get_From("tbl_items", array("Product_Name", "like", $Product_Name), array("Product_Code", "=", $Product_Code), 1);
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item = $Item_Result["data"][0];
            }
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_Balance($Item_ID, $Sub_Department_ID) {
        $Item = array();

        $Item_Result = Get_From("tbl_items_balance", array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID), 1);
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item = $Item_Result["data"][0];
            } else {
                $Insert_Item_Balance = Insert_DB("tbl_items_balance", array(
                    "Item_Balance" => 0,
                    "Item_ID" => $Item_ID,
                    "Sub_Department_ID" => $Sub_Department_ID
                ));

                $hasError = $Insert_Item_Balance["error"];
                if ($hasError) {
                    $Item_Result = Get_From("tbl_items_balance", array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID), 1);
                    $hasError = $Item_Result["error"];
                    if (!$hasError) {
                        if (!empty($Item_Result["data"])) {
                            $Item = $Item_Result["data"][0];
                        }
                    } else {
                        echo $Item_Result["errorMsg"];
                    }
                } else {
                    echo $Insert_Item_Balance['errorMsg'];
                }
            }
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_Balance_By_All_Classification($Sub_Department_ID, $Product_Name, $limit = 10) {
        $Item_Balance_List = array();

        $limit_statement = "";
        if ($limit > 0) {
            $limit_statement = "LIMIT {$limit}";
        }

        $product_name_statement = "";
        if ($Product_Name != "") {
            $Product_Name = Prepare_For_Like_Operator($Product_Name);
            $product_name_statement = "AND t.Product_Name like '{$Product_Name}'";
        }

        $Item_Balance_Result = Query_DB("SELECT Product_Name, t.Item_ID, t.Unit_Of_Measure,

                                           IFNULL((SELECT Buying_Price FROM tbl_purchase_order_items poi WHERE t.Item_ID = poi.Item_ID
                                           AND Grn_Status = 'RECEIVED' ORDER BY Order_Item_ID DESC LIMIT 1), '0') as Last_Buying_Price,

                                           IFNULL((SELECT ib.Item_Balance FROM tbl_items_balance ib
                                           WHERE ib.Sub_Department_ID = '{$Sub_Department_ID}' AND ib.Item_ID = t.Item_ID), '0') as Item_Balance

                                           FROM tbl_items t
                                           WHERE Classification in ('Pharmaceuticals', 'Dental Materials',
                                                            'Disposables', 'Laboratory Materials',
                                                            'Radiology Materials', 'Stationaries')

                                           {$product_name_statement}
                                           ORDER BY Product_Name
                                           {$limit_statement}");

        $hasError = $Item_Balance_Result["error"];
        if (!$hasError) {
            $Item_Balance_List = array_merge($Item_Balance_List, $Item_Balance_Result["data"]);
        } else {
            echo $Item_Balance_Result["errorMsg"];
        }

        return $Item_Balance_List;
    }

    function Get_Item_Balance_By_Classification($Classification, $Sub_Department_ID, $Product_Name, $limit = 10) {
        $Item_Balance_List = array();

        $limit_statement = "";
        if ($limit > 0) {
            $limit_statement = "LIMIT {$limit}";
        }

        $product_name_statement = "";
        if ($Product_Name != "") {
            $Product_Name = Prepare_For_Like_Operator($Product_Name);
            $product_name_statement = "AND t.Product_Name like '{$Product_Name}'";
        }

        $Item_Balance_Result = Query_DB("SELECT Product_Name, t.Item_ID, t.Unit_Of_Measure,

                                           IFNULL((SELECT Buying_Price FROM tbl_purchase_order_items poi WHERE t.Item_ID = poi.Item_ID
                                           AND Grn_Status = 'RECEIVED' ORDER BY Order_Item_ID DESC LIMIT 1), '0') as Last_Buying_Price,

                                           IFNULL((SELECT ib.Item_Balance FROM tbl_items_balance ib
                                           WHERE ib.Sub_Department_ID = '{$Sub_Department_ID}' AND ib.Item_ID = t.Item_ID), '0') as Item_Balance

                                           FROM tbl_items t
                                           WHERE Classification = '{$Classification}'
                                           {$product_name_statement}
                                           ORDER BY Product_Name
                                           {$limit_statement}");

        $hasError = $Item_Balance_Result["error"];
        if (!$hasError) {
            $Item_Balance_List = array_merge($Item_Balance_List, $Item_Balance_Result["data"]);
        } else {
            echo $Item_Balance_Result["errorMsg"];
        }

        return $Item_Balance_List;
    }

    function Get_Stock_Item_By_Classification($Classification, $Product_Name, $limit = 10) {
        $Item_Balance_List = array();

        $limit_statement = "";
        if ($limit > 0) {
            $limit_statement = "LIMIT {$limit}";
        }

        $product_name_statement = "";
        if ($Product_Name != "") {
            $Product_Name = Prepare_For_Like_Operator($Product_Name);
            $product_name_statement = "AND t.Product_Name like '{$Product_Name}'";
        }

        $Classification_Statement = "";
        if ($Classification != "" && strtolower($Classification) != "all") {
            $Classification_Statement = "AND t.Classification = '{$Classification}'";
        }

        $Item_Balance_Result = Query_DB("SELECT t.Product_Name, t.Item_ID, t.Unit_Of_Measure, t.Product_Code
                                               FROM tbl_items t
                                               WHERE t.Can_Be_Stocked = 'yes'
                                               {$Classification_Statement}
                                               {$product_name_statement}
                                               ORDER BY Product_Name
                                               {$limit_statement}");

        $hasError = $Item_Balance_Result["error"];
        if (!$hasError) {
            $Item_Balance_List = array_merge($Item_Balance_List, $Item_Balance_Result["data"]);
        } else {
            echo $Item_Balance_Result["errorMsg"];
        }

        return $Item_Balance_List;
    }

    function Update_Item_Balance($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination,
                                 $External_Source, $Registration_ID, $Document_Number, $Document_Date, $Quantity, $Add_Quantity){
        $Previous_Item_Balance = Get_From("tbl_items_balance", array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID), 1);
        $hasError = $Previous_Item_Balance["error"];
        if (!$hasError) {
           // echo $Previous_Item_Balance["count"];
            if ($Previous_Item_Balance["count"] > 0) {
                $Previous_Balance = $Previous_Item_Balance["data"][0]["Item_Balance"];
            } else {
                $Insert_Item_Balance = Insert_DB("tbl_items_balance", array( "Item_ID" => $Item_ID, "Sub_Department_ID" => $Sub_Department_ID));
                $hasError = $Insert_Item_Balance["error"];
                if ($hasError) {
                    echo $Insert_Item_Balance["errorMsg"];
                    return false;
                }
                $Previous_Balance = 0;
            }

            if ($Add_Quantity) {
                /**if ($Previous_Balance < 0) { allow negative balance
                    return false;
                }**/
                $Post_Balance = array("Item_Balance", "+", $Quantity);
                $Post_Bal = $Previous_Balance + $Quantity;
            } else {
                /**if ($Quantity > $Previous_Balance) { allow negative balance
                    return false;
                }**/
                $Post_Balance = array("Item_Balance", "-", $Quantity);
                $Post_Bal = $Previous_Balance - $Quantity;
            }

            $Item_Balance_Update = Update_DB("tbl_items_balance",
                array("Item_Balance" => $Post_Balance),
                array("Item_ID", "=", $Item_ID),
                array("Sub_Department_ID", "=", $Sub_Department_ID));

            $hasError = $Item_Balance_Update["error"];
            if (!$hasError) {
                $Stock_Ledger_Record = Get_Stock_Ledger_Record($Item_ID, $Sub_Department_ID, $Document_Number, $Movement_Type);
                if (!empty($Stock_Ledger_Record)) {
                    return Update_Stock_Ledger($Previous_Balance, $Post_Bal, $Stock_Ledger_Record);
                } else {
                    return Create_Stock_Ledger($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination,
                                $External_Source, $Registration_ID, $Document_Number, $Document_Date, $Previous_Balance, $Post_Bal);
                }
            } else {
                echo $Item_Balance_Update["errorMsg"];
                return false;
            }

        } else {
            echo $Previous_Item_Balance["errorMsg"];
            return false;
        }
    }

    function Get_Item_Last_Buying_Price_With_Supplier($Item_ID){
        $List_Of_Last_Buying_Price = array();
        $List_GRN_Without_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID);
        $List_GRN_With_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID);
        if (!empty($List_GRN_Without_PO)) {
            foreach($List_GRN_Without_PO as $GRN_Without_PO) {
                $Last_Buying_Price = array("Supplier_ID"=>$GRN_Without_PO["Supplier_ID"],
                                            "Supplier_Name"=>$GRN_Without_PO["Supplier_Name"],
                                            "Grn_ID"=>$GRN_Without_PO["Grn_ID"],
                                            "Grn_Date"=>$GRN_Without_PO["Grn_Date_And_Time"],
                                            "Buying_Price"=>$GRN_Without_PO["Price"],
                                            "Grn_Type" => "GRN Without PO");
                $List_Of_Last_Buying_Price = array_merge($List_Of_Last_Buying_Price, array($Last_Buying_Price));
            }

        }

        if (!empty($List_GRN_With_PO)) {
            foreach($List_GRN_With_PO as $GRN_With_PO) {
                $Last_Buying_Price = array("Supplier_ID"=>$GRN_With_PO["supplier_id"],
                    "Supplier_Name"=>$GRN_With_PO["Supplier_Name"],
                    "Grn_ID"=>$GRN_With_PO["Grn_Purchase_Order_ID"],
                    "Grn_Date"=>$GRN_With_PO["Created_Date_Time"],
                    "Buying_Price"=>$GRN_With_PO["Buying_Price"],
                    "Grn_Type" => "GRN With PO");
                $List_Of_Last_Buying_Price = array_merge($List_Of_Last_Buying_Price, array($Last_Buying_Price));
            }

        }

        if (!empty($List_Of_Last_Buying_Price)) {
            usort($List_Of_Last_Buying_Price, function($a, $b) {
                return $a['Grn_Date'] - $b['Grn_Date'];
            });

            //This is to remove duplicates
            $Final_List_Of_Last_Buying_Price = array();
            $List_Of_Supplier_ID = array();
            foreach ($List_Of_Last_Buying_Price as $Last_Buying_Price) {
                if(!in_array($Last_Buying_Price['Supplier_ID'], $List_Of_Supplier_ID)) {
                    $Final_List_Of_Last_Buying_Price = array_merge($Final_List_Of_Last_Buying_Price, array($Last_Buying_Price));
                    $List_Of_Supplier_ID = array_merge($List_Of_Supplier_ID, array($Last_Buying_Price['Supplier_ID']));
                }
            }
            return $Final_List_Of_Last_Buying_Price;
        } else {
            return array();
        }
    }

    function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID){
        $Item = array();

        $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID);
        foreach($Supplier_List as $Supplier) {
            $Item_Result = Query_DB("SELECT gpo.supplier_id, poi.Buying_Price, gpo.Grn_Purchase_Order_ID, gpo.Created_Date_Time, s.Supplier_Name
                                    FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo, tbl_supplier s
                                    WHERE poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                    AND poi.Item_ID = {$Item_ID}
                                    AND gpo.Supplier_ID = s.Supplier_ID
                                    AND gpo.Supplier_ID = {$Supplier['Supplier_ID']}
                                    ORDER BY gpo.Created_Date_Time DESC
                                    limit 1");
            $hasError = $Item_Result["error"];
            if (!$hasError) {
                if (!empty($Item_Result["data"])) {
                    $Item = array_merge($Item, $Item_Result["data"]);
                }
            } else {
                echo $Item_Result["errorMsg"];
            }
        }

        return $Item;
    }

    function Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID){
        $Item = array();

        $Item_Result = Query_DB("SELECT gpo.Supplier_ID, s.Supplier_Name
                                    FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo, tbl_supplier s
                                    WHERE poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                    AND poi.Item_ID = {$Item_ID}
                                    AND gpo.Supplier_ID = s.Supplier_ID
                                    GROUP BY gpo.Supplier_ID");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item = array_merge($Item, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID){
        $Item = array();

        $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_Without_PO($Item_ID);
        foreach($Supplier_List as $Supplier) {
            $Item_Result = Query_DB("SELECT gwpo.Supplier_ID, gwpoi.Price, gwpo.Grn_ID, gwpo.Grn_Date_And_Time, s.Supplier_Name
                                     FROM tbl_grn_without_purchase_order_items gwpoi, tbl_grn_without_purchase_order gwpo, tbl_supplier s
                                     WHERE gwpoi.Grn_ID = gwpo.Grn_ID
                                     AND gwpoi.Item_ID = {$Item_ID}
                                     AND gwpo.Supplier_ID = s.Supplier_ID
                                     AND gwpo.Supplier_ID = {$Supplier['Supplier_ID']}
                                     ORDER BY gwpo.Grn_Date_And_Time DESC
                                     LIMIT 1");
            $hasError = $Item_Result["error"];
            if (!$hasError) {
                if (!empty($Item_Result["data"])) {
                    $Item = array_merge($Item, $Item_Result["data"]);
                }
            } else {
                echo $Item_Result["errorMsg"];
            }
        }

        return $Item;
    }

    function Get_List_Of_Supplier_For_Item_In_GRN_Without_PO($Item_ID){
        $Item = array();

        $Item_Result = Query_DB("SELECT gwpo.Supplier_ID, s.Supplier_Name
                                 FROM tbl_grn_without_purchase_order_items gwpoi, tbl_grn_without_purchase_order gwpo, tbl_supplier s
                                 WHERE gwpoi.Grn_ID = gwpo.Grn_ID
                                 AND gwpoi.Item_ID = {$Item_ID}
                                 AND gwpo.Supplier_ID = s.Supplier_ID
                                 GROUP BY gwpo.Supplier_ID");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item = array_merge($Item, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_Purchase_History($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0){
        $Item_Purchase_History_From_GRN_With_PO = Get_Item_Purchase_History_From_GRN_With_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit);
        $Item_Purchase_History_From_GRN_Without_PO = Get_Item_Purchase_History_From_GRN_Without_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit);
        $Item_Purchase_History = array();

        if (!empty($Item_Purchase_History_From_GRN_Without_PO)) {
            foreach($Item_Purchase_History_From_GRN_Without_PO as $Purchase_History) {
                $Item_Purchase_History = array_merge($Item_Purchase_History, array(array(
                    "Item_ID"=>$Purchase_History["Item_ID"],
                    "Product_Name"=>$Purchase_History["Product_Name"],
                    "Product_Code"=>$Purchase_History["Product_Code"],
                    "Unit_Of_Measure"=>$Purchase_History["Unit_Of_Measure"],
                    "Classification"=>$Purchase_History["Classification"],
                    "Supplier_ID"=>$Purchase_History["Supplier_ID"],
                    "Supplier_Name"=>$Purchase_History["Supplier_Name"],
                    "Buying_Price"=>$Purchase_History["Price"],
                    "Quantity"=>$Purchase_History["Quantity_Required"],
                    "Purchase_Date"=>$Purchase_History["Grn_Date_And_Time"],
                    "Grn_ID"=>$Purchase_History["Grn_ID"],
                    "Type" => "GRN Without PO"
                )));
            }
        } if (!empty($Item_Purchase_History_From_GRN_With_PO)) {
            foreach($Item_Purchase_History_From_GRN_With_PO as $Purchase_History) {
                $Item_Purchase_History = array_merge($Item_Purchase_History, array(array(
                    "Item_ID"=>$Purchase_History["Item_ID"],
                    "Product_Name"=>$Purchase_History["Product_Name"],
                    "Product_Code"=>$Purchase_History["Product_Code"],
                    "Classification"=>$Purchase_History["Classification"],
                    "Unit_Of_Measure"=>$Purchase_History["Unit_Of_Measure"],
                    "Supplier_ID"=>$Purchase_History["supplier_id"],
                    "Supplier_Name"=>$Purchase_History["Supplier_Name"],
                    "Buying_Price"=>$Purchase_History["Buying_Price"],
                    "Quantity"=>$Purchase_History["Quantity_Received"],
                    "Purchase_Date"=>$Purchase_History["Created_Date_Time"],
                    "Grn_ID"=>$Purchase_History["Grn_Purchase_Order_ID"],
                    "Type" => "GRN With PO"
                )));
            }
        }

        if (!empty($Item_Purchase_History)) {
            usort($Item_Purchase_History, function($a, $b) {
                return $a['Purchase_Date'] - $b['Purchase_Date'];
            });
            return $Item_Purchase_History;
        } else {
            return array();
        }
    }

    function Get_Item_Purchase_History_From_GRN_With_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0){
        $Item_Purchase_History = array();

        $Start_End_Date_Statement = "";
        if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != ""){
            $Start_Date = Get_Day_Beginning($Start_Date);
            $End_Date = Get_Day_Ending($End_Date);
            $Start_End_Date_Statement = "AND gpo.Created_Date_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Item_ID_Statement = "";
        if ($Item_ID > 0 && $Item_ID != null) {
            $Item_ID_Statement = "AND poi.Item_ID = {$Item_ID}";
        }

        $Supplier_ID_Statement = "";
        if ($Supplier_ID > 0 && $Supplier_ID != null) {
            $Supplier_ID_Statement = "AND gpo.supplier_id = {$Supplier_ID}";
        }

        $Classification_Statement = "";
        if ($Classification != "" && strtolower($Classification) != "all") {
            $Classification_Statement = "AND i.Classification = '{$Classification}'";
        }

        $Item_Purchase_History_Result = Query_DB("SELECT gpo.supplier_id, s.Supplier_Name, poi.Buying_Price,
                                                    poi.Quantity_Received,
                                                    gpo.Created_Date_Time, gpo.Grn_Purchase_Order_ID,
                                                    i.Item_ID, i.Product_Name, i.Product_Code, i.Unit_Of_Measure, i.Classification
                                                    FROM tbl_purchase_order_items poi,
                                                         tbl_grn_purchase_order gpo,
                                                         tbl_supplier s,
                                                         tbl_items i
                                                    WHERE poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                                    AND gpo.supplier_id = s.Supplier_ID
                                                    AND poi.Item_ID = i.Item_ID
                                                    {$Supplier_ID_Statement}
                                                    {$Classification_Statement}
                                                    {$Item_ID_Statement}
                                                    {$Start_End_Date_Statement}
                                                    ORDER BY gpo.Created_Date_Time DESC
                                                    {$Limit_Statement}");
        $hasError = $Item_Purchase_History_Result["error"];
        if (!$hasError) {
            $Item_Purchase_History = array_merge($Item_Purchase_History, $Item_Purchase_History_Result["data"]);
        } else {
            echo $Item_Purchase_History_Result["errorMsg"];
        }

        return $Item_Purchase_History;
    }

    function Get_Item_Purchase_History_From_GRN_Without_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0){
        $Item_Purchase_History = array();

        $Start_End_Date_Statement = "";
        if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != ""){
            $Start_Date = Get_Day_Beginning($Start_Date);
            $End_Date = Get_Day_Ending($End_Date);
            $Start_End_Date_Statement = "AND gwpo.Grn_Date_And_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Item_ID_Statement = "";
        if ($Item_ID > 0 && $Item_ID != null) {
            $Item_ID_Statement = "AND gwpoi.Item_ID = {$Item_ID}";
        }

        $Supplier_ID_Statement = "";
        if ($Supplier_ID > 0 && $Supplier_ID != null) {
            $Supplier_ID_Statement = "AND gwpo.Supplier_ID = {$Supplier_ID}";
        }

        $Classification_Statement = "";
        if ($Classification != "" && strtolower($Classification) != "all") {
            $Classification_Statement = "AND i.Classification = '{$Classification}'";
        }

        $Item_Purchase_History_Result = Query_DB("SELECT gwpo.Supplier_ID, s.Supplier_Name, gwpoi.Price, gwpoi.Quantity_Required,
                                                  gwpo.Grn_Date_And_Time, gwpo.Grn_ID,
                                                  i.Item_ID, i.Product_Name, i.Product_Code, i.Unit_Of_Measure, i.Classification
                                                     FROM tbl_grn_without_purchase_order_items gwpoi,
                                                          tbl_grn_without_purchase_order gwpo,
                                                          tbl_supplier s,
                                                          tbl_items i
                                                     WHERE gwpoi.Grn_ID = gwpo.Grn_ID
                                                     AND gwpo.Supplier_ID = s.Supplier_ID
                                                     AND gwpoi.Item_ID = i.Item_ID
                                                     {$Supplier_ID_Statement}
                                                     {$Classification_Statement}
                                                     {$Item_ID_Statement}
                                                     {$Start_End_Date_Statement}
                                                     ORDER BY gwpo.Grn_Date_And_Time DESC
                                                     {$Limit_Statement}");
        $hasError = $Item_Purchase_History_Result["error"];
        if (!$hasError) {
            $Item_Purchase_History = array_merge($Item_Purchase_History, $Item_Purchase_History_Result["data"]);
        } else {
            echo $Item_Purchase_History_Result["errorMsg"];
        }

        return $Item_Purchase_History;
    }

    function Get_Items_By_Item_Type($Item_Type, $limit) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT * FROM tbl_items
                                    WHERE Item_Type = '{$Item_Type}'
                                    ORDER BY Product_Name
                                    LIMIT {$limit}");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item_List = array_merge($Item_List, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }

    function Get_Item_Price($Item_ID, $Sponsor_ID) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT Items_Price FROM tbl_item_price
                                 WHERE Sponsor_ID = '{$Sponsor_ID}'
                                 AND Item_ID = '{$Item_ID}' ");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item_List = $Item_Result["data"][0];
            } else {
                $Item_General_Price = Get_Item_General_Price($Item_ID);
                if (!empty($Item_General_Price)) {
                    $Item_List = $Item_General_Price;
                }
            }
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }

    function Get_Item_General_Price($Item_ID) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT Items_Price FROM tbl_general_item_price
                                 WHERE Item_ID = '{$Item_ID}'");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item_List = $Item_Result["data"][0];
            } else {
                $Item_List = array("Items_Price" => 0);
            }
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }
?>