<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Stock_Taking_ID'])){
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }else{
        $Stock_Taking_ID = 0;
    }

    if ($Item_ID > 0) {
        $Removed = false; $r = 0;
        $Stock_Taking_Item_List = $_SESSION['Stock_Taking']['Items'];
        foreach($Stock_Taking_Item_List as $Stock_Taking_Item) {
            if ($Stock_Taking_Item['Item_ID'] == $Item_ID) {
                unset($Stock_Taking_Item_List[$r]);
                $Removed = true;
                break;
            }
            $r++;
        }

        $_SESSION['Stock_Taking']['Items'] = $Stock_Taking_Item_List;

        if ($Removed) {
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
    }
?>