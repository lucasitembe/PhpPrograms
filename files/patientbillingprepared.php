<?php
include("./includes/header.php");
include("./includes/connection.php");
require_once  './includes/ehms.function.inc.php';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//$show_make_payment="";
//$sql_select_make_payment_configuration="SELECT 	configname FROM tbl_config WHERE configvalue='show' AND configname='showMakePaymentButton'";
//$sql_select_make_payment_configuration_result=mysqli_query($conn,$sql_select_make_payment_configuration) or die(mysqli_error($conn));
//if(mysqli_num_rows($sql_select_make_payment_configuration_result)>0){
//    //show button
//}else{
// $show_make_payment="style='display:none'";   
//}
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
?>

<style>
    table,tr,td{
        
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>


<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>


<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->








<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
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
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
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
}
?>






<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        //getPrice();
        var ItemListType = document.getElementById('Type').value;
        getItemListType(ItemListType);
        document.getElementById('Price').value = '';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemList.php?Item_Category_Name=' + Item_Category_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data1 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data1;
    }
</script>

<script type='text/javascript'>
    function getItemListType() {
        var Item_Category_Name = document.getElementById("Item_Category").value;


        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        alert(Item_Category_Name);
        //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Approval').disabled = 'disabled';
                document.getElementById('Approval_Comment').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Item_Category_Name=' + Item_Category_Name, true);
        myObject.send();
    }
</script>
<!-- end of filtering-->




<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        if (document.getElementById('direction').value == 'Direct To Doctor Via Nurse Station' || document.getElementById('direction').value == 'Direct To Doctor') {
            document.getElementById('Doctors_List').style.visibility = "";
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?Type_Of_Check_In=' + Type_Of_Check_In + '&direction=doctor', true);
            mm.send();
            $("#select_clinic").show();
        } else {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?direction=clinic', true);
            mm.send();
            $("#select_clinic").css("display","none");
            document.getElementById('Doctors_List').style.visibility = "hidden";
        }
    }
    function AJAXP3() {
        var data3 = mm.responseText;
        document.getElementById('Consultant').innerHTML = data3;
    }
</script>
<!-- end of selection-->



<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->
<?php
if (!isset($_GET['NR']) && !isset($_GET['CP'])) {
    //select the current Patient_Payment_ID to use as a foreign key
    $sql_Select_Current_Patient = mysqli_query($conn,"select pp.Patient_Payment_ID,pp.Claim_Form_Number, ppl.Patient_Direction,
					pp.Folio_Number, pp.Payment_Date_And_Time, ppl.Check_In_Type, ppl.Consultant,
					    pp.Billing_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
    $no = mysqli_num_rows($sql_Select_Current_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($sql_Select_Current_Patient)) {
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            //$Check_In_Type = $row['Check_In_Type'];
            $Folio_Number = $row['Folio_Number'];
            $Billing_Type = $row['Billing_Type'];
            $Patient_Direction = $row['Patient_Direction'];
            $Consultant = $row['Consultant'];
            $Claim_Form_Number = $row['Claim_Form_Number'];
        }

        //Select Last Check in to display
        //select the current Patient_Payment_ID to use as a foreign key
        $sql_Select_Current_Check_In = mysqli_query($conn,"select ppl.Check_In_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
				       where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					registration_id = '$Registration_ID' order by ppl.Patient_Payment_Item_List_ID desc limit 1");
        $no = mysqli_num_rows($sql_Select_Current_Check_In);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($sql_Select_Current_Check_In)) {
                $Check_In_Type = $row['Check_In_Type'];
            }
        }
    } else {
        $Patient_Payment_ID = '';
        $Payment_Date_And_Time = '';
        $Check_In_Type = '';
        $Billing_Type = '';
        $Folio_Number = '';
        $Patient_Direction = '';
        $Consultant = '';
    }
} elseif (isset($_GET['CP'])) {
    //select the current folio number only to use
    $sql_Select_Current_Patient = mysqli_query($conn,"select pp.Folio_Number from tbl_patient_payments pp
						  where registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
    $no = mysqli_num_rows($sql_Select_Current_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($sql_Select_Current_Patient)) {
            $Folio_Number = $row['Folio_Number'];
        }
        $Patient_Payment_ID = '';
        $Payment_Date_And_Time = '';
        $Check_In_Type = '';
        $Billing_Type = '';
        $Patient_Direction = '';
        $Consultant = '';
    } else {
        $Folio_Number = '';
        $Patient_Payment_ID = '';
        $Payment_Date_And_Time = '';
        $Check_In_Type = '';
        $Billing_Type = '';
        $Patient_Direction = '';
        $Consultant = '';
    }
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    $Check_In_Type = '';
    $Billing_Type = '';
    $Folio_Number = '';
    $Patient_Direction = '';
    $Consultant = '';
}
?>



<?php
if (isset($_POST['submittedPatientBillingForm'])) {

    if (isset($_GET['NR'])) {
        $Receipt_Date = mysqli_real_escape_string($conn,$_POST['Receipt_Date_Hidden']);
        $Claim_Form_Number = mysqli_real_escape_string($conn,$_POST['Claim_Form_Number']);
        $Billing_Type = mysqli_real_escape_string($conn,$_POST['Billing_Type']);
        $Type_Of_Check_In = mysqli_real_escape_string($conn,$_POST['Type_Of_Check_In']);
        $Item_Category = mysqli_real_escape_string($conn,$_POST['Item_Category']);
        $Item_Name = mysqli_real_escape_string($conn,$_POST['Item_Name']);
        $Discount = mysqli_real_escape_string($conn,$_POST['Discount']);
        //$Price = mysqli_real_escape_string($conn,$_POST['Price']);
        $Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
        $direction = mysqli_real_escape_string($conn,$_POST['direction']);
        $Consultant = mysqli_real_escape_string($conn,$_POST['Consultant']);
    } else {
        $Type_Of_Check_In = mysqli_real_escape_string($conn,$_POST['Type_Of_Check_In']);
        $Item_Category = mysqli_real_escape_string($conn,$_POST['Item_Category']);
        $Item_Name = mysqli_real_escape_string($conn,$_POST['Item_Name']);
        $Discount = mysqli_real_escape_string($conn,$_POST['Discount']);
        //$Price = mysqli_real_escape_string($conn,$_POST['Price']);
        $Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
        $direction = mysqli_real_escape_string($conn,$_POST['direction']);
        $Consultant = mysqli_real_escape_string($conn,$_POST['Consultant']);
    }

    /* selecting the price from the database
      if($Billing_Type=='Outpatient Credit'){
      if(strtolower($Guarantor_Name)=='nhif'){
      $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
      where i.Product_Name = '$Product_Name' ";
      }else{
      $Select_Price = "select Selling_Price_Credit as price from tbl_items i
      where i.Product_Name = '$Product_Name' ";
      }
      }elseif($Billing_Type=='Outpatient Cash'){
      $Select_Price = "select Selling_Price_Cash as price from tbl_items i
      where i.Product_Name = '$Product_Name' ";
      }

      $result = @mysqli_query($conn,$Select_Price);
      $row = @mysqli_fetch_assoc($result);
      echo $row['price'];
     */





    if (isset($_GET['NR']) && !isset($_GET['CP'])) {
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        //get the current date		
        $Today_Date = mysqli_query($conn,"select now() as today");
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date;
        }

        //GET BRANCH ID
        $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

        //check if the current date and the last folio number are in the same month and year
        //select the current folio number to check the month and year
        /* $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
          from tbl_folio where
          sponsor_id = '$Sponsor_ID' and
          order by folio_id desc limit 1"); */
        $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
						from tbl_folio where sponsor_id = '$Sponsor_ID' and
						    Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
        $no = mysqli_num_rows($current_folio);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($current_folio)) {
                $Folio_Number = $row['Folio_Number'];
                $Folio_date = $row['Folio_date'];
            }
        } else {
            $Folio_Number = 1;
            $Folio_date = 0;
        }

        //check the current month and year (Remove the day part of the date)
        $Current_Month_and_year = substr($Today, 0, 7);

        //check month and year of the last folio number (Remove the day part of the date)
        $Last_Folio_Month_and_year = substr($Folio_date, 0, 7);

        //compare month and year    
        if ($Last_Folio_Month_and_year == $Current_Month_and_year) {
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
				values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
            $Folio_Number = $Folio_Number + 1;
        } else {
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
				values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
            $Folio_Number = 1;
        }

        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
    }


    if (isset($_GET['NR'])) {
        //generate new receipt
        //---get supervisor id 
        if (isset($_SESSION['supervisor'])) {
            if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
                } else {
                    $Supervisor_ID = '';
                }
            } else {
                $Supervisor_ID = '';
            }
        } else {
            $Supervisor_ID = '';
        }
        //end of fetching supervisor id
        //get branch ID
        if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Branch_ID'])) {
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            } else {
                $Branch_ID = '';
            }
        } else {
            $Branch_ID = '';
        }
        //end of fetching branch ID

        $Insert_Data_To_tbl_patient_payments = "insert into tbl_patient_payments(
			    registration_id,supervisor_id,employee_id,
				payment_date_and_time,folio_number,claim_form_number,
				    sponsor_id,sponsor_name,billing_type,receipt_date,branch_id)
			values(
			    '$Registration_ID','$Supervisor_ID','$Employee_ID',
				(select now()),'$Folio_Number','$Claim_Form_Number',
				    '$Sponsor_ID','$Guarantor_Name','$Billing_Type',(select now()),'$Branch_ID')";

        if (!mysqli_query($conn,$Insert_Data_To_tbl_patient_payments)) {
            echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './patientbilling.php?Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
        }


        //Getting item price from the database
        if ($Billing_Type == 'Outpatient Credit') {
            if (strtolower($Guarantor_Name) == 'nhif') {
                $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
            } else {
                $Select_Price = "select Selling_Price_Credit as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
            }
        } elseif ($Billing_Type == 'Outpatient Cash') {
            $Select_Price = "select Selling_Price_Cash as price from tbl_items i
					    where i.Item_ID = '$Item_Name' ";
        }

        $result = @mysqli_query($conn,$Select_Price);
        $row = @mysqli_fetch_assoc($result);

        $Price = $row['price'];

        //select the current Patient_Payment_ID to use as a foreign key (Also will be used as a new receipt number)
        $sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1");

        while ($row = mysqli_fetch_array($sql_Select_date)) {
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
        }

        //understand the patient direction to allow the query either to select clinic id of doctor id
        if (strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station') {
            $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
			    check_In_type,category,item_id,discount,price,quantity,
				    patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
			    values(
				'$Type_Of_Check_In','$Item_Category','$Item_Name',
				    '$Discount','$Price','$Quantity',
					'$direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant'),
					    '$Patient_Payment_ID',(select now()))";
        } elseif (strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station') {
            $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
			    check_In_type,category,item_id,discount,price,quantity,
				    patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
			    values(
				'$Type_Of_Check_In','$Item_Category','$Item_Name',
				    '$Discount','$Price','$Quantity',
					'$direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant'),
					    '$Patient_Payment_ID',(select now()))";
        }
        if (!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)) {
            echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './patientbillingreception.php?Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
        } else {
            //get branch id
            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");
            echo "<script type='text/javascript'>
			    alert('TRANSACTION ADDED SUCCESSFUL');
			    document.location = './patientbillingreception.php?Patient_Payment_ID=" . $Patient_Payment_ID . "&Registration_ID=" . $Registration_ID . "&UpdatePayments=UpdatePaymentsThisPage';
			    </script>";
        }
    } else {

        //Getting item price from the database
        if ($Billing_Type == 'Outpatient Credit') {
            if (strtolower($Guarantor_Name) == 'nhif') {
                $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
            } else {
                $Select_Price = "select Selling_Price_Credit as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
            }
        } elseif ($Billing_Type == 'Outpatient Cash') {
            $Select_Price = "select Selling_Price_Cash as price from tbl_items i
					    where i.Item_ID = '$Item_Name' ";
        }

        $result = @mysqli_query($conn,$Select_Price);
        $row = @mysqli_fetch_assoc($result);

        $Price = $row['price'];



        //select the current Patient_Payment_ID to use as a foreign key (Also this is working as receipt number)
        $sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Billing_Type, Payment_Date_And_Time from tbl_patient_payments where
						registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1");

        while ($row = mysqli_fetch_array($sql_Select_date)) {
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            $Billing_Type = $row['Billing_Type'];
        }

        //understand the patient direction to allow the query either to select clinic id of doctor id
        if (strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station') {
            $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
			check_In_type,category,item_id,
			    discount,price,quantity,
				patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
			values(
			    '$Type_Of_Check_In','$Item_Category','$Item_Name',
				'$Discount','$Price','$Quantity',
				    '$direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant'),
					'$Patient_Payment_ID',(select now()))";
        } elseif (strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station') {
            $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
			check_In_type,category,item_id,
			    discount,price,quantity,
				patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
			values(
			    '$Type_Of_Check_In','$Item_Category','$Item_Name',
				'$Discount','$Price','$Quantity',
				    '$direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant'),
					'$Patient_Payment_ID',(select now()))";
        }
        if (!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)) {
            /* $Foreign_Key_Error = mysql_errno()."Yes";
              if($Foreign_Key_Error == '1452Yes'){
              echo '<span style="color: red;"><b>TRANSACTION FAIL.</b></span>';
              }
              die(mysqli_error($conn)); */
            echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './patientbillingreception.php?Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
        } else {
            mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID'");
            echo "<script type='text/javascript'>
			    alert('TRANSACTION ADDED SUCCESSFUL');
			    document.location = './patientbillingreception.php?Patient_Payment_ID=" . $Patient_Payment_ID . "&Registration_ID=" . $Registration_ID . "&UpdatePayments=UpdatePaymentsThisPage';
			    </script>";
        }
    }
}
?>
<!--end of process-->






<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->
    <fieldset>  
        <legend align=right><b>REVENUE CENTER</b></legend>
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right; width: 10%;'>Billing Type</td>

                                <td style='width: 15%;'>
                                    <select name='Billing_Type' id='Billing_Type' required='required'>
                                        <option selected='selected'>Outpatient Cash</option> 
                                    </select>
                                </td>
                                <td style='text-align: right; width: 10%'>Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td style='text-align: right; width: 7%;'>Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right;'>Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' id='Receipt_Number' readonly='readonly'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Type Of Check In</td>
                                <td>  
                                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='clearFocus(this)' onclick='clearFocus(this)'>

<?php /* if(!isset($_GET['NR'])){ ?>
  <option selected='selected'><?php echo $Check_In_Type; ?></option>
  <?php } else{ ?>
  <option selected='selected'><?php $Check_In_Type; ?></option>
  <?php } */ ?>

                                        <!-- <option>Cecap</option>
                                        <option>Dental</option>
                                        <option>Dialysis</option> -->
                                        <option>Doctor Room</option>
                                        <!-- <option>Dressing</option> -->
                                        <!-- <option>Ear</option> -->
                                        <!--<option>Laboratory</option>-->
                                        <!-- <option>Matenity</option>
                                        <option>Optical</option> --> 
                                        <!--<option>Pharmacy</option>-->
                                        <!-- <option>Physiotherapy</option> -->
                                        <!--<option>Radiology</option>-->
                                        <!-- <option>Theater</option> -->
                                    </select>
                                </td>

                                <td style='text-align: right;'>Occupation</td>
                                <td>
                                    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                                </td>
                                <td style='text-align: right; width: 11%'>Gender</td>
                                <td width='12%'><input type='text' name='Gender' readonly='readonly' id='Gender' value='<?php echo $Gender; ?>'></td>
                                <td style='text-align: right;'>Receipt Date & Time</td>
                                <td width='12%'><input type='text' name='Receipt_Date_Time' readonly='readonly' id='Receipt_Date_Time' value='<?php echo ''; ?>'></td>
                            </tr>
                            <tr>

                                <td style='text-align: right;'>Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' onclick='clearFocus(this);
                                            getDoctor()' required='required' onchange='clearFocus(this)'>
<?php if (!isset($_GET['NR']) && !isset($_GET['CP'])) { ?>
                                            <option selected='selected'><?php echo $Patient_Direction; ?></option>
<?php } else { ?>
                                            <option selected='selected'></option>
<?php } ?>
                                        <option>Direct To Doctor</option>
                                        <option>Direct To Doctor Via Nurse Station</option>
                                        <option>Direct To Clinic</option>
                                        <option>Direct To Clinic Via Nurse Station</option>
                                    </select>
                                </td>


                                <td style='text-align: right;'>Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right;'>Registered Date</td>
                                <td><input type='text' name='Registered_Date' id='Registered_Date' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                                <td style='text-align: right;'>Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly' value='<?php echo ''; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Consultant</td>
                                <td>
                                    <select name='Consultant' id='Consultant' value='<?php echo $Guarantor_Name; ?>' required='required' onchange='clearFocus(this)' onclick='clearFocus(this)'>

<?php if (!isset($_GET['NR']) && !isset($_GET['CP'])) { ?>
                                            <option selected='selected'><?php echo $Consultant; ?></option>
<?php } else { ?>
                                            <option selected='selected'></option>
<?php } ?>

<?php
$Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
$result = mysqli_query($conn,$Select_Consultant);
?> 
<?php
while ($row = mysqli_fetch_array($result)) {
    ?>
                                            <option><?php echo $row['Clinic_Name']; ?></option>
    <?php
}

$Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'";
$result = mysqli_query($conn,$Select_Doctors);
?> 
                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <option><?php echo $row['Employee_Name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <input type="button" name="Doctors_Queue" id="Doctors_Queue" value="Dq" onclick="open_Dialog()"/>
                                    <input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" onclick="Get_Doctor()" style="Visibility: hidden;">
                                </td>

                                <td style='text-align: right;'>Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right;'>Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>

                                <td style='text-align: right;'>Supervised By</td>

                                        <?php
                                        if (isset($_SESSION['supervisor'])) {
                                            if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                                                if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                                                    $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                                                } else {
                                                    $Supervisor = "Unknown Supervisor";
                                                }
                                            } else {
                                                $Supervisor = "Unknown Supervisor";
                                            }
                                        } else {
                                            $Supervisor = "Unknown Supervisor";
                                        }
                                        ?>


                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>	

                            </tr> 
                            
                            <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Clinic
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);' onclick='clearFocus(this)' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
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
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
    </fieldset>
    <div id="List_OF_Doctors">
        <center>
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
                            <table width="100%">
<?php
$counter = 0;
$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
$doctors_num = mysqli_num_rows($get_doctors);
if ($doctors_num > 0) {
    while ($data = mysqli_fetch_array($get_doctors)) {
        ?>
                                        <tr>
                                            <td style='text-align: right;'>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
                                            </td>
                                            <td>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                            </td>
                                        </tr>
        <?php
    }
}
?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </center>
    </div>

    <script type="text/javascript">
        function Get_Doctor() {
            var Direction = document.getElementById("direction").value;
            if (Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')) {
                $("#List_OF_Doctors").dialog("open");
            }
        }
    </script>

    <script type="text/javascript">
        function Search_Doctors() {
            var Doctror_Name = document.getElementById("Doc_Name").value;
            if (window.XMLHttpRequest) {
                myObject_Search_Doctor = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject_Search_Doctor.overrideMimeType('text/xml');
            }

            myObject_Search_Doctor.onreadystatechange = function () {
                data = myObject_Search_Doctor.responseText;
                if (myObject_Search_Doctor.readyState == 4) {
                    document.getElementById('Doctors_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject_Search_Doctor.open('GET', 'Search_Doctors.php?Doctror_Name=' + Doctror_Name, true);
            myObject_Search_Doctor.send();
        }
    </script>

    <script type="text/javascript">
        function Get_Selected_Doctor(Doctror_Name) {
            document.getElementById("Consultant").value = Doctror_Name;
            $("#List_OF_Doctors").dialog("close");
        }
    </script>
    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td width=25%>

                        <script type='text/javascript'>
                            function getItemsList(Item_Category_ID) {
                                document.getElementById("Search_Value").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                var Sponsor_ID='<?= $Sponsor_ID ?>';
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID+'&Sponsor_ID='+Sponsor_ID, true);
                                myObject.send();
                            }

                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                var Item_Category_ID = document.getElementById("Item_Category_ID").value;
                                if (Item_Category_ID == '' || Item_Category_ID == null) {
                                    Item_Category_ID = 'All';
                                }
                                var Sponsor_ID='<?= $Sponsor_ID ?>';
                                //alert(Sponsor_ID)
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name+'&Sponsor_ID='+Sponsor_ID, true);
                                myObject.send();
                            }

                            function Get_Item_Name(Item_Name, Item_ID) {
                                document.getElementById("Item_Name").value = Item_Name;
                                document.getElementById("Item_ID").value = Item_ID;
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Quantity").focus();
                            }

                            function Get_Item_Price(Item_ID,Guarantor_Name,Sponsor_ID) {
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Billing_Type = document.getElementById("Billing_Type").value;
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
                                        //alert(data);
                                    }
                                }; //specify name of function that will handle server response........

                                myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type+'&Sponsor_ID='+Sponsor_ID, true);
                                myObject.send();
                            }

                            function Make_Payment() {
                                var Item_Name = document.getElementById("Item_Name").value;
                                var Discount = document.getElementById("Discount").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var direction = document.getElementById("direction").value;
                                var Consultant = document.getElementById("Consultant").value;
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Quantity = document.getElementById("Quantity").value;
                                var Registration_ID = <?php echo $Registration_ID; ?>;

                                //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 

                                //alert(Item_Name+", "+Discount+", "+Type_Of_Check_In+", "+direction+", "+Consultant+", "+Item_ID+", "+Quantity+", "+Registration_ID);
                                if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {


                                    if (window.XMLHttpRequest) {
                                        myObject2 = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObject2.overrideMimeType('text/xml');
                                    }
                                    //alert("eHMS");

                                    myObject.onreadystatechange = function () {
                                        data = myObject.responseText;

                                        if (myObject2.readyState == 4) {
                                            document.getElementById('Price').value = data;
                                            //alert(data);
                                        }
                                    }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Perform_Reception_Transaction.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount, true);
                                    myObject2.send();

                                } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {
                                    alertMessage();
                                }
                            }

                            function alertMessage() {
                                alert("Please Select Item First");
                            }
                        </script>
                        <table width = 100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        <option selected='selected'></option>
<?php
$data = mysqli_query($conn,"select * from tbl_item_category");
while ($row = mysqli_fetch_array($data)) {
    echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
}
?>   
                                    </select>
                                </td>
                            </tr>
                            <tr><td><input type='text' id='Search_Value' name='Search_Value' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'></td></tr>

                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset'>
                                        <table width=100%>
<?php
$result = mysqli_query($conn,"SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID WHERE Status='Available' AND ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' order by Product_Name limit 200");
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>
							<td style='color:black; border:2px solid #ccc;text-align: left;'>";
    ?>							    
                                                <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                                        Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>','<?php echo $Sponsor_ID; ?>')">
                                            <?php
                                            echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                                        }
                                        ?> 
                                        </table>
                                    </fieldset>		
                                </td>
                            </tr>
                        </table> 
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;' colspan=2>



                                    <!-- ITEM DESCRIPTION START HERE -->


                            <center>
                                <table width=100%>
                                    <tr>
                                        <td><b>Item Name</b></td>
                                        <td><b>Discount</b></td>
                                        <td><b>Price</b></td>
                                        <td><b>Balance</b></td>
                                        <td><b>Qty</b></td>
                                        <td><b>Amount</b></td>
                                        <td></td>
                                    </tr>
                                    <tr>  
                                        <td width=35%> 
                                            <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                            <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                        </td> 
                                        <td>
                                            <input type='text' name='Discount' id='Discount' placeholder='Discount' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()' value=0>
                                        </td>
                                        <td>
                                            <input type='text' name='Price' id='Price' placeholder='Price' readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Balance' id='Balance' placeholder='Balance' value=1 readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Quantity' id='Quantity' required='required' autocomplete='off' placeholder='Quantity' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        </td>
                                        <td>
                                            <input type='text' name='Amount' id='Amount' placeholder='Amount' readonly='readonly'>
                                        </td>
                                        <td style='text-align: center;'>
                                            <script type='text/javascript'>
                                                function Make_Payment_Warning() {
                                                    alert("Please choose one of the options below");
                                                }
                                                function Select_Patient_First() {
                                                    alert("Please select patient first");
                                                }
                                            </script>
                                            <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='add_Item()'>
<?php
/* if(isset($_SESSION['userinfo']['Employee_ID'])){
  //get employee id
  $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  if($Registration_ID != ''){
  //check if there is any pending order but different from this patient
  //select previous record
  $Previous_Record =
  mysqli_query($conn,"select * from tbl_patient_payments_cache where
  Transaction_status = 'submitted' and
  Employee_ID = '$Employee_ID' and
  Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
  $num_rows = mysqli_num_rows($Previous_Record);
  if($num_rows > 0){
  echo "<input type='button' name='button' id='button' value='ADD' class='art-button-green' onclick='Make_Payment_Warning()'>";
  }else{
  echo "<input type='submit' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Make_Payment()'>";
  }
  }else{
  echo "<input type='button' name='button' id='button' value='ADD' class='art-button-green' onclick='Select_Patient_First()'>";
  }

  } */
?>
                                            <!--<input type='submit' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Make_Payment()'> -->
                                        </td>
                                    </tr>
                                </table>   
                            </center>
                            <!-- ITEM DESCRIPTION ENDS HERE -->
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                                            <?php
                                            //get Check_In_ID from url
                                            if (isset($_GET['Check_In_ID'])) {
                                                $Check_In_ID = $_GET['Check_In_ID'];
                                            } else {
                                                $Check_In_ID = 0;
                                            }
                                            //echo "<iframe src='Patient_Billing_Iframe_Prepared.php?Registration_ID=".$Registration_ID."&Check_In_ID=".$Check_In_ID."' width='100%' height=200px></iframe>";
                                            ?>

                        <fieldset style='overflow-y: scroll; height: 225px;' id='Picked_Items_Fieldset'>
<?php
$total = 0;
echo '<center><table width =100%>';
echo "<tr id='thead'><td><b>Check-In</b></td>";
echo '<td><b>Location</b></td>
						    <td><b>Item description</b></td>
							<td style="text-align:right; width: 10%;"><b>Price</b></td>
							    <td style="text-align:right; width: 10%;"><b>Discount</b></td>
								<td style="text-align:right; width: 10%;"><b>Qty</b></td>
								    <td style="text-align:right; width: 10%;"><b>Sub total</b></td>
									<td width=4%><b>Remove</b></td></tr>';


$select_Transaction_Items = mysqli_query($conn,
        "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID, ppc.Registration_ID
						from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
						    where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
							t.item_id = ppi.item_id and 
								Registration_ID = '$Registration_ID' and
								    Transaction_status = 'submitted' and Check_In_ID='$Check_In_ID'") or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    echo "<tr><td>" . $row['Check_In_Type'] . "</td>";
    echo "<td>" . $row['Patient_Direction'] . "</td>";
    echo "<td>" . $row['Product_Name'] . "</td>";
    echo "<td style='text-align:  right;'>" . number_format($row['Price']) . "</td>";
    echo "<td style='text-align: right;'>" . number_format($row['Discount']) . "</td>";
    echo "<td style='text-align: right;'>" . $row['Quantity'] . "</td>";
    echo "<td style='text-align: right;'>" . number_format(($row['Price'] - $row['Discount']) * $row['Quantity']) . "</td>";
    ?>
                                <td style='text-align: center;'> 
                                    <input type='button' style='color: red; font-size: 10px;' value='X'  onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Patient_Payment_Item_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                </td>

                                <?php
                                $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                            }echo "</tr>";
                            echo "<tr><td colspan=7 style='text-align: right;'> Total : " . number_format($total) . "</td></tr>";
                            ?></table>
    </fieldset>
</td>
</tr>

                            <?php
                            $total = 0;
                            $num_rows = 0;
                            $get_Total = mysqli_query($conn,
                                    "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity, ppc.Patient_Payment_Cache_ID
					from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
					    where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
						t.item_id = ppi.item_id and 
							Registration_ID = '$Registration_ID' and
							    ppc.Transaction_status = 'submitted'") or die(mysqli_error($conn));

                            $num_rows = mysqli_num_rows($get_Total);
                            if ($num_rows > 0) {
                                while ($row = mysqli_fetch_array($get_Total)) {
                                    $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
                                    $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                                }

                                //get number of items
                                $sql = mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_patient_payment_item_list_cache
									where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
                                $number_of_items = mysqli_num_rows($sql);
                            }
                            ?>

<tr>
<script>
    function add_Item() {
        var Item_Name = document.getElementById("Item_Name").value;
        var Discount = document.getElementById("Discount").value;
        var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
        var direction = document.getElementById("direction").value;
        var Consultant = document.getElementById("Consultant").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Clinic_ID = document.getElementById("Clinic_ID").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Patient_Payment_Cache_ID = <?php echo $Patient_Payment_Cache_ID; ?>;
        if((Clinic_ID==''|| Clinic_ID==null) && (direction=="Direct To Doctor Via Nurse Station"||direction=="Direct To Doctor")){
                                alert("Select clinic first")
                                exit;
                            }
        if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {

            if (window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }

            myObject2.onreadystatechange = function () {
                data = myObject2.responseText;

                if (myObject2.readyState == 4) {
                    //alert("One Item Added");
                    document.getElementById('Picked_Items_Fieldset').innerHTML = data;

                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Price").value = '';

                    //update_fieldset(Registration_ID);
                    update_total(Patient_Payment_Cache_ID);
                }
            }; //specify name of function that will handle server response........

            //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
            myObject2.open('GET', 'Add_Item_Patient_Billing_Prepared.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount + '&Patient_Payment_Cache_ID=' + Patient_Payment_Cache_ID+"&Clinic_ID="+Clinic_ID+"&Sponsor_ID="+Sponsor_ID, true);
            myObject2.send();

        } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {
            alertMessage();
        } else {
            if (Discount == '' || Discount == null) {
                document.getElementById("Discount").value = '0';
            }
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").focus();
                document.getElementById("Quantity").style = 'border-color: red';
            }
            if (Consultant == '' || Consultant == null) {
                document.getElementById("Consultant").focus();
                document.getElementById("Consultant").style = 'border-color: red';
            }
            if (direction == '' || direction == null) {
                document.getElementById("direction").focus();
                document.getElementById("direction").style = 'border-color: red';
            }
            if (Type_Of_Check_In == '' || Type_Of_Check_In == null) {
                document.getElementById("Type_Of_Check_In").focus();
                document.getElementById("Type_Of_Check_In").style = 'border-color: red';
            }
        }
    }
</script>
<script>
    function update_total(Patient_Payment_Cache_ID) {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data5 = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Total_Area').innerHTML = data5;
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'update_total_prepared.php?Patient_Payment_Cache_ID=' + Patient_Payment_Cache_ID, true);
        myObject.send();
    }
</script>
<script>
    function clearFocus(MyElement) {
        MyElement.style = 'border-color: white';
    }
</script>
<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
       
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
                    var Patient_Payment_Cache_ID = <?php echo $Patient_Payment_Cache_ID; ?>;
                    document.location = 'Patient_Billing_Prepared_Make_Payment.php?Patient_Payment_Cache_ID=' + Patient_Payment_Cache_ID+'&manual_offline=manual';
                }
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }
    
    
      function offline_transaction(amount_required,reg_id){
               
        var uri = '../epay/patientbillingpreparedOfflinePayment.php';
        
               var Patient_Payment_Cache_ID = <?php echo $Patient_Payment_Cache_ID; ?>;
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Patient_Payment_Cache_ID:Patient_Payment_Cache_ID},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
</script>
<script type='text/javascript'>
    function Make_Payment() {
         var reg_id = '<?php echo $Registration_ID; ?>';
         var amount_required=document.getElementById("total_txt").value;
        offline_transaction(amount_required,reg_id);
    }

    function Confirm_Make_Payment() {
       // var r = confirm("You are about to create Transaction.? \nClick OK to continue or CANCEL to stop process");
        //if (r == true) {
            Make_Payment();
        //}
    }
</script>

<script type='text/javascript'>
    function Confirm_Remove_Item(Item_Name, Patient_Payment_Item_List_ID, Registration_ID) {
        var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
        var Patient_Payment_Cache_ID = <?php echo $Patient_Payment_Cache_ID; ?>;
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                confirmObjt = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                confirmObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                confirmObjt.overrideMimeType('text/xml');
            }

            confirmObjt.onreadystatechange = function () {
                confirmData = confirmObjt.responseText;
                if (confirmObjt.readyState == 4) {
                    document.getElementById('Picked_Items_Fieldset').innerHTML = confirmData;
                    //update_fieldset(Registration_ID);
                    update_total(Patient_Payment_Cache_ID);
                }
            }; //specify name of function that will handle server response........

            confirmObjt.open('GET', 'Remove_Item_From_List_Revenue_Center.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Registration_ID=' + Registration_ID, true);
            confirmObjt.send();
        }
    }
</script>
<?php
if ($num_rows > 0) {
    echo "<td width=65% style='text-align: center;'>";
    if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                
                    
        ?>
        <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
    <?php
    }
    
                }
                        if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                    
    echo "<input type='button'  onclick='Confirm_Make_Payment()' class='art-button-green' value='MAKE PAYMENT'></td>
					<td width=35% style='text-align: center; font-weight: bold;' id='Total_Area' >";
                        }
    //display total items selected
    if ($number_of_items > 0) {
        if ($number_of_items == 1) {
            echo "1 Item selected ";
        } else {
            echo $number_of_items . " Items,  ";
        }
    }
   ?> <input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>
   <?php
    echo "TOTAL COST: " . number_format($total) . "</td>";
} else {
    echo "<td width=65% style='text-align: center;'>
						    <a href='Send_To_Cashier_Patient_Reception.php' class='art-button-green' style='visibility: hidden;'>
							SEND TO CASHIER
						    </a>
						</td>
					<td width=35% style='text-align: center; font-size: large;'></td>";
}
?>
</tr>
</table>

</td>
</tr> 
</table>
</center>
</fieldset>


<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> 
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->

<!-- pop up window -->
<div id="Display_Number_Of_Patients" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<!-- ePayment pop up windows -->
<div id="ePayment_Window" style="width:50%;" >
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

<script type="text/javascript">
    function Verify_ePayment_Bill() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }
        myObjectVerify.onreadystatechange = function () {
            data2912 = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = data2912;
                if (feedback == 'yes') {
                    Create_ePayment_Bill();
                } else {
                    alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Verify_ePayment_Bill.php?src=send_to_cash&P_Type=Credit_Patient&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Create_ePayment_Bill() {
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0') {
            alert("Process Fail! You can not prepare a bill with zero amount");
        } else {
            if (Payment_Mode != null && Payment_Mode != '') {
                if (Payment_Mode == 'Bank_Payment') {
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        document.location = 'Prepare_Bank_Payment_Transaction.php?src=send_to_cash&Section=departmental&Registration_ID=' + Registration_ID;
                    }
                } else if (Payment_Mode == 'Mobile_Payemnt') {
                    var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                }
            } else {
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script>
    function open_Dialog() {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Number_Of_Patients").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'patientqueue.php', true);
        myObjectGetDetails.send();
    }
</script>

<script>
    function Pay_Via_Mobile_Function() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php $Employee_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'ePayment_Patient_Details.php?src=send_to_cash&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID, true);
        myObjectGetDetails.send();
    }
</script>


<!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>-->

<script>
    $(document).ready(function () {
        $("#Display_Number_Of_Patients").dialog({autoOpen: false, width: '30%', height: 500, title: 'DOCTORS QUEUE', modal: true});
        $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});

//      $('.ui-dialog-titlebar-close').click(function(){
//	 Get_Transaction_List();
//      });

    });
</script>

<script>
    $(document).ready(function () {
        $("#List_OF_Doctors").dialog({autoOpen: false, width: '30%', height: 350, title: 'DOCTORS LIST', modal: true});
    });
</script>


<!-- end of pop up window -->


<?php
include("./includes/footer.php");
?>