<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Supervisor_Username'])){
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }else{
        $Supervisor_Username = '';
    }
    
    if(isset($_GET['Supervisor_Password'])){
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }else{
        $Supervisor_Password = '';
    }
    
    
    
    if(isset($_SESSION['Disposal_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }
    
    
    if(isset($_GET['Supervisor_Username']) && isset($_GET['Supervisor_Password'])){
        $select = mysqli_query($conn,"select emp.Employee_ID from tbl_employee emp, tbl_privileges p where
                                emp.Employee_ID = p.Employee_ID and
                                    p.Given_Username = '$Supervisor_Username' and
                                        Given_Password = '$Supervisor_Password'") or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                $Supervisor_ID = $row['Employee_ID'];
            }
        }else{
            $Supervisor_ID = 0;
        }
        
        //get sub department id
        $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_disposal where Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_sub_department);
        if($no > 0){
            while($data = mysqli_fetch_array($select_sub_department)){
                $Sub_Department_ID = $data['Sub_Department_ID'];
            }
        }else{
            $Sub_Department_ID = 0;
        }
        
        //update balance process
        $select_details = mysqli_query($conn,"select Item_ID, Quantity_Disposed from tbl_disposal_items where Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
        if($num > 0){
            while($row = mysqli_fetch_array($select_details)){
                $Item_ID = $row['Item_ID'];
                $Quantity_Disposed = $row['Quantity_Disposed'];
                
                //get last balance
                $Select_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                    Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                $num_row = mysqli_num_rows($Select_balance);
                if($num_row > 0){
                    while($balance = mysqli_fetch_array($Select_balance)){
                        $Item_Balance = $balance['Item_Balance'];
                    }
                }else{
                    $Item_Balance = 0;
                }
                
                if($Sub_Department_ID != 0 && $Supervisor_ID != 0 && $Disposal_ID != 0){
                    //save changes
                    $change = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance - '$Quantity_Disposed' where
                                            Item_ID = '$Item_ID' and
                                                Sub_Department_ID = '$Sub_Department_ID'")  or die(mysqli_error($conn));
                    if($change){
                        //save changes to tbl_disposal_items table
                        $change2 = mysqli_query($conn,"update tbl_disposal_items set Balance_Before_Disposal = '$Item_Balance'
                                                where Item_ID = '$Item_ID' and
                                                    Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
                    }
                }
                
            }
            $result = mysqli_query($conn,"update tbl_disposal set Disposal_Status = 'submitted', Supervisor_ID = '$Supervisor_ID' where Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
            
            if($result){
                unset($_SESSION['Disposal_ID']);
                header("Location: ./previousdisposals.php?PreviousDisposals=PreviousDisposalsThisPage");
            }
        }
    }
?>