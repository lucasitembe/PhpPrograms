<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){         
            
            if(isset($_GET['New_Requisition'])){
                unset($_SESSION['Pharmacy_Requisition_ID']);
                header("Location: ./pharmacyrequisition.php?Status=New&Requisition=RequisitionThisPage");
            }elseif(isset($_GET['Pending_Requisition']) && isset($_GET['Requisition_ID'])){
                $_SESSION['Pharmacy_Requisition_ID'] = $_GET['Requisition_ID'];
                header("Location: ./pharmacyrequisition.php?Status=New&Requisition=RequisitionThisPage");
            }elseif(isset($_GET['Quick_Requisition'])){
                unset($_SESSION['Pharmacy_Requisition_ID']);
                header("Location: ./quickrequisitioninsert.php?Status=QuickRequisition&QuickRequisition=QuickRequisitionThisPage");
            }elseif(isset($_GET['Self_Quick_Requisition'])){
                unset($_SESSION['Pharmacy_Requisition_ID']);
                header("Location: ./quickpharmacyrequisition.php?Status=QuickRequisition&QuickRequisition=QuickRequisitionThisPage&Quick=true");
            }
            
            //Pharmacy_Control_Requisition_Sessions.php?Self_Quick_Requisition=True';
            //}elseif(isset($_GET['Quick_Purchase_Order'])){
            //    unset($_SESSION['Purchase_Order_ID']);
            //    header("Location: ./quickpurchaseorderinsert.php?Status=QuickPurchaseOrder&QuickRequisition=PurchaseOrderThisPage");
            //    //header("Location: ./quickpurchaseorder.php?Status=QuickPurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
            //}elseif(isset($_GET['Self_Quick_Purchase_Order'])){
            //    unset($_SESSION['Purchase_Order_ID']);
            //    header("Location: ./quickpurchaseorder.php?Status=PurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
            //}
            
            
            else{
                echo 'Something was wrong!!';
            }
            
        }else{
            header("Location: ./index.php?InvalidPrivilege=True");
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>
echo "<td width=4%><a href='Pharmacy_Control_Requisition_Sessions.php?Requisition_ID=".$row['Requisition_ID']."&Pending_Requisition=True&Requisition=RequisitionThisPage' class='art-button-green' target='_parent'>&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a></td></tr>";