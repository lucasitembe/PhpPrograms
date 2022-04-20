<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->

<?php

include("./includes/connection.php");
     
if(isset($_POST['action'])){
   if($_POST['action']=='ViewItem') {
      $Patient_Payment_ID=$_POST['Patient_Payment_ID'];
        $total = 0;
        $subTotal=0;
                echo '<center><table width =100% border=1>';
                echo '<tr><td><b>CHECK IN TYPE</b></td>
                <td><b>DIRECTION</b></td>
                <td><b>ITEM DESCRIPTION</b></td>
                    <td><b>PRICE</b></td>
                        <td><b>DISCOUNT</b></td>
                            <td><b>QUANTITY</b></td>
                                <td><b>SUB TOTAL</b></td>
				    <td><b>Action</b></td></tr>';


                            $select_Transaction_Items = mysqli_query($conn,
                                    "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Item_Name, Quantity,Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
	    from tbl_items t, tbl_patient_payments pp, tbl_patient_payment_item_list ppi
                where pp.Patient_Payment_ID = ppi.Patient_Payment_ID and
		    t.item_id = ppi.item_id and
		        ppi.Patient_Payment_Item_List_ID = '$Patient_Payment_ID'");
                            while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                echo "<tr><td>" . $row['Check_In_Type'] . "</td>";
                                echo "<td>" . $row['Patient_Direction'] . "</td>";
                                echo "<td><input id='item_name' type='text' value='".$row['Item_Name']."'></td>";
                                echo "<td><input type='text' id='Item_price' value='".$row['Price']."'></td>";
                                echo "<td><input id='Item_discount' type='text' value='".$row['Discount']."'></td>";
                                echo "<td><input type='text' id='Item_Quantity' value='".$row['Quantity']."'></td>";
                                $subTotal=number_format(($row['Price'] - $row['Discount']) * $row['Quantity']);
                                echo "<td><input id='subTotal' type='text' readonly='true' value='$subTotal'></td>";
                                echo "<td><input type='button' id='".$row['Patient_Payment_Item_List_ID'] . "' class='art-button updatebtn' value='Update'></td>";
                                $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                            } echo "</tr></table>";    
   }
}
                
 ?>


<script>
     $('.updatebtn').on('click',function(){
        var id=$(this).attr('id');
        var item_name=$('#item_name').val();
        var Item_price=$('#Item_price').val();
        var Item_discount=$('#Item_discount').val();
        var Item_Quantity=$('#Item_Quantity').val();
        $.ajax({
            type:'POST',
            url:"requests/Sub_Item_price.php",
            data:"action=SaveDirectItem&Patient_Payment_ID="+id+'&item_name='+item_name+'&Item_price='+Item_price+'&Item_discount='+Item_discount+'&Item_Quantity='+Item_Quantity,
             success:function(html){
               alert(html);
            }
            }); 

     });
     
     $('#Item_price,#Item_discount,#Item_Quantity').on('input',function(){
       var Item_price=$('#Item_price').val();
        var Item_discount=$('#Item_discount').val();
        var Item_Quantity=$('#Item_Quantity').val();
        var subTotal=(Item_price-Item_discount)*Item_Quantity;
        $('#subTotal').val(subTotal);     
     });
</script>

