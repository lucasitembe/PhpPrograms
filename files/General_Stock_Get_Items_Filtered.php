<?php
  include("./includes/connection.php");

  if(isset($_GET['Item_Category_ID'])){
    $Item_Category_ID = $_GET['Item_Category_ID'];
  }else{
    $Item_Category_ID = '';
  }

  if(isset($_GET['Item_Subcategory_ID'])){
    $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
  }else{
    $Item_Subcategory_ID = '';
  }

  if(isset($_GET['Sub_Department_ID'])){
    $Temp_Sub_Department_ID = $_GET['Sub_Department_ID'];
  }else{
    $Temp_Sub_Department_ID = '';
  }
?>


<table width=100%>
<?php
  $Grand_Stock = 0;
  $Title = '<tr><td width="5%"><b>SN</b></td><td><b>ITEM NAME</b></td>';
  if($Temp_Sub_Department_ID == 0){
    $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                          sd.Sub_Department_ID = sb.Sub_Department_ID order by Sub_Department_Name") or die(mysqli_error($conn));
  }else{
    $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                          sd.Sub_Department_ID = sb.Sub_Department_ID and sd.Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
  }
  $num = mysqli_num_rows($select);
  if($num > 0){
    while ($data = mysqli_fetch_array($select)) {
      $Title .= "<td width='10%' style='text-align: right;'><b>".strtoupper($data['Sub_Department_Name'])."</b>&nbsp;&nbsp;&nbsp;</td>";
    }
  }
  $Title .= ' <td width="8%" style="text-align: right;"><b>TOTAL BALANCE</b>&nbsp;&nbsp;&nbsp;</td>';
  $Title .= ' <td width="8%" style="text-align: right;"><b>AVERAGE PRICE</b>&nbsp;&nbsp;&nbsp;</td>';
  $Title .= ' <td width="8%" style="text-align: right;"><b>STOCK VALUE</b>&nbsp;&nbsp;&nbsp;</td></tr>';

  echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
  echo $Title;
  echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
  //get items
  $temp = 0;
  if($Item_Category_ID == 0 && $Item_Subcategory_ID == 0){
    $select = mysqli_query($conn,"select Item_ID, Product_Name, Last_Buy_Price from tbl_items where Item_Type = 'Pharmacy' order by Product_Name") or die(mysqli_error($conn));
  }else{
    if($Item_Subcategory_ID == 0){
      $select = mysqli_query($conn,"select Item_ID, Product_Name, Last_Buy_Price from tbl_items i, tbl_item_subcategory isc, tbl_item_category ic where 
                              i.Item_Type = 'Pharmacy' and
                              isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
                              ic.Item_Category_ID = isc.Item_Category_ID and
                              ic.Item_Category_ID = '$Item_Category_ID' order by Product_Name") or die(mysqli_error($conn));
    }else{
      $select = mysqli_query($conn,"select Item_ID, Product_Name, Last_Buy_Price from tbl_items where Item_Type = 'Pharmacy' and Item_Subcategory_ID = '$Item_Subcategory_ID' order by Product_Name") or die(mysqli_error($conn));
    }
  }
  $num = mysqli_num_rows($select);
  if($num > 0){
    while ($data = mysqli_fetch_array($select)) {
      $Item_ID = $data['Item_ID'];
      $Total_Items = 0;
      echo "<tr><td>".++$temp."</td><td>".ucwords(strtolower($data['Product_Name']))."</td>";
      //get sub departments
      if($Temp_Sub_Department_ID == 0){
        $get_departments = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                                              sd.Sub_Department_ID = sb.Sub_Department_ID order by Sub_Department_Name") or die(mysqli_error($conn));
      }else{
        $get_departments = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                                              sd.Sub_Department_ID = sb.Sub_Department_ID and sd.Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
      }
      
      $num = mysqli_num_rows($get_departments);
      if($num > 0){
        while ($dt = mysqli_fetch_array($get_departments)) {
          $Sub_Department_ID = $dt['Sub_Department_ID'];
          $get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
          $balance_num = mysqli_num_rows($get_balance);
          if($balance_num > 0){
            while ($bl = mysqli_fetch_array($get_balance)) {
              $Item_Balance = $bl['Item_Balance'];
            }
          }else{
            $Item_Balance = 0;
            mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID,Item_Balance) values('$Item_ID','$Sub_Department_ID',0)") or die(mysqli_error($conn));
          }
          if($Item_Balance < 1){ $Item_Balance = 0; }
          $Total_Items += $Item_Balance;
          echo "<td style='text-align: right;'>".$Item_Balance."&nbsp;&nbsp;&nbsp;</td>";
        }
      }
      echo "<td style='text-align: right;'>".$Total_Items."&nbsp;&nbsp;&nbsp;</td>";
      echo "<td style='text-align: right;'>".$data['Last_Buy_Price']."&nbsp;&nbsp;&nbsp;</td>";
      $Stock_Value = ($Total_Items * $data['Last_Buy_Price']);
      if($Stock_Value > 0){
        echo "<td style='text-align: right;'>".number_format($Stock_Value)."&nbsp;&nbsp;&nbsp;</td></tr>";
        $Grand_Stock += $Stock_Value;
      }else{
        echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td></tr>";                        
      }
      if(($temp%20) == 0 && $temp != 200){
        echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
        echo $Title;
        echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
      }
    }
  }
  echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
  echo '<tr><td colspan="'.(4+$num).'" style="text-align: right;"><b>ESTIMATED GRAND TOTAL</b></td>
            <td style="text-align: right;"><b>'.number_format($Grand_Stock).'&nbsp;&nbsp;&nbsp;</b></td>
        </tr>';
  echo '<tr><td colspan="'.(5+$num).'"><hr></td></tr>';
?>
</table>