<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php


echo "<table width='100%' border='0'><tbody>";
echo "<tr>";
echo "<td style='text-align: center; width: 4%;'>Sn</td>";
echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
echo "<td style='text-align: center; width: 10%;'>Store Requesting Balance</td>";
echo "<td style='text-align: center; width: 10%;'>Store Issuing Balance</td>";
echo "<td style='text-align: center; width: 10%;'>Quantity Required</td>";
echo "<td style='text-align: center; width: 10%;'>Remark</td>";
echo "<td style='text-align: center; width: 7%;'>Remove</td>";
echo "</tr>";

$i = 1;
foreach($Requisition_Item_List as $Requisition_Item) {
    $Requisition_Item_I = Get_Requisition_Item($Requisition_ID, $Requisition_Item['Item_ID']);
    $Previous_Quantity_Required = 0;
    if (!empty($Requisition_Item_I)) {
        $Previous_Quantity_Required= $Requisition_Item_I['Quantity_Required'];
    }

    echo "<tr>";

    echo "<td> <input type='text' value='{$i}'/>  </td>";

    echo "<td> <input type='text' readonly='readonly'
                        id='Product_Name_{$Requisition_Item['Requisition_Item_ID']}'
                        value='{$Requisition_Item['Product_Name']}'/>  </td>";
    
    echo "<td> <input type='text' readonly='readonly'
                        id='Store_Balance_{$Requisition_Item['Requisition_Item_ID']}'
                        value='{$Requisition_Item['Store_Need_Balance']}'/>  </td>";

    echo "<td> <input type='text' readonly='readonly'
                        id='Store_Issue_Balance_{$Requisition_Item['Requisition_Item_ID']}'
                        value='{$Requisition_Item['Store_Issue_Balance']}'/>  </td>";

    echo "<td> <input type='text'
                        id='Quantity_Required_{$Requisition_Item['Requisition_Item_ID']}'
                        value='{$Requisition_Item['Quantity_Required']}'
                        onclick=\"removeZero(this)\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Requisition_Item['Requisition_Item_ID']},{$Requisition_Item['Item_ID']})\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Requisition_Item['Requisition_Item_ID']},{$Requisition_Item['Item_ID']})\" />
                        <input type='hidden' id='Previous_Quantity_Required_{$Requisition_Item['Requisition_Item_ID']}'
                                value='{$Previous_Quantity_Required}' />
                        </td>";

    echo "<td> <input type='text'
                        id='Item_Remark_{$Requisition_Item['Requisition_Item_ID']}'
                        value='{$Requisition_Item['Item_Remark']}'
                        onkeypress=\"Update_Item_Remark(this.value,{$Requisition_Item['Requisition_Item_ID']},{$Requisition_Item['Item_ID']})\"
                        onkeyup=\"Update_Item_Remark(this.value,{$Requisition_Item['Requisition_Item_ID']},{$Requisition_Item['Item_ID']})\" />
                        </td>";

    echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                        onclick='Confirm_Remove_Item(\"{$Requisition_Item['Product_Name']}\", {$Requisition_Item['Item_ID']})' />  </td>";

    echo "</tr>";
    $i++;
}

echo "</tbody></table>";
?>