<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){         
            if(isset($_GET['New_Bill'])){
                unset($_SESSION['Bill_ID']);
                header("Location: ./eclaimbilling.php?Status=NewBill&EclaimBilling=EclaimBillingThisPage");
            }elseif(isset($_GET['Previous_Bills'])){
                unset($_SESSION['Bill_ID']);
                header("Location: ./billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage");
            }else{
                echo 'Something was wrong!!';
            }
            
        }else{
            header("Location: ./index.php?InvalidPrivilege=True");
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>