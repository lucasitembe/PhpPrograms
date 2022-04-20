<?php
    @session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }
    
    if($Purchase_Order_ID != 0){
        //get supplier id
        $select_supplier_id = mysqli_query($conn,"select Supplier_ID from tbl_purchase_order where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_supplier_id);
        
        if($num > 0){
            while($row = mysqli_fetch_array($select_supplier_id)){
                $Supplier_ID = $row['Supplier_ID'];
            }
        }else{
            $Supplier_ID = 0;
        }
    }else{
        $Supplier_ID = 0;
    }
    
    //get supplier name
    $get_Supplier_name = mysqli_query($conn,"select * from tbl_supplier where Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_Supplier_name);
    if($no > 0){
        echo "<select name='Supplier_ID' id='Supplier_ID' required='required'>";
            $select_Supplier = mysqli_query($conn,"select * from tbl_supplier where
                                                Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
            while($row = mysqli_fetch_array($select_Supplier)){
                    echo "<option value='".$row['Supplier_ID']."'>".$row['Supplier_Name']."</option>";
            }
            echo '</select>';
    }
?>