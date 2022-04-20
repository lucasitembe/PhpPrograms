<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}

	//delete all previous trancactions 
	$delete = mysqli_query($conn,"delete from tbl_optical_items_list_cache where Registration_ID = '$Registration_ID' and consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));

	$select_Transaction_Items = mysqli_query($conn,"select Item_Cache_ID, Product_Name,alc.Price,alc.Clinic_ID,  Quantity, Registration_ID, Comment, Sub_Department_ID,
												Claim_Form_Number, Billing_Type, Sponsor_Name, alc.Sponsor_ID, t.Item_ID, Consultant_ID, Type_Of_Check_In
                                                from tbl_items t, tbl_departmental_items_list_cache alc
                                                where alc.Item_ID = t.Item_ID and
                                                alc.Employee_ID = '$Employee_ID' and
                                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$no_of_items = mysqli_num_rows($select_Transaction_Items);
	if($no_of_items > 0){
		//insert selected items into tbl_optical_items_list_cache
		while ($data = mysqli_fetch_array($select_Transaction_Items)) {
			$sql = "insert into tbl_optical_items_list_cache(
                        Claim_Form_Number, Billing_Type, Sponsor_Name,
                        Sponsor_ID, Item_ID, Price,Quantity,
                        Consultant_ID, Employee_ID, Registration_ID,Comment,Sub_Department_ID,Type_Of_Check_In,consultation_ID,Transaction_Date_Time,Clinic_ID)
                                                
                    values('".$data['Claim_Form_Number']."','".$data['Billing_Type']."','".$data['Sponsor_Name']."',
                        '".$data['Sponsor_ID']."','".$data['Item_ID']."','".$data['Price']."','".$data['Quantity']."',
                        '".$data['Consultant_ID']."','".$Employee_ID."','".$data['Registration_ID']."','".$data['Comment']."','".$data['Sub_Department_ID']."','".$data['Type_Of_Check_In']."',".$consultation_ID.",(select now()),'$Clinic_ID')";
			$insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                         
                
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                        $Type_Of_Check_In=$data['Type_Of_Check_In'];
                        $Item_ID=$data['Item_ID'];
                        $Price=$data['Price'];
                        $Quantity=$data['Quantity'];
                        $Consultant_ID=$data['Consultant_ID'];
                        $Comment=$data['Comment'];
                        $Sub_Department_ID=$data['Sub_Department_ID'];
                          
                        $Clinic_ID=$data['Clinic_ID'];
                        $Billing_Type=$data['Billing_Type'];
                        if($Billing_Type=="Outpatient Cash"){
                           $Transaction_Type="cash"; 
                        }else{
                            $Transaction_Type="credit"; 
                        }
                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Clinic_ID
                                                ) values(
                                                    '$Type_Of_Check_In','$Item_ID','$Price',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                    '$Comment','$Sub_Department_ID','$Transaction_Type',(select now()),'$Clinic_ID')") or die(mysqli_error($conn));
                
            
                     }
                    }   
                }
	}

	//delete data from cache table
	$delete = mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	header("Location: ./opticalpendingtransactions.php?OpticalPendingTransactions=OpticalPendingTransactionsThisPage");
?>

