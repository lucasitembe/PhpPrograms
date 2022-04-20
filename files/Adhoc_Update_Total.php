<?php
    @session_start();
    include("./includes/connection.php");
    $total = 0;
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if($Registration_ID != 0 && $Employee_ID != 0){
        //fetch the patient_payment_cache_id based on registration id given
        $select_ppc_id = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache where
                                        Registration_ID = '$Registration_ID' and
                                            Employee_ID = '$Employee_ID' order by Patient_Payment_Cache_ID DESC limit 1") or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($select_ppc_id);
        if($num > 0){
            while($row = mysqli_fetch_array($select_ppc_id)){
                $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
            }
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
                
                <td style='text-align: right;'>
                    <b><h4 id='Total_Area'>Total : <?php echo number_format($total); ?>&nbsp;&nbsp;&nbsp;</h4>
                </td>
                <td style='text-align: right;'>
                    <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick=' return Confirm_Send_To_Cashier()'>
                </td>
                
                <?php 
                //echo "Total : ".number_format($total)."&nbsp;&nbsp;&nbsp;";            
            }else{
                //delete all details based on this record
                $delete_details = mysqli_query($conn,"delete from tbl_patient_payments_cache where
                                              Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
                if($delete_details){
            ?><!--echo "Total : 0 &nbsp;&nbsp;&nbsp;";     -->
              
            <td style='text-align: right;'>
                <b><h4 id='Total_Area'>Total : 0&nbsp;&nbsp;&nbsp;</h4>
            </td>
            <td style='text-align: right;'>
                &nbsp;
            </td>
            <?php
                }
            }
        }
    }
?>