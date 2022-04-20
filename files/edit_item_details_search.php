<?php
    include("./includes/connection.php");
    $filter="";
    if(isset($_GET['folio_number'])){
        $folio_number=$_GET['folio_number'];
        if(!empty($folio_number)){
           $filter.="WHERE item_folio_number = '$folio_number' AND Status='Available'"; 
        }
    }
    if(isset($_GET['item_name'])){
        $item_name=$_GET['item_name'];
        if($item_name!=""){
           $filter.="WHERE Product_Name LIKE '%$item_name%' AND Status='Available'"; 
        }
    }
    
?>
<table class="table table-bordered" style="background: #FFFFFF">
        <tr>
            <th style="width:50px">
                S/No.
            </th>
            <th style="width:45%">ITEM NAME</th>
            <th style="width:20%">PRODUCT CODE</th>
            <th style="width:20%">FOLIO NUMBER</th>
            <th style="width:10%">UPDATE</th>
        </tr>
        <?php 
            $sql_select_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Code,Product_Name,item_folio_number FROM tbl_items  $filter ORDER BY Product_Name,item_folio_number ASC LIMIT 100") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_items_result)>0){
                $i=1;
               while($item_rows=mysqli_fetch_assoc($sql_select_items_result)){
                  $Item_ID= $item_rows['Item_ID'];
                  $Product_Code= $item_rows['Product_Code'];
                  $Product_Name= $item_rows['Product_Name'];
                  $item_folio_number= $item_rows['item_folio_number'];
                  echo "<tr>
                            <td>$i</td>
                            <td>$Product_Name</td>
                            <td style='text-align:center'>$Product_Code</td>
                            <td><input type='text' value='$item_folio_number' id='$Item_ID' class='form-control' style='text-align:center' placeholder='enter folio number'></td>
                            <td>
                                <button class='art-button-green' onclick='update_folio_number(\"$Item_ID\")'>UPDATE</button>
                            </td>
                        </tr>
                      ";
                  $i++;
               } 
            }
        ?>
    </table>
 