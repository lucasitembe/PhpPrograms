<?php
require_once './includes/constants.php';
include_once("./functions/database.php");
include_once("./functions/stockledger.php");

function Get_Item_Classification() {
    return array(
        array("Name" => "Bedding Plus Linen", "Description" => "Bedding Plus Linen"),
        array("Name" => "Cleaning Materials", "Description" => "Cleaning Materials"),
        array("Name" => "Crockery Materials", "Description" => "Crockery Materials"),
        array("Name" => "Disposables", "Description" => "Disposables"),
        array("Name" => "Dental Materials", "Description" => "Dental Materials"),
        array("Name" => "Dressing Materials", "Description" => "Dressing Materials"),
        array("Name" => "Engineering Materials", "Description" => "Engineering Materials"),
        array("Name" => "Foods Stuff", "Description" => "Foods Stuff"),
        array("Name" => "Furniture", "Description" => "Furniture"),
        array("Name" => "Laboratory Materials", "Description" => "Laboratory Materials"),
        array("Name" => "General Materials", "Description" => "General Materials"),
        array("Name" => "Pharmaceuticals", "Description" => "Pharmaceuticals"),
        array("Name" => "Radiology Materials", "Description" => "Radiology Materials"),
        array("Name" => "Stationery Materials", "Description" => "Stationery Materials"),
        array("Name" => "Anaestesiology Materials", "Description" => "Anaestesiology Materials"),
        array("Name" => "Instrument Chest Surgical", "Description" => "Instrument Chest Surgical"),
        array("Name" => "Suture Materials", "Description" => "Suture Materials"),
        array("Name" => "Hospital Machine", "Description" => "Hospital Machine"),
        array("Name" => "Instrument General Suggery", "Description" => "Instrument General Suggery"),
        array("Name" => "Instrument Orthopaedic", "Description" => "Instrument Orthopaedic"),
        array("Name" => "Instrument Obs & Gynaecology", "Description" => "Instrument Obs & Gynaecology"),
        array("Name" => "Instrument Dental Materials", "Description" => "Instrument Dental Materials"),
        array("Name" => "Instrument Urology", "Description" => "Instrument Urology"),
        array("Name" => "Cardiology Materials", "Description" => "Cardiology Materials"),
        array("Name" => "Electrical Materials", "Description" => "Electrical Materials"),
        array("Name" => "Spare Part", "Description" => "Spare Part"),
        array("Name" => "Medical Oxygen And Other Gases", "Description" => "Medical Oxygen And Other Gases"),
        array("Name" => "Orthopedic", "Description" => "Orthopedic"),
        array("Name" => "Fuel And Liquid", "Description" => "Fuel And Liquid"),
        array("Name" => "Building", "Description" => "Building"),
        array("Name" => "Hospital Equipments", "Description" => "Hospital Equipments"),
        array("Name" => "Plumber Material", "Description" => "Plumber Material"),
        array("Name" => "Others", "Description" => "Others")
    );
}

function Get_Item_ClassificationNonMedical() {
    return array(
        array("Name" => "Stationaries", "Description" => "Stationaries and Materials"),
        array("Name" => "Others", "Description" => "Others")
    );
}

function Get_Item_Consultation_Type() {
    return array(
        array("Name" => "Pharmacy", "Description" => "Pharmacy"),
        array("Name" => "Laboratory", "Description" => "Laboratory"),
        array("Name" => "Radiology", "Description" => "Radiology"),
        array("Name" => "Surgery", "Description" => "Surgery"),
        array("Name" => "Procedure", "Description" => "Procedure"),
        array("Name" => "Optical", "Description" => "Admission"),
        array("Name" => "Optical", "Description" => "Optical"),
        array("Name" => "Mortuary", "Description" => "Mortuary"),
        array("Name" => "Others", "Description" => "Others")
    );
}

function Get_Item_Type() {
    return array(
        array("Name" => "Service", "Description" => "Serviced Item"),
        array("Name" => "Pharmacy", "Description" => "Pharmaceutical and Consumable Item"),
        array("Name" => "Others", "Description" => "Other Item"),
        array("Name" => "Charges", "Description" => "Other Charges")
    );
}

function Get_Unit_Of_Measure() {
    return array(
        array("Name" => "", "Description" => ""),
        array("Name" => "btl", "Description" => "Btl"),
        array("Name" => "pc", "Description" => "PC"),
        array("Name" => "vial", "Description" => "Vial"),
        array("Name" => "tube", "Description" => "Tube"),
        array("Name" => "kit", "Description" => "KIT"),
        array("Name" => "nill", "Description" => "NILL"),
        array("Name" => "box", "Description" => "Box"),
        array("Name" => "cap", "Description" => "Cap"),
        array("Name" => "tab", "Description" => "Tab"),
        array("Name" => "tin", "Description" => "Tin"),
        array("Name" => "amp", "Description" => "amp"),
        array("Name" => "bag", "Description" => "Bag"),
        array("Name" => "vl", "Description" => "VL"),
        array("Name" => "pkt", "Description" => "Pkt"),
        array("Name" => "caps", "Description" => "Caps"),
        array("Name" => "supp", "Description" => "Supp"),
        array("Name" => "tabs", "Description" => "Tabs"),
        array("Name" => "25g", "Description" => "25g"),
        array("Name" => "ltr", "Description" => "ltr"),
        array("Name" => "pair", "Description" => "Pair"),
        array("Name" => "pct", "Description" => "pct"),
        array("Name" => "20lt", "Description" => "20lt"),
        array("Name" => "disc", "Description" => "Disc"),
        array("Name" => "100", "Description" => "100"),
        array("Name" => "ctn", "Description" => "ctn"),
        array("Name" => "roll", "Description" => "roll"),
        array("Name" => "25", "Description" => "25"),
        array("Name" => "doz", "Description" => "doz"),
        array("Name" => "each", "Description" => "each"),
        array("Name" => "soln", "Description" => "soln"),
        array("Name" => "inj", "Description" => "inj"),
        array("Name" => "mt", "Description" => "mt"),
        array("Name" => "stchts", "Description" => "stchts"),
        array("Name" => "Pieces", "Description" => "Pieces")
    );
}

function Get_Item($Item_ID) {
    global $conn;
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
    global $conn;
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
    global $conn;
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
    global $conn;
    $Item_Balance_List = array();

    $limit_statement = "";
    if ($limit > 0) {
        $limit_statement = "LIMIT {$limit}";
    }

    $product_name_statement = "";
    if ($Product_Name != "") {
        $Product_Name = Prepare_For_Like_Operator($Product_Name);
        $product_name_statement = "WHERE t.Product_Name like '{$Product_Name}' AND ";
    }else{
        $product_name_statement = " WHERE ";
    }

    $Item_Balance_Result = Query_DB("SELECT Product_Name, t.Item_ID, t.Unit_Of_Measure,

                                           IFNULL((SELECT Buying_Price FROM tbl_purchase_order_items poi WHERE t.Item_ID = poi.Item_ID
                                           AND Grn_Status = 'RECEIVED' ORDER BY Order_Item_ID DESC LIMIT 1), '0') as Last_Buying_Price,

                                           IFNULL((SELECT ib.Item_Balance FROM tbl_items_balance ib
                                           WHERE ib.Sub_Department_ID = '{$Sub_Department_ID}' AND ib.Item_ID = t.Item_ID), '0') as Item_Balance

                                           FROM tbl_items t
                                            

                                           {$product_name_statement} t.Status='Available'
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
    global $conn;
    $Item_Balance_List = array();

    $limit_statement = "";
    if ($limit > 0) {
        $limit_statement = "LIMIT {$limit}";
    }

    $product_name_statement = "";
    if ($Product_Name != "") {
        $Product_Name = Prepare_For_Like_Operator($Product_Name);
        $product_name_statement = "AND t.Product_Name like '%{$Product_Name}%' AND Status='Available'";
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
    global $conn;
    $Item_Balance_List = array();

    $limit_statement = "";
    if ($limit > 0) {
        $limit_statement = "LIMIT {$limit}";
    }

    $product_name_statement = "";
    if ($Product_Name != "") {
        $Product_Name = Prepare_For_Like_Operator($Product_Name);
        $product_name_statement = "AND t.Product_Name like '%{$Product_Name}%' AND Status='Available'";
    }

    $Classification_Statement = "";
    if ($Classification != "" && strtolower($Classification) != "all") {
        $Classification_Statement = "AND t.Classification = '{$Classification}' AND Status='Available'";
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

function Update_Item_Balance($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination, $External_Source, $Registration_ID, $Document_Number, $Document_Date, $Quantity, $Add_Quantity) {
    global $conn;
    $Previous_Item_Balance = Get_From("tbl_items_balance", array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID), 1);
    $hasError = $Previous_Item_Balance["error"];
    if (!$hasError) {
        if ($Previous_Item_Balance["count"] > 0) {
            $Previous_Balance = $Previous_Item_Balance["data"][0]["Item_Balance"];
        } else {
            $Insert_Item_Balance = Insert_DB("tbl_items_balance", array("Item_ID" => $Item_ID, "Sub_Department_ID" => $Sub_Department_ID));
            $hasError = $Insert_Item_Balance["error"];
            if ($hasError) {
                echo $Insert_Item_Balance["errorMsg"];
                return false;
            }
            $Previous_Balance = 0;
        }

        if ($Add_Quantity == 'yes') {
            /*             * if ($Previous_Balance < 0) { allow negative balance
              return false;
              }* */
            $Post_Balance = array("Item_Balance", "+", $Quantity);
            $Post_Bal = $Previous_Balance + $Quantity;
        } else {
            /*             * if ($Quantity > $Previous_Balance) { allow negative balance
              return false;
              }* */
            $Post_Balance = array("Item_Balance", "-", $Quantity);
            $Post_Bal = $Previous_Balance - $Quantity;
        }

        // die(print_r($Post_Balance));
        // die("000 ".$Add_Quantity."  ::: ".$Quantity." == Post balance :: ".$Post_Bal." === Post :: ".$Post_Balance[0]);

        $Item_Balance_Update = Update_DB("tbl_items_balance", array("Item_Balance" => $Post_Balance), array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID));

        $hasError = $Item_Balance_Update["error"];
        if (!$hasError) {
            $Stock_Ledger_Record = Get_Stock_Ledger_Record($Item_ID, $Sub_Department_ID, $Document_Number, $Movement_Type);
            if (!empty($Stock_Ledger_Record)) {
                return Update_Stock_Ledger($Previous_Balance, $Post_Bal, $Stock_Ledger_Record);
            } else {
                return Create_Stock_Ledger($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination, $External_Source, $Registration_ID, $Document_Number, $Document_Date, $Previous_Balance, $Post_Bal);
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

function Get_Item_Last_Buying_Price_With_Supplier($Item_ID) {
    global $conn;
    $List_Of_Last_Buying_Price = array();
    $List_GRN_Without_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID);
    $List_GRN_With_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID);
    if (!empty($List_GRN_Without_PO)) {
        foreach ($List_GRN_Without_PO as $GRN_Without_PO) {
            $Last_Buying_Price = array("Supplier_ID" => $GRN_Without_PO["Supplier_ID"],
                "Supplier_Name" => $GRN_Without_PO["Supplier_Name"],
                "Grn_ID" => $GRN_Without_PO["Grn_ID"],
                "Grn_Date" => $GRN_Without_PO["Grn_Date_And_Time"],
                "Buying_Price" => $GRN_Without_PO["Price"],
                "Grn_Type" => "GRN Without PO");
            $List_Of_Last_Buying_Price = array_merge($List_Of_Last_Buying_Price, array($Last_Buying_Price));
        }
    }

    if (!empty($List_GRN_With_PO)) {
        foreach ($List_GRN_With_PO as $GRN_With_PO) {
            $Last_Buying_Price = array("Supplier_ID" => $GRN_With_PO["supplier_id"],
                "Supplier_Name" => $GRN_With_PO["Supplier_Name"],
                "Grn_ID" => $GRN_With_PO["Grn_Purchase_Order_ID"],
                "Grn_Date" => $GRN_With_PO["Created_Date_Time"],
                "Buying_Price" => $GRN_With_PO["Buying_Price"],
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
            if (!in_array($Last_Buying_Price['Supplier_ID'], $List_Of_Supplier_ID)) {
                $Final_List_Of_Last_Buying_Price = array_merge($Final_List_Of_Last_Buying_Price, array($Last_Buying_Price));
                $List_Of_Supplier_ID = array_merge($List_Of_Supplier_ID, array($Last_Buying_Price['Supplier_ID']));
            }
        }
        return $Final_List_Of_Last_Buying_Price;
    } else {
        return array();
    }
}

function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID) {
    global $conn;
    $Item = array();

    $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID);
    foreach ($Supplier_List as $Supplier) {
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

function Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID) {
    global $conn;
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

function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID) {
    global $conn;
    $Item = array();

    $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_Without_PO($Item_ID);
    foreach ($Supplier_List as $Supplier) {
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

function Get_Item_Last_Buying_Price_From_Open_Balance($Item_ID) {
    global $conn;
    $Item = array();
    $Item_Result = Query_DB("SELECT grn.Saved_Date_Time, grni.Buying_Price
                                     FROM tbl_grn_open_balance grn, tbl_grn_open_balance_items grni
                                     WHERE grn.Grn_Open_Balance_ID = grni.Grn_Open_Balance_ID
                                     AND grni.Item_ID = {$Item_ID}
                                     AND grn.Grn_Open_Balance_Status = 'saved'
                                     ORDER BY grni.Open_Balance_Item_ID DESC
                                     LIMIT 1");
    $hasError = $Item_Result["error"];
    if (!$hasError) {
        if (!empty($Item_Result["data"])) {
            $Item = array_merge($Item, $Item_Result["data"]);
        }
    } else {
        echo $Item_Result["errorMsg"];
    }
    return $Item;
}

function Get_Last_Buy_Price($Item_ID) {
    global $conn;

    $Pr1 = 0;
    $Pr2 = 0;
    $Pr3 = 0;
    $PD1 = '0000/00/00 00:00';
    $PD2 = '0000/00/00 00:00';
    $PD3 = '0000/00/00 00:00';

    $Price1 = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID);
    foreach ($Price1 as $D1) {
        $Pr1 = $D1['Price']; //Price 1 (Without Purchase Order)
        $PD1 = $D1['Grn_Date_And_Time']; //Date 1 (Without Purchase Order)
    }

    $Price2 = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID);
    foreach ($Price2 as $D2) {
        $Pr2 = $D2['Buying_Price']; //Price 2 (With Purchase Order)
        $PD2 = $D2['Created_Date_Time']; //Date 2 (With Purchase Order)
    }

    $Price3 = Get_Item_Last_Buying_Price_From_Open_Balance($Item_ID);
    foreach ($Price3 as $D3) {
        $Pr3 = $D3['Buying_Price']; //Price 2 (As Open Balance)
        $PD3 = $D3['Saved_Date_Time']; //Date 2 (As Open Balance)
    }

    if ($Pr1 == '' || $Pr1 == null) {
        $Pr1 = 0;
    }
    if ($Pr2 == '' || $Pr2 == null) {
        $Pr2 = 0;
    }
    if ($Pr3 == '' || $Pr3 == null) {
        $Pr3 = 0;
    }

    if ($PD1 == '' || $PD1 == null) {
        $PD1 = '0000/00/00 00:00:00';
    }
    if ($PD2 == '' || $PD2 == null) {
        $PD2 = '0000/00/00 00:00:00';
    }
    if ($PD3 == '' || $PD3 == null) {
        $PD3 = '0000/00/00 00:00:00';
    }

    if ($PD1 > $PD2 && $PD1 > $PD3) {
        return $Pr1;
    } else if ($PD2 > $PD1 && $PD2 > $PD3) {
        return $Pr2;
    } else {
        return $Pr3;
    }
}

function Get_List_Of_Supplier_For_Item_In_GRN_Without_PO($Item_ID) {
    global $conn;
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

function Get_Item_Purchase_History($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History_From_GRN_With_PO = Get_Item_Purchase_History_From_GRN_With_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit);
    $Item_Purchase_History_From_GRN_Without_PO = Get_Item_Purchase_History_From_GRN_Without_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit);
    $Item_Purchase_History = array();

    if (!empty($Item_Purchase_History_From_GRN_Without_PO)) {
        foreach ($Item_Purchase_History_From_GRN_Without_PO as $Purchase_History) {
            $Item_Purchase_History = array_merge($Item_Purchase_History, array(array(
                    "Tax" => $Purchase_History["Tax"],
                    "Item_ID" => $Purchase_History["Item_ID"],
                    "Product_Name" => $Purchase_History["Product_Name"],
                    "Product_Code" => $Purchase_History["Product_Code"],
                    "Unit_Of_Measure" => $Purchase_History["Unit_Of_Measure"],
                    "Classification" => $Purchase_History["Classification"],
                    "Supplier_ID" => $Purchase_History["Supplier_ID"],
                    "Supplier_Name" => $Purchase_History["Supplier_Name"],
                    "Buying_Price" => $Purchase_History["Price"],
                    "Quantity" => $Purchase_History["Quantity_Required"],
                    "Purchase_Date" => $Purchase_History["Grn_Date_And_Time"],
                    "Grn_ID" => $Purchase_History["Grn_ID"],
                    "Type" => "GRN Without PO"
            )));
        }
    } if (!empty($Item_Purchase_History_From_GRN_With_PO)) {
        foreach ($Item_Purchase_History_From_GRN_With_PO as $Purchase_History) {
            $Item_Purchase_History = array_merge($Item_Purchase_History, array(array(
                    "Tax" => $Purchase_History["Tax"],
                    "Item_ID" => $Purchase_History["Item_ID"],
                    "Product_Name" => $Purchase_History["Product_Name"],
                    "Product_Code" => $Purchase_History["Product_Code"],
                    "Classification" => $Purchase_History["Classification"],
                    "Unit_Of_Measure" => $Purchase_History["Unit_Of_Measure"],
                    "Supplier_ID" => $Purchase_History["supplier_id"],
                    "Supplier_Name" => $Purchase_History["Supplier_Name"],
                    "Buying_Price" => $Purchase_History["Buying_Price"],
                    "Quantity" => $Purchase_History["Quantity_Received"],
                    "Purchase_Date" => $Purchase_History["Created_Date_Time"],
                    "Grn_ID" => $Purchase_History["Grn_Purchase_Order_ID"],
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

function Get_Item_Purchase_History_From_GRN_With_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History = array();

    $Start_End_Date_Statement = "";
    if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != "") {
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

    $Item_Purchase_History_Result = Query_DB("SELECT i.Tax,gpo.supplier_id, s.Supplier_Name, poi.Buying_Price,
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

//Start  returnInward function here

function Get_ReturnInward_Item_History($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History = array();

    $Start_End_Date_Statement = "";
    if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != "") {
        $Start_Date = Get_Day_Beginning($Start_Date);
        $End_Date = Get_Day_Ending($End_Date);
        $Start_End_Date_Statement = "AND tri.Inward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
    }

    $Limit_Statement = "";
    if ($Limit > 0) {
        $Limit_Statement = "LIMIT {$Limit}";
    }

    $Item_ID_Statement = "";
    if ($Item_ID > 0 && $Item_ID != null) {
        $Item_ID_Statement = "AND trii.Item_ID = {$Item_ID}";
    }

    $Supplier_ID_Statement = "";
    if ($Supplier_ID > 0 && $Supplier_ID != null) {
        $Supplier_ID_Statement = "AND gpo.supplier_id = {$Supplier_ID}";
    }

    $Classification_Statement = "";
    if ($Classification != "" && strtolower($Classification) != "all") {
        $Classification_Statement = "AND i.Classification = '{$Classification}'";
    }

    $Item_Purchase_History_Result = Query_DB("SELECT i.Tax,i.Item_ID, i.Product_Name,i.Product_Code, i.Unit_Of_Measure, i.Classification,tri.Inward_Date,trii.Quantity_Returned
                                                    FROM tbl_return_inward tri,
                                                         tbl_return_inward_items trii,
                                                         tbl_items i
                                                    WHERE tri.Inward_ID=trii.Inward_ID
                                                    AND trii.Item_ID = i.Item_ID
                                                    {$Classification_Statement}
                                                    {$Item_ID_Statement}
                                                    {$Start_End_Date_Statement}
                                                    ORDER BY tri.Inward_Date DESC
                                                    {$Limit_Statement}");
    $hasError = $Item_Purchase_History_Result["error"];
    if (!$hasError) {
        $Item_Purchase_History = array_merge($Item_Purchase_History, $Item_Purchase_History_Result["data"]);
    } else {
        echo $Item_Purchase_History_Result["errorMsg"];
    }

    return $Item_Purchase_History;
}

//Start  returnOutward function here
function Get_ReturnOutward_Item_History($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History = array();

    $Start_End_Date_Statement = "";
    if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != "") {
        $Start_Date = Get_Day_Beginning($Start_Date);
        $End_Date = Get_Day_Ending($End_Date);
        $Start_End_Date_Statement = "AND tro.Outward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
    }

    $Limit_Statement = "";
    if ($Limit > 0) {
        $Limit_Statement = "LIMIT {$Limit}";
    }

    $Item_ID_Statement = "";
    if ($Item_ID > 0 && $Item_ID != null) {
        $Item_ID_Statement = "AND trii.Item_ID = {$Item_ID}";
    }

    $Supplier_ID_Statement = "";
    if ($Supplier_ID > 0 && $Supplier_ID != null) {
        $Supplier_ID_Statement = "AND tro.Supplier_ID = {$Supplier_ID}";
    }

    $Classification_Statement = "";
    if ($Classification != "" && strtolower($Classification) != "all") {
        $Classification_Statement = "AND i.Classification = '{$Classification}'";
    }

    $Item_Purchase_History_Result = Query_DB("SELECT i.Tax,i.Item_ID, i.Product_Name,i.Product_Code, i.Unit_Of_Measure, i.Classification,tro.Outward_Date,troi.Quantity_Returned
                                                    FROM tbl_return_outward tro,
                                                         tbl_return_outward_items troi,
                                                         tbl_items i,
                                                         tbl_supplier ts
                                                    WHERE tro.Outward_ID=troi.Outward_ID
                                                    AND troi.Item_ID = i.Item_ID
                                                    AND ts.Supplier_ID=tro.Supplier_ID
                                                    {$Classification_Statement}
                                                    {$Item_ID_Statement}
                                                    {$Supplier_ID_Statement}
                                                    {$Start_End_Date_Statement}
                                                    ORDER BY tro.Outward_Date DESC
                                                    {$Limit_Statement}");
    $hasError = $Item_Purchase_History_Result["error"];
    if (!$hasError) {
        $Item_Purchase_History = array_merge($Item_Purchase_History, $Item_Purchase_History_Result["data"]);
    } else {
        echo $Item_Purchase_History_Result["errorMsg"];
    }

    return $Item_Purchase_History;
}

//Start  disposal function here
function Get_Disposal_Item_History($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History = array();

    $Start_End_Date_Statement = "";
    if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != "") {
        $Start_Date = Get_Day_Beginning($Start_Date);
        $End_Date = Get_Day_Ending($End_Date);
        $Start_End_Date_Statement = "AND td.Disposed_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
    }

    $Limit_Statement = "";
    if ($Limit > 0) {
        $Limit_Statement = "LIMIT {$Limit}";
    }

    $Item_ID_Statement = "";
    if ($Item_ID > 0 && $Item_ID != null) {
        $Item_ID_Statement = "AND tdi.Item_ID = {$Item_ID}";
    }

    $Supplier_ID_Statement = "";
    if ($Supplier_ID > 0 && $Supplier_ID != null) {
        $Supplier_ID_Statement = "AND tro.Supplier_ID = {$Supplier_ID}";
    }

    $Classification_Statement = "";
    if ($Classification != "" && strtolower($Classification) != "all") {
        $Classification_Statement = "AND i.Classification = '{$Classification}'";
    }

    $Item_Purchase_History_Result = Query_DB("SELECT i.Tax,i.Item_ID, i.Product_Name,i.Product_Code, i.Unit_Of_Measure, i.Classification,td.Disposed_Date,tdi.Quantity_Disposed
                                                    FROM tbl_disposal td,
                                                         tbl_disposal_items tdi,
                                                         tbl_items i
                                                    WHERE td.Disposal_ID=tdi.Disposal_ID
                                                    AND tdi.Item_ID = i.Item_ID
                                                    {$Classification_Statement}
                                                    {$Item_ID_Statement}
                                                    {$Start_End_Date_Statement}
                                                    ORDER BY td.Disposed_Date DESC
                                                    {$Limit_Statement}");
    $hasError = $Item_Purchase_History_Result["error"];
    if (!$hasError) {
        $Item_Purchase_History = array_merge($Item_Purchase_History, $Item_Purchase_History_Result["data"]);
    } else {
        echo $Item_Purchase_History_Result["errorMsg"];
    }

    return $Item_Purchase_History;
}

function Get_Item_Purchase_History_From_GRN_Without_PO($Supplier_ID, $Classification, $Item_ID, $Start_Date, $End_Date, $Limit = 0) {
    global $conn;
    $Item_Purchase_History = array();

    $Start_End_Date_Statement = "";
    if ($Start_Date != null && $Start_Date != "" && $End_Date != null && $End_Date != "") {
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

    $Item_Purchase_History_Result = Query_DB("SELECT i.Tax,gwpo.Supplier_ID, s.Supplier_Name, gwpoi.Price, gwpoi.Quantity_Required,
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
    global $conn;
    $Item_List = array();

    if($Item_Type != ""){
        $Item_Result = Query_DB("SELECT * FROM tbl_items WHERE Item_Type = '{$Item_Type}' ORDER BY Product_Name LIMIT {$limit}");
    }else{
        $Item_Result = Query_DB("SELECT * FROM tbl_items ORDER BY Product_Name LIMIT {$limit}");
    }


    $hasError = $Item_Result["error"];
    if (!$hasError) {
        $Item_List = array_merge($Item_List, $Item_Result["data"]);
    } else {
        echo $Item_Result["errorMsg"];
    }

    return $Item_List;
}

function Get_Item_Price($Item_ID, $Sponsor_ID) {
    global $conn;
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
    global $conn;
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

function Update_Issue_Note_Manual_Cost_Center($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination, $Document_Number, $Document_Date, $Quantity) {
    global $conn;
    $Previous_Item_Balance = Get_From("tbl_items_balance", array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID), 1);
    $hasError = $Previous_Item_Balance["error"];
    if (!$hasError) {
        if ($Previous_Item_Balance["count"] > 0) {
            $Previous_Balance = $Previous_Item_Balance["data"][0]["Item_Balance"];
        } else {
            $Insert_Item_Balance = Insert_DB("tbl_items_balance", array("Item_ID" => $Item_ID, "Sub_Department_ID" => $Sub_Department_ID));
            $hasError = $Insert_Item_Balance["error"];
            if ($hasError) {
                echo $Insert_Item_Balance["errorMsg"];
                return false;
            }
            $Previous_Balance = 0;
        }

        $Post_Balance = array("Item_Balance", "+", $Quantity);
        $Post_Bal = $Previous_Balance + $Quantity;

        $Item_Balance_Update = Update_DB("tbl_items_balance", array("Item_Balance" => $Post_Balance), array("Item_ID", "=", $Item_ID), array("Sub_Department_ID", "=", $Sub_Department_ID));

        $hasError = $Item_Balance_Update["error"];
        if (!$hasError) {
            $Stock_Ledger_Record = Get_Stock_Ledger_Record($Item_ID, $Sub_Department_ID, $Document_Number, $Movement_Type);
            if (!empty($Stock_Ledger_Record)) {
                return Update_Stock_Ledger($Previous_Balance, $Post_Bal, $Stock_Ledger_Record);
            } else {
                return Create_Stock_Ledger($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination, null, null, $Document_Number, $Document_Date, $Previous_Balance, $Post_Bal);
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

function gAccJournalEntry($jsonData, $action = "add") {
    global $conn;
    if(isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting'])=='no'){
        return 'success';
    }
    $opts = array('http' =>
        array(
            'method' => 'GET',
            'header' => 'Content-type: application/json',
            'content' => $jsonData
        )
    );

    $context = stream_context_create($opts);
    return file_get_contents(LIVE_SERVER_ROOT_URL . "/gaccounting/Api/ehmsJournalEntry?" . $action, false, $context);
}

//This function returns ledgers for inventory by consultation type respectively

function getInventoryLedgerByConsultationType($consultation_type) {
    global $conn;
    if (strtolower($consultation_type) == 'pharmacy')
        $invLedger = 'Pharmacy-INVENTORY';
    else if (strtolower($consultation_type) == 'laboratory')
        $invLedger = 'Laboratory-INVENTORY';
    else if (strtolower($consultation_type) == 'radiology')
        $invLedger = 'Radiology-INVENTORY';
    else if (strtolower($consultation_type) == 'surgery')
        $invLedger = 'Surgery-INVENTORY';
    else if (strtolower($consultation_type) == 'procedure')
        $invLedger = 'Procedure-INVENTORY';
    else if (strtolower($consultation_type) == 'optical')
        $invLedger = 'Optical-INVENTORY';
    else if (strtolower($consultation_type) == 'dialysis')
        $invLedger = 'Dialysis-INVENTORY';
    else if (strtolower($consultation_type) == 'others')
        $invLedger = 'Others-INVENTORY';
    else
        $invLedger = 'Others-INVENTORY';

    return $invLedger;
}

function getSupplierInfoById($supplier_id) {
    global $conn;
    $Item_Result = Query_DB("SELECT * FROM tbl_supplier WHERE Supplier_ID='$supplier_id'");

    return $Item_Result["data"][0];
}

function getPaymentsDetailsByReceiptNumber($recieptNumber) {
    global $conn;
    $Item_Result = Query_DB("SELECT Payment_Date_And_Time,Billing_Type,Payment_Mode, SUM((Price-Discount)*Quantity) AS TotalAmount FROM tbl_patient_payments p "
            . " JOIN tbl_patient_payment_item_list pl ON p.Patient_Payment_ID=pl.Patient_Payment_ID"
            . " WHERE pl.Patient_Payment_ID='$recieptNumber'");

    return $Item_Result["data"][0];
}

function getItemLastBuyingPrice($item_id) {
    global $conn;
    $Item_Result = Query_DB("SELECT Last_Buy_Price FROM tbl_items WHERE Item_ID='$item_id'");

    return $Item_Result["data"][0]['Last_Buy_Price'];
}

function getSubdepartmentByID($id) {
    global $conn;
    $Item_Result = Query_DB("SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$id'");

    return $Item_Result["data"][0]['Sub_Department_Name'];
}

function getEmployeeNameByIssueManualID($id, $type) {
    global $conn;
    $on = '';
    if ($type == 'issuer')
        $on = 'im.Employee_Issuing';
    if ($type == 'needer')
        $on = 'im.Employee_Receiving';


    $Item_Result = Query_DB("SELECT Employee_Name FROM tbl_issuesmanual im JOIN tbl_employee e ON e.Employee_ID=$on WHERE Issue_ID='$id'");

    return $Item_Result["data"][0]['Employee_Name'];
}
function Get_Selling_Price($Item_ID,$Sub_Department_ID){
    global $conn;
    $sql_check_if_aleardy_set_result=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_store_selling_price_setup WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_check_if_aleardy_set_result)>0){
           $Sponsor_ID=mysqli_fetch_assoc($sql_check_if_aleardy_set_result)['Sponsor_ID'];
           
           $sql_get_selling_price_result=mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_get_selling_price_result)>0){
               $Items_Price=mysqli_fetch_assoc($sql_get_selling_price_result)['Items_Price'];
               return $Items_Price;
           }else{
               return 0;
           }
           }else{
           return "not_exist";
       }
}
