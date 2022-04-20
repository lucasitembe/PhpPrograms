<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['Pharmacy'])){
            if(isset($_SESSION['userinfo']['Pharmacy'])){
                if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    @session_start();
                    if(!isset($_SESSION['Pharmacy_Supervisor'])){
                        header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
                }
            }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
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
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get requisition id
    if(isset($_SESSION['General_Requisition_ID'])){
        $Requisition_ID = $_SESSION['General_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }


    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Submitted', Sent_Date_Time = (select now()) where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        if($update_sql){
                $_SESSION['Requisition_Preview'] = $_SESSION['General_Requisition_ID'];
                unset($_SESSION['General_Requisition_ID']);
                header("Location: ./requisitionpreview.php?RequisitionsPreview=RequisitionsPreviewThisPage");
        }else{
            header("Location: ./generalrequisition.php?status=new&NPO=True&Requisition=RequisitionThisPage");
        }
    }
?>