<?php
include("./includes/connection.php");

if(isset($_POST['Item_ID'])&&isset($_POST['selected_Phamathetical_dataelement_id'])){
               $Item_ID = $_POST['Item_ID'];
                $selected_Phamathetical_dataelement_id = $_POST['selected_Phamathetical_dataelement_id'];
// $sql_select_brand_name_result=mysqli_query($conn,"SELECT phamathetical_item_id,phabrand_id,brand_name_id FROM tbl_phamathetical_item_brand_name WHERE phamathetical_item_id='$selected_Phamathetical_dataelement_id' LIMIT 50") or die(mysqli_error($conn));
//
//          if(mysqli_num_rows($sql_select_brand_name_result)>0){
//            $count=1;
//            while($items_rows=mysqli_fetch_assoc($sql_select_brand_name_result)){
//               $Item_ID=$items_rows['phabrand_id'];
//               $Brand_ID=$items_rows['brand_name_id'];
//               $phamathetical_item_id=$items_rows['phamathetical_item_id'];
//
//               $brand_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT brand_name FROM tbl_Brand_name WHERE brand_id=' $Brand_ID'"))['brand_name'];
//
//               echo "<tr>
//                         <td>$count.</td>
//                         <td>$brand_name</td>
//                         <td>
//                             <input type='button' value='X' onclick='remove_all_items_merged_to_this_value_source($Brand_ID,\"$phamathetical_item_id\")'/>
//                         </td>
//                    </tr>";
//               $count++;
//            }
// }

/*modified by Kipanga*/
  /*
    get list of the brand items
  */
  $Generic_ID = $_POST['selected_Phamathetical_dataelement_id'];

  $brand_result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '$Generic_ID'");
  $count = 1;
  while ($row  = mysqli_fetch_assoc($brand_result)) {
    extract($row);
    echo "<tr>
              <td>$count.</td>
              <td>$Product_Name</td>
              <td>
                  <input type='button' value='X' onclick='remove_all_items_merged_to_this_value_source($Item_ID,\"$Generic_ID\")'/>
              </td>
         </tr>";
    $count++;
  }
}
