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
    
    if($Item_Category_ID == 'All'){
        $Select_Items = "select * from tbl_reagents_items t, tbl_reagents_category c
                            where t.Reagent_Category_ID = c.Reagent_Category_ID and
                                Product_Name like '%$Item_Name%' order by t.Product_Name";
    }else{
        $Select_Items = "select * from tbl_reagents_items t, tbl_reagents_category c
                            where t.Reagent_Category_ID = c.Reagent_Category_ID and
                                c.Reagent_Category_ID = '$Item_Category_ID' and
                                    Product_Name like '%$Item_Name%' order by t.Product_Name";
    }
    
    //exit($Select_Items);
    $result = mysqli_query($conn,$Select_Items) or die(mysqli_error($conn));
    ?>
    
    
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
                        
                        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">
                   
                   <?php                   
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'>".$row['Product_Name']."</td></tr>";
            }
        ?> 
    </table>