<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Laboratory_Works'])){
                if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    //@session_start();
                    if(!isset($_SESSION['Laboratory_Supervisor'])){
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Laboratory&InvalidSupervisorAuthentication=yes");
                    }
                }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['Laboratory_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Laboratory_Requisition_ID'];
    }else{
        $Requisition_ID = 'new';
    }
    echo $Requisition_ID;
?>