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
    if(isset($_GET['Pharmacy'])){ //get requisition id using session from pharmacy ~ (Pharmacy_Requisition_id)
        if(isset($_SESSION['Pharmacy_Requisition_ID'])){
            $Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
        }else{
            $Requisition_ID = 0;
        }
    }else if($_GET['Laboratory']){
        if(isset($_SESSION['Laboratory_Requisition_ID'])){
            $Requisition_ID = $_SESSION['Laboratory_Requisition_ID'];
        }else{
            $Requisition_ID = 0;
        }
    }else{
        if(isset($_SESSION['Requisition_ID'])){
            $Requisition_ID = $_SESSION['Requisition_ID'];
        }else{
            $Requisition_ID = 0;
        }
    }

    //get requisition id
    //if(isset($_SESSION['Requisition_ID'])){
    //    $Requisition_ID = $_SESSION['Requisition_ID'];
    //}else{
    //    $Requisition_ID = 0;
    //}
    //update status

    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Submitted', Sent_Date_Time = (select now()) where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        if($update_sql){
            if(isset($_GET['Pharmacy'])){
                unset($_SESSION['Pharmacy_Requisition_ID']);
                header("Location: ./pharmacypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
            }else{
                unset($_SESSION['Pharmacy_Requisition_ID']);
                header("Location: ./previousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
            }
        }else{
            if(isset($_GET['Pharmacy'])){
                header("Location: ./pharmacypendingrequisitions.php?PendingRequisitions=PendingRequisitionsThisPage");
            }else if(isset($_GET['Laboratory'])){
                header("Location: ./laboratorypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
            }else{
                //header("Location: ./requisition.php?Status=PendingRequisition&Requisition=RequisitionThisPage");
                $_SESSION['Requisition_Preview'] = $_SESSION['Requisition_ID'];
                header("Location: ./requisitionpreview.php?RequisitionsPreview=RequisitionsPreviewThisPage");
            }
        }
    }
?>