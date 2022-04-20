<?php
    include("./includes/connection.php");
    if(isset($_GET['Food_Time'])){
        $Food_Time = $_GET['Food_Time'];
    }else{
        $Food_Time = 2;
    }
    
    $Select_Menu = "select * from  tbl_food_menu
                            where  	Food_Time = '$Food_Time'";
    $result = mysqli_query($conn,$Select_Menu);
    ?> 
	<select name="Food_Menu_ID" id="Food_Menu_ID" required='required' >
    <?php	
    while($row = mysqli_fetch_array($result)){
        ?>
		 <option value='<?php echo $row['Food_Menu_ID']; ?>'><?php echo ucwords(strtolower($row['Food_Name'])); ?></option>
    <?php
    }
?> </select>