<?php
    include_once("./includes/connection.php");
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/items.php");

    if(isset($_GET['IssueManual_ID'])){
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }else{
        $IssueManual_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    if(isset($_GET['constcenter'])){
        $Sub_Department_ID = $_GET['constcenter'];
    }else{
        $Sub_Department_ID = 0;
    }

    if ($IssueManual_ID > 0 && $Item_ID > 0 ) {
        //get last buying price as buying price
        $Buying_Price = Get_Last_Buy_Price($Item_ID);
        $Selling_Price = Get_Selling_Price($Item_ID,$Sub_Department_ID);
        if($Selling_Price=="not_exist"){
           $Selling_Price= $Buying_Price;
        }
        $Insert_Issue_Manual_Item = Insert_DB("tbl_issuemanual_items", array(
            "Quantity_Required" => 0,
            "Item_ID" => $Item_ID,
            "Buying_Price" => $Buying_Price,
            "Selling_Price" => $Selling_Price,
            "Quantity_Issued" => 0,
            "Issue_ID" => $IssueManual_ID
        ));

        $hasError = $Insert_Issue_Manual_Item["error"];
        if (!$hasError) {
            $Insert_Issue_Manual_Item_ID = $Insert_Issue_Manual_Item["id"];
        } else {
            echo $Insert_Issue_Manual['errorMsg'];
        }

        echo "<table width='100%' border='0'><tbody>";
        echo "<tr>";
        echo "<td style='text-align: center; width: 4%;'>Sn</td>";
        echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
        echo "<td style='text-align: center; width: 8%;'>Store Balance</td>";
        echo "<td style='text-align: center; width: 8%;'>Quantity Required</td>";
        echo "<td style='text-align: center; width: 8%;'>Quantity Issued</td>";
        echo "<td style='text-align: center; width: 8%;'>Buying Price</td>";
        echo "<td style='text-align: center; width: 8%;'>Selling Price</td>";
        echo "<td style='text-align: center; width: 10%;'>Total</td>";
        echo "<td style='text-align: center; width: 5%;'>Remove</td>";
        echo "</tr>";

        $Issue_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
        $i = 1;
        foreach($Issue_Manual_Item_List as $Issue_Manual_Item) {
            echo "<tr>";

            echo "<td> <input type='text' value='{$i}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                        id='Product_Name_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Product_Name']}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                        class='validate_issueing Store_Balance_$i' trg='$i' 
                        id='Store_Balance_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Store_Balance']}'/>  </td>";

            echo "<td> <input type='text'
                        id='Quantity_Required_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        class='validate_issueing Quantity_Required_$i' trg='$i'     
                        value='{$Issue_Manual_Item['Quantity_Required']}'
                        onclick=\"removeZero(this)\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']})\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']})\"/>
                        </td>";

            echo "<td> <input type='text'
                        id='Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        class='validate_issueing Quantity_Issued_$i' trg='$i'     
                        value='{$Issue_Manual_Item['Quantity_Issued']}'
                        onclick=\"removeZero(this); Update_Total(".$Issue_Manual_Item['Requisition_Item_ID'].",".$Issue_Manual_Item['Item_ID'].")\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']}); Update_Total(".$Issue_Manual_Item['Requisition_Item_ID'].",".$Issue_Manual_Item['Item_ID'].")\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']}); Update_Total(".$Issue_Manual_Item['Requisition_Item_ID'].",".$Issue_Manual_Item['Item_ID'].")\"
                        oninput=\"Update_Total(".$Issue_Manual_Item['Requisition_Item_ID'].",".$Issue_Manual_Item['Item_ID'].")\"/>
                        </td>";

            echo "<td> <input type='text' id='Buying_Price_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='".number_format($Issue_Manual_Item['Buying_Price'])."' readonly='readonly'/></td>";
            echo "<td> <input type='text' id='Buying_Price_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='".number_format($Issue_Manual_Item['Selling_Price'])."' readonly='readonly'/></td>";
            
            echo "<td> <input type='text' id='Total_{$Issue_Manual_Item['Requisition_Item_ID']}' style='text-align: right;' value='".number_format($Issue_Manual_Item['Quantity_Issued'] * $Issue_Manual_Item['Buying_Price'])."'  readonly='readonly'></td>";

            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                        onclick='Confirm_Remove_Item(\"{$Issue_Manual_Item['Product_Name']}\", {$Issue_Manual_Item['Requisition_Item_ID']})' />  </td>";

            echo "</tr>";
            $i++;
        }

        echo "</tbody></table>";
    }

?>