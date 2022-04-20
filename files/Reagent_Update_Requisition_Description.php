 <?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['Reagents_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Reagents_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        //get requisition description
        $get_details = mysqli_query($conn,"select Requisition_Description from tbl_reagents_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
            while($row = mysqli_fetch_array($get_details)){
                $Requisition_Description = $row['Requisition_Description'];
            }
        }else{
            $Requisition_Description = '';
        }
    }else{
        $Requisition_Description = '';
    }
    echo $Requisition_Description;
?>