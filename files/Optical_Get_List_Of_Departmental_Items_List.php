<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID']; 
    }else{
        $Item_Category_ID = 0;
    }
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID']; 
    }else{
        $Sponsor_ID = 0;
    }
    
    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = '';
    }

    if(isset($_GET['Visit_Type'])){
        $Visit_Type = $_GET['Visit_Type'];
    }else{
        $Visit_Type = '';
    }

    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name']; 
    }else{
        $Guarantor_Name = 0;
    }
    
    
    if($Item_Category_ID == '' || $Item_Category_ID == null){
        $Select_Items = "SELECT * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                    s.Item_Category_ID = c.Item_Category_ID   AND
                    t.Status='Available' and
                    t.Consultation_type = 'Optical' and
                    t.Visible_Status <> 'Others' order by t.Product_Name";
    }else{
        $Select_Items = "SELECT * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                    s.Item_Category_ID = c.Item_Category_ID and
                    c.Item_Category_ID = '$Item_Category_ID' AND
                    t.Status='Available' and
                    t.Consultation_type = 'Optical' and
                    t.Visible_Status <> 'Others' order by t.Product_Name";
    }
    
    $result = mysqli_query($conn,$Select_Items);
    ?>
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?> 
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>',<?php echo $Sponsor_ID; ?>);">
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
            }
        ?> 
    </table>