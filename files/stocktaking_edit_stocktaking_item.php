<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/stocktaking.php");
    include_once("./functions/items.php");

    if(isset($_GET['Stock_Taking_ID'])){
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }else{
        $Stock_Taking_ID = 0;
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

    if ($Stock_Taking_ID > 0 && $Item_ID > 0 ) {
        $Stock_Taking_Item_List = $_SESSION['Stock_Taking']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Issue_Item_ID = count($Stock_Taking_Item_List) - 1;

        $Stock_Taking_Item = array(
            "Stock_Taking_Item_ID" => $Issue_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Balance" => $Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Under_Quantity" => 0,
            "Over_Quantity" => 0,
            "Stock_Taking_ID" => $Stock_Taking_ID,
            "Item_Remark" => ''
        );
        $Stock_Taking_Item_List = array_merge($Stock_Taking_Item_List, array($Stock_Taking_Item));
        $_SESSION['Stock_Taking']['Items'] = $Stock_Taking_Item_List;

        echo "<table width='100%' border='0'><tbody>";
        echo "<tr>";
        echo "<td style='text-align: center; width: 4%;'>Sn</td>";
        echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
        echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
        echo "<td style='text-align: center; width: 10%;'>Under Quantity</td>";
        echo "<td style='text-align: center; width: 10%;'>Over Quantity</td>";
        echo "<td style='text-align: center; width: 10%;'>Store Balance After Stock Taking</td>";
        echo "<td style='text-align: center; width: 10%;'>Remark</td>";
        echo "<td style='text-align: center; width: 7%;'>Remove</td>";
        echo "</tr>";

        //$Stock_Taking_Items = Get_Stock_Taking_Items($Stock_Taking_ID);
        $i = 1;
        foreach($Stock_Taking_Item_List as $Stock_Taking_Item) {

            $Stock_Taking_Item_I = Get_Stock_Taking_Item($Stock_Taking_ID, $Stock_Taking_Item['Item_ID']);
            $Previous_Over_Quantity = 0;
            if (!empty($Stock_Taking_Item_I)) {
                $Previous_Over_Quantity= $Stock_Taking_Item_I['Over_Quantity'];
            }
            $Previous_Under_Quantity = 0;
            if (!empty($Stock_Taking_Item_I)) {
                $Previous_Under_Quantity= $Stock_Taking_Item_I['Under_Quantity'];
            }
            echo "<tr>";

            echo "<td> <input type='text' value='{$i}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                                id='Product_Name_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Stock_Taking_Item['Product_Name']}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                                id='Store_Balance_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Stock_Taking_Item['Store_Balance']}'/>  </td>";

            echo "<td> <input type='text'
                                id='Under_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Stock_Taking_Item['Under_Quantity']}'
                                onclick=\"removeZero(this)\"
                                onkeypress=\"numberOnly(this); Update_Under_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                onkeyup=\"numberOnly(this); Update_Under_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"/>
                                <input type='hidden' id='Previous_Under_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}' value='{$Previous_Under_Quantity}' />
                                </td>";

            echo "<td> <input type='text'
                                id='Over_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Stock_Taking_Item['Over_Quantity']}'
                                onclick=\"removeZero(this)\"
                                onkeypress=\"numberOnly(this); Update_Over_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                onkeyup=\"numberOnly(this); Update_Over_Quantity(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"/>
                                <input type='hidden' id='Previous_Over_Quantity_{$Stock_Taking_Item['Stock_Taking_Item_ID']}' value='{$Previous_Over_Quantity}' />
                                </td>";

            $Balance_After_Stock_Taking = $Stock_Taking_Item['Store_Balance'];

            $Over_Quantity = $Stock_Taking_Item['Over_Quantity'];
            if ($Over_Quantity > 0) {
                $Balance_After_Stock_Taking = ($Stock_Taking_Item['Store_Balance'] + $Over_Quantity + $Previous_Under_Quantity) - $Previous_Over_Quantity;
            }

            $Under_Quantity = $Stock_Taking_Item['Under_Quantity'];
            if ($Under_Quantity > 0) {
                $Balance_After_Stock_Taking = ($Stock_Taking_Item['Store_Balance']+$Previous_Under_Quantity) - ($Under_Quantity+$Previous_Over_Quantity);
            }

            echo "<td> <input type='text'
                                id='Balance_After_Stock_Taking_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Balance_After_Stock_Taking}' readonly />
                                </td>";

            echo "<td> <input type='text'
                                id='Item_Remark_{$Stock_Taking_Item['Stock_Taking_Item_ID']}'
                                value='{$Stock_Taking_Item['Item_Remark']}'
                                onkeypress=\"Update_Item_Remark(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\"
                                onkeyup=\"Update_Item_Remark(this.value,{$Stock_Taking_Item['Stock_Taking_Item_ID']},{$Stock_Taking_Item['Item_ID']})\" />
                                </td>";

            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                                onclick='Confirm_Remove_Item(\"{$Stock_Taking_Item['Product_Name']}\", {$Stock_Taking_Item['Item_ID']})' />  </td>";

            echo "</tr>";
            $i++;
        }

        echo "</tbody></table>";
    }

?>