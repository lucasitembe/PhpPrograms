<?php
    include("./includes/connection.php");
    $Select_Price='';
    if(isset($_GET['Food_Name'])&&($_GET['Food_Name']!='')){
        $Food_Name = $_GET['Food_Name'];
        $Food_Standard_Bill = $_GET['Food_Standard_Bill'];
     //   $Guarantor_Name = $_GET['Guarantor_Name'];
        
        if($Food_Standard_Bill=='standard_price'){
         
            $Select_Price = "select Selling_Price_Standard as price from tbl_food_menu f
                                    where f.Food_Menu_ID = '$Food_Name' ";
            }
			else{
                $Select_Price = "select Selling_Price_Quality as price from tbl_food_menu f
                                    where f.Food_Menu_ID = '$Food_Name' ";
            }
                $result = @mysqli_query($conn,$Select_Price);
                $row = @mysqli_fetch_assoc($result);
                echo $row['price'];
    }else{
		echo 'none';
	}
?>