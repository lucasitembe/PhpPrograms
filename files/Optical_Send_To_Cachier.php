<?php
	session_start();
	include("./includes/connection.php");
	$branch_id = $_SESSION['userinfo']['Branch_ID'];
	$Receipt_Date = Date('Y-m-d');
	$Payment_Date_And_Time = Date('Y-m-d H:i:s');
	$Transaction_status = 'pending';
	$Transaction_type = 'indirect cash';

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
	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = 0;
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}

	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}
	
	if(isset($_GET['Spectacle_ID'])){
		$Spectacle_ID = $_GET['Spectacle_ID'];
	}else{
		$Spectacle_ID = '';
	}
	if(isset($_GET['finance_department_id'])){
		$finance_department_id = $_GET['finance_department_id'];
	}else{
		$finance_department_id = '';
	}
	$Billing_Type=$_GET['Billing_Type'];
	

	if(strtolower($Section) == 'outside'){
		$insert1 = mysqli_query($conn,"insert into tbl_consultation(employee_ID, Registration_ID, Consultation_Date_And_Time) values('$Employee_ID','$Registration_ID',(select now()))") or die(mysqli_error($conn));
		if($insert1){
			//select consultation_ID
			$slct = mysqli_query($conn,"select consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($slct);
			if($nm > 0){
				while ($data = mysqli_fetch_array($slct)) {
					$consultation_ID = $data['consultation_ID'];
				}
			}else{
				$consultation_ID = 0;
			}
			mysqli_query($conn,"insert into tbl_spectacles(Registration_ID,consultation_ID,Date_Time,Spectacle_Status) values('$Registration_ID','$consultation_ID',(select now()),'done')") or die(mysqli_error($conn));
		}
	}
	$select_Transaction_Items = mysqli_query($conn,"SELECT Item_Cache_ID,alc.Price,alc.Comment,alc.Clinic_ID, Product_Name, Price, Quantity, Registration_ID, Comment, Sub_Department_ID,
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
			$insert = mysqli_query($conn,$sql);
		
                        
                  ///new added lines      
                        
				//   $select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' order by Payment_Cache_ID and Billing_Type='Outpatient Cash' AND Payment_Date_And_Time=CURRENT_DATE()  desc limit 1") or die(mysqli_error($conn));
				$select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and Billing_Type='Outpatient Cash' AND date(Payment_Date_And_Time)=CURRENT_DATE()  order by Payment_Cache_ID desc LIMIT 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
				}//CREATE NEW PAYMENT_CACHE FOR OUTPATIENT CASH
				else{
					$insert_payment_cache = mysqli_query($conn,"INSERT INTO tbl_payment_cache( Registration_ID, Employee_ID, consultation_id, Payment_Date_And_Time, Folio_Number, Sponsor_Name,Sponsor_ID, Billing_Type, Receipt_Date, Transaction_status, Transaction_type, Order_Type, branch_id) VALUES ('$Registration_ID','$Employee_ID','$consultation_ID','$Payment_Date_And_Time','$Folio_Number','".$data['Sponsor_Name']."','".$data['Sponsor_ID']."','Outpatient Cash','$Receipt_Date','$Transaction_status','$Transaction_type','post operative','$branch_id')") or die(mysqli_error($conn));
					if($insert_payment_cache){
						echo "payment cache created";
					}else{
						echo "Payment cache Not Created";
					}

					$select_cache = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                    Employee_ID = '$Employee_ID' order by Payment_Cache_ID and Billing_Type='Outpatient Cash' desc limit 1") or die(mysqli_error($conn));
					$no1 = mysqli_num_rows($select_cache);
					if($no1 > 0){
                    while($row1 = mysqli_fetch_array($select_cache)){
                        $Payment_Cache_ID = $row1['Payment_Cache_ID'];
                    }
				}
				} 
				
				
				
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
                        
                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(finance_department_id,
                                                Check_In_Type, Item_ID,Price,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Clinic_ID
                                                ) values('$finance_department_id','$Type_Of_Check_In','$Item_ID','$Price',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
													'$Comment','$Sub_Department_ID','$Transaction_Type',(select now()),'$Clinic_ID')") or die(mysqli_error($conn));
													                        
		}
	}

	//delete data from cache table
	$delete = mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$update = mysqli_query($conn,"update tbl_spectacles set Spectacle_Status = 'done' where Spectacle_ID = '$Spectacle_ID'") or die(mysqli_error($conn));
	header("Location: ./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage");
?>

