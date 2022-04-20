<?php
    @session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }
    
    if($Purchase_Order_ID != 0){
        //get sub department id & sub department name
        $select_Sub_Department_Name = mysqli_query($conn,"select sd.Sub_Department_Name, sd.Sub_Department_ID
                                                    from tbl_purchase_order po, tbl_sub_department sd where
                                                        po.Sub_Department_ID = sd.Sub_Department_ID and
                                                            po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_Sub_Department_Name);
        
        if($num > 0){
            while($row = mysqli_fetch_array($select_Sub_Department_Name)){
                $Sub_Department_Name = $row['Sub_Department_Name'];
                $Sub_Department_ID = $row['Sub_Department_ID'];
            }
        }else{
            $Sub_Department_Name = '';
            $Sub_Department_ID = 0;
        }
    }else{
        $Sub_Department_Name = '';
        $Sub_Department_ID = 0;
    }
    
    echo "<select name='Store_Need' id='Store_Need' required='required'>";
    echo "<option value='".$Sub_Department_ID."'>".$Sub_Department_Name."</option>";
    echo "</select>";
    
?>