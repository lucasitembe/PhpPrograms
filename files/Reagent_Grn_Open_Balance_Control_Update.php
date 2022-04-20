 <?php
    session_start();
    include("./includes/connection.php");
    if(isset($_GET['GetGrnNumber'])){
        
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
        }else{
            $Sub_Department_ID = 0;
        }
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Employee_ID != 0 && $Employee_ID != null){
            //get Grn_Open_Balance_ID
            $select_Grn_Open_Balance_ID = mysqli_query($conn,"select Grn_Open_Balance_ID from tbl_reagent_grn_open_balance where
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Grn_Open_Balance_ID);
            if($num > 0){
                while($row = mysqli_fetch_array($select_Grn_Open_Balance_ID)){
                    $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
                }
            }else{
                $Grn_Open_Balance_ID = 0;
            }
        }else{
            $Grn_Open_Balance_ID = 0;
        }
        echo $Grn_Open_Balance_ID;
        
    }elseif(isset($_GET['GetGrnCreatedDate'])){
        
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
        }else{
            $Sub_Department_ID = 0;
        }
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Employee_ID != 0 && $Employee_ID != null){
            //get Grn_Open_Balance_ID
            $select_Created_Date_Time = mysqli_query($conn,"select Created_Date_Time from tbl_reagent_grn_open_balance where
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Created_Date_Time);
            if($num > 0){
                while($row = mysqli_fetch_array($select_Created_Date_Time)){
                    $Created_Date_Time = $row['Created_Date_Time'];
                }
            }else{
                $Created_Date_Time = 0;
            }
        }else{
            $Created_Date_Time = 0;
        }
        echo $Created_Date_Time;
        
    }elseif(isset($_GET['GetGrnPreviewButtons'])){
        
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
        }else{
            $Sub_Department_ID = 0;
        }
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        
        if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Employee_ID != 0 && $Employee_ID != null){
            //get Grn_Open_Balance_ID
            $select_Grn_Open_Balance_ID = mysqli_query($conn,"select Grn_Open_Balance_ID from tbl_reagent_grn_open_balance where
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Grn_Open_Balance_ID);
            if($num > 0){
                while($row = mysqli_fetch_array($select_Grn_Open_Balance_ID)){
                    $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
                }
            }else{
                $Grn_Open_Balance_ID = 0;
            }
        }else{
            $Grn_Open_Balance_ID = 0;
        }
        if($Grn_Open_Balance_ID != 0 && $Grn_Open_Balance_ID != null && $Grn_Open_Balance_ID != ''){
        ?>
       
            <input type='button' name='Confirm_Grn' id='Confirm_Grn' onclick='Confirm_Grn_Open_Balance_Submission(<?php echo $Grn_Open_Balance_ID; ?>)' value='PROCESS GRN' class='art-button-green'>
	    <!--<a href='purchaseorderpreview.php?Grn_Open_Balance_ID=<?php //echo $Temp_Grn_Open_Balance_ID; ?>&PurchaseOrderPreview=PurchaseOrderPreviewThisPage' class='art-button-green' target='_Blank'>PREVIEW GRN</a>-->
       
        <?php }
    }elseif(isset($_GET['GetGrnUpdateDescription'])){
        
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
        }else{
            $Sub_Department_ID = 0;
        }
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        
        if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Employee_ID != 0 && $Employee_ID != null){
            //get Grn_Open_Balance_Description
            $select_Grn_Open_Balance_Description = mysqli_query($conn,"select Grn_Open_Balance_Description from tbl_reagent_grn_open_balance where
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Grn_Open_Balance_Description);
            if($num > 0){
                while($row = mysqli_fetch_array($select_Grn_Open_Balance_Description)){
                    $Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
                }
            }else{
                $Grn_Open_Balance_Description = '';
            }
        }else{
            $Grn_Open_Balance_Description = '';
        }
        echo $Grn_Open_Balance_Description;
    }elseif(isset($_GET['UpdateThisDescription'])){
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
        }else{
            $Sub_Department_ID = 0;
        }
        
        if(isset($_GET['Grn_Description'])){
            $Grn_Description = $_GET['Grn_Description'];
        }else{
            $Grn_Description = '';
        }
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        
        if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Employee_ID != 0 && $Employee_ID != null){
            //get Grn_Open_Balance_ID
            $select_Grn_Open_Balance_ID = mysqli_query($conn,"select Grn_Open_Balance_ID from tbl_reagent_grn_open_balance where
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_Grn_Open_Balance_ID);
            if($num > 0){
                while($row = mysqli_fetch_array($select_Grn_Open_Balance_ID)){
                    $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
                }
                
                if(isset($_GET['Grn_Description'])){
                    //update description
                    mysqli_query($conn,"update tbl_reagent_grn_open_balance set Grn_Open_Balance_Description = '$Grn_Description'
                                    where Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
                }
            }
        }
    }  
?>