<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
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

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    if ($IssueManual_ID > 0 && $Item_ID > 0 ) {
        $Issue_Manual_Item_List = $_SESSION['Issue_Note_Manual']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Issue_Item_ID = count($Issue_Manual_Item_List) - 1;

        $Issue_Note_Manual_Item = array(
            "Requisition_Item_ID" => $Issue_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Balance" => $Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Quantity_Required" => 0,
            "Quantity_Issued" => 0,
            "Issue_ID" => $IssueManual_ID,
            "Item_Remark" => ''
        );
        $Issue_Manual_Item_List = array_merge($Issue_Manual_Item_List, array($Issue_Note_Manual_Item));
        $_SESSION['Issue_Note_Manual']['Items'] = $Issue_Manual_Item_List;

        echo "<table width='100%' border='0'><tbody>";
        echo "<tr>";
        echo "<td style='text-align: center; width: 4%;'>Sn</td>";
        echo "<td style='text-align: center; width: 40%;'>Item Name</td>";
        echo "<td style='text-align: center; width: 10%;'>Store Balance</td>";
        echo "<td style='text-align: center; width: 10%;'>Quantity Required</td>";
        echo "<td style='text-align: center; width: 10%;'>Quantity Issued</td>";
        echo "<td style='text-align: center; width: 10%;'>Remark</td>";
        echo "<td style='text-align: center; width: 7%;'>Remove</td>";
        echo "</tr>";

        $i = 1;
        foreach($Issue_Manual_Item_List as $Issue_Manual_Item) {
            $Issue_Note_Manual_Item_I = Get_Issue_Note_Manual_Item($IssueManual_ID, $Issue_Manual_Item['Item_ID']);
            $Previous_Quantity_Issued = 0;
            if (!empty($Issue_Note_Manual_Item_I)) {
                $Previous_Quantity_Issued= $Issue_Note_Manual_Item_I['Quantity_Issued'];
            }

            echo "<tr>";

            echo "<td> <input type='text' value='{$i}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                        id='Product_Name_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Product_Name']}'/>  </td>";

            echo "<td> <input type='text' readonly='readonly'
                        id='Store_Balance_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Store_Balance']}'/>  </td>";

            echo "<td> <input type='text'
                        id='Quantity_Required_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Quantity_Required']}'
                        onclick=\"removeZero(this)\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Required(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"/>
                        </td>";

            echo "<td> <input type='text'
                        id='Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Quantity_Issued']}'
                        onclick=\"removeZero(this)\"
                        onkeypress=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                        onkeyup=\"numberOnly(this); Update_Quantity_Issued(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\" />
                        <input type='hidden' id='Previous_Quantity_Issued_{$Issue_Manual_Item['Requisition_Item_ID']}'
                                value='{$Previous_Quantity_Issued}' />
                        </td>";

            echo "<td> <input type='text'
                        id='Item_Remark_{$Issue_Manual_Item['Requisition_Item_ID']}'
                        value='{$Issue_Manual_Item['Item_Remark']}'
                        onkeypress=\"Update_Item_Remark(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\"
                        onkeyup=\"Update_Item_Remark(this.value,{$Issue_Manual_Item['Requisition_Item_ID']},{$Issue_Manual_Item['Item_ID']})\" />
                        </td>";

            echo "<td> <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                        onclick='Confirm_Remove_Item(\"{$Issue_Manual_Item['Product_Name']}\", {$Issue_Manual_Item['Item_ID']})' />  </td>";

            echo "</tr>";
            $i++;
        }

        echo "</tbody></table>";
    }

?>