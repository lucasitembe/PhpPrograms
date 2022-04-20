<?php
	include("./includes/connection.php");
?>
<legend style="padding:5px;color:white;background-color: #006400;text-align:left;"><b>SUB DEPARTMENTS LIST</b></legend>
    <table width="100%">
      <tr><td width="10%"><b>SN</b></td><td width="75%"><b>SUB DEPARTMENT NAME</b></td><td style="text-align: center;"><b>ACTION</b></td></tr>
      <tr><td colspan="3"><hr></td></tr>
      <?php
        $temp = 0;
        $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from 
                              tbl_department dep, tbl_sub_department sd where
                              dep.Department_ID = sd.Department_ID and
                              dep.Department_Location in('Pharmacy','Storage And Supply')
                              order by Sub_Department_Name") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
          while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_ID = $data['Sub_Department_ID'];
            $check = mysqli_query($conn,"select Sub_Department_ID from tbl_stock_balance_sub_departments where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $check_num = mysqli_num_rows($check);
            if($check_num == 0){
              echo '<tr>
                    <td>'.++$temp.'</td><td>'.ucwords(strtolower($data['Sub_Department_Name'])).'</td>
                    <td style="text-align: center;"><input type="button" value="ADD" class="art-button-green" onclick="Check_Sub_Department_Number('.$data['Sub_Department_ID'].')"></td>
                  </tr>';
            }
          }
        }else{
          echo '<tr><td colspan="3"><b>NO DEPARTMENT SELECTED</b></td></tr>';
        }
      ?>
    </table>