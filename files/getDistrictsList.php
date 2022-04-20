<?php
    include("./includes/connection.php");
    if(isset($_GET['Region_ID'])){
        $Region_ID = $_GET['Region_ID'];
    }else{
        $Region_ID = 0;
    }
    
    $Select_District = "select * from tbl_district d, tbl_regions r
                            where d.region_id = r.region_id and r.Region_ID = '$Region_ID'";
    $result = mysqli_query($conn,$Select_District);
    ?> 
    <?php
             echo "<option selected='selected' value='0'>All</option>";
    while($row = mysqli_fetch_array($result)){
        //if($Region_ID == 0)
   
        ?>
        <option value="<?php echo $row['District_ID']?>"><?php echo $row['District_Name']; ?></option>
    <?php
    }
?> 