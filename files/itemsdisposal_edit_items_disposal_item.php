<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

<?php
    include_once("./includes/connection.php");
    include_once("./functions/itemsdisposal.php");
    include_once("./functions/items.php");

    if(isset($_GET['Disposal_ID'])){
        $Disposal_ID = $_GET['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    if ($Disposal_ID > 0 && $Item_ID > 0 ) {
        $Items_Disposal_Item_List = $_SESSION['Disposal_Edit']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Disposal_Item_ID = count($Items_Disposal_Item_List) - 1;

        $Items_Disposal_Item = array(
            "Disposal_Item_ID" => $Disposal_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Balance" => $Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Quantity_Disposed" => 0,
            "Disposal_ID" => $Disposal_ID,
            "Item_Remark" => ''
        );
        $Items_Disposal_Item_List = array_merge($Items_Disposal_Item_List, array($Items_Disposal_Item));
        $_SESSION['Disposal_Edit']['Items'] = $Items_Disposal_Item_List;

        echo "<table width='100%' border='0'><tbody>";
        echo "<tr>";
        echo "<td style='text-align: center; width: 4%;'>Sn</td>";
        echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
        echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
        echo "<td style='text-align: center; width: 10%;'>Quantity Disposed</td>";
        echo "<td style='text-align: center; width: 10%;'>Balance After Disposal</td>";
        echo "<td style='text-align: center; width: 10%;'>Remark</td>";
        echo "<td style='text-align: center; width: 7%;'>Remove</td>";
        echo "</tr>";

        $i = 1;
        foreach($Items_Disposal_Item_List as $Disposal_Item) {
            $Items_Disposal_Item = Get_Items_Disposal_Item($Disposal_ID, $Disposal_Item['Item_ID']);
            $Previous_Disposed_Quantity = 0;
            if (!empty($Items_Disposal_Item)) {
                $Previous_Disposed_Quantity = $Items_Disposal_Item['Quantity_Disposed'];
            }

            echo "<tr>";

            echo "<td> <input type='text' value='{$i}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                                id='Product_Name_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Disposal_Item['Product_Name']}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                                id='Store_Balance_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Disposal_Item['Store_Balance']}'/>  </td>";

            echo "<td> <input type='text'
                                id='Quantity_Disposed_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Disposal_Item['Quantity_Disposed']}'
                                onclick=\"removeZero(this)\"
                                onkeypress=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"
                                onkeyup=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"/>
                                <input type='hidden' id='Previous_Quantity_Disposed_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Previous_Disposed_Quantity}' />
                                </td>";

            $Balance_After_Disposal = ($Disposal_Item['Store_Balance'] + $Previous_Disposed_Quantity) - $Disposal_Item['Quantity_Disposed'];

            echo "<td> <input type='text'
                                id='Balance_After_Disposal_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Balance_After_Disposal}' readonly />
                                </td>";

            echo "<td> <input type='text'
                                id='Item_Remark_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Disposal_Item['Item_Remark']}'
                                onkeypress=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\"
                                onkeyup=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']},{$Disposal_Item['Item_ID']})\" />
                                </td>";

            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                onclick='Confirm_Remove_Item(\"{$Disposal_Item['Product_Name']}\", {$Disposal_Item['Item_ID']})' />  </td>";

            echo "</tr>";
            $i++;
        }

        echo "</tbody></table>";
    }

?>