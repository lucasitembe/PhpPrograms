 <?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Reagents_Requisition_ID']) && isset($_GET['Item_ID'])){
        $Requisition_ID = $_SESSION['Reagents_Requisition_ID'];
        
        //get item id
        $Item_ID = $_GET['Item_ID'];
        
        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Requisition_Item_ID from tbl_reagents_requisition_items where
                                        Requisition_ID = '$Requisition_ID' and
                                            Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);        
        if($num > 0){
            echo 'Yes';
        }else{
            echo 'No';
        }        
    }else{
        echo 'No';
    }

?>