<?php
    include("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/items.php");

    if(isset($_GET['Classification'])){
        $Classification = $_GET['Classification'];
    }else{
        $Classification = '';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
?>


<table width=100%>
    <?php
        $Grand_Stock = 0;

        $Title = '<tr><td width="5%"><b>SN</b></td><td><b>ITEM NAME</b></td>';

        if($Sub_Department_ID == 0){
            $Sub_Department_List = Get_Stock_Balance_Sub_Departments();
        }else{
            $Sub_Department_List = Get_Stock_Balance_Sub_Departments($Sub_Department_ID);
        }

        foreach($Sub_Department_List as $Sub_Department) {
            $Title .= "<td width='10%' style='text-align: center;'><b>".strtoupper($Sub_Department['Sub_Department_Name'])."</b></td>";
        }

        $Title .= ' <td width="8%" style="text-align: center;"><b>TOTAL BALANCE</b></td>';
        $Title .= ' <td width="8%" style="text-align: right;"><b>BUYING PRICE</b>&nbsp;&nbsp;&nbsp;</td>';
        $Title .= ' <td width="8%" style="text-align: right;"><b>STOCK VALUE</b></td></tr>';

        echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';
        echo $Title;
        echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';

        $Classification_Statement = "";
        if (strtolower($Classification) != "all") {
            $Classification_Statement = "AND Classification = '{$Classification}'";
        }

        $sql = "SELECT Item_ID, Product_Name, Last_Buy_Price
                FROM tbl_items
                WHERE Can_Be_Stocked = 'yes'
                {$Classification_Statement}
                AND Product_Name LIKE '%$Search_Value%'
                ORDER BY Product_Name";

        $Item_List_Result = Query_DB($sql);

        $hasError = $Item_List_Result["error"];
        if (!$hasError) {
            $Item_List = $Item_List_Result["data"];
            if(!empty($Item_List)) {
                $temp = 0;
                foreach($Item_List as $Item) {
                    $Item_ID = $Item['Item_ID'];
                    $Total_Items = 0;
                    $Last_Buy_Price = Get_Last_Buy_Price($Item_ID);
                    echo "<tr><td>".++$temp."</td><td>".ucwords(strtolower($Item['Product_Name']))."</td>";

                    foreach($Sub_Department_List as $Sub_Department) {
                        $Item_Balance = 0;
                        $Get_Item_Balance = Get_Item_Balance($Item_ID, $Sub_Department['Sub_Department_ID']);
                        if (!empty($Get_Item_Balance)) {
                            $Item_Balance = $Get_Item_Balance['Item_Balance'];
                            if($Item_Balance > 0){
                                $Total_Items += $Item_Balance;
                            }
                        }
                        echo "<td style='text-align: center;'>";
                        if($Item_Balance < 0){ echo "<span style='color: #037CB0;'>"; }
                            echo $Item_Balance."";
                        if($Item_Balance < 0){ echo "*</span>"; }
                        echo "</td>";
                    }

                    echo "<td style='text-align: center;'>".$Total_Items."</td>";
                    echo "<td style='text-align: right;'>".number_format($Last_Buy_Price)."&nbsp;&nbsp;&nbsp;</td>";

                    $Stock_Value = ($Total_Items * $Last_Buy_Price);
                    if($Stock_Value > 0){
                        echo "<td style='text-align: right;'>".number_format($Stock_Value)."</td></tr>";
                        $Grand_Stock += $Stock_Value;
                    }else{
                        echo "<td style='text-align: right;'>0</td></tr>";
                    }

                    if(($temp%20) == 0 && $temp != 200){
                        echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';
                        echo $Title;
                        echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';
                    }

                }


                echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';
                echo '<tr><td colspan="'.(4+count($Sub_Department_List)).'" style="text-align: right;"><b>ESTIMATED GRAND TOTAL</b></td>
                        <td style="text-align: right;"><b>'.number_format($Grand_Stock).'&nbsp;&nbsp;&nbsp;</b></td>
                    </tr>';
                echo '<tr><td colspan="'.(5+count($Sub_Department_List)).'"><hr></td></tr>';
            }
        } else {
            echo $Item_List_Result["errorMsg"];
        }
    ?>
</table>