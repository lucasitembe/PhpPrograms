<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['IssueManual_ID'])){
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }else{
        $IssueManual_ID = 0;
    }

    if ($Item_ID > 0) {
        $Removed = false; $r = 0;
        $Issue_Note_Manual_Item_List = $_SESSION['Issue_Note_Manual']['Items'];
        foreach($Issue_Note_Manual_Item_List as $Issue_Note_Manual_Item) {
            if ($Issue_Note_Manual_Item['Item_ID'] == $Item_ID) {
                unset($Issue_Note_Manual_Item_List[$r]);
                $Removed = true;
                break;
            }
            $r++;
        }

        $_SESSION['Issue_Note_Manual']['Items'] = $Issue_Note_Manual_Item_List;

        if ($Removed) {
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

            //$Issue_Note_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
            $i = 1;
            foreach($Issue_Note_Manual_Item_List as $Issue_Manual_Item) {
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
    }
?>