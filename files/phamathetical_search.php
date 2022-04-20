<?php
include("./includes/connection.php");
    if(isset($_POST['search_value'])){
    $search_value  = $_POST['search_value'];
  $sql_select_phamathetical_item=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' AND item_kind ='generic' AND Product_Name LIKE '%$search_value%' LIMIT 50") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_phamathetical_item)>0){
                                $count=1;
                                while($phamathetical_elemetn_id_rows=mysqli_fetch_assoc($sql_select_phamathetical_item)){
                                    $phamathetical_dataelement_id=$phamathetical_elemetn_id_rows['Item_ID'];
                                    $displayname=$phamathetical_elemetn_id_rows['Product_Name'];

                                         $sql_brand_id=mysqli_fetch_assoc(mysqli_query($conn,"SELECT brand_name_id FROM tbl_phamathetical_item_brand_name WHERE phamathetical_item_id='$phamathetical_dataelement_id'"))['brand_name_id'];
                                    echo "<tr>
                                            <td>$count.</td>
                                            <td>$displayname</td>
                                            <td>
                                                <input type='button' style='color:green; ' value='>>' onclick='open_phamathetica_item(\"$phamathetical_dataelement_id\",\"$sql_brand_id\")'/>
                                            </td>
                                         </tr>";
                                    $count++;
                                }
                            }
    }
