<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/department.php");

    $Store_Issuing_Name = "Store Issuing";
    $Store_Requesting_Name = "Store Requesting";
    if ($Requisition_ID > 0 ) {
        $Requisition = Get_Requisition($Requisition_ID);
        $Store_Requesting_Name = Get_Sub_Department($Requisition['Store_Need'])['Sub_Department_Name'];
        $Store_Issuing_Name = Get_Sub_Department($Requisition['Store_Issue'])['Sub_Department_Name'];
    }
$sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='requisition' AND document_number='$Requisition_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_if_all_approve_result)>0){
                $hidden="style='display:none'";
                $display="display:none";
                $readonly="readonly='readonly'";
            }else{
                $hidden="";
                $display="";
                $readonly="";
            }
    echo "<table width='100%' border='0'><tbody>";
    echo "<tr>";
    echo "<td style='text-align: center; width: 4%;'>Sn</td>";
    echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
    echo "<td style='text-align: center; width: 10%;'>{$Store_Requesting_Name} Balance</td>";
    echo "<td style='text-align: center; width: 10%;'>{$Store_Issuing_Name} Balance</td>";
    echo "<td style='text-align: center; width: 10%;'>Quantity Required</td>";
    echo "<td style='text-align: center; width: 10%;'>Remark</td>";
    echo "<td style='text-align: center; width: 7%;$display' class='remove_button_column'>Remove</td>";
    echo "</tr>";

    
    $Requisition_Item_List = Get_Requisition_Items($Requisition_ID);
    $i = 1;
    foreach($Requisition_Item_List as $Requisition_Item) {
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

        echo "<td> <input type='text' $readonly
                            id='Quantity_Required_{$Requisition_Item['Requisition_Item_ID']}'
                            value='{$Requisition_Item['Quantity_Required']}'
                            onclick=\"removeZero(this)\"
                            onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Requisition_Item['Requisition_Item_ID']})\"
                            onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Requisition_Item['Requisition_Item_ID']})\" />
                            </td>";

        echo "<td> <input type='text'
                            id='Item_Remark_{$Requisition_Item['Requisition_Item_ID']}'
                            value='{$Requisition_Item['Item_Remark']}'
                            onkeypress=\"Update_Item_Remark(this.value,{$Requisition_Item['Requisition_Item_ID']})\"
                            onkeyup=\"Update_Item_Remark(this.value,{$Requisition_Item['Requisition_Item_ID']})\" />
                            </td>";

        echo "<td class='remove_button_column' $hidden > <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                            onclick='Confirm_Remove_Item(\"{$Requisition_Item['Product_Name']}\", {$Requisition_Item['Requisition_Item_ID']})' />  </td>";

        echo "</tr>";
        $i++;
    }

    echo "</tbody></table>";
?>