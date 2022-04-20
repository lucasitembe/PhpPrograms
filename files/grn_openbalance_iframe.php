<?php
    include("./includes/connection.php"); 
    
        if(isset($_GET['grn_id'])){
        $grn_id = $_GET['grn_id'];
    }else{
    $requision_id=0;
    }
    echo '<center><table width =100% border=1 >';
    echo '<tr><td><b>S/N</b></td>
                <td><b>ITEM DESCRIPTION</b></td>
                            <td><b>QUANTITY RECEIVED</b></td>
                            <td><b>UNIT COST</b></td><td><b>AMOUNT</b></td>'; 

    $select_Transaction_Items = mysqli_query($conn,"select * from tbl_grnopenbalance_items where grn_openbalacne_id='$grn_id'"); 

    $i=1; 
    $averallCost=0;  
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        $total=$row['Price'] * $row['quantity_received'];
        echo "<tr><td>".$i."</td>";
        echo "<td>".$row['item_name']."</td>";
        echo "<td>".$row['quantity_received']."</td>";
         echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
         echo "<td style='text-align:right;'>".number_format($total)."</td>";
       echo "</tr>";
    $averallCost+=$total;
    $i++;
    }
?>
 <tr>
    <td colspan="3"></td><td><b>Grand Total:</b><select name='' onchange="getTotal()" id='cVat' onload="getTotal()">
                                                    <option value="nVat" >without VAT</option>
                                                    <option value="wVat" >with VAT</option>
                                                </select>
    </td><td style='text-align:right;'><div id='total'></div></td>
</tr>
</table>
</center>
<script>
function getTotal(){
    if(document.getElementById('cVat').value=='nVat'){
        document.getElementById('total').innerHTML =<?php echo $averallCost; ?>;
    }else if(document.getElementById('cVat').value=='wVat'){
        document.getElementById('total').innerHTML =<?php echo $averallCost+(0.18*$averallCost); ?>;
    }
}
window.onload=getTotal();
</script>