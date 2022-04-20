<?php
include("includes/connection.php");
$Payment_Item_Cache_List_ID = (isset($_POST['Payment_Item_Cache_List_ID'])) ? $_POST['Payment_Item_Cache_List_ID'] : "";
$nurse_instruments = (isset($_POST['nurse_instruments'])) ? $_POST['nurse_instruments'] : "";
$consultation_ID = (isset($_POST['consultation_ID'])) ? $_POST['consultation_ID'] : "";
$Registration_ID = (isset($_POST['Registration_ID'])) ? $_POST['Registration_ID'] : "";
$checkboxvalue = (isset($_POST['checkboxvalue'])) ? $_POST['checkboxvalue'] : "";
$Surgeon_filled = (isset($_POST['Surgeon_filled'])) ? $_POST['Surgeon_filled'] : "";
$Anesthetist = (isset($_POST['Anesthetist'])) ? $_POST['Anesthetist'] : "";
$Admision_ID = (isset($_POST['Admision_ID'])) ? $_POST['Admision_ID'] : "";
$can_proceed = (isset($_POST['can_proceed'])) ? $_POST['can_proceed'] : "";
$Employee_ID = (isset($_POST['Employee_ID'])) ? $_POST['Employee_ID'] : 0;
$checkbox = (isset($_POST['checkbox'])) ? $_POST['checkbox'] : "";
$Form_ID = (isset($_POST['Form_ID'])) ? $_POST['Form_ID'] : "";
$remark = (isset($_POST['remark'])) ? $_POST['remark'] : "";
$doc = (isset($_POST['doc'])) ? $_POST['doc'] : "";


    if(!empty($Registration_ID) && !empty($consultation_ID) && !empty($Payment_Item_Cache_List_ID)){
        if($Form_ID == 'form_1'){
            $Select_Who_ID = mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Level_Status = 1") or die(mysqli_error($conn));

                if(mysqli_num_rows($Select_Who_ID) > 0){
                    $Pre_Operative_ID = mysqli_fetch_assoc($Select_Who_ID)['Pre_Operative_ID'];

                }else{
                    $insert_sql = mysqli_query($conn,"INSERT into tbl_who_pre_operative_checklist(Employee_ID,Registration_ID,Surgeon_filled,Anesthetist,Operative_Date_Time,nurse_instruments,can_proceed,surgery,surgeon, Admision_ID, consultation_ID, Payment_Item_Cache_List_ID, Level_Status)
											values('$Employee_ID','$Registration_ID','$Surgeon_filled','$Anesthetist',NOW(),'$nurse_instruments','$can_proceed', '$Product_Name', '$Employee_Name', '$Admision_ID', '$consultation_ID', '$Payment_Item_Cache_List_ID', '1')") or die(mysqli_error($conn));

                        if($insert_sql){
                            $Pre_Operative_ID = mysqli_insert_id($conn);
                        }
                }

                if($Pre_Operative_ID > 0){
                    for ($i=0; $i < sizeof($checkbox); $i++) { 
                        $Temp_Name = $checkboxvalue[$i]; //$checkboxvalue1
                        $Temp_Value = $checkbox[$i];
                        $Temp_Remark = $remark[$i];
                        $Temp_Sn = $doc[$i];
                                            $insert_sql_2 = mysqli_query($conn,"INSERT INTO tbl_who_pre_operative_checklist_items(Task_Name, Task_Value, Remark, Pre_Operative_ID, Sn)
                                            VALUES('$Temp_Name','$Temp_Value','$Temp_Remark','$Pre_Operative_ID','$Temp_Sn')") or die(mysqli_error($conn));
                    }
                }

                if($insert_sql_2){
                    echo 200;
                }else{
                    echo 201;
                }
        }elseif($Form_ID == 'form_2'){
            $Select_Who_ID = mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Level_Status = 1") or die(mysqli_error($conn));

            if(mysqli_num_rows($Select_Who_ID) > 0){
                $Pre_Operative_ID = mysqli_fetch_assoc($Select_Who_ID)['Pre_Operative_ID'];

            }else{
                $insert_sql = mysqli_query($conn,"INSERT INTO tbl_who_pre_operative_checklist(Employee_ID,Registration_ID,Surgeon_filled,Anesthetist,Operative_Date_Time,nurse_instruments,can_proceed,surgery,surgeon, Admision_ID, consultation_ID, Payment_Item_Cache_List_ID, Level_Status)
                                        VALUES('$Employee_ID','$Registration_ID','$Surgeon_filled','$Anesthetist',NOW(),'$nurse_instruments','$can_proceed', '$Product_Name', '$Employee_Name', '$Admision_ID', '$consultation_ID', '$Payment_Item_Cache_List_ID', '1')") or die(mysqli_error($conn));

                    if($insert_sql){
                        $Pre_Operative_ID = mysqli_insert_id($conn);
                    }
            }

            if($Pre_Operative_ID > 0){
                for ($i=0; $i < sizeof($checkbox); $i++) { 
                    $Temp_Name = $checkboxvalue[$i]; //$checkboxvalue1
                    $Temp_Value = $checkbox[$i];
                    $Temp_Remark = $remark[$i];
                    $Temp_Sn = $doc[$i];
                                        $insert_sql_2 = mysqli_query($conn,"INSERT INTO tbl_who_pre_operative_checklist_items(Task_Name, Task_Value, Remark, Pre_Operative_ID, Sn)
                                        VALUES('$Temp_Name','$Temp_Value','$Temp_Remark','$Pre_Operative_ID','$Temp_Sn')") or die(mysqli_error($conn));
                }
            }

            if($insert_sql_2){
                $Update = mysqli_query($conn, "UPDATE tbl_who_pre_operative_checklist SET Level_Status = '2' WHERE Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
                echo 200;
            }else{
                echo 201;
            }   
        }elseif($Form_ID == 'form_3'){
            $Select_Who_ID = mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_who_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND consultation_ID = '$consultation_ID' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Level_Status = 2") or die(mysqli_error($conn));

            if(mysqli_num_rows($Select_Who_ID) > 0){
                $Pre_Operative_ID = mysqli_fetch_assoc($Select_Who_ID)['Pre_Operative_ID'];

            }else{
                $insert_sql = mysqli_query($conn,"INSERT INTO tbl_who_pre_operative_checklist(Employee_ID,Registration_ID,Surgeon_filled,Anesthetist,Operative_Date_Time,nurse_instruments,can_proceed,surgery,surgeon, Admision_ID, consultation_ID, Payment_Item_Cache_List_ID, Level_Status)
                                        VALUES('$Employee_ID','$Registration_ID','$Surgeon_filled','$Anesthetist',NOW(),'$nurse_instruments','$can_proceed', '$Product_Name', '$Employee_Name', '$Admision_ID', '$consultation_ID', '$Payment_Item_Cache_List_ID', '3')") or die(mysqli_error($conn));

                    if($insert_sql){
                        $Pre_Operative_ID = mysqli_insert_id($conn);
                    }
            }

            if($Pre_Operative_ID > 0){
                for ($i=0; $i < sizeof($checkbox); $i++) { 
                    $Temp_Name = $checkboxvalue[$i]; //$checkboxvalue1
                    $Temp_Value = $checkbox[$i];
                    $Temp_Remark = $remark[$i];
                    $Temp_Sn = $doc[$i];
                                        $insert_sql_2 = mysqli_query($conn,"INSERT INTO tbl_who_pre_operative_checklist_items(Task_Name, Task_Value, Remark, Pre_Operative_ID, Sn)
                                        VALUES('$Temp_Name','$Temp_Value','$Temp_Remark','$Pre_Operative_ID','$Temp_Sn')") or die(mysqli_error($conn));
                }
            }

            if($insert_sql_2){
                $Update = mysqli_query($conn, "UPDATE tbl_who_pre_operative_checklist SET Level_Status = '3', nurse_instruments = '$nurse_instruments', Anesthetist = '$Anesthetist', Surgeon_filled = '$Surgeon_filled' WHERE Pre_Operative_ID = '$Pre_Operative_ID'") or die(mysqli_error($conn));
                echo 200;
            }else{
                echo 201;
            }   
        }
    }
?>