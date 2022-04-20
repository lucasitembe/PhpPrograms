<?php
    @session_start();
    include("./includes/connection.php");
	include("./UUID.php");
    $temp = 1;
    $GrandTotal = 0;
    $total = 0;
    $sub_Total = 0;
    $patient_number = 1;
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Quality_Assurance'])){
			if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get all details from the URL
    //Branch
    if(isset($_GET['Bill_ID'])){
        $Bill_ID = $_GET['Bill_ID'];
    }else{
        $Bill_ID = '';
    }
	
	$bill_qr = "SELECT * FROM tbl_bills b, tbl_sponsor s WHERE b.Sponsor_ID = s.Sponsor_ID AND b.Bill_ID = '$Bill_ID'";
	$bill_result = mysqli_query($conn,$bill_qr) or die(mysqli_error($conn));
	while($bill_row = mysqli_fetch_assoc($bill_result)){
		$Bill_Date_And_Time = $bill_row['Bill_Date_And_Time'];
		$Date_From = $bill_row['Start_Date'];
		$Date_To = $bill_row['End_Date'];
		$Guarantor_Name = $bill_row['Guarantor_Name'];
	}
    
    //Insurance
    if(isset($_GET['Insurance'])){
        $Insurance = $_GET['Insurance'];
    }else{
        $Insurance = '';
    }
	
    //Payment_Type
    if(isset($_GET['Payment_Type'])){
        $Payment_Type = $_GET['Payment_Type'];
    }else{
        $Payment_Type = '';
    }
	
    //Patient_Type
    if(isset($_GET['Patient_Type'])){
        $Patient_Type = $_GET['Patient_Type'];
    }else{
        $Patient_Type = '';
    }
	
    $folio_data = "";
    $folio_items = "";
    $folio_disease = "";
    $ClaimNo = "KINONDONI\\".$Guarantor_Name."\\".date('M-Y',date(strtotime($Bill_Date_And_Time)));
    $fileName = "KINONDONI ".$Guarantor_Name." ".date('M-Y',date(strtotime($Bill_Date_And_Time)));
    $FacilityCode = "03995";

    //$claim_data = "";
    $claim_data2 = "";
    $claim_data = "<ClaimRegistration>\n";
    $claim_data.= "<ClaimNo>".$ClaimNo."</ClaimNo>\n";
    $claim_data.= "<FacilityCode>".$FacilityCode."</FacilityCode>\n";
    $claim_data.= "<ClaimMonth>".date('m',date(strtotime($Bill_Date_And_Time)))."</ClaimMonth>\n";
    $claim_data.= "<ClaimYear>".date('Y',date(strtotime($Bill_Date_And_Time)))."</ClaimYear>\n";
    $claim_data.= "<TreatmentDateFrom>".$Date_From."T00:00:00+03:00"."</TreatmentDateFrom>\n";
    $claim_data.= "<TreatmentDateTo>".$Date_To."T00:00:00+03:00"."</TreatmentDateTo>\n";
    $claim_data.= "<FoliosSubmitted>0</FoliosSubmitted>\n";
    $claim_data.= "<AmountClaimed>";
    $claim_data2.="</AmountClaimed>\n";
    $claim_data2.= "<Status>Open</Status>\n";
    $claim_data2.= "<CreatedBy>".$_SESSION['userinfo']['Employee_Name']."</CreatedBy>\n";
    $claim_data2.= "<DateCreated>".str_replace(' ','T',date('Y-m-d h:i:s')).'.000+03:00'."</DateCreated>\n";
    $claim_data2.= "<LastModifiedBy>".$_SESSION['userinfo']['Employee_Name']."</LastModifiedBy>\n";
    $claim_data2.= "<LastModified>".str_replace(' ','T',date('Y-m-d h:i:s')).'.000+03:00'."</LastModified>\n";
    $claim_data2.= "</ClaimRegistration>\n";
    

    $Sponsor_Name = $Guarantor_Name;
    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,e.Employee_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender,pp.Payment_Date_And_Time,pp.Claim_Form_Number,pp.Patient_Payment_ID, pp.Registration_ID, pp.Sponsor_Name, pp.Folio_Number from
				    tbl_patient_registration pr, tbl_patient_payments pp,tbl_employee e
					where pr.registration_id = pp.registration_id and
					    e.employee_id = pp.employee_id and Bill_ID = '$Bill_ID'
						    group by pp.sponsor_Name,pp.Folio_Number order by pp.sponsor_Name,pp.Folio_Number") or die(mysqli_error($conn));
							
    //display all items
        while($row2 = mysqli_fetch_array($select_Patient_Details)){
		$Patient_Payment_ID = $row2['Patient_Payment_ID'];
	    $Today = Date("Y-m-d");
	    $Date_Of_Birth = $row2['Date_Of_Birth'];
	    $date1 = new DateTime($Today);
	    $date2 = new DateTime($Date_Of_Birth);
	    $diff = $date1 -> diff($date2);
	    $age = $diff->y;
			$FolioID = gen_uuid();
            $Folio_Number = $row2['Folio_Number'];
            $folio_data.="<Folios>\n";
			$folio_data.="<FolioID>".$FolioID."</FolioID>\n";
            $folio_data.="<ClaimNo>".$ClaimNo."</ClaimNo>\n";
            $folio_data.="<FolioNo>".$Folio_Number."</FolioNo>\n";
            $folio_data.="<SerialNo>".$row2['Claim_Form_Number']."</SerialNo>\n";
            $folio_data.="<CardNo>".$row2['Member_Number']."</CardNo>\n";
            $folio_data.="<FirstName>".explode(' ',$row2['Patient_Name'])[0]."</FirstName>\n";
            
	    if(sizeof(explode(' ',$row2['Patient_Name']))>= 3){
		$folio_data.="<LastName>".explode(' ',$row2['Patient_Name'])[sizeof(explode(' ',$row2['Patient_Name'])) - 1]."</LastName>\n";
	    }else{
		$folio_data.="<LastName>".explode(' ',$row2['Patient_Name'])[1]."</LastName>\n";
	    }
            
	    $folio_data.="<Gender>".$row2['Gender']."</Gender>\n";
            $folio_data.="<Age>".$age."</Age>\n";
            $folio_data.="<ServiceTypeID>1</ServiceTypeID>\n";
            $folio_data.="<AttendanceDate>".str_replace(' ','T',$row2['Payment_Date_And_Time']).'+03:00'."</AttendanceDate>\n";
            $folio_data.="<PatientTypeCode>OUT</PatientTypeCode>\n";
            $folio_data.="<CreatedBy>".$row2['Employee_Name']."</CreatedBy>\n";
            $folio_data.="<DateCreated>".str_replace(' ','T',$row2['Payment_Date_And_Time']).'.00+03:00'."</DateCreated>\n";
            $folio_data.="<LastModifiedBy>".$row2['Employee_Name']."</LastModifiedBy>\n";
            $folio_data.="<LastModified>".str_replace(' ','T',$row2['Payment_Date_And_Time']).'.000+03:00'."</LastModified>\n";
            $folio_data.="</Folios>\n";
            
            $results = mysqli_query($conn,"
                    select
                    ic.Item_Category_Name,e.Employee_Name,t.Product_Code,t.NHIFItem_Type, pp.Patient_Payment_ID, t.Product_Name,
		    pp.Claim_Form_Number, pp.Receipt_Date, ppl.Price, ppl.Quantity, ppl.Discount,ppl.Transaction_Date_And_Time
                    from
			tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
			    tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				pr.registration_id = pp.registration_id and
				e.employee_id = pp.employee_id and
				ic.item_category_id = ts.item_category_id and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id and
				pp.Patient_Payment_ID='$Patient_Payment_ID'  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
	    while($row3 = mysqli_fetch_array($results)){
                $FolioItemID = gen_uuid();
		$Patient_Payment_ID = $row3['Patient_Payment_ID'];
                $folio_items.="<FolioItems>\n";
                $folio_items.="<FolioItemID>".$FolioItemID."</FolioItemID>\n";
                $folio_items.="<FolioID>".$FolioID."</FolioID>\n";
                $folio_items.="<ItemTypeID>".$row3['NHIFItem_Type']."</ItemTypeID>\n";
                $folio_items.="<ItemCode>".$row3['Product_Code']."</ItemCode>\n";
                $folio_items.="<ItemQuantity>".$row3['Quantity']."</ItemQuantity>\n";
                $folio_items.="<UnitPrice>".$row3['Price']."</UnitPrice>\n";
                $folio_items.="<AmountClaimed>".($row3['Price'] - $row3['Discount'])*$row3['Quantity'].".00</AmountClaimed>\n";
                $folio_items.="<CreatedBy>".$row3['Employee_Name']."</CreatedBy>\n";
                $folio_items.="<DateCreated>".str_replace(' ','T',$row3['Transaction_Date_And_Time']).'.000+03:00'."</DateCreated>\n";
                $folio_items.="<LastModifiedBy>".$row3['Employee_Name']."</LastModifiedBy>\n";
                $folio_items.="<LastModified>".str_replace(' ','T',$row3['Transaction_Date_And_Time']).'.000+03:00'."</LastModified>\n";
                $folio_items.="<AmountAccepted>".(($row3['Price'] - $row3['Discount'])*$row3['Quantity'])."</AmountAccepted>\n";
                $folio_items.="</FolioItems>\n";
             
                $diagnosis_query = "SELECT d.nhif_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name,
			dc.Disease_Consultation_Date_And_Time
                        FROM tbl_consultation c,tbl_disease_consultation dc, tbl_disease d
                        WHERE c.Consultation_ID=dc.Consultation_ID AND d.Disease_ID = dc.Disease_ID
                        AND dc.diagnosis_type = 'diagnosis'
                        AND c.Patient_Payment_Item_List_ID IN (SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl WHERE ppl.Patient_Payment_ID=$Patient_Payment_ID)";
                 
                $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));
                
                $diagnosis = "";
		
                while($diagnosis_row = mysqli_fetch_assoc($diagnosis_result)){
                     $DiseaseCode =$diagnosis_row['nhif_code'];
					 $FolioDiseaseID = gen_uuid();
					 $DateCreated = $diagnosis_row['Disease_Consultation_Date_And_Time'];
                     $Consultant_Name = $diagnosis_row['Consultant_Name'];
                        $folio_disease.="<FolioDiseases>\n";
                        $folio_disease.="<FolioDiseaseID>".$FolioDiseaseID."</FolioDiseaseID>\n";
                        $folio_disease.="<DiseaseCode>".$DiseaseCode."</DiseaseCode>\n";
                        $folio_disease.="<FolioID>".$FolioID."</FolioID>\n";
                        $folio_disease.="<CreatedBy>".$Consultant_Name."</CreatedBy>\n";
                        $folio_disease.="<DateCreated>".str_replace(' ','T',$DateCreated).'.000+03:00'."</DateCreated>\n";
                        $folio_disease.="<LastModifiedBy>".$Consultant_Name."</LastModifiedBy>\n";
                        $folio_disease.="<LastModified>".str_replace(' ','T',$DateCreated).'.000+03:00'."</LastModified>\n";
                        $folio_disease.="</FolioDiseases>\n";
                }
            }
			$GrandTotal = $GrandTotal + $sub_Total;
			$sub_Total = 0;
			$total = 0;
        }
    
    //header('CONTENT-TYPE: text/xml');
    $xml = '<?xml version="1.0" standalone="yes" ?>';
    $xml .="\n";
    $xml .= '<ClaimsDataSet xmlns="http://tempuri.org/ClaimsDataSet.xsd">'."\n";
    $xml.= $claim_data.$GrandTotal.$claim_data2;
    $xml.= $folio_data;
    $xml.= $folio_items;
    $xml.= $folio_disease;
    $xml.= "<IssuableServiceTypes>\n";
	$xml.= "<ServiceTypeID>1</ServiceTypeID>\n";
	$xml.= "<ServiceName>Treatment(General Treatment)</ServiceName>\n";
	$xml.= "<RequiresReferringFacility>false</RequiresReferringFacility>\n";
	$xml.= "<RequiresRefNo>false</RequiresRefNo>\n";
	$xml.= "</IssuableServiceTypes>\n";
	$xml.= "<IssuableServiceTypes>\n";
	$xml.= "<ServiceTypeID>2</ServiceTypeID>\n";
	$xml.= "<ServiceName>Pharmaceutial Services</ServiceName>\n";
	$xml.= "<RequiresReferringFacility>true</RequiresReferringFacility>\n";
	$xml.= "<RequiresRefNo>false</RequiresRefNo>\n";
	$xml.= "</IssuableServiceTypes>\n";
	$xml.= "<IssuableServiceTypes>\n";
	$xml.= "<ServiceTypeID>3</ServiceTypeID>\n";
	$xml.= "<ServiceName>Investigation Only</ServiceName>\n";
	$xml.= "<RequiresReferringFacility>true</RequiresReferringFacility>\n";
	$xml.= "<RequiresRefNo>false</RequiresRefNo>\n";
	$xml.= "</IssuableServiceTypes>\n";
	$xml.= "<IssuableServiceTypes>\n";
	$xml.= "<ServiceTypeID>4</ServiceTypeID>\n";
	$xml.= "<ServiceName>Special Services(Example Dialisys)</ServiceName>\n";
	$xml.= "<RequiresReferringFacility>false</RequiresReferringFacility>\n";
	$xml.= "<RequiresRefNo>true</RequiresRefNo>\n";
	$xml.= "</IssuableServiceTypes>\n";
	$xml.='</ClaimsDataSet>';
    
    //$handle = fopen('./generated/'.$fileName.'.fls','w');
    //fwrite($handle,$xml);
    //fclose($handle);
    ob_start();
    header("Content-Disposition: attachment; filename=\"".basename('generated/'.$fileName.'.fls')."\"");
    header("Content-Type: application/force-download");echo $xml;
    //header("Content-Length: ".filesize('./generated/'.$fileName.'.fls'));
    header("Connection: close");
?>