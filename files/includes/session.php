<?php
    if(isset($_GET['SessionCategory'])){
        $SessionCategory = $_GET['SessionCategory'];
    }else{
        $SessionCategory = '';
    }
    
    if(strtolower($SessionCategory) == 'radiology'){
        if(isset($_SESSION['userinfo']['Radiology_Works'])) {
            if($_SESSION['userinfo']['Radiology_Works'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                    @session_start();
                    if(!isset($_SESSION['Radiology_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=$SessionCategory&InvalidSupervisorAuthentication=yes");
                    }
            }
        }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }
    
    
    
    
?>