 <?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['New_Requisition'])){
        unset($_SESSION['Reagents_Requisition_ID']);
        header("Location: ./deptrequisition.php?Section=Laboratory&DepartmentRequisition=DepartmentRequisitionThisPage");
    }if(isset($_GET['Pending_Requisition']) && isset($_GET['Requisition_ID'])){
        $_SESSION['Reagents_Requisition_ID'] = $_GET['Requisition_ID'];
        header("Location: ./deptrequisition.php?Section=Laboratory&DepartmentRequisition=DepartmentRequisitionThisPage");
    }else{
        echo 'Sorry!!! Something was not normal';
    }
?>