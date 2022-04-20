<?php
    include("./includes/connection.php");
    $filter="";
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID=$_GET['Item_Category_ID'];
        if($Item_Category_ID!="All"){
           $filter.=" AND ic.Item_Category_ID = '$Item_Category_ID'"; 
        }
    }
    if(isset($_GET['Item_Subcategory_ID'])){
        $Item_Subcategory_ID=$_GET['Item_Subcategory_ID'];
        if($Item_Subcategory_ID!="All"){
           $filter.="AND it.Item_Subcategory_ID = '$Item_Subcategory_ID'"; 
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
            $sql_select_items_result=mysqli_query($conn,"SELECT Item_ID,Product_Code,Product_Name,item_folio_number FROM tbl_items it,tbl_item_subcategory isc,tbl_item_category ic WHERE it.Item_Subcategory_ID=isc.Item_Subcategory_ID AND isc.Item_Category_ID=ic.Item_Category_ID AND it.Status='Available' $filter ORDER BY Product_Name,item_folio_number ASC LIMIT 100") or die(mysqli_error($conn));
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
 