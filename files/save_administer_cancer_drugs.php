    <?php
    include("./includes/connection.php");
    //finance_department_id
    session_start();

    if(isset($_POST['cyclenumber'])){
        $cyclenumber = mysqli_real_escape_string($conn,  $_POST['cyclenumber']);
    }else{
        $cyclenumber="";
    }
    if(isset($_POST['selected_physician_ID'])){
        $selected_physician_ID =   $_POST['selected_physician_ID'];
    }else{
        $selected_physician_ID=array();
    }

    if(isset($_POST['selected_treatment'])){
        $selected_treatment = $_POST['selected_treatment'];
    }else{
        $selected_treatment=array();
    }
    if(isset($_POST['selected_drug'])){
        $selected_drug =   $_POST['selected_drug'];
    }else{
        $selected_drug = array();
    }

    if(isset($_POST['administer_comment'])){
        $administer_comment = mysqli_real_escape_string($conn,  $_POST['administer_comment']);
    }else{
        $administer_comment = "";
    }
    if(isset($_POST['Patient_protocal_details_ID'])){
        $Patient_protocal_details_ID = $_POST['Patient_protocal_details_ID'];
    }else{
        $Patient_protocal_details_ID ="";
    }
    if(isset($_POST['Registration_ID'])){
        $Registration_ID = $_POST['Registration_ID'];
    }else{
        $Registration_ID = "";
    }
    if(isset($_POST['selected_treatment'])){
        $selected_treatment = $_POST['selected_treatment'];
    }else{
        $selected_treatment = array();
    }
    if(isset($_POST['phy_Pharmacy_ID'])){
        $phy_Pharmacy_ID = $_POST['phy_Pharmacy_ID'];
    }else{
        $phy_Pharmacy_ID =array();
    }
    if(isset($_POST['drug_selectedPharmacy'])){
        $drug_selectedPharmacy =$_POST['drug_selectedPharmacy'];
    }else{
        $drug_selectedPharmacy = array();
    }
    if(isset($_POST['treat_Pharmacy_ID'])){
        $treat_Pharmacy_ID = $_POST['treat_Pharmacy_ID'];
    }else{
        $treat_Pharmacy_ID=array();
    }
    
    $Cycle_details = $_POST['Cycle_details'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Employee_name = $_SESSION['userinfo']['Employee_Name'];
    $finance_department_id = $_SESSION['finance_department_id'];
                //Select cycle for today visit
                $select_cycles = mysqli_query($conn, "SELECT * FROM tbl_patient_protocal_cycles WHERE DATE(saved_at)= CURDATE() AND Patient_protocal_details_ID='$Patient_protocal_details_ID'") or die(mysqli_error($conn));
               $Treatment=0;
               $drug=0;
                if(mysqli_num_rows($select_cycles)>0){
                    $Cycle_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Cycle_ID FROM tbl_patient_protocal_cycles WHERE DATE(saved_at)= CURDATE() AND Patient_protocal_details_ID='$Patient_protocal_details_ID' ORDER BY `Patient_protocal_details_ID` DESC LIMIT 1"))['Cycle_ID'];
                    
                    for($i=0; $i<sizeof($selected_drug); $i++){
                        $updatepatient_chemodrug = mysqli_query($conn,  "UPDATE tbl_patient_chemotherapy_drug SET Cycle_ID='$Cycle_ID' WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND patient_chemotherapy_ID='$selected_drug[$i] '") or die(mysqli_error($conn));
                        $drug++;
                    }
                    for($i=0; $i<sizeof($selected_treatment); $i++){ 
                        $updatepatient_treatment = mysqli_query($conn,  "UPDATE tbl_patient_supportive_treatment SET Cycle_ID='$Cycle_ID' WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND patient_supportive_ID='$selected_treatment[$i] '") or die(mysqli_error($conn));
                        $Treatment++;
                    }
                   
                }else{
                    // Intert cycle if it doesnot exist
                    $mysql_regist_cycles = mysqli_query($conn,"INSERT INTO tbl_patient_protocal_cycles(cyclenumber,administer_comment, Patient_protocal_details_ID,administed_by)VALUES('$cyclenumber','$administer_comment', '$Patient_protocal_details_ID', '$Employee_ID' )") or die(mysqli_error($conn));
                    $Cycle_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Cycle_ID FROM tbl_patient_protocal_cycles WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' ORDER BY `Patient_protocal_details_ID` DESC LIMIT 1"))['Cycle_ID'];
                    // die($Cycle_ID."insert");

                    for($i=0; $i<sizeof($selected_drug); $i++){
                        $updatepatient_chemodrug = mysqli_query($conn,  "UPDATE tbl_patient_chemotherapy_drug SET Cycle_ID='$Cycle_ID' WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND patient_chemotherapy_ID='$selected_drug[$i] '") or die(mysqli_error($conn));
                        $drug++;
                    }
                    for($i=0; $i<sizeof($selected_treatment); $i++){ 
                        $updatepatient_treatment = mysqli_query($conn,  "UPDATE tbl_patient_supportive_treatment SET Cycle_ID='$Cycle_ID' WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND patient_supportive_ID='$selected_treatment[$i] '") or die(mysqli_error($conn));
                        $Treatment++;
                    }
                   
                }


                if(sizeof($selected_drug ) >= sizeof($selected_treatment)){
                   $sendpharmacy = $drug;
                }else{
                    $sendpharmacy = $Treatment;
                }
                if($sendpharmacy){
                    
                //create_patientpayment
  
                $mysql_check_if_admited=mysqli_query($conn,"SELECT Registration_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND (Admission_Status='Admitted' OR Credit_Bill_Status='pending')") or die(mysqli_error($conn));
                $folio_number=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Folio_Number FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID'"))['Folio_Number'];
                $consultation_ID=0;
                $consultation_query =mysqli_query($conn,  "SELECT consultation_ID FROM tbl_consultation WHERE  Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1");
                while($cos_rw = mysqli_fetch_assoc($consultation_query)){
                    $consultation_ID = $cos_rw['consultation_ID'];
                }

                if(mysqli_num_rows($mysql_check_if_admited)>0){
                  //  echo "admitted";
                    //    inpatient process, tbl_patient_payments
                     $count_row=0;
                     $Billing_Type="";
                     $Transaction_Type1="";
                    $select_patient=mysqli_query($conn,"SELECT re.Sponsor_ID,sp.Guarantor_Name,sp.payment_method FROM tbl_patient_registration re,tbl_sponsor sp WHERE re.Sponsor_ID= sp.Sponsor_ID AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    while($data=mysqli_fetch_assoc($select_patient)){
                       $Sponsor_ID =$data['Sponsor_ID'];
                       $Guarantor_Name=$data['Guarantor_Name'];
                       $payment_method=$data['payment_method'];
                        if($payment_method != "credit"){
                            $Billing_Type= "Inpatient Cash";
                            $Transaction_Type1 ="Cash";
                        }else{
                            $Billing_Type= "Inpatient Credit";
                            $Transaction_Type1="Credit";
                        }            
                       //start to applay payment cach
                       $patyment_cache_id = 0;
                    $select_payment_cach = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' AND consultation_id='$consultation_ID' AND Payment_Date_And_Time=CURDATE()") or die(mysqli_error($conn));
                    if(mysqli_num_rows($select_payment_cach)>0){
                        while($pay_cach_id = mysqli_fetch_assoc($select_payment_cach)){
                                $patyment_cache_id = $pay_cach_id['Payment_Cache_ID'];
                        }                       
                    }else{
                        $insert_data_patient=mysqli_query($conn,"INSERT INTO  tbl_payment_cache(Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Folio_Number,Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,Transaction_type)VALUES('$Registration_ID','$Employee_ID','$consultation_ID',NOW(),'$folio_number','$Sponsor_ID','$Guarantor_Name','$Billing_Type',CURDATE(),'indirect cash')") or die(mysqli_error($conn));
                       // echo "consultation_id====>".$consultation_ID."---";
                        $patyment_cache_id=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
                    }
                    //end of finding payment cach
                    }
                }else{
                    $select_patient=mysqli_query($conn,"SELECT re.Sponsor_ID,re.Employee_ID,sp.Guarantor_Name,sp.payment_method FROM tbl_patient_registration re,tbl_sponsor sp WHERE re.Sponsor_ID= sp.Sponsor_ID AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    while($data=mysqli_fetch_assoc($select_patient)){
                     //   echo "not admitted";
                       $Sponsor_ID =$data['Sponsor_ID'];         
                       $Guarantor_Name=$data['Guarantor_Name'];
                       $payment_method=$data['payment_method'];
            
                    if($payment_method != "credit"){
                        $Billing_Type= "Inpatient Cash";
                        $Transaction_Type1 ="Cash";
                    }else{
                        $Billing_Type= "Inpatient Credit";
                        $Transaction_Type1="Credit";
                    } 
            
                    //start to applay payment cach
                    $patyment_cache_id = 0;
                    $select_payment_cach = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' AND consultation_id='$consultation_ID'AND Payment_Date_And_Time=CURDATE() ") or die(mysqli_error($conn));
                    if(mysqli_num_rows($select_payment_cach)>0){
                        while($pay_cach_id = mysqli_fetch_assoc($select_payment_cach)){
                            $patyment_cache_id = $pay_cach_id['Payment_Cache_ID'];
                        }                       
                    }else{
                        $insert_data_patient=mysqli_query($conn,"INSERT INTO  tbl_payment_cache(Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Folio_Number,Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,Transaction_type)VALUES('$Registration_ID','$Employee_ID','$consultation_ID',NOW(),'$folio_number','$Sponsor_ID','$Guarantor_Name','$Billing_Type',CURDATE(),'indirect cash')") or die(mysqli_error($conn));
                        //echo "consultation_id====>".$consultation_ID."---";
                        $patyment_cache_id=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
                    }
                  //end of finding payment cach
                   
                    }
                }
                //end of create patient payment_cache_ID
                $i=0;
                if(sizeof($selected_physician_ID)>0){
                    foreach($selected_physician_ID as $rw){
                        $Sub_Department_ID =$phy_Pharmacy_ID[$i];
                        $physician_ID = $rw['physician_ID'];
                        ++$i;
                        $sql_data_cancer_duration = mysqli_query($conn,"SELECT Physician_Item_name,physician_ID, physician_volume,physician_type,physician_minutes,date_and_time FROM tbl_patient_physician WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Registration_ID='$Registration_ID' AND physician_ID='$physician_ID' ") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_data_cancer_duration)>0){
                            while($values = mysqli_fetch_assoc($sql_data_cancer_duration)){
                                $Physician_Item_name =$values['Physician_Item_name'];
                                $physician_volume=$values['physician_volume'];
                                $physician_minutes=$values['physician_minutes'];
                                $physician_type=$values['physician_type'];
                                $physician_ID=$values['physician_ID'];
                                
                                $Doctor_Comment = "Dose: ".$physician_volume." ml;  Route: ".$physician_type.";   Frequence: ".$physician_minutes;
                                
                                $mysql_item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$Physician_Item_name'"))['Physician_Item_name'];
                                $mysql_select_price =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$mysql_item_ID' AND Sponsor_ID='$Sponsor_ID'"))['Items_Price'];
                                $mysql_insert_cache=mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Consultant,Consultant_ID,Status,Payment_Cache_ID,Transaction_Date_And_Time,Transaction_Type,Service_Date_And_Time,Edited_Quantity,Sub_Department_ID, finance_department_id, Doctor_Comment)VALUES('Pharmacy','indirect cash','$mysql_item_ID','$Physician_Item_name','0','$mysql_select_price','1','$Employee_name','$Employee_ID','active','$patyment_cache_id',NOW(),'$Transaction_Type1',NOW(),'0', '$Sub_Department_ID','$finance_department_id', '$Doctor_Comment')") or die(mysqli_error($conn));

                                echo  $finance_department_id."===>0".$Employee_name;
                            }
                        }
                    }
                }
                $i=0;
                foreach ($selected_treatment as $treatment_nameone){
                    (int)$treatment_nameone;
                    $Sub_Department_ID =$treat_Pharmacy_ID[$i];
                    ++$i;
                        $mysql_select = mysqli_query($conn,"SELECT supportive_treatment,Dose, Route, Frequence FROM tbl_patient_supportive_treatment WHERE patient_supportive_ID='$treatment_nameone' AND Patient_protocal_details_ID='$Patient_protocal_details_ID' ")or die(mysqli_error($conn));
                        if(mysqli_num_rows($mysql_select)>0){
                        while($rows=mysqli_fetch_assoc($mysql_select)){
                                     
                                     $supportive_treatment =$rows['supportive_treatment'];
                                     $Dose = $rows['Dose'];
                                     $Route = $rows['Route'];
                                     $Frequence= $rows['Frequence'];

                                     $Doctor_Comment = "Dose: ".$Dose."mg;  Route: ".$Route.";   Frequence: ".$Frequence;
                                   
                                  
                                     if(mysqli_num_rows($mysql_check_if_admited)>0){
                                       
                                         $mysql_item_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$supportive_treatment'"))['Item_ID'];
                                         $mysql_price =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$mysql_item_ID' AND Sponsor_ID='$Sponsor_ID'"))['Items_Price'];
//                                        
                                         $mysql_select_Item_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT dg.item_id FROM tbl_items_cancer_drug dg,tbl_patient_supportive_treatment cd WHERE dg.cancer_id=cd.cancer_type_id AND cd.Registration_ID='$Registration_ID' AND cd.supportive_treatment='$supportive_treatment'"))['item_id'];
                                         $Billing_Type=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Billing_Type'];

                                            if($Billing_Type != "Inpatient Cash"){
                                               $Transaction_Type1="Credit";
                                            }else{
                                               $Transaction_Type1="Cash";
                                            }


                                         $mysql_insert_cache=mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Consultant,Consultant_ID,Status,Payment_Cache_ID,Transaction_Date_And_Time,Transaction_Type,Service_Date_And_Time,Edited_Quantity,finance_department_id, Doctor_Comment, Sub_Department_ID)VALUES('Pharmacy','indirect cash','$mysql_item_ID','$supportive_treatment','0','$mysql_select_price','1','$Employee_name','$Employee_ID','active','$patyment_cache_id',NOW(),'$Transaction_Type1',NOW(),'0', '$finance_department_id', '$Doctor_Comment', '$Sub_Department_ID')") or die(mysqli_error($conn));

                                         echo  $finance_department_id."===>0".$Employee_name;

                                     }else{

                                         $select_patient=mysqli_query($conn,"SELECT re.Sponsor_ID,re.Employee_ID,sp.Guarantor_Name,sp.payment_method FROM tbl_patient_registration re,tbl_sponsor sp WHERE re.Sponsor_ID= sp.Sponsor_ID AND Registration_ID='$Registration_ID'");
                                         while($data=mysqli_fetch_assoc($select_patient)){
                                           //  echo "not admitted";
                                            $Sponsor_ID =$data['Sponsor_ID'];
                                            $Employee_ID=$data['Employee_ID'];
                                            $Guarantor_Name=$data['Guarantor_Name'];
                                            $payment_method=$data['payment_method'];

                                            if($payment_method != "credit"){
                                               $Billing_Type= "Outpatient Cash";
                                            }else{
                                                 $Billing_Type= "Outpatient Credit";
                                            }

                                           

                                         $mysql_item_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$supportive_treatment'"))['Item_ID'];
                                         $mysql_price =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$mysql_item_ID' AND Sponsor_ID='$Sponsor_ID'"))['Items_Price'];

                                         $mysql_select_Item_ID_treatment=mysqli_fetch_assoc(mysqli_query($conn,"SELECT dg.item_id FROM tbl_items_cancer dg,tbl_patient_supportive_treatment cd WHERE dg.cancer_id=cd.cancer_type_id AND cd.Registration_ID='$Registration_ID' AND cd.supportive_treatment='$supportive_treatment'"))['item_id'];
                                         $Billing_Type=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Billing_Type'];

                                           if($Billing_Type != "Outpatient Cash"){

                                               $Transaction_Type1="Credit";

                                           }else{
                                               $Transaction_Type1="Cash";
                                           }

                                         $finance_department_id;
                                         $mysql_insert_cache=mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Status,Payment_Cache_ID,Transaction_Date_And_Time,Transaction_Type,Service_Date_And_Time,Edited_Quantity, finance_department_id,Doctor_Comment, Sub_Department_ID)VALUES('Pharmacy','indirect cash','$mysql_item_ID','$supportive_treatment','0','$mysql_price','1','others','$Employee_name','$Employee_ID','active','$patyment_cache_id',NOW(),'$Transaction_Type1',NOW(),'0','$finance_department_id', '$Doctor_Comment', '$Sub_Department_ID')")or die(mysqli_error($conn));

                                     //   echo  $finance_department_id."===>1".$Employee_name;
                                         }

                                     }
                                     //end of else not admitted
                        }

                         if(!$mysql_insert){
                           $an_error_occured=TRUE;
                       }
                    }else{
                        echo "nothing to display";
                    }
                        
                }
                //end of elect

                //===================chemo drud
                $i=0;
                foreach ($selected_drug as $drug_nameone){
                    $Sub_Department_ID =$drug_selectedPharmacy[$i];
                    $drug_nameone;
                    ++$i;
                        $mysql_selects= mysqli_query($conn,"SELECT Chemotherapy_Drug, Dose, Route, Admin_time, Frequency, Volume FROM tbl_patient_chemotherapy_drug WHERE patient_chemotherapy_ID='$drug_nameone' AND Patient_protocal_details_ID='$Patient_protocal_details_ID'")or die(mysqli_error($conn));

                        if(mysqli_num_rows($mysql_selects)>0){
                        while($rows=mysqli_fetch_assoc($mysql_selects)){
                                    $Chemotherapy_Drug=$rows['Chemotherapy_Drug'];
                                    $Dose =$rows['Dose'];
                                    $Volume =$rows['Volume'];
                                    $Route =$rows['Route'];
                                    $Admin_Time=$rows['Admin_Time'];
                                    $Frequency =$rows['Frequency'];

                                    $Doctor_Comment ="Dose: ". $Dose."mg;  Volume: ".$Volume."ml;  Route: ".$Route.";  Frequence: ".$Frequence;

                                     if(mysqli_num_rows($mysql_check_if_admited)>0){
                                      
//                                         inpatient process, tbl_patient_payments
                                     
                                         $select_patient=mysqli_query($conn,"SELECT re.Sponsor_ID,re.Employee_ID,sp.Guarantor_Name,sp.payment_method FROM tbl_patient_registration re,tbl_sponsor sp WHERE re.Sponsor_ID= sp.Sponsor_ID AND Registration_ID='$Registration_ID'");
                                         while($data=mysqli_fetch_assoc($select_patient)){
                                            $Sponsor_ID =$data['Sponsor_ID'];
                                            $Employee_ID=$data['Employee_ID'];
                                            $Guarantor_Name=$data['Guarantor_Name'];
                                            $payment_method=$data['payment_method'];

                                            if($payment_method != "credit"){
                                               $Billing_Type= "Inpatient Cash";

                                            }else{
                                                 $Billing_Type != "Inpatient Credit";
                                            }

                                            $mysql_item_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$Chemotherapy_Drug'"))['Item_ID'];
                                            $mysql_price =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$mysql_item_ID' AND Sponsor_ID='$Sponsor_ID'"))['Items_Price'];
//                                         
                                            $mysql_select_Item_ID_drug=mysqli_fetch_assoc(mysqli_query($conn,"SELECT dg.item_id FROM tbl_items_cancer_drug dg,tbl_patient_chemotherapy_drug cd WHERE dg.cancer_id=cd.cancer_type_id AND cd.Registration_ID='$Registration_ID' AND cd.Chemotherapy_Drug='$Chemotherapy_Drug'"))['item_id'];
                                            $Billing_Type=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Billing_Type'];

                                            if($Billing_Type != "Inpatient Cash"){
                                                $Transaction_Type1="Credit";
                                            }else{
                                                $Transaction_Type1="Cash";
                                            }

                                        
                                         $mysql_insert_cache=mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Consultant,Consultant_ID,Status,Payment_Cache_ID,Transaction_Date_And_Time,Transaction_Type,Service_Date_And_Time,Edited_Quantity,finance_department_id, Doctor_Comment, Sub_Department_ID)VALUES('Pharmacy','indirect cash','$mysql_item_ID','$Chemotherapy_Drug','0','$mysql_price','1','$Employee_name','$Employee_ID','active','$patyment_cache_id',NOW(),'$Transaction_Type1',NOW(),'0','$finance_department_id', '$Doctor_Comment', '$Sub_Department_ID')") or die(mysqli_error($conn));
                                       //  echo  $finance_department_id."===>2".$Employee_name;
                                         }


                                     }else{

                                         $mysql_item_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$Chemotherapy_Drug'"))['Item_ID'];
                                         $mysql_price =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$mysql_item_ID' AND Sponsor_ID='$Sponsor_ID'"))['Items_Price'];
                                    
                                         $mysql_select_Item_ID_drug_2=mysqli_fetch_assoc(mysqli_query($conn,"SELECT dg.item_id FROM tbl_items_cancer_drug dg,tbl_patient_chemotherapy_drug cd WHERE dg.cancer_id=cd.cancer_type_id AND cd.Registration_ID='$Registration_ID' AND cd.Chemotherapy_Drug='$Chemotherapy_Drug'"))['item_id'];
                                         $Billing_Type=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Billing_Type'];

                                           if($Billing_Type != "Outpatient Cash"){

                                               $Transaction_Type1="Credit";

                                           }else{
                                               $Transaction_Type1="Cash";
                                           }

                                         
                                         $mysql_insert_cache=mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Status,Payment_Cache_ID,Transaction_Date_And_Time,Transaction_Type,Service_Date_And_Time,Edited_Quantity,finance_department_id,Doctor_Comment, Sub_Department_ID)VALUES('Pharmacy','indirect cash','$mysql_item_ID','$Chemotherapy_Drug','0','$mysql_price','1','others','$Employee_name','$Employee_ID','active','$patyment_cache_id',NOW(),'$Transaction_Type1',NOW(),'0','$finance_department_id', '$Doctor_Comment', '$Sub_Department_ID')") or die(mysqli_error($conn));
                                        // echo  $finance_department_id."===>4".$Employee_name;
                                         }

                                     }

                        }else{
                            echo "nothing to display";
                        }
             }

                            echo "Administered successful";
            }else{
                echo "Failed to to add item Phamarcy";
            }
           

