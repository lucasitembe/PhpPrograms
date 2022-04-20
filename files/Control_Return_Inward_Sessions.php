<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            
            if(isset($_GET['New_Return_Inward'])){
                unset($_SESSION['General_Inward_ID']);
                header("Location: ./returninwards.php?status=new&ReturnInward=ReturnInwardThisPage");
            }elseif(isset($_GET['Pending_Return_Inward']) && isset($_GET['Return_Inward_ID'])){
                $_SESSION['General_Inward_ID'] = $_GET['Return_Inward_ID'];
                header("Location: ./returninwards.php?status=new&ReturnInward=ReturnInwardThisPage");
            }elseif(isset($_GET['Previous_Return_Inward'])){
                unset($_SESSION['General_Inward_ID']);
                $Inward_ID = $_GET['Return_Inward_ID'];
                header("Location: ./returninwardpreview.php?Status=PreviousReturnInward&ReturnInwardPreview=ReturnInwardPreviewThisPage&Inward_ID=$Inward_ID");
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