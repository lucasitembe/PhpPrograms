<style media="screen">
  #table-styles{
    width:100%;
    background: #fff;
    font-size: 18px;
  }

  #table-styles td{
    padding: 5px 5px 5px 5px;
  }
</style>
<?php
include("./includes/connection.php");

  $Item_Subcategory_ID = $_POST['Item_Subcategory_ID'];
  $Check_In_ID = $_POST['Check_In_ID'];

  $results = mysqli_query($conn,"SELECT i.Product_Name, ppl.Quantity, ppl.Price,ppl.Discount FROM tbl_items i, tbl_patient_payment_item_list ppl, tbl_item_subcategory isub, tbl_patient_payments pp WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID and i.Item_ID = ppl.Item_ID AND isub.Item_Subcategory_ID = i.Item_Subcategory_ID AND pp.Check_In_ID = '$Check_In_ID' AND i.Item_Subcategory_ID = '$Item_Subcategory_ID'");

  // echo ("SELECT i.Product_Name FROM tbl_items i, tbl_patient_payment_item_list ppl, tbl_item_subcategory isub, tbl_patient_payments pp WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID and i.Item_ID = ppl.Item_ID AND isub.Item_Subcategory_ID = i.Item_Subcategory_ID AND pp.Check_In_ID = '$Check_In_ID' AND i.Item_Subcategory_ID = '$Item_Subcategory_ID'");
  //o mysqli_num_rows($result);
  if(mysqli_num_rows($results) > 0){
    echo "<table id='table-styles'>
          <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th  style='text-align:center;'>Quantity</th>
            <th  style='text-align:right;'>Price</th>
            <th style='text-align:right;'>Total</th>
          </tr>";
        $count = 1;
        $total = 0;
        while ($row = mysqli_fetch_assoc($results)) {
          echo "<tr>
            <td>".$count."</td>
            <td>".$row['Product_Name']."</td>
            <td  style='text-align:center;'>".$row['Quantity']."</td>
            <td  style='text-align:right;'>".number_format($row['Price']-$row['Discount'])."</td>
            <td  style='text-align:right;'>".number_format(($row['Price']-$row['Discount'])*$row['Quantity'])."</td>
          </tr>";
          $count++;
          $total += (($row['Price']-$row['Discount'])*$row['Quantity']);
        }
  }
  echo "<tr><td colspan='4'  style='text-align:right;'><b>Grand Total</b></td><td  style='text-align:right;'><b>".number_format($total)."</b></td></tr>";
  echo "</table>";

 ?>
