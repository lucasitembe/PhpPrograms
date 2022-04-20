<?php
    include("./includes/connection.php");
    if(isset($_GET['disease_category_ID'])){
        $disease_category_ID = $_GET['disease_category_ID'];
    }else{
        $disease_category_ID = 0;
    }
    if($disease_category_ID=='ALL'){
    $Select_SubCategory = "select * from tbl_disease_subcategory";    
    }
    else{
    $Select_SubCategory = "select * from tbl_disease_category dc, tbl_disease_subcategory dsc
                            where dc.disease_category_ID = dsc.disease_category_ID and dc.disease_category_ID = '$disease_category_ID'";   
    }
    $result = mysqli_query($conn,$Select_SubCategory) or die(mysqli_error($conn));
    ?> 
    <?php
        echo "<option>ALL</option>
		
		     ";
    while($row = mysqli_fetch_array($result)){
        ?>
        <option value='<?php echo $row['subcategory_ID']; ?>'><?php echo $row['subcategory_description']; ?></option>
    <?php
    }
?>