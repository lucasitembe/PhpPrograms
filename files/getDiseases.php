<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $subcategory_ID = $_GET['subcategory_ID']; 
    }else{
        $subcategory_ID = 0;
    }
    if(isset($_GET['disease_name'])){
      $disease_name = mysqli_real_escape_string($conn,$_GET['disease_name']);
    }else{
        $disease_name = '';
    }
    
    if($subcategory_ID == 'All'){
        $Select_diseases = "SELECT * FROM tbl_disease WHERE disease_name LIKE '%$disease_name%'";
    }else{
        $Select_diseases = "SELECT * FROM tbl_disease
                            WHERE subcategory_ID = '$subcategory_ID'
                            AND disease_name LIKE '%$disease_name%'";
    }
                            

    $result = mysqli_query($conn,$Select_diseases);
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