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
    
    if(isset($_SESSION['Requisition_ID'])){
        $Requisition_ID = $_SESSION['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        //check if there is at least one item
        $get_details = mysqli_query($conn,"select Requisition_Item_ID from tbl_requisition_items where
                                   Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
?>
            <input type='button' class='art-button-green' value='SUBMIT REQUISITION' onclick='Confirm_Submit_Requisition()'>
<?php
        }
    }
?>