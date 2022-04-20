<?php
    @session_start();
    include("./includes/connection.php"); 
        //echo $Patient_Payment_Item_List_ID." ".$Type_Of_Check_In." ".$direction." ".$Consultant." ".$Item_Name." ".$Discount; exit;
	//select privious record then insert into history table
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
            $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
        }else{
            $Patient_Payment_Item_List_ID = 0;
        }
        
        $select_privious_record  = mysqli_query($conn,"select * from tbl_patient_payment_item_list ppl,tbl_patient_payments pp
                                                where ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
                                                    ppl.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($select_privious_record)){
            $Registration_ID = $row['Registration_ID'];
            $Sponsor_Name = $row['Sponsor_Name'];
	    $Patient_Payment_Item_List_ID = $row['Patient_Payment_Item_List_ID'];
	    $Check_In_Type = $row['Check_In_Type'];
	    $Category = $row['Category'];
	    $Item_ID = $row['Item_ID'];
	    $Item_Name = $row['Item_Name'];
	    $Discount = $row['Discount'];
	    $Price = $row['Price'];
	    $Quantity = $row['Quantity'];
	    $Patient_Direction = $row['Patient_Direction'];
	    $Consultant = $row['Consultant'];
	    $Consultant_ID = $row['Consultant_ID'];
	    $Status = $row['Status'];
	    $Patient_Payment_ID = $row['Patient_Payment_ID'];
	    $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
	    $Process_Status = $row['Process_Status'];
            $Billing_Type = $row['Billing_Type'];
	}
	//echo $Patient_Payment_Item_List_ID." ".$Type_Of_Check_In." ".$Patient_Direction." ".$Consultant." ".$Item_Name." ".$Item_ID." ".$Discount;   
        mysqli_query($conn,"insert into tbl_transaction_items_history(Patient_Payment_Item_List_ID,
                    check_In_type,category,item_id,
                        Item_name,discount,price,
                            quantity,patient_direction,consultant,
                                consultant_id,patient_payment_id,Transaction_Date_And_Time)
                    
                    values('$Patient_Payment_Item_List_ID','$Check_In_Type','$Category','$Item_ID',
                            '$Item_Name','$Discount','$Price',
                                '$Quantity','$Patient_Direction','$Consultant',
                                    '$Consultant_ID','$Patient_Payment_ID','$Transaction_Date_And_Time')") or die(mysqli_error($conn));
        
        echo "insert into tbl_transaction_items_history(Patient_Payment_Item_List_ID,
                    check_In_type,category,item_id,
                        Item_name,discount,price,
                            quantity,patient_direction,consultant,
                                consultant_id,patient_payment_id,Transaction_Date_And_Time)
                    
                    values('$Patient_Payment_Item_List_ID','$Check_In_Type','$Category','$Item_ID',
                            '$Item_Name','$Discount','$Price',
                                '$Quantity','$Patient_Direction','$Consultant',
                                    '$Consultant_ID','$Patient_Payment_ID','$Transaction_Date_And_Time')";
        exit();
        
        /*mysqli_query($conn,"update tbl_patient_payment_item_list set Check_In_Type = '$Check_In_Type', Category = '$Category',Item_ID = '$Item_ID',
                            Item_Name = '$Item_Name', Discount = '$Discount', Price = '$Price',
                                Quantity = '$Quantity', Patient_Direction = '$direction', Consultant = '$Consultant',
                                    Consultant_ID = '$Consultant_ID', Status = 'Edited', Transaction_Date_And_Time = (select now())
                                        where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
        */
        
        
        if(isset($_GET['Patient_Payment_Item_List_ID'])){
            $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
            $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
            $direction = $_GET['direction'];
            $Consultant = $_GET['Consultant'];
            $Item_ID = $_GET['Item_ID'];
            $Discount = $_GET['Discount'];
            $Quantity = $_GET['Quantity'];
            $Price = $_GET['Price'];
            $Item_Name = $_GET['Item_Name'];
        }else{
            $Patient_Payment_Item_List_ID = '';
            $Type_Of_Check_In = '';
            $direction = '';
            $Consultant = '';
            $Item_ID = '';
            $Discount = '';
            $Quantity = '';
            $Price = '';
            $Item_Name = '';
        }
        
           
            //get employee id to insert into history table
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    
            //echo "<br/>".$Patient_Payment_Item_List_ID." ".$Type_Of_Check_In." ".$direction." ".$Consultant." ".$Item_Name." ".$Discount; exit;    
            //understand the patient direction to allow the query either to select clinic id of doctor id
                if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){    
                    $Update_Data_To_tbl_patient_payment_item_list = "update tbl_patient_payment_item_list set
                            check_In_type = '$Type_Of_Check_In', item_id = (select item_id from tbl_items where Product_Name = '$Item_ID'), discount = '$Discount'
                                ,price = '$Price', quantity = '$Quantity',patient_direction = '$direction',Item_Name = '$Item_Name',
                                    consultant = '$Consultant',consultant_id = (select Employee_ID from tbl_employee where employee_name = '$Consultant' limit 1)
                                        ,Transaction_Date_And_Time = (select now()),Record_Status = 'edited',Employee_Edited_ID = '$Employee_ID'
                                            where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'";
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $Update_Data_To_tbl_patient_payment_item_list = "update tbl_patient_payment_item_list set
                            check_In_type = '$Type_Of_Check_In', item_id = (select item_id from tbl_items where Product_Name = '$Item_ID'), discount = '$Discount'
                                ,price = '$Price', quantity = '$Quantity',patient_direction = '$direction',Item_Name = '$Item_Name',
                                    consultant = '$Consultant',consultant_id = (select clinic_ID from tbl_clinic where clinic_name = '$Consultant')
                                        ,Transaction_Date_And_Time = (select now()),Record_Status = 'edited',Employee_Edited_ID = '$Employee_ID'
                                            where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'";                                       
                }
                if(mysqli_query($conn,$Update_Data_To_tbl_patient_payment_item_list)){
                    die(mysqli_error($conn));
                    echo "<script type='text/javascript'>
			    alert('PROCESS FAIL');
			    document.location = '#';
			    </script>";
                }else{
                    header("Location: ./patientbillingeditcashtransaction.php?Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&Insurance=$Sponsor_Name&Selected=Selected&EditPayment=EditPaymentThisForm");
                }
	
?>