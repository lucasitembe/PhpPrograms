<?php
    $sql_check = mysqli_query($conn,"select Check_In_ID, Type_Of_Check_In,Folio_Status from tbl_check_in
                                where Registration_ID = '$R_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    
    $no = mysqli_num_rows($sql_check);
    if($no > 0){
        while($row = mysqli_fetch_array($sql_check)){
            $Check_In_ID = $row['Check_In_ID'];
            $Type_Of_Check_In = $row['Type_Of_Check_In'];
            $Folio_Status = $row['Folio_Status'];
        }
    }else{
        $Check_In_ID = '';
        $Type_Of_Check_In = '';
        $Folio_Status = '';
    }
    
    if(strtolower($Type_Of_Check_In) == 'continuous'){
        //select the last folio number
        $select_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments
                                        where Registration_ID = '$R_ID'
                                            Order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_folio);
        if($num > 0){
            while($data = mysqli_fetch_array($select_folio)){
                $Folio_Number = $data['Folio_Number'];
            }
            //update Folio_Status
            mysqli_query($conn,"update tbl_check_in set Folio_Status = 'generated'
                            where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
        }else{
                //folio is missing, generate folio number
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                
                    //GET BRANCH ID
                    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                    //get the current date		
                    $Today_Date = mysqli_query($conn,"select now() as today");
                    while($row = mysqli_fetch_array($Today_Date)){
                        $original_Date = $row['today'];
                        $new_Date = date("Y-m-d", strtotime($original_Date));
                        $Today = $new_Date; 
                    }
                    
                    //select sponsor id & Sponsor name
                    $select_sp = mysqli_query($conn,"select Sponsor_ID, Sponsor_Name from tbl_reception_items_list_cache
                                                where Registration_ID = '$R_ID'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_sp)){
                        $Sponsor_ID = $row['Sponsor_ID'];
                        $Sponsor_Name = $row['Sponsor_Name'];
                    }
                    
                    //check if the current date and the last folio number are in the same month and year
                    //select the current folio number to check the month and year
                    $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
                                                    from tbl_folio where sponsor_id = '$Sponsor_ID' and
                                                        Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
                    $no = mysqli_num_rows($current_folio); 
                    if($no > 0){
                        while($row = mysqli_fetch_array($current_folio)){
                            $Folio_Number = $row['Folio_Number'];
                            $Folio_date = $row['Folio_date'];
                        } 
                    }else{
                        $Folio_Number = 1;
                        $Folio_date = 0;
                    }
                    
                    //check the current month and year (Remove the day part of the date)
                    $Current_Month_and_year = substr($Today,0,7); 
                    
                    //check month and year of the last folio number (Remove the day part of the date)
                    $Last_Folio_Month_and_year = substr($Folio_date,0,7); 
                    
                    //compare month and year    
                    if($Last_Folio_Month_and_year == $Current_Month_and_year){
                        mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                    values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
                        $Folio_Number = $Folio_Number + 1;
                    }else{
                        mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                    values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
                        $Folio_Number = 1;
                    }
                    
                //update Folio_Status
                mysqli_query($conn,"update tbl_check_in set Folio_Status = 'generated'
                                where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
                
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
                
            
        }
    }else{
        if(strtolower($Folio_Status) == 'pending'){
            //Generate new folio number
            
            //GENERATING FOLIO NUMBER
            //GENERATING FOLIO NUMBER
            //GENERATING FOLIO NUMBER
            //GENERATING FOLIO NUMBER
            
                //GET BRANCH ID
                $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        
                //get the current date		
                $Today_Date = mysqli_query($conn,"select now() as today");
                while($row = mysqli_fetch_array($Today_Date)){
                    $original_Date = $row['today'];
                    $new_Date = date("Y-m-d", strtotime($original_Date));
                    $Today = $new_Date; 
                }
                
                //select sponsor id & Sponsor name
                $select_sp = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name as Sponsor_Name from tbl_sponsor sp, tbl_patient_registration pr
                                            where pr.Registration_ID = '$R_ID' and pr.Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($select_sp)){
                    $Sponsor_ID = $row['Sponsor_ID'];
                    $Sponsor_Name = $row['Sponsor_Name'];
                }
                
                //check if the current date and the last folio number are in the same month and year
                //select the current folio number to check the month and year
                $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
                                                from tbl_folio where sponsor_id = '$Sponsor_ID' and
                                                    Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
                $no = mysqli_num_rows($current_folio); 
                if($no > 0){
                    while($row = mysqli_fetch_array($current_folio)){
                        $Folio_Number = $row['Folio_Number'];
                        $Folio_date = $row['Folio_date'];
                    } 
                }else{
                    $Folio_Number = 1;
                    $Folio_date = '';
                }
                
                //check the current month and year (Remove the day part of the date)
                $Current_Month_and_year = substr($Today,0,7); 
                
                //check month and year of the last folio number (Remove the day part of the date)
                $Last_Folio_Month_and_year = substr($Folio_date,0,7); 
                
                //compare month and year    
                if($Last_Folio_Month_and_year == $Current_Month_and_year){
                    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
                    $Folio_Number = $Folio_Number + 1;
                }else{
                    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
                    $Folio_Number = 1;
                }
                
                //update Folio_Status
                mysqli_query($conn,"update tbl_check_in set Folio_Status = 'generated'
                                where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
                
            //END OF GENERATING FOLIO NUMBER
            //END OF GENERATING FOLIO NUMBER
            //END OF GENERATING FOLIO NUMBER
            //END OF GENERATING FOLIO NUMBER
        }else{
            //get the last folio number
            $select_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments
                                            where Registration_ID = '$R_ID'
                                                Order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_folio);
            if($num > 0){
                while($data = mysqli_fetch_array($select_folio)){
                    $Folio_Number = $data['Folio_Number'];
                }
                //update Folio_Status
                mysqli_query($conn,"update tbl_check_in set Folio_Status = 'generated'
                                where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
            }else{
                //folio is missing, generate folio number
                
                //folio is missing so we create the new one
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                //GENERATING FOLIO NUMBER
                
                    //GET BRANCH ID
                    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                    //get the current date		
                    $Today_Date = mysqli_query($conn,"select now() as today");
                    while($row = mysqli_fetch_array($Today_Date)){
                        $original_Date = $row['today'];
                        $new_Date = date("Y-m-d", strtotime($original_Date));
                        $Today = $new_Date; 
                    }
                    
                    //select sponsor id & Sponsor name
                    $select_sp = mysqli_query($conn,"select Sponsor_ID, Sponsor_Name from tbl_reception_items_list_cache
                                                where Registration_ID = '$R_ID'") or die(mysqli_error($conn));
                    while($row = mysqli_fetch_array($select_sp)){
                        $Sponsor_ID = $row['Sponsor_ID'];
                        $Sponsor_Name = $row['Sponsor_Name'];
                    }
                    
                    //check if the current date and the last folio number are in the same month and year
                    //select the current folio number to check the month and year
                    $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
                                                    from tbl_folio where sponsor_id = '$Sponsor_ID' and
                                                        Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
                    $no = mysqli_num_rows($current_folio); 
                    if($no > 0){
                        while($row = mysqli_fetch_array($current_folio)){
                            $Folio_Number = $row['Folio_Number'];
                            $Folio_date = $row['Folio_date'];
                        } 
                    }else{
                        $Folio_Number = 1;
                        $Folio_date = 0;
                    }
                    
                    //check the current month and year (Remove the day part of the date)
                    $Current_Month_and_year = substr($Today,0,7); 
                    
                    //check month and year of the last folio number (Remove the day part of the date)
                    $Last_Folio_Month_and_year = substr($Folio_date,0,7); 
                    
                    //compare month and year    
                    if($Last_Folio_Month_and_year == $Current_Month_and_year){
                        mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                    values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
                        $Folio_Number = $Folio_Number + 1;
                    }else{
                        mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                                    values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
                        $Folio_Number = 1;
                    }
                    
                //update Folio_Status
                mysqli_query($conn,"update tbl_check_in set Folio_Status = 'generated'
                                where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
                
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
                //END OF GENERATING FOLIO NUMBER
            }
        }        
    }
?>