<?php
    @session_start();
    include("./includes/connection.php");
    $total = 0;
    if(isset($_GET['Patient_Payment_Cache_ID'])){
        $Patient_Payment_Cache_ID = $_GET['Patient_Payment_Cache_ID'];
    }else{
        $Patient_Payment_Cache_ID = 0;
    }
    
    if($Patient_Payment_Cache_ID != 0){
        $select_items_number = mysqli_query($conn,"select * from tbl_patient_payment_item_list_cache
                                            where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
        
        $num_of_rows = mysqli_num_rows($select_items_number);
        if($num_of_rows > 0){
            //get the new total
            while($row = mysqli_fetch_array($select_items_number)){
                $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
            }
            ?>
                <b><?php echo $num_of_rows; ?> Item<?php if($num_of_rows > 1) echo 's'; ?>,  Total : <?php echo number_format($total); ?>&nbsp;&nbsp;&nbsp;</b>
            <?php 
            //echo "Total : ".number_format($total)."&nbsp;&nbsp;&nbsp;";            
        }
    }
?>
<input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>