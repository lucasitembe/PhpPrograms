<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){         
            if(isset($_GET['New_Purchase_Order']) && isset($_GET['Store_Need'])){
                unset($_SESSION['Purchase_Order_ID']);
                $_SESSION['Purchase_Order_Store_Need'] = $_GET['Store_Need'];
                header("Location: ./procurementrequisitionlist.php?RequisitionList=RequisitionListThisPage");
            }

            else if(isset($_GET['New_Purchase_Order'])){
                    unset($_SESSION['Purchase_Order_ID']);
                    header("Location: ./procurementstoreorderlist.php?StoreOrderList=StoreOrderListThisPage&from=purchaseOrder");
            }

            elseif(isset($_GET['Selected_Requisition']) && isset($_GET['Requisition_ID'])){
                $_SESSION['Selected_Requisition_ID'] = $_GET['Requisition_ID'];
                header("Location: ./purchaseorder.php?NewPurchaseOrder=NewPurchaseOrderThisPage");
            }

            elseif(isset($_GET['Single_Supplier']) && isset($_GET['Selected_Store_Order']) && isset($_GET['Store_Order_ID'])){
                $_SESSION['Selected_Store_Order_ID'] = $_GET['Store_Order_ID'];
                header("Location: ./purchaseordersinglesupplier.php?NewPurchaseOrder=NewPurchaseOrderThisPage");
            }

            elseif(isset($_GET['Multi_Supplier']) && isset($_GET['Selected_Store_Order']) && isset($_GET['Store_Order_ID'])){
                $_SESSION['Selected_Store_Order_ID'] = $_GET['Store_Order_ID'];
                header("Location: ./purchaseordermultisupplier.php?NewPurchaseOrder=NewPurchaseOrderThisPage");
            }
            
            
            elseif(isset($_GET['Quick_Purchase_Order'])){
                unset($_SESSION['Purchase_Order_ID']);
                header("Location: ./quickpurchaseorderinsert.php?Status=QuickPurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
                //header("Location: ./quickpurchaseorder.php?Status=QuickPurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
            }
            
            elseif(isset($_GET['Self_Quick_Purchase_Order'])){
                unset($_SESSION['Purchase_Order_ID']);
                header("Location: ./quickpurchaseorder.php?Status=PurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
            }
            
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