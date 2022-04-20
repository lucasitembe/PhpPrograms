<?php
session_start();
include("./includes/connection.php");
$id=$_POST['id'];
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Patient_Payment_Test_ID = $_POST['Patient_Payment_Test_ID'];
$Payment_ID = $_POST['payment_id'];
$Laboratory_Test_specimen_ID = $_POST['Patient_Payment_Item_List_ID'];
$patient_Id=$_POST['patient_id'];
if($_POST['payment_id']!=''){
    $payment_id=$_POST['payment_id'];
}else{
    $payment_id='';
}

if($_POST['patient_id']){
    $patient_id=$_POST['patient_id'];
}else{
    $patient_id='';
}

if($_POST['Patient_Payment_Test_ID']!=''){
    $Patient_Payment_Test_ID=$_POST['Patient_Payment_Test_ID'];
}else{
    $Patient_Payment_Test_ID='';
}

if($_POST['id']!=''){
    $item_id=$_POST['id'];
}else{
    $item_id='';
}

//RUN THE QUERY TO CHECK PAYMENT TYPE
$select_Filtered_Patients = mysqli_query($conn,
"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pp.Payment_Date_And_Time,pr.Gender as Gender,
            pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
            il.Process_Status as Status,il.Status,pp.Billing_Type,il.Consultant as Consultant_Name,il.Consultant_ID,il.Price,il.Discount,il.Quantity,'Revenue Center' as Doctors_Name
            FROM tbl_patient_payment_item_list as il
            JOIN tbl_items as i ON i.Item_ID = il.Item_ID
              join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                  join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                          WHERE Check_In_Type ='Laboratory'
                          and i.Consultation_Type ='Laboratory'
                            and il.Item_ID='$item_id'
                            and il.Patient_Payment_ID='$payment_id'
                            AND pr.Registration_ID='$patient_id'
                                        GROUP BY payment_id

        union all
        SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pc.Payment_Date_And_Time,pr.Gender as Gender,
               pc.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
               il.Process_Status as Status,il.Status,il.Transaction_Type,il.Consultant as Consultant_Name,il.Consultant_ID,il.Price,il.Discount,il.Quantity,il.Consultant as Doctors_Name
                  FROM tbl_item_list_cache as il
                    JOIN tbl_items as i ON i.Item_ID = il.Item_ID
                      JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                        JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                            WHERE Check_In_Type ='Laboratory'
                            and i.Consultation_Type ='Laboratory'
                                and il.Item_ID='$item_id'
                                and il.Payment_Cache_ID='$payment_id'
                                AND pc.Registration_ID='$patient_id'
                                                  GROUP BY payment_id
              order by payment_id
            ")
or die(mysqli_error($conn));

$row=mysqli_fetch_array($select_Filtered_Patients);
$Consultant_Name=$row['Consultant_Name'];
$Consultant_ID=$row['Consultant_ID'];
$Patient_Direction='Direct To Doctor';
$Discount=$row['Discount'];
$Price=$row['Price'];
$Quantity=$row['Quantity'];

$Transaction_Type=$row['Billing_Type'];

if(strtolower($Transaction_Type) == 'credit'){
   //run the query to select details from tbl_patient_payments
    $Select_Patient_Payments=mysqli_query($conn,"SELECT pp.Folio_Number,pp.Billing_Type,sp.Sponsor_ID,sp.Guarantor_Name,pp.Claim_Form_Number  FROM tbl_patient_payments pp,tbl_patient_registration pr,tbl_sponsor sp
    WHERE pr.Registration_ID=pp.Registration_ID
    AND pp.Sponsor_ID=sp.Sponsor_ID
    AND pp.Registration_ID='$patient_id' ORDER BY pp.Patient_Payment_ID DESC limit 1");

    $payment_row=mysqli_fetch_array($Select_Patient_Payments);
    $Folio_Number=$payment_row['Folio_Number'];
    $Claim_Form_Number=$payment_row['Claim_Form_Number'];
    $Registration_ID=$patient_id;
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $Supervisor_ID=$_SESSION['Laboratory_Supervisor']['Employee_ID'];
    $Payment_Date_And_Time=date('Y-m-d H:i:s');
    $Sponsor_ID=$payment_row['Sponsor_ID'];
    $Guarantor_Name=$payment_row['Guarantor_Name'];
    $Billing_Type=explode(' ',$payment_row['Billing_Type'])[0].' Credit';
    $Receipt_Date=date('Y-m-d');
    $Transaction_Status='active';
    $Branch_ID = $_SESSION['Laboratory_Supervisor']['Branch_ID'];


    //run the query to insert data into tbl_patient_payments table
    $insert=mysqli_query($conn,"INSERT INTO tbl_patient_payments SET
                           Registration_ID='$Registration_ID',
                           Supervisor_ID='$Supervisor_ID',
                           Employee_ID='$Employee_ID',
                           Payment_Date_And_Time='$Payment_Date_And_Time',
                           Folio_Number='$Folio_Number',
                           Claim_Form_Number='$Claim_Form_Number',
                           Sponsor_ID='$Sponsor_ID',
                           Sponsor_Name='$Guarantor_Name',
                           Billing_Type='$Billing_Type',
                           Receipt_Date='$Receipt_Date',
                           branch_id='$Branch_ID'");

    //check if the query succeeded
    if($insert){
        //take the id if the last inserted row
        echo $Patient_Payment_ID=mysql_insert_id();
        $Transaction_Date_And_Type=date("Y-m-d H:i:s");

          //run the query to insert data into the tbl_patient_payment_item)list_id table
        $insert_items=mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list SET
                                    Check_In_Type='Laboratory',
                                    Item_ID='$item_id',
                                    Discount='$Discount',
                                    Price='$Price',
                                    Quantity='$Quantity',
                                    Patient_Direction='$Patient_Direction',
                                    Consultant='$Consultant_Name',
                                    Consultant_ID='$Consultant_ID',
                                    Patient_Payment_ID='$Patient_Payment_ID',
                                    Transaction_Date_And_Time='$Transaction_Date_And_Type',
                                    Process_Status='served' ");
        if($insert_items){
            echo $Patient_Payment_Item_List_ID=mysql_insert_id();
            echo "Bill Created";
        }else{
            die(mysqli_error($conn));
        }
    }else{
        echo "Failed to create bill.";
    }
}

?>