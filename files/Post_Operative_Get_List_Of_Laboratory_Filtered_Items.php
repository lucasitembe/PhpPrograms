<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID']; 
    }else{
        $Item_Category_ID = 0;
    }
    
    if(isset($_GET['Search_Value'])){
      $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name']; 
    }else{
        $Guarantor_Name = 0;
    }
    $Sponsor_ID = $_GET['Sponsor_ID'];
    
    if($Item_Category_ID == '0'){
        $Select_Items = "SELECT t.Item_ID, t.Product_Name from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and 
                        t.Visible_Status <> 'Others' and
                        t.Status = 'Available' and
                        t.Can_Be_Sold = 'yes' and
                        t.Consultation_Type = 'Laboratory' and t.Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID = '$Sponsor_ID' AND Items_Price <> 0) AND
                        Product_Name like '%$Search_Value%' order by t.Product_Name limit 100";
    }else{
        $Select_Items = "SELECT t.Item_ID, t.Product_Name from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and
                        c.Item_Category_ID = '$Item_Category_ID' and
                        t.Visible_Status <> 'Others' and
                        t.Status = 'Available' and
                        t.Can_Be_Sold = 'yes'
                        AND t.Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID = '$Sponsor_ID' AND Items_Price <> 0) AND
                        t.Consultation_Type = 'Laboratory' and
                        Product_Name like '%$Search_Value%' order by t.Product_Name limit 100";
    }
    $result = mysqli_query($conn,$Select_Items);
    ?>
    
    
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
                        
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name_Laboratory(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>');">
                   
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
            }
        ?> 
    </table>