<?php 
include("./includes/connection.php");
if(isset($_GET['Item_category_ID'])&&$_GET['Item_category_ID']!='All'){
      $Item_category_ID=$_GET['Item_category_ID'];
      $sql_select_sub_dep_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory WHERE Item_category_ID='$Item_category_ID'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_sub_dep_result)>0){
                                        $count_row=1;
                                        while($item_c_rows=mysqli_fetch_assoc($sql_select_sub_dep_result)){
                                           $Item_Subcategory_ID=$item_c_rows['Item_Subcategory_ID'];
                                           $Item_Subcategory_Name=$item_c_rows['Item_Subcategory_Name'];
                                           $enabled_disabled=$item_c_rows['enabled_disabled'];
                                           echo "<tr>
                                                    <td>$count_row</td>
                                                    <td>$Item_Subcategory_Name</td>
                                                    <td>
                                                        <form method='POST' action=''>
                                                        <input type='text' name='Item_Subcategory_ID' hidden='hidden'value='$Item_Subcategory_ID'/>
                                                            ";
                                                            
                                                    if($enabled_disabled=="enabled"){
                                                        echo "<input type='submit' name='disable' class='art-button-green' VALUE='DISABLE ITEM SUB CATEGORY'/>";
                                                    }else{
                                                        echo "<input type='submit' name='enable' class='btn btn-danger btn-sm btn-block' VALUE='ENABLE ITEM SUB CATEGORY'/>";
                                                    }
                                           echo "
                                                        </form>
                                                    </td>
                                                </tr>";
                                           $count_row++;
                                        }
                                    }
}else{
      $sql_select_sub_dep_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_sub_dep_result)>0){
                                        $count_row=1;
                                        while($item_c_rows=mysqli_fetch_assoc($sql_select_sub_dep_result)){
                                           $Item_Subcategory_ID=$item_c_rows['Item_Subcategory_ID'];
                                           $Item_Subcategory_Name=$item_c_rows['Item_Subcategory_Name'];
                                           $enabled_disabled=$item_c_rows['enabled_disabled'];
                                           echo "<tr>
                                                    <td>$count_row</td>
                                                    <td>$Item_Subcategory_Name</td>
                                                    <td>
                                                        <form method='POST' action=''>
                                                        <input type='text' name='Item_Subcategory_ID' hidden='hidden'value='$Item_Subcategory_ID'/>
                                                            ";
                                                            
                                                    if($enabled_disabled=="enabled"){
                                                        echo "<input type='submit' name='disable' class='art-button-green' VALUE='DISABLE ITEM SUB CATEGORY'/>";
                                                    }else{
                                                        echo "<input type='submit' name='enable' class='btn btn-danger btn-sm btn-block' VALUE='ENABLE ITEM SUB CATEGORY'/>";
                                                    }
                                           echo "
                                                        </form>
                                                    </td>
                                                </tr>";
                                           $count_row++;
                                        }
                                    }
}

?>