<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    @session_start();
                    if(!isset($_SESSION['Storage_Supervisor'])){ 
                        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
                }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['Edit_General_Order_ID'])){
        $Store_Order_ID = $_SESSION['Edit_General_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }
    
    if($Store_Order_ID != 0 && $Store_Order_ID != '' && $Store_Order_ID != null){
        //check if there is at least one item
        $get_details = mysqli_query($conn,"select Order_Item_ID from tbl_store_order_items where
                                   Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
?>
            <input type='button' class='art-button-green' value='SUBMIT STORE ORDER' onclick='Confirm_Submit_Store_Order()'>
<?php
        }
    }
?>