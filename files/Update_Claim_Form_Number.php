<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if($Registration_ID != 0 ){
        //get patient sponsor id
        $select_sponsor = mysqli_query($conn,"select Sponsor_ID from tbl_patient_registration
                                        where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $num_rec = mysqli_num_rows($select_sponsor);
        if($num_rec > 0){
            while($row = mysqli_fetch_array($select_sponsor)){
                $Sponsor_ID = $row['Sponsor_ID'];
            }
        }else{
            $Sponsor_ID = 0;
        }
    }
    
    if($Sponsor_ID != 0){
    
        //check claim form number status
        $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor
                                            where sponsor_id = '$Sponsor_ID'") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select_claim_status);
        if($num_rows > 0){
            while($row = mysqli_fetch_array($select_claim_status)){
                $Claim_Number_Status = $row['Claim_Number_Status'];
            }
        }else{
            $Claim_Number_Status = '';
        }
        
        if(strtolower($Claim_Number_Status) == 'mandatory'){
            //check if there is any record in cache then capture claim form number
            $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_reception_items_list_cache where
                                                    Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error($conn));
            $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
            if($num_of_rows > 0){
                while($row = mysqli_fetch_array($select_Claim_Number_Status)){
                    $Selected_Claim_Form_Number = $row['Claim_Form_Number'];
                }
                echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='".$Selected_Claim_Form_Number."'></td>";
            }else{
                echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' value=''></td>";
            }
        }elseif(strtolower($Claim_Number_Status) == 'not mandatory'){
            //check if there is any record in cache then capture claim form number
            $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_reception_items_list_cache where
                                                    Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error($conn));
            $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
            if($num_of_rows > 0){
                while($row = mysqli_fetch_array($select_Claim_Number_Status)){
                    $Selected_Claim_Form_Number = $row['Claim_Form_Number'];
                }
                echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='".$Selected_Claim_Form_Number."'></td>";
            }else{
                echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>";
            }
        }
    }
?>