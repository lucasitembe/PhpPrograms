<?php
	include("./includes/connection.php");
?>
<legend style="padding:5px;color:white;background-color: #006400;text-align:left;"><b>SELECTED SUB DEPARTMENTS</b></legend>
<table width="100%">
  <tr><td width="10%"><b>SN</b></td><td width="75%"><b>SUB DEPARTMENT NAME</b></td><td style="text-align: center;"><b>ACTION</b></td></tr>
  <tr><td colspan="3"><hr></td></tr>
  <?php
    $temp = 0;
    $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                            sd.Sub_Department_ID = sb.Sub_Department_ID order by Sub_Department_Name") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
      while ($data = mysqli_fetch_array($select)) {
        echo '<tr>
                <td>'.++$temp.'</td><td>'.ucwords(strtolower($data['Sub_Department_Name'])).'</td>
                <td style="text-align: center;"><input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Sub_Department('.$data['Sub_Department_ID'].')"></td>
              </tr>';
      }
    }else{
      echo '<tr><td colspan="3"><b>NO DEPARTMENT FOUND</b></td></tr>';
    }
  ?>
</table>