<?php
header("Content-type: text/xml");


	include("./includes/connection.php");

	$sql =mysqli_query($conn,"SELECT * FROM tbl_patient_payments as pp
						join tbl_patient_registration as pr ON pr.Registration_ID = pp.Registration_ID
						JOIN tbl_Employee as e ON e.Employee_ID = pp.Employee_ID
						where pp.Patient_Payment_ID ='".filter_input(INPUT_GET, 'patientID')."'
						");



$dom = new DOMDocument();

$data =$dom->createElement('data');
$dom ->appendChild($data);

$disp =mysqli_fetch_assoc($sql);

		$sponsor = $dom->createElement('sponsor');
		$sponsorText =$dom ->createTextNode($disp['Sponsor_Name']);
		$sponsor ->appendChild($sponsorText);

		$Gender = $dom->createElement('Gender');
		$GenderText =$dom ->createTextNode($disp['Gender']);
		$Gender ->appendChild($GenderText);

		$Employee_Name = $dom->createElement('Employee_Name');
		$Employee_NameText =$dom ->createTextNode($disp['Employee_Name']);
		$Employee_Name ->appendChild($Employee_NameText);


		$Claim_Form_Number = $dom->createElement('Claim_Form_Number');
		$Claim_Form_NumberText =$dom ->createTextNode($disp['Claim_Form_Number']);
		$Claim_Form_Number ->appendChild($Claim_Form_NumberText);

		$Age = $dom->createElement('Age');
		$AgeText =$dom ->createTextNode($disp['Date_Of_Birth']);
		$Age ->appendChild($AgeText);

  		$Results = $dom->createElement('Results');
		$Results->appendChild($sponsor);
		$Results->appendChild($Gender);
		$Results->appendChild($Employee_Name);
		$Results->appendChild($Claim_Form_Number);
		$Results->appendChild($Age);




		$select_tests =mysqli_query($conn,"SELECT i.Item_ID as Item_ID,i.Product_Name as Product_Name FROM tbl_patient_payment_item_list as il
									join tbl_items as i ON i.Item_ID =il.Item_ID 
									where Patient_Payment_ID='".filter_input(INPUT_GET, 'patientID')."' and Check_In_Type ='Laboratory'");


   $html="<center><table class='hiv_table' style='width:99%'>";       
   $html .="<tr><th>S/N</th>
                <th>Test Name</th>
                            <th colspan='2'>Doctor's Notes</th><th>Specimen Time</th><th>Test Status</th>
                            <th>Overall Remarks</th><th>Attachment</th><th>Results</th></tr>";
    
    
    //$select_Transaction_Items = mysqli_query($conn,"select * from tbl_requizition_items where Requizition_ID='$requision_id'"); 
$i=1;    
    while($row =mysqli_fetch_assoc($select_tests)){
            if ($i%2==0){
            $color='#F8F8F8';
            }else{
            $color='white';
            }
       $html.="<tr bgcolor=".$color.">";
       $html.= "<td>".$i."</td>";
       $html.="<td><input name='' value='".$row['Product_Name']."' style='width:95%'></td>";
       $html.="<td  colspan='2'><input name='' value='' style='width:95%'></td>";
       $html.="<td style='width:100px'><input name='' value='' style='width:95%'></td>";        
       $html.="<td style='width:50px'>
                            <select  class='list_style'>
                                <option>&nbsp;</option>
                                <option>Done</option>
                                <option>Not Applicable</option>
                                <option>Pending</option>
                            </select>
        </td>";
       $html.="<td><input name='' value='' style='width:95%'></td>";
       $html.="<td style='width:50px'><a href='#'><a href='' onclick='di()'><button>Attachment</button></a></td>";
       $html.="<td style='width:50px'><a href='results_templates.php?Item_ID=".$row['Item_ID']."'><button>Results</button></a> </td>";
      $html.="</tr>";
       $i++;
    }
	

	$html .="</table></center>";


	$ItemInfo = $dom->createElement('ItemInfo');
	$ItemInfoText =$dom ->createTextNode($html);
	$ItemInfo ->appendChild($ItemInfoText);



	$Results->appendChild($ItemInfo);


$data->appendChild($Results);

$xmlString =$dom->saveXML();
echo $xmlString;

?>