
<?php
    include_once("./functions/database.php");
    include_once("./functions/department.php");
    include_once("./functions/items.php");

    $Param_One = "";
    if(isset($_GET['param_one'])) {
        $Param_One = $_GET['param_one'];
    }

    $Param_Two = "";
    if(isset($_GET['param_two'])) {
        $Param_Two = $_GET['param_two'];
    }

    $Param_Three = "";
    if(isset($_GET['param_three'])) {
        $Param_Three = $_GET['param_three'];
    }

    $List_Of_Last_Buying_Price = Get_Item_Last_Buying_Price_With_Supplier(63);
    foreach($List_Of_Last_Buying_Price as $Last_Buying_Price) {
        echo "Supplier : {$Last_Buying_Price['Supplier_Name']} Buying Price : {$Last_Buying_Price['Buying_Price']} <br/>";
    }


    //echo Get_Time_Now();

    /*$json = '[{"Edit_Date":"2016-02-23 12:49:13","Edited_By":"JEREMIAH RUGEMARILA NGEMERA","Previous_Data":[{"0":"Absolute alcohol (Ethanol 99-100%)","Product_Name":"Absolute alcohol (Ethanol 99-100%)","1":"18","Disposal_Item_ID":"18","2":"2104","Item_ID":"2104","3":"10","Quantity_Disposed":"10","4":null,"Item_Remark":null,"5":"7","Sub_Department_ID":"7","6":"40","Store_Balance":"40"}]},{"Edit_Date":"2016-02-23 12:49:27","Edited_By":"JEREMIAH RUGEMARILA NGEMERA","Previous_Data":[{"0":"Absolute alcohol (Ethanol 99-100%)","Product_Name":"Absolute alcohol (Ethanol 99-100%)","1":"18","Disposal_Item_ID":"18","2":"2104","Item_ID":"2104","3":"10","Quantity_Disposed":"10","4":null,"Item_Remark":null,"5":"7","Sub_Department_ID":"7","6":"40","Store_Balance":"40"}]},{"Edit_Date":"2016-02-23 12:51:22","Edited_By":"JEREMIAH RUGEMARILA NGEMERA","Previous_Data":[{"0":"Absolute alcohol (Ethanol 99-100%)","Product_Name":"Absolute alcohol (Ethanol 99-100%)","1":"18","Disposal_Item_ID":"18","2":"2104","Item_ID":"2104","3":"10","Quantity_Disposed":"10","4":null,"Item_Remark":null,"5":"7","Sub_Department_ID":"7","6":"40","Store_Balance":"40"}]}]';
    $Items = json_decode($json, true);
    foreach($Items as $Item) {
        echo "Edited by : " . $Item['Edited_By'] . " at " . $Item['Edit_Date'] . "<br>";
    }*/

    /*$result = Update_DB("tbl_items",
        array("Product_Code" => "XX00", "Product_Name" => "Jeremiah"),
        array("Item_ID", "=", 2927)
    );
    $hasError = $result["error"];
    if ($hasError) {
        echo $result["errorMsg"];
    }

    $result = Get_From("tbl_items", array("Item_ID", "like", "%2%"), array("Product_Name", "like", "Parace%"), 2);
    $hasError = $result["error"];
    if (!$hasError) {
        $items = $result["data"];
        foreach($items as $item) {
            echo "Item Name : " . $item["Product_Name"] . " <br>";
        }
    }

    /*$Sub_Department_List = Get_Sub_Department_By_Department_Nature('Storage And Supply');

    foreach($Sub_Department_List as $Sub_Department) {
        echo "Sub Department Name : " . $Sub_Department["Sub_Department_Name"] . " <br>";
    }*/

    //$Date = Get_Time_Now();
    //echo $Date;
    //var_dump($Date);

    /*$Yesterday = Get_Time(Get_Time_Now(), "-1 day");
    echo Get_Day_Beginning($Yesterday);
    echo "<br/>";
    echo Get_Day_Ending($Yesterday);
    echo "<br/>";

    $Item_Balance_Update = Update_DB("tbl_items_balance",
        array("Item_Balance" => array("Item_Balance", "-", "10")),
        array("Item_ID", "=", "1"),
        array("Sub_Department_ID", "=", "7"));*/


    /**
     * TESTING PHP BREAK
     */
    /*for($i=0; $i < 3; $i++) {
        //echo $i . "</br>";
        if ($Param_One == "{$i}") {
            break;
        }
        for($j=0; $j < 3; $j++) {
            echo $i . " ". $j . "</br>";
            if ($Param_Two == "{$j}") {
                break;
            }

        }
    }*/
?>