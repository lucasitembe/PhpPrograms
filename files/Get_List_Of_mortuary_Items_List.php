<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID']; 
        $filter = "AND c.Item_Category_ID = '$Item_Category_ID' ";
    }

    // if(isset($_GET['Guarantor_Name'])){
    //     $Guarantor_Name = $_GET['Guarantor_Name']; 
    // }else{
    //     $Guarantor_Name = 0;
    // }
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID']; 
    }else{
        $Sponsor_ID = 0;
    }
    
    $Select_Items = "SELECT * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and
                        t.Item_ID=ip.Item_ID and
                        t.Visible_Status <> 'Others' and t.Consultation_Type = 'Mortuary' and t.Status='Available' AND ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' $filter order by t.Product_Name";

    $result = mysqli_query($conn,$Select_Items);
    ?>
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?> 
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Sponsor_ID; ?>');">
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
            }
        ?> 
    </table>