<style>
    #table_iterms input,select{
        padding:4px;
        font-size:14px; 
    }
    #saved{
        color:green;
    }
    .previous-notes{
        color: #8718AA;
        background-color: #FFF;margin: 4px;
        padding: 5px;
        border: 1px solid rgb(204, 204, 204);
        border-radius: 4px;
        font-weight: bold;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
require_once './includes/ehms.function.inc.php';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    header("Location: ./index.php?InvalidPrivilege=yes");
}


$req_ip_prov_dign = $_SESSION['hospitalConsultaioninfo']['req_ip_prov_dign'];

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Admision_ID = $_GET['Admision_ID'];

$previous = mysqli_query($conn, "SELECT consultation_ID, Patient_Payment_Item_List_ID FROM tbl_consultation WHERE Registration_ID = '$Registration_ID'  ORDER BY consultation_ID DESC LIMIT 1");

if(mysqli_num_rows($previous)> 0){
    while($dt = mysqli_fetch_assoc($previous)){
        $old_consultation_ID = $dt['consultation_ID'];
        $old_Patient_Payment_Item_List_ID = $dt['Patient_Payment_Item_List_ID'];
        
        if($old_Patient_Payment_Item_List_ID < 1){
            $old_Patient_Payment_Item_List_ID  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Registration_ID = '$Registration_ID' ORDER BY pp.Patient_Payment_ID DESC LIMIT 1 "))['Patient_Payment_Item_List_ID'];
        }
        $update_datas = mysqli_query($conn, "UPDATE tbl_consultation SET Process_Status = 'served', Patient_Payment_Item_List_ID='$old_Patient_Payment_Item_List_ID' WHERE consultation_ID='$consultation_ID' AND ((Patient_Payment_Item_List_ID IS NULL) OR(Patient_Payment_Item_List_ID = 0))");
    }
}

$excludedCheckType = array('Pharmacy', 'Optical');

//if editing
?>
    
    <div id="select_clinic" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl">
        <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Your working Clinic
                    </td>
                    <td>
                        <select  style='width: 100%;height:30%'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);update_clinic_id()' onclick='clearFocus(this)' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "SELECT * FROM tbl_clinic WHERE Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                   <td style="text-align:right">
                        Select Your working Department
                   </td>
                   <td style="width:60%">
                       <select id="working_department" style="width:100%">
                            <option value=""></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                   </td>
                </tr>
                <td colspan="2" align="right">
                    <input type="button" onclick="post_clinic_id()" class="art-button-green" value="Open"/>
                </td>
        </tr> 
    </table>
</div>

    <script>
    function post_clinic_id(){
       var Clinic_ID=$("#Clinic_ID").val();
       var working_department=$("#working_department").val();
       if(Clinic_ID==''||Clinic_ID==null){
          alert("select clinic first") 
          exit 
       }
       if(working_department==''||working_department==null){
          alert("select your working department first") 
          exit 
       }
       document.location="inpatientdoctorspage_select_clinic.php?Clinic_ID="+Clinic_ID+'&finance_department_id='+working_department;
    }
    function select_clinic_dialog(){
          $("#select_clinic").dialog({
                        title: 'SELECT CLINIC',
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
    }
</script>
    <?php
    
if(isset($_SESSION['finance_department_id'])&&($_SESSION['finance_department_id']!=NULL || $_SESSION['finance_department_id']!=0|| $_SESSION['finance_department_id']!="")){
    //$doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
    $finance_department_id=$_SESSION['finance_department_id'];
   // echo "<script>alert('$doctors_selected_clinic');</script>";
}else{
    echo "<script>select_clinic_dialog();</script>";
    die();
}

if (isset($_GET['src']) && $_GET['src'] == 'edit' && isset($_GET['Round_ID']) && !empty($_GET['Round_ID'])) {
    $Round_ID = $_GET['Round_ID'];
} else {
//check round  for this doctior if exist and not served then insert it
// AND DATE(Ward_Round_Date_And_Time)=DATE(NOW())
    $consultation_query = "SELECT Round_ID FROM tbl_ward_round WHERE consultation_ID = '$consultation_ID' AND Registration_ID='$Registration_ID' AND DATE(Ward_Round_Date_And_Time) =CURDATE() AND employee_ID='$employee_ID'  ORDER BY Round_ID DESC LIMIT 1";

    $consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));

    $pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

    $Findings = '';
    $Comment_For_Laboratory = '';
    $Comment_For_Radiology = '';
    $investigation_comments = '';
    $remarks = '';
    $Ward_Round_Date_And_Time = Date('Y-m-d h:i:s');
    $isFirstTime = false;

    if (mysqli_num_rows($consultation_query_result) == 0) {
        $insert_query = "INSERT INTO tbl_ward_round(employee_ID, Registration_ID,
                                       investigation_comments, remarks,
                                       Comment_For_Laboratory,
                                       Comment_For_Radiology,
                                       consultation_ID,Admision_ID,
                                       Ward_Round_Date_And_Time)
        VALUES ('$employee_ID', '$Registration_ID', 
                                       '$investigation_comments', '$remarks',
                                       '$Comment_For_Laboratory','$Comment_For_Radiology','$consultation_ID','$Admision_ID',NOW())";
        mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    }

    $result = mysqli_query($conn,"SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round
					       WHERE consultation_ID = '$consultation_ID' AND Registration_ID='$Registration_ID' AND employee_ID='$employee_ID' ") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    $Round_ID = $row['Round_ID'];
}
?>
<?php

if (isset($_POST['formsubmtt'])) {
    $_POST = sanitize_input($_POST);

    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Findings = $_POST['Findings'];
    $Comment_For_Laboratory = $_POST['Comment_For_Laboratory'];
    $Comment_For_Radiology = $_POST['Comment_For_Radiology'];
    $Comment_For_Procedure = $_POST['Comment_For_Procedure'];
    $Nuclearmedicinecomments = $_POST['Nuclearmedicinecomments'];
    $Comment_For_Surgery = $_POST['Comment_For_Surgery'];
    $investigation_comments = $_POST['investigation_comments'];
    $provisional_diagnosis = $_POST['provisional_diagnosis'];
    $diferential_diagnosis = $_POST['diferential_diagnosis'];
    $dischargedPatient = $_POST['dischargedPatient'];
    $discharge_reason = $_POST['Discharge_Reason_ID'];
    $remarks = $_POST['remarks'];
    $clinical_history = $_POST['clinical_history'];
    $Process_Status = 'served';
    $Patient_Type = '';
    $Ward_Round_Date_And_Time = Date('Y-m-d h:i:s');

    
    $update_query = "UPDATE tbl_ward_round SET Findings='$Findings',clinical_history='$clinical_history',
			    Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',Comment_For_Procedure='$Comment_For_Procedure',Nuclearmedicinecomments='$Nuclearmedicinecomments', Comment_For_Surgery='$Comment_For_Surgery',
			    investigation_comments='$investigation_comments',Patient_Type='$Patient_Type',remarks='$remarks',Admision_ID='$Admision_ID',Process_Status='$Process_Status',
			    Ward_Round_Date_And_Time=NOW()
			    WHERE Round_ID = '$Round_ID'";


    if (mysqli_query($conn,$update_query)) {
        
        //SAVE ASSOCIATED DOCTORS
        $sql_save_list_of_associated_doctors_result=mysqli_query($conn,"INSERT INTO tbl_round_associated_doctor(Employee_ID,consultation_ID,Registration_ID,Round_ID) SELECT Employee_ID,consultation_ID,Registration_ID,Round_ID FROM  tbl_round_associated_doctor_cache WHERE Round_ID = '$Round_ID'") or die(mysqli_error($conn));
        if($sql_save_list_of_associated_doctors_result){
            mysqli_query($conn,"DELETE FROM  tbl_round_associated_doctor_cache WHERE Round_ID = '$Round_ID'") or die(mysqli_error($conn));
        }
        
        //Bill patient round
        if (isset($_GET['item_ID']) && !empty($_GET['item_ID'])) {
            bill();
        }
        //End bill patient round
 
        $checkforupdate = mysqli_query($conn,"SELECT Patient_Direction, finance_department_id, il.Payment_Cache_ID,Sub_Department_ID,Consultant,  Payment_Item_Cache_List_ID,Check_In_Type,Item_ID,Discount,Price,Quantity,il.Transaction_Type,payment_type,Billing_Type,Sponsor_ID FROM tbl_item_list_cache il JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID WHERE consultation_id='$consultation_ID'  AND Status='notsaved'") or die(mysqli_error($conn));
        if(mysqli_num_rows($checkforupdate)){
        while ($rowup = mysqli_fetch_array($checkforupdate)) {
            
            $Payment_Item_Cache_List_ID = $rowup['Payment_Item_Cache_List_ID'];
            $Item_ID = $rowup['Item_ID'];
            $Sponsor_ID = $rowup['Sponsor_ID'];
            $Check_In_Type = $rowup['Check_In_Type'];
            $Discount = $rowup['Discount'];
            $Price = $rowup['Price'];
            $Quantity = $rowup['Quantity'];
            $Consultant = $rowup['Consultant'];
            $billingType = strtolower($rowup['Billing_Type']);
            $transactionType = strtolower($rowup['Transaction_Type']);
            $paymentType = strtolower($rowup['payment_type']);
            $Sub_Department_ID = $rowup['Sub_Department_ID'];
            $finance_department_id2 =$rowup['finance_department_id'];
            $transStatust = false;
            $checkIfAutoBilling = null;
            $Payment_Cache_ID2=$rowup['Payment_Cache_ID'];
            $branch_id = $rowup['branch_id'];
            $Sponsor_Name = $rowup['Sponsor_Name'];
            $Patient_Direction = $rowup['Patient_Direction'];


            //===========SAVE WARD ROUND ITEM BY MUGA 2021-09-20 =======
            
            $Round_item_ID = $_GET['item_ID'];
            $docs_items = "SELECT Items_Price,i.Item_ID,Product_Name FROM tbl_items i , tbl_item_price ip WHERE i.Item_ID=ip.Item_ID AND Ward_Round_Item = 'yes' AND Status='Available' AND ip.Sponsor_ID='$Sponsor_ID' AND ip.Items_Price<>0 AND ip.Item_ID='$Round_item_ID'";
            $docs_items_qry = mysqli_query($conn,$docs_items) or die(mysqli_error($conn));

            if(mysqli_num_rows($docs_items_qry)>0){
                while ($docs_item = mysqli_fetch_assoc($docs_items_qry)) {
                    $ItemCode = $docs_item['Item_ID'];
                    $ItemName = $docs_item['Product_Name'];
                    $Items_Price = $docs_item['Items_Price'];

                    $Check_In_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID']; 
                    $selectRoundIdexist =mysqli_query($conn, "SELECT Payment_Item_Cache_List_ID  FROM tbl_item_list_cache ilc, tbl_payment_cache pc WHERE Item_ID='$Round_item_ID' AND Consultant_ID='$employee_ID' AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND consultation_id='$consultation_ID' AND Registration_ID ='$Registration_ID' AND DATE(Transaction_Date_And_Time)=CURDATE()") or die(mysqli_error($conn));    
                    if(mysqli_num_rows($selectRoundIdexist)>0){

                    }else{
                    $insertround =mysqli_query($conn,"INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,   Payment_Cache_ID, Transaction_Date_And_Time,  Sub_Department_ID,Transaction_Type,Service_Date_And_Time,finance_department_id)   VALUES ('Others', '$Round_item_ID', '0', $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$employee_ID',  'notsaved','$Payment_Cache_ID2', NOW(),   '$Sub_Department_ID','$transactionType',NOW(),'$finance_department_id2')") or die(mysqli_error($conn));
                        $Payment_Item_Cache_List =mysqli_insert_id($conn); 
                    if ($insertround) {                       
                    $Patient_Bill_ID2=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Bill_ID from tbl_patient_bill WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1"))['Patient_Bill_ID'];
                    $create_receipt = mysqli_query($conn, "INSERT INTO tbl_patient_payments(Registration_ID,Employee_ID,    Payment_Date_And_Time,Folio_Number,     Sponsor_ID,Sponsor_Name,Billing_Type,    Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID, payment_type, Pre_Paid)  values('$Registration_ID','$employee_ID',(select now()),'$Folio_Number','$Sponsor_ID','$Sponsor_Name','$billingType',(select now()),'$branch_id','$Patient_Bill_ID2','$Check_In_ID', 'post', '1')") or die('kfkjf'.mysqli_error($conn));
                        if($create_receipt){
                            $Patient_Payment_ID2 =mysqli_insert_id($conn);
                            $add_sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,    Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,  Transaction_Date_And_Time,finance_department_id,Sub_Department_ID)values('others','$Round_item_ID','$Discount','$Items_Price','1',   'Others','$Consultant','$employee_ID','$Patient_Payment_ID2',(select now()),'$finance_department_id1','$Sub_Department_ID')") or die(mysqli_error($conn));                
                            if($add_sql){
                                mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',finance_department_id='$finance_department_id1',Patient_Payment_ID='$Patient_Payment_ID2' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List' AND Status='notsaved'") or die(mysqli_error($conn));
                            }
                        }
                    }
                }
                }
            }

            //===========END SAVE WARD ROUND ITEM BY MUGA=======

            if (($billingType == 'inpatient cash' ) || ($billingType == 'inpatient credit' && $transactionType == "cash")) {
                if ($pre_paid == '1') {
                    $transStatust = false;
                } elseif ($payment_type == 'pre') {
                    $transStatust = false;
                }
            } else {
                $transStatust = true;
                $checkIfAutoBilling = mysqli_query($conn,"SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' and  enab_auto_billing='yes'") or die(mysqli_error($conn));
            }

            //echo "SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' and  enab_auto_billing='yes'";
            //echo $transStatust .' && '. mysqli_num_rows($checkIfAutoBilling);

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            
            $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
            $finance_department_id=$_SESSION['finance_department_id'];
            
            if ($transStatust && mysqli_num_rows($checkIfAutoBilling) > 0 && !in_array($Check_In_Type, $excludedCheckType)) {
                if (mysqli_num_rows($check_if_covered2) > 0) {
                   // die("die1");
                    mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',finance_department_id='$finance_department_id',Clinic_ID='$doctors_selected_clinic' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                } else {
                   // die("die2");
                    auto_bill($Payment_Item_Cache_List_ID, $Item_ID, $Sponsor_ID, $Check_In_Type, $Discount, $Price, $Quantity, $rowup['Billing_Type']);
                }
               // die("die5");
            } else {
                
                if (mysqli_num_rows($check_if_covered2) > 0) {
                    mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',finance_department_id='$finance_department_id',Clinic_ID='$doctors_selected_clinic' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                } else {
                    mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active',finance_department_id='$finance_department_id',Clinic_ID='$doctors_selected_clinic' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                }
               // die("die3");
            }
           // die("die4");
        }
    }

        /** * * ========START FREE ITEMS 26-01-2019========  ***/
        $Check_In_ID1=mysqli_fetch_assoc(mysqli_query($conn,"select Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC"))['Check_In_ID'];       
        $Patient_Bill_ID1=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC"))['Patient_Bill_ID'];
        $Payment_Cache_ID1=mysqli_fetch_assoc(mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Payment_Cache_ID'];
        $Employee_ID1=mysqli_fetch_assoc(mysqli_query($conn,"select Employee_ID,Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Employee_ID'];
        $get_items =mysqli_query($conn,"SELECT li.Sub_Department_ID,li.Item_ID,ca.Folio_Number,ca.Sponsor_ID,ca.Sponsor_Name,ca.branch_id,li.Check_In_Type,li.Discount,li.Price,li.Quantity,li.Consultant,li.Consultant_ID,li.Clinic_ID,li.finance_department_id,li.Payment_Item_Cache_List_ID FROM tbl_item_list_cache li,tbl_payment_cache ca WHERE li.Payment_Cache_ID=ca.Payment_Cache_ID AND li.Payment_Cache_ID='$Payment_Cache_ID1'");
        
        $count=0;
        while($rows=mysqli_fetch_assoc($get_items)){
                $Item_ID1 =$rows['Item_ID'];
                $Folio_Number1 =$rows['Folio_Number'];
                $Sponsor_ID1 =$rows['Sponsor_ID'];
                $Sponsor_Name1 =$rows['Sponsor_Name'];
                $branch_id1 =$rows['branch_id'];
                $Check_In_Type1 =$rows['Check_In_Type'];
                $Discount1 =$rows['Discount'];
                $Price1 =$rows['Price'];
                $Quantity1 =$rows['Quantity'];
                $Consultant1 =$rows['Consultant'];
                $Consultant_ID1 =$rows['Consultant_ID'];
                $Clinic_ID1 =$rows['Clinic_ID'];
                $finance_department_id1 =$rows['finance_department_id'];
                $Payment_Item_Cache_List_ID1 =$rows['Payment_Item_Cache_List_ID'];
                $Sub_Department_ID =$rows['Sub_Department_ID'];

            $sql_fetch_free_items = mysqli_query($conn,"SELECT * FROM tbl_free_items WHERE item_id='$Item_ID1' AND sponsor_id='$Sponsor_ID1'") or die(mysqli_error($conn)); 

            if(mysqli_num_rows($sql_fetch_free_items)>0){
                if($count<=0){
                    $sql_select_sponsor_id_result=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration where Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    $Sponsor_ID=mysqli_fetch_assoc($sql_select_sponsor_id_result)['Sponsor_ID'];
                    // die($Sponsor_ID."moja");
                    $create_receipt = mysqli_query($conn,"INSERT into tbl_patient_payments(
                                    Registration_ID,Employee_ID,
                                    Payment_Date_And_Time,Folio_Number,
                                    Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)
                            values('$Registration_ID','$Employee_ID1',(select now()),'$Folio_Number1','$Sponsor_ID','$Sponsor_Name1','Free Item',(select now()),'$branch_id1','$Patient_Bill_ID1','$Check_In_ID1')") or die(mysqli_error($conn));  
                    
                }
                
                $Patient_Payment_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Payment_ID from tbl_patient_payments WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Payment_ID DESC"))['Patient_Payment_ID'];  
                    
                $add_sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,    Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,  Transaction_Date_And_Time,finance_department_id,Sub_Department_ID)values('$Check_In_Type1','$Item_ID1','$Discount1','$Price1','$Quantity1',   'Others','$Consultant1','$Consultant_ID1','$Patient_Payment_ID1',(select now()),'$finance_department_id1','$Sub_Department_ID')") or die(mysqli_error($conn));  
                

                
                if($add_sql){
                    $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'paid',  Patient_Payment_ID = '$Patient_Payment_ID1',     Payment_Date_And_Time =(select now()) where  Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID1'") or die(mysqli_error($conn));
                }
                
            }
        
            $count++;
        }
        
    /** *  * ================================ END FREE ITEMS ==============================***/

        
        if (isset($_SESSION['curr_receiptNumber']) && $_SESSION['curr_receiptNumber'] == true) {
            unset($_SESSION['curr_receiptNumber']);
        }

        // mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active' WHERE Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_id='$consultation_ID' AND Round_ID='$Round_ID' AND Status='notsaved')") or die(mysqli_error($conn));


        if ($dischargedPatient == 'Discharge') {
            $qr = "SELECT Admission_ID FROM tbl_check_in_details WHERE Registration_ID='" . $_GET['Registration_ID'] . "' AND consultation_ID='" . $_GET['consultation_ID'] . "'";
            $rs = mysqli_query($conn,$qr) or die(mysqli_error($conn));

            $Admission_ID = mysqli_fetch_assoc($rs)['Admission_ID'];
            $query = mysqli_query($conn,"UPDATE tbl_admission SET Doctor_Status='initial_pending',pending_set_time=NOW(),Discharge_Reason_ID='$discharge_reason',pending_setter='$employee_ID' WHERE Admision_ID='$Admission_ID'") or die(mysqli_error($conn));
        }
        ?>
        <script type='text/javascript' >
            alert("ROUND SAVED SUCCESSFULLY");
            document.location = 'admittedpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        </script>
        <?php
    } else {
        die(mysqli_error($conn));
    }
}

function auto_bill($Payment_Item_Cache_List_ID, $Item_ID, $Sponsor_ID, $Check_In_Type, $Discount, $Price, $Quantity, $billingType) {
    global $conn;
    $has_no_folio = false;
    $Folio_Number = '';
    $Registration_ID = $_GET['Registration_ID'];
    $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];


    $select = mysqli_query($conn,"select Folio_Number,Guarantor_Name,Claim_Form_Number from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

    $rows = mysqli_fetch_array($select);
    $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Folio_Number = $rows['Folio_Number'];
    $Sponsor_Name = $rows['Guarantor_Name'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    $Claim_Form_Number = $rows['Claim_Form_Number'];


    if (!isset($_SESSION['curr_receiptNumber'])) {
        include("./includes/Get_Patient_Transaction_Number.php");
        $sql = " insert into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                              (select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID',
                                              '$Sponsor_Name','$billingType',(select now()),'$Branch_ID','$Patient_Bill_ID')";

        $insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));

        $_SESSION['curr_receiptNumber'] = true;
    }

    $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $num_row = mysqli_num_rows($select_details);
    if ($num_row > 0) {
        $details_data = mysqli_fetch_row($select_details);
        $Patient_Payment_ID = $details_data[0];
        $Receipt_Date = $details_data[1];
    } else {
        $Patient_Payment_ID = 0;
        $Receipt_Date = '';
    }

    $Consultant = '';
    $Consultant_ID = $Employee_ID;

    //insert data to tbl_patient_payment_item_list
    if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
        $stat = 'active';

        if ($Check_In_Type == 'Others') {
            $stat = 'served';
        }
            $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
            $finance_department_id=$_SESSION['finance_department_id'];
        mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='$stat',finance_department_id='$finance_department_id',Clinic_ID='$doctors_selected_clinic',Patient_Payment_ID='$Patient_Payment_ID',Payment_Date_And_Time=NOW() WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
        
        $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,ItemOrigin,Clinic_ID,finance_department_id)   values('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW(),'Doctor','$doctors_selected_clinic','$finance_department_id')") or die(mysqli_error($conn));
    }
}

function bill() {
    global $conn;
    $has_no_folio = false;
    $Folio_Number = '';
    $Registration_ID = $_GET['Registration_ID'];
    $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));


    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

    $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    $Sponsor_ID2 = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

    $select = mysqli_query($conn,"select Folio_Number,pp.Sponsor_ID,Guarantor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID2 . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

    if (mysqli_num_rows($select)) {
        $rows = mysqli_fetch_array($select);
        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Folio_Number = $rows['Folio_Number'];
        $Sponsor_ID = $rows['Sponsor_ID'];
        $Sponsor_Name = $rows['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rows['Claim_Form_Number'];

        if (strtolower($Sponsor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID)) == 'cash') {
            $Billing_Type = "Inpatient Cash";
        } else {
            $Billing_Type = "Inpatient Credit";
        }

        //get last check in id
    } else {
        include("./includes/Folio_Number_Generator_Emergency.php");
        $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
        $rows = mysqli_fetch_array($select);

        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Sponsor_ID = $rows['Sponsor_ID'];
        $Sponsor_Name = $rows['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rows['Claim_Form_Number'];

        if (strtolower($Sponsor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID)) == 'cash') {
            $Billing_Type = "Inpatient Cash";
        } else {
            $Billing_Type = "Inpatient Credit";
        }

        $has_no_folio = true;
    }

    $result = mysqli_query($conn,"SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round
					       WHERE consultation_ID = '" . $_GET['consultation_ID'] . "' AND Registration_ID='$Registration_ID' AND employee_ID='" . $_SESSION['userinfo']['Employee_ID'] . "' ") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    $Round_ID = $row['Round_ID'];
    $pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

    if ($pre_paid == '0') {
        include("./includes/Get_Patient_Transaction_Number.php");
        $sql = "INSERT into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                              (select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID2',
                                              '$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID')";

        //die($sql);

        $insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));

        if ($insert) {

            //get the last patient_payment_id & date
            $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
            $num_row = mysqli_num_rows($select_details);
            if ($num_row > 0) {
                $details_data = mysqli_fetch_row($select_details);
                $Patient_Payment_ID = $details_data[0];
                $Receipt_Date = $details_data[1];
            } else {
                $Patient_Payment_ID = 0;
                $Receipt_Date = '';
            }

            $queryName = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
            $Guarantor_Name = mysqli_fetch_assoc($queryName)['Guarantor_Name'];
            //get data from tbl_item_list_cache
            $Item_ID = $_GET['item_ID'];
            $Discount = 0;
            $Price = getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name);
            $Quantity = 1;
            $Consultant = '';
            $Consultant_ID = $Employee_ID;

            global $doctors_selected_clinic;
           // $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
            $finance_department_id=$_SESSION['finance_department_id'];
            //insert data to tbl_patient_payment_item_list
            if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                $resultupdateWardRound = mysqli_query($conn,"UPDATE tbl_ward_round SET Patient_Payment_ID='$Patient_Payment_ID', Admision_ID = '$Admision_ID' WHERE Round_ID='$Round_ID'") or die(mysqli_error($conn));

                $insert = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,ItemOrigin,Clinic_ID,finance_department_id)    values('IPD Services','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW(),'Doctor','$doctors_selected_clinic','$finance_department_id')") or die(mysqli_error($conn));
            }

            //check if this user has folio 

            if ($has_no_folio) {
                $update_checkin_details = "
			UPDATE tbl_check_in_details 
				SET Folio_Number='$Folio_Number'
					WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID='" . $_GET['consultation_ID'] . "'";
                mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
            }
        }
    }
}

function getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name) {
    global $conn;
    $Item_ID = $Item_ID;
    $Billing_Type = $Billing_Type;
    $Guarantor_Name = $Guarantor_Name;

    $Price = 0;

    $Sponsor_ID = 0;

    $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];

//    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    }

    $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

    if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
        $Price = $row['price'];
        if ($Price == 0) {
            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {
                $row = mysqli_fetch_assoc($itemGenResult);
                $Price = $row['price'];
            } else {
                $Price = 0;
            }
        }

        //echo $Select_Price;
    } else {
        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemGenResult) > 0) {
            $row = mysqli_fetch_assoc($itemGenResult);
            $Price = $row['price'];
        }
    }

    return $Price;
}
?>
<!--START HERE-->
<link rel="stylesheet" type="text/css" href="jquerytabs/jquery-ui.theme.css"/>

<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->m . " Months";
        }
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->d . " Days";
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') {
        if (isset($_GET['Registration_ID'])) {
            ?>
            <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo $_GET['consultation_ID']; ?>&item_ID=<?php echo $_GET['item_ID']; ?>&position=in" class='art-button-green'  target="_blank">PATIENT FILE-DETAILS</a>
            <a href="Cancer_Patient.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>" class='art-button-green'> CANCER FORM</a>
            <?php
            echo "
                    <input type='button' name='patient_file' id='patient_file' value='SUMMERY PATIENT FILE' onclick='showSummeryPatientFile()' class='art-button-green' />";
        }
        ?>
        <input type='button' name='patientFileByFolio' id='patientFileByFolio' value='PATIENT FILE BY FOLIO' onclick='patientFileByFolio()' class='art-button-green' />
<a style="background: red;" href="Radiology_image_viewer.php?Registration_ID=<?php echo $Registration_ID; ?>" target="_blank" class='art-button-green'/>
            IMAGE VIEWER
    </a>    
    
        <a href='doctorspageinpatientwork.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>' class='art-button-green'>
            BACK 
        </a>
        <?php
    }
}
?>
<input type='button' name='patientFileByFolio' id='patientFileByFolio' style='background: #d40b72;'value='NURSING CARE SUMMARY' onclick='preview_nurse_documents(<?php echo $Registration_ID; ?>,<?php echo $consultation_ID; ?>,<?php echo $Admission_ID; ?>)' class='art-button-green' />
<a href="#" class="art-button-green" style="background:#d40b72;;" onclick="open_consultation_form_dialogy(<?php echo $Registration_ID; ?>)">REQUEST FOR CONSULTATION</a>

<br/><br/>
<div id="open_consultation_form_dialogy_div" style="display:none">
        <div class="col-md-6">
            <a class="btn btn-default btn-block" name="request_type" onclick="display_request_form(<?php echo $Registration_ID; ?>)">WRITE REQUEST</a>
        </div>
        <!-- <div class="col-md-4"> 
            <a class="btn btn-default btn-block" >NEW REQUEST</a>
        </div> -->
        <div class="col-md-6">
            <a class="btn btn-default btn-block" name="pre_request" onclick="display_request_forms(<?php echo $Registration_ID; ?>)">PREVIOUS REQUEST</a>
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="col-md-12" style="height:70vh;overflow-y: scroll;" id="consultation_form_request_body">

        </div>
    </div>
    <input type="text" class="Registration_ID" value="<?php echo $Registration_ID; ?>" style="display: none;">

    <script type="text/javascript">
        function display_request_form(Registration_ID) {
            $.ajax({
                type: 'POST',
                url: 'ajax_display_request_form.php',
                data: {
                    request_type: '',
                    Registration_ID: Registration_ID
                },
                success: function(data) {
                    $("#consultation_form_request_body").html(data);
                    $('select').select2();
                }
            });
        }

        function display_request_forms(Registration_ID) {
            $.ajax({
                type: 'POST',
                url: 'ajax_display_request_form.php',
                data: {
                    pre_request: '',
                    Registration_ID: Registration_ID
                },
                success: function(responce) {
                    $("#consultation_form_request_body").html(responce);
                    // $('select').select2();
                }
            });
        }

        function reply_request_consultation(Request_Consultation_ID, Registration_ID) {
            $.ajax({
                type: 'POST',
                url: 'ajax_display_request_form.php',
                data: {
                    preview_request: '',
                    Registration_ID: Registration_ID,
                    Request_Consultation_ID: Request_Consultation_ID
                },
                success: function(responce) {
                    $("#consultation_form_request_body").html(responce);
                    // $('select').select2();
                    display_replay()
                }
            });
        }

        function Replay_consultation_request(Registration_ID, Request_Consultation_ID) {
            var consultation_request_replay = $("#consultation_request_replay").val();
            if (consultation_request_replay == "") {
                $("#consultation_request_replay").css("border", "2px solid red");
            } else {
                $("#consultation_request_replay").css("border", "");
                $.ajax({
                    type: 'POST',
                    url: 'Ajax_save_burn_unit.php',
                    data: {
                        consultation_request_replay: consultation_request_replay,
                        Registration_ID: Registration_ID,
                        Request_Consultation_ID: Request_Consultation_ID,
                        replay_btn: ''
                    },
                    success: function(success) {
                        display_replay()

                    }
                })
            }
        }

        function display_replay() {
            var Request_Consultation_ID = $("#Request_Consultation_ID").val();
            var Registration_ID = $(".Registration_ID").val();
            $.ajax({
                type: 'POST',
                url: 'Ajax_display_burn.php',
                data: {
                    Request_Consultation_ID: Request_Consultation_ID,
                    Registration_ID: Registration_ID,
                    reply_body: ''
                },
                success: function(responce) {
                    $("#reply_body").html(responce);
                }
            })
        }

        function update_consultation_request(Request_Consultation_ID) {
            var Request_type = "";
            if ($("#Emergency").is(":checked")) {
                Request_type += "Emergency" + ','
            }
            if ($("#Urgent").is(":checked")) {
                Request_type += "Urgent" + ','
            }
            if ($("#Routine").is(":checked")) {
                Request_type += "Routine" + ','
            }

            var Brief_case_summary = $("#Brief_case_summary").val();
            var Question = $("#Question").val();
            // var Request_from = $("#Request_from").val();
            var Request_to = $("#Request_to").val();
            var Diagnosis = $("#Diagnosis").val();
            if (Brief_case_summary == "") {
                $("#Brief_case_summary").css("border", "1px solid red");
            } else if (Diagnosis == "") {
                $("#Diagnosis").css("border", "1px solid red");
            } else if (Request_to == "") {
                $("#Request_to").css("border", "1px solid red");

            } else {
                $("#Diagnosis").css("border", "");
                $("#Request_to").css("border", "");
                $("#Brief_case_summary").css("border", "");
                $.ajax({
                    type: 'POST',
                    url: 'Ajax_save_burn_unit.php',
                    data: {
                        Request_type: Request_type,
                        Request_Consultation_ID: Request_Consultation_ID,
                        Request_to: Request_to,
                        Diagnosis: Diagnosis,
                        Brief_case_summary: Brief_case_summary,
                        Question: Question,
                        request_btn_update: ''
                    },
                    success: function(responce) {
                        alert("Updated successful");
                    }
                });
            }
        }

        function save_request_form_data(Registration_ID) {
            var Request_type = "";
            if ($("#Emergency").is(":checked")) {
                Request_type += "Emergency" + ','
            }
            if ($("#Urgent").is(":checked")) {
                Request_type += "Urgent" + ','
            }
            if ($("#Routine").is(":checked")) {
                Request_type += "Routine" + ','
            }

            var Brief_case_summary = $("#Brief_case_summary").val();
            var Question = $("#Question").val();
            var Request_from = $("#Request_from").val();
            var Request_to = $("#Request_to").val();
            var Diagnosis = $("#Diagnosis").val();
            if (Brief_case_summary == "") {
                $("#Brief_case_summary").css("border", "1px solid red");
            } else if (Diagnosis == "") {
                $("#Diagnosis").css("border", "1px solid red");
            } else if (Request_to == "") {
                $("#Request_to").css("border", "1px solid red");
            } else {
                $("#Diagnosis").css("border", "");
                $("#Request_to").css("border", "");
                $("#Brief_case_summary").css("border", "");
                $.ajax({
                    type: 'POST',
                    url: 'Ajax_save_burn_unit.php',
                    data: {
                        Registration_ID: Registration_ID,
                        Request_type: Request_type,
                        Request_from: Request_from,
                        Request_to: Request_to,
                        Diagnosis: Diagnosis,
                        Brief_case_summary: Brief_case_summary,
                        Question: Question,
                        request_btn: ''
                    },
                    success: function(responce) {
                        alert("Saved successful");
                    }
                });
            }
        }

        function open_consultation_form_dialogy(Registration_ID) {
            var Patient_Name = $("#Patient_Name").val();
            $("#open_consultation_form_dialogy_div").dialog({
                title: 'CONSULTATION FORM  ' + Patient_Name + " " + Registration_ID,
                width: '90%',
                height: 700,
                modal: true,
            });
        }

        function select_diagnosis_for_consultation(Registration_ID) {
            $.ajax({
                type: 'POST',
                url: 'ajax_display_request_form.php',
                data: {
                    Registration_ID: Registration_ID,
                    select_diagnosis: ''
                },
                success: function(responce) {
                    $("#select_diagnosis_div").dialog({
                        title: 'SELECT DIAGNOSIS ',
                        width: '60%',
                        height: 400,
                        modal: true,
                    });
                    $("#select_diagnosis_div").html(responce);
                }
            });
        }
    </script>


<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "
					    and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Employee_Name = 'Unknown Employee';
    $employee_ID = 0;
}
//GET CONSULATATION ID IF IS SET/AVAILABLE
//$Patient_Payment_ID;

if (isset($_GET['src']) && $_GET['src'] == 'edit' && isset($_GET['Round_ID']) && !empty($_GET['Round_ID'])) {
    $Round_ID = $_GET['Round_ID'];
} else {

    $result = mysqli_query($conn,"SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round
					       WHERE consultation_ID = '$consultation_ID' AND Registration_ID='$Registration_ID' AND employee_ID='$employee_ID' ") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    $Round_ID = $row['Round_ID'];
}

$round_query_count = "SELECT COUNT(Round_ID) as No_Rounds FROM tbl_ward_round WHERE consultation_ID = '$consultation_ID' AND Registration_ID='$Registration_ID' AND DATE(Ward_Round_Date_And_Time)=DATE(NOW()) AND Process_Status='served'";
$round_count_result = mysqli_query($conn,$round_query_count) or die(mysqli_error($conn));


$patient_round_count = 0;

if (mysqli_num_rows($round_count_result) > 0) {
    //die($round_query_count);
    $patient_round_count = mysqli_fetch_assoc($round_count_result)['No_Rounds'];

    if ($patient_round_count > 0) {
        $patient_round_count += 1;
    } else {
        $patient_round_count = 1;
    }
}
?>
<script>

    function getItem(Consultation_Type) {
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }
        // alert('Nipo hapa');
        document.getElementById("recentConsultaionTyp").value = Consultation_Type;

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
if ($Registration_ID != '') {
    echo "Registration_ID=$Registration_ID&";
}
?><?php
if ($Round_ID != 0) {
    echo "Round_ID=" . $Round_ID . "&";
}if (isset($_GET['consultation_ID'])) {
    echo "consultation_ID=" . $_GET['consultation_ID'] . "";
}
echo "&Admision_ID=".$Admision_ID;
?>';

        $.ajax({
            type: 'GET',
            url: 'inpatientdoctoritemselectajax.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#myConsult').html(html);
                // $('#investigation').html(html);
                //alert(url2);
                $("#showdataConsult").dialog("open");
            }
        });


    }

</script>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }

    function getDisease(Consultation_Type) {
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }
        var frm = document.getElementById("clinicalnotes");
        var url = './inpatientclinicalautosave_todisease.php?Consultation_Type=' + Consultation_Type + '&<?php
if ($Registration_ID != '') {
    echo "Registration_ID=$Registration_ID&";
}
?><?php
if ($Round_ID != 0) {
    echo "Round_ID=" . $Round_ID . "&";
}if (isset($_GET['consultation_ID'])) {
    echo "consultation_ID=" . $_GET['consultation_ID'] . "";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        frm.action = url;
        frm.method = 'POST';
        frm.submit();
    }
</script>
<script>
    //function to confirm provisional diagnosis before test name selection 
    function confirmDiagnosis(Consultation_Type) {
<?php
if ($req_ip_prov_dign == '1') {
    ?>
            var testnameSelection = confirm("You about to specify laboratory test names for this patient.\nClick Ok to continue without provisional diagnosis.");


            if (testnameSelection) {
                getItem(Consultation_Type);
                return true;
            } else {
                location.href = "#";
                return false;
            }
    <?php
} else {
    ?>
            getItem(Consultation_Type);
    <?php
}
?>

    }
</script>
<script>
    function getDiseaseFinal(Consultation_Type) {
        // alert('Tis is it');
        var Round_ID = '<?php echo $Round_ID; ?>';

        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }

        var frm = document.getElementById("clinicalnotes");
        document.getElementById("recentConsultaionTyp").value = Consultation_Type;

        //alert('gsmmm');
        var ul = 'inpatientdoctoritemselectajax.php';
        if (Consultation_Type == 'diagnosis' || Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis') {
            ul = 'inpatientdoctordiagnosisselect.php';

        }

        var url = './inpatientclinicalautosave_todisease.php?Consultation_Type=' + Consultation_Type + '&Round_ID=' + Round_ID + '&<?php
if ($Registration_ID != '') {
    echo "Registration_ID=$Registration_ID&";
}
?><?php
if (isset($_GET['consultation_ID'])) {
    echo "consultation_ID=" . $_GET['consultation_ID'] . "&";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();
        // return false;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', url, true);
        mm.send();

        function AJAXP2() {
            var data1 = mm.responseText;

            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }


        var url2 = 'Consultation_Type=' + Consultation_Type + '&Round_ID=' + Round_ID + '&<?php
if ($Registration_ID != '') {
    echo "Registration_ID=$Registration_ID&";
}
?><?php
if ($consultation_ID != 0) {
    echo "consultation_ID=" . $consultation_ID . "";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';

        $.ajax({
            type: 'GET',
            url: ul,
            data: url2,
            success: function (html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });



    }
</script>

<center>
    <table width='100%' style='background: #006400 !important;color: white;'>
        <tr>
            <td>
        <center>
            <b>DOCTORS INPATIENT WORKPAGE : CLINICAL NOTES</b>
        </center>
        </td>
        </tr>
        <tr>
            <td>
        <center>
            <?php echo strtoupper($Patient_Name) . ', ' . strtoupper($Gender) . ', (' . $age . '), ' . strtoupper($Guarantor_Name); ?>
        </center>
        </td>
        </tr>
        <tr>
            <td>
        <center>
            <b>Today's Patient Rounds</b>&nbsp;&nbsp;<span style="background-color:red; padding:1px 4px 1px 4px; color:#fff; font-size:14px; border-radius:9px;"><?php echo $patient_round_count; ?></span>
        </center>
        </td>
        </tr>
    </table>
</center>
<fieldset >
    <form action='inpatientclinicalnotes.php?<?php echo $_SERVER['QUERY_STRING'] ?>' name='clinicalnotes' id='clinicalnotes' method='post'>
        <?php
        $allow_display_prev = $_SESSION['hospitalConsultaioninfo']['allow_display_prev'];

//get round items for edit

        if (isset($_GET['src']) && $_GET['src'] == 'edit' && isset($_GET['Round_ID']) && !empty($_GET['Round_ID'])) {
            $Round_ID = $_GET['Round_ID'];

            $select_round = "SELECT * FROM tbl_ward_round WHERE Process_Status='served' AND consultation_ID='" . $_GET['consultation_ID'] . "' AND Round_ID='" . $Round_ID . "'";


            $round_result = mysqli_query($conn,$select_round) or die(mysqli_error($conn));

            $round_rw = mysqli_fetch_assoc($round_result);

            $Find = $round_rw['Findings'];
            $Comment_For_Lab = $round_rw['Comment_For_Laboratory'];
            $Comment_For_Rad = $round_rw['Comment_For_Radiology'];
            $Comment_For_Proc = $round_rw['Comment_For_Procedure'];
            $Nuclearmedicinecomments2=$round_rw['Nuclearmedicinecomments'];
            $Comment_For_Surg = $round_rw['Comment_For_Surgery'];
            $investigation_comm = $round_rw['investigation_comments'];
            $remk = $round_rw['remarks'];
            $clinical_history = $round_rw['clinical_history'];
        } else {
            $Find = '';
            $Comment_For_Lab = '';
            $Comment_For_Rad = '';
            $Comment_For_Proc = '';
            $Nuclearmedicinecomments2='';
            $Comment_For_Surg = '';
            $investigation_comm = '';
            $remk = '';
            $clinical_history = '';
        }

        //Selesting Previously written consultation notes for this consultation of the same doctor  by Muga 23/04/2021
        
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $select_round = "SELECT * FROM tbl_ward_round WHERE  consultation_ID='" . $_GET['consultation_ID'] . "'  AND Process_Status='served' ORDER BY Round_ID DESC LIMIT 1";


        $round_result = mysqli_query($conn,$select_round) or die(mysqli_error($conn));
        $last_saved_round_ID = 0;

        if (mysqli_num_rows($round_result) > 0) {
            $round_row = mysqli_fetch_assoc($round_result);
            $last_saved_round_ID = $round_row['Round_ID'];
        } else {
            $last_saved_round_ID = 0;
        }
         // ================== Zioneshe za autosave =================
         $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
         if(!isset($_GET['src'])){
            $select_round_notes_logedin = mysqli_query($conn, "SELECT * FROM tbl_ward_round WHERE  consultation_ID='" . $_GET['consultation_ID'] . "'  AND Employee_ID='$Employee_ID' AND DATE(Ward_Round_Date_And_Time) =CURDATE() ORDER BY Round_ID DESC LIMIT 1") or die(mysqli_error($conn));
    
            if(mysqli_num_rows($select_round_notes_logedin)>0){
                while($doc_round = mysqli_fetch_assoc($select_round_notes_logedin)){
                    $clinical_history = $doc_round['clinical_history'];
                    $Find = $doc_round['Findings'];
                    $Comment_For_Lab= $doc_round['Comment_For_Lab'];
                    $Comment_For_Rad = $doc_round['Comment_For_Radiology'];
                    $Comment_For_Proc = $doc_round['Comment_For_Procedure'];
                    $Comment_For_Surg = $doc_round['Comment_For_Surgery'];
                    $investigation_comm = $doc_round['investigation_comments'];
                    $remk = $doc_round['remarks'];
                }
            }else{
                $Find = 'fdfd';
                $Comment_For_Lab = '';
                $Comment_For_Rad = '';
                $Comment_For_Proc = '';
                $Comment_For_Surg = '';
                $investigation_comm = '';
                $remk = '';
                $clinical_history = '';
            }
        }
         // ================++ End Zioneshe Autosaved notes for curdate =====================
 
        if (mysqli_num_rows($round_result) > 0) {

            if ($allow_display_prev == '1') {

                $clinical_history_round = (!empty($round_row['clinical_history'])) ? '<div class="previous-notes">' . $round_row['clinical_history'] . '</div>' : '';
                $Findings = (!empty($round_row['Findings'])) ? '<div class="previous-notes">' . $round_row['Findings'] . '</div>' : '';
                $Comment_For_Laboratory = (!empty($round_row['Comment_For_Laboratory'])) ? '<div class="previous-notes">' . $round_row['Comment_For_Laboratory'] . '</div>' : '';
                ;
                $Comment_For_Radiology = (!empty($round_row['Comment_For_Radiology'])) ? '<div class="previous-notes">' . $round_row['Comment_For_Radiology'] . '</div>' : '';
                ;
                $Comment_For_Procedure = (!empty($round_row['Comment_For_Procedure'])) ? '<div class="previous-notes">' . $round_row['Comment_For_Procedure'] . '</div>' : ''
                ;
                $Nuclearmedicinecomments = (!empty($round_row['Nuclearmedicinecomments'])) ? '<div class="previous-notes">' . $round_row['Nuclearmedicinecomments'] . '</div>' : ''
                ;
                $Comment_For_Surgery = (!empty($round_row['Comment_For_Surgery'])) ? '<div class="previous-notes">' . $round_row['Comment_For_Surgery'] . '</div>' : '';
                ;
                $investigation_comments = (!empty($round_row['investigation_comments'])) ? '<div class="previous-notes">' . $round_row['investigation_comments'] . '</div>' : '';
                ;
                $remarks = (!empty($round_row['remarks'])) ? '<div class="previous-notes">' . $round_row['remarks'] . '</div>' : '';
                ;
            } else {
                $Findings = '';
                $Comment_For_Laboratory = '';
                $Comment_For_Radiology = '';
                $Comment_For_Procedure = '';
                $Nuclearmedicinecomments ='';
                $Comment_For_Surgery = '';
                $investigation_comments = '';
                $remarks = '';
                $clinical_history_round = '';
            }
        } else {
            $Findings = '';
            $Comment_For_Laboratory = '';
            $Comment_For_Radiology = '';
            $Comment_For_Procedure = '';
            $Nuclearmedicinecomments ='';
            $Comment_For_Surgery = '';
            $investigation_comments = '';
            $remarks = '';
            $clinical_history_round = '';
        }

//select diagnosisi for this round
        $provisional_diagn = '';
        $diferential_diagn = '';
        $diagn = '';
        if (isset($_GET['src']) && $_GET['src'] == 'edit' && isset($_GET['Round_ID']) && !empty($_GET['Round_ID'])) {
            $Round_ID = $_GET['Round_ID'];

            $diagnosis_qr = "SELECT diagnosis_type,disease_name FROM tbl_ward_round_disease wd,tbl_disease d
		    WHERE wd.Round_ID = $Round_ID AND
		    wd.disease_ID = d.disease_ID 
                     ";
            //die($diagnosis_qr);
            $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                        $provisional_diagn .= ' ' . $row['disease_name'] . ';';
                    }
                    if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                        $diferential_diagn .= ' ' . $row['disease_name'] . ';';
                    }
                    if ($row['diagnosis_type'] == 'diagnosis') {
                        $diagn .= ' ' . $row['disease_name'] . ';';
                    }
                }
            }
        }



//selecting diagnosois
        if ($last_saved_round_ID != 0) {
            $provisional_diagnosis = '';
            $diferential_diagnosis = '';
            $diagnosis = '';

            
            //ZIONEKANE diagnosis za hyo consultation by Muga
            
            $diagnosis_qr="SELECT * FROM tbl_ward_round_disease wd,tbl_disease d,tbl_ward_round wr  WHERE wd.Round_ID=wr.Round_ID   AND wr.consultation_ID =$consultation_ID    AND wd.disease_ID = d.disease_ID GROUP BY disease_name";
            $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                        $provisional_diagnosis .= ' ' . $row['disease_name'] . ';';
                    }
                    if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                        $diferential_diagnosis .= ' ' . $row['disease_name'] . ';';
                    }
                    if ($row['diagnosis_type'] == 'diagnosis') {
                        $diagnosis .= ' ' . $row['disease_name'] . ';';
                    }
                }
            }

        }
        if ($allow_display_prev == '1') {
            $provisional_diagnosis1 =$provisional_diagnosis."";
            $diferential_diagnosis1 = $diferential_diagnosis;
            $diagnosis1 = $diagnosis;
            $provisional_diagnosis = (!empty($provisional_diagnosis)) ? '<div class="previous-notes">' . $provisional_diagnosis . '</div>' : '';
            $diferential_diagnosis = (!empty($diferential_diagnosis)) ? '<div class="previous-notes">' . $diferential_diagnosis . '</div>' : '';
            $diagnosis = (!empty($diagnosis)) ? '<div class="previous-notes">' . $diagnosis . '</div>' : '';
        } else {
            $provisional_diagnosis = '';
            $diferential_diagnosis = '';
            $diagnosis = '';
            $diagnosis1='';
            $diferential_diagnosis1='';
            $provisional_diagnosis1='';
        }

        $Rady = '';
        $Labory = '';
        $Pharcy = "";
        $Procr = "";
        $Surgry = "";
        $Othrs = "";
        $Nuclearmedicines="";
        if (isset($_GET['src']) && $_GET['src'] == 'edit' && isset($_GET['Round_ID']) && !empty($_GET['Round_ID'])) {
            $Round_ID = $_GET['Round_ID'];

            //Selecting Submitted Tests,Procedures, Drugs
            $select_payment_cache = "SELECT Check_In_Type,Product_Name ,Doctor_Comment
		FROM 
			tbl_payment_cache ipc,
			tbl_item_list_cache ilc,
			tbl_items i
			WHERE 
				ipc.Round_ID = $Round_ID AND 
				ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND 
				i.Item_ID = ilc.Item_ID AND
                                ilc.Status != 'notsaved'
				";

            $cache_result = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));

            if (mysqli_num_rows($cache_result) > 0) {

                while ($cache_row = mysqli_fetch_array($cache_result)) {
                    if ($cache_row['Check_In_Type'] == 'Radiology') {
                        $Rady .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Laboratory') {
                        $Labory .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                        if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                            $Pharcy .= ' ' . $cache_row['Product_Name'] . '[ ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                        } else {
                            $Pharcy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                        }
                        //$Pharmacy.= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                    }
                    if ($cache_row['Check_In_Type'] == 'Procedure') {
                        $Procr .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Surgery') {
                        $Surgry .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Nuclearmedicine') {
                        $Nuclearmedicines .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Others') {
                        $Othrs .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
            }
        }

        if ($last_saved_round_ID != 0) {

            //Selecting Submitted Tests,Procedures, Drugs
            $select_payment_cache = "SELECT  Check_In_Type,Product_Name ,Doctor_Comment 
		FROM 
			tbl_payment_cache ipc,
			tbl_item_list_cache ilc,
			tbl_items i
			WHERE 
				ipc.Round_ID = $last_saved_round_ID AND 
				ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND 
				i.Item_ID = ilc.Item_ID AND
                                ilc.Status != 'notsaved'
				";



            $cache_result = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));

            $Radiology = '';
            $Laboratory = '';
            $Pharmacy = "";
            $Procedure = "";
            $Surgery = "";
            $Others = "";

            if (mysqli_num_rows($cache_result) > 0) {

                while ($cache_row = mysqli_fetch_array($cache_result)) {
                    if ($cache_row['Check_In_Type'] == 'Radiology') {
                        $Radiology .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Laboratory') {
                        $Laboratory .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                        if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                            $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                        } else {
                            $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                        }
                        //$Pharmacy.= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                    }
                    if ($cache_row['Check_In_Type'] == 'Procedure') {
                        $Procedure .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                    if ($cache_row['Check_In_Type'] == 'Surgery') {
                        $Surgery .= ' ' . $cache_row['Product_Name'] . ';';
                    }

                    if ($cache_row['Check_In_Type'] == 'Others') {
                        $Others .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
            }
        }

        if ($allow_display_prev == '1') {
            $Radiology = (!empty($Radiology)) ? '<div class="previous-notes">' . $Radiology . '</div>' : '';
            $Laboratory = (!empty($Laboratory)) ? '<div class="previous-notes">' . $Laboratory . '</div>' : '';
            $Pharmacy = (!empty($Pharmacy)) ? '<div class="previous-notes">' . $Pharmacy . '</div>' : '';
            $Procedure = (!empty($Procedure)) ? '<div class="previous-notes">' . $Procedure . '</div>' : '';
            $Surgery = (!empty($Surgery)) ? '<div class="previous-notes">' . $Surgery . '</div>' : '';
            $Others = (!empty($Others)) ? '<div class="previous-notes">' . $Others . '</div>' : '';
        } else {
            $Radiology = '';
            $Laboratory = '';
            $Pharmacy = '';
            $Procedure = '';
            $Surgery = '';
            $Others = '';
        }



//Retrieve doctors participated
        $num_doctors = 0;
//if ($hospitalConsultType == 'One patient to many doctor') {
        $rsDoc = mysqli_query($conn,"SELECT COUNT(wr.Round_ID)AS docCount FROM tbl_ward_round wr  WHERE wr.consultation_ID=$consultation_ID AND wr.Registration_ID=$Registration_ID AND wr.employee_ID != $employee_ID AND Process_Status='served' LIMIT 10") or die(mysqli_error($conn));
        $num_doctors = mysqli_fetch_assoc($rsDoc)['docCount'];

        $auto_save_option = "";
        if ($_SESSION['hospitalConsultaioninfo']['set_doctors_auto_save'] == '1') {
            $auto_save_option = 'oninput="savedoctorinfos(this)"';
        }

//}
        ?>
        <div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
            <div id="myConsult">
            </div>
        </div>
        <div id="otherdoctorStaff" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
            <div align="center" style="display:none" id="doctorProgressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            <div id="doctorsInfo">
            </div>
        </div>
        <div id="summerypatientfile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
            <div align="center" style="display:none" id="summeryProgressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            <div id="summpatfileInfo">
            </div>
        </div>
        
        <?php
        if ($_SESSION['hospitalConsultaioninfo']['enable_clinic_not_scroll'] == '1') {
            include './clinic_scroll_inp_frame.php';
        } else {
            include './clinic_tabs_inp_frame.php'; 
        }
        ?>
        <input type='hidden' id='formsubmtt' name='formsubmtt'>
        <input type='hidden' id='recentConsultaionTyp' value=''>
        <center>
            <input type='button' id='send' name='send' value='SAVE'  class='art-button-green' onclick="check_Mortuary_status()"  style="width:15%;">
            <input type="button" value="BIOPSY REQUEST FORM" class="art-button-green" onclick="Biopsy_request_from()" style="margin-left:50px;" >
            <?php
            if (isset($_SESSION['hospitalConsultaioninfo']['Enable_Save_And_Transfer_Button']) && $_SESSION['hospitalConsultaioninfo']['Enable_Save_And_Transfer_Button'] == '1') {
                ?>
                <input type="button" name="Save_Transfer" id="Save_Transfer" class="art-button-green" value="SAVE AND TRANSFER" onclick="save_round2()" style="width:15%;">
                <?php
            }
            if ($num_doctors > 0) { ?>
                <button type="button" onclick="showOthersDoctorsStaff()" class="art-button-green">PREVIOUS DOCTOR'S NOTES <span id='alert_here' style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;<?php if ($num_doctors == 0) echo "display:none;"; ?> '> <?php echo $num_doctors; ?> </span></button>  
                <?php
            }
            ?>
            <input style="display:inline;" type='button' onclick="on_call_claim_form_dialogy()" value='ON CALL CLAIM' class='btn btn-sm btn-danger' style="width:15%;">

        </center>
    </form>
        <!-- // on call claim form -->
        <div id="claim_form" style="display:none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <form class="form-horzontal">
                                <div class="form-group">
                                    <label class="col-md-3">Select Ward</label>
                                    <div class="col-md-9">
                                        <select class='form-control' style='text-align: center;' id="ward_id">

                                            <?php
                                            $session_ward_id = $_SESSION['doctors_selected_ward'];
                                            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                            $Select_Ward = mysqli_query($conn, "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))");
                                            while ($Ward_Row = mysqli_fetch_array($Select_Ward)) {
                                                $ward_id = $Ward_Row['Hospital_Ward_ID'];
                                                $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                                                if ($session_ward_id === $ward_id) {
                                                    $sel = "selected='selected'";
                                                } else {
                                                    $sel = "";
                                                }
                                            ?>
                                                <option value="<?php echo $ward_id ?>" <?= $sel ?>><?php echo $Hospital_Ward_Name ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3"> Nurse on duty</label>
                                    <div class="col-md-9">

                                        <select class='form-control center' style='text-align: center; width:100%;' id="emp_id">
                                                <option value="">~~Select Nurse on Duty~~</option>
                                            <?php
                                            $Select_employee = mysqli_query($conn, "select Employee_ID,Employee_Name from tbl_employee where Employee_Type = 'Nurse'");
                                            while ($emp_Row = mysqli_fetch_array($Select_employee)) {
                                                $emp_id = $emp_Row['Employee_ID'];
                                                $emp_name = $emp_Row['Employee_Name'];
                                            ?>
                                                <option value="<?php echo $emp_id ?>"> <?php echo $emp_name ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </form>


                            <p class="text-danger text-center">I declare and confirm that I have reviewed this patient during my call.</p>
                            <br>

                            <?php

                            $data = '{"Registration_ID":"' . $Registration_ID . '","doctor_id":"' . $employee_ID . '","sponsor_id":"' . $Sponsor_ID . '","dept_id":"' . $finance_department_id . '"}';
                            ?>
                            <input type="button" value="SUBMIT" class="art-button-green pull-right" onclick='submit_oncall_claim(<?= $data ?>)' />

                        </div>
                    </div>
                </div>
            </div>

    <div id="Transfer_Patient_Dialog">
        <table width="100%" class="art-article">
            <tr>
                <td style="text-align: right;">Patient Name</td>
                <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
                <td style="text-align: right;">Gender</td>
                <td width="10%"><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
                <td style="text-align: right;">Patient Age</td>
                <td width="10%"><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
                <td style="text-align: right;">Sponsor Name</td>
                <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
            </tr>
        </table>
        <br/>Transfer Details<hr>
        <table width="100%" class="art-article">
            <tr>
                <?php
                //get patient current ward
                $get_ward = mysqli_query($conn,"select hw.Hospital_Ward_Name from
                                        tbl_admission ad, tbl_check_in_details cd, tbl_hospital_ward hw where
                                        ad.Hospital_Ward_ID = hw.Hospital_Ward_ID and
                                        cd.Admission_ID = ad.Admision_ID and
                                        cd.consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($get_ward);
                if ($nm > 0) {
                    while ($rw = mysqli_fetch_array($get_ward)) {
                        $Hospital_Ward_Name = $rw['Hospital_Ward_Name'];
                    }
                } else {
                    $Hospital_Ward_Name = '';
                }
                ?>
                <td style="text-align: right;" width="12%">Current Ward</td>
                <td width="18%">
                    <input type="text" value="<?php echo $Hospital_Ward_Name; ?>" readonly="readonly">
                </td>
                <td style="text-align: right;" width="13%">Transfered Ward</td>
                <td width="12%">
                    <select id="Hospital_Ward_ID" id="Hospital_Ward_ID" onchange="Refresh_Beds()">
                        <option value=""></option>
                        <?php
                        //get possible wards
                        $get_wards = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward where
                                        (ward_nature = 'Both' or ward_nature = '$Gender') and
                                        Hospital_Ward_Name <> '$Hospital_Ward_Name'") or die(mysqli_error($conn));
                        $nmz = mysqli_num_rows($get_wards);
                        if ($nmz > 0) {
                            while ($rw = mysqli_fetch_array($get_wards)) {
                                ?>
                                <option value="<?php echo $rw['Hospital_Ward_ID']; ?>"><?php echo $rw['Hospital_Ward_Name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td width="10%" id="Bed_Area">
                    <select id="Bed_ID" name="Bed_ID">
                </td>
                <td width="8%"><input type="button" value="SAVE AND TRANSFER" class="art-button-green" onclick="Transfer_Selected_Patient()">
            </tr>
        </table>
    </div>
    <div id="Transfer_Selected_Patient_Dialog">
        <table width="100%" class="art-article">
            <tr>
                <td style="text-align: center;">Are you sure you want to save & transfer selected patient?<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" value="YES" class="art-button-green" onclick="Transfer_Selected_Patient_Process()">
                    <input type="button" value="CANCEL" class="art-button-green" onclick="Close_Confirm_Dialog()">
                </td>
            </tr>
        </table>
    </div>
    <div id="getFileByFolio" style="display: none;">
        <div align="center" style="" id="getFileByFolioprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
        <div id="containerFileFolio">

        </div>
    </div>
    <div id="Transfer_Error">
        Process Fail! Please try again
    </div>
    <div id="prescribed_medicine_dialog"></div>
<!-- BIOPSY DIV -->
<div id="Biopsy_Form" style="width:50%;">

        <center id='Add_Postoperative_Area2'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
        </div>

        <div id="Investigation_Details2" style="width:50%;">
        <center id='Investigation_Area2'>

        </center>
</div>
<!-- BIOPSY DIV  -->
    <script>
                function on_call_claim_form_dialogy() {
                    if (confirm('Are you sure you what to submit this as on call claim?')) {
                        $("#claim_form").dialog({
                            title: 'ON CALL CLAIM',
                            width: '50%',
                            height: 250,
                            modal: true,
                        });
                    }
                }

                function submit_oncall_claim(data) {
                    var nurse_id = $('#emp_id').val();
                    var ward_id = $('#ward_id').val();
                    var Registration_ID = data.Registration_ID;
                    var doctor_id = data.doctor_id;
                    var sponsor_id = data.sponsor_id;
                    var dept_id = data.dept_id;
                        if(nurse_id==''){
                            alert("Please select Nurse On Duty");
                            exit()
                        }else if(doctor_id==''){
                            alert("Please Refresh page and continue with claiming call");
                            exit();
                        }
                    $.ajax({
                        type: 'POST',
                        url: 'on_call_claim.php',
                        data: {
                            nurse_id: nurse_id,
                            ward_id: ward_id,
                            Registration_ID: Registration_ID,
                            doctor_id: doctor_id,
                            sponsor_id: sponsor_id,
                            dept_id: dept_id
                        },
                        success: function(data) {
                            alert(data);
                            $("#claim_form").dialog("close");
                            
                        }
                    });
                }
            </script>

    <script type="text/javascript">
        function open_list_of_prescribed_medicine_dialog(){
            var consultation_ID='<?= $consultation_ID ?>';
            var Registration_ID='<?= $Registration_ID ?>';
            $.ajax({
                type:'POST',
                url:'ajax_open_list_of_prescribed_medicine_dialog.php',
                data:{consultation_ID:consultation_ID,Registration_ID:Registration_ID},
                success:function(data){
                  $("#prescribed_medicine_dialog").html(data);  
                  $("#prescribed_medicine_dialog").dialog("open");  
                }
            });
        }
        function prescribed_medicine(){
            var consultation_ID='<?= $consultation_ID ?>';
            var Registration_ID='<?= $Registration_ID ?>';
            $.ajax({
                type:'POST',
                url:'ajax_open_list_of_prescribed_medicine_dialog.php',
                data:{consultation_ID:consultation_ID,Registration_ID:Registration_ID},
                success:function(data){
                  $("#prescribed_medicine_dialog").html(data);   
                }
            });
        }
        function discontinue_medication_for_this_patient(Registration_ID,consultation_ID,Item_ID, Payment_Item_Cache_List_ID){
            var discontinue_reason=$("#discontinue_reason"+Item_ID).val();
            if(discontinue_reason==""){
                $("#discontinue_reason"+Item_ID).css("border","2px solid red");
                 $("#discontinue_reason"+Item_ID).focus();
                exit;
            }
            if(confirm("Are you sure you want to discontinue this medication for this patient?")){
                $.ajax({
                    type:'POST',
                    url:'ajax_discontinue_medication_for_this_patient.php',
                    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID,Item_ID:Item_ID,discontinue_reason:discontinue_reason, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
                    success:function(data){
                        if(data=="success"){
                            prescribed_medicine()
                        }else{
                            alert("Process Fail")
                        }
                        console.log(data);
                    }
                });
            }
        }
        function update_new_dosage(Payment_Item_Cache_List_ID,Doctor_Comment){
            var new_dosage=$("#new_dosage"+Payment_Item_Cache_List_ID).val();
            if(new_dosage==""){
                $("#new_dosage"+Payment_Item_Cache_List_ID).css("border","2px solid red");
                exit;
            }else{
                $("#new_dosage"+Payment_Item_Cache_List_ID).css("border","");
            }
            if(confirm("Are you sure you want to change patient dosage?")){
                $.ajax({
                    type: 'POST',
                    url: 'ajax_discontinue_medication_for_this_patient.php',
                    data: {
                        Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,new_dosage:new_dosage, update_dosage:''
                    },
                    success: function(data) {
                        if (data == "success") {
                            // alert("Dosage changed Successfully");
                            prescribed_medicine()
                        } else {
                            alert("Process Fail")
                        }
                        console.log(data);
                    }
                });
            }
        }
        function Refresh_Beds() {
            var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
            if (window.XMLHttpRequest) {
                myObjectRef = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRef = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRef.overrideMimeType('text/xml');
            }

            myObjectRef.onreadystatechange = function () {
                dataRef = myObjectRef.responseText;
                if (myObjectRef.readyState == 4) {
                    document.getElementById('Bed_Area').innerHTML = dataRef;
                }
            };
            myObjectRef.open('GET', 'Get_Transfer_Beds.php?Hospital_Ward_ID=' + Hospital_Ward_ID, true);
            myObjectRef.send();
        }
    </script>
    <script type="text/javascript">
        function Transfer_Selected_Patient() {
            var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
            var Bed_ID = document.getElementById("Bed_ID").value;

            if (Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Bed_ID != null && Bed_ID != '') {
                document.getElementById("Hospital_Ward_ID").style = 'border: 1px solid black';
                document.getElementById("Bed_ID").style = 'border: 1px solid black';
                $("#Transfer_Selected_Patient_Dialog").dialog("open");
            } else {
                if (Hospital_Ward_ID == null || Hospital_Ward_ID == '') {
                    document.getElementById("Hospital_Ward_ID").style = 'border: 2px solid red';
                } else {
                    document.getElementById("Hospital_Ward_ID").style = 'border: 1px solid black';
                }
                if (Bed_ID == null || Bed_ID == '') {
                    document.getElementById("Bed_ID").style = 'border: 2px solid red';
                } else {
                    document.getElementById("Bed_ID").style = 'border: 1px solid black';
                }
            }
        }

        function Close_Confirm_Dialog() {
            $("#Transfer_Selected_Patient_Dialog").dialog("close");
        }
    </script>

    <script type="text/javascript">
        function Transfer_Selected_Patient_Process() {
            var consultation_ID = "<?php echo $consultation_ID; ?>";
            var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
            var Bed_ID = document.getElementById("Bed_ID").value;

            if (consultation_ID != null && consultation_ID != '' && Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Bed_ID != null && Bed_ID != '') {
                document.getElementById("Hospital_Ward_ID").style = 'border: 1px solid black';
                document.getElementById("Bed_ID").style = 'border: 1px solid black';
                if (window.XMLHttpRequest) {
                    myObjectProc = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectProc = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectProc.overrideMimeType('text/xml');
                }

                myObjectProc.onreadystatechange = function () {
                    dataTran = myObjectProc.responseText;
                    if (myObjectProc.readyState == 4) {
                        var feedback = dataTran;
                        if (feedback == 'yes') {
                            document.getElementById('clinicalnotes').submit();
                        } else {
                            $("#Transfer_Selected_Patient_Dialog").dialog("close");
                            $("#Transfer_Error").dialog("open");
                        }
                    }
                };
                myObjectProc.open('GET', 'Transfer_Selected_Inpatient.php?consultation_ID=' + consultation_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Bed_ID=' + Bed_ID, true);
                myObjectProc.send();
            } else {
                if (Hospital_Ward_ID == null || Hospital_Ward_ID == '') {
                    document.getElementById("Hospital_Ward_ID").style = 'border: 2px solid red';
                } else {
                    document.getElementById("Hospital_Ward_ID").style = 'border: 1px solid black';
                }
                if (Bed_ID == null || Bed_ID == '') {
                    document.getElementById("Bed_ID").style = 'border: 2px solid red';
                } else {
                    document.getElementById("Bed_ID").style = 'border: 1px solid black';
                }
            }
        }
    </script>

    <script>
        $(function () {
            $("#tabs").tabs('option', 'a ctive',<?php
                        if (isset($_GET['Consultation_Type'])) {
                            $Consultation_Type = $_GET['Consultation_Type'];
                            if ($Consultation_Type == 'provisional_diagnosis' || $Consultation_Type == 'diferential_diagnosis') {
                                //add script for setting tab focus
                                echo 1;
                            } else if ($Consultation_Type == 'diagnosis' || $Consultation_Type == 'Treatment' || $Consultation_Type == 'Pharmacy' || $Consultation_Type == 'Procedure') {
                                echo 3;
                            } else if ($Consultation_Type == 'Laboratory' || $Consultation_Type == 'Radiology') {
                                echo 2;
                            } else if ($Consultation_Type == 'Laboratory') {
                                echo 2;
                            } else {
                                echo 0;
                            }
                        } else {
                            echo 0;
                        }
                        ?>);
        });
        $('#tabs').tabs();
    </script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
        $('#firstsymptom_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#firstsymptom_date').datetimepicker({value: '', step: 30});
    </script>
</fieldset>
<script>
    $(document).ready(function () {
        $("#showdataConsult").dialog({autoOpen: false, width: '90%', title: 'SELECT  ITEM FROM THIS CONSULTATION', modal: true, position: 'middle'});

        $("#otherdoctorStaff").dialog({autoOpen: false, width: '90%', height: 520, title: 'OTHER DOCTOR\'S REVIEWS', modal: true, position: 'middle'});

        $("#summerypatientfile").dialog({autoOpen: false, width: '95%', height: 620, title: 'PATIENT FILE', modal: true, position: 'middle'});
        $("#Transfer_Patient_Dialog").dialog({autoOpen: false, width: '70%', height: 220, title: 'eHMS 2.0 ~ SAVE AND TRANSFER PATIENT', modal: true, position: 'middle'});
        $("#Transfer_Selected_Patient_Dialog").dialog({autoOpen: false, width: '42%', height: 140, title: 'eHMS 2.0 ~ CONFIRM PATIENT TRANSFER', modal: true, position: 'middle'});
        $("#prescribed_medicine_dialog").dialog({autoOpen: false, width: '80%', height: 500, title: 'LIST OF PRESCRIBED MEDICINE', modal: true, position: 'middle'});
        $("#Transfer_Error").dialog({autoOpen: false, width: '30%', height: 120, title: 'eHMS 2.0 ~ Process Fail', modal: true, position: 'middle'});
        $("#Biopsy_Form").dialog({
                autoOpen: false, 
                width: '70%', 
                height: 650, 
                title: 'BIOPSY/HISTOLOGICAL EXAMINATION REQUESTING FORM', 
                modal: true
            });
        $("#Investigation_Details2").dialog({
                autoOpen: false, 
                width: '75%', 
                height: 500,
                title: 'ADD BIOPSY TEST', 
                modal: true
            });
        $(".ui-icon-closethick").click(function () {
            var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
            //alert(Consultation_Type);
            if (Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis' || Consultation_Type == 'diagnosis') {
                updateDoctorConsult();
            } else {
                updateConsult();
            }
        });

    });
</script>
<script>
    function updateConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo$Registration_ID ?>&Round_ID=<?php echo $Round_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
            type: 'GET',
            url: 'requests/itemselectupdateinpatient.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                var departs = html.split('tenganisha');
                for (var i = 0; i < departs.length; i++) {
                    var Consultation_Type = departs[i].split('<$$$&&&&>');
                    //alert(Consultation_Type[0]);
                    if (Consultation_Type[0] == 'Radiology') {
                        $('.Radiology').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0] == 'Treatment') {
                        $('.Treatment').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0] == 'Laboratory') {
                        $('#laboratory').html(Consultation_Type[1]);
                        //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                    } else if (Consultation_Type[0] == 'Procedure') {
                        $('.Procedure').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0] == 'Surgery') {
                        $('.Surgery').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0] == 'Others') {
                        $('.otherconstype').html(Consultation_Type[1]);
                    }else if (Consultation_Type[0] == 'Nuclearmedicine') {
                        $('.Nuclearmedicine').html(Consultation_Type[1]);
                    }
                }
            }
        });

    }

</script>
<?php 
    $sql_check_for_requirement_for_final_diagnosis_result=mysqli_query($conn,"SELECT require_final_diagnosis_before_select_treatment FROM tbl_hospital_consult_type") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_check_for_requirement_for_final_diagnosis_result)>0){
        $require_final_diagnosis=mysqli_fetch_assoc($sql_check_for_requirement_for_final_diagnosis_result)['require_final_diagnosis_before_select_treatment'];
    }else{
        $require_final_diagnosis="no";
    }
    ?>
<script>
    function updateDoctorConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo$Registration_ID ?>&Round_ID=<?php echo $Round_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
            type: 'GET',
            url: 'requests/itemdoctorselectupdateinpatient.php',
            data: url2,
            cache: false,
            success: function (html) {
                var Consultation_Type = html.split('<$$$&&&&>');

                if (Consultation_Type[0] == 'provisional_diagnosis') {
                    $('.provisional_diagnosis').attr('value', Consultation_Type[1]);

                    if ($('.provisional_diagnosis').val() != '') {
                        $('.confirmGetItem').attr("onclick", "getItem('Laboratory')");
                    } else {
                        $('.confirmGetItem').attr("onclick", "confirmDiagnosis('Laboratory')");
                    }
                } else if (Consultation_Type[0] == 'diferential_diagnosis') {
                    //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                    $('.diferential_diagnosis').attr('value', Consultation_Type[1]);
                } else if (Consultation_Type[0] == 'diagnosis') {
                    $('.final_diagnosis').attr('value', Consultation_Type[1]);
                }
            }
        });

    }
function confirm_final_diagnosis(Consultation_Type){
    var require_final_diagnosis='<?= $require_final_diagnosis ?>';
    if($('.final_diagnosis').val()==""&&require_final_diagnosis=="yes"){
       alert("YOU HAVE TO SELECT FINAL DIAGNOSIS FIRST,BEFORE ADD ANY TREATMENT"); 
    }else{
       getItem(Consultation_Type);
    }
}
</script>
<script>
    function doneDiagonosisselect() {
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        if (Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis' || Consultation_Type == 'diagnosis') {
            updateDoctorConsult();
        } else {
            updateConsult();
        }

        $("#showdataConsult").dialog("close");
    }
    
</script>

<script>
    $('select').select2();
</script>
<script>
    function dischargePatient(status) {
        var finalDiagonosis = $(".final_diagnosis").val();//document.getElementByClassName('final_diagnosis').value;//.getElementByClassName('final_diagnosis').value;

        if (finalDiagonosis == '' && status == 'Discharge') {
            alert('Please add final diagnosis before discharging the partient.');
            //status='Continue';
            $('#dischargedPatient').val('Continue');
            exit;
        }
        if (status != '') {
            if (confirm('Are you sure you want to change the patient to ' + status)) {

                if (window.XMLHttpRequest) {
                    mm2 = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    mm2 = new ActiveXObject('Microsoft.XMLHTTP');
                    mm2.overrideMimeType('text/xml');
                }

                mm2.onreadystatechange = AJAXP4; //specify name of function that will handle server response....
                mm2.open('GET', 'doctor_discharge_release.php?status=' + status + '&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&Registration_ID=<?php echo $Registration_ID ?>', true);
                mm2.send();
            }
        }
    }

    function AJAXP4() {
        if (mm2.readyState == 4) {
            var data = mm2.responseText;
            //alert(data);
            if (data == '1') {
                window.location = 'admittedpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
            }

        }
    }
</script>
<script>
    function updateDoctorItemCOmment(ppil, comm){
    //alert(ppil+' '+comm);exit;
    $.ajax({
    type:'POST',
    url:'requests/updateDoctorItemCOmment.php',
    data:'ppil=' + ppil + '&comm=' + comm,
    success:function(html){
    }, error:function(x, y, z){
    alert(z);
    }
    });
    }

    function cancel_discontinuetion(Item_ID,Registration_ID, Payment_Item_Cache_List_ID){
        if(confirm("Are you sure you want to undo Medicine Discontinuation")){
            $.ajax({
                type:'POST',
                url:'ajax_cancel_discontinuetion.php',
                data:{Item_ID:Item_ID,Registration_ID:Registration_ID, Payment_Item_Cache_List_ID},
                success:function(data){
                    if(data==="success"){
                        // alert("Process Successfully");s
                        prescribed_medicine()
                        // location.reload();
                    }else{
                       alert("Process Fail...Try again"); 
                    }
                }
            });
        }
    }
</script>
<div id="associated_doctor" style="display:none">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <input type="text" placeholder="~~~~~Search Doctor Name~~~~" id="associated_doctor_name" onkeyup="search_associated_doctor()" style="text-align: center"/>
                    </div>
                    <div class="box-body" id="list_of_associated_doctor_body" style="height:400px;overflow-x: hide;overflow-y: scroll;">
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h4>LIST OF ASSOCIATED DOCTORS</h4>
                    </div>
                    <div class="box-body" id="list_of_selected_associated_doctors" style="height:415px;overflow-x: hide;overflow-y: scroll;">
                        
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-md-12">
            <input type="button" value="DONE" class="art-button-green pull-right" onclick="close_associated_doctor_dialog()"/>
        </div>
    </div>
    </div>
<script>
    function close_associated_doctor_dialog(){
        console.log("----------juu---")
        $("#associated_doctor").dialog("close");
        console.log("-------------chini----------------");
        var dischargedPatient = document.getElementById('dischargedPatient').value;
            if (dischargedPatient == '') {
                check_Mortuary_status();
            }else{
               if (confirm("You are about to save this patient round.Click Ok to continue or cancel to return")) {
                    document.getElementById("clinicalnotes").submit();
                } 
            }
    }
    function search_associated_doctor(){
        var associated_doctor_name=$("#associated_doctor_name").val();
        $.ajax({
            type:'POST',
            url:'ajax_search_associated_doctor.php',
            data:{associated_doctor_name:associated_doctor_name},
            success:function(data){
                $("#list_of_associated_doctor_body").html(data);
            }
        });
    }//list_of_associated_doctor_body
    function associated_doctor_dialogy(){
        
        $("#associated_doctor").dialog({
                        title: 'ASSOCIATED DOCTORS',
                        width: '70%',
                        height: 600,
                        modal: true,
                    }); 
    }
    function fetch_list_of_selected_associated_doctors(){
         var consultation_ID='<?= $consultation_ID ?>';
         var Registration_ID='<?= $Registration_ID ?>';
         var Round_ID='<?= $Round_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_fetch_list_of_selected_associated_doctors.php',
            data:{consultation_ID:consultation_ID,Registration_ID:Registration_ID,Round_ID:Round_ID},
            success:function(data){
                //console.log("----->"+data);
                $("#list_of_selected_associated_doctors").html(data);
            }
        });
    }
    function remove_associated_doctor(round_associated_doctor_cache_id){
        $.ajax({
            type:'POST',
            url:'ajax_remove_associated_doctor.php',
            data:{round_associated_doctor_cache_id:round_associated_doctor_cache_id},
            success:function(data){
                //console.log(",m,m,m,m,==>"+data);
                fetch_list_of_selected_associated_doctors()
            }
        });
    }
    function add_associated_doctor_to_catch(Employee_ID){
         var consultation_ID='<?= $consultation_ID ?>';
         var Registration_ID='<?= $Registration_ID ?>';
         var Round_ID='<?= $Round_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_add_associated_doctor_to_catch.php',
            data:{consultation_ID:consultation_ID,Registration_ID:Registration_ID,Employee_ID:Employee_ID,Round_ID:Round_ID},
            success:function(data){
                console.log(data);
                if(data=="success"){
                      fetch_list_of_selected_associated_doctors();
                }else{
                    alert("Process Fail..please try again");
                }
//                $("#list_of_selected_associated_doctors").html(data);
            }
        });
    }
    $(document).ready(function(){
        search_associated_doctor();
        fetch_list_of_selected_associated_doctors();
    });
    function check_Mortuary_status(){
        Discharge_Reason_ID = $("#Discharge_Reason_ID").val();
        Admision_ID = '<?= $Admision_ID ?>';
        Registration_ID = '<?= $Registration_ID ?>';

            if(Discharge_Reason_ID == 3 && (Admision_ID != '' || Admision_ID != undefined)){
                $.ajax({
                    type: "GET",
                    url: "verify_death_details.php",
                    data: {
                        Registration_ID:Registration_ID,
                        Admision_ID:Admision_ID
                    },
                    cache: false,
                    success: function (response) {
                        if(response == 200){
                            save_round()
                        }else{
                            alert("You've Declared that the patient is DEAD! \n Please Fill Cause of Death to Complete this Action");
                            check_if_dead_reason(Discharge_Reason_ID,Registration_ID,Admision_ID);
                            exit();
                        }
                    }
                });
            }else{
                save_round();
            }
    }


    function save_round() {
        var finding = document.getElementById('Findings').value;
        var remarks_plan = document.getElementById('remarks_plan').value;
        var userText = remarks_plan.replace(/^\s+/, '').replace(/\s+$/, '');
        if (userText == "") {
            $("#remarks_plan").css("border", "2px solid red");
            $("#remarks_plan").focus();
            exit;
        } else {
            $("#remarks_plan").css("border", "");
        }
        var dischargedPatient = document.getElementById('dischargedPatient').value;
        
        if (finding != '') {
            if (dischargedPatient == '') {
                alert('Please select patient status');
                document.getElementById('dischargedPatient').focus();
                document.getElementById('dischargedPatient').style.borderColor = 'red';

                exit;
            }
            if (dischargedPatient == 'Discharge') {
                var finalDiagonosis = $(".final_diagnosis").val();

                if (finalDiagonosis == '') {
                    alert('Please add final diagnosis before discharging the partient.');
                    // $('#dischargedPatient').val('Continue');
                    exit;
                }
                var textdischargeDiagnosis =$("#textdischargeDiagnosis").val()
                if(textdischargeDiagnosis == ''){
                    alert("Please fill discharge diagnosis.");
                    $("#textdischargeDiagnosis").css('border', '2px solid red')
                    exit;
                }
                if (confirm("You are about to save this patient round and discharge.Click Ok to continue or cancel to return")) {
                    document.getElementById("clinicalnotes").submit();
                }
            } else {
                if (confirm("Do you want to add associated doctor?")) {
                    associated_doctor_dialogy()
                } else {
                    if (confirm("You are about to save this patient round.Click Ok to continue or cancel to return")) {
                        document.getElementById("clinicalnotes").submit();
                    }
                }
            }

        } else {
            alert("Please enter findings");
            document.getElementById('Findings').focus();
            document.getElementById('Findings').style.borderColor = 'red';

        }
    }

</script>

<script type="text/javascript">
    function save_round2() {
        var finding = document.getElementById('Findings').value;
        var dischargedPatient = document.getElementById('dischargedPatient').value;
        if (finding != '') {
            if (dischargedPatient == '') {
                alert('Please select patient status');
                document.getElementById('dischargedPatient').focus();
                document.getElementById('dischargedPatient').style.borderColor = 'red';

                exit;
            }
            if (dischargedPatient == 'Discharge') {
                var finalDiagonosis = $(".final_diagnosis").val();

                if (finalDiagonosis == '') {
                    alert('Please add final diagnosis before discharging the partient.');
                    // $('#dischargedPatient').val('Continue');
                    exit;
                }
                if (confirm("You are about to save this patient round and discharge.Click Ok to continue or cancel to return")) {
                    document.getElementById("clinicalnotes").submit();
                }
            } else {
                $("#Transfer_Patient_Dialog").dialog("open");
                /*if (confirm("You are about to save this patient round.Click Ok to continue or cancel to return")) {
                 document.getElementById("clinicalnotes").submit();
                 }*/
            }

        } else {
            alert("Please enter findings");
            document.getElementById('Findings').focus();
            document.getElementById('Findings').style.borderColor = 'red';

        }
    }
</script>
<script>
    function Show_Patient_File() {
        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $_GET['Registration_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>
<script>

    function showOthersDoctorsStaff() {
        $.ajax({
            type: 'GET',
            url: 'get_other_doc_staff_inpatient.php',
            data: 'consultation_ID=<?php echo $consultation_ID ?>&Registration_ID=<?php echo $Registration_ID ?>',
            cache: false,
            beforeSend: function (xhr) {
                $('#doctorProgressStatus').show();
            },
            success: function (html) {
                $('#doctorsInfo').html(html);
                $("#otherdoctorStaff").dialog("open");
            }, complete: function (jqXHR, textStatus) {
                $('#doctorProgressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#doctorProgressStatus').hide();
            }
        });

    }
</script>
<script>
    function showSummeryPatientFile() {
        $.ajax({
            type: 'GET',
            url: 'get_summery_pat_file.php',
            data: 'consultation_ID=<?php echo $consultation_ID ?>&Registration_ID=<?php echo $Registration_ID ?>',
            cache: false,
            beforeSend: function (xhr) {
                $('#summpatfileInfo').html('');
                $('#summeryProgressStatus').show();
            },
            success: function (html) {
                $('#summpatfileInfo').html(html);
                $("#summerypatientfile").dialog("open");
            }, complete: function (jqXHR, textStatus) {
                $('#summeryProgressStatus').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#summeryProgressStatus').hide();
            }
        });

    }
</script>
<script>
     function savedoctorinfos(instance) {
        var fieldName = $(instance).attr('name');
        var fieldValue = $(instance).val();

        var Round_ID = '<?php echo $Round_ID ?>';

        $.ajax({
            type: 'GET',
            url: 'inpatientclinicalautosave.php',
            data: 'fieldName=' + fieldName + '&fieldValue=' + fieldValue + '&Round_ID=' + Round_ID,
            cache: false,
            success: function(result) {
                console.log(result);
            }
        });

        console.log('Name=' + fieldName + '  value=' + fieldValue + '  Round_ID =' + Round_ID);
    }
</script>
<script>
    function consultChange(consultation_type) {
        document.getElementById("recentConsultaionTyp").value = consultation_type;

        var url2 = 'Consultation_Type=' + consultation_type + '&<?php
                        if ($Registration_ID != '') {
                            echo "Registration_ID=$Registration_ID&";
                        }
                        ?><?php
                        if ($Round_ID != 0) {
                            echo "Round_ID=" . $Round_ID . "&";
                        }if (isset($_GET['consultation_ID'])) {
                            echo "consultation_ID=" . $_GET['consultation_ID'] . "";
                        }
                        ?>';

        $.ajax({
            type: 'GET',
            url: 'inpatientdoctoritemselectajax.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#myConsult').html(html);
            }
        });

    }
</script>

<script type="text/javascript">
    function Perform_Procedure() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPerform = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPerform = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPerform.overrideMimeType('text/xml');
        }
        myObjectPerform.onreadystatechange = function () {
            dataP = myObjectPerform.responseText;
            if (myObjectPerform.readyState == 4) {
                window.open("proceduredocotorpatientinfo.php?Session=Inpatient&sectionpatnt=doctor_with_patnt&Registration_id=" + Registration_ID + "&consultation_ID=" + consultation_ID + "&ProcedureWorks=ProcedureWorksThisPage", "_parent");
            }
        }; //specify name of function that will handle server response........

        myObjectPerform.open('GET', 'Perform_Procedure_Inpatient.php?Section=Inpatient&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID, true);
        myObjectPerform.send();
    }
</script>

<script type="text/javascript">
    function Pequire_Spectacle(Registration_ID) {
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Status = '';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (document.getElementById("Optical_Option").checked) {
            Status = "Checked";
        } else {
            Status = "Not_checked";
        }
        if (window.XMLHttpRequest) {
            myObjectRequire = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRequire = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRequire.overrideMimeType('text/xml');
        }

        myObjectRequire.onreadystatechange = function () {
            data = myObjectRequire.responseText;
            if (myObjectRequire.readyState == 4) {

            }
        }; //specify name of function that will handle server response........

        myObjectRequire.open('GET', 'Require_Spectacle.php?Section=Inpatient&Registration_ID=' + Registration_ID + '&Status=' + Status + '&consultation_ID=' + consultation_ID + '&Employee_ID=' + Employee_ID, true);
        myObjectRequire.send();
    }
</script>
<script>
    function patientFileByFolio() {
//        $("#getFileByFolio").dialog('option', 'title', 'PATIENT FILE BY FOLIO FOR <?php echo $Patient_Name ?>);
        $("#getFileByFolio").dialog("open");
        var Start_Date = '000-00-00';
        var End_Date = '<?= $Today ?>';
        var Billing_Type = 'All';
        var Sponsor_ID = '<?= $Sponsor_ID ?>';
        var Patient_Number = '<?= $Registration_ID ?>';
        var Patient_Type = 'All';


        $.ajax({
            type: 'GET',
            url: 'Revenue_Collection_BY_Folio_Report_Filtered.php',
            data: {Start_Date: Start_Date, End_Date: End_Date, Billing_Type: Billing_Type, Sponsor_ID: Sponsor_ID, Patient_Number: Patient_Number, Patient_Type: Patient_Type},
            beforeSend: function (xhr) {
                $('#getFileByFolioprogress').show();
            },
            success: function (result) {
                $('#containerFileFolio').html(result);
            }, complete: function (jqXHR, textStatus) {
                $('#getFileByFolioprogress').hide();
            }
        });


        //alert(redid+' '+Patient_Payment_ID+' '+Patient_Payment_Item_List_ID);
    }
    $(document).ready(function () {
        $("#getFileByFolio").dialog({autoOpen: false, width: '80%', height: 450, title: 'PATIENT FILE BY FOLIO', modal: true});
    })
    $('#death_date_time').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#death_date_time').datetimepicker({value: '', step: 1});
</script> 
<?php
include("./includes/footer.php");
?>

<script>
    $('#dischargedPatient').change(function () {
        var status = $('#dischargedPatient').val();
        if (status === 'Discharge') {
            $('#dis_reasons').show();
            $('#Discharge_Reason_ID,.disch_req').css('border', '3px solid red');
            $('#remarksss,.disch_req').attr('required', 'required');
        } else {
            $('#Discharge_Reason_ID,.disch_req').css('border', '1px solid #ccc');
            $('#dis_reasons').hide();
            $('.disch_req').attr('required', false);
        }
    });
</script>
<script>
    function clear_other_input(disease_namedisease_code){
        $("#"+disease_namedisease_code).val("");
    }
   
    // function remove_added_death_disease(disease_death_ID,Registration_ID){
    //     $.ajax({
    //        type:'GET',
    //        url:'remove_death_reason_to_catch.php',
    //        data:{disease_death_ID:disease_death_ID,Registration_ID:Registration_ID},
    //        success:function (data){
    //            //console.log(data);
    //            $("#disease_suffred_table").html(data);
    //        },
    //        error:function (x,y,z){
    //            console.log(z);
    //        }
    //    }); 
    // }
    // function refresh_death_reason(){
    //    var Registration_ID=$("#Registration_ID").val();
    //    $.ajax({
    //        type:'GET',
    //        url:'refresh_death_reason.php',
    //        data:{Registration_ID:Registration_ID},
    //        success:function (data){
    //            //console.log(data);
    //            $("#disease_suffred_table").html(data);
    //        },
    //        error:function (x,y,z){
    //            console.log(z);
    //        }
    //    });  
    // }
    // function add_death_reason(disease_ID){
    //    var Registration_ID=$("#Registration_ID").val();
    //    $.ajax({
    //        type:'GET',
    //        url:'add_death_reason_to_catch.php',
    //        data:{disease_ID:disease_ID,Registration_ID:Registration_ID},
    //        success:function (data){
    //            console.log(data);
    //            $("#disease_suffred_table").html(data);
    //            search_disease_c_death();
    //        },
    //        error:function (x,y,z){
    //            console.log(z);
    //        }
    //    }); 
    // }
    // function open_add_reason_dialogy(){
        
    //     $("#add_death_course_dialogy").dialog({
    //                     title: 'ADD COURSE OF DEATH',
    //                     width: '50%',
    //                     height: 200,
    //                     modal: true,
    //                 }); 
    // }
    function close_this_dialog(){
        var Admision_ID=$("#Admision_ID").val();
        forceadmit(Admision_ID);
        $("#store_death_discharged_info").dialog("close");
    }
    // function check_if_dead_reason(rischarge_reason_id,Registration_ID,Admision_ID){
    //        //alert(rischarge_reason_id,Registration_ID,Admision_ID);
    //     var Discharge_Reason_ID=$(rischarge_reason_id).val();
    //     var death_date_time=$(death_date_time).val();
    //     $("#Docto_confirm_death_name").val(" ");
    //     $("#death_date_time").val("");
    //     $.ajax({
    //         type:'GET',
    //         url:'check_discharge_reason.php',
    //         data:{Discharge_Reason_ID:Discharge_Reason_ID},
    //         success:function(data){ 
    //             $("#Discharge_Reason_txt").val(data)
    //            if(data=="dead"){
    //                refresh_death_reason();
    //               $("#store_death_discharged_info").dialog({
    //                     title: 'FILL DEATH INFOMATION test',
    //                     width: '70%',
    //                     height: 550,
    //                     modal: true,
    //                 }); 
    //            }
    //         }
    //     });
    // }
    
  function forceadmit(Admision_ID){
  //alert('Select discharge reason');
    var death_date_time=$("#death_date_time").val();
    var Docto_confirm_death_name=$("#Doctor_confirm_death_name").val();
    var Discharge_Reason_txt=$("#Discharge_Reason_txt").val();
    var course_of_death=$("#course_of_death").val();
    var deceased_diseases=$("#deceased_diseases").val();
    var Admision_ID='<?= $Admision_ID ?>';
    var url="";
    var Registration_ID='<?= $Registration_ID ?>';
  var resId=$('#reason_'+Admision_ID);
  var Discharge_Reason=$("#Discharge_Reason_ID").val();

  if(Discharge_Reason_txt=="dead"){
      if(death_date_time==""|| Docto_confirm_death_name==""|| course_of_death==""){
          alert("Fill death infomation first");
          $("#store_death_discharged_info").dialog({
                        title: 'FILL DEATH INFOMATION ',
                        width: '70%',
                        height: 550,
                        modal: true,
                    });
                    exit;
      }
       url='doctor_discharge_release_force.php?admission_ID=' + Admision_ID + '&Discharge_Reason=' + Discharge_Reason+'&death_date_time='+death_date_time+'&Docto_confirm_death_name='+Docto_confirm_death_name+'&course_of_death='+course_of_death+'&deceased_diseases='+deceased_diseases+'&Registration_ID='+Registration_ID;
  }else{
        url='doctor_discharge_release_force.php?admission_ID=' + Admision_ID + '&Discharge_Reason=' + Discharge_Reason
    }
    console.log('Admision_ID=' + Admision_ID + '&Discharge_Reason=' + Discharge_Reason+'&death_date_time='+death_date_time+'&Docto_confirm_death_name='+Docto_confirm_death_name+'&course_of_death='+course_of_death+'&deceased_diseases='+deceased_diseases+'&Registration_ID='+Registration_ID);
      if(confirm("Are you sure you want to dischage this patient.The patient will not be visible to the doctor. Continue?")){
            if (window.XMLHttpRequest) {
                myobj = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myobj = new ActiveXObject('Micrsoft.XMLHTTP');
                myobj.overrideMimeType('text/xml');
            }

            myobj.onreadystatechange = function () {
              var  data = myobj.responseText;
                if (myobj.readyState == 4) {
                   if(data == '1'){
                       console.log(data);
                      alert("Processed successifully.Patient is in discharge state now!");
//                       filterPatient();
                   }else{
                     console.log(data);
                     alert('An error has occured try again or contact system administrator');
                   }
                   // document.getElementById('Patients_Fieldset_List').innerHTML = data6;
                }
            }; //specify name of function that will handle server response........

            myobj.open('GET', url , true);
            myobj.send();
        }
  }
  
</script>
<script type="text/javascript">
    $(document).ready(function () {
        
        $("#Docto_confirm_death_name").select2();
       // $("#deceased_diseases").select2();
        $("#course_of_death").select2();
        $("#emp_id").select2();
        $('#patientList').DataTable({
            "bJQueryUI": true

        });

        
    });
    
    // function search_disease_c_death(){
        
    //     var disease_code=$("#disease_code").val();
    //     var disease_name=$("#disease_name").val();
    //     var disease_version='<?= $configvalue_icd10_9 ?>';
    //     $.ajax({
    //        type:'GET',
    //        url:'search_disease_c_death.php',
    //        data:{disease_code:disease_code,disease_name:disease_name,disease_version:disease_version},
    //        success:function (data){
    //            console.log(data);
    //            $("#disease_suffred_table_selection").html(data);
    //        },
    //        error:function (x,y,z){
    //            console.log(z);
    //        }
    //     }); 
    // }
   
        $("#Docto_confirm_death_name").select2();
       // $("#deceased_diseases").select2();
        $("#course_of_death").select2();
        
        $('#patientList').DataTable({
            "bJQueryUI": true

        });

        
</script>
<script>
    function show_medication_history(Registration_ID){
        var Registration_ID= Registration_ID;
        $.get(
            'Previous_Medication_History.php', {
                Registration_ID: Registration_ID
            }, (data) => {
                $("#Previous_History").dialog({
                    autoOpen: false,
                    width: '80%',
                    Height: '80%',
                    title: 'eHMS 2.0: PREVIOUS CONSULTATION MEDICATION HISTORY',
                    modal: true
                });
                $("#Previous_History").html(data);
                $("#Previous_History").dialog("open");
            }
        );
    }
</script>
<script>
function show_investigation_history(Registration_ID){
    var Registration_ID= Registration_ID;
            $.get(
                'Previous_Medication_History.php', {
                    Registration_ID: Registration_ID
                }, (data) => {
                    $("#Previous_History").dialog({
                        autoOpen: false,
                        width: '80%',
                        Height: '60%',
                        title: 'eHMS 2.0: PREVIOUS INVESTIGATION HISTORY',
                        modal: true
                    });
                    $("#Previous_History").html(data);
                    $("#Previous_History").dialog("open");
                }
            );
}
</script>
<script>
function show_radiology_history(Registration_ID){
    var Registration_ID= Registration_ID;
            $.get(
                'Previous_Medication_History.php', {
                    Registration_ID: Registration_ID
                }, (data) => {
                    $("#Previous_History").dialog({
                        autoOpen: false,
                        width: '80%',
                        Height: '80%',
                        title: 'eHMS 2.0: PREVIOUS RADIOLOGY HISTORY',
                        modal: true
                    });
                    $("#Previous_History").html(data);
                    $("#Previous_History").dialog("open");
                }
            );
}
</script>

<div id='Nurse_previous_Summary'></div>
<script>
function preview_nurse_documents(Registration_ID,consultation_ID,Admission_ID){
    var consultation_ID= consultation_ID;
    var Registration_ID= Registration_ID;
    var Admission_ID='<?= $Admision_ID ?>';
    $.get(
        'popup_nursing_menu.php', {
            Registration_ID: Registration_ID,consultation_ID: consultation_ID,Admission_ID:Admission_ID
        }, (data) => {
            $("#Nurse_previous_Summary").dialog({
                autoOpen: false,
                width: '80%',
                Height: '60%',
                title: 'eHMS 2.0: NURSING CARE SUMMARY',
                modal: true
            });
            $("#Nurse_previous_Summary").html(data);
            $("#Nurse_previous_Summary").dialog("open");
        }
    );
}
</script>

<!-- BIOPSY OPD REQUEST AEREA -->
<script type="text/javascript">
    function Biopsy_request_from(){
        var Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Add_Postoperative_Area2').innerHTML = dataPost;
                $("#Biopsy_Form").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'biopsy_requesting_form.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Registration_ID='+Registration_ID+'&consultation_ID='+consultation_ID, true);
        myObjectPost.send();
    }
    function Open_Labodatory_Dialogy2(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Section = '<?php echo $Section; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectLab_Details = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectLab_Details = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectLab_Details.overrideMimeType('text/xml');
        }

        myObjectLab_Details.onreadystatechange = function () {
            dataAddLab = myObjectLab_Details.responseText;
            if (myObjectLab_Details.readyState == 4) {
                document.getElementById('Investigation_Area2').innerHTML = dataAddLab;
                $("#Investigation_Details2").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectLab_Details.open('GET', 'Postoperative_Add_Laboratory_Items_biopsy.php?consultation_ID='+consultation_ID+'&Section='+Section+'&Registration_ID='+Registration_ID, true);
        myObjectLab_Details.send();
    }
    function Get_Item_Name_Laboratory(Item_Name,Item_ID){
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        document.getElementById("Quantity").value = 1;
        if(Transaction_Type == 'Credit'){
            if (window.XMLHttpRequest) {
                My_Object_Verify_Item = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Verify_Item.overrideMimeType('text/xml');
            }

            My_Object_Verify_Item.onreadystatechange = function () {
                dataVerify = My_Object_Verify_Item.responseText;
                if (My_Object_Verify_Item.readyState == 4) {
                    var feedback = dataVerify;
                    if(feedback == 'yes'){
                        Get_Details_Laboratory(Item_Name,Item_ID);
                    }else{
                        document.getElementById("Price").value = 0;
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Price").value = '';
                        $("#Non_Supported_Item").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID, true);
            My_Object_Verify_Item.send();
        }else{
            Get_Details_Laboratory(Item_Name,Item_ID);
        }
    }
    function Get_Item_Price(Item_ID, Guarantor_Name) {

// alert(Guarantor_Name)

        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Billing_Type = '';
        if(Transaction_Type == 'Credit'){
            Billing_Type = 'Outpatient Credit';
        }else{
            Billing_Type = 'Outpatient Cash';
        }

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObject.onreadystatechange = function () {
            data = myObject.responseText;

            if (myObject.readyState == 4) {
                document.getElementById('Price').value = data;
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type+'&Sponsor_ID='+ "<?=$Sponsor_ID ?>", true);
        myObject.send();
    }
    function Get_Details_Laboratory(Item_Name, Item_ID) {
        document.getElementById('Quantity').value = 1;
        document.getElementById('Comment').value = '';
        var Temp = '';
        if (window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
    }
    function Get_Selected_Item_Laboratory2(Check_in_type="Laboratory"){
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Section = '<?php echo strtolower($Section); ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Round_ID = '<?= $Round_ID ?>';
        var Billing_Type = '';

        if(Transaction_Type == 'Credit'){
            Billing_Type = 'Inpatient Credit';
        }else if(Transaction_Type == 'Cash'){
            Billing_Type = 'Inpatient Cash';
        }

        var Item_ID = document.getElementById("Item_ID").value;
        var Item_Name = document.getElementById("Item_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Comment = document.getElementById("Comment").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

        var Price = document.getElementById("Price").value;
        if (parseFloat(Price) > 0) {

        } else {
            if( Item_ID != null && Item_ID != ''){
                alert('Selected Item missing price.');
                exit;
            }
        }

        if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Sub_Department_ID != null && Sub_Department_ID != '') {
            if (window.XMLHttpRequest) {
                myObjectLabs = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectLabs = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectLabs.overrideMimeType('text/xml');
            }
            myObjectLabs.onreadystatechange = function () {
                dataLab = myObjectLabs.responseText;
                if (myObjectLabs.readyState == 4) {
                    document.getElementById('Selected_Investigation_Area').innerHTML = dataLab;
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Price").value = '';
                    document.getElementById("Comment").value = '';
                    document.getElementById("Search_Value").focus();
                    Display_Pharmaceutical_And_Lab_Test_Given2();
                }
            }; //specify name of function that will handle server response........
            
            myObjectLabs.open('GET', 'Post_Operative_Add_Laboratory_biopsy.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Comment=' + Comment+'&Transaction_Type='+Transaction_Type+'&consultation_ID='+consultation_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Check_in_type='+Check_in_type+'&Round_ID='+Round_ID, true);
            myObjectLabs.send();

        } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
            alertMessage();
        } else {
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").value = 1;
            }

            if(Sub_Department_ID == '' || Sub_Department_ID == null){
                document.getElementById("Sub_Department_ID").focus();
                document.getElementById("Sub_Department_ID").style = 'border: 3px solid red';
            }else{
                document.getElementById("Sub_Department_ID").style = 'border: 2px solid black';
            }
        }
    }
    function Display_Pharmaceutical_And_Lab_Test_Given2(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            myObject_Pha_Lab = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Pha_Lab = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Pha_Lab.overrideMimeType('text/xml');
        }

        myObject_Pha_Lab.onreadystatechange = function () {
            dataPL = myObject_Pha_Lab.responseText;
            if (myObject_Pha_Lab.readyState == 4) {
                document.getElementById('Postoperative').value = dataPL;
            }
        }; //specify name of function that will handle server response........

        myObject_Pha_Lab.open('GET', 'Display_Pharmaceutical_And_Lab_Test_Given.php?consultation_ID='+consultation_ID, true);
        myObject_Pha_Lab.send();
    }
    function update_save_biopsy(){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var autospy = $("#autospy").val();
        // var Priority = $("#Priority").val();
        var Priority = $("input[name = 'Priority']:checked").val();

        var birth_region = $("#birth_region").val();
        var birth_district = $("#birth_district").val();
        var birth_village = $("#birth_village").val();
        var birth_year = $("#birth_year").val();
        var resident_year = $("#resident_year").val();
        var Employee_ID = '<?= $Employee_ID ?>';
        var Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        // if(Priority == ''){
        //     var Priority = $("#Priority2").val();
        // }
        // alert(Priority);
        // exit();
                $.ajax({
                    type: 'GET',
                    url: 'update_save_biopsy.php',
                    data: {consultation_ID:consultation_ID,Registration_ID:Registration_ID,Priority:Priority,Employee_ID:Employee_ID,birth_region:birth_region,birth_district:birth_district,birth_village:birth_village,birth_year:birth_year,resident_year:resident_year,autospy:autospy,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
                    success: function (responce){ 
                        // Biopsy_request_from();
                    }
                });
        }
        function save_biopsy_info(Biopsy_ID){
            if (Biopsy_ID != null ) {
                var Priority = $("input[name = 'Priority']:checked").val();
                    if(confirm("Are you sure you want To Submit this Histological Request Form?")){
                        $.ajax({
                            type: 'GET',
                            url: 'save_biopsy_info.php',
                            data: {Biopsy_ID:Biopsy_ID,Priority:Priority},
                            success: function (responce){
                                alert(responce);
                                $("#Biopsy_Form").dialog("close");
                            }
                        });
                    }
                
            }
        }
        function Remove_Investigation_biopsy(Payment_Item_Cache_List_ID){
        var consultation_ID = '<?php echo $consultation_ID; ?>';
            if(confirm("Are you sure You want to Remove this Ordered Biopsy?")){
                $.ajax({
                    type: 'GET',
                    url: 'Post_Operative_Remove_Laboratory_biopsy.php',
                    data: {consultation_ID:consultation_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
                    success: function (responce){                  
                        Open_Labodatory_Dialogy2();
                    }
                });
            }
        }
        function getItemsListFiltered_Labolatory_biopsy(){
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Quantity").value = '';
        document.getElementById("Price").value = '';
        var Search_Value = document.getElementById("Search_Value").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if(window.XMLHttpRequest) {
            myObjectgetLab = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectgetLab = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectgetLab.overrideMimeType('text/xml');
        }

        myObjectgetLab.onreadystatechange = function (){
            data1350 = myObjectgetLab.responseText;
            if (myObjectgetLab.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data1350;
            }
        }; //specify name of function that will handle server response........
        myObjectgetLab.open('GET','Post_Operative_Get_List_Of_Laboratory_Filtered_Items_biopsy.php?Item_Category_ID='+Item_Category_ID+'&Search_Value='+Search_Value+'&Guarantor_Name='+Guarantor_Name+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectgetLab.send();
    }
</script>


<div id='Preview_Details'></div>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
 
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
 






