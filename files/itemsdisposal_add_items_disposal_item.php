<?php
    include_once("./includes/connection.php");
    include_once("./functions/itemsdisposal.php");

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

    if ($Disposal_ID > 0 && $Item_ID > 0 ) {
        $Insert_Items_Disposal_Item = Insert_DB("tbl_disposal_items", array(
            "Item_ID" => $Item_ID,
            "Quantity_Disposed" => 0,
            "Disposal_ID" => $Disposal_ID
        ));

        $hasError = $Insert_Items_Disposal_Item["error"];
        if (!$hasError) {
            $Insert_Items_Disposal_Item_ID = $Insert_Items_Disposal_Item["id"];
        } else {
            echo $Insert_Items_Disposal_Item['errorMsg'];
        }

        echo "<table width='100%' border='0'><tbody>";
        echo "<tr>";
        echo "<td style='text-align: center; width: 4%;'>Sn</td>";
        echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
        echo "<td style='text-align: center; width: 10%;'>Batch No</td>";
        echo "<td style='text-align: center; width: 10%;'>Expire Date</td>";
        echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
        echo "<td style='text-align: center; width: 10%;'>Quantity Adjusted</td>";
        echo "<td style='text-align: center; width: 10%;'>Balance After Adjust</td>";
        echo "<td style='text-align: center; width: 10%;'>Remark</td>";
        echo "<td style='text-align: center; width: 7%;'>Remove</td>";
        echo "</tr>";

        $Items_Disposal_Item_List = Get_Items_Disposal_Items($Disposal_ID);
        $i = 1;
        foreach($Items_Disposal_Item_List as $Disposal_Item) {
            echo "<tr style='backgroud-color:#fff'>";

            echo "<td> <input type='text' style='text-align:center' value='{$i}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly' id='Product_Name_{$Disposal_Item['Disposal_Item_ID']}' value='{$Disposal_Item['Product_Name']}'/>  </td>";

            $date = $Disposal_Item['expire_date'] == "0000-00-00" ? "" : $Disposal_Item['expire_date'];
            echo "<td> <input type='text' id='batch_{$Disposal_Item['Disposal_Item_ID']}' style='text-align:center' value='{$Disposal_Item['batch_no']}' placeholder='Batch No'>  </td>";
            echo "<td> <input type='date' id='expire_{$Disposal_Item['Disposal_Item_ID']}' style='text-align:center;width:100%;padding:3px' value='{$date}' placeholder='Expire Date'></td>";

            echo "<td> <input type='text' readonly='readonly' style='text-align:center'
            id='Store_Balance_{$Disposal_Item['Disposal_Item_ID']}'
            class='validate_issueing Store_Balance_$i' trg='$i'     
            value='{$Disposal_Item['Store_Balance']}'/>  </td>";

            echo "<td> <input type='text' style='text-align:center'
                                id='Quantity_Disposed_{$Disposal_Item['Disposal_Item_ID']}'
                                class='validate_issueing Quantity_Disposed_$i' trg='$i'
                                value='{$Disposal_Item['Quantity_Disposed']}'
                                onclick=\"removeZero(this)\"
                                onkeypress=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']})\"
                                onkeyup=\"numberOnly(this); Update_Quantity_Disposed(this.value,{$Disposal_Item['Disposal_Item_ID']})\"/>
                                </td>";

            $Balance_After_Disposal = $Disposal_Item['Store_Balance'] - $Disposal_Item['Quantity_Disposed'];

            echo "<td> <input type='text' style='text-align:center'
                                id='Balance_After_Disposal_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Balance_After_Disposal}' readonly />
                                </td>";

            echo "<td> <input type='text' style='text-align:center'
                                id='Item_Remark_{$Disposal_Item['Disposal_Item_ID']}'
                                value='{$Disposal_Item['Item_Remark']}'
                                onkeypress=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']})\"
                                onkeyup=\"Update_Item_Remark(this.value,{$Disposal_Item['Disposal_Item_ID']})\" />
                                </td>";

            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' style='text-align:center'
                                onclick='Confirm_Remove_Item(\"{$Disposal_Item['Product_Name']}\", {$Disposal_Item['Disposal_Item_ID']})' />  </td>";

            echo "</tr>";
            $i++;
        }

        echo "</tbody></table>";
    }

?>




