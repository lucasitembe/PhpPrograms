<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID']; 
    }else{
        $Item_Category_ID = 0;
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name']; 
    }else{
        $Guarantor_Name = 0;
    }
    
    if($Item_Category_ID != 0){
        $Select_Items = "SELECT Product_Name, Item_ID from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        t.Consultation_Type = 'Laboratory' and
                        t.Status = 'Available' and
                        t.Can_Be_Sold = 'yes' and
                        t.Product_Name LIKE '%biopsy%' and
                        s.Item_Category_ID = c.Item_Category_ID and
                        c.Item_Category_ID = '$Item_Category_ID' and
                        t.Visible_Status <> 'Others' order by t.Product_Name limit 150";
    }else{
        $Select_Items = "SELECT Product_Name, Item_ID from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        t.Consultation_Type = 'Laboratory' and
                        t.Status = 'Available' and
                        t.Can_Be_Sold = 'yes' and
                        t.Product_Name LIKE '%biopsy%' and
                        s.Item_Category_ID = c.Item_Category_ID and
                        t.Visible_Status <> 'Others' order by t.Product_Name limit 150";
    }


    $result = mysqli_query($conn,$Select_Items) or die(mysqli_error($conn));
    ?>
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?> 
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>');">
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
            }
        ?> 
    </table>