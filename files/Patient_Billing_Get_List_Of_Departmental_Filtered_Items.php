<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID']; 
    }else{
        $Item_Category_ID = 0;
    }
    
    if(isset($_GET['Item_Name'])){
      $Item_Name = mysqli_real_escape_string($conn,$_GET['Item_Name']);
    }else{
        $Item_Name = '';
    }
    
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name']; 
    }else{
        $Guarantor_Name = 0;
    }

    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = '';
    }

    if(strtolower($Type_Of_Check_In) != 'radiology' && strtolower($Type_Of_Check_In) != 'procedure' && strtolower($Type_Of_Check_In) != 'laboratory'){
        if($Item_Category_ID == 'All'){
            $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                (t.Consultation_type <> 'radiology' and t.Consultation_type <> 'procedure' and t.Consultation_type <> 'laboratory') and
                                Product_Name like '%$Item_Name%' and t.Status='Available'
                                order by t.Product_Name";
        }else{
            $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                c.Item_Category_ID = '$Item_Category_ID' and
                                (t.Consultation_type <> 'radiology' and t.Consultation_type <> 'procedure' and t.Consultation_type <> 'laboratory') and
                                Product_Name like '%$Item_Name%' and t.Status='Available' order by t.Product_Name";
        }
    }else{
        if($Item_Category_ID == 'All'){
            $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                t.Consultation_type = '$Type_Of_Check_In' and t.Status='Available' and
                                Product_Name like '%$Item_Name%'
                                order by t.Product_Name";
        }else{
            $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                c.Item_Category_ID = '$Item_Category_ID' and
                                t.Consultation_type = '$Type_Of_Check_In' and t.Status='Available' and
                                Product_Name like '%$Item_Name%' order by t.Product_Name";
        }
    }
    
    //echo $Select_Items;
    $result = mysqli_query($conn,$Select_Items);
?>
    
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
                        
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>');">
                   
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</lable></td></tr>";
            }
        ?> 
    </table>