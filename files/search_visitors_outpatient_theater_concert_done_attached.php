<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name = '';
    }
	
	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

	
   echo "<center><table width =100%>";
    echo '<tr id="thead"><td style="width:2%;"><b>SN</b></td><td><b>PATIENT NAME</b></td>
            <td style="text-align: center;"><b>PATIENT NUMBER</b></td>
                    <td style="text-align: center;"><b>AGE</b></td>
                        <td style="text-align: center;"><b>GENDER</b></td>
                        <td style="text-align: center;"><b>PHONE NUMBER</b></td>
                            <td><b>CONSENT DESCRIPTION</b></td>
                                <td><b>ATTACHED EMPLOYEE</b></td>
                                <td><b>DATE ATTACHED CONCERT FORM</b></td>
                                </tr>';

    // $select_Filtered_Patients = mysqli_query($conn, "SELECT pr.Patient_Name, pr.Old_Registration_Number, ta.Registration_ID, pr.Date_Of_Birth, pr.Gender,  ta.Check_In_Type, ta.Attachment_Date, ta.Attachment_Url, ta.Description, em.Employee_Name ,pr.Phone_Number, sp.Guarantor_Name FROM tbl_attachment ta, tbl_employee em, tbl_patient_registration pr, tbl_sponsor sp where pr.sponsor_id = sp.sponsor_id and pr.Registration_ID = ta.Registration_ID AND ta.Check_In_Type = 'Consent' GROUP BY ta.Attachment_ID ORDER BY ta.Attachment_ID DESC LIMIT 50") or die(mysqli_error($conn));



    $query = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, ta.Registration_ID, ta.Check_In_Type, ta.Attachment_Date, ta.Attachment_Url, ta.Description, em.Employee_Name from tbl_patient_registration pr, tbl_attachment ta, tbl_employee em WHERE pr.Registration_ID = ta.Registration_ID AND ta.Check_In_Type = 'Consent'  AND em.Employee_ID = ta.Employee_ID GROUP BY ta.Attachment_ID ORDER BY ta.Attachment_ID DESC LIMIT 50");
      if($query){
                $attachment_url="";
                $count = 1;
                while ($attach = mysqli_fetch_array($query)) {
                    $Attachment_Date = $attach['Attachment_Date'];
                    $Check_In_Type = $attach['Check_In_Type'];
                    $Description = $attach['Description'];
                    $Employee_Name = $attach['Employee_Name'];
                    $Attachment_Url = $attach['Attachment_Url'];
                    $Patient_Name = $attach['Patient_Name'];
                    $Date_Of_Birth = $attach['Date_Of_Birth'];
                    $Gender = $attach['Gender'];
                    $Registration_ID = $attach['Registration_ID'];	
                    $Phone_Number = $attach['Phone_Number'];	

                	$date1 = new DateTime($Today);
                	$date2 = new DateTime($Date_Of_Birth);
                	$diff = $date1 -> diff($date2);
                	$age = $diff->y." Years, ";
                	$age .= $diff->m." Months, ";
                	$age .= $diff->d." Days";
                    $Attachment_Url = $attach['Attachment_Url'];

                    		
	// 	$date1 = new DateTime($Today);
	// 	$date2 = new DateTime($row['Date_Of_Birth']);
	// 	$diff = $date1 -> diff($date2);
	// 	$age = $diff->y." Years, ";
	// 	$age .= $diff->m." Months, ";
	// 	$age .= $diff->d." Days";

                    if ($attach['Attachment_Url'] != '') {
                            echo "<tr>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$count."</a></td>
                                      <td><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Patient_Name."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Registration_ID."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$age."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Gender."</a></td>
                                      <td><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Phone_Number."</a></td>
                                      <td><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Description."</a></td>
                                      <td><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Employee_Name."</a></td>
                                      <td><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' style='text-decoration: none;' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Attachment_Date."</a></td>
                                  </tr>";                            
                                        
                            $attachment_url="<object data='patient_attachments/$Attachment_Url' width='100%' height='1600'>
                                  alt :  $image 
                                </object>";
                      $count++;                    
                    }
                }


        }	   
    // while($row = mysqli_fetch_array($select_Filtered_Patients)){
    //     $Attachment_Url = $row['Attachment_Url'];
	
	// //AGE FUNCTION
	//    // if($age == 0){
		
	// 	$date1 = new DateTime($Today);
	// 	$date2 = new DateTime($row['Date_Of_Birth']);
	// 	$diff = $date1 -> diff($date2);
	// 	$age = $diff->y." Years, ";
	// 	$age .= $diff->m." Months, ";
	// 	$age .= $diff->d." Days";
	
    //     if ($attach['Attachment_Url'] != '') {
    //         echo "<tr><td width ='2%' id='thead'>".$temp."<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".($row['Old_Registration_Number']==""?$row['Registration_ID']:$row['Old_Registration_Number'])."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$age."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$row['Description']."</a></td>";
    //         echo "<td><a href='patient_attachments/" . $Attachment_Url . "&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".$row['Attachment_Date']."</a></td>";
            
    //     $attachment_url="<object data='patient_attachments/$Attachment_Url' width='100%' height='1600'>
    //     alt :  $image 
    //   </object>";

    //     	$temp++;
    //     }
    // }   echo "</tr>";

?></table></center>


