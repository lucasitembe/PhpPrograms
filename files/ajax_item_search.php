<?php
 include("./includes/connection.php");

 if(isset($_POST['item_search_value'])){
    $Product_Name = str_replace(" ", "%", $_POST['item_search_value']);
}else{
    $Product_Name = '';
}


$html = '<div id="items_list"><table class="table">';

    $sql_result=mysqli_query($conn,"SELECT * FROM tbl_items  WHERE  Status='Available' AND Product_Name like '%$Product_Name%' ") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_result)>0){
                while($category_rows=mysqli_fetch_assoc($sql_result)){
                    $Item_ID=$category_rows['Item_ID'];
                        $Product_Name=$category_rows['Product_Name'];
                        $html.= "<tr>
                                    <td>
                                        <label style='font-weight:normal'>
                                            <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
                                        </label>
                                    </td>
                                    
                            </tr>";
                }
            }else{
                $html.= "<tr>
                            <td>
                                <label style='color:red;'>
                                    SORRY, NO RESULT FOUND!
                                </label>
                            </td>
                    </tr>";
            }

        $html.= "</table></div>";

    echo $html;

?>