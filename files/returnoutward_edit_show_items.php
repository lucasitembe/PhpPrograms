<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php


echo "<table width='100%' border='0'><tbody>";
echo "<tr>";
echo "<td style='text-align: center; width: 4%;'>Sn</td>";
echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
echo "<td style='text-align: center; width: 10%;'>Quantity Returned</td>";
echo "<td style='text-align: center; width: 10%;'>Remark</td>";
echo "<td style='text-align: center; width: 7%;'>Remove</td>";
echo "</tr>";

$i = 1;
foreach($Return_Outward_Item_List as $Return_Outward_Item) {
    $Return_Outward_Item_I = Get_Return_Outward_Item($Outward_ID, $Return_Outward_Item['Item_ID']);
    $Previous_Quantity_Returned = 0;
    if (!empty($Return_Outward_Item_I)) {
        $Previous_Quantity_Returned= $Return_Outward_Item_I['Quantity_Returned'];
    }

    echo "<tr>";

    echo "<td> <input type='text' value='{$i}'/>  </td>";

    echo "<td> <input type='text' readonly='readonly'
                        id='Product_Name_{$Return_Outward_Item['Outward_Item_ID']}'
                        value='{$Return_Outward_Item['Product_Name']}'/>  </td>";

    echo "<td> <input type='text' readonly='readonly'
                        id='Store_Balance_{$Return_Outward_Item['Outward_Item_ID']}'
                        value='{$Return_Outward_Item['Store_Balance']}'/>  </td>";

    echo "<td> <input type='text'
                        id='Quantity_Returned_{$Return_Outward_Item['Outward_Item_ID']}'
                        value='{$Return_Outward_Item['Quantity_Returned']}'
                        onclick=\"removeZero(this)\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Returned(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Returned(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\" />
                        <input type='hidden' id='Previous_Quantity_Returned_{$Return_Outward_Item['Outward_Item_ID']}'
                                value='{$Previous_Quantity_Returned}' />
                        </td>";

    echo "<td> <input type='text'
                        id='Item_Remark_{$Return_Outward_Item['Outward_Item_ID']}'
                        value='{$Return_Outward_Item['Item_Remark']}'
                        onkeypress=\"Update_Item_Remark(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\"
                        onkeyup=\"Update_Item_Remark(this.value,{$Return_Outward_Item['Outward_Item_ID']},{$Return_Outward_Item['Item_ID']})\" />
                        </td>";

    echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                        onclick='Confirm_Remove_Item(\"{$Return_Outward_Item['Product_Name']}\", {$Return_Outward_Item['Item_ID']})' />  </td>";

    echo "</tr>";
    $i++;
}

echo "</tbody></table>";
?>