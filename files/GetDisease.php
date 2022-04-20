<?php
    include("./includes/connection.php");
    if(isset($_GET['subcategory_ID'])){
        $subcategory_ID = $_GET['subcategory_ID'];
    }else{
        $subcategory_ID = 0;
    }
    if($subcategory_ID=='ALL'){
    $Select_SubCategory = "SELECT * FROM tbl_disease";    
    }
    else{
    $Select_SubCategory = "SELECT * FROM tbl_disease WHERE subcategory_ID=$subcategory_ID";
    }
    $result = mysqli_query($conn,$Select_SubCategory);
    ?>
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>"; ?>
                        
                        <input type='radio' name='selection' id='<?php echo $row['disease_ID']; ?>' value='<?php echo $row['disease_name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['disease_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>)">
                   
                   <?php
                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'>".$row['disease_name']."</td></tr>";
            }
        ?> 
    </table>