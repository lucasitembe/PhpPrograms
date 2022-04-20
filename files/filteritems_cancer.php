<?php
    include("./includes/connection.php");
   
    if(isset($_POST['cancer_search'])){
        if(isset($_POST['items'])){
        $Item_Name = mysqli_real_escape_string($conn,$_POST['items']);
        }else{
            $Item_Name = '';
        }
    
    $result = mysqli_query($conn,"SELECT Item_ID, Product_Name  from tbl_items where Product_Name like '%$Item_Name%' AND Consultation_Type='Pharmacy'");
    ?>
    
     
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
              echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                    ?>

                <input type='checkbox' name='selection' id='item_ID_cancer'  class='item_ID_cancer' value='<?php echo $row['Item_ID']; ?>' onclick="take_id(<?php echo $row['Item_ID']; ?>)">

                <?php 
                echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                }
            ?>
    </table>

    <?php } 
    if(isset($_POST['listfilted'])){ 
        if(isset($_POST['items'])){
        $Item_Name = mysqli_real_escape_string($conn,$_POST['items']);
        }else{
            $Item_Name = '';
        }    

    $result = mysqli_query($conn,"SELECT Item_ID, Product_Name  from tbl_items where Product_Name like '%$Item_Name%' AND Consultation_Type='Pharmacy'") or die(mysqli_error($conn));
    ?>
    <table width=100%>
        <?php 
            while($row = mysqli_fetch_array($result)){
              echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                    ?>

                <input type='checkbox' name='selection' id='item_ID_cancer_id'  class='item_ID_cancer_id' value='<?php echo $row['Item_ID']; ?>' onclick="take_id(<?php echo $row['Item_ID']; ?>)">

                <?php 
                echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                }
            ?>
    </table>
    <?php
    }